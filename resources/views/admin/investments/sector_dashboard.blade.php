@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">

        <!-- Navigation Breadcrumb & Back -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="{{ route('admin.investments.central.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> কেন্দ্রীয় ড্যাশবোর্ড
                </a>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.investments.create', ['slug' => $sector->slug]) }}" class="btn btn-success fw-semibold shadow-sm px-3 rounded-pill">
                    <i class="fa fa-plus-circle me-1"></i> এই সেক্টরে নতুন বিনিয়োগ
                </a>
                <a href="{{ route('admin.investments.sector.customers', $sector->slug) }}" class="btn btn-outline-dark fw-semibold shadow-sm px-3 rounded-pill">
                    <i class="fa fa-users me-1"></i> ইনভেস্টর তালিকা
                </a>
                <a href="{{ route('admin.investments.ledger.index', ['slug' => $sector->slug]) }}" class="btn btn-outline-primary fw-semibold shadow-sm px-3 rounded-pill">
                    <i class="fa fa-book me-1"></i> ফাইন্যান্সিয়াল লেজার
                </a>
            </div>
        </div>

        <!-- Sector Banner -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 text-white overflow-hidden" 
             style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-4 p-3 bg-white bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="{{ $sector->icon ?: 'fa fa-chart-pie' }} fa-3x"></i>
                        </div>
                        <div>
                            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-20 text-white mb-2" style="font-size: 13px;">
                                <span>সেক্টর কোড: <strong>{{ $sector->code }}</strong></span>
                                <span class="mx-1">•</span>
                                <span>রসিদ প্রিফিক্স: <strong>{{ $sector->receipt_prefix }}</strong></span>
                            </div>
                            <h2 class="fw-bold mb-1 text-white">{{ $sector->name_bn }} ড্যাশবোর্ড</h2>
                            <p class="mb-0 text-white-50" style="font-size: 14px;">
                                {{ $sector->name_en }} — এই সেক্টরের আইসোলেটেড আর্থিক পোর্টফোলিও ও সার্বিক হিসাব।
                            </p>
                        </div>
                    </div>
                    <div class="text-md-end">
                        <div class="text-white-50 small mb-1">মোট চলমান মূলধন</div>
                        <h2 class="fw-bold text-success mb-0">৳{{ number_format($activeInvestment, 2) }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: 12 Dashboard Statistics KPI Cards -->
        <h5 class="fw-bold mb-3 text-dark"><i class="fa fa-calculator text-primary me-2"></i>সেক্টর ফাইন্যান্সিয়াল ব্যালেন্স ও পরিসংখ্যান (Dashboard Statistics)</h5>
        
        <div class="row g-3 mb-4">
            <!-- 1. Total Investors -->
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <span class="text-muted small fw-medium">১. Total Investors (মোট Customer)</span>
                    <h3 class="fw-bold my-2 text-dark">{{ number_format($totalInvestors) }} <span class="fs-6 text-muted">জন</span></h3>
                    <small class="text-muted">এই সেক্টরের রেজিস্টার্ড ইনভেস্টর</small>
                </div>
            </div>

            <!-- 2. Total Investment -->
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <span class="text-muted small fw-medium">২. Total Investment (মোট বিনিয়োগ)</span>
                    <h3 class="fw-bold my-2 text-success">৳{{ number_format($totalInvestment, 2) }}</h3>
                    <small class="text-muted">সর্বমোট সংগৃহীত তহবিল</small>
                </div>
            </div>

            <!-- 3. Active Investment -->
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <span class="text-muted small fw-medium">৩. Active Investment (চলমান বিনিয়োগ)</span>
                    <h3 class="fw-bold my-2 text-primary">৳{{ number_format($activeInvestment, 2) }}</h3>
                    <small class="text-muted">বর্তমানে কার্যকরী চুক্তি</small>
                </div>
            </div>

            <!-- 4. Completed Investment -->
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <span class="text-muted small fw-medium">৪. Completed Investment (মেয়াদ শেষ)</span>
                    <h3 class="fw-bold my-2 text-secondary">{{ number_format($completedInvestment) }} <span class="fs-6 text-muted">টি</span></h3>
                    <small class="text-muted">সম্পূর্ণ নিষ্পত্তি হওয়া চুক্তি</small>
                </div>
            </div>

            <!-- 5. Total Profit Payable -->
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <span class="text-muted small fw-medium">৫. Total Profit Payable (মোট সম্ভাব্য লাভ)</span>
                    <h3 class="fw-bold my-2 text-info">৳{{ number_format($totalProfitPayable, 2) }}</h3>
                    <small class="text-muted">চুক্তি অনুসারে মোট প্রদেয় মুনাফা</small>
                </div>
            </div>

            <!-- 6. Total Profit Paid -->
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <span class="text-muted small fw-medium">৬. Total Profit Paid (প্রদান করা লাভ)</span>
                    <h3 class="fw-bold my-2 text-success">৳{{ number_format($totalProfitPaid, 2) }}</h3>
                    <small class="text-muted">ইতোমধ্যে ছাড়কৃত মুনাফা</small>
                </div>
            </div>

            <!-- 7. Total Profit Due -->
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <span class="text-muted small fw-medium">৭. Total Profit Due (বাকি লাভ)</span>
                    <h3 class="fw-bold my-2 text-warning">৳{{ number_format($totalProfitDue, 2) }}</h3>
                    <small class="text-muted">ভবিষ্যতে প্রদেয় অবশিষ্ট লাভ</small>
                </div>
            </div>

            <!-- 8. Total Principal -->
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <span class="text-muted small fw-medium">৮. Total Principal (মোট মূলধন)</span>
                    <h3 class="fw-bold my-2 text-dark">৳{{ number_format($totalPrincipal, 2) }}</h3>
                    <small class="text-muted">মোট মূলধন তহবিল</small>
                </div>
            </div>

            <!-- 9. Principal Returned -->
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <span class="text-muted small fw-medium">৯. Principal Returned (ফেরত মূলধন)</span>
                    <h3 class="fw-bold my-2 text-danger">৳{{ number_format($principalReturned, 2) }}</h3>
                    <small class="text-muted">নিষ্পত্তিকৃত মূলধন</small>
                </div>
            </div>

            <!-- 10. Total Outstanding -->
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <span class="text-muted small fw-medium">১০. Total Outstanding (মোট বকেয়া)</span>
                    <h3 class="fw-bold my-2 text-danger">৳{{ number_format($totalOutstanding, 2) }}</h3>
                    <small class="text-muted">দায়বদ্ধ অবশিষ্ট মূলধন</small>
                </div>
            </div>

            <!-- 11. Monthly Investment -->
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <span class="text-muted small fw-medium">১১. Monthly Investment (এই মাসের বিনিয়োগ)</span>
                    <h3 class="fw-bold my-2 text-success">৳{{ number_format($monthlyInvestment, 2) }}</h3>
                    <small class="text-muted">বর্তমান চলতি মাসের নতুন মূলধন</small>
                </div>
            </div>

            <!-- 12. Monthly Profit Payment -->
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <span class="text-muted small fw-medium">১২. Monthly Profit (এই মাসের লাভ প্রদান)</span>
                    <h3 class="fw-bold my-2 text-info">৳{{ number_format($monthlyProfitPayment, 2) }}</h3>
                    <small class="text-muted">চলতি মাসে বিতরণকৃত কিস্তি</small>
                </div>
            </div>
        </div>

        <!-- Recent Sector Investments -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa fa-list-alt text-success me-2"></i>{{ $sector->name_bn }} - সক্রিয় বিনিয়োগ তালিকা</h5>
                <a href="{{ route('admin.investments.index', ['slug' => $sector->slug]) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                    সবগুলো দেখুন
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th>বিনিয়োগ কোড</th>
                                <th>গ্রাহকের নাম ও ফোন</th>
                                <th>বিনিয়োগের পরিমাণ</th>
                                <th>শুরু ও সমাপ্তি</th>
                                <th>মুনাফার হার ও প্ল্যান</th>
                                <th>পরিশোধিত / প্রদেয় লাভ</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInvestments as $inv)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $inv->investment_code }}</div>
                                        <small class="text-muted">{{ $inv->agreement_number }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark">{{ $inv->customer->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $inv->customer->phone ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">৳{{ number_format($inv->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <div>{{ \Carbon\Carbon::parse($inv->start_date)->format('d M Y') }}</div>
                                        <small class="text-muted">থেকে {{ \Carbon\Carbon::parse($inv->maturity_date)->format('d M Y') }}</small>
                                    </td>
                                    <td>
                                        <div><span class="fw-bold text-primary">{{ $inv->profit_rate }}%</span> ({{ ucfirst($inv->profit_frequency) }})</div>
                                        <small class="text-muted">{{ ucfirst($inv->profit_type) }}</small>
                                    </td>
                                    <td>
                                        <div class="text-info fw-bold">৳{{ number_format($inv->paid_profit_total, 2) }}</div>
                                        <small class="text-warning">বকেয়া: ৳{{ number_format($inv->profit_due, 2) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success">{{ ucfirst($inv->status) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.investments.show', $inv->id) }}" class="btn btn-sm btn-light border rounded-pill px-3">
                                            <i class="fa fa-eye text-primary me-1"></i> বিস্তারিত
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">এই সেক্টরে এখনো কোনো বিনিয়োগ নথিভুক্ত হয়নি।</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
