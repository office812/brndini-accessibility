@extends('layouts.app')

@php($title = 'המוצרים הבאים של Brndini | גישה מוקדמת לכלים עתידיים')
@php($metaDescription = 'Brndini בונה עוד מוצרים וכלים דיגיטליים לעסקים: SEO, לידים, אוטומציות ובקרת אתר. אפשר להצטרף עכשיו לרשימת הגישה המוקדמת.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="about-hero pricing-page-hero">
        <div class="about-hero-copy">
            <p class="eyebrow">Brndini Ecosystem</p>
            <h1>הווידג׳ט הוא רק ההתחלה. Brndini בונה עוד כלים לעסקים שרוצים יותר שליטה.</h1>
            <p class="hero-text hero-text-lead">
                A11Y Bridge היא פלטפורמת הכניסה. במקביל, Brndini מפתחת עוד שכבות מוצר סביב SEO,
                לידים, אוטומציות, ביצועי אתר וצמיחה דיגיטלית. אם מעניין אותך לתפוס מקום מוקדם,
                אפשר להיכנס עכשיו לרשימת המתעניינים.
            </p>
            <div class="hero-action-row">
                <a class="primary-button button-link" href="{{ route('brndini.services', array_merge($marketingParams, ['service' => 'ecosystem_access', 'entry' => 'products-page'])) }}#public-service-form">אני רוצה גישה מוקדמת</a>
                <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">לשירותי Brndini</a>
            </div>
        </div>

        <div class="about-hero-panel pricing-page-panel">
            <div class="about-hero-grid">
                <article class="about-mini-card">
                    <span class="eyebrow">גישה מוקדמת</span>
                    <strong>להיות ברשימה לפני כולם</strong>
                    <p>מי שנכנס עכשיו מקבל עדיפות במוצרים החדשים שייפתחו.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">אקוסיסטם</span>
                    <strong>כלים שמתחברים זה לזה</strong>
                    <p>לא מוצרים מבודדים, אלא סביבת עבודה שחושבת על תנועה, שליטה וצמיחה.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">Brndini</span>
                    <strong>מותג אחד, כמה שכבות ערך</strong>
                    <p>כלים חינמיים, שירותים עסקיים ומוצרים חדשים שנבנים על אותה תשתית.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">פוקוס</span>
                    <strong>לא נגישות בלבד</strong>
                    <p>הדגש כאן הוא על צמיחה, תשתית, לידים, אוטומציות וביצועי אתר.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מה נבנה בהמשך</p>
            <h2>שכבת מוצרים שתמשוך תנועה, תייצר דאטה ותפתח עוד ערוצים ל־Brndini.</h2>
            <p class="hero-text">
                המטרה היא לא לבנות עוד כלי בודד, אלא אקו־סיסטם של מוצרים חינמיים וחכמים
                שיתאימו לבעלי עסקים, סוכנויות ואתרים שצריכים שליטה טובה יותר.
            </p>
        </div>

        @include('partials.brndini-future-products')
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">למי זה מתאים</p>
            <h2>רשימת הגישה המוקדמת מיועדת לאנשים שרוצים להישאר קרובים למוצרים הבאים של Brndini.</h2>
        </div>

        <div class="audience-fit-grid">
            <article class="audience-fit-card">
                <span class="status-pill is-neutral">בעלי עסקים</span>
                <h3>מי שרוצה להיחשף מוקדם לכלי צמיחה חדשים לפני כולם.</h3>
                <p>אם האתר הוא מנוע עסקי אמיתי, יש יתרון בלהיות ברשימה מוקדם כשהכלים החדשים נפתחים.</p>
            </article>

            <article class="audience-fit-card">
                <span class="status-pill is-neutral">סוכנויות</span>
                <h3>מי שמחפש עוד שכבות מוצר שאפשר להכניס ללקוחות קיימים.</h3>
                <p>גישה מוקדמת מאפשרת לזהות מהר כלים שיכולים להפוך להצעת ערך נוספת מול הלקוחות שלך.</p>
            </article>

            <article class="audience-fit-card">
                <span class="status-pill is-neutral">מנהלי שיווק וצמיחה</span>
                <h3>מי שרוצה סביבת עבודה חכמה יותר סביב תנועה, לידים ואוטומציה.</h3>
                <p>לא עוד כלי בודד, אלא אקו־סיסטם שיכול להתרחב סביב העסק ולחבר כמה שכבות פעולה יחד.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-plain">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">למה להצטרף עכשיו</p>
            <h2>מי שנכנס מוקדם מקבל יתרון כשכלי חדש נפתח.</h2>
        </div>

        <div class="about-process-grid">
            <article class="process-card-strong">
                <span class="process-index">01</span>
                <h3>תופסים מקום ברשימת העניין</h3>
                <p>Brndini יודעת איזה סוג מוצרים מעניינים אותך, ומתי נכון לפנות אליך.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">02</span>
                <h3>נשארים קרובים למותג</h3>
                <p>גם אם התחלת בווידג׳ט החינמי, אתה כבר בתוך סביבת המוצרים הרחבה יותר.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">03</span>
                <h3>עוברים ראשונים לכלי הבא</h3>
                <p>כשמוצר חדש ייפתח, מי שהצטרף מוקדם יקבל גישה מהירה יותר והצעות רלוונטיות.</p>
            </article>
        </div>

        <div class="brndini-service-actions">
            <a class="primary-button button-link" href="{{ route('brndini.services', array_merge($marketingParams, ['service' => 'ecosystem_access', 'entry' => 'products-page'])) }}#public-service-form">
                להצטרף לרשימת הגישה המוקדמת
            </a>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">איך זה מתחבר למערכת הקיימת</p>
            <h2>הווידג׳ט החינמי נשאר נקודת הכניסה, והכלים הבאים מרחיבים את סביבת העבודה של Brndini.</h2>
        </div>

        <div class="decision-grid">
            <article class="decision-card decision-card-free">
                <span class="status-pill is-neutral">מה קיים היום</span>
                <strong>כלי חינמי, דשבורד, הצהרה בסיסית ושכבת ניהול טכנית.</strong>
                <p>כבר עכשיו אפשר להטמיע, לנהל ולהישאר בתוך סביבת מוצר מסודרת בלי חיכוך.</p>
            </article>
            <article class="decision-card decision-card-services">
                <span class="status-pill is-good">מה מתווסף בהמשך</span>
                <strong>עוד מוצרים חכמים לצמיחה, תנועה, לידים, אוטומציה וביצועי אתר.</strong>
                <p>המטרה היא לייצר אקו־סיסטם שמביא אנשים לברנדיני ונשאר רלוונטי גם אחרי התקנת הווידג׳ט.</p>
            </article>
        </div>
    </section>
@endsection
