@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-university text-primary me-2"></i> ব্রাঞ্চ প্রোফাইল ও প্রশাসনিক তথ্য (A - Administration)
                    </h3>
                    <small class="text-muted">ব্রাঞ্চের পরিচিতি, অবস্থান, অফিস সময়সূচি ও ব্যাংক তথ্য</small>
                </div>
                <a href="{{ route('admin.branch.dashboard', ['branch_id' => $branch->id]) }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ডে ফিরুন
                </a>
            </div>
        </div>

        <section class="content">
            <div class="row">
                <!-- Left: Branch Information Form -->
                <div class="col-lg-8 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-header py-3 px-4 bg-light border-bottom">
                            <h5 class="box-title fw-bold text-dark mb-0">শাখার সাধারণ ও প্রশাসনিক বিবরণ</h5>
                        </div>
                        <form action="{{ route('admin.branch.profile.update', ['branch_id' => $branch->id]) }}" method="POST">
                            @csrf
                            <div class="box-body p-4">
                                @if(session('success'))
                                    <div class="alert alert-success rounded-pill px-4 py-2 mb-4">
                                        <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                                    </div>
                                @endif

                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="fw-bold mb-1">ব্রাঞ্চের নাম (Branch Name)</label>
                                        <input type="text" name="name" class="form-control rounded-pill" value="{{ $branch->name }}" {{ $isAdmin ? '' : 'readonly' }}>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="fw-bold mb-1">ব্রাঞ্চ কোড (Branch Code)</label>
                                        <input type="text" name="code" class="form-control rounded-pill font-monospace" value="{{ $branch->code }}" {{ $isAdmin ? '' : 'readonly' }}>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="fw-bold mb-1">অফিসিয়াল ফোন নম্বর</label>
                                        <input type="text" name="phone" class="form-control rounded-pill" value="{{ $branch->phone }}" placeholder="যেমন: 01915-752724">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="fw-bold mb-1">জরুরি যোগাযোগ ফোন (Emergency Phone)</label>
                                        <input type="text" name="emergency_phone" class="form-control rounded-pill" value="{{ $branch->emergency_phone }}" placeholder="জরুরি হটলাইন নম্বর">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="fw-bold mb-1">ইমেইল ঠিকানা (Email)</label>
                                        <input type="email" name="email" class="form-control rounded-pill" value="{{ $branch->email }}" placeholder="branch@jamunastock.com">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="fw-bold mb-1">ট্রেড লাইসেন্স নম্বর (Trade License)</label>
                                        <input type="text" name="trade_license" class="form-control rounded-pill font-monospace" value="{{ $branch->trade_license }}" placeholder="TRAD/DSCC/...">
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="fw-bold mb-1">শাখার পূর্ণ ঠিকানা (Address)</label>
                                    <textarea name="address" class="form-control rounded-3" rows="2">{{ $branch->address }}</textarea>
                                </div>

                                <h6 class="fw-bold text-primary mt-4 mb-3 border-bottom pb-2">
                                    <i class="fa fa-clock-o me-1"></i> অফিস সময়সূচি ও কার্যদিবস (B - Branch Operations)
                                </h6>

                                <div class="row mb-3">
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <label class="fw-bold mb-1">অফিস খোলার সময়</label>
                                        <input type="text" name="opening_time" class="form-control rounded-pill" value="{{ $branch->opening_time ?: '09:00 AM' }}">
                                    </div>
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <label class="fw-bold mb-1">অফিস বন্ধের সময়</label>
                                        <input type="text" name="closing_time" class="form-control rounded-pill" value="{{ $branch->closing_time ?: '06:00 PM' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="fw-bold mb-1">সাপ্তাহিক ছুটি</label>
                                        <input type="text" name="weekly_holiday" class="form-control rounded-pill" value="{{ $branch->weekly_holiday ?: 'Friday' }}">
                                    </div>
                                </div>

                                <h6 class="fw-bold text-success mt-4 mb-3 border-bottom pb-2">
                                    <i class="fa fa-university me-1"></i> ব্যাংকিং ও আর্থিক তথ্য (F - Finance)
                                </h6>

                                <div class="row mb-3">
                                    <div class="col-md-6 mb-2 mb-md-0">
                                        <label class="fw-bold mb-1">ব্যাংক অ্যাকাউন্ট নাম</label>
                                        <input type="text" name="bank_account_name" class="form-control rounded-pill" value="{{ $branch->bank_account_name }}" placeholder="যমুনা স্টক মার্কেট পিএলসি">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="fw-bold mb-1">অ্যাকাউন্ট নম্বর</label>
                                        <input type="text" name="bank_account_no" class="form-control rounded-pill font-monospace" value="{{ $branch->bank_account_no }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <label class="fw-bold mb-1">ব্যাংকের নাম</label>
                                        <input type="text" name="bank_name" class="form-control rounded-pill" value="{{ $branch->bank_name }}" placeholder="যেমন: Islami Bank Bangladesh Ltd">
                                    </div>
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <label class="fw-bold mb-1">ব্যাংক শাখা</label>
                                        <input type="text" name="branch_name_bank" class="form-control rounded-pill" value="{{ $branch->branch_name_bank }}" placeholder="মিরপুর শাখা">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="fw-bold mb-1">রাউটিং নম্বর</label>
                                        <input type="text" name="routing_no" class="form-control rounded-pill font-monospace" value="{{ $branch->routing_no }}">
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer py-3 px-4 bg-light text-end">
                                <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">
                                    <i class="fa fa-check me-1"></i> প্রোফাইল সংরক্ষণ করুন
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right: Branch Director Profile & Summary Card -->
                <div class="col-lg-4 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-header py-3 px-4 bg-light border-bottom">
                            <h5 class="box-title fw-bold text-dark mb-0">দায়িত্বপ্রাপ্ত ব্রাঞ্চ পরিচালক</h5>
                        </div>
                        <div class="box-body p-4 text-center">
                            <div class="avatar-xl rounded-circle bg-primary-light text-primary d-inline-flex align-items-center justify-content-center fw-bold fs-32 mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                {{ strtoupper(substr($manager->name ?? 'B', 0, 1)) }}
                            </div>
                            <h4 class="fw-bold text-dark mb-1">{{ $manager->name ?? 'পরিচালক নির্ধারিত নয়' }}</h4>
                            <p class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold fs-12 mb-2">ব্রাঞ্চ পরিচালক (Branch Director)</p>
                            <p class="text-muted fs-13 mb-3">
                                <i class="fa fa-envelope me-1"></i> {{ $manager->email ?? 'N/A' }}<br>
                                <i class="fa fa-phone me-1"></i> {{ $manager->phone ?? 'N/A' }}
                            </p>
                            <hr>
                            <div class="text-start">
                                <div class="d-flex justify-content-between mb-2 fs-13">
                                    <span class="text-muted">ব্রাঞ্চ স্ট্যাটাস:</span>
                                    <span class="badge bg-success text-white px-2 py-1 rounded-pill">সক্রিয় (Active)</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 fs-13">
                                    <span class="text-muted">পেটি ক্যাশ বরাদ্দ:</span>
                                    <span class="fw-bold text-dark">৳{{ number_format($branch->petty_cash_limit ?: 50000, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 fs-13">
                                    <span class="text-muted">বর্তমান ব্যালেন্স:</span>
                                    <span class="fw-bold text-success">৳{{ number_format($branch->current_cash_balance ?: 0, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between fs-13">
                                    <span class="text-muted">প্রতিষ্ঠা সন:</span>
                                    <span class="text-dark">{{ $branch->established_date ? date('Y', strtotime($branch->established_date)) : '2025' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
