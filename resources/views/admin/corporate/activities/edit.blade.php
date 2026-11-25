@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-edit text-success me-2"></i>প্রকল্প কার্যক্রম সম্পাদনা</h4>
                    <p class="text-muted mb-0">{{ $activity->title }} এর তথ্য ও অগ্রগতি আপডেট করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.corporate.activities.index') }}" class="btn btn-secondary btn-sm">
                        &larr; তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="box">
                <div class="box-body">
                    <form action="{{ route('admin.corporate.activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label font-weight-bold">প্রকল্পের শিরোনাম <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required value="{{ $activity->title }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">ক্যাটাগরি <span class="text-danger">*</span></label>
                                <select name="category" class="form-select" required>
                                    <option value="ongoing" {{ $activity->category == 'ongoing' ? 'selected' : '' }}>চলমান (Ongoing)</option>
                                    <option value="completed" {{ $activity->category == 'completed' ? 'selected' : '' }}>সম্পন্ন (Completed)</option>
                                    <option value="planned" {{ $activity->category == 'planned' ? 'selected' : '' }}>পরিকল্পনাধীন (Planned)</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">খাত / ধরন</label>
                                <input type="text" name="sector_type" class="form-control" value="{{ $activity->sector_type }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">প্রকল্পের অবস্থান (Location)</label>
                                <input type="text" name="location" class="form-control" value="{{ $activity->location }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">মোট বাজেট (টাকা) <span class="text-danger">*</span></label>
                                <input type="number" name="budget" class="form-control" required value="{{ $activity->budget }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">অগ্রগতি (%) <span class="text-danger">*</span></label>
                                <input type="number" name="progress_percentage" class="form-control" min="0" max="100" required value="{{ $activity->progress_percentage }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">ফিচার্ড ছবি পরিবর্তন</label>
                                <input type="file" name="featured_image" class="form-control" accept="image/*">
                                @if($activity->featured_image)
                                    <div class="mt-2">
                                        <img src="{{ asset($activity->featured_image) }}" style="max-height: 50px;" class="rounded border">
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">স্ট্যাটাস <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="active" {{ $activity->status == 'active' ? 'selected' : '' }}>সক্রিয় (Active)</option>
                                    <option value="inactive" {{ $activity->status == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয় (Inactive)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">শুরুর তারিখ</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $activity->start_date ? $activity->start_date->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">সমাপ্তির তারিখ</label>
                                <input type="date" name="completion_date" class="form-control" value="{{ $activity->completion_date ? $activity->completion_date->format('Y-m-d') : '' }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label font-weight-bold">প্রকল্পের ভিডিও লিংক</label>
                                <input type="text" name="video_url" class="form-control" value="{{ $activity->video_url }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label font-weight-bold">প্রকল্পের বিবরণ</label>
                                <textarea name="description" class="form-control" rows="3">{{ $activity->description }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label font-weight-bold">মাঠপর্যায়ের প্রতিবেদন (Field Report)</label>
                                <textarea name="field_report" class="form-control" rows="3">{{ $activity->field_report }}</textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-success font-weight-bold px-4">আপডেট করুন</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

