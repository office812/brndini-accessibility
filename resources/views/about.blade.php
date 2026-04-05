@extends('layouts.app')

@php($title = 'אודות A11Y Bridge | כלי חינמי להטמעת וידג׳ט נגישות')
@php($metaDescription = 'A11Y Bridge היא שכבה חינמית, שקטה וברורה להטמעת וידג׳ט נגישות. Brndini נשארת שכבת שירותים עסקיים נפרדת ואופציונלית.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="public-stage">
        <div class="public-stage-copy">
            <p class="eyebrow">אודות A11Y Bridge</p>
            <h1>בנינו את A11Y Bridge כמו מוצר. לא כמו כפתור שמתחבא בתחתית האתר.</h1>
            <p class="hero-text hero-text-lead">
                הרעיון פשוט: לתת לבעלי אתרים, לסוכנויות ולצוותים שכבה חינמית שנראית רצינית,
                עובדת מהר, ונשארת שקופה במסרים שלה. לא שירות נגישות, לא ייעוץ, ולא עוד מסך צדדי
                שמרגיש זמני.
            </p>
            <div class="public-cta-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('how-it-works') }}">איך זה עובד</a>
            </div>
        </div>

        <div class="public-stage-visual" aria-hidden="true">
            <div class="public-device-shell public-device-shell-quiet">
                <div class="public-device-card">
                    <small>הגישה</small>
                    <strong>מוצר חינמי עם גבולות ברורים.</strong>
                    <p>הכלי נשאר self-service. Brndini נשארת שכבה עסקית נפרדת כשבאמת צריך אותה.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="public-shell-section">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">למה בנינו את זה</p>
            <h2>כי שכבת נגישות לא צריכה להרגיש כמו טלאי.</h2>
        </div>

        <div class="public-shell-grid public-shell-grid-three">
            <article class="public-shell-card">
                <small>01</small>
                <h3>פחות חיכוך</h3>
                <p>חשבון, אתר, snippet ודשבורד אחד במקום כמה נקודות מפוזרות.</p>
            </article>
            <article class="public-shell-card">
                <small>02</small>
                <h3>יותר בהירות</h3>
                <p>המערכת מסבירה מה היא כן עושה, ומה נשאר מחוץ לשכבה החינמית.</p>
            </article>
            <article class="public-shell-card">
                <small>03</small>
                <h3>בסיס לצמיחה</h3>
                <p>מי שצריך יותר עובר ל־Brndini, אבל רק כשיש צורך עסקי אמיתי.</p>
            </article>
        </div>
    </section>

    <section class="public-shell-section public-shell-section-soft">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">עקרונות עבודה</p>
            <h2>אותה שפה בכל נקודת מגע.</h2>
        </div>

        <div class="public-dual-panel">
            <article class="public-decision-card">
                <p class="eyebrow">A11Y Bridge</p>
                <h3>כלי חינמי, שקט ומדויק להטמעה וניהול.</h3>
                <ul class="compact-check-list">
                    <li>פתיחה מהירה</li>
                    <li>דשבורד ברור</li>
                    <li>הצהרה בסיסית</li>
                    <li>תמיכה טכנית בלבד</li>
                </ul>
            </article>
            <article class="public-decision-card">
                <p class="eyebrow">Brndini</p>
                <h3>שירותים עסקיים נפרדים למי שצריך שכבה רחבה יותר.</h3>
                <ul class="compact-check-list">
                    <li>אחסון ותחזוקה</li>
                    <li>SEO וקמפיינים</li>
                    <li>שדרוג אתר ודפי נחיתה</li>
                    <li>אוטומציות ומוצרים נוספים</li>
                </ul>
            </article>
        </div>

        <div class="public-cta-row public-cta-row-center">
            <a class="ghost-button button-link" href="{{ route('free-tool') }}">מה כלול בחינם</a>
            <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">שירותי Brndini</a>
        </div>
    </section>
@endsection
