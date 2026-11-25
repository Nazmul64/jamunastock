@extends('layouts.backend.app')

@section('title', 'HRM & Biometric ড্যাশবোর্ড')

@section('content')
<div class="content-wrapper">
    <!-- Top Executive Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h3 class="font-weight-bold mb-1" style="color: #2e7d32;">
                        <i class="ti-dashboard mr-2"></i> এন্টারপ্রাইজ HRM & বায়োমেট্রিক এটেনডেন্স ড্যাশবোর্ড
                    </h3>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের কর্মী জীবনচক্র (H2R), রিয়েল-টাইম ফিঙ্গারপ্রিন্ট হাজিরা, পে-রোল ও কর্মক্ষমতা মেট্রিক্স</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.hrm.biometric.events') }}" class="btn btn-outline-danger btn-sm rounded-pill shadow-sm">
                        <i class="ti-pulse mr-1"></i> লাইভ পাঞ্চ স্ট্রিম
                    </a>
                    <a href="{{ route('hrm.ess.dashboard') }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill shadow-sm">
                        <i class="ti-id-badge mr-1"></i> ESS পোর্টাল প্রিভিউ
                    </a>
                    <a href="{{ route('admin.hrm.employees.index') }}" class="btn btn-success btn-sm rounded-pill shadow-sm">
                        <i class="ti-plus mr-1"></i> নতুন কর্মী যোগ করুন
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 1: Executive KPI Summary Cards (Matching Dribbble & Reference Dashboards) -->
    <div class="row mb-4">
        <!-- Total Employees -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-lg" style="background: linear-gradient(135deg, #1b5e20, #2e7d32); color: #fff;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-white-50 small font-weight-bold">মোট কর্মকর্তা ও কর্মচারী</p>
                            <h2 class="mb-0 font-weight-bold text-white">{{ number_format($totalEmployees) }}</h2>
                            <small class="text-white-50">সক্রিয়: {{ $activeEmployees }} | শিক্ষানবিস: {{ $probationEmployees }}</small>
                        </div>
                        <div class="bg-white rounded-circle p-3 shadow-sm" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti-user text-success" style="font-size: 22px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today Present -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-lg" style="background: linear-gradient(135deg, #0277bd, #0288d1); color: #fff;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-white-50 small font-weight-bold">আজকের উপস্থিতি (Present)</p>
                            <h2 class="mb-0 font-weight-bold text-white">
                                {{ number_format($todayPresent) }}
                                <span class="badge badge-light text-primary font-weight-normal ml-1" style="font-size: 12px;">{{ $attendanceRate }}%</span>
                            </h2>
                            <small class="text-white-50">সময়মতো: {{ max(0, $todayPresent - $todayLate) }} | লেট পাঞ্চ: {{ $todayLate }}</small>
                        </div>
                        <div class="bg-white rounded-circle p-3 shadow-sm" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti-check-box text-primary" style="font-size: 22px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today Absent & Leave -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-lg" style="background: linear-gradient(135deg, #c62828, #d32f2f); color: #fff;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-white-50 small font-weight-bold">আজকে অনুপস্থিত ও ছুটি</p>
                            <h2 class="mb-0 font-weight-bold text-white">
                                {{ number_format($todayAbsent) }}
                                <span class="badge badge-light text-danger font-weight-normal ml-1" style="font-size: 12px;">{{ $absenteeismRate }}%</span>
                            </h2>
                            <small class="text-white-50">অননুমোদিত অনুপস্থিত: {{ max(0, $todayAbsent - $todayOnLeave) }} | ছুটিতে: {{ $todayOnLeave }}</small>
                        </div>
                        <div class="bg-white rounded-circle p-3 shadow-sm" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti-close text-danger" style="font-size: 22px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biometric Devices Status -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-lg" style="background: linear-gradient(135deg, #4527a0, #5e35b1); color: #fff;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-white-50 small font-weight-bold">বায়োমেট্রিক টার্মিনাল</p>
                            <h2 class="mb-0 font-weight-bold text-white">
                                {{ $onlineDevices }}/{{ $totalDevices }}
                                <span class="badge badge-light text-success font-weight-normal ml-1" style="font-size: 11px;">
                                    <span class="spinner-grow spinner-grow-sm text-success" style="width: 8px; height: 8px;"></span> Live
                                </span>
                            </h2>
                            <small class="text-white-50">ওভারটাইম: {{ $todayOvertimeHours }}h | কার্যকর সময়: {{ $todayWorkingHours }}h</small>
                        </div>
                        <div class="bg-white rounded-circle p-3 shadow-sm" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti-server text-purple" style="font-size: 22px; color: #5e35b1;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Attendance Rate Gauges & Department Distribution (Matching Image 4 & 5) -->
    <div class="row mb-4">
        <!-- Attendance & Absenteeism Gauge Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="ti-pie-chart text-success mr-2"></i> উপস্থিতির হার ও পার্সেন্টেজ
                    </h6>
                    <span class="badge badge-light border">{{ date('d M, Y') }}</span>
                </div>
                <div class="card-body pt-0 text-center d-flex flex-column justify-content-around">
                    <div class="row">
                        <div class="col-6 text-center">
                            <div class="p-3 rounded-lg mb-2" style="background: #e8f5e9;">
                                <h3 class="font-weight-bold text-success mb-1">{{ $attendanceRate }}%</h3>
                                <small class="text-muted font-weight-bold">উপস্থিতির হার (Attendance)</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $attendanceRate }}%;"></div>
                            </div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="p-3 rounded-lg mb-2" style="background: #ffebee;">
                                <h3 class="font-weight-bold text-danger mb-1">{{ $absenteeismRate }}%</h3>
                                <small class="text-muted font-weight-bold">অনুপস্থিতির হার (Absenteeism)</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $absenteeismRate }}%;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded text-left">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted"><i class="ti-timer text-warning mr-1"></i> লেট পাঞ্চ (বিলম্বিত আগমন):</span>
                            <strong class="text-dark">{{ $todayLate }} জন</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted"><i class="ti-time text-primary mr-1"></i> কার্যকর কাজের সময়:</span>
                            <strong class="text-dark">{{ $todayWorkingHours }} ঘণ্টা</strong>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted"><i class="ti-bolt text-danger mr-1"></i> আজকের অর্জিত ওভারটাইম:</span>
                            <strong class="text-dark">{{ $todayOvertimeHours }} ঘণ্টা</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Breakdown Chart -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="ti-layers text-primary mr-2"></i> বিভাগভিত্তিক কর্মী বণ্টন (Department Share)
                    </h6>
                    <a href="{{ route('admin.hrm.departments.index') }}" class="small text-primary">সকল দেখুন</a>
                </div>
                <div class="card-body pt-0 text-center">
                    <div style="height: 180px; position: relative;">
                        <canvas id="deptPieChart"></canvas>
                    </div>
                    <div class="mt-3 text-left small">
                        <div class="row">
                            @foreach($departments->take(4) as $idx => $dept)
                            <div class="col-6 mb-2">
                                <span class="d-inline-block rounded-circle mr-1" style="width: 10px; height: 10px; background-color: {{ ['#2e7d32', '#0288d1', '#f57c00', '#7b1fa2'][$idx % 4] }};"></span>
                                <span class="text-muted">{{ $dept->department_name }}:</span>
                                <strong>{{ $dept->employees_count }}</strong>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Quick Action Navigation Grid -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="ti-layout-grid2 text-secondary mr-2"></i> দ্রুত নেভিগেশন ও কনফিগারেশন
                    </h6>
                </div>
                <div class="card-body pt-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.hrm.biometric.devices') }}" class="list-group-item list-group-item-action border-0 px-0 py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <i class="ti-server text-success mr-2 font-weight-bold"></i>
                                <span class="font-weight-bold text-dark">বায়োমেট্রিক টার্মিনাল হাব</span>
                                <br><small class="text-muted">TCP/IP, USB ও ক্লাউড পুশ ডিভাইস</small>
                            </div>
                            <span class="badge badge-success px-2 py-1">{{ $totalDevices }} টি</span>
                        </a>
                        <a href="{{ route('admin.hrm.biometric.enrollment') }}" class="list-group-item list-group-item-action border-0 px-0 py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <i class="ti-hand-point-up text-primary mr-2 font-weight-bold"></i>
                                <span class="font-weight-bold text-dark">ফিঙ্গারপ্রিন্ট এনরোলমেন্ট ও ম্যাপিং</span>
                                <br><small class="text-muted">ডিভাইস UID ও কর্মী প্রোফাইল লিংক</small>
                            </div>
                            <i class="ti-angle-right text-muted"></i>
                        </a>
                        <a href="{{ route('admin.hrm.attendance.monthly') }}" class="list-group-item list-group-item-action border-0 px-0 py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <i class="ti-calendar text-info mr-2 font-weight-bold"></i>
                                <span class="font-weight-bold text-dark">মাসিক উপস্থিতি রেজিস্টার</span>
                                <br><small class="text-muted">দিনভিত্তিক ম্যাট্রিক্স ও শিট ভিউ</small>
                            </div>
                            <i class="ti-angle-right text-muted"></i>
                        </a>
                        <a href="{{ route('admin.hrm.attendance.corrections') }}" class="list-group-item list-group-item-action border-0 px-0 py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <i class="ti-check-box text-warning mr-2 font-weight-bold"></i>
                                <span class="font-weight-bold text-dark">হাজিরা সংশোধন আবেদন</span>
                                <br><small class="text-muted">ভুল পাঞ্চ ও মেশিন ত্রুটি সমাধান</small>
                            </div>
                            @if($pendingCorrections > 0)
                                <span class="badge badge-warning px-2 py-1">{{ $pendingCorrections }} পেন্ডিং</span>
                            @else
                                <i class="ti-angle-right text-muted"></i>
                            @endif
                        </a>
                        <a href="{{ route('admin.hrm.attendance.policy') }}" class="list-group-item list-group-item-action border-0 px-0 py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <i class="ti-settings text-secondary mr-2 font-weight-bold"></i>
                                <span class="font-weight-bold text-dark">আন্তর্জাতিক উপস্থিতি পলিসি</span>
                                <br><small class="text-muted">গ্রেস টাইম, ওভারটাইম ও রুলস সেটআপ</small>
                            </div>
                            <i class="ti-angle-right text-muted"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Live Feed Table & Announcements -->
    <div class="row">
        <!-- Today Attendance Live Feed -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="ti-alarm-clock text-success mr-2"></i> আজকের উপস্থিতি লাইভ ফিড (Today's Real-time Check-ins)
                    </h6>
                    <a href="{{ route('admin.hrm.attendance.index') }}" class="btn btn-outline-success btn-xs py-1 px-3 rounded-pill">
                        সকল হাজিরা দেখুন
                    </a>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>কর্মী</th>
                                    <th>চেক-ইন</th>
                                    <th>চেক-আউট</th>
                                    <th>কাজের সময়</th>
                                    <th>উৎস</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAttendances as $att)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ optional($att->user)->name ?: 'User' }}</div>
                                        <small class="text-muted">{{ optional(optional($att->user)->hrmProfile)->employee_code ?: 'N/A' }} | {{ optional(optional(optional($att->user)->hrmProfile)->department)->department_name ?: 'General' }}</small>
                                    </td>
                                    <td>
                                        @if($att->check_in)
                                            <span class="badge badge-light border text-success font-monospace font-weight-bold">
                                                <i class="ti-arrow-right mr-1"></i> {{ date('h:i A', strtotime($att->check_in)) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($att->check_out)
                                            <span class="badge badge-light border text-primary font-monospace font-weight-bold">
                                                <i class="ti-arrow-left mr-1"></i> {{ date('h:i A', strtotime($att->check_out)) }}
                                            </span>
                                        @else
                                            <span class="badge badge-light text-muted">চলমান</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $att->working_hours }}h</strong>
                                        @if($att->overtime_minutes > 0)
                                            <small class="text-purple ml-1 font-weight-bold">(+{{ round($att->overtime_minutes/60, 1) }}h OT)</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            @if($att->source == 'biometric')
                                                <i class="ti-server text-success mr-1"></i> {{ optional($att->device)->device_name ?: 'Device' }}
                                            @elseif($att->source == 'web_punch')
                                                <i class="ti-desktop text-info mr-1"></i> Web ESS
                                            @else
                                                <i class="ti-pencil-alt text-warning mr-1"></i> Manual
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if($att->status == 'present')
                                            <span class="badge badge-success px-2 py-1">উপস্থিত</span>
                                        @elseif($att->status == 'late')
                                            <span class="badge badge-warning px-2 py-1">দেরি (Late)</span>
                                        @elseif($att->status == 'half_day')
                                            <span class="badge badge-info px-2 py-1">হাফ-ডে</span>
                                        @else
                                            <span class="badge badge-danger px-2 py-1">অনুপস্থিত</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        আজকে এখনো কোনো উপস্থিতি রেকর্ড পাওয়া যায়নি।
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Raw Biometric Punch Events -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="ti-pulse text-danger mr-2"></i> কাঁচা পাঞ্চ স্ট্রিম (Raw Logs)
                    </h6>
                    <a href="{{ route('admin.hrm.biometric.events') }}" class="small text-danger">সকল স্ট্রিম</a>
                </div>
                <div class="card-body pt-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentEvents as $rev)
                        <li class="list-group-item px-0 py-2 border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <div class="font-weight-bold text-dark small">
                                    {{ optional($rev->user)->name ?: 'UID: '.$rev->device_user_id }}
                                </div>
                                <small class="text-muted">
                                    {{ optional($rev->device)->device_name ?: 'Terminal' }} | {{ $rev->verification_method ?: 'Fingerprint' }}
                                </small>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-light border text-dark font-monospace small">
                                    {{ date('h:i:s A', strtotime($rev->event_time)) }}
                                </span>
                                <br>
                                <span class="badge {{ $rev->event_type == 'in' ? 'badge-success' : 'badge-info' }}" style="font-size: 9px;">
                                    {{ strtoupper($rev->event_type ?: 'PUNCH') }}
                                </span>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item px-0 py-4 text-center text-muted border-0">
                            কোনো নতুন পাঞ্চ ইভেন্ট নেই।
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ChartJS CDN for Department Distribution Pie/Doughnut -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById('deptPieChart');
    if (ctx) {
        var deptNames = {!! json_encode($departments->pluck('department_name')) !!};
        var deptCounts = {!! json_encode($departments->pluck('employees_count')) !!};

        if (deptNames.length === 0) {
            deptNames = ['Administration', 'Marketing', 'Sales', 'Accounts'];
            deptCounts = [5, 12, 18, 4];
        }

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: deptNames,
                datasets: [{
                    data: deptCounts,
                    backgroundColor: ['#2e7d32', '#0288d1', '#f57c00', '#7b1fa2', '#0097a7', '#5c6bc0', '#e91e63'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });
    }
});
</script>
@endsection
