@extends('layouts.backend.app')

@section('title', 'কর্মী সেলফ-সার্ভিস পোর্টাল (ESS)')

@section('content')
<div class="content-wrapper">
    <!-- Header Greeting Card (Dribbble & NeuralHR Style) -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-lg text-white" style="background: linear-gradient(135deg, #1b5e20, #2e7d32, #43a047);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <img src="{{ $user->avatar ? asset('upload/user_images/'.$user->avatar) : asset('frontend/images/default-user.png') }}" 
                                     alt="{{ $user->name }}" 
                                     class="rounded-circle border border-white border-2 shadow" 
                                     style="width: 70px; height: 70px; object-fit: cover;"
                                     onerror="this.src='{{ asset('frontend/images/default-user.png') }}'">
                            </div>
                            <div>
                                <h3 class="font-weight-bold mb-1 text-white">
                                    স্বাগতম, {{ $user->name }}!
                                </h3>
                                <p class="mb-0 text-white-50">
                                    {{ optional($profile->designation)->designation_name ?: 'কর্মকর্তা' }} | {{ optional($profile->department)->department_name ?: 'সাধারণ শাখা' }} | আইডি: <strong>{{ $profile->employee_code ?: 'EMP-'.$user->id }}</strong>
                                </p>
                            </div>
                        </div>
                        <div class="text-md-right text-left">
                            <div class="font-monospace text-white font-weight-bold" style="font-size: 26px;" id="liveClock">
                                {{ date('h:i:s A') }}
                            </div>
                            <div class="text-white-50 small">
                                <i class="ti-calendar mr-1"></i> {{ date('l, d F, Y') }}
                            </div>
                        </div>
                    </div>
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

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="ti-alert mr-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Main Section: Live Punch & Monthly Stats -->
    <div class="row mb-4">
        <!-- Live Web Clock In/Out Card -->
        <div class="col-md-5 mb-4">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-dark">
                        <i class="ti-alarm-clock text-success mr-2"></i> ডিজিটাল হাজিরা (Live Web Clock)
                    </h5>
                    <span class="badge badge-light border text-muted">
                        {{ $profile->shift ? $profile->shift->shift_name : 'নিয়মিত শিফট' }}
                    </span>
                </div>
                <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                    <div class="mb-3">
                        <div class="rounded-circle mx-auto mb-3 shadow-sm d-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px; background: {{ $todayAttendance ? ($todayAttendance->check_out ? '#e0f2fe' : '#fef3c7') : '#f0fdf4' }};">
                            <i class="ti-time" style="font-size: 42px; color: {{ $todayAttendance ? ($todayAttendance->check_out ? '#0284c7' : '#d97706') : '#16a34a' }};"></i>
                        </div>

                        @if(!$todayAttendance)
                            <h5 class="font-weight-bold text-dark mb-1">আজকে এখনো পাঞ্চ করা হয়নি</h5>
                            <p class="text-muted small">কাজের সময় গণনা শুরু করতে নিচে "Web Punch IN" চাপুন</p>
                            <form action="{{ route('hrm.ess.web_punch') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg px-5 py-3 rounded-pill font-weight-bold shadow">
                                    <i class="ti-arrow-right mr-2"></i> Web Punch IN (চেক-ইন)
                                </button>
                            </form>
                        @elseif($todayAttendance && !$todayAttendance->check_out)
                            <h5 class="font-weight-bold text-dark mb-1">আপনি বর্তমানে কর্মরত আছেন</h5>
                            <p class="text-success small mb-2">
                                <i class="ti-check mr-1"></i> চেক-ইন সময়: <strong>{{ date('h:i A', strtotime($todayAttendance->check_in)) }}</strong>
                            </p>
                            <div class="p-2 bg-light rounded font-monospace font-weight-bold text-primary mb-3" id="elapsedTimer" style="font-size: 18px;">
                                চলমান কর্মঘণ্টা: গণনা হচ্ছে...
                            </div>
                            <form action="{{ route('hrm.ess.web_punch') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-lg px-5 py-3 rounded-pill font-weight-bold shadow">
                                    <i class="ti-arrow-left mr-2"></i> Web Punch OUT (চেক-আউট)
                                </button>
                            </form>
                        @else
                            <h5 class="font-weight-bold text-success mb-1">আজকের হাজিরা সম্পন্ন হয়েছে!</h5>
                            <p class="text-muted small mb-3">ইন: {{ date('h:i A', strtotime($todayAttendance->check_in)) }} | আউট: {{ date('h:i A', strtotime($todayAttendance->check_out)) }}</p>
                            <div class="alert alert-light border small text-dark mb-0">
                                <i class="ti-cup text-warning mr-1"></i> মোট কর্মঘণ্টা: <strong>{{ $todayAttendance->working_hours }} ঘণ্টা</strong>
                                @if($todayAttendance->overtime_minutes > 0)
                                    | ওভারটাইম: <strong>{{ round($todayAttendance->overtime_minutes / 60, 1) }} ঘণ্টা</strong>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Overview KPI Cards -->
        <div class="col-md-7 mb-4">
            <div class="row h-100">
                <div class="col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm rounded-lg h-100" style="background: #f0fdf4;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1 font-weight-bold">চলতি মাসে উপস্থিত</p>
                                    <h3 class="font-weight-bold text-success mb-0">{{ $presentDays }} দিন</h3>
                                    <small class="text-muted">{{ date('F Y') }}</small>
                                </div>
                                <div class="bg-white p-3 rounded-circle shadow-sm">
                                    <i class="ti-check text-success" style="font-size: 22px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm rounded-lg h-100" style="background: #fffbeb;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1 font-weight-bold">বিলম্ব উপস্থিতি (Late)</p>
                                    <h3 class="font-weight-bold text-warning mb-0">{{ $lateDays }} দিন</h3>
                                    <small class="text-muted">গ্রেস পিরিয়ড অতিক্রান্ত</small>
                                </div>
                                <div class="bg-white p-3 rounded-circle shadow-sm">
                                    <i class="ti-timer text-warning" style="font-size: 22px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm rounded-lg h-100" style="background: #f0f9ff;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1 font-weight-bold">মোট কার্যকর কাজের সময়</p>
                                    <h3 class="font-weight-bold text-primary mb-0">{{ round($totalWorkingHours, 1) }} ঘণ্টা</h3>
                                    <small class="text-muted">অন-ডিউটি ঘণ্টা</small>
                                </div>
                                <div class="bg-white p-3 rounded-circle shadow-sm">
                                    <i class="ti-stats-up text-primary" style="font-size: 22px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm rounded-lg h-100" style="background: #fdf2f8;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1 font-weight-bold">অর্জিত ওভারটাইম</p>
                                    <h3 class="font-weight-bold text-purple mb-0" style="color: #9333ea;">{{ round($totalOvertime / 60, 1) }} ঘণ্টা</h3>
                                    <small class="text-muted">বেতনের সাথে যুক্ত হবে</small>
                                </div>
                                <div class="bg-white p-3 rounded-circle shadow-sm">
                                    <i class="ti-bolt text-purple" style="font-size: 22px; color: #9333ea;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Action Buttons Row -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-lg">
                        <div class="card-body py-3 d-flex justify-content-around flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" data-toggle="modal" data-target="#leaveModal" data-bs-toggle="modal" data-bs-target="#leaveModal">
                                <i class="ti-calendar mr-1"></i> ছুটির আবেদন
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm rounded-pill px-3" data-toggle="modal" data-target="#correctionModal" data-bs-toggle="modal" data-bs-target="#correctionModal">
                                <i class="ti-pencil-alt mr-1"></i> হাজিরা সংশোধন আবেদন
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3" data-toggle="modal" data-target="#advanceModal" data-bs-toggle="modal" data-bs-target="#advanceModal">
                                <i class="ti-money mr-1"></i> অগ্রিম বেতন আবেদন
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Lists: My Leaves, Corrections & Announcements -->
    <div class="row">
        <!-- Leave Requests List -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="ti-calendar text-primary mr-2"></i> আমার সাম্প্রতিক ছুটির আবেদন
                    </h6>
                    <button type="button" class="btn btn-primary btn-xs py-1 px-2 rounded-pill" data-toggle="modal" data-target="#leaveModal" data-bs-toggle="modal" data-bs-target="#leaveModal">
                        + নতুন ছুটি
                    </button>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>ছুটির ধরন</th>
                                    <th>মেয়াদ</th>
                                    <th>দিন</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaveRequests as $lr)
                                <tr>
                                    <td>{{ optional($lr->leaveType)->type_name ?: 'ছুটি' }}</td>
                                    <td>{{ date('d M', strtotime($lr->start_date)) }} - {{ date('d M, Y', strtotime($lr->end_date)) }}</td>
                                    <td><span class="badge badge-light border">{{ $lr->days }} দিন</span></td>
                                    <td>
                                        @if($lr->status == 'approved')
                                            <span class="badge badge-success px-2 py-1">অনুমোদিত</span>
                                        @elseif($lr->status == 'rejected')
                                            <span class="badge badge-danger px-2 py-1">বাতিল</span>
                                        @else
                                            <span class="badge badge-warning px-2 py-1">অপেক্ষমাণ</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">কোনো ছুটির আবেদন পাওয়া যায়নি।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Corrections List -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="ti-check-box text-warning mr-2"></i> আমার হাজিরা সংশোধন আবেদন
                    </h6>
                    <button type="button" class="btn btn-warning btn-xs py-1 px-2 rounded-pill" data-toggle="modal" data-target="#correctionModal" data-bs-toggle="modal" data-bs-target="#correctionModal">
                        + আবেদন করুন
                    </button>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>তারিখ</th>
                                    <th>আবেদিত সময়</th>
                                    <th>কারণ</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($corrections as $cor)
                                <tr>
                                    <td>{{ date('d M, Y', strtotime($cor->date)) }}</td>
                                    <td>
                                        <small>{{ $cor->requested_check_in ? date('h:i A', strtotime($cor->requested_check_in)) : '-' }} - {{ $cor->requested_check_out ? date('h:i A', strtotime($cor->requested_check_out)) : '-' }}</small>
                                    </td>
                                    <td><small>{{ Str::limit($cor->reason, 20) }}</small></td>
                                    <td>
                                        @if($cor->status == 'approved')
                                            <span class="badge badge-success px-2 py-1">অনুমোদিত</span>
                                        @elseif($cor->status == 'rejected')
                                            <span class="badge badge-danger px-2 py-1">বাতিল</span>
                                        @else
                                            <span class="badge badge-warning px-2 py-1">অপেক্ষমাণ</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">কোনো সংশোধন আবেদন নেই।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Request Leave -->
<div class="modal fade" id="leaveModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="ti-calendar mr-1"></i> ছুটির আবেদন ফরম</h5>
                <button type="button" class="btn-close btn-close-white text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: 0; font-size: 20px;">&times;</button>
            </div>
            <form action="{{ route('hrm.ess.store_leave') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">ছুটির ধরন <span class="text-danger">*</span></label>
                        <select name="leave_type_id" class="form-control" required>
                            @foreach($leaveTypes as $lt)
                                <option value="{{ $lt->id }}">{{ $lt->type_name }} (সর্বোচ্চ {{ $lt->days_allowed }} দিন)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">শুরুর তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">সমাপ্তির তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">ছুটির কারণ <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="ছুটির বিস্তারিত কারণ উল্লেখ করুন..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary">আবেদন সাবমিট করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Attendance Correction -->
<div class="modal fade" id="correctionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold"><i class="ti-pencil-alt mr-1"></i> উপস্থিতি সংশোধন আবেদন</h5>
                <button type="button" class="btn-close text-dark" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: 0; font-size: 20px;">&times;</button>
            </div>
            <form action="{{ route('hrm.ess.store_correction') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">কোন তারিখের হাজিরা সংশোধন প্রয়োজন? <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control" max="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">সঠিক চেক-ইন সময়</label>
                            <input type="time" name="requested_check_in" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">সঠিক চেক-আউট সময়</label>
                            <input type="time" name="requested_check_out" class="form-control">
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">সংশোধনের যৌক্তিক কারণ <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="যেমন: বায়োমেট্রিক মেশিনে ফিঙ্গার স্ক্যানার সংযোগ বিচ্ছিন্ন ছিল..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-warning font-weight-bold">আবেদন দাখিল করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Advance Salary -->
<div class="modal fade" id="advanceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold"><i class="ti-money mr-1"></i> অগ্রিম বেতন আবেদন</h5>
                <button type="button" class="btn-close btn-close-white text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: 0; font-size: 20px;">&times;</button>
            </div>
            <form action="{{ route('hrm.ess.store_advance') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">আবেদিত টাকার পরিমাণ (BDT) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" placeholder="যেমন: 5000" min="500" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">অগ্রিম গ্রহণের কারণ <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="জরুরি চিকিৎসা, পারিবারিক প্রয়োজন ইত্যাদি..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success">আবেদন করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Live clock
setInterval(function() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12;
    minutes = minutes < 10 ? '0'+minutes : minutes;
    seconds = seconds < 10 ? '0'+seconds : seconds;
    var strTime = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
    var clockEl = document.getElementById('liveClock');
    if (clockEl) clockEl.innerText = strTime;
}, 1000);

@if($todayAttendance && $todayAttendance->check_in && !$todayAttendance->check_out)
// Elapsed worked timer
var checkInTimestamp = new Date("{{ date('Y-m-d') }}T{{ $todayAttendance->check_in }}").getTime();
setInterval(function() {
    var now = new Date().getTime();
    var diff = now - checkInTimestamp;
    if (diff > 0) {
        var h = Math.floor(diff / (1000 * 60 * 60));
        var m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        var s = Math.floor((diff % (1000 * 60)) / 1000);
        h = h < 10 ? '0' + h : h;
        m = m < 10 ? '0' + m : m;
        s = s < 10 ? '0' + s : s;
        var timerEl = document.getElementById('elapsedTimer');
        if (timerEl) timerEl.innerText = "চলমান কর্মঘণ্টা: " + h + "h " + m + "m " + s + "s";
    }
}, 1000);
@endif
</script>
@endsection
