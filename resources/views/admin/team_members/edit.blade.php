@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-edit text-success me-2"></i>টিম মেম্বার এডিট করুন</h4>
                    <p class="text-muted mb-0">টিম মেম্বারের ছবি, নাম ও পদবী আপডেট করুন</p>
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
                            <h5 class="box-title">টিম মেম্বার আপডেট ফরম</h5>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('admin.team_members.update', $member->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Name -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">নাম (Name) <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $member->name) }}" required autofocus>
                                </div>

                                <!-- Designation -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">পদবী (Designation) <span class="text-danger">*</span></label>
                                    <input type="text" name="designation" class="form-control" value="{{ old('designation', $member->designation) }}" required>
                                </div>

                                <!-- Photo Upload -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">ছবি পরিবর্তন (Photo)</label>
                                    <input type="file" name="photo" id="photoInput" class="form-control" accept="image/*" onchange="previewPhoto(event)">
                                    <small class="text-muted d-block mt-1">অনুমোদিত ফরম্যাট: JPG, JPEG, PNG, WEBP (সর্বোচ্চ ৫ মেগাবাইট)</small>
                                    
                                    <div class="mt-3">
                                        <label class="form-label small text-muted d-block">বর্তমান ছবি:</label>
                                        @if($member->photo && file_exists(public_path($member->photo)))
                                            <img id="photoPreview" src="{{ asset($member->photo) }}" alt="{{ $member->name }}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 3px solid #10b981;">
                                        @else
                                            <div id="photoPlaceholder" class="rounded-circle bg-light d-flex align-items-center justify-content-center text-muted" style="width: 120px; height: 120px; border: 3px solid #cbd5e1;">
                                                <i class="fa fa-user fa-3x"></i>
                                            </div>
                                            <img id="photoPreview" src="#" alt="Preview" style="display: none; width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 3px solid #10b981;">
                                        @endif
                                    </div>
                                </div>

                                <!-- Order & Status -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">ক্রমিক নম্বর (Display Order)</label>
                                        <input type="number" name="order_num" class="form-control" value="{{ old('order_num', $member->order_num) }}" min="1">
                                        <small class="text-muted">যে ক্রমানুসারে ফ্রন্টএন্ডে শো করবে (১, ২, ৩...)</small>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center pt-3">
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ $member->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold ms-2" for="isActive">সক্রিয় (Active)</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <button type="submit" class="btn btn-success px-4 fw-bold">
                                        <i class="fa fa-save me-1"></i>আপডেট সংরক্ষণ করুন
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
            const preview = document.getElementById('photoPreview');
            preview.src = e.target.result;
            preview.style.display = 'block';
            const placeholder = document.getElementById('photoPlaceholder');
            if (placeholder) placeholder.style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
