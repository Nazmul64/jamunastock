@extends('layouts.backend.app')

@section('title', 'ফিঙ্গারপ্রিন্ট ও বায়োমেট্রিক এনরোলমেন্ট')

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h3 class="font-weight-bold mb-1" style="color: #2e7d32;">
                        <i class="ti-hand-point-up mr-2"></i> বায়োমেট্রিক ফিঙ্গারপ্রিন্ট এনরোলমেন্ট ও ইউজার ম্যাপিং
                    </h3>
                    <p class="text-muted mb-0">ডিভাইস ইউজার আইডি (UID) এবং কর্মচারীর প্রোফাইল ম্যাপিং ও ফিঙ্গারপ্রিন্ট রেজিস্ট্রি</p>
                </div>
                <div>
                    <a href="{{ route('admin.hrm.biometric.devices') }}" class="btn btn-outline-success btn-sm rounded-pill shadow-sm">
                        <i class="ti-server mr-1"></i> বায়োমেট্রিক ডিভাইস তালিকা
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
        <!-- Enrollment Form Card -->
        <div class="col-md-5 mb-4">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 font-weight-bold text-dark">
                        <i class="ti-plus text-success mr-2"></i> নতুন ফিঙ্গারপ্রিন্ট / UID ম্যাপিং
                    </h5>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('admin.hrm.biometric.enrollment.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">কর্মচারী নির্বাচন করুন <span class="text-danger">*</span></label>
                            <select name="user_id" id="employeeSelect" class="form-control" required onchange="autoFillDeviceUid(this)">
                                <option value="">-- কর্মচারী বাছাই করুন --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" 
                                            data-code="{{ optional($emp->hrmProfile)->employee_code }}"
                                            data-bio="{{ optional($emp->hrmProfile)->biometric_user_id ?: $emp->id }}">
                                        {{ $emp->name }} ({{ optional($emp->hrmProfile)->employee_code ?: 'EMP-'.$emp->id }}) - {{ optional(optional($emp->hrmProfile)->designation)->designation_name ?: 'Staff' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="font-weight-bold">ডিভাইস ইউজার আইডি (UID) <span class="text-danger">*</span></label>
                                <input type="text" name="device_user_id" id="deviceUserId" class="form-control" placeholder="যেমন: 1001" required>
                                <small class="text-muted">টার্মিনালে ব্যবহৃত ইউনিক নম্বর</small>
                            </div>
                            <div class="col-md-6">
                                <label class="font-weight-bold">বায়োমেট্রিক টার্মিনাল</label>
                                <select name="device_id" class="form-control">
                                    <option value="">-- সকল ডিভাইসের জন্য --</option>
                                    @foreach($devices as $dev)
                                        <option value="{{ $dev->id }}">{{ $dev->device_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">আঙ্গুল নির্বাচন করুন (Finger Index) <span class="text-danger">*</span></label>
                            <select name="finger_index" class="form-control" required>
                                <option value="0">ডান হাতের বৃদ্ধাঙ্গুলি (Right Thumb)</option>
                                <option value="1">ডান হাতের তর্জনী (Right Index)</option>
                                <option value="2">ডান হাতের মধ্যমা (Right Middle)</option>
                                <option value="3">ডান হাতের অনামিকা (Right Ring)</option>
                                <option value="4">ডান হাতের কনিষ্ঠা (Right Little)</option>
                                <option value="5">বাম হাতের বৃদ্ধাঙ্গুলি (Left Thumb)</option>
                                <option value="6">বাম হাতের তর্জনী (Left Index)</option>
                                <option value="7">বাম হাতের মধ্যমা (Left Middle)</option>
                                <option value="8">ফেস রিকগনিশন (Face ID)</option>
                                <option value="9">আরএফআইডি কার্ড (RFID Card)</option>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold">আরএফআইডি / স্মার্ট কার্ড নম্বর (ঐচ্ছিক)</label>
                            <input type="text" name="card_number" class="form-control" placeholder="যেমন: 0014298194">
                        </div>

                        <button type="submit" class="btn btn-success btn-block py-2 rounded-lg font-weight-bold shadow-sm">
                            <i class="ti-check mr-1"></i> ফিঙ্গারপ্রিন্ট রেজিস্টার ও ম্যাপিং নিশ্চিত করুন
                        </button>
                    </form>
                </div>
            </div>

            <!-- Enrollment Tips Card -->
            <div class="card border-0 shadow-sm rounded-lg mt-4 bg-light">
                <div class="card-body">
                    <h6 class="font-weight-bold text-dark mb-2">
                        <i class="ti-light-bulb text-warning mr-1"></i> এনরোলমেন্ট নির্দেশিকা:
                    </h6>
                    <ul class="text-muted small pl-3 mb-0" style="line-height: 1.8;">
                        <li>বায়োমেট্রিক ডিভাইসে কর্মচারীর যে <strong>User ID</strong> দিয়ে ফিঙ্গার স্ক্যান করা হয়েছে, এখানে হুবহু সেই আইডিটি বসান।</li>
                        <li>একজন কর্মচারীর জন্য একাধিক আঙ্গুল (মাস্টার ও ব্যাকআপ) ম্যাপ করা সম্ভব।</li>
                        <li>ম্যাপিং সম্পন্ন হওয়ার সাথে সাথে টার্মিনালের পরবর্তী পাঞ্চগুলো স্বয়ংক্রিয়ভাবে উক্ত কর্মীর প্রোফাইলে যুক্ত হবে।</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Mapped Employees Table Card -->
        <div class="col-md-7 mb-4">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                    <h5 class="mb-0 font-weight-bold text-dark">
                        <i class="ti-layers text-primary mr-2"></i> সক্রিয় বায়োমেট্রিক ম্যাপিং তালিকা
                    </h5>
                    <span class="badge badge-light px-3 py-2 text-muted border">
                        মোট রেকর্ড: {{ $biometrics->total() }} টি
                    </span>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th>কর্মী</th>
                                    <th>ডিভাইস UID</th>
                                    <th>নিবন্ধিত আঙ্গুল/টাইপ</th>
                                    <th>টার্মিনাল</th>
                                    <th>নিবন্ধনের তারিখ</th>
                                    <th class="text-right">একশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($biometrics as $b)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ optional($b->user)->name ?: 'User #'.$b->user_id }}</div>
                                        <small class="text-muted">{{ optional(optional($b->user)->hrmProfile)->employee_code ?: 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info px-2 py-1 font-monospace" style="font-size: 13px;">
                                            UID: {{ $b->device_user_id }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success px-2 py-1">
                                            <i class="ti-hand-point-right mr-1"></i>
                                            @if($b->finger_index == 0) ডান বৃদ্ধাঙ্গুলি
                                            @elseif($b->finger_index == 1) ডান তর্জনী
                                            @elseif($b->finger_index == 5) বাম বৃদ্ধাঙ্গুলি
                                            @elseif($b->finger_index == 6) বাম তর্জনী
                                            @elseif($b->finger_index == 8) ফেস (Face)
                                            @elseif($b->finger_index == 9) আরএফআইডি কার্ড
                                            @else আঙ্গুল #{{ $b->finger_index }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-dark">{{ $b->device ? $b->device->device_name : 'সকল টার্মিনাল' }}</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ date('d M, Y', strtotime($b->created_at)) }}</small>
                                    </td>
                                    <td class="text-right">
                                        <form action="{{ route('admin.hrm.biometric.enrollment.destroy', $b->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই ম্যাপিংটি মুছে ফেলতে চান?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-xs py-1 px-2" title="মুছে ফেলুন">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="ti-hand-point-up d-block mb-2" style="font-size: 34px; color: #ccc;"></i>
                                        কোনো এনরোলমেন্ট ডাটা পাওয়া যায়নি।
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $biometrics->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function autoFillDeviceUid(sel) {
    var opt = sel.options[sel.selectedIndex];
    if (opt && opt.getAttribute('data-bio')) {
        document.getElementById('deviceUserId').value = opt.getAttribute('data-bio');
    }
}
</script>
@endsection
