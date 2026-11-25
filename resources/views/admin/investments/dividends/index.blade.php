@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-coins text-success me-2"></i>প্রকল্পভিত্তিক লভ্যাংশ বণ্টন ও হিসাব</h4>
                    <p class="text-muted mb-0">ঘোষিত ও পরিশোধিত লভ্যাংশের পূর্ণাঙ্গ বিবরণী</p>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-success fs-6 px-3 py-2">পরিশোধিত লভ্যাংশ: ৳ {{ number_format($totalPaid) }}</span>
                    <span class="badge bg-warning fs-6 px-3 py-2 text-dark">অপেক্ষমাণ লভ্যাংশ: ৳ {{ number_format($totalPending) }}</span>
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
                <div class="box-header with-border">
                    <h5 class="box-title">লভ্যাংশ বণ্টন তালিকা</h5>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>আইডি</th>
                                    <th>বিনিয়োগকারী</th>
                                    <th>প্রকল্প</th>
                                    <th>লভ্যাংশের পরিমাণ</th>
                                    <th>ঘোষণার তারিখ</th>
                                    <th>পরিশোধের তারিখ</th>
                                    <th>পদ্ধতি / রেফারেন্স</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dividends as $d)
                                    <tr>
                                        <td>#{{ $d->id }}</td>
                                        <td>
                                            <strong class="d-block text-dark">{{ $d->user->name ?? 'গ্রাহক' }}</strong>
                                            <span class="text-muted small">{{ $d->user->phone ?? '-' }}</span>
                                        </td>
                                        <td>{{ $d->investment->project->project_name ?? '-' }}</td>
                                        <td><strong class="text-success fs-6">৳ {{ number_format($d->amount) }}</strong></td>
                                        <td>{{ $d->declaration_date ? $d->declaration_date->format('d M, Y') : '-' }}</td>
                                        <td>{{ $d->payment_date ? $d->payment_date->format('d M, Y') : '-' }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $d->payout_method ?: '-' }}</span>
                                            <span class="small text-muted d-block">{{ $d->transaction_reference ?: '' }}</span>
                                        </td>
                                        <td>
                                            @if($d->status == 'paid')
                                                <span class="badge bg-success">পরিশোধিত</span>
                                            @else
                                                <span class="badge bg-warning text-dark">পেন্ডিং</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($d->status == 'pending')
                                                <form action="{{ route('admin.investments.dividends.mark_paid', $d->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="পরিশোধ চিহ্নিত করুন">
                                                        <i class="fa fa-check me-1"></i>পরিশোধ করুন
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted small">পরিশোধিত</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-5">কোনো লভ্যাংশ রেকর্ড পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $dividends->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

