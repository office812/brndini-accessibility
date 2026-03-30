@extends('layouts.app')

@php($title = $article->title . ' | A11Y Bridge')

@section('content')
    <article class="article-layout">
        <header class="article-hero panel-card">
            <p class="eyebrow">Article</p>
            <h1>{{ $article->title }}</h1>
            <p class="hero-text hero-text-lead">{{ $article->excerpt }}</p>

            <div class="article-meta">
                <span class="preview-pill">{{ optional($article->published_at)->format('d.m.Y') }}</span>
                <span class="preview-pill">A11Y Bridge</span>
                @if ($article->author)
                    <span class="preview-pill">{{ $article->author->name }}</span>
                @endif
            </div>
        </header>

        <section class="article-body panel-card">
            {!! nl2br(e($article->body)) !!}
        </section>

        @if ($relatedArticles->isNotEmpty())
            <section class="section-band articles-band">
                <div class="section-heading">
                    <p class="eyebrow">Related</p>
                    <h2>מאמרים נוספים</h2>
                </div>

                <div class="article-grid">
                    @foreach ($relatedArticles as $relatedArticle)
                        <article class="article-card">
                            <p class="meta-label">{{ optional($relatedArticle->published_at)->format('d.m.Y') }}</p>
                            <h3><a href="{{ route('articles.show', $relatedArticle) }}">{{ $relatedArticle->title }}</a></h3>
                            <p>{{ $relatedArticle->excerpt }}</p>
                            <a class="text-link" href="{{ route('articles.show', $relatedArticle) }}">לקריאה מלאה</a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </article>
@endsection
