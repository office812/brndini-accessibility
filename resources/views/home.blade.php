@extends('layouts.app')

@php($title = 'כלי חינמי להטמעת וידג׳ט נגישות | דשבורד, הטמעה והצהרה בסיסית | A11Y Bridge')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="public-hero" id="top">
        <div class="public-hero-copy">
            <p class="eyebrow">A11Y Bridge</p>
            <h1>כלי חינמי, חד וברור להטמעת וידג׳ט נגישות ולניהול האתר ממקום אחד.</h1>
            <p class="hero-text hero-text-lead">
                פותחים חשבון, מוסיפים אתר, מעתיקים קוד הטמעה קבוע ומקבלים דשבורד, הצהרה בסיסית,
                זיהוי התקנה ובקרה טכנית. בלי שיחת מכירה, בלי בלבול ובלי להציג את זה כשירות נגישות.
            </p>

            <div class="hero-action-row public-hero-actions">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('how-it-works') }}">איך זה עובד</a>
            </div>

            <div class="public-hero-signals">
                <article class="public-hero-signal">
                    <span class="meta-label">מה מקבלים</span>
                    <strong>ווידג׳ט, דשבורד, קוד הטמעה והצהרה בסיסית</strong>
                </article>
                <article class="public-hero-signal">
                    <span class="meta-label">מה לא</span>
                    <strong>לא שירות נגישות, אלא כלי self-service עם תמיכה טכנית</strong>
                </article>
                <article class="public-hero-signal">
                    <span class="meta-label">מה הלאה</span>
                    <strong>Brndini נכנסת רק כשצריך אחסון, SEO, קמפיינים או שדרוג אתר</strong>
                </article>
            </div>
        </div>

        <div class="public-hero-stage" aria-label="תצוגת המוצר">
            <div class="public-stage-shell">
                <div class="public-stage-bar">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <div class="public-stage-grid">
                    <article class="public-stage-card public-stage-card-primary">
                        <span class="public-stage-label">הטמעה</span>
                        <strong>קוד קבוע פעם אחת באתר</strong>
                        <p>מעתיקים קוד אחד, ומאותו רגע כל שינוי נמשך מתוך המערכת.</p>
                    </article>
                    <article class="public-stage-card">
                        <span class="public-stage-label">דשבורד</span>
                        <strong>שליטה ברישיונות, הטמעה ובדיקות</strong>
                        <p>במקום להתרוצץ בין תוסף, מיילים וקוד, הכול נשאר באותו workspace.</p>
                    </article>
                    <article class="public-stage-card">
                        <span class="public-stage-label">הצהרה</span>
                        <strong>עמוד בסיסי מוכן לציבור</strong>
                        <p>יוצר הצהרה מובנה עם קישור קבוע, פרטי קשר ושפה ברורה.</p>
                    </article>
                    <article class="public-stage-card public-stage-card-accent">
                        <span class="public-stage-label">Brndini layer</span>
                        <strong>שירותים עסקיים רק כשצריך</strong>
                        <p>אחסון, SEO, קמפיינים, תחזוקה, שדרוג אתר ואוטומציות נשארים שכבה נפרדת.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="public-proof-band" aria-label="למה לעבוד עם A11Y Bridge">
        <div class="public-proof-copy">
            <p class="eyebrow">למה זה מרגיש אחרת</p>
            <h2>פחות טפסים, פחות תוספים, פחות חיכוך. יותר מוצר אחד שמתחיל לעבוד מהר ונשאר ברור גם אחרי ההטמעה.</h2>
            <p class="hero-text">
                A11Y Bridge לא נבנתה כדי למכור עוד חבילת נגישות, אלא כדי לתת שכבת עבודה חינמית
                שנראית טוב, נשארת יציבה, ומחברת את המשתמש רק כשבאמת יש צורך בשכבת שירות נוספת.
            </p>
        </div>

        <div class="public-proof-grid">
            <article class="public-proof-panel">
                <span class="meta-label">פתיחה מהירה</span>
                <strong>חשבון, אתר וקוד הטמעה תוך דקות</strong>
                <p>בלי שיחת מכירה ובלי לחכות למישהו שיפעיל את המערכת ידנית.</p>
            </article>
            <article class="public-proof-panel">
                <span class="meta-label">שליטה רציפה</span>
                <strong>האתר מושך שינויים מהדשבורד</strong>
                <p>אותו snippet נשאר קבוע, והשינויים עצמם מתעדכנים בלי להחליף שוב קוד.</p>
            </article>
            <article class="public-proof-panel">
                <span class="meta-label">מסר שקוף</span>
                <strong>כלי חינמי, תמיכה טכנית, שירותים נפרדים</strong>
                <p>אין בלבול בין מוצר self-service לבין Brndini כשכבת צמיחה עסקית.</p>
            </article>
            <article class="public-proof-panel public-proof-panel-list">
                <span class="meta-label">מה נשאר אצלך</span>
                <ul class="compact-check-list">
                    <li>הטמעה קבועה באתר</li>
                    <li>דשבורד ניהול קצר וברור</li>
                    <li>הצהרה בסיסית וקישור ציבורי</li>
                    <li>חיבור לשירותים רק כשצריך</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="public-command-board" aria-label="שכבת העבודה של המוצר">
        <div class="public-command-copy">
            <p class="eyebrow">מרכז עבודה אחד</p>
            <h2>כך זה נראה כשהשכבה החינמית כבר יושבת באתר ועובדים ממנה בפועל.</h2>
            <p class="hero-text">
                במקום להחזיק תוסף בצד, מסמך הצהרה במקום אחר ועוד קוד snippet במייל ישן,
                יש סביבת עבודה אחת שמרכזת סטטוס, התקנה, בדיקות והמלצות המשך.
            </p>
            <div class="public-link-row">
                <a class="ghost-button button-link" href="{{ route('register.show') }}">לפתוח חשבון</a>
                <a class="ghost-button button-link" href="{{ route('how-it-works') }}">לראות את הזרימה</a>
            </div>
        </div>

        <div class="public-command-visual" aria-hidden="true">
            <div class="public-command-shell">
                <div class="public-command-topline">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="public-command-panels">
                    <article>
                        <small>חשבון</small>
                        <strong>אתר אחד מתחיל מהר, כמה אתרים נשארים מסודרים</strong>
                    </article>
                    <article>
                        <small>הטמעה</small>
                        <strong>קוד קבוע עם חיווי אם זוהה או לא זוהה לאחרונה</strong>
                    </article>
                    <article>
                        <small>בקרה</small>
                        <strong>בדיקה, הצהרה וסטטוס במבט אחד</strong>
                    </article>
                    <article>
                        <small>צמיחה</small>
                        <strong>המשך טבעי ל־Brndini רק כשצריך שירות עסקי רחב יותר</strong>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="public-section" id="solutions">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">איך זה עובד בפועל</p>
            <h2>פחות מסכים מיותרים, יותר מהלך ברור שמוביל מהטמעה לשליטה רציפה.</h2>
        </div>

        <div class="public-workflow-grid">
            <div class="public-step-list">
                <article class="public-step-card">
                    <span class="process-index">01</span>
                    <h3>פותחים חשבון ומוסיפים אתר</h3>
                    <p>שם אתר, דומיין וחשבון אחד שממנו מתחילים לנהל את כל מה שקשור לווידג׳ט.</p>
                </article>
                <article class="public-step-card">
                    <span class="process-index">02</span>
                    <h3>מעתיקים קוד הטמעה קבוע</h3>
                    <p>snippet אחד באתר, ואחריו כל שינוי במראה, שפה, הצהרה ובקרה נשאר מנוהל מרחוק.</p>
                </article>
                <article class="public-step-card">
                    <span class="process-index">03</span>
                    <h3>ממשיכים לעבוד מתוך הדשבורד</h3>
                    <p>רואים זיהוי התקנה, סטטוס אתר, בדיקות בסיסיות והצהרה ציבורית בלי לחזור שוב לקוד.</p>
                </article>
            </div>

            <article class="public-workflow-stage">
                <p class="eyebrow">מה נכלל כבר בשכבה החינמית</p>
                <h3>ווידג׳ט, התקנה, הצהרה בסיסית ובקרה טכנית. בלי “פרימיום חובה” כדי להתחיל.</h3>
                <p>
                    A11Y Bridge בנויה ככלי חינמי self-service. היא לא מחליפה שירות נגישות, אלא נותנת
                    בסיס מוצרי אמיתי לעבודה יום־יומית: שכבת widget, זיהוי התקנה, עמוד הצהרה וקישור
                    לשירותי Brndini כשבאמת צריך יותר.
                </p>
                <ul class="compact-check-list">
                    <li>קוד הטמעה קבוע וקל לניהול</li>
                    <li>עיצוב widget מתוך דשבורד מרכזי</li>
                    <li>עמוד הצהרה בסיסי עם קישור קבוע</li>
                    <li>חיווי אם ההתקנה זוהתה או לא זוהתה לאחרונה</li>
                </ul>
                <div class="public-link-row">
                    <a class="ghost-button button-link" href="{{ route('how-it-works') }}">למהלך המלא</a>
                    <a class="ghost-button button-link" href="{{ route('free-tool') }}">מה כלול בחינם</a>
                </div>
            </article>
        </div>
    </section>

    <section class="public-section public-section-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">למי זה מתאים</p>
            <h2>המערכת לא מיועדת לכולם באותה צורה. היא בנויה לשלושה סוגי משתמשים ברורים.</h2>
        </div>

        <div class="public-audience-grid">
            <article class="public-audience-card">
                <span class="status-pill is-neutral">בעלי אתרים</span>
                <h3>מי שרוצה להפעיל שכבת נגישות במהירות, בלי להיתקע על עוד פרויקט.</h3>
                <p>חשבון אחד, אתר אחד, ווידג׳ט, הצהרה בסיסית וקוד התקנה ברור שמתחיל לעבוד מיד.</p>
                <ul class="compact-check-list">
                    <li>פחות תלות במפתח על כל שינוי קטן</li>
                    <li>סטטוס ברור של מה מותקן ומה חסר</li>
                    <li>מקום אחד לכל מה שקשור לשכבת הנגישות</li>
                </ul>
            </article>

            <article class="public-audience-card">
                <span class="status-pill is-neutral">סוכנויות</span>
                <h3>מי שמנהל כמה אתרים ורוצה תהליך קבוע, נקי ומכבד ללקוחות.</h3>
                <p>אותו onboarding, אותו widget, אותה התקנה ואותה תצורת ניהול לכל אתר ולקוח.</p>
                <ul class="compact-check-list">
                    <li>פחות כאוס בין לקוחות</li>
                    <li>פחות מעבר בין תוספים ומסמכים</li>
                    <li>נקודת שליטה אחידה שמתאימה לצוותים</li>
                </ul>
            </article>

            <article class="public-audience-card">
                <span class="status-pill is-neutral">צוותים פנימיים</span>
                <h3>מי שצריך לראות סטטוס, בדיקות, תמיכה טכנית וקישור לשירותים נוספים כשצריך.</h3>
                <p>המערכת נשארת self-service, אבל יודעת להפוך לליד איכותי לשירותי Brndini בלי לבלבל בין המוצר לבין השירות.</p>
                <ul class="compact-check-list">
                    <li>הפרדה ברורה בין כלי לבין שירות</li>
                    <li>גישה נוחה לחשבון, הצהרה והטמעה</li>
                    <li>חיבור טבעי לאקו־סיסטם העתידי של Brndini</li>
                </ul>
            </article>
        </div>

        <div class="public-link-row public-link-row-center">
            <a class="ghost-button button-link" href="{{ route('audiences') }}">למי זה מתאים</a>
            <a class="ghost-button button-link" href="{{ route('agencies') }}">עמוד לסוכנויות</a>
            <a class="ghost-button button-link" href="{{ route('use-cases') }}">תרחישי שימוש</a>
        </div>
    </section>

    <section class="public-section" id="pricing">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מודל ברור ושקוף</p>
            <h2>הכלי נשאר חינמי. Brndini מציעה שכבת שירותים עסקיים רק כשצריך אותה באמת.</h2>
            <p class="hero-text">
                אין כאן “חבילות נגישות” שמבלבלות בין self-service לבין שירות. יש מוצר חינמי שעובד,
                ומסלול נפרד למי שרוצה אחסון, SEO, קמפיינים, תחזוקה, אוטומציות או שדרוג אתר.
            </p>
        </div>

        @include('partials.pricing-cards')

        <div class="public-model-grid">
            <article class="public-model-card public-model-card-free">
                <p class="eyebrow">נשארים בכלי</p>
                <h3>אם מה שצריך הוא widget, הטמעה, הצהרה בסיסית וניהול טכני.</h3>
                <ul class="compact-check-list">
                    <li>הטמעה באתר קיים</li>
                    <li>ניהול שפה, מיקום, עיצוב וטקסטים</li>
                    <li>חיווי התקנה ודשבורד ניהול</li>
                    <li>הצהרת נגישות בסיסית</li>
                </ul>
            </article>
            <article class="public-model-card public-model-card-service">
                <p class="eyebrow">ממשיכים ל־Brndini</p>
                <h3>אם מה שצריך הוא שכבה עסקית רחבה יותר מסביב לאתר.</h3>
                <ul class="compact-check-list">
                    <li>אחסון, תשתית ותחזוקה</li>
                    <li>SEO, תוכן וקידום אורגני</li>
                    <li>קמפיינים, פרסום ודפי נחיתה</li>
                    <li>אוטומציות, שדרוגים והמשך צמיחה</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="public-section public-section-alt">
        <div class="public-service-layout">
            <div class="public-service-column">
                <div class="section-heading">
                    <p class="eyebrow">שירותי Brndini</p>
                    <h2>כשהכלי החינמי כבר עובד, אפשר להמשיך רק למה שבאמת מקדם את העסק.</h2>
                    <p class="hero-text">
                        Brndini לא מוצגת כאן כשירות נגישות, אלא כשכבה עסקית נפרדת: אחסון, SEO, קמפיינים,
                        תחזוקה, שדרוג אתר, דפי נחיתה ואוטומציות.
                    </p>
                </div>

                @include('partials.brndini-services-cards')
            </div>

            <aside class="public-ecosystem-panel">
                <p class="eyebrow">Brndini Ecosystem</p>
                <h3>הווידג׳ט הוא דלת הכניסה. המוצרים הבאים כבר מתחילים להתארגן סביב אותו עולם.</h3>
                <p>
                    אם מעניין אותך להיות ראשון בכלי SEO, תשתיות, אוטומציות, analytics או כלים נוספים,
                    אפשר להיכנס עכשיו לרשימת הגישה המוקדמת ולהישאר קרוב למה שנבנה ב־Brndini.
                </p>
                <ul class="compact-check-list">
                    <li>מוצרים עתידיים עם אותו קו מותג</li>
                    <li>שכבת לידים ושירותים שמתחברת לאותו חשבון</li>
                    <li>מסלול גישה מוקדמת למי שרוצה להיות ראשון</li>
                </ul>
                <div class="public-link-row">
                    <a class="primary-button button-link" href="{{ route('products', $marketingParams) }}">למוצרים הבאים</a>
                    <a class="ghost-button button-link" href="{{ route('brndini.services', array_merge($marketingParams, ['service' => 'ecosystem_access', 'entry' => 'home-ecosystem'])) }}#public-service-form">גישה מוקדמת</a>
                </div>
            </aside>
        </div>
    </section>

    <section class="public-section public-section-alt" id="faq">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">שאלות נפוצות</p>
            <h2>תשובות קצרות וברורות לפני שמתחילים לעבוד עם המערכת.</h2>
        </div>

        @include('partials.faq-items')

        <div class="public-link-row public-link-row-center">
            <a class="primary-button button-link" href="{{ route('faq') }}">לכל השאלות והתשובות</a>
        </div>
    </section>

    <section class="public-section public-section-articles" id="articles">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מאמרים</p>
            <h2>אזור תוכן נקי שמחזק גם אמון במוצר וגם תנועה אורגנית סביב הקטגוריה.</h2>
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

        <div class="public-link-row public-link-row-center">
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

    <section class="public-final-cta" id="signup-form">
        <div class="public-final-cta-copy">
            <p class="eyebrow">מוכנים להתחיל?</p>
            <h2>החשבון החינמי נותן מוצר אמיתי. Brndini נשארת זמינה רק כשצריך שכבת המשך עסקית.</h2>
            <p class="hero-text">
                אפשר לפתוח חשבון, להטמיע, לעבוד עם הדשבורד ולהישאר בכלי. ואם בהמשך תצטרך אחסון,
                שדרוג אתר, SEO, קמפיינים או אוטומציות, Brndini כבר מחכה באותו אקו־סיסטם.
            </p>
        </div>

        <div class="public-final-cta-actions">
            <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון</a>
            <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">שירותי Brndini</a>
            <a class="ghost-button button-link" href="{{ route('products', $marketingParams) }}">מוצרים נוספים</a>
        </div>
    </section>
@endsection
