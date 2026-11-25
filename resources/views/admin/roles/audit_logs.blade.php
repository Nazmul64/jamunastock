@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-history text-primary me-2"></i> পারমিশন ও রোল সিকিউরিটি অডিট ট্রেইল (Audit Trail)
                    </h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">রোল ব্যবস্থাপনা</a></li>
                                <li class="breadcrumb-item active" aria-current="page">অডিট হিস্ট্রি</li>
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
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold text-dark mb-0">
                        <i class="fa fa-shield-alt text-success me-2"></i> সাম্প্রতিক রোল ও পারমিশন পরিবর্তনের পূর্ণাঙ্গ রেকর্ড
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">তারিখ ও সময়</th>
                                    <th>পরিবর্তনকারী</th>
                                    <th>টার্গেট ধরন ও নাম</th>
                                    <th>ইভেন্ট টাইপ</th>
                                    <th>পরিবর্তনের বিবরণ</th>
                                    <th>আইপি এড্রেস</th>
                                    <th class="pe-4">কারণ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($auditLogs as $log)
                                    <tr>
                                        <td class="ps-4 text-nowrap">
                                            <small class="text-muted d-block">{{ $log->created_at ? $log->created_at->format('d M, Y') : '' }}</small>
                                            <strong>{{ $log->created_at ? $log->created_at->format('h:i:s A') : '' }}</strong>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-user-circle text-primary me-2 fs-5"></i>
                                                <span class="fw-semibold">{{ $log->performer_name ?? 'System' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary text-uppercase">{{ $log->target_type }}</span>
                                            <strong class="d-block text-dark mt-1">{{ $log->target_name ?? 'N/A' }}</strong>
                                        </td>
                                        <td>
                                            @php
                                                $evBadge = 'bg-info text-dark';
                                                if (str_contains($log->event_type, 'created')) $evBadge = 'bg-success';
                                                elseif (str_contains($log->event_type, 'deleted')) $evBadge = 'bg-danger';
                                                elseif (str_contains($log->event_type, 'synced')) $evBadge = 'bg-primary';
                                            @endphp
                                            <span class="badge {{ $evBadge }} rounded-pill px-3 py-1">
                                                {{ $log->event_type }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(is_array($log->details))
                                                <code class="small text-muted">{{ json_encode($log->details, JSON_UNESCAPED_UNICODE) }}</code>
                                            @else
                                                <span class="small">{{ $log->details }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border font-monospace">{{ $log->ip_address }}</span>
                                        </td>
                                        <td class="pe-4 text-muted small">
                                            {{ $log->reason ?? 'Admin action' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fa fa-info-circle fs-3 mb-2 d-block"></i>
                                            কোনো অডিট লগ এখনো রেকর্ড করা হয়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($auditLogs->hasPages())
                    <div class="card-footer bg-white py-3">
                        {{ $auditLogs->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
