@extends('layouts.app')

@php($title = 'פלטפורמת נגישות לאתרים | Widget Hosted, דשבורד ניהול והצהרת נגישות | A11Y Bridge')

@section('content')
    <section class="home-hero" id="top">
        <div class="hero-copy hero-copy-wide">
            <p class="eyebrow">Website accessibility platform</p>
            <h1>נגישות אתרים ברמת enterprise, עם widget hosted וניהול מרכזי.</h1>
            <p class="hero-text hero-text-lead">
                A11Y Bridge משלבת widget hosted, דשבורד ניהול, guidance להתקנה, חיבור להצהרת נגישות
                ומסגור compliance ברור. הקוד נשאר קבוע באתר, והשליטה נשארת אצלך בפלטפורמה.
            </p>

            <div class="hero-action-row">
                <a class="primary-button button-link" href="#signup-form">התחל ניסיון</a>
                <a class="ghost-button button-link" href="#solutions">ראה פתרונות</a>
            </div>

            <ul class="hero-proof-list">
                <li>קוד הטמעה קבוע ועדכון אוטומטי לפי site key</li>
                <li>workflow ברור ל־WCAG, הצהרת נגישות ו־governance</li>
                <li>פתרון שמתאים לעסקים, סוכנויות ולקוחות enterprise</li>
            </ul>
        </div>

        <aside class="hero-visual">
            <div class="hero-panel">
                <span class="eyebrow">A11Y stack</span>
                <strong>קוד אחד באתר. שלוש שכבות של שליטה.</strong>
                <p>הטמעה קבועה, הגדרות widget דינמיות, וחיבור ברור למסכי install, compliance ו־account.</p>

                <div class="hero-stat-grid">
                    <div class="metric-card">
                        <strong>Hosted widget</strong>
                        <span>script אחד עם config שמתעדכן מהפלטפורמה</span>
                    </div>
                    <div class="metric-card">
                        <strong>Accessibility ops</strong>
                        <span>הגדרות, הטמעה, statement ו־compliance במקום אחד</span>
                    </div>
                    <div class="metric-card">
                        <strong>Agency ready</strong>
                        <span>מוכן למכירה, ללקוחות מרובים ול־SEO תוכני</span>
                    </div>
                </div>
            </div>
        </aside>
    </section>

    <section class="trust-band" aria-label="signals">
        <div class="trust-item">
            <span class="trust-label">WCAG workflow</span>
            <strong>Hosted remediation layer</strong>
        </div>
        <div class="trust-item">
            <span class="trust-label">Built for agencies</span>
            <strong>לקוחות, מאמרים, והטמעה בקנה מידה</strong>
        </div>
        <div class="trust-item">
            <span class="trust-label">Always on</span>
            <strong>שינויים מתעדכנים בלי להחליף snippet</strong>
        </div>
    </section>

    <section class="section-band" id="solutions">
        <div class="section-heading">
            <p class="eyebrow">Solutions</p>
            <h2>פתרונות נגישות שמותאמים למבנה העסק שלך.</h2>
            <p class="hero-text">
                לא רק כפתור באתר. שכבת המוצר נבנית סביב hosted widget, מסכי ניהול, תוכן,
                ותהליך ברור שמחבר בין UX, compliance ותפעול שוטף.
            </p>
        </div>

        <div class="feature-grid">
            <article class="feature-card">
                <h3>Automated hosted widget</h3>
                <p>אותו snippet נשאר באתר, וכל שינוי בצבע, שפה, מיקום ויכולות נמשך אוטומטית מהפלטפורמה.</p>
            </article>
            <article class="feature-card">
                <h3>Expert-facing dashboard</h3>
                <p>שליטה ב־install, statement, service mode, branding, account framing ותמיכה בלקוחות מתוך אזור אחד.</p>
            </article>
            <article class="feature-card">
                <h3>Content and compliance growth</h3>
                <p>אזור מאמרים מובנה, מסר משפטי אחראי, ובסיס טוב למכירה אורגנית של פתרונות נגישות.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt" id="how-it-works">
        <div class="section-heading">
            <p class="eyebrow">How it works</p>
            <h2>אתה בוחר את שכבת העבודה. הפלטפורמה עושה את השאר.</h2>
        </div>

        <div class="process-grid">
            <article class="process-card">
                <span class="process-index">01</span>
                <h3>פותחים workspace</h3>
                <p>יוצרים חשבון, מוסיפים אתר ודומיין, ומקבלים site key ציבורי וקוד הטמעה.</p>
            </article>
            <article class="process-card">
                <span class="process-index">02</span>
                <h3>מפעילים שכבת נגישות</h3>
                <p>מגדירים widget, מסלול שירות, הצהרת נגישות והעדפות תצוגה ממסך אחד.</p>
            </article>
            <article class="process-card">
                <span class="process-index">03</span>
                <h3>ממשיכים לנהל מרחוק</h3>
                <p>השינויים נשמרים מהדשבורד, נמשכים באתר בזמן טעינה, ונשארים קלים לתחזוקה.</p>
            </article>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading">
            <p class="eyebrow">More than compliance</p>
            <h2>נגישות טובה היא גם תפעול טוב יותר, גם מכירה טובה יותר.</h2>
            <p class="hero-text">
                כשבונים שכבת נגישות כמו מוצר ולא כמו patch, מקבלים חוויה יותר חזקה ללקוח,
                מסר מקצועי יותר בשוק, ובסיס יציב להתרחבות.
            </p>
        </div>

        <div class="feature-grid">
            <article class="feature-card">
                <h3>Demonstrate inclusion</h3>
                <p>האתר משדר רצינות, אחריות וחשיבה על משתמשים אמיתיים ולא רק על checkbox רגולטורי.</p>
            </article>
            <article class="feature-card">
                <h3>Reduce operational friction</h3>
                <p>הטמעה אחת, שינוי מרחוק, guidance ברור ללקוח ולמיישם, ופחות כאב בכל שינוי קטן.</p>
            </article>
            <article class="feature-card">
                <h3>Grow the business</h3>
                <p>עמוד בית, מסכי מוצר, SEO תוכני ו־dashboard שנראים כמו פתרון SaaS בוגר יותר.</p>
            </article>
        </div>
    </section>

    <section class="section-band articles-band" id="articles">
        <div class="section-heading">
            <p class="eyebrow">Articles</p>
            <h2>מאמרים שמחזקים את ה־SEO ומסבירים את המוצר כמו שצריך.</h2>
            <p class="hero-text">
                אזור התוכן נועד גם לעזור ללקוחות להבין את הפלטפורמה, וגם לענות לחיפושים אורגניים
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
                    מתחילים עם dashboard, widget hosted, install center, compliance center וקוד הטמעה קבוע.
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
                    כניסה מהירה להגדרות ה־widget, למסך ההתקנה, למסגור ה־compliance ולאזור החשבון.
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
