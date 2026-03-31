@extends('layouts.app')

@php($title = 'הצהרת נגישות | A11Y Bridge')

@section('content')
    <section class="domain-shell">
        @include('partials.domain-sidebar', ['activeSection' => 'compliance'])

        <div class="domain-shell-main">
            <section class="domain-shell-header">
                <h1>הצהרת נגישות</h1>
            </section>

            <section class="domain-card">
                <h2>מצב ניהול נוכחי</h2>
                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>סטטוס הצהרה</span>
                        <strong><span class="status-pill {{ $statementStatus === 'connected' ? 'is-good' : 'is-warn' }}">{{ $statementStatus === 'connected' ? 'מחובר' : 'חסר' }}</span></strong>
                    </div>
                    <div class="domain-info-row">
                        <span>קישור להצהרה</span>
                        <strong>{{ $site->statement_url ?: 'עדיין לא חובר' }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>מסלול שירות</span>
                        <strong>{{ $serviceModeLabel }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>פקדים פעילים</span>
                        <strong>{{ $featureCount }} פקדי ווידג׳ט פעילים</strong>
                    </div>
                </div>
            </section>

            <section class="domain-card" id="remediation-report">
                <h2>דוח תיקונים</h2>
                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>ווידג׳ט מנוהל</span>
                        <strong>פעיל</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>מסגור תיקונים בטוחים</span>
                        <strong>{{ $serviceModeLabel }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>בדיקת קוד ידנית</span>
                        <strong>מומלצת מחוץ לווידג׳ט</strong>
                    </div>
                </div>
            </section>

            <section class="domain-card" id="impact-report">
                <h2>דוח השפעה</h2>
                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>העדפות משתמש</span>
                        <strong>זמינות דרך הווידג׳ט המנוהל</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>גישה להצהרה</span>
                        <strong>{{ $statementStatus === 'connected' ? 'זמינה מתוך הווידג׳ט' : 'דורשת חיבור' }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>מסגור ציות</span>
                        <strong>תמיכת governance, לא התחייבות משפטית מלאה</strong>
                    </div>
                </div>
            </section>

            <section class="domain-card" id="audit-report">
                <h2>דוח ביקורת</h2>
                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>כיסוי ביקורת</span>
                        <strong>ווידג׳ט, הצהרה ומסרי governance</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>דרישות חיצוניות</span>
                        <strong>עדיין נדרשים תיקוני קוד, QA ובדיקת תוכן</strong>
                    </div>
                </div>
            </section>

            <section class="domain-card" id="proof-toolkit">
                <h2>ערכת הוכחת מאמץ</h2>
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
