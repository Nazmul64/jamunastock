@extends('layouts.agent.app')

@section('title', 'Customer Product Distribution & Daily Register - ' . $agentPoint->name)

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1"><i class="fa fa-share-square-o text-success me-2"></i>Customer Product Distribution & Daily Register</h4>
        <p class="text-muted small mb-0">{{ $agentPoint->name }} • গ্রাহকদের কাছে পণ্য বিতরণ ও দৈনিক স্টক রেজিস্টার</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('agent.distributions.customer_history') }}" class="btn btn-sm btn-outline-info">
            <i class="fa fa-user-circle me-1"></i> Customer History খুঁজুন
        </a>
        <a href="{{ route('agent.distributions.create') }}" class="btn btn-sm btn-success fw-bold">
            <i class="fa fa-plus-circle me-1"></i> নতুন Distribution Entry
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<!-- Today's Summary Cards (SRD Section 5) -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="dark-card p-3 mb-0">
            <small class="text-muted d-block">Today's Received Stock</small>
            <h4 class="fw-bold text-info mb-0 mt-1">{{ number_format($todayReceivedQty, 2) }}</h4>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="dark-card p-3 mb-0">
            <small class="text-muted d-block">Today's Distributed</small>
            <h4 class="fw-bold text-warning mb-0 mt-1">{{ number_format($todayDistributedQty, 2) }}</h4>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="dark-card p-3 mb-0">
            <small class="text-muted d-block">Current Stock</small>
            <h4 class="fw-bold text-success mb-0 mt-1">{{ number_format($currentTotalStock, 2) }}</h4>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="dark-card p-3 mb-0">
            <small class="text-muted d-block">Today's Customers</small>
            <h4 class="fw-bold text-white mb-0 mt-1">{{ $todayCustomersCount }}</h4>
        </div>
    </div>
</div>

<!-- Daily Product Distribution Register (SRD Section 4) -->
<div class="dark-card mb-4">
    <div class="dark-card-header">
        <h5 class="dark-card-title mb-0"><i class="fa fa-book text-warning me-2"></i>দৈনিক পণ্য স্টক ও বিতরণ রেজিস্টার (Daily Register - {{ date('d M, Y') }})</h5>
    </div>
    <div class="table-responsive">
        <table class="table dark-table mb-0">
            <thead>
                <tr>
                    <th>পণ্য (Product)</th>
                    <th class="text-end">Opening Stock</th>
                    <th class="text-end text-info">Received (+)</th>
                    <th class="text-end text-warning">Distributed (-)</th>
                    <th class="text-end text-success">Closing Stock</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dailyRegister as $reg)
                <tr>
                    <td class="fw-bold text-white">{{ $reg['product_name'] }}</td>
                    <td class="text-end">{{ number_format($reg['opening'], 2) }}</td>
                    <td class="text-end text-info fw-bold">+{{ number_format($reg['received'], 2) }}</td>
                    <td class="text-end text-warning fw-bold">-{{ number_format($reg['distributed'], 2) }}</td>
                    <td class="text-end text-success fw-bold">{{ number_format($reg['closing'], 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-3">কোনো স্টক তথ্য নেই।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Filter & Distribution List -->
<div class="dark-card">
    <div class="dark-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="dark-card-title mb-0"><i class="fa fa-list text-primary me-2"></i>Customer Distribution History</h5>
        <form method="GET" action="{{ route('agent.distributions.index') }}" class="d-flex gap-2 flex-wrap">
            <input type="text" name="search" class="form-control form-control-sm bg-dark text-white border-secondary" value="{{ request('search') }}" placeholder="DST ID / Customer / Phone">
            <select name="distribution_type" class="form-select form-select-sm bg-dark text-white border-secondary">
                <option value="">সকল ধরন</option>
                @foreach(\App\Models\CustomerDistribution::TYPE_LABELS as $k => $v)
                    <option value="{{ $k }}" {{ request('distribution_type') === $k ? 'selected' : '' }}>{{ $v['label'] }}</option>
                @endforeach
            </select>
            <input type="date" name="date" class="form-control form-control-sm bg-dark text-white border-secondary" value="{{ request('date') }}">
            <button type="submit" class="btn btn-sm btn-primary">ফিল্টার</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table dark-table mb-0">
            <thead>
                <tr>
                    <th>Distribution ID</th>
                    <th>তারিখ</th>
                    <th>Customer</th>
                    <th>পণ্যসমূহ</th>
                    <th>ধরন ও মূল্য</th>
                    <th>Payment</th>
                    <th class="text-end">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse($distributions as $dist)
                    @php
                        $tMeta = \App\Models\CustomerDistribution::TYPE_LABELS[$dist->distribution_type] ?? ['label' => $dist->distribution_type, 'badge' => 'secondary'];
                        $pMeta = \App\Models\CustomerDistribution::PAYMENT_LABELS[$dist->payment_status] ?? ['label' => $dist->payment_status, 'badge' => 'secondary'];
                    @endphp
                    <tr>
                        <td><strong class="text-info">{{ $dist->distribution_code }}</strong></td>
                        <td>{{ date('d M, Y', strtotime($dist->distribution_date)) }}</td>
                        <td>
                            <div class="text-white fw-bold">{{ $dist->customer_name }}</div>
                            <small class="text-muted">{{ $dist->customer_code ? $dist->customer_code . ' | ' : '' }}{{ $dist->customer_phone }}</small>
                        </td>
                        <td>
                            @foreach($dist->items as $it)
                                <div class="small">• {{ $it->product_name }}: <strong class="text-white">{{ floatval($it->quantity) }} {{ $it->unit }}</strong></div>
                            @endforeach
                        </td>
                        <td>
                            <span class="badge bg-{{ $tMeta['badge'] }}">{{ $tMeta['label'] }}</span>
                            @if($dist->total_amount > 0)
                                <div class="small text-success fw-bold mt-1">৳{{ number_format($dist->total_amount, 2) }}</div>
                            @endif
                        </td>
                        <td><span class="badge bg-{{ $pMeta['badge'] }}">{{ $pMeta['label'] }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('agent.distributions.show', $dist->id) }}" class="btn btn-xs btn-outline-info">রিসিট দেখুন</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">কোনো ডিস্ট্রিবিউশন রেকর্ড পাওয়া যায়নি।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $distributions->links() }}</div>
</div>
@endsection
