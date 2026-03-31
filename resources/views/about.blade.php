@extends('layouts.app')

@php($title = 'אודות A11Y Bridge | פלטפורמת נגישות לאתרים')
@php($metaDescription = 'A11Y Bridge בונה שכבת נגישות מנוהלת לאתרים: וידג׳ט, הטמעה קבועה, דשבורד ניהול, תוכן, ציות ותמיכה לצוותים, סוכנויות וארגונים.')

@section('content')
    <section class="about-hero">
        <div class="about-hero-copy">
            <p class="eyebrow">אודות A11Y Bridge</p>
            <h1>אנחנו בונים שכבת נגישות מנוהלת שנראית ומתנהגת כמו מוצר הייטק אמיתי.</h1>
            <p class="hero-text hero-text-lead">
                A11Y Bridge נולדה מתוך צורך פשוט: לא רק להוסיף כפתור לאתר, אלא לתת לעסקים,
                לסוכנויות ולצוותים מערכת שלמה שמחברת וידג׳ט, הטמעה, ציות, תוכן ותמיכה תחת מותג אחד.
            </p>
            <div class="hero-action-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון</a>
                <a class="ghost-button button-link" href="{{ route('pricing') }}">לצפייה בחבילות</a>
            </div>
        </div>

        <div class="about-hero-panel">
            <div class="about-hero-grid">
                <article class="about-mini-card">
                    <span class="eyebrow">מוצר</span>
                    <strong>וידג׳ט מנוהל</strong>
                    <p>הטמעה אחת, שליטה מלאה מהפלטפורמה.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">ציות</span>
                    <strong>מסגור רציני</strong>
                    <p>הצהרה, בדיקות, חיווי התקנה ומסלול שירות.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">צמיחה</span>
                    <strong>תוכן ו־SEO</strong>
                    <p>מאמרים, עמודי מוצר ושפה שמסבירה את הערך העסקי.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">תפעול</span>
                    <strong>תמיכה מרכזית</strong>
                    <p>ניהול לקוחות, אתרים, רישיונות ופניות מתוך מקום אחד.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">למה אנחנו קיימים</p>
            <h2>כי נגישות לא צריכה להיראות כמו תוסף צדדי, אלא כמו שכבת מוצר שלמה.</h2>
        </div>

        <div class="about-story-grid">
            <article class="story-card">
                <h3>פחות כאוס, יותר שליטה</h3>
                <p>במקום לערבב בין כמה מסכים, כמה כלים וכמה מסרים, בנינו מערכת אחת שמרכזת את כל מה שהלקוח צריך כדי להטמיע, לנהל ולעדכן.</p>
            </article>
            <article class="story-card">
                <h3>מותאם לעסקים, סוכנויות וארגונים</h3>
                <p>המערכת תוכננה לעבוד גם לאתר בודד וגם למי שמנהל כמה רישיונות, כמה לקוחות וכמה חבילות שירות במקביל.</p>
            </article>
            <article class="story-card">
                <h3>נראות של חברה גדולה</h3>
                <p>אנחנו מאמינים שמוצר נגישות צריך להיראות כמו SaaS חזק: מסכים ברורים, תמיכה, חבילות, אזור תוכן ושפה מוצרית עקבית.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">איך אנחנו עובדים</p>
            <h2>תשתית אחת שמחברת בין אתר, וידג׳ט, לקוח וצוות.</h2>
        </div>

        <div class="about-process-grid">
            <article class="process-card-strong">
                <span class="process-index">01</span>
                <h3>יוצרים סביבת עבודה לאתר</h3>
                <p>כל אתר מקבל מפתח אתר, רישיון, חבילה, סטטוס התקנה ונקודת ניהול משלו.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">02</span>
                <h3>מטמיעים פעם אחת</h3>
                <p>קוד אחד באתר, והמערכת מושכת אוטומטית את כל ההגדרות, הפיצ׳רים והשינויים הבאים.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">03</span>
                <h3>ממשיכים לנהל ולשפר</h3>
                <p>תמיכה, מאמרים, ציות, רישיונות וחוויית משתמש מנוהלים מתוך אותה פלטפורמה.</p>
            </article>
        </div>
    </section>

    <section class="cta-banner cta-banner-wide">
        <div>
            <p class="eyebrow">בואו נבנה שכבת נגישות שנראית כמו מוצר אמיתי</p>
            <h2>אם אתם רוצים שהאתר שלכם ייראה מנוהל, אמין ומוכן לצמיחה, זה המקום להתחיל ממנו.</h2>
        </div>
        <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון</a>
    </section>
@endsection
