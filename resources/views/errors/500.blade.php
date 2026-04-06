<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>שגיאה | A11Y Bridge</title>
    <link rel="icon" type="image/png" href="{{ url('/inn-logo.png') }}">
    <meta name="theme-color" content="#0a101e">
    <link rel="stylesheet" href="{{ url('/platform.css') }}?v={{ filemtime(public_path('platform.css')) }}">
    <style>
        body {
            background: linear-gradient(180deg, #080d17 0%, #0a1020 54%, #070b14 100%);
            color: #f3f7ff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
            font-family: "Heebo", "IBM Plex Sans Hebrew", sans-serif;
        }
        .error-page-card h1 { color: #f3f7ff; }
        .error-page-card p { color: #97aac6; }
    </style>
</head>
<body>
    <section class="error-page-shell">
        <div class="error-page-card">
            <span class="error-page-code">500</span>
            <h1>משהו השתבש</h1>
            <p>אירעה שגיאה בלתי צפויה. הצוות שלנו כבר על זה — אפשר לנסות שוב בעוד רגע.</p>
            <a class="primary-button" href="{{ url('/') }}">חזרה לדף הבית</a>
        </div>
    </section>
</body>
</html>
