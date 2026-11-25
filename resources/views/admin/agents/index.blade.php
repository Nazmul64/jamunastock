@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="fa fa-users me-2 text-primary"></i> এজেন্ট ব্যবস্থাপনা ও বিতরণ পর্যবেক্ষণ (Agent Management)</h4>
                    <p class="subtitle text-muted mb-0">সকল এজেন্ট পয়েন্টের দায়িত্বপ্রাপ্ত এজেন্টদের তালিকা, বাজার বিতরণ ও আর্থিক হিসাব খতিয়ান</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.agent_monitoring') }}" class="btn btn-outline-info btn-sm">
                        <i class="fa fa-television me-1"></i> লাইভ মনিটরিং
                    </a>
                    <a href="{{ route('admin.agents.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-user-plus me-1"></i> নতুন এজেন্ট তৈরি করুন
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- 4 Top Financial & Operational Metric Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="box bg-primary text-white shadow-sm mb-0">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1 text-white fw-bold">৳ {{ number_format($totalAllMarketAmount, 2) }}</h2>
                                <span class="fs-14 opacity-75">মোট বাজার বিতরণ মূল্য (Total Value)</span>
                            </div>
                            <div class="fs-36 opacity-50"><i class="fa fa-money"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="box bg-success text-white shadow-sm mb-0">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1 text-white fw-bold">{{ number_format($totalAllDeliveries) }}</h2>
                                <span class="fs-14 opacity-75">মোট সফল ডেলিভারি (Delivery Count)</span>
                            </div>
                            <div class="fs-36 opacity-50"><i class="fa fa-check-circle"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="box bg-info text-white shadow-sm mb-0">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1 text-white fw-bold">{{ number_format($totalAllMembers) }}</h2>
                                <span class="fs-14 opacity-75">নির্ধারিত কার্ডহোল্ডার (Assigned Members)</span>
                            </div>
                            <div class="fs-36 opacity-50"><i class="fa fa-id-card"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="box bg-warning text-dark shadow-sm mb-0">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1 text-dark fw-bold">{{ $agents->total() }} জন</h2>
                                <span class="fs-14 text-dark opacity-75">মোট নিবন্ধিত এজেন্ট (Active Agents)</span>
                            </div>
                            <div class="fs-36 opacity-50"><i class="fa fa-user-secret"></i></div>
                        </div>
                    </div>
                </div>
            </div>

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

            <!-- Filter Card -->
            <div class="box shadow-sm mb-4">
                <div class="box-body">
                    <form method="GET" action="{{ route('admin.agents.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">এজেন্ট খুঁজুন</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="নাম, এজেন্ট আইডি, মোবাইল বা ইমেইল লিখুন">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">এলাকা (Area)</label>
                            <select name="area_id" class="form-select form-select-sm">
                                <option value="">-- সকল এলাকা --</option>
                                @foreach($areas as $ar)
                                    <option value="{{ $ar->id }}" {{ request('area_id') == $ar->id ? 'selected' : '' }}>
                                        {{ $ar->name }} ({{ $ar->district }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">স্ট্যাটাস</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">-- সকল স্ট্যাটাস --</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active (সক্রিয়)</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive (নিষ্ক্রিয়)</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fa fa-search me-1"></i> ফিল্টার</button>
                            <a href="{{ route('admin.agents.index') }}" class="btn btn-light btn-sm border" title="রিসেট"><i class="fa fa-refresh"></i></a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Agents List Table -->
            <div class="box shadow-sm">
                <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                    <h5 class="box-title mb-0">এজেন্ট তালিকা ও পৃথক পারফরম্যান্স খতিয়ান ({{ $agents->total() }})</h5>
                    <a href="{{ route('admin.agents.create') }}" class="btn btn-xs btn-primary"><i class="fa fa-plus me-1"></i> এজেন্ট যোগ করুন</a>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>এজেন্ট প্রোফাইল</th>
                                    <th>এজেন্ট আইডি</th>
                                    <th>নির্ধারিত পয়েন্ট ও এলাকা</th>
                                    <th>যোগাযোগ</th>
                                    <th class="text-center">নির্ধারিত সদস্য</th>
                                    <th class="text-center">ডেলিভারি সংখ্যা</th>
                                    <th class="text-end">বিতরণকৃত বাজার মূল্য (৳)</th>
                                    <th class="text-end">অবশিষ্ট স্টক</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-center">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agentsData as $row)
                                @php
                                    $ag = $row['agent'];
                                    $pt = $row['point'];
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $ag->avatarUrl() }}" alt="{{ $ag->name }}" class="rounded-circle border" style="width: 42px; height: 42px; object-fit: cover;">
                                            <div>
                                                <a href="{{ route('admin.agents.show', $ag->id) }}" class="fw-bold text-dark text-decoration-none d-block">
                                                    {{ $ag->name }}
                                                </a>
                                                <small class="text-muted">{{ $ag->branch ?: 'Main Branch' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary font-monospace">{{ $ag->agent_id ?: ('AGT-' . $ag->id) }}</span>
                                    </td>
                                    <td>
                                        @if($pt)
                                            <strong class="text-primary">{{ $pt->name }}</strong>
                                            <div class="small text-muted">{{ $pt->areaModel ? $pt->areaModel->name : ($pt->area ?: 'N/A') }}</div>
                                        @else
                                            <span class="text-danger small"><i class="fa fa-exclamation-circle"></i> পয়েন্ট নির্ধারণ নেই</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $ag->phone }}</div>
                                        <small class="text-muted">{{ $ag->email }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info px-2 py-1 fs-12">{{ $row['members_count'] }} জন</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-2 py-1 fs-12">{{ $row['deliveries_count'] }} টি</span>
                                    </td>
                                    <td class="text-end fw-bold text-success fs-14">
                                        ৳ {{ number_format($row['market_amount'], 2) }}
                                    </td>
                                    <td class="text-end fw-bold {{ $row['stock_remaining'] > 0 ? 'text-warning' : 'text-muted' }}">
                                        {{ number_format($row['stock_remaining'], 1) }} KG
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.agents.toggle_status', $ag->id) }}" class="badge {{ $ag->status === 'active' ? 'bg-success' : 'bg-danger' }} text-decoration-none" title="ক্লিক করে স্ট্যাটাস পরিবর্তন করুন">
                                            {{ ucfirst($ag->status) }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.agents.show', $ag->id) }}" class="btn btn-xs btn-outline-primary" title="সম্পূর্ণ হিসাব ও অডিট রিপোর্ট">
                                                <i class="fa fa-eye"></i> রিপোর্ট
                                            </a>
                                            <a href="{{ route('admin.agents.edit', $ag->id) }}" class="btn btn-xs btn-outline-warning" title="এডিট">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">কোনো এজেন্ট পাওয়া যায়নি। "নতুন এজেন্ট তৈরি করুন" বাটনে ক্লিক করুন।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($agents->hasPages())
                <div class="box-footer">
                    {{ $agents->links() }}
                </div>
                @endif
            </div>

        </section>
    </div>
</div>
@endsection
