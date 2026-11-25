@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-university text-success me-2"></i>একীভূত হিসাব ও অ্যাকাউন্ট ওভারভিউ (Unified Accounts)</h4>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের ৫টি প্রধান অর্থনৈতিক অ্যাকাউন্টের সমন্বিত বিবরণী ও নিরীক্ষিত তহবিল</p>
                </div>
                <div>
                    <a href="{{ route('admin.finance.index') }}" class="btn btn-secondary btn-sm">
                        &larr; সেন্ট্রাল লেজারে ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- 5 Account Types Grid -->
            <div class="row">
                <!-- 1. SME Card Account -->
                <div class="col-lg-4 col-md-6">
                    <div class="box box-solid bg-white border-top-4" style="border-top: 4px solid #0b5e28 !important;">
                        <div class="box-header with-border">
                            <h5 class="box-title font-weight-bold text-dark">
                                <i class="fa fa-id-card text-success me-2"></i>১. SME Card Account
                            </h5>
                        </div>
                        <div class="box-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">নির্ধারিত ফি:</span>
                                <strong>৩০০ / ৫০ টাকা</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">সক্রিয় কার্ড সংখ্যা:</span>
                                <span class="badge bg-success">{{ $smeCardCount }} টি</span>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-2">
                                <span class="text-muted">আনুমানিক কার্ড ফান্ড:</span>
                                <strong class="text-success fs-6">৳ {{ number_format($smeCardRevenue) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. SME Gold Card Account -->
                <div class="col-lg-4 col-md-6">
                    <div class="box box-solid bg-white border-top-4" style="border-top: 4px solid #d97706 !important;">
                        <div class="box-header with-border">
                            <h5 class="box-title font-weight-bold text-dark">
                                <i class="fa fa-crown text-warning me-2"></i>২. SME Gold Card Account
                            </h5>
                        </div>
                        <div class="box-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">নির্ধারিত বার্ষিক প্যাকেজ:</span>
                                <strong>১,০০০ টাকা</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">অগ্রাধিকার সেবা:</span>
                                <span class="badge bg-warning text-dark">স্পেশাল ডিসকাউন্ট</span>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-2">
                                <span class="text-muted">প্রিমিয়াম তহবিল:</span>
                                <strong class="text-warning fs-6">সংরক্ষিত</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Property Card Account -->
                <div class="col-lg-4 col-md-6">
                    <div class="box box-solid bg-white border-top-4" style="border-top: 4px solid #2563eb !important;">
                        <div class="box-header with-border">
                            <h5 class="box-title font-weight-bold text-dark">
                                <i class="fa fa-home text-primary me-2"></i>৩. Property Card Account
                            </h5>
                        </div>
                        <div class="box-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">রেজিস্ট্রেশন ফি:</span>
                                <strong>৩,০০০ টাকা</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">নমিনি ও এনআইডি সুরক্ষা:</span>
                                <span class="badge bg-primary">আইনগত যাচাইকৃত</span>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-2">
                                <span class="text-muted">প্রপার্টি বরাদ্দ অগ্রাধিকার:</span>
                                <strong class="text-primary fs-6">সক্রিয়</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Shareholder Capital Account -->
                <div class="col-lg-6 col-md-6">
                    <div class="box box-solid bg-white border-top-4" style="border-top: 4px solid #10b981 !important;">
                        <div class="box-header with-border">
                            <h5 class="box-title font-weight-bold text-dark">
                                <i class="fa fa-user-shield text-success me-2"></i>৪. Shareholder Capital Account
                            </h5>
                        </div>
                        <div class="box-body">
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <span class="text-muted d-block small">সক্রিয় শেয়ারহোল্ডার:</span>
                                    <strong class="fs-6">{{ $shareholdersCount }} জন</strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block small">শেয়ার কাঠামো:</span>
                                    <strong>১০০ শেয়ার (প্রতি শেয়ার ৫,০০০ টাকা)</strong>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-2 mb-2">
                                <span class="text-muted">শেয়ারহোল্ডার মূলধন তহবিল:</span>
                                <strong class="text-success fs-5">৳ {{ number_format($shareCapital) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">সংগৃহীত অফিস তহবিল (৩,০০০/শেয়ার):</span>
                                <strong class="text-info">৳ {{ number_format($shareOfficeFund) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Partnership & Investment Capital Account -->
                <div class="col-lg-6 col-md-6">
                    <div class="box box-solid bg-white border-top-4" style="border-top: 4px solid #8b5cf6 !important;">
                        <div class="box-header with-border">
                            <h5 class="box-title font-weight-bold text-dark">
                                <i class="fa fa-handshake text-purple me-2"></i>৫. Partnership & Investment Capital
                            </h5>
                        </div>
                        <div class="box-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">প্রকল্প বিনিয়োগ মূলধন:</span>
                                <strong class="text-success fs-6">৳ {{ number_format($investmentsCapital) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">প্রাতিষ্ঠানিক পার্টনারশিপ ফান্ড:</span>
                                <strong class="text-purple fs-6">৳ {{ number_format($partnershipCapital) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-2">
                                <span class="text-muted">সার্বিক সেন্ট্রাল নিট উদ্বৃত্ত:</span>
                                <strong class="fs-5 {{ $centralBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                    ৳ {{ number_format($centralBalance) }}
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Central Overview Box -->
            <div class="box">
                <div class="box-header with-border">
                    <h5 class="box-title">সেন্ট্রাল অডিট ও স্বচ্ছতা নীতিমালা</h5>
                </div>
                <div class="box-body">
                    <p class="text-muted" style="line-height: 1.8;">
                        যমুনা স্টক মার্কেট পিএলসি-এর সকল লেনদেন ডাবল-এন্ট্রি বুককিপিং ও চার্টার্ড অ্যাকাউন্ট্যান্টস নিরীক্ষা বিধিমালার অধীনে সংরক্ষিত। প্রতিটি তহবিল (SME, Gold, Property, Shareholder, Investments) সম্পূর্ণ পৃথক লেজার কোডে পরিচালিত হয় যাতে এক খাতের অর্থ অন্য খাতে মিশ্রিত না হয় এবং লভ্যাংশ বণ্টন শতভাগ স্বচ্ছ ও ন্যায়সংগত থাকে।
                    </p>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

