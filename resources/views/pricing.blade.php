@extends('layouts.app')

@php($title = 'מחירון A11Y Bridge | כלי חינמי ושירותים אופציונליים של Brndini')
@php($metaDescription = 'מחירון A11Y Bridge: כלי חינמי להטמעת וידג׳ט נגישות, עם שירותים עסקיים אופציונליים של Brndini כמו אחסון, SEO, קמפיינים ותחזוקה.')

@section('content')
    <section class="about-hero pricing-page-hero">
        <div class="about-hero-copy">
            <p class="eyebrow">מחירון A11Y Bridge</p>
            <h1>מודל פשוט: הכלי חינמי, והשירותים של Brndini נשארים אופציונליים.</h1>
            <p class="hero-text hero-text-lead">
                רצינו שההצעה תהיה ברורה מהשנייה הראשונה: הווידג׳ט, הדשבורד, ההצהרה הבסיסית
                והבקרה הטכנית נשארים חינמיים. אם בהמשך צריך אחסון, SEO, קמפיינים, תחזוקה
                או שדרוג אתר, Brndini נכנסת כשירות עסקי נפרד.
            </p>
            <div class="hero-action-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון</a>
                <a class="ghost-button button-link" href="{{ route('brndini.services') }}">לשירותי Brndini</a>
            </div>
        </div>

        <div class="about-hero-panel pricing-page-panel">
            <div class="about-hero-grid">
                <article class="about-mini-card">
                    <span class="eyebrow">חינם</span>
                    <strong>המוצר נפתח מהר</strong>
                    <p>ווידג׳ט, הטמעה, הצהרה בסיסית ובקרה טכנית בלי תשלום כניסה.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">אופציונלי</span>
                    <strong>שירותים עסקיים</strong>
                    <p>Brndini נכנסת רק כשצריך צמיחה, קמפיינים, SEO, תחזוקה או תשתית.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">מודל פשוט</span>
                    <strong>בלי בלבול</strong>
                    <p>הכלי החינמי נשאר self-service, והשירותים נשארים שכבה נפרדת וברורה.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">צמיחה</span>
                    <strong>דלת כניסה</strong>
                    <p>הכלי מביא תנועה ולידים, וברנדיני פוגשת אותם כשיש צורך עסקי אמיתי.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section-band section-band-alt pricing-page-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">איך זה בנוי</p>
            <h2>מוצר חינמי אחד, ושכבת שירותים עסקיים נפרדת כשצריך.</h2>
        </div>

        @include('partials.pricing-cards')
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">למי זה מתאים</p>
            <h2>מודל שמתאים גם למי שרוצה רק כלי חינמי, וגם למי שצריך עזרה עסקית מעבר לזה.</h2>
        </div>

        <div class="capability-grid">
            <article class="capability-card">
                <h3>בעלי אתרים שרוצים להתחיל מהר</h3>
                <p>הכלי החינמי מאפשר להטמיע קוד, לפתוח דשבורד, להפיק הצהרה בסיסית ולנהל הכול לבד.</p>
            </article>
            <article class="capability-card">
                <h3>סוכנויות וצוותים</h3>
                <p>אותו כלי חינמי מתאים גם לניהול כמה אתרים, הטמעה מרחוק, ובקרה תפעולית ממקום אחד.</p>
            </article>
            <article class="capability-card">
                <h3>עסקים שצריכים צמיחה אמיתית</h3>
                <p>כשצריך אחסון, SEO, קמפיינים, תחזוקה או שדרוג אתר, אפשר לעבור לשירותים של Brndini בלי להחליף מערכת.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מעבר לכלי החינמי</p>
            <h2>הווידג׳ט הוא דלת הכניסה. שירותי Brndini הם השכבה העסקית שמעליו.</h2>
            <p class="hero-text">
                אם אתה רוצה יותר תנועה, יותר לידים, תשתית חזקה יותר או אתר שנראה ומתפקד טוב יותר,
                Brndini מציעה שירותים עסקיים נפרדים מהכלי החינמי.
            </p>
        </div>

        @include('partials.brndini-services-cards')
    </section>
@endsection
