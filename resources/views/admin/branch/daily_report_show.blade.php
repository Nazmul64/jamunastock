@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-file-text-o text-danger me-2"></i> দৈনিক কার্যবিবরণী রিপোর্ট (Daily Official Report)
                    </h3>
                    <small class="text-muted">তারিখ: {{ $report->report_date->format('d F, Y (l)') }} | ব্রাঞ্চ: {{ $report->branch->name }}</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="window.print()">
                        <i class="fa fa-print me-1"></i> রিপোর্ট প্রিন্ট করুন
                    </button>
                    <a href="{{ route('admin.branch.daily_reports', ['branch_id' => $branch->id]) }}" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-arrow-left me-1"></i> ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="box border-0 shadow-sm rounded-4 mb-4 p-4 printableArea" style="background: #ffffff;">
                <!-- Company Official Header -->
                <div class="text-center pb-3 border-bottom mb-4">
                    <h2 class="fw-bold text-success mb-1" style="letter-spacing: 1px;">যমুনা স্টক মার্কেট পিএলসি</h2>
                    <h5 class="text-dark fw-bold mb-1">JAMUNA STOCK MARKET PLC</h5>
                    <p class="text-muted mb-1 fs-13">প্রধান কার্যালয়: ঢাকা, বাংলাদেশ | ওয়েব: www.jamunastock.com</p>
                    <div class="badge bg-danger text-white px-4 py-2 rounded-pill fs-13 text-uppercase fw-bold mt-2">
                        শাখার দৈনিক কার্যবিবরণী ও হিসাব বিবরণী (DAILY BRANCH REPORT)
                    </div>
                </div>

                <!-- Branch & Report Metadata -->
                <div class="row mb-4 fs-13">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="fw-bold text-muted" style="width: 140px;">ব্রাঞ্চের নাম:</td>
                                <td class="fw-bold text-dark">{{ $report->branch->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">ব্রাঞ্চ কোড:</td>
                                <td class="font-monospace text-primary fw-bold">{{ $report->branch->code }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">অবস্থান ও ঠিকানা:</td>
                                <td>{{ $report->branch->address }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="fw-bold text-muted" style="width: 140px;">রিপোর্টের তারিখ:</td>
                                <td class="fw-bold text-dark">{{ $report->report_date->format('d/m/Y') }} ({{ $report->report_date->format('l') }})</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">প্রস্তুতকারক:</td>
                                <td class="text-dark">{{ $report->submitter->name ?? 'পরিচালক' }} ({{ $report->submitter->email ?? '' }})</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">জমার সময়:</td>
                                <td class="text-muted">{{ $report->created_at->format('d M, Y h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- 1. Financial Performance Grid -->
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                    <i class="fa fa-money text-success me-1"></i> ১. আর্থিক সারসংক্ষেপ (Financial Summary)
                </h5>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="bg-light fs-13">
                            <tr>
                                <th>দিনের মোট বিক্রয়</th>
                                <th>মোট আদায় / আয়</th>
                                <th>মোট অনুমোদিত ব্যয়</th>
                                <th>দিনের নীট উদ্বৃত্ত</th>
                                <th>দিনশেষে নগদ ক্যাশ ব্যালেন্স</th>
                            </tr>
                        </thead>
                        <tbody class="fs-15 fw-bold">
                            <tr>
                                <td class="text-primary">৳{{ number_format($report->total_sales, 2) }}</td>
                                <td class="text-success">৳{{ number_format($report->total_income, 2) }}</td>
                                <td class="text-danger">৳{{ number_format($report->total_expense, 2) }}</td>
                                <td class="{{ $report->net_balance >= 0 ? 'text-success' : 'text-danger' }}">৳{{ number_format($report->net_balance, 2) }}</td>
                                <td class="text-dark">৳{{ number_format($report->cash_in_hand, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- 2. Attendance & Growth -->
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                    <i class="fa fa-users text-primary me-1"></i> ২. জনবল উপস্থিতি ও ব্যবসায়িক প্রবৃদ্ধি (HR & Business Growth)
                </h5>
                <div class="row mb-4">
                    <div class="col-md-3 col-6 text-center mb-2">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted fs-12">উপস্থিত কর্মচারী</span>
                            <h4 class="fw-bold text-success mb-0 mt-1">{{ $report->present_employees }} জন</h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 text-center mb-2">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted fs-12">অনুপস্থিত কর্মচারী</span>
                            <h4 class="fw-bold text-danger mb-0 mt-1">{{ $report->absent_employees }} জন</h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 text-center mb-2">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted fs-12">নতুন নিবন্ধিত গ্রাহক</span>
                            <h4 class="fw-bold text-primary mb-0 mt-1">{{ $report->new_customers }} জন</h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 text-center mb-2">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted fs-12">নতুন ব্যবসায়িক লিড</span>
                            <h4 class="fw-bold text-warning mb-0 mt-1">{{ $report->new_leads }} টি</h4>
                        </div>
                    </div>
                </div>

                <!-- 3. Operational Highlights & Action Notes -->
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                    <i class="fa fa-list-alt text-dark me-1"></i> ৩. দিনের কার্যবিবরণী ও অগ্রগতি নোটসমূহ
                </h5>
                <div class="mb-3">
                    <label class="fw-bold text-muted small">আজকের উল্লেখযোগ্য অর্জন ও বিক্রয় হাইলাইটস:</label>
                    <div class="p-3 bg-light rounded-3 text-dark fs-14">
                        {{ $report->daily_highlights ?: 'কোনো বিশেষ মন্তব্য নেই।' }}
                    </div>
                </div>
                <div class="mb-3">
                    <label class="fw-bold text-muted small">সমস্যা বা চ্যালেঞ্জসমূহ:</label>
                    <div class="p-3 bg-light rounded-3 text-dark fs-14">
                        {{ $report->challenges_faced ?: 'আজকের কোনো সমস্যা বা বিচ্যুতি পরিলক্ষিত হয়নি।' }}
                    </div>
                </div>
                <div class="mb-4">
                    <label class="fw-bold text-muted small">আগামীকালের কর্মপরিকল্পনা:</label>
                    <div class="p-3 bg-light rounded-3 text-dark fs-14">
                        {{ $report->tomorrow_plan ?: 'নিয়মিত কার্যক্রম অব্যাহত থাকবে।' }}
                    </div>
                </div>

                <!-- Signatures -->
                <div class="row pt-5 mt-4 text-center">
                    <div class="col-4">
                        <div class="border-top pt-2 fw-bold text-dark fs-13">ব্রাঞ্চ পরিচালক / প্রস্তুতকারক</div>
                        <small class="text-muted">{{ $report->submitter->name ?? 'স্বাক্ষরিত' }}</small>
                    </div>
                    <div class="col-4">
                        <div class="border-top pt-2 fw-bold text-dark fs-13">হিসাব নিরীক্ষক / অডিট</div>
                        <small class="text-muted">যমুনা স্টক মার্কেট পিএলসি</small>
                    </div>
                    <div class="col-4">
                        <div class="border-top pt-2 fw-bold text-dark fs-13">প্রধান কার্যালয় অনুমোদন</div>
                        <small class="text-muted">ব্যবস্থাপনা পরিচালক / সিইও</small>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
