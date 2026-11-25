@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-line-chart text-success me-2"></i> উন্নয়ন ও কেপিআই (G/K - Growth & KPI)
                    </h3>
                    <small class="text-muted">ব্রাঞ্চ পরিচালক ও কর্মকর্তা-কর্মচারীদের কর্মদক্ষতা পরিমাপ ও ব্যবসায়িক লক্ষ্যমাত্রা</small>
                </div>
                <a href="{{ route('admin.branch.dashboard', ['branch_id' => $branch->id]) }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
                </a>
            </div>
        </div>

        <section class="content">
            <!-- Current Month KPI Performance Card -->
            <div class="box border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="box-header bg-gradient-primary text-white py-4 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold fs-12 mb-2">মাসিক পারফরম্যান্স রিপোর্ট</span>
                            <h3 class="fw-bold text-white mb-0">{{ date('F Y', strtotime($targetMonth . '-01')) }} — কেপিআই স্কোর কার্ড</h3>
                        </div>
                        <div class="text-end">
                            <span class="text-white-50 fs-12 d-block">সার্বিক রেটিং গ্রেড</span>
                            <span class="badge bg-white text-primary fw-bold fs-22 px-4 py-2 rounded-pill shadow-xs">{{ $kpi->grade ?: 'A' }}</span>
                        </div>
                    </div>
                </div>
                <div class="box-body p-4">
                    <div class="row g-4">
                        <!-- 1. Sales Target vs Achieved -->
                        <div class="col-md-4 col-12">
                            <div class="p-3 border rounded-3 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark fs-13">বিক্রয় লক্ষ্যমাত্রা (Sales Target)</span>
                                    <span class="badge bg-success text-white">
                                        {{ $kpi->sales_target > 0 ? round(($kpi->sales_achieved / $kpi->sales_target) * 100) : 0 }}%
                                    </span>
                                </div>
                                <h4 class="fw-bold text-success mb-2">৳{{ number_format($kpi->sales_achieved, 2) }} <small class="text-muted fs-12">/ ৳{{ number_format($kpi->sales_target, 2) }}</small></h4>
                                <div class="progress progress-sm" style="height: 8px;">
                                    @php $salesPct = $kpi->sales_target > 0 ? min(100, round(($kpi->sales_achieved / $kpi->sales_target) * 100)) : 0; @endphp
                                    <div class="progress-bar bg-success" style="width: {{ $salesPct }}%;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Customer Acquisition Target -->
                        <div class="col-md-4 col-12">
                            <div class="p-3 border rounded-3 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark fs-13">নতুন গ্রাহক লক্ষ্যমাত্রা</span>
                                    <span class="badge bg-primary text-white">
                                        {{ $kpi->customer_target > 0 ? round(($kpi->customer_achieved / $kpi->customer_target) * 100) : 0 }}%
                                    </span>
                                </div>
                                <h4 class="fw-bold text-primary mb-2">{{ $kpi->customer_achieved }} জন <small class="text-muted fs-12">/ {{ $kpi->customer_target }} জন</small></h4>
                                <div class="progress progress-sm" style="height: 8px;">
                                    @php $custPct = $kpi->customer_target > 0 ? min(100, round(($kpi->customer_achieved / $kpi->customer_target) * 100)) : 0; @endphp
                                    <div class="progress-bar bg-primary" style="width: {{ $custPct }}%;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Membership Card Issuance -->
                        <div class="col-md-4 col-12">
                            <div class="p-3 border rounded-3 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark fs-13">মেম্বারশিপ কার্ড বিতরণ</span>
                                    <span class="badge bg-warning text-dark">
                                        {{ $kpi->membership_target > 0 ? round(($kpi->membership_achieved / $kpi->membership_target) * 100) : 0 }}%
                                    </span>
                                </div>
                                <h4 class="fw-bold text-warning mb-2">{{ $kpi->membership_achieved }} টি <small class="text-muted fs-12">/ {{ $kpi->membership_target }} টি</small></h4>
                                <div class="progress progress-sm" style="height: 8px;">
                                    @php $memPct = $kpi->membership_target > 0 ? min(100, round(($kpi->membership_achieved / $kpi->membership_target) * 100)) : 0; @endphp
                                    <div class="progress-bar bg-warning" style="width: {{ $memPct }}%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Evaluation Factors Table -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-bold text-dark mb-3">কেপিআই মূল্যায়ন সূচকসমূহ (KPI Scoring Breakdown)</h6>
                        <div class="row g-3 fs-13">
                            <div class="col-md-3 col-6">
                                <div class="p-2 border rounded text-center">
                                    <div class="text-muted fs-12">কর্মী উপস্থিতি রেট</div>
                                    <div class="fw-bold text-success fs-16">{{ $kpi->attendance_score }}%</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-2 border rounded text-center">
                                    <div class="text-muted fs-12">নিয়মিত দৈনিক রিপোর্টিং</div>
                                    <div class="fw-bold text-primary fs-16">{{ $kpi->reporting_score }}%</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-2 border rounded text-center">
                                    <div class="text-muted fs-12">সার্বিক কর্মদক্ষতা স্কোর</div>
                                    <div class="fw-bold text-warning fs-16">{{ $kpi->overall_score }} / ১০০</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-2 border rounded text-center">
                                    <div class="text-muted fs-12">গ্রাহক সন্তুষ্টি রেটিং</div>
                                    <div class="fw-bold text-success fs-16">৪.৮ / ৫.০ <i class="fa fa-star text-warning"></i></div>
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
