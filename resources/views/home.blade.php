@extends('layouts.app')

@php($title = 'פלטפורמת נגישות לאתרים | Widget Hosted, דשבורד ניהול והצהרת נגישות | A11Y Bridge')

@section('content')
    <section class="home-hero" id="top">
        <div class="hero-copy hero-copy-wide">
            <p class="eyebrow">Accessibility platform</p>
            <h1>פלטפורמת נגישות לאתרים עם הטמעה קבועה, widget hosted וניהול מרכזי.</h1>
            <p class="hero-text hero-text-lead">
                A11Y Bridge נבנתה לעסקים, סוכנויות ואתרים שרוצים לנהל שכבת נגישות ממקום אחד:
                קוד הטמעה קבוע, הגדרות widget דינמיות, install center, statement ו־compliance ברור.
            </p>

            <div class="hero-action-row">
                <a class="primary-button button-link" href="#signup-form">פתח חשבון</a>
                <a class="ghost-button button-link" href="#platform-flow">איך זה עובד</a>
            </div>

            <ul class="hero-proof-list">
                <li>קוד הטמעה קבוע ועדכון אוטומטי לפי site key</li>
                <li>חיבור מהיר להצהרת נגישות, install center ו־compliance center</li>
                <li>מוכן למכירה לעסקים, סוכנויות ולקוחות enterprise</li>
            </ul>
        </div>

        <aside class="hero-visual">
            <div class="hero-panel hero-showcase">
                <span class="eyebrow">Product snapshot</span>
                <strong>שכבת מוצר מלאה, לא רק כפתור באתר.</strong>
                <p>מעל ה־widget יושבים dashboard, install flow, statement, compliance ו־account layer.</p>

                <div class="showcase-window">
                    <div class="showcase-topbar">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="showcase-grid">
                        <article class="showcase-card showcase-card-primary">
                            <span class="showcase-label">accessibility workspace</span>
                            <strong>Hosted widget</strong>
                            <p>הגדרות מותג, site key, כפתור נגישות וקוד הטמעה קבוע.</p>
                        </article>
                        <article class="showcase-card">
                            <span class="showcase-label">compliance</span>
                            <strong>Statement + service mode</strong>
                            <p>מסר ברור, statement URL ומסגרת שירות מקצועית.</p>
                        </article>
                        <article class="showcase-card">
                            <span class="showcase-label">installation</span>
                            <strong>Install once</strong>
                            <p>copy, paste, verify, ואז ממשיכים לנהל מרחוק.</p>
                        </article>
                        <article class="showcase-card">
                            <span class="showcase-label">content</span>
                            <strong>SEO articles</strong>
                            <p>עמודי תוכן לצמיחה אורגנית והסבר שוק בקטגוריה.</p>
                        </article>
                    </div>
                </div>
            </div>
        </aside>
    </section>

    <section class="trust-band" aria-label="signals">
        <div class="trust-item">
            <span class="trust-label">Hosted setup</span>
            <strong>Install once, manage centrally</strong>
        </div>
        <div class="trust-item">
            <span class="trust-label">Built for agencies</span>
            <strong>לקוחות, מאמרים ופריסה בקנה מידה</strong>
        </div>
        <div class="trust-item">
            <span class="trust-label">Accessibility ops</span>
            <strong>widget, statement ו־compliance במקום אחד</strong>
        </div>
    </section>

    <section class="section-band section-band-alt" id="platform-flow">
        <div class="section-heading">
            <p class="eyebrow">How it works</p>
            <h2>כך A11Y Bridge מפעילה את שכבת הנגישות שלך.</h2>
            <p class="hero-text">
                אנחנו לוקחים את האתר ממקטע התקנה יחיד אל שכבת ניהול שוטפת, עם תהליך ברור
                שמכסה setup, חוויית משתמש, statement, guidance ועדכונים שוטפים.
            </p>
        </div>

        <div class="process-grid">
            <article class="process-card">
                <span class="process-index">01</span>
                <h3>Connect</h3>
                <p>פותחים workspace, מוסיפים אתר ודומיין, ומקבלים site key וקוד הטמעה קבוע.</p>
            </article>
            <article class="process-card">
                <span class="process-index">02</span>
                <h3>Configure</h3>
                <p>מגדירים widget, statement, service mode, צבע, שפה ופרטי מותג מתוך dashboard אחד.</p>
            </article>
            <article class="process-card">
                <span class="process-index">03</span>
                <h3>Monitor</h3>
                <p>השינויים נמשכים באתר, הלקוח רואה widget מעודכן, ואתה ממשיך לנהל הכול מהפלטפורמה.</p>
            </article>
        </div>
    </section>

    <section class="section-band" id="solutions">
        <div class="section-heading">
            <p class="eyebrow">Core capabilities</p>
            <h2>Accessibility operations, simplified.</h2>
            <p class="hero-text">
                כל שכבה במוצר נועדה לחסוך friction: install קל, widget ברור, governance נכון,
                ו־SEO/content שמחזק את המכירה והאמון.
            </p>
        </div>

        <div class="feature-grid feature-grid-wide">
            <article class="feature-card">
                <h3>Hosted widget management</h3>
                <p>אותו snippet נשאר באתר, וכל שינוי בצבע, שפה, מיקום ויכולות נמשך אוטומטית מהפלטפורמה.</p>
            </article>
            <article class="feature-card">
                <h3>Install center</h3>
                <p>workflow התקנה ברור עם snippet, verification ו־handoff קל למיישם או לצוות של הלקוח.</p>
            </article>
            <article class="feature-card">
                <h3>Compliance center</h3>
                <p>statement, service mode ו־platform messaging כדי למסגר נכון מה הפלטפורמה עושה ומה לא.</p>
            </article>
            <article class="feature-card">
                <h3>Account & billing framing</h3>
                <p>אזור חשבון שמרגיש כמו מוצר SaaS אמיתי, עם plan framing, support ו־next actions.</p>
            </article>
            <article class="feature-card">
                <h3>Agency-ready publishing</h3>
                <p>אזור מאמרים עם יצירת תוכן מתוך משתמש מנהל, לחיזוק SEO והסבר שוק מתמשך.</p>
            </article>
            <article class="feature-card">
                <h3>Brand-consistent experience</h3>
                <p>ה־widget והפלטפורמה נראים כמו אותו מוצר, עם אותה שפה ויזואלית והיררכיה ברורה.</p>
            </article>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading">
            <p class="eyebrow">Why this matters</p>
            <h2>לא רק compliance. גם תפעול טוב יותר, גם מסר שיווקי חזק יותר.</h2>
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

    <section class="section-band section-band-dark">
        <div class="section-heading section-heading-light">
            <p class="eyebrow">Built to support compliance</p>
            <h2>הפלטפורמה נבנית סביב מסר defensible ו־WCAG workflow ברור.</h2>
            <p class="hero-text">
                במקום להבטיח "ציות בלחיצה", המערכת שלך נראית כמו פתרון אחראי: hosted widget, statement,
                install guidance, governance ו־service framing.
            </p>
        </div>

        <div class="compliance-badges">
            <span class="compliance-badge">WCAG workflow</span>
            <span class="compliance-badge">Accessibility statement</span>
            <span class="compliance-badge">Install verification</span>
            <span class="compliance-badge">Governance messaging</span>
            <span class="compliance-badge">Agency delivery</span>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading">
            <p class="eyebrow">Social proof</p>
            <h2>נבנה כדי להרגיש כמו פתרון SaaS שחברות רוצות לקנות.</h2>
        </div>

        <div class="testimonial-grid">
            <article class="testimonial-card">
                <p>“הטמעה אחת, שליטה מלאה, ופחות friction בכל שינוי קטן שאנחנו עושים ללקוחות.”</p>
                <strong>בעל סוכנות דיגיטל</strong>
            </article>
            <article class="testimonial-card">
                <p>“הערך האמיתי הוא לא רק ה־widget, אלא ה־dashboard, ה־statement והמסגור המקצועי מסביב.”</p>
                <strong>יועץ נגישות</strong>
            </article>
            <article class="testimonial-card">
                <p>“הפלטפורמה נראית כמו מוצר accessibility גדול, וזה משנה לגמרי איך הלקוח תופס את ההצעה.”</p>
                <strong>Partner / Reseller</strong>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading">
            <p class="eyebrow">Plans</p>
            <h2>חבילות שמסבירות ללקוח איפה הוא מתחיל ואיך הוא גדל.</h2>
        </div>

        <div class="pricing-grid">
            <article class="plan-card">
                <span class="status-pill is-neutral">Starter</span>
                <strong>לעסקים קטנים</strong>
                <p>widget hosted, install center, statement link ו־dashboard בסיסי.</p>
            </article>
            <article class="plan-card plan-card-current">
                <span class="status-pill is-good">Recommended</span>
                <strong>Growth</strong>
                <p>יותר governance, יותר תוכן, יותר framing ללקוחות ולסוכנויות.</p>
            </article>
            <article class="plan-card">
                <span class="status-pill is-neutral">Enterprise</span>
                <strong>מותאם לארגונים</strong>
                <p>delivery רחב יותר, מסכי account ו־compliance חזקים יותר ותפעול בקנה מידה.</p>
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
