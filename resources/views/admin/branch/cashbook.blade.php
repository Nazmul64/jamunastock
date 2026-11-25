@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-book text-success me-2"></i> ক্যাশবুক ও অর্থ ব্যবস্থাপনা (F - Finance & Cashbook)
                    </h3>
                    <small class="text-muted">ব্রাঞ্চের দৈনিক আয়, ব্যয়, ক্যাশ ইন-আউট, ভাউচার ও সমাপনী ব্যালেন্স হিসাব</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success rounded-pill px-3 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#incomeModal" data-toggle="modal" data-target="#incomeModal">
                        <i class="fa fa-plus-circle me-1"></i> দৈনিক আয় যোগ
                    </button>
                    <button type="button" class="btn btn-danger rounded-pill px-3 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#expenseModal" data-toggle="modal" data-target="#expenseModal">
                        <i class="fa fa-minus-circle me-1"></i> দৈনিক ব্যয় যোগ
                    </button>
                    <a href="{{ route('admin.branch.dashboard', ['branch_id' => $branch->id]) }}" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success rounded-pill px-4 py-2 mb-4">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                </div>
            @endif

            <!-- 3 Financial Overview Cards -->
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-body p-3">
                            <span class="text-muted fs-12 fw-bold text-uppercase">সর্বমোট জমা / আয় (Total Income)</span>
                            <h3 class="fw-bold text-success mb-0 mt-1">৳{{ number_format($totalIncome, 2) }}</h3>
                            <small class="text-muted fs-11">বিক্রয় ও মেম্বারশিপ ফি বাবদ আয়</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-body p-3">
                            <span class="text-muted fs-12 fw-bold text-uppercase">সর্বমোট খরচ / ব্যয় (Total Expense)</span>
                            <h3 class="fw-bold text-danger mb-0 mt-1">৳{{ number_format($totalExpense, 2) }}</h3>
                            <small class="text-muted fs-11">অফিস ভাড়া, ইউটিলিটি ও আপ্যায়ন</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-body p-3">
                            <span class="text-muted fs-12 fw-bold text-uppercase">ক্যাশ ইন হ্যান্ড উদ্বৃত্ত (Net Balance)</span>
                            <h3 class="fw-bold {{ $netBalance >= 0 ? 'text-primary' : 'text-danger' }} mb-0 mt-1">
                                ৳{{ number_format($netBalance, 2) }}
                            </h3>
                            <small class="text-muted fs-11">পেটি ক্যাশ বরাদ্দ: ৳{{ number_format($branch->petty_cash_limit ?: 50000, 2) }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cashbook Ledger Table & Filters -->
            <div class="box border-0 shadow-xs rounded-4 mb-4">
                <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="box-title fw-bold text-dark mb-0">দৈনিক ক্যাশবুক খতিয়ান (Cashbook Transactions)</h5>
                    <form method="GET" class="d-flex align-items-center gap-2 m-0 flex-wrap">
                        <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                        <input type="date" name="start_date" class="form-control form-control-sm rounded-pill" value="{{ request('start_date') }}">
                        <input type="date" name="end_date" class="form-control form-control-sm rounded-pill" value="{{ request('end_date') }}">
                        <select name="type" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                            <option value="">-- সকল প্রকার লেনদেন --</option>
                            <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>শুধু আয় (Income)</option>
                            <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>শুধু ব্যয় (Expense)</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">ফিল্টার</button>
                    </form>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted fs-12">
                                <tr>
                                    <th class="ps-4">তারিখ ও ভাউচার</th>
                                    <th>প্রকার</th>
                                    <th>খাত (Category)</th>
                                    <th>বিবরণ (Particulars)</th>
                                    <th>মাধ্যম</th>
                                    <th>গ্রহীতা / জমাকারী</th>
                                    <th class="text-end pe-4">পরিমাণ (টাকা)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cashbooks as $cb)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark fs-13">{{ $cb->transaction_date->format('d M, Y') }}</div>
                                            <small class="text-primary font-monospace">{{ $cb->voucher_no ?: '-' }}</small>
                                        </td>
                                        <td>
                                            @if($cb->transaction_type === 'income')
                                                <span class="badge bg-success-light text-success rounded-pill px-3 py-1 fw-bold fs-11">আয় (IN)</span>
                                            @else
                                                <span class="badge bg-danger-light text-danger rounded-pill px-3 py-1 fw-bold fs-11">ব্যয় (OUT)</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1 fs-11">
                                                {{ ucfirst(str_replace('_', ' ', $cb->category)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark fs-13">{{ $cb->particulars }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-light text-secondary rounded-pill px-2 py-1 fs-11">
                                                {{ strtoupper($cb->payment_method) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">{{ $cb->received_by_or_paid_to ?: '-' }}</span>
                                        </td>
                                        <td class="text-end pe-4">
                                            @if($cb->transaction_type === 'income')
                                                <span class="fw-bold text-success fs-14">+৳{{ number_format($cb->amount, 2) }}</span>
                                            @else
                                                <span class="fw-bold text-danger fs-14">-৳{{ number_format($cb->amount, 2) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            কোনো ক্যাশবুক লেনদেন পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($cashbooks->hasPages())
                    <div class="box-footer py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2 border-top">
                        <div class="text-muted fs-13">মোট {{ $cashbooks->total() }} টির মধ্যে {{ $cashbooks->firstItem() }} থেকে {{ $cashbooks->lastItem() }} দেখানো হচ্ছে</div>
                        <div>{{ $cashbooks->links('pagination::bootstrap-5') }}</div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

<!-- Modal: Add Income -->
<div class="modal fade" id="incomeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0"><i class="fa fa-plus-circle me-2"></i> দৈনিক আয় এন্ট্রি (Add Daily Income)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.branch.income.store', ['branch_id' => $branch->id]) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">তারিখ <span class="text-danger">*</span></label>
                        <input type="date" name="transaction_date" class="form-control rounded-pill" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">আয়ের খাত (Category) <span class="text-danger">*</span></label>
                        <select name="category" class="form-select rounded-pill" required>
                            <option value="product_sale">পণ্য বিক্রয় (Product Sale)</option>
                            <option value="fmcg_sale">FMCG ভোগ্যপণ্য বিক্রয়</option>
                            <option value="membership_fee">মেম্বারশিপ ফি গ্রহণ</option>
                            <option value="property_booking">প্রপার্টি বুকিং মানি গ্রহণ</option>
                            <option value="misc_income">অন্যান্য সেবা আয়</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">পরিমাণ (টাকা) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" class="form-control rounded-pill" placeholder="যেমন: 5000" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">পেমেন্ট মেথড <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select rounded-pill" required>
                            <option value="cash">নগদ (Cash)</option>
                            <option value="bank">ব্যাংক (Bank)</option>
                            <option value="bkash">বিকাশ (bKash)</option>
                            <option value="nagad">নগদ (Nagad)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">বিবরণ (Particulars) <span class="text-danger">*</span></label>
                        <textarea name="particulars" class="form-control rounded-3" rows="2" placeholder="লেনদেনের বিবরণ লিখুন..." required></textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label class="fw-bold mb-1">জমাকারী / আদায়কারী</label>
                        <input type="text" name="received_by_or_paid_to" class="form-control rounded-pill" placeholder="কাউন্টার ক্যাশিয়ার">
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Add Expense -->
<div class="modal fade" id="expenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-danger text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0"><i class="fa fa-minus-circle me-2"></i> দৈনিক ব্যয় এন্ট্রি (Add Daily Expense)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.branch.expense.store', ['branch_id' => $branch->id]) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">তারিখ <span class="text-danger">*</span></label>
                        <input type="date" name="transaction_date" class="form-control rounded-pill" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">ব্যয়ের খাত (Category) <span class="text-danger">*</span></label>
                        <select name="category" class="form-select rounded-pill" required>
                            <option value="entertainment_tea">আপ্যায়ন ও চা-নাস্তা</option>
                            <option value="stationery">অফিস স্টেশনরি ও ফটোকপি</option>
                            <option value="conveyance">যাতায়াত ও কনভেয়েন্স</option>
                            <option value="electricity">বিদ্যুৎ ও ইউটিলিটি বিল</option>
                            <option value="internet">ইন্টারনেট ও ফোন বিল</option>
                            <option value="repair_maintenance">মেরামত ও রক্ষণাবেক্ষণ</option>
                            <option value="office_rent">অফিস ভাড়া</option>
                            <option value="misc_expense">অন্যান্য জরুরি খরচ</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">পরিমাণ (টাকা) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" class="form-control rounded-pill" placeholder="যেমন: 500" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">পেমেন্ট মেথড <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select rounded-pill" required>
                            <option value="cash">নগদ (Cash)</option>
                            <option value="bank">ব্যাংক (Bank)</option>
                            <option value="bkash">বিকাশ (bKash)</option>
                            <option value="nagad">নগদ (Nagad)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">বিবরণ / কারণ <span class="text-danger">*</span></label>
                        <textarea name="particulars" class="form-control rounded-3" rows="2" placeholder="খরচের বিস্তারিত বিবরণ লিখুন..." required></textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label class="fw-bold mb-1">গ্রহীতা / দোকানদার</label>
                        <input type="text" name="received_by_or_paid_to" class="form-control rounded-pill" placeholder="যেমন: আল নূর স্টেশনারি">
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
