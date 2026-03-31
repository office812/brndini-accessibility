@extends('layouts.app')

@php($title = 'מחירון A11Y Bridge | חבילה חינמית וחבילת פרימיום')
@php($metaDescription = 'מחירון A11Y Bridge: חבילה חינמית להתחלה מהירה וחבילת פרימיום לכל היכולות הקריטיות של ווידג׳ט הנגישות.')

@section('content')
    <section class="about-hero pricing-page-hero">
        <div class="about-hero-copy">
            <p class="eyebrow">מחירון A11Y Bridge</p>
            <h1>שתי חבילות ברורות, מסודרות ונכונות למוצר מנוהל.</h1>
            <p class="hero-text hero-text-lead">
                בחרנו לבנות מחירון פשוט: חבילה חינמית להתחלה מהירה ופרימיום לכל היכולות הקריטיות,
                כדי שהלקוח יבין מיד מה הוא מקבל ומה הערך של כל רמה.
            </p>
            <div class="hero-action-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון</a>
                <a class="ghost-button button-link" href="{{ route('home') }}#how-a11y-bridge-works">איך זה עובד</a>
            </div>
        </div>

        <div class="about-hero-panel pricing-page-panel">
            <div class="about-hero-grid">
                <article class="about-mini-card">
                    <span class="eyebrow">חינם</span>
                    <strong>רוב היכולות</strong>
                    <p>מתחילים מהר עם ווידג׳ט, הטמעה קבועה וכלי נגישות מרכזיים.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">פרימיום</span>
                    <strong>עומק ושליטה</strong>
                    <p>כלי קריאה מתקדמים, פרופילים ייעודיים ושליטה עשירה יותר בחוויה.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">מודל פשוט</span>
                    <strong>בלי בלבול</strong>
                    <p>שתי חבילות בלבד, מסר חד וברור ללקוח ולשותף.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">צמיחה</span>
                    <strong>מעבר טבעי</strong>
                    <p>המסלול החינמי מייצר כניסה קלה, והפרימיום מייצר שדרוג ברור.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section-band section-band-alt pricing-page-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">החבילות</p>
            <h2>אותו מוצר, שתי רמות שימוש, מעבר ברור בין התחלה לעומק.</h2>
        </div>

        @include('partials.pricing-cards')
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">למי זה מתאים</p>
            <h2>מחירון שבנוי גם לעסקים קטנים וגם לאתרים שרוצים יותר שליטה.</h2>
        </div>

        <div class="capability-grid">
            <article class="capability-card">
                <h3>עסקים בתחילת הדרך</h3>
                <p>החבילה החינמית מאפשרת להטמיע קוד, להתחיל עם רוב היכולות, ולבדוק התאמה למוצר.</p>
            </article>
            <article class="capability-card">
                <h3>אתרים שרוצים חוויה עשירה</h3>
                <p>הפרימיום מוסיף את כלי הקריאה וההתאמה שהופכים את הווידג׳ט לפתרון עמוק יותר.</p>
            </article>
            <article class="capability-card">
                <h3>סוכנויות ושותפים</h3>
                <p>שתי חבילות בלבד עוזרות להסביר מהר את ההבדל, למכור נכון יותר, ולנהל ציפיות.</p>
            </article>
        </div>
    </section>
@endsection
