@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-user-shield text-success me-2"></i>শেয়ারহোল্ডার তালিকা ও পোর্টফোলিও</h4>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের নিবন্ধিত শেয়ারহোল্ডারদের প্রোফাইল, মূলধন ও লভ্যাংশ হিসাব</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge bg-success fs-6 px-3 py-2">মোট মূলধন: ৳ {{ number_format($totalCapital) }}</span>
                    <span class="badge bg-info fs-6 px-3 py-2">অফিস তহবিল: ৳ {{ number_format($totalOfficeCost) }}</span>
                    <button type="button" class="btn btn-success btn-sm font-weight-bold" data-bs-toggle="modal" data-bs-target="#addShareholderModal">
                        <i class="fa fa-plus me-1"></i>নতুন শেয়ারহোল্ডার যুক্ত করুন
                    </button>
                    <a href="{{ route('admin.shareholders.settings.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-cog me-1"></i>শেয়ার সেটিংস
                    </a>
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
                    <h5 class="box-title">শেয়ারহোল্ডারদের তালিকা</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.shareholders.index') }}" class="btn btn-xs {{ empty($status) ? 'btn-success' : 'btn-default' }}">সকল</a>
                        <a href="{{ route('admin.shareholders.index', ['status' => 'active']) }}" class="btn btn-xs {{ $status == 'active' ? 'btn-success' : 'btn-default' }}">সক্রিয়</a>
                        <a href="{{ route('admin.shareholders.index', ['status' => 'pending']) }}" class="btn btn-xs {{ $status == 'pending' ? 'btn-success' : 'btn-default' }}">পেন্ডিং</a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>কোড</th>
                                    <th>শেয়ারহোল্ডারের নাম</th>
                                    <th>মোবাইল / এনআইডি</th>
                                    <th>শেয়ার সংখ্যা</th>
                                    <th>মূলধন বিনিয়োগ</th>
                                    <th>অফিস খরচ</th>
                                    <th>নমিনি</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shareholders as $sh)
                                    <tr>
                                        <td><strong>{{ $sh->shareholder_code }}</strong></td>
                                        <td>
                                            <strong class="d-block text-dark">{{ $sh->user->name ?? 'গ্রাহক' }}</strong>
                                            <span class="text-muted small">{{ $sh->user->email ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="d-block">{{ $sh->user->phone ?? '-' }}</span>
                                            <span class="text-muted small">NID: {{ $sh->nid_number ?: '-' }}</span>
                                        </td>
                                        <td><span class="badge bg-primary fs-6">{{ $sh->shares_count }} টি</span></td>
                                        <td><strong class="text-success fs-6">৳ {{ number_format($sh->total_investment) }}</strong></td>
                                        <td>৳ {{ number_format($sh->office_expense_paid) }}</td>
                                        <td>
                                            <span class="d-block fw-bold small text-dark">{{ $sh->nominee_name ?: '-' }}</span>
                                            <span class="text-muted small">{{ $sh->nominee_relation ?: '' }} ({{ $sh->nominee_phone ?: '' }})</span>
                                        </td>
                                        <td>
                                            @if($sh->status == 'active')
                                                <span class="badge bg-success">সক্রিয়</span>
                                            @elseif($sh->status == 'pending')
                                                <span class="badge bg-warning text-dark">পেন্ডিং</span>
                                            @else
                                                <span class="badge bg-danger">{{ $sh->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($sh->status == 'pending')
                                                <form action="{{ route('admin.shareholders.status', $sh->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="active">
                                                    <button type="submit" class="btn btn-sm btn-success me-1" title="অনুমোদন করুন">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-5">কোনো শেয়ারহোল্ডার রেকর্ড পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $shareholders->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Add Shareholder Modal -->
<div class="modal fade" id="addShareholderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold">নতুন শেয়ারহোল্ডার যুক্ত করুন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.shareholders.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">গ্রাহক / ইউজার নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select" required>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->phone ?: $u->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">শেয়ার সংখ্যা <span class="text-danger">*</span></label>
                        <input type="number" name="shares_count" class="form-control" min="1" required value="1">
                        <div class="form-text small">প্রতি শেয়ার ৫,০০০ টাকা মূলধন + ৩,০০০ টাকা অফিস খরচ</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">জাতীয় পরিচয়পত্র নম্বর (NID)</label>
                        <input type="text" name="nid_number" class="form-control" placeholder="NID নম্বর">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">নমিনির নাম</label>
                        <input type="text" name="nominee_name" class="form-control" placeholder="নমিনির নাম">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label font-weight-bold">নমিনির মোবাইল</label>
                            <input type="text" name="nominee_phone" class="form-control" placeholder="017XXXXXXXX">
                        </div>
                        <div class="col-6">
                            <label class="form-label font-weight-bold">সম্পর্ক</label>
                            <input type="text" name="nominee_relation" class="form-control" placeholder="উদাঃ স্ত্রী / পুত্র">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">স্ট্যাটাস <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active">সক্রিয় (Active)</option>
                            <option value="pending">পেন্ডিং (Pending)</option>
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

