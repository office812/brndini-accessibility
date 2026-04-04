@extends('layouts.app')

@php($title = 'כלי חינמי להטמעת וידג׳ט נגישות | דשבורד, הטמעה והצהרה בסיסית | A11Y Bridge')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="landing-hero" id="top">
        <div class="landing-hero-copy">
            <p class="eyebrow">פלטפורמת נגישות</p>
            <h1>כלי חינמי להטמעת וידג׳ט נגישות, ניהול מרכזי והטמעה קבועה.</h1>
            <p class="hero-text hero-text-lead">
                A11Y Bridge מאפשרת לבעלי אתרים, סוכנויות וצוותים להטמיע וידג׳ט נגישות ממקום אחד:
                קוד הטמעה קבוע, דשבורד, הצהרה בסיסית, בקרה טכנית וממשק שנראה כמו מוצר אמיתי.
            </p>

            <div class="hero-action-row hero-action-row-center">
                <a class="primary-button button-link" href="{{ route('register.show') }}">התחל ניסיון</a>
                <a class="ghost-button button-link" href="{{ route('how-it-works') }}">איך זה עובד</a>
            </div>
        </div>

        <div class="hero-product-stage" aria-label="A11Y Bridge platform preview">
            <div class="hero-product-float hero-float-right">
                <span class="hero-float-label">הטמעה</span>
                <strong>קוד אחד באתר</strong>
                <p>קוד הטמעה קבוע עם הגדרות דינמיות.</p>
            </div>

            <div class="hero-product-float hero-float-left">
                <span class="hero-float-label">הצהרה</span>
                <strong>הצהרה + עמוד ציבורי</strong>
                <p>ניסוח בסיסי, קישור קבוע ועמוד ברור לגולשים.</p>
            </div>

            <div class="hero-device">
                <div class="hero-device-topbar">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <div class="hero-device-screen">
                    <div class="hero-device-nav">
                        <span>לוח ניהול</span>
                        <span>התקנה</span>
                        <span>ציות</span>
                        <span>חשבון</span>
                    </div>

                    <div class="hero-device-grid">
                        <article class="hero-device-card hero-device-card-primary">
                            <span class="hero-device-label">A11Y BRIDGE</span>
                            <strong>ווידג׳ט נגישות</strong>
                            <p>מפתח אתר, הגדרות מותג, טקסט כפתור ושפה</p>
                        </article>
                        <article class="hero-device-card">
                            <span class="hero-device-label">התקנה</span>
                            <strong>מתקינים פעם אחת</strong>
                            <p>העתקה, הדבקה, רענון ואימות</p>
                        </article>
                        <article class="hero-device-card">
                            <span class="hero-device-label">הצהרה</span>
                            <strong>יוצר הצהרה</strong>
                            <p>קישור קבוע, ניסוח בסיסי ופרטי קשר</p>
                        </article>
                        <article class="hero-device-card">
                            <span class="hero-device-label">תוכן</span>
                <strong>מאמרים אורגניים</strong>
                            <p>צמיחה אורגנית וחינוך שוק</p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="trust-bar" aria-label="trust signals">
        <div class="trust-chip">וידג׳ט חינמי עם שליטה מרחוק</div>
        <div class="trust-chip">הטמעה אחת, ניהול מרחוק</div>
        <div class="trust-chip">הצהרה בסיסית וקישור ציבורי</div>
        <div class="trust-chip">מותאם לבעלי אתרים, סוכנויות וצוותים</div>
    </section>

    <section class="company-proof-strip" aria-label="company proof">
        <article class="company-proof-card">
            <strong>פלטפורמה אחת</strong>
            <span>וידג׳ט, התקנה, הצהרה ובקרה טכנית</span>
        </article>
        <article class="company-proof-card">
            <strong>2 חבילות</strong>
            <span>חינם להתחלה ופרימיום ליכולות מתקדמות</span>
        </article>
        <article class="company-proof-card">
            <strong>Multi-site</strong>
            <span>ניהול כמה אתרים ורישיונות מאותו חשבון</span>
        </article>
        <article class="company-proof-card">
            <strong>תוכן אורגני</strong>
            <span>מאמרים, מסרים שיווקיים ועמודי מוצר</span>
        </article>
    </section>

    <section class="section-band section-band-plain" id="how-a11y-bridge-works">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">איך A11Y Bridge עובדת</p>
            <h2>שלושה שלבים פשוטים להפעלת כלי נגישות חינמי.</h2>
        </div>

        <div class="flow-section">
            <div class="flow-steps">
                <article class="flow-step-card">
                    <span class="process-index">01</span>
                    <h3>פותחים אתר חדש</h3>
                    <p>פותחים סביבת עבודה, מוסיפים אתר, ומקבלים מפתח אתר וקוד הטמעה קבוע.</p>
                </article>
                <article class="flow-step-card">
                    <span class="process-index">02</span>
                    <h3>מגדירים את הפלטפורמה</h3>
                    <p>מגדירים וידג׳ט, הצהרה בסיסית, שפה, מיקום וצבע.</p>
                </article>
                <article class="flow-step-card">
                    <span class="process-index">03</span>
                    <h3>מעדכנים ומנהלים</h3>
                    <p>האתר מושך את ההגדרות החדשות אוטומטית בלי להחליף שוב קוד.</p>
                </article>
            </div>

            <div class="flow-stage">
                <div class="flow-stage-card">
                    <span class="eyebrow">נגישות זורמת</span>
                <h3>מוצר אחד שמחבר וידג׳ט, התקנה, הצהרה ולוח ניהול.</h3>
                <p>
                    במקום כמה שכבות מפוזרות, A11Y Bridge נותנת תהליך אחד ברור לבעל האתר,
                    למי שמטמיע, ולמי שמנהל את החשבון לאורך זמן.
                </p>
                    <div class="flow-stage-pills">
                        <span class="preview-pill">ווידג׳ט</span>
                        <span class="preview-pill">התקנה</span>
                        <span class="preview-pill">ציות</span>
                        <span class="preview-pill">חשבון</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="magazine-actions">
            <a class="ghost-button button-link" href="{{ route('how-it-works') }}">לעמוד המלא: איך זה עובד</a>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">למי זה מתאים</p>
            <h2>שלושה קהלים שמקבלים ערך מיידי מהמערכת בלי להתחיל מפרויקט מורכב.</h2>
        </div>

        <div class="audience-fit-grid">
            <article class="audience-fit-card">
                <span class="status-pill is-neutral">בעלי עסקים</span>
                <h3>מי שרוצה להפעיל שכבת נגישות מהר, בלי להיתקע על טכני.</h3>
                <p>חשבון אחד, אתר אחד, קוד אחד, ווידג׳ט מנוהל והצהרה בסיסית שאפשר להעלות תוך זמן קצר.</p>
                <ul class="compact-check-list">
                    <li>פתיחה מהירה של חשבון</li>
                    <li>הטמעה פשוטה לאתר קיים</li>
                    <li>שליטה בלי תלות במפתח</li>
                </ul>
            </article>

            <article class="audience-fit-card">
                <span class="status-pill is-neutral">סוכנויות</span>
                <h3>מי שמנהל כמה אתרים ורוצה נקודת שליטה אחידה ולא עוד כאוס של תוספים.</h3>
                <p>אותו תהליך התקנה, אותו דשבורד, אותה שכבת הצהרה ובקרה טכנית לכל לקוח או אתר.</p>
                <ul class="compact-check-list">
                    <li>ניהול כמה אתרים</li>
                    <li>רישיונות והטמעות במקום אחד</li>
                    <li>חוויית מוצר נוחה יותר ללקוחות</li>
                </ul>
            </article>

            <article class="audience-fit-card">
                <span class="status-pill is-neutral">צוותים פנימיים</span>
                <h3>מי שצריך מערכת ברורה, שקופה וניתנת לניהול לאורך זמן.</h3>
                <p>מעקב אחר מצב האתר, זיהוי התקנה, תמיכה טכנית וכלי בסיסי שנשאר זמין גם כשאין פרויקט שירות מלא מסביב.</p>
                <ul class="compact-check-list">
                    <li>בקרה טכנית פשוטה</li>
                    <li>מסר ברור להנהלה ולצוות</li>
                    <li>חיבור טבעי לאקו־סיסטם של Brndini</li>
                </ul>
            </article>
        </div>

        <div class="magazine-actions">
            <a class="ghost-button button-link" href="{{ route('audiences') }}">לעמוד המלא: למי זה מתאים</a>
            <a class="ghost-button button-link" href="{{ route('agencies') }}">לעמוד המלא: לסוכנויות</a>
        </div>
    </section>

    <section class="cta-banner">
        <div>
            <p class="eyebrow">בדוק עכשיו אם האתר שלך נגיש</p>
            <h2>קבל שליטה מלאה על שכבת הנגישות של האתר שלך.</h2>
        </div>
        <a class="primary-button button-link" href="{{ route('register.show') }}">פתח חשבון</a>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מה מקבלים בחינם כבר בהתחלה</p>
            <h2>הערך מתחיל מהר, בלי שיחת מכירה ובלי להיתקע על חבילת פרימיום.</h2>
            <p class="hero-text">
                פותחים חשבון, מוסיפים אתר, מעתיקים קוד, ומקבלים שכבת ניהול בסיסית שאפשר לעבוד איתה באמת.
                המטרה היא לתת לך מערכת שימושית מהיום הראשון, לא רק עמוד הרשמה יפה.
            </p>
        </div>

        <div class="free-value-grid">
            <article class="free-value-card">
                <span class="process-index">01</span>
                <h3>קוד הטמעה קבוע</h3>
                <p>פעם אחת באתר, ואז כל השינויים הבאים נמשכים מתוך הדשבורד בלי להחליף שוב קוד.</p>
            </article>
            <article class="free-value-card">
                <span class="process-index">02</span>
                <h3>ווידג׳ט מנוהל</h3>
                <p>שליטה על טקסט כפתור, מיקום, שפה, מראה והתאמות בסיסיות מתוך מקום אחד.</p>
            </article>
            <article class="free-value-card">
                <span class="process-index">03</span>
                <h3>הצהרה בסיסית</h3>
                <p>עמוד ציבורי ברור עם ניסוח בסיסי, פרטי קשר וקישור קבוע מתוך הווידג׳ט.</p>
            </article>
            <article class="free-value-card">
                <span class="process-index">04</span>
                <h3>בקרה טכנית</h3>
                <p>חיווי אם ההטמעה זוהתה, בדיקות בסיסיות, סטטוס אתר והתראות לשכבת הניהול.</p>
            </article>
            <article class="free-value-card free-value-card-highlight">
                <p class="eyebrow">בלי הבטחות מיותרות</p>
                <h3>כלי self-service חינמי, ברור ושקוף.</h3>
                <ul class="compact-check-list">
                    <li>כולל widget, דשבורד והצהרה בסיסית</li>
                    <li>לא דורש שיחת מכירה כדי להתחיל</li>
                    <li>תמיכה טכנית בלבד בשימוש במערכת</li>
                    <li>שירותי Brndini נשארים שכבה נפרדת ואופציונלית</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="section-band" id="solutions">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">טכנולוגיה שעובדת</p>
            <h2>כלי נגישות פשוט יותר להפעלה.</h2>
            <p class="hero-text">
                המוצר שלך לא צריך להיות רק כפתור. הוא צריך לכלול התקנה, חשבון, הצהרה בסיסית,
                בקרה טכנית וצמיחת תוכן.
            </p>
        </div>

        <div class="capability-grid">
            <article class="capability-card">
                <h3>טעינה אוטומטית מהפלטפורמה</h3>
                <p>האתר נשאר עם אותו קוד הטמעה, וההגדרות נמשכות בזמן טעינה מתוך הפלטפורמה.</p>
            </article>
            <article class="capability-card">
                <h3>הטמעה פשוטה ושליטה מיידית</h3>
                <p>הלקוח רואה מסך התקנה ברור, העתקה והדבקה, ואימות מיידי.</p>
            </article>
            <article class="capability-card">
                <h3>מתאים כמעט לכל אתר</h3>
                <p>Custom code, WordPress, או builder אחר. העיקרון נשאר זהה.</p>
            </article>
            <article class="capability-card">
                <h3>ווידג׳ט עקבי עם המותג</h3>
                <p>מיקום, צבע, טקסט, שפה והעדפות תצוגה מתוך לוח ניהול אחד.</p>
            </article>
            <article class="capability-card">
                <h3>מסר ברור ושקוף</h3>
                <p>הצהרה בסיסית, קישור ציבורי וניסוח אחראי בלי להבטיח מה שהמוצר לא יכול להבטיח.</p>
            </article>
            <article class="capability-card">
                <h3>צמיחה דרך תוכן</h3>
                <p>אזור מאמרים מובנה שעוזר גם לקידום אורגני וגם להסברת הקטגוריה.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-plain enterprise-band">
        <div class="enterprise-band-grid">
            <div class="enterprise-band-copy">
                <p class="eyebrow">Enterprise ready</p>
                <h2>נראות של חברת הייטק, שליטה של SaaS ותהליך עבודה ברור לכל אתר.</h2>
                <p class="hero-text">
                    A11Y Bridge נבנתה כך שהמוצר עצמו ייראה רציני לא רק למבקר הקצה, אלא גם לבעל העסק,
                    לסוכנות, לאיש התמיכה ולכל מי שמנהל כמה אתרים במקביל.
                </p>
                <div class="flow-stage-pills">
                    <span class="preview-pill">Hosted widget</span>
                    <span class="preview-pill">Support center</span>
                    <span class="preview-pill">Super admin</span>
                    <span class="preview-pill">SEO content</span>
                </div>
            </div>

            <div class="enterprise-band-panel">
                <article class="enterprise-panel-card">
                    <span class="eyebrow">בקרה</span>
                    <strong>הטמעה, סטטוס, ציות וחבילה</strong>
                    <p>כל אתר מקבל מצב רישיון, קוד הטמעה, סטטוס חי וחיווי אמיתי על מה שהוטמע בפועל.</p>
                </article>
                <article class="enterprise-panel-card">
                    <span class="eyebrow">תפעול</span>
                    <strong>תפעול, משתמשים וסופר־אדמין</strong>
                    <p>ניהול פניות טכניות, מעקבים גלובליים, לקוחות, אתרים וחבילות מתוך אזור מרכזי אחד.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">הפתרון לכל אתר</p>
            <h2>מתאים לכל אתר, עם אותו תהליך ברור של ניהול והטמעה.</h2>
        </div>

        <div class="integration-cloud">
            <span class="integration-pill">WordPress</span>
            <span class="integration-pill">Shopify</span>
            <span class="integration-pill">WooCommerce</span>
            <span class="integration-pill">Webflow</span>
            <span class="integration-pill">Wix</span>
            <span class="integration-pill">Custom code</span>
            <span class="integration-pill">מתאים לסוכנויות</span>
            <span class="integration-pill">קוד הטמעה מנוהל</span>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">ברוכים הבאים לכל מבקר</p>
            <h2>חוויית widget ברורה, מותאמת ומבוססת העדפות משתמש.</h2>
        </div>

        <div class="profile-grid">
            <article class="profile-card">
                <h3>קוראי מסך</h3>
                <p>גישה ישירה לכלי נגישות והצהרת נגישות מתוך ממשק ברור.</p>
            </article>
            <article class="profile-card">
                <h3>ניווט מקלדת</h3>
                <p>גישה נוחה לניווט ולעבודה בלי עכבר.</p>
            </article>
            <article class="profile-card">
                <h3>תמיכה חזותית</h3>
                <p>גודל טקסט, ניגודיות, והדגשת קישורים.</p>
            </article>
            <article class="profile-card">
                <h3>הפחתת תנועה</h3>
                <p>הפחתת אנימציות ותנועה עבור חוויה שקטה יותר.</p>
            </article>
            <article class="profile-card">
                <h3>תמיכה בשפות</h3>
                <p>ממשק עברית/אנגלית ותמיכה בלקוחות מגוונים.</p>
            </article>
            <article class="profile-card">
                <h3>גישה להצהרת נגישות</h3>
                <p>גישה שקופה להצהרת נגישות מתוך ה־widget.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-dark">
        <div class="section-heading section-heading-light section-heading-center">
            <p class="eyebrow">נבנה כדי לתמוך בציות</p>
            <h2>המערכת ממסגרת נגישות כמו פתרון רציני, לא כמו תוסף בודד.</h2>
            <p class="hero-text">
                    וידג׳ט נגישות, אימות התקנה, הצהרה בסיסית ותהליך עבודה ברור
                יוצרים יחד מוצר שנראה מקצועי יותר גם ללקוח וגם לשוק.
            </p>
        </div>

        <div class="compliance-badges">
            <span class="compliance-badge">תהליך עבודה ל־WCAG</span>
            <span class="compliance-badge">הצהרת נגישות</span>
            <span class="compliance-badge">אימות הטמעה</span>
            <span class="compliance-badge">בדיקות בסיסיות</span>
            <span class="compliance-badge">שקיפות והצהרה</span>
        </div>
    </section>

    <section class="section-band section-band-plain">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">איך זה מרגיש אחרי שהמערכת עולה לאוויר</p>
            <h2>כל אחד רואה את השכבה שמתאימה לו, בלי בלגן ובלי לעבוד “סביב” המוצר.</h2>
            <p class="hero-text">
                במקום כלי שנראה אותו דבר לכולם, A11Y Bridge נותנת חוויה ברורה לכל תפקיד:
                מי שמפעיל את האתר, מי שמטמיע, ומי שמנהל כמה לקוחות או כמה אתרים.
            </p>
        </div>

        <div class="role-experience-grid">
            <article class="role-experience-card">
                <span class="status-pill is-neutral">בעל האתר</span>
                <h3>רואה שליטה ברורה, בלי להיכנס לטכני מיותר.</h3>
                <p>סטטוס אתר, ווידג׳ט, הצהרה בסיסית, והבנה מה מותקן ומה עדיין מחכה להטמעה.</p>
                <ul class="compact-check-list">
                    <li>הבנה מהירה של מצב האתר</li>
                    <li>שפה פשוטה במקום ז׳רגון טכני</li>
                    <li>מקום אחד לכל מה שצריך ביום־יום</li>
                </ul>
            </article>

            <article class="role-experience-card">
                <span class="status-pill is-neutral">המטמיע</span>
                <h3>רואה קוד, אימות וחיווי, בלי לחפש קבצים או גרסאות.</h3>
                <p>מסך התקנה ברור, קוד אחד קבוע, חיווי שהווידג׳ט באמת זוהה, ומסלול עבודה קצר לביצוע.</p>
                <ul class="compact-check-list">
                    <li>העתקה והדבקה מהירה</li>
                    <li>פחות טעויות בהטמעה</li>
                    <li>חיווי אם האתר באמת מושך את ההגדרות</li>
                </ul>
            </article>

            <article class="role-experience-card">
                <span class="status-pill is-neutral">סוכנות או צוות</span>
                <h3>רואים רישיונות, אתרים והטמעות כמו מערכת עבודה אמיתית.</h3>
                <p>לא עוד ערבוב של תוסף, מיילים והודעות. יש מקום אחד שמרכז אתרים, סטטוסים, הצהרות וניהול מתמשך.</p>
                <ul class="compact-check-list">
                    <li>פחות כאוס בין אתרים ולקוחות</li>
                    <li>נקודת שליטה אחידה לכל החשבון</li>
                    <li>בסיס טבעי להמשך למוצרים ושירותים נוספים</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="section-band">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">אמון והוכחת ערך</p>
            <h2>נראה כמו פתרון נגישות גדול, גם ברמה המוצרית וגם ברמה השיווקית.</h2>
        </div>

        <div class="testimonial-grid">
            <article class="testimonial-card">
                <p>“הטמעה אחת, שליטה מלאה, ופחות friction בכל שינוי קטן שאנחנו עושים ללקוחות.”</p>
                <strong>בעל סוכנות דיגיטל</strong>
            </article>
            <article class="testimonial-card">
                <p>“הערך הוא לא רק ה־widget, אלא כל ה־platform layer שמסביב.”</p>
                <strong>יועץ נגישות</strong>
            </article>
            <article class="testimonial-card">
                <p>“זה כבר נראה כמו מוצר נגישות אמיתי ולא כמו תוסף זמני.”</p>
                <strong>שותף / משווק</strong>
            </article>
        </div>

        <div class="people-signal-band" aria-label="אנשים מאחורי המוצר">
            <article class="people-signal-card people-signal-card-highlight">
                <div class="people-signal-avatars">
                    <span>PM</span>
                    <span>UX</span>
                    <span>CS</span>
                </div>
                <div>
                    <p class="eyebrow">People layer</p>
                    <h3>צוות אחד שמחבר מוצר, תמיכה ותוכן.</h3>
                    <p>המערכת מרגישה חיה יותר כי היא בנויה כמו חברה אמיתית: מוצר, תמיכה, חוויית משתמש ותפעול סביב אותו לקוח.</p>
                </div>
            </article>

            <article class="people-signal-card">
                <strong>מוצר ונראות</strong>
                <p>שפת ממשק אחידה, ווידג׳ט מנוהל וזרימה שנראית כמו SaaS בוגר.</p>
            </article>

            <article class="people-signal-card">
                <strong>תמיכה והטמעה</strong>
                <p>מרכז תמיכה, סטטוס התקנה וחיווי אמיתי למה שמוטמע בפועל.</p>
            </article>
        </div>

        <div class="operating-principles-strip">
            <article class="operating-principle-card">
                <strong>מהיר להתחלה</strong>
                <p>אפשר להפעיל את המוצר בלי להתחיל מפרויקט, הצעת מחיר או שיחת מכירה.</p>
            </article>
            <article class="operating-principle-card">
                <strong>שקוף במסרים</strong>
                <p>המערכת מסבירה מה היא כן נותנת, ומה נשאר באחריות בעל האתר.</p>
            </article>
            <article class="operating-principle-card">
                <strong>מוכן לצמיחה</strong>
                <p>כשהעסק רוצה יותר, Brndini והשכבות הבאות כבר מחכות סביב אותו חשבון.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">שירותים נוספים של Brndini</p>
            <h2>הווידג׳ט נשאר חינמי. Brndini נכנסת כשצריך צמיחה, תשתית ותפעול.</h2>
            <p class="hero-text">
                A11Y Bridge היא דלת הכניסה. אחריה אפשר להמשיך לאחסון, SEO, קמפיינים, תחזוקת אתר,
                שדרוג אתר קיים, דפי נחיתה ואוטומציות, בלי לבלבל את זה עם תמיכה טכנית של המערכת.
            </p>
        </div>

        @include('partials.brndini-services-cards')
    </section>

    <section class="section-band section-band-plain">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מתי נשארים במוצר, ומתי ממשיכים ל־Brndini</p>
            <h2>שתי שכבות ברורות: כלי חינמי לעבודה עצמאית, ושירותים רק כשיש צורך עסקי אמיתי.</h2>
        </div>

        <div class="decision-grid">
            <article class="decision-card decision-card-free">
                <span class="status-pill is-neutral">נשארים בכלי החינמי</span>
                <strong>אם מה שצריך הוא widget, הטמעה, הצהרה בסיסית וניהול טכני.</strong>
                <ul class="compact-check-list">
                    <li>להטמיע את הווידג׳ט באתר</li>
                    <li>לשנות טקסט, שפה, מיקום ועיצוב</li>
                    <li>להציג הצהרת נגישות בסיסית</li>
                    <li>לראות סטטוס התקנה וחיווי טכני</li>
                </ul>
            </article>

            <article class="decision-card decision-card-services">
                <span class="status-pill is-good">ממשיכים ל־Brndini</span>
                <strong>אם מה שצריך הוא שיפור עסקי רחב יותר מעבר לכלי עצמו.</strong>
                <ul class="compact-check-list">
                    <li>אחסון יציב וניהול תשתית</li>
                    <li>SEO, תוכן וקידום אורגני</li>
                    <li>קמפיינים, פרסום ודפי נחיתה</li>
                    <li>שדרוג אתר, תחזוקה או אוטומציות</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="section-band section-band-plain">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">Brndini Ecosystem</p>
            <h2>הווידג׳ט הוא רק הצעד הראשון. Brndini בונה עוד כלים חינמיים וחכמים לעסקים.</h2>
            <p class="hero-text">
                אם מעניין אותך לקבל גישה מוקדמת לכלים הבאים של Brndini, אפשר להיכנס כבר עכשיו
                לרשימת העניין ולהיות הראשונים לשמוע כשמשהו חדש נפתח.
            </p>
        </div>

        @include('partials.brndini-future-products')

        <div class="magazine-actions">
            <a class="primary-button button-link" href="{{ route('brndini.services', array_merge($marketingParams, ['service' => 'ecosystem_access', 'entry' => 'home-ecosystem'])) }}#public-service-form">
                אני רוצה גישה מוקדמת
            </a>
            <a class="ghost-button button-link" href="{{ route('products', $marketingParams) }}">לכל המוצרים הבאים</a>
            <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">לכל שירותי Brndini</a>
        </div>
    </section>

    <section class="section-band section-band-alt">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מה עושים מכאן</p>
            <h2>שלוש דרכים ברורות להמשיך מכאן, לפי מה שהעסק שלך צריך עכשיו.</h2>
        </div>

        <div class="ecosystem-route-grid">
            <article class="ecosystem-route-card">
                <span class="process-index">01</span>
                <h3>רוצה רק את הכלי</h3>
                <p>פותחים חשבון, מטמיעים את הקוד ומתחילים לעבוד עם הדשבורד והווידג׳ט.</p>
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
            </article>
            <article class="ecosystem-route-card">
                <span class="process-index">02</span>
                <h3>צריך שיפור עסקי באתר</h3>
                <p>ממשיכים לשירותי Brndini לאחסון, SEO, קמפיינים, תחזוקה, שדרוג אתר או אוטומציות.</p>
                <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">לשירותי Brndini</a>
            </article>
            <article class="ecosystem-route-card">
                <span class="process-index">03</span>
                <h3>רוצה להיות ראשון בכלים הבאים</h3>
                <p>נכנסים לרשימת הגישה המוקדמת של Brndini ועוקבים אחרי המוצרים החדשים שנפתחים.</p>
                <a class="ghost-button button-link" href="{{ route('products', $marketingParams) }}">למוצרים הבאים</a>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt" id="pricing">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">המודל העסקי</p>
            <h2>הכלי נשאר חינמי. Brndini מציעה שירותים רק כשבאמת צריך אותם.</h2>
            <p class="hero-text">
                רצינו לבנות הצעה פשוטה וברורה: מתחילים עם כלי self-service חינמי,
                וכשרוצים צמיחה, אחסון, SEO, קמפיינים או שדרוג אתר, עוברים לשירותים
                העסקיים של Brndini באופן נפרד.
            </p>
        </div>

        @include('partials.pricing-cards')
    </section>

    <section class="section-band section-band-plain">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">למה זה חינמי</p>
            <h2>המודל בנוי כך שהכלי עצמו יישאר פתוח, וברנדיני תצמח מסביב לשירותים ולמוצרים נוספים.</h2>
            <p class="hero-text">
                לא רצינו לבנות עוד מוצר שמסתיר את הערך מאחורי paywall. המטרה היא לתת לבעלי אתרים
                כלי אמיתי שעובד כבר מהיום הראשון, ולחבר ממנו בהמשך רק את מי שבאמת צריך שכבת שירות
                עסקית או מוצרים נוספים של Brndini.
            </p>
        </div>

        <div class="transparency-grid">
            <article class="transparency-card">
                <span class="process-index">01</span>
                <h3>פחות חיכוך, יותר התקנות</h3>
                <p>כלי חינמי מאפשר לאנשים להתחיל מהר, להבין את הערך, ולהכניס את Brndini לעולם שלהם בלי חסם כניסה מיותר.</p>
            </article>
            <article class="transparency-card">
                <span class="process-index">02</span>
                <h3>דלת כניסה לאקו־סיסטם</h3>
                <p>הווידג׳ט הוא נקודת פתיחה. אחריו אפשר להמשיך למוצרים נוספים או לשירותים עסקיים רק אם יש בזה צורך אמיתי.</p>
            </article>
            <article class="transparency-card">
                <span class="process-index">03</span>
                <h3>הפרדה ברורה בין מוצר לשירות</h3>
                <p>הכלי נשאר self-service, ותמיכה טכנית נשארת טכנית. Brndini לא “מתחבאת” בתוך הווידג׳ט כשכבת שירות נגישות.</p>
            </article>
            <article class="transparency-card transparency-card-highlight">
                <p class="eyebrow">מסר אחד ברור</p>
                <h3>כלי חינמי שמייצר אמון, תנועה ולידים. שירותים רק כשצריך אותם באמת.</h3>
                <ul class="compact-check-list">
                    <li>אין פרימיום חובה כדי להתחיל</li>
                    <li>אין שיחת מכירה כדי להפעיל את המוצר</li>
                    <li>יש שכבת שירותים נפרדת למי שרוצה צמיחה</li>
                    <li>יש שכבת מוצרים עתידית למי שרוצה להישאר קרוב ל־Brndini</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="section-band section-band-plain" id="faq">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">שאלות נפוצות</p>
            <h2>תשובות קצרות שיעזרו להבין מהר מה המוצר נותן, ומה הוא לא.</h2>
            <p class="hero-text">
                זה המקום לעשות סדר: מה כולל הכלי החינמי, איך נראית התמיכה, מה קורה אחרי ההטמעה,
                ואיפה Brndini נכנסת עם שירותים עסקיים נפרדים.
            </p>
        </div>

        @include('partials.faq-items')

        <div class="magazine-actions">
            <a class="primary-button button-link" href="{{ route('faq') }}">לכל השאלות והתשובות</a>
        </div>
    </section>

    <section class="section-band articles-band" id="articles">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">מאמרים</p>
            <h2>אזור מאמרים שנראה כמו מגזין, ומחזק גם את הקידום האורגני וגם את האמון במוצר.</h2>
            <p class="hero-text">
                אזור התוכן נועד גם לעזור ללקוחות להבין את הפלטפורמה, וגם לענות לחיפושים אורגניים
                סביב תוסף נגישות לאתר, הצהרת נגישות, תהליך עבודה ל־WCAG וניהול שכבת נגישות.
            </p>
        </div>

        @if ($articles->isNotEmpty())
            @php($featuredArticle = $articles->first())
            @php($featuredCover = $featuredArticle->coverTheme())

            <div class="magazine-home-grid">
                <article class="magazine-feature-card article-cover article-cover-{{ $featuredCover['slug'] }}">
                    <div class="article-cover-art" aria-hidden="true">
                        <span class="article-cover-orb"></span>
                        <span class="article-cover-panel"></span>
                        <span class="article-cover-beam"></span>
                    </div>

                    <div class="magazine-feature-copy">
                        <p class="eyebrow">{{ $featuredCover['eyebrow'] }}</p>
                        <h3><a href="{{ route('articles.show', $featuredArticle) }}">{{ $featuredArticle->title }}</a></h3>
                        <p>{{ $featuredArticle->excerpt }}</p>
                        <div class="article-meta">
                            <span class="preview-pill">{{ optional($featuredArticle->published_at)->format('d.m.Y') }}</span>
                            <span class="preview-pill">{{ $featuredArticle->readingTimeMinutes() }} דקות קריאה</span>
                            <span class="preview-pill">{{ $featuredCover['chips'][0] }}</span>
                        </div>
                        <a class="text-link" href="{{ route('articles.show', $featuredArticle) }}">לקריאה מלאה</a>
                    </div>
                </article>

                <div class="magazine-side-list">
                    @foreach ($articles->skip(1)->take(3) as $article)
                        @php($cover = $article->coverTheme())
                        <article class="magazine-side-card">
                            <div class="magazine-side-thumb article-cover article-cover-{{ $cover['slug'] }}">
                                <div class="article-cover-art" aria-hidden="true">
                                    <span class="article-cover-orb"></span>
                                    <span class="article-cover-panel"></span>
                                </div>
                            </div>
                            <div>
                                <p class="meta-label">{{ optional($article->published_at)->format('d.m.Y') }}</p>
                                <h3><a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a></h3>
                                <p>{{ $article->excerpt }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @else
            <div class="article-grid">
                <article class="article-card">
                    <h3>בקרוב יופיעו כאן מאמרים</h3>
                    <p>אחרי הרצת ה־migration החדש יופיעו מאמרי ברירת מחדל, ומשם אפשר יהיה להוסיף עוד תוכן מתוך משתמש מנהל.</p>
                </article>
            </div>
        @endif

        <div class="magazine-actions">
            <a class="primary-button button-link" href="{{ route('articles.index') }}">לכל המאמרים</a>
        </div>

        @auth
            @if (auth()->user()->is_admin || auth()->user()->isSuperAdmin())
                <section class="panel-card article-admin-card" aria-labelledby="article-admin-title">
                    <p class="eyebrow">ניהול תוכן</p>
                    <h2 id="article-admin-title">פרסום מאמר חדש</h2>

                    <form class="stack-form" method="POST" action="{{ route('articles.store') }}">
                        @csrf

                        <label for="article_title">כותרת המאמר</label>
                        <input id="article_title" name="title" type="text" value="{{ old('title') }}" required>

                        <label for="article_excerpt">תקציר קצר</label>
                        <textarea id="article_excerpt" name="excerpt" rows="3" required>{{ old('excerpt') }}</textarea>

                        <label for="article_meta_description">תיאור לעמוד</label>
                        <input id="article_meta_description" name="meta_description" type="text" value="{{ old('meta_description') }}" maxlength="160">

                        <label for="article_body">תוכן המאמר</label>
                        <textarea id="article_body" name="body" rows="10" required>{{ old('body') }}</textarea>

                        <label class="toggle-row">
                            <input type="hidden" name="publish_now" value="0">
                            <input type="checkbox" name="publish_now" value="1" checked>
                            <span>לפרסם מיידית</span>
                        </label>

                        <button class="primary-button" type="submit">פרסם מאמר</button>
                    </form>
                </section>
            @endif
        @endauth
    </section>

    <section class="section-band section-band-cta" id="signup-form">
        <div class="auth-cta-grid">
            <section class="panel-card auth-cta-card">
                <p class="eyebrow">פתיחת ניסיון</p>
                <h2>פתיחת חשבון חדש</h2>
                <p class="hero-text">
                    מתחילים עם לוח ניהול, וידג׳ט מנוהל, מרכז התקנה, מרכז ציות וקוד הטמעה קבוע.
                </p>
                <a class="primary-button button-link" href="{{ route('register.show') }}">למסך פתיחת חשבון</a>
            </section>

            <section class="panel-card auth-cta-card">
                <p class="eyebrow">התחברות</p>
                <h2>כניסה למשתמש קיים</h2>
                <p class="hero-text">
                    כניסה מהירה להגדרות הווידג׳ט, למסך ההתקנה, למרכז הציות ולאזור החשבון.
                </p>
                <a class="ghost-button button-link" href="{{ route('login.show') }}">למסך התחברות</a>
            </section>
        </div>
    </section>
@endsection
