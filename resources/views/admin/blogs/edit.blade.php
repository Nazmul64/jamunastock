@extends('layouts.backend.app')

@section('title', 'ব্লগ পোস্ট সম্পাদনা')

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
                    <i class="fa fa-pencil-square-o text-primary me-2"></i>ব্লগ পোস্ট সম্পাদনা (Edit Blog Post)
                </h4>
                <p class="text-muted small mb-0">ব্লগের শিরোনাম, বিবরণ, ক্যাটাগরি অথবা ছবি আপডেট করুন।</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('blogs.show', $post->slug) }}" target="_blank" class="btn btn-outline-info btn-sm rounded-pill px-3">
                    <i class="fa fa-eye me-1"></i>পোস্টটি দেখুন
                </a>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i>ব্লগ তালিকায় ফিরে যান
                </a>
            </div>
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

    <form action="{{ route('admin.blogs.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Left Side: Main Content -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark fs-14">ব্লগ পোস্টের শিরোনাম <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-lg" required value="{{ old('title', $post->title) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">সংক্ষিপ্ত বিবরণ (Short Summary / Excerpt)</label>
                            <textarea name="summary" rows="3" class="form-control">{{ old('summary', $post->summary) }}</textarea>
                            <small class="text-muted">হোমপেজ ও সোশ্যাল মিডিয়া শেয়ারের জন্য ব্যবহৃত হবে।</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark fs-14">বিস্তারিত বিষয়বস্তু (Full Content) <span class="text-danger">*</span></label>
                            <textarea name="content" id="blogEditor" rows="14" class="form-control" required>{{ old('content', $post->content) }}</textarea>
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
                                <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>পাবলিশড (Published)</option>
                                <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>খসড়া (Draft)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">ক্যাটাগরি</label>
                            <select name="category_id" class="form-select">
                                <option value="">ক্যাটাগরি নির্বাচন করুন</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $post->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">লেখকের নাম</label>
                            <input type="text" name="author_name" class="form-control" value="{{ old('author_name', $post->author_name) }}">
                        </div>

                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-dark small" for="is_featured">
                                <i class="fa fa-star text-warning me-1"></i>প্রধান পাতায় ফিচার্ড হিসেবে দেখান
                            </label>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">
                            <i class="fa fa-save me-1"></i>পরিবর্তন সংরক্ষণ করুন
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
                            @if($post->image && file_exists(public_path('uploads/blogs/' . $post->image)))
                                <img id="imagePreview" src="{{ asset('uploads/blogs/' . $post->image) }}" alt="{{ $post->title }}" style="max-height: 180px; width: 100%; object-fit: cover; display: block;" class="rounded-2">
                                <div id="noImageText" style="display: none;"></div>
                            @else
                                <img id="imagePreview" src="" alt="Preview" style="max-height: 180px; width: 100%; object-fit: cover; display: none;" class="rounded-2">
                                <div id="noImageText" class="text-muted small">
                                    <i class="fa fa-cloud-upload fa-3x d-block mb-2 text-secondary"></i>
                                    কোনো ছবি নেই। নতুন ছবি নির্বাচন করুন।
                                </div>
                            @endif
                        </div>
                        <input type="file" name="image" id="imageInput" class="form-control form-control-sm" accept="image/*" onchange="previewBlogImage(event)">
                        <small class="text-muted d-block mt-2">নতুন ছবি আপলোড করলে পূর্ববর্তী ছবিটি প্রতিস্থাপিত হবে।</small>
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
            if (placeholder) placeholder.style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
