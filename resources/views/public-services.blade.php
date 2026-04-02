@extends('layouts.app')

@php($title = 'שירותי Brndini | אחסון, SEO, קמפיינים, תחזוקה ואוטומציות')
@php($metaDescription = 'שירותי Brndini לעסקים: אחסון, SEO, קמפיינים, תחזוקת אתר, שדרוג אתר קיים, דפי נחיתה ואוטומציות. הווידג׳ט נשאר חינמי, והשירותים זמינים כשצריך צמיחה ותפעול חכם.')
@php($selectedPublicServiceType = old('service_type', request('service', 'hosting')))

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

    <section class="section-band section-band-plain">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">Brndini Ecosystem</p>
            <h2>בונים גם את השכבה הבאה: כלים שיביאו עוד תנועה, שליטה וצמיחה.</h2>
            <p class="hero-text">
                מעבר לכלי החינמי ולשירותים שכבר זמינים עכשיו, Brndini בונה עוד מוצרים וכלים דיגיטליים.
                אפשר להשאיר עניין מוקדם ולקבל גישה ראשונה כשהם ייפתחו.
            </p>
        </div>

        <div class="about-process-grid">
            <article class="process-card-strong">
                <span class="process-index">01</span>
                <h3>כלי SEO ותוכן</h3>
                <p>כלים שיחברו מחקר, תוכן, מבנה עמודים ואופטימיזציה למערכת פעולה אחת.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">02</span>
                <h3>כלי אוטומציה ולידים</h3>
                <p>פתרונות שיחברו טפסים, CRM, follow-up ותהליכי מכירה מהירים יותר.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">03</span>
                <h3>מרכז כלים של Brndini</h3>
                <p>אקו־סיסטם של מוצרים חינמיים וכלים חכמים, שכולם מובילים לצמיחה ולשליטה טובה יותר.</p>
            </article>
        </div>

        <div class="brndini-service-actions">
            <a class="primary-button button-link" href="{{ route('brndini.services', ['service' => 'ecosystem_access']) }}#public-service-form">
                אני רוצה גישה מוקדמת
            </a>
        </div>
    </section>

    <section class="section-band section-band-plain">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">איך זה עובד בפועל</p>
            <h2>תהליך פשוט, ברור ומהיר משלב הפנייה ועד התאמה לשירות.</h2>
        </div>

        <div class="about-process-grid">
            <article class="process-card-strong">
                <span class="process-index">01</span>
                <h3>בוחרים שירות שמתאים לעסק</h3>
                <p>אחסון, SEO, קמפיינים, תחזוקה, שדרוג אתר, דפי נחיתה או אוטומציות לפי השלב שבו העסק נמצא.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">02</span>
                <h3>משאירים פנייה עסקית מסודרת</h3>
                <p>שם, אימייל, אתר, מטרה עסקית ודרך חזרה מועדפת. בלי לפתוח טיקט תמיכה ובלי לבלבל עם הווידג׳ט.</p>
            </article>
            <article class="process-card-strong">
                <span class="process-index">03</span>
                <h3>Brndini ממשיכה משם</h3>
                <p>הפנייה נכנסת ישירות למרכז הלידים, ומאפשרת לך לנהל מכירה, תיאום וחזרה ללקוח מתוך מערכת אחת.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt" id="public-service-form">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">פנייה עסקית</p>
            <h2>רוצה שנחזור אליך על שירות מסוים?</h2>
            <p class="hero-text">
                לא צריך לפתוח חשבון כדי להשאיר פנייה עסקית. אם Brndini יכולה לעזור לך באחסון,
                SEO, קמפיינים, תחזוקת אתר, שדרוג אתר או אוטומציות, פשוט משאירים כאן פרטים.
            </p>
        </div>

        <section class="support-grid public-service-lead-grid">
            <section class="domain-card support-form-card">
                <div class="domain-card-head">
                    <div>
                        <h2>פתח פנייה לשירותי Brndini</h2>
                        <p class="panel-intro">זו פנייה עסקית לשירותים של Brndini, לא תמיכה טכנית של A11Y Bridge.</p>
                    </div>
                </div>

                <form class="stack-form" method="POST" action="{{ route('brndini.services.store') }}">
                    @csrf

                    <div class="support-form-row">
                        <div>
                            <label for="public_service_name">שם מלא</label>
                            <input id="public_service_name" name="name" type="text" value="{{ old('name') }}" placeholder="למשל: מיכאל אזין">
                        </div>
                        <div>
                            <label for="public_service_email">אימייל</label>
                            <input id="public_service_email" name="email" type="email" value="{{ old('email') }}" placeholder="office@example.com">
                        </div>
                    </div>

                    <label for="public_service_website">אתר / דומיין</label>
                    <input id="public_service_website" name="website" type="text" value="{{ old('website') }}" placeholder="https://your-site.com">

                    <label for="public_service_type">איזה שירות מעניין אותך?</label>
                    <select id="public_service_type" name="service_type">
                        <option value="hosting" @selected($selectedPublicServiceType === 'hosting')>אחסון וניהול שרת</option>
                        <option value="seo" @selected($selectedPublicServiceType === 'seo')>SEO וקידום אורגני</option>
                        <option value="campaigns" @selected($selectedPublicServiceType === 'campaigns')>קמפיינים ופרסום</option>
                        <option value="maintenance" @selected($selectedPublicServiceType === 'maintenance')>תחזוקת אתר</option>
                        <option value="website_upgrade" @selected($selectedPublicServiceType === 'website_upgrade')>שדרוג אתר קיים</option>
                        <option value="landing_pages" @selected($selectedPublicServiceType === 'landing_pages')>דפי נחיתה</option>
                        <option value="automations" @selected($selectedPublicServiceType === 'automations')>אוטומציות ותהליכים</option>
                        <option value="ecosystem_access" @selected($selectedPublicServiceType === 'ecosystem_access')>גישה מוקדמת לכלי Brndini הבאים</option>
                    </select>

                    <label for="public_service_goal">מה אתה רוצה להשיג?</label>
                    <input id="public_service_goal" name="goal" type="text" value="{{ old('goal') }}" placeholder="למשל: לשפר מהירות, להגדיל לידים, להעביר לאחסון יציב">

                    <label for="public_service_message">פרטים חשובים</label>
                    <textarea id="public_service_message" name="message" rows="6" placeholder="ספר בקצרה מה העסק צריך, מה מצב האתר היום, ומה היית רוצה שיקרה בחודש הקרוב.">{{ old('message') }}</textarea>

                    <label for="public_service_contact">איך נוח שנחזור אליך?</label>
                    <select id="public_service_contact" name="preferred_contact">
                        <option value="email" @selected(old('preferred_contact', 'email') === 'email')>אימייל</option>
                        <option value="phone" @selected(old('preferred_contact') === 'phone')>טלפון</option>
                        <option value="whatsapp" @selected(old('preferred_contact') === 'whatsapp')>ווטסאפ</option>
                    </select>

                    <div class="support-form-actions">
                        <button class="primary-button" type="submit">שלח פנייה עסקית</button>
                        <span class="meta-note">Brndini תחזור רק בנושא השירות שביקשת. זה לא ערוץ תמיכה טכנית של המערכת.</span>
                    </div>
                </form>
            </section>

            <aside class="support-side-stack">
                <section class="domain-card">
                    <h2>מתי זה מתאים?</h2>
                    <div class="domain-info-list">
                        <div class="domain-info-row">
                            <span>אחסון</span>
                            <strong>כשהאתר צריך יציבות, גיבויים ושקט תפעולי</strong>
                        </div>
                        <div class="domain-info-row">
                            <span>SEO</span>
                            <strong>כשרוצים יותר תנועה אורגנית ותוכן טוב יותר</strong>
                        </div>
                        <div class="domain-info-row">
                            <span>קמפיינים</span>
                            <strong>כשצריך יותר לידים, המרות ודפי נחיתה</strong>
                        </div>
                        <div class="domain-info-row">
                            <span>שדרוג אתר</span>
                            <strong>כשהאתר הנוכחי מרגיש כבד, מיושן או לא ממיר</strong>
                        </div>
                    </div>
                </section>

                <section class="domain-card">
                    <h2>מה קורה אחרי ששולחים?</h2>
                    <div class="domain-info-list">
                        <div class="domain-info-row">
                            <span>קליטת הפנייה</span>
                            <strong>נכנסת ישירות למרכז הלידים</strong>
                        </div>
                        <div class="domain-info-row">
                            <span>חזרה אליך</span>
                            <strong>לפי ערוץ הקשר שבחרת</strong>
                        </div>
                        <div class="domain-info-row">
                            <span>מטרת התהליך</span>
                            <strong>להבין התאמה עסקית, לא תמיכה טכנית</strong>
                        </div>
                    </div>
                </section>
            </aside>
        </section>
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
