@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-shopping-basket text-success me-2"></i> বিক্রয় ব্যবস্থাপনা (S - Sales Management)
                    </h3>
                    <small class="text-muted">ব্রাঞ্চ কাউন্টারে ভোগ্যপণ্য (FMCG), প্যাকেজ ও কৃষিপণ্য বিক্রয় ট্র্যাকার</small>
                </div>
                <a href="{{ route('admin.branch.dashboard', ['branch_id' => $branch->id]) }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
                </a>
            </div>
        </div>

        <section class="content">
            <div class="box border-0 shadow-xs rounded-4 mb-4">
                <div class="box-header py-3 px-4 bg-light border-bottom">
                    <h5 class="box-title fw-bold text-dark mb-0">পণ্য ও FMCG বিক্রয় হিসাবসমূহ</h5>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted fs-12">
                                <tr>
                                    <th class="ps-4">তারিখ ও ভাউচার</th>
                                    <th>বিক্রয় পণ্যের বিবরণ</th>
                                    <th>খাত</th>
                                    <th>পেমেন্ট মাধ্যম</th>
                                    <th>বিক্রয়কারী স্টাফ</th>
                                    <th class="text-end pe-4">বিক্রয় মূল্য (টাকা)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salesCashbooks as $sale)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark fs-13">{{ $sale->transaction_date->format('d M, Y') }}</div>
                                            <small class="text-primary font-monospace">{{ $sale->voucher_no ?: '-' }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark fs-13">{{ $sale->particulars }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $sale->category)) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-light text-secondary rounded-pill px-2 py-1 fs-11">{{ strtoupper($sale->payment_method) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">{{ $sale->received_by_or_paid_to ?: 'কাউন্টার স্টাফ' }}</span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <span class="fw-bold text-success fs-15">+৳{{ number_format($sale->amount, 2) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            কোনো বিক্রয় রেকর্ড পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($salesCashbooks->hasPages())
                    <div class="box-footer py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2 border-top">
                        <div class="text-muted fs-13">মোট {{ $salesCashbooks->total() }} টির মধ্যে {{ $salesCashbooks->firstItem() }} থেকে {{ $salesCashbooks->lastItem() }} দেখানো হচ্ছে</div>
                        <div>{{ $salesCashbooks->links('pagination::bootstrap-5') }}</div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
