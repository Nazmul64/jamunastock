<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminVideoGalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display Video Gallery Management List & Dashboard
     */
    public function index(Request $request)
    {
        $query = VideoGallery::with('uploader');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('video_url', 'like', "%{$s}%")
                  ->orWhere('title', 'like', "%{$s}%")
                  ->orWhere('youtube_id', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $videos = $query->orderBy('sort_order', 'asc')->latest('id')->paginate(15)->withQueryString();

        $totalVideos = VideoGallery::count();
        $activeVideos = VideoGallery::where('is_active', true)->count();
        $inactiveVideos = VideoGallery::where('is_active', false)->count();

        return view('admin.videos.index', compact(
            'videos',
            'totalVideos',
            'activeVideos',
            'inactiveVideos'
        ));
    }

    /**
     * Store YouTube Video Link(s)
     * Supports single link or bulk paste separated by newline or comma.
     */
    public function store(Request $request)
    {
        $request->validate([
            'video_url' => 'required|string',
            'title' => 'nullable|string|max:255',
        ]);

        $rawInput = trim($request->video_url);

        // Check if user entered multiple URLs separated by newlines or commas
        $urls = preg_split('/[\r\n,]+/', $rawInput, -1, PREG_SPLIT_NO_EMPTY);
        $addedCount = 0;
        $failedCount = 0;

        foreach ($urls as $url) {
            $cleanUrl = trim($url);
            if (empty($cleanUrl)) {
                continue;
            }

            $youtubeId = VideoGallery::extractYoutubeId($cleanUrl);
            if (!$youtubeId) {
                $failedCount++;
                continue;
            }

            VideoGallery::create([
                'video_url' => $cleanUrl,
                'youtube_id' => $youtubeId,
                'title' => $request->title ?: null,
                'is_active' => true,
                'sort_order' => (VideoGallery::max('sort_order') ?? 0) + 1,
                'created_by' => Auth::id(),
            ]);

            $addedCount++;
        }

        if ($addedCount > 0) {
            $msg = "{$addedCount} টি ইউটিউব ভিডিও সফলভাবে যুক্ত করা হয়েছে!";
            if ($failedCount > 0) {
                $msg .= " ({$failedCount} টি লিংক সঠিক না হওয়ায় বাদ দেওয়া হয়েছে)";
            }
            return back()->with('success', $msg);
        }

        return back()->with('error', 'অনুগ্রহ করে সঠিক ইউটিউব ভিডিও লিংক প্রদান করুন (যেমন: https://www.youtube.com/watch?v=... বা https://youtu.be/...)');
    }

    /**
     * Toggle Active/Inactive Status
     */
    public function toggleStatus($id)
    {
        $video = VideoGallery::findOrFail($id);
        $video->is_active = !$video->is_active;
        $video->save();

        $statusText = $video->is_active ? 'প্রকাশিত (Active)' : 'লুকানো (Inactive)';
        return back()->with('success', "ভিডিওর স্ট্যাটাস পরিবর্তন করে {$statusText} করা হয়েছে।");
    }

    /**
     * Update Video Details
     */
    public function update(Request $request, $id)
    {
        $video = VideoGallery::findOrFail($id);

        $request->validate([
            'video_url' => 'required|string',
            'title' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $youtubeId = VideoGallery::extractYoutubeId($request->video_url);
        if (!$youtubeId) {
            return back()->with('error', 'অবৈধ ইউটিউব ভিডিও লিংক!');
        }

        $video->update([
            'video_url' => trim($request->video_url),
            'youtube_id' => $youtubeId,
            'title' => $request->title ?: null,
            'sort_order' => $request->sort_order ?? $video->sort_order,
        ]);

        return back()->with('success', 'ভিডিও তথ্য সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Delete Video
     */
    public function destroy($id)
    {
        $video = VideoGallery::findOrFail($id);
        $video->delete();

        return back()->with('success', 'ভিডিওটি সফলভাবে মুছে ফেলা হয়েছে।');
    }

    /**
     * Bulk Delete Videos
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

        if (!empty($ids) && is_array($ids)) {
            VideoGallery::whereIn('id', $ids)->delete();
            return back()->with('success', count($ids) . ' টি ভিডিও সফলভাবে মুছে ফেলা হয়েছে।');
        }

        return back()->with('error', 'মুছে ফেলার জন্য কোনো ভিডিও নির্বাচন করা হয়নি।');
    }
}
