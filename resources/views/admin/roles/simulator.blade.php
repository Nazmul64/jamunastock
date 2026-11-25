@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-sliders-h text-warning me-2"></i> এন্টারপ্রাইজ এক্সেস সিমুলেটর (Access Simulator & Tester)
                    </h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">রোল ব্যবস্থাপনা</a></li>
                                <li class="breadcrumb-item active" aria-current="page">এক্সেস সিমুলেটর</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-list me-1"></i> রোল তালিকা
                    </a>
                    <a href="{{ route('admin.roles.matrix') }}" class="btn btn-outline-primary rounded-pill px-3">
                        <i class="fa fa-sitemap me-1"></i> পারমিশন ট্রি
                    </a>
                    <a href="{{ route('admin.roles.user_assignment') }}" class="btn btn-outline-info rounded-pill px-3">
                        <i class="fa fa-users me-1"></i> ইউজার অ্যাসাইনমেন্ট
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- User Selector Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('admin.roles.simulator') }}" id="simForm">
                        <div class="row align-items-center g-3">
                            <div class="col-lg-6 col-12">
                                <label class="form-label fw-bold text-dark fs-15">
                                    <i class="fa fa-user-circle text-primary me-1"></i> ব্যবহারকারী নির্বাচন করে লাইভ এক্সেস ও ড্যাশবোর্ড টেস্ট করুন:
                                </label>
                                <select name="user_id" class="form-select form-select-lg rounded-pill border-primary" onchange="document.getElementById('simForm').submit();" style="border-width: 2px;">
                                    <option value="">-- এক্সেস সিমুলেট করতে একজন ইউজার সিলেক্ট করুন --</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ ($selectedUser && $selectedUser->id == $u->id) ? 'selected' : '' }}>
                                            {{ $u->name }} ({{ $u->email }}) — [রোল: {{ $u->roleTitle() }}]
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 col-12">
                                @if($selectedUser)
                                    <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2 border">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold me-3" style="width: 48px; height: 48px; font-size: 18px;">
                                                {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-16">{{ $selectedUser->name }}</div>
                                                <small class="text-muted">{{ $selectedUser->email }}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary rounded-pill px-3 py-1 fs-13 fw-bold d-block mb-1">
                                                রোল: {{ $selectedUser->roleTitle() }}
                                            </span>
                                            <span class="badge bg-info text-dark rounded-pill px-2 py-1 small">
                                                স্কোপ: {{ strtoupper($selectedUser->data_scope ?? 'ALL') }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-3 bg-light rounded-3 text-muted border">
                                        <i class="fa fa-info-circle text-info me-1"></i> যেকোনো কর্মকর্তা বা কর্মীকে নির্বাচন করলে তার অনুমোদিত মডিউল, অ্যাকশন ও রুট এক্সেস লাইভ টেস্ট করা যাবে।
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if($selectedUser)
                <!-- Route / Permission Quick Tester Bar -->
                <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #eff6ff, #f8fafc); border-left: 5px solid #2563eb !important;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="fa fa-bolt text-warning me-2"></i> লাইভ রুট ও পারমিশন প্রিভিউ টেস্টার (Route Access Preview)
                        </h5>
                        <form method="GET" action="{{ route('admin.roles.simulator') }}">
                            <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">
                            <div class="input-group input-group-lg shadow-sm">
                                <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fa fa-key text-muted"></i></span>
                                <input type="text" name="test_permission" class="form-control border-start-0" placeholder="টেস্ট করার জন্য পারমিশন কী লিখুন (যেমন: hrm.attendances.create, support.tickets.reply, central_finance)..." value="{{ request('test_permission') }}" required>
                                <button type="submit" class="btn btn-primary px-4 rounded-end-pill">
                                    <i class="fa fa-play me-1"></i> টেস্ট করুন (Check Access)
                                </button>
                            </div>
                        </form>

                        @if($routeTestResult)
                            <div class="mt-3">
                                @if($routeTestResult['allowed'])
                                    <div class="alert alert-success d-flex align-items-center rounded-3 mb-0">
                                        <i class="fa fa-check-circle fs-3 me-3 text-success"></i>
                                        <div>
                                            <strong class="d-block fs-16">অনুমোদিত! (200 OK - Access Granted)</strong>
                                            <span>ব্যবহারকারী <strong>{{ $selectedUser->name }}</strong> এর কাছে <code>{{ $routeTestResult['permission'] }}</code> অ্যাকশন পরিচালনার বৈধ অধিকার রয়েছে।</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-danger d-flex align-items-center rounded-3 mb-0">
                                        <i class="fa fa-times-circle fs-3 me-3 text-danger"></i>
                                        <div>
                                            <strong class="d-block fs-16">নিষিদ্ধ! (403 Forbidden - Access Denied)</strong>
                                            <span>ব্যবহারকারী <strong>{{ $selectedUser->name }}</strong> এর কাছে <code>{{ $routeTestResult['permission'] }}</code> অ্যাকশন ব্যবহারের পারমিশন নেই। সরাসরি ইউআরএল অ্যাক্সেস করলেও ৪০৩ ব্লক হবে।</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Accessible Modules vs Denied Modules -->
                <div class="row g-4">
                    <!-- Left: Accessible Modules -->
                    <div class="col-lg-6 col-12">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-success bg-opacity-10 py-3 border-0">
                                <h5 class="card-title text-success fw-bold mb-0">
                                    <i class="fa fa-check-circle me-2"></i> ড্যাশবোর্ডে দৃশ্যমান ও অনুমোদিত মডিউলসমূহ ({{ count($accessibleModules) }})
                                </h5>
                            </div>
                            <div class="card-body p-3">
                                @forelse($accessibleModules as $mod)
                                    <div class="p-3 mb-3 bg-light rounded-3 border-start border-4 border-success shadow-xs">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <strong class="text-dark fs-15">{{ $mod['module_name'] }}</strong>
                                            <span class="badge bg-success rounded-pill px-2 py-1">অ্যাক্সেস সক্রিয়</span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($mod['menus'] as $menu)
                                                @foreach($menu['submenus'] as $sub)
                                                    @foreach($sub['actions'] as $act)
                                                        @if($selectedUser->hasPermission($act['permission_key']))
                                                            <span class="badge bg-white text-dark border small" title="{{ $act['permission_key'] }}">
                                                                <i class="fa fa-check text-success me-1"></i> {{ $act['action_name'] }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-muted">
                                        কোনো মডিউলের অনুমোদন নেই।
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Right: Denied Modules -->
                    <div class="col-lg-6 col-12">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-danger bg-opacity-10 py-3 border-0">
                                <h5 class="card-title text-danger fw-bold mb-0">
                                    <i class="fa fa-ban me-2"></i> সাইডবার থেকে লুকানো ও নিষিদ্ধ মডিউলসমূহ ({{ count($deniedModules) }})
                                </h5>
                            </div>
                            <div class="card-body p-3">
                                @forelse($deniedModules as $mod)
                                    <div class="p-3 mb-3 bg-light rounded-3 border-start border-4 border-danger shadow-xs opacity-75">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <strong class="text-secondary fs-15">{{ $mod['module_name'] }}</strong>
                                            <span class="badge bg-danger rounded-pill px-2 py-1">লুকানো (Hidden 403)</span>
                                        </div>
                                        <small class="text-muted d-block">
                                            এই মডিউলের কোনো অ্যাকশন পারমিশন এই ইউজারের রোলে বরাদ্দ করা নেই।
                                        </small>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-success">
                                        <i class="fa fa-crown text-warning fs-3 mb-2 d-block"></i>
                                        এই ব্যবহারকারী একজন সুপার এডমিন অথবা তার কাছে সকল মডিউলের ফুল এক্সেস রয়েছে!
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
