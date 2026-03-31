@extends('layouts.app')

@php($title = 'Compliance Center | A11Y Bridge')

@section('content')
    <section class="dashboard-header">
        <div>
            <p class="eyebrow">Compliance Center</p>
            <h1>Governance ברור, defensible messaging, ומסגור נכון של המוצר.</h1>
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

    <section class="command-strip" aria-label="Compliance signals">
        <article class="command-card">
            <span class="command-label">Service mode</span>
            <strong>{{ $serviceModeLabel }}</strong>
            <p>המסלול הזה קובע איך לדבר על רמת השירות, הבדיקות והעבודה הידנית מסביב.</p>
        </article>
        <article class="command-card">
            <span class="command-label">Statement</span>
            <strong>{{ $statementStatus === 'connected' ? 'Connected' : 'Missing' }}</strong>
            <p>הצהרת נגישות מחוברת משפרת גם clarity ללקוח וגם עקביות במסר.</p>
        </article>
        <article class="command-card">
            <span class="command-label">Enabled controls</span>
            <strong>{{ $featureCount }}</strong>
            <p>מספר פעולות ה־widget הפעילות כרגע באתר הזה.</p>
        </article>
    </section>

    <section class="dashboard-grid">
        <section class="panel-card">
            <p class="eyebrow">Platform message</p>
            <h2>הניסוח הנכון ללקוח</h2>
            <p class="panel-intro">כאן מנסחים את המוצר כמו פתרון מקצועי: תמיכה ב־compliance, לא claim שמחליף remediation.</p>

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
            <p class="panel-intro">החלק הזה הופך את המסר למעשי: מה קיים כבר, ומה עדיין צריך לסגור כדי שהלקוח יקבל חוויה שלמה.</p>

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

    <section class="dashboard-grid dashboard-grid-secondary">
        <section class="panel-card">
            <p class="eyebrow">Coverage</p>
            <h2>מה הפלטפורמה כן מכסה</h2>

            <ul class="check-list">
                <li>ניהול widget hosted והעדפות תצוגה מתוך dashboard אחד.</li>
                <li>קישור שקוף להצהרת נגישות ו־service framing ברור.</li>
                <li>שכבת install, account, content ו־governance שנראית כמו מוצר SaaS אמיתי.</li>
            </ul>
        </section>

        <aside class="panel-card">
            <p class="eyebrow">Remediation</p>
            <h2>מה עדיין דורש עבודה מחוץ ל־widget</h2>

            <ul class="check-list">
                <li>תיקוני קוד, semantic HTML, labels, flows וטבלאות.</li>
                <li>בדיקות ידניות עם מקלדת, קורא מסך ו־real user testing.</li>
                <li>בקרה שוטפת על תוכן חדש, קבצים והטמעות צד שלישי.</li>
            </ul>
        </aside>
    </section>
@endsection
