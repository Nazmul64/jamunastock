@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-cubes text-success me-2"></i> কোম্পানির স্টোর ও সম্পদ ব্যবস্থাপনা ERP (Store & Asset ERP)
                    </h3>
                    <p class="text-muted mb-0">স্থায়ী সম্পদ, স্টোর ও ওয়্যারহাউস, ইনভেন্টরি স্টক লেজার, গাড়ি ও পরিবহন, ওয়ার্ক অর্ডার, অবচয় ও অডিট</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.store.items.create') }}" class="btn btn-primary shadow-sm">
                        <i class="fa fa-plus-circle me-1"></i> নতুন পণ্য
                    </a>
                    <a href="{{ route('admin.store.assets.create') }}" class="btn btn-success shadow-sm">
                        <i class="fa fa-plus-circle me-1"></i> নতুন স্থায়ী সম্পদ
                    </a>
                    <a href="{{ route('admin.store.reports') }}" class="btn btn-outline-info shadow-sm">
                        <i class="fa fa-file-text-o me-1"></i> সার্বিক রিপোর্ট
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(isset($vehicleExpiringDocsCount) && $vehicleExpiringDocsCount > 0)
                <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <i class="fa fa-exclamation-triangle fa-lg me-2 text-warning"></i>
                        <strong>যানবাহন নবায়ন সতর্কতা:</strong> আগামী ৩০ দিনের মধ্যে <strong>{{ $vehicleExpiringDocsCount }}</strong> টি যানবাহনের ফিটনেস, ট্যাক্স টোকেন বা বীমার মেয়াদ শেষ হচ্ছে!
                    </div>
                    <a href="{{ route('admin.store.vehicles.index') }}" class="btn btn-sm btn-warning text-dark fw-bold">গাড়ি তালিকা দেখুন</a>
                </div>
            @endif

            @if(isset($lowStockCount) && $lowStockCount > 0)
                <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <i class="fa fa-bell fa-lg me-2 text-danger"></i>
                        <strong>ইনভেন্টরি লো-স্টক এলার্ট:</strong> <strong>{{ $lowStockCount }}</strong> টি আইটেমের মজুদ রিকুইজিশন থ্রেশহোল্ডের নিচে নেমে গেছে!
                    </div>
                    <a href="{{ route('admin.store.items.index') }}" class="btn btn-sm btn-danger fw-bold">স্টক চেক করুন</a>
                </div>
            @endif

            <!-- 1. Top Valuation & Primary ERP Stats Row -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card shadow-sm border-0 border-start border-success border-4 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted fw-bold d-block mb-1">স্থায়ী সম্পদের বর্তমান মূল্য</span>
                                    <h3 class="mb-0 fw-bold text-success">৳{{ number_format($totalValuation, 2) }}</h3>
                                    <small class="text-muted">ক্রয়মূল্য: ৳{{ number_format($totalPurchaseCost, 2) }}</small>
                                </div>
                                <div class="bg-success-light rounded-circle p-3 text-success">
                                    <i class="fa fa-database fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card shadow-sm border-0 border-start border-primary border-4 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted fw-bold d-block mb-1">চলতি ইনভেন্টরি স্টক মূল্য</span>
                                    <h3 class="mb-0 fw-bold text-primary">৳{{ number_format($totalStockValuation ?? 0, 2) }}</h3>
                                    <small class="text-muted">{{ $totalItemsCount ?? 0 }} টি আইটেম | স্টক: {{ number_format($totalStockQty ?? 0) }}</small>
                                </div>
                                <div class="bg-primary-light rounded-circle p-3 text-primary">
                                    <i class="fa fa-boxes fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card shadow-sm border-0 border-start border-warning border-4 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted fw-bold d-block mb-1">মোট যানবাহন ও বহর</span>
                                    <h3 class="mb-0 fw-bold text-warning">{{ $vehiclesCount ?? $vehicleCount }} টি যান</h3>
                                    <small class="text-muted">সক্রিয়: {{ $activeVehiclesCount ?? 0 }} | ট্রিপে আছে: {{ $inTripVehiclesCount ?? 0 }}</small>
                                </div>
                                <div class="bg-warning-light rounded-circle p-3 text-warning">
                                    <i class="fa fa-truck fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card shadow-sm border-0 border-start border-danger border-4 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted fw-bold d-block mb-1">মেইনটেনেন্স ব্যয় ও WO</span>
                                    <h3 class="mb-0 fw-bold text-danger">৳{{ number_format($totalMaintenanceCost ?? 0, 2) }}</h3>
                                    <small class="text-muted">মোট WO: {{ $totalWorkOrdersCount ?? 0 }} | চলমান: {{ $pendingWorkOrdersCount ?? 0 }}</small>
                                </div>
                                <div class="bg-danger-light rounded-circle p-3 text-danger">
                                    <i class="fa fa-wrench fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. ERP Quick Module Navigation Cards -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold text-dark mb-0">
                        <i class="fa fa-th-large text-primary me-2"></i> স্টোর ও সম্পদ ব্যবস্থাপনা ERP মডিউলসমূহ
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('admin.store.stores.index') }}" class="card text-center p-3 text-decoration-none border shadow-none hover-shadow transition h-100">
                                <div class="text-primary mb-2"><i class="fa fa-building fa-2x"></i></div>
                                <h6 class="fw-bold text-dark mb-1">স্টোর ও লোকেশন</h6>
                                <small class="text-muted">{{ $storesCount ?? 1 }} টি ওয়্যারহাউস</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('admin.store.items.index') }}" class="card text-center p-3 text-decoration-none border shadow-none hover-shadow transition h-100">
                                <div class="text-success mb-2"><i class="fa fa-boxes fa-2x"></i></div>
                                <h6 class="fw-bold text-dark mb-1">পণ্য মাস্টার তালিকা</h6>
                                <small class="text-muted">{{ $totalItemsCount ?? 0 }} টি আইটেম</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('admin.store.items.movements') }}" class="card text-center p-3 text-decoration-none border shadow-none hover-shadow transition h-100">
                                <div class="text-info mb-2"><i class="fa fa-exchange fa-2x"></i></div>
                                <h6 class="fw-bold text-dark mb-1">স্টক ইন / আউট</h6>
                                <small class="text-muted">লেজার ও রানিং ব্যালেন্স</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('admin.store.assets.index') }}" class="card text-center p-3 text-decoration-none border shadow-none hover-shadow transition h-100">
                                <div class="text-warning mb-2"><i class="fa fa-archive fa-2x"></i></div>
                                <h6 class="fw-bold text-dark mb-1">স্থায়ী সম্পদ রেজিস্ট্রি</h6>
                                <small class="text-muted">{{ $totalAssets }} টি সম্পদ</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('admin.store.vehicles.index') }}" class="card text-center p-3 text-decoration-none border shadow-none hover-shadow transition h-100">
                                <div class="text-danger mb-2"><i class="fa fa-car fa-2x"></i></div>
                                <h6 class="fw-bold text-dark mb-1">যানবাহন ও বহর</h6>
                                <small class="text-muted">লগ, ফুয়েল ও নবায়ন</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('admin.store.work_orders.index') }}" class="card text-center p-3 text-decoration-none border shadow-none hover-shadow transition h-100">
                                <div class="text-secondary mb-2"><i class="fa fa-wrench fa-2x"></i></div>
                                <h6 class="fw-bold text-dark mb-1">ওয়ার্ক অর্ডার ও AMC</h6>
                                <small class="text-muted">মেরামত ও সার্ভিসিং</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('admin.store.depreciations.index') }}" class="card text-center p-3 text-decoration-none border shadow-none hover-shadow transition h-100">
                                <div class="text-dark mb-2"><i class="fa fa-calculator fa-2x"></i></div>
                                <h6 class="fw-bold text-dark mb-1">সম্পদের অবচয় হিসাব</h6>
                                <small class="text-muted">Straight-Line & Declining</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('admin.store.audits.index') }}" class="card text-center p-3 text-decoration-none border shadow-none hover-shadow transition h-100">
                                <div class="text-primary mb-2"><i class="fa fa-clipboard-check fa-2x"></i></div>
                                <h6 class="fw-bold text-dark mb-1">স্টক অডিট ভেরিফিকেশন</h6>
                                <small class="text-muted">শারীরিক গণনা ও Variance</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('admin.store.disposals.index') }}" class="card text-center p-3 text-decoration-none border shadow-none hover-shadow transition h-100">
                                <div class="text-danger mb-2"><i class="fa fa-trash fa-2x"></i></div>
                                <h6 class="fw-bold text-dark mb-1">ক্ষতি ও স্ক্র্যাপ নিলাম</h6>
                                <small class="text-muted">Damage & Disposal</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('admin.store.vouchers.index') }}" class="card text-center p-3 text-decoration-none border shadow-none hover-shadow transition h-100">
                                <div class="text-success mb-2"><i class="fa fa-file-text-o fa-2x"></i></div>
                                <h6 class="fw-bold text-dark mb-1">বিল ও ভাউচার</h6>
                                <small class="text-muted">পেমেন্ট ও রিসিপ্ট</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('admin.store.vouchers.cash_memos') }}" class="card text-center p-3 text-decoration-none border shadow-none hover-shadow transition h-100">
                                <div class="text-warning mb-2"><i class="fa fa-money fa-2x"></i></div>
                                <h6 class="fw-bold text-dark mb-1">ক্যাশ মেমো ও রিসিট</h6>
                                <small class="text-muted">নগদ লেনদেন ও প্রিন্ট</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('admin.store.reports') }}" class="card text-center p-3 text-decoration-none border shadow-none hover-shadow transition h-100">
                                <div class="text-info mb-2"><i class="fa fa-bar-chart fa-2x"></i></div>
                                <h6 class="fw-bold text-dark mb-1">সার্বিক ERP রিপোর্ট</h6>
                                <small class="text-muted">স্টক ও ব্যয় অ্যানালিটিক্স</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Tables Row: Recent Stock Movements & Recent Maintenance Work Orders -->
            <div class="row g-4 mb-4">
                <!-- Recent Stock Movements -->
                <div class="col-lg-7 col-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold text-dark mb-0">
                                <i class="fa fa-exchange text-primary me-2"></i> সাম্প্রতিক স্টক ইন / আউট মুভমেন্ট
                            </h5>
                            <a href="{{ route('admin.store.items.movements') }}" class="btn btn-sm btn-outline-primary">সবগুলো দেখুন</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>তারিখ</th>
                                            <th>আইটেম ও স্টোর</th>
                                            <th>ধরন</th>
                                            <th>পরিমাণ</th>
                                            <th>ব্যালেন্স</th>
                                            <th>গ্রহীতা / রেফারেন্স</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentMovements as $mov)
                                            <tr>
                                                <td><small class="text-muted">{{ \Carbon\Carbon::parse($mov->movement_date)->format('d M, Y') }}</small></td>
                                                <td>
                                                    <strong class="text-dark d-block">{{ $mov->item->name ?? 'N/A' }}</strong>
                                                    <small class="text-muted">{{ $mov->store->name ?? 'Central Store' }}</small>
                                                </td>
                                                <td>
                                                    @if($mov->movement_type == 'in')
                                                        <span class="badge bg-success-light text-success fw-bold">Stock In</span>
                                                    @elseif($mov->movement_type == 'out')
                                                        <span class="badge bg-danger-light text-danger fw-bold">Stock Out</span>
                                                    @else
                                                        <span class="badge bg-info-light text-info fw-bold">{{ ucfirst($mov->movement_type) }}</span>
                                                    @endif
                                                </td>
                                                <td class="fw-bold {{ $mov->movement_type == 'in' ? 'text-success' : 'text-danger' }}">
                                                    {{ $mov->movement_type == 'in' ? '+' : '-' }}{{ $mov->quantity }} {{ $mov->item->unit ?? '' }}
                                                </td>
                                                <td><span class="badge bg-dark">{{ $mov->running_balance }}</span></td>
                                                <td>
                                                    <small class="text-muted">{{ $mov->issued_to ?? $mov->reference_no ?? '—' }}</small>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">কোনো স্টক মুভমেন্ট রেকর্ড নেই</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Work Orders -->
                <div class="col-lg-5 col-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold text-dark mb-0">
                                <i class="fa fa-wrench text-warning me-2"></i> সাম্প্রতিক মেইনটেনেন্স ওয়ার্ক অর্ডার
                            </h5>
                            <a href="{{ route('admin.store.work_orders.index') }}" class="btn btn-sm btn-outline-warning">সবগুলো দেখুন</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>WO ও শিরোনাম</th>
                                            <th>ক্যাটেগরি</th>
                                            <th>খরচ (৳)</th>
                                            <th>স্ট্যাটাস</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentWorkOrders as $wo)
                                            <tr>
                                                <td>
                                                    <strong class="text-dark d-block">{{ Str::limit($wo->title, 22) }}</strong>
                                                    <small class="text-muted font-monospace">{{ $wo->work_order_no }}</small>
                                                </td>
                                                <td><span class="badge bg-light text-dark border text-uppercase">{{ $wo->category }}</span></td>
                                                <td class="fw-bold text-danger">৳{{ number_format($wo->total_cost, 2) }}</td>
                                                <td>
                                                    @if($wo->status == 'completed')
                                                        <span class="badge bg-success">সম্পন্ন</span>
                                                    @elseif($wo->status == 'in_progress')
                                                        <span class="badge bg-primary">চলমান</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">{{ ucfirst($wo->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">কোনো ওয়ার্ক অর্ডার রেকর্ড নেই</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Fixed Assets & Vouchers Row -->
            <div class="row g-4 mb-4">
                <div class="col-lg-6 col-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold text-dark mb-0">
                                <i class="fa fa-archive text-primary me-2"></i> সাম্প্রতিক স্থায়ী সম্পদ
                            </h5>
                            <a href="{{ route('admin.store.assets.index') }}" class="btn btn-sm btn-outline-primary">সকল সম্পদ</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>কোড ও নাম</th>
                                            <th>ক্যাটাগরি</th>
                                            <th>মূল্য (৳)</th>
                                            <th>অবস্থান / বরাদ্দ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentAssets as $asset)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.store.assets.show', $asset->id) }}" class="fw-bold text-dark text-decoration-none">
                                                        <span class="badge bg-secondary me-1">{{ $asset->asset_code }}</span>
                                                        {{ Str::limit($asset->name, 22) }}
                                                    </a>
                                                </td>
                                                <td><span class="badge bg-light text-dark border">{{ $asset->category_label }}</span></td>
                                                <td class="fw-bold text-success">৳{{ number_format($asset->current_value, 2) }}</td>
                                                <td><small class="text-muted">{{ $asset->assigned_to ?: ($asset->location ?: 'স্টোর') }}</small></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">কোনো সম্পদ পাওয়া যায়নি</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold text-dark mb-0">
                                <i class="fa fa-car text-success me-2"></i> কোম্পানির যানবাহন ও পরিবহন বহর
                            </h5>
                            <a href="{{ route('admin.store.vehicles.index') }}" class="btn btn-sm btn-outline-success">সকল গাড়ি</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>গাড়ির নম্বর</th>
                                            <th>ধরন ও ব্র্যান্ড</th>
                                            <th>ড্রাইভার</th>
                                            <th>ওডোমিটার</th>
                                            <th>স্ট্যাটাস</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentVehicles as $veh)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.store.vehicles.show', $veh->id) }}" class="fw-bold text-dark text-decoration-none">
                                                        {{ $veh->vehicle_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $veh->brand }} ({{ ucfirst($veh->vehicle_type) }})</td>
                                                <td>{{ $veh->assigned_driver ?? '—' }}</td>
                                                <td class="fw-bold">{{ number_format($veh->current_odometer) }} KM</td>
                                                <td>
                                                    @if($veh->status == 'active')
                                                        <span class="badge bg-success">Active</span>
                                                    @elseif($veh->status == 'in_trip')
                                                        <span class="badge bg-info">In Trip</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">{{ ucfirst($veh->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">কোনো যানবাহন পাওয়া যায়নি</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
