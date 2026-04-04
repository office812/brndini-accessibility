@extends('layouts.app')

@php($title = 'איך A11Y Bridge עובדת | פתיחת חשבון, הטמעה, הצהרה ובקרה טכנית')
@php($metaDescription = 'עמוד שמסביר איך A11Y Bridge עובדת בפועל: פתיחת חשבון, יצירת אתר, הטמעת קוד, ניהול הווידג׳ט, הצהרה בסיסית, זיהוי התקנה ותמיכה טכנית.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="about-hero pricing-page-hero">
        <div class="about-hero-copy">
            <p class="eyebrow">איך זה עובד</p>
            <h1>הזרימה של A11Y Bridge בנויה כדי להתחיל מהר, להטמיע פעם אחת, ולהמשיך לנהל הכול ממקום אחד.</h1>
            <p class="hero-text hero-text-lead">
                הרעיון פשוט: פותחים חשבון, יוצרים אתר, מקבלים קוד הטמעה קבוע, מגדירים את הווידג׳ט
                ואת ההצהרה הבסיסית, ואז מנהלים את השינויים מתוך הדשבורד בלי להחליף שוב קוד.
                התמיכה במערכת היא תמיכה טכנית בלבד, והמעבר לשירותי Brndini נשאר נפרד ואופציונלי.
            </p>
            <div class="hero-action-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('pricing') }}">למודל המלא</a>
            </div>
        </div>

        <div class="about-hero-panel pricing-page-panel">
            <div class="about-hero-grid">
                <article class="about-mini-card">
                    <span class="eyebrow">פתיחה</span>
                    <strong>חשבון ואתר</strong>
                    <p>יוצרים סביבת עבודה, מוסיפים אתר ומקבלים מפתח אתר וקוד הטמעה.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">ניהול</span>
                    <strong>ווידג׳ט והצהרה</strong>
                    <p>מגדירים שפה, מיקום, מראה, טקסט כפתור והצהרה בסיסית.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">בקרה</span>
                    <strong>זיהוי התקנה</strong>
                    <p>רואים אם הקוד זוהה, מתי נצפה לאחרונה, ומה מצב האתר.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">תמיכה</span>
                    <strong>טכנית בלבד</strong>
                    <p>עזרה בהטמעה ובשימוש במערכת, לא שירות נגישות מקצועי.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section-band section-band-plain">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">הזרימה בפועל</p>
            <h2>חמישה צעדים ברורים מהרגע הראשון ועד למצב יציב באתר.</h2>
        </div>

        <div class="about-process-grid">
            <article class="process-card-strong">
                <span class="process-index">01</span>
                <h3>פותחים חשבון</h3>
                <p>פותחים משתמש, מאשרים את התנאים, ונכנסים לדשבורד שממנו מתחילים את כל התהליך.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">02</span>
                <h3>מוסיפים אתר</h3>
                <p>מייצרים סביבת עבודה לאתר, מקבלים מפתח אתר, חבילה, סטטוס אתר וקוד הטמעה קבוע.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">03</span>
                <h3>מטמיעים פעם אחת</h3>
                <p>מעתיקים את הקוד לאתר. משם והלאה השינויים נמשכים מהפלטפורמה, בלי להחליף שוב קוד.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">04</span>
                <h3>מגדירים את החוויה</h3>
                <p>בוחרים שפה, מיקום, עיצוב widget, טקסטים, והצהרת נגישות בסיסית עם קישור ציבורי.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">05</span>
                <h3>ממשיכים לנהל</h3>
                <p>רואים מה זוהה באתר, אילו התראות פתוחות, ומבצעים שינויים מתוך הדשבורד לאורך זמן.</p>
            </article>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מה רואים בדשבורד</p>
            <h2>המוצר לא נעצר ב־widget. הוא נותן שכבת ניהול שלמה סביבו.</h2>
        </div>

        <div class="capability-grid">
            <article class="capability-card">
                <h3>סטטוס התקנה</h3>
                <p>אם הקוד זוהה, אם לא זוהה לאחרונה, ואם האתר עדיין מחכה להטמעה.</p>
            </article>
            <article class="capability-card">
                <h3>הצהרה בסיסית</h3>
                <p>יוצר הצהרה פשוט, קישור ציבורי קבוע וחיווי אם ההצהרה מחוברת.</p>
            </article>
            <article class="capability-card">
                <h3>בקרה והתראות</h3>
                <p>בדיקות בסיסיות, פעולות פתוחות והגדרות התראות בתוך מסך אחד.</p>
            </article>
            <article class="capability-card">
                <h3>ניהול עיצוב הווידג׳ט</h3>
                <p>שליטה על טקסטים, מראה, שפה, מיקום וכפתור הפתיחה של הווידג׳ט.</p>
            </article>
            <article class="capability-card">
                <h3>מרכז תמיכה טכנית</h3>
                <p>פניות טכניות על שימוש בפלטפורמה, לא על שירותי נגישות מקצועיים.</p>
            </article>
            <article class="capability-card">
                <h3>שכבת שירותים נפרדת</h3>
                <p>אם בהמשך צריך אחסון, SEO, קמפיינים או שדרוג אתר, Brndini זמינה בנפרד.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מתי זה מספיק ומתי לא</p>
            <h2>המערכת החינמית בנויה לתת שכבת מוצר ברורה, בלי לבלבל אותה עם שירות עסקי.</h2>
        </div>

        <div class="decision-grid">
            <article class="decision-card decision-card-free">
                <span class="status-pill is-neutral">A11Y Bridge מספיקה</span>
                <strong>כשהמטרה היא להטמיע widget, לנהל אותו, ולהחזיק הצהרה בסיסית ובקרה טכנית.</strong>
                <ul class="compact-check-list">
                    <li>הטמעה עצמית</li>
                    <li>ניהול מרחוק</li>
                    <li>תמיכה טכנית במערכת</li>
                </ul>
            </article>
            <article class="decision-card decision-card-services">
                <span class="status-pill is-good">Brndini נכנסת</span>
                <strong>כשהעסק צריך תוצאה רחבה יותר: תשתית, צמיחה, שיווק, שדרוג אתר או אוטומציות.</strong>
                <ul class="compact-check-list">
                    <li>אחסון ותחזוקה</li>
                    <li>SEO, תוכן וקמפיינים</li>
                    <li>שדרוג אתר ודפי נחיתה</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מה קורה משם</p>
            <h2>אפשר להתחיל בכלי עצמו, ואחר כך לבחור אם להישאר בו או להרחיב הלאה.</h2>
        </div>

        <div class="ecosystem-route-grid">
            <article class="ecosystem-route-card">
                <span class="eyebrow">התחלה מהירה</span>
                <h3>פותחים חשבון ועובדים לבד</h3>
                <p>זה המסלול למי שרוצה להתחיל עכשיו, לבדוק את הזרימה ולהטמיע באתר קוד קבוע בלי שיחת מכירה.</p>
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון</a>
            </article>
            <article class="ecosystem-route-card">
                <span class="eyebrow">שירותים עסקיים</span>
                <h3>אם צריך יותר תשתית או צמיחה</h3>
                <p>Brndini מציעה שכבה עסקית נפרדת של אחסון, SEO, קמפיינים, תחזוקה, שדרוג אתר ואוטומציות.</p>
                <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">לשירותי Brndini</a>
            </article>
            <article class="ecosystem-route-card">
                <span class="eyebrow">אקו־סיסטם</span>
                <h3>להמשיך גם למוצרים הבאים</h3>
                <p>אם מעניין אותך להישאר קרוב לכלים ש־Brndini בונה בהמשך, אפשר להיכנס גם לרשימת הגישה המוקדמת.</p>
                <a class="ghost-button button-link" href="{{ route('products', $marketingParams) }}">למוצרים נוספים</a>
            </article>
        </div>
    </section>
@endsection
