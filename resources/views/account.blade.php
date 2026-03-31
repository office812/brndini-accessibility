@extends('layouts.app')

@php($title = 'תוכנית ותשלומים | A11Y Bridge')

@section('content')
    <section class="domain-shell">
        @include('partials.domain-sidebar', ['activeSection' => 'account'])

        <div class="domain-shell-main">
            <section class="domain-shell-header">
                <h1>תוכנית ותשלומים</h1>
            </section>

            <section class="domain-card">
                <h2>{{ parse_url($site->domain, PHP_URL_HOST) ?: $site->domain }}</h2>

                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>מסלול</span>
                        <strong>{{ $currentPlan['name'] }} · שנתי</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>סטטוס</span>
                        <strong><span class="status-pill is-good">פעיל</span></strong>
                    </div>
                    <div class="domain-info-row">
                        <span>מסלול שירות</span>
                        <strong>{{ $serviceModeLabel }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>מפתח אתר ציבורי</span>
                        <strong><code>{{ $site->public_key }}</code></strong>
                    </div>
                </div>
            </section>

            <section class="domain-card">
                <h2>יכולות מתקדמות</h2>

                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>Google Analytics</span>
                        <strong>לא זמין</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>הצהרה מנוהלת</span>
                        <strong>{{ $statementStatus === 'connected' ? 'פעיל' : 'דורש חיבור' }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>סנכרון ווידג׳ט מנוהל</span>
                        <strong>פעיל</strong>
                    </div>
                </div>
            </section>

            <section class="domain-card" id="license-owner">
                <h2>פרטי בעל הרישיון</h2>

                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>חברה</span>
                        <strong>{{ $user->name }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>אימייל ליצירת קשר</span>
                        <strong>{{ $user->contact_email }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>סביבת עבודה</span>
                        <strong>{{ $site->site_name }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>דומיין ראשי</span>
                        <strong>{{ $site->domain }}</strong>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
