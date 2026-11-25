@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-plus-circle text-success me-2"></i> নতুন পণ্য / আইটেম রেজিস্ট্রি (New Item Master)
                    </h3>
                    <p class="text-muted mb-0">সেন্ট্রাল স্টোরে নতুন আইটেম, বিবরণ, স্পেসিফিকেশন ও ওপেনিং ব্যালেন্স নথিবদ্ধ করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.store.items.index') }}" class="btn btn-secondary shadow-sm">
                        <i class="fa fa-arrow-left me-1"></i> পণ্য তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="card shadow-sm border-0">
                <form action="{{ route('admin.store.items.store') }}" method="POST">
                    @csrf
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-primary mb-3 border-bottom pb-2">
                            <i class="fa fa-info-circle me-1"></i> মৌলিক তথ্য (Basic Details)
                        </h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">আইটেম কোড <span class="text-danger">*</span></label>
                                <input type="text" name="item_code" class="form-control font-monospace" value="{{ $nextCode }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">বারকোড / কিউআর কোড</label>
                                <input type="text" name="barcode" class="form-control" placeholder="যেমন: 894110293">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">পণ্যের নাম (ইংরেজি) <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="যেমন: Executive Ballpen 0.5mm" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">পণ্যের নাম (বাংলা)</label>
                                <input type="text" name="bangla_name" class="form-control" placeholder="যেমন: বলপেন ০.৫ মিমি">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">ক্যাটেগরি <span class="text-danger">*</span></label>
                                <select name="erp_item_category_id" class="form-select" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">আইটেম ধরন <span class="text-danger">*</span></label>
                                <select name="item_type" class="form-select" required>
                                    <option value="consumable">কনজিউমেবল (Consumable - ব্যয়যোগ্য)</option>
                                    <option value="fixed_asset">স্থায়ী সম্পদ (Fixed Asset)</option>
                                    <option value="spare_part">যন্ত্রাংশ (Spare Part)</option>
                                    <option value="raw_material">কাঁচামাল (Raw Material)</option>
                                </select>
                            </div>
                        </div>

                        <h5 class="fw-bold text-primary mb-3 border-bottom pb-2">
                            <i class="fa fa-cubes me-1"></i> পরিমাপ ও ব্র্যান্ড স্পেসিফিকেশন
                        </h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">পরিমাপের একক (Unit) <span class="text-danger">*</span></label>
                                <select name="unit" class="form-select" required>
                                    <option value="Pcs">Pcs (টি / পিস)</option>
                                    <option value="Box">Box (বক্স / প্যাকেট)</option>
                                    <option value="Rim">Rim (রিম)</option>
                                    <option value="Dozen">Dozen (ডজন)</option>
                                    <option value="Kg">Kg (কেজি)</option>
                                    <option value="Ltr">Ltr (লিটার)</option>
                                    <option value="Set">Set (সেট)</option>
                                    <option value="Roll">Roll (রোল)</option>
                                    <option value="Carton">Carton (কার্টুন)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">ব্র্যান্ড / প্রস্তুতকারক</label>
                                <input type="text" name="brand" class="form-control" placeholder="যেমন: Matador, HP, Dell">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">মডেল নম্বর</label>
                                <input type="text" name="model_no" class="form-control" placeholder="যেমন: Laser 107a">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">স্ট্যান্ডার্ড / আনুমানিক একক মূল্য (৳)</label>
                                <input type="number" step="0.01" name="standard_cost" class="form-control" placeholder="0.00">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">বিস্তারিত স্পেসিফিকেশন</label>
                                <textarea name="specification" class="form-control" rows="2" placeholder="প্রযুক্তিগত বা শারীরিক বিবরণ"></textarea>
                            </div>
                        </div>

                        <h5 class="fw-bold text-primary mb-3 border-bottom pb-2">
                            <i class="fa fa-archive me-1"></i> প্রারম্ভিক স্টক ও স্টোর লোকেশন
                        </h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">সংরক্ষণাগার / স্টোর <span class="text-danger">*</span></label>
                                <select name="erp_store_id" class="form-select" required>
                                    @foreach($stores as $st)
                                        <option value="{{ $st->id }}">{{ $st->name }} ({{ $st->store_code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">প্রারম্ভিক স্টক (Opening Stock)</label>
                                <input type="number" step="0.01" name="opening_stock" class="form-control" value="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">রি-অর্ডার লেভেল (সতর্কবার্তা থ্রেশহোল্ড)</label>
                                <input type="number" step="0.01" name="reorder_level" class="form-control" value="5">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">সর্বনিম্ন মজুদ সীমা (Min Level)</label>
                                <input type="number" step="0.01" name="min_level" class="form-control" value="2">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">সর্বোচ্চ ধারণ ক্ষমতা (Max Level)</label>
                                <input type="number" step="0.01" name="max_level" class="form-control" value="500">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">অতিরিক্ত নোট / মন্তব্য</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="মন্তব্য"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light p-3 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.store.items.index') }}" class="btn btn-secondary">বাতিল</a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fa fa-save me-1"></i> পণ্য মাস্টার সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection

