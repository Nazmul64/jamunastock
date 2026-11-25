@extends('layouts.backend.app')

@section('title', 'কর্মী প্রোফাইল ও জীবনচক্র (H2R)')

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h3 class="font-weight-bold mb-1" style="color: #2e7d32;">
                        <i class="ti-id-badge mr-2"></i> {{ $employee->name }}-এর ৩৬০° কর্মী জীবনচক্র প্রোফাইল (H2R)
                    </h3>
                    <p class="text-muted mb-0">আইডি: <strong>{{ optional($employee->hrmProfile)->employee_code ?: 'EMP-'.$employee->id }}</strong> | পদবী: {{ optional(optional($employee->hrmProfile)->designation)->designation_name ?: 'কর্মকর্তা' }} | ডিপার্টমেন্ট: {{ optional(optional($employee->hrmProfile)->department)->department_name ?: 'সাধারণ' }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.hrm.employees.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill shadow-sm">
                        <i class="ti-arrow-left mr-1"></i> কর্মী তালিকায় ফিরুন
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

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="ti-alert mr-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Left Sidebar: Profile Card & Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-lg text-center p-4 mb-4">
                <div class="position-relative mx-auto mb-3" style="width: 110px; height: 110px;">
                    <img src="{{ $employee->avatar ? asset('upload/user_images/'.$employee->avatar) : asset('frontend/images/default-user.png') }}" 
                         alt="{{ $employee->name }}" 
                         class="rounded-circle border border-3 border-success shadow" 
                         style="width: 110px; height: 110px; object-fit: cover;"
                         onerror="this.src='{{ asset('frontend/images/default-user.png') }}'">
                </div>

                <h4 class="font-weight-bold mb-1 text-dark">{{ $employee->name }}</h4>
                <p class="text-muted mb-2 font-weight-bold">
                    {{ optional(optional($employee->hrmProfile)->designation)->designation_name ?: 'Staff' }}
                </p>
                <div class="mb-3">
                    @php
                        $st = optional($employee->hrmProfile)->status ?? 'active';
                    @endphp
                    @if($st == 'active')
                        <span class="badge badge-success px-3 py-2 rounded-pill"><i class="ti-check mr-1"></i> সক্রিয় (Active)</span>
                    @elseif($st == 'probation')
                        <span class="badge badge-warning px-3 py-2 rounded-pill"><i class="ti-timer mr-1"></i> শিক্ষানবিস (Probation)</span>
                    @elseif($st == 'resigned')
                        <span class="badge badge-secondary px-3 py-2 rounded-pill">ইস্তফাকৃত (Resigned)</span>
                    @else
                        <span class="badge badge-danger px-3 py-2 rounded-pill">{{ ucfirst($st) }}</span>
                    @endif
                </div>

                <hr class="my-3">

                <div class="text-left small" style="line-height: 2;">
                    <div><strong><i class="ti-email text-success mr-2"></i> ইমেইল:</strong> {{ $employee->email }}</div>
                    <div><strong><i class="ti-mobile text-primary mr-2"></i> ফোন:</strong> {{ $employee->phone ?: 'N/A' }}</div>
                    <div><strong><i class="ti-id-badge text-warning mr-2"></i> বায়োমেট্রিক UID:</strong> <code>{{ optional($employee->hrmProfile)->biometric_user_id ?: 'N/A' }}</code></div>
                    <div><strong><i class="ti-location-pin text-info mr-2"></i> শাখা:</strong> {{ optional(optional($employee->hrmProfile)->branch)->branch_name ?: 'প্রধান শাখা' }}</div>
                    <div><strong><i class="ti-time text-danger mr-2"></i> শিফট:</strong> {{ optional(optional($employee->hrmProfile)->shift)->shift_name ?: 'রেগুলার শিফট' }}</div>
                    <div><strong><i class="ti-calendar text-dark mr-2"></i> যোগদানের তারিখ:</strong> {{ optional($employee->hrmProfile)->joining_date ? date('d M, Y', strtotime($employee->hrmProfile->joining_date)) : 'N/A' }}</div>
                    <div><strong><i class="ti-desktop text-secondary mr-2"></i> কাজের ধরন:</strong> {{ optional($employee->hrmProfile)->work_location ?: 'Head Office' }}</div>
                </div>

                <hr class="my-3">

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-success btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#docModal" data-bs-toggle="modal" data-bs-target="#docModal">
                        <i class="ti-upload mr-1"></i> ডকুমেন্ট আপলোড
                    </button>
                    <button type="button" class="btn btn-outline-warning btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#discModal" data-bs-toggle="modal" data-bs-target="#discModal">
                        <i class="ti-alert mr-1"></i> সতর্কবার্তা / শোকজ
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#exitModal" data-bs-toggle="modal" data-bs-target="#exitModal">
                        <i class="ti-power-off mr-1"></i> রিলিজ & F&F সেটেলমেন্ট
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Main: 360-Degree Tabs -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white border-0 pt-3">
                    <ul class="nav nav-tabs card-header-tabs" id="employeeTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active font-weight-bold" id="personal-tab" data-toggle="tab" data-bs-toggle="tab" href="#personal" role="tab">
                                <i class="ti-user mr-1"></i> ব্যক্তিগত ও পারিবারিক
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="salary-tab" data-toggle="tab" data-bs-toggle="tab" href="#salary" role="tab">
                                <i class="ti-money mr-1"></i> বেতন কাঠামো
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="attendance-tab" data-toggle="tab" data-bs-toggle="tab" href="#attendance" role="tab">
                                <i class="ti-alarm-clock mr-1"></i> বায়োমেট্রিক ও হাজিরা
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="docs-tab" data-toggle="tab" data-bs-toggle="tab" href="#docs" role="tab">
                                <i class="ti-folder mr-1"></i> ডকুমেন্টস লকার
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="assets-tab" data-toggle="tab" data-bs-toggle="tab" href="#assets" role="tab">
                                <i class="ti-desktop mr-1"></i> কোম্পানি অ্যাসেট
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="disciplinary-tab" data-toggle="tab" data-bs-toggle="tab" href="#disciplinary" role="tab">
                                <i class="ti-medall mr-1"></i> পারফরম্যান্স ও শোকজ
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="employeeTabContent">
                        <!-- Tab 1: Personal & Family Information Form -->
                        <div class="tab-pane fade show active" id="personal" role="tabpanel">
                            <form action="{{ route('admin.hrm.employees.update_profile', $employee->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <h6 class="font-weight-bold text-success mb-3">ক. ব্যক্তিগত তথ্য (Personal Details)</h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="font-weight-bold">পুরো নাম <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ $employee->name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="font-weight-bold">ইমেইল <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="{{ $employee->email }}" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="font-weight-bold">মোবাইল ফোন <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control" value="{{ $employee->phone }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="font-weight-bold">জাতীয় পরিচয়পত্র (NID) / পাসপোর্ট</label>
                                        <input type="text" name="nid" class="form-control" value="{{ optional($employee->hrmProfile)->nid }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="font-weight-bold">রক্তের গ্রুপ</label>
                                        <select name="blood_group" class="form-control">
                                            <option value="">বাছাই করুন</option>
                                            @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                                <option value="{{ $bg }}" {{ optional($employee->hrmProfile)->blood_group == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="font-weight-bold">লিঙ্গ</label>
                                        <select name="gender" class="form-control">
                                            <option value="male" {{ optional($employee->hrmProfile)->gender == 'male' ? 'selected' : '' }}>পুরুষ (Male)</option>
                                            <option value="female" {{ optional($employee->hrmProfile)->gender == 'female' ? 'selected' : '' }}>মহিলা (Female)</option>
                                            <option value="other" {{ optional($employee->hrmProfile)->gender == 'other' ? 'selected' : '' }}>অন্যান্য</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="font-weight-bold">বৈবাহিক অবস্থা</label>
                                        <select name="marital_status" class="form-control">
                                            <option value="single" {{ optional($employee->hrmProfile)->marital_status == 'single' ? 'selected' : '' }}>অবিবাহিত</option>
                                            <option value="married" {{ optional($employee->hrmProfile)->marital_status == 'married' ? 'selected' : '' }}>বিবাহিত</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="font-weight-bold">বর্তমান ঠিকানা</label>
                                        <textarea name="present_address" class="form-control" rows="2">{{ optional($employee->hrmProfile)->present_address }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="font-weight-bold">স্থায়ী ঠিকানা</label>
                                        <textarea name="permanent_address" class="form-control" rows="2">{{ optional($employee->hrmProfile)->permanent_address }}</textarea>
                                    </div>
                                </div>

                                <h6 class="font-weight-bold text-success mt-4 mb-3">খ. পারিবারিক ও জরুরি যোগাযোগ</h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="font-weight-bold">পিতার নাম ও ফোন</label>
                                        <input type="text" name="father_name" class="form-control mb-1" placeholder="পিতার নাম" value="{{ optional($employee->hrmProfile)->father_name }}">
                                        <input type="text" name="father_phone" class="form-control" placeholder="ফোন নম্বর" value="{{ optional($employee->hrmProfile)->father_phone }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="font-weight-bold">মাতার নাম ও ফোন</label>
                                        <input type="text" name="mother_name" class="form-control mb-1" placeholder="মাতার নাম" value="{{ optional($employee->hrmProfile)->mother_name }}">
                                        <input type="text" name="mother_phone" class="form-control" placeholder="ফোন নম্বর" value="{{ optional($employee->hrmProfile)->mother_phone }}">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="font-weight-bold">জরুরি যোগাযোগের ব্যক্তি</label>
                                        <input type="text" name="emergency_contact_name" class="form-control" value="{{ optional($employee->hrmProfile)->emergency_contact_name }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="font-weight-bold">জরুরি ফোন</label>
                                        <input type="text" name="emergency_contact_phone" class="form-control" value="{{ optional($employee->hrmProfile)->emergency_contact_phone }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="font-weight-bold">সম্পর্ক</label>
                                        <input type="text" name="emergency_contact_relation" class="form-control" placeholder="যেমন: ভাই / বোন" value="{{ optional($employee->hrmProfile)->emergency_contact_relation }}">
                                    </div>
                                </div>

                                <h6 class="font-weight-bold text-success mt-4 mb-3">গ. অফিস ও নিয়োগ সংক্রান্ত</h6>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="font-weight-bold">কর্মী আইডি <span class="text-danger">*</span></label>
                                        <input type="text" name="employee_code" class="form-control" value="{{ optional($employee->hrmProfile)->employee_code ?: 'EMP-'.$employee->id }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="font-weight-bold">বায়োমেট্রিক UID (মেশিন আইডি)</label>
                                        <input type="text" name="biometric_user_id" class="form-control" value="{{ optional($employee->hrmProfile)->biometric_user_id }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="font-weight-bold">চাকরির স্ট্যাটাস</label>
                                        <select name="status" class="form-control">
                                            <option value="active" {{ optional($employee->hrmProfile)->status == 'active' ? 'selected' : '' }}>সক্রিয় (Active)</option>
                                            <option value="probation" {{ optional($employee->hrmProfile)->status == 'probation' ? 'selected' : '' }}>শিক্ষানবিস (Probation)</option>
                                            <option value="on_leave" {{ optional($employee->hrmProfile)->status == 'on_leave' ? 'selected' : '' }}>ছুটিতে (On Leave)</option>
                                            <option value="resigned" {{ optional($employee->hrmProfile)->status == 'resigned' ? 'selected' : '' }}>ইস্তফাকৃত (Resigned)</option>
                                            <option value="terminated" {{ optional($employee->hrmProfile)->status == 'terminated' ? 'selected' : '' }}>বরখাস্ত (Terminated)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="font-weight-bold">ডিপার্টমেন্ট</label>
                                        <select name="department_id" class="form-control">
                                            @foreach($departments as $d)
                                                <option value="{{ $d->id }}" {{ optional($employee->hrmProfile)->department_id == $d->id ? 'selected' : '' }}>{{ $d->department_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="font-weight-bold">পদবী</label>
                                        <select name="designation_id" class="form-control">
                                            @foreach($designations as $des)
                                                <option value="{{ $des->id }}" {{ optional($employee->hrmProfile)->designation_id == $des->id ? 'selected' : '' }}>{{ $des->designation_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="font-weight-bold">কাজের স্থান (Work Location)</label>
                                        <input type="text" name="work_location" class="form-control" value="{{ optional($employee->hrmProfile)->work_location ?: 'Head Office' }}">
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold">নতুন ছবি (Avatar)</label>
                                    <input type="file" name="avatar" class="form-control" accept="image/*">
                                </div>

                                <button type="submit" class="btn btn-success px-4 rounded-pill font-weight-bold">
                                    <i class="ti-check mr-1"></i> প্রোফাইল তথ্য আপডেট করুন
                                </button>
                            </form>
                        </div>

                        <!-- Tab 2: Salary & Compensation -->
                        <div class="tab-pane fade" id="salary" role="tabpanel">
                            <h6 class="font-weight-bold text-success mb-3">বেতন ও ভাতাদি কাঠামো</h6>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded border mb-3">
                                        <label class="text-muted small font-weight-bold">মূল বেতন (Basic Salary):</label>
                                        <h4 class="font-weight-bold text-dark mb-0">৳{{ number_format(optional($employee->hrmProfile)->basic_salary ?? 0, 2) }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded border mb-3">
                                        <label class="text-muted small font-weight-bold">বাড়ি ভাড়া ভাতা (House Rent - 40%):</label>
                                        <h4 class="font-weight-bold text-dark mb-0">৳{{ number_format(optional($employee->hrmProfile)->house_rent ?? 0, 2) }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded border mb-3">
                                        <label class="text-muted small font-weight-bold">চিকিৎসা ভাতা (Medical - 10%):</label>
                                        <h4 class="font-weight-bold text-dark mb-0">৳{{ number_format(optional($employee->hrmProfile)->medical_allowance ?? 0, 2) }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded border mb-3">
                                        <label class="text-muted small font-weight-bold">যাতায়াত ও অন্যান্য ভাতা:</label>
                                        <h4 class="font-weight-bold text-dark mb-0">৳{{ number_format(optional($employee->hrmProfile)->transport_allowance ?? 0, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 rounded border" style="background: #e8f5e9;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold text-success h5 mb-0">সর্বমোট মোট মাসিক বেতন (Gross Salary):</span>
                                    <span class="font-weight-bold text-success h4 mb-0">৳{{ number_format(optional($employee->hrmProfile)->gross_salary ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 3: Biometrics & Attendance -->
                        <div class="tab-pane fade" id="attendance" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="font-weight-bold text-success mb-0">নিবন্ধিত বায়োমেট্রিক ও সর্বশেষ ৩০ দিনের হাজিরা</h6>
                                <a href="{{ route('admin.hrm.biometric.enrollment') }}" class="btn btn-outline-primary btn-xs py-1 px-2 rounded-pill">
                                    + ফিঙ্গারপ্রিন্ট যোগ করুন
                                </a>
                            </div>

                            <!-- Enrolled Fingerprints Card -->
                            <div class="p-3 bg-light rounded border mb-4">
                                <label class="font-weight-bold small text-muted mb-2">ডিভাইসে রেজিস্টার্ড বায়োমেট্রিক টেমপ্লেট:</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @forelse($employee->biometrics as $bio)
                                        <span class="badge badge-success px-3 py-2 mr-2 mb-2 font-monospace">
                                            <i class="ti-hand-point-up mr-1"></i> UID: {{ $bio->device_user_id }} (Index #{{ $bio->finger_index }})
                                        </span>
                                    @empty
                                        <span class="text-muted small">কোনো ডিভাইস ফিঙ্গারপ্রিন্ট এনরোল করা হয়নি।</span>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Attendance History Table -->
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>তারিখ</th>
                                            <th>চেক-ইন</th>
                                            <th>চেক-আউট</th>
                                            <th>কাজের সময়</th>
                                            <th>ওভারটাইম</th>
                                            <th>স্ট্যাটাস</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($employee->hrmAttendances as $att)
                                        <tr>
                                            <td>{{ date('d M, Y', strtotime($att->date)) }}</td>
                                            <td>{{ $att->check_in ? date('h:i A', strtotime($att->check_in)) : '-' }}</td>
                                            <td>{{ $att->check_out ? date('h:i A', strtotime($att->check_out)) : '-' }}</td>
                                            <td>{{ $att->working_hours }}h</td>
                                            <td>{{ round($att->overtime_minutes/60, 1) }}h</td>
                                            <td>
                                                @if($att->status == 'present') <span class="badge badge-success">উপস্থিত</span>
                                                @elseif($att->status == 'late') <span class="badge badge-warning">বিলম্ব</span>
                                                @else <span class="badge badge-danger">{{ $att->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">কোনো সাম্প্রতিক হাজিরা রেকর্ড পাওয়া যায়নি।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab 4: Documents Locker -->
                        <div class="tab-pane fade" id="docs" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="font-weight-bold text-success mb-0">ডিজিটাল ডকুমেন্টস লকার</h6>
                                <button type="button" class="btn btn-success btn-xs py-1 px-3 rounded-pill" data-toggle="modal" data-target="#docModal" data-bs-toggle="modal" data-bs-target="#docModal">
                                    + ডকুমেন্ট আপলোড
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>শিরোনাম</th>
                                            <th>টাইপ</th>
                                            <th>সংযুক্ত ফাইল</th>
                                            <th>যাচাইকরণ</th>
                                            <th>তারিখ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($employee->hrmDocuments as $doc)
                                        <tr>
                                            <td class="font-weight-bold text-dark">{{ $doc->title }}</td>
                                            <td><span class="badge badge-light border">{{ ucfirst($doc->document_type) }}</span></td>
                                            <td>
                                                <a href="{{ asset($doc->file_path) }}" target="_blank" class="btn btn-outline-primary btn-xs py-1 px-2">
                                                    <i class="ti-download mr-1"></i> ডাউনলোড / দেখুন
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge badge-success px-2 py-1"><i class="ti-check mr-1"></i> Verified</span>
                                            </td>
                                            <td><small class="text-muted">{{ date('d M, Y', strtotime($doc->created_at)) }}</small></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">কোনো ডকুমেন্ট আপলোড করা হয়নি।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab 5: Allocated Company Assets -->
                        <div class="tab-pane fade" id="assets" role="tabpanel">
                            <h6 class="font-weight-bold text-success mb-3">কর্মীর নিকট ন্যস্ত কোম্পানি সরঞ্জাম (Assigned Hardware)</h6>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>সরঞ্জাম নাম</th>
                                            <th>সিরিয়াল নম্বর</th>
                                            <th>মডেল</th>
                                            <th>বরাদ্দের তারিখ</th>
                                            <th>স্ট্যাটাস</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($employee->hrmAssets as $asset)
                                        <tr>
                                            <td class="font-weight-bold text-dark">{{ $asset->asset_name }}</td>
                                            <td><code>{{ $asset->asset_code }}</code></td>
                                            <td>{{ $asset->category }}</td>
                                            <td>{{ $asset->assigned_date ? date('d M, Y', strtotime($asset->assigned_date)) : '-' }}</td>
                                            <td>
                                                <span class="badge badge-success px-2 py-1">ব্যবহৃত (Allocated)</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">এই কর্মীকে কোনো হার্ডওয়্যার বা অ্যাসেট বরাদ্দ দেওয়া হয়নি।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab 6: Disciplinary & Warnings -->
                        <div class="tab-pane fade" id="disciplinary" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="font-weight-bold text-success mb-0">শৃঙ্খলা রেকর্ড ও শোকজ নোটিশ</h6>
                                <button type="button" class="btn btn-warning btn-xs py-1 px-3 rounded-pill" data-toggle="modal" data-target="#discModal" data-bs-toggle="modal" data-bs-target="#discModal">
                                    + নোটিশ জারি করুন
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>রেকর্ড টাইপ</th>
                                            <th>জারি তারিখ</th>
                                            <th>বিবরণ ও কারণ</th>
                                            <th>জারি করেছেন</th>
                                            <th>স্ট্যাটাস</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($employee->hrmDisciplinaryRecords as $rec)
                                        <tr>
                                            <td>
                                                <span class="badge badge-danger px-2 py-1">{{ strtoupper($rec->record_type) }}</span>
                                            </td>
                                            <td>{{ date('d M, Y', strtotime($rec->issue_date)) }}</td>
                                            <td>{{ $rec->description }}</td>
                                            <td>{{ optional($rec->issuer)->name ?: 'HR Admin' }}</td>
                                            <td>
                                                <span class="badge badge-light border">{{ ucfirst($rec->status) }}</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">কোনো শৃঙ্খলাবিরোধী রেকর্ড নেই (Good Standing)।</td>
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
    </div>
</div>

<!-- Modal: Upload Document -->
<div class="modal fade" id="docModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold"><i class="ti-upload mr-1"></i> নতুন ডকুমেন্ট আপলোড</h5>
                <button type="button" class="btn-close btn-close-white text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: 0; font-size: 20px;">&times;</button>
            </div>
            <form action="{{ route('admin.hrm.employees.upload_document', $employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">ডকুমেন্টের ধরন <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-control" required>
                            <option value="nid">জাতীয় পরিচয়পত্র (National ID)</option>
                            <option value="passport">পাসপোর্ট (Passport)</option>
                            <option value="resume">জীবনবৃত্তান্ত (Resume/CV)</option>
                            <option value="contract">চাকরির চুক্তিপত্র (Offer/Contract)</option>
                            <option value="certificate">শিক্ষাগত সনদ (Certificate)</option>
                            <option value="other">অন্যান্য ডকুমেন্ট</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">ডকুমেন্ট শিরোনাম <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="যেমন: NID Copy Scan" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">ফাইল নির্বাচন করুন (PDF, JPG, PNG) <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">মেয়াদোত্তীর্ণের তারিখ (যদি প্রযোজ্য হয়)</label>
                        <input type="date" name="expiry_date" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success">আপলোড করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Disciplinary Action -->
<div class="modal fade" id="discModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold"><i class="ti-alert mr-1"></i> শোকজ / সতর্কবার্তা জারি</h5>
                <button type="button" class="btn-close text-dark" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: 0; font-size: 20px;">&times;</button>
            </div>
            <form action="{{ route('admin.hrm.employees.disciplinary', $employee->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">রেকর্ডের ধরন <span class="text-danger">*</span></label>
                        <select name="record_type" class="form-control" required>
                            <option value="verbal_warning">মৌখিক সতর্কবার্তা (Verbal Warning)</option>
                            <option value="written_warning">লিখিত সতর্কবার্তা (Written Warning)</option>
                            <option value="show_cause">শোকজ নোটিশ (Show Cause Notice)</option>
                            <option value="suspension">সাময়িক বরখাস্ত (Suspension)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">জারি করার তারিখ <span class="text-danger">*</span></label>
                        <input type="date" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">কারণ ও বিস্তারিত বিবরণ <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="3" placeholder="ঘটনা ও কারণ উল্লেখ করুন..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-warning font-weight-bold">জারি করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Exit Settlement (F&F) -->
<div class="modal fade" id="exitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold"><i class="ti-power-off mr-1"></i> রিলিজ ও ফাইনাল সেটেলমেন্ট (F&F)</h5>
                <button type="button" class="btn-close btn-close-white text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: 0; font-size: 20px;">&times;</button>
            </div>
            <form action="{{ route('admin.hrm.employees.exit_settlement', $employee->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">রিলিজের কারণ <span class="text-danger">*</span></label>
                            <select name="exit_type" class="form-control" required>
                                <option value="resignation">স্বেচ্ছায় ইস্তফা (Resignation)</option>
                                <option value="termination">চাকরিচ্যুতি (Termination)</option>
                                <option value="retirement">অবসর গ্রহণ (Retirement)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">কার্যকর তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="effective_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">মোট ফাইনাল নিষ্পত্তিকৃত অর্থ (F&F Settlement Amount BDT) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="net_payable" class="form-control" placeholder="যেমন: 25000" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">হ্যান্ডওভার ও ক্লিয়ারেন্স নোট</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="অ্যাসেট রিটার্ন ও আইডি কার্ড জমা নিশ্চিতকরণ..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-danger">রিলিজ ও সেটেলমেন্ট সম্পন্ন করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
