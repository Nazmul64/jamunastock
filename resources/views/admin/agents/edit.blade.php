@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="fa fa-pencil me-2 text-warning"></i> এজেন্ট প্রোফাইল সম্পাদনা (Edit Agent)</h4>
                    <p class="subtitle text-muted mb-0">এজেন্ট: <strong class="text-dark">{{ $agent->name }}</strong> (ID: {{ $agent->agent_id ?: ('AGT-'.$agent->id) }})</p>
                </div>
                <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> এজেন্ট তালিকায় ফিরুন
                </a>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.agents.update', $agent->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Left Column: Avatar Box -->
                    <div class="col-xl-3 col-lg-4 mb-4">
                        <div class="box shadow-sm text-center">
                            <div class="box-header with-border bg-light">
                                <h5 class="box-title mb-0">প্রোফাইল ছবি (Avatar)</h5>
                            </div>
                            <div class="box-body py-4">
                                <div class="avatar-preview-box mx-auto mb-3" style="width: 170px; height: 170px; border-radius: 16px; border: 2px dashed #cbd5e1; background: #f8fafc; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                                    <img id="avatarPreviewImg" src="{{ $agent->avatarUrl() }}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>

                                <label for="avatarInput" class="btn btn-outline-primary btn-sm px-4 fw-bold mb-2 cursor-pointer">
                                    <i class="fa fa-upload me-1"></i> ছবি পরিবর্তন করুন
                                </label>
                                <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewAvatar(this)">
                                <small class="text-muted d-block fs-11">JPG, JPEG, PNG, WEBP (সর্বোচ্চ 3MB)</small>
                            </div>
                        </div>

                        <div class="box shadow-sm">
                            <div class="box-body p-3">
                                <span class="text-muted fs-12 d-block">অ্যাসাইন করা পয়েন্ট:</span>
                                <h5 class="fw-bold text-primary mb-1">{{ $agent->agentPoint ? $agent->agentPoint->name : 'নির্ধারিত নেই' }}</h5>
                                <span class="badge bg-secondary font-monospace">{{ $agent->agentPoint ? $agent->agentPoint->code : '' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Agent Form Fields -->
                    <div class="col-xl-9 col-lg-8">
                        <div class="box shadow-sm">
                            <div class="box-header with-border bg-light">
                                <h5 class="box-title mb-0"><i class="fa fa-address-card text-warning me-2"></i> এজেন্টের তথ্য আপডেট</h5>
                            </div>
                            <div class="box-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" value="{{ old('first_name', $agent->first_name ?: $agent->name) }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Last Name</label>
                                        <input type="text" name="last_name" value="{{ old('last_name', $agent->last_name) }}" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">এজেন্ট আইডি <span class="text-danger">*</span></label>
                                        <input type="text" name="agent_id" value="{{ old('agent_id', $agent->agent_id ?: ('AGT-'.$agent->id)) }}" class="form-control font-monospace" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">দায়িত্বপ্রাপ্ত এজেন্ট পয়েন্ট <span class="text-danger">*</span></label>
                                        <select name="agent_point_id" class="form-select" required>
                                            @foreach($agentPoints as $pt)
                                                <option value="{{ $pt->id }}" {{ old('agent_point_id', $agent->agent_point_id) == $pt->id ? 'selected' : '' }}>
                                                    {{ $pt->name }} ({{ $pt->code ?: ('AP-'.$pt->id) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">এলাকা (Area)</label>
                                        <select name="area_id" class="form-select">
                                            <option value="">-- এলাকা নির্বাচন করুন --</option>
                                            @foreach($areas as $ar)
                                                <option value="{{ $ar->id }}" {{ old('area_id', $agent->area_id) == $ar->id ? 'selected' : '' }}>
                                                    {{ $ar->name }} ({{ $ar->district }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">জেলা (District)</label>
                                        <input type="text" name="district" value="{{ old('district', $agent->district) }}" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">উপজেলা (Upazila)</label>
                                        <input type="text" name="upazila" value="{{ old('upazila', $agent->upazila) }}" class="form-control">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">লগইন ইমেইল <span class="text-danger">*</span></label>
                                        <input type="email" name="email" value="{{ old('email', $agent->email) }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" value="{{ old('phone', $agent->phone) }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">শাখা (Branch)</label>
                                        <input type="text" name="branch" value="{{ old('branch', $agent->branch ?: 'Main Branch') }}" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">নতুন পাসওয়ার্ড (ঐচ্ছিক)</label>
                                        <input type="password" name="password" class="form-control" placeholder="পরিবর্তন না করতে চাইলে খালি রাখুন">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">কনফার্ম পাসওয়ার্ড</label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="নতুন পাসওয়ার্ড নিশ্চিত করুন">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">যোগদানের তারিখ</label>
                                        <input type="date" name="joining_date" value="{{ old('joining_date', $agent->joining_date) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">স্ট্যাটাস <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select" required>
                                            <option value="active" {{ old('status', $agent->status) === 'active' ? 'selected' : '' }}>Active (সক্রিয়)</option>
                                            <option value="inactive" {{ old('status', $agent->status) === 'inactive' ? 'selected' : '' }}>Inactive (নিষ্ক্রিয়)</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">বর্তমান ঠিকানা</label>
                                        <textarea name="present_address" class="form-control" rows="2">{{ old('present_address', $agent->present_address) }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">মন্তব্য (Remarks)</label>
                                        <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $agent->remarks) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer bg-light d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.agents.index') }}" class="btn btn-light border px-4">বাতিল</a>
                                <button type="submit" class="btn btn-warning fw-bold px-4">
                                    <i class="fa fa-save me-1"></i> তথ্য সংরক্ষণ করুন
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreviewImg').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
