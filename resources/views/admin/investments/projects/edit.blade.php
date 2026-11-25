@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-edit text-success me-2"></i>বিনিয়োগ প্রকল্প সম্পাদনা</h4>
                    <p class="text-muted mb-0">{{ $project->project_name }} এর তথ্য ও অগ্রগতি আপডেট করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.investments.projects.index') }}" class="btn btn-secondary btn-sm">
                        &larr; তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="box">
                <div class="box-body">
                    <form action="{{ route('admin.investments.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label font-weight-bold">প্রকল্পের নাম <span class="text-danger">*</span></label>
                                <input type="text" name="project_name" class="form-control" required value="{{ $project->project_name }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">বিনিয়োগ খাত <span class="text-danger">*</span></label>
                                <select name="sector_id" class="form-select" required>
                                    @foreach($sectors as $sec)
                                        <option value="{{ $sec->id }}" {{ $project->sector_id == $sec->id ? 'selected' : '' }}>{{ $sec->sector_no }} - {{ $sec->name_bn }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">টার্গেট বাজেট (টাকা) <span class="text-danger">*</span></label>
                                <input type="number" name="target_budget" class="form-control" required value="{{ $project->target_budget }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">সর্বনিম্ন বিনিয়োগ (টাকা) <span class="text-danger">*</span></label>
                                <input type="number" name="min_investment" class="form-control" required value="{{ $project->min_investment }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">সর্বোচ্চ বিনিয়োগ (টাকা)</label>
                                <input type="number" name="max_investment" class="form-control" value="{{ $project->max_investment }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">প্রকল্প মেয়াদ <span class="text-danger">*</span></label>
                                <input type="text" name="duration" class="form-control" required value="{{ $project->duration }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">অগ্রগতি (%) <span class="text-danger">*</span></label>
                                <input type="number" name="progress_percentage" class="form-control" min="0" max="100" required value="{{ $project->progress_percentage }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">স্ট্যাটাস <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="open" {{ $project->status == 'open' ? 'selected' : '' }}>উন্মুক্ত (Open for Investment)</option>
                                    <option value="ongoing" {{ $project->status == 'ongoing' ? 'selected' : '' }}>চলমান (Ongoing)</option>
                                    <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>সম্পন্ন (Completed)</option>
                                    <option value="paused" {{ $project->status == 'paused' ? 'selected' : '' }}>স্থগিত (Paused)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">মাঠের অবস্থান (Location)</label>
                                <input type="text" name="field_location" class="form-control" value="{{ $project->field_location }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">ব্যানার ছবি পরিবর্তন</label>
                                <input type="file" name="banner_image" class="form-control" accept="image/*">
                                @if($project->banner_image)
                                    <div class="mt-2">
                                        <img src="{{ asset($project->banner_image) }}" style="max-height: 50px;" class="rounded border">
                                    </div>
                                @endif
                            </div>

                            <div class="col-12">
                                <label class="form-label font-weight-bold">প্রকল্পের বিস্তারিত বিবরণ</label>
                                <textarea name="description" class="form-control" rows="3">{{ $project->description }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label font-weight-bold">প্রত্যাশিত লভ্যাংশ ও ঝুঁকি নীতি (ROI Policy)</label>
                                <textarea name="expected_roi_policy" class="form-control" rows="3">{{ $project->expected_roi_policy }}</textarea>
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

