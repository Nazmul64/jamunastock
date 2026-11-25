<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AgentPoint;
use App\Models\AgentStock;
use App\Models\AgentDispatch;
use App\Models\AgentDispatchItem;
use App\Models\CentralWarehouseStock;
use App\Models\CentralStockTransaction;
use App\Models\MemberDelivery;
use App\Models\MemberDeliveryItem;
use App\Models\MonthlyBazaarOrder;
use App\Models\MonthlyMarketCycle;
use App\Models\MonthlyClosing;
use App\Models\Area;
use App\Models\User;

class AdminDistributionController extends Controller
{
    /**
     * Section 17: Central Admin Agent Monitoring Dashboard
     */
    public function agentMonitoring(Request $request)
    {
        $currentMonth = date('Y-m');
        $agentPoints = AgentPoint::with(['areaModel', 'agentUser'])
            ->withCount('cardHolders')
            ->get();

        $monitoringData = [];
        $totalAllMembers = 0;
        $totalAllRequests = 0;
        $totalAllDelivered = 0;
        $totalAllRemaining = 0;
        $totalAllMarketAmount = 0;

        foreach ($agentPoints as $point) {
            $memberCount = $point->card_holders_count;
            $requestCount = MonthlyBazaarOrder::where('agent_point_id', $point->id)->count();

            // Stocks
            $agentUserId = $point->agent_user_id;
            $stocks = $agentUserId ? AgentStock::where('agent_id', $agentUserId)->get() : collect();
            $receivedStock = floatval($stocks->sum('allocated_quantity'));
            $deliveredStock = floatval($stocks->sum('sold_quantity'));
            $remainingStock = max(0, $receivedStock - $deliveredStock);

            // Financial Market Amount
            $marketAmount = floatval(MemberDelivery::where('agent_point_id', $point->id)->sum('total_amount'));

            $monitoringData[] = [
                'point' => $point,
                'assigned_members' => $memberCount,
                'market_requests' => $requestCount,
                'received_stock' => $receivedStock,
                'delivered_stock' => $deliveredStock,
                'remaining_stock' => $remainingStock,
                'market_amount' => $marketAmount,
            ];

            $totalAllMembers += $memberCount;
            $totalAllRequests += $requestCount;
            $totalAllDelivered += $deliveredStock;
            $totalAllRemaining += $remainingStock;
            $totalAllMarketAmount += $marketAmount;
        }

        // Recent Delivery Stream
        $recentDeliveries = MemberDelivery::with('agentPoint', 'cardHolder')
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get();

        return view('admin.distribution.monitoring', compact(
            'monitoringData',
            'totalAllMembers',
            'totalAllRequests',
            'totalAllDelivered',
            'totalAllRemaining',
            'totalAllMarketAmount',
            'recentDeliveries'
        ));
    }

    /**
     * Drill-down into a specific Agent Point's details
     */
    public function agentDetails($id)
    {
        $point = AgentPoint::with(['areaModel', 'agentUser'])->findOrFail($id);
        $agentUserId = $point->agent_user_id;

        $members = User::where('agent_point_id', $point->id)->paginate(15);
        $stocks = $agentUserId ? AgentStock::where('agent_id', $agentUserId)->get() : collect();
        $dispatches = AgentDispatch::with('items')->where('agent_point_id', $point->id)->orderBy('id', 'DESC')->limit(10)->get();
        $deliveries = MemberDelivery::with('items')->where('agent_point_id', $point->id)->orderBy('id', 'DESC')->paginate(15);

        return view('admin.distribution.agent_details', compact('point', 'members', 'stocks', 'dispatches', 'deliveries'));
    }

    /**
     * Section 7: Product-wise Demand Calculation & Overview
     */
    public function demandIndex()
    {
        $currentMonth = date('Y-m');

        // Aggregate Product Demands from Orders
        $demandSummary = MonthlyBazaarOrder::select(
                'package_title as product_name',
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(quantity) as total_requested_qty'),
                DB::raw('SUM(CASE WHEN status IN ("approved", "allocated", "ready", "partially_delivered", "delivered") THEN (allocated_quantity) ELSE 0 END) as approved_demand_qty')
            )
            ->groupBy('package_title')
            ->get();

        // Attach Central Stock Information to each product
        $productsData = [];
        foreach ($demandSummary as $item) {
            $pName = $item->product_name;
            $centralStock = CentralWarehouseStock::where('product_name', $pName)->first();

            $availStock = $centralStock ? floatval($centralStock->available_stock) : 0;
            $approvedDemand = floatval($item->approved_demand_qty ?: $item->total_requested_qty);
            $requiredPurchase = max(0, $approvedDemand - $availStock);

            $productsData[] = [
                'product_name' => $pName,
                'total_orders' => $item->total_orders,
                'total_requested' => $item->total_requested_qty,
                'approved_demand' => $approvedDemand,
                'central_stock' => $availStock,
                'required_purchase' => $requiredPurchase,
                'unit' => $centralStock ? $centralStock->unit : 'KG',
            ];
        }

        // Area-wise Demand Summary
        $areaDemands = MonthlyBazaarOrder::select(
                'request_area',
                DB::raw('COUNT(DISTINCT user_id) as total_members'),
                DB::raw('COUNT(*) as total_requests'),
                DB::raw('SUM(quantity) as total_qty')
            )
            ->whereNotNull('request_area')
            ->groupBy('request_area')
            ->get();

        return view('admin.distribution.demand', compact('productsData', 'areaDemands'));
    }

    /**
     * Section 11 & Reference UI Image 4: Product Allocation & Route Dispatch
     */
    public function allocateIndex()
    {
        $agentPoints = AgentPoint::with('areaModel')->where('status', 'active')->get();
        $warehouseStocks = CentralWarehouseStock::where('available_stock', '>', 0)->get();
        $dispatches = AgentDispatch::with('agentPoint', 'items')->orderBy('id', 'DESC')->paginate(10);

        return view('admin.distribution.allocate', compact('agentPoints', 'warehouseStocks', 'dispatches'));
    }

    /**
     * Store Product Dispatch to Agent Point
     */
    public function storeDispatch(Request $request)
    {
        $request->validate([
            'agent_point_id' => 'required|exists:agent_points,id',
            'dispatch_date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.1',
        ]);

        $agentPoint = AgentPoint::findOrFail($request->agent_point_id);

        DB::beginTransaction();
        try {
            $dispatchNo = 'DSP-' . date('Ym') . '-' . strtoupper(substr(uniqid(), -4));

            $dispatch = AgentDispatch::create([
                'dispatch_no' => $dispatchNo,
                'agent_point_id' => $agentPoint->id,
                'dispatch_date' => $request->dispatch_date,
                'status' => 'dispatched',
                'source_warehouse' => 'Central Warehouse',
                'notes' => $request->notes ?: "Dispatch to {$agentPoint->name}",
                'created_by' => Auth::id(),
            ]);

            foreach ($request->items as $it) {
                $qty = floatval($it['quantity']);
                if ($qty <= 0) continue;

                $pName = $it['product_name'];
                $stock = CentralWarehouseStock::where('product_name', $pName)->first();

                if ($stock) {
                    if ($stock->available_stock < $qty) {
                        throw new \Exception("পণ্য '{$pName}'-এর সেন্ট্রাল ওয়্যারহাউস স্টক অপর্যাপ্ত। বর্তমান স্টক: {$stock->available_stock} {$stock->unit}");
                    }
                    $stock->decrement('available_stock', $qty);
                    $stock->increment('allocated_stock', $qty);
                }

                AgentDispatchItem::create([
                    'dispatch_id' => $dispatch->id,
                    'product_name' => $pName,
                    'quantity' => $qty,
                    'unit' => $stock ? $stock->unit : 'KG',
                ]);

                // Central Stock Transaction Log
                CentralStockTransaction::create([
                    'transaction_no' => 'CTX-' . uniqid(),
                    'transaction_type' => 'agent_dispatch',
                    'product_name' => $pName,
                    'quantity' => $qty,
                    'unit' => $stock ? $stock->unit : 'KG',
                    'source' => 'Central Warehouse',
                    'destination' => $agentPoint->name,
                    'reference_id' => $dispatch->id,
                    'reference_type' => 'AgentDispatch',
                    'remarks' => "Dispatched to {$agentPoint->name} (#{$dispatchNo})",
                    'created_by' => Auth::id(),
                    'transaction_date' => $request->dispatch_date,
                ]);
            }

            DB::commit();
            return back()->with('success', "✓ ডিসপ্যাচ #{$dispatchNo} সফলভাবে তৈরি করা হয়েছে! এজেন্ট পয়েন্টে নোটিফিকেশন পাঠানো হয়েছে।");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'ডিসপ্যাচ সম্পন্ন করা যায়নি: ' . $e->getMessage());
        }
    }

    /**
     * Section 10: Central Warehouse Live Stock & Ledger
     */
    public function warehouseStockIndex()
    {
        $stocks = CentralWarehouseStock::all();
        $transactions = CentralStockTransaction::with('creator')->orderBy('id', 'DESC')->paginate(15);

        return view('admin.distribution.warehouse_stock', compact('stocks', 'transactions'));
    }

    /**
     * Central Stock Manual Adjustment or Damage Recording
     */
    public function adjustWarehouseStock(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string',
            'action_type' => 'required|in:stock_in,damage,adjustment',
            'quantity' => 'required|numeric|min:0.1',
            'remarks' => 'nullable|string',
        ]);

        $stock = CentralWarehouseStock::firstOrCreate(
            ['product_name' => $request->product_name],
            ['unit' => 'KG', 'available_stock' => 0]
        );

        $qty = floatval($request->quantity);

        if ($request->action_type === 'stock_in') {
            $stock->increment('available_stock', $qty);
            $tType = 'stock_adjustment';
        } elseif ($request->action_type === 'damage') {
            $stock->decrement('available_stock', $qty);
            $stock->increment('damaged_stock', $qty);
            $tType = 'damage_loss';
        } else {
            $stock->increment('available_stock', $qty);
            $tType = 'stock_adjustment';
        }

        CentralStockTransaction::create([
            'transaction_no' => 'CTX-ADJ-' . uniqid(),
            'transaction_type' => $tType,
            'product_name' => $request->product_name,
            'quantity' => $qty,
            'unit' => $stock->unit,
            'source' => 'Admin Adjustment',
            'destination' => 'Central Warehouse',
            'remarks' => $request->remarks ?: "Manual {$request->action_type}",
            'created_by' => Auth::id(),
            'transaction_date' => date('Y-m-d'),
        ]);

        return back()->with('success', 'সেন্ট্রাল ওয়্যারহাউস স্টক সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Section 18: Admin Agent Delivery Monitoring & History
     */
    public function deliveriesHistory(Request $request)
    {
        $agentPoints = AgentPoint::all();

        $query = MemberDelivery::with(['agentPoint', 'cardHolder', 'items', 'agentUser'])
            ->when($request->agent_point_id, function ($q, $apId) {
                $q->where('agent_point_id', $apId);
            })
            ->when($request->card_number, function ($q, $card) {
                $q->where('card_number', 'like', "%$card%");
            })
            ->when($request->status, function ($q, $st) {
                $q->where('status', $st);
            })
            ->when($request->from_date && $request->to_date, function ($q) use ($request) {
                $q->whereBetween('delivery_date', [$request->from_date, $request->to_date]);
            });

        // Export to CSV if requested
        if ($request->export === 'csv') {
            $deliveries = $query->get();
            $filename = "Deliveries_History_" . date('Ymd_His') . ".csv";

            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function () use ($deliveries) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Delivery ID', 'Agent Point', 'Card Number', 'Member Name', 'Products Delivered', 'Date', 'Time', 'Status']);

                foreach ($deliveries as $d) {
                    $prodList = $d->items->map(function ($it) {
                        return "{$it->product_name}: {$it->quantity} {$it->unit}";
                    })->implode(', ');

                    fputcsv($handle, [
                        $d->delivery_no,
                        $d->agentPoint->name ?? '',
                        $d->card_number,
                        $d->member_name,
                        $prodList,
                        $d->delivery_date,
                        $d->delivery_time,
                        $d->status
                    ]);
                }
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

        $deliveries = $query->orderBy('id', 'DESC')->paginate(20);

        return view('admin.distribution.deliveries_history', compact('deliveries', 'agentPoints'));
    }

    /**
     * Section 20: Comprehensive Area-wise, Agent-wise & Product-wise Reporting
     */
    public function reportsIndex(Request $request)
    {
        $reportType = $request->input('type', 'area_wise');

        $areas = Area::withCount('cardHolders')->get();
        $agentPoints = AgentPoint::with('areaModel')->get();
        $products = CentralWarehouseStock::all();

        return view('admin.distribution.reports', compact('reportType', 'areas', 'agentPoints', 'products'));
    }

    /**
     * Section 24: Monthly Closing & Reconciliation System
     */
    public function monthlyClosingIndex()
    {
        $currentMonth = date('Y-m');
        $closings = MonthlyClosing::with('closer')->orderBy('id', 'DESC')->paginate(10);

        // Calculate Real-time Summary for Current Month
        $totalRequests = MonthlyBazaarOrder::where('created_at', 'like', "$currentMonth%")->count();
        $totalApproved = MonthlyBazaarOrder::where('created_at', 'like', "$currentMonth%")->whereIn('status', ['approved', 'allocated', 'ready', 'partially_delivered', 'delivered'])->count();

        $totalPurchased = CentralStockTransaction::where('transaction_type', 'purchase_receive')->where('transaction_date', 'like', "$currentMonth%")->sum('quantity');
        $totalDispatched = AgentDispatchItem::whereHas('dispatch', function ($q) use ($currentMonth) {
            $q->where('dispatch_date', 'like', "$currentMonth%");
        })->sum('quantity');

        $totalDelivered = MemberDeliveryItem::whereHas('delivery', function ($q) use ($currentMonth) {
            $q->where('delivery_date', 'like', "$currentMonth%");
        })->sum('quantity');

        $totalCentralStock = CentralWarehouseStock::sum('available_stock');
        $totalRemainingStock = $totalCentralStock;

        return view('admin.distribution.closing', compact(
            'currentMonth',
            'closings',
            'totalRequests',
            'totalApproved',
            'totalPurchased',
            'totalDispatched',
            'totalDelivered',
            'totalCentralStock',
            'totalRemainingStock'
        ));
    }

    /**
     * Execute & Lock Monthly Closing
     */
    public function executeMonthlyClosing(Request $request)
    {
        $month = $request->month_year ?: date('Y-m');

        // Check if already closed
        $exists = MonthlyClosing::where('month_year', $month)->where('status', 'closed')->first();
        if ($exists) {
            return back()->with('info', "মাস {$month}-এর ক্লোজিং ইতিমধ্যে সম্পন্ন করা হয়েছে।");
        }

        $totalRequests = MonthlyBazaarOrder::where('created_at', 'like', "$month%")->count();
        $totalApproved = MonthlyBazaarOrder::where('created_at', 'like', "$month%")->whereIn('status', ['approved', 'allocated', 'ready', 'partially_delivered', 'delivered'])->count();
        $totalPurchased = CentralStockTransaction::where('transaction_type', 'purchase_receive')->where('transaction_date', 'like', "$month%")->sum('quantity');
        $totalDispatched = AgentDispatchItem::whereHas('dispatch', function ($q) use ($month) {
            $q->where('dispatch_date', 'like', "$month%");
        })->sum('quantity');
        $totalDelivered = MemberDeliveryItem::whereHas('delivery', function ($q) use ($month) {
            $q->where('delivery_date', 'like', "$month%");
        })->sum('quantity');
        $totalCentralStock = CentralWarehouseStock::sum('available_stock');

        MonthlyClosing::create([
            'month_year' => $month,
            'total_requests' => $totalRequests,
            'total_approved' => $totalApproved,
            'total_purchased_qty' => $totalPurchased,
            'total_central_stock' => $totalCentralStock,
            'total_dispatched_qty' => $totalDispatched,
            'total_agent_received_qty' => $totalDispatched,
            'total_delivered_qty' => $totalDelivered,
            'total_pending_qty' => max(0, $totalDispatched - $totalDelivered),
            'closing_stock_qty' => $totalCentralStock,
            'status' => 'closed',
            'closed_by' => Auth::id(),
            'closed_at' => now(),
            'notes' => $request->notes ?: 'সফলভাবে মাসিক ক্লোজিং সম্পন্ন ও হিসাব লক করা হয়েছে।',
        ]);

        return back()->with('success', "✓ মাস {$month}-এর ক্লোজিং সফলভাবে সম্পন্ন হয়েছে!");
    }
}
