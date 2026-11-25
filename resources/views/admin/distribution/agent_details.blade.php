@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="fa fa-university me-2 text-primary"></i> {{ $point->name }} — বিস্তারিত ও অডিট রিপোর্ট</h4>
                    <p class="subtitle text-muted mb-0">এজেন্ট কোড: <strong class="text-dark">{{ $point->code ?: ('AP-' . $point->id) }}</strong> | এলাকা: <strong class="text-dark">{{ $point->areaModel ? $point->areaModel->name : ($point->area ?: 'N/A') }}</strong></p>
                </div>
                <a href="{{ route('admin.agent_monitoring.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> মনিটরিং ড্যাশবোর্ডে ফিরুন
                </a>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Point Profile Card -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="box shadow-sm h-100">
                        <div class="box-header with-border bg-light">
                            <h5 class="box-title mb-0"><i class="fa fa-info-circle me-1 text-info"></i> পয়েন্ট বিবরণী</h5>
                        </div>
                        <div class="box-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">এজেন্ট ইনচার্জ:</span>
                                    <strong class="text-dark">{{ $point->agentUser ? $point->agentUser->name : 'নির্ধারিত নেই' }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">মোবাইল:</span>
                                    <span>{{ $point->contact_number ?: ($point->agentUser ? $point->agentUser->phone : 'N/A') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">ঠিকানা:</span>
                                    <span>{{ $point->address ?: 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">স্টোরেজ ধারণক্ষমতা:</span>
                                    <span>{{ number_format($point->capacity ?: 1000) }} KG</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">স্ট্যাটাস:</span>
                                    <span class="badge {{ $point->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($point->status) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 mb-4">
                    <div class="box shadow-sm h-100">
                        <div class="box-header with-border bg-light">
                            <h5 class="box-title mb-0"><i class="fa fa-cubes me-1 text-warning"></i> পয়েন্টের বর্তমান স্টক খতিয়ান (Agent Stock Ledger)</h5>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>পণ্যের নাম</th>
                                            <th class="text-end">বরাদ্দকৃত স্টক</th>
                                            <th class="text-end">বিতরণকৃত</th>
                                            <th class="text-end">অবশিষ্ট স্টক</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stocks as $st)
                                        @php
                                            $rem = max(0, floatval($st->allocated_quantity) - floatval($st->sold_quantity));
                                        @endphp
                                        <tr>
                                            <td><strong class="text-dark">{{ $st->product ? $st->product->stock_name : ('Product #' . $st->product_id) }}</strong></td>
                                            <td class="text-end fw-bold text-dark">{{ number_format($st->allocated_quantity, 1) }}</td>
                                            <td class="text-end fw-bold text-success">{{ number_format($st->sold_quantity, 1) }}</td>
                                            <td class="text-end fw-bold {{ $rem > 0 ? 'text-warning' : 'text-muted' }}">{{ number_format($rem, 1) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">এই পয়েন্টে বর্তমানে কোনো স্টক ডাটা নেই।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inbound Dispatches to this Point -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h5 class="box-title mb-0"><i class="fa fa-truck me-1 text-primary"></i> সেন্ট্রাল ওয়্যারহাউস থেকে প্রেরিত চালান (Recent Dispatches)</h5>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>চালান নং</th>
                                            <th>প্রেরণের তারিখ</th>
                                            <th>পণ্যের বিবরণ</th>
                                            <th>স্ট্যাটাস</th>
                                            <th>মন্তব্য</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($dispatches as $dsp)
                                        <tr>
                                            <td><span class="badge bg-secondary font-monospace">{{ $dsp->dispatch_no }}</span></td>
                                            <td>{{ $dsp->dispatch_date }}</td>
                                            <td>
                                                @foreach($dsp->items as $it)
                                                    <span class="badge bg-light text-dark border me-1">{{ $it->product_name }}: {{ $it->quantity }} {{ $it->unit }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <span class="badge {{ $dsp->status === 'received' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                    {{ ucfirst($dsp->status) }}
                                                </span>
                                            </td>
                                            <td class="text-muted small">{{ $dsp->notes ?: 'N/A' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">এই এজেন্ট পয়েন্টে কোনো চালান পাওয়া যায়নি।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Member Delivery Logs -->
            <div class="row">
                <div class="col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h5 class="box-title mb-0"><i class="fa fa-check-square-o me-1 text-success"></i> সদস্য বিতরণ লগ (Member Deliveries)</h5>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>রশিদ নং</th>
                                            <th>কার্ড নাম্বার</th>
                                            <th>সদস্যের নাম</th>
                                            <th>বিতরণকৃত পণ্য</th>
                                            <th>তারিখ ও সময়</th>
                                            <th>স্ট্যাটাস</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($deliveries as $del)
                                        <tr>
                                            <td><span class="badge bg-secondary font-monospace">{{ $del->delivery_no }}</span></td>
                                            <td><span class="badge bg-warning text-dark font-monospace">{{ $del->card_number }}</span></td>
                                            <td><strong class="text-dark">{{ $del->member_name }}</strong></td>
                                            <td>
                                                @foreach($del->items as $it)
                                                    <span class="badge bg-light text-dark border me-1">{{ $it->product_name }}: {{ $it->quantity }} {{ $it->unit }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ $del->delivery_date }} {{ $del->delivery_time ? date('h:i A', strtotime($del->delivery_time)) : '' }}</td>
                                            <td>
                                                <span class="badge {{ $del->status === 'completed' ? 'bg-success' : 'bg-info' }}">
                                                    {{ ucfirst($del->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">কোনো ডেলিভারি রেকর্ড পাওয়া যায়নি।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($deliveries->hasPages())
                        <div class="box-footer">
                            {{ $deliveries->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection
