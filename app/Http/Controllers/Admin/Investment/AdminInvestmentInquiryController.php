<?php

namespace App\Http\Controllers\Admin\Investment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvestmentSector;
use App\Models\Investment\InvestmentInquiry;
use App\Models\Investment\InvestmentPlan;
use App\Models\User;
use App\Models\AuditLog;

class AdminInvestmentInquiryController extends Controller
{
    /**
     * CRM Pipeline Inquiry Index
     */
    public function index(Request $request)
    {
        $query = InvestmentInquiry::with(['sector', 'plan', 'customer', 'assignee']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('sector_id')) {
            $query->where('sector_id', $request->sector_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('inquiry_code', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $inquiries = $query->orderByDesc('id')->paginate(15);
        $sectors = InvestmentSector::where('is_active', true)->orderBy('order_num')->get();
        $officers = User::all();

        // Count per status
        $statusCounts = [
            'all' => InvestmentInquiry::count(),
            'new' => InvestmentInquiry::where('status', 'new')->count(),
            'pending_contact' => InvestmentInquiry::where('status', 'pending_contact')->count(),
            'contacted' => InvestmentInquiry::where('status', 'contacted')->count(),
            'interested' => InvestmentInquiry::where('status', 'interested')->count(),
            'meeting_scheduled' => InvestmentInquiry::where('status', 'meeting_scheduled')->count(),
            'agreement_pending' => InvestmentInquiry::where('status', 'agreement_pending')->count(),
            'converted' => InvestmentInquiry::where('status', 'converted')->count(),
            'rejected' => InvestmentInquiry::where('status', 'rejected')->count(),
        ];

        return view('admin.investments.inquiries_index', compact('inquiries', 'sectors', 'officers', 'statusCounts'));
    }

    /**
     * Show single inquiry detail
     */
    public function show($id)
    {
        $inquiry = InvestmentInquiry::with(['sector', 'plan', 'customer', 'assignee', 'investment'])->findOrFail($id);
        $officers = User::all();

        return view('admin.investments.inquiry_detail', compact('inquiry', 'officers'));
    }

    /**
     * Update CRM Status & Notes
     */
    public function updateStatus(Request $request, $id)
    {
        $inquiry = InvestmentInquiry::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:new,pending_contact,contacted,interested,meeting_scheduled,meeting_completed,agreement_pending,converted,rejected,cancelled,follow_up',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $prevStatus = $inquiry->status;
        $inquiry->update($validated);

        AuditLog::log('inquiry_status_updated', 'InvestmentInquiry', $inquiry->id, "Inquiry {$inquiry->inquiry_code} updated from {$prevStatus} to {$validated['status']}");

        return back()->with('success', "ইনকোয়ারি স্ট্যাটাস সফলভাবে আপডেট করা হয়েছে।");
    }

    /**
     * Convert Inquiry to Investment Page
     */
    public function convertToInvestment($id)
    {
        $inquiry = InvestmentInquiry::with(['sector', 'plan'])->findOrFail($id);
        return redirect()->route('admin.investments.create', [
            'slug' => $inquiry->sector->slug,
            'inquiry_id' => $inquiry->id,
        ]);
    }
}
