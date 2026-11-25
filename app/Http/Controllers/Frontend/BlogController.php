<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display frontend blog listing.
     */
    public function index(Request $request)
    {
        $query = BlogPost::with('category')->where('status', 'published')->latest('published_at');

        $activeCategory = null;
        if ($request->filled('category')) {
            $activeCategory = BlogCategory::where('slug', $request->category)->first();
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $featuredPosts = BlogPost::where('status', 'published')
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        $posts = $query->paginate(9);

        $categories = BlogCategory::where('status', true)
            ->withCount(['posts' => function ($q) {
                $q->where('status', 'published');
            }])
            ->orderBy('sort_order')
            ->get();

        $recentPosts = BlogPost::where('status', 'published')
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('frontend.blogs.index', compact('posts', 'featuredPosts', 'categories', 'recentPosts', 'activeCategory'));
    }

    /**
     * Display single blog post reading page.
     */
    public function show($slug)
    {
        $post = BlogPost::with('category')->where('slug', $slug)->firstOrFail();

        // Increment view count safely
        $post->increment('views_count');

        $relatedPosts = BlogPost::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->latest('published_at')
            ->take(4)
            ->get();

        $recentPosts = BlogPost::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(5)
            ->get();

        $categories = BlogCategory::where('status', true)
            ->withCount(['posts' => function ($q) {
                $q->where('status', 'published');
            }])
            ->get();

        return view('frontend.blogs.show', compact('post', 'relatedPosts', 'recentPosts', 'categories'));
    }
}
