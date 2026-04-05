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
        $featuredArticle = $articles->first();
        $knowledgeTopics = collect(Article::knowledgeTopics())->map(function (array $topic, string $key) use ($articles) {
            $topicArticles = $articles->filter(fn (Article $article) => $article->knowledgeTopicKey() === $key)->take(3)->values();

            return [
                'key' => $key,
                'label' => $topic['label'],
                'pillar' => $topic['pillar'],
                'summary' => $topic['summary'],
                'eyebrow' => $topic['eyebrow'],
                'count' => $articles->filter(fn (Article $article) => $article->knowledgeTopicKey() === $key)->count(),
                'articles' => $topicArticles,
            ];
        })->filter(fn (array $topic) => $topic['count'] > 0)->values();

        return view('articles.index', [
            'articles' => $articles,
            'featuredArticle' => $featuredArticle,
            'knowledgeTopics' => $knowledgeTopics,
            'metaDescription' => 'מרכז הידע של A11Y Bridge: מאמרים ומדריכים על וידג׳ט נגישות, הצהרת נגישות, WCAG, SEO, הטמעה וניהול נגישות לאתרים.',
            'canonicalUrl' => route('articles.index'),
        ]);
    }

    public function show(Article $article): View
    {
        abort_unless($article->published_at, 404);

        $article = $article->load('author');
        $topicKey = $article->knowledgeTopicKey();

        return view('article', [
            'article' => $article,
            'relatedArticles' => Article::published()
                ->whereKeyNot($article->id)
                ->get()
                ->sortByDesc(function (Article $candidate) use ($topicKey) {
                    return $candidate->knowledgeTopicKey() === $topicKey ? 1 : 0;
                })
                ->take(3)
                ->values(),
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
