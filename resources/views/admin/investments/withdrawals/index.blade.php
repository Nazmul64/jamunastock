@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-money-bill-wave text-success me-2"></i>বিনিয়োগ ও লভ্যাংশ উত্তোলন আবেদন</h4>
                    <p class="text-muted mb-0">গ্রাহকদের জমাকৃত মূলধন ও মুনাফা উত্তোলন আবেদনসমূহ যাচাই ও নিষ্পত্তি করুন</p>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h5 class="box-title">উত্তোলন আবেদনের তালিকা</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.investments.withdrawals.index') }}" class="btn btn-xs {{ empty($status) ? 'btn-success' : 'btn-default' }}">সকল</a>
                        <a href="{{ route('admin.investments.withdrawals.index', ['status' => 'pending']) }}" class="btn btn-xs {{ $status == 'pending' ? 'btn-success' : 'btn-default' }}">পেন্ডিং</a>
                        <a href="{{ route('admin.investments.withdrawals.index', ['status' => 'approved']) }}" class="btn btn-xs {{ $status == 'approved' ? 'btn-success' : 'btn-default' }}">অনুমোদিত</a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>কোড</th>
                                    <th>গ্রাহক</th>
                                    <th>উত্তোলনের ধরন</th>
                                    <th>পরিমাণ</th>
                                    <th>পেমেন্ট মাধ্যম</th>
                                    <th>অ্যাকাউন্ট নম্বর</th>
                                    <th>তারিখ</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawals as $w)
                                    <tr>
                                        <td><strong>{{ $w->withdrawal_code }}</strong></td>
                                        <td>
                                            <strong class="d-block text-dark">{{ $w->user->name ?? 'গ্রাহক' }}</strong>
                                            <span class="text-muted small">{{ $w->user->phone ?? '-' }}</span>
                                        </td>
                                        <td><span class="badge bg-light text-dark">{{ ucfirst($w->withdrawal_type) }}</span></td>
                                        <td><strong class="text-success fs-6">৳ {{ number_format($w->amount) }}</strong></td>
                                        <td>{{ $w->payment_method }}</td>
                                        <td><code>{{ $w->account_number }}</code></td>
                                        <td>{{ $w->created_at ? $w->created_at->format('d M, Y') : '-' }}</td>
                                        <td>
                                            @if($w->status == 'approved' || $w->status == 'completed')
                                                <span class="badge bg-success">সম্পন্ন</span>
                                            @elseif($w->status == 'pending')
                                                <span class="badge bg-warning text-dark">পেন্ডিং</span>
                                            @else
                                                <span class="badge bg-danger">{{ $w->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($w->status == 'pending')
                                                <form action="{{ route('admin.investments.withdrawals.status', $w->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="btn btn-sm btn-success me-1" title="অনুমোদন ও পরিশোধ সম্পন্ন">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.investments.withdrawals.status', $w->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="বাতিল করুন">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted small">নিষ্পন্ন</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-5">কোনো উত্তোলন আবেদন পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $withdrawals->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

