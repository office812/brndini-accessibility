@extends('layouts.app')

@section('content')
    <section class="domain-shell super-admin-shell">
        <div class="domain-shell-main super-admin-main">
            <section class="domain-shell-header">
                <h1>מרכז סופר־אדמין</h1>
            </section>

            @unless($platformReadiness['ready'])
                <section class="alert-strip">
                    <strong>מצב שרת:</strong>
                    <span>{{ $platformReadiness['summary'] }}</span>
                </section>
            @endunless

            <section class="dashboard-workspace dashboard-workspace-inline super-admin-workspace" data-dashboard-tabs>
                <aside class="dashboard-tab-rail super-admin-rail">
                    <div class="licenses-sidebar-block">
                        <h2>סופר־אדמין</h2>
                        <nav class="licenses-product-nav" aria-label="ניווט סופר־אדמין">
                            <div class="licenses-product-group is-current">
                                <a class="is-current" href="{{ route('dashboard.super-admin') }}">
                                    <span class="licenses-product-icon">★</span>
                                    <span>מרכז שליטה</span>
                                </a>

                                <div class="licenses-product-subnav dashboard-tab-nav" aria-label="תתי עמודים של מרכז סופר־אדמין">
                                    <button class="dashboard-tab-button is-active" type="button" data-dashboard-tab-button="overview">סקירה</button>
                                    <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="tracking">קודי מעקב</button>
                                    <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="users">משתמשים</button>
                                    <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="sites">אתרים</button>
                                    <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="support">תמיכה</button>
                                    <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="leads">לידים</button>
                                </div>
                            </div>
                        </nav>
                    </div>

                    <div class="licenses-sidebar-block licenses-sidebar-help">
                        <span class="meta-label">גישה מלאה למערכת</span>
                        <h3>office@brndini.co.il</h3>
                        <p>רק החשבון הזה רואה את אזור הסופר־אדמין ויכול לנהל תמיכה, אתרים וקודי מעקב גלובליים.</p>
                        <div class="mini-status-list">
                            <span class="status-pill is-neutral">משתמשים: {{ $adminSummary['users'] }}</span>
                            <span class="status-pill is-neutral">אתרים: {{ $adminSummary['sites'] }}</span>
                            <span class="status-pill {{ $adminSummary['tickets_open'] > 0 ? 'is-warn' : 'is-good' }}">פניות פתוחות: {{ $adminSummary['tickets_open'] }}</span>
                            <span class="status-pill is-good">אתרים פעילים: {{ $adminSummary['active_sites'] }}</span>
                            <span class="status-pill is-neutral">לידים: {{ $adminSummary['service_leads'] }}</span>
                        </div>
                    </div>
                </aside>

                <div class="dashboard-tab-content">
                    <div class="dashboard-tab-panel is-active" data-dashboard-tab-panel="overview">
                        <section class="super-admin-hero-card">
                            <div class="super-admin-hero-copy">
                                <p class="eyebrow">שליטה מערכתית מלאה</p>
                                <h2>מרכז תפעול, תמיכה, אתרים ומשתמשים במקום אחד</h2>
                                <p class="panel-intro">האזור הזה מרכז את תמונת המצב של כל המערכת: לקוחות, רישיונות, הטמעות, טיקטים פתוחים וקודי מעקב גלובליים. הוא צריך לעזור לך לפתור בעיות מהר, לא לגרום לגלילה מיותרת.</p>
                                <div class="super-admin-hero-actions">
                                    <button class="primary-button" type="button" data-dashboard-tab-link="support">פתח מרכז תמיכה</button>
                                    <button class="secondary-button" type="button" data-dashboard-tab-link="tracking">ערוך קודי מעקב</button>
                                </div>
                            </div>

                            <div class="super-admin-health-card">
                                <h3>מצב מערכת</h3>
                                <div class="domain-info-list">
                                    @foreach ($platformReadiness['checks'] as $label => $ready)
                                        <div class="domain-info-row">
                                            <span>{{ $label }}</span>
                                            <strong class="{{ $ready ? 'super-admin-good' : 'super-admin-warn' }}">{{ $ready ? 'מוכן' : 'במצב גיבוי' }}</strong>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>

                        <section class="super-admin-kpi-grid">
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">👥</span>
                                <strong>{{ $adminSummary['users'] }}</strong>
                                <p>משתמשים פעילים במערכת</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🌐</span>
                                <strong>{{ $adminSummary['sites'] }}</strong>
                                <p>אתרים ורישיונות</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">✅</span>
                                <strong>{{ $adminSummary['active_sites'] }}</strong>
                                <p>אתרים פעילים</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🎫</span>
                                <strong>{{ $adminSummary['tickets_open'] }}</strong>
                                <p>פניות פתוחות לטיפול</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">⚡</span>
                                <strong>{{ $adminSummary['service_leads_needing_action'] }}</strong>
                                <p>לידים שדורשים טיפול עכשיו</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">📅</span>
                                <strong>{{ $adminSummary['service_leads_due_today'] }}</strong>
                                <p>חזרות שתוכננו להיום</p>
                            </article>
                        </section>

                        <section class="super-admin-content-grid">
                            <div class="super-admin-main-stack">
                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">משתמשים ואתרים</p>
                                            <h2>פעילות אחרונה של לקוחות</h2>
                                        </div>
                                        <button class="secondary-button" type="button" data-dashboard-tab-link="users">לכל המשתמשים</button>
                                    </div>

                                    <div class="domain-info-list">
                                        @foreach ($adminUsers->take(6) as $adminUser)
                                            <div class="domain-info-row">
                                                <span>{{ $adminUser->name }} · {{ $adminUser->email }}</span>
                                                <strong>{{ $adminUser->sites_count }} אתרים / {{ $adminUser->support_tickets_count }} פניות</strong>
                                            </div>
                                        @endforeach
                                    </div>
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">טיפול מהיר</p>
                                            <h2>פניות אחרונות במערכת</h2>
                                        </div>
                                        <button class="secondary-button" type="button" data-dashboard-tab-link="support">לכל הפניות</button>
                                    </div>

                                    @if ($adminSupportTickets->isEmpty())
                                        <div class="support-empty-state compact-empty-state">
                                            <strong>אין כרגע פניות פעילות</strong>
                                            <p>ברגע שלקוח יפתח פנייה, היא תופיע כאן עם סטטוס, עדיפות והקשר האתר.</p>
                                        </div>
                                    @else
                                        <div class="super-admin-ticket-stack">
                                            @foreach ($adminSupportTickets->take(4) as $ticket)
                                                <article class="super-admin-mini-ticket">
                                                    <div>
                                                        <strong>{{ $ticket->subject }}</strong>
                                                        <p>{{ $ticket->user_email ?? 'משתמש לא ידוע' }} · {{ $ticket->site_name ?? 'ללא אתר' }}</p>
                                                    </div>
                                                    <div class="support-ticket-pills">
                                                        <span class="status-pill {{ in_array($ticket->status, ['resolved', 'answered'], true) ? 'is-good' : 'is-neutral' }}">
                                                            {{ $supportStatusLabels[$ticket->status] ?? $ticket->status }}
                                                        </span>
                                                        <span class="status-pill {{ in_array($ticket->priority, ['high', 'urgent'], true) ? 'is-warn' : 'is-neutral' }}">
                                                            {{ $supportPriorityLabels[$ticket->priority] ?? $ticket->priority }}
                                                        </span>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>
                                    @endif
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">תור עבודה להיום</p>
                                            <h2>מה דורש טיפול עכשיו</h2>
                                        </div>
                                        <button class="secondary-button" type="button" data-dashboard-tab-link="leads">פתח לידים</button>
                                    </div>

                                    @if ($serviceLeadActionQueue->isEmpty())
                                        <div class="support-empty-state compact-empty-state">
                                            <strong>אין כרגע לידים דחופים</strong>
                                            <p>ברגע שליד ידרוש חזרה, יהיה חם או יתקרב מועד טיפול, הוא יופיע כאן כדי שתדע מה לעשות קודם.</p>
                                        </div>
                                    @else
                                        <div class="super-admin-ticket-stack">
                                            @foreach ($serviceLeadActionQueue->take(5) as $lead)
                                                <article class="super-admin-mini-ticket super-admin-mini-lead">
                                                    <div>
                                                        <strong>{{ $serviceCatalog[$lead->service_type]['label'] ?? $lead->service_type }}</strong>
                                                        <p>{{ $lead->user_name ?? 'ללא שם' }} · {{ $lead->site_name ?? 'ללא אתר' }}</p>
                                                        <p class="meta-note">{{ $lead->next_step_label }}</p>
                                                    </div>
                                                    <div class="support-ticket-pills">
                                                        <span class="status-pill {{ $lead->follow_up_tone === 'good' ? 'is-good' : ($lead->follow_up_tone === 'warn' ? 'is-warn' : 'is-neutral') }}">
                                                            {{ $lead->follow_up_label }}
                                                        </span>
                                                        <span class="status-pill {{ $lead->opportunity_tone === 'good' ? 'is-good' : ($lead->opportunity_tone === 'warn' ? 'is-warn' : 'is-neutral') }}">
                                                            {{ $lead->opportunity_label }}
                                                        </span>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>
                                    @endif
                                </article>
                            </div>

                            <div class="super-admin-side-stack">
                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">פעולות מהירות</p>
                                            <h2>ניווט ישיר</h2>
                                        </div>
                                    </div>
                                    <div class="super-admin-actions-list">
                                        <button class="secondary-button" type="button" data-dashboard-tab-link="tracking">עריכת קודי מעקב</button>
                                        <button class="secondary-button" type="button" data-dashboard-tab-link="users">ניהול משתמשים</button>
                                        <button class="secondary-button" type="button" data-dashboard-tab-link="sites">ניהול אתרים</button>
                                        <button class="secondary-button" type="button" data-dashboard-tab-link="support">טיפול בפניות</button>
                                        <button class="secondary-button" type="button" data-dashboard-tab-link="leads">לידים לשירותים</button>
                                    </div>
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">גישה ושיוך</p>
                                            <h2>חשבון הסופר־אדמין</h2>
                                        </div>
                                    </div>
                                    <p class="panel-intro">רק החשבון הזה רואה את מרכז הסופר־אדמין ויכול לנהל תמיכה, אתרים, רישיונות וקודי מעקב גלובליים.</p>
                                    <div class="mini-status-list">
                                        <span class="status-pill is-neutral">office@brndini.co.il</span>
                                        <span class="status-pill is-good">גישה מלאה</span>
                                    </div>
                                </article>
                            </div>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="tracking">
                        <section class="super-admin-kpi-grid super-admin-kpi-grid-compact">
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">📈</span>
                                <strong>{{ $trackingScriptsActiveCount }}</strong>
                                <p>קודי מעקב פעילים</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🌐</span>
                                <strong>3</strong>
                                <p>אזורים שמקבלים טעינה אוטומטית</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🧩</span>
                                <strong>{{ filled($trackingScripts['google_tag_manager_head'] ?? '') ? 'מוגדר' : 'חסר' }}</strong>
                                <p>מצב GTM</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🛰️</span>
                                <strong>{{ filled($trackingScripts['google_analytics_head'] ?? '') ? 'מוגדר' : 'חסר' }}</strong>
                                <p>מצב Analytics</p>
                            </article>
                        </section>

                        <section class="super-admin-content-grid super-admin-content-grid-wide">
                            <article class="portal-content-card super-admin-form-shell">
                                <div>
                                    <p class="eyebrow">קודי מעקב גלובליים</p>
                                    <h2>אנליטיקס, GTM, פיקסל וקודים מותאמים</h2>
                                </div>
                                <p class="panel-intro">הקודים כאן נטענים אוטומטית באתר הציבורי, במסכי ההתחברות וההרשמה, ובכל מקום שבו הפלטפורמה רצה.</p>

                                <form class="stack-form" method="POST" action="{{ route('dashboard.super-admin.tracking') }}">
                                    @csrf
                                    <div class="super-admin-form-grid">
                                        <section class="super-admin-form-card">
                                            <div class="super-admin-form-card-head">
                                                <h3>קודי מדידה עיקריים</h3>
                                                <p>כל מה שנדרש כדי לחבר את הפלטפורמה לאנליטיקס ולתגיות מרכזיות.</p>
                                            </div>

                                            <label for="google_analytics_head">Google Analytics / gtag בראש העמוד</label>
                                            <textarea id="google_analytics_head" name="google_analytics_head" rows="4">{{ old('google_analytics_head', $trackingScripts['google_analytics_head'] ?? '') }}</textarea>

                                            <label for="google_tag_manager_head">Google Tag Manager בראש העמוד</label>
                                            <textarea id="google_tag_manager_head" name="google_tag_manager_head" rows="4">{{ old('google_tag_manager_head', $trackingScripts['google_tag_manager_head'] ?? '') }}</textarea>

                                            <label for="google_tag_manager_body">Google Tag Manager בתחילת ה־body</label>
                                            <textarea id="google_tag_manager_body" name="google_tag_manager_body" rows="4">{{ old('google_tag_manager_body', $trackingScripts['google_tag_manager_body'] ?? '') }}</textarea>
                                        </section>

                                        <section class="super-admin-form-card">
                                            <div class="super-admin-form-card-head">
                                                <h3>פיקסלים וקודים מותאמים</h3>
                                                <p>תוספות שיווק, remarketing וטעינות מותאמות ל־head ול־body.</p>
                                            </div>

                                            <label for="meta_pixel_head">Meta Pixel / Facebook Pixel</label>
                                            <textarea id="meta_pixel_head" name="meta_pixel_head" rows="4">{{ old('meta_pixel_head', $trackingScripts['meta_pixel_head'] ?? '') }}</textarea>

                                            <label for="custom_head_scripts">קודים נוספים ל־head</label>
                                            <textarea id="custom_head_scripts" name="custom_head_scripts" rows="5">{{ old('custom_head_scripts', $trackingScripts['custom_head_scripts'] ?? '') }}</textarea>

                                            <label for="custom_body_scripts">קודים נוספים ל־body</label>
                                            <textarea id="custom_body_scripts" name="custom_body_scripts" rows="5">{{ old('custom_body_scripts', $trackingScripts['custom_body_scripts'] ?? '') }}</textarea>
                                        </section>
                                    </div>

                                    <div class="super-admin-form-actions">
                                        <p class="meta-note">השמירה כאן חלה על האתר הציבורי, דפי auth והאזור האישי בטעינה אחת מרוכזת.</p>
                                        <button class="primary-button" type="submit">שמור קודי מעקב</button>
                                    </div>
                                </form>
                            </article>

                            <aside class="super-admin-side-stack">
                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">איפה זה נטען</p>
                                            <h2>השפעה גלובלית</h2>
                                        </div>
                                    </div>
                                    <div class="domain-info-list">
                                        <div class="domain-info-row">
                                            <span>אתר ציבורי</span>
                                            <strong>נטען אוטומטית</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>התחברות והרשמה</span>
                                            <strong>נטען אוטומטית</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>אזור אישי</span>
                                            <strong>נטען אוטומטית</strong>
                                        </div>
                                    </div>
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">בדיקה מהירה</p>
                                            <h2>מה מוגדר כבר עכשיו</h2>
                                        </div>
                                    </div>
                                    <div class="domain-info-list">
                                        <div class="domain-info-row">
                                            <span>Google Analytics</span>
                                            <strong>{{ filled($trackingScripts['google_analytics_head'] ?? '') ? 'מוגדר' : 'לא הוגדר' }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>Google Tag Manager</span>
                                            <strong>{{ filled($trackingScripts['google_tag_manager_head'] ?? '') || filled($trackingScripts['google_tag_manager_body'] ?? '') ? 'מוגדר' : 'לא הוגדר' }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>Meta Pixel</span>
                                            <strong>{{ filled($trackingScripts['meta_pixel_head'] ?? '') ? 'מוגדר' : 'לא הוגדר' }}</strong>
                                        </div>
                                    </div>
                                </article>
                            </aside>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="users">
                        <section class="super-admin-kpi-grid super-admin-kpi-grid-compact">
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">👥</span>
                                <strong>{{ $adminSummary['users'] }}</strong>
                                <p>סה״כ משתמשים</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">⭐</span>
                                <strong>{{ $superAdminUsersCount }}</strong>
                                <p>סופר־אדמינים</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🛡️</span>
                                <strong>{{ $adminUsersCount }}</strong>
                                <p>מנהלי מערכת</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🏢</span>
                                <strong>{{ $clientUsersCount }}</strong>
                                <p>לקוחות רגילים</p>
                            </article>
                        </section>

                        <section class="portal-content-card super-admin-table-card">
                            <div class="domain-card-head">
                                <div>
                                    <h2>כל המשתמשים במערכת</h2>
                                    <p class="panel-intro">רשימה מהירה של בעלי החשבונות, כדי להבין למי יש אתרים פעילים ולמי יש פניות פתוחות.</p>
                                </div>
                            </div>

                            <div class="super-admin-toolbar" data-filter-root>
                                <div class="super-admin-toolbar-field">
                                    <label for="super_admin_users_search">חיפוש משתמש</label>
                                    <input id="super_admin_users_search" type="search" placeholder="חפש לפי שם או אימייל" data-filter-search>
                                </div>
                                <div class="super-admin-toolbar-field">
                                    <label for="super_admin_users_role">סינון לפי הרשאה</label>
                                    <select id="super_admin_users_role" data-filter-field="role">
                                        <option value="">כל ההרשאות</option>
                                        <option value="super">סופר־אדמין</option>
                                        <option value="admin">אדמין</option>
                                        <option value="client">לקוח</option>
                                    </select>
                                </div>
                            </div>

                            <div class="licenses-table-wrap">
                                <table class="licenses-table">
                                    <thead>
                                        <tr>
                                            <th>משתמש</th>
                                            <th>אימייל</th>
                                            <th>אתרים</th>
                                            <th>פניות</th>
                                            <th>הרשאות</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($adminUsers as $adminUser)
                                            @php
                                                $roleKey = $adminUser->isSuperAdmin() ? 'super' : ($adminUser->is_admin ? 'admin' : 'client');
                                            @endphp
                                            <tr data-filter-item data-filter-search-text="{{ Str::lower($adminUser->name . ' ' . $adminUser->email) }}" data-filter-role="{{ $roleKey }}">
                                                <td data-label="משתמש">{{ $adminUser->name }}</td>
                                                <td data-label="אימייל">{{ $adminUser->email }}</td>
                                                <td data-label="אתרים">{{ $adminUser->sites_count }}</td>
                                                <td data-label="פניות">{{ $adminUser->support_tickets_count }}</td>
                                                <td data-label="הרשאות">
                                                    @if ($adminUser->isSuperAdmin())
                                                        <span class="status-pill is-good">סופר־אדמין</span>
                                                    @elseif ($adminUser->is_admin)
                                                        <span class="status-pill is-neutral">אדמין</span>
                                                    @else
                                                        <span class="status-pill is-neutral">לקוח</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="sites">
                        <section class="super-admin-kpi-grid super-admin-kpi-grid-compact">
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🌐</span>
                                <strong>{{ $adminSites->count() }}</strong>
                                <p>סה״כ אתרים</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">✅</span>
                                <strong>{{ $adminSummary['active_sites'] }}</strong>
                                <p>רישיונות פעילים</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">📡</span>
                                <strong>{{ $installedSitesCount }}</strong>
                                <p>הטמעות שזוהו</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">⏱</span>
                                <strong>{{ $staleInstallSitesCount }}</strong>
                                <p>לא זוהו לאחרונה</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🧱</span>
                                <strong>{{ $pendingInstallSitesCount }}</strong>
                                <p>ממתינים להטמעה</p>
                            </article>
                        </section>

                        <section class="portal-content-card super-admin-table-card">
                            <div class="domain-card-head">
                                <div>
                                    <h2>כל האתרים והרישיונות</h2>
                                    <p class="panel-intro">כאן רואים מי בעל החשבון, איזה אתר פעיל, ומה מצב הרישיון וההטמעה.</p>
                                </div>
                            </div>

                            <div class="super-admin-toolbar" data-filter-root>
                                <div class="super-admin-toolbar-field">
                                    <label for="super_admin_sites_search">חיפוש אתר</label>
                                    <input id="super_admin_sites_search" type="search" placeholder="חפש לפי שם אתר, דומיין או בעלים" data-filter-search>
                                </div>
                                <div class="super-admin-toolbar-field">
                                    <label for="super_admin_sites_license">סינון רישיון</label>
                                    <select id="super_admin_sites_license" data-filter-field="license">
                                        <option value="">כל הרישיונות</option>
                                        <option value="active">פעיל</option>
                                        <option value="inactive">לא פעיל</option>
                                    </select>
                                </div>
                                <div class="super-admin-toolbar-field">
                                    <label for="super_admin_sites_install">סינון התקנה</label>
                                    <select id="super_admin_sites_install" data-filter-field="install">
                                        <option value="">כל מצבי ההתקנה</option>
                                        <option value="installed">זוהתה טעינה</option>
                                        <option value="stale">לא זוהה לאחרונה</option>
                                        <option value="pending">ממתין להטמעה</option>
                                    </select>
                                </div>
                            </div>

                            <div class="licenses-table-wrap">
                                <table class="licenses-table">
                                    <thead>
                                        <tr>
                                            <th>אתר</th>
                                            <th>דומיין</th>
                                            <th>בעלים</th>
                                            <th>רישיון</th>
                                            <th>הטמעה</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($adminSites as $adminSite)
                                            @php
                                                $licenseKey = $adminSite->licenseActive() ? 'active' : 'inactive';
                                                $adminInstallSignal = $adminSite->installationSignal();
                                                $installKey = $adminInstallSignal['status'];
                                                $ownerEmail = $adminSite->user?->email ?? 'לא ידוע';
                                            @endphp
                                            <tr
                                                data-filter-item
                                                data-filter-search-text="{{ Str::lower($adminSite->site_name . ' ' . $adminSite->domain . ' ' . $ownerEmail) }}"
                                                data-filter-license="{{ $licenseKey }}"
                                                data-filter-install="{{ $installKey }}"
                                            >
                                                <td data-label="אתר">{{ $adminSite->site_name }}</td>
                                                <td data-label="דומיין">{{ parse_url($adminSite->domain, PHP_URL_HOST) ?: $adminSite->domain }}</td>
                                                <td data-label="בעלים">{{ $adminSite->user?->email ?? 'לא ידוע' }}</td>
                                                <td data-label="רישיון">
                                                    <span class="status-pill {{ $adminSite->licenseActive() ? 'is-good' : 'is-warn' }}">
                                                        {{ $adminSite->licenseActive() ? 'פעיל' : 'לא פעיל' }}
                                                    </span>
                                                </td>
                                                <td data-label="הטמעה">
                                                    <span class="status-pill {{ $adminInstallSignal['tone'] === 'good' ? 'is-good' : ($adminInstallSignal['tone'] === 'neutral' ? 'is-neutral' : 'is-warn') }}">
                                                        {{ $adminInstallSignal['label'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="support">
                        <section class="super-admin-kpi-grid super-admin-kpi-grid-compact">
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">📬</span>
                                <strong>{{ $adminSupportTickets->count() }}</strong>
                                <p>פניות במערכת</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">⏳</span>
                                <strong>{{ $adminSupportTickets->whereIn('status', ['open', 'pending'])->count() }}</strong>
                                <p>פניות שדורשות תגובה</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">⚡</span>
                                <strong>{{ $adminSupportTickets->whereIn('priority', ['high', 'urgent'])->count() }}</strong>
                                <p>פניות בעדיפות גבוהה</p>
                            </article>
                        </section>

                        <section class="super-admin-content-grid super-admin-content-grid-wide">
                            <article class="portal-content-card">
                                <div class="domain-card-head">
                                    <div>
                                        <h2>מרכז פניות כולל</h2>
                                        <p class="panel-intro">כאן אתה מקבל את כל הטיקטים שנפתחו במערכת, ויכול לענות, לסמן סטטוס ולעזור למשתמשים.</p>
                                    </div>
                                </div>

                                <div class="super-admin-toolbar" data-filter-root>
                                    <div class="super-admin-toolbar-field">
                                        <label for="super_admin_tickets_search">חיפוש פנייה</label>
                                        <input id="super_admin_tickets_search" type="search" placeholder="חפש לפי נושא, אתר או אימייל" data-filter-search>
                                    </div>
                                    <div class="super-admin-toolbar-field">
                                        <label for="super_admin_tickets_status">סינון סטטוס</label>
                                        <select id="super_admin_tickets_status" data-filter-field="status">
                                            <option value="">כל הסטטוסים</option>
                                            @foreach ($supportStatusLabels as $statusKey => $statusLabel)
                                                <option value="{{ $statusKey }}">{{ $statusLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="super-admin-toolbar-field">
                                        <label for="super_admin_tickets_priority">סינון עדיפות</label>
                                        <select id="super_admin_tickets_priority" data-filter-field="priority">
                                            <option value="">כל העדיפויות</option>
                                            @foreach ($supportPriorityLabels as $priorityKey => $priorityLabel)
                                                <option value="{{ $priorityKey }}">{{ $priorityLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @if (($supportUsesRuntimeFallback ?? false) === true)
                                    <div class="support-empty-state">
                                        <strong>מרכז התמיכה פועל כרגע במצב גיבוי</strong>
                                        <p>הפניות עדיין מוצגות, ניתנות לעדכון, ונשמרות בצורה יציבה עד שהשרת ישלים את מיגרציית טבלת התמיכה.</p>
                                    </div>
                                @endif

                                @if ($adminSupportTickets->isEmpty())
                                    <div class="support-empty-state">
                                        <strong>עדיין אין פניות במערכת</strong>
                                        <p>ברגע שמשתמש יפתח פנייה, היא תופיע כאן עם כל הפרטים הדרושים לטיפול.</p>
                                    </div>
                                @else
                                    <div class="support-ticket-list">
                                        @foreach ($adminSupportTickets as $ticket)
                                            <article
                                                class="support-ticket-card"
                                                data-filter-item
                                                data-filter-search-text="{{ Str::lower(($ticket->subject ?? '') . ' ' . ($ticket->user_email ?? '') . ' ' . ($ticket->site_name ?? '') . ' ' . ($ticket->message ?? '')) }}"
                                                data-filter-status="{{ $ticket->status }}"
                                                data-filter-priority="{{ $ticket->priority }}"
                                            >
                                                <div class="support-ticket-head">
                                                    <div>
                                                        <p class="support-ticket-code">{{ $ticket->reference_code }}</p>
                                                        <h3>{{ $ticket->subject }}</h3>
                                                    </div>

                                                    <div class="support-ticket-pills">
                                                        <span class="status-pill {{ in_array($ticket->status, ['resolved', 'answered'], true) ? 'is-good' : 'is-neutral' }}">
                                                            {{ $supportStatusLabels[$ticket->status] ?? $ticket->status }}
                                                        </span>
                                                        <span class="status-pill {{ in_array($ticket->priority, ['high', 'urgent'], true) ? 'is-warn' : 'is-neutral' }}">
                                                            {{ $supportPriorityLabels[$ticket->priority] ?? $ticket->priority }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <p class="support-ticket-meta">
                                                    {{ $ticket->user_email ?? 'משתמש לא ידוע' }} ·
                                                    {{ $ticket->site_name ?? 'ללא אתר' }} ·
                                                    {{ $supportCategories[$ticket->category] ?? $ticket->category }}
                                                </p>

                                                <p class="support-ticket-message">{{ $ticket->message }}</p>

                                                @if (! empty($ticket->admin_response))
                                                    <div class="support-admin-response">
                                                        <strong>מענה פנימי</strong>
                                                        <p>{{ $ticket->admin_response }}</p>
                                                    </div>
                                                @endif

                                                <form class="stack-form compact-form" method="POST" action="{{ route('dashboard.super-admin.tickets.update', ['ticketKey' => $ticket->update_key]) }}">
                                                    @csrf

                                                    <div class="support-form-row">
                                                        <div>
                                                            <label for="ticket_status_{{ $ticket->update_key }}">סטטוס</label>
                                                            <select id="ticket_status_{{ $ticket->update_key }}" name="status">
                                                                @foreach ($supportStatusLabels as $statusKey => $statusLabel)
                                                                    <option value="{{ $statusKey }}" @selected($ticket->status === $statusKey)>{{ $statusLabel }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div>
                                                            <label for="ticket_priority_{{ $ticket->update_key }}">עדיפות</label>
                                                            <select id="ticket_priority_{{ $ticket->update_key }}" name="priority">
                                                                @foreach ($supportPriorityLabels as $priorityKey => $priorityLabel)
                                                                    <option value="{{ $priorityKey }}" @selected($ticket->priority === $priorityKey)>{{ $priorityLabel }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <label for="ticket_response_{{ $ticket->update_key }}">תגובה פנימית / מענה ללקוח</label>
                                                    <textarea id="ticket_response_{{ $ticket->update_key }}" name="admin_response" rows="4">{{ old('admin_response', $ticket->admin_response) }}</textarea>

                                                    <button class="primary-button" type="submit">עדכן פנייה</button>
                                                </form>
                                            </article>
                                        @endforeach
                                    </div>
                                @endif
                            </article>

                            <aside class="super-admin-side-stack">
                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">בריאות תור העבודה</p>
                                            <h2>תמונת מצב מהירה</h2>
                                        </div>
                                    </div>
                                    <div class="domain-info-list">
                                        <div class="domain-info-row">
                                            <span>פניות פתוחות</span>
                                            <strong>{{ $adminSupportTickets->whereIn('status', ['open', 'pending'])->count() }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>פניות דחופות</span>
                                            <strong>{{ $adminSupportTickets->whereIn('priority', ['high', 'urgent'])->count() }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>פתורות</span>
                                            <strong>{{ $adminSupportTickets->where('status', 'resolved')->count() }}</strong>
                                        </div>
                                    </div>
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">איך לטפל מהר</p>
                                            <h2>סדר עבודה מומלץ</h2>
                                        </div>
                                    </div>
                                    <div class="super-admin-note-list">
                                        <div>
                                            <strong>1. בדוק עדיפות וסטטוס</strong>
                                            <p>התחל מפניות דחופות או מכאלה שעדיין לא קיבלו תגובה.</p>
                                        </div>
                                        <div>
                                            <strong>2. ודא הקשר אתר</strong>
                                            <p>עבור כל פנייה בדוק אם יש רישיון פעיל, הטמעה וטעינה אחרונה.</p>
                                        </div>
                                        <div>
                                            <strong>3. השאר מענה פנימי ברור</strong>
                                            <p>כך תוכל לחזור בקלות להיסטוריה וגם לעזור לצוות בהמשך.</p>
                                        </div>
                                    </div>
                                </article>
                            </aside>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="leads">
                        <section class="super-admin-kpi-grid super-admin-kpi-grid-compact">
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🚀</span>
                                <strong>{{ $adminServiceLeads->count() }}</strong>
                                <p>לידים לשירותי Brndini</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🌐</span>
                                <strong>{{ $adminServiceLeads->pluck('site_name')->filter()->unique()->count() }}</strong>
                                <p>אתרים שביקשו שירות</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">📬</span>
                                <strong>{{ $adminServiceLeads->pluck('user_email')->filter()->unique()->count() }}</strong>
                                <p>לקוחות שפנו</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">✅</span>
                                <strong>{{ $adminServiceLeads->where('status', 'won')->count() }}</strong>
                                <p>נסגרו כלקוח</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🔥</span>
                                <strong>{{ $adminServiceLeads->where('opportunity_key', 'hot')->count() }}</strong>
                                <p>לידים חמים</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">↗</span>
                                <strong>{{ $adminServiceLeads->where('source', 'public')->count() }}</strong>
                                <p>לידים מהאתר הציבורי</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🪄</span>
                                <strong>{{ $adminServiceLeads->where('service_type', 'ecosystem_access')->count() }}</strong>
                                <p>עניין במוצרים הבאים</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">⏰</span>
                                <strong>{{ $adminServiceLeads->where('freshness_key', 'stale')->count() }}</strong>
                                <p>לידים שדורשים חזרה</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">📅</span>
                                <strong>{{ $adminServiceLeads->where('follow_up_tone', 'good')->count() }}</strong>
                                <p>חזרות שמתוזמנות להיום</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🕒</span>
                                <strong>{{ $adminSummary['service_leads_stuck'] ?? 0 }}</strong>
                                <p>לידים תקועים בלי נגיעה</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🚨</span>
                                <strong>{{ $adminSummary['service_leads_overdue_first_touch'] ?? 0 }}</strong>
                                <p>חרגו מזמן תגובה ראשונית</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🧑‍💼</span>
                                <strong>{{ $adminServiceLeads->filter(fn ($lead) => filled($lead->assigned_admin_email ?? null))->count() }}</strong>
                                <p>לידים שכבר שויכו למטפל</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">📌</span>
                                <strong>{{ $adminServiceLeads->filter(fn ($lead) => blank($lead->assigned_admin_email ?? null))->count() }}</strong>
                                <p>לידים שעדיין לא משויכים</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🔁</span>
                                <strong>{{ $adminSummary['service_leads_repeat_contacts'] ?? 0 }}</strong>
                                <p>פניות חוזרות מאותו איש קשר</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🌍</span>
                                <strong>{{ $adminSummary['service_leads_repeat_sites'] ?? 0 }}</strong>
                                <p>אתרים שחזרו שוב</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🤝</span>
                                <strong>{{ $adminSummary['service_leads_existing_customers'] ?? 0 }}</strong>
                                <p>לקוחות קיימים שחזרו</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🧠</span>
                                <strong>{{ $adminSummary['service_leads_cross_sell'] ?? 0 }}</strong>
                                <p>הזדמנויות Cross-sell</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">➡️</span>
                                <strong>{{ $adminSummary['service_leads_with_recommendation'] ?? 0 }}</strong>
                                <p>לידים עם הצעת המשך</p>
                            </article>
                            <article class="super-admin-kpi-card">
                                <span class="super-admin-kpi-icon">🧭</span>
                                <strong>{{ $adminSummary['service_leads_with_playbook'] ?? 0 }}</strong>
                                <p>לידים עם דרך גישה מומלצת</p>
                            </article>
                        </section>

                        <section class="super-admin-content-grid super-admin-content-grid-wide">
                            <article class="portal-content-card">
                                <div class="domain-card-head">
                                    <div>
                                        <h2>לידים לשירותי Brndini</h2>
                                        <p class="panel-intro">פניות שירות עסקיות שמגיעות מתוך הכלי החינמי: אחסון, SEO, קמפיינים, תחזוקה, שדרוגי אתר ואוטומציות. זה נפרד לחלוטין מהתמיכה הטכנית של המערכת.</p>
                                    </div>
                                    <div class="portal-card-actions">
                                        <a class="secondary-button" href="{{ route('dashboard.super-admin.leads.export') }}">ייצוא CSV</a>
                                    </div>
                                </div>

                                <div class="super-admin-toolbar" data-filter-root>
                                    <div class="super-admin-toolbar-field">
                                        <label for="super_admin_leads_search">חיפוש ליד</label>
                                        <input id="super_admin_leads_search" type="search" placeholder="חפש לפי שירות, אתר, משתמש או מטרה" data-filter-search>
                                    </div>
                                    <div class="super-admin-toolbar-field">
                                        <label for="super_admin_leads_service">סינון שירות</label>
                                        <select id="super_admin_leads_service" data-filter-field="service">
                                            <option value="">כל השירותים</option>
                                            @foreach ($serviceCatalog as $serviceKey => $service)
                                                <option value="{{ $serviceKey }}">{{ $service['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="super-admin-toolbar-field">
                                        <label for="super_admin_leads_source">סינון מקור</label>
                                        <select id="super_admin_leads_source" data-filter-field="source">
                                            <option value="">כל המקורות</option>
                                            @foreach ($serviceLeadSourceSummary as $item)
                                                <option value="{{ $item['key'] }}">{{ $item['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="super-admin-toolbar-field">
                                        <label for="super_admin_leads_entry">סינון נקודת כניסה</label>
                                        <select id="super_admin_leads_entry" data-filter-field="entry">
                                            <option value="">כל נקודות הכניסה</option>
                                            @foreach ($serviceLeadEntrySummary as $item)
                                                <option value="{{ $item['key'] }}">{{ $item['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="super-admin-toolbar-field">
                                        <label for="super_admin_leads_status">סינון סטטוס</label>
                                        <select id="super_admin_leads_status" data-filter-field="status">
                                            <option value="">כל הסטטוסים</option>
                                            @foreach ($serviceLeadStatusLabels as $statusKey => $statusLabel)
                                                <option value="{{ $statusKey }}">{{ $statusLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="super-admin-toolbar-field">
                                        <label for="super_admin_leads_assignee">סינון מטפל</label>
                                        <select id="super_admin_leads_assignee" data-filter-field="assignee">
                                            <option value="">כל המטפלים</option>
                                            <option value="unassigned">לא משויך</option>
                                            @foreach ($serviceLeadAssigneeSummary as $item)
                                                @continue($item['key'] === 'unassigned')
                                                <option value="{{ $item['key'] }}">{{ $item['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @if ($adminServiceLeads->isEmpty())
                                    <div class="support-empty-state">
                                        <strong>עדיין אין לידים לשירותים</strong>
                                        <p>ברגע שמשתמש יבקש אחסון, SEO, קמפיין, תחזוקה או שדרוג אתר, הפנייה תופיע כאן ותצטרף למשפך המכירה של Brndini.</p>
                                    </div>
                                @else
                                    <div class="support-ticket-list">
                                        @foreach ($adminServiceLeads as $lead)
                                            <article
                                                class="support-ticket-card"
                                                data-filter-item
                                                data-filter-search-text="{{ Str::lower(($serviceCatalog[$lead->service_type]['label'] ?? $lead->service_type) . ' ' . ($lead->user_email ?? '') . ' ' . ($lead->contact_phone ?? '') . ' ' . ($lead->site_name ?? '') . ' ' . ($lead->goal ?? '') . ' ' . ($lead->message ?? '') . ' ' . ($lead->business_type_label ?? '') . ' ' . ($lead->team_size_label ?? '') . ' ' . ($lead->timeframe_label ?? '') . ' ' . ($lead->budget_range_label ?? '') . ' ' . ($lead->urgency_level_label ?? '') . ' ' . ($lead->callback_window_label ?? '') . ' ' . ($lead->utm_source ?? '') . ' ' . ($lead->utm_medium ?? '') . ' ' . ($lead->utm_campaign ?? '') . ' ' . ($lead->referrer_host ?? '')) }}"
                                                data-filter-service="{{ $lead->service_type }}"
                                                data-filter-source="{{ $lead->source ?? 'dashboard' }}"
                                                data-filter-entry="{{ $lead->entry_point ?? '' }}"
                                                data-filter-status="{{ $lead->status }}"
                                                data-filter-assignee="{{ $lead->assigned_admin_email ?? 'unassigned' }}"
                                            >
                                                <div class="support-ticket-head">
                                                    <div>
                                                        <p class="support-ticket-code">{{ $lead->reference_code }}</p>
                                                        <h3>{{ $serviceCatalog[$lead->service_type]['label'] ?? $lead->service_type }}</h3>
                                                    </div>
                                                    <div class="support-ticket-pills">
                                                        <span class="status-pill is-neutral">{{ $lead->source_label ?? 'פנייה עסקית' }}</span>
                                                        <span class="status-pill is-neutral">{{ $lead->entry_label ?? 'כניסה כללית' }}</span>
                                                        <span class="status-pill is-neutral">{{ $servicePreferredContactLabels[$lead->preferred_contact_key ?? $lead->preferred_contact] ?? $lead->preferred_contact }}</span>
                                                        <span class="status-pill {{ $lead->opportunity_tone === 'good' ? 'is-good' : ($lead->opportunity_tone === 'warn' ? 'is-warn' : 'is-neutral') }}">
                                                            {{ $lead->opportunity_label }}
                                                        </span>
                                                        <span class="status-pill {{ $lead->freshness_tone === 'good' ? 'is-good' : ($lead->freshness_tone === 'warn' ? 'is-warn' : 'is-neutral') }}">
                                                            {{ $lead->freshness_label }}
                                                        </span>
                                                        <span class="status-pill {{ in_array($lead->status, ['won', 'qualified'], true) ? 'is-good' : ($lead->status === 'closed' ? 'is-neutral' : 'is-warn') }}">
                                                            {{ $serviceLeadStatusLabels[$lead->status] ?? $lead->status }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <p class="support-ticket-meta">
                                                    {{ $lead->user_name ?? 'ללא שם' }} · {{ $lead->user_email ?? 'ללא אימייל' }} · {{ $lead->site_name ?? 'ללא אתר' }}
                                                </p>
                                                @if (!empty($lead->contact_phone))
                                                    <p class="support-ticket-meta">טלפון לחזרה: {{ $lead->contact_phone }}</p>
                                                @elseif (!empty($lead->missing_preferred_contact_detail))
                                                    <p class="support-ticket-meta">נבחר ערוץ חזרה טלפוני, אבל עדיין חסר מספר.</p>
                                                @endif
                                                <div class="lead-intel-row">
                                                    <span class="status-pill is-neutral">{{ $lead->intent_label }}</span>
                                                    <span class="meta-note">הפעולה הבאה: {{ $lead->next_step_label }}</span>
                                                </div>
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
                                                <div class="lead-intel-row">
                                                    <span class="status-pill is-neutral">מטפל: {{ $lead->assigned_label }}</span>
                                                </div>
                                                <div class="lead-intel-row">
                                                    <span class="status-pill {{ ($lead->relationship_tone ?? 'neutral') === 'good' ? 'is-good' : (($lead->relationship_tone ?? 'neutral') === 'warn' ? 'is-warn' : 'is-neutral') }}">
                                                        {{ $lead->relationship_label }}
                                                    </span>
                                                    @if (!empty($lead->related_services_label))
                                                        <span class="meta-note">{{ $lead->related_services_label }}</span>
                                                    @endif
                                                </div>
                                                @if (!empty($lead->recommended_service_label))
                                                    <div class="lead-intel-row">
                                                        <span class="status-pill is-good">המשך מומלץ: {{ $lead->recommended_service_label }}</span>
                                                    </div>
                                                @endif
                                                @if (!empty($lead->playbook_label) || !empty($lead->recommended_contact_channel_label))
                                                    <div class="lead-intel-row">
                                                        @if (!empty($lead->recommended_contact_channel_label))
                                                            <span class="status-pill is-neutral">ערוץ פנייה: {{ $lead->recommended_contact_channel_label }}</span>
                                                        @endif
                                                        @if (!empty($lead->playbook_label))
                                                            <span class="status-pill is-good">דרך גישה: {{ $lead->playbook_label }}</span>
                                                        @endif
                                                    </div>
                                                @endif
                                                @if (!empty($lead->playbook_note))
                                                    <p class="support-ticket-message"><strong>המלצת גישה:</strong> {{ $lead->playbook_note }}</p>
                                                @endif
                                                @if (!empty($lead->opening_line))
                                                    @php($openingLineId = 'lead-opening-' . md5((string) $lead->update_key))
                                                    <div class="lead-opening-box">
                                                        <div class="lead-opening-head">
                                                            <strong>נוסח פתיחה מוצע</strong>
                                                            <div class="lead-opening-actions">
                                                                <button class="secondary-button" type="button" data-copy-target="{{ $openingLineId }}">העתק נוסח</button>
                                                                @if (!empty($lead->opening_mailto))
                                                                    <a class="secondary-button" href="{{ $lead->opening_mailto }}">פתח מייל</a>
                                                                @endif
                                                                @if (!empty($lead->opening_whatsapp_href))
                                                                    <a class="secondary-button" href="{{ $lead->opening_whatsapp_href }}" target="_blank" rel="noreferrer">פתח ווטסאפ</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @if (!empty($lead->opening_subject))
                                                            <p class="meta-note"><strong>כותרת:</strong> {{ $lead->opening_subject }}</p>
                                                        @endif
                                                        <p class="support-ticket-message" id="{{ $openingLineId }}">{{ $lead->opening_line }}</p>
                                                    </div>
                                                @endif
                                                @if (!empty($lead->repeat_contact_label) || !empty($lead->repeat_site_label))
                                                    <div class="lead-intel-row">
                                                        @if (!empty($lead->repeat_contact_label))
                                                            <span class="status-pill is-warn">{{ $lead->repeat_contact_label }}</span>
                                                        @endif
                                                        @if (!empty($lead->repeat_site_label))
                                                            <span class="status-pill is-neutral">{{ $lead->repeat_site_label }}</span>
                                                        @endif
                                                    </div>
                                                @endif
                                                <div class="lead-intel-row">
                                                    <span class="meta-note">איכות ליד: {{ $lead->opportunity_label }} · ציון {{ $lead->opportunity_score }}/100</span>
                                                    <span class="status-pill is-good">שווי: {{ $lead->budget_estimate_label }}</span>
                                                    <span class="status-pill is-neutral">משוקלל: {{ $lead->weighted_estimate_label }}</span>
                                                </div>
                                                @if (!empty($lead->lead_tags))
                                                    <div class="lead-intel-row">
                                                        @foreach ($lead->lead_tags as $tag)
                                                            <span class="status-pill {{ ($tag['tone'] ?? 'neutral') === 'good' ? 'is-good' : (($tag['tone'] ?? 'neutral') === 'warn' ? 'is-warn' : 'is-neutral') }}">{{ $tag['label'] }}</span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                @if (!empty($lead->marketing_label) || !empty($lead->referrer_host))
                                                    <div class="lead-intel-row">
                                                        @if (!empty($lead->marketing_label))
                                                            <span class="status-pill is-neutral">UTM: {{ $lead->marketing_label }}</span>
                                                        @endif
                                                        @if (!empty($lead->referrer_host))
                                                            <span class="meta-note">מפנה: {{ $lead->referrer_host }}</span>
                                                        @endif
                                                    </div>
                                                @endif
                                                <div class="lead-intel-row">
                                                    <span class="status-pill {{ $lead->follow_up_tone === 'good' ? 'is-good' : ($lead->follow_up_tone === 'warn' ? 'is-warn' : 'is-neutral') }}">
                                                        {{ $lead->follow_up_label }}
                                                    </span>
                                                    <span class="status-pill {{ $lead->first_touch_tone === 'good' ? 'is-good' : ($lead->first_touch_tone === 'warn' ? 'is-warn' : 'is-neutral') }}">
                                                        {{ $lead->first_touch_label }}
                                                    </span>
                                                    <span class="status-pill {{ $lead->age_bucket_tone === 'good' ? 'is-good' : ($lead->age_bucket_tone === 'warn' ? 'is-warn' : 'is-neutral') }}">
                                                        {{ $lead->age_bucket_label }}
                                                    </span>
                                                    <span class="status-pill {{ $lead->inactive_bucket_tone === 'good' ? 'is-good' : ($lead->inactive_bucket_tone === 'warn' ? 'is-warn' : 'is-neutral') }}">
                                                        {{ $lead->inactive_bucket_label }}
                                                    </span>
                                                    @if (!empty($lead->close_reason))
                                                        <span class="status-pill is-warn">חסם: {{ $lead->close_reason_label }}</span>
                                                    @endif
                                                </div>
                                                @if (!empty($lead->operational_blockers))
                                                    <div class="lead-intel-row">
                                                        @foreach ($lead->operational_blockers as $blocker)
                                                            <span class="status-pill {{ ($blocker['tone'] ?? 'neutral') === 'warn' ? 'is-warn' : 'is-neutral' }}">
                                                                {{ $blocker['label'] }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                <div class="lead-quick-actions">
                                                    @if (!empty($lead->opening_mailto))
                                                        <a class="secondary-button" href="{{ $lead->opening_mailto }}">שלח פתיחה במייל</a>
                                                    @elseif (!empty($lead->mail_to))
                                                        <a class="secondary-button" href="{{ $lead->mail_to }}">שלח מייל</a>
                                                    @endif
                                                    @if (!empty($lead->opening_whatsapp_href))
                                                        <a class="secondary-button" href="{{ $lead->opening_whatsapp_href }}" target="_blank" rel="noreferrer">שלח פתיחה בווטסאפ</a>
                                                    @elseif (!empty($lead->whatsapp_href))
                                                        <a class="secondary-button" href="{{ $lead->whatsapp_href }}" target="_blank" rel="noreferrer">ווטסאפ</a>
                                                    @endif
                                                    @if (!empty($lead->phone_href))
                                                        <a class="secondary-button" href="{{ $lead->phone_href }}">התקשר</a>
                                                    @endif
                                                    @if (filled($lead->site_domain))
                                                        <a class="secondary-button" href="{{ Str::startsWith($lead->site_domain, ['http://', 'https://']) ? $lead->site_domain : 'https://' . ltrim($lead->site_domain, '/') }}" target="_blank" rel="noreferrer">פתח אתר</a>
                                                    @endif
                                                </div>
                                            <p class="support-ticket-message"><strong>מטרה:</strong> {{ $lead->goal }}</p>
                                            <p class="support-ticket-message">{{ $lead->message }}</p>

                                            @if (!empty($lead->internal_note))
                                                <p class="support-ticket-message"><strong>הערה פנימית:</strong> {{ $lead->internal_note }}</p>
                                            @endif

                                            @if (($lead->activity_history ?? collect())->isNotEmpty())
                                                <div class="lead-activity-timeline">
                                                    @foreach ($lead->activity_history->take(3) as $activity)
                                                        <div class="lead-activity-item">
                                                            <div>
                                                                <strong>{{ $activity->label }}</strong>
                                                                <p>{{ $activity->details }}</p>
                                                            </div>
                                                            <div class="lead-activity-meta">
                                                                @if (!empty($activity->actor))
                                                                    <span>{{ $activity->actor }}</span>
                                                                @endif
                                                                @if (!empty($activity->time_label))
                                                                    <small>{{ $activity->time_label }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <form class="support-admin-form" method="POST" action="{{ route('dashboard.super-admin.leads.update', $lead->update_key) }}">
                                                @csrf
                                                <div class="support-admin-grid">
                                                    <label>
                                                        <span>סטטוס ליד</span>
                                                        <select name="status">
                                                            @foreach ($serviceLeadStatusLabels as $statusKey => $statusLabel)
                                                                <option value="{{ $statusKey }}" @selected($lead->status === $statusKey)>{{ $statusLabel }}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>
                                                    <label class="support-admin-span-2">
                                                        <span>הערה פנימית</span>
                                                        <textarea name="internal_note" rows="3" placeholder="למשל: לחזור מחר, מתאים לאחסון, צריך שיחת היכרות">{{ $lead->internal_note }}</textarea>
                                                    </label>
                                                    <label>
                                                        <span>מועד חזרה</span>
                                                        <input type="date" name="follow_up_at" value="{{ $lead->follow_up_at }}">
                                                    </label>
                                                    <label>
                                                        <span>בעל טיפול</span>
                                                        <select name="assigned_admin_email">
                                                            <option value="">לא משויך</option>
                                                            @foreach ($adminAssignableUsers as $assignableUser)
                                                                <option value="{{ $assignableUser['email'] }}" @selected(($lead->assigned_admin_email ?? null) === $assignableUser['email'])>{{ $assignableUser['name'] }} · {{ $assignableUser['email'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>
                                                    <label>
                                                        <span>סיבת סגירה / חסם</span>
                                                        <select name="close_reason">
                                                            <option value="">לא הוגדר</option>
                                                            @foreach ($serviceLeadCloseReasonLabels as $reasonKey => $reasonLabel)
                                                                <option value="{{ $reasonKey }}" @selected(($lead->close_reason ?? null) === $reasonKey)>{{ $reasonLabel }}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="support-form-actions">
                                                    <button class="secondary-button" type="submit">שמור ליד</button>
                                                </div>
                                            </form>
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                            </article>

                            <aside class="super-admin-side-stack">
                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">לטפל עכשיו</p>
                                            <h2>תור עבודה להיום</h2>
                                        </div>
                                    </div>
                                    @if ($serviceLeadActionQueue->isEmpty())
                                        <div class="support-empty-state compact-empty-state">
                                            <strong>אין כרגע תור עבודה פתוח</strong>
                                            <p>לידים דחופים, חזרות של היום ולידים חמים יופיעו כאן אוטומטית.</p>
                                        </div>
                                    @else
                                        <div class="domain-info-list">
                                            @foreach ($serviceLeadActionQueue->take(6) as $lead)
                                                <div class="domain-info-row domain-info-row-stack">
                                                    <div>
                                                        <span>{{ $lead->user_name ?? 'ללא שם' }} · {{ $serviceCatalog[$lead->service_type]['label'] ?? $lead->service_type }}</span>
                                                        <small class="meta-note">{{ $lead->next_step_label }}</small>
                                                    </div>
                                                    <strong>{{ $lead->follow_up_label }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">חוזרים ומתחממים</p>
                                            <h2>פניות חוזרות שצריך לזהות מהר</h2>
                                        </div>
                                    </div>
                                    @if (($serviceLeadRepeatSummary ?? collect())->isEmpty())
                                        <div class="support-empty-state compact-empty-state">
                                            <strong>עדיין אין דפוסי חזרה בולטים</strong>
                                            <p>כשאותו איש קשר או אותו אתר יחזרו יותר מפעם אחת, תראה את זה כאן וגם על כרטיסי הלידים עצמם.</p>
                                        </div>
                                    @else
                                        <div class="domain-info-list">
                                            @foreach (($serviceLeadRepeatSummary ?? collect()) as $item)
                                                <div class="domain-info-row">
                                                    <span>{{ $item['label'] }}</span>
                                                    <strong>{{ $item['count'] }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">קשר עסקי קיים</p>
                                            <h2>לקוחות חוזרים והזדמנויות הרחבה</h2>
                                        </div>
                                    </div>
                                    @if (($serviceLeadRelationshipSummary ?? collect())->isEmpty())
                                        <div class="support-empty-state compact-empty-state">
                                            <strong>עדיין אין היסטוריה עסקית חוזרת</strong>
                                            <p>כשלידים יחזרו אחרי זכייה או יבקשו יותר משירות אחד, תראה כאן מיד את הזדמנויות ההרחבה.</p>
                                        </div>
                                    @else
                                        <div class="domain-info-list">
                                            @foreach (($serviceLeadRelationshipSummary ?? collect()) as $item)
                                                <div class="domain-info-row">
                                                    <span>{{ $item['label'] }}</span>
                                                    <strong>{{ $item['count'] }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">מה למכור עכשיו</p>
                                            <h2>הצעות המשך שחוזרות הכי הרבה</h2>
                                        </div>
                                    </div>
                                    @if (($serviceLeadNextServiceSummary ?? collect())->isEmpty())
                                        <div class="support-empty-state compact-empty-state">
                                            <strong>עדיין אין מספיק מידע להצעות המשך</strong>
                                            <p>כשיצטברו יותר לידים חוזרים ושירותים שונים לכל קשר, תראה כאן מה הכי נכון להציע הלאה.</p>
                                        </div>
                                    @else
                                        <div class="domain-info-list">
                                            @foreach (($serviceLeadNextServiceSummary ?? collect())->take(6) as $item)
                                                <div class="domain-info-row">
                                                    <span>{{ $item['label'] }}</span>
                                                    <strong>{{ $item['count'] }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">איך נכון לגשת</p>
                                            <h2>דרכי גישה שחוזרות הכי הרבה</h2>
                                        </div>
                                    </div>
                                    @if (($serviceLeadPlaybookSummary ?? collect())->isEmpty())
                                        <div class="support-empty-state compact-empty-state">
                                            <strong>עדיין אין מספיק מידע לדרכי גישה</strong>
                                            <p>ככל שיצטברו יותר לידים, המערכת תציע אם נכון להתחיל בשיחת גילוי, הצעה מהירה, חימום או הרחבה.</p>
                                        </div>
                                    @else
                                        <div class="domain-info-list">
                                            @foreach (($serviceLeadPlaybookSummary ?? collect())->take(6) as $item)
                                                <div class="domain-info-row">
                                                    <span>{{ $item['label'] }}</span>
                                                    <strong>{{ $item['count'] }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">מאיזה ערוץ להתחיל</p>
                                            <h2>ערוצי פנייה מומלצים</h2>
                                        </div>
                                    </div>
                                    @if (($serviceLeadChannelSummary ?? collect())->isEmpty())
                                        <div class="support-empty-state compact-empty-state">
                                            <strong>עדיין אין מספיק מידע על ערוצי פנייה</strong>
                                            <p>ברגע שיהיו לידים עם ערוץ מועדף, דחיפות וקשר עסקי, תראה כאן אם נכון להתחיל בטלפון, ווטסאפ או מייל.</p>
                                        </div>
                                    @else
                                        <div class="domain-info-list">
                                            @foreach (($serviceLeadChannelSummary ?? collect())->take(6) as $item)
                                                <div class="domain-info-row">
                                                    <span>{{ $item['label'] }}</span>
                                                    <strong>{{ $item['count'] }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">התיישנות ונגיעה</p>
                                            <h2>גיל לידים ותקיעות בטיפול</h2>
                                        </div>
                                    </div>
                                    <div class="domain-info-list">
                                        @foreach (($serviceLeadAgeSummary ?? collect()) as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }}</span>
                                                <strong>{{ $item['count'] }}</strong>
                                            </div>
                                        @endforeach
                                        @foreach (($serviceLeadInactivitySummary ?? collect()) as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }}</span>
                                                <strong>{{ $item['count'] }}</strong>
                                            </div>
                                        @endforeach
                                    </div>
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">SLA תגובה</p>
                                            <h2>מגע ראשון בלידים</h2>
                                        </div>
                                    </div>
                                    <div class="domain-info-list">
                                        @foreach (($serviceLeadFirstTouchSummary ?? collect()) as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }}</span>
                                                <strong>{{ $item['count'] }}</strong>
                                            </div>
                                        @endforeach
                                    </div>
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">שווי צנרת</p>
                                            <h2>פוטנציאל עסקי משוער</h2>
                                        </div>
                                    </div>
                                    <div class="domain-info-list">
                                        <div class="domain-info-row">
                                            <span>שווי לידים כולל</span>
                                            <strong>{{ \App\Models\ServiceLead::formatCurrencyShort($serviceLeadValueSummary['pipeline_total'] ?? 0) }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>שווי משוקלל</span>
                                            <strong>{{ \App\Models\ServiceLead::formatCurrencyShort($serviceLeadValueSummary['weighted_pipeline_total'] ?? 0) }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>נסגרו כלקוח</span>
                                            <strong>{{ \App\Models\ServiceLead::formatCurrencyShort($serviceLeadValueSummary['won_total'] ?? 0) }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>חם כרגע</span>
                                            <strong>{{ \App\Models\ServiceLead::formatCurrencyShort($serviceLeadValueSummary['hot_total'] ?? 0) }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>לידים עם שווי</span>
                                            <strong>{{ $serviceLeadValueSummary['budgeted_count'] ?? 0 }}</strong>
                                        </div>
                                    </div>
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">ביצועי צנרת</p>
                                            <h2>המרה לפי מקור ושירות</h2>
                                        </div>
                                    </div>
                                    <div class="domain-info-list">
                                        <div class="domain-info-row">
                                            <span>אחוז סגירה כולל</span>
                                            <strong>{{ $serviceLeadPerformanceSummary['win_rate'] ?? '0%' }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>הגיעו לשלב רלוונטי+</span>
                                            <strong>{{ $serviceLeadPerformanceSummary['qualified_rate'] ?? '0%' }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>הגיעו להצעה+</span>
                                            <strong>{{ $serviceLeadPerformanceSummary['proposal_rate'] ?? '0%' }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>חלק האתר הציבורי</span>
                                            <strong>{{ $serviceLeadPerformanceSummary['public_share'] ?? '0%' }}</strong>
                                        </div>
                                        @foreach (($serviceLeadSourcePerformance ?? collect())->take(2) as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }} · {{ $item['count'] }} לידים</span>
                                                <strong>{{ $item['rate'] }}</strong>
                                            </div>
                                        @endforeach
                                        @foreach (($serviceLeadServicePerformance ?? collect())->take(2) as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }} · {{ $item['count'] }}</span>
                                                <strong>{{ $item['rate'] }} · {{ \App\Models\ServiceLead::formatCurrencyShort($item['weighted']) }}</strong>
                                            </div>
                                        @endforeach
                                    </div>
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">למה מאבדים לידים</p>
                                            <h2>חסמים וסיבות סגירה</h2>
                                        </div>
                                    </div>
                                    @if (($serviceLeadCloseReasonSummary ?? collect())->isEmpty())
                                        <div class="support-empty-state compact-empty-state">
                                            <strong>עדיין אין סיבות סגירה מתועדות</strong>
                                            <p>ברגע שתתחיל לתייג חסמים על לידים, תראה כאן מה עוצר עסקאות באמת.</p>
                                        </div>
                                    @else
                                        <div class="domain-info-list">
                                            @foreach (($serviceLeadCloseReasonSummary ?? collect()) as $item)
                                                <div class="domain-info-row">
                                                    <span>{{ $item['label'] }}</span>
                                                    <strong>{{ $item['count'] }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">מה עוצר טיפול</p>
                                            <h2>חסמים תפעוליים חוזרים</h2>
                                        </div>
                                    </div>
                                    @if (($serviceLeadOperationalBlockerSummary ?? collect())->isEmpty())
                                        <div class="support-empty-state compact-empty-state">
                                            <strong>אין כרגע חסמים תפעוליים בולטים</strong>
                                            <p>ברגע שיצטברו לידים בלי מטפל, בלי תקציב, בלי דומיין או בלי מועד חזרה, תראה אותם כאן.</p>
                                        </div>
                                    @else
                                        <div class="domain-info-list">
                                            @foreach (($serviceLeadOperationalBlockerSummary ?? collect()) as $item)
                                                <div class="domain-info-row">
                                                    <span>{{ $item['label'] }}</span>
                                                    <strong>{{ $item['count'] }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">מקורות צמיחה</p>
                                            <h2>מאיפה הלידים מגיעים</h2>
                                        </div>
                                    </div>
                                    <div class="domain-info-list">
                                        @foreach ($serviceLeadSourceSummary as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }}</span>
                                                <strong>{{ $item['count'] }}</strong>
                                            </div>
                                        @endforeach
                                        @foreach ($serviceLeadEntrySummary->take(4) as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }}</span>
                                                <strong>{{ $item['count'] }}</strong>
                                            </div>
                                        @endforeach
                                    </div>
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">איכות מסחרית</p>
                                            <h2>כמה הזדמנויות חמות יש</h2>
                                        </div>
                                    </div>
                                    <div class="domain-info-list">
                                        @foreach ($serviceLeadOpportunitySummary as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }}</span>
                                                <strong>{{ $item['count'] }}</strong>
                                            </div>
                                        @endforeach
                                        @foreach ($serviceLeadBusinessSummary as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }}</span>
                                                <strong>{{ $item['count'] }}</strong>
                                            </div>
                                        @endforeach
                                        @foreach ($serviceLeadTeamSummary as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }}</span>
                                                <strong>{{ $item['count'] }}</strong>
                                            </div>
                                        @endforeach
                                        @foreach ($serviceLeadTimeframeSummary as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }}</span>
                                                <strong>{{ $item['count'] }}</strong>
                                            </div>
                                        @endforeach
                                        @foreach ($serviceLeadBudgetSummary as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }}</span>
                                                <strong>{{ $item['count'] }}</strong>
                                            </div>
                                        @endforeach
                                        @foreach ($serviceLeadUrgencySummary as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }}</span>
                                                <strong>{{ $item['count'] }}</strong>
                                            </div>
                                        @endforeach
                                        @foreach ($serviceLeadCallbackWindowSummary as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }}</span>
                                                <strong>{{ $item['count'] }}</strong>
                                            </div>
                                        @endforeach
                                    </div>
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">צנרת מכירה</p>
                                            <h2>איפה הלידים עומדים עכשיו</h2>
                                        </div>
                                    </div>
                                    <div class="domain-info-list">
                                        @foreach ($serviceLeadStageSummary as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }}</span>
                                                <strong>{{ $item['count'] }}</strong>
                                            </div>
                                        @endforeach
                                    </div>
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">שירותים מבוקשים</p>
                                            <h2>מה הכי מביא עניין עכשיו</h2>
                                        </div>
                                    </div>
                                    <div class="domain-info-list">
                                        @foreach ($serviceLeadServiceSummary->take(6) as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }}</span>
                                                <strong>{{ $item['count'] }}</strong>
                                            </div>
                                        @endforeach
                                    </div>
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">חלוקת עבודה</p>
                                            <h2>כמה לידים יש לכל מטפל</h2>
                                        </div>
                                    </div>
                                    <div class="domain-info-list">
                                        @foreach ($serviceLeadAssigneeSummary->take(6) as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }}</span>
                                                <strong>{{ $item['count'] }}</strong>
                                            </div>
                                        @endforeach
                                    </div>
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">איכות שיווקית</p>
                                            <h2>כמה לידים באמת מדידים</h2>
                                        </div>
                                    </div>
                                    <div class="domain-info-list">
                                        @foreach ($serviceLeadMarketingSummary as $item)
                                            <div class="domain-info-row">
                                                <span>{{ $item['label'] }}</span>
                                                <strong>{{ $item['count'] }}</strong>
                                            </div>
                                        @endforeach
                                    </div>
                                </article>

                                <article class="portal-content-card">
                                    <div class="portal-card-head">
                                        <div>
                                            <p class="eyebrow">עניין לפי שירות</p>
                                            <h2>מה מעניין יותר עכשיו</h2>
                                        </div>
                                    </div>
                                    <div class="domain-info-list">
                                        @foreach ($serviceCatalog as $serviceKey => $service)
                                            <div class="domain-info-row">
                                                <span>{{ $service['label'] }}</span>
                                                <strong>{{ $adminServiceLeads->where('service_type', $serviceKey)->count() }}</strong>
                                            </div>
                                        @endforeach

                                        <div class="domain-info-row">
                                            <span>חדשים</span>
                                            <strong>{{ $adminServiceLeads->where('status', 'new')->count() }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>בהצעה</span>
                                            <strong>{{ $adminServiceLeads->where('status', 'proposal')->count() }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>נסגרו כלקוח</span>
                                            <strong>{{ $adminServiceLeads->where('status', 'won')->count() }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>מהאתר הציבורי</span>
                                            <strong>{{ $adminServiceLeads->where('source', 'public')->count() }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>מתוך הדשבורד</span>
                                            <strong>{{ $adminServiceLeads->where('source', 'dashboard')->count() }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>עניין במוצרים הבאים</span>
                                            <strong>{{ $adminServiceLeads->where('service_type', 'ecosystem_access')->count() }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>דורשים חזרה</span>
                                            <strong>{{ $adminServiceLeads->where('freshness_key', 'stale')->count() }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>לחזור היום</span>
                                            <strong>{{ $adminServiceLeads->where('follow_up_tone', 'good')->count() }}</strong>
                                        </div>
                                    </div>
                                </article>
                            </aside>
                        </section>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
