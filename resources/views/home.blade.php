@extends('layouts.app')

@php($title = 'A11Y Bridge | כלי חינמי להטמעת וידג׳ט נגישות')
@php($metaDescription = 'A11Y Bridge היא שכבה חינמית, שקטה וברורה להטמעת וידג׳ט נגישות: פתיחת חשבון, קוד הטמעה קבוע, דשבורד, הצהרה בסיסית ותמיכה טכנית בלבד.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="public-stage public-stage-home">
        <div class="public-stage-copy">
            <p class="eyebrow">A11Y Bridge</p>
            <h1>כלי חינמי, שקט ומדויק להטמעת וידג׳ט נגישות ולניהול טכני של האתר.</h1>
            <p class="hero-text hero-text-lead">
                פותחים חשבון, מוסיפים אתר, מטמיעים קוד קבוע, ומנהלים את הווידג׳ט, ההצהרה הבסיסית
                וזיהוי ההתקנה מתוך סביבת עבודה אחת. בלי להציג את זה כשירות נגישות, ובלי להכריח
                מעבר למסלול שירות כדי להתחיל.
            </p>

            <div class="public-cta-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('how-it-works') }}">איך זה עובד</a>
            </div>

            <div class="public-meta-strip">
                <span>self-service</span>
                <span>קוד הטמעה קבוע</span>
                <span>הצהרה בסיסית</span>
                <span>תמיכה טכנית בלבד</span>
            </div>
        </div>

        <div class="public-stage-visual" aria-hidden="true">
            <div class="public-device-shell">
                <div class="public-device-topline">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="public-device-card public-device-card-accent">
                    <small>workspace</small>
                    <strong>חשבון אחד, אתר אחד, snippet אחד.</strong>
                    <p>כל שינוי עתידי נשאר מנוהל מתוך הדשבורד, לא מתוך הקוד.</p>
                </div>
                <div class="public-device-grid">
                    <article>
                        <small>הטמעה</small>
                        <strong>קוד קבוע</strong>
                    </article>
                    <article>
                        <small>דשבורד</small>
                        <strong>ניהול מרוכז</strong>
                    </article>
                    <article>
                        <small>הצהרה</small>
                        <strong>עמוד בסיסי</strong>
                    </article>
                    <article>
                        <small>סטטוס</small>
                        <strong>זוהה / לא זוהה</strong>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="public-shell-section">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מה מקבלים בחינם</p>
            <h2>לא דמו, לא trial ולא “עוד מעט תצטרך לשלם”. שכבה חינמית אמיתית שמספיקה להתחלה רצינית.</h2>
        </div>

        <div class="public-shell-grid public-shell-grid-four">
            <article class="public-shell-card">
                <small>01</small>
                <h3>ווידג׳ט מנוהל</h3>
                <p>שפה, מיקום, טקסט כפתור, מראה בסיסי ושליטה רציפה מתוך המערכת.</p>
            </article>
            <article class="public-shell-card">
                <small>02</small>
                <h3>קוד הטמעה קבוע</h3>
                <p>מטמיעים פעם אחת באתר, ולא חוזרים להחליף snippet על כל שינוי קטן.</p>
            </article>
            <article class="public-shell-card">
                <small>03</small>
                <h3>דשבורד ברור</h3>
                <p>זיהוי התקנה, סטטוס אתר, הצהרה בסיסית ובקרה טכנית במקום אחד.</p>
            </article>
            <article class="public-shell-card">
                <small>04</small>
                <h3>שכבה שקופה</h3>
                <p>תמיכה טכנית בלבד, בלי להבטיח שירות נגישות ובלי לבלבל מול Brndini.</p>
            </article>
        </div>
    </section>

    <section class="public-shell-section public-shell-section-soft">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">איך זה עובד</p>
            <h2>ארבעה צעדים קצרים. זה כל הסיפור.</h2>
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

        <div class="public-cta-row public-cta-row-center">
            <a class="ghost-button button-link" href="{{ route('how-it-works') }}">למהלך המלא</a>
            <a class="ghost-button button-link" href="{{ route('free-tool') }}">מה כלול בחינם</a>
        </div>
    </section>

    <section class="public-shell-section">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">Brndini layer</p>
            <h2>נשארים בכלי כשצריך רק שכבה חינמית. ממשיכים ל־Brndini רק כשצריך שכבה עסקית רחבה יותר.</h2>
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

        <div class="public-cta-row public-cta-row-center">
            <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
            <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">שירותי Brndini</a>
        </div>
    </section>
@endsection
