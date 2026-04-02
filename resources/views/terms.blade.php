@extends('layouts.app')

@php($title = 'תנאי שימוש | A11Y Bridge')
@php($metaDescription = 'תנאי השימוש של A11Y Bridge: כלי self-service להטמעת וידג׳ט נגישות, תמיכה טכנית במערכת בלבד, והבהרה ברורה של אחריות המשתמש על האתר, התוכן והקוד.')

@section('content')
    <section class="about-hero pricing-page-hero">
        <div class="about-hero-copy">
            <p class="eyebrow">תנאי שימוש</p>
            <h1>הפלטפורמה ניתנת ככלי self-service, עם תמיכה טכנית במערכת בלבד.</h1>
            <p class="hero-text hero-text-lead">
                A11Y Bridge היא מערכת חינמית להטמעת וידג׳ט נגישות, ניהול בסיסי, הצהרה בסיסית ובקרה טכנית.
                השימוש במערכת כפוף לתנאים הבאים, שמבהירים מה השירות עושה, מה אינו עושה, ומה האחריות שנשארת אצל בעל האתר.
            </p>
        </div>
    </section>

    <section class="section-band">
        <div class="capability-grid">
            <article class="capability-card">
                <h3>השירות ניתן כפי שהוא</h3>
                <p>המערכת ניתנת לשימוש כפי שהיא, ללא התחייבות לזמינות רציפה, התאמה לכל מקרה, או תוצאה עסקית, טכנית או משפטית מסוימת.</p>
            </article>
            <article class="capability-card">
                <h3>אין כאן שירות נגישות</h3>
                <p>השימוש במערכת אינו מהווה ייעוץ נגישות, שירות נגישות מנוהל, בדיקה משפטית או התחייבות לעמידה מלאה ב־WCAG, ADA או כל דרישה רגולטורית אחרת.</p>
            </article>
            <article class="capability-card">
                <h3>האחריות על האתר נשארת אצלך</h3>
                <p>בעל האתר אחראי לתוכן, לקוד, למדיה, לעדכונים, לבדיקה בפועל של האתר ולכל הצהרה או שימוש שהוטמעו אצלו באמצעות המערכת.</p>
            </article>
            <article class="capability-card">
                <h3>תמיכה טכנית בלבד</h3>
                <p>Brndini מספקת תמיכה טכנית הקשורה למערכת עצמה בלבד, ואינה מספקת דרך A11Y Bridge שירותי תיקון אתר, ליווי ציות או שירות משפטי.</p>
            </article>
            <article class="capability-card">
                <h3>הגבלת אחריות</h3>
                <p>בשימוש המותר בדין, Brndini לא תישא באחריות לנזקים עקיפים, תוצאתיים, מסחריים או משפטיים, ואינה מתחייבת שהמערכת לבדה תמנע טענות או דרישות מצד שלישי.</p>
            </article>
            <article class="capability-card">
                <h3>שינויים, השעיה והפסקה</h3>
                <p>Brndini רשאית לעדכן, לשנות, להשעות או להפסיק חלקים מהמערכת, את תנאי השימוש או את זמינותה, לפי שיקול דעתה.</p>
            </article>
        </div>
    </section>
@endsection
