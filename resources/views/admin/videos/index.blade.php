@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-youtube-play text-danger me-2"></i> আমাদের ভিডিও সেকশন (Video Section)
                    </h3>
                    <p class="text-muted mb-0">ইউটিউব ভিডিও লিংক শেয়ার ও পরিচালনা (কোনো টাইটেল বাধ্যতামূলক নয়, শুধু লিংক দিন)</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('videos.index') }}" target="_blank" class="btn btn-outline-danger rounded-pill px-3 shadow-xs">
                        <i class="fa fa-external-link me-1"></i> ওয়েবসাইটে দেখুন (View on Website)
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-xs mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-xs mb-4" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Top Stats -->
            <div class="row g-3 mb-4">
                <div class="col-xl-4 col-md-4 col-12">
                    <div class="box border-0 shadow-xs rounded-4 bg-white mb-0">
                        <div class="box-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted fw-bold d-block text-uppercase">মোট ভিডিও</small>
                                <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalVideos) }}</h3>
                            </div>
                            <div class="bg-danger-light text-danger rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                                <i class="fa fa-video-camera fs-20"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-4 col-12">
                    <div class="box border-0 shadow-xs rounded-4 bg-white mb-0">
                        <div class="box-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted fw-bold d-block text-uppercase">প্রকাশিত / সক্রিয়</small>
                                <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($activeVideos) }}</h3>
                            </div>
                            <div class="bg-success-light text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                                <i class="fa fa-check-circle fs-20"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-4 col-12">
                    <div class="box border-0 shadow-xs rounded-4 bg-white mb-0">
                        <div class="box-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted fw-bold d-block text-uppercase">লুকানো / নিষ্ক্রিয়</small>
                                <h3 class="fw-bold text-warning mb-0 mt-1">{{ number_format($inactiveVideos) }}</h3>
                            </div>
                            <div class="bg-warning-light text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                                <i class="fa fa-eye-slash fs-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Video Box -->
            <div class="box border-0 shadow-xs rounded-4 mb-4">
                <div class="box-header py-3 px-4 bg-light border-bottom">
                    <h5 class="box-title fw-bold text-dark mb-0">
                        <i class="fa fa-plus-circle text-danger me-2"></i> নতুন ইউটিউব ভিডিও লিংক যুক্ত করুন
                    </h5>
                </div>
                <div class="box-body p-4">
                    <form action="{{ route('admin.videos.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-lg-8 col-12">
                                <label class="form-label fw-bold text-dark">
                                    ইউটিউব ভিডিও লিংক (YouTube URL) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-danger text-white border-danger">
                                        <i class="fa fa-youtube-play"></i>
                                    </span>
                                    <input type="text" name="video_url" class="form-control form-control-lg @error('video_url') is-invalid @enderror" 
                                           placeholder="যেমন: https://www.youtube.com/watch?v=dQw4w9WgXcQ অথবা https://youtu.be/dQw4w9WgXcQ" required>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fa fa-info-circle me-1"></i> আপনি সাধারণ ভিডিও, Shorts, কিংবা লাইভ যেকোনো ইউটিউব লিংক এখানে পেস্ট করতে পারেন। কোনো টাইটেল বাধ্যতামূলক নয়।
                                </small>
                                @error('video_url')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-4 col-12">
                                <label class="form-label fw-bold text-dark">
                                    শিরোনাম / ক্যাপশন (ঐচ্ছিক)
                                </label>
                                <input type="text" name="title" class="form-control form-control-lg" placeholder="ভিডিওর শিরোনাম (যদি দিতে চান)">
                                <small class="text-muted mt-1 d-block">টাইটেল না দিলে এটি খালি থাকবে।</small>
                            </div>

                            <div class="col-12 text-end pt-2">
                                <button type="submit" class="btn btn-danger btn-lg rounded-pill px-5 fw-bold shadow-sm">
                                    <i class="fa fa-upload me-1"></i> ভিডিও লিংক সেভ করুন
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Videos Grid (3 Columns: 4, 4, 4) -->
            <div class="box border-0 shadow-xs rounded-4">
                <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="box-title fw-bold text-dark mb-0">সকল সংরক্ষিত ভিডিও তালিকা ({{ $videos->total() }} টি)</h5>
                    <form method="GET" class="d-flex align-items-center gap-2 m-0">
                        <select name="status" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                            <option value="">সকল স্ট্যাটাস</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>শুধু সক্রিয়</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>শুধু নিষ্ক্রিয়</option>
                        </select>
                    </form>
                </div>

                <div class="box-body p-4">
                    <div class="row g-4">
                        @forelse($videos as $video)
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="card h-100 border rounded-4 overflow-hidden shadow-xs">
                                    <!-- 16:9 Video Player -->
                                    <div class="ratio ratio-16x9 bg-dark">
                                        <iframe src="{{ $video->youtube_embed_url }}" title="{{ $video->title ?: 'YouTube Video' }}" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                                allowfullscreen loading="lazy"></iframe>
                                    </div>
                                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span class="badge {{ $video->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                                                    {{ $video->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                                <small class="text-muted">
                                                    <i class="fa fa-calendar me-1"></i> {{ $video->created_at->format('d M, Y') }}
                                                </small>
                                            </div>
                                            @if($video->title)
                                                <h6 class="fw-bold text-dark mb-2">{{ $video->title }}</h6>
                                            @endif
                                            <div class="small text-truncate text-muted mb-2" title="{{ $video->video_url }}">
                                                <i class="fa fa-link me-1 text-danger"></i> {{ $video->video_url }}
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-2">
                                            <a href="{{ route('admin.videos.toggle', $video->id) }}" 
                                               class="btn btn-sm {{ $video->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} rounded-pill px-3">
                                                <i class="fa {{ $video->is_active ? 'fa-eye-slash' : 'fa-eye' }} me-1"></i>
                                                {{ $video->is_active ? 'লুকান' : 'প্রকাশ করুন' }}
                                            </a>

                                            <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" 
                                                  onsubmit="return confirm('আপনি কি নিশ্চিত যে এই ভিডিওটি মুছে ফেলতে চান?');" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="মুছে ফেলুন">
                                                    <i class="fa fa-trash me-1"></i> ডিলিট
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <div class="py-4">
                                    <i class="fa fa-youtube-play text-muted mb-3" style="font-size: 64px; opacity: 0.3;"></i>
                                    <h5 class="fw-bold text-dark">কোনো ভিডিও যুক্ত করা হয়নি</h5>
                                    <p class="text-muted mb-0">উপরের ফর্ম থেকে ইউটিউব ভিডিও লিংক পেস্ট করে সংরক্ষণ করুন।</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if($videos->hasPages())
                        <div class="d-flex justify-content-center mt-4 pt-3 border-top">
                            {{ $videos->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
