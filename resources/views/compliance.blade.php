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
                    <p class="eyebrow">Compliance center</p>
                    <h2>ציון {{ $auditSnapshot['score'] }} · {{ $lastAuditedLabel }}</h2>
                    <p class="panel-intro">{{ $auditSnapshot['summary'] }}</p>
                </div>

                <form method="POST" action="{{ route('dashboard.compliance.audit', ['site' => $site->id]) }}">
                    @csrf
                    <input type="hidden" name="site_id" value="{{ $site->id }}">
                    <button class="primary-button" type="submit">הרץ בדיקה חדשה</button>
                </form>
            </section>

            @unless($auditActionsAvailable)
                <section class="domain-card">
                    <p class="panel-intro">השרת הזה עדיין בלי עדכון המסד החדש. אפשר ללחוץ על הכפתור, והמערכת תחזיר הודעה מסודרת עד שה־migration יושלם.</p>
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
                    <p>{{ $statementStatus === 'connected' ? 'זמינה מהווידג׳ט ומהחשבון' : 'כדאי לחבר statement URL' }}</p>
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
                            <span>התראת statement חסר</span>
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
                        <p class="panel-intro">השרת הזה עדיין בלי עדכון המסד החדש. אפשר ללחוץ על הכפתור, והמערכת תחזיר הודעה מסודרת עד שה־migration יושלם.</p>
                    @endunless

                    <button class="primary-button" type="submit">שמור התראות</button>
                </form>
            </section>

            <section class="domain-card" id="proof-toolkit">
                <h2>ערכת הוכחת מאמץ</h2>
                <ul class="check-list">
                    <li>לכל אתר יש ציון בדיקה נפרד והתראות נפרדות, לא מצב רוחבי לכל החשבון.</li>
                    <li>אפשר לראות מתי רצה בדיקה לאחרונה והאם בוצעו שינויים מאז.</li>
                    <li>המערכת מבהירה מה מכוסה בווידג׳ט ומה עדיין דורש remediation ובדיקות ידניות.</li>
                </ul>
            </section>
        </div>
    </section>
@endsection
