<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notice;
use App\Models\AuditLog;
use Illuminate\Support\Facades\File;

class AdminNoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::orderByDesc('publish_date')->orderByDesc('id')->paginate(15);
        return view('admin.notices.index', compact('notices'));
    }

    public function create()
    {
        return view('admin.notices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'notice_type' => 'required|string|max:100',
            'content' => 'required|string',
            'attachment_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
            'publish_date' => 'required|date',
            'expiry_date' => 'nullable|date',
        ]);

        if ($request->hasFile('attachment_file')) {
            $fileName = time() . '_' . uniqid() . '.' . $request->file('attachment_file')->getClientOriginalExtension();
            $request->file('attachment_file')->move(public_path('uploads/notices'), $fileName);
            $validated['attachment_file'] = 'uploads/notices/' . $fileName;
        }

        $validated['is_pinned'] = $request->has('is_pinned');
        $validated['is_active'] = $request->has('is_active') ? true : true;

        $notice = Notice::create($validated);
        AuditLog::log('notice_created', 'Notice', $notice->id, 'Created notice: ' . $notice->title);

        return redirect()->route('admin.notices.index')->with('success', 'নোটিশ সফলভাবে প্রকাশ করা হয়েছে।');
    }

    public function edit($id)
    {
        $notice = Notice::findOrFail($id);
        return view('admin.notices.edit', compact('notice'));
    }

    public function update(Request $request, $id)
    {
        $notice = Notice::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'notice_type' => 'required|string|max:100',
            'content' => 'required|string',
            'attachment_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
            'publish_date' => 'required|date',
            'expiry_date' => 'nullable|date',
        ]);

        if ($request->hasFile('attachment_file')) {
            if ($notice->attachment_file && File::exists(public_path($notice->attachment_file))) {
                File::delete(public_path($notice->attachment_file));
            }
            $fileName = time() . '_' . uniqid() . '.' . $request->file('attachment_file')->getClientOriginalExtension();
            $request->file('attachment_file')->move(public_path('uploads/notices'), $fileName);
            $validated['attachment_file'] = 'uploads/notices/' . $fileName;
        }

        $validated['is_pinned'] = $request->has('is_pinned');
        $validated['is_active'] = $request->has('is_active');

        $notice->update($validated);
        AuditLog::log('notice_updated', 'Notice', $notice->id, 'Updated notice: ' . $notice->title);

        return redirect()->route('admin.notices.index')->with('success', 'নোটিশ সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroy($id)
    {
        $notice = Notice::findOrFail($id);
        if ($notice->attachment_file && File::exists(public_path($notice->attachment_file))) {
            File::delete(public_path($notice->attachment_file));
        }
        $notice->delete();

        return redirect()->route('admin.notices.index')->with('success', 'নোটিশ সফলভাবে মুছে ফেলা হয়েছে।');
    }
}

