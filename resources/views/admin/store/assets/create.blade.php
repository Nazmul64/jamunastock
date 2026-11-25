@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-plus-circle text-primary me-2"></i> নতুন মালামাল / সম্পদ যুক্ত করুন (Add Store Asset)
                    </h3>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের স্থায়ী সম্পদ, আসবাবপত্র, গাড়ি, স্টেশনারি বা স্টোর আইটেমের নতুন এন্ট্রি</p>
                </div>
                <a href="{{ route('admin.store.assets.index') }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="fa fa-arrow-left me-1"></i> তালিকায় ফিরে যান
                </a>
            </div>
        </div>

        <section class="content">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-12">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="card-title fw-bold mb-0">সম্পদের প্রাথমিক ও আর্থিক বিবরণী</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.store.assets.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">
                                    <!-- Asset Name -->
                                    <div class="col-md-8 col-12">
                                        <label class="form-label fw-bold">মালামাল / সম্পদের নাম <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="যেমন: কনফারেন্স টেবিল, টয়োটা নোয়া মাইক্রোবাস, লেজার খাতা ২০০ পৃষ্ঠা" value="{{ old('name') }}" required>
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <!-- Category -->
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">ক্যাটাগরি <span class="text-danger">*</span></label>
                                        <select name="category" class="form-select @error('category') is-invalid @enderror" required id="assetCategorySelect">
                                            <option value="">ক্যাটাগরি নির্বাচন করুন</option>
                                            <option value="furniture" {{ old('category') == 'furniture' ? 'selected' : '' }}>আসবাবপত্র (Furniture)</option>
                                            <option value="vehicle" {{ old('category') == 'vehicle' ? 'selected' : '' }}>গাড়ি / যানবাহন (Vehicle)</option>
                                            <option value="stationery" {{ old('category') == 'stationery' ? 'selected' : '' }}>খাতাপত্র / স্টেশনারি (Stationery)</option>
                                            <option value="electronics" {{ old('category') == 'electronics' ? 'selected' : '' }}>ইলেকট্রনিক্স ও আইটি (Electronics)</option>
                                            <option value="equipment" {{ old('category') == 'equipment' ? 'selected' : '' }}>যন্ত্রপাতি ও টুলস (Equipment)</option>
                                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>অন্যান্য মালামাল (Other)</option>
                                        </select>
                                        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <!-- Asset Code -->
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">সম্পদ কোড (Asset Code)</label>
                                        <input type="text" name="asset_code" class="form-control" placeholder="খালি রাখলে অটো জেনারেট হবে (যেমন: FUR-0012)" value="{{ old('asset_code') }}">
                                        <small class="text-muted">ঐচ্ছিক, নিজস্ব কোড থাকলে দিতে পারেন</small>
                                    </div>

                                    <!-- Model / Brand -->
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">মডেল / ব্র্যান্ড / স্পেসিফিকেশন</label>
                                        <input type="text" name="model_no" class="form-control" placeholder="যেমন: Hatil Regal, Dell OptiPlex, Bajaj 125" value="{{ old('model_no') }}">
                                    </div>

                                    <!-- Serial / Reg No -->
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">সিরিয়াল নং / গাড়ি রেজিস্ট্রেশন নং</label>
                                        <input type="text" name="vehicle_reg_no" class="form-control" placeholder="যেমন: ঢাকা মেট্রো-চ ৫৩-৮৮৯২ বা SN-98124" value="{{ old('vehicle_reg_no') }}">
                                    </div>

                                    <!-- Quantity & Unit -->
                                    <div class="col-md-3 col-6">
                                        <label class="form-label fw-bold">পরিমাণ (Quantity) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="quantity" class="form-control" value="{{ old('quantity', 1) }}" required>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <label class="form-label fw-bold">একক (Unit)</label>
                                        <input type="text" name="unit" class="form-control" placeholder="যেমন: টি, পিস, রিম, সেট, বক্স" value="{{ old('unit', 'টি') }}">
                                    </div>

                                    <!-- Purchase Cost & Current Value -->
                                    <div class="col-md-3 col-6">
                                        <label class="form-label fw-bold">ক্রয়মূল্য (৳)</label>
                                        <input type="number" step="0.01" name="purchase_cost" class="form-control" placeholder="0.00" value="{{ old('purchase_cost', 0) }}">
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <label class="form-label fw-bold">বর্তমান মূল্য (৳)</label>
                                        <input type="number" step="0.01" name="current_value" class="form-control" placeholder="0.00" value="{{ old('current_value', 0) }}">
                                    </div>

                                    <!-- Purchase Date & Vendor -->
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">ক্রয়ের তারিখ</label>
                                        <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', date('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">সরবরাহকারী / দোকান (Vendor Name)</label>
                                        <input type="text" name="vendor_name" class="form-control" placeholder="যেমন: অটোবি ফার্নিচার, সততা স্টেশনারি" value="{{ old('vendor_name') }}">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">ভেন্ডরের ফোন নম্বর</label>
                                        <input type="text" name="vendor_contact" class="form-control" placeholder="017XXXXXXXX" value="{{ old('vendor_contact') }}">
                                    </div>

                                    <!-- Location & Assigned Person -->
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold">অবস্থান / রুম নং (Location)</label>
                                        <input type="text" name="location" class="form-control" placeholder="যেমন: প্রধান কার্যালয় রুম ২০১, গ্যারেজ, স্টোর রুম শেলফ এ" value="{{ old('location') }}">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold">দায়িত্বপ্রাপ্ত ব্যক্তি / ব্যবহারকারী (Custodian)</label>
                                        <input type="text" name="assigned_to" class="form-control" placeholder="যেমন: ড্রাইভার কাশেম, একাউন্টস অফিসার, স্টোর কিপার" value="{{ old('assigned_to') }}">
                                    </div>

                                    <!-- Condition & Status -->
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold">বর্তমান অবস্থা (Condition)</label>
                                        <select name="condition" class="form-select">
                                            <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>নতুন (New)</option>
                                            <option value="good" {{ old('condition', 'good') == 'good' ? 'selected' : '' }}>ভালো (Good)</option>
                                            <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>মোটামুটি (Fair)</option>
                                            <option value="needs_repair" {{ old('condition') == 'needs_repair' ? 'selected' : '' }}>মেরামত প্রয়োজন (Needs Repair)</option>
                                            <option value="damaged" {{ old('condition') == 'damaged' ? 'selected' : '' }}>অকেজো (Damaged)</option>
                                            <option value="disposed" {{ old('condition') == 'disposed' ? 'selected' : '' }}>বাতিলকৃত (Disposed)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold">ব্যবহারের স্ট্যাটাস (Status)</label>
                                        <select name="status" class="form-select">
                                            <option value="in_use" {{ old('status', 'in_use') == 'in_use' ? 'selected' : '' }}>ব্যবহারাধীন (In Use)</option>
                                            <option value="in_store" {{ old('status') == 'in_store' ? 'selected' : '' }}>স্টোরে সংরক্ষিত (In Store)</option>
                                            <option value="under_maintenance" {{ old('status') == 'under_maintenance' ? 'selected' : '' }}>মেরামত চলছে (Under Maintenance)</option>
                                            <option value="scrapped" {{ old('status') == 'scrapped' ? 'selected' : '' }}>স্ক্র্যাপ / পরিত্যক্ত (Scrapped)</option>
                                        </select>
                                    </div>

                                    <!-- Image Upload -->
                                    <div class="col-12">
                                        <label class="form-label fw-bold">মালামাল / সম্পদের ছবি (যদি থাকে)</label>
                                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                        <small class="text-muted">অনুমোদিত ফরম্যাট: JPG, PNG, WEBP (সর্বোচ্চ ৩ মেগাবাইট)</small>
                                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <!-- Notes -->
                                    <div class="col-12">
                                        <label class="form-label fw-bold">অতিরিক্ত নোট / বিবরণ</label>
                                        <textarea name="notes" rows="3" class="form-control" placeholder="এই মালামাল বা সম্পদ সম্পর্কিত অতিরিক্ত কোনো তথ্য বা বিবরণ থাকলে এখানে লিখুন...">{{ old('notes') }}</textarea>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.store.assets.index') }}" class="btn btn-light px-4">বাতিল</a>
                                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                                        <i class="fa fa-save me-1"></i> সম্পদ সংরক্ষণ করুন
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

