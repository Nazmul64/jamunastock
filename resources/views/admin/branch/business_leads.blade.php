@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-briefcase text-primary me-2"></i> নতুন ব্যবসা উন্নয়ন ও সম্প্রসারণ (N/X - New Business & Expansion)
                    </h3>
                    <small class="text-muted">নতুন ব্যবসায়িক সুযোগ, করপোরেট ক্লায়েন্ট, প্রাতিষ্ঠানিক পার্টনারশিপ ও সম্প্রসারণ প্রস্তাবনা</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addLeadModal" data-toggle="modal" data-target="#addLeadModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন সুযোগ / লিড যোগ করুন
                    </button>
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
                    <h5 class="box-title fw-bold text-dark mb-0">ব্যবসায়িক লিড ও সুযোগের তালিকা</h5>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted fs-12">
                                <tr>
                                    <th class="ps-4">ব্যবসায়ী / ক্লায়েন্টের নাম</th>
                                    <th>প্রতিষ্ঠান ও যোগাযোগ</th>
                                    <th>ব্যবসার ধরন (Type)</th>
                                    <th>সম্ভাব্য মূল্য (Estimated Value)</th>
                                    <th>বর্তমান অবস্থা (Stage)</th>
                                    <th class="text-end pe-4">হেড অফিস স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leads as $ld)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark fs-14">{{ $ld->lead_name }}</div>
                                            <small class="text-muted">{{ $ld->details ? Str::limit($ld->details, 40) : 'কোনো অতিরিক্ত বিবরণ নেই' }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark fs-13">{{ $ld->organization_name ?: 'ব্যক্তিগত' }}</div>
                                            <small class="text-muted"><i class="fa fa-phone me-1"></i> {{ $ld->phone }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ ucfirst(str_replace('_', ' ', $ld->lead_type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success fs-14">৳{{ number_format($ld->estimated_value, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($ld->stage === 'closed_won')
                                                <span class="badge bg-success-light text-success rounded-pill px-3 py-1 fw-bold fs-11">সফল (Closed Won)</span>
                                            @elseif($ld->stage === 'proposal_submitted')
                                                <span class="badge bg-primary-light text-primary rounded-pill px-3 py-1 fw-bold fs-11">প্রস্তাবনা প্রেরিত</span>
                                            @else
                                                <span class="badge bg-warning-light text-warning rounded-pill px-3 py-1 fw-bold fs-11">যোগাযোগ চলছে</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            @if($ld->hq_status === 'approved')
                                                <span class="badge bg-success text-white rounded-pill px-2 py-1 fs-11">অনুমোদিত</span>
                                            @else
                                                <span class="badge bg-secondary-light text-secondary rounded-pill px-2 py-1 fs-11">পর্যালোচনাধীন</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            কোনো নতুন ব্যবসায়িক লিড এখনো রেকর্ড করা হয়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($leads->hasPages())
                    <div class="box-footer py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2 border-top">
                        <div class="text-muted fs-13">মোট {{ $leads->total() }} টির মধ্যে {{ $leads->firstItem() }} থেকে {{ $leads->lastItem() }} দেখানো হচ্ছে</div>
                        <div>{{ $leads->links('pagination::bootstrap-5') }}</div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

<!-- Modal: Add Lead -->
<div class="modal fade" id="addLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0"><i class="fa fa-plus-circle me-2"></i> নতুন ব্যবসায়িক সুযোগ / লিড যোগ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.branch.business_leads.store', ['branch_id' => $branch->id]) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">ক্লায়েন্টের নাম (Lead Name) <span class="text-danger">*</span></label>
                        <input type="text" name="lead_name" class="form-control rounded-pill" placeholder="যেমন: মো: রফিক উদ্দিন" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="fw-bold mb-1">প্রতিষ্ঠানের নাম</label>
                            <input type="text" name="organization_name" class="form-control rounded-pill" placeholder="যেমন: রফিক ট্রেডার্স">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-1">মোবাইল ফোন <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control rounded-pill" placeholder="017xxxxxxxx" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="fw-bold mb-1">ব্যবসার ধরন (Lead Type) <span class="text-danger">*</span></label>
                            <select name="lead_type" class="form-select rounded-pill" required>
                                <option value="fmcg_wholesale">FMCG পাইকারি ডিলারশিপ</option>
                                <option value="property_investor">প্রপার্টি ও রিয়েল এস্টেট ক্রেতা</option>
                                <option value="corporate_membership">করপোরেট মেম্বারশিপ</option>
                                <option value="agro_supply">কৃষি পণ্য সরবরাহ চুক্তি</option>
                                <option value="other">অন্যান্য অংশীদারিত্ব</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-1">সম্ভাব্য আর্থিক মূল্য (টাকা)</label>
                            <input type="number" step="0.01" name="estimated_value" class="form-control rounded-pill" placeholder="যেমন: 200000">
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="fw-bold mb-1">বিস্তারিত বিবরণ ও প্রস্তাবনা</label>
                        <textarea name="details" class="form-control rounded-3" rows="3" placeholder="আলোচনার সারসংক্ষেপ ও ব্যবসায়িক সুযোগের বিবরণ..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
