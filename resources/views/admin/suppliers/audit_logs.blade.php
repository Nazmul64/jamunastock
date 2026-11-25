@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold mb-1"><i class="fa fa-shield text-dark me-2"></i> সাপ্লাই সিস্টেম অডিট লগ (Audit & Security)</h3>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">ড্যাশবোর্ড</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.suppliers.pending_supplies') }}">সরবরাহ রিকোয়েস্ট</a></li>
                        <li class="breadcrumb-item active">অডিট লগ</li>
                    </ol>
                </div>
                <a href="{{ route('admin.suppliers.pending_supplies') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">ফিরে যান</a>
            </div>
        </div>

        <section class="content">
            <div class="box border-0 shadow-xs rounded-4">
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-3">তারিখ ও সময়</th>
                                    <th>Supply ID</th>
                                    <th>অ্যাকশন</th>
                                    <th>ইউজার ও রোল</th>
                                    <th>পরিবর্তনের বিবরণ</th>
                                    <th class="pe-3">মন্তব্য</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr>
                                    <td class="ps-3 small">{{ $log->created_at->format('d M, Y h:i A') }}</td>
                                    <td>
                                        @if($log->supply)
                                            <a href="{{ route('admin.suppliers.supplies.show', $log->supply->id) }}" class="fw-bold text-primary">{{ $log->supply->supply_code }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td><span class="badge bg-info">{{ $log->action }}</span></td>
                                    <td>
                                        <strong>{{ $log->user->name ?? 'System' }}</strong>
                                        <div class="small text-muted">{{ $log->user_role }}</div>
                                    </td>
                                    <td class="small">
                                        @if($log->new_values)
                                            <code>{{ json_encode($log->new_values, JSON_UNESCAPED_UNICODE) }}</code>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="pe-3 small text-muted">{{ $log->remarks ?: '-' }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-4 text-muted">কোনো অডিট লগ পাওয়া যায়নি।</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">{{ $logs->links() }}</div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
