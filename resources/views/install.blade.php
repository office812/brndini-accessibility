@extends('layouts.app')

@php($title = 'Install Center | A11Y Bridge')

@section('content')
    <section class="dashboard-header">
        <div>
            <p class="eyebrow">Install Center</p>
            <h1>מרכז הטמעה ברור, כך שכל אתר עולה לאוויר באותה שיטה.</h1>
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

    <section class="command-strip" aria-label="Install signals">
        <article class="command-card">
            <span class="command-label">Deployment model</span>
            <strong>Hosted snippet</strong>
            <p>אותו script נשאר באתר וכל שינוי ממשיך לצאת מתוך הפלטפורמה.</p>
        </article>
        <article class="command-card">
            <span class="command-label">Primary domain</span>
            <strong>{{ $site->domain }}</strong>
            <p>זה הדומיין שאליו מיועד קוד ההטמעה הנוכחי.</p>
        </article>
        <article class="command-card">
            <span class="command-label">Widget status</span>
            <strong>Ready to embed</strong>
            <p>ההגדרות כבר קיימות. נשאר רק להדביק, לרענן, ולוודא שהשינויים נמשכים כמו שצריך.</p>
        </article>
    </section>

    <section class="dashboard-grid">
        <section class="panel-card">
            <p class="eyebrow">How to install</p>
            <h2>שלבי התקנה</h2>
            <p class="panel-intro">השלבים נשארים זהים כמעט בכל אתר: copy, paste, refresh, verify.</p>

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
            <p class="panel-intro">המטרה היא לא רק לראות כפתור. המטרה היא לוודא שהפלטפורמה באמת מנהלת את מה שמופיע באתר.</p>

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

            <div class="info-card info-card-tight">
                <h3>Agency handoff</h3>
                <p>
                    אם הלקוח לא מטמיע לבד, אפשר לשלוח למיישם את ה־snippet ואת הדומיין המאושר, בלי להסביר מחדש את כל הלוגיקה.
                </p>
            </div>
        </aside>
    </section>
@endsection
