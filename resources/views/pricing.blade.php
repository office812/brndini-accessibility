@extends('layouts.app')

@php($title = 'המודל של A11Y Bridge | כלי חינמי ושירותי Brndini')
@php($metaDescription = 'המודל של A11Y Bridge פשוט: הכלי החינמי נשאר self-service, ושירותי Brndini נשארים שכבה עסקית נפרדת ואופציונלית.')

@section('content')
    <section class="public-stage">
        <div class="public-stage-copy">
            <p class="eyebrow">המודל</p>
            <h1>הכלי נשאר חינמי. Brndini נשארת שכבת שירותים עסקיים נפרדת.</h1>
            <p class="hero-text hero-text-lead">
                אין כאן "חינם מול פרימיום". יש שכבת מוצר חינמית, ברורה ושימושית, ולצידה
                שכבת שירותים אופציונלית לעסקים שרוצים תשתית, צמיחה או שדרוג רחב יותר.
            </p>
            <div class="public-cta-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('brndini.services') }}">שירותי Brndini</a>
            </div>
        </div>
    </section>

    <section class="public-shell-section" data-reveal>
        <div class="section-heading section-heading-center">
            <p class="eyebrow">שתי שכבות. לא יותר.</p>
            <h2>מוצר חינמי להתחלה, ושירותים עסקיים למי שבאמת צריך אותם.</h2>
        </div>

        @include('partials.pricing-cards')
    </section>

    {{-- ─── What's free: feature breakdown ──────────────────────────────── --}}
    <section class="public-shell-section pricing-features-section" data-reveal>
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מה בדיוק כלול בחינם</p>
            <h2>כמעט כל מה שצריך, בלי לשאול כמה עולה.</h2>
            <p class="section-heading-body">הכלי החינמי לא "מוגבל על ידי מכירות". הוא עומד לעצמו.</p>
        </div>

        <div class="pricing-features-grid">
            <article class="pricing-feature-card">
                <div class="pricing-feature-icon" aria-hidden="true">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M8 12l3 3 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <strong>ווידג׳ט נגישות מלא</strong>
                <p>כפתור נגישות עם פאנל מובנה, התאמות טקסט, ניגודיות, קישורים, סמן גדול ועוד. מותקן בשורה אחת של קוד.</p>
            </article>

            <article class="pricing-feature-card">
                <div class="pricing-feature-icon" aria-hidden="true">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </div>
                <strong>קוד הטמעה קבוע</strong>
                <p>ה-snippet לא משתנה לעולם. מטמיעים פעם אחת, ומנהלים הכול מהפלטפורמה בלי לגעת שוב בקוד האתר.</p>
            </article>

            <article class="pricing-feature-card">
                <div class="pricing-feature-icon" aria-hidden="true">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" stroke="currentColor" stroke-width="1.8"/><polyline points="14 2 14 8 20 8" stroke="currentColor" stroke-width="1.8"/><path d="M9 15h6M9 11h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </div>
                <strong>הצהרת נגישות מובנית</strong>
                <p>יוצר מונחה ליצירת הצהרת נגישות ציבורית, עם קישור ייעודי שמתחבר לווידג׳ט אוטומטית.</p>
            </article>

            <article class="pricing-feature-card">
                <div class="pricing-feature-icon" aria-hidden="true">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M12 8v4l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </div>
                <strong>בדיקות ומעקב</strong>
                <p>ביצוע בדיקות נגישות, מעקב אחר ציון, התראות על שינויים וניתוח מצב האתר לאורך זמן.</p>
            </article>

            <article class="pricing-feature-card">
                <div class="pricing-feature-icon" aria-hidden="true">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                </div>
                <strong>ניהול מרחוק</strong>
                <p>שולטים על עיצוב הווידג׳ט, הגדרות הנגישות, מיקום הכפתור ומצב הרישיון ישירות מהדשבורד.</p>
            </article>

            <article class="pricing-feature-card">
                <div class="pricing-feature-icon" aria-hidden="true">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="1.8"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </div>
                <strong>מספר אתרים</strong>
                <p>כל אתר מקבל רישיון עצמאי, public key נפרד וניהול עצמאי. מנהל סוכנות יכול להחזיק מספר לקוחות.</p>
            </article>
        </div>
    </section>

    {{-- ─── No tricks trust strip ─────────────────────────────────────────── --}}
    <section class="pricing-trust-strip" data-reveal>
        <div class="pricing-trust-inner">
            <article class="pricing-trust-item">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/></svg>
                <span>ללא כרטיס אשראי</span>
            </article>
            <article class="pricing-trust-item">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/></svg>
                <span>ללא הגבלת זמן</span>
            </article>
            <article class="pricing-trust-item">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/></svg>
                <span>ללא הגבלת תנועה</span>
            </article>
            <article class="pricing-trust-item">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/></svg>
                <span>בלי מודעות בתוך הווידג׳ט</span>
            </article>
            <article class="pricing-trust-item">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/></svg>
                <span>קוד ההטמעה לא משתנה</span>
            </article>
        </div>
    </section>

    {{-- ─── FAQ ───────────────────────────────────────────────────────────── --}}
    <section class="public-shell-section pricing-faq-section" data-reveal>
        <div class="section-heading section-heading-center">
            <p class="eyebrow">שאלות נפוצות</p>
            <h2>מה בדיוק חינמי? מה שירותי Brndini? ועוד.</h2>
        </div>

        <div class="pricing-faq-grid">
            <details class="pricing-faq-item">
                <summary>האם הכלי באמת חינמי לצמיתות?</summary>
                <p>כן. הווידג׳ט, קוד ההטמעה, ניהול הדשבורד, הצהרת הנגישות, הבדיקות — כל אלה נשארים חינמיים לצמיתות. אין תוכנית פרימיום שמגבילה את הכלי הבסיסי.</p>
            </details>

            <details class="pricing-faq-item">
                <summary>מה ההבדל בין הכלי לשירותי Brndini?</summary>
                <p>הכלי הוא מוצר טכני: ווידג׳ט, הצהרה, בדיקות. שירותי Brndini הם שכבה עסקית לגמרי נפרדת: אחסון, עיצוב, SEO, קמפיינים, תחזוקה. שתי שכבות שלא חייבות להיות קשורות.</p>
            </details>

            <details class="pricing-faq-item">
                <summary>כמה אתרים אפשר לנהל?</summary>
                <p>אין הגבלה. כל אתר מקבל רישיון עצמאי, public key נפרד וניהול עצמאי מהדשבורד. סוכנויות ומנהלי אתרים מרובים ייהנו במיוחד.</p>
            </details>

            <details class="pricing-faq-item">
                <summary>האם הקוד שלי ישתנה אם אעדכן הגדרות?</summary>
                <p>לא. קוד ההטמעה נשאר זהה לצמיתות. כל השינויים מתעדכנים בצד השרת — ממיקום הכפתור ועד לפקדים הפעילים — בלי שתצטרך לגעת שוב בקוד האתר.</p>
            </details>

            <details class="pricing-faq-item">
                <summary>האם ניתן להשתמש בכלי לצרכים עסקיים?</summary>
                <p>כן, לחלוטין. הכלי מיועד גם לסוכנויות, קבלנים ובעלי אתרים עסקיים. אין הגבלה על שימוש מסחרי בכלי החינמי.</p>
            </details>

            <details class="pricing-faq-item">
                <summary>מה קורה אם אני רוצה שירות של Brndini?</summary>
                <p>פותחים פנייה עסקית מסודרת ישירות מהפלטפורמה — בלי לפגוע בהגדרות הכלי. הפנייה מגיעה ל-Brndini כצרכי הפרויקט הספציפי שלך, ואתה מקבל הצעה מותאמת.</p>
            </details>
        </div>
    </section>

    {{-- ─── Final CTA ─────────────────────────────────────────────────────── --}}
    <section class="public-shell-section pricing-cta-section" data-reveal>
        <div class="pricing-cta-inner">
            <p class="eyebrow">מוכן להתחיל?</p>
            <h2>דקה אחת מפרידה אותך מהווידג׳ט הראשון.</h2>
            <p>פותחים חשבון, מוסיפים אתר, מקבלים snippet — ומטמיעים. הכלי מוכן לפעולה.</p>
            <div class="public-cta-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('free-tool') }}">מה כלול בדיוק</a>
            </div>
        </div>
    </section>
@endsection
