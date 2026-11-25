@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-users text-primary me-2"></i> ব্যবহারকারী রোল ও এক্সেস অ্যাসাইনমেন্ট
                    </h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">রোল ব্যবস্থাপনা</a></li>
                                <li class="breadcrumb-item active" aria-current="page">ইউজার অ্যাসাইনমেন্ট</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-success rounded-pill px-3 shadow-sm" data-toggle="modal" data-target="#createUserModal" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="fa fa-user-plus me-1"></i> নতুন ইউজার / কর্মকর্তা তৈরি করুন
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-list me-1"></i> রোল তালিকা
                    </a>
                    <a href="{{ route('admin.roles.matrix') }}" class="btn btn-outline-primary rounded-pill px-3">
                        <i class="fa fa-th me-1"></i> পারমিশন ম্যাট্রিক্স
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-check-circle fs-3 me-3 text-success"></i>
                        <div>
                            <strong class="d-block">সফল হয়েছে!</strong>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-exclamation-triangle fs-3 me-3 text-danger"></i>
                        <div>
                            <strong class="d-block">সতর্কবার্তা!</strong>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Info Guide Banner -->
            <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #eff6ff, #f8fafc); border-left: 4px solid #3b82f6 !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-info-circle text-primary fs-2 me-3"></i>
                        <div>
                            <strong class="text-dark">ইউজার ও রোল সিস্টেমের নির্দেশিকা:</strong>
                            <p class="mb-0 text-muted small">
                                যেকোনো নতুন কর্মকর্তা, কর্মচারী, ম্যানেজার বা সাপোর্ট এজেন্টের জন্য <strong>"নতুন ইউজার / কর্মকর্তা তৈরি করুন"</strong> বাটনে ক্লিক করে ইমেইল, পাসওয়ার্ড ও কাঙ্ক্ষিত রোল নির্ধারণ করুন। ওই কর্মী যখন তার ইমেইল ও পাসওয়ার্ড দিয়ে লগইন করবেন, তখন তার রোল-এর পারমিশন অনুযায়ী শুধুমাত্র অনুমোদিত মেনু ও অপশনগুলো তার ড্যাশবোর্ডে দৃশ্যমান হবে।
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Card -->
            <div class="box rounded-4 shadow-sm border-0 mb-4">
                <div class="box-body p-4">
                    <form method="GET" action="{{ route('admin.roles.user_assignment') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-5 col-md-6 col-12">
                                <label class="form-label fw-bold text-dark fs-14">🔍 নাম বা ইমেইল দিয়ে খুঁজুন:</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fa fa-search text-muted"></i></span>
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 rounded-end-pill" placeholder="ব্যবহারকারীর নাম, ইমেইল বা রোল...">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12">
                                <label class="form-label fw-bold text-dark fs-14">রোল অনুযায়ী ফিল্টার:</label>
                                <select name="role" class="form-select rounded-pill">
                                    <option value="">-- সকল রোল --</option>
                                    @foreach($roles as $r)
                                        <option value="{{ $r->slug }}" {{ request('role') == $r->slug ? 'selected' : '' }}>{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-2 col-12 d-flex gap-2">
                                <button type="submit" class="btn btn-primary rounded-pill px-4 flex-grow-1">
                                    <i class="fa fa-filter me-1"></i> ফিল্টার
                                </button>
                                @if(request()->hasAny(['search', 'role']))
                                    <a href="{{ route('admin.roles.user_assignment') }}" class="btn btn-outline-secondary rounded-pill px-3">
                                        <i class="fa fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Table Card -->
            <div class="box rounded-4 shadow-sm border-0">
                <div class="box-header py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h4 class="box-title mb-0 fw-bold text-dark">
                        ব্যবহারকারী তালিকা (মোট {{ $users->total() }} জন)
                    </h4>
                    <span class="badge bg-light text-muted border px-3 py-2">
                        পেজ: {{ $users->currentPage() }} / {{ $users->lastPage() }}
                    </span>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted fs-13">
                                <tr>
                                    <th class="ps-4" style="width: 70px;">#ID</th>
                                    <th>ব্যবহারকারী</th>
                                    <th>লগইন ইমেইল ও ফোন</th>
                                    <th>বর্তমান রোল</th>
                                    <th>অ্যাক্সেস পারমিশন স্ট্যাটাস</th>
                                    <th class="text-end pe-4" style="width: 280px;">একশন ও রোল পরিবর্তন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $u)
                                    @php
                                        $primaryRole = $u->roles->first() ?? \App\Models\Role::where('slug', $u->role)->first();
                                    @endphp
                                    <tr>
                                        <td class="ps-4 fw-bold text-muted">#{{ $u->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center fw-bold me-3 shadow-xs" style="width: 42px; height: 42px; font-size: 16px;">
                                                    {{ strtoupper(substr($u->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark fs-15">{{ $u->name }}</div>
                                                    <small class="text-muted">ইউজারনেম: {{ $u->username ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fs-13 text-dark font-monospace"><i class="fa fa-envelope text-muted me-1"></i> {{ $u->email }}</div>
                                            @if($u->phone)
                                                <div class="fs-12 text-muted"><i class="fa fa-phone text-muted me-1"></i> {{ $u->phone }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($u->id == 1 || $u->role === 'super_admin')
                                                <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold fs-12">
                                                    <i class="fa fa-shield me-1"></i> সুপার এডমিন
                                                </span>
                                            @elseif($primaryRole)
                                                <span class="badge bg-primary-light text-primary rounded-pill px-3 py-2 fw-bold fs-12">
                                                    {{ $primaryRole->name }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-light text-secondary rounded-pill px-3 py-2 fw-bold fs-12">
                                                    {{ ucfirst($u->role ?? 'user') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($u->id == 1 || $u->role === 'super_admin')
                                                <span class="badge bg-success-light text-success rounded-pill px-2 py-1 fs-11">
                                                    <i class="fa fa-check-circle me-1"></i> পূর্ণ আনলিমিটেড এক্সেস
                                                </span>
                                            @elseif($primaryRole)
                                                <a href="{{ route('admin.roles.matrix', ['role_id' => $primaryRole->id]) }}" class="badge bg-info-light text-info rounded-pill px-3 py-1 fs-11 text-decoration-none shadow-2xs" title="পারমিশন ম্যাট্রিক্সে এই রোলের অনুমতি দেখুন বা পরিবর্তন করুন">
                                                    <i class="fa fa-sliders text-primary me-1"></i> {{ $primaryRole->permissions->count() }} টি পারমিশন সক্রিয়
                                                </a>
                                            @else
                                                <span class="text-muted fs-12">কোনো স্পেশাল পারমিশন নেই</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            @if($u->id == 1)
                                                <span class="text-muted fs-12 italic"><i class="fa fa-lock me-1"></i> সিস্টেম প্রোটেক্টেড</span>
                                            @else
                                                <div class="d-flex align-items-center justify-content-end gap-2">
                                                    <!-- Role Change Form -->
                                                    <form method="POST" action="{{ route('admin.roles.user_assignment.update', $u->id) }}" class="d-inline-flex gap-1">
                                                        @csrf
                                                        <select name="role_id" class="form-select form-select-sm rounded-pill" style="max-width: 160px;">
                                                            @foreach($roles as $r)
                                                                <option value="{{ $r->id }}" {{ (($primaryRole && $primaryRole->id == $r->id) || $u->role == $r->slug) ? 'selected' : '' }}>
                                                                    {{ $r->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-2" title="রোল আপডেট করুন">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </form>

                                                    @if($primaryRole)
                                                        <a href="{{ route('admin.roles.matrix', ['role_id' => $primaryRole->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2" title="এই রোলের পারমিশন কনফিগার করুন">
                                                            <i class="fa fa-sliders"></i>
                                                        </a>
                                                    @endif

                                                    <!-- Reset Password Modal Trigger -->
                                                    <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-2" data-toggle="modal" data-target="#resetPassModal{{ $u->id }}" data-bs-toggle="modal" data-bs-target="#resetPassModal{{ $u->id }}" title="পাসওয়ার্ড পরিবর্তন">
                                                        <i class="fa fa-key"></i>
                                                    </button>
                                                </div>

                                                <!-- Reset Password Modal -->
                                                <div class="modal fade text-start" id="resetPassModal{{ $u->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content border-0 shadow">
                                                            <div class="modal-header bg-warning text-dark">
                                                                <h5 class="modal-title fw-bold">
                                                                    <i class="fa fa-key me-1"></i> পাসওয়ার্ড পরিবর্তন: {{ $u->name }}
                                                                </h5>
                                                                <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('admin.roles.user.reset_password', $u->id) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <p class="text-muted small">
                                                                        ব্যবহারকারী <strong>{{ $u->name }}</strong> (ইমেইল: <code>{{ $u->email }}</code>) এর জন্য নতুন লগইন পাসওয়ার্ড দিন।
                                                                    </p>
                                                                    <div class="form-group mb-3">
                                                                        <label class="fw-bold mb-1">নতুন পাসওয়ার্ড (Password) <span class="text-danger">*</span></label>
                                                                        <input type="password" name="password" class="form-control rounded-pill" placeholder="ন্যূনতম ৬ অক্ষর" minlength="6" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-light rounded-pill px-3" data-dismiss="modal" data-bs-dismiss="modal">বাতিল</button>
                                                                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">পাসওয়ার্ড সংরক্ষণ</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fa fa-info-circle fs-2 text-warning mb-2 d-block"></i>
                                            কোনো ব্যবহারকারী পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($users->hasPages())
                    <div class="box-footer py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-3 border-top" style="background: #ffffff;">
                        <div class="text-muted fs-13 fw-semibold">
                            দেখানো হচ্ছে <span class="text-dark fw-bold">{{ $users->firstItem() }}</span> থেকে <span class="text-dark fw-bold">{{ $users->lastItem() }}</span> (সর্বমোট <span class="text-success fw-bold">{{ $users->total() }}</span> জন)
                        </div>
                        <div class="user-assignment-pagination">
                            {{ $users->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

<!-- Modal: Create New User with Email, Password & Role Assignment -->
<div class="modal fade text-start" id="createUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0">
                    <i class="fa fa-user-plus me-2"></i> নতুন ইউজার / কর্মকর্তা তৈরি ও রোল নির্ধারণ
                </h5>
                <button type="button" class="btn-close btn-close-white text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: 0; font-size: 22px;">&times;</button>
            </div>
            <form action="{{ route('admin.roles.user.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">
                        এখানে তৈরি করা ইমেইল ও পাসওয়ার্ড দিয়ে সংশ্লিষ্ট কর্মকর্তা বা কর্মচারী সিস্টেমে লগইন করতে পারবেন। তিনি যে রোল পাবেন, তার পারমিশন ম্যাট্রিক্স অনুযায়ী শুধুমাত্র নির্দিষ্ট অপশনগুলো তার ড্যাশবোর্ডে থাকবে।
                    </p>

                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1 text-dark">পুরো নাম (Full Name) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-pill" placeholder="যেমন: মো: রফিকুল ইসলাম" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1 text-dark">লগইন ইমেইল (Login Email) <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control rounded-pill font-monospace" placeholder="যেমন: rofiq.hr@jamunastock.com" required>
                        <small class="text-muted">এই ইমেইলটি দিয়ে তিনি সিস্টেমে লগইন করবেন</small>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="fw-bold mb-1 text-dark">লগইন পাসওয়ার্ড <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control rounded-pill" placeholder="ন্যূনতম ৬ অক্ষর" minlength="6" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-1 text-dark">মোবাইল ফোন (Phone)</label>
                            <input type="text" name="phone" class="form-control rounded-pill" placeholder="যেমন: 01712345678">
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="fw-bold mb-1 text-dark">রোল নির্বাচন করুন (Assign Role) <span class="text-danger">*</span></label>
                        <select name="role_id" class="form-select rounded-pill border-success" style="border-width: 2px;" required>
                            <option value="">-- রোল বাছাই করুন --</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->id }}">
                                    {{ $r->name }} ({{ $r->permissions->count() }} টি পারমিশন অন্তর্ভুক্ত)
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">রোলের সাথে যুক্ত সকল অপশন এই ইউজারের ড্যাশবোর্ডে সচল হবে</small>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fa fa-check me-1"></i> অ্যাকাউন্ট তৈরি ও রোল বরাদ্দ করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
