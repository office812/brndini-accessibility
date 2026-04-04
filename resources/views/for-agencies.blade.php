@extends('layouts.app')

@php($title = 'A11Y Bridge לסוכנויות | ניהול כמה אתרים, הטמעות ורישיונות ממקום אחד')
@php($metaDescription = 'עמוד ייעודי לסוכנויות: איך A11Y Bridge עוזרת לנהל כמה אתרים, הטמעות, רישיונות והצהרה בסיסית מתוך סביבת עבודה אחת, ומה Brndini מוסיפה כשצריך שכבה עסקית רחבה יותר.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="about-hero pricing-page-hero">
        <div class="about-hero-copy">
            <p class="eyebrow">לסוכנויות</p>
            <h1>סוכנויות לא צריכות עוד תוסף בודד. הן צריכות סביבת עבודה אחת ללקוחות, אתרים והטמעות.</h1>
            <p class="hero-text hero-text-lead">
                A11Y Bridge נותנת לסוכנויות דרך מסודרת להטמיע וידג׳ט נגישות, לייצר הצהרה בסיסית,
                לנהל רישיונות ולבדוק מה באמת הוטמע בכל אתר. בלי בלגן של כמה תוספים,
                ובלי להכניס כל לקוח למסלול שירות עוד לפני שהתחיל לעבוד.
            </p>
            <div class="hero-action-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">לפתיחת חשבון סוכנות</a>
                <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">לשירותי Brndini לסוכנויות</a>
            </div>
        </div>

        <div class="about-hero-panel pricing-page-panel">
            <div class="about-hero-grid">
                <article class="about-mini-card">
                    <span class="eyebrow">ניהול לקוחות</span>
                    <strong>כמה אתרים, שפה מוצרית אחת</strong>
                    <p>אותה התקנה, אותו דשבורד ואותו תהליך עבודה לכל לקוח.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">הטמעה</span>
                    <strong>קוד אחד שממשיך לחיות</strong>
                    <p>מעתיקים פעם אחת, וכל שינויי ההגדרות נמשכים משם.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">שקיפות</span>
                    <strong>רואים מה זוהה ומה לא</strong>
                    <p>סטטוס התקנה, הצהרה בסיסית, בקרה והתראות למקום אחד.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">Brndini</span>
                    <strong>שכבה עסקית נפרדת</strong>
                    <p>אחסון, SEO, קמפיינים, תחזוקה ושדרוגי אתר רק כשצריך.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">למה זה מתאים לסוכנויות</p>
            <h2>כי סוכנות צריכה flow אחיד, ברור וקל להסבר ללקוחות, לא עוד כלי צדדי שמרגיש זמני.</h2>
        </div>

        <div class="audience-fit-grid">
            <article class="audience-fit-card">
                <span class="status-pill is-neutral">יותר סדר</span>
                <h3>מפסיקים לנהל כל לקוח אחרת.</h3>
                <p>אותו מודל עבודה לכל אתר: יצירת סביבת עבודה, קוד הטמעה, ווידג׳ט, הצהרה בסיסית ובקרה טכנית.</p>
                <ul class="compact-check-list">
                    <li>פחות בלגן בין אתרים</li>
                    <li>אותו onboarding לכל לקוח</li>
                    <li>שליטה מרוכזת ברישיונות</li>
                </ul>
            </article>

            <article class="audience-fit-card">
                <span class="status-pill is-neutral">יותר אמון</span>
                <h3>הלקוח רואה סביבת ניהול מסודרת, לא רק כפתור.</h3>
                <p>המערכת נותנת תחושת מוצר אמיתי: מסכי התקנה, דשבורד, הצהרה בסיסית ותמיכה טכנית.</p>
                <ul class="compact-check-list">
                    <li>חוויית מוצר יותר חזקה מול הלקוח</li>
                    <li>נראות מקצועית ועקבית</li>
                    <li>פחות צורך להסביר ידנית כל שלב</li>
                </ul>
            </article>

            <article class="audience-fit-card">
                <span class="status-pill is-neutral">יותר אפשרויות</span>
                <h3>כשלקוח צריך מעבר לכלי, אפשר להמשיך ממנו לשירותים אחרים של Brndini.</h3>
                <p>הווידג׳ט החינמי נשאר כלי, והשכבה העסקית נשארת נפרדת: אחסון, שדרוג אתר, SEO, קמפיינים ותחזוקה.</p>
                <ul class="compact-check-list">
                    <li>Upsell טבעי בלי לכפות שירות</li>
                    <li>חיבור ישיר לשירותים עסקיים</li>
                    <li>אותו מותג, יותר ערך ללקוח</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">איך זה נראה ביום הראשון</p>
            <h2>סוכנות יכולה להתחיל מהר, ועדיין להרגיש שיש לה סביבת עבודה מסודרת לאורך זמן.</h2>
        </div>

        <div class="about-process-grid">
            <article class="process-card-strong">
                <span class="process-index">01</span>
                <h3>פותחים אתר ראשון</h3>
                <p>יוצרים סביבת עבודה ללקוח, מקבלים מפתח אתר וקוד הטמעה קבוע.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">02</span>
                <h3>מגדירים שפה, מיקום והצהרה</h3>
                <p>מתאימים את הווידג׳ט למותג, לטקסט ולמבנה העבודה של הלקוח.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">03</span>
                <h3>ממשיכים ללקוח הבא מאותו flow</h3>
                <p>לא ממציאים מחדש את התהליך. שומרים על שפה מוצרית אחידה לכל אתר נוסף.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-plain">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מתי נשארים בכלי ומתי עוברים הלאה</p>
            <h2>המערכת החינמית מכסה את שכבת הווידג׳ט והניהול. Brndini נכנסת רק כשהסוכנות צריכה שכבה עסקית רחבה יותר.</h2>
        </div>

        <div class="decision-grid">
            <article class="decision-card decision-card-free">
                <span class="status-pill is-neutral">נשארים ב־A11Y Bridge</span>
                <strong>כשצריך ניהול widget, קוד הטמעה, הצהרה בסיסית ובקרה טכנית.</strong>
                <ul class="compact-check-list">
                    <li>פתיחת אתרים ורישיונות</li>
                    <li>ניהול כמה לקוחות מאותו חשבון</li>
                    <li>תמיכה טכנית במערכת בלבד</li>
                </ul>
            </article>
            <article class="decision-card decision-card-services">
                <span class="status-pill is-good">עוברים ל־Brndini</span>
                <strong>כשצריך תשתית רחבה יותר: אחסון, תחזוקה, SEO, שדרוג אתר, דפי נחיתה או קמפיינים.</strong>
                <ul class="compact-check-list">
                    <li>שדרוג תשתית ללקוח קיים</li>
                    <li>מהלך צמיחה או פרסום</li>
                    <li>חבילות שירות נפרדות לסוכנות וללקוח</li>
                </ul>
            </article>
        </div>

        <div class="magazine-actions">
            <a class="primary-button button-link" href="{{ route('register.show') }}">להתחיל עם החשבון החינמי</a>
            <a class="ghost-button button-link" href="{{ route('pricing') }}">לראות איך זה בנוי</a>
            <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">לקבל שירותי Brndini לסוכנויות</a>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">שלב הבא לסוכנויות</p>
            <h2>אם אתם מנהלים כמה אתרים, זה העמוד שממנו הכי נכון להתחיל.</h2>
        </div>

        <div class="ecosystem-route-grid">
            <article class="ecosystem-route-card">
                <span class="eyebrow">חשבון חינמי</span>
                <h3>פותחים סביבת עבודה לסוכנות</h3>
                <p>המסלול הכי מהיר לבדוק את ה־flow, לפתוח לקוח ראשון ולהבין איך המערכת מרגישה בפועל.</p>
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון</a>
            </article>
            <article class="ecosystem-route-card">
                <span class="eyebrow">שירותים נלווים</span>
                <h3>מחפשים גם אחסון, SEO או שדרוג אתר?</h3>
                <p>Brndini יכולה להיכנס כשצריך שכבה עסקית רחבה יותר, אבל לא מערבבים את זה עם הכלי החינמי עצמו.</p>
                <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">לשירותי Brndini</a>
            </article>
            <article class="ecosystem-route-card">
                <span class="eyebrow">המשך אקו־סיסטם</span>
                <h3>רוצים לראות גם מה נבנה בהמשך?</h3>
                <p>אם הסוכנות שלך מחפשת עוד כלים למערך לקוחות, אפשר כבר עכשיו להיכנס לרשימת הגישה המוקדמת.</p>
                <a class="ghost-button button-link" href="{{ route('products', $marketingParams) }}">למוצרים נוספים</a>
            </article>
        </div>
    </section>
@endsection
