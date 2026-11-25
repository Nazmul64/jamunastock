@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">
        
        <!-- Header Banner -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 text-white overflow-hidden" 
             style="background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #065f46 100%);">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-20 text-white mb-2" style="font-size: 13px;">
                            <i class="fa fa-cubes text-warning"></i>
                            <span>Enterprise Multi-Sector Investment Management</span>
                        </div>
                        <h2 class="fw-bold mb-1 text-white">৯টি ইনভেস্টমেন্ট সেক্টর কেন্দ্রীয় নিয়ন্ত্রণ ড্যাশবোর্ড</h2>
                        <p class="mb-0 text-white-50" style="font-size: 14px;">
                            প্রতিটি সেক্টরের নিজস্ব আইসোলেটেড ব্যালেন্স, ইনভেস্টর পোর্টফোলিও, লাভ বণ্টন এবং সেন্ট্রাল ফাইন্যান্সিয়াল ট্র্যাকিং।
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.investments.create') }}" class="btn btn-warning fw-semibold shadow-sm px-3 py-2 rounded-3">
                            <i class="fa fa-plus-circle me-1"></i> নতুন বিনিয়োগ এন্ট্রি
                        </a>
                        <a href="{{ route('admin.investments.inquiries.index') }}" class="btn btn-light fw-semibold shadow-sm px-3 py-2 rounded-3">
                            <i class="fa fa-envelope-open-text me-1"></i> অনলাইন ইনকোয়ারি
                        </a>
                        <a href="{{ route('admin.investments.receipts.index') }}" class="btn btn-outline-light fw-semibold shadow-sm px-3 py-2 rounded-3">
                            <i class="fa fa-receipt me-1"></i> মানি রসিদ সমূহ
                        </a>
                        <a href="{{ route('admin.investments.ledger.index') }}" class="btn btn-outline-light fw-semibold shadow-sm px-3 py-2 rounded-3">
                            <i class="fa fa-book me-1"></i> লেজার খাতা
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="fa fa-check-circle fa-lg"></i>
                <div class="fw-medium">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Global KPI Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3 text-primary bg-primary bg-opacity-10">
                            <i class="fa fa-users fa-2x"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-medium">মোট গ্রাহক / ইনভেস্টর</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalInvestors) }}</h3>
                            <span class="badge bg-light text-primary border small">৯টি সেক্টরে সক্রিয়</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3 text-success bg-success bg-opacity-10">
                            <i class="fa fa-hand-holding-usd fa-2x"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-medium">মোট সংগৃহীত মূলধন</span>
                            <h3 class="fw-bold mb-0 text-success">৳{{ number_format($totalInvestment, 2) }}</h3>
                            <span class="badge bg-success bg-opacity-10 text-success small">চলমান: ৳{{ number_format($activeInvestment, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3 text-info bg-info bg-opacity-10">
                            <i class="fa fa-coins fa-2x"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-medium">ইতোমধ্যে লাভ প্রদান</span>
                            <h3 class="fw-bold mb-0 text-info">৳{{ number_format($totalProfitPaid, 2) }}</h3>
                            <span class="badge bg-warning bg-opacity-10 text-warning small">বাকি লাভ: ৳{{ number_format($totalProfitDue, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3 text-danger bg-danger bg-opacity-10">
                            <i class="fa fa-wallet fa-2x"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-medium">মোট অপরিশোধিত মূলধন</span>
                            <h3 class="fw-bold mb-0 text-danger">৳{{ number_format($totalPrincipalDue, 2) }}</h3>
                            <span class="badge bg-light text-muted border small">ম্যাচিউরিটি সাপেক্ষ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 9 Sectors Grid Section (Section 1 & 10) -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-0 text-dark"><i class="fa fa-th-large text-success me-2"></i>৯টি ইনভেস্টমেন্ট সেক্টর পোর্টফোলিও</h4>
                <p class="text-muted small mb-0">প্রতিটি সেক্টরে ক্লিক করে তার নিজস্ব ডেডিকেটেড ড্যাশবোর্ড ও আর্থিক হিসাব পরিচালনা করুন।</p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            @foreach($sectorSummaries as $item)
                @php $sec = $item['sector']; @endphp
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white hover-shadow transition" style="border: 1px solid #e2e8f0 !important;">
                        <div class="card-header border-0 bg-light p-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-dark text-white rounded-pill px-2 py-1" style="font-size: 11px;">#{{ $sec->sector_no }}</span>
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold">{{ $sec->code }}</span>
                            </div>
                            <span class="badge bg-light text-muted border">প্রিফিক্স: {{ $sec->receipt_prefix }}</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white" 
                                     style="width: 48px; height: 48px; background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                                    <i class="{{ $sec->icon ?: 'fa fa-chart-line' }} fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0 text-dark">{{ $sec->name_bn }}</h5>
                                    <small class="text-muted">{{ $sec->name_en }}</small>
                                </div>
                            </div>
                            <p class="text-muted small mb-3" style="min-height: 40px; line-height: 1.4;">
                                {{ Str::limit($sec->description, 85) }}
                            </p>

                            <!-- Financial Mini Table -->
                            <div class="bg-light rounded-3 p-3 mb-3">
                                <div class="row g-2 text-center" style="font-size: 13px;">
                                    <div class="col-6 border-end">
                                        <div class="text-muted small">মোট বিনিয়োগ</div>
                                        <div class="fw-bold text-success">৳{{ number_format($item['total_investment']) }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted small">সক্রিয় ইনভেস্টর</div>
                                        <div class="fw-bold text-dark">{{ $item['investors_count'] }} জন</div>
                                    </div>
                                    <div class="col-6 border-end border-top pt-2">
                                        <div class="text-muted small">পরিশোধিত লাভ</div>
                                        <div class="fw-bold text-info">৳{{ number_format($item['profit_paid']) }}</div>
                                    </div>
                                    <div class="col-6 border-top pt-2">
                                        <div class="text-muted small">বকেয়া লাভ</div>
                                        <div class="fw-bold text-warning">৳{{ number_format($item['profit_due']) }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.investments.sector.dashboard', $sec->slug) }}" 
                                   class="btn btn-success btn-sm w-100 fw-semibold rounded-3 py-2">
                                    <i class="fa fa-tachometer-alt me-1"></i> সেক্টর ড্যাশবোর্ড
                                </a>
                                <a href="{{ route('admin.investments.create', ['slug' => $sec->slug]) }}" 
                                   class="btn btn-outline-primary btn-sm rounded-3 py-2 px-3" title="নতুন বিনিয়োগ">
                                    <i class="fa fa-plus"></i>
                                </a>
                                <a href="{{ route('admin.investments.ledger.index', ['slug' => $sec->slug]) }}" 
                                   class="btn btn-outline-secondary btn-sm rounded-3 py-2 px-3" title="লেজার">
                                    <i class="fa fa-book"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Recent Investments & Inquiries -->
        <div class="row g-4">
            <!-- Recent Investments -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa fa-file-invoice-dollar text-primary me-2"></i>সাম্প্রতিক অফিশিয়াল বিনিয়োগ</h5>
                        <a href="{{ route('admin.investments.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">সবগুলো দেখুন</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted small">
                                    <tr>
                                        <th>কোড / চুক্তি</th>
                                        <th>গ্রাহকের নাম</th>
                                        <th>সেক্টর</th>
                                        <th>পরিমাণ</th>
                                        <th>মেয়াদ</th>
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
                                                <span class="badge bg-light text-dark border">{{ $inv->sector->code ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">৳{{ number_format($inv->amount) }}</span>
                                            </td>
                                            <td>{{ $inv->duration_years }} বছর</td>
                                            <td>
                                                <span class="badge bg-success bg-opacity-10 text-success">{{ ucfirst($inv->status) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.investments.show', $inv->id) }}" class="btn btn-sm btn-light border rounded-pill px-2">
                                                    <i class="fa fa-eye text-primary"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">এখনো কোনো বিনিয়োগ এন্ট্রি করা হয়নি।</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Inquiries -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa fa-envelope-open-text text-warning me-2"></i>ওয়েবসাইট ইনকোয়ারি লিড</h5>
                        <a href="{{ route('admin.investments.inquiries.index') }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">CRM দেখুন</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted small">
                                    <tr>
                                        <th>ইনকোয়ারি</th>
                                        <th>আবেদনকারী</th>
                                        <th>সেক্টর ও পরিমাণ</th>
                                        <th>স্ট্যাটাস</th>
                                        <th class="text-end">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentInquiries as $inq)
                                        <tr>
                                            <td>
                                                <span class="badge bg-light text-primary border">{{ $inq->inquiry_code }}</span>
                                            </td>
                                            <td>
                                                <div class="fw-medium text-dark">{{ $inq->name }}</div>
                                                <small class="text-muted">{{ $inq->phone }}</small>
                                            </td>
                                            <td>
                                                <div>{{ $inq->sector->code ?? 'N/A' }}</div>
                                                <small class="fw-bold text-success">৳{{ number_format($inq->proposed_amount) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge {{ $inq->status_badge }}" style="font-size: 11px;">{{ ucfirst(str_replace('_', ' ', $inq->status)) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.investments.inquiries.show', $inq->id) }}" class="btn btn-sm btn-light border rounded-pill px-2">
                                                    <i class="fa fa-arrow-right text-dark"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">কোনো নতুন ইনকোয়ারি নেই।</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
