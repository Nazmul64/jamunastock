@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-file-text-o text-success me-2"></i> বিল ও ভাউচার ড্যাশবোর্ড (Bills & Vouchers ERP)
                    </h3>
                    <p class="text-muted mb-0">এন্টারপ্রাইজ ডাবল-এন্ট্রি একাউন্টিং, আর্থিক বিল, ক্যাশ/ব্যাংক ভাউচার ও জেনারেল লেজার ম্যানেজমেন্ট</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.bills_vouchers.vouchers.create') }}" class="btn btn-primary shadow-sm">
                        <i class="fa fa-plus-circle me-1"></i> নতুন ভাউচার তৈরি
                    </a>
                    <a href="{{ route('admin.bills_vouchers.bills.create') }}" class="btn btn-success shadow-sm">
                        <i class="fa fa-file-text me-1"></i> নতুন বিল এন্ট্রি
                    </a>
                    <a href="{{ route('admin.bills_vouchers.reports.trial_balance') }}" class="btn btn-outline-info shadow-sm">
                        <i class="fa fa-balance-scale me-1"></i> রেওয়ামিল (Trial Balance)
                    </a>
                    <a href="{{ route('admin.bills_vouchers.chart_of_accounts') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fa fa-list me-1"></i> চার্ট অব অ্যাকাউন্টস
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 1. Top Core Financial Metrics -->
            <div class="row g-3 mb-4">
                <!-- Total Bills & Outstanding -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card shadow-sm border-0 border-start border-primary border-4 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted fw-bold d-block mb-1">মোট বিল ও বকেয়া</span>
                                    <h3 class="mb-0 fw-bold text-primary">৳{{ number_format($totalBillsAmount, 2) }}</h3>
                                    <div class="mt-2 small">
                                        <span class="badge bg-danger-light text-danger fw-bold">বকেয়া: ৳{{ number_format($totalOutstandingBillsAmount, 2) }}</span>
                                        <span class="badge bg-success-light text-success fw-bold ms-1">পরিশোধ: ৳{{ number_format($totalPaidBillsAmount, 2) }}</span>
                                    </div>
                                </div>
                                <div class="bg-primary-light rounded-circle p-3 text-primary">
                                    <i class="fa fa-file-invoice-dollar fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Payments Posted -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card shadow-sm border-0 border-start border-danger border-4 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted fw-bold d-block mb-1">মোট পরিশোধ (Payments)</span>
                                    <h3 class="mb-0 fw-bold text-danger">৳{{ number_format($totalPaymentsPosted, 2) }}</h3>
                                    <small class="text-muted">পোস্টেড পেমেন্ট ও ব্যয় ভাউচার</small>
                                </div>
                                <div class="bg-danger-light rounded-circle p-3 text-danger">
                                    <i class="fa fa-arrow-circle-up fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Receipts Posted -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card shadow-sm border-0 border-start border-success border-4 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted fw-bold d-block mb-1">মোট প্রাপ্তি (Receipts)</span>
                                    <h3 class="mb-0 fw-bold text-success">৳{{ number_format($totalReceiptsPosted, 2) }}</h3>
                                    <small class="text-muted">পোস্টেড রিসিপ্ট ও আয় ভাউচার</small>
                                </div>
                                <div class="bg-success-light rounded-circle p-3 text-success">
                                    <i class="fa fa-arrow-circle-down fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liquidity: Cash & Bank -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card shadow-sm border-0 border-start border-warning border-4 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted fw-bold d-block mb-1">তারল্য (Cash & Bank)</span>
                                    <h3 class="mb-0 fw-bold text-warning">৳{{ number_format($cashBalance + $bankBalance, 2) }}</h3>
                                    <div class="mt-2 small">
                                        <span class="text-muted">ক্যাশ: <strong>৳{{ number_format($cashBalance, 2) }}</strong></span> | 
                                        <span class="text-muted">ব্যাংক: <strong>৳{{ number_format($bankBalance, 2) }}</strong></span>
                                    </div>
                                </div>
                                <div class="bg-warning-light rounded-circle p-3 text-warning">
                                    <i class="fa fa-university fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Chart of Accounts Snapshot & Status Badges -->
            <div class="row g-3 mb-4">
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="card border-0 shadow-sm text-center py-3 bg-light">
                        <span class="text-muted fs-12 fw-bold">মোট সম্পদ (Assets)</span>
                        <h4 class="mb-0 fw-bold text-primary mt-1">৳{{ number_format($assetBalance, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="card border-0 shadow-sm text-center py-3 bg-light">
                        <span class="text-muted fs-12 fw-bold">দায়সমূহ (Liabilities)</span>
                        <h4 class="mb-0 fw-bold text-danger mt-1">৳{{ number_format($liabilityBalance, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="card border-0 shadow-sm text-center py-3 bg-light">
                        <span class="text-muted fs-12 fw-bold">মালিকানাস্বত্ব (Equity)</span>
                        <h4 class="mb-0 fw-bold text-purple mt-1">৳{{ number_format($equityBalance, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="card border-0 shadow-sm text-center py-3 bg-light">
                        <span class="text-muted fs-12 fw-bold">মোট আয় (Income)</span>
                        <h4 class="mb-0 fw-bold text-success mt-1">৳{{ number_format($incomeBalance, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="card border-0 shadow-sm text-center py-3 bg-light">
                        <span class="text-muted fs-12 fw-bold">মোট ব্যয় (Expenses)</span>
                        <h4 class="mb-0 fw-bold text-warning mt-1">৳{{ number_format($expenseBalance, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="card border-0 shadow-sm text-center py-3 bg-light">
                        <span class="text-muted fs-12 fw-bold">পোস্টেড ভাউচার</span>
                        <h4 class="mb-0 fw-bold text-info mt-1">{{ $postedVouchersCount }} / {{ $totalVouchersCount }}</h4>
                    </div>
                </div>
            </div>

            <!-- 3. Recent Vouchers & Recent Bills -->
            <div class="row g-4">
                <!-- Recent Vouchers -->
                <div class="col-xl-6 col-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                            <h5 class="card-title fw-bold mb-0 text-dark">
                                <i class="fa fa-receipt text-primary me-2"></i> সাম্প্রতিক ভাউচারসমূহ (Recent Vouchers)
                            </h5>
                            <a href="{{ route('admin.bills_vouchers.vouchers.index') }}" class="btn btn-sm btn-outline-primary">
                                সকল ভাউচার <i class="fa fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>ভাউচার নং</th>
                                            <th>প্রকার</th>
                                            <th>গ্রহীতা / দাতা</th>
                                            <th class="text-end">পরিমাণ</th>
                                            <th>স্ট্যাটাস</th>
                                            <th class="text-center">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentVouchers as $v)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.bills_vouchers.vouchers.show', $v->id) }}" class="fw-bold text-primary">
                                                        {{ $v->voucher_no }}
                                                    </a>
                                                    <div class="text-muted fs-11">{{ date('d M, Y', strtotime($v->voucher_date)) }}</div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">{{ $v->voucherTypeBangla() }}</span>
                                                </td>
                                                <td>{{ $v->payee_payer_name ?: 'N/A' }}</td>
                                                <td class="text-end fw-bold text-dark">৳{{ number_format($v->total_debit, 2) }}</td>
                                                <td>{!! $v->statusBadge() !!}</td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.bills_vouchers.vouchers.show', $v->id) }}" class="btn btn-outline-secondary" title="বিস্তারিত">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.bills_vouchers.vouchers.print', $v->id) }}" class="btn btn-outline-info" title="প্রিন্ট ভাউচার" target="_blank">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">কোনো ভাউচার পাওয়া যায়নি।</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bills -->
                <div class="col-xl-6 col-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                            <h5 class="card-title fw-bold mb-0 text-dark">
                                <i class="fa fa-file-text-o text-success me-2"></i> সাম্প্রতিক বিলসমূহ (Recent Bills)
                            </h5>
                            <a href="{{ route('admin.bills_vouchers.bills.index') }}" class="btn btn-sm btn-outline-success">
                                সকল বিল <i class="fa fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>বিল নং</th>
                                            <th>প্রকার</th>
                                            <th>পার্টি / সাপ্লায়ার</th>
                                            <th class="text-end">মোট বিল</th>
                                            <th class="text-end">বকেয়া</th>
                                            <th>স্ট্যাটাস</th>
                                            <th class="text-center">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentBills as $b)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.bills_vouchers.bills.show', $b->id) }}" class="fw-bold text-success">
                                                        {{ $b->bill_no }}
                                                    </a>
                                                    <div class="text-muted fs-11">{{ date('d M, Y', strtotime($b->bill_date)) }}</div>
                                                </td>
                                                <td><span class="badge bg-light text-dark border">{{ $b->billTypeBangla() }}</span></td>
                                                <td>{{ $b->party_name }}</td>
                                                <td class="text-end fw-bold">৳{{ number_format($b->grand_total, 2) }}</td>
                                                <td class="text-end fw-bold text-danger">৳{{ number_format($b->outstanding_amount, 2) }}</td>
                                                <td>{!! $b->paymentStatusBadge() !!}</td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.bills_vouchers.bills.show', $b->id) }}" class="btn btn-outline-secondary" title="দেখুন">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        @if($b->outstanding_amount > 0)
                                                            <a href="{{ route('admin.bills_vouchers.vouchers.create', ['type' => 'payment', 'bill_id' => $b->id]) }}" class="btn btn-outline-success" title="পেমেন্ট ভাউচার করুন">
                                                                <i class="fa fa-money"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">কোনো বিল পাওয়া যায়নি।</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Quick Bank & Cash Summary Footprint -->
            <div class="row g-4 mt-1">
                <div class="col-md-6 col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="fw-bold mb-0 text-dark"><i class="fa fa-money text-success me-2"></i> ক্যাশ ইন হ্যান্ড অ্যাকাউন্টস (Cash in Hand)</h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @foreach($cashAccounts as $ca)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-secondary me-2">{{ $ca->code }}</span>
                                            <strong>{{ $ca->name }}</strong>
                                        </div>
                                        <span class="fw-bold text-success fs-14">৳{{ number_format($ca->current_balance, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="fw-bold mb-0 text-dark"><i class="fa fa-bank text-primary me-2"></i> ব্যাংক অ্যাকাউন্টস (Cash at Bank)</h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @foreach($bankAccounts as $ba)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-primary me-2">{{ $ba->code }}</span>
                                            <strong>{{ $ba->name }}</strong>
                                        </div>
                                        <span class="fw-bold text-primary fs-14">৳{{ number_format($ba->current_balance, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

