@extends('layouts.app')

@php($title = 'תמיכה טכנית במערכת | A11Y Bridge')
@php($domainLabel = parse_url($site->domain, PHP_URL_HOST) ?: $site->domain)

@section('content')
    <section class="domain-shell">
        @include('partials.domain-sidebar', ['activeSection' => 'support'])

        <div class="domain-shell-main">
            <section class="domain-shell-header">
                <h1>תמיכה טכנית במערכת</h1>
            </section>

            <section class="domain-card domain-hero-card">
                <div>
                    <p class="eyebrow">תמיכה טכנית</p>
                    <h2>{{ $site->site_name }}</h2>
                    <p class="panel-intro">מכאן פותחים פניות טכניות על המערכת עצמה: הטמעה, רישיון, טעינת הווידג׳ט, או שמירת הגדרות. כל פנייה נשמרת בהקשר של הרישיון והדומיין הנכון.</p>
                </div>

                <div class="billing-hero-meta">
                    <span class="status-pill is-neutral">פניות פתוחות: {{ $supportSummary['open'] }}</span>
                    <span class="status-pill {{ $supportSummary['urgent'] > 0 ? 'is-warn' : 'is-good' }}">דחופות: {{ $supportSummary['urgent'] }}</span>
                    <span class="status-pill is-neutral">פעילות אחרונה: {{ $supportSummary['lastActivity'] }}</span>
                </div>
            </section>

            <section class="dashboard-workspace dashboard-workspace-inline domain-tab-workspace" data-dashboard-tabs>
                <div class="dashboard-tab-content">
                    <div class="dashboard-tab-nav domain-inline-tab-nav" aria-label="לשוניות מרכז התמיכה">
                        <button class="dashboard-tab-button is-active" type="button" data-dashboard-tab-button="new-ticket">פנייה חדשה</button>
                        <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="tickets">הפניות שלי</button>
                        <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="guide">מה חשוב לדעת</button>
                    </div>

                    <div class="dashboard-tab-panel is-active" data-dashboard-tab-panel="new-ticket">
                        <section class="support-grid">
                            <section class="domain-card support-form-card">
                                <div class="domain-card-head">
                                    <div>
                                        <h2>פתח פנייה חדשה</h2>
                                        <p class="panel-intro">ככל שתתאר את הבעיה בצורה מדויקת יותר, יהיה קל יותר לקשר אותה להתקנה, לרישיון או להגדרות של הווידג׳ט.</p>
                                    </div>
                                </div>

                                @if ($supportUsesRuntimeFallback ?? false)
                                    <div class="support-empty-state">
                                        <strong>מרכז התמיכה פועל כרגע במצב גיבוי</strong>
                                        <p>הפניות יישמרו ויהיו זמינות גם בלי מיגרציית הטבלה, ובחזרה למסד הן כבר יעברו לשמירה מלאה.</p>
                                    </div>
                                @endif

                                <form class="stack-form" method="POST" action="{{ route('dashboard.support.store', ['site' => $site->id]) }}">
                                    @csrf
                                    <input type="hidden" name="site_id" value="{{ old('site_id', $site->id) }}">

                                    <label for="support_subject">נושא הפנייה</label>
                                    <input id="support_subject" name="subject" type="text" value="{{ old('subject') }}" placeholder="למשל: הווידג׳ט לא נטען באתר במובייל">

                                    <div class="support-form-row">
                                        <div>
                                            <label for="support_category">סוג הפנייה</label>
                                            <select id="support_category" name="category">
                                                @foreach ($supportCategories as $categoryKey => $categoryLabel)
                                                    <option value="{{ $categoryKey }}" @selected(old('category') === $categoryKey)>{{ $categoryLabel }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label for="support_priority">עדיפות</label>
                                            <select id="support_priority" name="priority">
                                                @foreach ($supportPriorityLabels as $priorityKey => $priorityLabel)
                                                    <option value="{{ $priorityKey }}" @selected(old('priority', 'normal') === $priorityKey)>{{ $priorityLabel }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <label for="support_message">תיאור מלא</label>
                                    <textarea id="support_message" name="message" rows="7" placeholder="מה בדיוק קרה, באיזה דף, מה ציפית לראות, ומה הופיע בפועל?">{{ old('message') }}</textarea>

                                    <div class="support-form-actions">
                                        <button class="primary-button" type="submit">פתח פנייה</button>
                                        <span class="meta-note">התמיכה כאן היא תמיכה טכנית במערכת בלבד, ולא שירות ייעוץ או ליווי נגישות.</span>
                                    </div>
                                </form>
                            </section>

                            <aside class="support-side-stack">
                                <section class="domain-card">
                                    <h2>מצב התמיכה</h2>
                                    <div class="domain-info-list">
                                        <div class="domain-info-row">
                                            <span>פניות פתוחות</span>
                                            <strong>{{ $supportSummary['open'] }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>פניות שנסגרו</span>
                                            <strong>{{ $supportSummary['resolved'] }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>פעילות אחרונה</span>
                                            <strong>{{ $supportSummary['lastActivity'] }}</strong>
                                        </div>
                                    </div>
                                </section>
                            </aside>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="tickets">
                        <section class="domain-card">
                            <div class="domain-card-head">
                                <div>
                                <h2>הפניות של האתר הזה</h2>
                                <p class="panel-intro">רשימת כל הטיקטים הטכניים שנפתחו עבור {{ $domainLabel }}. אם תעבור לאתר אחר דרך הסוויצ׳ר, תראה את הפניות של אותו אתר.</p>
                                </div>
                            </div>

                            @if ($supportTickets->isEmpty())
                                <div class="support-empty-state">
                                <strong>עדיין אין פניות פעילות</strong>
                                <p>ברגע שתפתח פנייה ראשונה, היא תופיע כאן עם סטטוס, עדיפות ומספר טיקט מסודר.</p>
                                </div>
                            @else
                                <div class="support-ticket-list">
                                    @foreach ($supportTickets as $ticket)
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
                                                {{ $supportCategories[$ticket->category] ?? $ticket->category }} ·
                                                {{ $ticket->last_activity_label }} ·
                                                {{ $ticket->site_name ?? $site->site_name }}
                                            </p>

                                            <p class="support-ticket-message">{{ $ticket->message }}</p>
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="guide">
                        <section class="support-grid">
                            <section class="domain-card">
                                <h2>מה חשוב לדעת</h2>
                                <div class="domain-info-list">
                                    <div class="domain-info-row">
                                        <span>דף</span>
                                        <strong>URL מדויק שבו רואים את הבעיה</strong>
                                    </div>
                                    <div class="domain-info-row">
                                        <span>מכשיר</span>
                                        <strong>דסקטופ, מובייל, דפדפן או מערכת</strong>
                                    </div>
                                    <div class="domain-info-row">
                                    <span>הקשר</span>
                                    <strong>רישיון, הטמעה, עיצוב וידג׳ט או בדיקה</strong>
                                </div>
                            </div>
                        </section>

                        <aside class="support-side-stack">
                            <section class="domain-card">
                                <h2>היקף התמיכה</h2>
                                <p class="panel-intro">התמיכה מיועדת לשימוש במערכת עצמה: התקנה, רישיון, טעינת הווידג׳ט, שמירת הגדרות ועמודי החשבון. היא אינה כוללת ייעוץ, תיקון או ליווי נגישות.</p>
                            </section>
                        </aside>
                        </section>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
