@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="fa fa-user-plus me-2 text-primary"></i> নতুন এজেন্ট তৈরি করুন (Create New Agent)</h4>
                    <p class="subtitle text-muted mb-0">এজেন্ট পয়েন্টের দায়িত্বপ্রাপ্ত অফিসারের প্রোফাইল, পয়েন্ট অ্যাসাইন ও লগইন ক্রেডেনশিয়াল সেট করুন</p>
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
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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

            <form action="{{ route('admin.agents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Left Column: Avatar Upload Box (Matching W3CMS / AGAdmin style) -->
                    <div class="col-xl-3 col-lg-4 mb-4">
                        <div class="box shadow-sm text-center">
                            <div class="box-header with-border bg-light">
                                <h5 class="box-title mb-0">প্রোফাইল ছবি (Avatar)</h5>
                            </div>
                            <div class="box-body py-4">
                                <div class="avatar-preview-box mx-auto mb-3" style="width: 170px; height: 170px; border-radius: 16px; border: 2px dashed #cbd5e1; background: #f8fafc; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                                    <img id="avatarPreviewImg" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                    <div id="noImagePlaceholder" class="text-muted text-center p-3">
                                        <i class="fa fa-image fs-40 text-secondary mb-2 d-block"></i>
                                        <span class="fs-13 fw-semibold">No Image</span>
                                    </div>
                                </div>

                                <label for="avatarInput" class="btn btn-outline-primary btn-sm px-4 fw-bold mb-2 cursor-pointer">
                                    <i class="fa fa-upload me-1"></i> ছবি আপলোড করুন
                                </label>
                                <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewAvatar(this)">
                                <small class="text-muted d-block fs-11">JPG, JPEG, PNG, WEBP (সর্বোচ্চ 3MB)</small>
                            </div>
                        </div>

                        <!-- Assignment Help Card -->
                        <div class="box shadow-sm bg-light-primary border-primary border">
                            <div class="box-body">
                                <h6 class="fw-bold text-primary mb-2"><i class="fa fa-shield me-1"></i> সিস্টেম অ্যাক্সেস নোট</h6>
                                <p class="text-muted small mb-0">
                                    এজেন্ট অ্যাকাউন্ট তৈরি হওয়ার পর এজেন্ট সরাসরি <code>http://127.0.0.1:8000/agent</code> এ লগইন করতে পারবেন। তিনি শুধুমাত্র তার অ্যাসাইন করা পয়েন্টের তথ্য ও বাজার ডেলিভারি দেখতে পারবেন।
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Agent Form Fields -->
                    <div class="col-xl-9 col-lg-8">
                        <div class="box shadow-sm">
                            <div class="box-header with-border bg-light">
                                <h5 class="box-title mb-0"><i class="fa fa-address-card text-primary me-2"></i> এজেন্টের মূল তথ্য ও দায়িত্ব নির্ধারণ</h5>
                            </div>
                            <div class="box-body">
                                <div class="row g-3">
                                    <!-- Name Fields -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">First Name (নামের প্রথম অংশ) <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" placeholder="যেমন: মো: রহিম" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Last Name (নামের শেষ অংশ)</label>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" placeholder="যেমন: ইসলাম">
                                    </div>

                                    <!-- Agent ID & Assigned Point -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">এজেন্ট আইডি (Agent ID) <span class="text-danger">*</span></label>
                                        <input type="text" name="agent_id" value="{{ old('agent_id', $autoAgentId) }}" class="form-control font-monospace" required>
                                        <small class="text-muted">স্বয়ংক্রিয়ভাবে তৈরি হয়েছে, প্রয়োজন হলে পরিবর্তন করতে পারেন</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">দায়িত্বপ্রাপ্ত এজেন্ট পয়েন্ট <span class="text-danger">*</span></label>
                                        <select name="agent_point_id" class="form-select" required id="agentPointSelector" onchange="syncAreaDetails(this)">
                                            <option value="">-- এজেন্ট পয়েন্ট নির্বাচন করুন --</option>
                                            @foreach($agentPoints as $pt)
                                                <option value="{{ $pt->id }}" 
                                                        data-area-id="{{ $pt->area_id }}"
                                                        data-district="{{ $pt->areaModel ? $pt->areaModel->district : '' }}"
                                                        data-upazila="{{ $pt->areaModel ? $pt->areaModel->upazila : '' }}"
                                                        {{ old('agent_point_id') == $pt->id ? 'selected' : '' }}>
                                                    {{ $pt->name }} ({{ $pt->code ?: ('AP-'.$pt->id) }}) - {{ $pt->areaModel ? $pt->areaModel->name : ($pt->area ?: 'N/A') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Area & Branch -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">এলাকা (Area)</label>
                                        <select name="area_id" id="areaSelect" class="form-select">
                                            <option value="">-- এলাকা নির্বাচন করুন --</option>
                                            @foreach($areas as $ar)
                                                <option value="{{ $ar->id }}" {{ old('area_id') == $ar->id ? 'selected' : '' }}>
                                                    {{ $ar->name }} ({{ $ar->district }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">জেলা (District)</label>
                                        <input type="text" name="district" id="districtInput" value="{{ old('district') }}" class="form-control" placeholder="যেমন: নওগাঁ">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">উপজেলা (Upazila)</label>
                                        <input type="text" name="upazila" id="upazilaInput" value="{{ old('upazila') }}" class="form-control" placeholder="যেমন: সদর">
                                    </div>

                                    <!-- Contact & Branch -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">লগইন ইমেইল (Login Email) <span class="text-danger">*</span></label>
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="agent@ekrishisheba.com" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">মোবাইল নম্বর (Phone) <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="017XXXXXXXX" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">শাখা (Branch)</label>
                                        <input type="text" name="branch" value="{{ old('branch', 'Main Branch') }}" class="form-control" placeholder="যেমন: নওগাঁ শাখা">
                                    </div>

                                    <!-- Password Fields -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">লগইন পাসওয়ার্ড (Password) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="passInput" class="form-control" placeholder="নূন্যতম ৬ অক্ষরের পাসওয়ার্ড" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('passInput', this)">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">কনফার্ম পাসওয়ার্ড (Confirm Password) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation" id="passConfirmInput" class="form-control" placeholder="পাসওয়ার্ড পুনরায় লিখুন" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('passConfirmInput', this)">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Joining Date & Status -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">যোগদানের তারিখ (Joining Date)</label>
                                        <input type="date" name="joining_date" value="{{ old('joining_date', date('Y-m-d')) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">একাউন্ট স্ট্যাটাস <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select" required>
                                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active (সক্রিয়)</option>
                                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive (নিষ্ক্রিয়)</option>
                                        </select>
                                    </div>

                                    <!-- Addresses & Remarks -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">বর্তমান ঠিকানা (Full Address)</label>
                                        <textarea name="present_address" class="form-control" rows="2" placeholder="এজেন্টের বাসস্থান বা অফিসের বিস্তারিত ঠিকানা">{{ old('present_address') }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">মন্তব্য / অতিরিক্ত তথ্য (Remarks)</label>
                                        <textarea name="remarks" class="form-control" rows="2" placeholder="কোনো বিশেষ তথ্য বা নোট">{{ old('remarks') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer bg-light d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.agents.index') }}" class="btn btn-light border px-4">বাতিল (Cancel)</a>
                                <button type="submit" class="btn btn-primary fw-bold px-4">
                                    <i class="fa fa-save me-1"></i> এজেন্ট একাউন্ট তৈরি করুন
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
            document.getElementById('avatarPreviewImg').style.display = 'block';
            document.getElementById('noImagePlaceholder').style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fa fa-eye';
    }
}

function syncAreaDetails(select) {
    const option = select.options[select.selectedIndex];
    if (option && option.dataset) {
        if (option.dataset.areaId) {
            document.getElementById('areaSelect').value = option.dataset.areaId;
        }
        if (option.dataset.district) {
            document.getElementById('districtInput').value = option.dataset.district;
        }
        if (option.dataset.upazila) {
            document.getElementById('upazilaInput').value = option.dataset.upazila;
        }
    }
}
</script>
@endsection
