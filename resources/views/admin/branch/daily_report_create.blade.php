@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-pencil-square-o text-danger me-2"></i> দৈনিক রিপোর্ট ফর্ম (Submit Daily Report)
                    </h3>
                    <small class="text-muted">প্রধান কার্যালয়ে আজকের সামগ্রিক কার্যক্রম ও আর্থিক হিসাব প্রেরণ</small>
                </div>
                <a href="{{ route('admin.branch.daily_reports', ['branch_id' => $branch->id]) }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> রিপোর্ট তালিকায় ফিরুন
                </a>
            </div>
        </div>

        <section class="content">
            <div class="box border-0 shadow-xs rounded-4 mb-4">
                <div class="box-header py-3 px-4 bg-light border-bottom">
                    <h5 class="box-title fw-bold text-dark mb-0">{{ $branch->name }} — দৈনিক কার্যবিবরণী ফরম</h5>
                </div>
                <form action="{{ route('admin.branch.daily_reports.store', ['branch_id' => $branch->id]) }}" method="POST">
                    @csrf
                    <div class="box-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="fw-bold mb-1">রিপোর্টের তারিখ (Report Date) <span class="text-danger">*</span></label>
                                <input type="date" name="report_date" class="form-control rounded-pill fw-bold" value="{{ $reportDate }}" required>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="fw-bold mb-1">রিপোর্ট প্রস্তুতকারক</label>
                                <input type="text" class="form-control rounded-pill bg-light" value="{{ Auth::user()->name }} ({{ Auth::user()->role }})" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold mb-1">শাখার নাম</label>
                                <input type="text" class="form-control rounded-pill bg-light" value="{{ $branch->name }}" readonly>
                            </div>
                        </div>

                        <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                            <i class="fa fa-money me-1"></i> ১. আর্থিক সারসংক্ষেপ (Financial Summary - ক্যাশবুক হতে স্বয়ংক্রিয় লোড)
                        </h6>

                        <div class="row mb-4">
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="fw-bold mb-1 text-success">আজকের মোট আয় (টাকা)</label>
                                <input type="number" step="0.01" name="total_income" class="form-control rounded-pill fw-bold text-success" value="{{ $totalIncome }}">
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="fw-bold mb-1 text-danger">আজকের মোট ব্যয় (টাকা)</label>
                                <input type="number" step="0.01" name="total_expense" class="form-control rounded-pill fw-bold text-danger" value="{{ $totalExpense }}">
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="fw-bold mb-1 text-primary">আজকের মোট বিক্রয় (Sales)</label>
                                <input type="number" step="0.01" name="total_sales" class="form-control rounded-pill fw-bold text-primary" value="{{ $totalIncome }}">
                            </div>
                            <div class="col-md-3">
                                <label class="fw-bold mb-1 text-dark">দিনশেষে ক্যাশ ইন হ্যান্ড (টাকা)</label>
                                <input type="number" step="0.01" name="cash_in_hand" class="form-control rounded-pill fw-bold" value="{{ $branch->current_cash_balance ?: $netBalance }}">
                            </div>
                        </div>

                        <h6 class="fw-bold text-success mb-3 border-bottom pb-2">
                            <i class="fa fa-users me-1"></i> ২. মানবসম্পদ ও গ্রাহক প্রবৃদ্ধি (HR & Growth Metrics)
                        </h6>

                        <div class="row mb-4">
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="fw-bold mb-1">উপস্থিত কর্মচারী সংখ্যা</label>
                                <input type="number" name="present_employees" class="form-control rounded-pill text-success fw-bold" value="{{ $presentEmployees }}">
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="fw-bold mb-1">অনুপস্থিত কর্মচারী সংখ্যা</label>
                                <input type="number" name="absent_employees" class="form-control rounded-pill text-danger fw-bold" value="{{ $absentEmployees }}">
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="fw-bold mb-1">আজকের নতুন গ্রাহক</label>
                                <input type="number" name="new_customers" class="form-control rounded-pill fw-bold" value="{{ $newCustomers }}">
                            </div>
                            <div class="col-md-3">
                                <label class="fw-bold mb-1">আজকের নতুন ব্যবসায়িক লিড</label>
                                <input type="number" name="new_leads" class="form-control rounded-pill fw-bold" value="0">
                            </div>
                        </div>

                        <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">
                            <i class="fa fa-commenting-o me-1"></i> ৩. দিনের সারসংক্ষেপ ও অগ্রগতি (Operational Notes)
                        </h6>

                        <div class="form-group mb-3">
                            <label class="fw-bold mb-1">আজকের গুরুত্বপূর্ণ অর্জন ও বিক্রয় হাইলাইটস (Daily Highlights)</label>
                            <textarea name="daily_highlights" class="form-control rounded-3" rows="3" placeholder="আজকের উল্লেখযোগ্য পণ্য বিক্রয়, মিটিং, গ্রাহক সেবা ও অর্জনের বিবরণ লিখুন..."></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold mb-1">কোনো সমস্যা বা চ্যালেঞ্জ (Challenges / Issues Faced)</label>
                            <textarea name="challenges_faced" class="form-control rounded-3" rows="2" placeholder="ব্রাঞ্চ পরিচালনায় কোনো সমস্যা বা হেড অফিসের সহায়তা প্রয়োজন হলে উল্লেখ করুন..."></textarea>
                        </div>

                        <div class="form-group mb-2">
                            <label class="fw-bold mb-1">আগামীকালের কর্মপরিকল্পনা (Tomorrow's Plan)</label>
                            <textarea name="tomorrow_plan" class="form-control rounded-3" rows="2" placeholder="পরবর্তী কার্যদিবসের লক্ষ্যমাত্রা ও কাজের পরিকল্পনা..."></textarea>
                        </div>
                    </div>
                    <div class="box-footer py-3 px-4 bg-light text-end">
                        <button type="submit" class="btn btn-danger rounded-pill px-5 fw-bold shadow-sm">
                            <i class="fa fa-paper-plane me-1"></i> প্রধান কার্যালয়ে রিপোর্ট জমা দিন
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
