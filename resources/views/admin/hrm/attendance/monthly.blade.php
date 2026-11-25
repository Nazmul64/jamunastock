@extends('layouts.backend.app')

@section('title', 'মাসিক উপস্থিতি রেজিস্টার')

@section('content')
<style>
    .matrix-cell {
        min-width: 32px;
        height: 34px;
        text-align: center;
        padding: 4px 2px !important;
        font-size: 11px;
        font-weight: 700;
        vertical-align: middle !important;
    }
    .status-P { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    .status-L { background-color: #fff3e0; color: #e65100; border: 1px solid #ffe0b2; }
    .status-A { background-color: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
    .status-LV { background-color: #e3f2fd; color: #1565c0; border: 1px solid #bbdefb; }
    .status-H { background-color: #fffde7; color: #f57f17; border: 1px solid #fff9c4; }
    .status-W { background-color: #f5f5f5; color: #757575; border: 1px solid #e0e0e0; }
    .status-HD { background-color: #f3e5f5; color: #6a1b9a; border: 1px solid #e1bee7; }
</style>

<div class="content-wrapper">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h3 class="font-weight-bold mb-1" style="color: #2e7d32;">
                        <i class="ti-calendar mr-2"></i> মাসিক উপস্থিতি রেজিস্টার ও ম্যাট্রিক্স (Monthly Register)
                    </h3>
                    <p class="text-muted mb-0">প্রতিটি কর্মীর পুরো মাসের দিনভিত্তিক উপস্থিতি, লেট, ছুটি ও ওভারটাইম সামারি</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill shadow-sm" onclick="window.print();">
                        <i class="ti-printer mr-1"></i> প্রিন্ট রিপোর্ট
                    </button>
                    <a href="{{ route('admin.hrm.attendance.index') }}" class="btn btn-outline-success btn-sm rounded-pill shadow-sm">
                        <i class="ti-alarm-clock mr-1"></i> দৈনিক উপস্থিতি
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Legend Card -->
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-body py-3">
            <form action="{{ route('admin.hrm.attendance.monthly') }}" method="GET" class="row align-items-center">
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="small font-weight-bold text-muted mb-1">মাস:</label>
                    <select name="month" class="form-control form-control-sm" onchange="this.form.submit()">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ sprintf('%02d', $m) }}" {{ (int)$month == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="small font-weight-bold text-muted mb-1">বছর:</label>
                    <select name="year" class="form-control form-control-sm" onchange="this.form.submit()">
                        @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6 text-md-right pt-2 pt-md-0">
                    <span class="badge status-P px-2 py-1 mr-1">P: উপস্থিত (Present)</span>
                    <span class="badge status-L px-2 py-1 mr-1">L: বিলম্ব (Late)</span>
                    <span class="badge status-A px-2 py-1 mr-1">A: অনুপস্থিত (Absent)</span>
                    <span class="badge status-LV px-2 py-1 mr-1">LV: ছুটি (Leave)</span>
                    <span class="badge status-H px-2 py-1 mr-1">H: হাফ-ডে</span>
                    <span class="badge status-W px-2 py-1">W: সাপ্তাহিক বন্ধ</span>
                </div>
            </form>
        </div>
    </div>

    <!-- Monthly Matrix Table Card -->
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 font-weight-bold text-dark">
                {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }} এর হাজিরা রেজিস্টার
            </h5>
        </div>
        <div class="card-body pt-0 px-2">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-bordered table-sm align-middle text-nowrap mb-0">
                    <thead class="bg-light text-dark sticky-top" style="z-index: 10;">
                        <tr>
                            <th style="min-width: 170px;" class="bg-light">কর্মকর্তা / কর্মচারী</th>
                            @for($d = 1; $d <= $daysInMonth; $d++)
                                @php
                                    $dayDate = sprintf('%04d-%02d-%02d', $year, $month, $d);
                                    $dayName = date('D', strtotime($dayDate));
                                    $isWeekend = in_array(date('l', strtotime($dayDate)), $policy->weekend_days ?? ['Friday']);
                                @endphp
                                <th class="matrix-cell {{ $isWeekend ? 'bg-secondary text-white' : '' }}" title="{{ $dayDate }} ({{ $dayName }})">
                                    {{ $d }}<br><small style="font-size: 8px;">{{ substr($dayName, 0, 2) }}</small>
                                </th>
                            @endfor
                            <th class="text-center bg-success text-white">উপস্থিত</th>
                            <th class="text-center bg-warning text-dark">বিলম্ব</th>
                            <th class="text-center bg-danger text-white">অনুপস্থিত</th>
                            <th class="text-center bg-info text-white">ছুটি</th>
                            <th class="text-center bg-primary text-white">মোট কর্মঘণ্টা</th>
                            <th class="text-center bg-dark text-white">ওভারটাইম</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                        @php
                            $pCount = 0;
                            $lCount = 0;
                            $aCount = 0;
                            $lvCount = 0;
                            $hCount = 0;
                            $totalHours = 0;
                            $totalOt = 0;
                        @endphp
                        <tr>
                            <td class="font-weight-bold bg-white" style="position: sticky; left: 0; z-index: 5;">
                                <div class="text-dark">{{ $emp->name }}</div>
                                <small class="text-muted">{{ optional($emp->hrmProfile)->employee_code ?: 'EMP-'.$emp->id }}</small>
                            </td>
                            @for($d = 1; $d <= $daysInMonth; $d++)
                                @php
                                    $dayDate = sprintf('%04d-%02d-%02d', $year, $month, $d);
                                    $isWeekend = in_array(date('l', strtotime($dayDate)), $policy->weekend_days ?? ['Friday']);
                                    $att = $matrix[$emp->id][$d] ?? null;
                                    $code = '-';
                                    $cls = '';

                                    if ($att) {
                                        $totalHours += $att->working_hours;
                                        $totalOt += $att->overtime_minutes;

                                        if ($att->status == 'present') {
                                            $code = 'P'; $cls = 'status-P'; $pCount++;
                                        } elseif ($att->status == 'late') {
                                            $code = 'L'; $cls = 'status-L'; $lCount++; $pCount++;
                                        } elseif ($att->status == 'half_day') {
                                            $code = 'H'; $cls = 'status-H'; $hCount++;
                                        } elseif ($att->status == 'leave') {
                                            $code = 'LV'; $cls = 'status-LV'; $lvCount++;
                                        } elseif ($att->status == 'absent') {
                                            $code = 'A'; $cls = 'status-A'; $aCount++;
                                        } elseif ($att->status == 'holiday') {
                                            $code = 'HD'; $cls = 'status-HD';
                                        } else {
                                            $code = 'P'; $cls = 'status-P'; $pCount++;
                                        }
                                    } else {
                                        if ($isWeekend) {
                                            $code = 'W'; $cls = 'status-W';
                                        } elseif (strtotime($dayDate) <= time()) {
                                            $code = 'A'; $cls = 'status-A'; $aCount++;
                                        }
                                    }
                                @endphp
                                <td class="matrix-cell {{ $cls }}" title="{{ $dayDate }}: {{ $att ? $att->status : ($isWeekend ? 'Weekend' : 'Not Recorded') }}">
                                    {{ $code }}
                                </td>
                            @endfor
                            <td class="text-center font-weight-bold text-success">{{ $pCount }}</td>
                            <td class="text-center font-weight-bold text-warning">{{ $lCount }}</td>
                            <td class="text-center font-weight-bold text-danger">{{ $aCount }}</td>
                            <td class="text-center font-weight-bold text-info">{{ $lvCount }}</td>
                            <td class="text-center font-weight-bold text-primary">{{ round($totalHours, 1) }}h</td>
                            <td class="text-center font-weight-bold text-dark">{{ round($totalOt / 60, 1) }}h</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $daysInMonth + 7 }}" class="text-center py-5 text-muted">
                                কোনো কর্মকর্তা বা কর্মচারীর রেকর্ড পাওয়া যায়নি।
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
