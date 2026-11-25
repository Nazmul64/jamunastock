@extends('layouts.frontend.app')

@section('title', 'Shareholder Profile & Guidelines - Jamuna Stock Market PLC')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #063c19 0%, #0b5e28 50%, #04240f 100%); padding: 50px 0; border-bottom: 3px solid #d97706;">
        <div class="container text-center text-white">
            <span class="badge px-3 py-2 mb-2" style="background: rgba(217, 119, 6, 0.25); color: #fbbf24; border: 1px solid #d97706; font-size: 13px; font-weight: 600;">Ownership & Equity</span>
            <h2 style="color: #fff; font-size: 34px; font-weight: 800; margin-bottom: 10px;">Shareholder Profile & Guidelines</h2>
            <p style="color: #d1fae5; font-size: 15px; margin: 0; max-width: 700px; margin: 0 auto;">Become an equity shareholder in Jamuna Stock Market PLC and share in long-term corporate growth and annual dividend distributions.</p>
        </div>
    </div>

    <main class="site-main py-5" style="background: #f8fafc;">
        <div class="container">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- 4 Stat Cards for Share Capital -->
            <div class="row g-4 mb-5">
                <div class="col-md-3 col-6">
                    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white text-center" style="border-top: 4px solid #0b5e28 !important;">
                        <span class="text-muted small fw-bold d-block mb-1">Total Shares</span>
                        <h3 class="fw-bold mb-0 text-dark" style="color: #063c19 !important;">{{ $setting->total_shares }}</h3>
                        <span class="badge bg-success-subtle text-success small mt-2" style="background: #dcfce7; color: #166534 !important;">Limited Share Capital</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white text-center" style="border-top: 4px solid #d97706 !important;">
                        <span class="text-muted small fw-bold d-block mb-1">Price Per Share</span>
                        <h3 class="fw-bold mb-0" style="color: #d97706 !important;">BDT {{ number_format($setting->price_per_share) }}</h3>
                        <span class="text-muted small mt-2 d-block">Office Cost: BDT {{ number_format($setting->office_expense_per_share) }}</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white text-center" style="border-top: 4px solid #2563eb !important;">
                        <span class="text-muted small fw-bold d-block mb-1">Total Share Capital</span>
                        <h3 class="fw-bold mb-0 text-dark">BDT {{ number_format($setting->total_share_capital) }}</h3>
                        <span class="badge bg-primary-subtle text-primary small mt-2" style="background: #dbeafe; color: #1d4ed8 !important;">Authorized Capital</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white text-center" style="border-top: 4px solid #10b981 !important;">
                        <span class="text-muted small fw-bold d-block mb-1">Available Shares</span>
                        <h3 class="fw-bold mb-0 text-success">{{ $availableShares }}</h3>
                        <span class="text-muted small mt-2 d-block">Allocated: {{ $allocatedCount }}</span>
                    </div>
                </div>
            </div>

            <!-- Shareholder Rights & Guidelines -->
            <div class="row g-4 mb-5">
                <div class="col-lg-7">
                    <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm h-100" style="border: 1px solid #e2e8f0;">
                        <h4 class="fw-bold mb-3" style="color: #063c19;"><i class="fa fa-award text-success me-2"></i>Shareholder Rights & Benefits</h4>
                        <ul class="text-secondary small mb-4" style="line-height: 2; font-size: 14px;">
                            <li><strong>Annual Dividend Distribution:</strong> Net annual profits from all sectors are distributed proportionally based on audited financial performance.</li>
                            <li><strong>Annual General Meeting (AGM):</strong> Right to participate, vote, and present operational recommendations in the company's AGM.</li>
                            <li><strong>Audited Financial Reports:</strong> Full transparency with access to certified annual balance sheets and profit & loss statements.</li>
                            <li><strong>Nominee & Inheritance Protection:</strong> Legally registered nominees inherit all capital equity and dividend entitlements seamlessly.</li>
                            <li><strong>Share Transferability:</strong> Shares can be transferred to another citizen or legal heir subject to board approval.</li>
                        </ul>

                        <div class="p-3 rounded-3" style="background: #f0fdf4; border-left: 4px solid #0b5e28; font-size: 13px; line-height: 1.8;">
                            <strong>Fee Structure:</strong> A one-time registration fee of BDT 2,000 and an administrative office cost of BDT 3,000 per share apply.
                        </div>
                    </div>
                </div>

                <!-- Application Form -->
                <div class="col-lg-5">
                    <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm" style="border: 1px solid #e2e8f0; border-top: 4px solid #0b5e28;">
                        <h4 class="fw-bold mb-1" style="color: #063c19;">Shareholder Application Form</h4>
                        <p class="text-muted small mb-4">Complete the form to become an equity partner with Jamuna Family</p>

                        @if($myProfile)
                            <div class="alert alert-success rounded-3 p-3">
                                <h6 class="fw-bold mb-1"><i class="fa fa-check-circle me-1"></i>Your Shareholder Profile is Active</h6>
                                <p class="small mb-1">Code: <strong>{{ $myProfile->shareholder_code }}</strong></p>
                                <p class="small mb-1">Shares: <strong>{{ $myProfile->shares_count }}</strong></p>
                                <p class="small mb-0">Status: <span class="badge bg-success">{{ $myProfile->status }}</span></p>
                            </div>
                        @else
                            @if(Auth::check())
                                <form action="{{ route('shareholders.apply') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Number of Shares <span class="text-danger">*</span></label>
                                        <input type="number" name="shares_count" id="sharesCountInput" class="form-control rounded-3" min="1" max="{{ max(1, $availableShares) }}" required value="1">
                                        <div class="form-text small">BDT 5,000 per share + BDT 3,000 office cost</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">National ID (NID) <span class="text-danger">*</span></label>
                                        <input type="text" name="nid_number" class="form-control rounded-3" required placeholder="Enter your NID number">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Nominee Name <span class="text-danger">*</span></label>
                                        <input type="text" name="nominee_name" class="form-control rounded-3" required placeholder="Full name of nominee">
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label small fw-bold">Nominee Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="nominee_phone" class="form-control rounded-3" required placeholder="017XXXXXXXX">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold">Relationship <span class="text-danger">*</span></label>
                                            <input type="text" name="nominee_relation" class="form-control rounded-3" required placeholder="Spouse / Son / Brother...">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Nominee NID Number</label>
                                        <input type="text" name="nominee_nid" class="form-control rounded-3" placeholder="Optional">
                                    </div>

                                    <div class="bg-light p-3 rounded-3 small mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Share Capital:</span>
                                            <strong id="calcShareAmount">BDT 5,000</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Office Cost:</span>
                                            <strong id="calcOfficeCost">BDT 3,000</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Registration Fee:</span>
                                            <strong>BDT 2,000</strong>
                                        </div>
                                        <hr class="my-1">
                                        <div class="d-flex justify-content-between text-success fw-bold">
                                            <span>Total Payable:</span>
                                            <span id="calcTotal">BDT 10,000</span>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold rounded-pill shadow-sm" style="background: #0b5e28;">
                                        Submit Shareholder Application &rarr;
                                    </button>
                                </form>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const countInput = document.getElementById('sharesCountInput');
                                        const calcShare = document.getElementById('calcShareAmount');
                                        const calcOffice = document.getElementById('calcOfficeCost');
                                        const calcTotal = document.getElementById('calcTotal');

                                        function updateCalc() {
                                            const count = parseInt(countInput.value) || 1;
                                            const shareAmt = count * 5000;
                                            const officeAmt = count * 3000;
                                            const total = shareAmt + officeAmt + 2000;

                                            calcShare.textContent = 'BDT ' + shareAmt.toLocaleString();
                                            calcOffice.textContent = 'BDT ' + officeAmt.toLocaleString();
                                            calcTotal.textContent = 'BDT ' + total.toLocaleString();
                                        }

                                        if (countInput) {
                                            countInput.addEventListener('input', updateCalc);
                                        }
                                    });
                                </script>
                            @else
                                <div class="text-center py-4">
                                    <i class="fa fa-lock fa-3x text-muted mb-3"></i>
                                    <h6 class="fw-bold mb-2">Login Required</h6>
                                    <p class="small text-muted mb-3">Please log in to your account to apply as a shareholder.</p>
                                    <a href="{{ route('login') }}" class="btn btn-success btn-sm rounded-pill px-4 fw-bold" style="background: #0b5e28;">Login &rarr;</a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </main>

</div>
@endsection

