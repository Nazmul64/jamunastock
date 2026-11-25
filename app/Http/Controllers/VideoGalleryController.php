<?php

namespace App\Http\Controllers;

use App\Models\VideoGallery;
use Illuminate\Http\Request;

class VideoGalleryController extends Controller
{
    /**
     * Show Public Video Gallery Page
     * 3 Videos Per Row (col-lg-4 col-md-6 col-12)
     */
    public function index(Request $request)
    {
        $query = VideoGallery::active();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('title', 'like', "%{$s}%");
        }

        // Display 12 videos per page (4 rows of 3 columns each)
        $videos = $query->orderBy('sort_order', 'asc')->latest('id')->paginate(12)->withQueryString();

        return view('frontend.videos', compact('videos'));
    }
}
