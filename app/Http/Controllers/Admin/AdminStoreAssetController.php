<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreAsset;
use App\Models\StoreAssetMaintenance;
use App\Models\StoreVoucher;
use App\Models\ErpStore;
use App\Models\ErpStoreLocation;
use App\Models\ErpItemCategory;
use App\Models\ErpItem;
use App\Models\ErpStockMovement;
use App\Models\ErpPurchaseRequisition;
use App\Models\ErpPurchaseOrder;
use App\Models\ErpMaterialIssue;
use App\Models\ErpAssetAssignment;
use App\Models\ErpAssetDepreciation;
use App\Models\ErpVehicle;
use App\Models\ErpVehicleTrip;
use App\Models\ErpVehicleFuelLog;
use App\Models\ErpMaintenanceWorkOrder;
use App\Models\ErpStockAudit;
use App\Models\ErpDamageLossDisposal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminStoreAssetController extends Controller
{
    /**
     * Store & Asset ERP Main Dashboard
     */
    public function dashboard()
    {
        // Fixed Assets Stats
        $totalAssets = StoreAsset::count();
        $totalValuation = StoreAsset::sum('current_value');
        $totalPurchaseCost = StoreAsset::sum('purchase_cost');

        $furnitureCount = StoreAsset::where('category', 'furniture')->count();
        $furnitureValuation = StoreAsset::where('category', 'furniture')->sum('current_value');

        $vehicleCount = StoreAsset::where('category', 'vehicle')->count();
        $vehicleValuation = StoreAsset::where('category', 'vehicle')->sum('current_value');

        $stationeryCount = StoreAsset::where('category', 'stationery')->count();
        $stationeryValuation = StoreAsset::where('category', 'stationery')->sum('current_value');

        $electronicsCount = StoreAsset::where('category', 'electronics')->count();
        $electronicsValuation = StoreAsset::where('category', 'electronics')->sum('current_value');

        // Inventory & Stock Stats
        $totalItemsCount = ErpItem::count();
        $totalStockQty = ErpItem::sum('current_stock');
        $totalStockValuation = ErpItem::sum(DB::raw('current_stock * purchase_price'));
        $lowStockCount = ErpItem::whereColumn('current_stock', '<=', 'reorder_level')->where('current_stock', '>', 0)->count();
        $outOfStockCount = ErpItem::where('current_stock', '<=', 0)->count();

        // Stores & Warehouses
        $storesCount = ErpStore::count();

        // Maintenances & Work Orders
        $totalWorkOrdersCount = ErpMaintenanceWorkOrder::count();
        $pendingWorkOrdersCount = ErpMaintenanceWorkOrder::whereIn('status', ['requested', 'approved', 'in_progress'])->count();
        $totalMaintenanceCost = ErpMaintenanceWorkOrder::where('status', 'completed')->sum('total_cost') + StoreAssetMaintenance::sum('cost');

        // Vehicles & Fleet Management
        $vehiclesCount = ErpVehicle::count();
        $activeVehiclesCount = ErpVehicle::where('status', 'active')->count();
        $inTripVehiclesCount = ErpVehicle::where('status', 'in_trip')->count();
        $vehicleExpiringDocsCount = ErpVehicle::where(function ($q) {
            $thirtyDays = Carbon::now()->addDays(30);
            $q->where('fitness_expiry', '<=', $thirtyDays)
              ->orWhere('tax_token_expiry', '<=', $thirtyDays)
              ->orWhere('insurance_expiry', '<=', $thirtyDays);
        })->count();

        // Requisitions & Approvals
        $pendingPRCount = ErpPurchaseRequisition::where('status', 'pending')->count();
        $pendingIssuesCount = ErpMaterialIssue::where('status', 'issued')->count();

        // Vouchers & Memos
        $totalDebitVouchers = StoreVoucher::where('type', 'debit_voucher')->sum('amount');
        $totalCashMemos = StoreVoucher::where('type', 'cash_memo')->sum('amount');
        $totalMoneyReceipts = StoreVoucher::where('type', 'money_receipt')->sum('amount');
        $totalBills = StoreVoucher::where('type', 'bill')->sum('amount');

        // Recent Activities
        $recentAssets = StoreAsset::latest()->take(5)->get();
        $recentMovements = ErpStockMovement::with(['item', 'store'])->latest()->take(6)->get();
        $recentWorkOrders = ErpMaintenanceWorkOrder::latest()->take(5)->get();
        $recentPRs = ErpPurchaseRequisition::latest()->take(5)->get();
        $recentVehicles = ErpVehicle::latest()->take(4)->get();

        return view('admin.store.dashboard', compact(
            'totalAssets',
            'totalValuation',
            'totalPurchaseCost',
            'furnitureCount',
            'furnitureValuation',
            'vehicleCount',
            'vehicleValuation',
            'stationeryCount',
            'stationeryValuation',
            'electronicsCount',
            'electronicsValuation',
            'totalItemsCount',
            'totalStockQty',
            'totalStockValuation',
            'lowStockCount',
            'outOfStockCount',
            'storesCount',
            'totalWorkOrdersCount',
            'pendingWorkOrdersCount',
            'totalMaintenanceCost',
            'vehiclesCount',
            'activeVehiclesCount',
            'inTripVehiclesCount',
            'vehicleExpiringDocsCount',
            'pendingPRCount',
            'pendingIssuesCount',
            'totalDebitVouchers',
            'totalCashMemos',
            'totalMoneyReceipts',
            'totalBills',
            'recentAssets',
            'recentMovements',
            'recentWorkOrders',
            'recentPRs',
            'recentVehicles'
        ));
    }

    /**
     * All Assets & Store Items
     */
    public function index(Request $request)
    {
        $query = StoreAsset::query()->withCount('maintenances');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('asset_code', 'like', "%{$search}%")
                    ->orWhere('serial_no', 'like', "%{$search}%")
                    ->orWhere('model_no', 'like', "%{$search}%")
                    ->orWhere('vehicle_reg_no', 'like', "%{$search}%")
                    ->orWhere('assigned_to', 'like', "%{$search}%");
            });
        }

        $assets = $query->latest('id')->paginate(15)->withQueryString();

        $totalValuation = StoreAsset::sum('current_value');
        $totalAssets = StoreAsset::count();
        $inUseCount = StoreAsset::where('status', 'in_use')->count();
        $damagedCount = StoreAsset::where('status', 'damaged')->count();

        return view('admin.store.assets.index', compact(
            'assets',
            'totalValuation',
            'totalAssets',
            'inUseCount',
            'damagedCount'
        ));
    }

    public function create()
    {
        $year = date('Y');
        $nextId = (StoreAsset::max('id') ?? 0) + 1;
        $suggestedCode = sprintf("AST-%s-%04d", $year, $nextId);
        $categories = ErpItemCategory::all();
        $stores = ErpStore::all();

        return view('admin.store.assets.create', compact('suggestedCode', 'categories', 'stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'category' => 'required|string',
            'purchase_cost' => 'required|numeric|min:0',
            'current_value' => 'nullable|numeric|min:0',
        ]);

        $assetCode = $request->asset_code;
        if (empty($assetCode) || StoreAsset::where('asset_code', $assetCode)->exists()) {
            $assetCode = 'AST-' . date('Y') . '-' . str_pad((StoreAsset::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $uploadDir = public_path('upload/store_assets');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . '_' . Str::random(6) . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $fileName);
            $imagePath = $fileName;
        }

        StoreAsset::create([
            'asset_code' => $assetCode,
            'name' => $request->name,
            'category' => $request->category,
            'model_no' => $request->model_no,
            'serial_no' => $request->serial_no,
            'vehicle_reg_no' => $request->vehicle_reg_no,
            'quantity' => $request->quantity ?? 1,
            'unit' => $request->unit ?? 'Pcs',
            'purchase_date' => $request->purchase_date ?? now()->toDateString(),
            'purchase_cost' => $request->purchase_cost,
            'current_value' => $request->current_value ?? $request->purchase_cost,
            'vendor_name' => $request->vendor_name,
            'vendor_contact' => $request->vendor_contact,
            'location' => $request->location,
            'assigned_to' => $request->assigned_to,
            'condition' => $request->condition ?? 'good',
            'status' => $request->status ?? 'in_use',
            'image' => $imagePath,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.store.assets.index')->with('success', 'নতুন সম্পদ সফলভাবে সিস্টেমে যুক্ত হয়েছে!');
    }

    public function show($id)
    {
        $asset = StoreAsset::with('maintenances')->findOrFail($id);
        $assignments = ErpAssetAssignment::where('asset_id', $id)->latest()->get();
        $depreciations = ErpAssetDepreciation::where('asset_id', $id)->orderBy('fiscal_year')->get();

        return view('admin.store.assets.show', compact('asset', 'assignments', 'depreciations'));
    }

    public function edit($id)
    {
        $asset = StoreAsset::findOrFail($id);
        $categories = ErpItemCategory::all();

        return view('admin.store.assets.edit', compact('asset', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $asset = StoreAsset::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:200',
            'category' => 'required|string',
            'purchase_cost' => 'required|numeric|min:0',
        ]);

        $imagePath = $asset->image;
        if ($request->hasFile('image')) {
            $uploadDir = public_path('upload/store_assets');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if ($asset->image && file_exists($uploadDir . '/' . $asset->image)) {
                @unlink($uploadDir . '/' . $asset->image);
            }
            $fileName = time() . '_' . Str::random(6) . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $fileName);
            $imagePath = $fileName;
        }

        $asset->update([
            'name' => $request->name,
            'category' => $request->category,
            'model_no' => $request->model_no,
            'serial_no' => $request->serial_no,
            'vehicle_reg_no' => $request->vehicle_reg_no,
            'quantity' => $request->quantity ?? 1,
            'unit' => $request->unit ?? 'Pcs',
            'purchase_date' => $request->purchase_date,
            'purchase_cost' => $request->purchase_cost,
            'current_value' => $request->current_value ?? $asset->current_value,
            'vendor_name' => $request->vendor_name,
            'vendor_contact' => $request->vendor_contact,
            'location' => $request->location,
            'assigned_to' => $request->assigned_to,
            'condition' => $request->condition,
            'status' => $request->status,
            'image' => $imagePath,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.store.assets.show', $asset->id)->with('success', 'সম্পদের বিবরণ সফলভাবে আপডেট হয়েছে!');
    }

    public function destroy($id)
    {
        $asset = StoreAsset::findOrFail($id);
        if ($asset->image && file_exists(public_path('upload/store_assets/' . $asset->image))) {
            @unlink(public_path('upload/store_assets/' . $asset->image));
        }
        $asset->delete();

        return redirect()->route('admin.store.assets.index')->with('success', 'সম্পদ রেকর্ডটি মুছে ফেলা হয়েছে!');
    }

    // =========================================================================
    // 2. STORES & WAREHOUSES MANAGEMENT
    // =========================================================================
    public function storesIndex()
    {
        $stores = ErpStore::with('locations')->get();
        return view('admin.store.stores.index', compact('stores'));
    }

    public function storeStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'store_type' => 'required|string',
        ]);

        $code = $request->code;
        if (empty($code)) {
            $code = 'STORE-' . strtoupper(substr($request->store_type, 0, 4)) . '-' . rand(10, 99);
        }

        ErpStore::create([
            'name' => $request->name,
            'code' => $code,
            'location' => $request->location,
            'manager_name' => $request->manager_name,
            'contact' => $request->contact,
            'store_type' => $request->store_type,
            'capacity' => $request->capacity,
            'status' => true,
        ]);

        return back()->with('success', 'নতুন স্টোর / গুদাম সফলভাবে তৈরি হয়েছে!');
    }

    public function storeLocationStore(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:erp_stores,id',
            'rack_code' => 'required|string',
        ]);

        ErpStoreLocation::create($request->all());
        return back()->with('success', 'স্টোর লোকেশন (Rack/Shelf/Bin) সফলভাবে সংরক্ষণ করা হয়েছে!');
    }

    // =========================================================================
    // 3. ITEM MASTER & INVENTORY MANAGEMENT
    // =========================================================================
    public function itemsIndex(Request $request)
    {
        $query = ErpItem::with('category');

        if ($request->filled('category_code') && $request->category_code !== 'all') {
            $query->where('category_code', $request->category_code);
        }
        if ($request->filled('item_type') && $request->item_type !== 'all') {
            $query->where('item_type', $request->item_type);
        }
        if ($request->filter === 'low_stock') {
            $query->whereColumn('current_stock', '<=', 'reorder_level')->where('current_stock', '>', 0);
        } elseif ($request->filter === 'out_of_stock') {
            $query->where('current_stock', '<=', 0);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name_bn', 'like', "%{$s}%")
                  ->orWhere('name_en', 'like', "%{$s}%")
                  ->orWhere('item_code', 'like', "%{$s}%")
                  ->orWhere('brand', 'like', "%{$s}%");
            });
        }

        $items = $query->orderBy('name_bn')->paginate(15)->withQueryString();
        $categories = ErpItemCategory::all();

        $totalItems = ErpItem::count();
        $totalStockValue = ErpItem::sum(DB::raw('current_stock * purchase_price'));
        $lowStockCount = ErpItem::whereColumn('current_stock', '<=', 'reorder_level')->where('current_stock', '>', 0)->count();

        return view('admin.store.items.index', compact('items', 'categories', 'totalItems', 'totalStockValue', 'lowStockCount'));
    }

    public function itemCreate()
    {
        $categories = ErpItemCategory::all();
        $stores = ErpStore::all();
        $nextCode = 'ITEM-' . rand(100, 999) . '-' . str_pad((ErpItem::max('id') ?? 0) + 1, 3, '0', STR_PAD_LEFT);

        return view('admin.store.items.create', compact('categories', 'stores', 'nextCode'));
    }

    public function itemStore(Request $request)
    {
        $request->validate([
            'name_bn' => 'required|string|max:200',
            'category_code' => 'required|string',
            'purchase_price' => 'required|numeric|min:0',
        ]);

        $category = ErpItemCategory::where('code', $request->category_code)->first();

        $code = $request->item_code ?: 'ITEM-' . rand(100, 999);
        $barcode = $request->barcode ?: '894' . rand(100000000, 999999999);

        $item = ErpItem::create([
            'item_code' => $code,
            'name_bn' => $request->name_bn,
            'name_en' => $request->name_en,
            'category_id' => $category?->id,
            'category_code' => $request->category_code,
            'brand' => $request->brand,
            'model_no' => $request->model_no,
            'unit' => $request->unit ?? 'Pcs',
            'item_type' => $request->item_type ?? 'consumable',
            'purchase_price' => $request->purchase_price,
            'average_cost' => $request->purchase_price,
            'current_stock' => $request->opening_stock ?? 0,
            'min_stock' => $request->min_stock ?? 5,
            'max_stock' => $request->max_stock ?? 1000,
            'reorder_level' => $request->reorder_level ?? 10,
            'warranty_months' => $request->warranty_months ?? 0,
            'barcode' => $barcode,
            'status' => true,
        ]);

        // Record Opening Stock if > 0
        if ($request->opening_stock > 0) {
            ErpStockMovement::create([
                'item_id' => $item->id,
                'store_id' => $request->store_id,
                'movement_type' => 'opening',
                'quantity' => $request->opening_stock,
                'unit_price' => $request->purchase_price,
                'total_cost' => $request->opening_stock * $request->purchase_price,
                'running_stock' => $request->opening_stock,
                'reference_no' => 'OPENING-STOCK',
                'notes' => 'প্রারম্ভিক স্টক এন্ট্রি',
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->route('admin.store.items.index')->with('success', 'নতুন আইটেম সফলভাবে আইটেম মাস্টারে যুক্ত হয়েছে!');
    }

    public function stockMovements(Request $request)
    {
        $query = ErpStockMovement::with(['item', 'store']);

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }
        if ($request->filled('movement_type') && $request->movement_type !== 'all') {
            $query->where('movement_type', $request->movement_type);
        }

        $movements = $query->latest('id')->paginate(20)->withQueryString();
        $items = ErpItem::all();
        $stores = ErpStore::all();

        return view('admin.store.items.movements', compact('movements', 'items', 'stores'));
    }

    public function stockIn(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:erp_items,id',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $item = ErpItem::findOrFail($request->item_id);
            $newStock = $item->current_stock + $request->quantity;

            // Recalculate average cost
            $currentTotalVal = $item->current_stock * $item->average_cost;
            $newTotalVal = $request->quantity * $request->unit_price;
            $avgCost = ($currentTotalVal + $newTotalVal) / $newStock;

            $item->update([
                'current_stock' => $newStock,
                'average_cost' => round($avgCost, 2),
                'purchase_price' => $request->unit_price,
            ]);

            ErpStockMovement::create([
                'item_id' => $item->id,
                'store_id' => $request->store_id,
                'movement_type' => $request->movement_type ?? 'purchase_in',
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'total_cost' => $request->quantity * $request->unit_price,
                'running_stock' => $newStock,
                'reference_no' => $request->reference_no,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            DB::commit();
            return back()->with('success', 'স্টকে মালামাল সফলভাবে জমা (Stock In) হয়েছে!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'স্টক ইন করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    public function stockOut(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:erp_items,id',
            'quantity' => 'required|numeric|min:0.01',
            'department' => 'required|string',
            'employee_name' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $item = ErpItem::findOrFail($request->item_id);

            if ($item->current_stock < $request->quantity) {
                return back()->with('error', 'পর্যাপ্ত স্টক নেই! বর্তমান উপলব্ধ স্টক: ' . $item->current_stock . ' ' . $item->unit);
            }

            $newStock = $item->current_stock - $request->quantity;
            $item->update(['current_stock' => $newStock]);

            ErpStockMovement::create([
                'item_id' => $item->id,
                'store_id' => $request->store_id,
                'movement_type' => 'issue_out',
                'quantity' => $request->quantity,
                'unit_price' => $item->average_cost,
                'total_cost' => $request->quantity * $item->average_cost,
                'running_stock' => $newStock,
                'reference_no' => $request->reference_no,
                'department' => $request->department,
                'notes' => "গ্রহীতা: {$request->employee_name}। বিবরণ: {$request->notes}",
                'created_by' => Auth::id(),
            ]);

            DB::commit();
            return back()->with('success', 'মালামাল সফলভাবে বিতরণ / ইস্যু (Stock Out) সম্পন্ন হয়েছে!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'বিতরণ করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // 4. VEHICLES & FLEET MANAGEMENT
    // =========================================================================
    public function vehiclesIndex(Request $request)
    {
        $query = ErpVehicle::withCount(['trips', 'fuelLogs']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $vehicles = $query->latest('id')->paginate(12);

        $totalVehicles = ErpVehicle::count();
        $activeCount = ErpVehicle::where('status', 'active')->count();
        $inTripCount = ErpVehicle::where('status', 'in_trip')->count();

        return view('admin.store.vehicles.index', compact('vehicles', 'totalVehicles', 'activeCount', 'inTripCount'));
    }

    public function vehicleStore(Request $request)
    {
        $request->validate([
            'reg_no' => 'required|string|unique:erp_vehicles,reg_no',
            'vehicle_name' => 'required|string',
            'vehicle_type' => 'required|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $uploadDir = public_path('upload/vehicles');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . '_' . Str::random(6) . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $fileName);
            $imagePath = 'upload/vehicles/' . $fileName;
        }

        ErpVehicle::create(array_merge($request->all(), ['image' => $imagePath]));

        return back()->with('success', 'নতুন গাড়ি / যানবাহন সফলভাবে যুক্ত হয়েছে!');
    }

    public function vehicleShow($id)
    {
        $vehicle = ErpVehicle::with(['trips' => function($q) { $q->latest()->take(10); }, 'fuelLogs' => function($q) { $q->latest()->take(10); }])->findOrFail($id);
        $totalTripKm = $vehicle->trips->sum('total_km');
        $totalFuelCost = $vehicle->fuelLogs->sum('total_cost');

        return view('admin.store.vehicles.show', compact('vehicle', 'totalTripKm', 'totalFuelCost'));
    }

    public function vehicleTripStore(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:erp_vehicles,id',
            'driver_name' => 'required|string',
            'start_location' => 'required|string',
            'destination' => 'required|string',
            'start_km' => 'required|numeric',
        ]);

        $tripKm = 0.00;
        if ($request->end_km && $request->end_km >= $request->start_km) {
            $tripKm = $request->end_km - $request->start_km;
        }

        ErpVehicleTrip::create([
            'vehicle_id' => $request->vehicle_id,
            'driver_name' => $request->driver_name,
            'start_location' => $request->start_location,
            'destination' => $request->destination,
            'purpose' => $request->purpose,
            'start_time' => $request->start_time ?? now(),
            'end_time' => $request->end_time,
            'start_km' => $request->start_km,
            'end_km' => $request->end_km,
            'total_km' => $tripKm,
            'fuel_used_liters' => $request->fuel_used_liters ?? 0,
            'trip_cost' => $request->trip_cost ?? 0,
            'status' => $request->end_km ? 'completed' : 'ongoing',
            'notes' => $request->notes,
        ]);

        // Update vehicle odometer
        if ($request->end_km) {
            ErpVehicle::where('id', $request->vehicle_id)->update(['current_mileage' => $request->end_km]);
        }

        return back()->with('success', 'ট্রিপ লগ সফলভাবে সংরক্ষণ করা হয়েছে!');
    }

    public function vehicleFuelStore(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:erp_vehicles,id',
            'fuel_station' => 'required|string',
            'quantity_liters' => 'required|numeric|min:0.1',
            'rate_per_liter' => 'required|numeric|min:1',
            'odometer_km' => 'required|numeric',
        ]);

        $totalCost = $request->quantity_liters * $request->rate_per_liter;

        ErpVehicleFuelLog::create([
            'vehicle_id' => $request->vehicle_id,
            'log_date' => $request->log_date ?? now()->toDateString(),
            'fuel_station' => $request->fuel_station,
            'fuel_type' => $request->fuel_type ?? 'Octane',
            'quantity_liters' => $request->quantity_liters,
            'rate_per_liter' => $request->rate_per_liter,
            'total_cost' => $totalCost,
            'odometer_km' => $request->odometer_km,
            'slip_no' => $request->slip_no,
            'driver_name' => $request->driver_name,
            'payment_method' => $request->payment_method ?? 'cash',
        ]);

        return back()->with('success', 'জ্বালানি / ফুয়েল ভাউচার এন্ট্রি সফল হয়েছে!');
    }

    // =========================================================================
    // 5. MAINTENANCE & WORK ORDERS
    // =========================================================================
    public function workOrders(Request $request)
    {
        $query = ErpMaintenanceWorkOrder::with('asset');

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $workOrders = $query->latest('id')->paginate(15)->withQueryString();
        $assets = StoreAsset::all();

        return view('admin.store.maintenances.work_orders', compact('workOrders', 'assets'));
    }

    public function workOrderStore(Request $request)
    {
        $request->validate([
            'problem_title' => 'required|string|max:200',
            'category' => 'required|string',
        ]);

        $woNo = 'MWO-' . date('Y') . '-' . str_pad((ErpMaintenanceWorkOrder::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);
        $totalCost = (float)($request->spare_parts_cost ?? 0) + (float)($request->labor_cost ?? 0) + (float)($request->other_cost ?? 0);

        ErpMaintenanceWorkOrder::create([
            'work_order_no' => $woNo,
            'asset_id' => $request->asset_id,
            'category' => $request->category,
            'problem_title' => $request->problem_title,
            'problem_description' => $request->problem_description,
            'priority' => $request->priority ?? 'normal',
            'service_provider' => $request->service_provider,
            'provider_contact' => $request->provider_contact,
            'is_amc' => $request->boolean('is_amc'),
            'spare_parts_cost' => $request->spare_parts_cost ?? 0,
            'labor_cost' => $request->labor_cost ?? 0,
            'other_cost' => $request->other_cost ?? 0,
            'total_cost' => $totalCost,
            'start_date' => $request->start_date ?? now()->toDateString(),
            'completion_date' => $request->completion_date,
            'status' => $request->status ?? 'requested',
            'preventive_interval_months' => $request->preventive_interval_months ?? 0,
            'next_due_date' => $request->next_due_date,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'মেরামত ওয়ার্ক অর্ডার সফলভাবে তৈরি হয়েছে!');
    }

    // =========================================================================
    // 6. ASSET DEPRECIATION ENGINE
    // =========================================================================
    public function depreciations(Request $request)
    {
        $depreciations = ErpAssetDepreciation::with('asset')->latest()->paginate(20);
        $assets = StoreAsset::where('purchase_cost', '>', 0)->get();

        return view('admin.store.assets.depreciations', compact('depreciations', 'assets'));
    }

    public function depreciationCalculate(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:store_assets,id',
            'useful_life_years' => 'required|numeric|min:1',
            'fiscal_year' => 'required|string',
        ]);

        $asset = StoreAsset::findOrFail($request->asset_id);
        $cost = (float) $asset->purchase_cost;
        $salvage = (float) ($request->salvage_value ?? 0);
        $life = (float) $request->useful_life_years;
        $method = $request->method ?? 'straight_line';

        if ($method === 'straight_line') {
            $annualDep = max(0, ($cost - $salvage) / $life);
            $rate = ($cost > 0) ? ($annualDep / $cost) * 100 : 0;
        } else {
            $rate = (float) ($request->depreciation_rate ?? 20.00);
            $annualDep = ($cost * $rate) / 100;
        }

        $prevAccum = ErpAssetDepreciation::where('asset_id', $asset->id)->sum('annual_depreciation');
        $newAccum = $prevAccum + $annualDep;
        $bookValue = max($salvage, $cost - $newAccum);

        ErpAssetDepreciation::create([
            'asset_id' => $asset->id,
            'fiscal_year' => $request->fiscal_year,
            'method' => $method,
            'purchase_cost' => $cost,
            'salvage_value' => $salvage,
            'useful_life_years' => $life,
            'depreciation_rate' => round($rate, 2),
            'annual_depreciation' => round($annualDep, 2),
            'accumulated_depreciation' => round($newAccum, 2),
            'current_book_value' => round($bookValue, 2),
            'calculated_at' => now()->toDateString(),
        ]);

        // Update asset current book value
        $asset->update(['current_value' => round($bookValue, 2)]);

        return back()->with('success', 'সম্পদের অবচয় (Depreciation) স্বয়ংক্রিয়ভাবে গণনা ও রেকর্ড করা হয়েছে!');
    }

    // =========================================================================
    // 7. STOCK AUDITS & DAMAGE/DISPOSAL REGISTERS
    // =========================================================================
    public function audits(Request $request)
    {
        $audits = ErpStockAudit::with('store')->latest()->paginate(15);
        $stores = ErpStore::all();
        return view('admin.store.audits.index', compact('audits', 'stores'));
    }

    public function auditStore(Request $request)
    {
        $request->validate([
            'auditor_name' => 'required|string',
            'audit_date' => 'required|date',
        ]);

        $auditNo = 'AUDIT-' . date('Y') . '-' . rand(100, 999);
        ErpStockAudit::create(array_merge($request->all(), [
            'audit_no' => $auditNo,
            'status' => 'approved',
        ]));

        return back()->with('success', 'স্টক অডিট ও ভেরিফিকেশন রিপোর্ট সফলভাবে সংরক্ষণ করা হয়েছে!');
    }

    public function disposals(Request $request)
    {
        $disposals = ErpDamageLossDisposal::latest()->paginate(15);
        $assets = StoreAsset::all();
        $items = ErpItem::all();
        return view('admin.store.disposals.index', compact('disposals', 'assets', 'items'));
    }

    public function disposalStore(Request $request)
    {
        $request->validate([
            'type' => 'required|in:damage,lost,disposal,write_off',
            'reason' => 'required|string',
            'incident_date' => 'required|date',
        ]);

        $recNo = 'REC-' . strtoupper($request->type) . '-' . date('Y') . '-' . rand(100, 999);
        $scrap = (float)($request->scrap_selling_price ?? 0);
        $book = (float)($request->original_book_value ?? 0);
        $gainLoss = $scrap - $book;

        ErpDamageLossDisposal::create(array_merge($request->all(), [
            'record_no' => $recNo,
            'net_gain_loss' => $gainLoss,
            'status' => 'approved',
        ]));

        return back()->with('success', 'রেকর্ড সফলভাবে সংরক্ষণ করা হয়েছে!');
    }

    // =========================================================================
    // 8. EXISTING ASSET MAINTENANCES, VOUCHERS & REPORTS (PRESERVED)
    // =========================================================================
    public function maintenances(Request $request)
    {
        $query = StoreAssetMaintenance::with('asset');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('maintenance_no', 'like', "%{$search}%")
                    ->orWhere('service_provider', 'like', "%{$search}%");
            });
        }

        $maintenances = $query->latest('service_date')->paginate(15)->withQueryString();
        $totalCost = StoreAssetMaintenance::sum('cost');
        $assets = StoreAsset::all();

        return view('admin.store.maintenances.index', compact('maintenances', 'totalCost', 'assets'));
    }

    public function storeMaintenance(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:store_assets,id',
            'title' => 'required|string|max:200',
            'service_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
        ]);

        $maintenanceNo = 'MNT-' . date('Y') . '-' . str_pad((StoreAssetMaintenance::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);

        StoreAssetMaintenance::create([
            'maintenance_no' => $maintenanceNo,
            'asset_id' => $request->asset_id,
            'title' => $request->title,
            'description' => $request->description,
            'service_date' => $request->service_date,
            'next_service_due' => $request->next_service_due,
            'service_provider' => $request->service_provider,
            'provider_contact' => $request->provider_contact,
            'cost' => $request->cost,
            'voucher_ref' => $request->voucher_ref,
            'status' => $request->status ?? 'completed',
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'রক্ষণাবেক্ষণ ও মেরামত তথ্য সফলভাবে সংরক্ষণ করা হয়েছে!');
    }

    public function deleteMaintenance($id)
    {
        $m = StoreAssetMaintenance::findOrFail($id);
        $m->delete();
        return back()->with('success', 'মেনটেনেন্স রেকর্ড মুছে ফেলা হয়েছে!');
    }

    public function vouchers(Request $request)
    {
        $query = StoreVoucher::query();
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('voucher_no', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('vendor_customer_name', 'like', "%{$search}%")
                  ->orWhere('item_details', 'like', "%{$search}%");
            });
        }
        $vouchers = $query->latest('date')->paginate(15)->withQueryString();
        $totalAmount = (clone $query)->sum('amount');

        return view('admin.store.vouchers.index', compact('vouchers', 'totalAmount'));
    }

    public function cashMemos(Request $request)
    {
        $query = StoreVoucher::whereIn('type', ['cash_memo', 'money_receipt']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('voucher_no', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('vendor_customer_name', 'like', "%{$search}%")
                  ->orWhere('item_details', 'like', "%{$search}%");
            });
        }

        $memos = $query->latest('date')->paginate(15)->withQueryString();
        $vouchers = $memos;
        $totalAmount = (clone $query)->sum('amount');

        return view('admin.store.vouchers.cash_memos', compact('memos', 'vouchers', 'totalAmount'));
    }

    public function storeVoucher(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:200',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        $prefix = match ($request->type) {
            'debit_voucher' => 'DV',
            'cash_memo' => 'CM',
            'money_receipt' => 'MR',
            'bill' => 'BL',
            default => 'VCH',
        };
        $voucherNo = $prefix . '-' . date('Y') . '-' . str_pad((StoreVoucher::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);

        StoreVoucher::create([
            'voucher_no' => $voucherNo,
            'type' => $request->type,
            'category' => $request->category ?? 'office_expense',
            'title' => $request->title,
            'vendor_customer_name' => $request->vendor_customer_name,
            'amount' => $request->amount,
            'date' => $request->date,
            'payment_method' => $request->payment_method,
            'payment_ref' => $request->payment_ref,
            'item_details' => $request->item_details,
            'description' => $request->description,
            'status' => $request->status ?? 'paid',
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'ডকুমেন্ট / ভাউচার সফলভাবে সংরক্ষণ করা হয়েছে!');
    }

    public function deleteVoucher($id)
    {
        $v = StoreVoucher::findOrFail($id);
        $v->delete();
        return back()->with('success', 'ভাউচার / মেমো রেকর্ড মুছে ফেলা হয়েছে!');
    }

    public function printVoucher($id)
    {
        $voucher = StoreVoucher::findOrFail($id);
        return view('admin.store.vouchers.print', compact('voucher'));
    }

    public function reports(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        $maintenances = StoreAssetMaintenance::with('asset')
            ->whereBetween('service_date', [$startDate, $endDate])
            ->latest('service_date')
            ->get();

        $vouchers = StoreVoucher::whereBetween('date', [$startDate, $endDate])
            ->latest('date')
            ->get();

        $totalMaintenanceCost = $maintenances->sum('cost');
        $totalDebitVouchers = $vouchers->where('type', 'debit_voucher')->sum('amount');
        $totalCashMemos = $vouchers->where('type', 'cash_memo')->sum('amount');
        $totalMoneyReceipts = $vouchers->where('type', 'money_receipt')->sum('amount');
        $totalBills = $vouchers->where('type', 'bill')->sum('amount');

        return view('admin.store.reports', compact(
            'startDate',
            'endDate',
            'maintenances',
            'vouchers',
            'totalMaintenanceCost',
            'totalDebitVouchers',
            'totalCashMemos',
            'totalMoneyReceipts',
            'totalBills'
        ));
    }
}
