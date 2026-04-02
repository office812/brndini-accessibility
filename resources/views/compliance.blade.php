@extends('layouts.app')

@php($title = 'הצהרת נגישות | A11Y Bridge')

@section('content')
    <section class="domain-shell">
        @include('partials.domain-sidebar', ['activeSection' => 'compliance'])

        <div class="domain-shell-main">
            <section class="domain-shell-header">
                <h1>ביקורות, התראות וציות</h1>
            </section>

            <section class="domain-card domain-hero-card">
                <div>
                    <p class="eyebrow">מרכז ציות</p>
                    <h2>ציון {{ $auditSnapshot['score'] }} · {{ $lastAuditedLabel }}</h2>
                    <p class="panel-intro">{{ $auditSnapshot['summary'] }}</p>
                </div>

                <form method="POST" action="{{ route('dashboard.compliance.audit', ['site' => $site->id]) }}">
                    @csrf
                    <input type="hidden" name="site_id" value="{{ $site->id }}">
                    <button class="primary-button" type="submit">הרץ בדיקה חדשה</button>
                </form>
            </section>

            @include('partials.service-recommendations-panel', [
                'heading' => 'רוצה לקחת את האתר צעד קדימה מעבר לכלי החינמי?',
                'intro' => 'אם מתוך הבדיקות עולה שהאתר עצמו צריך שדרוג, תחזוקה, שיפור מהירות או קידום, אפשר להשאיר מכאן פנייה עסקית נפרדת ל־Brndini.',
            ])

            <section class="dashboard-workspace dashboard-workspace-inline domain-tab-workspace" data-dashboard-tabs>
                <div class="dashboard-tab-content">
                    <div class="dashboard-tab-nav domain-inline-tab-nav" aria-label="לשוניות ציות ובקרה">
                        <button class="dashboard-tab-button is-active" type="button" data-dashboard-tab-button="audit-overview">סקירת בדיקה</button>
                        <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="alerts">התראות</button>
                        <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="statement-builder">יוצר הצהרה</button>
                        <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="proof">ערכת הוכחה</button>
                    </div>

                    <div class="dashboard-tab-panel is-active" data-dashboard-tab-panel="audit-overview">
                        @unless($auditActionsAvailable)
                            <section class="domain-card">
                                <p class="panel-intro">השרת הזה עדיין בלי עדכון המסד החדש, אבל אפשר כבר להריץ בדיקה. התוצאה תישמר זמנית עד שה־migration יושלם בשרת.</p>
                            </section>
                        @endunless

                        <section class="status-grid">
                            <article class="portal-stat-card">
                                <span class="meta-label">סטטוס בדיקה</span>
                                <strong>{{ $auditSnapshot['status'] }}</strong>
                                <p>{{ $openAlertsCount }} התראות פתוחות</p>
                            </article>
                            <article class="portal-stat-card">
                                <span class="meta-label">הצהרת נגישות</span>
                                <strong>{{ $statementStatus === 'connected' ? 'מחוברת' : 'חסרה' }}</strong>
                                <p>{{ $statementStatus === 'connected' ? 'זמינה מהווידג׳ט, מהחשבון ומקישור ציבורי' : 'אפשר ליצור אותה כאן בתהליך מונחה' }}</p>
                            </article>
                            <article class="portal-stat-card">
                                <span class="meta-label">רישיון</span>
                                <strong>{{ $licenseStatus === 'active' ? 'פעיל' : 'לא פעיל' }}</strong>
                                <p>{{ $licenseStatus === 'active' ? 'הווידג׳ט רשאי להיטען' : 'באתר יוצג כפתור אדום להפעלת רישיון' }}</p>
                            </article>
                        </section>

                        <section class="domain-card" id="audit-report">
                            <h2>בדיקות פתוחות</h2>
                            <div class="audit-check-list">
                                @foreach ($auditChecks as $check)
                                    <article class="audit-check-card audit-check-{{ $check['status'] }}">
                                        <div>
                                            <strong>{{ $check['label'] }}</strong>
                                            <p>{{ $check['detail'] }}</p>
                                        </div>
                                        <span class="status-pill {{ $check['status'] === 'pass' ? 'is-good' : ($check['status'] === 'warn' ? 'is-warn' : 'is-neutral') }}">
                                            {{ $check['status'] === 'pass' ? 'תקין' : ($check['status'] === 'warn' ? 'מעקב' : 'טיפול') }}
                                        </span>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="alerts">
                        <section class="domain-card" id="remediation-report">
                            <div class="domain-card-head">
                                <div>
                                    <h2>התראות פעילות</h2>
                                    <p class="panel-intro">התראות מוצגות לפי ההעדפות שסימנת למטה. אם הכול תקין, החלק הזה יישאר שקט יותר.</p>
                                </div>
                            </div>

                            @if ($openAlertsCount === 0)
                                <p class="panel-intro">כרגע אין התראות פתוחות. זה אומר שהאתר במצב יציב יחסית לפי ההגדרות הקיימות.</p>
                            @else
                                <div class="alert-list">
                                    @foreach ($openAlerts as $alert)
                                        <article class="alert-card alert-{{ $alert['severity'] }}">
                                            <strong>{{ $alert['title'] }}</strong>
                                            <p>{{ $alert['detail'] }}</p>
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                        </section>

                        <section class="domain-card" id="impact-report">
                            <h2>הגדרות התראות</h2>
                            <form class="stack-form" method="POST" action="{{ route('dashboard.compliance.alerts', ['site' => $site->id]) }}">
                                @csrf
                                <input type="hidden" name="site_id" value="{{ $site->id }}">

                                <div class="toggle-grid">
                                    <label class="toggle-row">
                                        <input type="hidden" name="alerts[license]" value="0">
                                        <input type="checkbox" name="alerts[license]" value="1" @checked($alertSettings['license'])>
                                        <span>התראת רישיון לא פעיל</span>
                                    </label>
                                    <label class="toggle-row">
                                        <input type="hidden" name="alerts[statement]" value="0">
                                        <input type="checkbox" name="alerts[statement]" value="1" @checked($alertSettings['statement'])>
                                        <span>התראת הצהרה חסרה</span>
                                    </label>
                                    <label class="toggle-row">
                                        <input type="hidden" name="alerts[audit]" value="0">
                                        <input type="checkbox" name="alerts[audit]" value="1" @checked($alertSettings['audit'])>
                                        <span>התראת בדיקה לא עדכנית</span>
                                    </label>
                                    <label class="toggle-row">
                                        <input type="hidden" name="alerts[sync]" value="0">
                                        <input type="checkbox" name="alerts[sync]" value="1" @checked($alertSettings['sync'])>
                                        <span>התראת שינויים מאז הבדיקה</span>
                                    </label>
                                </div>

                                @unless($alertSettingsAvailable)
                                    <p class="panel-intro">השרת הזה עדיין בלי עדכון המסד החדש, אבל אפשר כבר לשמור התראות. ההגדרות יישמרו זמנית עד שה־migration יושלם בשרת.</p>
                                @endunless

                                <button class="primary-button" type="submit">שמור התראות</button>
                            </form>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="statement-builder">
                        <section class="domain-card statement-builder-shell">
                            <div class="statement-builder-head">
                                <div>
                                    <p class="eyebrow">יוצר הצהרת נגישות</p>
                                    <h2>תהליך מונחה, קל ונעים ליצירת ההצהרה</h2>
                                    <p class="panel-intro">עונים על כמה שאלות קצרות, בוחרים אפשרויות, ובסוף מקבלים ניסוח מוכן עם קישור ציבורי שאפשר לחבר לווידג׳ט ולחשבון.</p>
                                </div>

                                <div class="statement-builder-actions">
                                    @if ($statementUrl)
                                        <a class="secondary-button" href="{{ $statementUrl }}" target="_blank" rel="noreferrer">פתח הצהרה</a>
                                    @endif
                                    @if ($statementUrl)
                                        <button class="copy-button" type="button" data-copy-target="statement-public-url">העתק קישור</button>
                                    @endif
                                </div>
                            </div>

                            @if ($statementUrl)
                                <div class="domain-code-block compact-code-block">
                                    <code id="statement-public-url">{{ $statementUrl }}</code>
                                </div>
                            @endif

                            <div class="statement-builder-grid" data-widget-pane-root>
                                <form class="statement-builder-form" method="POST" action="{{ route('dashboard.compliance.statement', ['site' => $site->id]) }}">
                                    @csrf
                                    <input type="hidden" name="site_id" value="{{ $site->id }}">

                                    <div class="statement-stepper">
                                        <button class="statement-step is-active" type="button" data-widget-pane-button="statement-org">1. הארגון</button>
                                        <button class="statement-step" type="button" data-widget-pane-button="statement-contact">2. קשר ומשוב</button>
                                        <button class="statement-step" type="button" data-widget-pane-button="statement-testing">3. בדיקות והתאמות</button>
                                        <button class="statement-step" type="button" data-widget-pane-button="statement-notes">4. חריגים והערות</button>
                                    </div>

                                    <div class="statement-pane is-active" data-widget-pane="statement-org">
                                        <div class="stack-form compact-form-grid">
                                            <label>
                                                <span>שם הארגון / העסק</span>
                                                <input name="statement[organization_name]" type="text" value="{{ old('statement.organization_name', $statementBuilder['organization_name']) }}" placeholder="למשל Brndini">
                                            </label>
                                            <label>
                                                <span>סוג הארגון</span>
                                                <select name="statement[organization_type]">
                                                    @foreach ($statementOrganizationTypes as $typeValue => $typeLabel)
                                                        <option value="{{ $typeValue }}" @selected(old('statement.organization_type', $statementBuilder['organization_type']) === $typeValue)>{{ $typeLabel }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                            <label class="field-span-2">
                                                <span>מה האתר מאפשר או מציג?</span>
                                                <input name="statement[website_purpose]" type="text" value="{{ old('statement.website_purpose', $statementBuilder['website_purpose']) }}" placeholder="מידע, שירותים דיגיטליים, מכירה, יצירת קשר">
                                            </label>
                                            <label>
                                                <span>היקף השירות</span>
                                                <select name="statement[service_scope]">
                                                    @foreach ($statementServiceScopes as $scopeValue => $scopeLabel)
                                                        <option value="{{ $scopeValue }}" @selected(old('statement.service_scope', $statementBuilder['service_scope']) === $scopeValue)>{{ $scopeLabel }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                            <label>
                                                <span>אחראי נגישות / איש קשר</span>
                                                <input name="statement[accessibility_owner]" type="text" value="{{ old('statement.accessibility_owner', $statementBuilder['accessibility_owner']) }}" placeholder="שם מלא">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="statement-pane" data-widget-pane="statement-contact" hidden>
                                        <div class="stack-form compact-form-grid">
                                            <label>
                                                <span>אימייל ליצירת קשר</span>
                                                <input name="statement[contact_email]" type="email" value="{{ old('statement.contact_email', $statementBuilder['contact_email']) }}" placeholder="office@example.com">
                                            </label>
                                            <label>
                                                <span>טלפון</span>
                                                <input name="statement[contact_phone]" type="text" value="{{ old('statement.contact_phone', $statementBuilder['contact_phone']) }}" placeholder="050-0000000">
                                            </label>
                                            <label class="field-span-2">
                                                <span>קישור לטופס פנייה</span>
                                                <input name="statement[contact_form_url]" type="text" value="{{ old('statement.contact_form_url', $statementBuilder['contact_form_url']) }}" placeholder="https://your-site.com/contact">
                                            </label>
                                            <label>
                                                <span>זמן מענה רצוי</span>
                                                <select name="statement[response_time]">
                                                    @foreach ($statementResponseTimes as $responseValue => $responseLabel)
                                                        <option value="{{ $responseValue }}" @selected(old('statement.response_time', $statementBuilder['response_time']) === $responseValue)>{{ $responseLabel }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                            <label>
                                                <span>תאריך עדכון אחרון</span>
                                                <input name="statement[last_reviewed_at]" type="date" value="{{ old('statement.last_reviewed_at', $statementBuilder['last_reviewed_at']) }}">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="statement-pane" data-widget-pane="statement-testing" hidden>
                                        <div class="statement-option-grid">
                                            <label class="toggle-row">
                                                <input type="hidden" name="statement[testing_manual_keyboard]" value="0">
                                                <input type="checkbox" name="statement[testing_manual_keyboard]" value="1" @checked(old('statement.testing_manual_keyboard', $statementBuilder['testing_manual_keyboard']))>
                                                <span>בדיקות מקלדת וזרימת פוקוס</span>
                                            </label>
                                            <label class="toggle-row">
                                                <input type="hidden" name="statement[testing_screen_reader]" value="0">
                                                <input type="checkbox" name="statement[testing_screen_reader]" value="1" @checked(old('statement.testing_screen_reader', $statementBuilder['testing_screen_reader']))>
                                                <span>בדיקות עם קוראי מסך</span>
                                            </label>
                                            <label class="toggle-row">
                                                <input type="hidden" name="statement[testing_automation]" value="0">
                                                <input type="checkbox" name="statement[testing_automation]" value="1" @checked(old('statement.testing_automation', $statementBuilder['testing_automation']))>
                                                <span>בדיקות אוטומטיות וסריקה</span>
                                            </label>
                                            <label class="toggle-row">
                                                <input type="hidden" name="statement[testing_content_review]" value="0">
                                                <input type="checkbox" name="statement[testing_content_review]" value="1" @checked(old('statement.testing_content_review', $statementBuilder['testing_content_review']))>
                                                <span>סקירה ידנית של תוכן, קישורים וטפסים</span>
                                            </label>
                                            <label class="toggle-row">
                                                <input type="hidden" name="statement[physical_location_accessible]" value="0">
                                                <input type="checkbox" name="statement[physical_location_accessible]" value="1" @checked(old('statement.physical_location_accessible', $statementBuilder['physical_location_accessible']))>
                                                <span>יש גם הסדרי נגישות פיזיים</span>
                                            </label>
                                            <label class="toggle-row">
                                                <input type="hidden" name="statement[accessible_parking]" value="0">
                                                <input type="checkbox" name="statement[accessible_parking]" value="1" @checked(old('statement.accessible_parking', $statementBuilder['accessible_parking']))>
                                                <span>כולל חניה נגישה</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="statement-pane" data-widget-pane="statement-notes" hidden>
                                        <div class="stack-form">
                                            <label>
                                                <span>הסדרים נוספים או התאמות מיוחדות</span>
                                                <textarea name="statement[additional_arrangements]" rows="4" placeholder="למשל: אפשר לפנות מראש לקבלת עזרה מותאמת, ליווי טלפוני, או תיאום נגישות נוסף.">{{ old('statement.additional_arrangements', $statementBuilder['additional_arrangements']) }}</textarea>
                                            </label>
                                            <label>
                                                <span>מגבלות ידועות</span>
                                                <textarea name="statement[known_limitations]" rows="5" placeholder="אם יש אזורים באתר שעדיין דורשים שיפור, זה המקום לנסח אותם בשפה רגועה וברורה.">{{ old('statement.known_limitations', $statementBuilder['known_limitations']) }}</textarea>
                                            </label>
                                            <label>
                                                <span>הערה נוספת</span>
                                                <textarea name="statement[additional_note]" rows="4" placeholder="כל ניסוח נוסף שחשוב לך שיופיע בסוף ההצהרה.">{{ old('statement.additional_note', $statementBuilder['additional_note']) }}</textarea>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="statement-submit-bar">
                                        <button class="primary-button" type="submit">שמור ויצר הצהרה</button>
                                    </div>
                                </form>

                                <aside class="statement-preview-card">
                                    <p class="eyebrow">תצוגה מקדימה</p>
                                    <h3>{{ $statementPreview['title'] }}</h3>
                                    <p class="panel-intro">{{ $statementPreview['summary'] }}</p>

                                    <div class="statement-badge-row">
                                        @foreach ($statementPreview['badges'] as $badge)
                                            <span class="status-pill is-neutral">{{ $badge }}</span>
                                        @endforeach
                                    </div>

                                    <div class="statement-preview-scroll" id="statement-preview-text">
                                        @foreach ($statementPreview['sections'] as $section)
                                            <article class="statement-preview-section">
                                                <strong>{{ $section['title'] }}</strong>
                                                <p>{{ $section['body'] }}</p>
                                            </article>
                                        @endforeach
                                    </div>

                                    <div class="statement-preview-meta">
                                        <span>עודכן לאחרונה: {{ $statementPreview['last_reviewed_label'] }}</span>
                                        <button class="copy-button" type="button" data-copy-target="statement-preview-text">העתק נוסח</button>
                                    </div>
                                </aside>
                            </div>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="proof">
                        <section class="domain-card" id="proof-toolkit">
                            <h2>ערכת הוכחת מאמץ</h2>
                            <ul class="check-list">
                                <li>לכל אתר יש ציון בדיקה נפרד והתראות נפרדות, לא מצב רוחבי לכל החשבון.</li>
                                <li>אפשר לראות מתי רצה בדיקה לאחרונה והאם בוצעו שינויים מאז.</li>
                                <li>המערכת מבהירה מה מכוסה בווידג׳ט ומה עדיין דורש remediation ובדיקות ידניות.</li>
                            </ul>
                        </section>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
