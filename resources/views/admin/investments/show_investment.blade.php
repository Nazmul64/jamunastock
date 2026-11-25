@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">

        <!-- Top Header & Actions -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <a href="{{ route('admin.investments.sector.dashboard', $investment->sector->slug) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mb-2">
                    <i class="fa fa-arrow-left me-1"></i> {{ $investment->sector->name_bn }} ড্যাশবোর্ড
                </a>
                <div class="d-flex align-items-center gap-3">
                    <h3 class="fw-bold text-dark mb-0">বিনিয়োগ হিসাব: {{ $investment->investment_code }}</h3>
                    <span class="badge px-3 py-2 fs-6 rounded-pill" style="background-color: #d1fae5 !important; color: #065f46 !important; border: 1.5px solid #10b981 !important; font-weight: 700 !important;">
                        <i class="fa fa-check-circle me-1 text-success"></i>{{ ucfirst($investment->status) }}
                    </span>
                </div>
                <small class="text-muted">চুক্তিপত্র নম্বর: <strong>{{ $investment->agreement_number }}</strong> • সেক্টর: <strong>{{ $investment->sector->name_bn }}</strong></small>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @if($investment->receipts->count() > 0)
                    <a href="{{ route('admin.investments.receipts.view', $investment->receipts->first()->id) }}" target="_blank" class="btn btn-primary fw-semibold rounded-pill px-3 shadow-sm">
                        <i class="fa fa-print me-1"></i> মানি রসিদ প্রিন্ট
                    </a>
                @endif
                @if($investment->status === 'active')
                    <button type="button" class="btn btn-outline-danger fw-semibold rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#settlementModal">
                        <i class="fa fa-hand-holding-usd me-1"></i> মেয়াদপূর্ব নিষ্পত্তি (Early Settlement)
                    </button>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="fa fa-check-circle fa-lg"></i>
                <div class="fw-medium">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Customer & Investment Summary Grid -->
        <div class="row g-4 mb-4">
            <!-- Customer KYC Profile Card (Section 4) -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white h-100 p-4">
                    <div class="d-flex align-items-center gap-3 mb-3 border-bottom pb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                            <i class="fa fa-user-check fa-2x"></i>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark border small">{{ $investment->customer->customer_code }}</span>
                            <h5 class="fw-bold mb-0 text-dark">{{ $investment->customer->name }}</h5>
                            <small class="text-muted">{{ $investment->customer->phone }}</small>
                        </div>
                    </div>
                    <div class="small">
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">পিতার নাম:</span>
                            <span class="fw-medium">{{ $investment->customer->father_name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">এনআইডি নম্বর:</span>
                            <span class="fw-medium">{{ $investment->customer->nid_number ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">পেশা:</span>
                            <span class="fw-medium">{{ $investment->customer->occupation ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">ঠিকানা:</span>
                            <span class="fw-medium text-end">{{ Str::limit($investment->customer->present_address ?? 'N/A', 30) }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">নমিনি:</span>
                            <span class="fw-medium">{{ $investment->customer->nominee_name ?? 'N/A' }} ({{ $investment->customer->nominee_relation ?? '' }})</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 pt-2">
                            <span class="text-muted">নমিনির ফোন:</span>
                            <span class="fw-medium">{{ $investment->customer->nominee_phone ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="{{ route('admin.investments.customer.profile', $investment->customer->id) }}" class="btn btn-sm btn-outline-primary rounded-pill w-100">
                            <i class="fa fa-id-card me-1"></i> গ্রাহকের পূর্ণ প্রোফাইল ও সর্বমোট পোর্টফোলিও
                        </a>
                    </div>
                </div>
            </div>

            <!-- Financial Account Overview (Section 5 & 6) -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 bg-white h-100 p-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa fa-wallet text-success me-2"></i>আর্থিক হিসাব ও চুক্তির সার্বিক পরিসংখ্যান</h5>
                    <div class="row g-3 text-center mb-4">
                        <div class="col-sm-3 col-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small">মূল বিনিয়োগ (Principal)</div>
                                <h4 class="fw-bold text-success my-1">৳{{ number_format($investment->amount, 2) }}</h4>
                                <small class="text-muted">{{ $investment->payment_method }}</small>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small">চুক্তিবদ্ধ মুনাফার হার</div>
                                <h4 class="fw-bold text-primary my-1">{{ $investment->profit_rate }}%</h4>
                                <small class="text-muted">{{ ucfirst($investment->profit_frequency) }}</small>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small">ইতোমধ্যে লাভ প্রদান</div>
                                <h4 class="fw-bold text-info my-1">৳{{ number_format($investment->paid_profit_total, 2) }}</h4>
                                <small class="text-success">পরিশোধিত</small>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small">অবশিষ্ট প্রদেয় লাভ</div>
                                <h4 class="fw-bold text-warning my-1">৳{{ number_format($investment->profit_due, 2) }}</h4>
                                <small class="text-muted">ভবিষ্যত কিস্তি</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 small">
                        <div class="col-md-4">
                            <span class="text-muted d-block">চুক্তির মেয়াদকাল:</span>
                            <span class="fw-bold text-dark">{{ $investment->duration_years }} বছর ({{ \Carbon\Carbon::parse($investment->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($investment->maturity_date)->format('d M Y') }})</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block">মুনাফা বণ্টন স্কিম:</span>
                            <span class="fw-bold text-dark">{{ ucfirst($investment->profit_type) }} • {{ ucfirst($investment->profit_frequency) }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block">মেয়াদান্তে সর্বমোট রিটার্ন:</span>
                            <span class="fw-bold text-success">৳{{ number_format($investment->total_expected_return, 2) }}</span>
                        </div>
                    </div>

                    @if($investment->agreement_file)
                        <div class="mt-3 p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fa fa-file-pdf text-danger fa-lg me-2"></i>
                                <span class="fw-medium">সংযুক্ত অফিসিয়াল চুক্তিপত্র কপি</span>
                            </div>
                            <a href="{{ asset($investment->agreement_file) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                <i class="fa fa-download me-1"></i> ডাউনলোড করুন
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Profit Installment Schedule Table (Section 7) -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0 text-dark"><i class="fa fa-calendar-alt text-primary me-2"></i>মুনাফা কিস্তি শিডিউল ও পেমেন্ট ট্র্যাকার (Profit Installment Schedule)</h5>
                    <small class="text-muted">চুক্তি অনুসারে প্রতিটি বছরের কিস্তি, পরিশোধিত অবস্থা এবং বকেয়া ট্র্যাকিং</small>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th>কিস্তি #</th>
                                <th>পিরিয়ড / কিস্তির বিবরণ</th>
                                <th>পরিশোধের নির্ধারিত তারিখ</th>
                                <th>প্রদেয় সম্ভাব্য লাভ</th>
                                <th>পরিশোধিত লাভ</th>
                                <th>বাকি লাভ</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($investment->profitSchedules as $sched)
                                <tr>
                                    <td><strong class="text-dark">#{{ $sched->installment_no }}</strong></td>
                                    <td><span class="fw-bold text-dark fs-6">{{ $sched->period_label }}</span></td>
                                    <td class="fw-semibold text-secondary">{{ \Carbon\Carbon::parse($sched->due_date)->format('d M Y') }}</td>
                                    <td><span class="fw-bold text-dark" style="font-size: 15px;">৳{{ number_format($sched->expected_profit, 2) }}</span></td>
                                    <td><span class="fw-bold text-success" style="font-size: 15px;">৳{{ number_format($sched->paid_amount, 2) }}</span></td>
                                    <td><span class="fw-bold {{ $sched->due_amount > 0 ? 'text-danger' : 'text-muted' }}" style="font-size: 15px;">৳{{ number_format($sched->due_amount, 2) }}</span></td>
                                    <td>
                                        @if($sched->status === 'paid')
                                            <span class="badge px-3 py-2" style="background-color: #d1fae5 !important; color: #065f46 !important; border: 1.5px solid #10b981 !important; font-size: 13px !important; font-weight: 700 !important; border-radius: 20px !important;">
                                                <i class="fa fa-check-circle me-1 text-success"></i>পরিশোধিত
                                            </span>
                                        @elseif($sched->status === 'due')
                                            <span class="badge px-3 py-2" style="background-color: #fee2e2 !important; color: #991b1b !important; border: 1.5px solid #ef4444 !important; font-size: 13px !important; font-weight: 700 !important; border-radius: 20px !important;">
                                                <i class="fa fa-clock me-1 text-danger"></i>বকেয়া (Due)
                                            </span>
                                        @else
                                            <span class="badge px-3 py-2" style="background-color: #f1f5f9 !important; color: #1e293b !important; border: 1.5px solid #94a3b8 !important; font-size: 13px !important; font-weight: 600 !important; border-radius: 20px !important;">
                                                <i class="fa fa-hourglass-half me-1 text-secondary"></i>আসন্ন (Upcoming)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($sched->status !== 'paid')
                                            <button type="button" class="btn btn-sm btn-success rounded-pill px-3" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#payModal{{ $sched->id }}">
                                                <i class="fa fa-hand-holding-usd me-1"></i> লাভ প্রদান করুন
                                            </button>

                                            <!-- Pay Installment Modal -->
                                            <div class="modal fade" id="payModal{{ $sched->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content rounded-4 border-0 text-start">
                                                        <div class="modal-header border-0 bg-light p-4">
                                                            <h5 class="modal-title fw-bold text-dark">লাভ কিস্তি পরিশোধ: {{ $sched->period_label }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('admin.investments.profit.pay', $sched->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body p-4">
                                                                <div class="alert alert-info rounded-3 small mb-3">
                                                                    গ্রাহক: <strong>{{ $investment->customer->name }}</strong><br>
                                                                    প্রদেয় কিস্তি: <strong>৳{{ number_format($sched->expected_profit, 2) }}</strong>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-semibold">পরিশোধের পরিমাণ (BDT) <span class="text-danger">*</span></label>
                                                                    <input type="number" step="any" name="paid_amount" class="form-control form-control-lg" value="{{ $sched->due_amount > 0 ? $sched->due_amount : $sched->expected_profit }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-semibold">পেমেন্ট মেথড <span class="text-danger">*</span></label>
                                                                    <select name="payment_method" class="form-select" required>
                                                                        <option value="Bank Transfer">ব্যাংক ট্রান্সফার / এনপিএসবি</option>
                                                                        <option value="Cash">নগদ প্রদান (Cash)</option>
                                                                        <option value="Cheque">ব্যাংক চেক</option>
                                                                        <option value="Mobile Banking">বিকাশ / নগদ</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-semibold">ট্রানজেকশন রেফারেন্স / চেক নং</label>
                                                                    <input type="text" name="transaction_ref" class="form-control" placeholder="TXN-XXXXX">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-semibold">মন্তব্য (Optional)</label>
                                                                    <textarea name="remarks" class="form-control" rows="2"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-0 p-4 pt-0">
                                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">বাতিল</button>
                                                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">পরিশোধ নিশ্চিত করুন</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted small">পরিশোধিত: {{ \Carbon\Carbon::parse($sched->paid_at)->format('d M Y') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">কোনো কিস্তি শিডিউল পাওয়া যায়নি।</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Official Receipts Issued List (Section 8) -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa fa-receipt text-warning me-2"></i>ইস্যুকৃত অফিসিয়াল মানি রসিদ সমূহ (Official Receipts)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th>রসিদ নম্বর</th>
                                <th>রসিদের ধরণ</th>
                                <th>তারিখ</th>
                                <th>পরিমাণ</th>
                                <th>পেমেন্ট মেথড</th>
                                <th>প্রিন্ট সংখ্যা</th>
                                <th>অবস্থা</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($investment->receipts as $rcp)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary">{{ $rcp->receipt_number }}</span>
                                    </td>
                                    <td>
                                        @if($rcp->receipt_type === 'investment_deposit')
                                            <span class="badge bg-success bg-opacity-10 text-success">মূলধন জমা</span>
                                        @elseif($rcp->receipt_type === 'profit_payout')
                                            <span class="badge bg-info bg-opacity-10 text-info">মুনাফা ছাড়</span>
                                        @else
                                            <span class="badge bg-secondary text-white">সেটেলমেন্ট ফেরত</span>
                                        @endif
                                    </td>
                                    <td>{{ $rcp->created_at->format('d M Y, h:i A') }}</td>
                                    <td><span class="fw-bold text-dark">৳{{ number_format($rcp->amount, 2) }}</span></td>
                                    <td>{{ $rcp->payment_method }}</td>
                                    <td><span class="badge bg-light text-muted border">{{ $rcp->print_count }} বার</span></td>
                                    <td>
                                        @if($rcp->is_void)
                                            <span class="badge bg-danger text-white">বাতিল (Void)</span>
                                        @else
                                            <span class="badge bg-success text-white">বৈধ (Valid)</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.investments.receipts.view', $rcp->id) }}" target="_blank" class="btn btn-sm btn-light border rounded-pill px-3">
                                            <i class="fa fa-print text-primary me-1"></i> ভিউ / প্রিন্ট
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">কোনো রসিদ নেই।</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- EARLY SETTLEMENT MODAL (SECTION 6) -->
<div class="modal fade" id="settlementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 bg-light p-4">
                <div>
                    <h5 class="modal-title fw-bold text-dark">মেয়াদপূর্ব উত্তোলন ও চূড়ান্ত নিষ্পত্তি (Early Settlement)</h5>
                    <small class="text-muted">চুক্তির শর্ত অনুযায়ী মেয়াদ শেষ হওয়ার পূর্বে অর্থ উত্তোলন ও লাভ কর্তন হিসাব</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.investments.settlement.process', $investment->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">সেটেলমেন্ট ধরণ <span class="text-danger">*</span></label>
                            <select name="settlement_type" class="form-select" required>
                                <option value="early_withdrawal">মেয়াদপূর্ব উত্তোলন (Early Withdrawal)</option>
                                <option value="cancellation">চুক্তি বাতিল (Cancellation)</option>
                                <option value="maturity">নিয়মিত মেয়াদান্তে নিষ্পত্তি (Maturity Settlement)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">সেটেলমেন্টের তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="settlement_date" id="settlement_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- Settlement Calculation Box -->
                        <div class="col-12">
                            <div class="card bg-light border-0 rounded-4 p-4">
                                <div class="row g-3 text-center">
                                    <div class="col-md-3 col-6">
                                        <div class="text-muted small">মূল বিনিয়োগ</div>
                                        <h5 class="fw-bold text-dark my-1">৳{{ number_format($investment->amount, 2) }}</h5>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="text-muted small">গৃহীত মোট লাভ</div>
                                        <h5 class="fw-bold text-info my-1">৳{{ number_format($investment->paid_profit_total, 2) }}</h5>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="text-muted small">কর্তন / জরিমানা</div>
                                        <h5 class="fw-bold text-danger my-1" id="dispPenalty">৳{{ number_format($investment->paid_profit_total, 2) }}</h5>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="text-muted small">ফেরতযোগ্য নিট আসল</div>
                                        <h5 class="fw-bold text-success my-1" id="dispNetRefund">৳{{ number_format(max(0, $investment->amount - $investment->paid_profit_total), 2) }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">মোট কর্তন / সমন্বয় (BDT) <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="penalty_or_deduction" id="penalty_or_deduction" class="form-control" 
                                   value="{{ $investment->paid_profit_total }}" required>
                            <small class="text-muted">চুক্তি অনুসারে প্রারম্ভিক উত্তোলনে লাভ বাতিল বা প্রযোজ্য চার্জ</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">নিট ফেরত প্রদান (BDT) <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="net_refund_amount" id="net_refund_amount" class="form-control" 
                                   value="{{ max(0, $investment->amount - $investment->paid_profit_total) }}" required>
                            <small class="text-muted">গ্রাহককে ব্যাংক বা নগদে যে অর্থ ফেরত দেওয়া হবে</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">ফেরত প্রদানের মাধ্যম <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-select" required>
                                <option value="Bank Transfer">ব্যাংক ট্রান্সফার / এনপিএসবি</option>
                                <option value="Cheque">অ্যাকাউন্ট পেয়ী চেক</option>
                                <option value="Cash">নগদ ফেরত</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">কর্তনের কারণ / চুক্তি রেফারেন্স</label>
                            <input type="text" name="deduction_reason" class="form-control" value="চুক্তি অনুযায়ী মেয়াদের পূর্বে উত্তোলন করায় প্রদত্ত লাভ মূলধন থেকে কর্তন।">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">নিষ্পত্তি অনুমোদন ও রসিদ তৈরি করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const origAmount = {{ $investment->amount }};
    const penaltyInput = document.getElementById('penalty_or_deduction');
    const netInput = document.getElementById('net_refund_amount');
    const dispPenalty = document.getElementById('dispPenalty');
    const dispNetRefund = document.getElementById('dispNetRefund');

    penaltyInput.addEventListener('input', function() {
        const penalty = parseFloat(penaltyInput.value) || 0;
        const net = Math.max(0, origAmount - penalty);
        netInput.value = net;
        dispPenalty.innerText = '৳' + penalty.toLocaleString('en-IN', {minimumFractionDigits: 2});
        dispNetRefund.innerText = '৳' + net.toLocaleString('en-IN', {minimumFractionDigits: 2});
    });
});
</script>
@endsection
