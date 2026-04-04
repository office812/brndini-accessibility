@extends('layouts.app')

@php($title = 'מה כלול בחינם ב-A11Y Bridge | וידג׳ט, דשבורד, הצהרה ובקרה טכנית')
@php($metaDescription = 'עמוד שמרכז בדיוק מה כלול בחינם ב-A11Y Bridge: וידג׳ט נגישות, קוד הטמעה קבוע, דשבורד, הצהרת נגישות בסיסית, זיהוי התקנה ובקרה טכנית.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="about-hero pricing-page-hero">
        <div class="about-hero-copy">
            <p class="eyebrow">מה כלול בחינם</p>
            <h1>המודל של A11Y Bridge פשוט: הכלי עצמו חינמי, ברור ושימושי כבר מהיום הראשון.</h1>
            <p class="hero-text hero-text-lead">
                לא צריך שיחת מכירה כדי להתחיל, ולא צריך להיכנס למסלול שירות כדי להבין אם זה מתאים.
                כאן ריכזנו בצורה נקייה את כל מה שמקבלים בחינם, מה נשאר תמיכה טכנית בלבד,
                ומתי ממשיכים לשירותים של Brndini רק אם יש בזה צורך עסקי אמיתי.
            </p>
            <div class="hero-action-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('pricing') }}">למחירון המלא</a>
            </div>
        </div>

        <div class="about-hero-panel pricing-page-panel">
            <div class="about-hero-grid">
                <article class="about-mini-card">
                    <span class="eyebrow">חינם</span>
                    <strong>בלי חסם כניסה</strong>
                    <p>פותחים חשבון, יוצרים אתר ומתחילים לעבוד בלי תשלום התחלה.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">מוצר</span>
                    <strong>דשבורד + widget</strong>
                    <p>ניהול, הטמעה, עיצוב בסיסי, הצהרה וחיווי טכני במקום אחד.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">תמיכה</span>
                    <strong>טכנית בלבד</strong>
                    <p>עזרה בשימוש במערכת, לא שירות נגישות מקצועי או ייעוץ.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">Brndini</span>
                    <strong>שכבה אופציונלית</strong>
                    <p>אם צריך צמיחה או תשתית, עוברים לשירותים העסקיים בנפרד.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מה מקבלים בפועל</p>
            <h2>אלה הדברים שנכנסים לתוך השכבה החינמית של המערכת.</h2>
        </div>

        <div class="free-value-grid">
            <article class="free-value-card">
                <span class="process-index">01</span>
                <h3>ווידג׳ט נגישות מנוהל</h3>
                <p>כפתור נגישות, התאמות בסיסיות, שליטה על מיקום, שפה, טקסט כפתור ועיצוב כללי.</p>
            </article>
            <article class="free-value-card">
                <span class="process-index">02</span>
                <h3>קוד הטמעה קבוע</h3>
                <p>מטמיעים פעם אחת באתר, וממשיכים לעדכן מהדשבורד בלי להחליף שוב קוד.</p>
            </article>
            <article class="free-value-card">
                <span class="process-index">03</span>
                <h3>דשבורד ניהול</h3>
                <p>ניהול אתר, סטטוס התקנה, חשבון, ציות ובקרה טכנית מתוך סביבת עבודה אחת.</p>
            </article>
            <article class="free-value-card">
                <span class="process-index">04</span>
                <h3>הצהרת נגישות בסיסית</h3>
                <p>יוצר הצהרה, קישור ציבורי קבוע וחיבור ההצהרה מתוך הווידג׳ט.</p>
            </article>
            <article class="free-value-card">
                <span class="process-index">05</span>
                <h3>זיהוי התקנה</h3>
                <p>המערכת יודעת להראות אם הווידג׳ט זוהה, מתי נראה לאחרונה ומה מצב האתר.</p>
            </article>
            <article class="free-value-card">
                <span class="process-index">06</span>
                <h3>בדיקות וחיווי בסיסי</h3>
                <p>התראות, בדיקה בסיסית וחיווי מה פתוח לטיפול בממשק, בלי מערכת כבדה מדי.</p>
            </article>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מה לא כלול כאן</p>
            <h2>כדי שלא יהיה בלבול, הנה מה לא נכלל בשכבה החינמית.</h2>
        </div>

        <div class="decision-grid">
            <article class="decision-card decision-card-free">
                <span class="status-pill is-neutral">מה כן כלול</span>
                <ul class="compact-check-list">
                    <li>כלי self-service מלא להתחלה</li>
                    <li>תמיכה טכנית בשימוש בפלטפורמה</li>
                    <li>שליטה שוטפת דרך הדשבורד</li>
                    <li>סביבת עבודה ברורה לבעל האתר או לסוכנות</li>
                </ul>
            </article>
            <article class="decision-card decision-card-services">
                <span class="status-pill is-good">מה נשאר מחוץ לכלי</span>
                <ul class="compact-check-list">
                    <li>שירות נגישות מקצועי או ייעוץ</li>
                    <li>אחסון, SEO, קמפיינים ותחזוקת אתר</li>
                    <li>שדרוגי אתר, דפי נחיתה ואוטומציות</li>
                    <li>כל שכבת שירות עסקית של Brndini</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="section-band section-band-plain">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">למי זה מספיק</p>
            <h2>ברוב המקרים, מי שרוצה להטמיע widget ולהחזיק שכבת ניהול מסודרת, יכול להתחיל ולהישאר כאן.</h2>
        </div>

        <div class="audience-fit-grid">
            <article class="audience-fit-card">
                <span class="status-pill is-neutral">בעל אתר</span>
                <h3>צריך הטמעה מהירה ושכבת ניהול ברורה.</h3>
                <p>מספיק למי שרוצה לעלות מהר לאוויר ולנהל את השכבה בעצמו.</p>
            </article>
            <article class="audience-fit-card">
                <span class="status-pill is-neutral">סוכנות</span>
                <h3>צריך flow אחיד ללקוחות בלי להוסיף מורכבות.</h3>
                <p>מספיק לסוכנויות שרוצות מערכת מסודרת ולא שירות נוסף שהן מוכרות בעצמן.</p>
            </article>
            <article class="audience-fit-card">
                <span class="status-pill is-neutral">צוות פנימי</span>
                <h3>צריך לראות מה הוטמע ומה מצב האתר בפועל.</h3>
                <p>מספיק לצוותים שרוצים בקרה, סטטוס והצהרה בסיסית בלי להרים פרויקט נפרד.</p>
            </article>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">לאן ממשיכים מכאן</p>
            <h2>אם מה שכלול כאן מספיק לך, פותחים חשבון. אם צריך יותר, עוברים למסלול העסקי של Brndini.</h2>
        </div>

        <div class="ecosystem-route-grid">
            <article class="ecosystem-route-card">
                <span class="eyebrow">התחלה חינמית</span>
                <h3>הדרך הכי מהירה לבדוק אם זה מתאים</h3>
                <p>המסלול למי שרוצה לפתוח חשבון, להכניס אתר ולראות את המערכת עובדת באמת.</p>
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון</a>
            </article>
            <article class="ecosystem-route-card">
                <span class="eyebrow">עוד בהירות</span>
                <h3>רוצה להבין את ה-flow המלא?</h3>
                <p>יש גם עמודי עומק של “איך זה עובד”, “למי זה מתאים” ו”תרחישי שימוש” שיסגרו לך את התמונה.</p>
                <a class="ghost-button button-link" href="{{ route('how-it-works') }}">לאיך זה עובד</a>
            </article>
            <article class="ecosystem-route-card">
                <span class="eyebrow">Brndini</span>
                <h3>צריך תוצאה עסקית רחבה יותר?</h3>
                <p>אז ממשיכים בנפרד לאחסון, SEO, קמפיינים, תחזוקה, שדרוג אתר, דפי נחיתה ואוטומציות.</p>
                <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">לשירותי Brndini</a>
            </article>
        </div>
    </section>
@endsection
