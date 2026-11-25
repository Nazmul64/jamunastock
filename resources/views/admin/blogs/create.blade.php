@extends('layouts.backend.app')

@section('title', 'নতুন ব্লগ পোস্ট তৈরি')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content pt-3">
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="fw-bold mb-1" style="color: #0f172a;">
                    <i class="fa fa-pencil-square-o text-success me-2"></i>নতুন ব্লগ পোস্ট তৈরি (Create Blog Post)
                </h4>
                <p class="text-muted small mb-0">শিরোনাম, ক্যাটাগরি, ফিচার্ড ছবি এবং বিস্তারিত কন্টেন্ট যুক্ত করুন।</p>
            </div>
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="fa fa-arrow-left me-1"></i>ব্লগ তালিকায় ফিরে যান
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Left Side: Main Content -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark fs-14">ব্লগ পোস্টের শিরোনাম <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-lg" placeholder="যেমন: টেকসই কৃষিতে আধুনিক বিনিয়োগের গুরুত্ব" required value="{{ old('title') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">সংক্ষিপ্ত বিবরণ (Short Summary / Excerpt)</label>
                            <textarea name="summary" rows="3" class="form-control" placeholder="১-২ লাইনে পোস্টের সারসংক্ষেপ লিখুন যা হোমপেজ বা ব্লগের তালিকায় দেখা যাবে...">{{ old('summary') }}</textarea>
                            <small class="text-muted">হোমপেজ ও সোশ্যাল মিডিয়া শেয়ারের জন্য ব্যবহৃত হবে।</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark fs-14">বিস্তারিত বিষয়বস্তু (Full Content) <span class="text-danger">*</span></label>
                            <textarea name="content" id="blogEditor" rows="14" class="form-control" placeholder="এখানে আপনার পূর্ণ ব্লগ পোস্টের বিস্তারিত লিখুন..." required>{{ old('content') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Publishing Options & Media -->
            <div class="col-lg-4">
                <!-- Publishing Card -->
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold mb-0 text-dark"><i class="fa fa-cogs text-secondary me-2"></i>প্রকাশনা তথ্য</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">স্ট্যাটাস <span class="text-danger">*</span></label>
                            <select name="status" class="form-select">
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>পাবলিশ করুন (Published)</option>
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>খসড়া রাখুন (Draft)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">ক্যাটাগরি</label>
                            <select name="category_id" class="form-select">
                                <option value="">ক্যাটাগরি নির্বাচন করুন</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">লেখকের নাম</label>
                            <input type="text" name="author_name" class="form-control" value="{{ old('author_name', 'যমুনা এডিটরিয়াল') }}">
                        </div>

                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-dark small" for="is_featured">
                                <i class="fa fa-star text-warning me-1"></i>প্রধান পাতায় ফিচার্ড হিসেবে দেখান
                            </label>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-bold shadow-sm">
                            <i class="fa fa-paper-plane me-1"></i>ব্লগ পোস্ট সংরক্ষণ ও প্রকাশ করুন
                        </button>
                    </div>
                </div>

                <!-- Featured Image Card -->
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold mb-0 text-dark"><i class="fa fa-image text-success me-2"></i>ফিচার্ড ছবি (Featured Image)</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3 border rounded-3 p-2 bg-light" style="min-height: 180px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <img id="imagePreview" src="{{ asset('assets/images/placeholder-blog.jpg') }}" alt="Preview" style="max-height: 180px; width: 100%; object-fit: cover; display: none;" class="rounded-2">
                            <div id="noImageText" class="text-muted small">
                                <i class="fa fa-cloud-upload fa-3x d-block mb-2 text-secondary"></i>
                                ছবি নির্বাচন করুন (JPG, PNG, WebP)
                            </div>
                        </div>
                        <input type="file" name="image" id="imageInput" class="form-control form-control-sm" accept="image/*" onchange="previewBlogImage(event)">
                        <small class="text-muted d-block mt-2">সর্বোচ্চ ফাইলের আকার: 5MB</small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
        </section>
    </div>
</div>

<script>
function previewBlogImage(event) {
    const input = event.target;
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('noImageText');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
