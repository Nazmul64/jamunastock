@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('admin.investments.inquiries.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> সকল ইনকোয়ারিতে ফিরে যান
                </a>
            </div>
            @if($inquiry->status !== 'converted')
                <div>
                    <a href="{{ route('admin.investments.inquiries.convert', $inquiry->id) }}" class="btn btn-success fw-bold px-4 rounded-pill shadow-sm">
                        <i class="fa fa-check-double me-1"></i> Convert Inquiry to Investment (চুক্তিতে রূপান্তর)
                    </a>
                </div>
            @endif
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="fa fa-check-circle fa-lg"></i>
                <div class="fw-medium">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Left: Inquiry Details -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                        <div>
                            <span class="badge bg-light text-primary border fs-6">{{ $inquiry->inquiry_code }}</span>
                            <h4 class="fw-bold mb-0 text-dark mt-2">{{ $inquiry->name }}</h4>
                            <small class="text-muted"><i class="fa fa-clock me-1"></i>আবেদনের তারিখ: {{ $inquiry->created_at->format('d F, Y - h:i A') }}</small>
                        </div>
                        <span class="badge {{ $inquiry->status_badge }} fs-6 px-3 py-2">
                            {{ ucfirst(str_replace('_', ' ', $inquiry->status)) }}
                        </span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">মোবাইল নম্বর:</span>
                            <span class="fw-bold text-dark fs-6">{{ $inquiry->phone }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">ইমেইল এড্রেস:</span>
                            <span class="fw-bold text-dark">{{ $inquiry->email ?? 'N/A' }}</span>
                        </div>
                        <div class="col-12">
                            <span class="text-muted small d-block">ঠিকানা:</span>
                            <span class="fw-medium text-dark">{{ $inquiry->address ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">আগ্রহী বিনিয়োগের বিবরণ</h6>
                    <div class="bg-light rounded-4 p-4 mb-4">
                        <div class="row g-3 text-center">
                            <div class="col-4 border-end">
                                <span class="text-muted small">নির্বাচিত খাত</span>
                                <h6 class="fw-bold text-dark my-1">{{ $inquiry->sector->name_bn }}</h6>
                                <span class="badge bg-success bg-opacity-10 text-success">{{ $inquiry->sector->code }}</span>
                            </div>
                            <div class="col-4 border-end">
                                <span class="text-muted small">প্রস্তাবিত পরিমাণ</span>
                                <h5 class="fw-bold text-success my-1">৳{{ number_format($inquiry->proposed_amount) }}</h5>
                            </div>
                            <div class="col-4">
                                <span class="text-muted small">প্রস্তাবিত মেয়াদ</span>
                                <h5 class="fw-bold text-primary my-1">{{ $inquiry->duration_years }} বছর</h5>
                            </div>
                        </div>
                    </div>

                    @if($inquiry->message)
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small">আবেদনকারীর বার্তা / মন্তব্য:</label>
                            <div class="p-3 bg-light rounded-3 text-dark">{{ $inquiry->message }}</div>
                        </div>
                    @endif

                    @if($inquiry->converted_investment_id)
                        <div class="alert alert-success rounded-4 p-3 d-flex align-items-center gap-3">
                            <i class="fa fa-check-circle fa-2x"></i>
                            <div>
                                <h6 class="fw-bold mb-0">এই ইনকোয়ারিটি অফিসিয়াল বিনিয়োগে রূপান্তরিত হয়েছে!</h6>
                                <a href="{{ route('admin.investments.show', $inquiry->converted_investment_id) }}" class="btn btn-sm btn-dark rounded-pill mt-2">
                                    বিনিয়োগ অ্যাকাউন্ট দেখুন (#{{ $inquiry->investment->investment_code ?? '' }})
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right: CRM Status & Call Notes -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa fa-tasks text-primary me-2"></i>CRM পাইপলাইন স্ট্যাটাস ও যোগাযোগ নোট</h5>
                    
                    <form action="{{ route('admin.investments.inquiries.update_status', $inquiry->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">বর্তমান স্ট্যাটাস <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-lg" required>
                                <option value="new" {{ $inquiry->status == 'new' ? 'selected' : '' }}>১. New Inquiry (নতুন আবেদন)</option>
                                <option value="pending_contact" {{ $inquiry->status == 'pending_contact' ? 'selected' : '' }}>২. Pending Contact (ফোন করা বাকি)</option>
                                <option value="contacted" {{ $inquiry->status == 'contacted' ? 'selected' : '' }}>৩. Contacted (যোগাযোগ সম্পন্ন)</option>
                                <option value="interested" {{ $inquiry->status == 'interested' ? 'selected' : '' }}>৪. Interested (আগ্রহী গ্রাহক)</option>
                                <option value="meeting_scheduled" {{ $inquiry->status == 'meeting_scheduled' ? 'selected' : '' }}>৫. Meeting Scheduled (মিটিং নির্ধারিত)</option>
                                <option value="meeting_completed" {{ $inquiry->status == 'meeting_completed' ? 'selected' : '' }}>৬. Meeting Completed (অফিস মিটিং সম্পন্ন)</option>
                                <option value="agreement_pending" {{ $inquiry->status == 'agreement_pending' ? 'selected' : '' }}>৭. Agreement Pending (চুক্তি প্রক্রিয়াধীন)</option>
                                <option value="converted" {{ $inquiry->status == 'converted' ? 'selected' : '' }}>৮. Converted (বিনিয়োগ সম্পন্ন)</option>
                                <option value="rejected" {{ $inquiry->status == 'rejected' ? 'selected' : '' }}>৯. Rejected (আগ্রহী নয়)</option>
                                <option value="cancelled" {{ $inquiry->status == 'cancelled' ? 'selected' : '' }}>১০. Cancelled (বাতিল)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">দায়িত্বপ্রাপ্ত কর্মকর্তা (Assigned Officer)</label>
                            <select name="assigned_to" class="form-select">
                                <option value="">-- কাউকে এসাইন করা হয়নি --</option>
                                @foreach($officers as $off)
                                    <option value="{{ $off->id }}" {{ $inquiry->assigned_to == $off->id ? 'selected' : '' }}>
                                        {{ $off->name }} ({{ $off->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">যোগাযোগ হিস্ট্রি ও অভ্যন্তরীণ নোট</label>
                            <textarea name="notes" class="form-control" rows="5" placeholder="গ্রাহকের সাথে কি কথা হলো, কবে অফিসে আসবেন ইত্যাদি নোট লিখুন...">{{ $inquiry->notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm">
                            <i class="fa fa-save me-1"></i> নোট ও স্ট্যাটাস সংরক্ষণ করুন
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
