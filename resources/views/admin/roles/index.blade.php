@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-shield text-success me-2"></i> এন্টারপ্রাইজ রোল ও পারমিশন কন্ট্রোল
                    </h3>
                    <p class="text-muted mb-0">Role-Based (RBAC) ও Attribute-Based (ABAC) ডাইনামিক অ্যাক্সেস কন্ট্রোল সিস্টেম</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.roles.matrix') }}" class="btn btn-primary shadow-sm">
                        <i class="fa fa-th-large me-1"></i> পারমিশন ম্যাট্রিক্স
                    </a>
                    <a href="{{ route('admin.roles.user_assignment') }}" class="btn btn-info shadow-sm">
                        <i class="fa fa-user-plus me-1"></i> ইউজার রোল অ্যাসাইন
                    </a>
                    <a href="{{ route('admin.roles.simulator') }}" class="btn btn-warning text-dark shadow-sm">
                        <i class="fa fa-eye me-1"></i> পারমিশন সিমুলেটর
                    </a>
                    <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন রোল তৈরি করুন
                    </button>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Top Metric Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body border-0 shadow-sm rounded-4" style="border-left: 4px solid #10b981 !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 fw-bold text-uppercase" style="font-size: 12px;">মোট অ্যাক্টিভ রোল</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $roles->count() }}</h3>
                            </div>
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                                <i class="fa fa-id-badge fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body border-0 shadow-sm rounded-4" style="border-left: 4px solid #3b82f6 !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 fw-bold text-uppercase" style="font-size: 12px;">রেজিস্টার্ড পারমিশন</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $totalPermissions }}</h3>
                            </div>
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                                <i class="fa fa-key fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body border-0 shadow-sm rounded-4" style="border-left: 4px solid #f59e0b !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 fw-bold text-uppercase" style="font-size: 12px;">সিস্টেম ইউজার সংখ্যা</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $totalUsers }}</h3>
                            </div>
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning">
                                <i class="fa fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body border-0 shadow-sm rounded-4" style="border-left: 4px solid #6366f1 !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 fw-bold text-uppercase" style="font-size: 12px;">অ্যাক্সেস কন্ট্রোল পলিসি</p>
                                <h4 class="fw-bold mb-0 text-dark">Default Deny</h4>
                            </div>
                            <div class="rounded-circle bg-info bg-opacity-10 p-3 text-info">
                                <i class="fa fa-lock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles Table Card -->
            <div class="box border-0 shadow-sm rounded-4">
                <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                    <h4 class="box-title fw-bold text-dark mb-0">
                        <i class="fa fa-list-alt text-success me-2"></i> সিস্টেমের সকল রোল তালিকা (Roles Directory)
                    </h4>
                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                        মোট {{ $roles->count() }} টি রোল সচল
                    </span>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th style="width: 70px;" class="text-center"># ID</th>
                                    <th>রোলের নাম (Role Name)</th>
                                    <th>রোল স্লাগ / কোড</th>
                                    <th>ক্যাটাগরি</th>
                                    <th>ডেটা স্কোপ (Scope)</th>
                                    <th class="text-center">অর্পণকৃত পারমিশন</th>
                                    <th class="text-center">ইউজার সংখ্যা</th>
                                    <th class="text-center" style="width: 220px;">অ্যাকশন (Actions)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $role->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="p-2 rounded bg-light border text-success">
                                                    <i class="fa fa-shield"></i>
                                                </div>
                                                <div>
                                                    <strong class="text-dark d-block" style="font-size: 15px;">{{ $role->name }}</strong>
                                                    @if($role->description)
                                                        <small class="text-muted">{{ Str::limit($role->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded text-primary">{{ $role->slug }}</code>
                                        </td>
                                        <td>
                                            @if($role->is_system)
                                                <span class="badge bg-secondary">System Role</span>
                                            @else
                                                <span class="badge bg-success">Custom Role</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info text-uppercase">{{ $role->data_scope ?? 'all' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary px-3 py-1 rounded-pill" style="font-size: 13px;">
                                                <i class="fa fa-key me-1"></i> {{ $role->permissions_count }} টি
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-dark px-3 py-1 rounded-pill" style="font-size: 13px;">
                                                <i class="fa fa-users me-1"></i> {{ $role->users_count }} জন
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.roles.matrix', ['role_id' => $role->id]) }}" class="btn btn-sm btn-outline-primary" title="পারমিশন ম্যাট্রিক্স কনফিগার করুন">
                                                    <i class="fa fa-key"></i> পারমিশন
                                                </a>
                                                <a href="{{ route('admin.roles.clone', $role->id) }}" class="btn btn-sm btn-outline-info" onclick="return confirm('আপনি কি এই রোলের একটি কপি তৈরি করতে চান?');" title="ক্লোন রোল">
                                                    <i class="fa fa-clone"></i>
                                                </a>
                                                @if(!$role->is_system && !in_array($role->slug, ['super_admin', 'admin']))
                                                    <a href="{{ route('admin.roles.delete', $role->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('আপনি কি নিশ্চিত যে এই রোলটি মুছে ফেলতে চান?');" title="মুছে ফেলুন">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal: Create Role -->
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold" id="createRoleModalLabel">
                    <i class="fa fa-plus-circle me-2"></i> নতুন রোল তৈরি করুন (Create Role)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">রোলের নাম (Role Name) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="যেমন: স্টোর ইন-চার্জ বা অডিট অফিসার" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ইউনিক স্লাগ / কোড (Unique Identifier)</label>
                        <input type="text" name="slug" class="form-control" placeholder="যেমন: store_incharge (খালি রাখলে স্বয়ংক্রিয় তৈরি হবে)">
                        <small class="text-muted">ইংরেজি ছোট হাতের অক্ষর ও আন্ডারস্কোর ব্যবহার করুন।</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ডেটা অ্যাক্সেস স্কোপ (Data Scope)</label>
                        <select name="data_scope" class="form-select">
                            <option value="all">All Data (সারা প্রতিষ্ঠানের সব তথ্য)</option>
                            <option value="team">Team Data (নিজের বিভাগ বা টিম)</option>
                            <option value="own">Own Data (শুধু নিজের তৈরি করা তথ্য)</option>
                            <option value="assigned">Assigned Data (তাকে অর্পণকৃত তথ্য)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">বিবরণ / দায়িত্ব (Description)</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="এই রোলের আওতাভুক্ত দায়িত্ব ও প্রশাসনিক পরিধি লিখুন..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success fw-bold px-4">
                        <i class="fa fa-save me-1"></i> রোল তৈরি ও পারমিশন সেট করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

