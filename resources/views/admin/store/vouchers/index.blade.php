@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-file-text-o text-success me-2"></i> বিল ও ভাউচার ব্যবস্থাপনা (Bills & Vouchers)
                    </h3>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের ডেবিট ভাউচার, ক্রেডিট ভাউচার ও সকল অফিস বিলের সমন্বিত ডিজিটাল হিসাব</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#newVoucherModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন ভাউচার / বিল তৈরি করুন
                    </button>
                    <a href="{{ route('admin.store.vouchers.cash_memos') }}" class="btn btn-outline-primary shadow-sm">
                        <i class="fa fa-money me-1"></i> ক্যাশ মেমো ও মানি রিসিট
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
                    <form method="GET" action="{{ route('admin.store.vouchers.index') }}" class="row g-2 align-items-center">
                        <div class="col-lg-4 col-md-6 col-12">
                            <input type="text" name="search" class="form-control" placeholder="ভাউচার নং, শিরোনাম, ভেন্ডর নাম বা রেফারেন্স..." value="{{ request('search') }}">
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <select name="type" class="form-select">
                                <option value="">সকল ধরন</option>
                                <option value="debit_voucher" {{ request('type') == 'debit_voucher' ? 'selected' : '' }}>ডেবিট ভাউচার (ব্যয়)</option>
                                <option value="credit_voucher" {{ request('type') == 'credit_voucher' ? 'selected' : '' }}>ক্রেডিট ভাউচার (আয়)</option>
                                <option value="bill" {{ request('type') == 'bill' ? 'selected' : '' }}>বিল (Bill)</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <select name="category" class="form-select">
                                <option value="">সকল খাত / ক্যাটাগরি</option>
                                <option value="furniture" {{ request('category') == 'furniture' ? 'selected' : '' }}>আসবাবপত্র ক্রয়/মেরামত</option>
                                <option value="vehicle_maintenance" {{ request('category') == 'vehicle_maintenance' ? 'selected' : '' }}>যানবাহন ও ফুয়েল খরচ</option>
                                <option value="stationery" {{ request('category') == 'stationery' ? 'selected' : '' }}>খাতাপত্র ও স্টেশনারি</option>
                                <option value="office_expense" {{ request('category') == 'office_expense' ? 'selected' : '' }}>সাধারণ অফিস খরচ</option>
                                <option value="store_purchase" {{ request('category') == 'store_purchase' ? 'selected' : '' }}>স্টোর সামগ্রী ক্রয়</option>
                                <option value="repair" {{ request('category') == 'repair' ? 'selected' : '' }}>মেরামত ও সার্ভিসিং</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6 col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-success w-100 fw-bold"><i class="fa fa-filter me-1"></i> ফিল্টার</button>
                            @if(request()->anyFilled(['search', 'type', 'category']))
                                <a href="{{ route('admin.store.vouchers.index') }}" class="btn btn-outline-secondary" title="রিসেট"><i class="fa fa-refresh"></i></a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Vouchers Table -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>ভাউচার নং ও তারিখ</th>
                                    <th>ধরন</th>
                                    <th>শিরোনাম ও খাত</th>
                                    <th>ভেন্ডর / গ্রাহক</th>
                                    <th>টাকার পরিমাণ (৳)</th>
                                    <th>পেমেন্ট মাধ্যম</th>
                                    <th>স্ট্যাটাস</th>
                                    <th>সংযুক্তি</th>
                                    <th class="text-center" style="width: 130px;">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vouchers as $v)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-dark d-block">{{ $v->voucher_no }}</span>
                                            <small class="text-muted">{{ $v->date ? $v->date->format('d/m/Y') : '' }}</small>
                                        </td>
                                        <td>{!! $v->type_badge !!}</td>
                                        <td>
                                            <strong class="text-dark">{{ $v->title }}</strong>
                                            <small class="text-muted d-block"><i class="fa fa-tag me-1"></i>{{ $v->category_label }}</small>
                                        </td>
                                        <td>{{ $v->vendor_customer_name ?: '—' }}</td>
                                        <td class="fw-bold fs-6 {{ $v->type == 'credit_voucher' ? 'text-success' : 'text-danger' }}">
                                            {{ $v->type == 'credit_voucher' ? '+' : '-' }}৳{{ number_format($v->amount, 2) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ strtoupper($v->payment_method) }}</span>
                                            @if($v->payment_ref)
                                                <small class="d-block text-muted">{{ $v->payment_ref }}</small>
                                            @endif
                                        </td>
                                        <td>{!! $v->status_badge !!}</td>
                                        <td>
                                            @if($v->attachment && file_exists(public_path('upload/store_vouchers/' . $v->attachment)))
                                                <a href="{{ asset('upload/store_vouchers/' . $v->attachment) }}" target="_blank" class="btn btn-sm btn-outline-info" title="সংযুক্তি দেখুন">
                                                    <i class="fa fa-paperclip"></i> ফাইল
                                                </a>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.store.vouchers.print', $v->id) }}" target="_blank" class="btn btn-outline-secondary" title="প্রিন্ট স্লিপ">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                <a href="{{ route('admin.store.vouchers.destroy', $v->id) }}" class="btn btn-outline-danger" onclick="return confirm('আপনি কি নিশ্চিত যে এই ভাউচারটি মুছে ফেলতে চান?');" title="মুছে ফেলুন">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="fa fa-file-text-o fa-3x mb-3 text-secondary d-block"></i>
                                            কোনো ভাউচার বা বিল পাওয়া যায়নি।
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#newVoucherModal">
                                                    <i class="fa fa-plus me-1"></i> নতুন ভাউচার তৈরি করুন
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($vouchers->hasPages())
                    <div class="card-footer bg-white py-3">
                        {{ $vouchers->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
</div>

<!-- Add Voucher Modal -->
<div class="modal fade" id="newVoucherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.store.vouchers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="fa fa-plus-circle me-2"></i> নতুন বিল / ভাউচার তৈরি করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">ভাউচারের ধরন <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="debit_voucher">ডেবিট ভাউচার (ব্যয় / পেমেন্ট)</option>
                                <option value="credit_voucher">ক্রেডিট ভাউচার (আয় / জমা)</option>
                                <option value="bill">অফিসিয়াল ব্যয় বিল (Bill)</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">হিসাবের খাত / ক্যাটাগরি <span class="text-danger">*</span></label>
                            <select name="category" class="form-select" required>
                                <option value="office_expense">সাধারণ অফিস খরচ</option>
                                <option value="furniture">আসবাবপত্র ক্রয়/মেরামত</option>
                                <option value="vehicle_maintenance">যানবাহন ও ফুয়েল খরচ</option>
                                <option value="stationery">খাতাপত্র ও অফিস স্টেশনারি</option>
                                <option value="store_purchase">স্টোর সামগ্রী ক্রয়</option>
                                <option value="repair">মেরামত ও রক্ষণাবেক্ষণ</option>
                                <option value="general_utility">ইউটিলিটি ও বিদ্যুৎ বিল</option>
                                <option value="other">অন্যান্য ব্যয়</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">ভাউচার / বিলের শিরোনাম <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="যেমন: নতুন অফিস চেয়ার ক্রয় ডেবিট ভাউচার, মাইক্রোবাস অকটেন বিল" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">ভেন্ডর / বিক্রেতা / প্রাপকের নাম</label>
                            <input type="text" name="vendor_customer_name" class="form-control" placeholder="দোকান বা ব্যক্তির নাম">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">টাকার পরিমাণ (৳) <span class="text-danger">*</span></label>
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
                                <option value="bank">ব্যাংক ট্রান্সফার (Bank)</option>
                                <option value="bkash">বিকাশ (bKash)</option>
                                <option value="nagad">নগদ (Nagad)</option>
                                <option value="cheque">ব্যাংক চেক (Cheque)</option>
                                <option value="other">অন্যান্য</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label fw-bold">চেক / ট্রানজেকশন রেফারেন্স নং</label>
                            <input type="text" name="payment_ref" class="form-control" placeholder="যেমন: CHQ-98124, TRX9812">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">মালামালের আইটেমভিত্তিক হিসাব / ব্রেকডাউন</label>
                            <textarea name="item_details" rows="2" class="form-control" placeholder="যেমন: ১. চেয়ার ২ পিস @ ৳৭,০০০ = ৳১৪,০০০&#10;২. রিকশাভাড়া = ৳৫০০"></textarea>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">অনুমোদনকারী কর্মকর্তা (Approved By)</label>
                            <input type="text" name="approved_by" class="form-control" placeholder="যেমন: ম্যানেজিং ডিরেক্টর / হেড অব একাউন্টস">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">ভাউচার / বিলের কপি আপলোড (ছবি বা PDF)</label>
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
                    <button type="submit" class="btn btn-success fw-bold"><i class="fa fa-save me-1"></i> ভাউচার সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

