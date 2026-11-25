<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AgentPoint;
use App\Models\AgentStock;
use App\Models\AgentStockTransaction;
use App\Models\AgentDispatch;
use App\Models\AgentDispatchItem;
use App\Models\MemberDelivery;
use App\Models\MemberDeliveryItem;
use App\Models\MonthlyBazaarOrder;
use App\Models\MonthlyMarketCycle;
use App\Models\CentralWarehouseStock;
use App\Models\User;

class AgentPortalController extends Controller
{
    /**
     * Get Current Authenticated Agent's Assigned Agent Point
     * Strictly enforces RBAC & Data Isolation.
     */
    protected function getAgentPoint()
    {
        $user = Auth::user();

        // 1. Super Admin fallback for testing/previewing
        if ($user->id == 1 || $user->role === 'super_admin' || $user->role === 'admin') {
            $pointId = request('agent_point_id') ?: $user->agent_point_id;
            if ($pointId) {
                return AgentPoint::find($pointId);
            }
            return AgentPoint::first();
        }

        // 2. Strict Agent Point Binding
        $pointId = $user->getAssignedAgentPointId();
        if (!$pointId) {
            abort(403, 'আপনার অ্যাকাউন্টের সাথে কোনো অনুমোদিত এজেন্ট পয়েন্ট সংযুক্ত নেই।');
        }

        return AgentPoint::findOrFail($pointId);
    }

    /**
     * Helper to get standard unit price for a product
     */
    protected function getProductUnitPrice($productName)
    {
        $stock = CentralWarehouseStock::where('product_name', $productName)->first();
        if ($stock && $stock->distribution_unit_price > 0) {
            return floatval($stock->distribution_unit_price);
        }

        // Standard Default Agricultural Market Rates (৳ per KG / Liter)
        $lower = mb_strtolower($productName);
        if (str_contains($lower, 'মিনিকেট') || str_contains($lower, 'miniket')) return 60.00;
        if (str_contains($lower, 'নাজিরশাইল') || str_contains($lower, 'nazirshail')) return 75.00;
        if (str_contains($lower, 'চাল') || str_contains($lower, 'rice')) return 60.00;
        if (str_contains($lower, 'ডাল') || str_contains($lower, 'lentil')) return 120.00;
        if (str_contains($lower, 'তেল') || str_contains($lower, 'oil')) return 170.00;
        if (str_contains($lower, 'চিনি') || str_contains($lower, 'sugar')) return 130.00;
        if (str_contains($lower, 'লবণ') || str_contains($lower, 'salt')) return 40.00;
        if (str_contains($lower, 'আটা') || str_contains($lower, 'flour')) return 55.00;

        return 60.00;
    }

    /**
     * Agent Dashboard Overview
     * Section 33.7 & 33.13: Daily, Weekly, Monthly & Yearly Market Summary + Date Filter
     */
    public function dashboard(Request $request)
    {
        $agentPoint = $this->getAgentPoint();
        $pointId = $agentPoint->id;
        $agentUserId = $agentPoint->agent_user_id ?: Auth::id();

        // 1. Date Filter Logic (Today, Yesterday, This Week, Last Week, This Month, Last Month, This Year, Custom)
        $period = $request->input('period', 'today');
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
        } else {
            $startDate = $now->format('Y-m-d');
            $endDate = $startDate;
        }

        // Active Monthly Cycle
        $currentCycle = MonthlyMarketCycle::where('is_active', true)->latest('id')->first();
        $currentMonth = date('Y-m');

        // 2. Base Queries scoped to this Agent Point
        $deliveriesBase = MemberDelivery::where('agent_point_id', $pointId);

        // Filtered Queries for Selected Date Range
        $filteredDeliveries = (clone $deliveriesBase)->whereBetween('delivery_date', [$startDate, $endDate]);
        $filteredDeliveryIds = (clone $filteredDeliveries)->pluck('id');

        $totalMembersServedFiltered = (clone $filteredDeliveries)->distinct('card_holder_id')->count('card_holder_id');
        $totalTransactionsFiltered = (clone $filteredDeliveries)->count();
        $totalMarketAmountFiltered = floatval((clone $filteredDeliveries)->sum('total_amount'));
        $totalQtyFiltered = floatval(MemberDeliveryItem::whereIn('delivery_id', $filteredDeliveryIds)->sum('quantity'));

        // Product-wise Distribution for Filtered Period
        $productDistributionFiltered = MemberDeliveryItem::whereIn('delivery_id', $filteredDeliveryIds)
            ->select(
                'product_name',
                'unit',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('AVG(unit_price) as avg_unit_price'),
                DB::raw('SUM(total_amount) as total_amount')
            )
            ->groupBy('product_name', 'unit')
            ->get();

        // 3. Lifetime Timelines (Today, Weekly, Monthly, Yearly)
        $todayStr = date('Y-m-d');
        $weekStart = $now->copy()->startOfWeek()->format('Y-m-d');
        $monthStart = date('Y-m-01');
        $yearStart = date('Y-01-01');

        $todayMarketAmount = floatval((clone $deliveriesBase)->where('delivery_date', $todayStr)->sum('total_amount'));
        $weeklyMarketAmount = floatval((clone $deliveriesBase)->where('delivery_date', '>=', $weekStart)->sum('total_amount'));
        $monthlyMarketAmount = floatval((clone $deliveriesBase)->where('delivery_date', '>=', $monthStart)->sum('total_amount'));
        $yearlyMarketAmount = floatval((clone $deliveriesBase)->where('delivery_date', '>=', $yearStart)->sum('total_amount'));

        $todayDeliveriesCount = (clone $deliveriesBase)->where('delivery_date', $todayStr)->count();
        $monthlyDeliveriesCount = (clone $deliveriesBase)->where('delivery_date', '>=', $monthStart)->count();

        // 4. Local Stock & Member Counts
        $totalAssignedMembers = User::where('agent_point_id', $pointId)->count();
        $pendingCollectionCount = max(0, $totalAssignedMembers - $totalMembersServedFiltered);

        $agentStocks = AgentStock::where('agent_id', $agentUserId)->get();
        $totalCurrentStockQty = max(0, $agentStocks->sum('allocated_quantity') - $agentStocks->sum('sold_quantity'));

        // Recent 10 Deliveries
        $recentDeliveries = MemberDelivery::with('items')
            ->where('agent_point_id', $pointId)
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get();

        // Incoming Dispatches Pending Confirmation
        $pendingDispatches = AgentDispatch::with('items')
            ->where('agent_point_id', $pointId)
            ->whereIn('status', ['dispatched', 'in_transit'])
            ->orderBy('id', 'DESC')
            ->get();

        // Incoming Supplier Supplies Assigned by Admin (SRD Section 6.1)
        $incomingSupplierSupplies = \App\Models\SupplierSupply::with(['supplier.supplierProfile', 'assignedBranch'])
            ->where(function ($q) use ($agentUserId, $pointId) {
                $q->where('assigned_agent_id', $agentUserId)
                  ->orWhere('assigned_agent_point_id', $pointId);
            })
            ->whereIn('status', ['assigned', 'in_transit', 'partially_received'])
            ->orderByDesc('id')
            ->get();

        // Weekly Distribution Trend (Last 7 Days) for Chart
        $chartLabels = [];
        $chartAmounts = [];
        $chartQtys = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i)->format('Y-m-d');
            $dayLabel = $now->copy()->subDays($i)->format('D');
            $dayDelivs = MemberDelivery::where('agent_point_id', $pointId)->where('delivery_date', $day)->get();
            $chartLabels[] = $dayLabel;
            $chartAmounts[] = floatval($dayDelivs->sum('total_amount'));
            $chartQtys[] = floatval(MemberDeliveryItem::whereIn('delivery_id', $dayDelivs->pluck('id'))->sum('quantity'));
        }

        return view('agent.dashboard', compact(
            'agentPoint',
            'currentCycle',
            'period',
            'startDate',
            'endDate',
            'totalMembersServedFiltered',
            'totalTransactionsFiltered',
            'totalMarketAmountFiltered',
            'totalQtyFiltered',
            'productDistributionFiltered',
            'todayMarketAmount',
            'weeklyMarketAmount',
            'monthlyMarketAmount',
            'yearlyMarketAmount',
            'todayDeliveriesCount',
            'monthlyDeliveriesCount',
            'totalAssignedMembers',
            'pendingCollectionCount',
            'totalCurrentStockQty',
            'recentDeliveries',
            'pendingDispatches',
            'incomingSupplierSupplies',
            'agentStocks',
            'chartLabels',
            'chartAmounts',
            'chartQtys'
        ));
    }

    /**
     * Member Verification & Search System
     * Section 33.4 & 33.6: Instant Verification + Product Price & Financial Forecast
     */
    public function verifyMember(Request $request)
    {
        $searchQuery = trim($request->input('search_query') ?? $request->input('card_number') ?? '');
        if (empty($searchQuery)) {
            return response()->json([
                'success' => false,
                'message' => 'অনুগ্রহ করে সদস্যের কার্ড নম্বর বা মেম্বার আইডি লিখুন।'
            ], 422);
        }

        $agentPoint = $this->getAgentPoint();
        $currentMonth = date('Y-m');

        // Find Card Holder by card_number, customer_id, username, or phone
        $member = User::where(function ($q) use ($searchQuery) {
            $q->where('card_number', $searchQuery)
              ->orWhere('customer_id', $searchQuery)
              ->orWhere('username', $searchQuery)
              ->orWhere('phone', $searchQuery);
        })->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'code' => 'NOT_FOUND',
                'message' => '❌ এই কার্ড নম্বর বা মেম্বার আইডির কোনো সদস্য খুঁজে পাওয়া যায়নি।'
            ], 404);
        }

        // Check Active Status
        if ($member->status === 'inactive' || $member->status === 'suspended') {
            return response()->json([
                'success' => false,
                'code' => 'INACTIVE_MEMBER',
                'message' => '⚠️ সদস্যের অ্যাকাউন্ট বর্তমানে স্থগিত বা নিষ্ক্রিয় রয়েছে।'
            ], 403);
        }

        // Strict Agent Point Verification (Section 33.4: Security Rule)
        $memberPointId = $member->agent_point_id;
        if ($memberPointId && $memberPointId != $agentPoint->id) {
            $otherPoint = AgentPoint::find($memberPointId);
            $otherPointName = $otherPoint ? $otherPoint->name : 'অন্য একটি পয়েন্ট';

            return response()->json([
                'success' => false,
                'code' => 'WRONG_AGENT_POINT',
                'member_name' => $member->name,
                'card_number' => $member->card_number ?: $member->customer_id,
                'assigned_point' => $otherPointName,
                'message' => "⛔ ডেলিভারি ব্লক! এই সদস্য '{$otherPointName}'-এর নিবন্ধিত সদস্য। আপনার পয়েন্ট থেকে পণ্য বিতরণ সম্পূর্ণভাবে নিষিদ্ধ।"
            ], 403);
        }

        // Fetch Approved Monthly Request Orders for this Member
        $orders = MonthlyBazaarOrder::where('user_id', $member->id)
            ->where(function ($q) use ($agentPoint) {
                $q->where('agent_point_id', $agentPoint->id)
                  ->orWhereNull('agent_point_id')
                  ->orWhere('agent_point', 'like', "%{$agentPoint->name}%")
                  ->orWhere('agent_point', 'like', "%{$agentPoint->area}%");
            })
            ->whereIn('status', ['approved', 'allocated', 'ready', 'partially_delivered'])
            ->get();

        if ($orders->isEmpty()) {
            // Check if already delivered
            $alreadyDelivered = MonthlyBazaarOrder::where('user_id', $member->id)
                ->where('status', 'delivered')
                ->where('created_at', 'like', "$currentMonth%")
                ->first();

            if ($alreadyDelivered) {
                return response()->json([
                    'success' => false,
                    'code' => 'ALREADY_DELIVERED',
                    'member' => [
                        'name' => $member->name,
                        'card_number' => $member->card_number ?: $member->customer_id,
                        'phone' => $member->phone,
                        'area' => $member->areaModel->name ?? $member->agent_area ?? $agentPoint->area,
                    ],
                    'message' => "✓ এই সদস্য চলতি মাসের ({$currentMonth}) বরাদ্দকৃত বাজার ইতিমধ্যে সম্পূর্ণ গ্রহণ করেছেন।"
                ]);
            }

            return response()->json([
                'success' => false,
                'code' => 'NO_APPROVED_REQUEST',
                'member' => [
                    'name' => $member->name,
                    'card_number' => $member->card_number ?: $member->customer_id,
                    'phone' => $member->phone,
                    'area' => $member->areaModel->name ?? $member->agent_area ?? $agentPoint->area,
                ],
                'message' => '⚠️ সদস্যের এই মাসের কোনো অনুমোদিত বাজার চাহিদা (Approved Market Request) পাওয়া যায়নি।'
            ]);
        }

        // Prepare Itemized List with Available Quantities & Stock Check + Financial Calculation
        $agentUserId = $agentPoint->agent_user_id ?: Auth::id();
        $itemsData = [];
        $totalEstimatedMarketAmount = 0;

        foreach ($orders as $order) {
            $approvedQty = floatval($order->allocated_quantity ?: $order->quantity);
            $prevDelivered = floatval($order->delivered_quantity ?: 0);
            $remainingQty = max(0, $approvedQty - $prevDelivered);

            // Unit Price Calculation
            $unitPrice = $this->getProductUnitPrice($order->package_title);
            $itemEstimatedAmount = $remainingQty * $unitPrice;
            $totalEstimatedMarketAmount += $itemEstimatedAmount;

            // Check Local Agent Stock for this product
            $localStock = AgentStock::where('agent_id', $agentUserId)
                ->where(function ($q) use ($order) {
                    $q->where('stock_name', $order->package_title)
                      ->orWhere('stock_id', $order->item_id);
                })->first();

            $availStock = $localStock ? max(0, floatval($localStock->allocated_quantity - $localStock->sold_quantity)) : 0;

            $itemsData[] = [
                'order_id' => $order->id,
                'product_name' => $order->package_title,
                'unit' => 'KG',
                'unit_price' => $unitPrice,
                'approved_quantity' => $approvedQty,
                'previously_delivered' => $prevDelivered,
                'remaining_quantity' => $remainingQty,
                'estimated_amount' => $itemEstimatedAmount,
                'available_stock' => $availStock,
                'can_deliver' => ($remainingQty > 0 && $availStock > 0),
            ];
        }

        return response()->json([
            'success' => true,
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'card_number' => $member->card_number ?: $member->customer_id ?: 'EK-' . $member->id,
                'phone' => $member->phone,
                'avatar' => $member->avatarUrl(),
                'area' => $member->areaModel->name ?? $member->agent_area ?? $agentPoint->area,
                'agent_point' => $agentPoint->name,
                'membership_status' => ucfirst($member->status ?: 'Active'),
            ],
            'items' => $itemsData,
            'total_estimated_amount' => $totalEstimatedMarketAmount,
            'message' => '✓ সদস্য যাচাইকরণ সফল! অনুমোদিত পণ্যের তালিকা ও টাকার হিসাব প্রস্তুত।'
        ]);
    }

    /**
     * Store Product Delivery Entry
     * Section 33.5 & 33.6: Confirms Delivery, Permanent Unit Price & Amount Storage, Generates Receipt
     */
    public function storeDelivery(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users,id',
            'card_number' => 'required|string',
            'deliveries' => 'required|array',
            'deliveries.*.order_id' => 'required|exists:monthly_bazaar_orders,id',
            'deliveries.*.deliver_qty' => 'required|numeric|min:0',
        ]);

        $agentPoint = $this->getAgentPoint();
        $agentUserId = $agentPoint->agent_user_id ?: Auth::id();
        $member = User::findOrFail($request->member_id);

        // Security check
        if ($member->agent_point_id && $member->agent_point_id != $agentPoint->id) {
            return response()->json([
                'success' => false,
                'message' => 'অননুমোদিত চেষ্টা: সদস্য এই পয়েন্টের অন্তর্ভুক্ত নন।'
            ], 403);
        }

        $deliveries = $request->deliveries;
        $totalDeliveredItemsCount = 0;
        $totalDeliveryMarketAmount = 0;
        $currentMonth = date('Y-m');

        DB::beginTransaction();
        try {
            // Generate Unique Delivery Transaction ID
            $deliveryNo = 'EK-DLV-' . date('ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $deliveryRecord = MemberDelivery::create([
                'delivery_no' => $deliveryNo,
                'agent_point_id' => $agentPoint->id,
                'agent_user_id' => $agentUserId,
                'card_holder_id' => $member->id,
                'card_number' => $request->card_number,
                'member_name' => $member->name,
                'area' => $member->areaModel->name ?? $agentPoint->area,
                'delivery_date' => date('Y-m-d'),
                'delivery_time' => date('H:i:s'),
                'status' => 'completed',
                'total_amount' => 0,
                'market_month' => $currentMonth,
                'remarks' => $request->remarks ?: 'সফলভাবে মাসিক বাজার বিতরণ সম্পন্ন',
            ]);

            $allOrdersCompleted = true;

            foreach ($deliveries as $del) {
                $deliverQty = floatval($del['deliver_qty']);
                if ($deliverQty <= 0) continue;

                $order = MonthlyBazaarOrder::findOrFail($del['order_id']);
                $approvedQty = floatval($order->allocated_quantity ?: $order->quantity);
                $prevDelivered = floatval($order->delivered_quantity ?: 0);
                $maxAllowable = max(0, $approvedQty - $prevDelivered);

                // Duplicate / Excess delivery prevention
                if ($deliverQty > $maxAllowable) {
                    $deliverQty = $maxAllowable;
                }

                $newDeliveredTotal = $prevDelivered + $deliverQty;
                $newRemaining = max(0, $approvedQty - $newDeliveredTotal);

                // Fixed Product Unit Price (Permanent Storage - Section 33.6)
                $unitPrice = $this->getProductUnitPrice($order->package_title);
                $itemTotalAmount = $deliverQty * $unitPrice;
                $totalDeliveryMarketAmount += $itemTotalAmount;

                // Create Delivery Item Record with permanent Unit Price & Total Amount
                MemberDeliveryItem::create([
                    'delivery_id' => $deliveryRecord->id,
                    'product_name' => $order->package_title,
                    'unit' => 'KG',
                    'approved_quantity' => $approvedQty,
                    'previously_delivered' => $prevDelivered,
                    'quantity' => $deliverQty,
                    'remaining_quantity' => $newRemaining,
                    'unit_price' => $unitPrice,
                    'total_amount' => $itemTotalAmount,
                ]);

                // Deduct from Agent Local Stock
                $localStock = AgentStock::where('agent_id', $agentUserId)
                    ->where(function ($q) use ($order) {
                        $q->where('stock_name', $order->package_title)
                          ->orWhere('stock_id', $order->item_id);
                    })->first();

                if ($localStock) {
                    $localStock->increment('sold_quantity', $deliverQty);
                } else {
                    AgentStock::create([
                        'agent_id' => $agentUserId,
                        'stock_name' => $order->package_title,
                        'allocated_quantity' => $deliverQty,
                        'sold_quantity' => $deliverQty,
                        'unit_price' => $unitPrice,
                    ]);
                }

                // Create Agent Stock Transaction Ledger Entry
                AgentStockTransaction::create([
                    'agent_id' => $agentUserId,
                    'stock_name' => $order->package_title,
                    'transaction_type' => 'delivery_out',
                    'quantity' => $deliverQty,
                    'unit_price' => $unitPrice,
                    'total_price' => $itemTotalAmount,
                    'customer_name' => $member->name,
                    'customer_phone' => $member->phone,
                    'customer_card_number' => $request->card_number,
                    'notes' => "Delivery: {$deliveryNo}",
                    'transaction_date' => date('Y-m-d'),
                ]);

                // Update Order Status
                $orderStatus = ($newRemaining <= 0) ? 'delivered' : 'partially_delivered';
                if ($newRemaining > 0) $allOrdersCompleted = false;

                $order->update([
                    'delivered_quantity' => $newDeliveredTotal,
                    'remaining_quantity' => $newRemaining,
                    'status' => $orderStatus,
                    'distribution_status' => $orderStatus,
                    'collection_note' => "Delivered {$deliverQty} (৳{$itemTotalAmount}) on " . date('d M Y') . " via {$agentPoint->name}",
                ]);

                $totalDeliveredItemsCount++;
            }

            // Update Total Delivery Amount permanently
            $deliveryRecord->update([
                'total_amount' => $totalDeliveryMarketAmount,
                'status' => $allOrdersCompleted ? 'completed' : 'partial'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'delivery_id' => $deliveryRecord->id,
                'delivery_no' => $deliveryNo,
                'total_amount' => $totalDeliveryMarketAmount,
                'message' => "✓ ডেলিভারি সফলভাবে সংরক্ষিত হয়েছে! মোট বাজার মূল্য: ৳" . number_format($totalDeliveryMarketAmount, 2) . " | ভাউচার নং: {$deliveryNo}",
                'receipt_url' => route('agent.receipt', $deliveryRecord->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'ডেলিভারি সংরক্ষণে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Agent Local Stock & Inventory Ledger
     * Section 33.7: Stock Formula & Real-time Ledger
     */
    public function stockIndex()
    {
        $agentPoint = $this->getAgentPoint();
        $agentUserId = $agentPoint->agent_user_id ?: Auth::id();

        $stocks = AgentStock::where('agent_id', $agentUserId)->get();
        $transactions = AgentStockTransaction::where('agent_id', $agentUserId)
            ->orderBy('id', 'DESC')
            ->paginate(15);

        return view('agent.stock', compact('agentPoint', 'stocks', 'transactions'));
    }

    /**
     * Incoming Dispatches List & Confirm Receive
     */
    public function dispatches()
    {
        $agentPoint = $this->getAgentPoint();

        $dispatches = AgentDispatch::with('items', 'creator')
            ->where('agent_point_id', $agentPoint->id)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('agent.dispatches', compact('agentPoint', 'dispatches'));
    }

    /**
     * Confirm Receive Inbound Dispatch & Increment Agent Stock
     */
    public function confirmReceiveDispatch($id)
    {
        $agentPoint = $this->getAgentPoint();
        $agentUserId = $agentPoint->agent_user_id ?: Auth::id();

        $dispatch = AgentDispatch::with('items')
            ->where('agent_point_id', $agentPoint->id)
            ->findOrFail($id);

        if ($dispatch->status === 'received') {
            return back()->with('info', 'এই চালানটি ইতিমধ্যে গৃহীত হয়েছে।');
        }

        DB::beginTransaction();
        try {
            foreach ($dispatch->items as $item) {
                $qty = floatval($item->quantity);
                $unitPrice = $this->getProductUnitPrice($item->product_name);

                // Increment or Create Agent Stock
                $stock = AgentStock::firstOrCreate(
                    [
                        'agent_id' => $agentUserId,
                        'stock_name' => $item->product_name
                    ],
                    [
                        'stock_id' => 1,
                        'allocated_quantity' => 0,
                        'sold_quantity' => 0,
                        'unit_price' => $unitPrice,
                    ]
                );

                $stock->increment('allocated_quantity', $qty);
                $item->update(['received_quantity' => $qty]);

                // Create Inbound Stock Transaction
                AgentStockTransaction::create([
                    'agent_id' => $agentUserId,
                    'stock_name' => $item->product_name,
                    'transaction_type' => 'stock_in',
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'total_price' => $qty * $unitPrice,
                    'notes' => "Dispatch Received: {$dispatch->dispatch_no}",
                    'transaction_date' => date('Y-m-d'),
                ]);
            }

            $dispatch->update([
                'status' => 'received',
                'received_by' => Auth::id(),
                'received_at' => now(),
            ]);

            DB::commit();

            return back()->with('success', "✓ চালান #{$dispatch->dispatch_no} সফলভাবে গৃহীত হয়েছে এবং লোকাল স্টকে যোগ করা হয়েছে।");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'চালান গ্রহণে ত্রুটি: ' . $e->getMessage());
        }
    }

    /**
     * List of Card Holders Assigned to This Agent Point
     */
    public function members(Request $request)
    {
        $agentPoint = $this->getAgentPoint();

        $members = User::where('agent_point_id', $agentPoint->id)
            ->when($request->search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%$search%")
                        ->orWhere('card_number', 'like', "%$search%")
                        ->orWhere('customer_id', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
            })
            ->orderBy('id', 'DESC')
            ->paginate(15);

        return view('agent.members', compact('agentPoint', 'members'));
    }

    /**
     * Scoped Monthly Market Requests
     */
    public function requests(Request $request)
    {
        $agentPoint = $this->getAgentPoint();

        $orders = MonthlyBazaarOrder::with('user')
            ->where('agent_point_id', $agentPoint->id)
            ->when($request->status, function ($q, $st) {
                $q->where('status', $st);
            })
            ->orderBy('id', 'DESC')
            ->paginate(15);

        return view('agent.requests', compact('agentPoint', 'orders'));
    }

    /**
     * Delivery History for This Agent Point
     * Section 33.11 & 33.12
     */
    public function deliveryHistory(Request $request)
    {
        $agentPoint = $this->getAgentPoint();

        $deliveries = MemberDelivery::with('items', 'cardHolder')
            ->where('agent_point_id', $agentPoint->id)
            ->when($request->search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('card_number', 'like', "%$search%")
                        ->orWhere('member_name', 'like', "%$search%")
                        ->orWhere('delivery_no', 'like', "%$search%");
                });
            })
            ->when($request->date, function ($q, $d) {
                $q->where('delivery_date', $d);
            })
            ->orderBy('id', 'DESC')
            ->paginate(15);

        return view('agent.delivery_history', compact('agentPoint', 'deliveries'));
    }

    /**
     * Member Collection Receipt
     * Section 33.6: Printable Financial Voucher
     */
    public function receipt($id)
    {
        $agentPoint = $this->getAgentPoint();

        $delivery = MemberDelivery::with('items', 'cardHolder', 'agentPoint', 'agentUser')
            ->where('agent_point_id', $agentPoint->id)
            ->findOrFail($id);

        return view('agent.receipt', compact('agentPoint', 'delivery'));
    }

    /**
     * SRD Section 6: Agent Incoming Supplier Supplies List (Strict Agent Isolation)
     */
    public function incomingSupplies(Request $request)
    {
        $agentPoint = $this->getAgentPoint();
        $agentUserId = $agentPoint->agent_user_id ?: Auth::id();

        $query = \App\Models\SupplierSupply::with(['supplier.supplierProfile', 'assignedBranch', 'items'])
            ->where(function ($q) use ($agentUserId, $agentPoint) {
                $q->where('assigned_agent_id', $agentUserId)
                  ->orWhere('assigned_agent_point_id', $agentPoint->id);
            });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $supplies = $query->orderByDesc('id')->paginate(15)->withQueryString();

        return view('agent.supplies.index', compact('agentPoint', 'supplies'));
    }

    /**
     * SRD Section 6 & 8: Agent Incoming Supply Details & Receiving Form
     */
    public function showIncomingSupply($id)
    {
        $agentPoint = $this->getAgentPoint();
        $agentUserId = $agentPoint->agent_user_id ?: Auth::id();

        $supply = \App\Models\SupplierSupply::with([
            'supplier.supplierProfile',
            'assignedBranch',
            'items',
            'receipts.items',
            'statusHistories.user',
            'attachments',
        ])->findOrFail($id);

        // Enforce strict isolation: Only assigned agent/point (or super_admin) can view
        $user = Auth::user();
        if (!in_array($user->role, ['super_admin', 'admin'], true)) {
            if ((int) $supply->assigned_agent_id !== (int) $agentUserId && (int) $supply->assigned_agent_point_id !== (int) $agentPoint->id) {
                abort(403, 'এই সরবরাহটি আপনার পয়েন্টে অ্যাসাইন করা হয়নি।');
            }
        }

        return view('agent.supplies.show', compact('agentPoint', 'supply'));
    }

    /**
     * SRD Section 8 & 16: Agent Product Receiving, Verification, Discrepancy & Stock Update
     */
    public function receiveIncomingSupply(Request $request, $id)
    {
        $agentPoint = $this->getAgentPoint();
        $agentUserId = $agentPoint->agent_user_id ?: Auth::id();

        $supply = \App\Models\SupplierSupply::with('items')->findOrFail($id);

        $user = Auth::user();
        if (!in_array($user->role, ['super_admin', 'admin'], true)) {
            if ((int) $supply->assigned_agent_id !== (int) $agentUserId && (int) $supply->assigned_agent_point_id !== (int) $agentPoint->id) {
                abort(403, 'অনুমতি নেই।');
            }
        }

        if (in_array($supply->status, ['received', 'rejected', 'cancelled'], true)) {
            return back()->with('error', 'এই সরবরাহটি ইতিমধ্যে সম্পন্ন বা বাতিল হয়ে গেছে।');
        }

        $request->validate([
            'action_type' => 'required|in:confirm_received,partial_received,discrepancy,rejected',
            'product_condition' => 'required|in:good,damaged,partially_damaged',
            'received_date' => 'required|date',
            'receiving_note' => 'nullable|string',
            'discrepancy_report' => 'nullable|string',
            'reject_reason' => 'required_if:action_type,rejected|nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'items' => 'nullable|array',
            'actual_received_quantity' => 'nullable|numeric|min:0',
            'damaged_quantity' => 'nullable|numeric|min:0',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = 'rcpt_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('upload/receiving_photos');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }
            $file->move($uploadDir, $fileName);
            $photoPath = 'upload/receiving_photos/' . $fileName;
        }

        DB::transaction(function () use ($request, $supply, $agentPoint, $agentUserId, $photoPath) {
            $oldStatus = $supply->status;
            $receiptNo = 'RCPT-' . date('Y') . '-' . str_pad((string) (\App\Models\SupplierSupplyReceipt::count() + 1), 5, '0', STR_PAD_LEFT);
            $actionType = $request->input('action_type');

            if ($actionType === 'rejected') {
                \App\Models\SupplierSupplyReceipt::create([
                    'receipt_no' => $receiptNo,
                    'supplier_supply_id' => $supply->id,
                    'agent_id' => Auth::id(),
                    'agent_point_id' => $agentPoint->id,
                    'branch_id' => $supply->assigned_branch_id,
                    'approved_quantity' => $supply->approved_quantity ?? $supply->quantity,
                    'actual_received_quantity' => 0,
                    'damaged_quantity' => 0,
                    'missing_quantity' => $supply->approved_quantity ?? $supply->quantity,
                    'product_condition' => $request->product_condition,
                    'action_type' => 'rejected',
                    'received_date' => $request->received_date,
                    'receiving_note' => $request->receiving_note,
                    'reject_reason' => $request->reject_reason,
                    'photo_path' => $photoPath,
                ]);

                $supply->update([
                    'status' => 'rejected',
                    'rejected_by' => Auth::id(),
                    'rejected_at' => now(),
                    'rejection_reason' => 'এজেন্ট রিজেক্ট করেছেন: ' . $request->reject_reason,
                ]);

                \App\Services\SupplyWorkflowService::recordStatusChange($supply, $oldStatus, 'rejected', 'এজেন্ট ডেলিভারি রিজেক্ট করেছেন: ' . $request->reject_reason);
                \App\Services\SupplyWorkflowService::logAudit($supply->id, 'agent_rejected_delivery', ['status' => $oldStatus], ['status' => 'rejected', 'reason' => $request->reject_reason]);

                \App\Services\SupplyWorkflowService::notifyParty(null, 'admin', 'এজেন্ট ডেলিভারি রিজেক্ট (' . $supply->supply_code . ')', 'কারণ: ' . $request->reject_reason, 'danger', $supply, route('admin.suppliers.supplies.show', $supply->id));
                \App\Services\SupplyWorkflowService::notifyParty($supply->supplier_id, 'supplier', 'এজেন্ট ডেলিভারি রিজেক্ট (' . $supply->supply_code . ')', 'কারণ: ' . $request->reject_reason, 'danger', $supply, route('supplier.supplies.show', $supply->id));
                return;
            }

            $totalApprovedQty = (float) ($supply->approved_quantity ?? $supply->quantity);
            $totalReceivedQty = 0;
            $totalDamagedQty = 0;
            $totalMissingQty = 0;
            $totalReceivedAmount = 0;

            $receipt = \App\Models\SupplierSupplyReceipt::create([
                'receipt_no' => $receiptNo,
                'supplier_supply_id' => $supply->id,
                'agent_id' => Auth::id(),
                'agent_point_id' => $agentPoint->id,
                'branch_id' => $supply->assigned_branch_id,
                'approved_quantity' => $totalApprovedQty,
                'product_condition' => $request->product_condition,
                'action_type' => $actionType,
                'received_date' => $request->received_date,
                'receiving_note' => $request->receiving_note,
                'discrepancy_report' => $request->discrepancy_report,
                'photo_path' => $photoPath,
            ]);

            if ($request->has('items') && $supply->items->count() > 0) {
                foreach ($request->input('items') as $itemId => $itemData) {
                    $item = \App\Models\SupplierSupplyItem::where('supplier_supply_id', $supply->id)->find($itemId);
                    if (!$item) continue;

                    $appQty = (float) ($item->approved_quantity ?? $item->quantity);
                    $recQty = (float) ($itemData['received_quantity'] ?? 0);
                    $dmgQty = (float) ($itemData['damaged_quantity'] ?? 0);
                    $misQty = max(0, $appQty - $recQty);
                    $rate = (float) ($item->approved_rate ?? $item->rate);
                    $recAmt = $recQty * $rate;

                    \App\Models\SupplierSupplyReceiptItem::create([
                        'receipt_id' => $receipt->id,
                        'supply_item_id' => $item->id,
                        'product_name' => $item->product_name,
                        'unit' => $item->unit,
                        'approved_quantity' => $appQty,
                        'received_quantity' => $recQty,
                        'damaged_quantity' => $dmgQty,
                        'missing_quantity' => $misQty,
                        'condition' => $request->product_condition,
                    ]);

                    $item->update([
                        'received_quantity' => $item->received_quantity + $recQty,
                        'damaged_quantity' => $item->damaged_quantity + $dmgQty,
                        'missing_quantity' => max(0, $appQty - ($item->received_quantity + $recQty)),
                        'received_amount' => ($item->received_quantity + $recQty) * $rate,
                    ]);

                    $totalReceivedQty += $recQty;
                    $totalDamagedQty += $dmgQty;
                    $totalMissingQty += $misQty;
                    $totalReceivedAmount += $recAmt;

                    // Update Agent Stock with Actual Received Quantity!
                    if ($recQty > 0) {
                        $this->incrementAgentInventory($agentUserId, $item->product_name, $recQty, $rate, $supply->supply_code);
                    }
                }
            } else {
                $recQty = (float) ($request->input('actual_received_quantity') ?? $totalApprovedQty);
                $dmgQty = (float) ($request->input('damaged_quantity') ?? 0);
                $misQty = max(0, $totalApprovedQty - $recQty);
                $rate = (float) ($supply->approved_rate ?? $supply->rate);
                $recAmt = $recQty * $rate;

                \App\Models\SupplierSupplyReceiptItem::create([
                    'receipt_id' => $receipt->id,
                    'product_name' => $supply->product_name,
                    'unit' => $supply->unit,
                    'approved_quantity' => $totalApprovedQty,
                    'received_quantity' => $recQty,
                    'damaged_quantity' => $dmgQty,
                    'missing_quantity' => $misQty,
                    'condition' => $request->product_condition,
                ]);

                $totalReceivedQty = $recQty;
                $totalDamagedQty = $dmgQty;
                $totalMissingQty = $misQty;
                $totalReceivedAmount = $recAmt;

                if ($recQty > 0) {
                    $this->incrementAgentInventory($agentUserId, $supply->product_name, $recQty, $rate, $supply->supply_code);
                }
            }

            $receipt->update([
                'actual_received_quantity' => $totalReceivedQty,
                'damaged_quantity' => $totalDamagedQty,
                'missing_quantity' => $totalMissingQty,
            ]);

            $cumReceivedQty = $supply->actual_received_quantity + $totalReceivedQty;
            $cumReceivedAmount = $supply->actual_received_amount + $totalReceivedAmount;
            $discrepancyQty = max(0, $totalApprovedQty - $cumReceivedQty);
            $avgRate = (float) ($supply->approved_rate ?? $supply->rate);
            $discrepancyAmt = max(0, ($supply->approved_amount ?? $supply->total_amount) - $cumReceivedAmount);

            $newStatus = 'received';
            if ($actionType === 'partial_received' || ($discrepancyQty > 0 && $actionType !== 'discrepancy' && $cumReceivedQty < $totalApprovedQty)) {
                $newStatus = $actionType === 'partial_received' ? 'partially_received' : 'discrepancy';
            } elseif ($actionType === 'discrepancy' || $totalDamagedQty > 0 || $request->product_condition !== 'good') {
                $newStatus = 'discrepancy';
            }

            $supply->update([
                'actual_received_quantity' => $cumReceivedQty,
                'actual_received_amount' => $cumReceivedAmount,
                'discrepancy_quantity' => $discrepancyQty,
                'discrepancy_amount' => $discrepancyAmt,
                'status' => $newStatus,
                'received_at' => now(),
            ]);

            \App\Services\SupplyWorkflowService::recordStatusChange(
                $supply,
                $oldStatus,
                $newStatus,
                "এজেন্ট {$totalReceivedQty} {$supply->unit} রিসিভ করেছেন।" . ($discrepancyQty > 0 ? " (ঘাটতি: {$discrepancyQty})" : '')
            );

            \App\Services\SupplyWorkflowService::logAudit(
                $supply->id,
                'agent_received_supply',
                ['status' => $oldStatus, 'actual_received_quantity' => $supply->actual_received_quantity - $totalReceivedQty],
                ['status' => $newStatus, 'received_qty' => $totalReceivedQty, 'damaged_qty' => $totalDamagedQty, 'missing_qty' => $totalMissingQty],
                $request->receiving_note
            );

            \App\Services\SupplyWorkflowService::notifyParty(
                null,
                'admin',
                'এজেন্ট পণ্য রিসিভ করেছেন (' . $supply->supply_code . ')',
                "{$agentPoint->name} এ {$totalReceivedQty} {$supply->unit} রিসিভ হয়েছে (৳" . number_format($totalReceivedAmount, 2) . ')।',
                $newStatus === 'discrepancy' ? 'warning' : 'success',
                $supply,
                route('admin.suppliers.supplies.show', $supply->id)
            );

            \App\Services\SupplyWorkflowService::notifyParty(
                $supply->supplier_id,
                'supplier',
                'পণ্য রিসিভ সম্পন্ন (' . $supply->supply_code . ')',
                "এজেন্ট {$totalReceivedQty} {$supply->unit} রিসিভ করেছেন। স্ট্যাটাস: " . (\App\Models\SupplierSupply::STATUS_META[$newStatus]['label'] ?? $newStatus),
                $newStatus === 'discrepancy' ? 'warning' : 'success',
                $supply,
                route('supplier.supplies.show', $supply->id)
            );
        });

        return redirect()->route('agent.supplies.show', $supply->id)->with('success', 'পণ্য রিসিভ এবং এজেন্ট স্টক সফলভাবে আপডেট করা হয়েছে!');
    }

    protected function incrementAgentInventory(int $agentUserId, string $productName, float $qty, float $unitPrice, ?string $supplyCode): void
    {
        $stock = AgentStock::firstOrCreate(
            [
                'agent_id' => $agentUserId,
                'stock_name' => $productName,
            ],
            [
                'stock_id' => 1,
                'allocated_quantity' => 0,
                'sold_quantity' => 0,
                'unit_price' => $unitPrice > 0 ? $unitPrice : $this->getProductUnitPrice($productName),
            ]
        );

        $stock->increment('allocated_quantity', $qty);

        AgentStockTransaction::create([
            'agent_id' => $agentUserId,
            'stock_name' => $productName,
            'transaction_type' => 'stock_in',
            'quantity' => $qty,
            'unit_price' => $unitPrice,
            'total_price' => $qty * $unitPrice,
            'notes' => "Supplier Direct Supply Received: {$supplyCode}",
            'transaction_date' => date('Y-m-d'),
        ]);
    }
}
