<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notice;

class NoticeController extends Controller
{
    /**
     * Display Notices & Circulars
     */
    public function index(Request $request)
    {
        $type = $request->query('type');
        $query = Notice::where('is_active', true);

        if ($type) {
            $query->where('notice_type', $type);
        }

        $pinnedNotices = Notice::where('is_active', true)->where('is_pinned', true)->get();
        $notices = $query->orderByDesc('publish_date')->orderByDesc('id')->paginate(10);

        return view('frontend.notices.index', compact('notices', 'pinnedNotices', 'type'));
    }

    /**
     * View Single Notice
     */
    public function show($id)
    {
        $notice = Notice::where('is_active', true)->findOrFail($id);
        $recentNotices = Notice::where('is_active', true)->where('id', '!=', $id)->orderByDesc('publish_date')->take(5)->get();

        return view('frontend.notices.show', compact('notice', 'recentNotices'));
    }
}

