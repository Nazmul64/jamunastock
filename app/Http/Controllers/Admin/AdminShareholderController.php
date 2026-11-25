<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShareSetting;
use App\Models\ShareholderProfile;
use App\Models\ShareTransaction;
use App\Models\Partnership;
use App\Models\AuditLog;

class AdminShareholderController extends Controller
{
    // ==========================================
    // 1. SHARE SETTINGS
    // ==========================================
    public function settingsIndex()
    {
        $setting = ShareSetting::first();
        if (!$setting) {
            $setting = ShareSetting::create([
                'total_shares' => 100,
                'price_per_share' => 5000,
                'total_share_capital' => 500000,
                'office_expense_per_share' => 3000,
                'allocated_shares' => 0,
                'registration_fee' => 2000,
            ]);
        }

        $allocatedCount = ShareholderProfile::where('status', 'active')->sum('shares_count');
        $setting->allocated_shares = $allocatedCount;
        $setting->save();

        return view('admin.shareholders.settings', compact('setting'));
    }

    public function settingsUpdate(Request $request)
    {
        $setting = ShareSetting::firstOrFail();
        $validated = $request->validate([
            'total_shares' => 'required|integer|min:1',
            'price_per_share' => 'required|numeric|min:0',
            'office_expense_per_share' => 'required|numeric|min:0',
            'registration_fee' => 'required|numeric|min:0',
        ]);

        $validated['total_share_capital'] = $validated['total_shares'] * $validated['price_per_share'];
        $setting->update($validated);

        AuditLog::log('share_settings_updated', 'ShareSetting', $setting->id, 'Updated share settings');

        return back()->with('success', 'শেয়ার সেটিংস সফলভাবে আপডেট হয়েছে।');
    }

    // ==========================================
    // 2. SHAREHOLDERS
    // ==========================================
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = ShareholderProfile::with('user');

        if ($status) {
            $query->where('status', $status);
        }

        $shareholders = $query->orderByDesc('id')->paginate(20);
        $totalCapital = ShareholderProfile::where('status', 'active')->sum('total_investment');
        $totalOfficeCost = ShareholderProfile::where('status', 'active')->sum('office_expense_paid');
        $users = \App\Models\User::orderBy('name')->get();

        return view('admin.shareholders.index', compact('shareholders', 'status', 'totalCapital', 'totalOfficeCost', 'users'));
    }

    public function storeShareholder(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'shares_count' => 'required|integer|min:1',
            'nid_number' => 'nullable|string',
            'nominee_name' => 'nullable|string',
            'nominee_phone' => 'nullable|string',
            'nominee_relation' => 'nullable|string',
            'status' => 'required|in:active,pending',
        ]);

        $setting = ShareSetting::first();
        $price = $setting ? $setting->price_per_share : 5000;
        $office = $setting ? ($setting->office_expense_per_share * $validated['shares_count']) : (3000 * $validated['shares_count']);
        $regFee = $setting ? $setting->registration_fee : 2000;

        $profile = ShareholderProfile::create([
            'user_id' => $validated['user_id'],
            'shareholder_code' => 'SH-' . rand(10000, 99999),
            'shares_count' => $validated['shares_count'],
            'total_investment' => $price * $validated['shares_count'],
            'office_expense_paid' => $office,
            'registration_fee_paid' => $regFee,
            'total_dividend_earned' => 0,
            'total_dividend_withdrawn' => 0,
            'available_dividend_balance' => 0,
            'nominee_name' => $validated['nominee_name'] ?? null,
            'nominee_phone' => $validated['nominee_phone'] ?? null,
            'nominee_relation' => $validated['nominee_relation'] ?? null,
            'nid_number' => $validated['nid_number'] ?? null,
            'status' => $validated['status'],
        ]);

        AuditLog::log('admin_created_shareholder', 'ShareholderProfile', $profile->id, 'Admin added shareholder');
        return back()->with('success', 'নতুন শেয়ারহোল্ডার সফলভাবে যুক্ত করা হয়েছে।');
    }

    public function updateStatus(Request $request, $id)
    {
        $shareholder = ShareholderProfile::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:pending,active,suspended,cancelled',
        ]);

        $shareholder->update($validated);
        AuditLog::log('shareholder_status_updated', 'ShareholderProfile', $shareholder->id, 'Status updated to ' . $validated['status']);

        return back()->with('success', 'শেয়ারহোল্ডারের স্ট্যাটাস সফলভাবে আপডেট হয়েছে।');
    }

    // ==========================================
    // 3. PARTNERSHIPS
    // ==========================================
    public function partnershipsIndex()
    {
        $partnerships = Partnership::with('user')->orderByDesc('id')->paginate(20);
        return view('admin.shareholders.partnerships', compact('partnerships'));
    }

    public function storePartnership(Request $request)
    {
        $validated = $request->validate([
            'partner_name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'partnership_type' => 'required|string',
            'capital_contribution' => 'nullable|numeric|min:0',
            'profit_share_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,pending',
            'terms_and_conditions' => 'nullable|string',
        ]);

        $partner = Partnership::create([
            'user_id' => auth()->id() ?? 1,
            'partner_code' => 'PRT-' . rand(1000, 9999),
            'partner_name' => $validated['partner_name'],
            'business_name' => $validated['business_name'] ?? '',
            'partnership_type' => $validated['partnership_type'],
            'capital_contribution' => $validated['capital_contribution'] ?? 0,
            'profit_share_percentage' => $validated['profit_share_percentage'] ?? 0,
            'total_profit_shared' => 0,
            'status' => $validated['status'],
            'terms_and_conditions' => $validated['terms_and_conditions'] ?? '',
        ]);

        AuditLog::log('admin_created_partnership', 'Partnership', $partner->id, 'Admin added partnership');
        return back()->with('success', 'নতুন পার্টনারশিপ সফলভাবে যুক্ত করা হয়েছে।');
    }

    public function updatePartnershipStatus(Request $request, $id)
    {
        $partner = Partnership::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:pending,active,completed,cancelled',
            'profit_share_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $partner->update($validated);
        AuditLog::log('partnership_status_updated', 'Partnership', $partner->id, 'Partnership status updated to ' . $validated['status']);

        return back()->with('success', 'পার্টনারশিপ স্ট্যাটাস সফলভাবে আপডেট হয়েছে।');
    }
}

