@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('admin.investments.sector.dashboard', $sector->slug) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> {{ $sector->name_bn }} ড্যাশবোর্ড
                </a>
                <h3 class="fw-bold text-dark mb-1 mt-2">
                    <i class="fa fa-users text-primary me-2"></i>{{ $sector->name_bn }} - ইনভেস্টর তালিকা (Sector Investors)
                </h3>
            </div>
            <a href="{{ route('admin.investments.create', ['slug' => $sector->slug]) }}" class="btn btn-success rounded-pill px-3">
                <i class="fa fa-plus-circle me-1"></i> নতুন বিনিয়োগ
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th>গ্রাহক কোড</th>
                                <th>পূর্ণ নাম</th>
                                <th>মোবাইল নম্বর</th>
                                <th>জাতীয় পরিচয়পত্র</th>
                                <th>ঠিকানা</th>
                                <th>এই সেক্টরে বিনিয়োগ</th>
                                <th>মোট সক্রিয় চুক্তি</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $c)
                                <tr>
                                    <td><span class="badge bg-light text-primary border">{{ $c->customer_code }}</span></td>
                                    <td><strong>{{ $c->name }}</strong></td>
                                    <td>{{ $c->phone }}</td>
                                    <td>{{ $c->nid_number ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($c->present_address ?? 'N/A', 30) }}</td>
                                    <td>
                                        <span class="fw-bold text-success fs-6">৳{{ number_format($c->investments->sum('amount'), 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success">{{ $c->investments->count() }}টি চুক্তি</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.investments.customer.profile', $c->id) }}" class="btn btn-sm btn-light border rounded-pill px-3">
                                            <i class="fa fa-id-card text-primary me-1"></i> প্রোফাইল
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">এই সেক্টরে কোনো গ্রাহক পাওয়া যায়নি।</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($customers->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
