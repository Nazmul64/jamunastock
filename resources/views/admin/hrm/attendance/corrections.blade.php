@extends('layouts.backend.app')

@section('title', 'উপস্থিতি সংশোধন আবেদন')

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h3 class="font-weight-bold mb-1" style="color: #2e7d32;">
                        <i class="ti-check-box mr-2"></i> উপস্থিতি সংশোধন আবেদন (Attendance Corrections)
                    </h3>
                    <p class="text-muted mb-0">ভুলবশত পাঞ্চ মিস হওয়া বা ডিভাইস ত্রুটির কারণে কর্মীদের আবেদনের পর্যালোচনা ও অনুমোদন</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.hrm.attendance.index') }}" class="btn btn-outline-success btn-sm rounded-pill shadow-sm">
                        <i class="ti-alarm-clock mr-1"></i> দৈনিক উপস্থিতি
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

    <!-- Table Card -->
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <h5 class="mb-0 font-weight-bold text-dark">
                <i class="ti-list text-primary mr-2"></i> সংশোধন আবেদনের তালিকা
            </h5>
            <span class="badge badge-light px-3 py-2 text-muted border">
                মোট আবেদন: {{ $corrections->total() }} টি
            </span>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>কর্মচারী</th>
                            <th>হাজিরার তারিখ</th>
                            <th>আবেদিত চেক-ইন</th>
                            <th>আবেদিত চেক-আউট</th>
                            <th>কারণের বিবরণ</th>
                            <th>স্ট্যাটাস</th>
                            <th>আবেদনের সময়</th>
                            <th class="text-right">একশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($corrections as $c)
                        <tr>
                            <td>
                                <div class="font-weight-bold text-dark">{{ optional($c->user)->name ?: 'User #'.$c->user_id }}</div>
                                <small class="text-muted">{{ optional(optional($c->user)->hrmProfile)->employee_code ?: 'N/A' }} | {{ optional(optional(optional($c->user)->hrmProfile)->department)->department_name ?: 'General' }}</small>
                            </td>
                            <td>
                                <span class="badge badge-light border text-dark font-weight-bold">
                                    {{ date('d M, Y', strtotime($c->date)) }}
                                </span>
                            </td>
                            <td>
                                @if($c->requested_check_in)
                                    <span class="badge badge-success px-2 py-1"><i class="ti-time mr-1"></i> {{ date('h:i A', strtotime($c->requested_check_in)) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($c->requested_check_out)
                                    <span class="badge badge-info px-2 py-1"><i class="ti-time mr-1"></i> {{ date('h:i A', strtotime($c->requested_check_out)) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-dark">{{ $c->reason }}</span>
                                @if($c->attachment)
                                    <br><a href="{{ asset($c->attachment) }}" target="_blank" class="small text-primary"><i class="ti-clip mr-1"></i> সংযুক্ত ফাইল</a>
                                @endif
                            </td>
                            <td>
                                @if($c->status == 'approved')
                                    <span class="badge badge-success px-2 py-1"><i class="ti-check mr-1"></i> অনুমোদিত</span>
                                @elseif($c->status == 'rejected')
                                    <span class="badge badge-danger px-2 py-1"><i class="ti-close mr-1"></i> প্রত্যাখ্যাত</span>
                                @else
                                    <span class="badge badge-warning px-2 py-1"><i class="ti-timer mr-1"></i> অপেক্ষমাণ</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ date('d M, Y h:i A', strtotime($c->created_at)) }}</small>
                            </td>
                            <td class="text-right">
                                @if($c->status == 'pending')
                                    <div class="btn-group" role="group">
                                        <!-- Approve Form -->
                                        <form action="{{ route('admin.hrm.attendance.corrections.approve', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই আবেদনটি অনুমোদন করতে চান?');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-xs py-1 px-2">
                                                <i class="ti-check mr-1"></i> অনুমোদন
                                            </button>
                                        </form>

                                        <!-- Reject Trigger -->
                                        <button type="button" class="btn btn-outline-danger btn-xs py-1 px-2 ml-1" data-toggle="modal" data-target="#rejectModal{{ $c->id }}" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $c->id }}">
                                            <i class="ti-close mr-1"></i> বাতিল
                                        </button>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade text-left" id="rejectModal{{ $c->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title font-weight-bold">আবেদন বাতিলের কারণ উল্লেখ করুন</h5>
                                                    <button type="button" class="btn-close btn-close-white text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: 0; font-size: 20px;">&times;</button>
                                                </div>
                                                <form action="{{ route('admin.hrm.attendance.corrections.reject', $c->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group mb-3">
                                                            <label class="font-weight-bold">প্রত্যাখ্যানের কারণ / মন্তব্য</label>
                                                            <textarea name="remarks" class="form-control" rows="3" placeholder="কেন এই আবেদনটি বাতিল করা হচ্ছে..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-dismiss="modal" data-bs-dismiss="modal">বন্ধ করুন</button>
                                                        <button type="submit" class="btn btn-danger">বাতিল নিশ্চিত করুন</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <small class="text-muted">{{ optional($c->reviewer)->name ? 'Reviewed by '.$c->reviewer->name : 'প্রসেসড' }}</small>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="ti-check-box d-block mb-2 text-muted" style="font-size: 34px;"></i>
                                কোনো অপেক্ষমাণ সংশোধন আবেদন নেই।
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $corrections->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
