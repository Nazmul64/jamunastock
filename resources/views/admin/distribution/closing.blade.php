@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="fa fa-lock me-2 text-danger"></i> মাসিক ক্লোজিং ও হিসাব সমন্বয় (Monthly Closing & Reconciliation)</h4>
                    <p class="subtitle text-muted mb-0">মাস শেষে সম্পূর্ণ সেন্ট্রাল ক্রয়, চাহিদা, এজেন্ট চালান ও সদস্য ডেলিভারির চূড়ান্ত হিসাব লককরণ</p>
                </div>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#executeClosingModal">
                    <i class="fa fa-check-square me-1"></i> চলতি মাসের ক্লোজিং সম্পন্ন করুন
                </button>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fa fa-info-circle me-2"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Current Month Real-time Reconciliation Box -->
            <div class="box shadow-sm border-top border-primary border-3 mb-4">
                <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                    <h5 class="box-title text-dark mb-0">চলতি মাস ({{ date('F Y', strtotime($currentMonth . '-01')) }}) লাইভ রিকনসিলিয়েশন হিসাব</h5>
                    <span class="badge bg-primary">Cycle: {{ $currentMonth }}</span>
                </div>
                <div class="box-body">
                    <div class="row text-center g-3">
                        <div class="col-md-2 col-6 border-end">
                            <span class="text-muted fs-12 d-block">মোট আবেদন</span>
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($totalRequests) }} টি</h3>
                        </div>
                        <div class="col-md-2 col-6 border-end">
                            <span class="text-muted fs-12 d-block">অনুমোদিত চাহিদা</span>
                            <h3 class="fw-bold text-primary mb-0">{{ number_format($totalApproved) }} টি</h3>
                        </div>
                        <div class="col-md-2 col-6 border-end">
                            <span class="text-muted fs-12 d-block">সেন্ট্রাল ক্রয় প্রাপ্তি</span>
                            <h3 class="fw-bold text-info mb-0">{{ number_format($totalPurchased, 1) }}</h3>
                        </div>
                        <div class="col-md-2 col-6 border-end">
                            <span class="text-muted fs-12 d-block">এজেন্ট চালান প্রেরিত</span>
                            <h3 class="fw-bold text-warning mb-0">{{ number_format($totalDispatched, 1) }}</h3>
                        </div>
                        <div class="col-md-2 col-6 border-end">
                            <span class="text-muted fs-12 d-block">সদস্য বিতরণ সম্পন্ন</span>
                            <h3 class="fw-bold text-success mb-0">{{ number_format($totalDelivered, 1) }}</h3>
                        </div>
                        <div class="col-md-2 col-6">
                            <span class="text-muted fs-12 d-block">সেন্ট্রাল সমাপনী স্টক</span>
                            <h3 class="fw-bold text-danger mb-0">{{ number_format($totalCentralStock, 1) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Past Closing Records Table -->
            <div class="box shadow-sm">
                <div class="box-header with-border bg-light">
                    <h5 class="box-title text-dark mb-0"><i class="fa fa-history text-secondary me-2"></i> সংরক্ষিত মাসিক ক্লোজিং খতিয়ান</h5>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>মাস / বছর</th>
                                    <th class="text-center">আবেদন / অনুমোদিত</th>
                                    <th class="text-end">মোট ক্রয়</th>
                                    <th class="text-end">এজেন্ট চালান</th>
                                    <th class="text-end">সদস্য বিতরণ</th>
                                    <th class="text-end">সমাপনী স্টক</th>
                                    <th>ক্লোজ করেছেন</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($closings as $c)
                                <tr>
                                    <td><strong class="text-dark fs-15">{{ date('F Y', strtotime($c->month_year . '-01')) }}</strong></td>
                                    <td class="text-center">{{ $c->total_requests }} / {{ $c->total_approved }}</td>
                                    <td class="text-end fw-bold text-info">{{ number_format($c->total_purchased_qty, 1) }}</td>
                                    <td class="text-end fw-bold text-warning">{{ number_format($c->total_dispatched_qty, 1) }}</td>
                                    <td class="text-end fw-bold text-success">{{ number_format($c->total_delivered_qty, 1) }}</td>
                                    <td class="text-end fw-bold text-danger">{{ number_format($c->closing_stock_qty, 1) }}</td>
                                    <td>
                                        <div>{{ $c->closer ? $c->closer->name : 'Admin' }}</div>
                                        <small class="text-muted">{{ $c->closed_at ? date('d M, Y', strtotime($c->closed_at)) : '' }}</small>
                                    </td>
                                    <td><span class="badge bg-success"><i class="fa fa-lock"></i> Closed & Locked</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">পূর্বে কোনো মাসিক ক্লোজিং রেকর্ড পাওয়া যায়নি।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($closings->hasPages())
                <div class="box-footer">
                    {{ $closings->links() }}
                </div>
                @endif
            </div>

        </section>
    </div>
</div>

<!-- Modal: Execute Monthly Closing -->
<div class="modal fade" id="executeClosingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.monthly_closing.execute') }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fa fa-lock me-2"></i> মাসিক ক্লোজিং ও হিসাব লক করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <i class="fa fa-exclamation-triangle me-1"></i> সতর্কবার্তা: মাসিক ক্লোজিং সম্পন্ন হলে উক্ত মাসের যাবতীয় হিসাব ও অডিট লক হয়ে যাবে।
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ক্লোজিং মাস (Month Cycle) <span class="text-danger">*</span></label>
                        <input type="month" name="month_year" class="form-control" value="{{ $currentMonth }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ক্লোজিং নোট বা মন্তব্য</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="হিসাব নিরীক্ষা ও সমাপনী সংক্রান্ত কোনো বিশেষ মন্তব্য থাকলে লিখুন"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-danger fw-bold"><i class="fa fa-lock me-1"></i> নিশ্চিত ও ক্লোজিং সম্পন্ন করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
