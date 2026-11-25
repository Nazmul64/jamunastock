<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\AgentPoint;
use App\Models\Area;
use App\Models\AgentStock;
use App\Models\AgentStockTransaction;
use App\Models\AgentDispatch;
use App\Models\MemberDelivery;
use App\Models\MemberDeliveryItem;
use App\Models\MonthlyBazaarOrder;
use App\Models\CentralWarehouseStock;

class AdminAgentManagementController extends Controller
{
    /**
     * Section 33.2 & 33.8: Agent Management List & Comparison Table
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $areaId = $request->input('area_id');
        $status = $request->input('status');

        $query = User::where(function ($q) {
            $q->where('role', 'agent')
              ->orWhere('user_type', 'agent');
        })->with(['agentPoint.areaModel', 'area']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('agent_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($areaId) {
            $query->where('area_id', $areaId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $agents = $query->orderBy('id', 'DESC')->paginate(15);

        // Aggregate statistics for each agent
        $agentsData = [];
        $totalAllMarketAmount = 0;
        $totalAllDeliveries = 0;
        $totalAllMembers = 0;

        foreach ($agents as $agent) {
            $point = $agent->agentPoint ?: AgentPoint::where('agent_user_id', $agent->id)->first();
            $pointId = $point ? $point->id : null;

            $membersCount = $pointId ? User::where('agent_point_id', $pointId)->count() : 0;
            $deliveriesQuery = $pointId ? MemberDelivery::where('agent_point_id', $pointId) : MemberDelivery::where('agent_user_id', $agent->id);
            $deliveriesCount = $deliveriesQuery->count();
            $marketAmount = floatval($deliveriesQuery->sum('total_amount'));

            // Remaining stock
            $stocks = AgentStock::where('agent_id', $agent->id)->get();
            $stockRemaining = max(0, $stocks->sum('allocated_quantity') - $stocks->sum('sold_quantity'));

            $agentsData[] = [
                'agent' => $agent,
                'point' => $point,
                'members_count' => $membersCount,
                'deliveries_count' => $deliveriesCount,
                'market_amount' => $marketAmount,
                'stock_remaining' => $stockRemaining,
            ];

            $totalAllMarketAmount += $marketAmount;
            $totalAllDeliveries += $deliveriesCount;
            $totalAllMembers += $membersCount;
        }

        $areas = Area::where('status', 'active')->orderBy('name')->get();

        return view('admin.agents.index', compact(
            'agents',
            'agentsData',
            'areas',
            'totalAllMarketAmount',
            'totalAllDeliveries',
            'totalAllMembers'
        ));
    }

    /**
     * Show Create New Agent Form
     * References W3CMS / Lara Dashboard / AGAdmin styles
     */
    public function create()
    {
        $agentPoints = AgentPoint::where('status', 'active')->orderBy('name')->get();
        $areas = Area::where('status', 'active')->orderBy('name')->get();

        // Auto-generate next Agent ID
        $count = User::where('role', 'agent')->count() + 1;
        $autoAgentId = 'AGT-' . date('Ym') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        return view('admin.agents.create', compact('agentPoints', 'areas', 'autoAgentId'));
    }

    /**
     * Store Newly Created Agent
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'agent_id' => 'required|string|max:50|unique:users,agent_id',
            'agent_point_id' => 'required|exists:agent_points,id',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'status' => 'required|in:active,inactive',
        ]);

        $fullName = trim($request->first_name . ' ' . $request->last_name);
        $agentPoint = AgentPoint::findOrFail($request->agent_point_id);

        // Handle Profile Picture Upload
        $avatarName = null;
        if ($request->hasFile('avatar')) {
            $uploadDir = public_path('upload/agents');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $avatarName = time() . '_' . Str::random(8) . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move($uploadDir, $avatarName);
        }

        DB::beginTransaction();
        try {
            $username = 'agent_' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $request->first_name)) . rand(10, 99);
            while (User::where('username', $username)->exists()) {
                $username = 'agent_' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $request->first_name)) . rand(100, 999);
            }

            $agent = User::create([
                'name' => $fullName,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $username,
                'agent_id' => $request->agent_id,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'agent',
                'user_type' => 'agent',
                'agent_type' => 'agent',
                'avatar' => $avatarName ?: 'default-user.png',
                'agent_point_id' => $agentPoint->id,
                'area_id' => $request->area_id ?: $agentPoint->area_id,
                'district' => $request->district ?: ($agentPoint->areaModel ? $agentPoint->areaModel->district : null),
                'upazila' => $request->upazila ?: ($agentPoint->areaModel ? $agentPoint->areaModel->upazila : null),
                'branch' => $request->branch ?: 'Main Branch',
                'present_address' => $request->present_address,
                'permanent_address' => $request->permanent_address ?: $request->present_address,
                'joining_date' => $request->joining_date ?: date('Y-m-d'),
                'status' => $request->status,
                'is_active' => $request->status === 'active' ? 1 : 0,
                'remarks' => $request->remarks,
            ]);

            // Assign this agent user as the manager/in-charge of the selected Agent Point
            $agentPoint->update([
                'agent_user_id' => $agent->id,
                'agent_email' => $agent->email,
                'contact_number' => $agent->phone,
            ]);

            DB::commit();

            return redirect()->route('admin.agents.index')->with('success', "✓ নতুন এজেন্ট '{$fullName}' (ID: {$request->agent_id}) সফলভাবে তৈরি ও পয়েন্টে অ্যাসাইন করা হয়েছে!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'এজেন্ট তৈরিতে ত্রুটি: ' . $e->getMessage());
        }
    }

    /**
     * Show Edit Agent Form
     */
    public function edit($id)
    {
        $agent = User::findOrFail($id);
        $agentPoints = AgentPoint::where('status', 'active')->orderBy('name')->get();
        $areas = Area::where('status', 'active')->orderBy('name')->get();

        return view('admin.agents.edit', compact('agent', 'agentPoints', 'areas'));
    }

    /**
     * Update Agent Details
     */
    public function update(Request $request, $id)
    {
        $agent = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'agent_id' => 'required|string|max:50|unique:users,agent_id,' . $agent->id,
            'agent_point_id' => 'required|exists:agent_points,id',
            'email' => 'required|email|max:255|unique:users,email,' . $agent->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $agent->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'status' => 'required|in:active,inactive',
        ]);

        $fullName = trim($request->first_name . ' ' . $request->last_name);
        $agentPoint = AgentPoint::findOrFail($request->agent_point_id);

        // Profile Picture Upload
        $avatarName = $agent->avatar;
        if ($request->hasFile('avatar')) {
            $uploadDir = public_path('upload/agents');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if ($agent->avatar && file_exists($uploadDir . '/' . $agent->avatar)) {
                @unlink($uploadDir . '/' . $agent->avatar);
            }
            $avatarName = time() . '_' . Str::random(8) . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move($uploadDir, $avatarName);
        }

        $data = [
            'name' => $fullName,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'agent_id' => $request->agent_id,
            'email' => $request->email,
            'phone' => $request->phone,
            'avatar' => $avatarName ?: ($agent->avatar ?: 'default-user.png'),
            'agent_point_id' => $agentPoint->id,
            'area_id' => $request->area_id ?: $agentPoint->area_id,
            'district' => $request->district ?: ($agentPoint->areaModel ? $agentPoint->areaModel->district : null),
            'upazila' => $request->upazila ?: ($agentPoint->areaModel ? $agentPoint->areaModel->upazila : null),
            'branch' => $request->branch ?: 'Main Branch',
            'present_address' => $request->present_address,
            'permanent_address' => $request->permanent_address ?: $request->present_address,
            'joining_date' => $request->joining_date ?: $agent->joining_date,
            'status' => $request->status,
            'is_active' => $request->status === 'active' ? 1 : 0,
            'remarks' => $request->remarks,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $agent->update($data);

        // Update Point Manager
        $agentPoint->update([
            'agent_user_id' => $agent->id,
            'agent_email' => $agent->email,
            'contact_number' => $agent->phone,
        ]);

        return redirect()->route('admin.agents.index')->with('success', "✓ এজেন্ট '{$fullName}' এর তথ্য সফলভাবে আপডেট করা হয়েছে!");
    }

    /**
     * Toggle Agent Active/Inactive Status
     */
    public function toggleStatus($id)
    {
        $agent = User::findOrFail($id);
        $newStatus = $agent->status === 'active' ? 'inactive' : 'active';
        $agent->update([
            'status' => $newStatus,
            'is_active' => $newStatus === 'active' ? 1 : 0,
        ]);

        return back()->with('success', "✓ এজেন্টের স্ট্যাটাস '{$newStatus}' করা হয়েছে।");
    }

    /**
     * Section 33.9 - 33.12: Agent Individual Details & Comprehensive Financial Market Report
     */
    public function show(Request $request, $id)
    {
        $agent = User::with(['agentPoint.areaModel', 'area'])->findOrFail($id);
        $point = $agent->agentPoint ?: AgentPoint::where('agent_user_id', $agent->id)->first();
        $pointId = $point ? $point->id : null;

        // Date Filter Logic (Section 13)
        $period = $request->input('period', 'all');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $now = now();
        $startDate = null;
        $endDate = null;

        if ($period === 'today') {
            $startDate = $now->format('Y-m-d');
            $endDate = $startDate;
        } elseif ($period === 'yesterday') {
            $startDate = $now->copy()->subDay()->format('Y-m-d');
            $endDate = $startDate;
        } elseif ($period === 'this_week') {
            $startDate = $now->copy()->startOfWeek()->format('Y-m-d');
            $endDate = $now->copy()->endOfWeek()->format('Y-m-d');
        } elseif ($period === 'last_week') {
            $startDate = $now->copy()->subWeek()->startOfWeek()->format('Y-m-d');
            $endDate = $now->copy()->subWeek()->endOfWeek()->format('Y-m-d');
        } elseif ($period === 'this_month') {
            $startDate = $now->format('Y-m-01');
            $endDate = $now->format('Y-m-t');
        } elseif ($period === 'last_month') {
            $startDate = $now->copy()->subMonth()->format('Y-m-01');
            $endDate = $now->copy()->subMonth()->format('Y-m-t');
        } elseif ($period === 'this_year') {
            $startDate = $now->format('Y-01-01');
            $endDate = $now->format('Y-12-31');
        } elseif ($period === 'custom' && $fromDate && $toDate) {
            $startDate = $fromDate;
            $endDate = $toDate;
        }

        // Base Delivery Query for this Agent Point
        $deliveriesBase = MemberDelivery::with(['items', 'cardHolder'])
            ->where(function ($q) use ($pointId, $agent) {
                if ($pointId) {
                    $q->where('agent_point_id', $pointId);
                } else {
                    $q->where('agent_user_id', $agent->id);
                }
            });

        // Apply Date Filter if selected
        $filteredDeliveriesQuery = clone $deliveriesBase;
        if ($startDate && $endDate) {
            $filteredDeliveriesQuery->whereBetween('delivery_date', [$startDate, $endDate]);
        }

        // Search by Transaction ID or Card Number
        $searchTx = $request->input('search_tx');
        if ($searchTx) {
            $filteredDeliveriesQuery->where(function ($q) use ($searchTx) {
                $q->where('delivery_no', 'like', "%{$searchTx}%")
                  ->orWhere('card_number', 'like', "%{$searchTx}%")
                  ->orWhere('member_name', 'like', "%{$searchTx}%");
            });
        }

        $deliveries = $filteredDeliveriesQuery->orderBy('id', 'DESC')->paginate(15);

        // Financial & Volume Calculations
        $filteredDeliveryIds = (clone $filteredDeliveriesQuery)->pluck('id');

        $totalMembersServed = (clone $filteredDeliveriesQuery)->distinct('card_holder_id')->count('card_holder_id');
        $totalDeliveryCount = (clone $filteredDeliveriesQuery)->count();
        $totalMarketAmount = floatval((clone $filteredDeliveriesQuery)->sum('total_amount'));
        $totalProductQty = floatval(MemberDeliveryItem::whereIn('delivery_id', $filteredDeliveryIds)->sum('quantity'));

        // Lifetime Timelines (Today, Weekly, Monthly, Yearly)
        $todayStr = date('Y-m-d');
        $weekStart = $now->copy()->startOfWeek()->format('Y-m-d');
        $monthStart = date('Y-m-01');
        $yearStart = date('Y-01-01');

        $todayMarketAmount = floatval((clone $deliveriesBase)->where('delivery_date', $todayStr)->sum('total_amount'));
        $weeklyMarketAmount = floatval((clone $deliveriesBase)->where('delivery_date', '>=', $weekStart)->sum('total_amount'));
        $monthlyMarketAmount = floatval((clone $deliveriesBase)->where('delivery_date', '>=', $monthStart)->sum('total_amount'));
        $yearlyMarketAmount = floatval((clone $deliveriesBase)->where('delivery_date', '>=', $yearStart)->sum('total_amount'));

        // Product-wise Market Breakdown (Section 10)
        $productBreakdown = MemberDeliveryItem::whereIn('delivery_id', $filteredDeliveryIds)
            ->select(
                'product_name',
                'unit',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('AVG(unit_price) as avg_unit_price'),
                DB::raw('SUM(total_amount) as total_amount')
            )
            ->groupBy('product_name', 'unit')
            ->get();

        // Local Agent Stocks Ledger
        $agentStocks = AgentStock::where('agent_id', $agent->id)->get();
        $remainingStock = max(0, $agentStocks->sum('allocated_quantity') - $agentStocks->sum('sold_quantity'));

        // Assigned Members & Pending Collection
        $assignedMembersCount = $pointId ? User::where('agent_point_id', $pointId)->count() : 0;
        $pendingCollectionCount = max(0, $assignedMembersCount - $totalMembersServed);

        return view('admin.agents.show', compact(
            'agent',
            'point',
            'deliveries',
            'period',
            'startDate',
            'endDate',
            'totalMembersServed',
            'totalDeliveryCount',
            'totalMarketAmount',
            'totalProductQty',
            'todayMarketAmount',
            'weeklyMarketAmount',
            'monthlyMarketAmount',
            'yearlyMarketAmount',
            'productBreakdown',
            'agentStocks',
            'remainingStock',
            'assignedMembersCount',
            'pendingCollectionCount'
        ));
    }
}
