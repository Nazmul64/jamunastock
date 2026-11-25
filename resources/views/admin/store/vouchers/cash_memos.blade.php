@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-money text-primary me-2"></i> ক্যাশ মেমো ও মানি রিসিট (Cash Memos & Money Receipts)
                    </h3>
                    <p class="text-muted mb-0">দোকানের ক্রয় ক্যাশ মেমো, মালামাল বিক্রয় ও টাকা প্রাপ্তির অফিসিয়াল মানি রিসিট হিসাব</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#newCashMemoModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন ক্যাশ মেমো / রিসিট যুক্ত করুন
                    </button>
                    <a href="{{ route('admin.store.vouchers.index') }}" class="btn btn-outline-success shadow-sm">
                        <i class="fa fa-file-text-o me-1"></i> বিল ও ভাউচার
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filter & Search Bar -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.store.vouchers.cash_memos') }}" class="row g-2 align-items-center">
                        <div class="col-lg-5 col-md-6 col-12">
                            <input type="text" name="search" class="form-control" placeholder="মেমো / রিসিট নং, বিবরণ বা দোকান/গ্রাহকের নাম..." value="{{ request('search') }}">
                        </div>
                        <div class="col-lg-4 col-md-3 col-6">
                            <select name="type" class="form-select">
                                <option value="">ক্যাশ মেমো ও মানি রিসিট (উভয়)</option>
                                <option value="cash_memo" {{ request('type') == 'cash_memo' ? 'selected' : '' }}>ক্যাশ মেমো (ক্রয় বাবদ মেমো)</option>
                                <option value="money_receipt" {{ request('type') == 'money_receipt' ? 'selected' : '' }}>মানি রিসিট (অর্থ প্রাপ্তি রসিদ)</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fa fa-filter me-1"></i> ফিল্টার</button>
                            @if(request()->anyFilled(['search', 'type']))
                                <a href="{{ route('admin.store.vouchers.cash_memos') }}" class="btn btn-outline-secondary" title="রিসেট"><i class="fa fa-refresh"></i></a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Memos Table -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>নম্বর ও তারিখ</th>
                                    <th>ধরন</th>
                                    <th>শিরোনাম ও মালামালের বিবরণ</th>
                                    <th>দোকান / গ্রাহকের নাম</th>
                                    <th>টাকার পরিমাণ (৳)</th>
                                    <th>পেমেন্ট মাধ্যম</th>
                                    <th>স্ট্যাটাস</th>
                                    <th>মেমো ছবি / ফাইল</th>
                                    <th class="text-center" style="width: 130px;">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $memos = $memos ?? ($vouchers ?? collect()); @endphp
                                @forelse($memos as $m)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-dark d-block">{{ $m->voucher_no }}</span>
                                            <small class="text-muted">{{ $m->date ? $m->date->format('d/m/Y') : '' }}</small>
                                        </td>
                                        <td>{!! $m->type_badge !!}</td>
                                        <td>
                                            <strong class="text-dark">{{ $m->title }}</strong>
                                            @if($m->item_details)
                                                <small class="text-muted d-block" style="white-space: pre-line;">{{ Str::limit($m->item_details, 60) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $m->vendor_customer_name ?: '—' }}</td>
                                        <td class="fw-bold fs-6 {{ $m->type == 'money_receipt' ? 'text-success' : 'text-danger' }}">
                                            {{ $m->type == 'money_receipt' ? '+' : '-' }}৳{{ number_format($m->amount, 2) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ strtoupper($m->payment_method) }}</span>
                                            @if($m->payment_ref)
                                                <small class="d-block text-muted">{{ $m->payment_ref }}</small>
                                            @endif
                                        </td>
                                        <td>{!! $m->status_badge !!}</td>
                                        <td>
                                            @if($m->attachment && file_exists(public_path('upload/store_vouchers/' . $m->attachment)))
                                                <a href="{{ asset('upload/store_vouchers/' . $m->attachment) }}" target="_blank" class="btn btn-sm btn-outline-info" title="মেমোর ছবি দেখুন">
                                                    <i class="fa fa-paperclip"></i> মেমো
                                                </a>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.store.vouchers.print', $m->id) }}" target="_blank" class="btn btn-outline-secondary" title="প্রিন্ট স্লিপ">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                <a href="{{ route('admin.store.vouchers.destroy', $m->id) }}" class="btn btn-outline-danger" onclick="return confirm('আপনি কি নিশ্চিত যে এই রেকর্ডটি মুছে ফেলতে চান?');" title="মুছে ফেলুন">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="fa fa-money fa-3x mb-3 text-secondary d-block"></i>
                                            কোনো ক্যাশ মেমো বা মানি রিসিট রেকর্ড পাওয়া যায়নি।
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newCashMemoModal">
                                                    <i class="fa fa-plus me-1"></i> নতুন ক্যাশ মেমো / রিসিট যুক্ত করুন
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($memos->hasPages())
                    <div class="card-footer bg-white py-3">
                        {{ $memos->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
</div>

<!-- Add Cash Memo / Money Receipt Modal -->
<div class="modal fade" id="newCashMemoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.store.vouchers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="fa fa-plus-circle me-2"></i> নতুন ক্যাশ মেমো / মানি রিসিট এন্ট্রি</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">ডকুমেন্টের ধরন <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="cash_memo">ক্যাশ মেমো (দোকান থেকে কেনাকাটার মেমো)</option>
                                <option value="money_receipt">মানি রিসিট (অর্থ প্রাপ্তির রসিদ)</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">হিসাবের খাত / ক্যাটাগরি <span class="text-danger">*</span></label>
                            <select name="category" class="form-select" required>
                                <option value="store_purchase">স্টোর মালামাল ক্রয়</option>
                                <option value="stationery">খাতাপত্র ও অফিস স্টেশনারি</option>
                                <option value="furniture">আসবাবপত্র ক্রয়</option>
                                <option value="vehicle_maintenance">যানবাহন ও ফুয়েল বিল</option>
                                <option value="repair">মেরামত ও সার্ভিসিং</option>
                                <option value="office_expense">সাধারণ অফিস খরচ</option>
                                <option value="other">অন্যান্য</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">শিরোনাম / মেমোর বিষয় <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="যেমন: সততা স্টেশনারি থেকে খাতা-কলম ক্রয় মেমো, পুরোনো ব্যাটারি বিক্রয় মানি রিসিট" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">দোকান / ভেন্ডর / গ্রাহকের নাম</label>
                            <input type="text" name="vendor_customer_name" class="form-control" placeholder="দোকান বা ব্যক্তির নাম">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">মোট টাকার পরিমাণ (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label fw-bold">তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label fw-bold">পেমেন্ট মাধ্যম <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-select" required>
                                <option value="cash">ক্যাশ (Cash)</option>
                                <option value="bkash">বিকাশ (bKash)</option>
                                <option value="nagad">নগদ (Nagad)</option>
                                <option value="bank">ব্যাংক (Bank)</option>
                                <option value="cheque">চেক (Cheque)</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label fw-bold">মেমো / ট্রানজেকশন রেফারেন্স নং</label>
                            <input type="text" name="payment_ref" class="form-control" placeholder="যেমন: MEMO-882, TRX7711">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">মালামালের আইটেমভিত্তিক হিসাব (রেট ও কোয়ান্টিটি)</label>
                            <textarea name="item_details" rows="3" class="form-control" placeholder="যেমন:&#10;১. লেজার খাতা: ৫ পিস @ ৳৪০০ = ৳২,০০০&#10;২. প্রিন্টার পেপার: ২ বক্স @ ৳১,৮৫০ = ৳৩,৭০০"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">ক্যাশ মেমো / রিসিটের ছবি বা স্ক্যান কপি</label>
                            <input type="file" name="attachment" class="form-control" accept="image/*,application/pdf">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">অতিরিক্ত নোট / বিবরণ</label>
                            <textarea name="description" rows="2" class="form-control" placeholder="প্রয়োজনীয় কোনো তথ্য থাকলে লিখুন..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="fa fa-save me-1"></i> সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

