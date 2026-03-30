@extends('layouts.app')

@php($title = 'Account & Billing | A11Y Bridge')

@section('content')
    <section class="dashboard-header">
        <div>
            <p class="eyebrow">Account & Billing</p>
            <h1>כל מה שהלקוח צריך כדי להבין מי הוא, מה מחובר, ואיך השירות ממוסגר.</h1>
            <p class="hero-text">
                המסך הזה מאחד בין פרטי החברה, מצב האתר, plan framing וערוצי support. הוא צריך
                להרגיש כמו אזור ניהול אמיתי ולא כמו הגדרות זמניות.
            </p>

            <div class="metric-grid">
                <div class="metric-card">
                    <strong>Primary workspace</strong>
                    <span>{{ $site->site_name }}</span>
                </div>
                <div class="metric-card">
                    <strong>Contact owner</strong>
                    <span>{{ $user->contact_email }}</span>
                </div>
                <div class="metric-card">
                    <strong>Hosted plan</strong>
                    <span>{{ $currentPlan['name'] }}</span>
                </div>
            </div>
        </div>

        <div class="code-card">
            <span class="meta-label">Workspace summary</span>
            <strong>{{ $user->name }}</strong>
            <div class="status-grid">
                <div class="status-card">
                    <span class="status-pill is-neutral">{{ $currentPlan['name'] }}</span>
                    <p>{{ $currentPlan['description'] }}</p>
                </div>
                <div class="status-card">
                    <span class="status-pill {{ $statementStatus === 'connected' ? 'is-good' : 'is-warn' }}">
                        {{ $statementStatus === 'connected' ? 'Statement connected' : 'Statement missing' }}
                    </span>
                    <p>הדומיין הפעיל: <code>{{ $site->domain }}</code></p>
                </div>
            </div>
        </div>
    </section>

    <section class="dashboard-grid">
        <section class="panel-card">
            <p class="eyebrow">Profile</p>
            <h2>פרטי חברה ולקוח</h2>

            <div class="spec-list">
                <div class="spec-row">
                    <span>שם החברה</span>
                    <strong>{{ $user->name }}</strong>
                </div>
                <div class="spec-row">
                    <span>אימייל קשר</span>
                    <strong>{{ $user->contact_email }}</strong>
                </div>
                <div class="spec-row">
                    <span>אתר ראשי</span>
                    <strong>{{ $site->site_name }}</strong>
                </div>
                <div class="spec-row">
                    <span>דומיין</span>
                    <strong>{{ $site->domain }}</strong>
                </div>
                <div class="spec-row">
                    <span>Site key</span>
                    <strong>{{ $site->public_key }}</strong>
                </div>
            </div>
        </section>

        <aside class="panel-card">
            <p class="eyebrow">Plan framing</p>
            <h2>מסלול נוכחי והרחבה מומלצת</h2>

            <div class="plan-card plan-card-current">
                <span class="status-pill is-neutral">{{ $currentPlan['price'] }}</span>
                <strong>{{ $currentPlan['name'] }}</strong>
                <p>{{ $currentPlan['description'] }}</p>
            </div>

            <div class="plan-card">
                <span class="status-pill is-good">Recommended next layer</span>
                <strong>{{ $recommendedPlan['name'] }}</strong>
                <p>{{ $recommendedPlan['description'] }}</p>
            </div>
        </aside>
    </section>

    <section class="dashboard-grid dashboard-grid-secondary">
        <section class="panel-card">
            <p class="eyebrow">Support</p>
            <h2>מה הלקוח מקבל מסביב למוצר</h2>

            <ul class="check-list">
                <li>תמיכה בחיבור widget והטמעה ראשונית.</li>
                <li>עזרה בחיבור הצהרת נגישות למסך ה־widget.</li>
                <li>ליווי בהבנת ההבדל בין widget לבין audit ותיקונים.</li>
                <li>בסיס טוב להרחבה עתידית ל־billing, plans ו־multi-site.</li>
            </ul>
        </section>

        <aside class="panel-card">
            <p class="eyebrow">Next actions</p>
            <h2>מה כדאי לעשות עכשיו</h2>

            <div class="status-grid">
                <div class="status-card">
                    <span class="status-pill is-neutral">1. Finish widget setup</span>
                    <p>לסגור צבע, טקסט, שפה ומיקום ב־dashboard הראשי.</p>
                </div>
                <div class="status-card">
                    <span class="status-pill is-neutral">2. Verify install</span>
                    <p>לעבור ל־Install Center ולוודא שהשינוי נמשך באתר בלי החלפת קוד.</p>
                </div>
                <div class="status-card">
                    <span class="status-pill {{ $statementStatus === 'connected' ? 'is-good' : 'is-warn' }}">
                        3. Compliance framing
                    </span>
                    <p>להוסיף statement אם חסר, ולמסגר נכון את השירות במסך compliance.</p>
                </div>
            </div>
        </aside>
    </section>
@endsection
