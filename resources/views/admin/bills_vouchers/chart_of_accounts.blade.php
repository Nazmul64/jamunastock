@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-list-alt text-primary me-2"></i> চার্ট অব অ্যাকাউন্টস (Chart of Accounts - COA)
                    </h3>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের সকল হিসাবের খাত (সম্পদ, দায়, মূলধন, আয় ও ব্যয়) এবং এদের রিয়েল-টাইম ব্যালেন্স</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন হিসাবের খাত যোগ করুন
                    </button>
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

            <!-- 5 Accounting Class Overview Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl col-md-4 col-sm-6 col-12">
                    <div class="card border-0 shadow-sm p-3 border-start border-primary border-4 h-100">
                        <span class="text-muted small fw-bold">১. মোট সম্পদ (Assets - 1000s)</span>
                        <h4 class="fw-bold text-primary mb-0 mt-1">৳{{ number_format($totalAssets, 2) }}</h4>
                    </div>
                </div>
                <div class="col-xl col-md-4 col-sm-6 col-12">
                    <div class="card border-0 shadow-sm p-3 border-start border-danger border-4 h-100">
                        <span class="text-muted small fw-bold">২. মোট দায় (Liabilities - 2000s)</span>
                        <h4 class="fw-bold text-danger mb-0 mt-1">৳{{ number_format($totalLiabilities, 2) }}</h4>
                    </div>
                </div>
                <div class="col-xl col-md-4 col-sm-6 col-12">
                    <div class="card border-0 shadow-sm p-3 border-start border-purple border-4 h-100">
                        <span class="text-muted small fw-bold">৩. মূলধন (Equity - 3000s)</span>
                        <h4 class="fw-bold text-purple mb-0 mt-1">৳{{ number_format($totalEquity, 2) }}</h4>
                    </div>
                </div>
                <div class="col-xl col-md-4 col-sm-6 col-12">
                    <div class="card border-0 shadow-sm p-3 border-start border-success border-4 h-100">
                        <span class="text-muted small fw-bold">৪. মোট রাজস্ব (Income - 4000s)</span>
                        <h4 class="fw-bold text-success mb-0 mt-1">৳{{ number_format($totalIncome, 2) }}</h4>
                    </div>
                </div>
                <div class="col-xl col-md-4 col-sm-6 col-12">
                    <div class="card border-0 shadow-sm p-3 border-start border-warning border-4 h-100">
                        <span class="text-muted small fw-bold">৫. মোট ব্যয় (Expenses - 5000s)</span>
                        <h4 class="fw-bold text-warning mb-0 mt-1">৳{{ number_format($totalExpense, 2) }}</h4>
                    </div>
                </div>
            </div>

            <!-- Tabbed View for Account Classes -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom p-0">
                    <ul class="nav nav-tabs nav-bordered" id="coaTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active fw-bold py-3 px-4 text-primary" id="assets-tab" data-bs-toggle="tab" href="#assets" role="tab">
                                <i class="fa fa-money me-1"></i> ১. সম্পদ (Assets) <span class="badge bg-primary ms-1">{{ $accountsGrouped['asset']->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold py-3 px-4 text-danger" id="liabilities-tab" data-bs-toggle="tab" href="#liabilities" role="tab">
                                <i class="fa fa-credit-card me-1"></i> ২. দায় (Liabilities) <span class="badge bg-danger ms-1">{{ $accountsGrouped['liability']->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold py-3 px-4 text-purple" id="equity-tab" data-bs-toggle="tab" href="#equity" role="tab">
                                <i class="fa fa-pie-chart me-1"></i> ৩. মালিকানাস্বত্ব (Equity) <span class="badge bg-secondary ms-1">{{ $accountsGrouped['equity']->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold py-3 px-4 text-success" id="income-tab" data-bs-toggle="tab" href="#income" role="tab">
                                <i class="fa fa-line-chart me-1"></i> ৪. আয় / রাজস্ব (Income) <span class="badge bg-success ms-1">{{ $accountsGrouped['income']->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold py-3 px-4 text-warning" id="expense-tab" data-bs-toggle="tab" href="#expense" role="tab">
                                <i class="fa fa-shopping-cart me-1"></i> ৫. খরচ / ব্যয় (Expenses) <span class="badge bg-warning text-dark ms-1">{{ $accountsGrouped['expense']->count() }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-0">
                    <div class="tab-content" id="coaTabsContent">
                        @foreach(['asset' => 'assets', 'liability' => 'liabilities', 'equity' => 'equity', 'income' => 'income', 'expense' => 'expense'] as $typeKey => $tabId)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $tabId }}" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th style="width: 12%;">হিসাব কোড</th>
                                                <th style="width: 32%;">হিসাবের খাতের নাম (Account Name)</th>
                                                <th style="width: 18%;">সাব-ক্যাটাগরি</th>
                                                <th style="width: 15%;" class="text-end">প্রারম্ভিক জের (৳)</th>
                                                <th style="width: 15%;" class="text-end">বর্তমান জের (Current Balance ৳)</th>
                                                <th style="width: 8%;" class="text-center">খতিয়ান</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($accountsGrouped[$typeKey] as $acc)
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-dark font-monospace fs-12 px-2 py-1">{{ $acc->code }}</span>
                                                    </td>
                                                    <td>
                                                        <strong class="text-dark">{{ $acc->name }}</strong>
                                                        @if($acc->description)
                                                            <div class="text-muted small">{{ $acc->description }}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-secondary border">{{ $acc->category }}</span>
                                                    </td>
                                                    <td class="text-end text-muted">৳{{ number_format($acc->opening_balance, 2) }}</td>
                                                    <td class="text-end fw-bold {{ $acc->current_balance < 0 ? 'text-danger' : 'text-dark' }} fs-14">
                                                        ৳{{ number_format($acc->current_balance, 2) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('admin.bills_vouchers.reports.general_ledger', ['account_id' => $acc->id]) }}" class="btn btn-sm btn-outline-primary" title="জেনারেল লেজার দেখুন">
                                                            <i class="fa fa-book"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted">এই বিভাগে কোনো হিসাবের খাত তৈরি হয়নি।</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal: Add New Account Head -->
<div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.bills_vouchers.chart_of_accounts.store') }}" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="addAccountModalLabel">
                    <i class="fa fa-plus-circle me-2"></i> নতুন হিসাবের খাত (Account Head) যোগ করুন
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">হিসাবের মূল ধরণ (Account Type) <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="asset">সম্পদ (Asset - 1000s)</option>
                        <option value="liability">দায় (Liability - 2000s)</option>
                        <option value="equity">মালিকানাস্বত্ব (Equity - 3000s)</option>
                        <option value="income">আয় / রাজস্ব (Income - 4000s)</option>
                        <option value="expense">ব্যয় / খরচ (Expense - 5000s)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">হিসাবের খাতের নাম <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="যেমন: নতুন ব্যাংক অ্যাকাউন্ট, অফিস ইন্টারকম..." required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">হিসাব কোড (ঐচ্ছিক - খালি রাখলে স্বয়ংক্রিয় তৈরি হবে)</label>
                    <input type="text" name="code" class="form-control" placeholder="যেমন: 1025, 5090">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">সাব-ক্যাটাগরি</label>
                    <input type="text" name="category" class="form-control" placeholder="যেমন: current_asset, operating_expense...">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">প্রারম্ভিক জের (Opening Balance ৳)</label>
                    <input type="number" step="0.01" name="opening_balance" class="form-control" value="0.00">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">বিবরণ (ঐচ্ছিক)</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="হিসাবের খাত সম্পর্কে সংক্ষেপ নোট..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="submit" class="btn btn-primary fw-bold">
                    <i class="fa fa-save me-1"></i> হিসাবের খাত সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

