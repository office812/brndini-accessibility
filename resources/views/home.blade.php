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
            </div>
        </div>

        <div class="public-stage-visual" aria-hidden="true">
            <div class="public-device-shell public-device-shell-poster">
                <div class="public-stage-canvas">
                    <div class="public-stage-canvas-topline">
                        <span>A11Y Bridge</span>
                        <span>INSTALL / WIDGET / STATEMENT</span>
                    </div>
                    <div class="public-stage-canvas-compact">
                        <div class="public-stage-canvas-copy public-stage-canvas-copy-compact">
                            <small>workspace</small>
                            <strong>snippet אחד באתר. כל השאר מנוהל מרחוק.</strong>
                            <p>הטמעה, widget, statement וחיווי התקנה מתוך סביבת עבודה אחת.</p>
                        </div>
                        <div class="public-stage-list">
                            <article class="is-primary">
                                <span>הטמעה</span>
                                <strong>snippet קבוע</strong>
                                <em>מטמיעים פעם אחת</em>
                            </article>
                            <article>
                                <span>widget</span>
                                <strong>מנוהל מתוך dashboard</strong>
                                <em>טקסטים ופעולות במקום אחד</em>
                            </article>
                        </div>
                    </div>
                    <div class="public-stage-canvas-metrics">
                        <div><small>snippet</small><strong>פעם אחת</strong></div>
                        <div><small>status</small><strong>זוהה</strong></div>
                        <div><small>support</small><strong>טכני בלבד</strong></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="public-stage-hero-actions">
            <div class="public-hero-proofline" aria-label="עקרונות המוצר">
                <span>snippet קבוע</span>
                <span>ניהול ממקום אחד</span>
                <span>תמיכה טכנית בלבד</span>
            </div>
        </div>

    </section>

    <section class="public-shell-section public-shell-section-logo-band">
        <div class="public-logo-band" aria-label="עקרונות המוצר">
            <span>snippet קבוע</span>
            <span>widget מנוהל מרחוק</span>
            <span>statement בסיסי</span>
            <span>חיווי התקנה</span>
            <span>תמיכה טכנית בלבד</span>
        </div>
    </section>

    <section class="public-shell-section public-shell-section-showcase">
        <div class="section-heading section-heading-heroish">
            <p class="eyebrow">מפסיקים להחליף קוד בכל שינוי</p>
            <h2>פותחים חשבון, מטמיעים snippet אחד, וכל שאר השכבה מנוהלת מתוך המערכת.</h2>
        </div>

        <div class="public-showcase-stack">
            <div class="public-showcase-visual public-showcase-visual-centered" aria-hidden="true">
                <div class="public-device-shell public-device-shell-inline">
                    <div class="public-stage-canvas public-stage-canvas-secondary">
                        <div class="public-stage-canvas-topline">
                            <span>OVERVIEW / INSTALL / STATUS</span>
                            <span>A11Y BRIDGE</span>
                        </div>
                        <div class="public-stage-canvas-grid public-stage-canvas-grid-dual">
                            <article class="is-primary">
                                <span>status</span>
                                <strong>widget זוהה</strong>
                                <p>הקוד באתר נטען ומעודכן מתוך סביבת העבודה.</p>
                            </article>
                            <article>
                                <span>statement</span>
                                <strong>נקודת פתיחה בסיסית</strong>
                                <p>הצהרה מחוברת למסך הציות בלי לערבב שירות נגישות.</p>
                            </article>
                            <article>
                                <span>preset</span>
                                <strong>מנוהל מרחוק</strong>
                                <p>טקסטים, preset ופעולות נגישות מנוהלים בלי החלפת snippet.</p>
                            </article>
                            <article>
                                <span>support</span>
                                <strong>טכני בלבד</strong>
                                <p>פונים לתמיכה טכנית רק כשבאמת צריך עזרה בשימוש במערכת.</p>
                            </article>
                        </div>
                    </div>
                </div>
            </div>

            <div class="public-showcase-copy public-showcase-copy-inline">
                <article>
                    <small>01</small>
                    <strong>הטמעה פעם אחת</strong>
                    <p>snippet קבוע באתר, בלי לחזור למפתח בכל שינוי טקסט או preset.</p>
                </article>
                <article>
                    <small>02</small>
                    <strong>ניהול מתוך dashboard</strong>
                    <p>widget, statement וחיווי התקנה ממקום אחד, בשפה שקטה ולא טכנית מדי.</p>
                </article>
                <article>
                    <small>03</small>
                    <strong>רואים מה חסר עכשיו</strong>
                    <p>המסכים הפנימיים מראים סטטוס, פעולה הבאה ותחזוקה שוטפת של השכבה.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="public-shell-section public-shell-section-metrics">
        <div class="section-heading section-heading-heroish">
            <p class="eyebrow">בנוי לבעלי אתרים, לסוכנויות ולצוותים פנימיים</p>
            <h2>מוצר אחד, סביבת עבודה אחת, וכל מה שצריך כדי להתחיל מהר.</h2>
        </div>

        <div class="public-metric-line">
            <article>
                <strong>1</strong>
                <span>snippet קבוע</span>
            </article>
            <article>
                <strong>4</strong>
                <span>מסכים מרכזיים לניהול</span>
            </article>
            <article>
                <strong>0</strong>
                <span>שירות נגישות במסווה</span>
            </article>
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

    <section class="public-shell-section public-shell-section-last public-shell-section-darkend">
        <div class="section-heading section-heading-heroish">
            <p class="eyebrow">Brndini</p>
            <h2>כשצריך מעבר למוצר, ממשיכים לשכבה עסקית נפרדת ולא “פרימיום של A11Y Bridge”.</h2>
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
