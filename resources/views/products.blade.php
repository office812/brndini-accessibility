@extends('layouts.app')

@php($title = 'Brndini | מוצרים, שירותים ושכבות המשך לעסק')
@php($metaDescription = 'Brndini היא שכבת ההמשך של A11Y Bridge: שירותים עסקיים, מוצרים חינמיים נוספים, וגישה מוקדמת לכלים שנבנים סביב צמיחה, אתרים, תוכן ואוטומציות.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="public-stage public-stage-home public-stage-brndini">
        <div class="public-stage-copy">
            <p class="eyebrow">Brndini</p>
            <h1>Brndini היא שכבת ההמשך: מוצרים חינמיים, שירותים עסקיים וכלים שנבנים סביב העסק.</h1>
            <p class="hero-text hero-text-lead">
                A11Y Bridge נשארת המוצר focused סביב נגישות. Brndini מרכזת את מה שממשיך אחרי זה:
                שירותים עסקיים, כלים נוספים, אוטומציות, צמיחה, תוכן ותשתית.
            </p>
            <div class="public-cta-row public-cta-row-hero">
                <a class="primary-button button-link" href="{{ route('brndini.services', $marketingParams) }}">לשירותי Brndini</a>
                <a class="ghost-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
            </div>
        </div>

        <div class="public-stage-visual" aria-hidden="true">
            <div class="public-device-shell public-device-shell-poster">
                <div class="public-stage-canvas public-stage-canvas-brndini">
                    <div class="public-stage-canvas-copy">
                        <small>hub / services / tools</small>
                        <strong>מותג אחד שמחבר בין free tools, lead capture, services ו־future products.</strong>
                    </div>
                    <div class="public-stage-canvas-metrics">
                        <div><small>free tools</small><strong>2+</strong></div>
                        <div><small>services</small><strong>7</strong></div>
                        <div><small>entry points</small><strong>CRM-ready</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="public-shell-section">
        <div class="section-heading">
            <p class="eyebrow">מוצרי כניסה</p>
            <h2>Brndini לא מתחילה משירות. היא מתחילה ממוצרים שמכניסים ערך ותנועה.</h2>
        </div>

        <div class="public-proof-rail public-proof-rail-tools">
            <article>
                <small>כבר פעיל</small>
                <strong>A11Y Bridge</strong>
                <p>widget נגישות, install detection, dashboard ו־statement בסיסי.</p>
            </article>
            <article>
                <small>הבא בתור</small>
                <strong>Digital Card</strong>
                <p>כרטיס ביקור דיגיטלי מהיר לאיסוף פרטים, קישורים וזהות עסקית ברורה.</p>
            </article>
            <article>
                <small>כניסת תוכן</small>
                <strong>Content Hub</strong>
                <p>חיבור אתר, 2 כתבות חינמיות בחודש, ותשתית להמשך מסלול תוכן גדול יותר.</p>
            </article>
        </div>
    </section>

    <section class="public-shell-section public-shell-section-soft">
        <div class="section-heading">
            <p class="eyebrow">שירותים עסקיים</p>
            <h2>כשצריך שכבת המשך רחבה יותר, Brndini נכנסת דרך שירותים ולא דרך “שדרוג לתוסף”.</h2>
        </div>

        <div class="public-dual-panel public-dual-panel-quiet">
            <article class="public-decision-card">
                <p class="eyebrow">צמיחה</p>
                <h3>SEO, קמפיינים, דפי נחיתה ותוכן.</h3>
                <ul class="compact-check-list">
                    <li>יותר תנועה אורגנית</li>
                    <li>יותר לידים</li>
                    <li>דפי נחיתה ותוכן ממיר</li>
                </ul>
            </article>

            <article class="public-decision-card public-decision-card-accent">
                <p class="eyebrow">תשתית</p>
                <h3>אחסון, תחזוקה, שדרוג אתר ואוטומציות.</h3>
                <ul class="compact-check-list">
                    <li>מעבר תשתיות</li>
                    <li>תחזוקה שוטפת</li>
                    <li>שיפור אתר ותהליכים חכמים</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="public-shell-section public-shell-section-last">
        <div class="section-heading">
            <p class="eyebrow">גישה מוקדמת</p>
            <h2>מי שרוצה להיות קרוב לכלי הבא, יכול להיכנס עכשיו לרשימת העניין.</h2>
        </div>

        <div class="public-bridge-layout">
            <div>
                <h3>זה לא hub עמוס. זו שכבת המשך מסודרת.</h3>
                <p>השלב הראשון הוא A11Y Bridge. Brndini מחברת אחרי זה בין שירותים, מוצרים וכלים שנכונים לעסק כשהזמן מגיע.</p>
            </div>
            <div class="public-cta-row public-cta-row-left">
                <a class="primary-button button-link" href="{{ route('brndini.services', array_merge($marketingParams, ['service' => 'ecosystem_access', 'entry' => 'products-page'])) }}#public-service-form">אני רוצה גישה מוקדמת</a>
                <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">לדבר עם Brndini</a>
            </div>
        </div>

        @include('partials.brndini-future-products')
    </section>
@endsection
