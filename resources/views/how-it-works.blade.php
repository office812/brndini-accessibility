@extends('layouts.app')

@php($title = 'איך זה עובד | A11Y Bridge')
@php($metaDescription = 'פתיחת חשבון, יצירת אתר, קוד הטמעה קבוע, דשבורד, הצהרה בסיסית ותמיכה טכנית. כך A11Y Bridge עובדת בפועל.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="public-stage">
        <div class="public-stage-copy">
            <p class="eyebrow">איך זה עובד</p>
            <h1>פותחים חשבון, מטמיעים פעם אחת, וממשיכים לנהל הכול ממקום אחד.</h1>
            <p class="hero-text hero-text-lead">
                A11Y Bridge בנויה לזרימה קצרה וברורה: חשבון, אתר, snippet קבוע, דשבורד, הצהרה בסיסית
                וזיהוי התקנה. התמיכה במערכת נשארת טכנית בלבד, ושירותי Brndini נשארים נפרדים.
            </p>
            <div class="public-cta-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('free-tool') }}">מה כלול בחינם</a>
            </div>
        </div>
    </section>

    <section class="public-shell-section" data-reveal>
        <div class="section-heading section-heading-center">
            <p class="eyebrow">הזרימה</p>
            <h2>ארבעה שלבים. בלי מעקפים.</h2>
        </div>

        <div class="public-flow-grid">
            <article class="public-flow-step">
                <span>01</span>
                <h3>חשבון ואתר</h3>
                <p>פותחים משתמש, מוסיפים אתר ומקבלים workspace מסודר להתחלה.</p>
            </article>
            <article class="public-flow-step">
                <span>02</span>
                <h3>snippet קבוע</h3>
                <p>מטמיעים פעם אחת באתר. כל שינוי בהמשך נשמר מתוך הדשבורד.</p>
            </article>
            <article class="public-flow-step">
                <span>03</span>
                <h3>widget והצהרה</h3>
                <p>מגדירים את השכבה הבסיסית ואת עמוד ההצהרה בלי לפצל את העבודה.</p>
            </article>
            <article class="public-flow-step">
                <span>04</span>
                <h3>ניהול רציף</h3>
                <p>רואים זיהוי התקנה, בדיקה בסיסית, סטטוס אתר ופניות טכניות מתוך אותו מקום.</p>
            </article>
        </div>
    </section>

    <section class="public-shell-section public-shell-section-soft" data-reveal>
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מה רואים בפועל</p>
            <h2>המערכת לא נעצרת ב־widget. היא נותנת שכבת עבודה ברורה מסביבו.</h2>
        </div>

        <div class="public-shell-grid public-shell-grid-four">
            <article class="public-shell-card">
                <small>דשבורד</small>
                <h3>ניהול אתר</h3>
                <p>שם אתר, דומיין, מפתח ציבורי וסטטוס מסודר במקום אחד.</p>
            </article>
            <article class="public-shell-card">
                <small>הטמעה</small>
                <h3>זיהוי התקנה</h3>
                <p>רואים אם הקוד זוהה, ואם לא זוהה לאחרונה.</p>
            </article>
            <article class="public-shell-card">
                <small>ציות</small>
                <h3>הצהרה בסיסית</h3>
                <p>קישור ציבורי ברור, בלי להציג את זה כשירות מקצועי נפרד.</p>
            </article>
            <article class="public-shell-card">
                <small>תמיכה</small>
                <h3>טכנית בלבד</h3>
                <p>עזרה בשימוש במערכת ובהטמעה, לא שירות נגישות ולא ייעוץ.</p>
            </article>
        </div>

        <div class="public-cta-row public-cta-row-center">
            <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">שירותי Brndini</a>
        </div>
    </section>
@endsection
