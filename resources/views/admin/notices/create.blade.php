@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-plus-circle text-success me-2"></i>নতুন নোটিশ প্রকাশ করুন</h4>
                    <p class="text-muted mb-0">সাধারণ গ্রাহক ও অংশীদারদের জন্য সার্কুলার প্রদান করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.notices.index') }}" class="btn btn-secondary btn-sm">
                        &larr; তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="box">
                <div class="box-body">
                    <form action="{{ route('admin.notices.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label font-weight-bold">নোটিশের শিরোনাম <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required placeholder="উদাঃ ২০২৬ সালের ত্রৈমাসিক লভ্যাংশ বণ্টন সংক্রান্ত সার্কুলার">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">নোটিশের ধরন <span class="text-danger">*</span></label>
                                <select name="notice_type" class="form-select" required>
                                    <option value="announcement">সাধারণ ঘোষণা (Announcement)</option>
                                    <option value="urgent">জরুরি সতর্কতা (Urgent Alert)</option>
                                    <option value="project_update">প্রকল্প আপডেট (Project Update)</option>
                                    <option value="shareholder">শেয়ারহোল্ডার সার্কুলার (Shareholder)</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">প্রকাশের তারিখ <span class="text-danger">*</span></label>
                                <input type="date" name="publish_date" class="form-control" required value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">মেয়াদ শেষ (Expiry Date)</label>
                                <input type="date" name="expiry_date" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">সংযুক্তি ফাইল (PDF/Doc/Image)</label>
                                <input type="file" name="attachment_file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                            </div>

                            <div class="col-12">
                                <label class="form-label font-weight-bold">নোটিশের বিস্তারিত বিষয়বস্তু <span class="text-danger">*</span></label>
                                <textarea name="content" class="form-control" rows="6" required placeholder="সম্পূর্ণ নোটিশের বিবরণ লিখুন..."></textarea>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_pinned" id="isPinnedCheck" value="1">
                                    <label class="form-check-label font-weight-bold text-danger" for="isPinnedCheck">
                                        <i class="fa fa-thumbtack me-1"></i>হোমপেইজ ও বোর্ডে শীর্ষে পিন করে রাখুন
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCheck" checked value="1">
                                    <label class="form-check-label font-weight-bold" for="isActiveCheck">
                                        তাৎক্ষণিক ওয়েবসাইটে প্রকাশ করুন
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-success font-weight-bold px-4">নোটিশ প্রকাশ করুন</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

