<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminArticleController extends Controller
{
    /**
     * Display a listing of articles for admin.
     */
    public function index(Request $request)
    {
        $query = Article::query()->latest('id');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhere('author_name', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $articles = $query->paginate(15)->withQueryString();
        $totalArticles = Article::count();
        $publishedCount = Article::where('is_published', true)->count();
        $draftCount = Article::where('is_published', false)->count();
        $totalViews = Article::sum('views');

        return view('admin.articles.index', compact('articles', 'totalArticles', 'publishedCount', 'draftCount', 'totalViews'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create()
    {
        $categories = ['Panduan & Tips', 'Tips Game', 'Review & Keamanan', 'Promo', 'Berita'];
        return view('admin.articles.create', compact('categories'));
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug',
            'category' => 'required|string|max:100',
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'thumbnail' => 'nullable|string|max:500',
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'author_name' => 'nullable|string|max:100',
            'is_published' => 'nullable|boolean',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        
        // Ensure slug uniqueness
        $originalSlug = $slug;
        $counter = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        // Process Image Upload directly from computer
        $thumbnail = $validated['thumbnail'] ?? null;
        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $ext = $file->getClientOriginalExtension() ?: 'png';
            $fileName = time() . '_art_' . Str::random(8) . '.' . $ext;

            $destDirs = [
                public_path('images/articles'),
                base_path('images/articles'),
                base_path('public/images/articles'),
            ];
            foreach ($destDirs as $d) {
                @mkdir($d, 0777, true);
                @copy($file->getRealPath(), $d . '/' . $fileName);
            }
            $file->move(public_path('images/articles'), $fileName);
            $thumbnail = '/images/articles/' . $fileName;
        }

        $isPublished = $request->has('is_published') && (bool) $request->is_published;

        Article::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'thumbnail' => $thumbnail,
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 160),
            'content' => $validated['content'],
            'meta_title' => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? Str::limit(strip_tags($validated['excerpt'] ?? $validated['content']), 160),
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'author_name' => !empty($validated['author_name']) ? $validated['author_name'] : 'ToTap Store Official',
            'is_published' => $isPublished,
            'published_at' => $isPublished ? now() : null,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diterbitkan!');
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Article $article)
    {
        $categories = ['Panduan & Tips', 'Tips Game', 'Review & Keamanan', 'Promo', 'Berita'];
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified article in storage.
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug,' . $article->id,
            'category' => 'required|string|max:100',
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'thumbnail' => 'nullable|string|max:500',
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'author_name' => 'nullable|string|max:100',
            'is_published' => 'nullable|boolean',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        if ($slug !== $article->slug) {
            $originalSlug = $slug;
            $counter = 1;
            while (Article::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
                $slug = "{$originalSlug}-{$counter}";
                $counter++;
            }
        }

        // Process Image Upload directly from computer or keep existing/URL
        $thumbnail = $article->thumbnail;
        if ($request->filled('thumbnail')) {
            $thumbnail = $request->thumbnail;
        }
        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $ext = $file->getClientOriginalExtension() ?: 'png';
            $fileName = time() . '_art_' . Str::random(8) . '.' . $ext;

            $destDirs = [
                public_path('images/articles'),
                base_path('images/articles'),
                base_path('public/images/articles'),
            ];
            foreach ($destDirs as $d) {
                @mkdir($d, 0777, true);
                @copy($file->getRealPath(), $d . '/' . $fileName);
            }
            $file->move(public_path('images/articles'), $fileName);
            $thumbnail = '/images/articles/' . $fileName;
        }

        $isPublished = $request->has('is_published') && (bool) $request->is_published;

        $publishedAt = $article->published_at;
        if ($isPublished && !$article->published_at) {
            $publishedAt = now();
        }

        $article->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'thumbnail' => $thumbnail,
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 160),
            'content' => $validated['content'],
            'meta_title' => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? Str::limit(strip_tags($validated['excerpt'] ?? $validated['content']), 160),
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'author_name' => !empty($validated['author_name']) ? $validated['author_name'] : 'ToTap Store Official',
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus!');
    }

    /**
     * Upload an image to be embedded directly into article content.
     */
    public function uploadContentImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,gif,svg|max:5120',
        ]);

        $file = $request->file('image');
        $ext = $file->getClientOriginalExtension() ?: 'png';
        $fileName = 'content_' . time() . '_' . Str::random(8) . '.' . $ext;

        $destDirs = [
            public_path('images/articles'),
            base_path('images/articles'),
            base_path('public/images/articles'),
        ];
        foreach ($destDirs as $d) {
            @mkdir($d, 0777, true);
            @copy($file->getRealPath(), $d . '/' . $fileName);
        }
        $file->move(public_path('images/articles'), $fileName);

        return response()->json([
            'success' => true,
            'url' => asset('images/articles/' . $fileName),
        ]);
    }
}
