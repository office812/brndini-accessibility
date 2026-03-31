<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::published()->latest('published_at')->get();

        return view('articles.index', [
            'articles' => $articles,
            'featuredArticle' => $articles->first(),
            'metaDescription' => 'מאמרים על נגישות אתרים, וידג׳ט נגישות, הצהרת נגישות, בדיקות אתר, WCAG וניהול נגישות בפלטפורמה אחת.',
            'canonicalUrl' => route('articles.index'),
        ]);
    }

    public function show(Article $article): View
    {
        abort_unless($article->published_at, 404);

        return view('article', [
            'article' => $article->load('author'),
            'relatedArticles' => Article::published()
                ->whereKeyNot($article->id)
                ->latest('published_at')
                ->take(3)
                ->get(),
            'metaDescription' => $article->meta_description ?: $article->excerpt,
            'canonicalUrl' => route('articles.show', $article),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->is_admin || $request->user()?->isSuperAdmin(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'excerpt' => ['required', 'string', 'max:280'],
            'body' => ['required', 'string', 'min:120'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'publish_now' => ['nullable', 'boolean'],
        ]);

        $slugBase = Str::slug($validated['title']);
        $slug = $slugBase;
        $counter = 2;

        while (Article::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $counter;
            $counter++;
        }

        $article = Article::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'slug' => $slug,
            'excerpt' => $validated['excerpt'],
            'body' => trim($validated['body']),
            'meta_description' => $validated['meta_description'] ?: $validated['excerpt'],
            'published_at' => $request->boolean('publish_now') ? now() : null,
        ]);

        return redirect()
            ->to(route('home') . '#articles')
            ->with('status', $article->published_at ? 'המאמר פורסם בהצלחה.' : 'המאמר נשמר כטיוטה.');
    }
}
