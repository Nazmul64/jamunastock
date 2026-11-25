@extends('layouts.frontend.app')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #1b88ce, #1469a0); padding: 60px 0;">
        <div class="container text-center text-white">
            <h2 style="color: #fff; font-size: 38px; font-weight: 700; margin-bottom: 10px;">Privacy Policy</h2>
            <p style="color: #e2e8f0; font-size: 16px; margin: 0;">Security and privacy policy for your personal information</p>
        </div>
    </div>

    <main class="site-main py-5">
        <div class="container">
            <div class="bg-white p-4 p-md-5 rounded shadow-sm" style="border: 1px solid #e2e8f0; line-height: 1.8; color: #444;">
                <h3 class="mb-4" style="color: #1b88ce; font-weight: 700;">Privacy Policy</h3>
                @if(setting('privacy_policy'))
                    <div>{!! setting('privacy_policy') !!}</div>
                @else
                    <p>Jamuna Stock Market PLC is committed to maintaining the confidentiality and integrity of your personal information.</p>
                    <ul>
                        <li>Your name, email address, and phone number are used strictly for account verification and communications.</li>
                        <li>We do not sell, rent, or share customer data with third parties.</li>
                        <li>You may update or correct your profile information at any time from your account dashboard.</li>
                    </ul>
                @endif
            </div>
        </div>
    </main>

</div>
@endsection
