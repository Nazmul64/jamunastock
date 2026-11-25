@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-percent text-warning me-2"></i> কমিশন ব্যবস্থাপনা (C - Commission Management)
                    </h3>
                    <small class="text-muted">পণ্য বিক্রয়, FMCG, প্রপার্টি, মেম্বারশিপ ও রেফারেল কমিশন লেজার</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addCommModal" data-toggle="modal" data-target="#addCommModal">
                        <i class="fa fa-plus-circle me-1"></i> কমিশন এন্ট্রি যোগ করুন
                    </button>
                    <a href="{{ route('admin.branch.dashboard', ['branch_id' => $branch->id]) }}" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- 3 Commission Overview Cards -->
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-body p-3">
                            <span class="text-muted fs-12 fw-bold text-uppercase">পরিশোধিত কমিশন (Paid Commission)</span>
                            <h3 class="fw-bold text-success mb-0 mt-1">৳{{ number_format($totalPaid, 2) }}</h3>
                            <small class="text-muted fs-11">ডিরেক্টরের অ্যাকাউন্টে ট্রান্সফার সম্পন্ন</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-body p-3">
                            <span class="text-muted fs-12 fw-bold text-uppercase">অনুমোদিত কমিশন (Approved)</span>
                            <h3 class="fw-bold text-primary mb-0 mt-1">৳{{ number_format($totalApproved, 2) }}</h3>
                            <small class="text-muted fs-11">হেড অফিস অনুমোদিত, বিতরণের অপেক্ষায়</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-body p-3">
                            <span class="text-muted fs-12 fw-bold text-uppercase">অপেক্ষমাণ কমিশন (Pending)</span>
                            <h3 class="fw-bold text-danger mb-0 mt-1">৳{{ number_format($totalPending, 2) }}</h3>
                            <small class="text-muted fs-11">যাচাই ও অনুমোদন প্রক্রিয়াধীন</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commission Records Table -->
            <div class="box border-0 shadow-xs rounded-4 mb-4">
                <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="box-title fw-bold text-dark mb-0">ব্রাঞ্চ কমিশন রেকর্ডসমূহ</h5>
                    <form method="GET" class="d-flex align-items-center gap-2 m-0">
                        <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                        <select name="source_type" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                            <option value="">-- সকল খাত (All Types) --</option>
                            <option value="product_sales" {{ request('source_type') === 'product_sales' ? 'selected' : '' }}>পণ্য বিক্রয় (Product Sales)</option>
                            <option value="fmcg" {{ request('source_type') === 'fmcg' ? 'selected' : '' }}>FMCG ভোগ্যপণ্য</option>
                            <option value="property" {{ request('source_type') === 'property' ? 'selected' : '' }}>প্রপার্টি ও জমি (Property)</option>
                            <option value="membership" {{ request('source_type') === 'membership' ? 'selected' : '' }}>মেম্বারশিপ কার্ড (Membership)</option>
                            <option value="bonus" {{ request('source_type') === 'bonus' ? 'selected' : '' }}>পারফরম্যান্স বোনাস</option>
                        </select>
                    </form>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted fs-12">
                                <tr>
                                    <th class="ps-4">রেফারেন্স ও খাত</th>
                                    <th>লেনদেনের বিবরণ</th>
                                    <th>মোট বিক্রয় / মূল্য</th>
                                    <th>কমিশন হার (%)</th>
                                    <th>কমিশন আয় (টাকা)</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end pe-4">পেমেন্ট মেথড ও তারিখ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commissions as $comm)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold font-monospace text-primary fs-12">{{ $comm->reference_no }}</div>
                                            <span class="badge bg-light text-dark border px-2 py-1 fs-11 mt-1">
                                                {{ strtoupper(str_replace('_', ' ', $comm->source_type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark fs-13">{{ $comm->reference_title }}</div>
                                            <small class="text-muted">{{ $comm->notes ?: 'কোনো অতিরিক্ত মন্তব্য নেই' }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark fs-13">৳{{ number_format($comm->gross_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark px-2 py-1 rounded-pill fw-bold fs-12">{{ $comm->commission_rate }}%</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success fs-14">৳{{ number_format($comm->commission_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($comm->status === 'paid')
                                                <span class="badge bg-success-light text-success rounded-pill px-3 py-1 fw-bold fs-12">পরিশোধিত</span>
                                            @elseif($comm->status === 'approved')
                                                <span class="badge bg-primary-light text-primary rounded-pill px-3 py-1 fw-bold fs-12">অনুমোদিত</span>
                                            @else
                                                <span class="badge bg-danger-light text-danger rounded-pill px-3 py-1 fw-bold fs-12">অপেক্ষমাণ</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="text-dark fs-12">{{ $comm->payout_date ? $comm->payout_date->format('d M, Y') : 'পেন্ডিং' }}</div>
                                            <small class="text-muted">{{ $comm->payment_method ? ucfirst($comm->payment_method) : '-' }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            কোনো কমিশন রেকর্ড পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($commissions->hasPages())
                    <div class="box-footer py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2 border-top">
                        <div class="text-muted fs-13">মোট {{ $commissions->total() }} টির মধ্যে {{ $commissions->firstItem() }} থেকে {{ $commissions->lastItem() }} দেখানো হচ্ছে</div>
                        <div>{{ $commissions->links('pagination::bootstrap-5') }}</div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

<!-- Modal: Add Commission Entry -->
<div class="modal fade" id="addCommModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-warning text-dark py-3 px-4">
                <h5 class="modal-title fw-bold mb-0"><i class="fa fa-plus-circle me-2"></i> নতুন কমিশন এন্ট্রি যোগ করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.branch.commissions.store', ['branch_id' => $branch->id]) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">কমিশনের খাত (Source Type) <span class="text-danger">*</span></label>
                        <select name="source_type" class="form-select rounded-pill" required>
                            <option value="product_sales">পণ্য বিক্রয় (Product Sales Commission)</option>
                            <option value="fmcg">FMCG ভোগ্যপণ্য কমিশন</option>
                            <option value="property">প্রপার্টি ও রিয়েল এস্টেট কমিশন (Property)</option>
                            <option value="membership">মেম্বারশিপ কমিশন (Membership)</option>
                            <option value="business">ব্যবসায়িক বিক্রয় (Business Sales)</option>
                            <option value="investment_referral">বিনিয়োগ রেফারেল কমিশন</option>
                            <option value="bonus">পারফরম্যান্স বোনাস (Performance Bonus)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">রেফারেন্স বিবরণ / চালান (Reference Title) <span class="text-danger">*</span></label>
                        <input type="text" name="reference_title" class="form-control rounded-pill" placeholder="যেমন: তিস্তা চাল পাইকারি বিক্রয় চাল-১০৪" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="fw-bold mb-1">মোট লেনদেনের পরিমাণ (টাকা) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="gross_amount" class="form-control rounded-pill" placeholder="যেমন: 100000" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-1">কমিশন হার (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="commission_rate" class="form-control rounded-pill" placeholder="যেমন: 5.0" required>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="fw-bold mb-1">মন্তব্য ও নোট (Notes)</label>
                        <textarea name="notes" class="form-control rounded-3" rows="2" placeholder="কমিশনের বিবরণ বা চালান নম্বর লিখুন..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
