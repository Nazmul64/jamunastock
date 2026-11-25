@extends('layouts.frontend.app')

@section('title', 'Institutional Partnerships & Collaborations - Jamuna Stock Market PLC')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #063c19 0%, #0b5e28 50%, #04240f 100%); padding: 50px 0; border-bottom: 3px solid #d97706;">
        <div class="container text-center text-white">
            <span class="badge px-3 py-2 mb-2" style="background: rgba(217, 119, 6, 0.25); color: #fbbf24; border: 1px solid #d97706; font-size: 13px; font-weight: 600;">Strategic Partnerships</span>
            <h2 style="color: #fff; font-size: 34px; font-weight: 800; margin-bottom: 10px;">Institutional & Project Partnerships</h2>
            <p style="color: #d1fae5; font-size: 15px; margin: 0; max-width: 700px; margin: 0 auto;">Commercial agreements, franchise hubs, and agro-industrial partnership opportunities with Jamuna Stock Market PLC.</p>
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

            <!-- Partnership Models Grid -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card h-100 border-0 rounded-4 shadow-sm p-4 bg-white" style="border-top: 4px solid #0b5e28 !important;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; background: #dcfce7; color: #0b5e28; font-size: 22px;">
                            <i class="fa fa-handshake"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="color: #063c19;">1. Franchise & Regional Hubs</h5>
                        <p class="text-secondary small" style="line-height: 1.8;">
                            Establishment of authorized Jamuna Service Centers and Collection Points at district and upazila levels. Opportunities for local representation and distribution.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 rounded-4 shadow-sm p-4 bg-white" style="border-top: 4px solid #d97706 !important;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; background: #fef3c7; color: #d97706; font-size: 22px;">
                            <i class="fa fa-warehouse"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="color: #92400e;">2. Integrated Cold Storage & Farming</h5>
                        <p class="text-secondary small" style="line-height: 1.8;">
                            Joint venture modern farming and cold storage infrastructure development with landowners and commercial developers on a 50-50 or contract basis.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 rounded-4 shadow-sm p-4 bg-white" style="border-top: 4px solid #2563eb !important;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; background: #dbeafe; color: #2563eb; font-size: 22px;">
                            <i class="fa fa-globe"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="color: #1e40af;">3. Import & Export Joint Ventures</h5>
                        <p class="text-secondary small" style="line-height: 1.8;">
                            Capital partnerships in international organic food exports and high-efficiency agricultural machinery imports.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Partnership Inquiry Form -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm" style="border: 1px solid #e2e8f0; border-top: 4px solid #0b5e28;">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold mb-1" style="color: #063c19;">Submit Partnership Proposal</h4>
                            <p class="text-muted small">Submit your corporate or individual proposal details below, and our business team will contact you promptly.</p>
                        </div>

                        <form action="{{ route('shareholders.partnerships.inquiry') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Representative Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="partner_name" class="form-control rounded-3" required placeholder="e.g. John Doe" value="{{ Auth::check() ? Auth::user()->name : '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Company Name (If applicable)</label>
                                    <input type="text" name="business_name" class="form-control rounded-3" placeholder="e.g. Green Agro Ltd.">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Partnership Type <span class="text-danger">*</span></label>
                                    <select name="partnership_type" class="form-select rounded-3" required>
                                        <option value="Franchise Hub">Regional Franchise or Service Hub</option>
                                        <option value="Agro Farming Joint Venture">Agro Farming Joint Venture</option>
                                        <option value="Cold Storage & Logistics">Cold Storage & Logistics Partner</option>
                                        <option value="Import & Export Partner">Import & Export Consortium</option>
                                        <option value="Corporate Investment">Corporate Investment</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Proposed Capital Contribution (BDT)</label>
                                    <input type="number" name="capital_contribution" class="form-control rounded-3" placeholder="e.g. 500000">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Detailed Proposal & Objectives <span class="text-danger">*</span></label>
                                    <textarea name="terms_and_conditions" class="form-control rounded-3" rows="4" required placeholder="Describe your proposal, location, and envisioned partnership structure..."></textarea>
                                </div>
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn btn-success px-5 py-2 fw-bold rounded-pill shadow-sm" style="background: #0b5e28;">
                                        Submit Proposal &rarr;
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>

</div>
@endsection

