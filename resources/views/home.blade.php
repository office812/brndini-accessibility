@extends('layouts.app')

@php($title = 'A11Y Bridge | כלי חינמי להטמעת וידג׳ט נגישות')
@php($metaDescription = 'A11Y Bridge היא שכבה חינמית, שקטה וברורה להטמעת וידג׳ט נגישות: פתיחת חשבון, קוד הטמעה קבוע, דשבורד, הצהרה בסיסית ותמיכה טכנית בלבד.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="public-stage public-stage-home">
        <div class="public-stage-copy">
            <p class="eyebrow">A11Y Bridge</p>
            <h1>פותחים חשבון, מחברים אתר, ומטמיעים שכבת נגישות טכנית שנשארת מנוהלת.</h1>
            <p class="hero-text hero-text-lead">
                A11Y Bridge היא נקודת כניסה חינמית לאתר: snippet קבוע, dashboard ברור, statement בסיסי,
                וזיהוי התקנה במקום אחד. המוצר נשאר self-service, התמיכה טכנית בלבד, ו־Brndini נכנסת רק כשצריך שכבת המשך עסקית.
            </p>

            <div class="public-cta-row public-cta-row-hero">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('how-it-works') }}">איך זה עובד</a>
            </div>

            <div class="public-hero-notes" aria-label="יתרונות מרכזיים">
                <span>snippet קבוע לאתר</span>
                <span>dashboard ברור וקל לסריקה</span>
                <span>statement בסיסי ותמיכה טכנית בלבד</span>
            </div>
        </div>

        <div class="public-stage-visual" aria-hidden="true">
            <div class="public-device-shell public-device-shell-poster">
                <div class="public-stage-canvas">
                    <div class="public-stage-canvas-line"></div>
                    <div class="public-stage-canvas-copy">
                        <small>workspace / install / statement</small>
                        <strong>מוצר חינמי שמתנהג כמו סביבת עבודה, לא כמו תוסף שנשכח בתחתית האתר.</strong>
                    </div>
                    <div class="public-stage-canvas-stack">
                        <article>
                            <span>האתר הפעיל</span>
                            <strong>snippet אחד, מנוהל מרחוק</strong>
                            <p>מטמיעים פעם אחת. שינויי widget והצהרה נמשכים מתוך המערכת.</p>
                        </article>
                        <article>
                            <span>סטטוס</span>
                            <strong>זוהה / לא זוהה לאחרונה</strong>
                            <p>חיווי אמיתי אם שכבת הנגישות נטענת באתר בפועל.</p>
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
            <h2>השכבה החינמית נותנת התחלה אמיתית, לא דמו.</h2>
        </div>

        <div class="public-proof-rail">
            <article>
                <small>ווידג׳ט</small>
                <strong>הגדרות בסיסיות מנוהלות מתוך dashboard</strong>
                <p>שפה, טקסט כפתור, preset, panel layout ופקדים מרכזיים.</p>
            </article>
            <article>
                <small>הטמעה</small>
                <strong>קוד קבוע לאתר</strong>
                <p>המערכת מושכת את השינויים מרחוק בלי להחליף snippet על כל שינוי.</p>
            </article>
            <article>
                <small>ציות</small>
                <strong>statement בסיסי וזיהוי התקנה</strong>
                <p>נקודת התחלה מסודרת וברורה בלי למסגר את זה כשירות נגישות.</p>
            </article>
        </div>
    </section>

    <section class="public-shell-section public-shell-section-soft">
        <div class="section-heading">
            <p class="eyebrow">איך זה עובד</p>
            <h2>ארבעה צעדים קצרים, ואז ממשיכים לנהל.</h2>
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
                <h3>אם הצורך הוא widget, snippet, statement בסיסי וניהול טכני, A11Y Bridge מספיקה לבד.</h3>
            </div>
            <div>
                <p>אם האתר צריך מעבר לזה תשתית, צמיחה, SEO, קמפיינים, תחזוקה או אוטומציות, לא מערבבים את זה עם הכלי. ממשיכים ל־Brndini.</p>
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
                <h3>כשמה שצריך הוא מוצר self-service ברור ומהיר ליישום.</h3>
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
