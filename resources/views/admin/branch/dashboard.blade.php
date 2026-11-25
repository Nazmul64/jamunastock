@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-building-o text-success me-2"></i> ব্রাঞ্চ ড্যাশবোর্ড (Branch Portal)
                    </h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb mb-0 bg-transparent p-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa fa-home"></i> হোম</a></li>
                                <li class="breadcrumb-item active" aria-current="page">A–Z ব্রাঞ্চ ম্যানেজমেন্ট</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <!-- Admin Branch Switcher (If Super Admin / Admin) -->
                @if($isAdmin && $allBranches->count() > 1)
                    <div class="d-flex align-items-center gap-2 bg-white p-2 rounded-pill shadow-xs border">
                        <span class="text-muted small ps-2 fw-bold"><i class="fa fa-exchange text-primary me-1"></i> ব্রাঞ্চ পরিবর্তন:</span>
                        <form method="GET" action="{{ route('admin.branch.dashboard') }}" class="m-0">
                            <select name="branch_id" class="form-select form-select-sm rounded-pill border-0 fw-bold text-success bg-light" onchange="this.form.submit()">
                                @foreach($allBranches as $b)
                                    <option value="{{ $b->id }}" {{ $branch->id == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }} ({{ $b->code }})
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <!-- Active Branch Info Banner -->
            <div class="box bg-gradient-primary text-white border-0 shadow-sm rounded-4 mb-4 overflow-hidden position-relative">
                <div class="box-body p-4 position-relative" style="z-index: 2;">
                    <div class="row align-items-center">
                        <div class="col-lg-8 col-md-7">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="avatar-lg bg-white bg-opacity-20 text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-24 shadow-sm" style="width: 58px; height: 58px;">
                                    <i class="fa fa-university"></i>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <h3 class="fw-bold text-white mb-0">{{ $branch->name }}</h3>
                                        <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill">{{ $branch->code ?: 'BR-ACTIVE' }}</span>
                                        <span class="badge bg-success text-white px-2 py-1 rounded-pill fs-11"><i class="fa fa-check-circle"></i> সচল ব্রাঞ্চ</span>
                                    </div>
                                    <p class="mb-0 text-white-50 fs-13 mt-1">
                                        <i class="fa fa-map-marker me-1 text-warning"></i> {{ $branch->address ?: 'শাখার ঠিকানা উপলব্ধ নেই' }} | 
                                        <i class="fa fa-phone me-1 text-warning"></i> {{ $branch->phone ?: 'ফোন উপলব্ধ নেই' }} |
                                        <i class="fa fa-clock-o me-1 text-warning"></i> {{ $branch->opening_time }} - {{ $branch->closing_time }} (ছুটি: {{ $branch->weekly_holiday }})
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-5 text-md-end mt-3 mt-md-0">
                            <div class="d-flex align-items-center justify-content-md-end gap-2 flex-wrap">
                                <a href="{{ route('admin.branch.daily_reports.create', ['branch_id' => $branch->id]) }}" class="btn btn-warning text-dark fw-bold rounded-pill px-3 py-2 shadow-sm">
                                    <i class="fa fa-file-text-o me-1"></i> দৈনিক রিপোর্ট পেশ করুন
                                </a>
                                <a href="{{ route('admin.branch.policy_az', ['branch_id' => $branch->id]) }}" class="btn btn-outline-light rounded-pill px-3 py-2">
                                    <i class="fa fa-book me-1"></i> A–Z নীতিমালা
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4 Quick Key Metrics Cards -->
            <div class="row">
                <!-- 1. Today Cash In / Income -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted fs-12 fw-bold text-uppercase">আজকের মোট আয় (Income)</span>
                                    <h3 class="fw-bold text-success mb-0 mt-1">৳{{ number_format($todayIncome, 2) }}</h3>
                                    <small class="text-muted fs-11">মাসিক আয়: ৳{{ number_format($monthlyIncome, 2) }}</small>
                                </div>
                                <div class="avatar-sm rounded-circle bg-success-light text-success d-flex align-items-center justify-content-center fs-20" style="width: 48px; height: 48px;">
                                    <i class="fa fa-arrow-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Today Cash Out / Expense -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted fs-12 fw-bold text-uppercase">আজকের মোট ব্যয় (Expense)</span>
                                    <h3 class="fw-bold text-danger mb-0 mt-1">৳{{ number_format($todayExpense, 2) }}</h3>
                                    <small class="text-muted fs-11">মাসিক ব্যয়: ৳{{ number_format($monthlyExpense, 2) }}</small>
                                </div>
                                <div class="avatar-sm rounded-circle bg-danger-light text-danger d-flex align-items-center justify-content-center fs-20" style="width: 48px; height: 48px;">
                                    <i class="fa fa-arrow-up"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Current Cash in Hand / Petty Cash -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted fs-12 fw-bold text-uppercase">ক্যাশ ইন হ্যান্ড (Cash Balance)</span>
                                    <h3 class="fw-bold text-primary mb-0 mt-1">৳{{ number_format($branch->current_cash_balance ?: 0, 2) }}</h3>
                                    <small class="text-muted fs-11">পেটি ক্যাশ লিমিট: ৳{{ number_format($branch->petty_cash_limit ?: 50000, 2) }}</small>
                                </div>
                                <div class="avatar-sm rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center fs-20" style="width: 48px; height: 48px;">
                                    <i class="fa fa-money"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Employee Attendance Today -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted fs-12 fw-bold text-uppercase">আজকের স্টাফ হাজিরা (Attendance)</span>
                                    <h3 class="fw-bold text-dark mb-0 mt-1">
                                        <span class="text-success">{{ $presentToday }}</span> / <span class="text-muted">{{ $employeesCount }}</span>
                                    </h3>
                                    <small class="text-danger fs-11"><i class="fa fa-times-circle"></i> অনুপস্থিত: {{ $absentToday }} জন</small>
                                </div>
                                <div class="avatar-sm rounded-circle bg-warning-light text-warning d-flex align-items-center justify-content-center fs-20" style="width: 48px; height: 48px;">
                                    <i class="fa fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action Shortcuts Bar -->
            <div class="box border-0 shadow-xs rounded-4 mb-4">
                <div class="box-body py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <span class="fw-bold text-dark fs-14"><i class="fa fa-bolt text-warning me-1"></i> দ্রুত একশন (Quick Actions):</span>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.branch.cashbook', ['branch_id' => $branch->id]) }}#addIncomeModal" class="btn btn-sm btn-outline-success rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#quickIncomeModal" data-toggle="modal" data-target="#quickIncomeModal">
                                <i class="fa fa-plus-circle me-1"></i> দৈনিক আয় যোগ
                            </a>
                            <a href="{{ route('admin.branch.cashbook', ['branch_id' => $branch->id]) }}#addExpenseModal" class="btn btn-sm btn-outline-danger rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#quickExpenseModal" data-toggle="modal" data-target="#quickExpenseModal">
                                <i class="fa fa-minus-circle me-1"></i> দৈনিক ব্যয় যোগ
                            </a>
                            <a href="{{ route('admin.branch.attendance', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="fa fa-calendar-check-o me-1"></i> আজকের হাজিরা
                            </a>
                            <a href="{{ route('admin.branch.membership', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3">
                                <i class="fa fa-id-card-o me-1"></i> নতুন মেম্বারশিপ ইস্যু
                            </a>
                            <a href="{{ route('admin.branch.commissions', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                <i class="fa fa-percent me-1"></i> কমিশন লেজার
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- A–Z Interactive Module Grid (The User Core Requirement) -->
            <div class="box border-0 shadow-xs rounded-4 mb-4">
                <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="box-title fw-bold text-dark mb-0">
                            <i class="fa fa-th-large text-primary me-2"></i> Jamuna Stock Market PLC — ব্রাঞ্চ পরিচালনা ও নীতিমালা (A–Z সিস্টেম)
                        </h4>
                        <small class="text-muted">কোম্পানির অফিশিয়াল নীতিমালা ও ব্রাঞ্চ ডিরেক্টর সিস্টেমের সম্পূর্ণ মডিউল ডিরেক্টরি</small>
                    </div>
                    <a href="{{ route('admin.branch.policy_az', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                        <i class="fa fa-book me-1"></i> সম্পূর্ণ A–Z রুলবুক
                    </a>
                </div>
                <div class="box-body p-4">
                    <div class="row g-3">
                        <!-- A: Administration -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.branch.profile', ['branch_id' => $branch->id]) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white hover-shadow transition-all h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-primary text-white rounded-circle fw-bold fs-12" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">A</span>
                                        <h6 class="fw-bold text-dark mb-0">Administration</h6>
                                    </div>
                                    <p class="text-muted fs-12 mb-0">প্রশাসনিক ব্যবস্থাপনা ও ব্রাঞ্চ প্রোফাইল</p>
                                </div>
                            </a>
                        </div>

                        <!-- B: Branch Operations -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.branch.tasks', ['branch_id' => $branch->id]) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white hover-shadow transition-all h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-success text-white rounded-circle fw-bold fs-12" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">B</span>
                                        <h6 class="fw-bold text-dark mb-0">Branch Operations</h6>
                                    </div>
                                    <p class="text-muted fs-12 mb-0">দৈনিক কার্যক্রম ও টাস্ক শিডিউল ({{ $pendingTasks }} টি বাকি)</p>
                                </div>
                            </a>
                        </div>

                        <!-- C: Commission Management -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.branch.commissions', ['branch_id' => $branch->id]) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white hover-shadow transition-all h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-warning text-dark rounded-circle fw-bold fs-12" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">C</span>
                                        <h6 class="fw-bold text-dark mb-0">Commission Mgmt</h6>
                                    </div>
                                    <p class="text-muted fs-12 mb-0">পণ্য, প্রপার্টি ও মেম্বারশিপ কমিশন</p>
                                </div>
                            </a>
                        </div>

                        <!-- D: Daily Reporting -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.branch.daily_reports', ['branch_id' => $branch->id]) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white hover-shadow transition-all h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-danger text-white rounded-circle fw-bold fs-12" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">D</span>
                                        <h6 class="fw-bold text-dark mb-0">Daily Reporting</h6>
                                    </div>
                                    <p class="text-muted fs-12 mb-0">প্রধান অফিসে দৈনিক রিপোর্ট সাবমিশন</p>
                                </div>
                            </a>
                        </div>

                        <!-- E & J: Employee & Job Responsibility -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.branch.employees', ['branch_id' => $branch->id]) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white hover-shadow transition-all h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-info text-white rounded-circle fw-bold fs-12" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">E/J</span>
                                        <h6 class="fw-bold text-dark mb-0">Employee & Roles</h6>
                                    </div>
                                    <p class="text-muted fs-12 mb-0">কর্মচারী ব্যবস্থাপনা ও দায়িত্ব বণ্টন</p>
                                </div>
                            </a>
                        </div>

                        <!-- F: Financial Management & Cashbook -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.branch.cashbook', ['branch_id' => $branch->id]) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white hover-shadow transition-all h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-success text-white rounded-circle fw-bold fs-12" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">F</span>
                                        <h6 class="fw-bold text-dark mb-0">Finance & Cashbook</h6>
                                    </div>
                                    <p class="text-muted fs-12 mb-0">দৈনিক আয়-ব্যয়, ভাউচার ও ক্যাশবুক</p>
                                </div>
                            </a>
                        </div>

                        <!-- G & K: Growth & KPI -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.branch.kpi', ['branch_id' => $branch->id]) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white hover-shadow transition-all h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-secondary text-white rounded-circle fw-bold fs-12" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">G/K</span>
                                        <h6 class="fw-bold text-dark mb-0">Growth & KPI</h6>
                                    </div>
                                    <p class="text-muted fs-12 mb-0">টার্গেট বনাম অর্জন ও মূল্যায়ন রেটিং</p>
                                </div>
                            </a>
                        </div>

                        <!-- H: HR & Attendance -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.branch.attendance', ['branch_id' => $branch->id]) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white hover-shadow transition-all h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-primary text-white rounded-circle fw-bold fs-12" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">H</span>
                                        <h6 class="fw-bold text-dark mb-0">HR & Attendance</h6>
                                    </div>
                                    <p class="text-muted fs-12 mb-0">ফিঙ্গারপ্রিন্ট ও ম্যানুয়াল হাজিরা</p>
                                </div>
                            </a>
                        </div>

                        <!-- I: Inventory Management -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.branch.inventory', ['branch_id' => $branch->id]) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white hover-shadow transition-all h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-dark text-white rounded-circle fw-bold fs-12" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">I</span>
                                        <h6 class="fw-bold text-dark mb-0">Inventory & Assets</h6>
                                    </div>
                                    <p class="text-muted fs-12 mb-0">কম্পিউটার, আসবাবপত্র ও মালামাল</p>
                                </div>
                            </a>
                        </div>

                        <!-- M: Membership Management -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.branch.membership', ['branch_id' => $branch->id]) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white hover-shadow transition-all h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-warning text-dark rounded-circle fw-bold fs-12" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">M</span>
                                        <h6 class="fw-bold text-dark mb-0">Membership Cards</h6>
                                    </div>
                                    <p class="text-muted fs-12 mb-0">এসএমই, গোল্ড ও প্রপার্টি মেম্বারশিপ</p>
                                </div>
                            </a>
                        </div>

                        <!-- N & X: New Business & Expansion -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.branch.business_leads', ['branch_id' => $branch->id]) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white hover-shadow transition-all h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-primary text-white rounded-circle fw-bold fs-12" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">N/X</span>
                                        <h6 class="fw-bold text-dark mb-0">New Business (N/X)</h6>
                                    </div>
                                    <p class="text-muted fs-12 mb-0">নতুন ব্যবসায়িক সুযোগ ও সম্প্রসারণ</p>
                                </div>
                            </a>
                        </div>

                        <!-- P: Property Management -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.branch.property', ['branch_id' => $branch->id]) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white hover-shadow transition-all h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-success text-white rounded-circle fw-bold fs-12" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">P</span>
                                        <h6 class="fw-bold text-dark mb-0">Property & Plots</h6>
                                    </div>
                                    <p class="text-muted fs-12 mb-0">অনুমোদিত জমি, প্রপার্টি বুকিং ও লিড</p>
                                </div>
                            </a>
                        </div>

                        <!-- S: Sales Management -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.branch.sales', ['branch_id' => $branch->id]) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white hover-shadow transition-all h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-info text-white rounded-circle fw-bold fs-12" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">S</span>
                                        <h6 class="fw-bold text-dark mb-0">Sales (FMCG)</h6>
                                    </div>
                                    <p class="text-muted fs-12 mb-0">ভোগ্যপণ্য ও কাউন্টার পণ্য বিক্রয়</p>
                                </div>
                            </a>
                        </div>

                        <!-- L: Legal & Compliance -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.branch.documents', ['branch_id' => $branch->id]) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white hover-shadow transition-all h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-secondary text-white rounded-circle fw-bold fs-12" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">L</span>
                                        <h6 class="fw-bold text-dark mb-0">Legal & Compliance</h6>
                                    </div>
                                    <p class="text-muted fs-12 mb-0">চুক্তিপত্র, নীতিমালা ও লাইসেন্স ফাইল</p>
                                </div>
                            </a>
                        </div>

                        <!-- Z: Zero Tolerance & Complaints -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.branch.complaints', ['branch_id' => $branch->id]) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white hover-shadow transition-all h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-danger text-white rounded-circle fw-bold fs-12" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">Z</span>
                                        <h6 class="fw-bold text-dark mb-0">Zero Tolerance (Z)</h6>
                                    </div>
                                    <p class="text-muted fs-12 mb-0">জবাবদিহিতা ও অভিযোগ প্রতিকার</p>
                                </div>
                            </a>
                        </div>

                        <!-- R: Reports & Policy Rules -->
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.branch.policy_az', ['branch_id' => $branch->id]) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-white hover-shadow transition-all h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-primary text-white rounded-circle fw-bold fs-12" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">A–Z</span>
                                        <h6 class="fw-bold text-dark mb-0">A–Z Complete Policy</h6>
                                    </div>
                                    <p class="text-muted fs-12 mb-0">কোম্পানির সকল নিয়মাবলি ও নির্দেশিকা</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Columns: Cashbook Ledger & Operational Tasks -->
            <div class="row">
                <!-- Left: Recent Cashbook Transactions -->
                <div class="col-xl-7 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                            <h5 class="box-title fw-bold text-dark mb-0">
                                <i class="fa fa-book text-success me-2"></i> সাম্প্রতিক ক্যাশবুক এন্ট্রি (Cashbook Ledger)
                            </h5>
                            <a href="{{ route('admin.branch.cashbook', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                সম্পূর্ণ ক্যাশবুক দেখুন <i class="fa fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-muted fs-12">
                                        <tr>
                                            <th class="ps-4">তারিখ ও ভাউচার</th>
                                            <th>খাত (Particulars)</th>
                                            <th>পেমেন্ট মেথড</th>
                                            <th class="text-end pe-4">পরিমাণ (টাকা)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentCashbooks as $cb)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="fw-bold text-dark fs-13">{{ $cb->transaction_date->format('d M, Y') }}</div>
                                                    <small class="text-muted font-monospace">{{ $cb->voucher_no ?: 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <div class="fw-bold fs-13 text-dark">{{ $cb->particulars }}</div>
                                                    <small class="badge bg-light text-muted border">{{ ucfirst($cb->category) }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary-light text-secondary rounded-pill px-2 py-1 fs-11">
                                                        {{ strtoupper($cb->payment_method) }}
                                                    </span>
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
                                                <td colspan="4" class="text-center py-4 text-muted">
                                                    কোনো ক্যাশবুক লেনদেন পাওয়া যায়নি।
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Operations & Tasks Status -->
                <div class="col-xl-5 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                            <h5 class="box-title fw-bold text-dark mb-0">
                                <i class="fa fa-tasks text-primary me-2"></i> অপারেশনাল টাস্ক (Operational Tasks)
                            </h5>
                            <a href="{{ route('admin.branch.tasks', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                সব টাস্ক <i class="fa fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="box-body p-3">
                            <div class="list-group list-group-flush">
                                @forelse($recentTasks as $task)
                                    <div class="list-group-item px-0 py-3 border-bottom">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <h6 class="fw-bold text-dark mb-0 fs-13">{{ $task->title }}</h6>
                                            @if($task->status === 'completed')
                                                <span class="badge bg-success-light text-success rounded-pill px-2 py-1 fs-10">সম্পন্ন</span>
                                            @elseif($task->status === 'in_progress')
                                                <span class="badge bg-warning-light text-warning rounded-pill px-2 py-1 fs-10">চলমান</span>
                                            @else
                                                <span class="badge bg-danger-light text-danger rounded-pill px-2 py-1 fs-10">অপেক্ষমাণ</span>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between text-muted fs-11">
                                            <span>
                                                @if($task->priority === 'urgent')
                                                    <span class="badge bg-danger text-white fs-10 me-1">জরুরি</span>
                                                @elseif($task->priority === 'high')
                                                    <span class="badge bg-warning text-dark fs-10 me-1">উচ্চ</span>
                                                @endif
                                                {{ $task->description ? Str::limit($task->description, 45) : 'কোনো অতিরিক্ত নোট নেই' }}
                                            </span>
                                            @if($task->due_date)
                                                <span><i class="fa fa-calendar me-1"></i> {{ $task->due_date->format('d M') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted">
                                        কোনো টাস্ক বাকি নেই।
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal: Quick Add Income -->
<div class="modal fade" id="quickIncomeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0"><i class="fa fa-plus-circle me-2"></i> দৈনিক আয় এন্ট্রি (Add Daily Income)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.branch.income.store', ['branch_id' => $branch->id]) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">তারিখ (Date) <span class="text-danger">*</span></label>
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
                        <label class="fw-bold mb-1">পরিমাণ (Amount in BDT) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" class="form-control rounded-pill" placeholder="যেমন: 5000" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">পেমেন্ট মাধ্যম (Payment Method) <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select rounded-pill" required>
                            <option value="cash">নগদ (Cash)</option>
                            <option value="bank">ব্যাংক ট্রান্সফার (Bank)</option>
                            <option value="bkash">বিকাশ (bKash)</option>
                            <option value="nagad">নগদ (Nagad)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">বিবরণ (Particulars / Notes) <span class="text-danger">*</span></label>
                        <textarea name="particulars" class="form-control rounded-3" rows="2" placeholder="লেনদেনের বিস্তারিত লিখুন..." required></textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label class="fw-bold mb-1">আদায়কারী / জমাকারী (Received By)</label>
                        <input type="text" name="received_by_or_paid_to" class="form-control rounded-pill" placeholder="যেমন: কাউন্টার ক্যাশিয়ার">
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

<!-- Modal: Quick Add Expense -->
<div class="modal fade" id="quickExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-danger text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0"><i class="fa fa-minus-circle me-2"></i> দৈনিক ব্যয় এন্ট্রি (Add Daily Expense)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.branch.expense.store', ['branch_id' => $branch->id]) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">তারিখ (Date) <span class="text-danger">*</span></label>
                        <input type="date" name="transaction_date" class="form-control rounded-pill" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">ব্যয়ের খাত (Category) <span class="text-danger">*</span></label>
                        <select name="category" class="form-select rounded-pill" required>
                            <option value="entertainment_tea">আপ্যায়ন ও চা-নাস্তা (Entertainment)</option>
                            <option value="stationery">অফিস স্টেশনরি ও ফটোকপি</option>
                            <option value="conveyance">যাতায়াত ও কনভেয়েন্স (Conveyance)</option>
                            <option value="electricity">বিদ্যুৎ ও ইউটিলিটি বিল</option>
                            <option value="internet">ইন্টারনেট ও ফোন বিল</option>
                            <option value="repair_maintenance">মেরামত ও রক্ষণাবেক্ষণ</option>
                            <option value="office_rent">অফিস ভাড়া</option>
                            <option value="misc_expense">অন্যান্য জরুরি খরচ</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">পরিমাণ (Amount in BDT) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" class="form-control rounded-pill" placeholder="যেমন: 500" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">পেমেন্ট মাধ্যম (Payment Method) <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select rounded-pill" required>
                            <option value="cash">নগদ (Cash)</option>
                            <option value="bank">ব্যাংক (Bank)</option>
                            <option value="bkash">বিকাশ (bKash)</option>
                            <option value="nagad">নগদ (Nagad)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">বিবরণ / কারণ (Particulars) <span class="text-danger">*</span></label>
                        <textarea name="particulars" class="form-control rounded-3" rows="2" placeholder="খরচের কারণ বিস্তারিত লিখুন..." required></textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label class="fw-bold mb-1">গ্রহীতা / দোকান (Paid To)</label>
                        <input type="text" name="received_by_or_paid_to" class="form-control rounded-pill" placeholder="যেমন: ভাই ভাই স্টেশনারি">
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
