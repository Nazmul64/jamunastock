@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-id-card-o text-warning me-2"></i> মেম্বারশিপ ব্যবস্থাপনা (M - Membership Management)
                    </h3>
                    <small class="text-muted">ব্রাঞ্চের আওতায় এসএমই কার্ড, গোল্ড কার্ড ও মেম্বারশিপ নিবন্ধন ও কমিশন</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#issueCardModal" data-toggle="modal" data-target="#issueCardModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন মেম্বারশিপ ইস্যু করুন
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
                    <h5 class="box-title fw-bold text-dark mb-0">নিবন্ধিত মেম্বারশিপ কার্ড তালিকা</h5>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted fs-12">
                                <tr>
                                    <th class="ps-4">সদস্যের নাম ও আইডি</th>
                                    <th>কার্ডের ধরন</th>
                                    <th>মোবাইল নম্বর</th>
                                    <th>এনআইডি (NID)</th>
                                    <th>নিবন্ধনের তারিখ</th>
                                    <th class="text-end pe-4">স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $m)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark fs-14">{{ $m->name }}</div>
                                            <small class="font-monospace text-primary fw-bold">{{ $m->customer_id ?: 'JSM-CARD-' . $m->id }}</small>
                                        </td>
                                        <td>
                                            @if(str_contains(strtolower($m->membership_card_type), 'gold'))
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold fs-12">যমুনা গোল্ড কার্ড</span>
                                            @elseif(str_contains(strtolower($m->membership_card_type), 'property'))
                                                <span class="badge bg-info-light text-info rounded-pill px-3 py-1 fw-bold fs-12">যমুনা প্রপার্টি কার্ড</span>
                                            @else
                                                <span class="badge bg-success-light text-success rounded-pill px-3 py-1 fw-bold fs-12">যমুনা এসএমই কার্ড</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-dark fs-13"><i class="fa fa-phone text-muted me-1"></i> {{ $m->phone ?: 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="font-monospace text-muted fs-12">{{ $m->nid_number ?: 'যাচাইকৃত' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">{{ $m->created_at->format('d M, Y') }}</span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <span class="badge bg-success text-white rounded-pill px-3 py-1 fs-11">সক্রিয় মেম্বার</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            এই ব্রাঞ্চে এখনো কোনো মেম্বারশিপ কার্ড রেকর্ড নেই।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($members->hasPages())
                    <div class="box-footer py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2 border-top">
                        <div class="text-muted fs-13">মোট {{ $members->total() }} জনের মধ্যে {{ $members->firstItem() }} থেকে {{ $members->lastItem() }} দেখানো হচ্ছে</div>
                        <div>{{ $members->links('pagination::bootstrap-5') }}</div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

<!-- Modal: Issue Card -->
<div class="modal fade" id="issueCardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-warning text-dark py-3 px-4">
                <h5 class="modal-title fw-bold mb-0"><i class="fa fa-id-card-o me-2"></i> নতুন মেম্বারশিপ কার্ড প্রদান</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.branch.membership.store', ['branch_id' => $branch->id]) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">গ্রাহকের পুরো নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-pill" placeholder="যেমন: মো: আব্দুল্লাহ" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">মোবাইল ফোন নম্বর <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control rounded-pill" placeholder="017xxxxxxxx" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">কার্ডের ধরন বাছাই করুন <span class="text-danger">*</span></label>
                        <select name="membership_card_type" class="form-select rounded-pill" required>
                            <option value="Jamuna SME Card">যমুনা এসএমই কার্ড (SME Card)</option>
                            <option value="Jamuna SME Gold Card">যমুনা এসএমই গোল্ড কার্ড (Gold Card)</option>
                            <option value="Jamuna Property Card">যমুনা প্রপার্টি কার্ড (Property Card)</option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label class="fw-bold mb-1">এনআইডি নম্বর (NID Number)</label>
                        <input type="text" name="nid_number" class="form-control rounded-pill" placeholder="জাতীয় পরিচয়পত্র নম্বর">
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">কার্ড ইস্যু করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
