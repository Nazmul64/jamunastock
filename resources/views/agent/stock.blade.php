@extends('layouts.agent.app')

@section('title', 'পণ্য স্টক ও ইনভেন্টরি লেজার - ' . $agentPoint->name)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1"><i class="fa fa-cubes text-success me-2"></i>লোকাল পণ্য স্টক ও ইনভেন্টরি লেজার</h4>
        <p class="text-muted small mb-0">{{ $agentPoint->name }} • {{ $agentPoint->area }}</p>
    </div>
    <span class="status-pill success"><i class="fa fa-check"></i> লাইভ ব্যালেন্স</span>
</div>

<!-- Stock Formula Box (Section 15) -->
<div class="dark-card mb-4" style="background: rgba(30, 41, 59, 0.5); border-left: 4px solid #10b981;">
    <div class="d-flex align-items-center gap-3">
        <i class="fa fa-calculator fs-3 text-success"></i>
        <div>
            <h6 class="text-white fw-bold mb-1">স্টক হিসাব সূত্র (Stock Calculation Formula):</h6>
            <code class="text-info fw-bold" style="font-size: 13.5px; background: transparent;">
                Closing Stock = Opening Stock + Received Stock - Delivered Stock - Damage / Loss ± Adjustment
            </code>
        </div>
    </div>
</div>

<!-- Stock Overview Grid -->
<div class="row mb-4">
    @forelse($stocks as $stk)
    @php
        $alloc = floatval($stk->allocated_quantity);
        $sold = floatval($stk->sold_quantity);
        $curr = max(0, $alloc - $sold);
    @endphp
    <div class="col-lg-3 col-md-6 col-12 mb-3">
        <div class="dark-card h-100 p-3" style="border: 1px solid rgba(255, 255, 255, 0.08);">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-primary bg-opacity-25 text-primary fw-bold">{{ $stk->stock_name }}</span>
                <span class="status-pill {{ $curr > 0 ? 'success' : 'danger' }}">{{ $curr > 0 ? 'In Stock' : 'Stock Out' }}</span>
            </div>
            <h3 class="fw-bold text-white mb-2" style="font-family: 'Sora', sans-serif;">{{ number_format($curr, 1) }} <small class="fs-6 text-muted">KG</small></h3>
            <div class="small text-muted pt-2 border-top border-secondary d-flex justify-content-between">
                <span>রিসিভ: {{ number_format($alloc, 1) }}</span>
                <span>বিতরণ: {{ number_format($sold, 1) }}</span>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="dark-card text-center py-4 text-muted">স্টকে এখনো কোনো পণ্য এন্ট্রি করা হয়নি।</div>
    </div>
    @endforelse
</div>

<!-- Stock Transactions Ledger -->
<div class="dark-card">
    <div class="dark-card-header">
        <h5 class="dark-card-title"><i class="fa fa-book text-warning"></i>স্টক ট্রানজ্যাকশন লেজার (Stock Ledger)</h5>
    </div>

    @if($transactions->isEmpty())
        <div class="text-center py-4 text-muted">কোনো স্টক মুভমেন্ট রেকর্ড পাওয়া যায়নি।</div>
    @else
        <div class="table-responsive">
            <table class="table dark-table">
                <thead>
                    <tr>
                        <th>তারিখ</th>
                        <th>পণ্যের নাম</th>
                        <th>ট্রানজ্যাকশন ধরন</th>
                        <th>পরিমাণ</th>
                        <th>রেফারেন্স / নোট</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $t)
                    <tr>
                        <td>{{ date('d M, Y', strtotime($t->transaction_date ?: $t->created_at)) }}</td>
                        <td><strong class="text-white">{{ $t->stock_name }}</strong></td>
                        <td>
                            @if($t->transaction_type === 'dispatch_in')
                                <span class="badge bg-success"><i class="fa fa-arrow-down me-1"></i>ইনকামিং রিসিভ (Stock In)</span>
                            @elseif($t->transaction_type === 'delivery_out')
                                <span class="badge bg-danger"><i class="fa fa-arrow-up me-1"></i>সদস্য বিতরণ (Delivered)</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($t->transaction_type) }}</span>
                            @endif
                        </td>
                        <td>
                            <strong class="{{ $t->transaction_type === 'dispatch_in' ? 'text-success' : 'text-danger' }}">
                                {{ $t->transaction_type === 'dispatch_in' ? '+' : '-' }}{{ number_format($t->quantity, 1) }} KG
                            </strong>
                        </td>
                        <td>
                            <small class="text-muted">{{ $t->notes ?: '-' }}</small>
                            @if($t->customer_name)
                                <br><small class="text-info">{{ $t->customer_name }} ({{ $t->customer_card_number }})</small>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $transactions->links() }}</div>
    @endif
</div>
@endsection
