@extends('layouts.app')

@php($title = 'שאלות נפוצות | A11Y Bridge')
@php($metaDescription = 'שאלות נפוצות על A11Y Bridge: מה כולל הכלי החינמי, איך עובדת ההטמעה, מה זה לא כולל, ואיך Brndini משתלבת עם שירותים עסקיים נוספים.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="about-hero pricing-page-hero">
        <div class="about-hero-copy">
            <p class="eyebrow">שאלות נפוצות</p>
            <h1>כל מה שצריך לדעת לפני שמתחילים להשתמש ב־A11Y Bridge.</h1>
            <p class="hero-text hero-text-lead">
                ריכזנו כאן את השאלות הכי חשובות על הכלי החינמי, על ההתקנה, על ההצהרה הבסיסית,
                על התמיכה הטכנית, ועל הדרך שבה Brndini מציעה שירותים עסקיים נפרדים למי שצריך.
            </p>
            <div class="hero-action-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('how-it-works') }}">איך זה עובד</a>
                <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">לשירותי Brndini</a>
            </div>
        </div>

        <div class="about-hero-panel pricing-page-panel">
            <div class="about-hero-grid">
                <article class="about-mini-card">
                    <span class="eyebrow">חינם</span>
                    <strong>ווידג׳ט, דשבורד והצהרה בסיסית</strong>
                    <p>המוצר מיועד לתת שכבת נגישות self-service, עם הטמעה פשוטה וניהול מרכזי.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">תמיכה</span>
                    <strong>טכנית בלבד</strong>
                    <p>התמיכה במערכת עוזרת בהטמעה, שימוש ותפעול, אבל אינה שירות נגישות מקצועי.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">Brndini</span>
                    <strong>שירותים עסקיים נפרדים</strong>
                    <p>אחסון, SEO, קמפיינים, תחזוקה, שדרוג אתר, דפי נחיתה ואוטומציות.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">שקיפות</span>
                    <strong>הגדרות, זיהוי התקנה ומעקב</strong>
                    <p>המערכת עוזרת להבין מה הוטמע, מה זוהה, ומה דורש פעולה מצד בעל האתר.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">FAQ</p>
            <h2>תשובות קצרות וברורות לשאלות שחוזרות שוב ושוב.</h2>
        </div>

        @include('partials.faq-items')
    </section>

    <section class="cta-banner">
        <div>
            <p class="eyebrow">מוכנים להתחיל?</p>
            <h2>פתח חשבון חינמי והטמע את הווידג׳ט תוך דקות.</h2>
        </div>
        <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון</a>
    </section>
@endsection
