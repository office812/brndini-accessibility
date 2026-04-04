@extends('layouts.app')

@php($title = 'שירותי Brndini | אחסון, SEO, קמפיינים, תחזוקה ואוטומציות')
@php($metaDescription = 'שירותי Brndini לעסקים: אחסון, SEO, קמפיינים, תחזוקת אתר, שדרוג אתר קיים, דפי נחיתה ואוטומציות. הווידג׳ט נשאר חינמי, והשירותים זמינים כשצריך צמיחה ותפעול חכם.')
@php($selectedPublicServiceType = old('service_type', request('service', 'hosting')))
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

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
            <p class="eyebrow">מתי השירותים האלה מתאימים</p>
            <h2>לא כל מי שמתקין את הכלי צריך שירות. השירותים כאן מיועדים רק למי שרוצה קפיצה עסקית נוספת.</h2>
        </div>

        <div class="decision-grid">
            <article class="decision-card decision-card-free">
                <span class="status-pill is-neutral">הכלי לבדו מספיק</span>
                <strong>אם אתה רוצה רק להפעיל את המוצר, להטמיע את הקוד ולנהל את הווידג׳ט לבד.</strong>
                <ul class="compact-check-list">
                    <li>יש לך אתר קיים שמתוחזק אצלך</li>
                    <li>אתה יודע לבצע הטמעה בסיסית</li>
                    <li>אין כרגע צורך בשדרוג אתר או שיווק</li>
                    <li>הצורך הוא תפעולי, לא שירותי</li>
                </ul>
            </article>

            <article class="decision-card decision-card-services">
                <span class="status-pill is-good">Brndini רלוונטית</span>
                <strong>אם מעבר לכלי אתה רוצה תשתית, צמיחה, שיפור אתר או מנוע שיווקי שעובד טוב יותר.</strong>
                <ul class="compact-check-list">
                    <li>האתר צריך אחסון, תחזוקה או מעבר מסודר</li>
                    <li>יש צורך ב־SEO, קמפיינים או דפי נחיתה</li>
                    <li>רוצים לשפר את הביצועים, המהירות או ההמרה</li>
                    <li>יש תהליכים שצריך להפוך לאוטומטיים</li>
                </ul>
            </article>
        </div>
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
            <a class="primary-button button-link" href="{{ route('brndini.services', array_merge($marketingParams, ['service' => 'ecosystem_access'])) }}#public-service-form">
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

        <div class="service-expectation-strip">
            <article class="service-expectation-card">
                <strong>פנייה עסקית, לא טיקט תמיכה</strong>
                <p>האזור הזה מיועד להצעות שירות ולבדיקת התאמה עסקית. תמיכה טכנית בכלי החינמי נשארת בתוך המערכת עצמה.</p>
            </article>
            <article class="service-expectation-card">
                <strong>שומרים על הפרדה ברורה</strong>
                <p>הווידג׳ט נשאר חינמי ו־self-service. השירותים של Brndini נכנסים רק כשבאמת צריך תוצאה עסקית מעבר לזה.</p>
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

                <form class="stack-form lead-wizard-form" method="POST" action="{{ route('brndini.services.store') }}" data-flow-wizard>
                    @csrf
                    <input type="hidden" name="flow_step" value="{{ old('flow_step', '1') }}" data-flow-step-input>

                    <div class="flow-wizard-intro">
                        <div>
                            <p class="eyebrow">פנייה עסקית</p>
                            <strong>3 צעדים קצרים ומסודרים</strong>
                            <p class="signup-note">נסביר לנו במה אתה צריך עזרה, נבין את המסגרת העסקית, ואז נחזור אליך בדרך שנוחה לך.</p>
                        </div>
                        <div class="flow-wizard-stepper" aria-label="התקדמות בפנייה עסקית">
                            <button class="flow-wizard-step {{ old('flow_step', '1') === '1' ? 'is-active' : '' }}" type="button" data-flow-step="1">
                                <span>1</span>
                                <strong>הצורך</strong>
                            </button>
                            <button class="flow-wizard-step {{ old('flow_step') === '2' ? 'is-active' : '' }}" type="button" data-flow-step="2">
                                <span>2</span>
                                <strong>המסגרת</strong>
                            </button>
                            <button class="flow-wizard-step {{ old('flow_step') === '3' ? 'is-active' : '' }}" type="button" data-flow-step="3">
                                <span>3</span>
                                <strong>פרטי חזרה</strong>
                            </button>
                        </div>
                    </div>

                    <div class="flow-summary-card flow-summary-card-lead">
                        <strong>הפנייה שלך כרגע</strong>
                        <div class="flow-summary-grid">
                            <div><span>שירות</span><strong data-flow-summary-target="service_type">אחסון וניהול שרת</strong></div>
                            <div><span>מטרה</span><strong data-flow-summary-target="goal">נמלא יחד תוך כדי</strong></div>
                            <div><span>זמן</span><strong data-flow-summary-target="timeframe">טרם הוגדר</strong></div>
                            <div><span>חזרה</span><strong data-flow-summary-target="preferred_contact">אימייל</strong></div>
                        </div>
                    </div>

                    <div class="flow-stage {{ old('flow_step', '1') === '1' ? 'is-active' : '' }}" data-flow-stage="1">
                        <div class="flow-stage-head">
                            <strong>במה בדיוק נוכל לעזור?</strong>
                            <p>נגדיר את השירות שמעניין אותך ואת המטרה העסקית, כדי שנחזור עם כיוון מדויק ולא עם תשובה כללית.</p>
                        </div>

                        <div class="support-form-row">
                            <div>
                                <label for="public_service_name">שם מלא</label>
                                <input id="public_service_name" name="name" type="text" value="{{ old('name') }}" placeholder="למשל: מיכאל אזין" required>
                            </div>
                            <div>
                                <label for="public_service_email">אימייל</label>
                                <input id="public_service_email" name="email" type="email" value="{{ old('email') }}" placeholder="office@example.com" required>
                            </div>
                        </div>

                        <label for="public_service_website">אתר / דומיין</label>
                        <input id="public_service_website" name="website" type="text" value="{{ old('website') }}" placeholder="https://your-site.com">

                        <label for="public_service_type">איזה שירות מעניין אותך?</label>
                        <select id="public_service_type" name="service_type" required>
                            <option value="hosting" @selected($selectedPublicServiceType === 'hosting')>אחסון וניהול שרת</option>
                            <option value="seo" @selected($selectedPublicServiceType === 'seo')>SEO וקידום אורגני</option>
                            <option value="campaigns" @selected($selectedPublicServiceType === 'campaigns')>קמפיינים ופרסום</option>
                            <option value="maintenance" @selected($selectedPublicServiceType === 'maintenance')>תחזוקת אתר</option>
                            <option value="website_upgrade" @selected($selectedPublicServiceType === 'website_upgrade')>שדרוג אתר קיים</option>
                            <option value="landing_pages" @selected($selectedPublicServiceType === 'landing_pages')>דפי נחיתה</option>
                            <option value="automations" @selected($selectedPublicServiceType === 'automations')>אוטומציות ותהליכים</option>
                            <option value="ecosystem_access" @selected($selectedPublicServiceType === 'ecosystem_access')>גישה מוקדמת לכלי Brndini הבאים</option>
                        </select>

                        <input type="hidden" name="entry_point" value="{{ old('entry_point', request('entry', 'public-services')) }}">
                        <input type="hidden" name="utm_source" value="{{ old('utm_source', request('utm_source')) }}">
                        <input type="hidden" name="utm_medium" value="{{ old('utm_medium', request('utm_medium')) }}">
                        <input type="hidden" name="utm_campaign" value="{{ old('utm_campaign', request('utm_campaign')) }}">
                        <input type="hidden" name="referrer_url" value="{{ old('referrer_url', request('referrer_url', request()->headers->get('referer'))) }}">

                        <label for="public_service_goal">מה אתה רוצה להשיג?</label>
                        <input id="public_service_goal" name="goal" type="text" value="{{ old('goal') }}" placeholder="למשל: לשפר מהירות, להגדיל לידים, להעביר לאחסון יציב" required>

                        <div class="signup-actions-row">
                            <button class="primary-button" type="button" data-flow-next>המשך</button>
                        </div>
                    </div>

                    <div class="flow-stage {{ old('flow_step') === '2' ? 'is-active' : '' }}" data-flow-stage="2">
                        <div class="flow-stage-head">
                            <strong>נבין את המסגרת העסקית</strong>
                            <p>כמה פרטים קצרים שיעזרו לנו לדעת אם מדובר בצורך דחוף, בצמיחה, בשדרוג או במהלך ארוך יותר.</p>
                        </div>

                        <div class="support-form-row">
                            <div>
                                <label for="public_service_business_type">איזה סוג עסק אתה?</label>
                                <select id="public_service_business_type" name="business_type">
                                    <option value="">בחר סוג עסק</option>
                                    @foreach ($serviceLeadBusinessTypeLabels as $businessKey => $businessLabel)
                                        <option value="{{ $businessKey }}" @selected(old('business_type') === $businessKey)>{{ $businessLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="public_service_team_size">מה גודל הצוות?</label>
                                <select id="public_service_team_size" name="team_size">
                                    <option value="">בחר גודל צוות</option>
                                    @foreach ($serviceLeadTeamSizeLabels as $teamKey => $teamLabel)
                                        <option value="{{ $teamKey }}" @selected(old('team_size') === $teamKey)>{{ $teamLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="support-form-row">
                            <div>
                                <label for="public_service_timeframe">מתי תרצה להתחיל?</label>
                                <select id="public_service_timeframe" name="timeframe">
                                    <option value="">בחר טווח זמן</option>
                                    @foreach ($serviceLeadTimeframeLabels as $timeframeKey => $timeframeLabel)
                                        <option value="{{ $timeframeKey }}" @selected(old('timeframe') === $timeframeKey)>{{ $timeframeLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="public_service_budget">מה סדר הגודל התקציבי?</label>
                                <select id="public_service_budget" name="budget_range">
                                    <option value="">בחר תקציב משוער</option>
                                    @foreach ($serviceLeadBudgetLabels as $budgetKey => $budgetLabel)
                                        <option value="{{ $budgetKey }}" @selected(old('budget_range') === $budgetKey)>{{ $budgetLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="support-form-row">
                            <div>
                                <label for="public_service_urgency">מה רמת הדחיפות?</label>
                                <select id="public_service_urgency" name="urgency_level">
                                    <option value="">בחר רמת דחיפות</option>
                                    @foreach ($serviceLeadUrgencyLabels as $urgencyKey => $urgencyLabel)
                                        <option value="{{ $urgencyKey }}" @selected(old('urgency_level') === $urgencyKey)>{{ $urgencyLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="public_service_callback_window">מתי הכי נוח לחזור אליך?</label>
                                <select id="public_service_callback_window" name="callback_window">
                                    <option value="">בחר חלון חזרה</option>
                                    @foreach ($serviceLeadCallbackWindowLabels as $windowKey => $windowLabel)
                                        <option value="{{ $windowKey }}" @selected(old('callback_window') === $windowKey)>{{ $windowLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="signup-actions-row">
                            <button class="ghost-button" type="button" data-flow-prev>חזרה</button>
                            <button class="primary-button" type="button" data-flow-next>המשך</button>
                        </div>
                    </div>

                    <div class="flow-stage {{ old('flow_step') === '3' ? 'is-active' : '' }}" data-flow-stage="3">
                        <div class="flow-stage-head">
                            <strong>עוד פרטי קשר קצרים ואפשר לשלוח</strong>
                            <p>כאן נסיים עם הדרך הנוחה לחזור אליך ועם כמה פרטים שיעזרו לנו להגיב מדויק יותר.</p>
                        </div>

                        <label for="public_service_message">פרטים חשובים</label>
                        <textarea id="public_service_message" name="message" rows="6" placeholder="ספר בקצרה מה העסק צריך, מה מצב האתר היום, ומה היית רוצה שיקרה בחודש הקרוב." required>{{ old('message') }}</textarea>

                        <label for="public_service_contact">איך נוח שנחזור אליך?</label>
                        <select id="public_service_contact" name="preferred_contact" required>
                            <option value="email" @selected(old('preferred_contact', 'email') === 'email')>אימייל</option>
                            <option value="phone" @selected(old('preferred_contact') === 'phone')>טלפון</option>
                            <option value="whatsapp" @selected(old('preferred_contact') === 'whatsapp')>ווטסאפ</option>
                        </select>

                        <label for="public_service_phone">טלפון / ווטסאפ לחזרה</label>
                        <input id="public_service_phone" name="contact_phone" type="text" value="{{ old('contact_phone') }}" placeholder="למשל: 050-123-4567">
                        <span class="meta-note">אם בחרת טלפון או ווטסאפ, חשוב להוסיף כאן מספר זמין.</span>

                        <div class="flow-summary-card">
                            <strong>לפני שליחה</strong>
                            <p class="signup-note">Brndini תחזור רק על השירות שביקשת. זה לא ערוץ תמיכה טכנית של המערכת, אלא פנייה עסקית מסודרת.</p>
                        </div>

                        <div class="signup-actions-row">
                            <button class="ghost-button" type="button" data-flow-prev>חזרה</button>
                            <button class="primary-button" type="submit">שלח פנייה עסקית</button>
                        </div>
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
