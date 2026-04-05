@extends('layouts.app')

@php($title = $article->title . ' | A11Y Bridge')
@php($topic = $article->knowledgeTopic())
@php($cta = $article->targetCta())

@section('content')
    @php($cover = $article->coverTheme())

    <article class="article-layout knowledge-article-layout">
        <section class="public-stage">
            <div class="public-stage-copy">
                <p class="eyebrow">{{ $topic['eyebrow'] }}</p>
                <h1>{{ $article->title }}</h1>
                <p class="hero-text hero-text-lead">{{ $article->excerpt }}</p>

                <div class="public-meta-strip">
                    <span>{{ $article->topicLabel() }}</span>
                    <span>{{ $article->pillarLabel() }}</span>
                    <span>{{ $article->contentIntentLabel() }}</span>
                    <span>{{ $article->readingTimeMinutes() }} דקות קריאה</span>
                </div>
            </div>

            <div class="public-stage-visual" aria-hidden="true">
                <div class="public-device-shell article-cover article-cover-{{ $cover['slug'] }}">
                    <div class="article-cover-art">
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
            </div>
        </section>

        <section class="public-shell-section">
            <div class="public-dual-panel">
                <article class="public-decision-card">
                    <p class="eyebrow">תשובה קצרה</p>
                    <h3>אם אתה רוצה להבין את זה מהר</h3>
                    <p>{{ $article->introAnswer() }}</p>
                </article>

                <article class="public-decision-card">
                    <p class="eyebrow">מאת מי</p>
                    <h3>{{ $article->author?->name ?? 'צוות A11Y Bridge' }}</h3>
                    <ul class="compact-check-list">
                        <li>{{ optional($article->published_at)->format('d.m.Y') }}</li>
                        <li>עודכן לאחרונה {{ optional($article->updated_at)->format('d.m.Y') }}</li>
                        <li>מרכז ידע סביב נגישות, widget, הצהרה ו־SEO</li>
                    </ul>
                </article>
            </div>
        </section>

        <section class="public-shell-section article-body-shell">
            <div class="article-body panel-card">
                {!! nl2br(e($article->body)) !!}
            </div>
        </section>

        <section class="public-shell-section public-shell-section-soft">
            <div class="public-dual-panel">
                <article class="public-decision-card public-decision-card-accent">
                    <p class="eyebrow">הצעד הבא</p>
                    <h3>{{ $cta['label'] }}</h3>
                    <p>{{ $cta['copy'] }}</p>
                    <div class="public-cta-row">
                        <a class="primary-button button-link" href="{{ $cta['route'] }}">{{ $cta['label'] }}</a>
                    </div>
                </article>

                <article class="public-decision-card">
                    <p class="eyebrow">בהירות</p>
                    <h3>העמוד הזה מסביר. המוצר פועל. Brndini נשארת שכבה עסקית נפרדת.</h3>
                    <ul class="compact-check-list">
                        <li>A11Y Bridge היא כלי חינמי self-service</li>
                        <li>תמיכה טכנית בלבד</li>
                        <li>לא שירות נגישות ולא התחייבות לציות מלא</li>
                    </ul>
                </article>
            </div>
        </section>

        @if ($relatedArticles->isNotEmpty())
            <section class="public-shell-section">
                <div class="section-heading section-heading-center">
                    <p class="eyebrow">עוד קריאה</p>
                    <h2>המשך ישיר לאותו אשכול.</h2>
                </div>

                <div class="public-shell-grid public-shell-grid-three">
                    @foreach ($relatedArticles as $relatedArticle)
                        <article class="public-shell-card">
                            <small>{{ $relatedArticle->topicLabel() }}</small>
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
