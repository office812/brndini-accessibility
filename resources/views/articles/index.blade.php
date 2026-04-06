@extends('layouts.app')

@php($title = 'מרכז הידע של A11Y Bridge | נגישות, WCAG, הצהרות ו־SEO')

@section('content')
    <section class="public-stage">
        <div class="public-stage-copy">
            <p class="eyebrow">מרכז הידע</p>
            <h1>מרכז הידע של A11Y Bridge סביב נגישות לאתרים, הצהרות, WCAG ו־SEO.</h1>
            <p class="hero-text hero-text-lead">
                במקום בלוג כללי, בנינו שכבת תוכן שמחלקת את התחום לאשכולות ברורים: ווידג׳ט,
                הצהרות, ציות, פלטפורמות, סוכנויות ו־AI SEO. כל עמוד כאן נועד או להסביר משהו
                היטב, או להוביל אותך לצעד הבא הנכון.
            </p>
            <div class="public-cta-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('free-tool') }}">מה כלול בחינם</a>
            </div>
        </div>

        <div class="public-stage-visual" aria-hidden="true">
            <div class="public-device-shell public-device-shell-quiet">
                <div class="public-device-card">
                    <small>תוכן</small>
                    <strong>{{ $articles->count() }} עמודי ידע ומדריכים</strong>
                    <p>תוכן שנועד לבנות authority, לעזור למשתמשים, ולהוביל למסלול הנכון מתוך המוצר.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="public-shell-section" data-reveal>
        <div class="section-heading section-heading-center">
            <p class="eyebrow">אשכולות תוכן</p>
            <h2>אותו תחום, מחולק למסלולים ברורים.</h2>
        </div>

        <div class="public-shell-grid public-shell-grid-three">
            @forelse ($knowledgeTopics as $topic)
                <article class="public-shell-card">
                    <small>{{ $topic['count'] }} עמודים</small>
                    <h3>{{ $topic['label'] }}</h3>
                    <p>{{ $topic['summary'] }}</p>
                </article>
            @empty
                <p class="panel-intro" style="grid-column:1/-1;text-align:center;opacity:.6;">אין תכנים זמינים כרגע. בקרוב.</p>
            @endforelse
        </div>
    </section>

    @if ($featuredArticle)
        @php($featuredCover = $featuredArticle->coverTheme())
        <section class="public-shell-section public-shell-section-soft">
            <div class="section-heading section-heading-center">
                <p class="eyebrow">מאמר מוביל</p>
                <h2>{{ $featuredArticle->title }}</h2>
                <p class="hero-text">{{ $featuredArticle->excerpt }}</p>
            </div>

            <div class="public-dual-panel">
                <article class="public-decision-card">
                    <p class="eyebrow">{{ $featuredArticle->topicLabel() }}</p>
                    <h3>{{ $featuredArticle->pillarLabel() }}</h3>
                    <p>{{ $featuredArticle->articleSummary() }}</p>
                    <ul class="compact-check-list">
                        <li>{{ optional($featuredArticle->published_at)->format('d.m.Y') }}</li>
                        <li>{{ $featuredArticle->readingTimeMinutes() }} דקות קריאה</li>
                        <li>{{ $featuredArticle->contentIntentLabel() }}</li>
                    </ul>
                    <div class="public-cta-row">
                        <a class="primary-button button-link" href="{{ route('articles.show', $featuredArticle) }}">לקריאה מלאה</a>
                    </div>
                </article>

                <article class="public-decision-card public-decision-card-accent article-cover article-cover-{{ $featuredCover['slug'] }}">
                    <div class="article-cover-art" aria-hidden="true">
                        <span class="article-cover-orb"></span>
                        <span class="article-cover-panel"></span>
                        <span class="article-cover-beam"></span>
                    </div>
                    <div class="article-cover-copy">
                        <span class="article-cover-kicker">{{ $featuredCover['kicker'] }}</span>
                        <div class="article-cover-chips">
                            @foreach ($featuredCover['chips'] as $chip)
                                <span>{{ $chip }}</span>
                            @endforeach
                        </div>
                    </div>
                </article>
            </div>
        </section>
    @endif

    <section class="public-shell-section">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">אשכולות ומאמרים</p>
            <h2>כל נושא מוביל לעמודי עומק, לא רק לרשימת פוסטים.</h2>
        </div>

        <div class="knowledge-topic-list">
            @forelse ($knowledgeTopics as $topic)
                <section class="knowledge-topic-card">
                    <header class="knowledge-topic-head">
                        <div>
                            <p class="eyebrow">{{ $topic['eyebrow'] }}</p>
                            <h3>{{ $topic['label'] }}</h3>
                            <p>{{ $topic['summary'] }}</p>
                        </div>
                        <span class="preview-pill">{{ $topic['count'] }} עמודים</span>
                    </header>

                    <div class="knowledge-article-list">
                        @forelse ($topic['articles'] as $article)
                            <article class="knowledge-article-item">
                                <div>
                                    <p class="meta-label">{{ optional($article->published_at)->format('d.m.Y') }} · {{ $article->readingTimeMinutes() }} דקות</p>
                                    <h4><a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a></h4>
                                    <p>{{ $article->excerpt }}</p>
                                </div>
                                <a class="text-link" href="{{ route('articles.show', $article) }}">לקריאה מלאה</a>
                            </article>
                        @empty
                            <p class="meta-label" style="opacity:.5;">אין מאמרים בנושא זה עדיין.</p>
                        @endforelse
                    </div>
                </section>
            @empty
                <p class="panel-intro" style="text-align:center;opacity:.6;padding:2rem 0;">אין נושאים זמינים כרגע.</p>
            @endforelse
        </div>
    </section>

    @auth
        @if (auth()->user()->is_admin || auth()->user()->isSuperAdmin())
            <section class="section-band section-band-alt article-admin-shell">
                <div class="section-heading section-heading-center">
                    <p class="eyebrow">ניהול תוכן</p>
                    <h2>פרסום עמוד ידע חדש</h2>
                    <p class="hero-text">המערכת תזהה אוטומטית את האשכול, הכוונה וה־CTA המרכזי לפי התוכן עצמו.</p>
                </div>

                <section class="panel-card article-admin-card">
                    <form class="stack-form" method="POST" action="{{ route('articles.store') }}">
                        @csrf

                        <label for="article_title">כותרת</label>
                        <input id="article_title" name="title" type="text" value="{{ old('title') }}" required>

                        <label for="article_excerpt">תקציר</label>
                        <textarea id="article_excerpt" name="excerpt" rows="3" required>{{ old('excerpt') }}</textarea>

                        <label for="article_meta_description">Meta description</label>
                        <input id="article_meta_description" name="meta_description" type="text" value="{{ old('meta_description') }}" maxlength="160">

                        <label for="article_body">תוכן</label>
                        <textarea id="article_body" name="body" rows="12" required>{{ old('body') }}</textarea>

                        <label class="toggle-row">
                            <input type="hidden" name="publish_now" value="0">
                            <input type="checkbox" name="publish_now" value="1" checked>
                            <span>לפרסם מיידית</span>
                        </label>

                        <button class="primary-button" type="submit">פרסם עמוד ידע</button>
                    </form>
                </section>
            </section>
        @endif
    @endauth
@endsection
