@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-calculator text-success me-2"></i>সেন্ট্রাল ফিন্যান্স ও আয়-ব্যয় লেজার</h4>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের সার্বিক আর্থিক আয়, পরিচালনা ব্যয় এবং নিট ব্যালেন্স তদারকি</p>
                </div>
                <div>
                    <a href="{{ route('admin.finance.unified_accounts') }}" class="btn btn-outline-success btn-sm me-2">
                        <i class="fa fa-university me-1"></i>একীভূত হিসাব বিবরণী (Unified Accounts)
                    </a>
                    <button type="button" class="btn btn-success btn-sm font-weight-bold" data-bs-toggle="modal" data-bs-target="#addFinanceModal">
                        <i class="fa fa-plus me-1"></i>নতুন আয় / ব্যয় যুক্ত করুন
                    </button>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- 3 Stat Boxes for Central Finances -->
            <div class="row">
                <div class="col-md-4">
                    <div class="box bg-gradient-success text-white">
                        <div class="box-body">
                            <span class="text-white-50 small d-block">সর্বমোট আয় (Total Income)</span>
                            <h2 class="fw-bold mb-0">৳ {{ number_format($totalIncome) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box bg-gradient-danger text-white">
                        <div class="box-body">
                            <span class="text-white-50 small d-block">সর্বমোট ব্যয় (Total Expense)</span>
                            <h2 class="fw-bold mb-0">৳ {{ number_format($totalExpense) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box bg-gradient-primary text-white">
                        <div class="box-body">
                            <span class="text-white-50 small d-block">নিট উদ্বৃত্ত (Net Balance)</span>
                            <h2 class="fw-bold mb-0">৳ {{ number_format($netBalance) }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h5 class="box-title">আর্থিক লেনদেন লেজার</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.finance.index') }}" class="btn btn-xs {{ empty($type) ? 'btn-success' : 'btn-default' }}">সকল</a>
                        <a href="{{ route('admin.finance.index', ['entry_type' => 'income']) }}" class="btn btn-xs {{ $type == 'income' ? 'btn-success' : 'btn-default' }}">আয় (Income)</a>
                        <a href="{{ route('admin.finance.index', ['entry_type' => 'expense']) }}" class="btn btn-xs {{ $type == 'expense' ? 'btn-danger' : 'btn-default' }}">ব্যয় (Expense)</a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>কোড</th>
                                    <th>তারিখ</th>
                                    <th>ধরন</th>
                                    <th>খাত / ক্যাটাগরি</th>
                                    <th>পরিমাণ</th>
                                    <th>পেমেন্ট মাধ্যম</th>
                                    <th>বিবরণ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entries as $e)
                                    <tr>
                                        <td><strong>{{ $e->transaction_code }}</strong></td>
                                        <td>{{ $e->entry_date ? $e->entry_date->format('d M, Y') : '-' }}</td>
                                        <td>
                                            @if($e->entry_type == 'income')
                                                <span class="badge bg-success"><i class="fa fa-arrow-down me-1"></i>আয়</span>
                                            @else
                                                <span class="badge bg-danger"><i class="fa fa-arrow-up me-1"></i>ব্যয়</span>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-light text-dark">{{ $e->category }}</span></td>
                                        <td>
                                            <strong class="{{ $e->entry_type == 'income' ? 'text-success' : 'text-danger' }} fs-6">
                                                {{ $e->entry_type == 'income' ? '+' : '-' }} ৳ {{ number_format($e->amount) }}
                                            </strong>
                                        </td>
                                        <td>{{ $e->payment_method }}</td>
                                        <td class="small text-muted">{{ Str::limit($e->description, 50) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">কোনো লেনদেন রেকর্ড নেই।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $entries->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Add Transaction Modal -->
<div class="modal fade" id="addFinanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold">নতুন আর্থিক লেনদেন যুক্ত করুন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.finance.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">লেনদেনের ধরন <span class="text-danger">*</span></label>
                        <select name="entry_type" class="form-select" required>
                            <option value="income">আয় (Income)</option>
                            <option value="expense">ব্যয় (Expense)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">খাত / ক্যাটাগরি <span class="text-danger">*</span></label>
                        <input type="text" name="category" class="form-control" required placeholder="উদাঃ অফিস ভাড়া / কৃষিপণ্য বিক্রয় / বিদ্যুৎ বিল">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">পরিমাণ (টাকা) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" min="1" required placeholder="উদাঃ ১০,০০০">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">তারিখ <span class="text-danger">*</span></label>
                        <input type="date" name="entry_date" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">পেমেন্ট মাধ্যম <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select" required>
                            <option value="Cash">নগদ (Cash)</option>
                            <option value="Bank">ব্যাংক অ্যাকাউন্ট (Bank)</option>
                            <option value="bKash">বিকাশ (bKash)</option>
                            <option value="Nagad">নগদ (Nagad)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">বিবরণ / নোট</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="প্রয়োজনীয় বিবরণ..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success font-weight-bold">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

