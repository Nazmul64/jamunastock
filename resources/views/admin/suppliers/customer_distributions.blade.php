@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold mb-1">
                        <i class="fa fa-users text-primary me-2"></i> Customer Product Distribution Monitoring
                    </h3>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">ড্যাশবোর্ড</a></li>
                        <li class="breadcrumb-item active">Customer Distribution</li>
                    </ol>
                </div>
                <a href="{{ route('admin.suppliers.master_dashboard') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    <i class="fa fa-line-chart me-1"></i> Master Accounting & Stock
                </a>
            </div>
        </div>

        <section class="content">
            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="bg-white border rounded-4 p-3 shadow-xs">
                        <small class="text-muted fw-bold">মোট বিতরণকৃত পরিমাণ</small>
                        <h3 class="fw-bold text-primary mb-0 mt-1">{{ number_format($totalQty, 2) }}</h3>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="bg-white border rounded-4 p-3 shadow-xs">
                        <small class="text-muted fw-bold">বিনামূল্যে বিতরণ (Free)</small>
                        <h3 class="fw-bold text-success mb-0 mt-1">{{ $freeCount }} টি</h3>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="bg-white border rounded-4 p-3 shadow-xs">
                        <small class="text-muted fw-bold">বিক্রয় / Paid / Credit</small>
                        <h3 class="fw-bold text-info mb-0 mt-1">{{ $paidCount }} টি</h3>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="bg-white border rounded-4 p-3 shadow-xs">
                        <small class="text-muted fw-bold">মোট বিক্রয় মূল্য</small>
                        <h3 class="fw-bold text-dark mb-0 mt-1">৳{{ number_format($totalAmount, 2) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white border rounded-4 p-3 shadow-xs mb-4">
                <form method="GET" action="{{ route('admin.suppliers.customer_distributions') }}" class="row g-2 align-items-end">
                    <div class="col-md-2 col-6">
                        <label class="form-label fs-12 fw-bold">Distribution ID</label>
                        <input type="text" name="distribution_code" class="form-control form-control-sm" value="{{ request('distribution_code') }}" placeholder="DST-2026...">
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
                        <label class="form-label fs-12 fw-bold">গ্রাহক / মোবাইল</label>
                        <input type="text" name="customer" class="form-control form-control-sm" value="{{ request('customer') }}" placeholder="নাম/ID/ফোন">
                    </div>
                    <div class="col-md-2 col-6">
                        <label class="form-label fs-12 fw-bold">পণ্য</label>
                        <input type="text" name="product" class="form-control form-control-sm" value="{{ request('product') }}" placeholder="চাল, ডাল...">
                    </div>
                    <div class="col-md-2 col-6">
                        <label class="form-label fs-12 fw-bold">বিতরণের ধরন</label>
                        <select name="distribution_type" class="form-select form-select-sm">
                            <option value="">সকল ধরন</option>
                            @foreach(\App\Models\CustomerDistribution::TYPE_LABELS as $k => $v)
                                <option value="{{ $k }}" {{ request('distribution_type') === $k ? 'selected' : '' }}>{{ $v['label'] }}</option>
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
                        <button type="submit" class="btn btn-sm btn-primary w-100">ফিল্টার</button>
                        <a href="{{ route('admin.suppliers.customer_distributions') }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-refresh"></i></a>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white border rounded-4 shadow-xs overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3">Distribution ID</th>
                                <th>তারিখ</th>
                                <th>এজেন্ট ও ব্রাঞ্চ</th>
                                <th>Customer</th>
                                <th>পণ্য ও পরিমাণ</th>
                                <th>ধরন ও মূল্য</th>
                                <th class="pe-3">Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($distributions as $d)
                                @php
                                    $tMeta = \App\Models\CustomerDistribution::TYPE_LABELS[$d->distribution_type] ?? ['label' => $d->distribution_type, 'badge' => 'secondary'];
                                    $pMeta = \App\Models\CustomerDistribution::PAYMENT_LABELS[$d->payment_status] ?? ['label' => $d->payment_status, 'badge' => 'secondary'];
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-bold text-primary">{{ $d->distribution_code }}</td>
                                    <td>{{ date('d M, Y', strtotime($d->distribution_date)) }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $d->agent->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $d->branch->name ?? ($d->agentPoint->name ?? '') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $d->customer_name }}</div>
                                        <small class="text-muted">{{ $d->customer_code ? $d->customer_code . ' | ' : '' }}{{ $d->customer_phone }}</small>
                                    </td>
                                    <td>
                                        @foreach($d->items as $it)
                                            <span class="badge bg-light text-dark border me-1">{{ $it->product_name }}: <strong>{{ floatval($it->quantity) }} {{ $it->unit }}</strong></span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $tMeta['badge'] }}">{{ $tMeta['label'] }}</span>
                                        @if($d->total_amount > 0)
                                            <div class="small fw-bold text-success mt-1">৳{{ number_format($d->total_amount, 2) }}</div>
                                        @endif
                                    </td>
                                    <td class="pe-3"><span class="badge bg-{{ $pMeta['badge'] }}">{{ $pMeta['label'] }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center py-5 text-muted">কোনো গ্রাহক বিতরণ রেকর্ড পাওয়া যায়নি।</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $distributions->links() }}</div>
            </div>
        </section>
    </div>
</div>
@endsection
