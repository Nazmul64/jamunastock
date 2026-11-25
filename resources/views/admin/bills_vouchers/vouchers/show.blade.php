@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-receipt text-primary me-2"></i> ভাউচার বিস্তারিত: {{ $voucher->voucher_no }}
                    </h3>
                    <p class="text-muted mb-0">{{ $voucher->voucherTypeBangla() }} | তারিখ: {{ date('d M, Y', strtotime($voucher->voucher_date)) }}</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.bills_vouchers.vouchers.print', $voucher->id) }}" class="btn btn-info shadow-sm" target="_blank">
                        <i class="fa fa-print me-1"></i> প্রিন্ট ভাউচার স্লিপ
                    </a>

                    @if(in_array($voucher->status, ['draft', 'submitted']))
                        <form method="POST" action="{{ route('admin.bills_vouchers.vouchers.approve', $voucher->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary shadow-sm">
                                <i class="fa fa-check me-1"></i> অনুমোদন করুন (Approve)
                            </button>
                        </form>
                    @endif

                    @if(in_array($voucher->status, ['draft', 'submitted', 'approved']))
                        <form method="POST" action="{{ route('admin.bills_vouchers.vouchers.post', $voucher->id) }}" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই ভাউচারটি জেনারেল লেজারে পোস্ট করতে চান?');">
                            @csrf
                            <button type="submit" class="btn btn-success shadow-sm">
                                <i class="fa fa-check-circle me-1"></i> লেজারে পোস্ট করুন (Post to GL)
                            </button>
                        </form>
                    @endif

                    @if($voucher->status === 'posted' && !$voucher->is_reversed)
                        <button type="button" class="btn btn-warning shadow-sm" data-bs-toggle="modal" data-bs-target="#reverseModal">
                            <i class="fa fa-undo me-1"></i> রিভার্স / বিপরীত দাখিলা
                        </button>
                    @endif

                    <a href="{{ route('admin.bills_vouchers.vouchers.index') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fa fa-arrow-left me-1"></i> তালিকা
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

            @if($voucher->is_reversed)
                <div class="alert alert-warning border-warning d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <i class="fa fa-warning fa-lg me-2"></i>
                        <strong>সতর্কতা:</strong> এই ভাউচারটি বিপরীত দাখিলা (Reversed) দ্বারা বাতিল করা হয়েছে।
                        কারণ: <em>{{ $voucher->reversal_reason ?: 'ভুল সংশোধন' }}</em>
                    </div>
                    @if($voucher->reversalVoucher)
                        <a href="{{ route('admin.bills_vouchers.vouchers.show', $voucher->reversal_voucher_id) }}" class="btn btn-sm btn-dark">
                            বিপরীত ভাউচার দেখুন ({{ $voucher->reversalVoucher->voucher_no }})
                        </a>
                    @endif
                </div>
            @endif

            <!-- 1. Key Meta Details -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <span class="text-muted small fw-bold d-block">ভাউচার নম্বর</span>
                            <h5 class="fw-bold text-primary mb-0">{{ $voucher->voucher_no }}</h5>
                        </div>
                        <div class="col-md-3 col-6">
                            <span class="text-muted small fw-bold d-block">ভাউচার প্রকার</span>
                            <span class="badge bg-light text-dark border fs-12">{{ $voucher->voucherTypeBangla() }}</span>
                        </div>
                        <div class="col-md-3 col-6">
                            <span class="text-muted small fw-bold d-block">তারিখ</span>
                            <span class="fw-bold text-dark">{{ date('d M, Y', strtotime($voucher->voucher_date)) }}</span>
                        </div>
                        <div class="col-md-3 col-6">
                            <span class="text-muted small fw-bold d-block">বর্তমান স্ট্যাটাস</span>
                            <div>{!! $voucher->statusBadge() !!}</div>
                        </div>

                        <div class="col-12"><hr class="my-1"></div>

                        <div class="col-md-3 col-6">
                            <span class="text-muted small fw-bold d-block">প্রাপক / প্রদানকারী (Party)</span>
                            <span class="fw-bold text-dark">{{ $voucher->payee_payer_name ?: 'N/A' }}</span>
                        </div>
                        <div class="col-md-3 col-6">
                            <span class="text-muted small fw-bold d-block">পেমেন্ট মেথড</span>
                            <span class="badge bg-secondary-light text-secondary text-uppercase">{{ $voucher->payment_mode }}</span>
                        </div>
                        <div class="col-md-3 col-6">
                            <span class="text-muted small fw-bold d-block">ব্যাংক হিসাবের নাম</span>
                            <span>{{ $voucher->bank_account_name ?: 'N/A' }}</span>
                        </div>
                        <div class="col-md-3 col-6">
                            <span class="text-muted small fw-bold d-block">চেক নম্বর ও তারিখ</span>
                            <span>{{ $voucher->cheque_no ?: 'N/A' }} {{ $voucher->cheque_date ? '(' . date('d M, Y', strtotime($voucher->cheque_date)) . ')' : '' }}</span>
                        </div>

                        @if($voucher->general_narration)
                            <div class="col-12 mt-2">
                                <div class="bg-light p-3 rounded">
                                    <span class="text-muted small fw-bold d-block mb-1">সাধারণ বিবরণ (General Narration):</span>
                                    <p class="mb-0 text-dark">{{ $voucher->general_narration }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="col-12">
                            <div class="bg-light-info p-2 rounded text-info small">
                                <i class="fa fa-font me-1"></i> <strong>কথায় (In Words):</strong> {{ $voucher->amount_in_words }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Double-Entry Accounting Lines Table -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="fa fa-list-alt text-success me-2"></i> ডাবল এন্ট্রি খতিয়ান লাইন (Debit & Credit Lines)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 15%;">হিসাব কোড</th>
                                    <th style="width: 30%;">হিসাবের খাত (Account Head)</th>
                                    <th style="width: 25%;">বিবরণ (Narration)</th>
                                    <th style="width: 12%;" class="text-end">ডেবিট (Debit ৳)</th>
                                    <th style="width: 13%;" class="text-end">ক্রেডিট (Credit ৳)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($voucher->entries as $idx => $entry)
                                    <tr>
                                        <td class="text-center">{{ $idx + 1 }}</td>
                                        <td><span class="badge bg-secondary">{{ $entry->account_code ?: 'N/A' }}</span></td>
                                        <td>
                                            <strong class="text-dark">{{ $entry->account_name }}</strong>
                                            @if($entry->account)
                                                <span class="text-muted small d-block">({{ ucfirst($entry->account->type) }} - {{ $entry->account->category }})</span>
                                            @endif
                                        </td>
                                        <td>{{ $entry->line_narration ?: '-' }}</td>
                                        <td class="text-end fw-bold text-primary">
                                            {{ $entry->entry_type === 'debit' ? '৳' . number_format($entry->amount, 2) : '-' }}
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            {{ $entry->entry_type === 'credit' ? '৳' . number_format($entry->amount, 2) : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr class="fw-bold fs-15">
                                    <td colspan="4" class="text-end">সর্বমোট (Total):</td>
                                    <td class="text-end text-primary">৳{{ number_format($voucher->total_debit, 2) }}</td>
                                    <td class="text-end text-success">৳{{ number_format($voucher->total_credit, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 3. Linked Bill Allocation (if exists) -->
            @if($voucher->billAllocations->count() > 0)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title fw-bold mb-0 text-dark">
                            <i class="fa fa-file-text-o text-warning me-2"></i> বিল নিষ্পত্তির বিবরণ (Settled Bill Allocations)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>বিল নং</th>
                                        <th>প্রকার</th>
                                        <th>সরবরাহকারী</th>
                                        <th class="text-end">বিলের মোট পরিমাণ</th>
                                        <th class="text-end">এই ভাউচার থেকে পরিশোধ</th>
                                        <th>বিলের বর্তমান স্ট্যাটাস</th>
                                        <th class="text-center">বিল দেখুন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($voucher->billAllocations as $alloc)
                                        <tr>
                                            <td><strong>{{ $alloc->bill?->bill_no }}</strong></td>
                                            <td><span class="badge bg-light text-dark border">{{ $alloc->bill?->billTypeBangla() }}</span></td>
                                            <td>{{ $alloc->bill?->party_name }}</td>
                                            <td class="text-end fw-bold">৳{{ number_format($alloc->bill?->grand_total, 2) }}</td>
                                            <td class="text-end fw-bold text-success">৳{{ number_format($alloc->allocated_amount, 2) }}</td>
                                            <td>{!! $alloc->bill?->paymentStatusBadge() !!}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.bills_vouchers.bills.show', $alloc->bill_id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-external-link"></i> বিল বিস্তারিত
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 4. General Ledger Postings (Audit verification) -->
            @if($voucher->ledgerEntries->count() > 0)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title fw-bold mb-0 text-dark">
                            <i class="fa fa-book text-info me-2"></i> জেনারেল লেজার পোস্টিং খতিয়ান (General Ledger Snapshot)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>তারিখ</th>
                                        <th>হিসাব কোড ও নাম</th>
                                        <th class="text-end">ডেবিট (৳)</th>
                                        <th class="text-end">ক্রেডিট (৳)</th>
                                        <th class="text-end">চলতি ব্যালেন্স (৳)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($voucher->ledgerEntries as $gle)
                                        <tr>
                                            <td>{{ date('d M, Y', strtotime($gle->transaction_date)) }}</td>
                                            <td>[{{ $gle->account_code }}] {{ $gle->account_name }}</td>
                                            <td class="text-end fw-bold text-primary">{{ $gle->debit > 0 ? number_format($gle->debit, 2) : '-' }}</td>
                                            <td class="text-end fw-bold text-success">{{ $gle->credit > 0 ? number_format($gle->credit, 2) : '-' }}</td>
                                            <td class="text-end fw-bold text-dark">৳{{ number_format($gle->running_balance, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 5. Audit & Signature Tracking Footprint -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-md-3 col-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block mb-1">প্রস্তুতকারক (Prepared By)</small>
                                <strong class="text-dark">{{ $voucher->preparedByUser?->name ?? 'System' }}</strong>
                                <div class="text-muted fs-11 mt-1">{{ $voucher->created_at->format('d M, Y h:i A') }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block mb-1">যাচাইকারী (Checked By)</small>
                                <strong class="text-dark">{{ $voucher->checkedByUser?->name ?? 'Finance Dept' }}</strong>
                                <div class="text-muted fs-11 mt-1">অভ্যন্তরীণ নিরীক্ষা</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block mb-1">অনুমোদনকারী (Approved By)</small>
                                <strong class="text-dark">{{ $voucher->approvedByUser?->name ?? 'অপেক্ষমাণ' }}</strong>
                                <div class="text-muted fs-11 mt-1">{{ $voucher->approvedByUser ? 'অনুমোদিত' : 'অপেক্ষমাণ' }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="border rounded p-3">
                                <small class="text-muted d-block mb-1">লেজারে পোস্টকারী (Posted By)</small>
                                <strong class="text-dark">{{ $voucher->postedByUser?->name ?? ($voucher->status === 'posted' ? 'System' : 'অপেক্ষমাণ') }}</strong>
                                <div class="text-muted fs-11 mt-1">{{ $voucher->posted_at ? date('d M, Y h:i A', strtotime($voucher->posted_at)) : 'পোস্ট হয়নি' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal: Reversal Reason -->
<div class="modal fade" id="reverseModal" tabindex="-1" aria-labelledby="reverseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.bills_vouchers.vouchers.reverse', $voucher->id) }}" class="modal-content">
            @csrf
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold text-dark" id="reverseModalLabel">
                    <i class="fa fa-undo me-2"></i> ভাউচার রিভার্স করুন (বিপরীত দাখিলা)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">
                    একাউন্টিং নিয়ম অনুযায়ী পোস্ট করা ভাউচার সরাসরি ডিলিট করা যায় না। এর পরিবর্তে একটি বিপরীত দাখিলা (Mirror Reversal) তৈরি করে লেজার ব্যালেন্স স্বয়ংক্রিয়ভাবে আগের অবস্থায় ফিরিয়ে নেওয়া হবে।
                </p>
                <div class="mb-3">
                    <label class="form-label fw-bold">রিভার্স করার কারণ <span class="text-danger">*</span></label>
                    <textarea name="reversal_reason" class="form-control" rows="3" required placeholder="ভুলের কারণ উল্লেখ করুন..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="submit" class="btn btn-warning fw-bold text-dark">
                    <i class="fa fa-undo me-1"></i> নিশ্চিত করুন ও রিভার্স ভাউচার তৈরি করুন
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

