<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AdminGalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display Gallery Management Dashboard & List
     */
    public function index(Request $request)
    {
        $query = Gallery::with('uploader');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('title', 'like', "%{$s}%");
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $galleries = $query->orderBy('sort_order', 'asc')->latest('id')->paginate(16);

        $totalImages = Gallery::count();
        $activeImages = Gallery::where('is_active', true)->count();
        $inactiveImages = Gallery::where('is_active', false)->count();

        $categories = [
            'general' => 'সাধারণ (General)',
            'office' => 'অফিস ও ক্যাম্পাস (Office & Campus)',
            'events' => 'ইভেন্ট ও সেমিনার (Events & Seminars)',
            'projects' => 'প্রকল্প ও মাঠ কার্যক্রম (Projects & Field Work)',
            'board' => 'বোর্ড ও ব্যবস্থাপনা (Board & Management)',
            'awards' => 'পুরস্কার ও অর্জন (Awards & Achievements)',
        ];

        return view('admin.gallery.index', compact(
            'galleries',
            'totalImages',
            'activeImages',
            'inactiveImages',
            'categories'
        ));
    }

    /**
     * Store Multiple Gallery Images (Destination: public/uploads/gallery)
     */
    public function store(Request $request)
    {
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'title' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
        ], [
            'images.required' => 'কমপক্ষে একটি ছবি নির্বাচন করা আবশ্যক।',
            'images.*.image' => 'নির্বাচিত ফাইলটি অবশ্যই একটি ছবি হতে হবে।',
            'images.*.mimes' => 'শুধুমাত্র JPG, JPEG, PNG, WEBP অথবা GIF ফরম্যাটের ছবি আপলোড করা যাবে।',
            'images.*.max' => 'প্রতিটি ছবির সাইজ সর্বোচ্চ ১০ মেগাবাইট (10MB) হতে পারবে।',
        ]);

        $uploadPath = public_path('uploads/gallery');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0777, true, true);
        }

        $uploadedCount = 0;
        $category = $request->category ?: 'general';
        $baseTitle = $request->title;

        foreach ($request->file('images') as $index => $file) {
            $extension = $file->getClientOriginalExtension();
            $filename = 'gallery_' . date('Ymd_His') . '_' . Str::random(8) . '.' . $extension;

            // Move image to public/uploads/gallery
            $file->move($uploadPath, $filename);

            // Determine item title (optional, empty by default)
            $itemTitle = $baseTitle ?: null;
            if ($baseTitle && count($request->file('images')) > 1) {
                $itemTitle = $baseTitle . ' (' . ($index + 1) . ')';
            }

            Gallery::create([
                'title' => $itemTitle,
                'image' => $filename,
                'category' => $category,
                'is_active' => true,
                'sort_order' => 0,
                'uploaded_by' => Auth::id(),
            ]);

            $uploadedCount++;
        }

        return back()->with('success', "✓ সফল হয়েছে! সর্বমোট {$uploadedCount}টি ছবি 'public/uploads/gallery' লোকেশনে সফলভাবে আপলোড করা হয়েছে এবং ফ্রন্টএন্ডে যুক্ত হয়েছে।");
    }

    /**
     * Update Gallery Image Details
     */
    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        $gallery->update([
            'title' => $request->title,
            'category' => $request->category ?: $gallery->category,
            'sort_order' => $request->sort_order ?? $gallery->sort_order,
        ]);

        return back()->with('success', "ছবির বিবরণ সফলভাবে আপডেট করা হয়েছে।");
    }

    /**
     * Toggle Active/Inactive Status
     */
    public function toggleStatus($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->update(['is_active' => !$gallery->is_active]);

        $statusText = $gallery->is_active ? 'সক্রিয় (Active)' : 'নিষ্ক্রিয় (Inactive)';
        return back()->with('success', "ছবির স্ট্যাটাস সফলভাবে '{$statusText}' করা হয়েছে।");
    }

    /**
     * Delete Gallery Image (Record & Physical File)
     */
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        // Delete physical file from public/uploads/gallery
        $filePath = public_path('uploads/gallery/' . $gallery->image);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        $gallery->delete();

        return back()->with('success', "ছবিটি গ্যালারি থেকে সফলভাবে মুছে ফেলা হয়েছে।");
    }

    /**
     * Bulk Delete Selected Images
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        if (empty($ids)) {
            return back()->with('error', 'মুছে ফেলার জন্য কমপক্ষে একটি ছবি নির্বাচন করুন।');
        }

        $galleries = Gallery::whereIn('id', $ids)->get();
        $deletedCount = 0;

        foreach ($galleries as $g) {
            $filePath = public_path('uploads/gallery/' . $g->image);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            $g->delete();
            $deletedCount++;
        }

        return back()->with('success', "✓ নির্বাচিত {$deletedCount}টি ছবি সফলভাবে মুছে ফেলা হয়েছে।");
    }
}
