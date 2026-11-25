@extends('layouts.frontend.app')

@section('title', 'Digital Membership Cards & Benefits - Jamuna Stock Market PLC')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #063c19 0%, #0b5e28 50%, #04240f 100%); padding: 50px 0; border-bottom: 3px solid #d97706;">
        <div class="container text-center text-white">
            <span class="badge px-3 py-2 mb-2" style="background: rgba(217, 119, 6, 0.25); color: #fbbf24; border: 1px solid #d97706; font-size: 13px; font-weight: 600;">Smart Membership Services</span>
            <h2 style="color: #fff; font-size: 34px; font-weight: 800; margin-bottom: 10px;">Digital Membership Cards & Exclusive Privileges</h2>
            <p style="color: #d1fae5; font-size: 15px; margin: 0; max-width: 700px; margin: 0 auto;">Partner with comprehensive commercial benefits and financial services through Jamuna SME Card, SME Gold Card, and Exclusive Property Card.</p>
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

            <!-- 3 Card Offerings Grid -->
            <div class="row g-4 mb-5">
                @foreach($cards as $c)
                    <div class="col-lg-4 col-md-6" id="{{ $c['type'] }}-card">
                        <div class="card h-100 border-0 rounded-4 shadow-sm bg-white overflow-hidden d-flex flex-column" style="border: 1px solid #e2e8f0; transition: transform 0.2s;">
                            <!-- Top Visual Card Mockup -->
                            <div class="p-4 text-white position-relative" style="background: {{ $c['type'] == 'gold' ? 'linear-gradient(135deg, #b45309, #d97706, #78350f)' : ($c['type'] == 'property' ? 'linear-gradient(135deg, #1e3a8a, #2563eb, #172554)' : 'linear-gradient(135deg, #063c19, #0b5e28, #04240f)') }}; border-radius: 16px; margin: 15px;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge px-3 py-1 rounded-pill" style="background: rgba(255,255,255,0.2); font-size: 11px;">{{ $c['badge'] }}</span>
                                    <i class="fa fa-wifi fa-rotate-90" style="opacity: 0.7;"></i>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-white p-1 me-2" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="fa fa-leaf text-success"></i>
                                    </div>
                                    <span class="fw-bold tracking-wide" style="font-size: 14px;">JAMUNA STOCK PLC</span>
                                </div>
                                <h4 class="fw-bold mb-1" style="font-size: 18px; letter-spacing: 0.5px;">{{ $c['name_bn'] }}</h4>
                                <div class="font-monospace small mb-3" style="letter-spacing: 2px; opacity: 0.85;">•••• •••• •••• {{ rand(1000, 9999) }}</div>
                                <div class="d-flex justify-content-between align-items-end small">
                                    <div>
                                        <span class="d-block text-white-50" style="font-size: 10px;">CARD HOLDER</span>
                                        <span class="fw-bold">{{ Auth::check() ? Auth::user()->name : 'VALUED MEMBER' }}</span>
                                    </div>
                                    <div class="text-end">
                                        <span class="d-block text-white-50" style="font-size: 10px;">VALID THRU</span>
                                        <span class="fw-bold">12/29</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Body & Features -->
                            <div class="p-4 pt-2 d-flex flex-column flex-grow-1">
                                <div class="text-center py-2 border-bottom mb-3">
                                    <span class="text-muted small d-block">Registration Fee</span>
                                    <h3 class="fw-bold text-dark mb-0" style="color: #063c19 !important;">{{ $c['fee'] }}</h3>
                                    <span class="text-muted small fst-italic">{{ $c['fee_sub'] }}</span>
                                </div>

                                <ul class="list-unstyled mb-4" style="line-height: 2; font-size: 14px; color: #334155;">
                                    @foreach($c['features'] as $f)
                                        <li class="d-flex align-items-start mb-2">
                                            <i class="fa fa-check-circle text-success mt-1 me-2"></i>
                                            <span>{{ $f }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="mt-auto">
                                    <button type="button" class="btn btn-success w-100 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#applyCardModal" data-cardtype="{{ $c['type'] }}" data-cardname="{{ $c['name_bn'] }}" style="border-radius: 20px; background: #0b5e28;">
                                        Apply for Card &rarr;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Regulatory & Nominee Policy Section -->
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm mb-5" style="border: 1px solid #e2e8f0; border-left: 6px solid #0b5e28;">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <h4 class="fw-bold mb-2" style="color: #063c19;"><i class="fa fa-info-circle text-success me-2"></i>Membership & Property Card Policies</h4>
                        <p class="text-muted small mb-3">Our commitment to legal protection, nominee security, and transparent accounting:</p>
                        <ul class="text-secondary small mb-0" style="line-height: 1.9;">
                            <li>Each cardholder is assigned a unique digital Customer ID and an online ledger account.</li>
                            <li>Full NID and nominee information for property cardholders will be stored in the database with enforceable legal rights.</li>
                            <li>Dividends or profits are systematically distributable based on specific project outcomes and audited financial statements.</li>
                        </ul>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="p-3 rounded-3" style="background: #f0fdf4; border: 1px dashed #0b5e28;">
                            <i class="fa fa-qrcode fa-3x text-success mb-2"></i>
                            <h6 class="fw-bold mb-1" style="color: #063c19;">QR Verification</h6>
                            <p class="text-muted small mb-0">Each card features a unique QR code that can be scanned via smartphone for instant authenticity verification.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

</div>

<!-- Application Modal -->
<div class="modal fade" id="applyCardModal" tabindex="-1" aria-labelledby="applyCardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #063c19, #0b5e28); border-radius: 16px 16px 0 0;">
                <h5 class="modal-title fw-bold" id="applyCardModalLabel">Card Registration Application</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('membership.apply') }}" method="POST">
                @csrf
                <input type="hidden" name="card_type" id="modalCardType" value="sme">
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 small rounded-3 mb-3">
                        Selected Card: <strong id="modalCardName">Jamuna SME Card</strong>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control rounded-3" value="{{ Auth::check() ? Auth::user()->phone : '' }}" required placeholder="017XXXXXXXX">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Present Address <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control rounded-3" rows="2" required placeholder="District, Thana, Village/Area"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">National ID Number (NID)</label>
                        <input type="text" name="nid_number" class="form-control rounded-3" placeholder="Optional, but required for Property Card">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Nominee Name</label>
                            <input type="text" name="nominee_name" class="form-control rounded-3" placeholder="Full name of nominee">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Relationship with Nominee</label>
                            <input type="text" name="nominee_relation" class="form-control rounded-3" placeholder="e.g. Spouse / Son / Brother">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold" style="background: #0b5e28;">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('applyCardModal');
        if (modal) {
            modal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const cardType = button.getAttribute('data-cardtype');
                const cardName = button.getAttribute('data-cardname');
                document.getElementById('modalCardType').value = cardType;
                document.getElementById('modalCardName').textContent = cardName;
            });
        }
    });
</script>
@endsection

