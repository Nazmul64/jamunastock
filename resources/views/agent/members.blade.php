@extends('layouts.agent.app')

@section('title', 'নিবন্ধিত কার্ড হোল্ডার সদস্য - ' . $agentPoint->name)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1"><i class="fa fa-id-card text-info me-2"></i>পয়েন্টের নিবন্ধিত কার্ড হোল্ডার সদস্যবৃন্দ</h4>
        <p class="text-muted small mb-0">{{ $agentPoint->name }} • শুধুমাত্র এই এজেন্ট পয়েন্টের আওতাভুক্ত সদস্য তালিকা</p>
    </div>
    <span class="badge bg-primary px-3 py-2 fw-bold" style="border-radius: 8px;">মোট সদস্য: {{ $members->total() }} জন</span>
</div>

<div class="dark-card">
    <div class="dark-card-header">
        <h5 class="dark-card-title"><i class="fa fa-users text-primary"></i>কার্ড হোল্ডার তালিকা (Card Holders)</h5>

        <form method="GET" action="{{ route('agent.members') }}" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm dark-input" placeholder="কার্ড নম্বর, নাম বা ফোন..." style="width: 240px;">
            <button type="submit" class="btn btn-sm btn-primary px-3"><i class="fa fa-search"></i></button>
        </form>
    </div>

    @if($members->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fa fa-users fs-1 mb-2"></i>
            <p>এই পয়েন্টে এখনো কোনো কার্ড হোল্ডার সদস্য নিবন্ধিত নেই।</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table dark-table">
                <thead>
                    <tr>
                        <th>কার্ড নম্বর / আইডি</th>
                        <th>সদস্যের নাম</th>
                        <th>মোবাইল নম্বর</th>
                        <th>এলাকা</th>
                        <th>স্ট্যাটাস</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $m)
                    <tr>
                        <td><span class="badge bg-dark border border-secondary">{{ $m->card_number ?: $m->customer_id ?: 'EK-' . $m->id }}</span></td>
                        <td><strong class="text-white">{{ $m->name }}</strong></td>
                        <td>{{ $m->phone ?: '-' }}</td>
                        <td>{{ $m->areaModel->name ?? $m->agent_area ?? $agentPoint->area }}</td>
                        <td>
                            @if($m->status === 'active' || $m->is_active)
                                <span class="status-pill success"><i class="fa fa-check-circle"></i> সক্রিয় (Active)</span>
                            @else
                                <span class="status-pill danger"><i class="fa fa-ban"></i> নিষ্ক্রিয়</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('agent.dashboard') }}?search={{ $m->card_number ?: $m->phone }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-shopping-basket me-1"></i> বাজার বিতরণ
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $members->links() }}</div>
    @endif
</div>
@endsection
