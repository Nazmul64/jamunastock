@extends('layouts.backend.app')

@section('title', 'সকল ব্লগ পোস্ট')

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
                                <i class="fa fa-newspaper-o text-success me-2"></i>ব্লগ পোস্ট ম্যানেজমেন্ট (Blog Posts)
                            </h4>
                            <p class="text-muted small mb-0">নতুন ব্লগ লিখুন, ছবি আপলোড করুন ও পাবলিশ বা ড্রাফট করুন।</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.blogs.categories.index') }}" class="btn btn-outline-warning btn-sm rounded-pill px-3">
                                <i class="fa fa-folder me-1"></i>ক্যাটাগরি সমূহ
                            </a>
                            <a href="{{ route('admin.blogs.create') }}" class="btn btn-success btn-sm rounded-pill px-3">
                                <i class="fa fa-plus-circle me-1"></i>নতুন ব্লগ পোস্ট লিখুন
                            </a>
                            <a href="{{ route('blogs.index') }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                <i class="fa fa-external-link me-1"></i>লাইভ ব্লগ দেখুন
                            </a>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Filter Card -->
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-body p-3">
                        <form action="{{ route('admin.blogs.index') }}" method="GET" class="row g-2 align-items-center">
                            <div class="col-12 col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="শিরোনাম বা বিবরণ দিয়ে খুঁজুন..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <select name="category_id" class="form-select">
                                    <option value="">সকল ক্যাটাগরি</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">সকল স্ট্যাটাস</option>
                                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>পাবলিশড</option>
                                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>ড্রাফট</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100 rounded-pill">
                                    <i class="fa fa-filter me-1"></i>ফিল্টার
                                </button>
                                @if(request()->hasAny(['search', 'category_id', 'status']))
                                    <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary rounded-pill" title="রিসেট"><i class="fa fa-times"></i></a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

    <!-- Posts Table Card -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="fa fa-list-alt text-success me-2"></i>পোস্ট তালিকা
            </h5>
            <span class="badge bg-light text-dark border">মোট পোস্ট: {{ $posts->total() }}টি</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="min-width: 880px;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 80px;">ছবি</th>
                            <th>শিরোনাম ও ক্যাটাগরি</th>
                            <th>লেখক</th>
                            <th class="text-center">ভিউ</th>
                            <th class="text-center">ফিচার্ড</th>
                            <th class="text-center">স্ট্যাটাস</th>
                            <th>প্রকাশের তারিখ</th>
                            <th class="text-end" style="width: 140px;">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $index => $post)
                            <tr>
                                <td>{{ $posts->firstItem() + $index }}</td>
                                <td>
                                    @if($post->image && file_exists(public_path('uploads/blogs/' . $post->image)))
                                        <img src="{{ asset('uploads/blogs/' . $post->image) }}" alt="{{ $post->title }}" class="rounded-3 shadow-sm" style="width: 60px; height: 45px; object-fit: cover;">
                                    @else
                                        <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 45px; font-size: 11px;">
                                            <i class="fa fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark fs-14">{{ $post->title }}</div>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        @if($post->category)
                                            <span class="badge bg-light text-success border border-success-subtle px-2 py-1" style="font-size: 11px;">
                                                <i class="fa fa-folder-o me-1"></i>{{ $post->category->name }}
                                            </span>
                                        @else
                                            <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 11px;">আনক্যাটেগোরাইজড</span>
                                        @endif
                                        <a href="{{ route('blogs.show', $post->slug) }}" target="_blank" class="small text-muted text-decoration-none" title="লিংক দেখুন">
                                            <i class="fa fa-link me-1"></i>{{ Str::limit($post->slug, 25) }}
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <span class="small fw-semibold text-secondary"><i class="fa fa-user-circle me-1"></i>{{ $post->author_name }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info-subtle text-info px-2 py-1 rounded-pill" style="background-color: #e0f2fe; color: #0369a1;">
                                        <i class="fa fa-eye me-1"></i>{{ number_format($post->views_count) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($post->is_featured)
                                        <span class="badge bg-warning text-dark rounded-pill px-2 py-1"><i class="fa fa-star me-1"></i>ফিচার্ড</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.blogs.toggle', $post->id) }}" class="text-decoration-none" title="স্ট্যাটাস পরিবর্তন করুন">
                                        @if($post->status === 'published')
                                            <span class="badge bg-success rounded-pill px-2 py-1"><i class="fa fa-check-circle me-1"></i>পাবলিশড</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-2 py-1"><i class="fa fa-clock-o me-1"></i>ড্রাফট</span>
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <span class="small text-muted">
                                        <i class="fa fa-calendar-o me-1"></i>{{ $post->published_at ? $post->published_at->format('d M, Y') : '—' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('blogs.show', $post->slug) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-circle" title="ভিউ করুন">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.blogs.edit', $post->id) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="এডিট করুন">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.blogs.destroy', $post->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই ব্লগ পোস্টটি মুছে ফেলতে চান?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="মুছে ফেলুন">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fa fa-newspaper-o fa-3x mb-2 text-secondary d-block"></i>
                                    কোনো ব্লগ পোস্ট পাওয়া যায়নি। <br>
                                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-success btn-sm rounded-pill mt-2">
                                        <i class="fa fa-plus-circle me-1"></i>প্রথম পোস্ট তৈরি করুন
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($posts->hasPages())
                <div class="p-3 border-top d-flex justify-content-end">
                    {{ $posts->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
        </section>
    </div>
</div>
@endsection
