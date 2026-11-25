@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="fa fa-book text-primary me-2"></i>
                    @if($selectedSector)
                        {{ $selectedSector->name_bn }} - ফাইন্যান্সিয়াল লেজার খাতা (Sector Financial Ledger)
                    @else
                        সার্বিক এন্টারপ্রাইজ ইনভেস্টমেন্ট লেজার খাতা (Double-Entry Financial Ledger)
                    @endif
                </h3>
                <p class="text-muted small mb-0">প্রতিটি সেক্টর ও গ্রাহকভিত্তিক জমা, লাভ প্রদান, মূলধন সমন্বয় ও চলমান ব্যালেন্স ট্র্যাকিং</p>
            </div>
            @if($selectedSector)
                <a href="{{ route('admin.investments.sector.dashboard', $selectedSector->slug) }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> সেক্টর ড্যাশবোর্ড
                </a>
            @endif
        </div>

        <!-- Ledger KPI Summary -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <span class="text-muted small fw-medium">মোট ক্রেডিট / জমা (Inflow)</span>
                    <h3 class="fw-bold my-1 text-success">৳{{ number_format($totalCredit, 2) }}</h3>
                    <small class="text-muted">বিনিয়োগকারীদের থেকে জমাকৃত মূলধন</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <span class="text-muted small fw-medium">মোট ডেবিট / খরচ (Outflow)</span>
                    <h3 class="fw-bold my-1 text-danger">৳{{ number_format($totalDebit, 2) }}</h3>
                    <small class="text-muted">প্রদত্ত লাভ ও মূলধন ফেরত</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <span class="text-muted small fw-medium">নিট সেক্টর ব্যালেন্স (Net Running Balance)</span>
                    <h3 class="fw-bold my-1 text-primary">৳{{ number_format($netBalance, 2) }}</h3>
                    <small class="text-muted">বর্তমানে সেক্টরে রক্ষিত নিট তহবিল</small>
                </div>
            </div>
        </div>

        <!-- Filter Box -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-3">
            <form action="{{ $selectedSector ? route('admin.investments.ledger.index', ['slug' => $selectedSector->slug]) : route('admin.investments.ledger.index') }}" method="GET">
                <div class="row g-2 align-items-center">
                    @if(!$selectedSector)
                        <div class="col-md-3">
                            <select name="sector_id" class="form-select">
                                <option value="">-- সকল সেক্টর --</option>
                                @foreach($sectors as $sec)
                                    <option value="{{ $sec->id }}" {{ request('sector_id') == $sec->id ? 'selected' : '' }}>
                                        {{ $sec->sector_no }}. {{ $sec->name_bn }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-3">
                        <select name="customer_id" class="form-select">
                            <option value="">-- গ্রাহক ফিল্টার --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ $c->customer_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="transaction_type" class="form-select">
                            <option value="">-- লেনদেনের ধরণ --</option>
                            <option value="investment_received" {{ request('transaction_type') == 'investment_received' ? 'selected' : '' }}>বিনিয়োগ জমা</option>
                            <option value="profit_paid" {{ request('transaction_type') == 'profit_paid' ? 'selected' : '' }}>লাভ প্রদান</option>
                            <option value="principal_refunded" {{ request('transaction_type') == 'principal_refunded' ? 'selected' : '' }}>মূলধন ফেরত</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" placeholder="শুরুর তারিখ">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill">
                            <i class="fa fa-filter me-1"></i> ফিল্টার
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Ledger Table (Section 9) -->
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th>তারিখ</th>
                                <th>গ্রাহক (Customer)</th>
                                <th>সেক্টর</th>
                                <th>লেনদেনের বিবরণ (Transaction)</th>
                                <th>রসিদ নম্বর</th>
                                <th class="text-end text-success">ক্রেডিট (+)</th>
                                <th class="text-end text-danger">ডেবিট (-)</th>
                                <th class="text-end text-dark">চলমান ব্যালেন্স</th>
                                <th>এন্ট্রি বাই</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ledgers as $led)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($led->transaction_date)->format('d M, Y') }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $led->customer->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $led->customer->customer_code ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $led->sector->code ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark">{{ $led->description }}</div>
                                        <small class="text-muted">{{ $led->payment_method }}</small>
                                    </td>
                                    <td>
                                        @if($led->receipt_number)
                                            <span class="badge bg-light text-primary border">{{ $led->receipt_number }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        {{ $led->credit > 0 ? '৳' . number_format($led->credit, 2) : '-' }}
                                    </td>
                                    <td class="text-end fw-bold text-danger">
                                        {{ $led->debit > 0 ? '৳' . number_format($led->debit, 2) : '-' }}
                                    </td>
                                    <td class="text-end fw-bold text-dark fs-6">
                                        ৳{{ number_format($led->balance, 2) }}
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $led->entryUser->name ?? 'System' }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">লেজারে কোনো লেনদেন পাওয়া যায়নি।</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($ledgers->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $ledgers->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
