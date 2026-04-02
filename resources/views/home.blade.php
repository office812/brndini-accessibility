@extends('layouts.app')

@php($title = 'כלי חינמי להטמעת וידג׳ט נגישות | דשבורד, הטמעה והצהרה בסיסית | A11Y Bridge')

@section('content')
    <section class="landing-hero" id="top">
        <div class="landing-hero-copy">
            <p class="eyebrow">פלטפורמת נגישות</p>
            <h1>כלי חינמי להטמעת וידג׳ט נגישות, ניהול מרכזי והטמעה קבועה.</h1>
            <p class="hero-text hero-text-lead">
                A11Y Bridge מאפשרת לבעלי אתרים, סוכנויות וצוותים להטמיע וידג׳ט נגישות ממקום אחד:
                קוד הטמעה קבוע, דשבורד, הצהרה בסיסית, בקרה טכנית וממשק שנראה כמו מוצר אמיתי.
            </p>

            <div class="hero-action-row hero-action-row-center">
                <a class="primary-button button-link" href="{{ route('register.show') }}">התחל ניסיון</a>
                <a class="ghost-button button-link" href="#how-a11y-bridge-works">איך זה עובד</a>
            </div>
        </div>

        <div class="hero-product-stage" aria-label="A11Y Bridge platform preview">
            <div class="hero-product-float hero-float-right">
                <span class="hero-float-label">הטמעה</span>
                <strong>קוד אחד באתר</strong>
                <p>קוד הטמעה קבוע עם הגדרות דינמיות.</p>
            </div>

            <div class="hero-product-float hero-float-left">
                <span class="hero-float-label">הצהרה</span>
                <strong>הצהרה + עמוד ציבורי</strong>
                <p>ניסוח בסיסי, קישור קבוע ועמוד ברור לגולשים.</p>
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
                            <strong>ווידג׳ט נגישות</strong>
                            <p>מפתח אתר, הגדרות מותג, טקסט כפתור ושפה</p>
                        </article>
                        <article class="hero-device-card">
                            <span class="hero-device-label">התקנה</span>
                            <strong>מתקינים פעם אחת</strong>
                            <p>העתקה, הדבקה, רענון ואימות</p>
                        </article>
                        <article class="hero-device-card">
                            <span class="hero-device-label">הצהרה</span>
                            <strong>יוצר הצהרה</strong>
                            <p>קישור קבוע, ניסוח בסיסי ופרטי קשר</p>
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
        <div class="trust-chip">וידג׳ט חינמי עם שליטה מרחוק</div>
        <div class="trust-chip">הטמעה אחת, ניהול מרחוק</div>
        <div class="trust-chip">הצהרה בסיסית וקישור ציבורי</div>
        <div class="trust-chip">מותאם לבעלי אתרים, סוכנויות וצוותים</div>
    </section>

    <section class="company-proof-strip" aria-label="company proof">
        <article class="company-proof-card">
            <strong>פלטפורמה אחת</strong>
            <span>וידג׳ט, התקנה, הצהרה ובקרה טכנית</span>
        </article>
        <article class="company-proof-card">
            <strong>2 חבילות</strong>
            <span>חינם להתחלה ופרימיום ליכולות מתקדמות</span>
        </article>
        <article class="company-proof-card">
            <strong>Multi-site</strong>
            <span>ניהול כמה אתרים ורישיונות מאותו חשבון</span>
        </article>
        <article class="company-proof-card">
            <strong>תוכן אורגני</strong>
            <span>מאמרים, מסרים שיווקיים ועמודי מוצר</span>
        </article>
    </section>

    <section class="section-band section-band-plain" id="how-a11y-bridge-works">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">איך A11Y Bridge עובדת</p>
            <h2>שלושה שלבים פשוטים להפעלת כלי נגישות חינמי.</h2>
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
                    <p>מגדירים וידג׳ט, הצהרה בסיסית, שפה, מיקום וצבע.</p>
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
                    במקום כמה שכבות מפוזרות, A11Y Bridge נותנת תהליך אחד ברור לבעל האתר,
                    למי שמטמיע, ולמי שמנהל את החשבון לאורך זמן.
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
            <h2>כלי נגישות פשוט יותר להפעלה.</h2>
            <p class="hero-text">
                המוצר שלך לא צריך להיות רק כפתור. הוא צריך לכלול התקנה, חשבון, הצהרה בסיסית,
                בקרה טכנית וצמיחת תוכן.
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
                <h3>מסר ברור ושקוף</h3>
                <p>הצהרה בסיסית, קישור ציבורי וניסוח אחראי בלי להבטיח מה שהמוצר לא יכול להבטיח.</p>
            </article>
            <article class="capability-card">
                <h3>צמיחה דרך תוכן</h3>
                <p>אזור מאמרים מובנה שעוזר גם לקידום אורגני וגם להסברת הקטגוריה.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-plain enterprise-band">
        <div class="enterprise-band-grid">
            <div class="enterprise-band-copy">
                <p class="eyebrow">Enterprise ready</p>
                <h2>נראות של חברת הייטק, שליטה של SaaS ותהליך עבודה ברור לכל אתר.</h2>
                <p class="hero-text">
                    A11Y Bridge נבנתה כך שהמוצר עצמו ייראה רציני לא רק למבקר הקצה, אלא גם לבעל העסק,
                    לסוכנות, לאיש התמיכה ולכל מי שמנהל כמה אתרים במקביל.
                </p>
                <div class="flow-stage-pills">
                    <span class="preview-pill">Hosted widget</span>
                    <span class="preview-pill">Support center</span>
                    <span class="preview-pill">Super admin</span>
                    <span class="preview-pill">SEO content</span>
                </div>
            </div>

            <div class="enterprise-band-panel">
                <article class="enterprise-panel-card">
                    <span class="eyebrow">בקרה</span>
                    <strong>הטמעה, סטטוס, ציות וחבילה</strong>
                    <p>כל אתר מקבל מצב רישיון, קוד הטמעה, סטטוס חי וחיווי אמיתי על מה שהוטמע בפועל.</p>
                </article>
                <article class="enterprise-panel-card">
                    <span class="eyebrow">תפעול</span>
                    <strong>תפעול, משתמשים וסופר־אדמין</strong>
                    <p>ניהול פניות טכניות, מעקבים גלובליים, לקוחות, אתרים וחבילות מתוך אזור מרכזי אחד.</p>
                </article>
            </div>
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
            <span class="integration-pill">מתאים לסוכנויות</span>
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
                    וידג׳ט נגישות, אימות התקנה, הצהרה בסיסית ותהליך עבודה ברור
                יוצרים יחד מוצר שנראה מקצועי יותר גם ללקוח וגם לשוק.
            </p>
        </div>

        <div class="compliance-badges">
            <span class="compliance-badge">תהליך עבודה ל־WCAG</span>
            <span class="compliance-badge">הצהרת נגישות</span>
            <span class="compliance-badge">אימות הטמעה</span>
            <span class="compliance-badge">בדיקות בסיסיות</span>
            <span class="compliance-badge">שקיפות והצהרה</span>
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

        <div class="people-signal-band" aria-label="אנשים מאחורי המוצר">
            <article class="people-signal-card people-signal-card-highlight">
                <div class="people-signal-avatars">
                    <span>PM</span>
                    <span>UX</span>
                    <span>CS</span>
                </div>
                <div>
                    <p class="eyebrow">People layer</p>
                    <h3>צוות אחד שמחבר מוצר, תמיכה ותוכן.</h3>
                    <p>המערכת מרגישה חיה יותר כי היא בנויה כמו חברה אמיתית: מוצר, תמיכה, חוויית משתמש ותפעול סביב אותו לקוח.</p>
                </div>
            </article>

            <article class="people-signal-card">
                <strong>מוצר ונראות</strong>
                <p>שפת ממשק אחידה, ווידג׳ט מנוהל וזרימה שנראית כמו SaaS בוגר.</p>
            </article>

            <article class="people-signal-card">
                <strong>תמיכה והטמעה</strong>
                <p>מרכז תמיכה, סטטוס התקנה וחיווי אמיתי למה שמוטמע בפועל.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">שירותים נוספים של Brndini</p>
            <h2>הווידג׳ט נשאר חינמי. Brndini נכנסת כשצריך צמיחה, תשתית ותפעול.</h2>
            <p class="hero-text">
                A11Y Bridge היא דלת הכניסה. אחריה אפשר להמשיך לאחסון, SEO, קמפיינים, תחזוקת אתר,
                שדרוג אתר קיים, דפי נחיתה ואוטומציות, בלי לבלבל את זה עם תמיכה טכנית של המערכת.
            </p>
        </div>

        @include('partials.brndini-services-cards')
    </section>

    <section class="section-band section-band-plain">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">Brndini Ecosystem</p>
            <h2>הווידג׳ט הוא רק הצעד הראשון. Brndini בונה עוד כלים חינמיים וחכמים לעסקים.</h2>
            <p class="hero-text">
                אם מעניין אותך לקבל גישה מוקדמת לכלים הבאים של Brndini, אפשר להיכנס כבר עכשיו
                לרשימת העניין ולהיות הראשונים לשמוע כשמשהו חדש נפתח.
            </p>
        </div>

        @include('partials.brndini-future-products')

        <div class="magazine-actions">
            <a class="primary-button button-link" href="{{ route('brndini.services', ['service' => 'ecosystem_access']) }}#public-service-form">
                אני רוצה גישה מוקדמת
            </a>
            <a class="ghost-button button-link" href="{{ route('products') }}">לכל המוצרים הבאים</a>
            <a class="ghost-button button-link" href="{{ route('brndini.services') }}">לכל שירותי Brndini</a>
        </div>
    </section>

    <section class="section-band section-band-alt" id="pricing">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מסלולים</p>
            <h2>שתי חבילות ברורות: חינם להתחלה, פרימיום לכל היכולות הקריטיות.</h2>
            <p class="hero-text">
                בנינו את המוצר כך שכ־70% מהיכולות פתוחות כבר במסלול החינמי, וה־30% הכי מתקדמים
                והכי חשובים לעומק החוויה זמינים במסלול פרימיום.
            </p>
        </div>

        @include('partials.pricing-cards')
    </section>

    <section class="section-band section-band-plain" id="faq">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">שאלות נפוצות</p>
            <h2>תשובות קצרות שיעזרו להבין מהר מה המוצר נותן, ומה הוא לא.</h2>
            <p class="hero-text">
                זה המקום לעשות סדר: מה כולל הכלי החינמי, איך נראית התמיכה, מה קורה אחרי ההטמעה,
                ואיפה Brndini נכנסת עם שירותים עסקיים נפרדים.
            </p>
        </div>

        @include('partials.faq-items')

        <div class="magazine-actions">
            <a class="primary-button button-link" href="{{ route('faq') }}">לכל השאלות והתשובות</a>
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
