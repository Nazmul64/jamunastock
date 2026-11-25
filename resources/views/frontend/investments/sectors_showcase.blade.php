@extends('layouts.frontend.app')

@section('title', '৯টি লাভজনক ইনভেস্টমেন্ট সেক্টর - Jamuna Stock Market PLC')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" 
         style="background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #065f46 100%); padding: 60px 0; border-bottom: 3px solid #d97706;">
        <div class="container text-center text-white">
            <span class="badge px-3 py-2 mb-2" style="background: rgba(217, 119, 6, 0.25); color: #fbbf24; border: 1px solid #d97706; font-size: 13px; font-weight: 600;">
                <i class="fa fa-cubes me-1"></i> Sustainable & Enterprise Investments
            </span>
            <h1 style="color: #fff; font-size: 38px; font-weight: 800; margin-bottom: 10px;">৯টি লাভজনক ইনভেস্টমেন্ট সেক্টর</h1>
            <p style="color: #d1fae5; font-size: 16px; margin: 0 auto; max-width: 750px;">
                কৃষি, মৎস্য, গবাদিপশু, রিয়েল এস্টেট, পর্যটন ও আন্তর্জাতিক বাণিজ্যের সমন্বয়ে নিরাপদ ও লাভজনক বিনিয়োগের ক্ষেত্রসমূহ।
            </p>
        </div>
    </div>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                @foreach($sectors as $sec)
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden hover-shadow transition">
                            <div class="position-relative" style="height: 200px; overflow: hidden;">
                                <img src="{{ $sec->image ? asset($sec->image) : asset('frontend/assets/images/sectors/' . $sec->slug . '.jpg') }}" 
                                     alt="{{ $sec->name_bn }}" 
                                     class="w-100 h-100" 
                                     style="object-fit: cover;"
                                     onerror="this.src='https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=600&q=80'">
                                <span class="badge bg-dark bg-opacity-75 text-white position-absolute top-0 start-0 m-3 px-3 py-1 rounded-pill">
                                    #{{ $sec->sector_no }} • {{ $sec->code }}
                                </span>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <h4 class="fw-bold mb-1 text-dark">{{ $sec->name_bn }}</h4>
                                <small class="text-muted mb-3 d-block">{{ $sec->name_en }}</small>
                                <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.5;">
                                    {{ Str::limit($sec->description, 110) }}
                                </p>
                                <div class="bg-light rounded-3 p-3 mb-3 small">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">বিনিয়োগ রেঞ্জ:</span>
                                        <span class="fw-bold text-success">{{ $sec->investment_range }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">মেয়াদকাল:</span>
                                        <span class="fw-bold text-dark">{{ $sec->duration }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('frontend.investments.sector_detail', $sec->slug) }}" class="btn btn-success fw-bold rounded-pill w-100 py-2">
                                    বিস্তারিত ও ইনকোয়ারি <i class="fa fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

</div>
@endsection
