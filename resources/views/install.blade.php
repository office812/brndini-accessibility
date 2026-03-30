@extends('layouts.app')

@php($title = 'Install Center | A11Y Bridge')

@section('content')
    <section class="dashboard-header">
        <div>
            <p class="eyebrow">Install Center</p>
            <h1>התקנה ברורה, בלי לנחש מה מדביקים ואיפה.</h1>
            <p class="hero-text">
                הקוד שלך נשאר קבוע. כל שינוי בדשבורד מעדכן את ה־widget מתוך הפלטפורמה, בלי
                להחליף שוב סקריפט באתר.
            </p>

            <div class="metric-grid">
                <div class="metric-card">
                    <strong>Stable embed</strong>
                    <span>אותו snippet עובד גם אחרי שינויי צבע, שפה ומיקום.</span>
                </div>
                <div class="metric-card">
                    <strong>Hosted config</strong>
                    <span>ה־widget מושך את ההגדרות מהשרת בזמן טעינה.</span>
                </div>
                <div class="metric-card">
                    <strong>Multi-step guidance</strong>
                    <span>הסבר ברור גם ללקוח לא טכני וגם למיישם.</span>
                </div>
            </div>
        </div>

        <div class="code-card">
            <span class="meta-label">Install snippet</span>
            <strong>{{ $site->site_name }}</strong>
            <code id="install-embed-code">{{ $embedCode }}</code>
            <button class="copy-button" type="button" data-copy-target="install-embed-code">העתק קוד הטמעה</button>
            <p class="inline-note">
                מומלץ להדביק לפני הסגירה של <code>&lt;/body&gt;</code> או דרך שדה custom code במערכת האתר.
            </p>
        </div>
    </section>

    <section class="dashboard-grid">
        <section class="panel-card">
            <p class="eyebrow">How to install</p>
            <h2>שלבי התקנה</h2>

            <ol class="step-list">
                <li>
                    <strong>העתק את הסקריפט</strong>
                    <span>השתמש באותו snippet לכל עוד הדומיין הזה נשאר מחובר.</span>
                </li>
                <li>
                    <strong>הדבק באתר</strong>
                    <span>עדיף לפני <code>&lt;/body&gt;</code> או באזור scripts גלובלי.</span>
                </li>
                <li>
                    <strong>רענן את האתר</strong>
                    <span>הכפתור אמור להופיע לפי המיקום והצבע שהוגדרו אצלך בדשבורד.</span>
                </li>
                <li>
                    <strong>בדוק שינוי קטן</strong>
                    <span>שנה צבע או טקסט בדשבורד וודא שהשינוי משתקף בלי להחליף קוד.</span>
                </li>
            </ol>
        </section>

        <aside class="panel-card">
            <p class="eyebrow">Verification</p>
            <h2>מה בודקים אחרי ההתקנה</h2>

            <ul class="check-list">
                <li>הכפתור מופיע באתר במיקום הנכון.</li>
                <li>הטקסט על הכפתור תואם למה שהגדרת.</li>
                <li>שינוי צבע בדשבורד מתעדכן באתר.</li>
                <li>הקישור להצהרת הנגישות נפתח נכון.</li>
                <li>האתר עצמו נשאר יציב גם אם ה־widget לא נטען.</li>
            </ul>

            <div class="info-card info-card-tight">
                <h3>WordPress</h3>
                <p>
                    אם עובדים עם WordPress, אפשר להזין את אותו <code>site key</code> לתוסף שכבר בנינו,
                    או להטמיע את הסקריפט ישירות דרך custom code.
                </p>
            </div>
        </aside>
    </section>
@endsection
