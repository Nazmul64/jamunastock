@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-balance-scale text-success me-2"></i> রেওয়ামিল (Trial Balance Statement)
                    </h3>
                    <p class="text-muted mb-0">হিসাবের গাণিতিক নির্ভুলতা যাচাই ও আর্থিক বিবরণী তৈরির পূর্বশর্ত</p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-info shadow-sm">
                        <i class="fa fa-print me-1"></i> প্রিন্ট স্টেটমেন্ট
                    </button>
                    <a href="{{ route('admin.bills_vouchers.dashboard') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fa fa-dashboard me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Filter Bar -->
            <div class="card shadow-sm border-0 mb-4 no-print">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.bills_vouchers.reports.trial_balance') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">নির্দিষ্ট তারিখ অনুযায়ী (As of Date)</label>
                            <input type="date" name="as_of_date" class="form-control" value="{{ $asOfDate }}">
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fa fa-calculator me-1"></i> হিসাব গণনা করুন
                            </button>
                            <a href="{{ route('admin.bills_vouchers.reports.trial_balance') }}" class="btn btn-outline-secondary">
                                আজ পর্যন্ত
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Balanced Indicator Banner -->
            @if($isBalanced)
                <div class="alert alert-success border-success d-flex align-items-center justify-content-between mb-4 shadow-sm">
                    <div>
                        <i class="fa fa-check-circle fa-2x me-3"></i>
                        <span class="fs-15"><strong>আলহামদুলিল্লাহ! রেওয়ামিল সম্পূর্ণ সামঞ্জস্যপূর্ণ (Trial Balance is Balanced)</strong></span>
                        <div class="text-muted small mt-1">মোট ডেবিট জের এবং মোট ক্রেডিট জেরের পরিমাণ সম্পূর্ণ সমান।</div>
                    </div>
                    <span class="badge bg-success fs-14 py-2 px-3">ব্যালেন্সড: ৳{{ number_format($grandDebit, 2) }}</span>
                </div>
            @else
                <div class="alert alert-danger border-danger d-flex align-items-center justify-content-between mb-4 shadow-sm">
                    <div>
                        <i class="fa fa-exclamation-triangle fa-2x me-3"></i>
                        <span class="fs-15"><strong>সতর্কতা! রেওয়ামিলে অসামঞ্জস্য রয়েছে (Out of Balance)</strong></span>
                        <div class="text-muted small mt-1">পার্থক্য: ৳{{ number_format(abs($grandDebit - $grandCredit), 2) }}</div>
                    </div>
                    <span class="badge bg-danger fs-14 py-2 px-3">পার্থক্য বিদ্যমান</span>
                </div>
            @endif

            <!-- Trial Balance Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title fw-bold mb-0 text-dark">যমুনা স্টক মার্কেট পিএলসি - রেওয়ামিল</h5>
                        <small class="text-muted">তারিখ: {{ date('d F, Y', strtotime($asOfDate)) }}</small>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th style="width: 12%;">হিসাব কোড</th>
                                    <th style="width: 32%;">হিসাবের খাতের নাম (Account Name)</th>
                                    <th style="width: 16%;">হিসাবের শ্রেণী</th>
                                    <th style="width: 20%;" class="text-end">ডেবিট উদ্বৃত্ত (Debit ৳)</th>
                                    <th style="width: 20%;" class="text-end">ক্রেডিট উদ্বৃত্ত (Credit ৳)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rows as $row)
                                    <tr>
                                        <td class="text-center font-monospace fw-bold">{{ $row['account']->code }}</td>
                                        <td>
                                            <a href="{{ route('admin.bills_vouchers.reports.general_ledger', ['account_id' => $row['account']->id]) }}" class="text-dark fw-bold" title="খতিয়ান দেখুন">
                                                {{ $row['account']->name }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $badgeClass = match($row['account']->type) {
                                                    'asset' => 'bg-primary-light text-primary',
                                                    'liability' => 'bg-danger-light text-danger',
                                                    'equity' => 'bg-purple-light text-purple',
                                                    'income' => 'bg-success-light text-success',
                                                    'expense' => 'bg-warning-light text-warning',
                                                    default => 'bg-secondary-light text-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ ucfirst($row['account']->type) }}</span>
                                        </td>
                                        <td class="text-end fw-bold text-primary">
                                            {{ $row['net_debit'] > 0 ? '৳' . number_format($row['net_debit'], 2) : '-' }}
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            {{ $row['net_credit'] > 0 ? '৳' . number_format($row['net_credit'], 2) : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            কোনো সক্রিয় হিসাবের ব্যালেন্স পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-light fs-16 fw-bold">
                                <tr>
                                    <td colspan="3" class="text-end">সর্বমোট রেওয়ামিল যোগফল (Grand Total):</td>
                                    <td class="text-end text-primary">৳{{ number_format($grandDebit, 2) }}</td>
                                    <td class="text-end text-success">৳{{ number_format($grandCredit, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

