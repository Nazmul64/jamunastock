@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-user-edit text-success me-2"></i>সদস্যের তথ্য সম্পাদনা</h4>
                    <p class="text-muted mb-0">{{ $member->name }} এর তথ্য আপডেট করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.corporate.board.index') }}" class="btn btn-secondary btn-sm">
                        &larr; তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="box">
                <div class="box-body">
                    <form action="{{ route('admin.corporate.board.update', $member->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">নাম (ইংরেজি) <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required value="{{ $member->name }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">নাম (বাংলা)</label>
                                <input type="text" name="name_bn" class="form-control" value="{{ $member->name_bn }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">পদবী (ইংরেজি) <span class="text-danger">*</span></label>
                                <input type="text" name="designation" class="form-control" required value="{{ $member->designation }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">পদবী (বাংলা)</label>
                                <input type="text" name="designation_bn" class="form-control" value="{{ $member->designation_bn }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">প্রশাসনিক ভূমিকা (Role) <span class="text-danger">*</span></label>
                                <select name="role_type" class="form-select" required>
                                    <option value="chairman" {{ $member->role_type == 'chairman' ? 'selected' : '' }}>চেয়ারম্যান (Chairman)</option>
                                    <option value="md" {{ $member->role_type == 'md' ? 'selected' : '' }}>ম্যানেজিং ডিরেক্টর (MD)</option>
                                    <option value="ceo" {{ $member->role_type == 'ceo' ? 'selected' : '' }}>চিফ এক্সিকিউটিভ অফিসার (CEO)</option>
                                    <option value="marketing_head" {{ $member->role_type == 'marketing_head' ? 'selected' : '' }}>হেড অব মার্কেটিং (Marketing Head)</option>
                                    <option value="finance_head" {{ $member->role_type == 'finance_head' ? 'selected' : '' }}>হেড অব ফাইন্যান্স (Finance Head)</option>
                                    <option value="director" {{ $member->role_type == 'director' ? 'selected' : '' }}>পরিচালক (Director)</option>
                                    <option value="executive" {{ $member->role_type == 'executive' ? 'selected' : '' }}>কর্মকর্তা (Executive)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">ক্রম নম্বর (Order)</label>
                                <input type="number" name="order_num" class="form-control" value="{{ $member->order_num }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">প্রোফাইল ছবি পরিবর্তন</label>
                                <input type="file" name="photo" class="form-control" accept="image/*">
                                @if($member->photo)
                                    <div class="mt-2">
                                        <img src="{{ asset($member->photo) }}" style="width: 50px; height: 50px;" class="rounded-circle">
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">মোবাইল নম্বর</label>
                                <input type="text" name="phone" class="form-control" value="{{ $member->phone }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">ইমেইল ঠিকানা</label>
                                <input type="email" name="email" class="form-control" value="{{ $member->email }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label font-weight-bold">সংক্ষিপ্ত পরিচিতি ও ভূমিকা (Bio)</label>
                                <textarea name="bio" class="form-control" rows="3">{{ $member->bio }}</textarea>
                            </div>

                            <!-- Leadership Message Section -->
                            <div class="col-12 pt-3 border-top">
                                <h5 class="text-success font-weight-bold"><i class="fa fa-comment-alt me-1"></i>নেতৃবৃন্দের বাণী</h5>
                            </div>
                            <div class="col-12">
                                <label class="form-label font-weight-bold">বাণীর শিরোনাম (Speech Title)</label>
                                <input type="text" name="speech_title" class="form-control" value="{{ $member->speech_title }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label font-weight-bold">সম্পূর্ণ বাণী / বার্তা (Speech Message)</label>
                                <textarea name="speech_message" class="form-control" rows="4">{{ $member->speech_message }}</textarea>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCheck" {{ $member->is_active ? 'checked' : '' }} value="1">
                                    <label class="form-check-label font-weight-bold" for="isActiveCheck">
                                        ওয়েবসাইটে সক্রিয় রাখুন
                                    </label>
                                </div>
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

