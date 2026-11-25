@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-shield text-danger me-2"></i> জিরো টলারেন্স ও অভিযোগ (Z - Zero Tolerance & Complaints)
                    </h3>
                    <small class="text-muted">আর্থিক অনিয়ম প্রতিরোধ, গ্রাহক ও কর্মচারী অভিযোগ বক্স ও জবাবদিহিতা নিশ্চিতকরণ</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-danger rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addComplaintModal" data-toggle="modal" data-target="#addComplaintModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন অভিযোগ / অনিয়ম রিপোর্ট
                    </button>
                    <a href="{{ route('admin.branch.dashboard', ['branch_id' => $branch->id]) }}" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Zero Tolerance Declaration Banner -->
            <div class="alert alert-danger border-0 rounded-4 p-4 shadow-xs mb-4">
                <div class="d-flex align-items-center gap-3">
                    <i class="fa fa-exclamation-triangle fs-36 text-danger"></i>
                    <div>
                        <h5 class="fw-bold text-danger mb-1">যমুনা স্টক মার্কেট পিএলসি — জিরো টলারেন্স নীতিমালা (Zero Tolerance Policy)</h5>
                        <p class="mb-0 fs-13 text-dark">
                            আর্থিক অনিয়ম, ভুয়া ভাউচার বা রিপোর্ট তৈরি, অনুমোদন ছাড়া অর্থ লেনদেন এবং কোম্পানির সম্পদের অপব্যবহার সম্পূর্ণ নিষিদ্ধ। যেকোনো অভিযোগ বা অনিয়ম তাৎক্ষণিকভাবে প্রধান কার্যালয় ও অডিট সেলে পৌঁছায়।
                        </p>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success rounded-pill px-4 py-2 mb-4">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                </div>
            @endif

            <div class="box border-0 shadow-xs rounded-4 mb-4">
                <div class="box-header py-3 px-4 bg-light border-bottom">
                    <h5 class="box-title fw-bold text-dark mb-0">অভিযোগ ও তদন্ত রেজিস্টার</h5>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted fs-12">
                                <tr>
                                    <th class="ps-4">অভিযোগকারী</th>
                                    <th>বিষয় (Subject)</th>
                                    <th>গুরুত্ব (Severity)</th>
                                    <th>বিস্তারিত অভিযোগ</th>
                                    <th>তারিখ</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end pe-4">একশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($complaints as $cmp)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark fs-14">{{ $cmp->complainant_name }}</div>
                                            <small class="badge bg-light text-muted border">{{ ucfirst($cmp->complainant_type) }}</small>
                                            @if($cmp->phone)<small class="text-muted d-block">{{ $cmp->phone }}</small>@endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark fs-13">{{ $cmp->subject }}</div>
                                        </td>
                                        <td>
                                            @if($cmp->severity === 'critical')
                                                <span class="badge bg-danger text-white rounded-pill px-3 py-1 fw-bold fs-11">জরুরি / মারাত্মক</span>
                                            @elseif($cmp->severity === 'high')
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold fs-11">উচ্চ</span>
                                            @else
                                                <span class="badge bg-info-light text-info rounded-pill px-3 py-1 fs-11">সাধারণ</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-dark fs-12" style="max-width: 300px;">{{ Str::limit($cmp->complaint_details, 60) }}</div>
                                            @if($cmp->resolution_notes)
                                                <small class="text-success fw-semibold d-block mt-1">সমাধান: {{ $cmp->resolution_notes }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">{{ $cmp->created_at->format('d M, Y') }}</span>
                                        </td>
                                        <td>
                                            @if($cmp->status === 'resolved')
                                                <span class="badge bg-success-light text-success rounded-pill px-3 py-1 fw-bold fs-12">নিষ্পত্তিকৃত</span>
                                            @elseif($cmp->status === 'investigating')
                                                <span class="badge bg-warning-light text-warning rounded-pill px-3 py-1 fw-bold fs-12">তদন্তাধীন</span>
                                            @else
                                                <span class="badge bg-danger-light text-danger rounded-pill px-3 py-1 fw-bold fs-12">পেন্ডিং</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            @if($cmp->status !== 'resolved')
                                                <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#resolveModal{{ $cmp->id }}" data-toggle="modal" data-target="#resolveModal{{ $cmp->id }}">
                                                    <i class="fa fa-check me-1"></i> সমাধান করুন
                                                </button>

                                                <!-- Resolve Modal -->
                                                <div class="modal fade text-start" id="resolveModal{{ $cmp->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
                                                            <div class="modal-header bg-success text-white py-3 px-4">
                                                                <h5 class="modal-title fw-bold mb-0">অভিযোগ নিষ্পত্তি ফরম</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal"></button>
                                                            </div>
                                                            <form action="{{ route('admin.branch.complaints.resolve', ['id' => $cmp->id, 'branch_id' => $branch->id]) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body p-4">
                                                                    <p class="text-muted small mb-3">অভিযোগের বিষয়: <strong>{{ $cmp->subject }}</strong></p>
                                                                    <div class="form-group mb-2">
                                                                        <label class="fw-bold mb-1">সমাধান ও গৃহীত পদক্ষেপের বিবরণ <span class="text-danger">*</span></label>
                                                                        <textarea name="resolution_notes" class="form-control rounded-3" rows="3" placeholder="কিভাবে বিষয়টি সমাধান করা হলো বিস্তারিত লিখুন..." required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer bg-light py-3 px-4">
                                                                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                                                                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">নিষ্পত্তি নিশ্চিত করুন</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-success fs-12 fw-bold"><i class="fa fa-check-circle me-1"></i> সম্পন্ন</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            বর্তমানে এই ব্রাঞ্চে কোনো সক্রিয় অভিযোগ নেই।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal: Add Complaint -->
<div class="modal fade" id="addComplaintModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-danger text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0"><i class="fa fa-exclamation-circle me-2"></i> অভিযোগ / অনিয়ম নথিভুক্ত করুন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.branch.complaints.store', ['branch_id' => $branch->id]) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">অভিযোগকারীর নাম <span class="text-danger">*</span></label>
                        <input type="text" name="complainant_name" class="form-control rounded-pill" placeholder="যেমন: মো: সেলিম রেজা" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="fw-bold mb-1">অভিযোগকারীর ধরন</label>
                            <select name="complainant_type" class="form-select rounded-pill" required>
                                <option value="customer">সাধারণ গ্রাহক (Customer)</option>
                                <option value="employee">ব্রাঞ্চ কর্মচারী (Employee)</option>
                                <option value="public">বহিরাগত / জনসাধারণ</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-1">মোবাইল ফোন</label>
                            <input type="text" name="phone" class="form-control rounded-pill" placeholder="017xxxxxxxx">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-8 mb-2 mb-md-0">
                            <label class="fw-bold mb-1">অভিযোগের বিষয় (Subject) <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control rounded-pill" placeholder="যেমন: ক্যাশ রশিদে হিসাবের গরমিল" required>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold mb-1">গুরুত্ব (Severity) <span class="text-danger">*</span></label>
                            <select name="severity" class="form-select rounded-pill" required>
                                <option value="medium">সাধারণ</option>
                                <option value="high">উচ্চ</option>
                                <option value="critical">মারাত্মক / জরুরি</option>
                                <option value="low">কম</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="fw-bold mb-1">অভিযোগের বিস্তারিত বিবরণ <span class="text-danger">*</span></label>
                        <textarea name="complaint_details" class="form-control rounded-3" rows="3" placeholder="ঘটনা ও বিবরণ সুস্পষ্টভাবে লিখুন..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">নথিভুক্ত করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
