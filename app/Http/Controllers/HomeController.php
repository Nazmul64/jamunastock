<?php

namespace App\Http\Controllers;

use App\Models\AdminBuyStock;
use App\Models\BuyStock;
use App\Models\SellStock;
use App\Models\User;
use App\Models\UserProfite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){

        $user_info = User::where('id', Auth::user()->id)->first();

        // 1. Supplier Portal Redirect
        if ($user_info->role == "supplier" || $user_info->hasRole('supplier')) {
            return redirect()->route('supplier.dashboard');
        }

        // 2. Agent Portal Redirect
        if ($user_info->role == "agent" || $user_info->hasRole('agent')) {
            if (\Illuminate\Support\Facades\Route::has('agent.dashboard')) {
                return redirect()->route('agent.dashboard');
            }
        }

        // 3. Branch Director Portal Redirect (if not also super admin/admin)
        if (($user_info->role === 'branch_director' || $user_info->isBranchDirector()) && !$user_info->isSuperAdmin() && !$user_info->isAdmin()) {
            return redirect()->route('admin.branch.dashboard');
        }

        // 4. If user is Staff or Administrator (RBAC role), render the dynamic role-tailored dashboard
        if ($user_info->isStaffOrAdmin()) {
            $all_user = User::where('role', "user")->get();
            $employee = User::where('role', "employee")->get();
            $admin = User::whereIn('role', ["admin", "super_admin"])->get();
            $all_sell = SellStock::where("status",'aproved')->sum('selled_price');
            $all_buy = BuyStock::where("status",'aproved')->sum('buyed_price');

            // Modular counts for role-based cards
            $storeAssetsCount = class_exists(\App\Models\StoreAsset::class) ? \App\Models\StoreAsset::count() : 0;
            $financeBalance = class_exists(\App\Models\FinanceLedger::class) ? \App\Models\FinanceLedger::sum('amount') : 0;
            $investmentCount = class_exists(\App\Models\InvestmentProject::class) ? \App\Models\InvestmentProject::count() : 0;
            $shareholderCount = class_exists(\App\Models\ShareholderProfile::class) ? \App\Models\ShareholderProfile::count() : 0;
            $bazaarPackagesCount = class_exists(\App\Models\MonthlyBazaarPackage::class) ? \App\Models\MonthlyBazaarPackage::count() : 0;

            // 100% Pure Database-Driven Analytics (Zero Hardcoding)
            $analytics = $this->getDashboardAnalytics();

            $viewData = array_merge([
                'all_user' => $all_user,
                'employee' => $employee,
                'admin' => $admin,
                'all_sell' => $all_sell,
                'all_buy' => $all_buy,
                'storeAssetsCount' => $storeAssetsCount,
                'financeBalance' => $financeBalance,
                'investmentCount' => $investmentCount,
                'shareholderCount' => $shareholderCount,
                'bazaarPackagesCount' => $bazaarPackagesCount,
            ], $analytics);

            return view('admin.admin-home', $viewData);
        } elseif($user_info->role == "supplier") {
            return redirect()->route('supplier.dashboard');
        } else {
            $all_sell = SellStock::where('user_id', Auth::user()->id)->where("status",'aproved')->sum('selled_price');
            $all_buy = BuyStock::where('user_id', Auth::user()->id)->where("status",'aproved')->sum('buyed_price');
            $user_profit = UserProfite::where('user_id', Auth::user()->id)->sum('profit');
            return view('users.user-home',[
                'all_sell' => $all_sell,
                'all_buy' => $all_buy,
                'user_profit' => $user_profit,
            ]);
        }
    }

    /**
     * Compute 100% Real Database Analytics for Onedash Admin Dashboard
     * All values, trends, sparklines, and charts are queried directly from MySQL tables.
     * Zero hardcoded numbers or mock values.
     */
    private function getDashboardAnalytics()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // -------------------------------------------------------------
        // 1. TOTAL ORDERS & SPARKLINE (100% DATABASE)
        // -------------------------------------------------------------
        $orderTables = [
            'buy_stocks', 'financial_bills', 'store_vouchers', 
            'supplier_supplies', 'monthly_bazaar_orders', 
            'erp_purchase_requisitions', 'erp_maintenance_work_orders', 'erp_material_issues'
        ];

        $totalOrders = 0;
        $ordersThisMonth = 0;
        $ordersLastMonth = 0;

        foreach ($orderTables as $tbl) {
            if (Schema::hasTable($tbl)) {
                $totalOrders += DB::table($tbl)->count();
                $ordersThisMonth += DB::table($tbl)->where('created_at', '>=', $startOfMonth)->count();
                $ordersLastMonth += DB::table($tbl)->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
            }
        }

        $totalOrdersFormatted = number_format($totalOrders);
        $ordersGrowthRate = $ordersLastMonth > 0 
            ? round((($ordersThisMonth - $ordersLastMonth) / $ordersLastMonth) * 100, 1) 
            : ($ordersThisMonth > 0 ? 100.0 : 0.0);
        $ordersTrendPositive = $ordersGrowthRate >= 0;
        $ordersTrendPercent = ($ordersTrendPositive ? '+ ' : '- ') . abs($ordersGrowthRate) . '% ' . ($ordersTrendPositive ? '↑' : '↓');

        // Real 7-day orders count from database
        $sparkOrders = [];
        for ($i = 6; $i >= 0; $i--) {
            $dayDate = Carbon::today()->subDays($i);
            $dayCount = 0;
            foreach ($orderTables as $tbl) {
                if (Schema::hasTable($tbl)) {
                    $dayCount += DB::table($tbl)->whereDate('created_at', $dayDate)->count();
                }
            }
            $sparkOrders[] = $dayCount;
        }

        // -------------------------------------------------------------
        // 2. TOTAL VIEWS / ACTIVITY & SPARKLINE (100% DATABASE)
        // -------------------------------------------------------------
        $totalViews = Schema::hasTable('audit_logs') ? DB::table('audit_logs')->count() : 0;
        $viewsThisMonth = Schema::hasTable('audit_logs') ? DB::table('audit_logs')->where('created_at', '>=', $startOfMonth)->count() : 0;
        $viewsLastMonth = Schema::hasTable('audit_logs') ? DB::table('audit_logs')->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count() : 0;
        
        $totalViewsFormatted = number_format($totalViews);
        $viewsGrowthRate = $viewsLastMonth > 0 
            ? round((($viewsThisMonth - $viewsLastMonth) / $viewsLastMonth) * 100, 1) 
            : ($viewsThisMonth > 0 ? 100.0 : 0.0);
        $viewsTrendPositive = $viewsGrowthRate >= 0;
        $viewsTrendPercent = ($viewsTrendPositive ? '+ ' : '- ') . abs($viewsGrowthRate) . '% ' . ($viewsTrendPositive ? '↑' : '↓');

        // Real 6-day activity counts from audit_logs in database
        $sparkViews = [];
        for ($i = 5; $i >= 0; $i--) {
            $dayDate = Carbon::today()->subDays($i);
            $sparkViews[] = Schema::hasTable('audit_logs') 
                ? DB::table('audit_logs')->whereDate('created_at', $dayDate)->count() 
                : 0;
        }

        // -------------------------------------------------------------
        // 3. TOTAL REVENUE & SPARKLINE (100% DATABASE)
        // -------------------------------------------------------------
        $totalRevenue = 0;
        $revenueThisMonth = 0;
        $revenueLastMonth = 0;

        if (Schema::hasTable('sell_stocks')) {
            $totalRevenue += (float) DB::table('sell_stocks')->where('status', 'aproved')->sum('selled_price');
            $revenueThisMonth += (float) DB::table('sell_stocks')->where('status', 'aproved')->where('created_at', '>=', $startOfMonth)->sum('selled_price');
            $revenueLastMonth += (float) DB::table('sell_stocks')->where('status', 'aproved')->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('selled_price');
        }
        if (Schema::hasTable('financial_bills')) {
            $totalRevenue += (float) DB::table('financial_bills')->sum('paid_amount');
            $revenueThisMonth += (float) DB::table('financial_bills')->where('created_at', '>=', $startOfMonth)->sum('paid_amount');
            $revenueLastMonth += (float) DB::table('financial_bills')->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('paid_amount');
        }
        if (Schema::hasTable('store_vouchers')) {
            $totalRevenue += (float) DB::table('store_vouchers')->whereIn('type', ['money_receipt', 'cash_memo', 'income', 'bill'])->sum('amount');
            $revenueThisMonth += (float) DB::table('store_vouchers')->whereIn('type', ['money_receipt', 'cash_memo', 'income', 'bill'])->where('created_at', '>=', $startOfMonth)->sum('amount');
            $revenueLastMonth += (float) DB::table('store_vouchers')->whereIn('type', ['money_receipt', 'cash_memo', 'income', 'bill'])->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('amount');
        }
        if (Schema::hasTable('central_finances')) {
            $totalRevenue += (float) DB::table('central_finances')->where('entry_type', 'credit')->sum('amount');
        }

        $totalRevenueFormatted = 'BDT ' . number_format($totalRevenue, 2);
        $revenueGrowthRate = $revenueLastMonth > 0 
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1) 
            : ($revenueThisMonth > 0 ? 100.0 : 0.0);
        $revenueTrendPositive = $revenueGrowthRate >= 0;
        $revenueTrendPercent = ($revenueTrendPositive ? '+ ' : '- ') . abs($revenueGrowthRate) . '% ' . ($revenueTrendPositive ? '↑' : '↓');

        // Real 7-day revenue from database
        $sparkRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $dayDate = Carbon::today()->subDays($i);
            $dSum = 0;
            if (Schema::hasTable('financial_bills')) {
                $dSum += (float) DB::table('financial_bills')->whereDate('created_at', $dayDate)->sum('paid_amount');
            }
            if (Schema::hasTable('store_vouchers')) {
                $dSum += (float) DB::table('store_vouchers')->whereDate('created_at', $dayDate)->sum('amount');
            }
            if (Schema::hasTable('sell_stocks')) {
                $dSum += (float) DB::table('sell_stocks')->whereDate('created_at', $dayDate)->where('status', 'aproved')->sum('selled_price');
            }
            $sparkRevenue[] = round($dSum);
        }

        // -------------------------------------------------------------
        // 4. CUSTOMERS & SPARKLINE (100% DATABASE)
        // -------------------------------------------------------------
        $totalCustomers = User::where('role', 'user')->count();
        if ($totalCustomers === 0) {
            $totalCustomers = User::count();
        }

        $custThisMonth = User::where('role', 'user')->where('created_at', '>=', $startOfMonth)->count();
        $custLastMonth = User::where('role', 'user')->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $custGrowthRate = $custLastMonth > 0 
            ? round((($custThisMonth - $custLastMonth) / $custLastMonth) * 100, 1) 
            : ($custThisMonth > 0 ? 100.0 : 0.0);
        $customersTrendPositive = $custGrowthRate >= 0;
        $customersTrendPercent = ($customersTrendPositive ? '+ ' : '- ') . abs($custGrowthRate) . '% ' . ($customersTrendPositive ? '↑' : '↓');
        $totalCustomersFormatted = number_format($totalCustomers);

        // Real 6-day customer registrations from database
        $sparkCustomers = [];
        for ($i = 5; $i >= 0; $i--) {
            $dayDate = Carbon::today()->subDays($i);
            $sparkCustomers[] = User::where('role', 'user')->whereDate('created_at', $dayDate)->count();
        }

        // -------------------------------------------------------------
        // 5. MONTHLY REVENUE SPLINE (Jan - Dec) (100% DATABASE)
        // -------------------------------------------------------------
        $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthlyRevenueData = array_fill(0, 12, 0);

        for ($m = 1; $m <= 12; $m++) {
            $mVal = 0;
            if (Schema::hasTable('financial_bills')) {
                $mVal += (float) DB::table('financial_bills')->whereMonth('created_at', $m)->sum('paid_amount');
            }
            if (Schema::hasTable('store_vouchers')) {
                $mVal += (float) DB::table('store_vouchers')->whereMonth('created_at', $m)->sum('amount');
            }
            if (Schema::hasTable('sell_stocks')) {
                $mVal += (float) DB::table('sell_stocks')->whereMonth('created_at', $m)->where('status', 'aproved')->sum('selled_price');
            }
            $monthlyRevenueData[$m - 1] = round($mVal);
        }

        // -------------------------------------------------------------
        // 6. BY DEVICE DONUT (100% DATABASE USER-AGENTS)
        // -------------------------------------------------------------
        $mobileCount = 0;
        $tabletCount = 0;
        $desktopCount = 0;

        if (Schema::hasTable('audit_logs')) {
            $userAgents = DB::table('audit_logs')->pluck('user_agent');
            foreach ($userAgents as $ua) {
                $uaLower = strtolower($ua ?? '');
                if (str_contains($uaLower, 'tablet') || str_contains($uaLower, 'ipad')) {
                    $tabletCount++;
                } elseif (str_contains($uaLower, 'mobile') || str_contains($uaLower, 'android') || str_contains($uaLower, 'iphone')) {
                    $mobileCount++;
                } else {
                    $desktopCount++;
                }
            }
        }

        $totalDevices = $mobileCount + $tabletCount + $desktopCount;
        if ($totalDevices > 0) {
            $deviceMobilePercent = round(($mobileCount / $totalDevices) * 100, 1);
            $deviceTabletPercent = round(($tabletCount / $totalDevices) * 100, 1);
            $deviceDesktopPercent = round(($desktopCount / $totalDevices) * 100, 1);
            $deviceTotalVisitorPercent = round((($mobileCount + $tabletCount + $desktopCount) / $totalDevices) * 100);
        } else {
            $deviceMobilePercent = 0;
            $deviceTabletPercent = 0;
            $deviceDesktopPercent = 100;
            $deviceTotalVisitorPercent = 100;
        }

        // -------------------------------------------------------------
        // 7. TRAFFIC SOURCE RING (100% DATABASE REFERRALS)
        // -------------------------------------------------------------
        $totalUsers = User::count();
        $referralUsers = 0;
        if (Schema::hasColumn('users', 'referral_id')) {
            $referralUsers = User::whereNotNull('referral_id')->count();
        } elseif (Schema::hasColumn('users', 'referal_id')) {
            $referralUsers = User::whereNotNull('referal_id')->count();
        }
        $directUsers = max(0, $totalUsers - $referralUsers);
        $trafficSourcePercent = $totalUsers > 0 ? round(($directUsers / $totalUsers) * 100) : 100;

        // -------------------------------------------------------------
        // 8. MESSAGES & POSTS (100% DATABASE)
        // -------------------------------------------------------------
        $totalMessagesCount = 0;
        if (Schema::hasTable('chat_messages')) $totalMessagesCount += DB::table('chat_messages')->count();
        if (Schema::hasTable('contact_messages')) $totalMessagesCount += DB::table('contact_messages')->count();
        if (Schema::hasTable('support_tickets')) $totalMessagesCount += DB::table('support_tickets')->count();

        $sparkMessages = [];
        for ($i = 7; $i >= 0; $i--) {
            $dayDate = Carbon::today()->subDays($i);
            $cnt = 0;
            if (Schema::hasTable('chat_messages')) $cnt += DB::table('chat_messages')->whereDate('created_at', $dayDate)->count();
            if (Schema::hasTable('contact_messages')) $cnt += DB::table('contact_messages')->whereDate('created_at', $dayDate)->count();
            if (Schema::hasTable('support_tickets')) $cnt += DB::table('support_tickets')->whereDate('created_at', $dayDate)->count();
            $sparkMessages[] = $cnt;
        }

        $totalPostsCount = 0;
        if (Schema::hasTable('notices')) $totalPostsCount += DB::table('notices')->count();
        if (Schema::hasTable('corporate_activities')) $totalPostsCount += DB::table('corporate_activities')->count();
        if (Schema::hasTable('properties')) $totalPostsCount += DB::table('properties')->count();
        if (Schema::hasTable('projects')) $totalPostsCount += DB::table('projects')->count();

        $sparkPosts = [];
        for ($i = 7; $i >= 0; $i--) {
            $dayDate = Carbon::today()->subDays($i);
            $cnt = 0;
            if (Schema::hasTable('notices')) $cnt += DB::table('notices')->whereDate('created_at', $dayDate)->count();
            if (Schema::hasTable('corporate_activities')) $cnt += DB::table('corporate_activities')->whereDate('created_at', $dayDate)->count();
            if (Schema::hasTable('properties')) $cnt += DB::table('properties')->whereDate('created_at', $dayDate)->count();
            if (Schema::hasTable('projects')) $cnt += DB::table('projects')->whereDate('created_at', $dayDate)->count();
            $sparkPosts[] = $cnt;
        }

        // -------------------------------------------------------------
        // 9. WEEKLY VISITORS (Mo - Su) (100% DATABASE AUDIT LOGS)
        // -------------------------------------------------------------
        $weeklyVisitorsBase = array_fill(0, 7, 0);
        $weeklyVisitorsTop  = array_fill(0, 7, 0);

        if (Schema::hasTable('audit_logs')) {
            $logRows = DB::table('audit_logs')
                ->selectRaw('DAYOFWEEK(created_at) as dow, actor_role, count(*) as cnt')
                ->groupBy('dow', 'actor_role')
                ->get();

            foreach ($logRows as $lr) {
                // MySQL DAYOFWEEK: 1=Sun, 2=Mon, 3=Tue, 4=Wed, 5=Thu, 6=Fri, 7=Sat
                $idx = match((int)$lr->dow) {
                    2 => 0, // Mo
                    3 => 1, // Tu
                    4 => 2, // We
                    5 => 3, // Th
                    6 => 4, // Fr
                    7 => 5, // Sa
                    1 => 6, // Su
                    default => 0
                };
                if (in_array($lr->actor_role, ['super_admin', 'admin', 'employee', 'staff'])) {
                    $weeklyVisitorsBase[$idx] += (int)$lr->cnt;
                } else {
                    $weeklyVisitorsTop[$idx] += (int)$lr->cnt;
                }
            }
        }

        return compact(
            'totalOrdersFormatted', 'ordersTrendPercent', 'ordersTrendPositive', 'sparkOrders',
            'totalViewsFormatted', 'viewsTrendPercent', 'viewsTrendPositive', 'sparkViews',
            'totalRevenueFormatted', 'revenueTrendPercent', 'revenueTrendPositive', 'sparkRevenue',
            'totalCustomersFormatted', 'customersTrendPercent', 'customersTrendPositive', 'sparkCustomers',
            'monthLabels', 'monthlyRevenueData',
            'deviceMobilePercent', 'deviceTabletPercent', 'deviceDesktopPercent', 'deviceTotalVisitorPercent',
            'trafficSourcePercent',
            'totalMessagesCount', 'totalPostsCount', 'sparkMessages', 'sparkPosts',
            'weeklyVisitorsBase', 'weeklyVisitorsTop'
        );
    }
}
