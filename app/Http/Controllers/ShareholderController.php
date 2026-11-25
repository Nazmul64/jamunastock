<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShareSetting;
use App\Models\ShareholderProfile;
use App\Models\Partnership;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class ShareholderController extends Controller
{
    /**
     * Display Shareholder Information & Application
     */
    public function index()
    {
        $setting = ShareSetting::first() ?? new ShareSetting([
            'total_shares' => 100,
            'price_per_share' => 5000,
            'total_share_capital' => 500000,
            'office_expense_per_share' => 3000,
            'allocated_shares' => 0,
            'registration_fee' => 2000,
        ]);

        $allocatedCount = ShareholderProfile::where('status', 'active')->sum('shares_count');
        $availableShares = max(0, $setting->total_shares - $allocatedCount);

        $myProfile = Auth::check() ? ShareholderProfile::where('user_id', Auth::id())->first() : null;

        return view('frontend.shareholders.index', compact('setting', 'allocatedCount', 'availableShares', 'myProfile'));
    }

    /**
     * Submit Shareholder Application
     */
    public function apply(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to submit a shareholder application.');
        }

        $validated = $request->validate([
            'shares_count' => 'required|integer|min:1|max:100',
            'nid_number' => 'required|string|max:50',
            'nominee_name' => 'required|string|max:255',
            'nominee_phone' => 'required|string|max:20',
            'nominee_relation' => 'required|string|max:100',
            'nominee_nid' => 'nullable|string|max:50',
        ]);

        $setting = ShareSetting::first();
        $pricePerShare = $setting ? $setting->price_per_share : 5000;
        $officeCost = $setting ? ($setting->office_expense_per_share * $validated['shares_count']) : (3000 * $validated['shares_count']);
        $regFee = $setting ? $setting->registration_fee : 2000;
        $totalInvestment = $pricePerShare * $validated['shares_count'];

        $existing = ShareholderProfile::where('user_id', Auth::id())->first();
        if ($existing) {
            return back()->with('error', 'You already have an existing shareholder profile.');
        }

        $profile = ShareholderProfile::create([
            'user_id' => Auth::id(),
            'shareholder_code' => 'SH-' . rand(10000, 99999),
            'shares_count' => $validated['shares_count'],
            'total_investment' => $totalInvestment,
            'office_expense_paid' => $officeCost,
            'registration_fee_paid' => $regFee,
            'total_dividend_earned' => 0,
            'total_dividend_withdrawn' => 0,
            'available_dividend_balance' => 0,
            'nominee_name' => $validated['nominee_name'],
            'nominee_phone' => $validated['nominee_phone'],
            'nominee_relation' => $validated['nominee_relation'],
            'nominee_nid' => $validated['nominee_nid'] ?? null,
            'nid_number' => $validated['nid_number'],
            'status' => 'pending',
        ]);

        AuditLog::log('shareholder_application', 'ShareholderProfile', $profile->id, 'User submitted shareholder application');

        return back()->with('success', 'Your shareholder application has been received successfully. Our representative will contact you soon.');
    }

    /**
     * Display Partnership Opportunities
     */
    public function partnerships()
    {
        return view('frontend.shareholders.partnerships');
    }

    /**
     * Store Partnership Inquiry
     */
    public function storePartnershipInquiry(Request $request)
    {
        $validated = $request->validate([
            'partner_name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'partnership_type' => 'required|string|max:100',
            'capital_contribution' => 'nullable|numeric|min:0',
            'terms_and_conditions' => 'nullable|string',
        ]);

        $partnership = Partnership::create([
            'user_id' => Auth::check() ? Auth::id() : 1,
            'partner_code' => 'PRT-' . rand(1000, 9999),
            'partner_name' => $validated['partner_name'],
            'business_name' => $validated['business_name'] ?? '',
            'partnership_type' => $validated['partnership_type'],
            'capital_contribution' => $validated['capital_contribution'] ?? 0,
            'profit_share_percentage' => 0,
            'total_profit_shared' => 0,
            'status' => 'pending',
            'terms_and_conditions' => $validated['terms_and_conditions'] ?? '',
        ]);

        AuditLog::log('partnership_inquiry', 'Partnership', $partnership->id, 'New partnership inquiry from ' . $validated['partner_name']);

        return back()->with('success', 'Your partnership proposal has been submitted successfully. Our representative will contact you soon.');
    }
}

