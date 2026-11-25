@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-book text-success me-2"></i> Jamuna Stock Market PLC — ব্রাঞ্চ পরিচালনা ও সম্পূর্ণ নীতিমালা (A–Z System)
                    </h3>
                    <small class="text-muted">Branch Management Policy & Operational System (A–Z Rulebook & Guidelines)</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="window.print()">
                        <i class="fa fa-print me-1"></i> রুলবুক প্রিন্ট করুন
                    </button>
                    <a href="{{ route('admin.branch.dashboard', ['branch_id' => $branch->id]) }}" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Official Header Hero Box -->
            <div class="box border-0 shadow-sm rounded-4 mb-4 overflow-hidden bg-gradient-primary text-white p-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold fs-12 mb-2">অফিসিয়াল পলিসি ডকুমেন্ট</span>
                        <h2 class="fw-bold text-white mb-2">Jamuna Stock Market PLC</h2>
                        <h5 class="text-white-50 mb-3">Branch Management Policy & Operational System (A–Z)</h5>
                        <p class="mb-0 fs-14 text-light">
                            ব্রাঞ্চ পরিচালনা, ব্রাঞ্চ পরিচালক, কমিশন, কর্মচারী, অফিস পরিচালনা ও কোম্পানির সম্পূর্ণ নিয়মাবলি নিচে A থেকে Z পর্যন্ত বিস্তৃতভাবে বিবৃত হলো। ব্রাঞ্চ পরিচালক ও সংশ্লিষ্ট সকল কর্মকর্তাকে এই নির্দেশিকা কঠোরভাবে অনুসরণের নির্দেশ প্রদান করা হচ্ছে।
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <div class="p-3 bg-white bg-opacity-10 rounded-3 d-inline-block text-start">
                            <div class="fs-13 text-white mb-1"><strong>ব্রাঞ্চের নাম:</strong> {{ $branch->name }}</div>
                            <div class="fs-13 text-white mb-1"><strong>শাখা কোড:</strong> {{ $branch->code }}</div>
                            <div class="fs-13 text-white"><strong>অনুমোদিত পরিচালক:</strong> {{ $branch->manager->name ?? 'পরিচালক' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Live Filter Box -->
            <div class="box border-0 shadow-xs rounded-4 mb-4">
                <div class="box-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <span class="fw-bold text-dark fs-14"><i class="fa fa-filter text-primary me-1"></i> নীতিমালা খুঁজুন (Search Policy):</span>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="policySearchInput" class="form-control rounded-pill" placeholder="A, B, C বা নীতিমালার কি-ওয়ার্ড লিখে খুঁজুন (যেমন: কমিশন, হাজিরা, ক্যাশ)...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Complete A to Z Policy Accordion Cards -->
            <div id="policyContainer">
                <!-- A: Administration -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-A">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-primary text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">A</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">A — Administration (প্রশাসনিক ব্যবস্থাপনা)</h5>
                                <small class="text-muted">সার্বিক প্রশাসনিক নিয়ন্ত্রণ ও হেড অফিস সমন্বয়</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.profile', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> প্রোফাইল মডিউল
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> ব্রাঞ্চের সম্পূর্ণ প্রশাসনিক কার্যক্রম পরিচালনা করা।</li>
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> অফিসের দৈনিক কার্যক্রম নিবিড়ভাবে পর্যবেক্ষণ ও তদারকি করা।</li>
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> প্রতিষ্ঠানের সকল নিয়ম ও নীতিমালা পূর্ণাঙ্গভাবে বাস্তবায়ন করা।</li>
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> ব্রাঞ্চের যাবতীয় রিপোর্ট ও অগ্রগতি প্রধান অফিসে নিয়মিত প্রদান করা।</li>
                        </ul>
                    </div>
                </div>

                <!-- B: Branch Operations -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-B">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-success text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">B</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">B — Branch Operations (ব্রাঞ্চ পরিচালনা)</h5>
                                <small class="text-muted">দৈনিক অপারেশন ও সময়সূচি নিশ্চিতকরণ</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.tasks', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> অপারেশন মডিউল
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> ব্রাঞ্চের দৈনিক সকল ব্যবসায়িক ও দাপ্তরিক কার্যক্রম পরিচালনা।</li>
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> ব্রাঞ্চ সময়মতো খোলা (সকাল ৯:০০ টা) ও বন্ধের (সন্ধ্যা ৬:০০ টা) নিয়ম শতভাগ মেনে চলা।</li>
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> অফিসের যাবতীয় কার্যক্রমের নির্ভুলতা নিশ্চিত করা।</li>
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> ব্রাঞ্চের সমস্যা সমাধান ও অভ্যন্তরীণ কার্যক্রম সার্বক্ষণিক পর্যবেক্ষণ।</li>
                        </ul>
                    </div>
                </div>

                <!-- C: Commission Management -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-C">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-warning text-dark rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">C</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">C — Commission Management (কমিশন ব্যবস্থাপনা)</h5>
                                <small class="text-muted">অনুমোদিত বিক্রয় ও রেফারেল কমিশন নীতিমালা</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.commissions', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> কমিশন লেজার
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="text-dark fw-bold mb-2">ব্রাঞ্চ পরিচালক কোম্পানির অনুমোদিত নিম্নলিখিত কার্যক্রম থেকে নির্ধারিত কমিশন পাবেন:</p>
                        <div class="row g-2 mb-3">
                            <div class="col-md-4 col-sm-6"><span class="badge bg-light text-dark border p-2 w-100 text-start"><i class="fa fa-shopping-cart text-success me-1"></i> Product Sales Commission</span></div>
                            <div class="col-md-4 col-sm-6"><span class="badge bg-light text-dark border p-2 w-100 text-start"><i class="fa fa-cutlery text-warning me-1"></i> FMCG Commission</span></div>
                            <div class="col-md-4 col-sm-6"><span class="badge bg-light text-dark border p-2 w-100 text-start"><i class="fa fa-building text-primary me-1"></i> Property Sales Commission</span></div>
                            <div class="col-md-4 col-sm-6"><span class="badge bg-light text-dark border p-2 w-100 text-start"><i class="fa fa-id-card text-info me-1"></i> Membership Commission</span></div>
                            <div class="col-md-4 col-sm-6"><span class="badge bg-light text-dark border p-2 w-100 text-start"><i class="fa fa-briefcase text-secondary me-1"></i> Business Sales Commission</span></div>
                            <div class="col-md-4 col-sm-6"><span class="badge bg-light text-dark border p-2 w-100 text-start"><i class="fa fa-line-chart text-danger me-1"></i> Investment Referral Commission</span></div>
                        </div>
                        <p class="mb-0 text-muted"><i class="fa fa-info-circle text-primary me-1"></i> <strong>বিশেষ দ্রষ্টব্য:</strong> কমিশনের হার ও শর্তাবলী প্রধান কার্যালয়ের অ্যাডমিন প্যানেল হতে নির্ধারিত হবে এবং পারফরম্যান্স বোনাস প্রদান করা হবে।</p>
                    </div>
                </div>

                <!-- D: Daily Reporting -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-D">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-danger text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">D</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">D — Daily Reporting (দৈনিক রিপোর্টিং)</h5>
                                <small class="text-muted">প্রতিদিনের কার্যবিবরণী ও হিসাব জমাদান</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.daily_reports', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> রিপোর্ট মডিউল
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="fw-bold text-dark mb-2">প্রতিদিনের রিপোর্টে নিম্নোক্ত বিষয়সমূহ অন্তর্ভুক্ত থাকা বাধ্যতামূলক:</p>
                        <div class="row g-2">
                            <div class="col-md-3 col-6"><div class="p-2 border rounded bg-light"><i class="fa fa-check text-success me-1"></i> দৈনিক বিক্রয়</div></div>
                            <div class="col-md-3 col-6"><div class="p-2 border rounded bg-light"><i class="fa fa-check text-success me-1"></i> দৈনিক আয়</div></div>
                            <div class="col-md-3 col-6"><div class="p-2 border rounded bg-light"><i class="fa fa-check text-success me-1"></i> দৈনিক ব্যয়</div></div>
                            <div class="col-md-3 col-6"><div class="p-2 border rounded bg-light"><i class="fa fa-check text-success me-1"></i> কর্মচারীদের উপস্থিতি</div></div>
                            <div class="col-md-3 col-6"><div class="p-2 border rounded bg-light"><i class="fa fa-check text-success me-1"></i> নতুন গ্রাহক</div></div>
                            <div class="col-md-3 col-6"><div class="p-2 border rounded bg-light"><i class="fa fa-check text-success me-1"></i> নতুন ব্যবসায়িক কার্যক্রম</div></div>
                            <div class="col-md-3 col-6"><div class="p-2 border rounded bg-light"><i class="fa fa-check text-success me-1"></i> কমিশনের হিসাব</div></div>
                            <div class="col-md-3 col-6"><div class="p-2 border rounded bg-light"><i class="fa fa-check text-success me-1"></i> দিনের গুরুত্বপূর্ণ ঘটনা</div></div>
                        </div>
                    </div>
                </div>

                <!-- E: Employee Management -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-E">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-primary text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">E</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">E — Employee Management (কর্মচারী ব্যবস্থাপনা)</h5>
                                <small class="text-muted">নিয়োগ আবেদন, দায়িত্ব বণ্টন ও শৃঙ্খলা</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.employees', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> স্টাফ তালিকা
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> কর্মচারী নিয়োগের আবেদন পর্যালোচনা ও প্রধান কার্যালয়ে সুপারিশ প্রদান।</li>
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> প্রতিটি কর্মচারীর জন্য সুস্পষ্ট দায়িত্ব বণ্টন নিশ্চিত করা।</li>
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> কর্মচারীদের সঠিক উপস্থিতি ও সময়ানুবর্তিতা যাচাই করা।</li>
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> কর্মচারীদের কর্মদক্ষতা নিয়মিত মূল্যায়ন ও রেকর্ড সংরক্ষণ।</li>
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> কর্মচারীদের ছুটি, শৃঙ্খলা ও আচরণবিধি পরিচালনা।</li>
                        </ul>
                    </div>
                </div>

                <!-- F: Financial Management -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-F">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-success text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">F</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">F — Financial Management (আর্থিক ব্যবস্থাপনা)</h5>
                                <small class="text-muted">ক্যাশ ইন, ক্যাশ আউট, ভাউচার ও খতিয়ান</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.cashbook', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> ক্যাশবুক
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> ব্রাঞ্চের দৈনিক আয় ও ব্যয়ের হিসাব প্রতিটি লেনদেনের সাথে সাথে লিপিবদ্ধ করা।</li>
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> ক্যাশ ইন ও ক্যাশ আউট ভাউচার নম্বরসহ সংরক্ষণ।</li>
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> বৈধ রশিদ ও অনুমোদিত ভাউচার ব্যতিরেকে কোনো খরচ গ্রহণযোগ্য হবে না।</li>
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> ব্যাংক ও অনুমোদিত ডিজিটাল পেমেন্ট মাধ্যম অগ্রাধিকার দেওয়া।</li>
                            <li class="list-group-item px-0 py-2 border-0"><i class="fa fa-check-circle text-success me-2"></i> প্রতি মাসের শেষে পূর্ণাঙ্গ মাসিক আর্থিক বিবরণী প্রস্তুত করা।</li>
                        </ul>
                    </div>
                </div>

                <!-- G & K: Growth & KPI -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-G">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-info text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">G</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">G — Growth & Performance (ব্রাঞ্চের উন্নয়ন ও অগ্রগতি)</h5>
                                <small class="text-muted">ব্যবসায়িক সম্প্রসারণ ও লক্ষ্যমাত্রা পূরণ</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.kpi', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> কেপিআই স্কোর
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-2">ব্রাঞ্চের সার্বিক ব্যবসায়িক প্রসার ও কোম্পানির লক্ষ্যমাত্রা বাস্তবায়নে কাজ করা। বিক্রয় প্রবৃদ্ধি নিশ্চিত করতে প্রচার ও নতুন গ্রাহক সংযোজন কার্যক্রম পরিচালনা করতে হবে।</p>
                    </div>
                </div>

                <!-- H: HR & Attendance -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-H">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-primary text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">H</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">H — HR & Attendance (হিউম্যান রিসোর্স ও হাজিরা)</h5>
                                <small class="text-muted">বায়োমেট্রিক ও সময়ানুবর্তিতা নীতিমালা</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.attendance', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> হাজিরা মডিউল
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <div class="row g-2">
                            <div class="col-md-6"><i class="fa fa-hand-o-up text-warning me-2"></i> <strong>Fingerprint Attendance System:</strong> প্রতিটি কর্মীকে প্রতিদিন মেশিনে ফিঙ্গারপ্রিন্ট দিতে হবে।</div>
                            <div class="col-md-6"><i class="fa fa-pencil text-primary me-2"></i> <strong>Manual Attendance:</strong> জরুরি অবস্থায় পরিচালকের স্বাক্ষরিত ম্যানুয়াল রেজিস্টার প্রযোজ্য।</div>
                            <div class="col-md-6"><i class="fa fa-clock-o text-danger me-2"></i> <strong>Late Entry:</strong> সকাল ৯:১৫ এর পরে আসলে তা বিলম্বে উপস্থিতি গণ্য হবে।</div>
                            <div class="col-md-6"><i class="fa fa-sign-out text-info me-2"></i> <strong>Early Leave:</strong> অনুমোদন ছাড়া অফিস ত্যাগে অর্ধদিবস ছুটি কাটা হবে।</div>
                        </div>
                    </div>
                </div>

                <!-- I: Inventory Management -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-I">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-dark text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">I</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">I — Inventory Management (মালামাল ও সম্পদ ব্যবস্থাপনা)</h5>
                                <small class="text-muted">অফিস সম্পদ গ্রহণ, বিতরণ ও সুরক্ষা</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.inventory', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> সম্পদ তালিকা
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">অফিসের কম্পিউটার, প্রিন্টার, ইলেকট্রনিক সরঞ্জাম, আসবাবপত্র ও স্টেশনারি সামগ্রীর সঠিক তালিকা সংরক্ষণ করা। কোনো মালামাল ক্ষতিগ্রস্ত হলে ২৪ ঘণ্টার মধ্যে প্রধান অফিসে রিপোর্ট করতে হবে।</p>
                    </div>
                </div>

                <!-- J: Job Responsibility -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-J">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-secondary text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">J</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">J — Job Responsibility (দায়িত্ব বণ্টন ও কর্তৃত্ব)</h5>
                                <small class="text-muted">কাজের সীমানা ও অনুমোদন প্রটোকল</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.employees', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> দায়িত্ব বণ্টন
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">প্রত্যেক কর্মচারীর লিখিত জব ডেসক্রিপশন থাকতে হবে। কোন কাজের জন্য কার অনুমোদন লাগবে এবং কে কাকে রিপোর্ট করবে তা সুস্পষ্ট থাকবে।</p>
                    </div>
                </div>

                <!-- K: KPI -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-K">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-success text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">K</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">K — KPI (Key Performance Indicator)</h5>
                                <small class="text-muted">কর্মদক্ষতা পরিমাপক সূচকসমূহ</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.kpi', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> কেপিআই
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">ব্রাঞ্চ পরিচালকের কর্মদক্ষতা পরিমাপ করা হবে: বিক্রয় লক্ষ্যমাত্রা অর্জন, নতুন গ্রাহক সৃষ্টি, কর্মীদের সময়ানুবর্তিতা, রিপোর্টিংয়ের মান এবং অফিস পরিচালনার শৃঙ্খলার ওপর।</p>
                    </div>
                </div>

                <!-- L: Legal & Compliance -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-L">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-danger text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">L</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">L — Legal & Compliance (আইন ও নিয়মাবলি)</h5>
                                <small class="text-muted">কোম্পানি আইন ও প্রাতিষ্ঠানিক সম্মতি</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.documents', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> লিগ্যাল ফাইল
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">কোম্পানির অফিশিয়াল অনুমোদিত চুক্তিপত্র ব্যতীত কোনো লেনদেন বা চুক্তি করা যাবে না। গ্রাহকের তথ্য সুরক্ষা ও গোপনীয়তা বজায় রাখা বাধ্যতামূলক।</p>
                    </div>
                </div>

                <!-- M: Membership Management -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-M">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-warning text-dark rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">M</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">M — Membership Management (সদস্য ব্যবস্থাপনা)</h5>
                                <small class="text-muted">কার্ড প্রদান ও সদস্য সেবা</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.membership', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> মেম্বারশিপ
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">যমুনা এসএমই কার্ড, গোল্ড কার্ড ও প্রপার্টি কার্ডের তথ্য সংগ্রহ, সদস্য নিবন্ধন এবং যথাযথ কমিশন প্রাপ্তি নিশ্চিতকরণ।</p>
                    </div>
                </div>

                <!-- N: New Business Development -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-N">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-primary text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">N</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">N — New Business Development (নতুন ব্যবসা উন্নয়ন)</h5>
                                <small class="text-muted">মার্কেট এক্সপ্যানশন ও করপোরেট ডিল</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.business_leads', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> লিড ট্র্যাকার
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">এলাকায় পাইকারি ডিলার, করপোরেট ক্লায়েন্ট ও অংশীদারিত্বের নতুন ব্যবসায়িক সুযোগ খুঁজে বের করে প্রধান কার্যালয়ে প্রস্তাবনা প্রেরণ করা।</p>
                    </div>
                </div>

                <!-- O: Office Management -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-O">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-info text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">O</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">O — Office Management (অফিস ব্যবস্থাপনা)</h5>
                                <small class="text-muted">পরিচ্ছন্নতা, নিরাপত্তা ও সেবা পরিবেশ</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.profile', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> অফিস প্রোফাইল
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">অফিসের পরিষ্কার-পরিচ্ছন্নতা, বিদ্যুৎ, ইন্টারনেট ও পানির সুষ্ঠু ব্যবস্থা এবং গ্রাহক সেবার উপযুক্ত সম্মানজনক পরিবেশ বজায় রাখা।</p>
                    </div>
                </div>

                <!-- P: Property & Real Estate -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-P">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-success text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">P</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">P — Property & Real Estate (প্রপার্টি ব্যবস্থাপনা)</h5>
                                <small class="text-muted">অনুমোদিত জমি ও প্লট বুকিং</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.property', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> প্রপার্টি প্রজেক্ট
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">যমুনা গ্রুপের অনুমোদিত আবাসন ও প্লট প্রজেক্টের তথ্য প্রদর্শন, বুকিং মানি গ্রহণ ও অনুমোদিত ২.৫% রেফারেল কমিশন প্রক্রিয়াকরণ।</p>
                    </div>
                </div>

                <!-- Q: Quality Control -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-Q">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-primary text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">Q</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Q — Quality Control (কাজের মান নিয়ন্ত্রণ)</h5>
                                <small class="text-muted">সেবার নির্ভুলতা ও মান নিশ্চিতকরণ</small>
                            </div>
                        </div>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">কর্মচারীদের কাজের নির্ভুলতা, গ্রাহকসেবার মান পর্যবেক্ষণ, হিসাবের সঠিকতা নিশ্চিত করা এবং ভুল রিপোর্ট অবিলম্বে সংশোধন করা।</p>
                    </div>
                </div>

                <!-- R: Reporting & Record Keeping -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-R">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-secondary text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">R</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">R — Reporting & Record Keeping (রিপোর্ট ও রেকর্ড সংরক্ষণ)</h5>
                                <small class="text-muted">নথি ও রেজিস্টার সংরক্ষণ</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.daily_reports', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> রিপোর্ট সেন্টার
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">Daily, Weekly, Monthly, Sales, Commission, Expense ও Attendance রিপোর্টসমূহ যথাযথ ফাইলে সংরক্ষণ করতে হবে।</p>
                    </div>
                </div>

                <!-- S: Sales Management -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-S">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-success text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">S</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">S — Sales Management (বিক্রয় ব্যবস্থাপনা)</h5>
                                <small class="text-muted">কাউন্টার ও আউটলেট বিক্রয়</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.sales', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> বিক্রয় রেজিস্টার
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">কোম্পানির অনুমোদিত তিস্তা ব্র্যান্ডের পণ্য, FMCG ও অন্যান্য সেবা বিক্রয় এবং বিক্রয় লক্ষ্যমাত্রা পূরণ করা।</p>
                    </div>
                </div>

                <!-- T: Training & Development -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-T">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-warning text-dark rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">T</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">T — Training & Development (প্রশিক্ষণ ও উন্নয়ন)</h5>
                                <small class="text-muted">কর্মী সক্ষমতা বৃদ্ধি</small>
                            </div>
                        </div>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">কর্মচারীদের গ্রাহকসেবা, সততা, কোম্পানির নীতিমালা এবং আধুনিক সফটওয়্যার ব্যবহারে নিয়মিত প্রশিক্ষণ প্রদান।</p>
                    </div>
                </div>

                <!-- U: User & Customer Management -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-U">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-info text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">U</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">U — User & Customer Management (গ্রাহক ব্যবস্থাপনা)</h5>
                                <small class="text-muted">গ্রাহক সেবা ও যোগাযোগ</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.membership', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> গ্রাহক ডেটাবেস
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">গ্রাহকের নাম ও যোগাযোগের পূর্ণ তথ্য সংরক্ষণ, তাদের সমস্যার দ্রুত সমাধান এবং নিয়মিত যোগাযোগ রক্ষা করা।</p>
                    </div>
                </div>

                <!-- V: Verification & Validation -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-V">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-primary text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">V</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">V — Verification & Validation (যাচাই ও অনুমোদন)</h5>
                                <small class="text-muted">লেনদেন ও ভাউচার যাচাই</small>
                            </div>
                        </div>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">ব্যবসায়িক কার্যক্রম, বিক্রয়, কমিশন, বিল ও ভাউচার সঠিকভাবে যাচাই করে প্রয়োজনীয় অনুমোদন গ্রহণ নিশ্চিত করা।</p>
                    </div>
                </div>

                <!-- W: Welfare & Workplace Safety -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-W">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-success text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">W</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">W — Welfare & Workplace Safety (কর্মচারী কল্যাণ ও নিরাপত্তা)</h5>
                                <small class="text-muted">নিরাপদ কর্মপরিবেশ ও সুরক্ষা</small>
                            </div>
                        </div>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">কর্মচারীদের জন্য নিরাপদ কর্মপরিবেশ, ফার্স্ট এইড সুবিধা, অগ্নিনির্বাপক ব্যবস্থা এবং জরুরি পরিস্থিতির প্রস্তুতি নিশ্চিত করা।</p>
                    </div>
                </div>

                <!-- X: eXcellence & Expansion -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-X">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-warning text-dark rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">X</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">X — eXcellence & Expansion (উন্নয়ন ও সম্প্রসারণ)</h5>
                                <small class="text-muted">নতুন সাব-ব্রাঞ্চ ও সেবা বিস্তার</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.business_leads', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> সম্প্রসারণ প্রস্তাব
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">ব্রাঞ্চের কর্মক্ষমতা বৃদ্ধির পাশাপাশি পার্শ্ববর্তী উপজেলাগুলোতে নতুন কালেকশন পয়েন্ট বা সাব-ব্রাঞ্চ চালুর পরিকল্পনা প্রস্তাব করা।</p>
                    </div>
                </div>

                <!-- Y: Yearly Evaluation -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-Y">
                    <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-secondary text-white rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">Y</span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Y — Yearly Evaluation (বার্ষিক মূল্যায়ন)</h5>
                                <small class="text-muted">বাৎসরিক পারফরম্যান্স ও পরিকল্পনা</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.kpi', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> বার্ষিক কেপিআই
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14">
                        <p class="mb-0">বার্ষিক বিক্রয়, আয়-ব্যয়, কর্মচারীদের পারফরম্যান্স মূল্যায়ন, ইনসেনটিভ পর্যালোচনা এবং পরবর্তী বছরের লক্ষ্যমাত্রা নির্ধারণ।</p>
                    </div>
                </div>

                <!-- Z: Zero Tolerance & Accountability -->
                <div class="box border-0 shadow-xs rounded-4 mb-3 policy-item" id="section-Z">
                    <div class="box-header py-3 px-4 bg-danger text-white border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-white text-danger rounded-circle fw-bold fs-15" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">Z</span>
                            <div>
                                <h5 class="fw-bold text-white mb-0">Z — Zero Tolerance & Accountability (জবাবদিহিতা ও অনিয়ম প্রতিরোধ)</h5>
                                <small class="text-white-50">কঠোর আর্থিক অনুশাসন ও নৈতিকতা</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.branch.complaints', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                            <i class="fa fa-external-link me-1"></i> অভিযোগ সেল
                        </a>
                    </div>
                    <div class="box-body p-4 fs-14 bg-white">
                        <div class="alert alert-danger rounded-3 mb-3">
                            <strong>সতর্কবাণী:</strong> কোম্পানিতে দুর্নীতি বা অনিয়মে কোনো প্রকার আপস করা হবে না।
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 py-2 border-0 text-danger fw-bold"><i class="fa fa-ban me-2"></i> কোনো প্রকার আর্থিক অনিয়ম বা ফান্ড অপব্যবহার সহ্য করা হবে না।</li>
                            <li class="list-group-item px-0 py-2 border-0 text-danger fw-bold"><i class="fa fa-ban me-2"></i> ভুয়া ভাউচার বা অসত্য রিপোর্ট পেশ করা সম্পূর্ণ নিষিদ্ধ ও আইনত দণ্ডনীয়।</li>
                            <li class="list-group-item px-0 py-2 border-0 text-danger fw-bold"><i class="fa fa-ban me-2"></i> প্রধান অফিসের অনুমোদন ব্যতিরেকে নগদ অর্থ লেনদেন নিষিদ্ধ।</li>
                            <li class="list-group-item px-0 py-2 border-0 text-danger fw-bold"><i class="fa fa-ban me-2"></i> কোম্পানির সম্পদ বা গ্রাহকের ব্যক্তিগত তথ্যের অপব্যবহার সম্পূর্ণ নিষিদ্ধ।</li>
                            <li class="list-group-item px-0 py-2 border-0 text-dark fw-bold"><i class="fa fa-check-circle text-success me-2"></i> প্রত্যেক কর্মচারী ও ব্রাঞ্চ পরিচালক নিজ নিজ কাজের জন্য পরিচালনা পর্ষদের কাছে শতভাগ জবাবদিহি করবেন।</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var searchInput = document.getElementById('policySearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                var query = this.value.toLowerCase().trim();
                var items = document.querySelectorAll('.policy-item');
                items.forEach(function(item) {
                    var text = item.textContent.toLowerCase();
                    if (text.includes(query)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection
