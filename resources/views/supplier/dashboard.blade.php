@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-truck text-primary me-2"></i> সাপ্লায়ার ড্যাশবোর্ড (Supplier Portal)
                    </h3>
                    <p class="text-muted mb-0">
                        স্বাগতম, <strong class="text-dark">{{ $user->supplierProfile->company_name ?? $user->name }}</strong>
                        @if(!empty($user->supplierProfile->supplier_code))
                            <span class="badge bg-primary rounded-pill ms-1">আইডি: {{ $user->supplierProfile->supplier_code }}</span>
                        @endif
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('supplier.supplies.create') }}" class="btn btn-success rounded-pill px-3 shadow-xs">
                        <i class="fa fa-plus-circle me-1"></i> নতুন পণ্য এন্ট্রি করুন
                    </a>
                    <a href="{{ route('supplier.statement') }}" class="btn btn-primary rounded-pill px-3 shadow-xs">
                        <i class="fa fa-file-text-o me-1"></i> স্টেটমেন্ট দেখুন
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Supplier Notifications -->
            @if(isset($notifications) && $notifications->count() > 0)
            <div class="mb-4">
                @foreach($notifications->take(3) as $notif)
                <div class="alert alert-{{ $notif->type ?? 'info' }} alert-dismissible fade show d-flex justify-content-between align-items-center flex-wrap gap-2 rounded-4 shadow-xs mb-2">
                    <div>
                        <strong><i class="fa fa-bell me-1"></i> {{ $notif->title }}:</strong> {{ $notif->message }}
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($notif->action_url)
                            <a href="{{ $notif->action_url }}" class="btn btn-xs btn-dark rounded-pill px-3">দেখুন</a>
                        @endif
                        <form action="{{ route('supplier.notifications.read', $notif->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-xs btn-outline-dark rounded-pill">পঠিত</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Overview Stat Cards (4 Cards - 100% Responsive Grid) -->
            <div class="row g-3 mb-4">
                <!-- Card 1: Total Supplies -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card border-0 shadow-xs rounded-4 bg-white mb-0 h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted fw-bold d-block text-uppercase fs-12">মোট চালান সংখ্যা</small>
                                <h3 class="fw-bold text-dark mb-1 mt-1">{{ number_format($totalSuppliesCount) }}</h3>
                                <div class="fs-12">
                                    <span class="text-warning fw-bold">{{ $pendingCount }} পেন্ডিং</span> |
                                    <span class="text-primary fw-bold">{{ $approvedAssignedCount }} অ্যাসাইনড</span> |
                                    <span class="text-success fw-bold">{{ $receivedCount }} রিসিভড</span>
                                </div>
                            </div>
                            <div class="bg-primary-light text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                                <i class="fa fa-truck fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Total Submitted vs Received Value -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card border-0 shadow-xs rounded-4 bg-white mb-0 h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted fw-bold d-block text-uppercase fs-12">প্রাপ্য সরবরাহ মূল্য (Payable)</small>
                                <h3 class="fw-bold text-info mb-1 mt-1">৳{{ number_format($totalSupplyAmount, 2) }}</h3>
                                <small class="text-muted fs-12">জমাকৃত: ৳{{ number_format($totalSubmittedAmount, 2) }}</small>
                            </div>
                            <div class="bg-info-light text-info rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                                <i class="fa fa-cubes fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Total Paid Amount -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card border-0 shadow-xs rounded-4 bg-white mb-0 h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted fw-bold d-block text-uppercase fs-12">মোট প্রাপ্ত পরিশোধ (Paid)</small>
                                <h3 class="fw-bold text-success mb-1 mt-1">৳{{ number_format($totalPaidAmount, 2) }}</h3>
                                <small class="text-muted fs-12">পরিশোধিত মোট অর্থ</small>
                            </div>
                            <div class="bg-success-light text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                                <i class="fa fa-check-circle fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Total Due Amount -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card border-0 shadow-xs rounded-4 bg-white mb-0 h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted fw-bold d-block text-uppercase fs-12">বর্তমান পাওনা / বকেয়া (Due)</small>
                                <h3 class="fw-bold text-danger mb-1 mt-1">৳{{ number_format($totalDue, 2) }}</h3>
                                <small class="text-muted fs-12">ঘাটতি: ৳{{ number_format($totalDiscrepancyAmount, 2) }}</small>
                            </div>
                            <div class="bg-danger-light text-danger rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                                <i class="fa fa-money fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="row g-4">
                <!-- Recent Product Supplies -->
                <div class="col-lg-8 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-header py-3 px-4 bg-light border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h5 class="box-title fw-bold text-dark mb-0">
                                <i class="fa fa-list text-primary me-2"></i> সাম্প্রতিক পণ্য সরবরাহ (Recent Supplies)
                            </h5>
                            <a href="{{ route('supplier.supplies.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                সব দেখুন <i class="fa fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-muted fs-12">
                                        <tr>
                                            <th class="ps-4">Supply ID</th>
                                            <th>পণ্য ও পরিমাণ</th>
                                            <th>মোট মূল্য</th>
                                            <th>গন্তব্য (Agent/Branch)</th>
                                            <th class="pe-4 text-center">স্ট্যাটাস</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentSupplies as $sup)
                                            @php $meta = $sup->status_meta; @endphp
                                            <tr>
                                                <td class="ps-4">
                                                    <a href="{{ route('supplier.supplies.show', $sup->id) }}" class="fw-bold text-primary text-decoration-none">
                                                        {{ $sup->supply_code ?: $sup->invoice_no }}
                                                    </a>
                                                    <div class="small text-muted">{{ date('d M, Y', strtotime($sup->supply_date)) }}</div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold">{{ $sup->product_name }}</div>
                                                    <small class="text-muted">{{ floatval($sup->quantity) }} {{ $sup->unit }}</small>
                                                </td>
                                                <td class="fw-bold text-success">৳{{ number_format($sup->total_amount, 2) }}</td>
                                                <td>
                                                    @if($sup->assignedBranch || $sup->assignedAgent)
                                                        <div class="fs-12 fw-bold">{{ $sup->assignedBranch->name ?? '-' }}</div>
                                                        <small class="text-muted">{{ $sup->assignedAgent->name ?? '-' }}</small>
                                                    @else
                                                        <span class="text-muted fs-12">অ্যাডমিন যাচাই</span>
                                                    @endif
                                                </td>
                                                <td class="pe-4 text-center">
                                                    <span class="badge bg-{{ $meta['badge'] }} rounded-pill px-3">{{ $meta['label'] }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5 text-muted">
                                                    কোনো পণ্য সরবরাহ রেকর্ড পাওয়া যায়নি।
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="col-lg-4 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-header py-3 px-4 bg-light border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h5 class="box-title fw-bold text-dark mb-0">
                                <i class="fa fa-history text-success me-2"></i> সাম্প্রতিক পেমেন্ট
                            </h5>
                            <a href="{{ route('supplier.statement') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                স্টেটমেন্ট <i class="fa fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-muted fs-12">
                                        <tr>
                                            <th class="ps-4">তারিখ</th>
                                            <th>মেথড</th>
                                            <th class="pe-4 text-end">টাকা (৳)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentPayments as $pay)
                                            <tr>
                                                <td class="ps-4">
                                                    <small class="text-muted">{{ date('d M, Y', strtotime($pay->payment_date)) }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">{{ strtoupper($pay->payment_method) }}</span>
                                                    @if($pay->bank_name)
                                                        <small class="text-muted d-block">{{ $pay->bank_name }}</small>
                                                    @endif
                                                </td>
                                                <td class="pe-4 text-end fw-bold text-success fs-14">
                                                    ৳{{ number_format($pay->amount, 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-5 text-muted">
                                                    কোনো পেমেন্ট রেকর্ড পাওয়া যায়নি।
                                                </td>
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
