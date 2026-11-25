@extends('layouts.backend.app')

@section('title', 'বায়োমেট্রিক ডিভাইস ব্যবস্থাপনা')

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h3 class="font-weight-bold mb-1" style="color: #2e7d32;">
                        <i class="ti-server mr-2"></i> বায়োমেট্রিক ও ফেস রিকগনিশন ডিভাইস (Biometric Terminals)
                    </h3>
                    <p class="text-muted mb-0">ZKTeco, Suprema, uAttend ইত্যাদি ফিঙ্গারপ্রিন্ট ও ফেস টার্মিনাল ইন্টিগ্রেশন ও রিয়েল-টাইম সিঙ্ক</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill shadow-sm" data-toggle="modal" data-target="#usbUploadModal" data-bs-toggle="modal" data-bs-target="#usbUploadModal">
                        <i class="ti-upload mr-1"></i> USB পেনড্রাইভ লগ আপলোড
                    </button>
                    <a href="{{ route('admin.hrm.biometric.events') }}" class="btn btn-outline-info btn-sm rounded-pill shadow-sm">
                        <i class="ti-pulse mr-1"></i> লাইভ পাঞ্চ স্ট্রিম
                    </a>
                    <button type="button" class="btn btn-success btn-sm rounded-pill shadow-sm" data-toggle="modal" data-target="#addDeviceModal" data-bs-toggle="modal" data-bs-target="#addDeviceModal">
                        <i class="ti-plus mr-1"></i> নতুন টার্মিনাল যুক্ত করুন
                    </button>
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

    <!-- Metrics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm rounded-lg" style="background: linear-gradient(135deg, #1b5e20, #2e7d32); color: #fff;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-white-50 small font-weight-bold">মোট টার্মিনাল</p>
                            <h2 class="mb-0 font-weight-bold text-white">{{ $totalDevices }}</h2>
                            <small class="text-white-50">কানেক্টেড হার্ডওয়্যার</small>
                        </div>
                        <div class="bg-white rounded-circle p-3 shadow-sm" style="width: 54px; height: 54px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti-desktop text-success" style="font-size: 22px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm rounded-lg" style="background: linear-gradient(135deg, #0277bd, #0288d1); color: #fff;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-white-50 small font-weight-bold">অনলাইন ডিভাইস</p>
                            <h2 class="mb-0 font-weight-bold text-white">
                                {{ $onlineDevices }}
                                <span class="badge badge-light text-success font-weight-normal ml-1" style="font-size: 11px;">
                                    <span class="spinner-grow spinner-grow-sm text-success" style="width: 8px; height: 8px;" role="status"></span> Active
                                </span>
                            </h2>
                            <small class="text-white-50">সরাসরি সংযুক্ত ও সচল</small>
                        </div>
                        <div class="bg-white rounded-circle p-3 shadow-sm" style="width: 54px; height: 54px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti-signal text-primary" style="font-size: 22px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm rounded-lg" style="background: linear-gradient(135deg, #d84315, #f4511e); color: #fff;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-white-50 small font-weight-bold">অফলাইন ডিভাইস</p>
                            <h2 class="mb-0 font-weight-bold text-white">{{ $offlineDevices }}</h2>
                            <small class="text-white-50">নেটওয়ার্ক বিচ্ছিন্ন বা বন্ধ</small>
                        </div>
                        <div class="bg-white rounded-circle p-3 shadow-sm" style="width: 54px; height: 54px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti-unlink text-danger" style="font-size: 22px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm rounded-lg" style="background: linear-gradient(135deg, #4527a0, #5e35b1); color: #fff;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-white-50 small font-weight-bold">মোট পাঞ্চ ইভেন্ট</p>
                            <h2 class="mb-0 font-weight-bold text-white">{{ number_format($totalEvents) }}</h2>
                            <small class="text-white-50">ক্যাপচার্ড বায়োমেট্রিক ডাটা</small>
                        </div>
                        <div class="bg-white rounded-circle p-3 shadow-sm" style="width: 54px; height: 54px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti-hand-point-up text-purple" style="font-size: 22px; color: #5e35b1;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Devices Table Card -->
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <h5 class="mb-0 font-weight-bold text-dark">
                <i class="ti-view-list text-success mr-2"></i> রেজিস্টার্ড বায়োমেট্রিক টার্মিনাল তালিকা
            </h5>
            <span class="badge badge-light px-3 py-2 text-muted border">
                মোট ডিভাইস: {{ $devices->count() }} টি
            </span>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>টার্মিনাল নাম ও মডেল</th>
                            <th>সিরিয়াল নম্বর</th>
                            <th>কানেকশন টাইপ</th>
                            <th>আইপি ও পোর্ট</th>
                            <th>ব্রাঞ্চ ও লোকেশন</th>
                            <th>স্ট্যাটাস</th>
                            <th>সর্বশেষ সিঙ্ক</th>
                            <th class="text-right">একশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($devices as $dev)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm rounded p-2 mr-2 bg-light text-success font-weight-bold" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="ti-hardware" style="font-size: 18px;"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark">{{ $dev->device_name }}</div>
                                        <small class="text-muted">{{ $dev->device_model ?: 'Universal Terminal' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code>{{ $dev->serial_number }}</code>
                            </td>
                            <td>
                                @if($dev->connection_type == 'tcp_ip')
                                    <span class="badge badge-primary px-2 py-1"><i class="ti-cloud-down mr-1"></i> TCP/IP LAN</span>
                                @elseif($dev->connection_type == 'cloud_push')
                                    <span class="badge badge-info px-2 py-1"><i class="ti-rss mr-1"></i> Cloud Push API</span>
                                @elseif($dev->connection_type == 'usb_disk')
                                    <span class="badge badge-warning px-2 py-1"><i class="ti-harddrive mr-1"></i> USB U-Disk</span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1">SDK Socket</span>
                                @endif
                            </td>
                            <td>
                                @if($dev->ip_address)
                                    <span class="text-dark font-monospace">{{ $dev->ip_address }}:{{ $dev->port ?: '4370' }}</span>
                                @else
                                    <span class="text-muted">N/A (Offline/USB)</span>
                                @endif
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark">{{ $dev->branch ? $dev->branch->branch_name : 'প্রধান কার্যালয়' }}</div>
                                <small class="text-muted">{{ $dev->device_location ?: 'মেইন গেট' }}</small>
                            </td>
                            <td>
                                @if($dev->status == 'online')
                                    <span class="badge badge-success px-2 py-1">
                                        <i class="fa fa-circle text-white mr-1" style="font-size: 8px;"></i> Online
                                    </span>
                                @elseif($dev->status == 'sync_pending')
                                    <span class="badge badge-warning px-2 py-1">Sync Pending</span>
                                @else
                                    <span class="badge badge-danger px-2 py-1">Offline</span>
                                @endif
                            </td>
                            <td>
                                @if($dev->last_synced_at)
                                    <span class="text-dark small font-weight-bold">{{ \Carbon\Carbon::parse($dev->last_synced_at)->diffForHumans() }}</span>
                                    <br><small class="text-muted">{{ date('d M, h:i A', strtotime($dev->last_synced_at)) }}</small>
                                @else
                                    <span class="text-muted small">কখনও সিঙ্ক হয়নি</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="btn-group" role="group">
                                    <!-- Ping / Socket Test -->
                                    <form action="{{ route('admin.hrm.biometric.devices.ping', $dev->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-info btn-xs py-1 px-2" title="পিংক টেস্ট (Ping Device)">
                                            <i class="ti-signal"></i> পিং
                                        </button>
                                    </form>

                                    <!-- Sync Now -->
                                    <form action="{{ route('admin.hrm.biometric.devices.sync', $dev->id) }}" method="POST" class="d-inline ml-1">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success btn-xs py-1 px-2" title="সরাসরি সিঙ্ক করুন (Fetch Logs)">
                                            <i class="ti-reload"></i> সিঙ্ক
                                        </button>
                                    </form>

                                    <!-- Edit Trigger -->
                                    <button type="button" class="btn btn-outline-primary btn-xs py-1 px-2 ml-1" data-toggle="modal" data-target="#editModal{{ $dev->id }}" data-bs-toggle="modal" data-bs-target="#editModal{{ $dev->id }}">
                                        <i class="ti-pencil"></i>
                                    </button>

                                    <!-- Delete Trigger -->
                                    <form action="{{ route('admin.hrm.biometric.devices.destroy', $dev->id) }}" method="POST" class="d-inline ml-1" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই ডিভাইসটি ডিলিট করতে চান?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-xs py-1 px-2">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Edit Device Modal -->
                                <div class="modal fade text-left" id="editModal{{ $dev->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title font-weight-bold">টার্মিনাল সম্পাদনা: {{ $dev->device_name }}</h5>
                                                <button type="button" class="btn-close btn-close-white text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: 0; font-size: 20px;">&times;</button>
                                            </div>
                                            <form action="{{ route('admin.hrm.biometric.devices.update', $dev->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group mb-3">
                                                        <label class="font-weight-bold">ডিভাইস নাম <span class="text-danger">*</span></label>
                                                        <input type="text" name="device_name" class="form-control" value="{{ $dev->device_name }}" required>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="font-weight-bold">সিরিয়াল নম্বর <span class="text-danger">*</span></label>
                                                            <input type="text" name="serial_number" class="form-control" value="{{ $dev->serial_number }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="font-weight-bold">মডেল</label>
                                                            <input type="text" name="device_model" class="form-control" value="{{ $dev->device_model }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-8">
                                                            <label class="font-weight-bold">আইপি এড্রেস</label>
                                                            <input type="text" name="ip_address" class="form-control" value="{{ $dev->ip_address }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="font-weight-bold">পোর্ট</label>
                                                            <input type="number" name="port" class="form-control" value="{{ $dev->port ?: 4370 }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="font-weight-bold">কানেকশন টাইপ</label>
                                                            <select name="connection_type" class="form-control">
                                                                <option value="tcp_ip" {{ $dev->connection_type == 'tcp_ip' ? 'selected' : '' }}>TCP/IP LAN Socket</option>
                                                                <option value="cloud_push" {{ $dev->connection_type == 'cloud_push' ? 'selected' : '' }}>Cloud Push REST API</option>
                                                                <option value="usb_disk" {{ $dev->connection_type == 'usb_disk' ? 'selected' : '' }}>USB U-Disk Import</option>
                                                                <option value="sdk" {{ $dev->connection_type == 'sdk' ? 'selected' : '' }}>SDK Integration</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="font-weight-bold">স্ট্যাটাস</label>
                                                            <select name="status" class="form-control">
                                                                <option value="online" {{ $dev->status == 'online' ? 'selected' : '' }}>অনলাইন (Online)</option>
                                                                <option value="offline" {{ $dev->status == 'offline' ? 'selected' : '' }}>অফলাইন (Offline)</option>
                                                                <option value="disabled" {{ $dev->status == 'disabled' ? 'selected' : '' }}>নিষ্ক্রিয় (Disabled)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="font-weight-bold">ব্রাঞ্চ</label>
                                                            <select name="branch_id" class="form-control">
                                                                <option value="">-- প্রধান কার্যালয় --</option>
                                                                @foreach($branches as $b)
                                                                    <option value="{{ $b->id }}" {{ $dev->branch_id == $b->id ? 'selected' : '' }}>{{ $b->branch_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="font-weight-bold">লোকেশন</label>
                                                            <input type="text" name="device_location" class="form-control" value="{{ $dev->device_location }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-dismiss="modal" data-bs-dismiss="modal">বন্ধ করুন</button>
                                                    <button type="submit" class="btn btn-primary">আপডেট সংরক্ষণ করুন</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="ti-server d-block mb-2" style="font-size: 38px; color: #ccc;"></i>
                                কোনো বায়োমেট্রিক ডিভাইস এখনো যুক্ত করা হয়নি।<br>
                                <button type="button" class="btn btn-success btn-sm mt-3 rounded-pill" data-toggle="modal" data-target="#addDeviceModal" data-bs-toggle="modal" data-bs-target="#addDeviceModal">
                                    <i class="ti-plus mr-1"></i> প্রথম টার্মিনাল যোগ করুন
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Cloud Push Integration API Info Accordion/Card -->
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-header bg-white py-3 border-0">
            <h6 class="mb-0 font-weight-bold text-dark">
                <i class="ti-world text-info mr-2"></i> রিয়েল-টাইম ক্লাউড পুশ ইন্টিগ্রেশন নির্দেশিকা (Cloud Push REST API Docs)
            </h6>
        </div>
        <div class="card-body pt-0">
            <p class="text-muted small mb-3">যেকোনো আধুনিক বায়োমেট্রিক ডিভাইস (ZKTeco BioTime, ADMS, Suprema, eSSL ইত্যাদি) থেকে সরাসরি সার্ভারে লাইভ হাজিরা পুশ করার জন্য নিচের REST API এন্ডপয়েন্ট ব্যবহার করুন:</p>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold small text-muted">API পুশ এন্ডপয়েন্ট (Endpoint):</label>
                    <div class="p-2 bg-light rounded font-monospace small border">
                        <span class="badge badge-success mr-2">POST</span> {{ url('/api/biometric/attendance-events') }}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold small text-muted">ডিভাইস হার্টবিট এন্ডপয়েন্ট (Heartbeat Ping):</label>
                    <div class="p-2 bg-light rounded font-monospace small border">
                        <span class="badge badge-info mr-2">POST</span> {{ url('/api/biometric/heartbeat') }}
                    </div>
                </div>
                <div class="col-12">
                    <label class="font-weight-bold small text-muted">নমুনা JSON রিকোয়েস্ট পেলোড (Sample Payload):</label>
                    <pre class="bg-dark text-light p-3 rounded small mb-0" style="font-family: monospace;">
{
    "device_sn": "ZK-TERMINAL-01",
    "events": [
        {
            "device_user_id": "1001",
            "event_time": "2026-11-23 09:05:12",
            "event_type": "in",
            "verification_method": "fingerprint",
            "raw_log_id": "LOG_98410294"
        }
    ]
}
                    </pre>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Add New Device -->
<div class="modal fade" id="addDeviceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold"><i class="ti-plus mr-1"></i> নতুন বায়োমেট্রিক টার্মিনাল যোগ করুন</h5>
                <button type="button" class="btn-close btn-close-white text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: 0; font-size: 20px;">&times;</button>
            </div>
            <form action="{{ route('admin.hrm.biometric.devices.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">টার্মিনাল নাম <span class="text-danger">*</span></label>
                        <input type="text" name="device_name" class="form-control" placeholder="যেমন: Main Entrance Fingerprint Terminal" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">সিরিয়াল নম্বর <span class="text-danger">*</span></label>
                            <input type="text" name="serial_number" class="form-control" placeholder="যেমন: ZK-984029-BD" required>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">মডেল নাম</label>
                            <input type="text" name="device_model" class="form-control" placeholder="যেমন: ZKTeco K40 / Face Station">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="font-weight-bold">আইপি এড্রেস (LAN/Static IP)</label>
                            <input type="text" name="ip_address" class="form-control" placeholder="যেমন: 192.168.1.201">
                        </div>
                        <div class="col-md-4">
                            <label class="font-weight-bold">পোর্ট (Default: 4370)</label>
                            <input type="number" name="port" class="form-control" value="4370">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">কানেকশন টাইপ <span class="text-danger">*</span></label>
                            <select name="connection_type" class="form-control" required>
                                <option value="tcp_ip">TCP/IP LAN (সরাসরি সকেট)</option>
                                <option value="cloud_push">Cloud Push REST API (ADMS)</option>
                                <option value="usb_disk">USB U-Disk (পেনড্রাইভ)</option>
                                <option value="sdk">SDK Integration</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">ব্রাঞ্চ</label>
                            <select name="branch_id" class="form-control">
                                <option value="">-- প্রধান কার্যালয় --</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}">{{ $b->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">টার্মিনাল লোকেশন / রুম</label>
                        <input type="text" name="device_location" class="form-control" placeholder="যেমন: গ্রাউন্ড ফ্লোর, সিকিউরিটি গেট-১">
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">মন্তব্য / বিবরণ</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="ডিভাইস সংক্রান্ত অন্যান্য তথ্য..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success">ডিভাইস সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Upload USB U-Disk Attendance Log -->
<div class="modal fade" id="usbUploadModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="ti-upload mr-1"></i> USB পেনড্রাইভ লগ ফাইল আপলোড</h5>
                <button type="button" class="btn-close btn-close-white text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: 0; font-size: 20px;">&times;</button>
            </div>
            <form action="{{ route('admin.hrm.biometric.devices.upload_usb') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small">
                        অফলাইন বায়োমেট্রিক ডিভাইস থেকে পেনড্রাইভে এক্সপোর্ট করা <code>attlog.dat</code>, <code>.csv</code> বা <code>.txt</code> ফাইল নির্বাচন করুন। সিস্টেম স্বয়ংক্রিয়ভাবে ইন/আউট হিসাব করে ডাটাবেজে রেকর্ড করবে।
                    </p>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">টার্মিনাল নির্বাচন করুন (ঐচ্ছিক)</label>
                        <select name="device_id" class="form-control">
                            <option value="">-- স্বয়ংক্রিয় শনাক্তকরণ --</option>
                            @foreach($devices as $dev)
                                <option value="{{ $dev->id }}">{{ $dev->device_name }} ({{ $dev->serial_number }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">লগ ফাইল (.dat, .csv, .txt) <span class="text-danger">*</span></label>
                        <input type="file" name="log_file" class="form-control" accept=".dat,.csv,.txt" required>
                    </div>
                    <div class="alert alert-light border small text-muted">
                        <i class="ti-info-alt text-primary mr-1"></i>
                        ফরম্যাট: <code>User_ID &nbsp; Date_Time &nbsp; State</code> (যেমন: 1001 &nbsp; 2026-11-23 09:12:00 &nbsp; 0)
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary">লগ প্রসেস ও আপলোড করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
