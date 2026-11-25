@extends('layouts.agent.app')

@section('title', 'ইনকামিং ডিসপ্যাচ ও স্টক রিসিভ - ' . $agentPoint->name)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1"><i class="fa fa-truck text-warning me-2"></i>সেন্ট্রাল ওয়্যারহাউস থেকে ইনকামিং ডিসপ্যাচ</h4>
        <p class="text-muted small mb-0">{{ $agentPoint->name }} • পণ্যের চালান বুঝে নিয়ে রিসিভ কনফার্ম করুন</p>
    </div>
</div>

<div class="dark-card">
    <div class="dark-card-header">
        <h5 class="dark-card-title"><i class="fa fa-list text-info"></i>ডিসপ্যাচ তালিকা (Dispatch History)</h5>
    </div>

    @if($dispatches->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fa fa-truck fs-1 mb-2"></i>
            <p>আপনার পয়েন্টের জন্য কোনো ডিসপ্যাচ চালান ইস্যু করা হয়নি।</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table dark-table">
                <thead>
                    <tr>
                        <th>চালান আইডি (Dispatch No)</th>
                        <th>ইস্যু তারিখ</th>
                        <th>পণ্য ও পরিমাণ</th>
                        <th>সোর্স ওয়্যারহাউস</th>
                        <th>স্ট্যাটাস</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dispatches as $disp)
                    <tr>
                        <td><strong class="text-white">{{ $disp->dispatch_no }}</strong></td>
                        <td>{{ date('d M, Y', strtotime($disp->dispatch_date)) }}</td>
                        <td>
                            @foreach($disp->items as $it)
                                <div class="small">• {{ $it->product_name }}: <strong class="text-white">{{ $it->quantity }} {{ $it->unit }}</strong></div>
                            @endforeach
                        </td>
                        <td>{{ $disp->source_warehouse ?: 'Central Warehouse' }}</td>
                        <td>
                            @if($disp->status === 'received')
                                <span class="status-pill success"><i class="fa fa-check-circle"></i> রিসিভ সম্পন্ন</span>
                            @else
                                <span class="status-pill warning"><i class="fa fa-clock-o"></i> পথিমধ্যে অপেক্ষমাণ</span>
                            @endif
                        </td>
                        <td>
                            @if($disp->status !== 'received')
                                <form method="POST" action="{{ route('agent.dispatches.receive', $disp->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success fw-bold" onclick="return confirm('আপনি কি নিশ্চিত যে পণ্যগুলো সঠিকভাবে বুঝে পেয়েছেন?')">
                                        <i class="fa fa-check-circle me-1"></i> পণ্য বুঝে পেলাম
                                    </button>
                                </form>
                            @else
                                <small class="text-muted">{{ date('d M, Y h:i A', strtotime($disp->received_at)) }}</small>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $dispatches->links() }}</div>
    @endif
</div>
@endsection
