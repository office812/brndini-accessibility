@extends('layouts.app')

@php($title = 'A11Y Bridge | כלי חינמי להטמעת וידג׳ט נגישות')
@php($metaDescription = 'A11Y Bridge היא שכבה חינמית, שקטה וברורה להטמעת וידג׳ט נגישות: פתיחת חשבון, קוד הטמעה קבוע, דשבורד, הצהרה בסיסית ותמיכה טכנית בלבד.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="public-stage public-stage-home">
        <div class="public-stage-copy">
            <p class="eyebrow">A11Y Bridge</p>
            <h1>פותחים חשבון, מחברים אתר, ומטמיעים שכבת נגישות מנוהלת.</h1>
            <p class="hero-text hero-text-lead">
                כלי חינמי אחד שמוביל מהר מפתיחת חשבון ל־snippet קבוע, dashboard ברור,
                statement בסיסי וזיהוי התקנה. בלי מסלול פרימיום מבלבל, ובלי מסגור של שירות נגישות.
            </p>

            <div class="public-cta-row public-cta-row-hero">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('how-it-works') }}">איך זה עובד</a>
            </div>

            <div class="public-hero-notes" aria-label="יתרונות מרכזיים">
                <article>
                    <small>snippet</small>
                    <strong>מוסיפים פעם אחת</strong>
                </article>
                <article>
                    <small>dashboard</small>
                    <strong>מנהלים ממקום אחד</strong>
                </article>
                <article>
                    <small>support</small>
                    <strong>טכני בלבד</strong>
                </article>
            </div>
        </div>

        <div class="public-stage-visual" aria-hidden="true">
            <div class="public-device-shell public-device-shell-poster">
                <div class="public-stage-canvas">
                    <div class="public-stage-canvas-line"></div>
                    <div class="public-stage-canvas-copy">
                        <small>install / widget / statement</small>
                        <strong>סביבת עבודה שקטה וברורה שמנהלת את שכבת הנגישות מתוך חשבון אחד.</strong>
                        <p>לא מחליפים snippet בכל שינוי, לא מחפשים קבצים ידנית, ולא מתבלבלים בין מוצר חינמי לשירות עסקי.</p>
                    </div>
                    <div class="public-stage-canvas-stack">
                        <article>
                            <span>הטמעה</span>
                            <strong>snippet אחד, מנוהל מרחוק</strong>
                            <p>מטמיעים פעם אחת, וכל שינוי נמשך מתוך המערכת.</p>
                        </article>
                        <article>
                            <span>סטטוס</span>
                            <strong>זוהה / לא זוהה לאחרונה</strong>
                            <p>חיווי ברור אם שכבת הנגישות נטענת באתר בפועל.</p>
                        </article>
                    </div>
                    <div class="public-stage-canvas-metrics">
                        <div><small>widget</small><strong>מנוהל</strong></div>
                        <div><small>statement</small><strong>מחובר</strong></div>
                        <div><small>support</small><strong>טכני בלבד</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="public-shell-section public-shell-section-divider">
        <div class="section-heading">
            <p class="eyebrow">מה מקבלים בחינם</p>
            <h2>השכבה החינמית נועדה לעבוד באמת כבר מהיום הראשון.</h2>
        </div>

        <div class="public-proof-rail">
            <article>
                <small>ווידג׳ט</small>
                <strong>הגדרות ליבה מתוך dashboard</strong>
                <p>שפה, טקסט כפתור, preset ופקדים מרכזיים במקום אחד.</p>
            </article>
            <article>
                <small>הטמעה</small>
                <strong>קוד קבוע לאתר</strong>
                <p>שינויים נמשכים מרחוק בלי להחליף snippet בכל עדכון.</p>
            </article>
            <article>
                <small>ציות</small>
                <strong>statement בסיסי וזיהוי התקנה</strong>
                <p>נקודת פתיחה טכנית מסודרת בלי למסגר את זה כשירות נגישות.</p>
            </article>
        </div>
    </section>

    <section class="public-shell-section public-shell-section-soft">
        <div class="section-heading">
            <p class="eyebrow">איך זה עובד</p>
            <h2>ארבעה צעדים קצרים, ואז ממשיכים לנהל מתוך סביבת עבודה אחת.</h2>
        </div>

        <div class="public-flow-grid public-flow-grid-compact">
            <article class="public-flow-step">
                <span>01</span>
                <h3>פותחים חשבון</h3>
                <p>יוצרים משתמש ומוסיפים את האתר הראשון כדי לפתוח סביבת עבודה פעילה.</p>
            </article>
            <article class="public-flow-step">
                <span>02</span>
                <h3>מטמיעים קוד קבוע</h3>
                <p>snippet אחד באתר, שממנו ה־widget וה־statement נמשכים בהמשך.</p>
            </article>
            <article class="public-flow-step">
                <span>03</span>
                <h3>מגדירים את השכבה</h3>
                <p>מעדכנים מראה, טקסטים והצהרה בסיסית מתוך dashboard אחד.</p>
            </article>
            <article class="public-flow-step">
                <span>04</span>
                <h3>ממשיכים לנהל</h3>
                <p>רואים אם ההתקנה זוהתה, מה חסר, ומה כדאי לעשות עכשיו.</p>
            </article>
        </div>

        <div class="public-bridge-layout">
            <div>
                <p class="eyebrow">זה מספיק כדי להתחיל</p>
                <h3>אם מה שצריך הוא widget, snippet, statement בסיסי וניהול טכני, A11Y Bridge מספיקה לבד.</h3>
            </div>
            <div>
                <p>אם האתר צריך מעבר לזה תשתית, צמיחה, SEO, קמפיינים, תחזוקה או אוטומציות, לא מערבבים את זה עם הכלי. ממשיכים ל־Brndini רק כשצריך.</p>
                <a class="ghost-button button-link" href="{{ route('how-it-works') }}">למהלך המלא</a>
            </div>
        </div>
    </section>

    <section class="public-shell-section public-shell-section-last">
        <div class="section-heading">
            <p class="eyebrow">Brndini</p>
            <h2>Brndini היא שכבת המשך עסקית, לא “פרימיום של A11Y Bridge”.</h2>
        </div>

        <div class="public-dual-panel public-dual-panel-quiet">
            <article class="public-decision-card">
                <p class="eyebrow">נשארים ב־A11Y Bridge</p>
                <h3>כשמה שצריך הוא מוצר self-service ברור, מהיר ושקט ליישום.</h3>
                <ul class="compact-check-list">
                    <li>הטמעה באתר קיים</li>
                    <li>ניהול שוטף של הווידג׳ט והטקסטים</li>
                    <li>חיווי התקנה ובקרה בסיסית</li>
                    <li>תמיכה טכנית בלבד</li>
                </ul>
            </article>

            <article class="public-decision-card public-decision-card-accent">
                <p class="eyebrow">ממשיכים ל־Brndini</p>
                <h3>כשצריך שכבה רחבה יותר של תשתית, צמיחה, ביצועים או מוצרים נוספים.</h3>
                <ul class="compact-check-list">
                    <li>שירותים עסקיים נפרדים מהכלי</li>
                    <li>פנייה עסקית מסודרת ולא טיקט תמיכה</li>
                    <li>שכבת המשך לעסק, לא “פרימיום נגישות”</li>
                    <li>גישה גם לכלים נוספים שמתרחבים מתוך Brndini</li>
                </ul>
            </article>
        </div>

        <div class="public-cta-row public-cta-row-center">
            <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
            <a class="ghost-button button-link" href="{{ route('brndini.home', $marketingParams) }}">ל־Brndini</a>
        </div>
    </section>
@endsection
