@extends('layouts.frontend.app')

@section('title', 'Leadership Messages - Jamuna Stock Market PLC')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #063c19 0%, #0b5e28 50%, #04240f 100%); padding: 50px 0; border-bottom: 3px solid #d97706;">
        <div class="container text-center text-white">
            <span class="badge px-3 py-2 mb-2" style="background: rgba(217, 119, 6, 0.25); color: #fbbf24; border: 1px solid #d97706; font-size: 13px; font-weight: 600;">Guidance & Strategic Vision</span>
            <h2 style="color: #fff; font-size: 34px; font-weight: 800; margin-bottom: 10px;">Leadership Messages</h2>
            <p style="color: #d1fae5; font-size: 15px; margin: 0; max-width: 700px; margin: 0 auto;">Key strategic insights, messages, and future roadmap from the Chairman, MD, CEO, and leadership team of Jamuna Stock Market PLC.</p>
        </div>
    </div>

    <main class="site-main py-5" style="background: #f8fafc;">
        <div class="container">

            <div class="row g-4">
                @forelse($speeches as $sp)
                    <div class="col-12" id="speech-{{ $sp->id }}">
                        <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-white mb-4 position-relative" style="border-left: 6px solid #0b5e28 !important; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
                            <div class="row align-items-center g-4">
                                <!-- Photo & Details -->
                                <div class="col-md-4 text-center border-end-md pb-4 pb-md-0">
                                    <div class="mx-auto mb-3 position-relative" style="width: 140px; height: 140px;">
                                        @if(!empty($sp->photo))
                                            <img src="{{ asset($sp->photo) }}" alt="{{ $sp->name }}" class="rounded-circle object-fit-cover shadow-sm" style="width: 140px; height: 140px; border: 4px solid #0b5e28;">
                                        @else
                                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-light text-success fw-bold shadow-sm" style="width: 140px; height: 140px; border: 4px solid #0b5e28; font-size: 46px;">
                                                <i class="fa fa-user-tie"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <h4 class="fw-bold mb-1" style="color: #063c19; font-size: 20px;">{{ $sp->name }}</h4>
                                    <span class="badge px-3 py-2 rounded-pill" style="background: #dcfce7; color: #166534; font-size: 13px; font-weight: 600;">
                                        {{ $sp->designation }}
                                    </span>
                                    @if(!empty($sp->administrative_role))
                                        <p class="text-secondary small mt-2 mb-0"><i class="fa fa-briefcase me-1"></i>{{ $sp->administrative_role }}</p>
                                    @endif
                                </div>

                                <!-- Speech Message -->
                                <div class="col-md-8 ps-md-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa fa-quote-left text-success fa-2x me-3"></i>
                                        <h4 class="fw-bold mb-0" style="color: #063c19; font-size: 22px;">{{ $sp->speech_title ?: 'Welcome & Keynote Address' }}</h4>
                                    </div>
                                    <div class="speech-body text-secondary" style="font-size: 15px; line-height: 1.9; text-align: justify;">
                                        {!! nl2br(e($sp->speech_message)) !!}
                                    </div>
                                    <div class="mt-4 pt-3 border-top text-end">
                                        <span class="small text-muted fst-italic">— Jamuna Stock Market PLC Family</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-5">
                        <i class="fa fa-comment-dots fa-3x text-secondary mb-3"></i>
                        <h5>No leadership messages are currently available.</h5>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('corporate.board') }}" class="btn btn-outline-success px-4 py-2 fw-semibold" style="border-radius: 25px;">&larr; Back to Board of Directors</a>
            </div>

        </div>
    </main>

</div>
@endsection

