@extends('layouts.agent.app')

@section('title', 'ইনকামিং সাপ্লায়ার পণ্যসমূহ - ' . $agentPoint->name)

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1"><i class="fa fa-cubes text-info me-2"></i>অ্যাডমিন নির্ধারিত ইনকামিং সাপ্লাই (Incoming Supplies)</h4>
        <p class="text-muted small mb-0">{{ $agentPoint->name }} • সাপ্লায়ার থেকে সরাসরি আসা পণ্যের চালান যাচাই করে রিসিভ করুন</p>
    </div>
    <div>
        <form method="GET" action="{{ route('agent.supplies.index') }}" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm bg-dark text-white border-secondary" onchange="this.form.submit()">
                <option value="">সকল স্ট্যাটাস</option>
                @foreach(\App\Models\SupplierSupply::STATUS_META as $k => $m)
                    <option value="{{ $k }}" {{ request('status') === $k ? 'selected' : '' }}>{{ $m['label'] }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="dark-card">
    <div class="dark-card-header">
        <h5 class="dark-card-title"><i class="fa fa-list text-warning"></i> আমার পয়েন্টের অ্যাসাইনকৃত সরবরাহ তালিকা</h5>
    </div>

    @if($supplies->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fa fa-inbox fs-1 mb-2"></i>
            <p>আপনার পয়েন্টে কোনো ইনকামিং সাপ্লাই অ্যাসাইন করা নেই।</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table dark-table">
                <thead>
                    <tr>
                        <th>Supply ID</th>
                        <th>সাপ্লায়ার ও ইনভয়েস</th>
                        <th>পণ্য ও অনুমোদিত Qty</th>
                        <th>সম্ভাব্য তারিখ ও ব্রাঞ্চ</th>
                        <th>স্ট্যাটাস</th>
                        <th class="text-end">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supplies as $sup)
                        @php $meta = $sup->status_meta; @endphp
                        <tr>
                            <td><strong class="text-info">{{ $sup->supply_code ?: ('#' . $sup->id) }}</strong></td>
                            <td>
                                <div class="text-white fw-bold">{{ $sup->supplier->supplierProfile->company_name ?? $sup->supplier->name }}</div>
                                <small class="text-muted">ইনভয়েস: {{ $sup->invoice_no }}</small>
                            </td>
                            <td>
                                <div class="text-white fw-bold">{{ $sup->product_name }}</div>
                                <small class="text-success">{{ floatval($sup->approved_quantity ?? $sup->quantity) }} {{ $sup->unit }}</small>
                            </td>
                            <td>
                                <div>{{ $sup->assignedBranch->name ?? 'N/A' }}</div>
                                <small class="text-warning">{{ $sup->expected_delivery_date ? $sup->expected_delivery_date->format('d M, Y') : 'তারিখ অনির্ধারিত' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $meta['badge'] }} rounded-pill px-3">{{ $meta['label'] }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('agent.supplies.show', $sup->id) }}" class="btn btn-sm btn-{{ in_array($sup->status, ['assigned', 'in_transit', 'partially_received']) ? 'success' : 'outline-info' }} fw-bold">
                                    <i class="fa fa-{{ in_array($sup->status, ['assigned', 'in_transit', 'partially_received']) ? 'check-square-o' : 'eye' }} me-1"></i>
                                    {{ in_array($sup->status, ['assigned', 'in_transit', 'partially_received']) ? 'যাচাই ও রিসিভ' : 'বিস্তারিত' }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $supplies->links() }}</div>
    @endif
</div>
@endsection
