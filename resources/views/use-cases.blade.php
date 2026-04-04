@extends('layouts.app')

@php($title = 'תרחישי שימוש ל-A11Y Bridge | בעלי אתרים, סוכנויות וצוותים')
@php($metaDescription = 'תרחישי שימוש אמיתיים ל-A11Y Bridge: איך בעלי אתרים, סוכנויות וצוותים משתמשים בכלי החינמי, מתי הוא מספיק לבד, ומתי ממשיכים לשירותי Brndini.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="about-hero pricing-page-hero">
        <div class="about-hero-copy">
            <p class="eyebrow">תרחישי שימוש</p>
            <h1>הדרך הכי טובה להבין את A11Y Bridge היא לראות באילו מצבים משתמשים בה באמת.</h1>
            <p class="hero-text hero-text-lead">
                לא כל מי שנכנס צריך אותו דבר. יש מי שרוצה רק להטמיע widget חינמי מהר,
                יש מי שמנהל כמה אתרים, ויש מי שרוצה לשמור על flow ברור לצוות או ללקוחות.
                כאן ריכזנו את המצבים הכי נפוצים ואיך המערכת משתלבת בהם בפועל.
            </p>
            <div class="hero-action-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">להתחיל בחינם</a>
                <a class="ghost-button button-link" href="{{ route('how-it-works') }}">לזרימת העבודה המלאה</a>
            </div>
        </div>

        <div class="about-hero-panel pricing-page-panel">
            <div class="about-hero-grid">
                <article class="about-mini-card">
                    <span class="eyebrow">בעל אתר</span>
                    <strong>הטמעה מהירה</strong>
                    <p>לוקחים קוד, מטמיעים פעם אחת, ומנהלים משם הכול בדשבורד.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">סוכנות</span>
                    <strong>כמה לקוחות, אותו flow</strong>
                    <p>סביבת עבודה אחת לכל האתרים, הרישיונות וההטמעות.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">צוות פנימי</span>
                    <strong>שליטה ובקרה</strong>
                    <p>רואים אם ההטמעה זוהתה, אם יש הצהרה, ומה מצב האתר.</p>
                </article>
                <article class="about-mini-card">
                    <span class="eyebrow">Brndini</span>
                    <strong>שכבה עסקית נפרדת</strong>
                    <p>כשצריך מעבר לכלי, ממשיכים לשירותים עסקיים ולא מערבבים מסרים.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">ארבעה מצבים נפוצים</p>
            <h2>כך המערכת משתלבת ביום־יום, לא רק ברמת המסר.</h2>
        </div>

        <div class="role-experience-grid">
            <article class="role-experience-card">
                <span class="status-pill is-neutral">תרחיש 01</span>
                <h3>בעל עסק שרוצה להתחיל עוד היום</h3>
                <p>פותח חשבון, מוסיף אתר, מדביק קוד קבוע ומקבל widget, דשבורד, הצהרה בסיסית ובקרה טכנית.</p>
                <ul class="compact-check-list">
                    <li>אין צורך בפרויקט שירות כדי להתחיל</li>
                    <li>הערך מורגש כבר מהיום הראשון</li>
                    <li>תמיכה טכנית אם משהו לא ברור</li>
                </ul>
            </article>

            <article class="role-experience-card">
                <span class="status-pill is-neutral">תרחיש 02</span>
                <h3>סוכנות שמנהלת כמה לקוחות</h3>
                <p>שומרת על flow אחיד: אותו תהליך פתיחה, אותו קוד הטמעה, אותה שכבת בקרה מול כל לקוח חדש.</p>
                <ul class="compact-check-list">
                    <li>פחות בלגן בין לקוחות</li>
                    <li>פחות תלות בתוספים שונים</li>
                    <li>נראות מקצועית יותר מול הלקוח</li>
                </ul>
            </article>

            <article class="role-experience-card">
                <span class="status-pill is-neutral">תרחיש 03</span>
                <h3>צוות פנימי שצריך לעקוב אחרי ההטמעה</h3>
                <p>לא רק לראות widget באתר, אלא לדעת אם הוא זוהה, מתי נראה לאחרונה והאם ההצהרה מחוברת.</p>
                <ul class="compact-check-list">
                    <li>סטטוס התקנה ברור</li>
                    <li>הצהרה בסיסית נגישה בלחיצה</li>
                    <li>בדיקות והתראות בסיסיות</li>
                </ul>
            </article>

            <article class="role-experience-card">
                <span class="status-pill is-neutral">תרחיש 04</span>
                <h3>לקוח שמתחיל בכלי, ואחר כך צריך יותר</h3>
                <p>המערכת נשארת נקודת הכניסה. אם צריך תשתית, שדרוג אתר, SEO או קמפיינים, עוברים לשירותי Brndini.</p>
                <ul class="compact-check-list">
                    <li>אין בלבול בין הכלי לשירות</li>
                    <li>המעבר נשאר טבעי</li>
                    <li>אותו מותג ממשיך איתך הלאה</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מה קורה בכל תרחיש</p>
            <h2>בכל מצב, ה־flow נשאר דומה: חשבון, אתר, הטמעה, ניהול ובקרה.</h2>
        </div>

        <div class="about-process-grid">
            <article class="process-card-strong">
                <span class="process-index">01</span>
                <h3>פותחים סביבת עבודה</h3>
                <p>כל תרחיש מתחיל ביצירת חשבון ואתר, עם מפתח אתר וקוד הטמעה קבוע.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">02</span>
                <h3>מגדירים חוויית בסיס</h3>
                <p>שפה, מיקום, הצהרה בסיסית, טקסט כפתור והגדרות widget במקום אחד.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">03</span>
                <h3>רואים סטטוס אמיתי</h3>
                <p>האם ההטמעה זוהתה, האם יש התראות, ומה מצב השכבה באתר כרגע.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-plain">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מתי זה מספיק ומתי ממשיכים</p>
            <h2>הכלי החינמי בנוי לתת שכבה מוצרית שלמה. השירותים נשארים אופציונליים ונפרדים.</h2>
        </div>

        <div class="decision-grid">
            <article class="decision-card decision-card-free">
                <span class="status-pill is-neutral">נשארים בכלי</span>
                <strong>כשצריך widget, דשבורד, הצהרה בסיסית, זיהוי התקנה ותמיכה טכנית במערכת.</strong>
                <ul class="compact-check-list">
                    <li>הטמעה וניהול עצמי</li>
                    <li>שימוש חינמי שוטף</li>
                    <li>סביבת עבודה ברורה</li>
                </ul>
            </article>
            <article class="decision-card decision-card-services">
                <span class="status-pill is-good">ממשיכים ל-Brndini</span>
                <strong>כשצריך יותר: אחסון, תחזוקה, SEO, שדרוג אתר, קמפיינים, דפי נחיתה או אוטומציות.</strong>
                <ul class="compact-check-list">
                    <li>צמיחה עסקית מעבר לכלי</li>
                    <li>שירותים נפרדים ולא חלק מהתמיכה הטכנית</li>
                    <li>אותו מותג, שכבת ערך אחרת</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">לאן ממשיכים מכאן</p>
            <h2>אם התרחיש שלך כבר ברור, עכשיו פשוט בוחרים את המסלול הבא.</h2>
        </div>

        <div class="ecosystem-route-grid">
            <article class="ecosystem-route-card">
                <span class="eyebrow">התחלה עצמאית</span>
                <h3>מתאימים לאחד מהתרחישים כאן?</h3>
                <p>פתח חשבון ותתחיל מהכלי החינמי כדי לראות איך המערכת עובדת בפועל.</p>
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון</a>
            </article>
            <article class="ecosystem-route-card">
                <span class="eyebrow">שירותים נלווים</span>
                <h3>כבר ברור שצריך יותר?</h3>
                <p>אם המטרה היא תשתית או צמיחה עסקית, עדיף לעבור ישר לעמוד השירותים של Brndini.</p>
                <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">לשירותי Brndini</a>
            </article>
            <article class="ecosystem-route-card">
                <span class="eyebrow">עוד מסלולים</span>
                <h3>רוצה לראות למי זה מתאים או איך זה עובד?</h3>
                <p>יש לך גם עמודי עומק מסודרים שמפרקים את הקהל, הזרימה והמוצרים הבאים.</p>
                <a class="ghost-button button-link" href="{{ route('audiences') }}">לעוד עמודי עומק</a>
            </article>
        </div>
    </section>
@endsection
