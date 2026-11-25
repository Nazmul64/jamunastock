@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-plus-circle text-success me-2"></i>নতুন বিনিয়োগ প্রকল্প তৈরি করুন</h4>
                    <p class="text-muted mb-0">নতুন এগ্রো বা প্রপার্টি প্রকল্পের লক্ষ্য ও বিনিয়োগ সীমা নির্ধারণ করুন</p>
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
                    <form action="{{ route('admin.investments.projects.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label font-weight-bold">প্রকল্পের নাম <span class="text-danger">*</span></label>
                                <input type="text" name="project_name" class="form-control" required placeholder="উদাঃ আধুনিক গ্রিনহাউস ক্যাপসিকাম চাষ প্রকল্প">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">বিনিয়োগ খাত <span class="text-danger">*</span></label>
                                <select name="sector_id" class="form-select" required>
                                    @foreach($sectors as $sec)
                                        <option value="{{ $sec->id }}">{{ $sec->sector_no }} - {{ $sec->name_bn }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">টার্গেট বাজেট (টাকা) <span class="text-danger">*</span></label>
                                <input type="number" name="target_budget" class="form-control" required placeholder="উদাঃ ৫০,০০,০০০">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">সর্বনিম্ন বিনিয়োগ (টাকা) <span class="text-danger">*</span></label>
                                <input type="number" name="min_investment" class="form-control" required placeholder="উদাঃ ১০,০০০">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">সর্বোচ্চ বিনিয়োগ (টাকা)</label>
                                <input type="number" name="max_investment" class="form-control" placeholder="০ বা খালি রাখলে অসীম">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">প্রকল্প মেয়াদ <span class="text-danger">*</span></label>
                                <input type="text" name="duration" class="form-control" required placeholder="উদাঃ ১২ মাস / ২ বছর">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">অগ্রগতি (%) <span class="text-danger">*</span></label>
                                <input type="number" name="progress_percentage" class="form-control" min="0" max="100" required value="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">স্ট্যাটাস <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="open">উন্মুক্ত (Open for Investment)</option>
                                    <option value="ongoing">চলমান (Ongoing)</option>
                                    <option value="completed">সম্পন্ন (Completed)</option>
                                    <option value="paused">স্থগিত (Paused)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">মাঠের অবস্থান (Location)</label>
                                <input type="text" name="field_location" class="form-control" placeholder="উদাঃ গাজীপুর এগ্রো পার্ক">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">ব্যানার ছবি</label>
                                <input type="file" name="banner_image" class="form-control" accept="image/*">
                            </div>

                            <div class="col-12">
                                <label class="form-label font-weight-bold">প্রকল্পের বিস্তারিত বিবরণ</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="প্রকল্পের উদ্দেশ্য ও কর্মপরিকল্পনা..."></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label font-weight-bold">প্রত্যাশিত লভ্যাংশ ও ঝুঁকি নীতি (ROI Policy)</label>
                                <textarea name="expected_roi_policy" class="form-control" rows="3" placeholder="মুনাফা বণ্টন এবং নিরীক্ষিত ব্যালেন্স শিট সংক্রান্ত নীতি..."></textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-success font-weight-bold px-4">প্রকল্প তৈরি করুন</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

