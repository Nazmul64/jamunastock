@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-edit text-success me-2"></i>বিনিয়োগ খাত সম্পাদনা</h4>
                    <p class="text-muted mb-0">{{ $sector->name_bn }} এর তথ্য, সীমা ও মুনাফা নীতি আপডেট করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.investments.sectors.index') }}" class="btn btn-secondary btn-sm">
                        &larr; তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="box">
                <div class="box-body">
                    <form action="{{ route('admin.investments.sectors.update', $sector->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">খাতের নাম (বাংলা) <span class="text-danger">*</span></label>
                                <input type="text" name="name_bn" class="form-control" required value="{{ $sector->name_bn }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">খাতের নাম (ইংরেজি) <span class="text-danger">*</span></label>
                                <input type="text" name="name_en" class="form-control" required value="{{ $sector->name_en }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">বিনিয়োগ পরিসর (টেক্সট) <span class="text-danger">*</span></label>
                                <input type="text" name="investment_range" class="form-control" required value="{{ $sector->investment_range }}" placeholder="উদাঃ ২ লাখ - ১০ লাখ টাকা">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">সর্বনিম্ন বিনিয়োগ (টাকা) <span class="text-danger">*</span></label>
                                <input type="number" name="min_amount" class="form-control" required value="{{ $sector->min_amount }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">সর্বোচ্চ বিনিয়োগ (টাকা) <span class="text-danger">*</span></label>
                                <input type="number" name="max_amount" class="form-control" required value="{{ $sector->max_amount }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">প্রকল্প মেয়াদ <span class="text-danger">*</span></label>
                                <input type="text" name="duration" class="form-control" required value="{{ $sector->duration }}" placeholder="উদাঃ ১০০ দিন / কর্তৃপক্ষ নির্ধারিত">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">ক্রম নম্বর (Order)</label>
                                <input type="number" name="order_num" class="form-control" value="{{ $sector->order_num }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">ব্যানার ছবি</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>

                            <div class="col-12">
                                <label class="form-label font-weight-bold">খাত পরিচিতি ও বিবরণ</label>
                                <textarea name="description" class="form-control" rows="3">{{ $sector->description }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label font-weight-bold">সম্ভাব্য মুনাফা ও ঝুঁকি নীতি (Profit/Loss Policy)</label>
                                <textarea name="profit_loss_policy" class="form-control" rows="3">{{ $sector->profit_loss_policy }}</textarea>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCheck" {{ $sector->is_active ? 'checked' : '' }} value="1">
                                    <label class="form-check-label font-weight-bold" for="isActiveCheck">
                                        এই বিনিয়োগ খাত সক্রিয় রাখুন
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-success font-weight-bold px-4">আপডেট করুন</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

