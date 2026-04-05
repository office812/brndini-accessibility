@extends('layouts.app')

@php($title = 'מה כלול בחינם | A11Y Bridge')
@php($metaDescription = 'מה מקבלים בחינם ב-A11Y Bridge: ווידג׳ט מנוהל, קוד הטמעה קבוע, דשבורד, הצהרה בסיסית, זיהוי התקנה ותמיכה טכנית בלבד.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="public-stage">
        <div class="public-stage-copy">
            <p class="eyebrow">מה כלול בחינם</p>
            <h1>השכבה החינמית של A11Y Bridge מספיקה כדי להתחיל נכון, בלי תקופת ניסיון ובלי חבילת “פרימיום” בדרך.</h1>
            <p class="hero-text hero-text-lead">
                המטרה היא לתת מוצר אמיתי מהרגע הראשון: ווידג׳ט, הטמעה, דשבורד, הצהרה בסיסית
                וזיהוי התקנה. מה שלא כלול כאן נשאר מחוץ לכלי, ובמקרה הצורך עובר לשירותי Brndini.
            </p>
            <div class="public-cta-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('how-it-works') }}">איך זה עובד</a>
            </div>
        </div>
    </section>

    <section class="public-shell-section">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">כלול בשכבה החינמית</p>
            <h2>ארבעה דברים שנשארים זמינים כבר בהתחלה.</h2>
        </div>

        <div class="public-shell-grid public-shell-grid-four">
            <article class="public-shell-card">
                <small>01</small>
                <h3>ווידג׳ט מנוהל</h3>
                <p>כפתור, שפה, טקסטים, מיקום ועיצוב בסיסי מתוך המערכת.</p>
            </article>
            <article class="public-shell-card">
                <small>02</small>
                <h3>קוד הטמעה קבוע</h3>
                <p>מטמיעים פעם אחת וממשיכים לנהל הכל מרחוק בלי להחליף קוד הטמעה.</p>
            </article>
            <article class="public-shell-card">
                <small>03</small>
                <h3>דשבורד והצהרה</h3>
                <p>ניהול אתר, הצהרה בסיסית, קישור ציבורי וחיווי מצב התקנה.</p>
            </article>
            <article class="public-shell-card">
                <small>04</small>
                <h3>בקרה טכנית</h3>
                <p>בדיקה בסיסית והתראות, בלי להפוך את זה למערכת כבדה מדי.</p>
            </article>
        </div>
    </section>

    <section class="public-shell-section public-shell-section-soft">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מה לא כלול</p>
            <h2>כדי להשאיר את המוצר נקי, יש דברים שנשארים מחוץ לשכבה הזו.</h2>
        </div>

        <div class="public-dual-panel">
            <article class="public-decision-card">
                <p class="eyebrow">כן כלול</p>
                <ul class="compact-check-list">
                    <li>פתיחת חשבון חופשית</li>
                    <li>שכבת ווידג׳ט ודשבורד</li>
                    <li>הצהרה בסיסית</li>
                    <li>תמיכה טכנית בפלטפורמה</li>
                </ul>
            </article>
            <article class="public-decision-card public-decision-card-accent">
                <p class="eyebrow">לא כלול</p>
                <ul class="compact-check-list">
                    <li>שירות נגישות מקצועי</li>
                    <li>ייעוץ או ליווי משפטי</li>
                    <li>אחסון, SEO, קמפיינים או תחזוקת אתר</li>
                    <li>שדרוג אתר ואוטומציות</li>
                </ul>
            </article>
        </div>

        <div class="public-cta-row public-cta-row-center">
            <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">שירותי Brndini</a>
        </div>
    </section>
@endsection
