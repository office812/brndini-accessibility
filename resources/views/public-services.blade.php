@extends('layouts.app')

@php($title = 'שירותי Brndini | אחסון, SEO, קמפיינים, תחזוקה ואוטומציות')
@php($metaDescription = 'שירותי Brndini לעסקים: אחסון, SEO, קמפיינים, תחזוקת אתר, שדרוג אתר קיים, דפי נחיתה ואוטומציות. הווידג׳ט נשאר חינמי, והשירותים זמינים כשצריך צמיחה ותפעול חכם.')

@section('content')
    <section class="about-hero pricing-page-hero">
        <div class="about-hero-copy">
            <p class="eyebrow">שירותי Brndini</p>
            <h1>הווידג׳ט נשאר חינמי. כשרוצים צמיחה, Brndini נכנסת לתמונה.</h1>
            <p class="hero-text hero-text-lead">
                A11Y Bridge היא דלת הכניסה. מעבר לכלי החינמי, Brndini מציעה שכבת שירותים עסקיים
                לעסקים שרוצים אתר טוב יותר, תנועה טובה יותר ותפעול שקט יותר: אחסון, SEO, קמפיינים,
                תחזוקה, שדרוג אתר קיים, דפי נחיתה ואוטומציות.
            </p>
            <div class="hero-action-row">
                <a class="primary-button button-link" href="{{ auth()->check() ? route('dashboard.services') : route('register.show') }}">
                    {{ auth()->check() ? 'לפתיחת פנייה לשירות' : 'פתיחת חשבון חינמי' }}
                </a>
                <a class="ghost-button button-link" href="{{ route('home') }}#pricing">להכיר את הכלי החינמי</a>
            </div>
        </div>

        <div class="about-hero-panel pricing-page-panel">
            <div class="about-hero-grid">
                <article class="about-mini-card">
                    <span class="eyebrow">אחסון</span>
                    <strong>יציבות ותשתית</strong>
                    <p>שרתים, גיבויים, זמינות ושקט תפעולי למי שלא רוצה להתעסק בתשתיות לבד.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">צמיחה</span>
                    <strong>SEO, קמפיינים ודפי נחיתה</strong>
                    <p>תנועה אורגנית וממומנת עם מדידה נכונה, תהליך שיווקי ועמודים ממירים.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">שיפור אתר</span>
                    <strong>תחזוקה ושדרוג</strong>
                    <p>אתר קיים שלא ממצה את עצמו יכול להפוך למהיר, חד ומדויק יותר בלי להתחיל מחדש.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">אוטומציות</span>
                    <strong>תהליכים חכמים</strong>
                    <p>חיבור לידים, CRM, טפסים ותהליכי follow-up שחוסכים זמן ומשפרים תוצאות.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">שירותים עסקיים</p>
            <h2>שכבת שירותים שנכנסת לפעולה כשצריך יותר מאשר כלי חינמי.</h2>
            <p class="hero-text">
                השירותים של Brndini לא קשורים לתמיכה הטכנית של הווידג׳ט. הם מיועדים לעסקים שרוצים
                שיפור אמיתי בתשתית, בשיווק, באתר או בתהליכים.
            </p>
        </div>

        @include('partials.brndini-services-cards')
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">איך זה מתחבר</p>
            <h2>קודם מתקינים את הכלי החינמי, ואז בוחרים אם צריך גם שירות עסקי.</h2>
        </div>

        <div class="about-process-grid">
            <article class="process-card-strong">
                <span class="process-index">01</span>
                <h3>פותחים חשבון ומטמיעים</h3>
                <p>הווידג׳ט החינמי נותן שכבת נגישות בסיסית, דשבורד, סטטוס התקנה והצהרה בסיסית.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">02</span>
                <h3>מזהים מה העסק צריך</h3>
                <p>אחסון, SEO, קמפיינים, תחזוקה, שדרוג אתר, דפי נחיתה או אוטומציות.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">03</span>
                <h3>משאירים פנייה בתוך Brndini</h3>
                <p>בלי לחפש ספק אחר ובלי לעבור מערכת. הכול נשמר בתוך סביבת העבודה שלך.</p>
            </article>
        </div>
    </section>
@endsection
