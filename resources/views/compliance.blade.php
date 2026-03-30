@extends('layouts.app')

@php($title = 'Compliance Center | A11Y Bridge')

@section('content')
    <section class="dashboard-header">
        <div>
            <p class="eyebrow">Compliance Center</p>
            <h1>Governance ברור, בלי להבטיח ללקוח מה שהמוצר לא יכול להבטיח.</h1>
            <p class="hero-text">
                המסך הזה מחבר בין ה־widget, הצהרת הנגישות ומסלול השירות. הוא נועד למסגר את
                הפלטפורמה נכון: שכבת ניהול, העדפות וגישה למידע, לא "כפתור קסם" שמחליף בדיקות ותיקוני קוד.
            </p>

            <div class="metric-grid">
                <div class="metric-card">
                    <strong>Service mode</strong>
                    <span>{{ $serviceModeLabel }}</span>
                </div>
                <div class="metric-card">
                    <strong>Statement status</strong>
                    <span>{{ $statementStatus === 'connected' ? 'Connected' : 'Missing' }}</span>
                </div>
                <div class="metric-card">
                    <strong>Active preferences</strong>
                    <span>{{ $featureCount }} widget controls enabled</span>
                </div>
            </div>
        </div>

        <div class="code-card">
            <span class="meta-label">Current governance state</span>
            <strong>{{ $site->site_name }}</strong>
            <div class="status-grid">
                <div class="status-card">
                    <span class="status-pill {{ $statementStatus === 'connected' ? 'is-good' : 'is-warn' }}">
                        {{ $statementStatus === 'connected' ? 'Statement connected' : 'Statement missing' }}
                    </span>
                    <p>הצהרת נגישות צריכה להיות זמינה וקלה למציאה מתוך ה־widget.</p>
                </div>
                <div class="status-card">
                    <span class="status-pill is-neutral">{{ $serviceModeLabel }}</span>
                    <p>זה המסלול שמגדיר אם אתם רק עושים audit, מוסיפים safe fixes, או שירות מנוהל.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="dashboard-grid">
        <section class="panel-card">
            <p class="eyebrow">Platform message</p>
            <h2>הניסוח הנכון ללקוח</h2>

            <ul class="check-list">
                <li>ה־widget מספק העדפות תצוגה, גישה להצהרת נגישות ושכבת governance.</li>
                <li>ציות מלא תלוי גם בקוד האתר, בתוכן ובבדיקות ידניות.</li>
                <li>Audit ותיקונים נשארים שכבה נפרדת מהממשק עצמו.</li>
                <li>המערכת תומכת בתהליך compliance, לא מחליפה אותו.</li>
            </ul>

            <div class="info-card info-card-tight">
                <h3>המלצת מוצר</h3>
                <p>
                    השאירו את ה־widget קל וברור, ואת ההבטחות המשפטיות והמקצועיות במסכי compliance,
                    ב־statement ובשירות שמסביב.
                </p>
            </div>
        </section>

        <aside class="panel-card">
            <p class="eyebrow">Current setup</p>
            <h2>מה חסר כרגע</h2>

            <div class="status-grid">
                <div class="status-card">
                    <span class="status-pill {{ $site->statement_url ? 'is-good' : 'is-warn' }}">
                        {{ $site->statement_url ? 'Statement linked' : 'Add statement URL' }}
                    </span>
                    <p>
                        @if ($site->statement_url)
                            הלקוח כבר חיבר הצהרת נגישות ויכול להפנות אליה מתוך ה־widget.
                        @else
                            עדיין אין קישור להצהרת נגישות. כדאי להוסיף אותו בדשבורד הראשי.
                        @endif
                    </p>
                </div>

                <div class="status-card">
                    <span class="status-pill is-neutral">Hosted widget active</span>
                    <p>הקוד באתר נשאר קבוע וכל שינוי נמשך דרך השרת לפי <code>site key</code>.</p>
                </div>
            </div>

            <a class="copy-button cta-link" href="{{ route('dashboard') }}">חזרה להגדרות הראשיות</a>
        </aside>
    </section>
@endsection
