@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold mb-1">
                        <i class="fa fa-truck text-primary me-2"></i> সাপ্লায়ার সরবরাহ ব্যবস্থাপনা ও অ্যাপ্রুভাল (Supply Requests)
                    </h3>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.suppliers.index') }}">সাপ্লায়ার তালিকা</a></li>
                        <li class="breadcrumb-item active">সরবরাহ রিকোয়েস্ট</li>
                    </ol>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.suppliers.units.index') }}" class="btn btn-outline-info btn-sm rounded-pill px-3">
                        <i class="fa fa-balance-scale me-1"></i> ইউনিট ও ক্যাটাগরি
                    </a>
                    <a href="{{ route('admin.suppliers.audit_logs') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                        <i class="fa fa-shield me-1"></i> অডিট লগ
                    </a>
                    <a href="{{ route('admin.suppliers.reports') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                        <i class="fa fa-bar-chart me-1"></i> রিপোর্টস
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3">
                    <strong>সফল!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3">
                    <strong>নোটিশ:</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show rounded-3">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(isset($notifications) && $notifications->count() > 0)
                <div class="mb-4">
                    @foreach($notifications as $notif)
                        <div class="alert alert-warning border-start border-4 border-danger d-flex justify-content-between align-items-center flex-wrap gap-2 rounded-4 shadow-xs mb-2">
                            <div>
                                <strong><i class="fa fa-bell text-danger me-1"></i> {{ $notif->title }}:</strong> {{ $notif->message }}
                            </div>
                            <a href="{{ route('admin.suppliers.notifications.read', $notif->id) }}" class="btn btn-sm btn-dark rounded-pill px-3">
                                <i class="fa fa-eye me-1"></i> দেখুন ও পঠিত করুন
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Dashboard Statistics (Section 14) -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card border-0 shadow-xs rounded-4 bg-white mb-0">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted fw-bold">মোট সরবরাহ (Total Supplies)</small>
                                <h3 class="fw-bold mb-0 mt-1">{{ number_format($allStats->total_count ?? 0) }}</h3>
                                <small class="text-muted">৳{{ number_format($allStats->total_submitted_amount ?? 0, 2) }}</small>
                            </div>
                            <div class="bg-primary-light text-primary rounded-circle p-3"><i class="fa fa-cubes fs-20"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card border-0 shadow-xs rounded-4 bg-white mb-0">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted fw-bold">পেন্ডিং অনুমোদন (Pending)</small>
                                <h3 class="fw-bold text-warning mb-0 mt-1">{{ number_format($allStats->pending_count ?? 0) }}</h3>
                                <small class="text-muted">যাচাই ও অ্যাসাইন অপেক্ষমাণ</small>
                            </div>
                            <div class="bg-warning-light text-warning rounded-circle p-3"><i class="fa fa-clock-o fs-20"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card border-0 shadow-xs rounded-4 bg-white mb-0">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted fw-bold">এজেন্টে প্রেরিত (Assigned/Transit)</small>
                                <h3 class="fw-bold text-info mb-0 mt-1">{{ number_format($allStats->assigned_count ?? 0) }}</h3>
                                <small class="text-muted">ডেলিভারি চলমান</small>
                            </div>
                            <div class="bg-info-light text-info rounded-circle p-3"><i class="fa fa-truck fs-20"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card border-0 shadow-xs rounded-4 bg-white mb-0">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted fw-bold">রিসিভড ও ঘাটতি (Received)</small>
                                <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($allStats->received_count ?? 0) }}</h3>
                                <small class="text-success">রিসিভড: ৳{{ number_format($allStats->total_received_amount ?? 0, 2) }}</small>
                                @if(($allStats->discrepancy_count ?? 0) > 0)
                                    <small class="text-danger ms-1">({{ $allStats->discrepancy_count }} অমিল)</small>
                                @endif
                            </div>
                            <div class="bg-success-light text-success rounded-circle p-3"><i class="fa fa-check-circle fs-20"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Multi-Criteria Filter (Section 4.1) -->
            <div class="box border-0 shadow-xs rounded-4 mb-4">
                <div class="box-body p-3">
                    <form method="GET" action="{{ route('admin.suppliers.pending_supplies') }}" class="row g-2 align-items-end">
                        <div class="col-md-2 col-6">
                            <label class="form-label fs-12 fw-bold">Supply ID / ইনভয়েস</label>
                            <input type="text" name="supply_code" class="form-control form-control-sm" value="{{ request('supply_code') }}" placeholder="SUP-2026...">
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label fs-12 fw-bold">সাপ্লায়ার</label>
                            <select name="supplier_id" class="form-select form-select-sm">
                                <option value="">সকল সাপ্লায়ার</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->supplierProfile->company_name ?? $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label fs-12 fw-bold">পণ্য</label>
                            <input type="text" name="product_name" class="form-control form-control-sm" value="{{ request('product_name') }}" placeholder="পণ্যের নাম">
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label fs-12 fw-bold">স্ট্যাটাস</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">সকল স্ট্যাটাস</option>
                                @foreach(\App\Models\SupplierSupply::STATUS_META as $k => $m)
                                    <option value="{{ $k }}" {{ request('status') === $k ? 'selected' : '' }}>{{ $m['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label fs-12 fw-bold">এজেন্ট</label>
                            <select name="agent_id" class="form-select form-select-sm">
                                <option value="">সকল এজেন্ট</option>
                                @foreach($agents as $a)
                                    <option value="{{ $a->id }}" {{ request('agent_id') == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label fs-12 fw-bold">ব্রাঞ্চ</label>
                            <select name="branch_id" class="form-select form-select-sm">
                                <option value="">সকল ব্রাঞ্চ</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label fs-12 fw-bold">শুরু তারিখ</label>
                            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label fs-12 fw-bold">শেষ তারিখ</label>
                            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-2 col-12 d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-primary w-100"><i class="fa fa-filter me-1"></i> ফিল্টার</button>
                            <a href="{{ route('admin.suppliers.pending_supplies') }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-refresh"></i></a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Supplies Table -->
            <div class="box border-0 shadow-xs rounded-4">
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-3">Supply ID</th>
                                    <th>সাপ্লায়ার / প্রতিষ্ঠান</th>
                                    <th>ইনভয়েস ও তারিখ</th>
                                    <th>পণ্য ও পরিমাণ</th>
                                    <th>মূল্য (জমা / রিসিভ)</th>
                                    <th>নির্ধারিত এজেন্ট ও ব্রাঞ্চ</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end pe-3">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supplies as $sup)
                                    @php $meta = $sup->status_meta; @endphp
                                    <tr>
                                        <td class="ps-3">
                                            <a href="{{ route('admin.suppliers.supplies.show', $sup->id) }}" class="fw-bold text-primary text-decoration-none">
                                                {{ $sup->supply_code ?: ('#' . $sup->id) }}
                                            </a>
                                        </td>
                                        <td>
                                            <strong>{{ $sup->supplier->supplierProfile->company_name ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $sup->supplier->name ?? '' }} ({{ $sup->supplier->phone ?? '' }})</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $sup->invoice_no }}</div>
                                            <small class="text-muted">{{ date('d M, Y', strtotime($sup->supply_date)) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $sup->product_name }}</span>
                                            <div class="small mt-1">{{ floatval($sup->quantity) }} {{ $sup->unit }} @ ৳{{ number_format($sup->rate, 2) }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">৳{{ number_format($sup->total_amount, 2) }}</div>
                                            @if($sup->actual_received_amount > 0)
                                                <small class="text-success fw-bold">রিসিভ: ৳{{ number_format($sup->actual_received_amount, 2) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($sup->assignedAgent || $sup->assignedBranch)
                                                <div class="fs-12 fw-bold text-dark">{{ $sup->assignedBranch->name ?? '-' }}</div>
                                                <small class="text-muted">এজেন্ট: {{ $sup->assignedAgent->name ?? '-' }}</small>
                                            @else
                                                <span class="text-muted fs-12">অনির্ধারিত</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $meta['badge'] }} rounded-pill px-3">
                                                <i class="fa {{ $meta['icon'] }} me-1"></i> {{ $meta['label'] }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <a href="{{ route('admin.suppliers.supplies.show', $sup->id) }}" class="btn btn-sm btn-{{ $sup->status === 'pending' ? 'success' : 'primary' }} rounded-pill px-3">
                                                <i class="fa fa-{{ $sup->status === 'pending' ? 'check-square-o' : 'eye' }} me-1"></i>
                                                {{ $sup->status === 'pending' ? 'যাচাই ও অ্যাসাইন' : 'বিস্তারিত' }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">কোনো সরবরাহ রেকর্ড পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $supplies->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
