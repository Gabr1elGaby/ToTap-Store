<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of published articles.
     */
    public function index(Request $request)
    {
        $categories = ['Semua', 'Panduan & Tips', 'Tips Game', 'Review & Keamanan', 'Promo'];
        $selectedCategory = $request->query('category', 'Semua');
        $search = $request->query('q');

        $query = Article::published();

        if ($selectedCategory && $selectedCategory !== 'Semua') {
            $query->where('category', $selectedCategory);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->latest('published_at')->paginate(9)->withQueryString();

        $featuredArticles = Article::published()
            ->orderBy('views', 'desc')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('articles.index', compact('articles', 'categories', 'selectedCategory', 'search', 'featuredArticles'));
    }

    /**
     * Display the specified article.
     */
    public function show($slug)
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();

        // Increment view count (simple session check to prevent refresh spam)
        $sessionKey = 'viewed_article_' . $article->id;
        if (!session()->has($sessionKey)) {
            $article->increment('views');
            session()->put($sessionKey, true);
        }

        // Fetch related articles
        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($relatedArticles->count() < 3) {
            $fallback = Article::published()
                ->where('id', '!=', $article->id)
                ->whereNotIn('id', $relatedArticles->pluck('id'))
                ->latest('published_at')
                ->take(3 - $relatedArticles->count())
                ->get();
            $relatedArticles = $relatedArticles->concat($fallback);
        }

        return view('articles.show', compact('article', 'relatedArticles'));
    }
}
