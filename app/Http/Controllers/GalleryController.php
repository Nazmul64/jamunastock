<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\StockGallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Show Public Photo Gallery
     */
    public function index(Request $request)
    {
        $category = $request->query('category', 'all');

        $query = Gallery::active();

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        $galleries = $query->orderBy('sort_order', 'asc')->latest('id')->paginate(16);

        // Fallback: If no galleries exist in the new table yet, check StockGallery
        $stockGalleries = collect();
        if ($galleries->isEmpty() && ($category === 'all' || !$category)) {
            $stockGalleries = StockGallery::latest('id')->paginate(16);
        }

        $categories = [
            'all' => 'সব ছবি (All)',
            'office' => 'অফিস ও ক্যাম্পাস',
            'events' => 'ইভেন্ট ও সেমিনার',
            'projects' => 'প্রকল্প ও মাঠ কার্যক্রম',
            'board' => 'বোর্ড ও পরিচালনা পর্ষদ',
            'awards' => 'পুরস্কার ও অর্জন',
            'general' => 'সাধারণ',
        ];

        return view('frontend.gallery', compact('galleries', 'stockGalleries', 'categories', 'category'));
    }
}
