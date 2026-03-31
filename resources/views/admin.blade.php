@extends('layouts.app')

@php($title = 'מרכז סופר־אדמין | A11Y Bridge')

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
                        </div>
                    </div>
                </aside>

                <div class="dashboard-tab-content">
                    <div class="dashboard-tab-panel is-active" data-dashboard-tab-panel="overview">
                        <section class="domain-card domain-hero-card">
                            <div>
                                <p class="eyebrow">גישה מלאה למערכת</p>
                                <h2>ניהול משתמשים, פניות, אתרים וקודי מעקב גלובליים</h2>
                                <p class="panel-intro">מכאן אפשר לעזור ללקוחות, לעקוב אחרי פניות פתוחות, לראות את כל האתרים במערכת ולהטמיע קודי אנליטיקס ומעקב לכל האתר.</p>
                            </div>

                            <div class="billing-hero-meta">
                                <span class="status-pill is-neutral">משתמשים: {{ $adminSummary['users'] }}</span>
                                <span class="status-pill is-neutral">אתרים: {{ $adminSummary['sites'] }}</span>
                                <span class="status-pill {{ $adminSummary['tickets_open'] > 0 ? 'is-warn' : 'is-good' }}">פניות פתוחות: {{ $adminSummary['tickets_open'] }}</span>
                                <span class="status-pill is-good">אתרים פעילים: {{ $adminSummary['active_sites'] }}</span>
                            </div>
                        </section>

                        <section class="licenses-lower-grid super-admin-grid">
                            <article class="portal-content-card">
                                <div>
                                    <p class="eyebrow">משתמשים ואתרים</p>
                                    <h2>תמונת מצב מהירה</h2>
                                </div>

                                <div class="domain-info-list">
                                    @foreach ($adminUsers->take(8) as $adminUser)
                                        <div class="domain-info-row">
                                            <span>{{ $adminUser->name }} · {{ $adminUser->email }}</span>
                                            <strong>{{ $adminUser->sites_count }} אתרים / {{ $adminUser->support_tickets_count }} פניות</strong>
                                        </div>
                                    @endforeach
                                </div>
                            </article>

                            <article class="portal-content-card">
                                <div>
                                    <p class="eyebrow">מרכז שליטה</p>
                                    <h2>פעולות מהירות</h2>
                                </div>
                                <div class="domain-info-list">
                                    <div class="domain-info-row">
                                        <span>עריכת קודי מעקב גלובליים</span>
                                        <button class="secondary-button" type="button" data-dashboard-tab-link="tracking">פתח</button>
                                    </div>
                                    <div class="domain-info-row">
                                        <span>מעבר על כל המשתמשים</span>
                                        <button class="secondary-button" type="button" data-dashboard-tab-link="users">פתח</button>
                                    </div>
                                    <div class="domain-info-row">
                                        <span>ניהול כל האתרים והרישיונות</span>
                                        <button class="secondary-button" type="button" data-dashboard-tab-link="sites">פתח</button>
                                    </div>
                                    <div class="domain-info-row">
                                        <span>טיפול בטיקטים פתוחים</span>
                                        <button class="secondary-button" type="button" data-dashboard-tab-link="support">פתח</button>
                                    </div>
                                </div>
                            </article>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="tracking">
                        <article class="portal-content-card">
                            <div>
                                <p class="eyebrow">קודי מעקב גלובליים</p>
                                <h2>אנליטיקס, GTM, פיקסל וקודים מותאמים</h2>
                            </div>
                            <p class="panel-intro">הקודים כאן נטענים אוטומטית באתר הציבורי, במסכי ההתחברות וההרשמה, ובכל מקום שבו הפלטפורמה רצה.</p>

                            <form class="stack-form" method="POST" action="{{ route('dashboard.super-admin.tracking') }}">
                                @csrf

                                <label for="google_analytics_head">Google Analytics / gtag בראש העמוד</label>
                                <textarea id="google_analytics_head" name="google_analytics_head" rows="4">{{ old('google_analytics_head', $trackingScripts['google_analytics_head'] ?? '') }}</textarea>

                                <label for="google_tag_manager_head">Google Tag Manager בראש העמוד</label>
                                <textarea id="google_tag_manager_head" name="google_tag_manager_head" rows="4">{{ old('google_tag_manager_head', $trackingScripts['google_tag_manager_head'] ?? '') }}</textarea>

                                <label for="google_tag_manager_body">Google Tag Manager בתחילת ה־body</label>
                                <textarea id="google_tag_manager_body" name="google_tag_manager_body" rows="4">{{ old('google_tag_manager_body', $trackingScripts['google_tag_manager_body'] ?? '') }}</textarea>

                                <label for="meta_pixel_head">Meta Pixel / Facebook Pixel</label>
                                <textarea id="meta_pixel_head" name="meta_pixel_head" rows="4">{{ old('meta_pixel_head', $trackingScripts['meta_pixel_head'] ?? '') }}</textarea>

                                <label for="custom_head_scripts">קודים נוספים ל־head</label>
                                <textarea id="custom_head_scripts" name="custom_head_scripts" rows="5">{{ old('custom_head_scripts', $trackingScripts['custom_head_scripts'] ?? '') }}</textarea>

                                <label for="custom_body_scripts">קודים נוספים ל־body</label>
                                <textarea id="custom_body_scripts" name="custom_body_scripts" rows="5">{{ old('custom_body_scripts', $trackingScripts['custom_body_scripts'] ?? '') }}</textarea>

                                <button class="primary-button" type="submit">שמור קודי מעקב</button>
                            </form>
                        </article>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="users">
                        <section class="domain-card">
                            <div class="domain-card-head">
                                <div>
                                    <h2>כל המשתמשים במערכת</h2>
                                    <p class="panel-intro">רשימה מהירה של בעלי החשבונות, כדי להבין למי יש אתרים פעילים ולמי יש פניות פתוחות.</p>
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
                                            <tr>
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
                        <section class="domain-card">
                            <div class="domain-card-head">
                                <div>
                                    <h2>כל האתרים והרישיונות</h2>
                                    <p class="panel-intro">כאן רואים מי בעל החשבון, איזה אתר פעיל, ומה מצב הרישיון וההטמעה.</p>
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
                                            <tr>
                                                <td data-label="אתר">{{ $adminSite->site_name }}</td>
                                                <td data-label="דומיין">{{ parse_url($adminSite->domain, PHP_URL_HOST) ?: $adminSite->domain }}</td>
                                                <td data-label="בעלים">{{ $adminSite->user?->email ?? 'לא ידוע' }}</td>
                                                <td data-label="רישיון">
                                                    <span class="status-pill {{ ($adminSite->license_status ?? 'active') === 'active' ? 'is-good' : 'is-warn' }}">
                                                        {{ ($adminSite->license_status ?? 'active') === 'active' ? 'פעיל' : 'לא פעיל' }}
                                                    </span>
                                                </td>
                                                <td data-label="הטמעה">
                                                    <span class="status-pill {{ filled($adminSite->last_seen_at) ? 'is-good' : 'is-neutral' }}">
                                                        {{ filled($adminSite->last_seen_at) ? 'זוהתה טעינה' : 'ממתין להטמעה' }}
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
                        <section class="domain-card">
                            <div class="domain-card-head">
                                <div>
                                    <h2>מרכז פניות כולל</h2>
                                    <p class="panel-intro">כאן אתה מקבל את כל הטיקטים שנפתחו במערכת, ויכול לענות, לסמן סטטוס ולעזור למשתמשים.</p>
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
                                        <article class="support-ticket-card">
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
                        </section>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
