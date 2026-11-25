@extends('layouts.frontend.app')

@section('title', 'Our Team Members - Jamuna Stock Market PLC')

@section('content')
<div class="main-container">

    <!-- Hero Banner -->
    <div class="container-fluid no-left-padding no-right-padding team-hero-banner" style="background: linear-gradient(135deg, #06381a 0%, #0c5c2d 50%, #053316 100%); padding: 65px 0; border-bottom: 3px solid #f59e0b; position: relative;">
        <div class="container text-center text-white">
            <span class="badge px-4 py-2 text-uppercase mb-2 shadow-sm" style="background: rgba(245, 158, 11, 0.2); border: 1px solid #f59e0b; color: #fde68a; border-radius: 30px; font-size: 12.5px; font-weight: 700; letter-spacing: 1px;">
                <i class="fa fa-users me-1 text-warning"></i> Leadership & Professionals
            </span>
            <h1 style="color: #ffffff; font-size: 38px; font-weight: 800; margin-bottom: 12px; letter-spacing: 0.5px;">
                Our Team Members
            </h1>
            <p style="color: #e2e8f0; font-size: 16px; margin: 0 auto; max-width: 650px; line-height: 1.6;">
                Meet the passionate leadership and dedicated experts driving innovation and prosperity at Jamuna Stock Market PLC.
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="site-main py-5" style="background: #f8fafc; min-height: 550px;">
        <div class="container">

            <style>
                /* Team Card Animation & Hover Styling */
                @keyframes teamCardEntrance {
                    0% {
                        opacity: 0;
                        transform: translateY(35px) scale(0.96);
                    }
                    100% {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }

                .team-member-card {
                    background: #ffffff;
                    border: 1.5px solid #e2e8f0;
                    border-radius: 20px;
                    padding: 24px 20px;
                    text-align: center;
                    transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
                    position: relative;
                    overflow: hidden;
                    animation: teamCardEntrance 0.6s cubic-bezier(0.165, 0.84, 0.44, 1) both;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
                }

                .team-member-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 4px;
                    background: linear-gradient(90deg, #10b981, #f59e0b, #059669);
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }

                .team-member-card:hover {
                    transform: translateY(-8px);
                    border-color: #86efac;
                    box-shadow: 0 20px 35px -10px rgba(6, 78, 59, 0.18);
                }

                .team-member-card:hover::before {
                    opacity: 1;
                }

                /* Photo Frame */
                .team-photo-wrapper {
                    width: 170px;
                    height: 170px;
                    margin: 0 auto 18px;
                    border-radius: 50%;
                    padding: 5px;
                    background: linear-gradient(135deg, #10b981 0%, #059669 50%, #f59e0b 100%);
                    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.25);
                    transition: transform 0.35s ease, box-shadow 0.35s ease;
                }

                .team-member-card:hover .team-photo-wrapper {
                    transform: scale(1.05);
                    box-shadow: 0 15px 30px rgba(245, 158, 11, 0.35);
                }

                .team-photo-inner {
                    width: 100%;
                    height: 100%;
                    border-radius: 50%;
                    overflow: hidden;
                    background: #ffffff;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .team-photo-inner img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    display: block;
                    transition: transform 0.4s ease;
                }

                .team-member-card:hover .team-photo-inner img {
                    transform: scale(1.1);
                }

                .team-avatar-fallback {
                    width: 100%;
                    height: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
                    color: #065f46;
                    font-size: 55px;
                }

                /* Name & Designation */
                .team-member-name {
                    font-size: 20px;
                    font-weight: 800;
                    color: #064e3b;
                    margin: 0 0 6px;
                    letter-spacing: -0.2px;
                    transition: color 0.2s ease;
                }

                .team-member-card:hover .team-member-name {
                    color: #047857;
                }

                .team-member-designation {
                    display: inline-block;
                    font-size: 13.5px;
                    font-weight: 700;
                    color: #92400e;
                    background: #fef3c7;
                    border: 1px solid #fde68a;
                    padding: 4px 16px;
                    border-radius: 20px;
                    letter-spacing: 0.3px;
                }
            </style>

            <div class="row g-4 justify-content-center">
                @forelse($teamMembers as $member)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="team-member-card" style="animation-delay: {{ $loop->index * 0.08 }}s;">
                            
                            <!-- 1. Picture -->
                            <div class="team-photo-wrapper">
                                <div class="team-photo-inner">
                                    @if($member->photo && file_exists(public_path($member->photo)))
                                        <img src="{{ asset($member->photo) }}" alt="{{ $member->name }}">
                                    @else
                                        <div class="team-avatar-fallback">
                                            <i class="fa fa-user"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- 2. Name -->
                            <h4 class="team-member-name">
                                {{ $member->name }}
                            </h4>

                            <!-- 3. Designation -->
                            <div class="team-member-designation">
                                {{ $member->designation }}
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="p-5 bg-white rounded-4 shadow-sm" style="max-width: 500px; margin: 0 auto; border: 2px dashed #cbd5e1;">
                            <i class="fa fa-users text-muted mb-3" style="font-size: 48px;"></i>
                            <h5 class="fw-bold text-dark mb-2">No Team Members Found</h5>
                            <p class="text-muted small mb-0">Team members can be added anytime from the Admin Panel.</p>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </main>

</div>
@endsection
