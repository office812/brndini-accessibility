@extends('layouts.app')

@php($title = 'Accessibility Statement | A11Y Bridge')

@section('content')
    <section class="domain-shell">
        @include('partials.domain-sidebar', ['activeSection' => 'compliance'])

        <div class="domain-shell-main">
            <section class="domain-shell-header">
                <h1>Accessibility statement</h1>
            </section>

            <section class="domain-card">
                <h2>Current governance state</h2>
                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>Statement status</span>
                        <strong><span class="status-pill {{ $statementStatus === 'connected' ? 'is-good' : 'is-warn' }}">{{ $statementStatus === 'connected' ? 'Connected' : 'Missing' }}</span></strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Statement URL</span>
                        <strong>{{ $site->statement_url ?: 'Not connected yet' }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Service mode</span>
                        <strong>{{ $serviceModeLabel }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Enabled controls</span>
                        <strong>{{ $featureCount }} widget controls enabled</strong>
                    </div>
                </div>
            </section>

            <section class="domain-card" id="remediation-report">
                <h2>Remediation report</h2>
                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>Hosted widget</span>
                        <strong>Active</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Safe fixes framing</span>
                        <strong>{{ $serviceModeLabel }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Manual code review</span>
                        <strong>Recommended outside the widget</strong>
                    </div>
                </div>
            </section>

            <section class="domain-card" id="impact-report">
                <h2>Impact report</h2>
                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>User preferences</span>
                        <strong>Available through hosted widget</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Statement access</span>
                        <strong>{{ $statementStatus === 'connected' ? 'Available in widget flow' : 'Needs connection' }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Compliance framing</span>
                        <strong>Governance support, not full legal guarantee</strong>
                    </div>
                </div>
            </section>

            <section class="domain-card" id="audit-report">
                <h2>Audit report</h2>
                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>Audit coverage</span>
                        <strong>Widget, statement and governance messaging</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>External requirements</span>
                        <strong>Code remediation, QA and content review remain required</strong>
                    </div>
                </div>
            </section>

            <section class="domain-card" id="proof-toolkit">
                <h2>Proof of effort toolkit</h2>
                <p class="panel-intro">החלק הזה נועד למסגר ללקוח מה המערכת כן מכסה ומה עדיין דורש עבודה ידנית מחוץ ל־widget.</p>
                <ul class="check-list">
                    <li>גישה שקופה להצהרת נגישות מתוך ה־widget.</li>
                    <li>Hosted controls והעדפות תצוגה מנוהלות מהפלטפורמה.</li>
                    <li>מסר ברור שציות מלא דורש גם remediation ובדיקות ידניות.</li>
                </ul>
            </section>
        </div>
    </section>
@endsection
