@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-folder-open text-primary me-2"></i> অফিস ডকুমেন্টস ও কমপ্লায়েন্স (L - Legal & Compliance)
                    </h3>
                    <small class="text-muted">কোম্পানির অফিশিয়াল নীতিমালা, চুক্তিপত্র, ট্রেড লাইসেন্স ও সার্কুলার আর্কাইভ</small>
                </div>
                <a href="{{ route('admin.branch.dashboard', ['branch_id' => $branch->id]) }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
                </a>
            </div>
        </div>

        <section class="content">
            <div class="row">
                <!-- Standard Corporate Documents Grid -->
                <div class="col-md-4 col-sm-6 col-12 mb-4">
                    <div class="card h-100 border rounded-4 p-4 shadow-xs text-center">
                        <i class="fa fa-file-pdf-o text-danger fs-48 mb-3"></i>
                        <h5 class="fw-bold text-dark mb-1">ব্রাঞ্চ পরিচালকের চুক্তিপত্র</h5>
                        <p class="text-muted fs-12 mb-3">Branch Director Agreement & Mandate</p>
                        <span class="badge bg-success-light text-success rounded-pill px-3 py-1 mb-3">যাচাইকৃত ও সক্রিয়</span>
                        <a href="{{ route('admin.branch.policy_az', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="fa fa-eye me-1"></i> নীতিমালা দেখুন
                        </a>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12 mb-4">
                    <div class="card h-100 border rounded-4 p-4 shadow-xs text-center">
                        <i class="fa fa-id-card text-warning fs-48 mb-3"></i>
                        <h5 class="fw-bold text-dark mb-1">ট্রেড লাইসেন্স কপি</h5>
                        <p class="text-muted fs-12 mb-3">Trade License Document</p>
                        <span class="badge bg-light text-dark border rounded-pill px-3 py-1 mb-3">নম্বর: {{ $branch->trade_license ?: 'TRAD/2026/0129' }}</span>
                        <button class="btn btn-sm btn-outline-secondary rounded-pill" disabled>সংরক্ষিত</button>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12 mb-4">
                    <div class="card h-100 border rounded-4 p-4 shadow-xs text-center">
                        <i class="fa fa-shield text-success fs-48 mb-3"></i>
                        <h5 class="fw-bold text-dark mb-1">জিরো টলারেন্স নীতি ঘোষণা</h5>
                        <p class="text-muted fs-12 mb-3">Zero Tolerance Compliance Policy</p>
                        <span class="badge bg-danger text-white rounded-pill px-3 py-1 mb-3">বাধ্যতামূলক অনুসরণীয়</span>
                        <a href="{{ route('admin.branch.policy_az', ['branch_id' => $branch->id]) }}#section-Z" class="btn btn-sm btn-outline-danger rounded-pill">
                            <i class="fa fa-book me-1"></i> নিয়মাবলি দেখুন
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
