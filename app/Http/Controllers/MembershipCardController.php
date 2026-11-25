<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardBenefit;
use App\Models\CardNumber;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class MembershipCardController extends Controller
{
    /**
     * Display Membership Cards & Benefits (SME Card, SME Gold Card, Property Card)
     * 100% Database-Driven from `card_benefits` table
     */
    public function index()
    {
        $cardBenefits = CardBenefit::where('status', 1)->orderBy('order_num')->get();
        $userCard = Auth::check() ? CardNumber::where('used_by', Auth::id())->first() : null;

        $cards = [];
        foreach ($cardBenefits as $cb) {
            $facilities = is_array($cb->facilities) ? $cb->facilities : (json_decode($cb->facilities, true) ?: []);
            $cards[] = [
                'id' => $cb->id,
                'type' => $cb->card_type,
                'name_bn' => $cb->card_name,
                'name_en' => $cb->card_name,
                'fee' => $cb->card_fee,
                'fee_sub' => $cb->investment_limit ?: 'Investment & Member Benefit',
                'badge' => $cb->badge_text ?: 'Official Membership',
                'color' => in_array($cb->card_type, ['property', 'partnership']) ? 'primary' : ($cb->card_type == 'sme_gold' ? 'warning' : 'success'),
                'features' => $facilities,
                'validity' => $cb->validity,
                'card_sample' => $cb->card_number_sample,
                'description' => $cb->short_description,
            ];
        }

        return view('frontend.membership_cards', [
            'benefits' => $cardBenefits,
            'userCard' => $userCard,
            'cards' => $cards,
        ]);
    }

    /**
     * Submit Membership Card Registration
     */
    public function apply(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to apply for a membership card.');
        }

        $validated = $request->validate([
            'card_type' => 'required|in:sme,gold,property',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'nid_number' => 'nullable|string|max:50',
            'nominee_name' => 'nullable|string|max:255',
            'nominee_relation' => 'nullable|string|max:100',
        ]);

        $cardNumber = 'JC' . rand(10000000, 99999999);
        $amount = $validated['card_type'] == 'property' ? 3000 : ($validated['card_type'] == 'gold' ? 1000 : 300);

        $card = CardNumber::create([
            'number' => $cardNumber,
            'amount' => $amount,
            'card_type' => $validated['card_type'],
            'is_used' => 1,
            'used_by' => Auth::id(),
            'used_at' => now(),
        ]);

        AuditLog::log('membership_card_apply', 'CardNumber', $card->id, 'User applied for ' . $validated['card_type'] . ' card');

        return back()->with('success', 'Your ' . strtoupper($validated['card_type']) . ' membership card application has been submitted successfully. Card ID: ' . $cardNumber);
    }
}
