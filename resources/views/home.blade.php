@extends('layouts.app')

@php($title = 'A11Y Bridge | כלי חינמי להטמעת וידג׳ט נגישות')
@php($metaDescription = 'A11Y Bridge היא שכבה חינמית, שקטה וברורה להטמעת וידג׳ט נגישות: פתיחת חשבון, קוד הטמעה קבוע, דשבורד, הצהרה בסיסית ותמיכה טכנית בלבד.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="public-stage public-stage-home">
        <div class="public-stage-copy">
            <p class="eyebrow">A11Y Bridge</p>
            <h1>שכבה חינמית, ברורה ונקייה להטמעת וידג׳ט נגישות.</h1>
            <p class="hero-text hero-text-lead">
                פותחים חשבון, מוסיפים אתר, מטמיעים קוד קבוע, ורואים הכול מתוך סביבת עבודה אחת:
                widget, הצהרה בסיסית, זיהוי התקנה ותמיכה טכנית. לא שירות נגישות, לא trial,
                ולא שכבת “פרימיום” שחוסמת התחלה.
            </p>

            <div class="public-cta-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('how-it-works') }}">איך זה עובד</a>
            </div>
        </div>

        <div class="public-stage-visual" aria-hidden="true">
            <div class="public-device-shell public-device-shell-poster">
                <div class="public-device-topline">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="public-stage-poster">
                    <div class="public-stage-poster-copy">
                        <small>workspace</small>
                        <strong>חשבון אחד, אתר אחד, snippet אחד.</strong>
                        <p>מכאן ממשיכים לנהל את שכבת הנגישות הטכנית, בלי לגעת שוב בקוד על כל שינוי קטן.</p>
                    </div>
                    <div class="public-stage-poster-stack">
                        <article>
                            <span>הטמעה</span>
                            <strong>קוד קבוע לאתר</strong>
                            <p>שינויי widget והצהרה נמשכים מתוך המערכת.</p>
                        </article>
                        <article>
                            <span>בקרה</span>
                            <strong>זוהה / לא זוהה</strong>
                            <p>חיווי ברור אם ה־snippet נטען באתר בפועל.</p>
                        </article>
                        <article>
                            <span>הצהרה</span>
                            <strong>עמוד בסיסי וקישור ציבורי</strong>
                            <p>שכבה התחלתית מסודרת, בלי לבלבל עם שירות נגישות.</p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="public-shell-section public-shell-section-divider">
        <div class="section-heading">
            <p class="eyebrow">מה מקבלים בחינם</p>
            <h2>מוצר מלא מספיק כדי להתחיל נכון.</h2>
        </div>

        <div class="public-shell-grid public-shell-grid-four">
            <article class="public-shell-card">
                <small>01</small>
                <h3>ווידג׳ט מנוהל</h3>
                <p>שפה, טקסט כפתור, מיקום ומראה בסיסי מתוך סביבת העבודה.</p>
            </article>
            <article class="public-shell-card">
                <small>02</small>
                <h3>קוד הטמעה קבוע</h3>
                <p>מטמיעים פעם אחת, וכל שאר העדכונים נשארים מנוהלים מרחוק.</p>
            </article>
            <article class="public-shell-card">
                <small>03</small>
                <h3>דשבורד טכני ברור</h3>
                <p>זיהוי התקנה, סטטוס אתר, הצהרה בסיסית ובקרה במקום אחד.</p>
            </article>
            <article class="public-shell-card">
                <small>04</small>
                <h3>שכבה שקופה</h3>
                <p>תמיכה טכנית בלבד, עם גבול ברור בין A11Y Bridge לבין Brndini.</p>
            </article>
        </div>
    </section>

    <section class="public-shell-section public-shell-section-soft">
        <div class="section-heading">
            <p class="eyebrow">איך זה עובד</p>
            <h2>ארבעה צעדים קצרים, בלי בלגן מסביב.</h2>
        </div>

        <div class="public-flow-grid">
            <article class="public-flow-step">
                <span>01</span>
                <h3>פותחים חשבון</h3>
                <p>יוצרים משתמש, מוסיפים אתר ומקבלים סביבת עבודה מסודרת מהרגע הראשון.</p>
            </article>
            <article class="public-flow-step">
                <span>02</span>
                <h3>מטמיעים קוד קבוע</h3>
                <p>snippet אחד באתר, שממנו כל ההגדרות נמשכות בהמשך מתוך המערכת.</p>
            </article>
            <article class="public-flow-step">
                <span>03</span>
                <h3>מגדירים את השכבה</h3>
                <p>מעדכנים widget, שפה, טקסטים והצהרה בסיסית, בלי להסתבך עם כמה כלים במקביל.</p>
            </article>
            <article class="public-flow-step">
                <span>04</span>
                <h3>ממשיכים לנהל</h3>
                <p>רואים אם ההתקנה זוהתה, מה פתוח לטיפול, ומה קורה באתר בפועל.</p>
            </article>
        </div>

        <div class="public-inline-callout">
            <p>צריך רק widget, קוד הטמעה, הצהרה בסיסית ובקרה טכנית? נשארים כאן. צריך שכבה עסקית רחבה יותר? ממשיכים ל־Brndini.</p>
            <a class="ghost-button button-link" href="{{ route('how-it-works') }}">למהלך המלא</a>
        </div>
    </section>

    <section class="public-shell-section public-shell-section-last">
        <div class="section-heading">
            <p class="eyebrow">Brndini layer</p>
            <h2>Brndini היא שכבת המשך עסקית, לא “שדרוג לתוסף”.</h2>
        </div>

        <div class="public-dual-panel">
            <article class="public-decision-card">
                <p class="eyebrow">נשארים ב־A11Y Bridge</p>
                <h3>אם מה שצריך הוא widget, הטמעה, הצהרה בסיסית וניהול טכני.</h3>
                <ul class="compact-check-list">
                    <li>הטמעה באתר קיים</li>
                    <li>ניהול שוטף של הווידג׳ט והטקסטים</li>
                    <li>חיווי התקנה ובקרה בסיסית</li>
                    <li>תמיכה טכנית בלבד</li>
                </ul>
            </article>

            <article class="public-decision-card public-decision-card-accent">
                <p class="eyebrow">ממשיכים ל־Brndini</p>
                <h3>אם צריך אחסון, SEO, קמפיינים, תחזוקה, שדרוג אתר, דפי נחיתה או אוטומציות.</h3>
                <ul class="compact-check-list">
                    <li>שירותים עסקיים נפרדים מהכלי</li>
                    <li>פנייה עסקית מסודרת ולא טיקט תמיכה</li>
                    <li>שכבת המשך לעסק, לא “פרימיום נגישות”</li>
                    <li>גישה גם למוצרים הבאים של Brndini</li>
                </ul>
            </article>
        </div>

        <div class="public-cta-row">
            <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
            <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">שירותי Brndini</a>
        </div>
    </section>
@endsection
