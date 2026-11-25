<?php

namespace App\Http\Controllers;

use App\Models\CareerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CareerController extends Controller
{
    /**
     * Display Career page with application form.
     */
    public function index()
    {
        return view('frontend.career');
    }

    /**
     * Store submitted career application.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'required|string|max:30',
            'email'      => 'nullable|email|max:150',
            'position'   => 'nullable|string|max:150',
            'address'    => 'required|string|max:500',
            'resume'     => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'notes'      => 'nullable|string|max:1000',
        ], [
            'first_name.required' => 'First name is required.',
            'last_name.required'  => 'Last name is required.',
            'phone.required'      => 'Phone number is required.',
            'address.required'    => 'Current address or location is required.',
            'resume.required'     => 'Please upload your Resume or CV file.',
            'resume.mimes'        => 'Resume must be a PDF, DOC, DOCX or image file (JPG, PNG).',
            'resume.max'          => 'File size cannot exceed 10 MB.',
        ]);

        $file = $request->file('resume');
        $originalFilename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $newFilename = time() . '_' . Str::random(10) . '.' . $extension;

        $destinationPath = public_path('upload/careers');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $newFilename);
        $savedPath = 'upload/careers/' . $newFilename;

        CareerApplication::create([
            'first_name'        => $validated['first_name'],
            'last_name'         => $validated['last_name'],
            'phone'             => $validated['phone'],
            'email'             => $validated['email'] ?? null,
            'position'          => $validated['position'] ?? 'General Application',
            'address'           => $validated['address'],
            'resume_file'       => $savedPath,
            'original_filename' => $originalFilename,
            'notes'             => $validated['notes'] ?? null,
            'status'            => 'pending',
        ]);

        $successMsg = 'Thank you! Your resume and career application have been submitted successfully. Our HR department will contact you shortly.';

        if ($request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => $successMsg
            ]);
        }

        return redirect()->back()->with('success', $successMsg);
    }
}

