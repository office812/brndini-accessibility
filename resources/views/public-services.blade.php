@extends('layouts.app')

@php($title = 'שירותי Brndini | אחסון, SEO, קמפיינים, תחזוקה ואוטומציות')
@php($metaDescription = 'Brndini מציעה שכבת שירותים עסקיים נפרדת ל-A11Y Bridge: אחסון, SEO, קמפיינים, תחזוקה, שדרוג אתר, דפי נחיתה ואוטומציות.')
@php($selectedPublicServiceType = old('service_type', request('service', 'hosting')))
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="public-stage public-stage-brndini">
        <div class="public-stage-copy">
            <p class="eyebrow">שירותי Brndini</p>
            <h1>כשהאתר צריך יותר מווידג׳ט ודשבורד, Brndini היא שכבת ההמשך העסקית.</h1>
            <p class="hero-text hero-text-lead">
                כאן לא פותחים טיקט. כאן פותחים שיחה עסקית על אחסון, SEO, קמפיינים, תחזוקה,
                שדרוג אתר, דפי נחיתה או אוטומציות. אם צריך רק את הכלי החינמי, נשארים בתוך
                A11Y Bridge.
            </p>
            <div class="public-cta-row">
                <a class="primary-button button-link" href="#public-service-form">להשאיר פנייה עסקית</a>
                <a class="ghost-button button-link" href="{{ route('free-tool') }}">מה כלול בחינם</a>
            </div>
        </div>

        <div class="public-stage-visual" aria-hidden="true">
            <div class="public-device-shell public-device-shell-poster public-device-shell-services">
                <div class="public-stage-canvas public-stage-canvas-services">
                    <div class="public-stage-canvas-topline">
                        <span>Brndini</span>
                        <span>אחסון / SEO / צמיחה</span>
                    </div>
                    <div class="public-stage-canvas-copy">
                        <small>שכבה עסקית</small>
                        <strong>כאן עוברים משכבה טכנית לכל מה שמקדם את האתר קדימה.</strong>
                        <p>אחסון, SEO, קמפיינים, תחזוקה, שדרוג אתר ואוטומציות. פנייה עסקית מסודרת, לא טיקט תמיכה.</p>
                    </div>
                    <div class="public-stage-canvas-grid">
                        <article class="is-primary">
                            <span>תשתית</span>
                            <strong>אחסון ותחזוקה</strong>
                            <p>שרתים, גיבויים, יציבות ושקט תפעולי.</p>
                        </article>
                        <article>
                            <span>צמיחה</span>
                            <strong>SEO וקמפיינים</strong>
                            <p>יותר תנועה, יותר לידים, ויותר שליטה.</p>
                        </article>
                        <article>
                            <span>שדרוג</span>
                            <strong>אתר ואוטומציות</strong>
                            <p>שיפור אתר קיים ותהליכים שחוסכים עבודה.</p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="public-shell-section">
        <div class="section-heading">
            <p class="eyebrow">מתי זה מתאים</p>
            <h2>Brndini לא מחליפה את A11Y Bridge. היא נכנסת רק כשיש צורך עסקי רחב יותר.</h2>
        </div>

        <div class="public-dual-panel">
            <article class="public-decision-card">
                <p class="eyebrow">נשארים בכלי</p>
                <h3>אם מה שצריך הוא ווידג׳ט, קוד הטמעה, דשבורד והצהרה בסיסית.</h3>
                <ul class="compact-check-list">
                    <li>יש אתר קיים ומתוחזק</li>
                    <li>הצורך הוא תפעולי וטכני</li>
                    <li>אין כרגע צורך בשיווק, אחסון או שדרוג רחב</li>
                </ul>
            </article>
            <article class="public-decision-card public-decision-card-accent">
                <p class="eyebrow">ממשיכים ל־Brndini</p>
                <h3>אם צריך תשתית, צמיחה, תחזוקה או שיפור מקיף יותר סביב האתר.</h3>
                <ul class="compact-check-list">
                    <li>אחסון ומעבר תשתית</li>
                    <li>SEO, קמפיינים ודפי נחיתה</li>
                    <li>תחזוקה, שדרוג אתר ואוטומציות</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="public-shell-section public-shell-section-soft">
        <div class="section-heading">
            <p class="eyebrow">משפחות השירות</p>
            <h2>שלושה סוגי עבודה עיקריים, בלי קטלוג עמוס ובלי רעש.</h2>
        </div>

        <div class="public-shell-grid public-shell-grid-three">
            <article class="public-shell-card">
                <small>תשתית</small>
                <h3>אחסון ותחזוקה</h3>
                <p>שרתים, גיבויים, זמינות, תחזוקה שוטפת ושקט תפעולי.</p>
            </article>
            <article class="public-shell-card">
                <small>צמיחה</small>
                <h3>SEO, קמפיינים ודפי נחיתה</h3>
                <p>יותר תנועה, יותר לידים, ועמודים חדים יותר סביב המטרה העסקית.</p>
            </article>
            <article class="public-shell-card">
                <small>שדרוג</small>
                <h3>שיפור אתר ואוטומציות</h3>
                <p>שדרוג אתר קיים, חיבורי CRM ותהליכים שחוסכים עבודה ידנית.</p>
            </article>
        </div>
    </section>

    <section class="section-band section-band-alt" id="public-service-form">
        <div class="section-heading">
            <p class="eyebrow">פנייה עסקית</p>
            <h2>שיחה עסקית קצרה, לא טופס מכביד.</h2>
            <p class="hero-text">
                בוחרים כיוון, מוסיפים מסגרת ודרך חזרה, ומכניסים את הפנייה למרכז הלידים של Brndini.
            </p>
        </div>

        <section class="services-lead-shell public-services-shell">
            <section class="panel-card">
                <form class="stack-form lead-wizard-form" method="POST" action="{{ route('brndini.services.store') }}" data-flow-wizard>
                    @csrf
                    <input type="hidden" name="entry_point" value="{{ old('entry_point', request('entry', 'public-services')) }}">
                    <input type="hidden" name="utm_source" value="{{ old('utm_source', request('utm_source')) }}">
                    <input type="hidden" name="utm_medium" value="{{ old('utm_medium', request('utm_medium')) }}">
                    <input type="hidden" name="utm_campaign" value="{{ old('utm_campaign', request('utm_campaign')) }}">
                    <input type="hidden" name="referrer_url" value="{{ old('referrer_url', request('referrer_url', request()->headers->get('referer'))) }}">

                    <div class="flow-wizard-intro flow-wizard-intro-compact flow-wizard-intro-public">
                        <div class="flow-wizard-intro-copy">
                            <p class="eyebrow">פנייה עסקית קצרה</p>
                            <strong>מגדירים צורך, קצב ודרך חזרה. זה הכול.</strong>
                            <p class="signup-note">שלושה שלבים קצרים, בלי למלא “טופס” שמרגיש כמו עבודה.</p>
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
                                <strong>חזרה</strong>
                            </button>
                        </div>
                    </div>

                    <div class="flow-shell-grid flow-shell-grid-product">
                        <div class="flow-shell-main">
                            <div class="flow-progress-shell">
                                <div class="flow-progress-track" aria-hidden="true">
                                    <span class="flow-progress-fill" data-flow-progress-fill></span>
                                </div>
                                <div class="flow-progress-meta">
                                    <strong data-flow-progress-label>שלב 1 מתוך 3</strong>
                                    <span data-flow-progress-caption>מה השירות, מה האתר, ומה המטרה העסקית.</span>
                                </div>
                            </div>

                            <div class="flow-stage flow-stage-product {{ old('flow_step', '1') === '1' ? 'is-active' : '' }}" data-flow-stage="1" data-flow-caption="מה השירות, מה האתר, ומה המטרה העסקית.">
                                <div class="flow-stage-head">
                                    <strong>מה אתם צריכים כרגע?</strong>
                                    <p>מספיק לבחור שירות אחד, להוסיף אתר ולנסח מטרה קצרה.</p>
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

                                <label for="public_service_goal">מה אתה רוצה להשיג?</label>
                                <input id="public_service_goal" name="goal" type="text" value="{{ old('goal') }}" placeholder="למשל: לשפר מהירות, לגדול בתנועה או לעשות סדר בתשתית" required>

                                <div class="signup-actions-row">
                                    <button class="primary-button" type="button" data-flow-next>המשך</button>
                                </div>
                            </div>

                            <div class="flow-stage flow-stage-product {{ old('flow_step') === '2' ? 'is-active' : '' }}" data-flow-stage="2" data-flow-caption="זמן, דחיפות ותקציב עוזרים להבין איך נכון לפתוח את השיחה.">
                                <div class="flow-stage-head">
                                    <strong>נבין את המסגרת</strong>
                                    <p>פרטים קצרים שיעזרו לנו להבין קצב, תקציב ואופי העסק.</p>
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

                            <div class="flow-stage flow-stage-product {{ old('flow_step') === '3' ? 'is-active' : '' }}" data-flow-stage="3" data-flow-caption="כמה מילים על המצב היום ודרך החזרה המועדפת עליך.">
                                <div class="flow-stage-head">
                                    <strong>נסיים עם פרטי חזרה</strong>
                                    <p>עוד קצת הקשר, ואז הפנייה נכנסת ישירות למרכז הלידים.</p>
                                </div>

                                <label for="public_service_message">פרטים חשובים</label>
                                <textarea id="public_service_message" name="message" rows="6" placeholder="ספר בקצרה מה מצב האתר היום, מה מפריע, ומה היית רוצה שיקרה בחודש הקרוב." required>{{ old('message') }}</textarea>

                                <label for="public_service_contact">איך נוח שנחזור אליך?</label>
                                <select id="public_service_contact" name="preferred_contact" required>
                                    <option value="email" @selected(old('preferred_contact', 'email') === 'email')>אימייל</option>
                                    <option value="phone" @selected(old('preferred_contact') === 'phone')>טלפון</option>
                                    <option value="whatsapp" @selected(old('preferred_contact') === 'whatsapp')>ווטסאפ</option>
                                </select>

                                <label for="public_service_phone">טלפון / ווטסאפ לחזרה</label>
                                <input id="public_service_phone" name="contact_phone" type="text" value="{{ old('contact_phone') }}" placeholder="למשל: 050-123-4567">
                                <span class="meta-note">אם בחרת טלפון או ווטסאפ, חשוב להוסיף כאן מספר זמין.</span>

                                <div class="signup-actions-row">
                                    <button class="ghost-button" type="button" data-flow-prev>חזרה</button>
                                    <button class="primary-button" type="submit">שלח פנייה עסקית</button>
                                </div>
                            </div>
                        </div>

                        <aside class="flow-shell-aside flow-shell-aside-product">
                            <div class="flow-summary-card flow-summary-card-lead flow-summary-card-inline flow-summary-card-sticky flow-summary-card-product">
                                <strong>הפנייה שלך כרגע</strong>
                                <div class="flow-summary-grid">
                                    <div><span>שירות</span><strong data-flow-summary-target="service_type">אחסון וניהול שרת</strong></div>
                                    <div><span>מטרה</span><strong data-flow-summary-target="goal">נבנה יחד תוך כדי</strong></div>
                                    <div><span>זמן</span><strong data-flow-summary-target="timeframe">טרם הוגדר</strong></div>
                                    <div><span>חזרה</span><strong data-flow-summary-target="preferred_contact">אימייל</strong></div>
                                </div>
                            </div>

                            <div class="flow-support-card flow-support-card-product">
                                <p class="eyebrow">מה קורה אחרי השליחה</p>
                                <strong>הפנייה נכנסת למרכז הלידים של Brndini, לא למרכז התמיכה הטכנית.</strong>
                                <ul class="compact-check-list">
                                    <li>השירות והמטרה נשמרים יחד עם פרטי העסק</li>
                                    <li>אפשר לחזור אליך במייל, טלפון או ווטסאפ</li>
                                    <li>הפנייה נשארת מסודרת גם אם נרצה להמשיך לשלב הבא</li>
                                </ul>
                            </div>
                        </aside>
                    </div>
                </form>
            </section>

            <aside class="support-side-stack">
                <section class="domain-card public-side-note">
                    <h2>פנייה עסקית, לא תמיכה</h2>
                    <div class="domain-info-list">
                        <div class="domain-info-row">
                            <span>הצורך</span>
                            <strong>להבין התאמה עסקית, לא לפתור תקלה בווידג׳ט</strong>
                        </div>
                        <div class="domain-info-row">
                            <span>החזרה</span>
                            <strong>לפי הערוץ שנוח לך: מייל, טלפון או ווטסאפ</strong>
                        </div>
                        <div class="domain-info-row">
                            <span>ההמשך</span>
                            <strong>פתיחת שיחה, הצעה או התאמת שירות לפי המצב העסקי</strong>
                        </div>
                    </div>
                </section>
            </aside>
        </section>
    </section>
@endsection
