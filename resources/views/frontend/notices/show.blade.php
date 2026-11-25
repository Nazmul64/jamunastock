@extends('layouts.frontend.app')

@section('title', $notice->title . ' - Jamuna Stock Market PLC')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #063c19 0%, #0b5e28 50%, #04240f 100%); padding: 45px 0; border-bottom: 3px solid #d97706;">
        <div class="container text-center text-white">
            <span class="badge px-3 py-1 mb-2 bg-warning text-dark fw-bold">{{ ucfirst($notice->notice_type) }}</span>
            <h2 style="color: #fff; font-size: 28px; font-weight: 800; margin-bottom: 8px;">{{ $notice->title }}</h2>
            <p style="color: #d1fae5; font-size: 14px; margin: 0;"><i class="fa fa-calendar-alt me-1"></i>Published Date: {{ $notice->publish_date ? $notice->publish_date->format('d M, Y') : '-' }}</p>
        </div>
    </div>

    <main class="site-main py-5" style="background: #f8fafc;">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-9">
                    <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm mb-4" style="border: 1px solid #e2e8f0; border-top: 4px solid #0b5e28;">
                        <div class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
                            <div>
                                <span class="text-muted small">Circular Reference:</span>
                                <strong>#NOT-{{ str_pad($notice->id, 4, '0', STR_PAD_LEFT) }}</strong>
                            </div>
                            <div>
                                <a href="{{ route('notices.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">&larr; All Notices</a>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="notice-content text-secondary" style="font-size: 16px; line-height: 1.9; text-align: justify;">
                            {!! nl2br(e($notice->content)) !!}
                        </div>

                        <!-- Attachment (if any) -->
                        @if(!empty($notice->attachment_file))
                            <div class="mt-5 p-4 rounded-3 bg-light border text-center">
                                <i class="fa fa-file-pdf fa-3x text-danger mb-2"></i>
                                <h6 class="fw-bold mb-1">Official File / Circular Attachment</h6>
                                <p class="text-muted small mb-3">Download the official attachment associated with this notice.</p>
                                <a href="{{ asset($notice->attachment_file) }}" target="_blank" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold">
                                    <i class="fa fa-download me-1"></i>Download File
                                </a>
                            </div>
                        @endif

                        <div class="mt-5 pt-3 border-top text-end">
                            <span class="small text-muted fst-italic">Administrative Authority, Jamuna Stock Market PLC</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</div>
@endsection

