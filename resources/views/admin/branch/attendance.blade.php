@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-calendar-check-o text-success me-2"></i> মানবসম্পদ ও হাজিরা (H - HR & Attendance)
                    </h3>
                    <small class="text-muted">বায়োমেট্রিক ফিঙ্গারপ্রিন্ট ও ম্যানুয়াল হাজিরা রেকর্ড, বিলম্ব ও ছুটি ব্যবস্থাপনা</small>
                </div>
                <a href="{{ route('admin.branch.dashboard', ['branch_id' => $branch->id]) }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
                </a>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success rounded-pill px-4 py-2 mb-4">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                </div>
            @endif

            <div class="box border-0 shadow-xs rounded-4 mb-4">
                <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="box-title fw-bold text-dark mb-0">হাজিরা রেজিস্টার (তারিখ: {{ date('d M, Y', strtotime($selectedDate)) }})</h5>
                    <form method="GET" class="d-flex align-items-center gap-2 m-0">
                        <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                        <label class="text-muted small fw-bold mb-0">তারিখ পরিবর্তন:</label>
                        <input type="date" name="date" class="form-control form-control-sm rounded-pill" value="{{ $selectedDate }}" onchange="this.form.submit()">
                    </form>
                </div>
                <form action="{{ route('admin.branch.attendance.store', ['branch_id' => $branch->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="date" value="{{ $selectedDate }}">
                    <div class="box-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted fs-12">
                                    <tr>
                                        <th class="ps-4">কর্মচারী</th>
                                        <th>পদবি</th>
                                        <th>উপস্থিতির স্ট্যাটাস</th>
                                        <th>প্রবেশের সময় (In Time)</th>
                                        <th>প্রস্থানের সময় (Out Time)</th>
                                        <th>বিলম্বে উপস্থিতি (মিনিট)</th>
                                        <th class="pe-4">মন্তব্য / কারণ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employees as $emp)
                                        @php
                                            $att = $attendances->get($emp->id);
                                            $status = $att->status ?? 'present';
                                            $inTime = $att->check_in ?? ($att->in_time ?? '09:00');
                                            $outTime = $att->check_out ?? ($att->out_time ?? '18:00');
                                            $late = $att->late_minutes ?? 0;
                                            $notes = $att->remarks ?? ($att->notes ?? '');
                                        @endphp
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark fs-14">{{ $emp->name }}</div>
                                                <small class="text-muted">{{ $emp->email }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">{{ ucfirst($emp->role) }}</span>
                                            </td>
                                            <td>
                                                <select name="attendance[{{ $emp->id }}][status]" class="form-select form-select-sm rounded-pill" style="max-width: 150px;">
                                                    <option value="present" {{ $status === 'present' ? 'selected' : '' }}>উপস্থিত (Present)</option>
                                                    <option value="late" {{ $status === 'late' ? 'selected' : '' }}>দেরিতে উপস্থিত (Late)</option>
                                                    <option value="absent" {{ $status === 'absent' ? 'selected' : '' }}>অনুপস্থিত (Absent)</option>
                                                    <option value="on_leave" {{ $status === 'on_leave' ? 'selected' : '' }}>ছুটিতে (On Leave)</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="time" name="attendance[{{ $emp->id }}][in_time]" class="form-control form-control-sm rounded-pill" style="max-width: 120px;" value="{{ $inTime }}">
                                            </td>
                                            <td>
                                                <input type="time" name="attendance[{{ $emp->id }}][out_time]" class="form-control form-control-sm rounded-pill" style="max-width: 120px;" value="{{ $outTime }}">
                                            </td>
                                            <td>
                                                <input type="number" name="attendance[{{ $emp->id }}][late_minutes]" class="form-control form-control-sm rounded-pill" style="max-width: 90px;" value="{{ $late }}">
                                            </td>
                                            <td class="pe-4">
                                                <input type="text" name="attendance[{{ $emp->id }}][notes]" class="form-control form-control-sm rounded-pill" placeholder="মন্তব্য..." value="{{ $notes }}">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                হাজিরা নেওয়ার জন্য কোনো কর্মচারী পাওয়া যায়নি।
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($employees->count() > 0)
                        <div class="box-footer py-3 px-4 bg-light text-end border-top">
                            <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">
                                <i class="fa fa-save me-1"></i> আজকের হাজিরা সংরক্ষণ করুন
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
