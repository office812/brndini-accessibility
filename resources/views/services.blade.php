@extends('layouts.app')

@php($title = 'שירותי Brndini | A11Y Bridge')
@php($domainLabel = parse_url($site->domain, PHP_URL_HOST) ?: $site->domain)
@php($selectedServiceType = old('service_type', request('service', array_key_first($serviceCatalog))))

@section('content')
    <section class="domain-shell">
        @include('partials.domain-sidebar', ['activeSection' => 'services'])

        <div class="domain-shell-main">
            <section class="domain-shell-header">
                <h1>שירותי Brndini</h1>
            </section>

            <section class="domain-card domain-hero-card">
                <div>
                    <p class="eyebrow">שירותים נוספים לעסק שלך</p>
                    <h2>{{ $site->site_name }}</h2>
                    <p class="panel-intro">הווידג׳ט נשאר חינמי, אבל אם תרצה עזרה עם אחסון, SEO, קמפיינים, תחזוקה או שדרוג אתר, מכאן אפשר להשאיר פנייה מסודרת. זה אזור שירותי Brndini, לא תמיכה טכנית של המערכת.</p>
                </div>

                <div class="billing-hero-meta">
                    <span class="status-pill is-neutral">דומיין: {{ $domainLabel }}</span>
                    <span class="status-pill is-neutral">פניות שירות: {{ $serviceLeadSummary['total'] }}</span>
                    <span class="status-pill {{ $serviceLeadSummary['new'] > 0 ? 'is-warn' : 'is-good' }}">חדשות: {{ $serviceLeadSummary['new'] }}</span>
                </div>
            </section>

            <section class="super-admin-kpi-grid super-admin-kpi-grid-compact">
                <article class="super-admin-kpi-card">
                    <span class="super-admin-kpi-icon">🚀</span>
                    <strong>{{ $serviceLeadSummary['total'] }}</strong>
                    <p>פניות עסקיות לאתר הזה</p>
                </article>
                <article class="super-admin-kpi-card">
                    <span class="super-admin-kpi-icon">📬</span>
                    <strong>{{ $serviceLeadSummary['new'] }}</strong>
                    <p>פניות חדשות שמחכות לטיפול</p>
                </article>
                <article class="super-admin-kpi-card">
                    <span class="super-admin-kpi-icon">🧾</span>
                    <strong>{{ $serviceLeadSummary['proposal'] }}</strong>
                    <p>פניות שנמצאות בשלב הצעה</p>
                </article>
                <article class="super-admin-kpi-card">
                    <span class="super-admin-kpi-icon">✅</span>
                    <strong>{{ $serviceLeadSummary['won'] }}</strong>
                    <p>פניות שנסגרו כלקוח</p>
                </article>
            </section>

            <section class="dashboard-workspace dashboard-workspace-inline domain-tab-workspace" data-dashboard-tabs>
                <div class="dashboard-tab-content">
                    <div class="dashboard-tab-nav domain-inline-tab-nav" aria-label="לשוניות שירותי Brndini">
                        <button class="dashboard-tab-button is-active" type="button" data-dashboard-tab-button="catalog">השירותים</button>
                        <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="request">פתח פנייה</button>
                        <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="requests">הפניות שלי</button>
                    </div>

                    <div class="dashboard-tab-panel is-active" data-dashboard-tab-panel="catalog">
                        <section class="plan-card-grid">
                            @foreach ($serviceCatalog as $serviceKey => $service)
                                <article class="plan-choice-card">
                                    <strong>{{ $service['label'] }}</strong>
                                    <p>{{ $service['description'] }}</p>
                                    <ul class="plan-choice-list">
                                        @foreach ($service['highlights'] as $highlight)
                                            <li>{{ $highlight }}</li>
                                        @endforeach
                                    </ul>
                                    <button class="primary-button" type="button" data-dashboard-tab-link="request" data-service-type="{{ $serviceKey }}">
                                        רוצה פרטים על {{ $service['label'] }}
                                    </button>
                                </article>
                            @endforeach
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="request">
                        <section class="support-grid">
                            <section class="domain-card support-form-card">
                                <div class="domain-card-head">
                                    <div>
                                        <h2>פתח פנייה לשירותי Brndini</h2>
                                        <p class="panel-intro">פנייה כאן מיועדת לשירותים עסקיים של Brndini כמו אחסון, שדרוג אתר, SEO, קמפיינים ותחזוקה. זו לא תמיכה טכנית של המערכת.</p>
                                    </div>
                                </div>

                                <form class="stack-form lead-wizard-form" method="POST" action="{{ route('dashboard.services.store', ['site' => $site->id]) }}" data-flow-wizard>
                                    @csrf
                                    <input type="hidden" name="site_id" value="{{ old('site_id', $site->id) }}">
                                    <input type="hidden" name="entry_point" value="{{ old('entry_point', request('entry', 'dashboard-services')) }}">
                                    <input type="hidden" name="flow_step" value="{{ old('flow_step', '1') }}" data-flow-step-input>

                                    <div class="flow-wizard-intro flow-wizard-intro-compact">
                                        <div>
                                            <p class="eyebrow">פנייה לשירות</p>
                                            <strong>מגדירים את השירות בכמה צעדים קצרים</strong>
                                            <p class="signup-note">הצורך, המסגרת העסקית ודרך החזרה. בלי בלגן ובלי טופס אינסופי.</p>
                                        </div>
                                        <div class="flow-wizard-stepper" aria-label="התקדמות בפניית שירות">
                                            <button class="flow-wizard-step {{ old('flow_step', '1') === '1' ? 'is-active' : '' }}" type="button" data-flow-step="1">
                                                <span>1</span>
                                                <strong>השירות</strong>
                                            </button>
                                            <button class="flow-wizard-step {{ old('flow_step') === '2' ? 'is-active' : '' }}" type="button" data-flow-step="2">
                                                <span>2</span>
                                                <strong>המסגרת</strong>
                                            </button>
                                            <button class="flow-wizard-step {{ old('flow_step') === '3' ? 'is-active' : '' }}" type="button" data-flow-step="3">
                                                <span>3</span>
                                                <strong>שליחה</strong>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flow-progress-shell">
                                        <div class="flow-progress-track" aria-hidden="true">
                                            <span class="flow-progress-fill" data-flow-progress-fill></span>
                                        </div>
                                        <div class="flow-progress-meta">
                                            <strong data-flow-progress-label>שלב 1 מתוך 3</strong>
                                            <span data-flow-progress-caption>מגדירים את השירות ואת המטרה, כדי להתחיל מכיוון ברור.</span>
                                        </div>
                                    </div>

                                    <div class="flow-summary-card flow-summary-card-lead flow-summary-card-inline">
                                        <strong>הפנייה של {{ $site->site_name }}</strong>
                                        <div class="flow-summary-grid">
                                            <div><span>שירות</span><strong data-flow-summary-target="service_type">{{ $serviceCatalog[$selectedServiceType]['label'] ?? 'טרם נבחר' }}</strong></div>
                                            <div><span>מטרה</span><strong data-flow-summary-target="goal">נגדיר יחד בצעד הראשון</strong></div>
                                            <div><span>זמן</span><strong data-flow-summary-target="timeframe">טרם הוגדר</strong></div>
                                            <div><span>חזרה</span><strong data-flow-summary-target="preferred_contact">אימייל</strong></div>
                                        </div>
                                    </div>

                                    <div class="flow-stage {{ old('flow_step', '1') === '1' ? 'is-active' : '' }}" data-flow-stage="1" data-flow-caption="מגדירים את השירות ואת המטרה, כדי להתחיל מכיוון ברור.">
                                        <div class="flow-stage-head">
                                            <strong>מה השירות שיקדם את האתר הזה?</strong>
                                            <p>נבחר כיוון ברור ונבין בקצרה מה היית רוצה להשיג.</p>
                                        </div>

                                        <label for="service_type">איזה שירות מעניין אותך?</label>
                                        <select id="service_type" name="service_type" required>
                                            @foreach ($serviceCatalog as $serviceKey => $service)
                                                <option value="{{ $serviceKey }}" @selected($selectedServiceType === $serviceKey)>{{ $service['label'] }}</option>
                                            @endforeach
                                        </select>

                                        <label for="service_goal">מה המטרה שלך?</label>
                                        <input id="service_goal" name="goal" type="text" value="{{ old('goal') }}" placeholder="למשל: לשפר מהירות, להעלות לידים, להעביר לאחסון יציב" required>

                                        <div class="signup-actions-row">
                                            <button class="primary-button" type="button" data-flow-next>המשך</button>
                                        </div>
                                    </div>

                                    <div class="flow-stage {{ old('flow_step') === '2' ? 'is-active' : '' }}" data-flow-stage="2" data-flow-caption="קצב, תקציב ודחיפות עוזרים ל-Brndini להבין איך נכון להמשיך.">
                                        <div class="flow-stage-head">
                                            <strong>נבין את המסגרת העסקית</strong>
                                            <p>כמה פרטים שיעזרו לנו להבין קצב, תקציב ודחיפות.</p>
                                        </div>

                                        <div class="support-form-row">
                                            <div>
                                                <label for="service_business_type">איזה סוג עסק אתה?</label>
                                                <select id="service_business_type" name="business_type">
                                                    <option value="">בחר סוג עסק</option>
                                                    @foreach ($serviceLeadBusinessTypeLabels as $businessKey => $businessLabel)
                                                        <option value="{{ $businessKey }}" @selected(old('business_type') === $businessKey)>{{ $businessLabel }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label for="service_team_size">מה גודל הצוות?</label>
                                                <select id="service_team_size" name="team_size">
                                                    <option value="">בחר גודל צוות</option>
                                                    @foreach ($serviceLeadTeamSizeLabels as $teamKey => $teamLabel)
                                                        <option value="{{ $teamKey }}" @selected(old('team_size') === $teamKey)>{{ $teamLabel }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="support-form-row">
                                            <div>
                                                <label for="service_timeframe">מתי תרצה להתחיל?</label>
                                                <select id="service_timeframe" name="timeframe">
                                                    <option value="">בחר טווח זמן</option>
                                                    @foreach ($serviceLeadTimeframeLabels as $timeframeKey => $timeframeLabel)
                                                        <option value="{{ $timeframeKey }}" @selected(old('timeframe') === $timeframeKey)>{{ $timeframeLabel }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label for="service_budget_range">מה סדר הגודל התקציבי?</label>
                                                <select id="service_budget_range" name="budget_range">
                                                    <option value="">בחר תקציב משוער</option>
                                                    @foreach ($serviceLeadBudgetLabels as $budgetKey => $budgetLabel)
                                                        <option value="{{ $budgetKey }}" @selected(old('budget_range') === $budgetKey)>{{ $budgetLabel }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="support-form-row">
                                            <div>
                                                <label for="service_urgency_level">מה רמת הדחיפות?</label>
                                                <select id="service_urgency_level" name="urgency_level">
                                                    <option value="">בחר רמת דחיפות</option>
                                                    @foreach ($serviceLeadUrgencyLabels as $urgencyKey => $urgencyLabel)
                                                        <option value="{{ $urgencyKey }}" @selected(old('urgency_level') === $urgencyKey)>{{ $urgencyLabel }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label for="service_callback_window">מתי הכי נוח לחזור אליך?</label>
                                                <select id="service_callback_window" name="callback_window">
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

                                    <div class="flow-stage {{ old('flow_step') === '3' ? 'is-active' : '' }}" data-flow-stage="3" data-flow-caption="עוד קצת הקשר ודרך חזרה, ואז הפנייה נכנסת למרכז הלידים.">
                                        <div class="flow-stage-head">
                                            <strong>נסיים את הפנייה ונשלח</strong>
                                            <p>עוד קצת הקשר ודרך חזרה, ואז זה יוצא ישר למרכז הלידים.</p>
                                        </div>

                                        <label for="service_message">פרטים חשובים</label>
                                        <textarea id="service_message" name="message" rows="7" placeholder="ספר בקצרה מה העסק צריך, מה מצב האתר היום, ומה תרצה להשיג." required>{{ old('message') }}</textarea>

                                        <label for="service_preferred_contact">איך נוח לך שנחזור?</label>
                                        <select id="service_preferred_contact" name="preferred_contact" required>
                                            @foreach ($servicePreferredContactLabels as $contactKey => $contactLabel)
                                                <option value="{{ $contactKey }}" @selected(old('preferred_contact', 'email') === $contactKey)>{{ $contactLabel }}</option>
                                            @endforeach
                                        </select>

                                        <label for="service_contact_phone">טלפון / ווטסאפ לחזרה</label>
                                        <input id="service_contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone') }}" placeholder="למשל: 050-123-4567">
                                        <span class="meta-note">אם נוח לך שיחזרו בטלפון או בווטסאפ, צריך להשאיר כאן מספר זמין.</span>

                                        <div class="flow-summary-card flow-summary-card-compact">
                                            <strong>מה קורה אחרי השליחה</strong>
                                            <p class="signup-note">הפנייה תיכנס ישירות למרכז הלידים של Brndini. זו לא תמיכה טכנית של המערכת, אלא פנייה עסקית על השירות שביקשת.</p>
                                        </div>

                                        <div class="support-form-actions">
                                            <button class="ghost-button" type="button" data-flow-prev>חזרה</button>
                                            <button class="primary-button" type="submit">שלח פנייה לשירות</button>
                                            <span class="meta-note">פניות כאן מועברות לאזור הלידים של Brndini, בנפרד מהתמיכה הטכנית של המערכת.</span>
                                        </div>
                                    </div>
                                </form>
                            </section>

                            <aside class="support-side-stack">
                                <section class="domain-card">
                                    <h2>למה להשאיר פנייה כאן?</h2>
                                    <div class="domain-info-list">
                                        <div class="domain-info-row">
                                            <span>הקשר אתר</span>
                                            <strong>{{ $site->site_name }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>דומיין</span>
                                            <strong>{{ $domainLabel }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>פניות שנשלחו</span>
                                            <strong>{{ $serviceLeadSummary['total'] }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>עם פרופיל עסקי</span>
                                            <strong>{{ $serviceLeadSummary['qualifiedBusinesses'] }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>צריכים להתחיל מהר</span>
                                            <strong>{{ $serviceLeadSummary['urgent'] }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>עם תקציב מוגדר</span>
                                            <strong>{{ $serviceLeadSummary['budgeted'] }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>דורשים טיפול מהיר</span>
                                            <strong>{{ $serviceLeadSummary['needsFastReply'] }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>לחזור אליהם היום</span>
                                            <strong>{{ $serviceLeadSummary['scheduledToday'] }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>גישה מוקדמת</span>
                                            <strong>אפשר גם להתעניין בכלי Brndini הבאים</strong>
                                        </div>
                                    </div>
                                </section>
                            </aside>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="requests">
                        <section class="domain-card">
                            <div class="domain-card-head">
                                <div>
                                    <h2>הפניות העסקיות של האתר הזה</h2>
                                    <p class="panel-intro">כאן תראה רק פניות לשירותי Brndini, כמו אחסון, SEO, קמפיינים או שדרוג אתר. פניות טכניות למערכת נשארות במרכז התמיכה.</p>
                                </div>
                            </div>

                            @if ($serviceLeads->isEmpty())
                                <div class="support-empty-state">
                                    <strong>עדיין לא נשלחה פנייה לשירות</strong>
                                    <p>ברגע שתפתח פנייה ראשונה, היא תופיע כאן עם השירות שביקשת, דרך החזרה המועדפת והזמן האחרון שבו עודכנה.</p>
                                </div>
                            @else
                                <div class="super-admin-toolbar" data-filter-root>
                                    <div class="super-admin-toolbar-field">
                                        <label for="site_service_leads_search">חיפוש פנייה</label>
                                        <input id="site_service_leads_search" type="search" placeholder="חפש לפי שירות, מטרה או תוכן" data-filter-search>
                                    </div>
                                    <div class="super-admin-toolbar-field">
                                        <label for="site_service_leads_service">סינון שירות</label>
                                        <select id="site_service_leads_service" data-filter-field="service">
                                            <option value="">כל השירותים</option>
                                            @foreach ($serviceCatalog as $serviceKey => $service)
                                                <option value="{{ $serviceKey }}">{{ $service['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="super-admin-toolbar-field">
                                        <label for="site_service_leads_status">סינון סטטוס</label>
                                        <select id="site_service_leads_status" data-filter-field="status">
                                            <option value="">כל הסטטוסים</option>
                                            @foreach ($serviceLeadStatusLabels as $statusKey => $statusLabel)
                                                <option value="{{ $statusKey }}">{{ $statusLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="support-ticket-list">
                                    @foreach ($serviceLeads as $lead)
                                        <article
                                            class="support-ticket-card"
                                            data-filter-item
                                            data-filter-search-text="{{ Str::lower(($serviceCatalog[$lead->service_type]['label'] ?? $lead->service_type) . ' ' . ($lead->goal ?? '') . ' ' . ($lead->message ?? '') . ' ' . ($lead->business_type_label ?? '') . ' ' . ($lead->team_size_label ?? '') . ' ' . ($lead->timeframe_label ?? '') . ' ' . ($lead->budget_range_label ?? '') . ' ' . ($lead->urgency_level_label ?? '') . ' ' . ($lead->callback_window_label ?? '')) }}"
                                            data-filter-service="{{ $lead->service_type }}"
                                            data-filter-status="{{ $lead->status }}"
                                        >
                                            <div class="support-ticket-head">
                                                <div>
                                                    <p class="support-ticket-code">{{ $lead->reference_code }}</p>
                                                    <h3>{{ $serviceCatalog[$lead->service_type]['label'] ?? $lead->service_type }}</h3>
                                                </div>

                                                <div class="support-ticket-pills">
                                                    <span class="status-pill is-neutral">{{ $lead->source_label ?? 'פנייה עסקית' }}</span>
                                                    <span class="status-pill is-neutral">{{ $lead->entry_label ?? 'כניסה כללית' }}</span>
                                                    @if (!empty($lead->marketing_label))
                                                        <span class="status-pill is-neutral">UTM: {{ $lead->marketing_label }}</span>
                                                    @endif
                                                    <span class="status-pill {{ $lead->opportunity_tone === 'good' ? 'is-good' : ($lead->opportunity_tone === 'warn' ? 'is-warn' : 'is-neutral') }}">{{ $lead->opportunity_label }}</span>
                                                    <span class="status-pill {{ in_array($lead->status, ['won', 'qualified'], true) ? 'is-good' : ($lead->status === 'closed' ? 'is-neutral' : 'is-warn') }}">
                                                        {{ $serviceLeadStatusLabels[$lead->status] ?? $lead->status }}
                                                    </span>
                                                    <span class="status-pill is-good">{{ $servicePreferredContactLabels[$lead->preferred_contact] ?? $lead->preferred_contact }}</span>
                                                </div>
                                            </div>

                                                <p class="support-ticket-meta">
                                                    {{ $lead->goal }} · {{ $lead->last_activity_label }}
                                                </p>
                                                <div class="lead-intel-row">
                                                    <span class="status-pill is-neutral">עסק: {{ $lead->business_type_label }}</span>
                                                    <span class="status-pill is-neutral">צוות: {{ $lead->team_size_label }}</span>
                                                </div>
                                                <div class="lead-intel-row">
                                                    <span class="status-pill is-neutral">זמן: {{ $lead->timeframe_label }}</span>
                                                    <span class="status-pill is-neutral">תקציב: {{ $lead->budget_range_label }}</span>
                                                </div>
                                                <div class="lead-intel-row">
                                                    <span class="status-pill is-neutral">דחיפות: {{ $lead->urgency_level_label }}</span>
                                                    <span class="status-pill is-neutral">חזרה: {{ $lead->callback_window_label }}</span>
                                                </div>

                                                @if (!empty($lead->contact_phone))
                                                    <p class="support-ticket-meta">טלפון לחזרה: {{ $lead->contact_phone }}</p>
                                            @elseif (!empty($lead->missing_preferred_contact_detail))
                                                <p class="support-ticket-meta">נבחר ערוץ חזרה טלפוני, אבל עדיין חסר מספר.</p>
                                            @endif

                                            @if (!empty($lead->referrer_host))
                                                <p class="support-ticket-meta">הגיע דרך: {{ $lead->referrer_host }}</p>
                                            @endif

                                            <p class="support-ticket-meta">איכות פנייה: {{ $lead->opportunity_label }} · ציון {{ $lead->opportunity_score }}/100</p>

                                            <p class="support-ticket-message">{{ $lead->message }}</p>
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                        </section>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
