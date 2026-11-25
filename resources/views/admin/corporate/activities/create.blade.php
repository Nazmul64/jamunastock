@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-plus-circle text-success me-2"></i>নতুন প্রকল্প কার্যক্রম যুক্ত করুন</h4>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের মাঠপর্যায়ের প্রকল্প, অগ্রগতি ও বাজেট প্রদান করুন</p>
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
                    <form action="{{ route('admin.corporate.activities.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label font-weight-bold">প্রকল্পের শিরোনাম <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required placeholder="উদাঃ সমন্বিত ডেইরি ও অর্গানিক সার উৎপাদন প্রকল্প">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">ক্যাটাগরি <span class="text-danger">*</span></label>
                                <select name="category" class="form-select" required>
                                    <option value="ongoing">চলমান (Ongoing)</option>
                                    <option value="completed">সম্পন্ন (Completed)</option>
                                    <option value="planned">পরিকল্পনাধীন (Planned)</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">খাত / ধরন</label>
                                <input type="text" name="sector_type" class="form-control" placeholder="উদাঃ ডেইরি ও প্রাণিসম্পদ">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">প্রকল্পের অবস্থান (Location)</label>
                                <input type="text" name="location" class="form-control" placeholder="উদাঃ নরসিংদী, ঢাকা">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">মোট বাজেট (টাকা) <span class="text-danger">*</span></label>
                                <input type="number" name="budget" class="form-control" required placeholder="উদাঃ ২৫,০০,০০০">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">অগ্রগতি (%) <span class="text-danger">*</span></label>
                                <input type="number" name="progress_percentage" class="form-control" min="0" max="100" required value="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">ফিচার্ড ছবি</label>
                                <input type="file" name="featured_image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">স্ট্যাটাস <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="active">সক্রিয় (Active)</option>
                                    <option value="inactive">নিষ্ক্রিয় (Inactive)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">শুরুর তারিখ</label>
                                <input type="date" name="start_date" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">সমাপ্তির তারিখ (সম্ভাব্য)</label>
                                <input type="date" name="completion_date" class="form-control">
                            </div>

                            <div class="col-12">
                                <label class="form-label font-weight-bold">প্রকল্পের ভিডিও লিংক (ইউটিউব/ভিডিও ইউআরএল)</label>
                                <input type="text" name="video_url" class="form-control" placeholder="https://www.youtube.com/embed/...">
                            </div>

                            <div class="col-12">
                                <label class="form-label font-weight-bold">প্রকল্পের বিবরণ</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="প্রকল্পের উদ্দেশ্য, লক্ষ্য ও পটভূমি লিখুন..."></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label font-weight-bold">মাঠপর্যায়ের প্রতিবেদন (Field Report)</label>
                                <textarea name="field_report" class="form-control" rows="3" placeholder="মাঠের অগ্রগতি, উৎপাদন ফলাফল ও পর্যবেক্ষণ..."></textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-success font-weight-bold px-4">সংরক্ষণ করুন</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

