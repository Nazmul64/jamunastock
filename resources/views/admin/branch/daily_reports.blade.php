@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-file-text-o text-danger me-2"></i> দৈনিক রিপোর্টিং (D - Daily Reporting)
                    </h3>
                    <small class="text-muted">প্রধান কার্যালয়ে দৈনিক বিক্রয়, আয়, ব্যয়, কর্মী উপস্থিতি ও ব্যবসায়িক রিপোর্ট</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.branch.daily_reports.create', ['branch_id' => $branch->id]) }}" class="btn btn-danger rounded-pill px-4 shadow-sm fw-bold">
                        <i class="fa fa-plus-circle me-1"></i> আজকের দৈনিক রিপোর্ট পেশ করুন
                    </a>
                    <a href="{{ route('admin.branch.dashboard', ['branch_id' => $branch->id]) }}" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success rounded-pill px-4 py-2 mb-4">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                </div>
            @endif

            <div class="box border-0 shadow-xs rounded-4 mb-4">
                <div class="box-header py-3 px-4 bg-light border-bottom">
                    <h5 class="box-title fw-bold text-dark mb-0">জমাকৃত দৈনিক রিপোর্টের ইতিহাস</h5>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted fs-12">
                                <tr>
                                    <th class="ps-4">তারিখ</th>
                                    <th>মোট আয়</th>
                                    <th>মোট ব্যয়</th>
                                    <th>দিনের উদ্বৃত্ত (Net)</th>
                                    <th>ক্যাশ ইন হ্যান্ড</th>
                                    <th>উপস্থিতি</th>
                                    <th>নতুন গ্রাহক / লিড</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end pe-4">একশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $r)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark fs-13">{{ $r->report_date->format('d M, Y') }}</div>
                                            <small class="text-muted">{{ $r->report_date->format('l') }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success fs-13">৳{{ number_format($r->total_income, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-danger fs-13">৳{{ number_format($r->total_expense, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold fs-13 {{ $r->net_balance >= 0 ? 'text-primary' : 'text-danger' }}">
                                                ৳{{ number_format($r->net_balance, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark fs-13">৳{{ number_format($r->cash_in_hand, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success-light text-success rounded-pill px-2 py-1 fs-11 me-1">{{ $r->present_employees }} জন উপস্থিত</span>
                                            <span class="badge bg-danger-light text-danger rounded-pill px-2 py-1 fs-11">{{ $r->absent_employees }} অনুপস্থিত</span>
                                        </td>
                                        <td>
                                            <span class="text-dark fs-13"><i class="fa fa-user-plus text-primary me-1"></i> {{ $r->new_customers }} জন</span>
                                            @if($r->new_leads > 0)
                                                <small class="badge bg-info-light text-info ms-1">{{ $r->new_leads }} লিড</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($r->status === 'reviewed')
                                                <span class="badge bg-success-light text-success rounded-pill px-3 py-1 fw-bold fs-12">রিভিউ সম্পন্ন</span>
                                            @elseif($r->status === 'submitted')
                                                <span class="badge bg-warning-light text-warning rounded-pill px-3 py-1 fw-bold fs-12">জমাকৃত</span>
                                            @else
                                                <span class="badge bg-danger-light text-danger rounded-pill px-3 py-1 fw-bold fs-12">সংশোধন প্রয়োজন</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('admin.branch.daily_reports.show', ['id' => $r->id, 'branch_id' => $branch->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="রিপোর্টটি দেখুন ও প্রিন্ট করুন">
                                                <i class="fa fa-eye me-1"></i> বিস্তারিত রিপোর্ট
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            কোনো দৈনিক রিপোর্ট এখনো জমা দেওয়া হয়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($reports->hasPages())
                    <div class="box-footer py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2 border-top">
                        <div class="text-muted fs-13">মোট {{ $reports->total() }} টির মধ্যে {{ $reports->firstItem() }} থেকে {{ $reports->lastItem() }} দেখানো হচ্ছে</div>
                        <div>{{ $reports->links('pagination::bootstrap-5') }}</div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
