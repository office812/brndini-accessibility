@extends('layouts.app')

@php($title = 'פלטפורמת נגישות לאתרים | Widget Hosted, דשבורד ניהול והצהרת נגישות | A11Y Bridge')

@section('content')
    <section class="landing-hero" id="top">
        <div class="landing-hero-copy">
            <p class="eyebrow">ACCESSIBILITY PLATFORM</p>
            <h1>פתרון נגישות אתרים עם widget hosted, ניהול מרכזי והטמעה קבועה.</h1>
            <p class="hero-text hero-text-lead">
                A11Y Bridge מאפשרת לעסקים, סוכנויות ואתרים גדולים לנהל שכבת נגישות ממקום אחד:
                hosted widget, install center, statement, compliance ומסכי ניהול שנראים כמו מוצר אמיתי.
            </p>

            <div class="hero-action-row hero-action-row-center">
                <a class="primary-button button-link" href="{{ route('register.show') }}">התחל ניסיון</a>
                <a class="ghost-button button-link" href="#how-a11y-bridge-works">איך זה עובד</a>
            </div>
        </div>

        <div class="hero-product-stage" aria-label="A11Y Bridge platform preview">
            <div class="hero-product-float hero-float-right">
                <span class="hero-float-label">Hosted setup</span>
                <strong>קוד אחד באתר</strong>
                <p>snippet קבוע עם config דינמי.</p>
            </div>

            <div class="hero-product-float hero-float-left">
                <span class="hero-float-label">Compliance</span>
                <strong>statement + service mode</strong>
                <p>מסר ברור ושקוף ללקוח.</p>
            </div>

            <div class="hero-device">
                <div class="hero-device-topbar">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <div class="hero-device-screen">
                    <div class="hero-device-nav">
                        <span>Dashboard</span>
                        <span>Install</span>
                        <span>Compliance</span>
                        <span>Account</span>
                    </div>

                    <div class="hero-device-grid">
                        <article class="hero-device-card hero-device-card-primary">
                            <span class="hero-device-label">A11Y BRIDGE</span>
                            <strong>Hosted widget</strong>
                            <p>site key, brand settings, CTA label, language</p>
                        </article>
                        <article class="hero-device-card">
                            <span class="hero-device-label">INSTALL</span>
                            <strong>Install once</strong>
                            <p>Copy, paste, refresh, verify</p>
                        </article>
                        <article class="hero-device-card">
                            <span class="hero-device-label">COMPLIANCE</span>
                            <strong>Statement center</strong>
                            <p>statement URL, governance, service mode</p>
                        </article>
                        <article class="hero-device-card">
                            <span class="hero-device-label">CONTENT</span>
                            <strong>SEO articles</strong>
                            <p>organic growth and category education</p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="trust-bar" aria-label="trust signals">
        <div class="trust-chip">Hosted widget management</div>
        <div class="trust-chip">Install once, manage remotely</div>
        <div class="trust-chip">Statement and compliance framing</div>
        <div class="trust-chip">Built for agencies and growing teams</div>
    </section>

    <section class="section-band section-band-plain" id="how-a11y-bridge-works">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">How A11Y Bridge works</p>
            <h2>שלושה שלבים פשוטים להפעלת שכבת נגישות מנוהלת.</h2>
        </div>

        <div class="flow-section">
            <div class="flow-steps">
                <article class="flow-step-card">
                    <span class="process-index">01</span>
                    <h3>Audit your setup</h3>
                    <p>פותחים workspace, מוסיפים אתר, ומקבלים site key וקוד הטמעה קבוע.</p>
                </article>
                <article class="flow-step-card">
                    <span class="process-index">02</span>
                    <h3>Configure the platform</h3>
                    <p>מגדירים widget, statement, שפה, מיקום, צבע ומסלול שירות.</p>
                </article>
                <article class="flow-step-card">
                    <span class="process-index">03</span>
                    <h3>Monitor and update</h3>
                    <p>האתר מושך את ההגדרות החדשות אוטומטית בלי להחליף שוב snippet.</p>
                </article>
            </div>

            <div class="flow-stage">
                <div class="flow-stage-card">
                    <span class="eyebrow">SEAMLESS ACCESSIBILITY</span>
                    <h3>מוצר אחד שמחבר widget, install, statement ו־dashboard.</h3>
                    <p>
                        במקום כמה שכבות מפוזרות, A11Y Bridge נותנת flow אחד ברור ללקוח, למיישם
                        ולמי שמנהל את החשבון לאורך זמן.
                    </p>
                    <div class="flow-stage-pills">
                        <span class="preview-pill">Widget</span>
                        <span class="preview-pill">Install</span>
                        <span class="preview-pill">Compliance</span>
                        <span class="preview-pill">Account</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-banner">
        <div>
            <p class="eyebrow">Find out now if your website is accessible</p>
            <h2>קבל שליטה מלאה על שכבת הנגישות של האתר שלך.</h2>
        </div>
        <a class="primary-button button-link" href="{{ route('register.show') }}">פתח חשבון</a>
    </section>

    <section class="section-band" id="solutions">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">Technology that works</p>
            <h2>AI-driven accessibility, simplified.</h2>
            <p class="hero-text">
                שכבת המוצר שלך לא צריכה להיות רק widget. היא צריכה לכלול install, governance,
                account framing, statement ו־content growth.
            </p>
        </div>

        <div class="capability-grid">
            <article class="capability-card">
                <h3>Automatic hosted delivery</h3>
                <p>האתר נשאר עם אותו snippet, וההגדרות נמשכות בזמן טעינה מתוך הפלטפורמה.</p>
            </article>
            <article class="capability-card">
                <h3>Easy setup, immediate control</h3>
                <p>הלקוח רואה install ברור, copy-paste flow, ו־verification מיידי.</p>
            </article>
            <article class="capability-card">
                <h3>Compatible with any website</h3>
                <p>Custom code, WordPress, או builder אחר. העיקרון נשאר זהה.</p>
            </article>
            <article class="capability-card">
                <h3>Brand-consistent widget</h3>
                <p>מיקום, צבע, טקסט, שפה והעדפות תצוגה מתוך dashboard אחד.</p>
            </article>
            <article class="capability-card">
                <h3>Compliance messaging</h3>
                <p>Statement, service mode ו־governance בלי להבטיח מה שהמוצר לא יכול להבטיח.</p>
            </article>
            <article class="capability-card">
                <h3>Content-led growth</h3>
                <p>אזור מאמרים מובנה שעוזר גם ל־SEO וגם להסברת הקטגוריה.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">The solution for every website</p>
            <h2>מתאים לכל אתר, עם אותו flow ברור של ניהול והטמעה.</h2>
        </div>

        <div class="integration-cloud">
            <span class="integration-pill">WordPress</span>
            <span class="integration-pill">Shopify</span>
            <span class="integration-pill">WooCommerce</span>
            <span class="integration-pill">Webflow</span>
            <span class="integration-pill">Wix</span>
            <span class="integration-pill">Custom code</span>
            <span class="integration-pill">Agency delivery</span>
            <span class="integration-pill">Hosted snippet</span>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">Welcome all visitors</p>
            <h2>חוויית widget ברורה, מותאמת ומבוססת העדפות משתמש.</h2>
        </div>

        <div class="profile-grid">
            <article class="profile-card">
                <h3>Screen readers</h3>
                <p>גישה ישירה לכלי נגישות והצהרת נגישות מתוך ממשק ברור.</p>
            </article>
            <article class="profile-card">
                <h3>Keyboard navigation</h3>
                <p>גישה נוחה לניווט ולעבודה בלי עכבר.</p>
            </article>
            <article class="profile-card">
                <h3>Vision support</h3>
                <p>גודל טקסט, ניגודיות, והדגשת קישורים.</p>
            </article>
            <article class="profile-card">
                <h3>Reduce motion</h3>
                <p>הפחתת אנימציות ותנועה עבור חוויה שקטה יותר.</p>
            </article>
            <article class="profile-card">
                <h3>Language support</h3>
                <p>ממשק עברית/אנגלית ותמיכה בלקוחות מגוונים.</p>
            </article>
            <article class="profile-card">
                <h3>Statement access</h3>
                <p>גישה שקופה להצהרת נגישות מתוך ה־widget.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-dark">
        <div class="section-heading section-heading-light section-heading-center">
            <p class="eyebrow">Built to support compliance</p>
            <h2>המערכת ממסגרת נגישות כמו פתרון רציני, לא כמו תוסף בודד.</h2>
            <p class="hero-text">
                hosted widget, install verification, statement, governance ו־service mode
                יוצרים יחד מוצר שנראה מקצועי יותר גם ללקוח וגם לשוק.
            </p>
        </div>

        <div class="compliance-badges">
            <span class="compliance-badge">WCAG workflow</span>
            <span class="compliance-badge">Accessibility statement</span>
            <span class="compliance-badge">Install verification</span>
            <span class="compliance-badge">Service mode</span>
            <span class="compliance-badge">Governance messaging</span>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">Customer proof</p>
            <h2>נראה כמו פתרון accessibility גדול, גם ברמה המוצרית וגם ברמה השיווקית.</h2>
        </div>

        <div class="testimonial-grid">
            <article class="testimonial-card">
                <p>“הטמעה אחת, שליטה מלאה, ופחות friction בכל שינוי קטן שאנחנו עושים ללקוחות.”</p>
                <strong>בעל סוכנות דיגיטל</strong>
            </article>
            <article class="testimonial-card">
                <p>“הערך הוא לא רק ה־widget, אלא כל ה־platform layer שמסביב.”</p>
                <strong>יועץ נגישות</strong>
            </article>
            <article class="testimonial-card">
                <p>“זה כבר נראה כמו SaaS accessibility אמיתי ולא כמו תוסף זמני.”</p>
                <strong>Partner / Reseller</strong>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
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
        <div class="section-heading section-heading-center">
            <p class="eyebrow">Resources</p>
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
        <div class="auth-cta-grid">
            <section class="panel-card auth-cta-card">
                <p class="eyebrow">Start free trial</p>
                <h2>פתיחת חשבון חדש</h2>
                <p class="hero-text">
                    מתחילים עם dashboard, hosted widget, install center, compliance center וקוד הטמעה קבוע.
                </p>
                <a class="primary-button button-link" href="{{ route('register.show') }}">למסך פתיחת חשבון</a>
            </section>

            <section class="panel-card auth-cta-card">
                <p class="eyebrow">Login</p>
                <h2>כניסה למשתמש קיים</h2>
                <p class="hero-text">
                    כניסה מהירה להגדרות ה־widget, למסך ההתקנה, למסגור ה־compliance ולאזור החשבון.
                </p>
                <a class="ghost-button button-link" href="{{ route('login.show') }}">למסך התחברות</a>
            </section>
        </div>
    </section>
@endsection
