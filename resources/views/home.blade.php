@extends('layouts.app')

@php($title = 'פלטפורמת נגישות לאתרים | Widget Hosted, דשבורד ניהול והצהרת נגישות | A11Y Bridge')

@section('content')
    <section class="home-hero" id="top">
        <div class="hero-copy hero-copy-wide">
            <p class="eyebrow">Website accessibility platform</p>
            <h1>פלטפורמת נגישות לאתרים עם widget hosted, דשבורד ניהול וקוד הטמעה קבוע.</h1>
            <p class="hero-text hero-text-lead">
                A11Y Bridge מאפשרת לבעלי אתרים, סוכנויות ועסקים לנהל שכבת נגישות באתר ממקום אחד:
                widget מעוצב, עדכון אוטומטי לפי site key, הצהרת נגישות, install guidance ו־governance ברור.
            </p>

            <div class="hero-action-row">
                <a class="primary-button button-link" href="#signup-form">פתח חשבון</a>
                <a class="ghost-button button-link" href="#articles">קרא מאמרים</a>
            </div>

            <div class="seo-bullets">
                <span class="preview-pill">תוסף נגישות לאתר</span>
                <span class="preview-pill">widget hosted</span>
                <span class="preview-pill">הצהרת נגישות</span>
                <span class="preview-pill">WCAG workflow</span>
            </div>
        </div>

        <aside class="hero-visual">
            <div class="hero-panel">
                <span class="eyebrow">Live platform snapshot</span>
                <strong>One hosted script. Centralized control.</strong>
                <p>שינוי צבע, טקסט, מיקום או statement URL מתעדכן באתר בלי להחליף שוב קוד.</p>

                <div class="hero-stat-grid">
                    <div class="metric-card">
                        <strong>Hosted widget</strong>
                        <span>Script קבוע עם config דינמי</span>
                    </div>
                    <div class="metric-card">
                        <strong>Dashboard</strong>
                        <span>ניהול לקוחות, install ו־compliance</span>
                    </div>
                    <div class="metric-card">
                        <strong>Articles</strong>
                        <span>תוכן אורגני וחינוך שוק מתוך הפלטפורמה</span>
                    </div>
                </div>
            </div>
        </aside>
    </section>

    <section class="section-band" id="why-a11y-bridge">
        <div class="section-heading">
            <p class="eyebrow">Built for growth</p>
            <h2>גם מוצר שמרגיש premium, גם בסיס חזק לצמיחה אורגנית.</h2>
            <p class="hero-text">
                עמוד הבית בנוי כדי להסביר בצורה פשוטה מה המערכת עושה, למה hosted configuration הוא יתרון,
                ואיך מציגים נגישות בצורה אחראית יותר.
            </p>
        </div>

        <div class="feature-grid">
            <article class="feature-card">
                <h3>קוד הטמעה קבוע</h3>
                <p>אותו snippet נשאר באתר, וכל השינויים מגיעים מהפלטפורמה לפי site key.</p>
            </article>
            <article class="feature-card">
                <h3>דשבורד ניהול ללקוחות</h3>
                <p>שליטה בצבע, גודל, מיקום, שפה, פיצ'רים, install ו־compliance במקום אחד.</p>
            </article>
            <article class="feature-card">
                <h3>שכבת governance אמיתית</h3>
                <p>המערכת מסבירה נכון מה ה־widget נותן ומה עדיין תלוי בקוד, בתוכן ובבדיקות.</p>
            </article>
            <article class="feature-card">
                <h3>בסיס ל־SEO תוכני</h3>
                <p>אזור מאמרים מובנה מאפשר לענות לחיפושים כמו “תוסף נגישות לאתר” או “הצהרת נגישות”.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt" id="how-it-works">
        <div class="section-heading">
            <p class="eyebrow">How it works</p>
            <h2>כך העסק עולה לאוויר עם מערכת נגישות hosted.</h2>
        </div>

        <div class="process-grid">
            <article class="process-card">
                <span class="process-index">01</span>
                <h3>פותחים חשבון</h3>
                <p>מזינים פרטי חברה, שם אתר ודומיין, ונוצר site key ציבורי.</p>
            </article>
            <article class="process-card">
                <span class="process-index">02</span>
                <h3>מגדירים widget</h3>
                <p>בוחרים מיקום, צבע, שפה והעדפות תצוגה מתוך dashboard אחד.</p>
            </article>
            <article class="process-card">
                <span class="process-index">03</span>
                <h3>מטמיעים פעם אחת</h3>
                <p>מעתיקים script קבוע לאתר, ומאותו רגע השינויים נמשכים מהשרת.</p>
            </article>
        </div>
    </section>

    <section class="section-band articles-band" id="articles">
        <div class="section-heading">
            <p class="eyebrow">Articles</p>
            <h2>מאמרים על נגישות אתרים, widget hosted ו-governance נכון.</h2>
            <p class="hero-text">
                אזור התוכן הזה נועד גם לעזור ללקוחות להבין את המוצר, וגם לענות לחיפושים אורגניים רלוונטיים
                סביב תוסף נגישות לאתר, הצהרת נגישות, workflow ל־WCAG וניהול שכבת נגישות.
            </p>
        </div>

        <div class="article-grid">
            @forelse ($articles as $article)
                <article class="article-card">
                    <p class="meta-label">{{ optional($article->published_at)->format('d.m.Y') }}</p>
                    <h3><a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a></h3>
                    <p>{{ $article->excerpt }}</p>
                    <a class="text-link" href="{{ route('articles.show', $article) }}">לקריאה מלאה</a>
                </article>
            @empty
                <article class="article-card">
                    <h3>בקרוב יופיעו כאן מאמרים</h3>
                    <p>אחרי הרצת ה־migration החדש יופיעו מאמרי ברירת מחדל, ומשם אפשר יהיה להוסיף עוד תוכן מתוך משתמש מנהל.</p>
                </article>
            @endforelse
        </div>

        @auth
            @if (auth()->user()->is_admin)
                <section class="panel-card article-admin-card" aria-labelledby="article-admin-title">
                    <p class="eyebrow">Admin publishing</p>
                    <h2 id="article-admin-title">פרסום מאמר חדש</h2>

                    <form class="stack-form" method="POST" action="{{ route('articles.store') }}">
                        @csrf

                        <label for="article_title">כותרת המאמר</label>
                        <input id="article_title" name="title" type="text" value="{{ old('title') }}" required>

                        <label for="article_excerpt">תקציר קצר</label>
                        <textarea id="article_excerpt" name="excerpt" rows="3" required>{{ old('excerpt') }}</textarea>

                        <label for="article_meta_description">Meta description</label>
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

    <section class="section-band section-band-cta" id="signup-form">
        <div class="auth-grid auth-grid-home">
            <section class="panel-card">
                <p class="eyebrow">Open account</p>
                <h2>פתיחת חשבון חדש</h2>
                <p class="hero-text">
                    התחילו עם dashboard, widget hosted, install center, compliance center וקוד הטמעה קבוע.
                </p>

                <form class="stack-form" method="POST" action="{{ route('register') }}">
                    @csrf

                    <label for="company_name">שם החברה</label>
                    <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" required>

                    <label for="signup_email">אימייל</label>
                    <input id="signup_email" name="email" type="email" value="{{ old('email') }}" required>

                    <label for="signup_password">סיסמה</label>
                    <input id="signup_password" name="password" type="password" minlength="8" required>

                    <label for="site_name">שם האתר</label>
                    <input id="site_name" name="site_name" type="text" value="{{ old('site_name') }}" required>

                    <label for="domain">דומיין</label>
                    <input id="domain" name="domain" type="text" value="{{ old('domain') }}" placeholder="https://your-site.com" required>

                    <button class="primary-button" type="submit">ליצור חשבון</button>
                </form>
            </section>

            <section class="panel-card" id="login-form" aria-labelledby="login-title">
                <p class="eyebrow">Sign in</p>
                <h2 id="login-title">כניסה למשתמש קיים</h2>
                <p class="hero-text">
                    חזרה מהירה ל־dashboard, install center, compliance center ואזור החשבון.
                </p>

                <form class="stack-form" method="POST" action="{{ route('login') }}">
                    @csrf

                    <label for="login_email">אימייל</label>
                    <input id="login_email" name="email" type="email" value="{{ old('email') }}" required>

                    <label for="login_password">סיסמה</label>
                    <input id="login_password" name="password" type="password" required>

                    <button class="secondary-button" type="submit">להיכנס</button>
                </form>
            </section>
        </div>
    </section>
@endsection
