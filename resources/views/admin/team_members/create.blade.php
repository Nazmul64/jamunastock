@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-user-plus text-success me-2"></i>নতুন টিম মেম্বার যুক্ত করুন</h4>
                    <p class="text-muted mb-0">টিম মেম্বারের ছবি, নাম ও পদবী এন্ট্রি করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.team_members.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i>তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if ($errors->any())
                <div class="alert alert-danger rounded-3 mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8 col-md-10">
                    <div class="box">
                        <div class="box-header with-border">
                            <h5 class="box-title">টিম মেম্বার তথ্য ফরম</h5>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('admin.team_members.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Name -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">নাম (Name) <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="যেমন: মো: আরিফুল ইসলাম" required autofocus>
                                </div>

                                <!-- Designation -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">পদবী (Designation) <span class="text-danger">*</span></label>
                                    <input type="text" name="designation" class="form-control" value="{{ old('designation') }}" placeholder="যেমন: চিফ টেকনোলজি অফিসার / সিনিয়র এক্সিকিউটিভ" required>
                                </div>

                                <!-- Photo Upload -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">ছবি (Photo) <span class="text-danger">*</span></label>
                                    <input type="file" name="photo" id="photoInput" class="form-control" accept="image/*" onchange="previewPhoto(event)">
                                    <small class="text-muted d-block mt-1">অনুমোদিত ফরম্যাট: JPG, JPEG, PNG, WEBP (সর্বোচ্চ ৫ মেগাবাইট)</small>
                                    
                                    <div class="mt-3" id="photoPreviewContainer" style="display: none;">
                                        <label class="form-label small text-muted d-block">প্রিভিউ:</label>
                                        <img id="photoPreview" src="#" alt="Preview" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 3px solid #10b981;">
                                    </div>
                                </div>

                                <!-- Order & Status -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">ক্রমিক নম্বর (Display Order)</label>
                                        <input type="number" name="order_num" class="form-control" value="{{ old('order_num', 1) }}" min="1">
                                        <small class="text-muted">যে ক্রমানুসারে ফ্রন্টএন্ডে শো করবে (১, ২, ৩...)</small>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center pt-3">
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" checked>
                                            <label class="form-check-label fw-bold ms-2" for="isActive">সক্রিয় (Active)</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <button type="submit" class="btn btn-success px-4 fw-bold">
                                        <i class="fa fa-save me-1"></i>টিম মেম্বার সংরক্ষণ করুন
                                    </button>
                                    <a href="{{ route('admin.team_members.index') }}" class="btn btn-light px-4 ms-2">বাতিল</a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
function previewPhoto(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').src = e.target.result;
            document.getElementById('photoPreviewContainer').style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
