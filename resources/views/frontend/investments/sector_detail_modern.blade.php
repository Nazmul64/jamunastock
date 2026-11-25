@extends('layouts.frontend.app')

@section('title', $sector->name_bn . ' - Jamuna Stock Market PLC')

@section('content')
<style>
.sector-hero-banner {
    position: relative;
    padding: 90px 0 70px;
    background: linear-gradient(135deg, rgba(6, 78, 59, 0.95) 0%, rgba(4, 120, 87, 0.88) 100%), 
                url('{{ $sector->banner_image ? asset($sector->banner_image) : asset("frontend/assets/images/sectors/" . $sector->slug . ".jpg") }}') center/cover no-repeat;
    color: #ffffff;
}
.inquiry-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    border: 1px solid #e2e8f0;
}
.plan-card {
    border-radius: 16px;
    transition: all 0.25s ease;
    border: 1px solid #cbd5e1;
    background: #ffffff;
}
.plan-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(5, 150, 105, 0.15);
    border-color: #10b981;
}
.plan-duration-badge {
    background: #064e3b !important;
    color: #fde047 !important;
    border: 1.5px solid #059669 !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    padding: 6px 14px !important;
    border-radius: 20px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    box-shadow: 0 2px 6px rgba(6, 78, 59, 0.2) !important;
}
.plan-duration-badge span {
    color: #fde047 !important;
    font-weight: 700 !important;
}
.plan-card h5 {
    font-family: 'Hind Siliguri', 'Segoe UI', Arial, sans-serif !important;
    font-weight: 700 !important;
    font-size: 18px !important;
    color: #0f172a !important;
    line-height: 1.45 !important;
    margin-top: 8px !important;
    margin-bottom: 8px !important;
    padding-left: 2px !important;
    word-break: normal !important;
}
</style>

<div class="main-container">

    <!-- Sector Hero Header -->
    <section class="sector-hero-banner">
        <div class="container text-center">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-20 text-warning mb-3" style="font-size: 14px; font-weight: 600;">
                <i class="{{ $sector->icon ?: 'fa fa-chart-line' }}"></i>
                <span>ইনভেস্টমেন্ট সেক্টর #{{ $sector->sector_no }} ({{ $sector->code }})</span>
            </div>
            <h1 class="fw-bold mb-3 text-white display-5">{{ $sector->name_bn }}</h1>
            <p class="lead mb-4 text-white-50 mx-auto" style="max-width: 750px;">
                {{ $sector->name_en }} • নির্ভরযোগ্য চুক্তিভিত্তিক এন্টারপ্রাইজ বিনিয়োগ ও সর্বোচ্চ আর্থিক নিরাপত্তা
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#inquiryFormSection" class="btn btn-warning btn-lg fw-bold px-4 rounded-pill shadow">
                    <i class="fa fa-paper-plane me-2"></i>বিনিয়োগের আগ্রহ প্রকাশ করুন
                </a>
                <a href="#plansSection" class="btn btn-outline-light btn-lg fw-semibold px-4 rounded-pill">
                    <i class="fa fa-list-alt me-2"></i>বিনিয়োগ স্কিমসমূহ
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content Body -->
    <section class="py-5 bg-light">
        <div class="container">

            @if(session('inquiry_success'))
                <div class="alert alert-success border-0 rounded-4 shadow-sm p-4 mb-5" style="background: #ecfdf5; border-left: 6px solid #059669 !important;">
                    <div class="d-flex align-items-start gap-3">
                        <i class="fa fa-check-circle text-success fa-3x mt-1"></i>
                        <div>
                            <h4 class="fw-bold text-success mb-1">ধন্যবাদ! আপনার ইনভেস্টমেন্ট ইনকোয়ারি সফলভাবে গৃহীত হয়েছে।</h4>
                            <p class="mb-2 text-dark">{{ session('inquiry_success')['message'] }}</p>
                            <div class="badge bg-success fs-6 px-3 py-2">
                                আপনার রেফারেন্স ট্র্যাকিং কোড: <strong>{{ session('inquiry_success')['code'] }}</strong>
                            </div>
                            <small class="d-block text-muted mt-2">আমাদের ইনভেস্টর রিলেশনশিপ টিম শীঘ্রই আপনার সাথে ফোনে আলোচনা করবে এবং প্রধান কার্যালয়ে মিটিংয়ের সময় নির্ধারণ করবে।</small>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row g-5">
                <!-- Left Column: Sector Details & Plans -->
                <div class="col-lg-7">
                    
                    <!-- Overview & Description -->
                    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5 mb-4">
                        <h3 class="fw-bold text-dark mb-3">
                            <i class="fa fa-info-circle text-success me-2"></i>প্রকল্প সারসংক্ষেপ ও পরিচিতি
                        </h3>
                        <p class="text-secondary lead" style="line-height: 1.8; font-size: 16px;">
                            {{ $sector->description }}
                        </p>

                        @if($sector->features && is_array($sector->features))
                            <h5 class="fw-bold text-dark mt-4 mb-3">প্রধান সুবিধাসমূহ:</h5>
                            <div class="row g-2">
                                @foreach($sector->features as $feat)
                                    <div class="col-12 d-flex align-items-center gap-2">
                                        <i class="fa fa-check-circle text-success"></i>
                                        <span class="text-dark">{{ $feat }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Regulatory & Policy Box -->
                        <div class="mt-4 p-4 rounded-4 bg-light border-start border-4 border-success">
                            <h6 class="fw-bold text-dark mb-1"><i class="fa fa-balance-scale text-success me-2"></i>লাভ বণ্টন নীতিমালা (Profit Policy):</h6>
                            <p class="mb-0 text-muted small" style="line-height: 1.6;">
                                {{ $sector->profit_loss_policy ?: 'বাস্তব উৎপাদন, বাজার বিক্রয় এবং অডিট রিপোর্টের ভিত্তিতে চুক্তি অনুযায়ী নিয়মিত লভ্যাংশ বণ্টন করা হয়।' }}
                            </p>
                        </div>
                    </div>

                    <!-- Investment Plans (Section 12) -->
                    <div id="plansSection" class="mb-4">
                        <h3 class="fw-bold text-dark mb-3">
                            <i class="fa fa-cubes text-primary me-2"></i>উপলব্ধ বিনিয়োগ স্কিমসমূহ (Investment Plans)
                        </h3>
                        <div class="row g-3">
                            @forelse($sector->plans as $plan)
                                <div class="col-md-6">
                                    <div class="plan-card p-4 h-100 shadow-sm">
                                        <span class="plan-duration-badge mb-2">
                                            <i class="fa fa-calendar-check text-warning"></i>
                                            <span>{{ $plan->duration_years ? $plan->duration_years . ' বছর মেয়াদি স্কিম' : $plan->name }}</span>
                                        </span>
                                        <h5 class="fw-bold text-dark mb-2">{{ $plan->name }}</h5>
                                        <div class="display-6 fw-bold text-primary my-2">{{ $plan->profit_rate }}%</div>
                                        <div class="text-muted small mb-3">মুনাফা প্রদেয়: <strong>{{ ucfirst($plan->profit_frequency) }}</strong></div>
                                        <p class="text-muted small mb-3" style="min-height: 40px;">{{ $plan->description }}</p>
                                        <div class="border-top pt-2 small text-muted">
                                            সর্বনিম্ন মূলধন: <strong class="text-dark">৳{{ number_format($plan->min_amount) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="card p-4 rounded-4 text-center text-muted">
                                        এই সেক্টরে কাস্টমাইজড চুক্তিভিত্তিক স্কিম প্রযোজ্য। বিস্তারিত জানতে ইনকোয়ারি জমা দিন।
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- 4 Steps Process (Section 15) -->
                    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5 mb-4">
                        <h4 class="fw-bold text-dark mb-4">
                            <i class="fa fa-stream text-warning me-2"></i>বিনিয়োগ প্রক্রিয়া ও নিরাপত্তা ফ্রেমওয়ার্ক
                        </h4>
                        <div class="row g-4">
                            <div class="col-sm-6 d-flex gap-3">
                                <div class="rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">১</div>
                                <div>
                                    <h6 class="fw-bold mb-1">অনলাইন ইনকোয়ারি</h6>
                                    <small class="text-muted">আপনার প্রস্তাবিত পরিমাণ ও মেয়াদ উল্লেখ করে আগ্রহ প্রকাশ করুন।</small>
                                </div>
                            </div>
                            <div class="col-sm-6 d-flex gap-3">
                                <div class="rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">২</div>
                                <div>
                                    <h6 class="fw-bold mb-1">পরামর্শ ও অফিস মিটিং</h6>
                                    <small class="text-muted">আমাদের রিলেশনশিপ টিম ফোনে যোগাযোগ করবে এবং অফিসে মিটিং হবে।</small>
                                </div>
                            </div>
                            <div class="col-sm-6 d-flex gap-3">
                                <div class="rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">৩</div>
                                <div>
                                    <h6 class="fw-bold mb-1">অফিসিয়াল স্ট্যাম্প চুক্তি</h6>
                                    <small class="text-muted">উভয় পক্ষের উপস্থিতিতে আইনি স্ট্যাম্পে দ্বিপাক্ষিক চুক্তি সম্পাদিত হবে।</small>
                                </div>
                            </div>
                            <div class="col-sm-6 d-flex gap-3">
                                <div class="rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">৪</div>
                                <div>
                                    <h6 class="fw-bold mb-1">মানি রসিদ ও নিয়মিত লাভ</h6>
                                    <small class="text-muted">স্বয়ংক্রিয় অফিসিয়াল মানি রসিদ ও ব্যাংক একাউন্টে নিয়মিত লভ্যাংশ প্রদান।</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Accordion -->
                    @if($sector->faq && is_array($sector->faq))
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                            <h4 class="fw-bold text-dark mb-3"><i class="fa fa-question-circle text-info me-2"></i>সাধারণ জিজ্ঞাসাসমূহ (FAQ)</h4>
                            <div class="accordion accordion-flush" id="faqAccordion">
                                @foreach($sector->faq as $idx => $f)
                                    <div class="accordion-item border-0 mb-2 rounded-3 bg-light">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed rounded-3 bg-light fw-semibold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $idx }}">
                                                {{ $f['q'] }}
                                            </button>
                                        </h2>
                                        <div id="faq{{ $idx }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body text-muted small">
                                                {{ $f['a'] }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Right Column: Online Investment Inquiry Form (Section 13) -->
                <div class="col-lg-5" id="inquiryFormSection">
                    <div class="inquiry-card p-4 p-md-5 sticky-top" style="top: 100px;">
                        <div class="text-center mb-4">
                            <span class="badge bg-warning bg-opacity-20 text-warning px-3 py-1 rounded-pill fw-bold text-uppercase mb-2" style="color: #b45309 !important;">
                                Investment Inquiry
                            </span>
                            <h3 class="fw-bold text-dark mb-1">বিনিয়োগের আগ্রহ প্রকাশ</h3>
                            <p class="text-muted small mb-0">ফর্মটি পূরণ করে জমা দিন। কোনো অগ্রিম পেমেন্টের প্রয়োজন নেই।</p>
                        </div>

                        <form action="{{ route('frontend.investments.inquiry.submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="sector_id" value="{{ $sector->id }}">

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">আপনার পূর্ণ নাম <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg rounded-3" placeholder="উদাঃ মোঃ রহিম আহমেদ" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control form-control-lg rounded-3" placeholder="017XXXXXXXX" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">ইমেইল এড্রেস (Optional)</label>
                                <input type="email" name="email" class="form-control rounded-3" placeholder="yourname@gmail.com">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">বর্তমান ঠিকানা</label>
                                <input type="text" name="address" class="form-control rounded-3" placeholder="উদাঃ ধানমন্ডি, ঢাকা">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-sm-7">
                                    <label class="form-label fw-semibold small">প্রস্তাবিত বিনিয়োগ (BDT) <span class="text-danger">*</span></label>
                                    <input type="number" step="any" name="proposed_amount" class="form-control rounded-3" 
                                           placeholder="উদাঃ 500000" min="{{ $sector->min_amount }}" required>
                                </div>
                                <div class="col-sm-5">
                                    <label class="form-label fw-semibold small">মেয়াদ (বছর) <span class="text-danger">*</span></label>
                                    <select name="duration_years" class="form-select rounded-3" required>
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ $i == 3 ? 'selected' : '' }}>{{ $i }} বছর</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            @if($sector->plans->count() > 0)
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">পছন্দনীয় প্ল্যান</label>
                                    <select name="preferred_plan" class="form-select rounded-3">
                                        <option value="">-- যেকোনো স্কিম --</option>
                                        @foreach($sector->plans as $pl)
                                            <option value="{{ $pl->name }}">{{ $pl->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="mb-4">
                                <label class="form-label fw-semibold small">কোনো বিশেষ প্রশ্ন বা মন্তব্য থাকলে লিখুন</label>
                                <textarea name="message" class="form-control rounded-3" rows="3" placeholder="আপনার বক্তব্য লিখুন..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-bold py-3 shadow">
                                <i class="fa fa-check-circle me-2"></i>আবেদন জমা দিন (Submit Inquiry)
                            </button>

                            <div class="text-center mt-3">
                                <small class="text-muted" style="font-size: 11px;">
                                    <i class="fa fa-shield-alt text-success me-1"></i>আপনার তথ্য সম্পূর্ণ নিরাপদ এবং শুধু অফিসিয়াল যোগাযোগের জন্য ব্যবহৃত হবে।
                                </small>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection
