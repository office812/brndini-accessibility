@extends('layouts.app')

@php($title = 'מדיניות פרטיות | A11Y Bridge')
@php($metaDescription = 'מדיניות הפרטיות של A11Y Bridge: אילו נתונים נאספים, למה הם נשמרים, ואיך הם משמשים להפעלת הפלטפורמה, תמיכה טכנית ושירותי Brndini.')

@section('content')
    <section class="about-hero pricing-page-hero">
        <div class="about-hero-copy">
            <p class="eyebrow">מדיניות פרטיות</p>
            <h1>שומרים רק את מה שצריך כדי להפעיל את הפלטפורמה, לספק תמיכה טכנית ולנהל פניות.</h1>
            <p class="hero-text hero-text-lead">
                A11Y Bridge אוספת ושומרת מידע טכני ועסקי בסיסי לצורך יצירת חשבון, ניהול אתרים,
                זיהוי הטמעה, תמיכה טכנית ופניות לשירותי Brndini.
            </p>
        </div>
    </section>

    <section class="section-band">
        <div class="capability-grid">
            <article class="capability-card">
                <h3>איזה מידע נאסף</h3>
                <p>שם, אימייל, דומיין, הגדרות וידג׳ט, סטטוסי התקנה, פניות תמיכה טכנית ופניות לשירותים עסקיים של Brndini.</p>
            </article>
            <article class="capability-card">
                <h3>למה המידע נשמר</h3>
                <p>כדי לאפשר פתיחת חשבון, ניהול אתר, הפעלת הווידג׳ט, יצירת הצהרה בסיסית, זיהוי התקנה, תמיכה טכנית ושיפור חוויית השימוש.</p>
            </article>
            <article class="capability-card">
                <h3>פניות לשירותים עסקיים</h3>
                <p>אם משאירים פנייה לאחסון, SEO, קמפיינים, תחזוקה או שירות אחר של Brndini, המידע נשמר בנפרד כתשתית לידים ושירות.</p>
            </article>
            <article class="capability-card">
                <h3>קודי מעקב ואנליטיקס</h3>
                <p>ייתכן שהאתר והפלטפורמה ישתמשו בקודי מעקב ואנליטיקס לצורך מדידה, אבחון ושיפור חוויית המשתמש, בהתאם להגדרות המערכת.</p>
            </article>
            <article class="capability-card">
                <h3>שמירת הסכמות</h3>
                <p>בפתיחת חשבון נשמרת הוכחת הסכמה לתנאי השימוש, למדיניות הפרטיות ולהבנה שמדובר בכלי self-service ולא בשירות נגישות.</p>
            </article>
            <article class="capability-card">
                <h3>יצירת קשר</h3>
                <p>לשאלות על פרטיות, תמיכה טכנית במערכת או בקשות הקשורות לחשבון, אפשר ליצור קשר דרך המערכת או דרך ערוצי Brndini הרשמיים.</p>
            </article>
        </div>
    </section>
@endsection
