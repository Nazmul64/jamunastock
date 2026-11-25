<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvestmentSector;
use App\Models\Investment\InvestmentPlan;
use App\Models\Investment\InvestmentInquiry;
use App\Services\InvestmentCalculationService;

class PublicInvestmentInquiryController extends Controller
{
    /**
     * Show 9 Sectors Overview Page
     */
    public function sectors()
    {
        $sectors = InvestmentSector::where('is_active', true)->orderBy('order_num')->get();
        return view('frontend.investments.sectors_showcase', compact('sectors'));
    }

    /**
     * Show single sector detail page (/investment/{slug})
     */
    public function sectorDetail($slug)
    {
        $sector = InvestmentSector::with(['plans' => function ($q) {
            $q->where('is_active', true)->orderBy('order_num');
        }])->where('slug', $slug)->firstOrFail();

        $allSectors = InvestmentSector::where('is_active', true)->where('id', '!=', $sector->id)->orderBy('order_num')->get();

        return view('frontend.investments.sector_detail_modern', compact('sector', 'allSectors'));
    }

    /**
     * Submit online inquiry from frontend form
     */
    public function submitInquiry(Request $request)
    {
        $validated = $request->validate([
            'sector_id' => 'required|exists:investment_sectors,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'proposed_amount' => 'required|numeric|min:1000',
            'duration_years' => 'required|integer|min:1|max:20',
            'preferred_plan' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);

        $inquiryCode = InvestmentCalculationService::generateInquiryCode();

        $inquiry = InvestmentInquiry::create([
            'inquiry_code' => $inquiryCode,
            'sector_id' => $validated['sector_id'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'proposed_amount' => $validated['proposed_amount'],
            'duration_years' => $validated['duration_years'],
            'preferred_plan' => $validated['preferred_plan'] ?? null,
            'message' => $validated['message'] ?? null,
            'status' => 'new',
        ]);

        return back()->with('inquiry_success', [
            'code' => $inquiryCode,
            'message' => "আপনার বিনিয়োগ আগ্রহ ও আবেদন সফলভাবে জমা হয়েছে। আমাদের ইনভেস্টর রিলেশনশিপ টিম দ্রুত আপনার সাথে যোগাযোগ করবে।"
        ]);
    }
}
