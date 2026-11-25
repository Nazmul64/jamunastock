@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-hand-holding-usd text-success me-2"></i>গ্রাহক বিনিয়োগ আবেদন ও পোর্টফোলিও</h4>
                    <p class="text-muted mb-0">গ্রাহকদের জমাকৃত বিনিয়োগ আবেদন যাচাই ও অনুমোদন করুন</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge bg-success fs-6 px-3 py-2">মোট বিনিয়োগ: ৳ {{ number_format($totalInvested) }}</span>
                    @if($pendingCount > 0)
                        <span class="badge bg-warning fs-6 px-3 py-2 text-dark">{{ $pendingCount }} টি পেন্ডিং</span>
                    @endif
                    <button type="button" class="btn btn-success btn-sm font-weight-bold" data-bs-toggle="modal" data-bs-target="#addUserInvestmentModal">
                        <i class="fa fa-plus me-1"></i>নতুন বিনিয়োগ যুক্ত করুন
                    </button>
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
                    <h5 class="box-title">বিনিয়োগ আবেদনের তালিকা</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.investments.user_investments.index') }}" class="btn btn-xs {{ empty($status) ? 'btn-success' : 'btn-default' }}">সকল</a>
                        <a href="{{ route('admin.investments.user_investments.index', ['status' => 'pending']) }}" class="btn btn-xs {{ $status == 'pending' ? 'btn-success' : 'btn-default' }}">পেন্ডিং</a>
                        <a href="{{ route('admin.investments.user_investments.index', ['status' => 'approved']) }}" class="btn btn-xs {{ $status == 'approved' ? 'btn-success' : 'btn-default' }}">অনুমোদিত</a>
                        <a href="{{ route('admin.investments.user_investments.index', ['status' => 'active']) }}" class="btn btn-xs {{ $status == 'active' ? 'btn-success' : 'btn-default' }}">সক্রিয়</a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>কোড</th>
                                    <th>গ্রাহক</th>
                                    <th>প্রকল্পের নাম</th>
                                    <th>পরিমাণ</th>
                                    <th>পেমেন্ট মাধ্যম</th>
                                    <th>ট্রানজেকশন ID</th>
                                    <th>তারিখ</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($investments as $inv)
                                    <tr>
                                        <td><strong>{{ $inv->investment_code }}</strong></td>
                                        <td>
                                            <strong class="d-block text-dark">{{ $inv->user->name ?? 'গ্রাহক' }}</strong>
                                            <span class="text-muted small">{{ $inv->user->phone ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="d-block fw-bold text-dark">{{ $inv->project->project_name ?? '-' }}</span>
                                            <span class="text-muted small">{{ $inv->sector->name_bn ?? '-' }}</span>
                                        </td>
                                        <td><strong class="text-success fs-6">৳ {{ number_format($inv->amount) }}</strong></td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $inv->payment_method }}</span>
                                            @if($inv->payment_proof)
                                                <a href="{{ asset($inv->payment_proof) }}" target="_blank" class="d-block small text-primary mt-1"><i class="fa fa-image me-1"></i>রসিদ দেখুন</a>
                                            @endif
                                        </td>
                                        <td><code>{{ $inv->transaction_id }}</code></td>
                                        <td>{{ $inv->investment_date ? $inv->investment_date->format('d M, Y') : '-' }}</td>
                                        <td>
                                            @if($inv->status == 'approved' || $inv->status == 'active')
                                                <span class="badge bg-success">অনুমোদিত</span>
                                            @elseif($inv->status == 'pending')
                                                <span class="badge bg-warning text-dark">পেন্ডিং</span>
                                            @elseif($inv->status == 'completed')
                                                <span class="badge bg-info">সম্পন্ন</span>
                                            @else
                                                <span class="badge bg-danger">{{ $inv->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($inv->status == 'pending')
                                                <form action="{{ route('admin.investments.user_investments.status', $inv->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="btn btn-sm btn-success me-1" title="অনুমোদন করুন">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.investments.user_investments.status', $inv->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="বাতিল করুন">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted small">কার্যক্রম সম্পন্ন</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-5">কোনো বিনিয়োগ আবেদন পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $investments->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Add User Investment Modal -->
<div class="modal fade" id="addUserInvestmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold">সরাসরি গ্রাহক বিনিয়োগ অন্তর্ভুক্ত করুন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.investments.user_investments.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">গ্রাহক নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select" required>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->phone ?: $u->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">প্রকল্প নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="project_id" class="form-select" required>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->project_name }} ({{ $p->project_id_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">বিনিয়োগের পরিমাণ (টাকা) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" min="100" required placeholder="উদাঃ ৫০,০০০">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">পেমেন্ট মাধ্যম <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select" required>
                            <option value="নগদ (Cash)">নগদ (Cash)</option>
                            <option value="ব্যাংক ডিপোজিট (Bank)">ব্যাংক ডিপোজিট (Bank)</option>
                            <option value="বিকাশ (bKash)">বিকাশ (bKash)</option>
                            <option value="নগদ (Nagad)">নগদ (Nagad)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">ট্রানজেকশন ID / রসিদ নম্বর <span class="text-danger">*</span></label>
                        <input type="text" name="transaction_id" class="form-control" required placeholder="উদাঃ CASH-2026 বা TrxID">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">স্ট্যাটাস <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="approved">অনুমোদিত ও সক্রিয় (Approved)</option>
                            <option value="pending">যাচাইাধীন (Pending)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success font-weight-bold">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

