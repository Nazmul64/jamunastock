<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerApplication;
use Illuminate\Http\Request;

class AdminCareerController extends Controller
{
    /**
     * Display list of career applications.
     */
    public function index(Request $request)
    {
        $query = CareerApplication::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(15);

        $counts = [
            'total'       => CareerApplication::count(),
            'pending'     => CareerApplication::where('status', 'pending')->count(),
            'reviewed'    => CareerApplication::where('status', 'reviewed')->count(),
            'shortlisted' => CareerApplication::where('status', 'shortlisted')->count(),
            'rejected'    => CareerApplication::where('status', 'rejected')->count(),
        ];

        return view('admin.careers.index', compact('applications', 'counts'));
    }

    /**
     * View application details (AJAX modal).
     */
    public function show($id)
    {
        $application = CareerApplication::findOrFail($id);

        if ($application->status === 'pending') {
            $application->update(['status' => 'reviewed']);
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'                => $application->id,
                'full_name'         => $application->full_name,
                'first_name'        => $application->first_name,
                'last_name'         => $application->last_name,
                'phone'             => $application->phone,
                'email'             => $application->email ?? 'N/A',
                'position'          => $application->position ?? 'N/A',
                'address'           => $application->address,
                'notes'             => $application->notes ?? 'কোনো মন্তব্য নেই',
                'status'            => $application->status,
                'admin_notes'       => $application->admin_notes ?? '',
                'resume_url'        => asset($application->resume_file),
                'original_filename' => $application->original_filename ?? 'Resume',
                'created_at'        => $application->created_at->format('d M, Y h:i A'),
            ]
        ]);
    }

    /**
     * Update application status and admin notes.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'      => 'required|in:pending,reviewed,shortlisted,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $application = CareerApplication::findOrFail($id);
        $application->update([
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'আবেদনের স্ট্যাটাস সফলভাবে আপডেট করা হয়েছে।'
            ]);
        }

        return redirect()->back()->with('success', 'আবেদনের স্ট্যাটাস সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Download uploaded resume file.
     */
    public function downloadResume($id)
    {
        $application = CareerApplication::findOrFail($id);
        $filePath = public_path($application->resume_file);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'ফাইলটি সার্ভারে খুঁজে পাওয়া যায়নি।');
        }

        $downloadName = $application->original_filename ?: ($application->full_name . '_Resume.' . pathinfo($filePath, PATHINFO_EXTENSION));

        return response()->download($filePath, $downloadName);
    }

    /**
     * Delete application and its resume file.
     */
    public function destroy($id)
    {
        $application = CareerApplication::findOrFail($id);
        $filePath = public_path($application->resume_file);

        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $application->delete();

        return redirect()->back()->with('success', 'আবেদনটি সফলভাবে মুছে ফেলা হয়েছে।');
    }
}

