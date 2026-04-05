@extends('layouts.app')

@php($title = 'A11Y Bridge | כלי חינמי להטמעת וידג׳ט נגישות')
@php($metaDescription = 'A11Y Bridge היא שכבה חינמית, שקטה וברורה להטמעת וידג׳ט נגישות: פתיחת חשבון, קוד הטמעה קבוע, דשבורד, הצהרה בסיסית ותמיכה טכנית בלבד.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="public-stage public-stage-home">
        <div class="public-stage-copy public-stage-copy-centered">
            <div class="public-stage-kicker">
                <span>A11Y Bridge</span>
                <span>free self-service</span>
            </div>
            <h1>
                <span>שכבת נגישות</span>
                <span>טכנית שמחברים</span>
                <span class="is-accent">בדקות.</span>
            </h1>
            <p class="hero-text hero-text-lead">
                פותחים חשבון, מחברים אתר, מטמיעים snippet אחד ומנהלים widget, statement
                וחיווי התקנה מתוך סביבת עבודה שקטה אחת.
            </p>

            <div class="public-cta-row public-cta-row-hero">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('how-it-works') }}">איך זה עובד</a>
            </div>

            <div class="public-hero-proofline" aria-label="עקרונות המוצר">
                <span>snippet קבוע</span>
                <span>ניהול ממקום אחד</span>
                <span>תמיכה טכנית בלבד</span>
            </div>
        </div>

        <div class="public-stage-visual" aria-hidden="true">
            <div class="public-device-shell public-device-shell-poster">
                <div class="public-stage-canvas">
                    <div class="public-stage-canvas-topline">
                        <span>A11Y Bridge</span>
                        <span>INSTALL / WIDGET / STATEMENT</span>
                    </div>
                    <div class="public-stage-canvas-copy">
                        <small>workspace</small>
                        <strong>snippet אחד באתר. כל השאר מנוהל מרחוק.</strong>
                        <p>הטמעה, widget, statement וחיווי התקנה בלי להחליף קוד בכל שינוי.</p>
                    </div>
                    <div class="public-stage-canvas-grid">
                        <article class="is-primary">
                            <span>הטמעה</span>
                            <strong>snippet קבוע</strong>
                            <p>מטמיעים פעם אחת, ומכאן מנהלים מרחוק.</p>
                        </article>
                        <article>
                            <span>widget</span>
                            <strong>מנוהל מתוך dashboard</strong>
                            <p>טקסט, preset ופעולות נגישות במקום אחד.</p>
                        </article>
                        <article>
                            <span>statement</span>
                            <strong>מחובר למסך הציות</strong>
                            <p>נקודת פתיחה בסיסית, שקטה וברורה.</p>
                        </article>
                        <article>
                            <span>status</span>
                            <strong>זוהה / לא זוהה לאחרונה</strong>
                            <p>רואים אם שכבת הנגישות נטענת בפועל.</p>
                        </article>
                    </div>
                    <div class="public-stage-canvas-metrics">
                        <div><small>snippet</small><strong>פעם אחת</strong></div>
                        <div><small>dashboard</small><strong>מקום אחד</strong></div>
                        <div><small>support</small><strong>טכני בלבד</strong></div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section class="public-shell-section public-shell-section-divider">
        <div class="section-heading">
            <p class="eyebrow">מה מקבלים בחינם</p>
            <h2>הכול מתחיל ב־snippet אחד, ואז ממשיכים לנהל מרחוק.</h2>
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
