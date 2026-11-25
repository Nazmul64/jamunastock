@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold mb-1">
                        <i class="fa fa-bell text-warning me-2"></i> সাপ্লাই ও ডিস্ট্রিবিউশন নোটিফিকেশন (Admin Notifications)
                    </h3>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">ড্যাশবোর্ড</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.suppliers.pending_supplies') }}">সরবরাহ রিকোয়েস্ট</a></li>
                        <li class="breadcrumb-item active">নোটিফিকেশন</li>
                    </ol>
                </div>
                <div class="d-flex gap-2">
                    <form action="{{ route('admin.suppliers.notifications.read_all') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">
                            <i class="fa fa-check-double me-1"></i> সবগুলো পঠিত করুন
                        </button>
                    </form>
                    <a href="{{ route('admin.suppliers.pending_supplies') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">ফিরে যান</a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif

            <div class="box border-0 shadow-xs rounded-4">
                <div class="box-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($notifications as $notif)
                            <div class="list-group-item p-3 d-flex justify-content-between align-items-center flex-wrap gap-2 {{ !$notif->is_read ? 'bg-light border-start border-4 border-primary' : '' }}">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        @if(!$notif->is_read)
                                            <span class="badge bg-danger rounded-pill">নতুন</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill">পঠিত</span>
                                        @endif
                                        <strong class="text-dark">{{ $notif->title }}</strong>
                                    </div>
                                    <p class="mb-1 text-muted small">{{ $notif->message }}</p>
                                    <small class="text-muted"><i class="fa fa-clock-o me-1"></i> {{ $notif->created_at->diffForHumans() }} ({{ $notif->created_at->format('d M, Y h:i A') }})</small>
                                </div>
                                <div>
                                    <a href="{{ route('admin.suppliers.notifications.read', $notif->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                        <i class="fa fa-eye me-1"></i> বিস্তারিত দেখুন
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="fa fa-bell-slash fs-1 mb-2"></i>
                                <p class="mb-0">কোনো সাপ্লাই নোটিফিকেশন পাওয়া যায়নি।</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="p-3">{{ $notifications->links() }}</div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
