@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-edit text-primary me-2"></i> মালামাল / সম্পদ সম্পাদনা (Edit Asset: {{ $asset->asset_code }})
                    </h3>
                    <p class="text-muted mb-0">{{ $asset->name }} - এর তথ্য ও বর্তমান বিবরণ পরিবর্তন করুন</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.store.assets.show', $asset->id) }}" class="btn btn-outline-info shadow-sm">
                        <i class="fa fa-eye me-1"></i> বিস্তারিত দেখুন
                    </a>
                    <a href="{{ route('admin.store.assets.index') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fa fa-arrow-left me-1"></i> তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-12">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="card-title fw-bold mb-0">সম্পদের বিবরণী আপডেট ফর্ম</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.store.assets.update', $asset->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">
                                    <!-- Asset Name -->
                                    <div class="col-md-8 col-12">
                                        <label class="form-label fw-bold">মালামাল / সম্পদের নাম <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $asset->name) }}" required>
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <!-- Category -->
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">ক্যাটাগরি <span class="text-danger">*</span></label>
                                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                            <option value="furniture" {{ old('category', $asset->category) == 'furniture' ? 'selected' : '' }}>আসবাবপত্র (Furniture)</option>
                                            <option value="vehicle" {{ old('category', $asset->category) == 'vehicle' ? 'selected' : '' }}>গাড়ি / যানবাহন (Vehicle)</option>
                                            <option value="stationery" {{ old('category', $asset->category) == 'stationery' ? 'selected' : '' }}>খাতাপত্র / স্টেশনারি (Stationery)</option>
                                            <option value="electronics" {{ old('category', $asset->category) == 'electronics' ? 'selected' : '' }}>ইলেকট্রনিক্স ও আইটি (Electronics)</option>
                                            <option value="equipment" {{ old('category', $asset->category) == 'equipment' ? 'selected' : '' }}>যন্ত্রপাতি ও টুলস (Equipment)</option>
                                            <option value="other" {{ old('category', $asset->category) == 'other' ? 'selected' : '' }}>অন্যান্য মালামাল (Other)</option>
                                        </select>
                                        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <!-- Asset Code -->
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">সম্পদ কোড (Asset Code)</label>
                                        <input type="text" name="asset_code" class="form-control" value="{{ old('asset_code', $asset->asset_code) }}">
                                    </div>

                                    <!-- Model / Brand -->
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">মডেল / ব্র্যান্ড / স্পেসিফিকেশন</label>
                                        <input type="text" name="model_no" class="form-control" value="{{ old('model_no', $asset->model_no) }}">
                                    </div>

                                    <!-- Serial / Reg No -->
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">সিরিয়াল নং / গাড়ি রেজিস্ট্রেশন নং</label>
                                        <input type="text" name="vehicle_reg_no" class="form-control" value="{{ old('vehicle_reg_no', $asset->vehicle_reg_no) }}">
                                    </div>

                                    <!-- Quantity & Unit -->
                                    <div class="col-md-3 col-6">
                                        <label class="form-label fw-bold">পরিমাণ (Quantity) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="quantity" class="form-control" value="{{ old('quantity', floatval($asset->quantity)) }}" required>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <label class="form-label fw-bold">একক (Unit)</label>
                                        <input type="text" name="unit" class="form-control" value="{{ old('unit', $asset->unit) }}">
                                    </div>

                                    <!-- Purchase Cost & Current Value -->
                                    <div class="col-md-3 col-6">
                                        <label class="form-label fw-bold">ক্রয়মূল্য (৳)</label>
                                        <input type="number" step="0.01" name="purchase_cost" class="form-control" value="{{ old('purchase_cost', $asset->purchase_cost) }}">
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <label class="form-label fw-bold">বর্তমান মূল্য (৳)</label>
                                        <input type="number" step="0.01" name="current_value" class="form-control" value="{{ old('current_value', $asset->current_value) }}">
                                    </div>

                                    <!-- Purchase Date & Vendor -->
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">ক্রয়ের তারিখ</label>
                                        <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '') }}">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">সরবরাহকারী / দোকান (Vendor Name)</label>
                                        <input type="text" name="vendor_name" class="form-control" value="{{ old('vendor_name', $asset->vendor_name) }}">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">ভেন্ডরের ফোন নম্বর</label>
                                        <input type="text" name="vendor_contact" class="form-control" value="{{ old('vendor_contact', $asset->vendor_contact) }}">
                                    </div>

                                    <!-- Location & Assigned Person -->
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold">অবস্থান / রুম নং (Location)</label>
                                        <input type="text" name="location" class="form-control" value="{{ old('location', $asset->location) }}">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold">দায়িত্বপ্রাপ্ত ব্যক্তি / ব্যবহারকারী (Custodian)</label>
                                        <input type="text" name="assigned_to" class="form-control" value="{{ old('assigned_to', $asset->assigned_to) }}">
                                    </div>

                                    <!-- Condition & Status -->
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold">বর্তমান অবস্থা (Condition)</label>
                                        <select name="condition" class="form-select">
                                            <option value="new" {{ old('condition', $asset->condition) == 'new' ? 'selected' : '' }}>নতুন (New)</option>
                                            <option value="good" {{ old('condition', $asset->condition) == 'good' ? 'selected' : '' }}>ভালো (Good)</option>
                                            <option value="fair" {{ old('condition', $asset->condition) == 'fair' ? 'selected' : '' }}>মোটামুটি (Fair)</option>
                                            <option value="needs_repair" {{ old('condition', $asset->condition) == 'needs_repair' ? 'selected' : '' }}>মেরামত প্রয়োজন (Needs Repair)</option>
                                            <option value="damaged" {{ old('condition', $asset->condition) == 'damaged' ? 'selected' : '' }}>অকেজো (Damaged)</option>
                                            <option value="disposed" {{ old('condition', $asset->condition) == 'disposed' ? 'selected' : '' }}>বাতিলকৃত (Disposed)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold">ব্যবহারের স্ট্যাটাস (Status)</label>
                                        <select name="status" class="form-select">
                                            <option value="in_use" {{ old('status', $asset->status) == 'in_use' ? 'selected' : '' }}>ব্যবহারাধীন (In Use)</option>
                                            <option value="in_store" {{ old('status', $asset->status) == 'in_store' ? 'selected' : '' }}>স্টোরে সংরক্ষিত (In Store)</option>
                                            <option value="under_maintenance" {{ old('status', $asset->status) == 'under_maintenance' ? 'selected' : '' }}>মেরামত চলছে (Under Maintenance)</option>
                                            <option value="scrapped" {{ old('status', $asset->status) == 'scrapped' ? 'selected' : '' }}>স্ক্র্যাপ / পরিত্যক্ত (Scrapped)</option>
                                        </select>
                                    </div>

                                    <!-- Image Upload & Current Image Preview -->
                                    <div class="col-12">
                                        <label class="form-label fw-bold">মালামাল / সম্পদের ছবি পরিবর্তন করুন</label>
                                        @if($asset->image && file_exists(public_path('upload/store_assets/' . $asset->image)))
                                            <div class="mb-2">
                                                <img src="{{ asset('upload/store_assets/' . $asset->image) }}" alt="Asset" class="rounded shadow-sm" style="max-height: 80px;">
                                                <span class="text-muted ms-2 small">বর্তমান ছবি</span>
                                            </div>
                                        @endif
                                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <!-- Notes -->
                                    <div class="col-12">
                                        <label class="form-label fw-bold">অতিরিক্ত নোট / বিবরণ</label>
                                        <textarea name="notes" rows="3" class="form-control">{{ old('notes', $asset->notes) }}</textarea>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.store.assets.index') }}" class="btn btn-light px-4">বাতিল</a>
                                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                                        <i class="fa fa-save me-1"></i> আপডেট সংরক্ষণ করুন
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

