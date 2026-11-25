@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-cogs text-success me-2"></i>শেয়ার মূলধন ও রেগুলেটরি সেটিংস</h4>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের অনুমোদিত মোট ১০০ শেয়ার, একক শেয়ার মূল্য ও ফি নির্ধারণ</p>
                </div>
                <div>
                    <a href="{{ route('admin.shareholders.index') }}" class="btn btn-secondary btn-sm">
                        শেয়ারহোল্ডার তালিকা &rarr;
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

            <div class="row">
                <div class="col-lg-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h5 class="box-title">শেয়ার কনফিগারেশন ফর্ম</h5>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('admin.shareholders.settings.update') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">অনুমোদিত মোট শেয়ার সংখ্যা <span class="text-danger">*</span></label>
                                    <input type="number" name="total_shares" class="form-control" required value="{{ $setting->total_shares }}">
                                    <div class="form-text small">স্পেসিফিকেশন অনুযায়ী নির্ধারিত ১০০ টি শেয়ার</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">প্রতি শেয়ারের মূল্য (টাকা) <span class="text-danger">*</span></label>
                                    <input type="number" name="price_per_share" class="form-control" required value="{{ $setting->price_per_share }}">
                                    <div class="form-text small">নির্ধারিত ৫,০০০ টাকা</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">প্রতি শেয়ারে অফিস খরচ (টাকা) <span class="text-danger">*</span></label>
                                    <input type="number" name="office_expense_per_share" class="form-control" required value="{{ $setting->office_expense_per_share }}">
                                    <div class="form-text small">নির্ধারিত ৩,০০০ টাকা</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">শেয়ারহোল্ডার প্রাথমিক রেজিস্ট্রেশন ফি (টাকা) <span class="text-danger">*</span></label>
                                    <input type="number" name="registration_fee" class="form-control" required value="{{ $setting->registration_fee }}">
                                    <div class="form-text small">নির্ধারিত ২,০০০ টাকা এককালীন</div>
                                </div>

                                <button type="submit" class="btn btn-success font-weight-bold px-4">সেটিংস সংরক্ষণ করুন</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h5 class="box-title">শেয়ার ক্যাপিটাল ও বর্তমান স্ট্যাটাস</h5>
                        </div>
                        <div class="box-body">
                            <ul class="list-group list-group-flush mb-4">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>মোট অনুমোদিত মূলধন:</span>
                                    <strong class="text-success fs-5">৳ {{ number_format($setting->total_share_capital) }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>মোট শেয়ার সংখ্যা:</span>
                                    <strong>{{ $setting->total_shares }} টি</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>বরাদ্দকৃত শেয়ার:</span>
                                    <strong class="text-primary">{{ $setting->allocated_shares }} টি</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>অবশিষ্ট খালি শেয়ার:</span>
                                    <strong class="text-warning">{{ max(0, $setting->total_shares - $setting->allocated_shares) }} টি</strong>
                                </li>
                            </ul>

                            <div class="p-3 bg-light rounded-3 small text-muted border">
                                <i class="fa fa-info-circle text-info me-1"></i>
                                স্পেসিফিকেশন অনুযায়ী প্রতিটি শেয়ার হস্তান্তরের ক্ষেত্রে অফিসিয়াল বোর্ড রেজুলেশন ও অডিট ছাড়পত্র বাধ্যতামূলক।
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

