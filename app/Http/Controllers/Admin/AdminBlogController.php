<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AdminBlogController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request)
    {
        $query = BlogPost::with('category')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $posts = $query->paginate(15);
        $categories = BlogCategory::where('status', true)->get();

        return view('admin.blogs.index', compact('posts', 'categories'));
    }

    /**
     * Show form for creating a new blog post.
     */
    public function create()
    {
        $categories = BlogCategory::where('status', true)->orderBy('name')->get();
        return view('admin.blogs.create', compact('categories'));
    }

    /**
     * Store a newly created blog post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'nullable|exists:blog_categories,id',
            'summary'     => 'nullable|string|max:1000',
            'content'     => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'author_name' => 'nullable|string|max:150',
            'status'      => 'required|in:published,draft',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;
        while (BlogPost::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(8) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/blogs');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            $image->move($destinationPath, $imageName);
        }

        BlogPost::create([
            'category_id'  => $request->category_id,
            'title'        => $request->title,
            'slug'         => $slug,
            'summary'      => $request->summary,
            'content'      => $request->content,
            'image'        => $imageName,
            'author_name'  => $request->author_name ?: 'যমুনা এডিটরিয়াল',
            'is_featured'  => $request->has('is_featured'),
            'status'       => $request->status,
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'নতুন ব্লগ পোস্ট সফলভাবে প্রকাশ করা হয়েছে!');
    }

    /**
     * Show form for editing the specified blog post.
     */
    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        $categories = BlogCategory::where('status', true)->orderBy('name')->get();
        return view('admin.blogs.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified blog post.
     */
    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'nullable|exists:blog_categories,id',
            'summary'     => 'nullable|string|max:1000',
            'content'     => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'author_name' => 'nullable|string|max:150',
            'status'      => 'required|in:published,draft',
        ]);

        if ($request->title !== $post->title) {
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $counter = 1;
            while (BlogPost::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                $slug = "{$originalSlug}-{$counter}";
                $counter++;
            }
            $post->slug = $slug;
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(8) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/blogs');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            // Remove old image
            if ($post->image && File::exists(public_path('uploads/blogs/' . $post->image))) {
                File::delete(public_path('uploads/blogs/' . $post->image));
            }

            $image->move($destinationPath, $imageName);
            $post->image = $imageName;
        }

        $post->category_id = $request->category_id;
        $post->title = $request->title;
        $post->summary = $request->summary;
        $post->content = $request->content;
        $post->author_name = $request->author_name ?: 'যমুনা এডিটরিয়াল';
        $post->is_featured = $request->has('is_featured');
        $post->status = $request->status;

        if ($request->status === 'published' && !$post->published_at) {
            $post->published_at = now();
        }

        $post->save();

        return redirect()->route('admin.blogs.index')->with('success', 'ব্লগ পোস্ট সফলভাবে আপডেট করা হয়েছে!');
    }

    /**
     * Remove the specified blog post.
     */
    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);

        if ($post->image && File::exists(public_path('uploads/blogs/' . $post->image))) {
            File::delete(public_path('uploads/blogs/' . $post->image));
        }

        $post->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'ব্লগ পোস্ট সফলভাবে ডিলিট করা হয়েছে!');
    }

    /**
     * Toggle post status.
     */
    public function toggleStatus($id)
    {
        $post = BlogPost::findOrFail($id);
        $post->status = ($post->status === 'published') ? 'draft' : 'published';
        if ($post->status === 'published' && !$post->published_at) {
            $post->published_at = now();
        }
        $post->save();

        return redirect()->back()->with('success', 'পোস্টের স্ট্যাটাস পরিবর্তন করা হয়েছে!');
    }

    // ==========================================
    // BLOG CATEGORIES MANAGEMENT
    // ==========================================

    /**
     * View all blog categories.
     */
    public function categories()
    {
        $categories = BlogCategory::withCount('posts')->orderBy('sort_order')->latest()->get();
        return view('admin.blogs.categories', compact('categories'));
    }

    /**
     * Store new blog category.
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:150|unique:blog_categories,name',
            'description' => 'nullable|string|max:500',
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        while (BlogCategory::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        BlogCategory::create([
            'name'        => $request->name,
            'slug'         => $slug,
            'description' => $request->description,
            'status'      => true,
            'sort_order'  => BlogCategory::count() + 1,
        ]);

        return redirect()->back()->with('success', 'নতুন ব্লগ ক্যাটাগরি সফলভাবে তৈরি করা হয়েছে!');
    }

    /**
     * Update existing blog category.
     */
    public function updateCategory(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:150|unique:blog_categories,name,' . $id,
            'description' => 'nullable|string|max:500',
            'status'      => 'required|boolean',
        ]);

        if ($request->name !== $category->name) {
            $category->slug = Str::slug($request->name);
        }

        $category->name = $request->name;
        $category->description = $request->description;
        $category->status = $request->status;
        $category->save();

        return redirect()->back()->with('success', 'ব্লগ ক্যাটাগরি সফলভাবে আপডেট করা হয়েছে!');
    }

    /**
     * Delete blog category.
     */
    public function destroyCategory($id)
    {
        $category = BlogCategory::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'ব্লগ ক্যাটাগরি সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
