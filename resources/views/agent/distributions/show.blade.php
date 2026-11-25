@extends('layouts.agent.app')

@section('title', 'Distribution Receipt - ' . $distribution->distribution_code)

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 no-print">
    <div>
        <h4 class="fw-bold text-white mb-1"><i class="fa fa-file-text text-info me-2"></i>Distribution Receipt: {{ $distribution->distribution_code }}</h4>
        <p class="text-muted small mb-0">{{ $agentPoint->name }} • {{ date('d M, Y', strtotime($distribution->distribution_date)) }}</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-sm btn-primary"><i class="fa fa-print me-1"></i> প্রিন্ট করুন</button>
        <a href="{{ route('agent.distributions.index') }}" class="btn btn-sm btn-outline-light">ফিরে যান</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show no-print">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="dark-card p-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h6 class="text-info fw-bold">গ্রাহকের তথ্য (Customer)</h6>
            <p class="mb-1 text-white"><strong>{{ $distribution->customer_name }}</strong></p>
            @if($distribution->customer_code)<p class="mb-1 small text-muted">Customer ID: {{ $distribution->customer_code }}</p>@endif
            @if($distribution->customer_phone)<p class="mb-0 small text-muted">Phone: {{ $distribution->customer_phone }}</p>@endif
        </div>
        <div class="col-md-6 text-md-end">
            <h6 class="text-info fw-bold">বিতরণ তথ্য</h6>
            <p class="mb-1"><strong>Distribution ID:</strong> {{ $distribution->distribution_code }}</p>
            <p class="mb-1 small"><strong>তারিখ:</strong> {{ date('d M, Y', strtotime($distribution->distribution_date)) }}</p>
            <p class="mb-0 small"><strong>এজেন্ট / পয়েন্ট:</strong> {{ $distribution->agent->name ?? '' }} ({{ $agentPoint->name }})</p>
        </div>
    </div>

    <div class="table-responsive mb-4">
        <table class="table dark-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>পণ্য (Product)</th>
                    <th class="text-center">পরিমাণ</th>
                    <th class="text-end">রেট (৳)</th>
                    <th class="text-end">মোট মূল্য (৳)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($distribution->items as $idx => $item)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td class="text-white fw-bold">{{ $item->product_name }}</td>
                    <td class="text-center text-info fw-bold">{{ floatval($item->quantity) }} {{ $item->unit }}</td>
                    <td class="text-end">৳{{ number_format($item->rate, 2) }}</td>
                    <td class="text-end fw-bold text-success">৳{{ number_format($item->total_amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-end fw-bold text-white">সর্বমোট পরিমাণ ও মূল্য:</td>
                    <td class="text-center fw-bold text-info">{{ floatval($distribution->total_quantity) }}</td>
                    <td></td>
                    <td class="text-end fw-bold text-success fs-5">৳{{ number_format($distribution->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="d-flex justify-content-between flex-wrap gap-2 small text-muted">
        <div>
            <strong>ধরন:</strong> {{ \App\Models\CustomerDistribution::TYPE_LABELS[$distribution->distribution_type]['label'] ?? $distribution->distribution_type }} |
            <strong>Payment:</strong> {{ \App\Models\CustomerDistribution::PAYMENT_LABELS[$distribution->payment_status]['label'] ?? $distribution->payment_status }}
        </div>
        @if($distribution->note)
            <div><strong>নোট:</strong> {{ $distribution->note }}</div>
        @endif
    </div>
</div>
@endsection
