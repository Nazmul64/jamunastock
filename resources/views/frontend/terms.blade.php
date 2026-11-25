@extends('layouts.frontend.app')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #1b88ce, #1469a0); padding: 60px 0;">
        <div class="container text-center text-white">
            <h2 style="color: #fff; font-size: 38px; font-weight: 700; margin-bottom: 10px;">Terms & Conditions</h2>
            <p style="color: #e2e8f0; font-size: 16px; margin: 0;">Terms and conditions for utilizing our services</p>
        </div>
    </div>

    <main class="site-main py-5">
        <div class="container">
            <div class="bg-white p-4 p-md-5 rounded shadow-sm" style="border: 1px solid #e2e8f0; line-height: 1.8; color: #444;">
                <h3 class="mb-4" style="color: #1b88ce; font-weight: 700;">Terms & Conditions</h3>
                @if(setting('terms_conditions'))
                    <div>{!! setting('terms_conditions') !!}</div>
                @else
                    <p>Welcome to Jamuna Stock Market PLC. By using our website and services, you agree to comply with the following terms and conditions:</p>
                    <ul>
                        <li>All stock information, market rates, and transactions are governed by standard operating policies.</li>
                        <li>Buy and sell requests are finalized upon verification and administrative review.</li>
                        <li>Providing misleading information or engaging in unauthorized activities is strictly prohibited.</li>
                    </ul>
                @endif
            </div>
        </div>
    </main>

</div>
@endsection
