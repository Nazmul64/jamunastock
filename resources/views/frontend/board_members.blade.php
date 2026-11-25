@extends('layouts.frontend.app')

@section('title', 'Board of Directors & Executives - Jamuna Stock Market PLC')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #063c19 0%, #0b5e28 50%, #04240f 100%); padding: 50px 0; border-bottom: 3px solid #d97706;">
        <div class="container text-center text-white">
            <span class="badge px-3 py-2 mb-2" style="background: rgba(217, 119, 6, 0.25); color: #fbbf24; border: 1px solid #d97706; font-size: 13px; font-weight: 600;">Leadership & Governance</span>
            <h2 style="color: #fff; font-size: 34px; font-weight: 800; margin-bottom: 10px;">Board of Directors & Executive Council</h2>
            <p style="color: #d1fae5; font-size: 15px; margin: 0; max-width: 700px; margin: 0 auto;">Guided by experienced and visionary leadership, Jamuna Stock Market PLC operates on the principles of integrity and accountability.</p>
        </div>
    </div>

    <main class="site-main py-5" style="background: #f8fafc;">
        <div class="container">

            <!-- Filter Buttons -->
            <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
                <a href="{{ route('corporate.board') }}" class="btn btn-sm px-4 py-2 fw-semibold shadow-sm {{ empty($roleFilter) ? 'btn-success text-white' : 'btn-outline-secondary' }}" style="border-radius: 20px; {{ empty($roleFilter) ? 'background: #0b5e28;' : '' }}">All Members ({{ $totalMembers }})</a>
                <a href="{{ route('corporate.board', ['role' => 'chairman']) }}" class="btn btn-sm px-4 py-2 fw-semibold shadow-sm {{ $roleFilter == 'chairman' ? 'btn-success text-white' : 'btn-outline-secondary' }}" style="border-radius: 20px; {{ $roleFilter == 'chairman' ? 'background: #0b5e28;' : '' }}">Chairman</a>
                <a href="{{ route('corporate.board', ['role' => 'md']) }}" class="btn btn-sm px-4 py-2 fw-semibold shadow-sm {{ $roleFilter == 'md' ? 'btn-success text-white' : 'btn-outline-secondary' }}" style="border-radius: 20px; {{ $roleFilter == 'md' ? 'background: #0b5e28;' : '' }}">Managing Director</a>
                <a href="{{ route('corporate.board', ['role' => 'ceo']) }}" class="btn btn-sm px-4 py-2 fw-semibold shadow-sm {{ $roleFilter == 'ceo' ? 'btn-success text-white' : 'btn-outline-secondary' }}" style="border-radius: 20px; {{ $roleFilter == 'ceo' ? 'background: #0b5e28;' : '' }}">CEO</a>
                <a href="{{ route('corporate.board', ['role' => 'marketing_head']) }}" class="btn btn-sm px-4 py-2 fw-semibold shadow-sm {{ $roleFilter == 'marketing_head' ? 'btn-success text-white' : 'btn-outline-secondary' }}" style="border-radius: 20px; {{ $roleFilter == 'marketing_head' ? 'background: #0b5e28;' : '' }}">Head of Marketing</a>
                <a href="{{ route('corporate.board', ['role' => 'finance_head']) }}" class="btn btn-sm px-4 py-2 fw-semibold shadow-sm {{ $roleFilter == 'finance_head' ? 'btn-success text-white' : 'btn-outline-secondary' }}" style="border-radius: 20px; {{ $roleFilter == 'finance_head' ? 'background: #0b5e28;' : '' }}">Head of Finance</a>
            </div>

            <!-- Members Grid -->
            <div class="row g-4 mb-5">
                @forelse($members as $m)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 rounded-4 shadow-sm text-center p-4 bg-white position-relative" style="border: 1px solid #e2e8f0; transition: transform 0.2s;">
                            <!-- Photo -->
                            <div class="mx-auto mb-3 position-relative" style="width: 130px; height: 130px;">
                                @if(!empty($m->photo))
                                    <img src="{{ asset($m->photo) }}" alt="{{ $m->name }}" class="rounded-circle object-fit-cover shadow-sm" style="width: 130px; height: 130px; border: 4px solid #0b5e28;">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-light text-success fw-bold shadow-sm" style="width: 130px; height: 130px; border: 4px solid #0b5e28; font-size: 42px;">
                                        <i class="fa fa-user-tie"></i>
                                    </div>
                                @endif
                                <span class="badge position-absolute bottom-0 end-0 bg-success rounded-pill px-2 py-1 shadow-sm" style="font-size: 11px;">Active</span>
                            </div>

                            <!-- Name & Designation -->
                            <h5 class="fw-bold mb-1" style="color: #063c19; font-size: 19px;">{{ $m->name }}</h5>
                            <span class="badge px-3 py-1 rounded-pill mx-auto mb-3" style="background: #dcfce7; color: #166534; font-size: 12px; font-weight: 600;">
                                {{ $m->designation }}
                            </span>

                            <!-- Bio / Summary -->
                            <p class="text-secondary small mb-3" style="line-height: 1.7; text-align: justify;">
                                {{ Str::limit($m->bio ?: 'Dedicated to institutional growth and the execution of strategic initiatives.', 140) }}
                            </p>

                            <!-- Speech Link (if any) -->
                            @if(!empty($m->speech_message))
                                <div class="mt-auto pt-3 border-top">
                                    <a href="{{ route('corporate.speeches') }}#speech-{{ $m->id }}" class="btn btn-outline-success btn-sm px-3 py-1 fw-semibold" style="border-radius: 15px;">
                                        <i class="fa fa-comment-dots me-1"></i>Read Message
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-5">
                        <i class="fa fa-users fa-3x text-secondary mb-3"></i>
                        <h5>No members found</h5>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $members->links() }}
            </div>

        </div>
    </main>

</div>
@endsection

