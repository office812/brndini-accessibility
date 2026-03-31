@extends('layouts.app')

@php($title = 'פלטפורמת נגישות לאתרים | וידג׳ט מנוהל, דשבורד והצהרת נגישות | A11Y Bridge')

@section('content')
    <section class="landing-hero" id="top">
        <div class="landing-hero-copy">
            <p class="eyebrow">פלטפורמת נגישות</p>
            <h1>פתרון נגישות אתרים עם וידג׳ט מנוהל, ניהול מרכזי והטמעה קבועה.</h1>
            <p class="hero-text hero-text-lead">
                A11Y Bridge מאפשרת לעסקים, סוכנויות ואתרים גדולים לנהל שכבת נגישות ממקום אחד:
                וידג׳ט מנוהל, מרכז התקנה, הצהרה, ציות ומסכי ניהול שנראים כמו מוצר אמיתי.
            </p>

            <div class="hero-action-row hero-action-row-center">
                <a class="primary-button button-link" href="{{ route('register.show') }}">התחל ניסיון</a>
                <a class="ghost-button button-link" href="#how-a11y-bridge-works">איך זה עובד</a>
            </div>
        </div>

        <div class="hero-product-stage" aria-label="A11Y Bridge platform preview">
            <div class="hero-product-float hero-float-right">
                <span class="hero-float-label">הטמעה מנוהלת</span>
                <strong>קוד אחד באתר</strong>
                <p>קוד הטמעה קבוע עם הגדרות דינמיות.</p>
            </div>

            <div class="hero-product-float hero-float-left">
                <span class="hero-float-label">ציות</span>
                <strong>הצהרה + מסלול שירות</strong>
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
                        <span>לוח ניהול</span>
                        <span>התקנה</span>
                        <span>ציות</span>
                        <span>חשבון</span>
                    </div>

                    <div class="hero-device-grid">
                        <article class="hero-device-card hero-device-card-primary">
                            <span class="hero-device-label">A11Y BRIDGE</span>
                            <strong>ווידג׳ט מנוהל</strong>
                            <p>מפתח אתר, הגדרות מותג, טקסט כפתור ושפה</p>
                        </article>
                        <article class="hero-device-card">
                            <span class="hero-device-label">התקנה</span>
                            <strong>מתקינים פעם אחת</strong>
                            <p>העתקה, הדבקה, רענון ואימות</p>
                        </article>
                        <article class="hero-device-card">
                            <span class="hero-device-label">ציות</span>
                            <strong>מרכז הצהרה</strong>
                            <p>קישור להצהרה, מסגור שירות ומסלול עבודה</p>
                        </article>
                        <article class="hero-device-card">
                            <span class="hero-device-label">תוכן</span>
                <strong>מאמרים אורגניים</strong>
                            <p>צמיחה אורגנית וחינוך שוק</p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="trust-bar" aria-label="trust signals">
        <div class="trust-chip">ניהול ווידג׳ט מנוהל</div>
        <div class="trust-chip">הטמעה אחת, ניהול מרחוק</div>
        <div class="trust-chip">הצהרה ומסגרת ציות</div>
        <div class="trust-chip">מותאם לסוכנויות וצוותים גדלים</div>
    </section>

    <section class="section-band section-band-plain" id="how-a11y-bridge-works">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">איך A11Y Bridge עובדת</p>
            <h2>שלושה שלבים פשוטים להפעלת שכבת נגישות מנוהלת.</h2>
        </div>

        <div class="flow-section">
            <div class="flow-steps">
                <article class="flow-step-card">
                    <span class="process-index">01</span>
                    <h3>פותחים אתר חדש</h3>
                    <p>פותחים סביבת עבודה, מוסיפים אתר, ומקבלים מפתח אתר וקוד הטמעה קבוע.</p>
                </article>
                <article class="flow-step-card">
                    <span class="process-index">02</span>
                    <h3>מגדירים את הפלטפורמה</h3>
                    <p>מגדירים widget, statement, שפה, מיקום, צבע ומסלול שירות.</p>
                </article>
                <article class="flow-step-card">
                    <span class="process-index">03</span>
                    <h3>מעדכנים ומנהלים</h3>
                    <p>האתר מושך את ההגדרות החדשות אוטומטית בלי להחליף שוב קוד.</p>
                </article>
            </div>

            <div class="flow-stage">
                <div class="flow-stage-card">
                    <span class="eyebrow">נגישות זורמת</span>
                    <h3>מוצר אחד שמחבר וידג׳ט, התקנה, הצהרה ולוח ניהול.</h3>
                    <p>
                        במקום כמה שכבות מפוזרות, A11Y Bridge נותנת תהליך אחד ברור ללקוח, למיישם
                        ולמי שמנהל את החשבון לאורך זמן.
                    </p>
                    <div class="flow-stage-pills">
                        <span class="preview-pill">ווידג׳ט</span>
                        <span class="preview-pill">התקנה</span>
                        <span class="preview-pill">ציות</span>
                        <span class="preview-pill">חשבון</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-banner">
        <div>
            <p class="eyebrow">בדוק עכשיו אם האתר שלך נגיש</p>
            <h2>קבל שליטה מלאה על שכבת הנגישות של האתר שלך.</h2>
        </div>
        <a class="primary-button button-link" href="{{ route('register.show') }}">פתח חשבון</a>
    </section>

    <section class="section-band" id="solutions">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">טכנולוגיה שעובדת</p>
            <h2>נגישות מנוהלת, פשוטה יותר להפעלה.</h2>
            <p class="hero-text">
                שכבת המוצר שלך לא צריכה להיות רק כפתור. היא צריכה לכלול התקנה, מסגור שירות,
                חשבון, הצהרה וצמיחת תוכן.
            </p>
        </div>

        <div class="capability-grid">
            <article class="capability-card">
                <h3>טעינה אוטומטית מהפלטפורמה</h3>
                <p>האתר נשאר עם אותו קוד הטמעה, וההגדרות נמשכות בזמן טעינה מתוך הפלטפורמה.</p>
            </article>
            <article class="capability-card">
                <h3>הטמעה פשוטה ושליטה מיידית</h3>
                <p>הלקוח רואה מסך התקנה ברור, העתקה והדבקה, ואימות מיידי.</p>
            </article>
            <article class="capability-card">
                <h3>מתאים כמעט לכל אתר</h3>
                <p>Custom code, WordPress, או builder אחר. העיקרון נשאר זהה.</p>
            </article>
            <article class="capability-card">
                <h3>ווידג׳ט עקבי עם המותג</h3>
                <p>מיקום, צבע, טקסט, שפה והעדפות תצוגה מתוך לוח ניהול אחד.</p>
            </article>
            <article class="capability-card">
                <h3>מסגור ציות ברור</h3>
                <p>הצהרה, מסלול שירות ומסגרת עבודה בלי להבטיח מה שהמוצר לא יכול להבטיח.</p>
            </article>
            <article class="capability-card">
                <h3>צמיחה דרך תוכן</h3>
                <p>אזור מאמרים מובנה שעוזר גם לקידום אורגני וגם להסברת הקטגוריה.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">הפתרון לכל אתר</p>
            <h2>מתאים לכל אתר, עם אותו תהליך ברור של ניהול והטמעה.</h2>
        </div>

        <div class="integration-cloud">
            <span class="integration-pill">WordPress</span>
            <span class="integration-pill">Shopify</span>
            <span class="integration-pill">WooCommerce</span>
            <span class="integration-pill">Webflow</span>
            <span class="integration-pill">Wix</span>
            <span class="integration-pill">Custom code</span>
            <span class="integration-pill">שירות לסוכנויות</span>
            <span class="integration-pill">קוד הטמעה מנוהל</span>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">ברוכים הבאים לכל מבקר</p>
            <h2>חוויית widget ברורה, מותאמת ומבוססת העדפות משתמש.</h2>
        </div>

        <div class="profile-grid">
            <article class="profile-card">
                <h3>קוראי מסך</h3>
                <p>גישה ישירה לכלי נגישות והצהרת נגישות מתוך ממשק ברור.</p>
            </article>
            <article class="profile-card">
                <h3>ניווט מקלדת</h3>
                <p>גישה נוחה לניווט ולעבודה בלי עכבר.</p>
            </article>
            <article class="profile-card">
                <h3>תמיכה חזותית</h3>
                <p>גודל טקסט, ניגודיות, והדגשת קישורים.</p>
            </article>
            <article class="profile-card">
                <h3>הפחתת תנועה</h3>
                <p>הפחתת אנימציות ותנועה עבור חוויה שקטה יותר.</p>
            </article>
            <article class="profile-card">
                <h3>תמיכה בשפות</h3>
                <p>ממשק עברית/אנגלית ותמיכה בלקוחות מגוונים.</p>
            </article>
            <article class="profile-card">
                <h3>גישה להצהרת נגישות</h3>
                <p>גישה שקופה להצהרת נגישות מתוך ה־widget.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-dark">
        <div class="section-heading section-heading-light section-heading-center">
            <p class="eyebrow">נבנה כדי לתמוך בציות</p>
            <h2>המערכת ממסגרת נגישות כמו פתרון רציני, לא כמו תוסף בודד.</h2>
            <p class="hero-text">
                וידג׳ט מנוהל, אימות התקנה, הצהרה, מסגור שירות ומסלול עבודה
                יוצרים יחד מוצר שנראה מקצועי יותר גם ללקוח וגם לשוק.
            </p>
        </div>

        <div class="compliance-badges">
            <span class="compliance-badge">תהליך עבודה ל־WCAG</span>
            <span class="compliance-badge">הצהרת נגישות</span>
            <span class="compliance-badge">אימות הטמעה</span>
            <span class="compliance-badge">מסלול שירות</span>
            <span class="compliance-badge">מסגור שירות ושקיפות</span>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">אמון והוכחת ערך</p>
            <h2>נראה כמו פתרון נגישות גדול, גם ברמה המוצרית וגם ברמה השיווקית.</h2>
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
                <p>“זה כבר נראה כמו מוצר נגישות אמיתי ולא כמו תוסף זמני.”</p>
                <strong>שותף / משווק</strong>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מסלולים</p>
            <h2>חבילות שמסבירות ללקוח איפה הוא מתחיל ואיך הוא גדל.</h2>
        </div>

        <div class="pricing-grid">
            <article class="plan-card">
                <span class="status-pill is-neutral">התחלה</span>
                <strong>לעסקים קטנים</strong>
                <p>וידג׳ט מנוהל, מרכז התקנה, קישור להצהרה ולוח ניהול בסיסי.</p>
            </article>
            <article class="plan-card plan-card-current">
                <span class="status-pill is-good">מומלץ</span>
                <strong>צמיחה</strong>
                <p>יותר מסגור שירות, יותר תוכן, ויותר כלים ללקוחות ולסוכנויות.</p>
            </article>
            <article class="plan-card">
                <span class="status-pill is-neutral">ארגוני</span>
                <strong>מותאם לארגונים</strong>
                <p>פריסה רחבה יותר, מסכי חשבון וציות חזקים יותר ותפעול בקנה מידה.</p>
            </article>
        </div>
    </section>

    <section class="section-band articles-band" id="articles">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מאמרים</p>
            <h2>אזור מאמרים שנראה כמו מגזין, ומחזק גם את הקידום האורגני וגם את האמון במוצר.</h2>
            <p class="hero-text">
                אזור התוכן נועד גם לעזור ללקוחות להבין את הפלטפורמה, וגם לענות לחיפושים אורגניים
                סביב תוסף נגישות לאתר, הצהרת נגישות, תהליך עבודה ל־WCAG וניהול שכבת נגישות.
            </p>
        </div>

        @if ($articles->isNotEmpty())
            @php($featuredArticle = $articles->first())
            @php($featuredCover = $featuredArticle->coverTheme())

            <div class="magazine-home-grid">
                <article class="magazine-feature-card article-cover article-cover-{{ $featuredCover['slug'] }}">
                    <div class="article-cover-art" aria-hidden="true">
                        <span class="article-cover-orb"></span>
                        <span class="article-cover-panel"></span>
                        <span class="article-cover-beam"></span>
                    </div>

                    <div class="magazine-feature-copy">
                        <p class="eyebrow">{{ $featuredCover['eyebrow'] }}</p>
                        <h3><a href="{{ route('articles.show', $featuredArticle) }}">{{ $featuredArticle->title }}</a></h3>
                        <p>{{ $featuredArticle->excerpt }}</p>
                        <div class="article-meta">
                            <span class="preview-pill">{{ optional($featuredArticle->published_at)->format('d.m.Y') }}</span>
                            <span class="preview-pill">{{ $featuredArticle->readingTimeMinutes() }} דקות קריאה</span>
                            <span class="preview-pill">{{ $featuredCover['chips'][0] }}</span>
                        </div>
                        <a class="text-link" href="{{ route('articles.show', $featuredArticle) }}">לקריאה מלאה</a>
                    </div>
                </article>

                <div class="magazine-side-list">
                    @foreach ($articles->skip(1)->take(3) as $article)
                        @php($cover = $article->coverTheme())
                        <article class="magazine-side-card">
                            <div class="magazine-side-thumb article-cover article-cover-{{ $cover['slug'] }}">
                                <div class="article-cover-art" aria-hidden="true">
                                    <span class="article-cover-orb"></span>
                                    <span class="article-cover-panel"></span>
                                </div>
                            </div>
                            <div>
                                <p class="meta-label">{{ optional($article->published_at)->format('d.m.Y') }}</p>
                                <h3><a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a></h3>
                                <p>{{ $article->excerpt }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @else
            <div class="article-grid">
                <article class="article-card">
                    <h3>בקרוב יופיעו כאן מאמרים</h3>
                    <p>אחרי הרצת ה־migration החדש יופיעו מאמרי ברירת מחדל, ומשם אפשר יהיה להוסיף עוד תוכן מתוך משתמש מנהל.</p>
                </article>
            </div>
        @endif

        <div class="magazine-actions">
            <a class="primary-button button-link" href="{{ route('articles.index') }}">לכל המאמרים</a>
        </div>

        @auth
            @if (auth()->user()->is_admin || auth()->user()->isSuperAdmin())
                <section class="panel-card article-admin-card" aria-labelledby="article-admin-title">
                    <p class="eyebrow">ניהול תוכן</p>
                    <h2 id="article-admin-title">פרסום מאמר חדש</h2>

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

    <section class="section-band section-band-cta" id="signup-form">
        <div class="auth-cta-grid">
            <section class="panel-card auth-cta-card">
                <p class="eyebrow">פתיחת ניסיון</p>
                <h2>פתיחת חשבון חדש</h2>
                <p class="hero-text">
                    מתחילים עם לוח ניהול, וידג׳ט מנוהל, מרכז התקנה, מרכז ציות וקוד הטמעה קבוע.
                </p>
                <a class="primary-button button-link" href="{{ route('register.show') }}">למסך פתיחת חשבון</a>
            </section>

            <section class="panel-card auth-cta-card">
                <p class="eyebrow">התחברות</p>
                <h2>כניסה למשתמש קיים</h2>
                <p class="hero-text">
                    כניסה מהירה להגדרות הווידג׳ט, למסך ההתקנה, למרכז הציות ולאזור החשבון.
                </p>
                <a class="ghost-button button-link" href="{{ route('login.show') }}">למסך התחברות</a>
            </section>
        </div>
    </section>
@endsection
