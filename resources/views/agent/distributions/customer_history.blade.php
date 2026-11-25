@extends('layouts.agent.app')

@section('title', 'Customer Product Receiving History - ' . $agentPoint->name)

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1"><i class="fa fa-history text-info me-2"></i>Customer Product Receiving History</h4>
        <p class="text-muted small mb-0">Customer ID, মোবাইল নম্বর বা নাম দিয়ে যেকোনো গ্রাহকের সম্পূর্ণ পণ্য গ্রহণের ইতিহাস দেখুন</p>
    </div>
    <a href="{{ route('agent.distributions.index') }}" class="btn btn-sm btn-outline-light">ফিরে যান</a>
</div>

<div class="dark-card p-4 mb-4">
    <form method="GET" action="{{ route('agent.distributions.customer_history') }}" class="row g-2 align-items-center">
        <div class="col-md-9 col-12">
            <input type="text" name="search" class="form-control bg-dark text-white border-secondary" value="{{ $search }}" placeholder="Customer ID (যেমন: CUS-00125), মোবাইল নম্বর বা নাম লিখুন..." required>
        </div>
        <div class="col-md-3 col-12">
            <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fa fa-search me-1"></i> ইতিহাস খুঁজুন</button>
        </div>
    </form>
</div>

@if($search)
    @if($distributions->isEmpty())
        <div class="dark-card p-5 text-center text-muted">
            "{{ $search }}" এর জন্য কোনো ডিস্ট্রিবিউশন ইতিহাস পাওয়া যায়নি।
        </div>
    @else
        <!-- Product-wise Cumulative Summary -->
        <div class="dark-card mb-4">
            <div class="dark-card-header">
                <h5 class="dark-card-title mb-0"><i class="fa fa-cubes text-success me-2"></i>পণ্যভিত্তিক মোট গ্রহণ ({{ $distributions->first()->customer_name }})</h5>
            </div>
            <div class="p-3 d-flex flex-wrap gap-3">
                @foreach($productTotals as $pt)
                    <div class="p-3 rounded bg-dark border border-secondary">
                        <small class="text-muted d-block">{{ $pt['product_name'] }}</small>
                        <strong class="text-info fs-5">{{ floatval($pt['total_qty']) }} {{ $pt['unit'] }}</strong>
                        @if($pt['total_amount'] > 0)
                            <div class="small text-success">৳{{ number_format($pt['total_amount'], 2) }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Chronological History -->
        <div class="dark-card">
            <div class="dark-card-header">
                <h5 class="dark-card-title mb-0"><i class="fa fa-list text-warning me-2"></i>তারিখ অনুযায়ী গ্রহণের বিবরণ ({{ $distributions->count() }} বার)</h5>
            </div>
            <div class="table-responsive">
                <table class="table dark-table mb-0">
                    <thead>
                        <tr>
                            <th>Distribution ID</th>
                            <th>তারিখ</th>
                            <th>পণ্য ও পরিমাণ</th>
                            <th>ধরন ও মূল্য</th>
                            <th>Payment</th>
                            <th class="text-end">রিসিট</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($distributions as $d)
                        <tr>
                            <td><strong class="text-info">{{ $d->distribution_code }}</strong></td>
                            <td>{{ date('d M, Y', strtotime($d->distribution_date)) }}</td>
                            <td>
                                @foreach($d->items as $it)
                                    <span class="badge bg-secondary me-1">{{ $it->product_name }}: {{ floatval($it->quantity) }} {{ $it->unit }}</span>
                                @endforeach
                            </td>
                            <td>৳{{ number_format($d->total_amount, 2) }} ({{ $d->distribution_type }})</td>
                            <td><span class="badge bg-info">{{ $d->payment_status }}</span></td>
                            <td class="text-end"><a href="{{ route('agent.distributions.show', $d->id) }}" class="btn btn-xs btn-outline-info">দেখুন</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endif
@endsection
