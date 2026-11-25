@extends('layouts.frontend.app')

@section('title', 'Corporate Profile & Philosophy - Jamuna Stock Market PLC')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #063c19 0%, #0b5e28 50%, #04240f 100%); padding: 60px 0; border-bottom: 4px solid #d97706;">
        <div class="container text-center text-white">
            <span class="badge px-3 py-2 mb-2" style="background: rgba(217, 119, 6, 0.3); color: #fbbf24; border: 1px solid #d97706; font-size: 13px; font-weight: 700; letter-spacing: 1px;">
                CORPORATE PROFILE & INSTITUTIONAL PHILOSOPHY
            </span>
            <h1 style="color: #fff; font-size: 38px; font-weight: 800; margin-bottom: 12px; letter-spacing: 0.5px;">Jamuna Stock Market PLC</h1>
            <h5 style="color: #fef08a; font-size: 18px; font-weight: 600; margin-bottom: 15px; letter-spacing: 1px;">JAMUNA STOCK MARKET PLC &bull; Integrity &bull; Justice &bull; Commitment</h5>
            <p style="color: #d1fae5; font-size: 16px; margin: 0 auto; max-width: 820px; line-height: 1.7;">
                Prosperity through collective investment, advancement through production, and self-reliance through employment generation. A modern institutional economic blueprint integrating agriculture, industry, consumer goods, housing, and tourism.
            </p>
        </div>
    </div>

    <main class="site-main py-5" style="background: #f8fafc;">
        <div class="container">

            <!-- Quick Navigation Pills -->
            <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
                <a href="#intro" class="btn btn-outline-success btn-sm px-3 py-2 fw-semibold shadow-xs" style="border-radius: 20px;">1. Profile & Philosophy</a>
                <a href="#chairman" class="btn btn-outline-success btn-sm px-3 py-2 fw-semibold shadow-xs" style="border-radius: 20px;">2. Chairman's Message</a>
                <a href="#vision-mission" class="btn btn-outline-success btn-sm px-3 py-2 fw-semibold shadow-xs" style="border-radius: 20px;">3. Vision & Mission</a>
                <a href="#values" class="btn btn-outline-success btn-sm px-3 py-2 fw-semibold shadow-xs" style="border-radius: 20px;">4. Core Values</a>
                <a href="#sectors" class="btn btn-outline-success btn-sm px-3 py-2 fw-semibold shadow-xs" style="border-radius: 20px;">5. Economic Sectors</a>
                <a href="#projects" class="btn btn-outline-success btn-sm px-3 py-2 fw-semibold shadow-xs" style="border-radius: 20px;">6. Subsidiaries & Projects</a>
                <a href="#finance-shariah" class="btn btn-outline-success btn-sm px-3 py-2 fw-semibold shadow-xs" style="border-radius: 20px;">7. Shariah & Financial Governance</a>
                <a href="#marketing" class="btn btn-outline-success btn-sm px-3 py-2 fw-semibold shadow-xs" style="border-radius: 20px;">8. Marketing Philosophy</a>
                <a href="#sme-card" class="btn btn-outline-success btn-sm px-3 py-2 fw-semibold shadow-xs" style="border-radius: 20px;">9. SME Operations</a>
                <a href="#csr" class="btn btn-outline-success btn-sm px-3 py-2 fw-semibold shadow-xs" style="border-radius: 20px;">10. Social Responsibility (CSR)</a>
            </div>

            <!-- ==========================================
                 1. INSTITUTIONAL INTRODUCTION & PHILOSOPHY
                 ========================================== -->
            <section id="intro" class="mb-5 pb-4">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm h-100" style="border: 1px solid #e2e8f0; border-left: 6px solid #0b5e28;">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill me-2 fw-bold" style="background: #dcfce7; color: #166534 !important;">Corporate Philosophy</span>
                                <h3 class="mb-0 fw-bold" style="color: #063c19; font-size: 24px;">{{ $profiles['history']->title ?? 'Company Overview & Corporate Philosophy' }}</h3>
                            </div>
                            <div style="font-size: 15px; line-height: 1.9; color: #334155; text-align: justify;">
                                {!! nl2br(e($profiles['history']->content ?? ($profiles['history']->content_bn ?? 'Jamuna Stock Market PLC is an enterprise built upon investment, production, entrepreneurship development, and multifaceted economic initiatives. The core philosophy of the company is to expand productive economic activities through collective investment, create new employment opportunities, nurture small and medium entrepreneurs, and contribute to overall national economic progress.'))) !!}
                            </div>
                            <div class="mt-4 pt-3 border-top">
                                <span class="fw-bold text-success me-3"><i class="fa fa-check-circle me-1"></i> Integrity</span>
                                <span class="fw-bold text-success me-3"><i class="fa fa-check-circle me-1"></i> Justice</span>
                                <span class="fw-bold text-success"><i class="fa fa-check-circle me-1"></i> Commitment</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="p-4 bg-white rounded-4 shadow-sm text-center" style="border: 2px dashed #0b5e28;">
                            <div class="p-3 mb-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: #ecfdf5; color: #0b5e28; font-size: 38px;">
                                <i class="fa fa-handshake"></i>
                            </div>
                            <h5 class="fw-bold mb-1" style="color: #063c19;">Together for Prosperity</h5>
                            <p class="small text-muted mb-3">Prosperity through collective investment, advancement through production, and self-reliance through employment.</p>
                            <div class="p-3 text-start rounded-3" style="background: #f0fdf4; border-left: 4px solid #0b5e28;">
                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 14px;"><i class="fa fa-shield-alt text-success me-2"></i>Transparent & Accountable Governance</h6>
                                <p class="small text-muted mb-0">Every business sector operates under audited balance sheets, legal regulatory compliance, and responsible audit systems.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==========================================
                 2. CHAIRMAN'S MESSAGE
                 ========================================== -->
            @php $chairman = $boardMembers->where('role_type', 'chairman')->first() ?? $boardMembers->first(); @endphp
            @if($chairman)
            <section id="chairman" class="mb-5 pb-4">
                <div class="card border-0 rounded-4 shadow-sm overflow-hidden bg-white" style="border-top: 5px solid #0b5e28 !important; border: 1px solid #e2e8f0;">
                    <div class="row g-0 align-items-center">
                        <div class="col-lg-4 text-center p-4 p-md-5 bg-light border-end">
                            <div class="position-relative d-inline-block mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-success shadow-sm" style="width: 140px; height: 140px; background: #ecfdf5; border: 3px solid #0b5e28; font-size: 55px; margin: 0 auto;">
                                    <i class="fa fa-user-tie"></i>
                                </div>
                                <span class="badge position-absolute bottom-0 end-0 px-3 py-1 rounded-pill bg-success text-white shadow-sm" style="font-size: 11px;">Chairman</span>
                            </div>
                            <h4 class="fw-bold mb-1" style="color: #063c19;">{{ $chairman->name ?: $chairman->name_bn }}</h4>
                            <p class="text-muted fw-semibold small mb-2">{{ $chairman->designation ?: $chairman->designation_bn }}</p>
                            <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill" style="background: #dcfce7; color: #166534 !important;">Jamuna Stock Market PLC</span>
                            <div class="mt-3 text-start small text-muted">
                                <p class="mb-1"><i class="fa fa-envelope me-2 text-success"></i>{{ $chairman->email ?: 'chairman@jamunastock.com' }}</p>
                                <p class="mb-0"><i class="fa fa-phone me-2 text-success"></i>{{ $chairman->phone ?: '+880 1700-000001' }}</p>
                            </div>
                        </div>
                        <div class="col-lg-8 p-4 p-md-5">
                            <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-1 rounded-pill fw-bold mb-2" style="background: #fef3c7; color: #92400e !important;">Executive Leadership</span>
                            <h3 class="fw-bold mb-3" style="color: #063c19; font-size: 24px;">{{ $chairman->speech_title ?? 'Message from the Chairman' }}</h3>
                            <div class="text-secondary" style="font-size: 15px; line-height: 1.9; text-align: justify;">
                                {!! nl2br(e($chairman->speech_message)) !!}
                            </div>
                            <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="d-block text-dark">{{ $chairman->name ?: $chairman->name_bn }}</strong>
                                    <small class="text-muted">Chairman, Jamuna Stock Market PLC</small>
                                </div>
                                <span class="text-muted small fst-italic">"Trust in Investment, Momentum in Production, Prosperity in Employment"</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            @endif

            <!-- ==========================================
                 3. VISION, MISSION & OBJECTIVES
                 ========================================== -->
            <section id="vision-mission" class="mb-5 pb-4">
                <div class="text-center mb-4">
                    <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill fw-bold" style="background: #dcfce7; color: #166534 !important;">Future Roadmap</span>
                    <h3 class="fw-bold mt-2" style="color: #063c19; font-size: 26px;">Institutional Vision, Mission & Objectives</h3>
                    <p class="text-muted small">Committed to establishing a prosperous, self-reliant, and production-driven economic environment</p>
                </div>
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="h-100 p-4 bg-white rounded-4 shadow-sm" style="border: 1px solid #e2e8f0; border-top: 5px solid #0b5e28;">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: #dcfce7; color: #0b5e28; font-size: 22px;">
                                    <i class="fa fa-eye"></i>
                                </div>
                                <h4 class="fw-bold mb-0" style="color: #063c19;">Our Vision</h4>
                            </div>
                            <p style="font-size: 15px; line-height: 1.9; color: #334155; text-align: justify;">
                                To establish a prosperous, self-reliant, production-oriented, and employment-generating economic environment where people from all walks of life have equal opportunities to participate meaningfully in sustainable economic growth.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="h-100 p-4 bg-white rounded-4 shadow-sm" style="border: 1px solid #e2e8f0; border-top: 5px solid #d97706;">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: #fef3c7; color: #d97706; font-size: 22px;">
                                    <i class="fa fa-bullseye"></i>
                                </div>
                                <h4 class="fw-bold mb-0" style="color: #92400e;">Our Mission</h4>
                            </div>
                            <ol class="small text-secondary ps-3 mb-0" style="line-height: 1.9;">
                                <li>Expand productive economic sectors through collective and inclusive investment.</li>
                                <li>Provide comprehensive development and technical support to small and medium enterprises (SMEs).</li>
                                <li>Modernize agriculture and agro-processing industries with state-of-the-art technologies.</li>
                                <li>Create realistic, sustainable local employment opportunities across regions.</li>
                                <li>Produce and distribute hygienic, high-grade, adulteration-free food products.</li>
                                <li>Foster eco-friendly developments in real estate, housing communities, and tourism.</li>
                                <li>Ensure total financial transparency, good governance, and rigorous accountability.</li>
                                <li>Build a compassionate and humane Bangladesh through meaningful corporate social responsibility.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==========================================
                 4. CORE VALUES (8 PILLARS)
                 ========================================== -->
            <section id="values" class="mb-5 pb-4">
                <div class="text-center mb-4">
                    <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-1 rounded-pill fw-bold" style="background: #fef3c7; color: #92400e !important;">Core Values</span>
                    <h3 class="fw-bold mt-2" style="color: #063c19; font-size: 26px;">Guiding Principles & Organizational Values</h3>
                    <p class="text-muted small">Eight ethical pillars reflected across every business venture and service</p>
                </div>
                <div class="row g-3">
                    @php
                    $values = [
                        ['icon' => 'fa-shield-alt', 'title' => 'Integrity', 'desc' => 'Upholding absolute honesty, fiduciary trust, and ethical standards across all business operations.'],
                        ['icon' => 'fa-balance-scale', 'title' => 'Justice', 'desc' => 'Ensuring equitable treatment, fair rights, and transparent accounting for all members and stakeholders.'],
                        ['icon' => 'fa-handshake', 'title' => 'Commitment', 'desc' => 'Honoring promises and commitments to investors, clients, and partners punctually.'],
                        ['icon' => 'fa-file-invoice-dollar', 'title' => 'Transparency', 'desc' => 'Maintaining clear, verifiable, and open records across financial transactions and policies.'],
                        ['icon' => 'fa-check-double', 'title' => 'Accountability', 'desc' => 'Thorough accounting, auditing, and organizational responsibility for every capital allocation.'],
                        ['icon' => 'fa-building', 'title' => 'Good Governance', 'desc' => 'Professional leadership driven by an experienced, disciplined, and visionary Board of Directors.'],
                        ['icon' => 'fa-seedling', 'title' => 'Production', 'desc' => 'Prioritizing tangible production-driven initiatives over idle capital accumulation.'],
                        ['icon' => 'fa-heart', 'title' => 'Humanity', 'desc' => 'Active contribution toward social welfare, education, public health, and humanitarian support.'],
                    ];
                    @endphp
                    @foreach($values as $v)
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="p-3 bg-white rounded-4 shadow-xs h-100 border text-center" style="border-color: #e2e8f0 !important;">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px; background: #ecfdf5; color: #0b5e28; font-size: 20px;">
                                <i class="fa {{ $v['icon'] }}"></i>
                            </div>
                            <h6 class="fw-bold mb-1" style="color: #063c19;">{{ $v['title'] }}</h6>
                            <p class="text-muted small mb-0" style="line-height: 1.6;">{{ $v['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            <!-- ==========================================
                 5. ECONOMIC SECTORS (6 DOMAINS)
                 ========================================== -->
            <section id="sectors" class="mb-5 pb-4">
                <div class="text-center mb-4">
                    <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill fw-bold" style="background: #dcfce7; color: #166534 !important;">Business Scope</span>
                    <h3 class="fw-bold mt-2" style="color: #063c19; font-size: 26px;">Economic Activities & Business Sectors</h3>
                    <p class="text-muted small">Diverse investment and productive sectors of Jamuna Stock Market PLC</p>
                </div>
                <div class="row g-4">
                    @php
                    $sectors = [
                        ['title' => 'Agro & Agro-Processing', 'sub' => 'High-tech greenhouse cultivation of organic vegetables, fruits, and modern agro processing.', 'icon' => 'fa-tractor'],
                        ['title' => 'Food Production & FMCG', 'sub' => "'Teesta' brand premium aromatic rice, flour, oil, sugar, iodized salt, and pure spices.", 'icon' => 'fa-shopping-basket'],
                        ['title' => 'Fisheries & Livestock Farming', 'sub' => 'Modern dairy farming, poultry, freshwater aquaculture, and scientific farm management.', 'icon' => 'fa-paw'],
                        ['title' => 'Real Estate & Housing', 'sub' => "'Prokriti' modern residential plots, planned farmhouses, and contemporary apartment projects.", 'icon' => 'fa-city'],
                        ['title' => 'Hospitality & Eco-Tourism', 'sub' => 'Luxury eco-resorts, lakeview cottages, multi-cuisine restaurants, and family tourism facilities.', 'icon' => 'fa-hotel'],
                        ['title' => 'Import & Export Trade', 'sub' => 'Expanding international markets for premium domestic goods and cross-border trade operations.', 'icon' => 'fa-globe'],
                    ];
                    @endphp
                    @foreach($sectors as $sec)
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="p-4 bg-white rounded-4 shadow-sm h-100 border" style="border-left: 5px solid #0b5e28 !important;">
                            <div class="d-flex align-items-center mb-2">
                                <div class="rounded-3 p-2 me-3" style="background: #ecfdf5; color: #0b5e28; font-size: 22px;">
                                    <i class="fa {{ $sec['icon'] }}"></i>
                                </div>
                                <h6 class="fw-bold mb-0" style="color: #063c19;">{{ $sec['title'] }}</h6>
                            </div>
                            <p class="text-muted small mb-0" style="line-height: 1.7;">{{ $sec['sub'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            <!-- ==========================================
                 6. SUBSIDIARIES & 6 MAJOR PROJECTS
                 ========================================== -->
            <section id="projects" class="mb-5 pb-4">
                <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
                    <div>
                        <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-1 rounded-pill fw-bold" style="background: #fef3c7; color: #92400e !important;">Key Projects</span>
                        <h3 class="fw-bold mt-2 mb-0" style="color: #063c19; font-size: 26px;">Subsidiaries & Ongoing Projects</h3>
                    </div>
                    <span class="badge bg-white text-muted border px-3 py-2 shadow-xs fw-semibold">
                        Institutional Overview of Prokriti & Teesta Projects
                    </span>
                </div>

                <div class="row g-4">
                    @forelse($properties as $prop)
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card h-100 border-0 rounded-4 shadow-sm bg-white overflow-hidden d-flex flex-column" style="border: 1px solid #e2e8f0;">
                            <div class="p-4 text-white" style="background: linear-gradient(135deg, #063c19, #0b5e28);">
                                <span class="badge bg-warning text-dark px-2 py-1 mb-2 font-monospace" style="font-size: 11px;">{{ $prop->property_code }}</span>
                                <h5 class="fw-bold mb-1 text-white">{{ $prop->property_name }}</h5>
                                <small class="text-light" style="opacity: 0.85;">Sector: {{ $prop->property_type_name }}</small>
                            </div>
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <p class="text-secondary small mb-3" style="line-height: 1.7; text-align: justify;">
                                    {{ Str::limit(strip_tags($prop->description), 140) }}
                                </p>
                                <div class="p-3 rounded-3 bg-light mb-3">
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>Location:</span>
                                        <strong class="text-dark">{{ $prop->district }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>Area / Size:</span>
                                        <strong class="text-dark">{{ $prop->total_area }} {{ $prop->measurement_unit }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between small text-muted">
                                        <span>Booking Money:</span>
                                        <strong class="text-success">BDT {{ number_format($prop->booking_amount, 2) }}</strong>
                                    </div>
                                </div>
                                <div class="mt-auto">
                                    <a href="{{ route('membership.cards') }}" class="btn btn-outline-success btn-sm w-100 fw-bold py-2" style="border-radius: 20px;">
                                        Participate & Apply &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center text-muted py-4">No active projects available currently.</div>
                    @endforelse
                </div>
            </section>

            <!-- ==========================================
                 7. DIRECTOR OF FINANCE & SHARIAH GOVERNANCE
                 ========================================== -->
            @php $financeDirector = $boardMembers->where('role_type', 'director')->firstWhere('designation', 'Director of Finance') ?? $boardMembers->skip(1)->first(); @endphp
            @if($financeDirector)
            <section id="finance-shariah" class="mb-5 pb-4">
                <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden" style="border-left: 6px solid #1e3a8a !important; border: 1px solid #e2e8f0;">
                    <div class="p-4 p-md-5">
                        <div class="row g-4 align-items-center">
                            <div class="col-lg-8">
                                <span class="badge px-3 py-1 rounded-pill mb-2 fw-bold" style="background: #dbeafe; color: #1e40af;">Financial Philosophy & Governance</span>
                                <h3 class="fw-bold mb-2" style="color: #1e3a8a; font-size: 24px;">{{ $financeDirector->speech_title ?? 'Economic Prosperity Rooted in Integrity, Justice & Shariah-Compliant Investment' }}</h3>
                                <p class="text-muted small mb-3">Finance Director's Message &bull; {{ $financeDirector->name ?: $financeDirector->name_bn }}</p>
                                <div class="text-secondary" style="font-size: 15px; line-height: 1.85; text-align: justify;">
                                    {!! nl2br(e($financeDirector->speech_message)) !!}
                                </div>
                                <div class="mt-4 p-3 rounded-3 bg-light">
                                    <h6 class="fw-bold mb-2 text-dark"><i class="fa fa-balance-scale text-primary me-2"></i>Shariah-Compliant Principles & Transparency:</h6>
                                    <ul class="small text-muted mb-0 ps-3" style="line-height: 1.8;">
                                        <li>Asset-backed real business investments free from interest and deception.</li>
                                        <li>Independent project auditing and dual-entry accounting records.</li>
                                        <li>Fair profit sharing structured on genuine commercial outcomes and risk allocation.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-4 text-center bg-light p-4 rounded-4">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-primary shadow-sm mb-3" style="width: 120px; height: 120px; background: #eff6ff; border: 3px solid #1e3a8a; font-size: 48px;">
                                    <i class="fa fa-coins"></i>
                                </div>
                                <h5 class="fw-bold mb-1" style="color: #1e3a8a;">{{ $financeDirector->name ?: $financeDirector->name_bn }}</h5>
                                <span class="badge bg-primary text-white mb-3">{{ $financeDirector->designation ?: $financeDirector->designation_bn }}</span>
                                <p class="small text-muted mb-0">"Honest investment, journey to prosperity. Strength in production, freedom through employment."</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            @endif

            <!-- ==========================================
                 8. MARKETING DIRECTOR'S MESSAGE
                 ========================================== -->
            @php $marketingDirector = $boardMembers->where('role_type', 'director')->where('id', 3)->first() ?? $boardMembers->last(); @endphp
            @if($marketingDirector && $marketingDirector->id != ($financeDirector->id ?? 0))
            <section id="marketing" class="mb-5 pb-4">
                <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden" style="border-left: 6px solid #d97706 !important; border: 1px solid #e2e8f0;">
                    <div class="p-4 p-md-5">
                        <div class="row g-4 align-items-center">
                            <div class="col-lg-4 text-center bg-light p-4 rounded-4 order-lg-1 order-2">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-warning shadow-sm mb-3" style="width: 120px; height: 120px; background: #fef3c7; border: 3px solid #d97706; font-size: 48px;">
                                    <i class="fa fa-chart-line"></i>
                                </div>
                                <h5 class="fw-bold mb-1" style="color: #92400e;">{{ $marketingDirector->name ?: $marketingDirector->name_bn }}</h5>
                                <span class="badge bg-warning text-dark mb-3">{{ $marketingDirector->designation ?: $marketingDirector->designation_bn }}</span>
                                <p class="small text-muted mb-0">"Prosperity through collective effort, progress through production, self-reliance through employment."</p>
                            </div>
                            <div class="col-lg-8 order-lg-2 order-1">
                                <span class="badge px-3 py-1 rounded-pill mb-2 fw-bold" style="background: #fef3c7; color: #92400e;">Marketing Philosophy & Market Expansion</span>
                                <h3 class="fw-bold mb-2" style="color: #92400e; font-size: 24px;">{{ $marketingDirector->speech_title ?? 'Prosperity through Collective Effort, Progress in Production, Self-Reliance in Employment' }}</h3>
                                <p class="text-muted small mb-3">Marketing Director's Message &bull; {{ $marketingDirector->name ?: $marketingDirector->name_bn }}</p>
                                <div class="text-secondary" style="font-size: 15px; line-height: 1.85; text-align: justify;">
                                    {!! nl2br(e($marketingDirector->speech_message)) !!}
                                </div>
                                <div class="mt-4 p-3 rounded-3 bg-light">
                                    <h6 class="fw-bold mb-1 text-dark"><i class="fa fa-bullhorn text-warning me-2"></i>Ten Pillars of Marketing Policy:</h6>
                                    <small class="text-muted">Targeted Sales Strategy &bull; Skilled Workforce &bull; Market Analytics &bull; Premium Quality &bull; Fair Pricing &bull; Rapid Distribution &bull; Customer Satisfaction</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            @endif

            <!-- ==========================================
                 9. JAMUNA SME MARKET & MEMBERSHIP CARDS
                 ========================================== -->
            <section id="sme-card" class="mb-5 pb-4">
                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm" style="border: 1px solid #e2e8f0; border-top: 5px solid #0b5e28;">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-7">
                            <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill fw-bold mb-2" style="background: #dcfce7; color: #166534 !important;">SME Market & Cards</span>
                            <h3 class="fw-bold mb-2" style="color: #063c19; font-size: 24px;">Small Capital, Big Dreams — Jamuna Stock Market for Everyone</h3>
                            <p class="text-muted small mb-3">Start business with low capital, become an entrepreneur, and procure essential food commodities at affordable rates</p>
                            <ul class="small text-secondary ps-3 mb-4" style="line-height: 1.9;">
                                <li><strong>SME Card Fee:</strong> BDT 300 and Jamuna Service Passbook BDT 50 (Total BDT 350).</li>
                                <li><strong>Investment Range:</strong> Minimum BDT 5,000 to Maximum BDT 20,000.</li>
                                <li><strong>Withdrawal Policy:</strong> To withdraw full principal capital, apply 7 days in advance.</li>
                                <li><strong>Commodity Delivery:</strong> Collect from regional branches or notify 3 days prior for home delivery.</li>
                            </ul>
                            <a href="{{ route('membership.cards') }}" class="btn btn-success px-4 py-2 fw-bold" style="border-radius: 20px; background: #0b5e28;">
                                View Membership & Property Cards &rarr;
                            </a>
                        </div>
                        <div class="col-lg-5 text-center">
                            <div class="p-4 rounded-4 text-white position-relative shadow" style="background: linear-gradient(135deg, #063c19, #0b5e28, #04240f); border-radius: 16px;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-warning text-dark font-monospace" style="font-size: 11px;">JAMUNA SME CARD</span>
                                    <i class="fa fa-wifi fa-rotate-90" style="opacity: 0.8;"></i>
                                </div>
                                <h5 class="fw-bold mb-1" style="letter-spacing: 1px;">SME MEMBERSHIP CARD</h5>
                                <div class="font-monospace small my-3" style="letter-spacing: 2px;">1234 5678 9012 3456</div>
                                <div class="d-flex justify-content-between small text-white-50">
                                    <span>VALID: 12/2032</span>
                                    <span>JAMUNA STOCK PLC</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==========================================
                 10. CORPORATE SOCIAL RESPONSIBILITY (CSR)
                 ========================================== -->
            <section id="csr" class="mb-5 pb-4">
                <div class="p-4 p-md-5 rounded-4 shadow-sm text-white text-center" style="background: linear-gradient(135deg, #0b5e28, #063c19, #04240f); border: 2px solid #d97706;">
                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-2">Social Development Message</span>
                    <h2 class="fw-bold mb-2" style="font-size: 28px;">Corporate Social Responsibility for a Humane Nation</h2>
                    <p class="text-light small mx-auto mb-4" style="max-width: 720px; line-height: 1.8;">
                        Educational Aid &bull; Healthcare Support &bull; Affordable Food Supplies &bull; Vocational Training &bull; Poverty Alleviation &bull; Welfare for Underprivileged Communities. Jamuna Family's steadfast commitment to building a sustainable, empathetic, and self-sufficient future.
                    </p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('contact') }}" class="btn btn-warning px-4 py-2 fw-bold text-dark" style="border-radius: 25px;">Contact Us</a>
                        <a href="{{ route('corporate.board') }}" class="btn btn-outline-light px-4 py-2 fw-bold" style="border-radius: 25px;">Board of Directors Profile</a>
                    </div>
                </div>
            </section>

        </div>
    </main>

</div>
@endsection
