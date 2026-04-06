<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>קוד ההטמעה שלך מוכן — A11Y Bridge</title>
    <style>
        body { margin: 0; padding: 0; background: #f0f4f8; font-family: "Segoe UI", Arial, sans-serif; direction: rtl; }
        .wrap { max-width: 560px; margin: 0 auto; padding: 32px 16px 48px; }
        .card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 16px rgba(0,0,0,0.07); }
        .card-header { background: #0d1726; padding: 28px 32px; text-align: center; }
        .card-header .brand { color: #fff; font-size: 1.1rem; font-weight: 700; letter-spacing: -0.01em; text-decoration: none; }
        .card-header .brand span { color: #4d82ff; }
        .card-body { padding: 32px 32px 28px; }
        h1 { font-size: 1.25rem; font-weight: 700; color: #0d1726; margin: 0 0 8px; line-height: 1.3; }
        .subtitle { font-size: 0.9rem; color: #64748b; margin: 0 0 28px; line-height: 1.6; }
        .code-label { font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 8px; }
        .code-block { background: #0d1726; border-radius: 8px; padding: 16px 18px; margin-bottom: 8px; }
        .code-block code { color: #7ba8ff; font-family: "Courier New", Courier, monospace; font-size: 0.82rem; line-height: 1.6; word-break: break-all; direction: ltr; display: block; text-align: left; }
        .code-hint { font-size: 0.78rem; color: #94a3b8; margin: 0 0 28px; }
        .divider { border: none; border-top: 1px solid #e8edf2; margin: 24px 0; }
        .steps { margin: 0 0 28px; padding: 0; list-style: none; }
        .steps li { display: flex; gap: 12px; align-items: flex-start; padding: 8px 0; font-size: 0.88rem; color: #374151; line-height: 1.5; }
        .step-num { flex-shrink: 0; width: 22px; height: 22px; background: #1463ff; color: #fff; border-radius: 50%; font-size: 0.72rem; font-weight: 700; display: flex; align-items: center; justify-content: center; margin-top: 1px; }
        .cta-wrap { text-align: center; margin: 4px 0 8px; }
        .cta { display: inline-block; background: #1463ff; color: #fff !important; text-decoration: none; padding: 13px 28px; border-radius: 8px; font-size: 0.95rem; font-weight: 600; letter-spacing: -0.01em; }
        .card-footer { background: #f8fafc; padding: 20px 32px; border-top: 1px solid #e8edf2; text-align: center; }
        .card-footer p { font-size: 0.78rem; color: #94a3b8; margin: 0 0 4px; line-height: 1.6; }
        .card-footer a { color: #1463ff; text-decoration: none; }
        @media (max-width: 560px) {
            .card-body { padding: 24px 20px 20px; }
            .card-footer { padding: 16px 20px; }
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">

        <div class="card-header">
            <span class="brand">A11Y <span>Bridge</span></span>
        </div>

        <div class="card-body">
            <h1>שלום {{ $user->name }}, קוד ההטמעה שלך מוכן</h1>
            <p class="subtitle">
                האתר <strong>{{ $site->site_name }}</strong> מוגדר. כל מה שצריך עכשיו הוא להדביק את הקוד הזה לפני
                <code style="background:#f1f5f9;padding:1px 5px;border-radius:4px;font-size:0.85em;">&lt;/body&gt;</code>
                בכל עמוד באתר.
            </p>

            <div class="code-label">קוד ההטמעה שלך</div>
            <div class="code-block">
                <code>{{ $embedCode }}</code>
            </div>
            <p class="code-hint">הקוד הזה ייחודי לאתר שלך — אל תשתף אותו עם אתרים אחרים.</p>

            <hr class="divider">

            <ol class="steps" style="padding-right: 0;">
                <li>
                    <span class="step-num">1</span>
                    <span>העתק את הקוד למעלה</span>
                </li>
                <li>
                    <span class="step-num">2</span>
                    <span>הדבק לפני <code style="background:#f1f5f9;padding:1px 4px;border-radius:3px;font-size:0.85em;">&lt;/body&gt;</code> — WordPress, Wix, Shopify, או כל פלטפורמה אחרת</span>
                </li>
                <li>
                    <span class="step-num">3</span>
                    <span>חזור לדשבורד ולחץ "בדוק שהווידג׳ט פועל" — הבדיקה אוטומטית ותאשר תוך שניות</span>
                </li>
            </ol>

            <div class="cta-wrap">
                <a class="cta" href="{{ $installUrl }}">כניסה לדשבורד ←</a>
            </div>
        </div>

        <div class="card-footer">
            <p>שלחנו אליך את המייל הזה כי נרשמת ל-A11Y Bridge.</p>
            <p>שאלות? <a href="mailto:office@brndini.co.il">office@brndini.co.il</a></p>
        </div>

    </div>
</div>
</body>
</html>
