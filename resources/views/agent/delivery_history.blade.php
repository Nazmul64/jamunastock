@extends('layouts.agent.app')

@section('title', 'ডেলিভারি হিস্ট্রি - ' . $agentPoint->name)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1"><i class="fa fa-history text-danger me-2"></i>পয়েন্টের পণ্য বিতরণ হিস্ট্রি</h4>
        <p class="text-muted small mb-0">{{ $agentPoint->name }} • কার্ড হোল্ডারদের পণ্য প্রদানের সম্পূর্ণ অডিট লগ</p>
    </div>
</div>

<div class="dark-card">
    <div class="dark-card-header">
        <h5 class="dark-card-title"><i class="fa fa-file-text-o text-info"></i>ডেলিভারি লগসমূহ</h5>

        <form method="GET" action="{{ route('agent.delivery_history') }}" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm dark-input" placeholder="ভাউচার বা কার্ড..." style="width: 180px;">
            <input type="date" name="date" value="{{ request('date') }}" class="form-control form-control-sm dark-input" style="width: 150px;">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-filter"></i></button>
        </form>
    </div>

    @if($deliveries->isEmpty())
        <div class="text-center py-5 text-muted">কোনো ডেলিভারি হিস্ট্রি পাওয়া যায়নি।</div>
    @else
        <div class="table-responsive">
            <table class="table dark-table">
                <thead>
                    <tr>
                        <th>ভাউচার নং</th>
                        <th>সদস্যের নাম</th>
                        <th>কার্ড নম্বর</th>
                        <th>বিতরণকৃত পণ্য</th>
                        <th>তারিখ ও সময়</th>
                        <th>রসিদ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveries as $del)
                    <tr>
                        <td><span class="badge bg-dark border border-secondary">{{ $del->delivery_no }}</span></td>
                        <td><strong class="text-white">{{ $del->member_name }}</strong></td>
                        <td>{{ $del->card_number }}</td>
                        <td>
                            @foreach($del->items as $it)
                                <span class="badge bg-info bg-opacity-25 text-info me-1">{{ $it->product_name }}: {{ $it->quantity }} {{ $it->unit }}</span>
                            @endforeach
                        </td>
                        <td>
                            {{ date('d M, Y', strtotime($del->delivery_date)) }}<br>
                            <small class="text-muted">{{ date('h:i A', strtotime($del->delivery_time)) }}</small>
                        </td>
                        <td>
                            <a href="{{ route('agent.receipt', $del->id) }}" target="_blank" class="btn btn-sm btn-outline-light">
                                <i class="fa fa-print me-1"></i> ভাউচার
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $deliveries->links() }}</div>
    @endif
</div>
@endsection
