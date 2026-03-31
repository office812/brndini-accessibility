@extends('layouts.app')

@php($title = 'מאמרים ובלוג נגישות | A11Y Bridge')

@section('content')
    <section class="magazine-page">
        <header class="magazine-page-hero panel-card">
            <div class="magazine-page-copy">
                <p class="eyebrow">מגזין A11Y Bridge</p>
                <h1>מאמרים, מדריכים ותוכן שמסביר נגישות כמו מוצר רציני.</h1>
                <p class="hero-text hero-text-lead">
                    כאן מרוכזים המאמרים של A11Y Bridge סביב וידג׳ט נגישות, הצהרת נגישות, בדיקות אתר,
                    הטמעה, WCAG, קידום אורגני וסביבת ניהול נגישות מלאה.
                </p>
            </div>

            <div class="magazine-page-summary">
                <span class="preview-pill">{{ $articles->count() }} מאמרים</span>
                <span class="preview-pill">תוכן אורגני</span>
                <span class="preview-pill">מדריכי הטמעה</span>
            </div>
        </header>

        @if ($featuredArticle)
            @php($featuredCover = $featuredArticle->coverTheme())
            <section class="magazine-feature-card article-cover article-cover-{{ $featuredCover['slug'] }}">
                <div class="article-cover-art" aria-hidden="true">
                    <span class="article-cover-orb"></span>
                    <span class="article-cover-panel"></span>
                    <span class="article-cover-beam"></span>
                </div>

                <div class="magazine-feature-copy">
                    <p class="eyebrow">{{ $featuredCover['eyebrow'] }}</p>
                    <h2><a href="{{ route('articles.show', $featuredArticle) }}">{{ $featuredArticle->title }}</a></h2>
                    <p>{{ $featuredArticle->excerpt }}</p>

                    <div class="article-meta">
                        <span class="preview-pill">{{ optional($featuredArticle->published_at)->format('d.m.Y') }}</span>
                        <span class="preview-pill">{{ $featuredArticle->readingTimeMinutes() }} דקות קריאה</span>
                        @foreach ($featuredCover['chips'] as $chip)
                            <span class="preview-pill">{{ $chip }}</span>
                        @endforeach
                    </div>

                    <a class="text-link" href="{{ route('articles.show', $featuredArticle) }}">לקריאה מלאה</a>
                </div>
            </section>
        @endif

        <section class="magazine-grid">
            @forelse ($articles->skip(1) as $article)
                @php($cover = $article->coverTheme())
                <article class="magazine-card">
                    <a class="magazine-card-visual article-cover article-cover-{{ $cover['slug'] }}" href="{{ route('articles.show', $article) }}" aria-label="{{ $article->title }}">
                        <div class="article-cover-art" aria-hidden="true">
                            <span class="article-cover-orb"></span>
                            <span class="article-cover-panel"></span>
                            <span class="article-cover-beam"></span>
                        </div>
                        <div class="article-cover-copy">
                            <span class="article-cover-kicker">{{ $cover['kicker'] }}</span>
                        </div>
                    </a>

                    <div class="magazine-card-copy">
                        <p class="meta-label">{{ optional($article->published_at)->format('d.m.Y') }} · {{ $article->readingTimeMinutes() }} דקות קריאה</p>
                        <h3><a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a></h3>
                        <p>{{ $article->excerpt }}</p>
                        <a class="text-link" href="{{ route('articles.show', $article) }}">לקריאה מלאה</a>
                    </div>
                </article>
            @empty
                <article class="article-card">
                    <h3>עדיין אין כאן מאמרים</h3>
                    <p>כשתעלה תוכן ראשון, העמוד הזה יתחיל להתמלא במאמרים, מדריכים ועמודי תוכן.</p>
                </article>
            @endforelse
        </section>

        @auth
            @if (auth()->user()->is_admin || auth()->user()->isSuperAdmin())
                <section class="panel-card article-admin-card">
                    <p class="eyebrow">ניהול תוכן</p>
                    <h2>פרסום מאמר חדש</h2>

                    <form class="stack-form" method="POST" action="{{ route('articles.store') }}">
                        @csrf

                        <label for="article_title">כותרת המאמר</label>
                        <input id="article_title" name="title" type="text" value="{{ old('title') }}" required>

                        <label for="article_excerpt">תקציר קצר</label>
                        <textarea id="article_excerpt" name="excerpt" rows="3" required>{{ old('excerpt') }}</textarea>

                        <label for="article_meta_description">תיאור לעמוד</label>
                        <input id="article_meta_description" name="meta_description" type="text" value="{{ old('meta_description') }}" maxlength="160">

                        <label for="article_body">תוכן המאמר</label>
                        <textarea id="article_body" name="body" rows="10" required>{{ old('body') }}</textarea>

                        <label class="toggle-row">
                            <input type="hidden" name="publish_now" value="0">
                            <input type="checkbox" name="publish_now" value="1" checked>
                            <span>לפרסם מיידית</span>
                        </label>

                        <button class="primary-button" type="submit">פרסם מאמר</button>
                    </form>
                </section>
            @endif
        @endauth
    </section>
@endsection
