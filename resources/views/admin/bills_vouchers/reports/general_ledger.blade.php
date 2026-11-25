@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-book text-info me-2"></i> সাধারণ খতিয়ান বহি (General Ledger Register)
                    </h3>
                    <p class="text-muted mb-0">নির্দিষ্ট হিসাবের খাতের কালানুক্রমিক সমস্ত লেনদেন, প্রারম্ভিক জের ও চলতি উদ্বৃত্ত</p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-info shadow-sm">
                        <i class="fa fa-print me-1"></i> প্রিন্ট খতিয়ান
                    </button>
                    <a href="{{ route('admin.bills_vouchers.dashboard') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fa fa-dashboard me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Filter Selector -->
            <div class="card shadow-sm border-0 mb-4 no-print">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.bills_vouchers.reports.general_ledger') }}" class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-bold small text-muted">হিসাবের খাত নির্বাচন করুন (Account Head)</label>
                            <select name="account_id" class="form-select select2" required>
                                @foreach($accounts as $acc)
                                    <option value="{{ $acc->id }}" {{ $accountId == $acc->id ? 'selected' : '' }}>
                                        [{{ $acc->code }}] {{ $acc->name }} ({{ ucfirst($acc->type) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-muted">শুরুর তারিখ</label>
                            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-muted">শেষ তারিখ</label>
                            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fa fa-search me-1"></i> লেজার দেখুন
                            </button>
                            <a href="{{ route('admin.bills_vouchers.reports.general_ledger') }}" class="btn btn-outline-secondary">
                                রিসেট
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            @if($selectedAccount)
                <!-- Ledger Header Snapshot Card -->
                <div class="card shadow-sm border-0 mb-4 border-top border-primary border-3">
                    <div class="card-body">
                        <div class="row align-items-center g-3">
                            <div class="col-md-6">
                                <span class="badge bg-primary fs-13 mb-1 font-monospace">{{ $selectedAccount->code }}</span>
                                <h4 class="fw-bold text-dark mb-0">{{ $selectedAccount->name }}</h4>
                                <small class="text-muted">ধরণ: <strong>{{ ucfirst($selectedAccount->type) }}</strong> | সাব-ক্যাটাগরি: {{ $selectedAccount->category }}</small>
                            </div>
                            <div class="col-md-6">
                                <div class="row text-center g-2">
                                    <div class="col-3 border-end">
                                        <small class="text-muted d-block">প্রারম্ভিক জের</small>
                                        <strong class="text-dark">৳{{ number_format($openingBalance, 2) }}</strong>
                                    </div>
                                    <div class="col-3 border-end">
                                        <small class="text-muted d-block">মোট ডেবিট</small>
                                        <strong class="text-primary">৳{{ number_format($totalDebit, 2) }}</strong>
                                    </div>
                                    <div class="col-3 border-end">
                                        <small class="text-muted d-block">মোট ক্রেডিট</small>
                                        <strong class="text-success">৳{{ number_format($totalCredit, 2) }}</strong>
                                    </div>
                                    <div class="col-3">
                                        <small class="text-muted d-block">সমাপনী উদ্বৃত্ত</small>
                                        <strong class="text-dark fs-15">৳{{ number_format($closingBalance, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ledger Entries Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="bg-light text-center">
                                    <tr>
                                        <th style="width: 12%;">তারিখ</th>
                                        <th style="width: 16%;">ভাউচার নম্বর</th>
                                        <th style="width: 12%;">ভাউচার প্রকার</th>
                                        <th style="width: 30%;">বিবরণ (Particulars / Narration)</th>
                                        <th style="width: 10%;" class="text-end">ডেবিট (Dr. ৳)</th>
                                        <th style="width: 10%;" class="text-end">ক্রেডিট (Cr. ৳)</th>
                                        <th style="width: 10%;" class="text-end">ব্যালেন্স (Balance ৳)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Opening Balance Row -->
                                    <tr class="table-light fw-bold">
                                        <td>{{ date('d M, Y', strtotime($dateFrom)) }}</td>
                                        <td colspan="3" class="text-primary">
                                            <em>প্রারম্ভিক ব্যালেন্স (Opening Balance b/f)</em>
                                        </td>
                                        <td class="text-end">-</td>
                                        <td class="text-end">-</td>
                                        <td class="text-end">৳{{ number_format($openingBalance, 2) }}</td>
                                    </tr>

                                    @php
                                        $currentRunning = $openingBalance;
                                    @endphp

                                    @forelse($ledgerEntries as $entry)
                                        @php
                                            if (in_array($selectedAccount->type, ['asset', 'expense'])) {
                                                $currentRunning += $entry->debit - $entry->credit;
                                            } else {
                                                $currentRunning += $entry->credit - $entry->debit;
                                            }
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ date('d M, Y', strtotime($entry->transaction_date)) }}</td>
                                            <td>
                                                <a href="{{ route('admin.bills_vouchers.vouchers.show', $entry->voucher_id) }}" class="fw-bold text-primary" target="_blank">
                                                    {{ $entry->voucher_no }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $entry->voucher_type)) }}</span>
                                            </td>
                                            <td>
                                                {{ $entry->narration ?: 'General accounting entry' }}
                                                @if($entry->reference_no)
                                                    <span class="text-muted small ms-1">(রেফ: {{ $entry->reference_no }})</span>
                                                @endif
                                            </td>
                                            <td class="text-end fw-bold text-primary">
                                                {{ $entry->debit > 0 ? number_format($entry->debit, 2) : '-' }}
                                            </td>
                                            <td class="text-end fw-bold text-success">
                                                {{ $entry->credit > 0 ? number_format($entry->credit, 2) : '-' }}
                                            </td>
                                            <td class="text-end fw-bold text-dark">
                                                ৳{{ number_format($currentRunning, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                নির্বাচিত সময়সীমার মধ্যে কোনো লেনদেন রেকর্ড পাওয়া যায়নি।
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="bg-light fw-bold fs-15">
                                    <tr>
                                        <td colspan="4" class="text-end">সময়কালের মোট যোগফল (Period Total):</td>
                                        <td class="text-end text-primary">৳{{ number_format($totalDebit, 2) }}</td>
                                        <td class="text-end text-success">৳{{ number_format($totalCredit, 2) }}</td>
                                        <td class="text-end text-dark">৳{{ number_format($closingBalance, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection

