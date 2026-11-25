@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">

        <!-- Breadcrumb & Back -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="{{ $selectedSector ? route('admin.investments.sector.dashboard', $selectedSector->slug) : route('admin.investments.central.dashboard') }}" 
                   class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ডে ফিরে যান
                </a>
            </div>
            @if($inquiry)
                <span class="badge bg-warning bg-opacity-10 text-warning border px-3 py-2 rounded-pill">
                    <i class="fa fa-link me-1"></i> অনলাইন ইনকোয়ারি <strong>#{{ $inquiry->inquiry_code }}</strong> থেকে রূপান্তর হচ্ছে
                </span>
            @endif
        </div>

        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
            <div class="card-header border-0 bg-light p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3 bg-success text-white">
                        <i class="fa fa-file-signature fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 text-dark">নতুন অফিসিয়াল বিনিয়োগ এন্ট্রি ও চুক্তি সম্পাদন</h4>
                        <p class="text-muted small mb-0">
                            গ্রাহকের তথ্য, সেক্টর, বিনিয়োগের পরিমাণ, লাভ বণ্টন পলিসি এবং চুক্তিনামা এন্ট্রি করুন। জমাদানের সাথে সাথে স্বয়ংক্রিয় মানি রসিদ ও লেজার এন্ট্রি তৈরি হবে।
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 p-lg-5">
                @if($errors->any())
                    <div class="alert alert-danger rounded-3 mb-4">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.investments.store') }}" method="POST" enctype="multipart/form-data" id="investmentForm">
                    @csrf
                    @if($inquiry)
                        <input type="hidden" name="inquiry_id" value="{{ $inquiry->id }}">
                    @endif

                    <!-- STEP 1: SECTOR SELECTION -->
                    <div class="card border border-light-subtle rounded-3 p-4 mb-4 bg-light">
                        <h5 class="fw-bold text-dark mb-3"><i class="fa fa-cubes text-success me-2"></i>১. বিনিয়োগ খাত (Investment Sector) নির্বাচন</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">বিনিয়োগ সেক্টর <span class="text-danger">*</span></label>
                                <select name="sector_id" id="sector_id" class="form-select form-select-lg" required>
                                    <option value="">-- সেক্টর নির্বাচন করুন --</option>
                                    @foreach($sectors as $sec)
                                        <option value="{{ $sec->id }}" 
                                                data-code="{{ $sec->code }}" 
                                                data-min="{{ $sec->min_amount }}" 
                                                data-max="{{ $sec->max_amount }}"
                                                data-prefix="{{ $sec->receipt_prefix }}"
                                                {{ (old('sector_id', $selectedSector->id ?? ($inquiry->sector_id ?? '')) == $sec->id) ? 'selected' : '' }}>
                                            {{ $sec->sector_no }}. {{ $sec->name_bn }} ({{ $sec->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">বিনিয়োগ স্কিম / প্ল্যান (Optional)</label>
                                <select name="plan_id" id="plan_id" class="form-select form-select-lg">
                                    <option value="">-- কাস্টম / সাধারণ চুক্তি --</option>
                                    @if($selectedSector)
                                        @foreach($selectedSector->plans as $p)
                                            <option value="{{ $p->id }}" 
                                                    data-years="{{ $p->duration_years }}" 
                                                    data-rate="{{ $p->profit_rate }}" 
                                                    data-freq="{{ $p->profit_frequency }}">
                                                {{ $p->name }} ({{ $p->duration_years }}Y - {{ $p->profit_rate }}% {{ $p->profit_frequency }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: CUSTOMER SELECTION (SECTION 4) -->
                    <div class="card border border-light-subtle rounded-3 p-4 mb-4 bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="fa fa-user-tie text-primary me-2"></i>২. গ্রাহকের প্রোফাইল (Customer Profile)</h5>
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="customer_type" id="type_existing" value="existing" {{ old('customer_type', $inquiry ? 'new' : 'existing') == 'existing' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary btn-sm" for="type_existing">বিদ্যমান গ্রাহক (Existing)</label>

                                <input type="radio" class="btn-check" name="customer_type" id="type_new" value="new" {{ old('customer_type', $inquiry ? 'new' : 'existing') == 'new' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary btn-sm" for="type_new">নতুন গ্রাহক (New Customer)</label>
                            </div>
                        </div>

                        <!-- Existing Customer Select -->
                        <div id="existingCustomerBox" class="{{ old('customer_type', $inquiry ? 'new' : 'existing') == 'existing' ? '' : 'd-none' }}">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">বিদ্যমান গ্রাহক নির্বাচন করুন <span class="text-danger">*</span></label>
                                <select name="customer_id" id="customer_id" class="form-select form-select-lg">
                                    <option value="">-- গ্রাহক সিলেক্ট করুন --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->customer_code }} - {{ $c->name }} ({{ $c->phone }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- New Customer Form Fields -->
                        <div id="newCustomerBox" class="{{ old('customer_type', $inquiry ? 'new' : 'existing') == 'new' ? '' : 'd-none' }}">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">গ্রাহকের পূর্ণ নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $inquiry->name ?? '') }}" placeholder="উদাঃ মোঃ রহিম আহমেদ">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                    <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone', $inquiry->phone ?? '') }}" placeholder="017XXXXXXXX">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">ইমেইল (Optional)</label>
                                    <input type="email" name="customer_email" class="form-control" value="{{ old('customer_email', $inquiry->email ?? '') }}" placeholder="email@example.com">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">জাতীয় পরিচয়পত্র (NID) নম্বর</label>
                                    <input type="text" name="customer_nid" class="form-control" value="{{ old('customer_nid') }}" placeholder="NID Number">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">পিতার নাম</label>
                                    <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">মাতার নাম</label>
                                    <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">বর্তমান ঠিকানা</label>
                                    <textarea name="customer_address" class="form-control" rows="2" placeholder="বর্তমান ঠিকানা">{{ old('customer_address', $inquiry->address ?? '') }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">পেশা</label>
                                    <input type="text" name="occupation" class="form-control" value="{{ old('occupation') }}" placeholder="ব্যবসা / চাকরি">
                                </div>
                                <!-- Nominee Details -->
                                <div class="col-12 border-top pt-3">
                                    <h6 class="fw-bold text-muted mb-2">নমিনি সংক্রান্ত তথ্য</h6>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-medium">নমিনির নাম</label>
                                    <input type="text" name="nominee_name" class="form-control" value="{{ old('nominee_name') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-medium">নমিনির সাথে সম্পর্ক</label>
                                    <input type="text" name="nominee_relation" class="form-control" value="{{ old('nominee_relation') }}" placeholder="স্ত্রী / ভাই / পুত্র">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-medium">নমিনির মোবাইল নম্বর</label>
                                    <input type="text" name="nominee_phone" class="form-control" value="{{ old('nominee_phone') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-medium">নমিনির NID নম্বর</label>
                                    <input type="text" name="nominee_nid" class="form-control" value="{{ old('nominee_nid') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: INVESTMENT DETAILS & DYNAMIC CALCULATOR (SECTION 5 & 6) -->
                    <div class="card border border-light-subtle rounded-3 p-4 mb-4 bg-light">
                        <h5 class="fw-bold text-dark mb-3"><i class="fa fa-money-check-alt text-success me-2"></i>৩. বিনিয়োগ বিবরণ ও চুক্তি শর্তাবলী</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">মূল বিনিয়োগের পরিমাণ (BDT) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white fw-bold">৳</span>
                                    <input type="number" step="any" name="amount" id="amount" class="form-control" 
                                           value="{{ old('amount', $inquiry->proposed_amount ?? 500000) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">চুক্তির মেয়াদ (বছর) <span class="text-danger">*</span></label>
                                <select name="duration_years" id="duration_years" class="form-select form-select-lg" required>
                                    @for($y = 1; $y <= 10; $y++)
                                        <option value="{{ $y }}" {{ old('duration_years', $inquiry->duration_years ?? 3) == $y ? 'selected' : '' }}>
                                            {{ $y }} বছর ({{ $y * 12 }} মাস)
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">চুক্তি শুরুর তারিখ <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date" class="form-control form-control-lg" 
                                       value="{{ old('start_date', date('Y-m-d')) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">মুনাফার ধরণ <span class="text-danger">*</span></label>
                                <select name="profit_type" id="profit_type" class="form-select">
                                    <option value="contractual" {{ old('profit_type') == 'contractual' ? 'selected' : '' }}>চুক্তিভিত্তিক (Contractual)</option>
                                    <option value="percentage" {{ old('profit_type') == 'percentage' ? 'selected' : '' }}>বার্ষিক শতকরা হার (Percentage)</option>
                                    <option value="fixed" {{ old('profit_type') == 'fixed' ? 'selected' : '' }}>ফিক্সড মুনাফা (Fixed Profit)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">বাৎসরিক মুনাফার হার (%) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="profit_rate" id="profit_rate" class="form-control" 
                                           value="{{ old('profit_rate', 15.00) }}" required>
                                    <span class="input-group-text bg-white">%</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">লাভ উত্তোলনের কিস্তি / ফ্রিকোয়েন্সি <span class="text-danger">*</span></label>
                                <select name="profit_frequency" id="profit_frequency" class="form-select">
                                    <option value="yearly" {{ old('profit_frequency') == 'yearly' ? 'selected' : '' }}>বার্ষিক (Yearly Profit)</option>
                                    <option value="monthly" {{ old('profit_frequency') == 'monthly' ? 'selected' : '' }}>মাসিক (Monthly Profit)</option>
                                    <option value="quarterly" {{ old('profit_frequency') == 'quarterly' ? 'selected' : '' }}>ত্রৈমাসিক (Quarterly)</option>
                                    <option value="at_maturity" {{ old('profit_frequency') == 'at_maturity' ? 'selected' : '' }}>মেয়াদান্তে এককালীন (At Maturity)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">টাকা গ্রহণের মাধ্যম <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="Cash">নগদ গ্রহণ (Cash in Hand)</option>
                                    <option value="Bank Transfer">ব্যাংক ট্রান্সফার / এনপিএসবি</option>
                                    <option value="Cheque">ব্যাংক চেক (Account Payee Cheque)</option>
                                    <option value="Mobile Banking">মোবাইল ব্যাংকিং (bKash/Nagad)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">স্বাক্ষরিত চুক্তিপত্র ফাইল (PDF/Image)</label>
                                <input type="file" name="agreement_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>

                        <!-- LIVE PROFIT PREVIEW BOX (SECTION 6 & 7) -->
                        <div class="rounded-4 p-4 mt-4 bg-white border border-success-subtle shadow-sm">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="fa fa-chart-line text-success fa-lg"></i>
                                <h6 class="fw-bold mb-0 text-dark">লাইভ আর্থিক হিসাব ও রিটার্ন প্রিভিউ (Calculation Preview)</h6>
                            </div>
                            <div class="row g-3 text-center">
                                <div class="col-md-3 col-6">
                                    <div class="text-muted small">মূল বিনিয়োগ</div>
                                    <h4 class="fw-bold text-dark my-1" id="previewPrincipal">৳০</h4>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="text-muted small">কিস্তি প্রতি প্রদেয় লাভ</div>
                                    <h4 class="fw-bold text-primary my-1" id="previewInstallment">৳০</h4>
                                    <small class="text-muted" id="previewInstallmentLabel">প্রতি বছর</small>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="text-muted small">মোট সম্ভাব্য মুনাফা</div>
                                    <h4 class="fw-bold text-info my-1" id="previewTotalProfit">৳০</h4>
                                    <small class="text-muted" id="previewDurationText">০ বছর মেয়াদে</small>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="text-muted small">মেয়াদ শেষে সর্বমোট পাওনা</div>
                                    <h4 class="fw-bold text-success my-1" id="previewTotalReturn">৳০</h4>
                                    <small class="badge bg-success bg-opacity-10 text-success">আসল + মোট লাভ</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-success btn-lg px-5 py-3 rounded-pill fw-bold shadow">
                            <i class="fa fa-check-circle me-2"></i> বিনিয়োগ অনুমোদন ও রসিদ তৈরি করুন
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle New / Existing customer
    const radioExisting = document.getElementById('type_existing');
    const radioNew = document.getElementById('type_new');
    const boxExisting = document.getElementById('existingCustomerBox');
    const boxNew = document.getElementById('newCustomerBox');

    function toggleCustomerType() {
        if (radioNew.checked) {
            boxNew.classList.remove('d-none');
            boxExisting.classList.add('d-none');
        } else {
            boxExisting.classList.remove('d-none');
            boxNew.classList.add('d-none');
        }
    }

    radioExisting.addEventListener('change', toggleCustomerType);
    radioNew.addEventListener('change', toggleCustomerType);

    // Dynamic Live Calculator
    const inputAmount = document.getElementById('amount');
    const selectDuration = document.getElementById('duration_years');
    const inputRate = document.getElementById('profit_rate');
    const selectFreq = document.getElementById('profit_frequency');

    const previewPrincipal = document.getElementById('previewPrincipal');
    const previewInstallment = document.getElementById('previewInstallment');
    const previewInstallmentLabel = document.getElementById('previewInstallmentLabel');
    const previewTotalProfit = document.getElementById('previewTotalProfit');
    const previewDurationText = document.getElementById('previewDurationText');
    const previewTotalReturn = document.getElementById('previewTotalReturn');

    function recalculate() {
        const amount = parseFloat(inputAmount.value) || 0;
        const years = parseInt(selectDuration.value) || 1;
        const rate = parseFloat(inputRate.value) || 0;
        const freq = selectFreq.value;

        const totalProfit = (amount * (rate / 100)) * years;
        const totalReturn = amount + totalProfit;

        let installmentProfit = 0;
        let label = 'প্রতি বছর';

        if (freq === 'yearly') {
            installmentProfit = amount * (rate / 100);
            label = 'প্রতি বছর';
        } else if (freq === 'monthly') {
            installmentProfit = (amount * (rate / 100)) / 12;
            label = 'প্রতি মাসে';
        } else if (freq === 'quarterly') {
            installmentProfit = (amount * (rate / 100)) / 4;
            label = 'প্রতি ৩ মাসে';
        } else if (freq === 'at_maturity') {
            installmentProfit = totalProfit;
            label = 'মেয়াদান্তে এককালীন';
        }

        previewPrincipal.innerText = '৳' + amount.toLocaleString('en-IN', {minimumFractionDigits: 0, maximumFractionDigits: 2});
        previewInstallment.innerText = '৳' + installmentProfit.toLocaleString('en-IN', {minimumFractionDigits: 0, maximumFractionDigits: 2});
        previewInstallmentLabel.innerText = label;
        previewTotalProfit.innerText = '৳' + totalProfit.toLocaleString('en-IN', {minimumFractionDigits: 0, maximumFractionDigits: 2});
        previewDurationText.innerText = years + ' বছর মেয়াদে';
        previewTotalReturn.innerText = '৳' + totalReturn.toLocaleString('en-IN', {minimumFractionDigits: 0, maximumFractionDigits: 2});
    }

    inputAmount.addEventListener('input', recalculate);
    selectDuration.addEventListener('change', recalculate);
    inputRate.addEventListener('input', recalculate);
    selectFreq.addEventListener('change', recalculate);

    recalculate();
});
</script>
@endsection
