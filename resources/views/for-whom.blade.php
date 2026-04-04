@extends('layouts.app')

@php($title = 'למי A11Y Bridge מתאימה | בעלי אתרים, סוכנויות וצוותים')
@php($metaDescription = 'עמוד שמסביר למי A11Y Bridge מתאימה: בעלי אתרים, סוכנויות וצוותים שרוצים כלי חינמי להטמעת וידג׳ט נגישות, דשבורד, הצהרה בסיסית ובקרה טכנית.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="about-hero pricing-page-hero">
        <div class="about-hero-copy">
            <p class="eyebrow">למי זה מתאים</p>
            <h1>A11Y Bridge מתאימה למי שרוצה להתחיל מהר עם כלי חינמי, ברור ונוח לניהול.</h1>
            <p class="hero-text hero-text-lead">
                לא כל עסק מחפש פרויקט כבד, ולא כל סוכנות רוצה עוד תוסף מבלבל. A11Y Bridge נבנתה
                בשביל מי שצריך שכבת נגישות מסודרת, דשבורד ברור, הצהרה בסיסית וחיווי טכני,
                בלי להיכנס ישר למסלול שירות.
            </p>
            <div class="hero-action-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('pricing') }}">למודל החינמי</a>
            </div>
        </div>

        <div class="about-hero-panel pricing-page-panel">
            <div class="about-hero-grid">
                <article class="about-mini-card">
                    <span class="eyebrow">בעל אתר</span>
                    <strong>רוצה להטמיע מהר</strong>
                    <p>קוד קבוע, שליטה פשוטה וסטטוס ברור בלי להסתבך בטכני.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">סוכנות</span>
                    <strong>רוצה מערכת מסודרת</strong>
                    <p>ניהול כמה אתרים, הטמעות ורישיונות מתוך מקום אחד.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">צוות</span>
                    <strong>רוצה תהליך ברור</strong>
                    <p>דשבורד, הצהרה בסיסית, חיווי התקנה ובקרה טכנית.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">Brndini</span>
                    <strong>שכבה עסקית נפרדת</strong>
                    <p>אם צריך צמיחה, תשתית או שדרוג אתר, ממשיכים אחר כך לשירותים הנפרדים.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">שלושה מצבים קלאסיים</p>
            <h2>המוצר מתאים במיוחד למי שנמצא באחד מהתרחישים האלה.</h2>
        </div>

        <div class="audience-fit-grid">
            <article class="audience-fit-card">
                <span class="status-pill is-neutral">בעלי עסקים</span>
                <h3>יש לך אתר קיים, ואתה רוצה להפעיל שכבת נגישות בלי להרים פרויקט חדש.</h3>
                <p>פותחים חשבון, מוסיפים אתר, מטמיעים קוד אחד, ומקבלים שליטה על הווידג׳ט, ההצהרה והסטטוס.</p>
                <ul class="compact-check-list">
                    <li>הטמעה בסיסית תוך זמן קצר</li>
                    <li>ממשק ברור גם למי שלא טכני</li>
                    <li>שימוש יומיומי בלי תלות חיצונית</li>
                </ul>
            </article>

            <article class="audience-fit-card">
                <span class="status-pill is-neutral">סוכנויות</span>
                <h3>אתה צריך לנהל כמה לקוחות באותה שפה מוצרית ולא בעוד סט כלים מפוזר.</h3>
                <p>אותו תהליך התקנה, אותה שכבת ניהול ואותה בקרה טכנית על כמה אתרים ורישיונות ממקום אחד.</p>
                <ul class="compact-check-list">
                    <li>פחות בלגן בין לקוחות</li>
                    <li>אותו flow לכל אתר</li>
                    <li>נראות יותר רצינית מול הלקוח</li>
                </ul>
            </article>

            <article class="audience-fit-card">
                <span class="status-pill is-neutral">צוותים פנימיים</span>
                <h3>אתה מחפש שכבת ניהול מסודרת שמראה מה הוטמע, מה זוהה ומה עדיין חסר.</h3>
                <p>המערכת מתאימה גם לצוותים שרוצים מעקב מסודר ולא רק widget שמופיע באתר בלי הקשר.</p>
                <ul class="compact-check-list">
                    <li>סטטוס התקנה ברור</li>
                    <li>הצהרה בסיסית וקישור ציבורי</li>
                    <li>תמיכה טכנית במערכת עצמה</li>
                </ul>
            </article>
        </div>

        <div class="magazine-actions">
            <a class="ghost-button button-link" href="{{ route('agencies') }}">לעמוד הייעודי לסוכנויות</a>
        </div>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מה קורה ביום הראשון</p>
            <h2>לא צריך לגלות לבד איך להתחיל. זו התמונה הפרקטית של השימוש הראשוני.</h2>
        </div>

        <div class="about-process-grid">
            <article class="process-card-strong">
                <span class="process-index">01</span>
                <h3>פותחים חשבון ומוסיפים אתר</h3>
                <p>מייצרים סביבת עבודה לאתר, מקבלים מפתח אתר וקוד הטמעה קבוע.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">02</span>
                <h3>מגדירים את הבסיס</h3>
                <p>שפה, מיקום, טקסט כפתור, מראה widget והצהרה בסיסית.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">03</span>
                <h3>רואים אם זה עלה באמת</h3>
                <p>המערכת מציגה אם ההטמעה זוהתה, מתי נראתה לאחרונה ומה הסטטוס בפועל.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-plain">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מתי ממשיכים מעבר לכלי</p>
            <h2>המערכת עצמה מתאימה למי שרוצה self-service. Brndini נכנסת רק כשצריך שכבה עסקית נוספת.</h2>
        </div>

        <div class="decision-grid">
            <article class="decision-card decision-card-free">
                <span class="status-pill is-neutral">A11Y Bridge מספיקה</span>
                <strong>כשצריך widget, דשבורד, הצהרה בסיסית וניהול טכני.</strong>
                <ul class="compact-check-list">
                    <li>הטמעת קוד וניהול מרחוק</li>
                    <li>שימוש חינמי בלי שיחת מכירה</li>
                    <li>תמיכה טכנית במערכת</li>
                </ul>
            </article>
            <article class="decision-card decision-card-services">
                <span class="status-pill is-good">Brndini נכנסת</span>
                <strong>כשצריך תוצאה עסקית רחבה יותר: תשתית, צמיחה, שיווק או שדרוג אתר.</strong>
                <ul class="compact-check-list">
                    <li>אחסון ותחזוקת אתר</li>
                    <li>SEO, קמפיינים ודפי נחיתה</li>
                    <li>אוטומציות ושיפור תהליכים</li>
                </ul>
            </article>
        </div>

        <div class="magazine-actions">
            <a class="primary-button button-link" href="{{ route('register.show') }}">אני רוצה להתחיל בחינם</a>
            <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">אני צריך גם שירותים עסקיים</a>
            <a class="ghost-button button-link" href="{{ route('products', $marketingParams) }}">מעניין אותי גם מה Brndini בונה בהמשך</a>
        </div>
    </section>
@endsection
