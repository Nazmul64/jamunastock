@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="fa fa-receipt text-warning me-2"></i>অফিসিয়াল ইনভেস্টমেন্ট মানি রসিদ রেজিস্ট্রি (Official Receipts)
                </h3>
                <p class="text-muted small mb-0">সকল সেক্টরের স্বয়ংক্রিয় নম্বরযুক্ত মানি রসিদ, প্রিন্ট ট্র্যাকিং ও অডিট ট্রেইল</p>
            </div>
        </div>

        <!-- Filter Box -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-3">
            <form action="{{ route('admin.investments.receipts.index') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" 
                                   placeholder="রসিদ নম্বর (যেমন LIVE-RCP-2027-00001) বা গ্রাহকের নাম..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="sector_id" class="form-select">
                            <option value="">-- সকল সেক্টর --</option>
                            @foreach($sectors as $sec)
                                <option value="{{ $sec->id }}" {{ request('sector_id') == $sec->id ? 'selected' : '' }}>
                                    {{ $sec->sector_no }}. {{ $sec->name_bn }} ({{ $sec->receipt_prefix }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill">
                            <i class="fa fa-filter me-1"></i> ফিল্টার
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Receipts Table -->
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th>রসিদ নম্বর</th>
                                <th>বিনিয়োগ কোড</th>
                                <th>গ্রাহকের নাম ও ফোন</th>
                                <th>সেক্টর</th>
                                <th>ধরণ</th>
                                <th>পরিমাণ</th>
                                <th>পেমেন্ট মেথড</th>
                                <th>তারিখ</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($receipts as $rcp)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.investments.receipts.view', $rcp->id) }}" class="fw-bold text-primary">
                                            {{ $rcp->receipt_number }}
                                        </a>
                                        @if($rcp->print_count > 1)
                                            <span class="badge bg-light text-muted border ms-1" style="font-size: 10px;">Reprint x{{ $rcp->print_count }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $rcp->investment->investment_code ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $rcp->customer->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $rcp->customer->phone ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success">{{ $rcp->sector->code ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if($rcp->receipt_type === 'investment_deposit')
                                            <span class="badge bg-success bg-opacity-10 text-success">মূলধন জমা</span>
                                        @elseif($rcp->receipt_type === 'profit_payout')
                                            <span class="badge bg-info bg-opacity-10 text-info">মুনাফা ছাড়</span>
                                        @else
                                            <span class="badge bg-secondary text-white">সেটেলমেন্ট</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">৳{{ number_format($rcp->amount, 2) }}</span>
                                    </td>
                                    <td>{{ $rcp->payment_method }}</td>
                                    <td>{{ $rcp->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($rcp->is_void)
                                            <span class="badge bg-danger text-white">বাতিল (Void)</span>
                                        @else
                                            <span class="badge bg-success text-white">বৈধ (Valid)</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.investments.receipts.view', $rcp->id) }}" target="_blank" class="btn btn-sm btn-light border rounded-pill px-3">
                                            <i class="fa fa-print text-primary me-1"></i> প্রিন্ট
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5 text-muted">কোনো মানি রসিদ পাওয়া যায়নি।</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($receipts->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $receipts->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
