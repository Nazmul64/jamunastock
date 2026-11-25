@extends('layouts.agent.app')

@section('title', 'মাসিক বাজার রিকোয়েস্ট - ' . $agentPoint->name)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1"><i class="fa fa-shopping-basket text-primary me-2"></i>পয়েন্টের মাসিক বাজার রিকোয়েস্টসমূহ</h4>
        <p class="text-muted small mb-0">{{ $agentPoint->name }} • সংগৃহীত ও অপেক্ষমাণ রিকোয়েস্ট তালিকা</p>
    </div>
</div>

<div class="dark-card">
    <div class="dark-card-header">
        <h5 class="dark-card-title"><i class="fa fa-list text-warning"></i>রিকোয়েস্ট তালিকা</h5>

        <div class="d-flex gap-2">
            <a href="{{ route('agent.requests') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">সব</a>
            <a href="{{ route('agent.requests', ['status' => 'approved']) }}" class="btn btn-sm {{ request('status') === 'approved' ? 'btn-primary' : 'btn-outline-secondary' }}">অনুমোদিত</a>
            <a href="{{ route('agent.requests', ['status' => 'delivered']) }}" class="btn btn-sm {{ request('status') === 'delivered' ? 'btn-primary' : 'btn-outline-secondary' }}">বিতরণ সম্পন্ন</a>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-5 text-muted">কোনো মাসিক বাজার রিকোয়েস্ট পাওয়া যায়নি।</div>
    @else
        <div class="table-responsive">
            <table class="table dark-table">
                <thead>
                    <tr>
                        <th>অর্ডার আইডি</th>
                        <th>সদস্যের নাম ও কার্ড</th>
                        <th>প্যাকেজ / পণ্য</th>
                        <th>চাহিদা পরিমাণ</th>
                        <th>বিতরণকৃত</th>
                        <th>অবশিষ্ট</th>
                        <th>স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $ord)
                    <tr>
                        <td>#{{ $ord->id }}</td>
                        <td>
                            <strong class="text-white">{{ $ord->user->name ?? 'গ্রাহক' }}</strong><br>
                            <small class="text-muted">{{ $ord->user->card_number ?? $ord->pay_from_number }}</small>
                        </td>
                        <td><strong class="text-info">{{ $ord->package_title }}</strong></td>
                        <td>{{ $ord->quantity }} KG</td>
                        <td>{{ $ord->delivered_quantity ?: 0 }} KG</td>
                        <td><span class="badge bg-warning text-dark">{{ max(0, $ord->quantity - ($ord->delivered_quantity ?: 0)) }} KG</span></td>
                        <td>
                            @if($ord->status === 'delivered')
                                <span class="status-pill success"><i class="fa fa-check"></i> সম্পূর্ণ বিতরণ</span>
                            @elseif($ord->status === 'partially_delivered')
                                <span class="status-pill warning"><i class="fa fa-pie-chart"></i> আংশিক বিতরণ</span>
                            @elseif($ord->status === 'approved')
                                <span class="status-pill info"><i class="fa fa-clock-o"></i> সংগ্রহের জন্য প্রস্তুত</span>
                            @else
                                <span class="status-pill warning">{{ ucfirst($ord->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
