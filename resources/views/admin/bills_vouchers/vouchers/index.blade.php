@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-receipt text-primary me-2"></i> ভাউচার রেজিস্টার (Accounting Vouchers)
                    </h3>
                    <p class="text-muted mb-0">পেমেন্ট, ক্যাশ/ব্যাংক রিসিপ্ট, জার্নাল ও কন্ট্রা ভাউচারের পূর্ণাঙ্গ তালিকা ও ট্র্যাকিং</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.bills_vouchers.vouchers.create') }}" class="btn btn-primary shadow-sm">
                        <i class="fa fa-plus-circle me-1"></i> নতুন ভাউচার তৈরি করুন
                    </a>
                    <a href="{{ route('admin.bills_vouchers.dashboard') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fa fa-dashboard me-1"></i> ড্যাশবোর্ড
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

            <!-- Type Filter Tabs & Counter Pills -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <a href="{{ route('admin.bills_vouchers.vouchers.index') }}" class="btn btn-sm {{ !request('type') || request('type') == 'all' ? 'btn-primary' : 'btn-light' }}">
                            সকল ভাউচার <span class="badge bg-white text-dark ms-1">{{ $allCount }}</span>
                        </a>
                        <a href="{{ route('admin.bills_vouchers.vouchers.index', ['type' => 'payment_all']) }}" class="btn btn-sm {{ request('type') == 'payment_all' ? 'btn-danger text-white' : 'btn-light' }}">
                            পেমেন্ট ভাউচার (PV) <span class="badge bg-secondary ms-1">{{ $paymentCount }}</span>
                        </a>
                        <a href="{{ route('admin.bills_vouchers.vouchers.index', ['type' => 'receipt_all']) }}" class="btn btn-sm {{ request('type') == 'receipt_all' ? 'btn-success text-white' : 'btn-light' }}">
                            রিসিপ্ট ভাউচার (RV) <span class="badge bg-secondary ms-1">{{ $receiptCount }}</span>
                        </a>
                        <a href="{{ route('admin.bills_vouchers.vouchers.index', ['type' => 'journal']) }}" class="btn btn-sm {{ request('type') == 'journal' ? 'btn-info text-white' : 'btn-light' }}">
                            জার্নাল ভাউচার (JV) <span class="badge bg-secondary ms-1">{{ $journalCount }}</span>
                        </a>
                        <a href="{{ route('admin.bills_vouchers.vouchers.index', ['type' => 'contra']) }}" class="btn btn-sm {{ request('type') == 'contra' ? 'btn-warning text-dark' : 'btn-light' }}">
                            কন্ট্রা ভাউচার (CV) <span class="badge bg-secondary ms-1">{{ $contraCount }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filter Search Form -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.bills_vouchers.vouchers.index') }}" class="row g-3 align-items-end">
                        <input type="hidden" name="type" value="{{ request('type') }}">

                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted">সার্চ (ভাউচার নং / নাম / রেফারেন্স)</label>
                            <input type="text" name="search" class="form-control" placeholder="যেমন: PV-2026, জন ডো..." value="{{ request('search') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-muted">স্ট্যাটাস</label>
                            <select name="status" class="form-select">
                                <option value="all">সকল স্ট্যাটাস</option>
                                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>পর্যালোচনায় (Submitted)</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>অনুমোদিত (Approved)</option>
                                <option value="posted" {{ request('status') == 'posted' ? 'selected' : '' }}>লেজারে পোস্টেড (Posted)</option>
                                <option value="reversed" {{ request('status') == 'reversed' ? 'selected' : '' }}>রিভার্সড (Reversed)</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-muted">শুরুর তারিখ</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-muted">শেষ তারিখ</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fa fa-filter me-1"></i> ফিল্টার
                            </button>
                            <a href="{{ route('admin.bills_vouchers.vouchers.index') }}" class="btn btn-outline-secondary">
                                রিসেট
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Vouchers Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ভাউচার নং ও তারিখ</th>
                                    <th>ভাউচার প্রকার</th>
                                    <th>গ্রহীতা / দাতা</th>
                                    <th>পেমেন্ট মেথড</th>
                                    <th class="text-end">মোট ডেবিট (৳)</th>
                                    <th class="text-end">মোট ক্রেডিট (৳)</th>
                                    <th>প্রস্তুতকারক</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-center" style="width: 140px;">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vouchers as $voucher)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.bills_vouchers.vouchers.show', $voucher->id) }}" class="fw-bold text-primary">
                                                {{ $voucher->voucher_no }}
                                            </a>
                                            <div class="text-muted small">
                                                <i class="fa fa-calendar me-1"></i> {{ date('d M, Y', strtotime($voucher->voucher_date)) }}
                                            </div>
                                            @if($voucher->reference_no)
                                                <div class="text-muted fs-11">রেফ: {{ $voucher->reference_no }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $voucher->voucherTypeBangla() }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $voucher->payee_payer_name ?: 'N/A' }}</strong>
                                            @if($voucher->bank_account_name)
                                                <div class="text-muted fs-11">{{ $voucher->bank_account_name }}</div>
                                            @endif
                                            @if($voucher->cheque_no)
                                                <div class="text-muted fs-11">চেক নং: {{ $voucher->cheque_no }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-light text-secondary text-uppercase">{{ $voucher->payment_mode }}</span>
                                        </td>
                                        <td class="text-end fw-bold text-dark">৳{{ number_format($voucher->total_debit, 2) }}</td>
                                        <td class="text-end fw-bold text-dark">৳{{ number_format($voucher->total_credit, 2) }}</td>
                                        <td>
                                            <small class="text-muted">{{ $voucher->preparedByUser?->name ?? 'System' }}</small>
                                        </td>
                                        <td>
                                            {!! $voucher->statusBadge() !!}
                                            @if($voucher->is_reversed)
                                                <div class="badge bg-warning-light text-warning fs-11 mt-1">বিপরীত দাখিলা সম্পন্ন</div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.bills_vouchers.vouchers.show', $voucher->id) }}" class="btn btn-outline-secondary" title="ভিউ">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.bills_vouchers.vouchers.print', $voucher->id) }}" class="btn btn-outline-info" title="প্রিন্ট স্লিপ" target="_blank">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                @if(in_array($voucher->status, ['draft', 'submitted', 'approved']))
                                                    <form method="POST" action="{{ route('admin.bills_vouchers.vouchers.post', $voucher->id) }}" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই ভাউচারটি জেনারেল লেজারে পোস্ট করতে চান?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success" title="লেজারে পোস্ট করুন">
                                                            <i class="fa fa-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="fa fa-inbox fa-3x mb-3 text-muted opacity-50 d-block"></i>
                                            কোনো ভাউচার রেকর্ড পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($vouchers->hasPages())
                    <div class="card-footer bg-white border-top py-3">
                        {{ $vouchers->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection

