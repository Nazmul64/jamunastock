@extends('layouts.backend.app')

@section('title', 'লাইভ বায়োমেট্রিক পাঞ্চ স্ট্রিম')

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h3 class="font-weight-bold mb-1" style="color: #2e7d32;">
                        <i class="ti-pulse mr-2 text-danger"></i> রিয়েল-টাইম বায়োমেট্রিক পাঞ্চ স্ট্রিম (Live Events Log)
                    </h3>
                    <p class="text-muted mb-0">ডিভাইস, ইউ-ডিস্ক ও ওয়েব থেকে সংগৃহীত সরাসরি ও কাঁচা হাজিরা ইভেন্টের লগ</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill shadow-sm" onclick="location.reload();">
                        <i class="ti-reload mr-1"></i> রিফ্রেশ করুন
                    </button>
                    <a href="{{ route('admin.hrm.biometric.devices') }}" class="btn btn-outline-success btn-sm rounded-pill shadow-sm">
                        <i class="ti-server mr-1"></i> ডিভাইস সমূহ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-body py-3">
            <form action="{{ route('admin.hrm.biometric.events') }}" method="GET" class="row align-items-center">
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="small font-weight-bold text-muted mb-1">তারিখ:</label>
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="small font-weight-bold text-muted mb-1">উৎস (Source):</label>
                    <select name="source" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">-- সকল উৎস --</option>
                        <option value="device" {{ request('source') == 'device' ? 'selected' : '' }}>Biometric Device (সরাসরি সকেট/পুশ)</option>
                        <option value="usb_import" {{ request('source') == 'usb_import' ? 'selected' : '' }}>USB Disk Import</option>
                        <option value="web_punch" {{ request('source') == 'web_punch' ? 'selected' : '' }}>Web Punch (ESS পোর্টাল)</option>
                        <option value="mobile" {{ request('source') == 'mobile' ? 'selected' : '' }}>মোবাইল অ্যাপ</option>
                        <option value="manual" {{ request('source') == 'manual' ? 'selected' : '' }}>ম্যানুয়াল এন্ট্রি</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <label class="small font-weight-bold text-muted mb-1">অনুসন্ধান (নাম / UID):</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="কর্মীর নাম বা টার্মিনাল UID লিখুন..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end pt-md-4">
                    <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill">
                        <i class="ti-search mr-1"></i> ফিল্টার
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stream Table Card -->
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <h5 class="mb-0 font-weight-bold text-dark">
                <i class="ti-list text-success mr-2"></i> কাঁচা পাঞ্চ ইভেন্ট তালিকা
            </h5>
            <span class="badge badge-light px-3 py-2 text-muted border">
                মোট রেকর্ড: {{ $events->total() }} টি
            </span>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>তারিখ ও সময়</th>
                            <th>কর্মী</th>
                            <th>টার্মিনাল UID</th>
                            <th>ইভেন্ট টাইপ</th>
                            <th>যাচাইকরণ মাধ্যম</th>
                            <th>উৎস ও ডিভাইস</th>
                            <th>স্ট্যাটাস</th>
                            <th>লগ আইডি</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $ev)
                        <tr>
                            <td>
                                <div class="font-weight-bold text-dark">{{ date('d M, Y', strtotime($ev->event_time)) }}</div>
                                <span class="badge badge-light text-primary font-monospace" style="font-size: 13px;">
                                    <i class="ti-time mr-1"></i> {{ date('h:i:s A', strtotime($ev->event_time)) }}
                                </span>
                            </td>
                            <td>
                                @if($ev->user)
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">
                                            <img src="{{ $ev->user->avatar ? asset('upload/user_images/'.$ev->user->avatar) : asset('frontend/images/default-user.png') }}" 
                                                 alt="{{ $ev->user->name }}" 
                                                 class="rounded-circle shadow-sm" 
                                                 style="width: 36px; height: 36px; object-fit: cover;"
                                                 onerror="this.src='{{ asset('frontend/images/default-user.png') }}'">
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark">{{ $ev->user->name }}</div>
                                            <small class="text-muted">{{ optional($ev->user->hrmProfile)->employee_code ?: 'EMP-'.$ev->user->id }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-danger font-weight-bold">
                                        <i class="ti-alert mr-1"></i> অমিল কর্মী (UID: {{ $ev->device_user_id }})
                                    </span>
                                @endif
                            </td>
                            <td>
                                <code class="px-2 py-1 bg-light rounded text-dark font-weight-bold">
                                    {{ $ev->device_user_id }}
                                </code>
                            </td>
                            <td>
                                @if($ev->event_type == 'in')
                                    <span class="badge badge-success px-2 py-1"><i class="ti-arrow-right mr-1"></i> চেক-ইন (IN)</span>
                                @elseif($ev->event_type == 'out')
                                    <span class="badge badge-info px-2 py-1"><i class="ti-arrow-left mr-1"></i> চেক-আউট (OUT)</span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1">Auto-Detect</span>
                                @endif
                            </td>
                            <td>
                                @if($ev->verification_method == 'fingerprint')
                                    <span class="badge badge-light border text-dark"><i class="ti-hand-point-up text-success mr-1"></i> Fingerprint</span>
                                @elseif($ev->verification_method == 'face')
                                    <span class="badge badge-light border text-dark"><i class="ti-eye text-primary mr-1"></i> Face ID</span>
                                @elseif($ev->verification_method == 'card')
                                    <span class="badge badge-light border text-dark"><i class="ti-credit-card text-warning mr-1"></i> RFID Card</span>
                                @elseif($ev->verification_method == 'web_punch')
                                    <span class="badge badge-light border text-primary"><i class="ti-desktop text-info mr-1"></i> Web Punch</span>
                                @else
                                    <span class="badge badge-light border text-muted">{{ $ev->verification_method ?: 'Standard' }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark">
                                    @if($ev->source == 'device')
                                        <span class="badge badge-success mr-1">Terminal</span>
                                    @elseif($ev->source == 'usb_import')
                                        <span class="badge badge-warning mr-1">USB U-Disk</span>
                                    @elseif($ev->source == 'web_punch')
                                        <span class="badge badge-info mr-1">ESS Web</span>
                                    @else
                                        <span class="badge badge-secondary mr-1">{{ $ev->source }}</span>
                                    @endif
                                    {{ $ev->device ? $ev->device->device_name : 'Direct Socket' }}
                                </div>
                                @if($ev->ip_address)
                                    <small class="text-muted font-monospace">{{ $ev->ip_address }}</small>
                                @endif
                            </td>
                            <td>
                                @if($ev->is_processed)
                                    <span class="badge badge-success px-2 py-1"><i class="ti-check mr-1"></i> Processed</span>
                                @else
                                    <span class="badge badge-warning px-2 py-1">Pending</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted font-monospace">{{ Str::limit($ev->raw_log_id, 14) }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="ti-pulse d-block mb-2 text-muted" style="font-size: 34px;"></i>
                                কোনো লাইভ পাঞ্চ ইভেন্ট পাওয়া যায়নি।
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $events->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
