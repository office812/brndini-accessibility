@extends('layouts.app')

@php($title = $article->title . ' | A11Y Bridge')

@section('content')
    @php($cover = $article->coverTheme())

    <article class="article-layout">
        <header class="article-hero article-hero-magazine panel-card">
            <div class="article-hero-copy">
                <p class="eyebrow">{{ $cover['eyebrow'] }}</p>
                <h1>{{ $article->title }}</h1>
                <p class="hero-text hero-text-lead">{{ $article->excerpt }}</p>

                <div class="article-meta">
                    <span class="preview-pill">{{ optional($article->published_at)->format('d.m.Y') }}</span>
                    <span class="preview-pill">{{ $article->readingTimeMinutes() }} דקות קריאה</span>
                    @if ($article->author)
                        <span class="preview-pill">{{ $article->author->name }}</span>
                    @endif
                </div>
            </div>

            <div class="article-hero-cover article-cover article-cover-{{ $cover['slug'] }}">
                <div class="article-cover-art" aria-hidden="true">
                    <span class="article-cover-orb"></span>
                    <span class="article-cover-panel"></span>
                    <span class="article-cover-beam"></span>
                </div>
                <div class="article-cover-copy">
                    <span class="article-cover-kicker">{{ $cover['kicker'] }}</span>
                    <div class="article-cover-chips">
                        @foreach ($cover['chips'] as $chip)
                            <span>{{ $chip }}</span>
                        @endforeach
                    </div>
                </div>
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
                        @php($relatedCover = $relatedArticle->coverTheme())
                        <article class="magazine-card">
                            <a class="magazine-card-visual article-cover article-cover-{{ $relatedCover['slug'] }}" href="{{ route('articles.show', $relatedArticle) }}" aria-label="{{ $relatedArticle->title }}">
                                <div class="article-cover-art" aria-hidden="true">
                                    <span class="article-cover-orb"></span>
                                    <span class="article-cover-panel"></span>
                                    <span class="article-cover-beam"></span>
                                </div>
                            </a>

                            <div class="magazine-card-copy">
                                <p class="meta-label">{{ optional($relatedArticle->published_at)->format('d.m.Y') }}</p>
                                <h3><a href="{{ route('articles.show', $relatedArticle) }}">{{ $relatedArticle->title }}</a></h3>
                                <p>{{ $relatedArticle->excerpt }}</p>
                                <a class="text-link" href="{{ route('articles.show', $relatedArticle) }}">לקריאה מלאה</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </article>
@endsection
