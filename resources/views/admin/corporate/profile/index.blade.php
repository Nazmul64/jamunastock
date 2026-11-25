@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-building text-success me-2"></i>প্রাতিষ্ঠানিক পরিচিতি ও নেতৃত্বের বাণী ব্যবস্থাপনা</h4>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের ইতিহাস, ভিশন-মিশন, লক্ষ্য এবং শীর্ষ নেতৃবৃন্দের বক্তব্য পরিচালনা করুন</p>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Section 1: Corporate Profile Sections -->
            <div class="box">
                <div class="box-header with-border">
                    <h5 class="box-title">১. প্রাতিষ্ঠানিক পরিচিতি সেকশনসমূহ</h5>
                </div>
                <div class="box-body">
                    <div class="accordion" id="corporateAccordion">
                        @foreach($profiles as $p)
                            <div class="accordion-item mb-3 border rounded-3 overflow-hidden">
                                <h2 class="accordion-header" id="heading-{{ $p->id }}">
                                    <button class="accordion-button collapsed fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $p->id }}" aria-expanded="false" aria-controls="collapse-{{ $p->id }}">
                                        <i class="fa fa-bookmark text-success me-2"></i>{{ $p->title_bn }} ({{ $p->section_key }})
                                    </button>
                                </h2>
                                <div id="collapse-{{ $p->id }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $p->id }}" data-bs-parent="#corporateAccordion">
                                    <div class="accordion-body p-4 bg-white">
                                        <form action="{{ route('admin.corporate.profile.update', $p->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label font-weight-bold">শিরোনাম (বাংলা) <span class="text-danger">*</span></label>
                                                    <input type="text" name="title_bn" class="form-control" required value="{{ $p->title_bn }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label font-weight-bold">শিরোনাম (ইংরেজি)</label>
                                                    <input type="text" name="title_en" class="form-control" value="{{ $p->title_en }}">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label font-weight-bold">বিস্তারিত বিষয়বস্তু (বাংলা) <span class="text-danger">*</span></label>
                                                    <textarea name="content_bn" class="form-control" rows="5" required>{{ $p->content_bn }}</textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label font-weight-bold">ফিচার্ড ছবি পরিবর্তন</label>
                                                    <input type="file" name="featured_image" class="form-control" accept="image/*">
                                                    @if($p->featured_image)
                                                        <div class="mt-2">
                                                            <img src="{{ asset($p->featured_image) }}" style="max-height: 80px;" class="rounded border">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-12 text-end">
                                                    <button type="submit" class="btn btn-success font-weight-bold">আপডেট করুন</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Section 2: Leadership Speeches -->
            <div class="box mt-4">
                <div class="box-header with-border">
                    <h5 class="box-title">২. শীর্ষ নেতৃবৃন্দের বাণীসমূহ (Leadership Messages)</h5>
                </div>
                <div class="box-body">
                    <div class="row g-4">
                        @foreach($speeches as $sp)
                            <div class="col-lg-6">
                                <div class="card border rounded-3 p-3 h-100 shadow-none">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3">
                                            @if($sp->photo)
                                                <img src="{{ asset($sp->photo) }}" class="rounded-circle object-fit-cover" style="width: 50px; height: 50px;">
                                            @else
                                                <div class="rounded-circle bg-light text-success d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fa fa-user-tie"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="font-weight-bold mb-0 text-dark">{{ $sp->name_bn ?: $sp->name }}</h6>
                                            <span class="badge bg-success-subtle text-success">{{ $sp->designation_bn ?: $sp->designation }}</span>
                                        </div>
                                    </div>

                                    <form action="{{ route('admin.corporate.speeches.update', $sp->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-2">
                                            <label class="form-label small font-weight-bold">বাণীর শিরোনাম <span class="text-danger">*</span></label>
                                            <input type="text" name="speech_title" class="form-control form-control-sm" required value="{{ $sp->speech_title ?: 'শুভেচ্ছা বাণী' }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small font-weight-bold">বাণীর বিষয়বস্তু <span class="text-danger">*</span></label>
                                            <textarea name="speech_message" class="form-control form-control-sm" rows="4" required>{{ $sp->speech_message }}</textarea>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-sm btn-success font-weight-bold">বাণী সংরক্ষণ করুন</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection

