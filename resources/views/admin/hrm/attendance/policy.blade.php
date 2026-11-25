@extends('layouts.backend.app')

@section('title', 'উপস্থিতি পলিসি কনফিগারেশন')

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h3 class="font-weight-bold mb-1" style="color: #2e7d32;">
                        <i class="ti-settings mr-2"></i> আন্তর্জাতিক উপস্থিতি ও কাজের পলিসি (Attendance Policy)
                    </h3>
                    <p class="text-muted mb-0">গ্রেস পিরিয়ড, বিলম্ব নির্ধারণ, ওভারটাইম নিয়মাবলি, হাফ-ডে ও সাপ্তাহিক ছুটির নিয়মাবলি সেটআপ</p>
                </div>
                <div>
                    <a href="{{ route('admin.hrm.attendance.index') }}" class="btn btn-outline-success btn-sm rounded-pill shadow-sm">
                        <i class="ti-alarm-clock mr-1"></i> দৈনিক উপস্থিতি রেজিস্টার
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="ti-check-box mr-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 font-weight-bold text-dark">
                        <i class="ti-pencil-alt text-success mr-2"></i> সক্রিয় পলিসি সেটিংস
                    </h5>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('admin.hrm.attendance.policy.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-4">
                            <label class="font-weight-bold">পলিসির শিরোনাম (Policy Name) <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $policy->name ?? 'স্ট্যান্ডার্ড কর্পোরেট উপস্থিতি পলিসি' }}" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="font-weight-bold">ডিফল্ট অফিস শুরুর সময় (Start Time) <span class="text-danger">*</span></label>
                                <input type="time" name="default_start_time" class="form-control" value="{{ $policy->default_start_time ?? '09:00' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="font-weight-bold">ডিফল্ট অফিস সমাপ্তির সময় (End Time) <span class="text-danger">*</span></label>
                                <input type="time" name="default_end_time" class="form-control" value="{{ $policy->default_end_time ?? '18:00' }}" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="font-weight-bold">গ্রেস পিরিয়ড (মিনিট) <span class="text-danger">*</span></label>
                                <input type="number" name="grace_period_minutes" class="form-control" value="{{ $policy->grace_period_minutes ?? 10 }}" min="0" required>
                                <small class="text-muted">যেমন: ৯:১০ পর্যন্ত লেট ধরা হবে না</small>
                            </div>
                            <div class="col-md-4">
                                <label class="font-weight-bold">বিলম্ব থ্রেশহোল্ড (মিনিট) <span class="text-danger">*</span></label>
                                <input type="number" name="late_threshold_minutes" class="form-control" value="{{ $policy->late_threshold_minutes ?? 30 }}" min="0" required>
                                <small class="text-muted">কত মিনিট পর 'Late' স্ট্যাটাস হবে</small>
                            </div>
                            <div class="col-md-4">
                                <label class="font-weight-bold">আর্লি লিভ ছাড় (মিনিট) <span class="text-danger">*</span></label>
                                <input type="number" name="early_leave_minutes" class="form-control" value="{{ $policy->early_leave_minutes ?? 15 }}" min="0" required>
                                <small class="text-muted">ছুটির কত মিনিট আগে বের হওয়া যাবে</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="font-weight-bold">হাফ-ডে ন্যূনতম কর্মঘণ্টা <span class="text-danger">*</span></label>
                                <input type="number" step="0.5" name="half_day_hours" class="form-control" value="{{ $policy->half_day_hours ?? 4.0 }}" min="1" required>
                                <small class="text-muted">এর কম কাজ করলে 'অনুপস্থিত' হবে</small>
                            </div>
                            <div class="col-md-6">
                                <label class="font-weight-bold">ফুল-ডে সাধারণ কর্মঘণ্টা <span class="text-danger">*</span></label>
                                <input type="number" step="0.5" name="full_day_hours" class="form-control" value="{{ $policy->full_day_hours ?? 8.0 }}" min="4" required>
                                <small class="text-muted">দৈনিক স্ট্যান্ডার্ড কার্যকর কাজের সময়</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="font-weight-bold">ওভারটাইম ন্যূনতম মিনিট <span class="text-danger">*</span></label>
                                <input type="number" name="overtime_min_minutes" class="form-control" value="{{ $policy->overtime_min_minutes ?? 30 }}" min="0" required>
                                <small class="text-muted">অফিস সময়ের পর কত মিনিট কাজ করলে OT হবে</small>
                            </div>
                            <div class="col-md-6">
                                <label class="font-weight-bold">ওভারটাইম রেট গুণক (Multiplier) <span class="text-danger">*</span></label>
                                <input type="number" step="0.1" name="overtime_rate_multiplier" class="form-control" value="{{ $policy->overtime_rate_multiplier ?? 1.5 }}" min="1" required>
                                <small class="text-muted">যেমন: ১.৫x (ঘণ্টাপ্রতি বেসিকের দেড়গুণ)</small>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold mb-2">সাপ্তাহিক বন্ধের দিনসমূহ (Weekend Days)</label>
                            @php
                                $currentWeekends = is_array($policy->weekend_days) ? $policy->weekend_days : ['Friday'];
                                $weekDays = ['Friday' => 'শুক্রবার', 'Saturday' => 'শনিবার', 'Sunday' => 'রবিবার', 'Thursday' => 'বৃহস্পতিবার'];
                            @endphp
                            <div class="d-flex flex-wrap gap-3">
                                @foreach($weekDays as $key => $bn)
                                    <div class="form-check mr-4">
                                        <input class="form-check-input" type="checkbox" name="weekend_days[]" value="{{ $key }}" id="w_{{ $key }}" {{ in_array($key, $currentWeekends) ? 'checked' : '' }}>
                                        <label class="form-check-label font-weight-bold" for="w_{{ $key }}">
                                            {{ $bn }} ({{ $key }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="font-weight-bold">হাজিরা লক পিরিয়ড (দিন) <span class="text-danger">*</span></label>
                                <input type="number" name="lock_attendance_after_days" class="form-control" value="{{ $policy->lock_attendance_after_days ?? 7 }}" min="1" required>
                                <small class="text-muted">কত দিন আগের অতীত হাজিরা ম্যানুয়ালি এডিট করা নিষিদ্ধ</small>
                            </div>
                            <div class="col-md-6 d-flex align-items-center pt-3">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="missing_punch_penalty" value="1" id="missing_penalty" {{ ($policy->missing_punch_penalty ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label font-weight-bold" for="missing_penalty">
                                        চেক-আউট মিস হলে হাফ-ডে জরিমানা প্রয়োগ করুন
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success px-5 py-2 font-weight-bold rounded-pill shadow-sm">
                                <i class="ti-save mr-1"></i> পলিসি সংরক্ষণ করুন
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
