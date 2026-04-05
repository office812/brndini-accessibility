<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 | A11Y Bridge</title>
    <link rel="stylesheet" href="{{ url('/platform.css') }}">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 20px; }
    </style>
</head>
<body>
    <section class="error-page-shell">
        <div class="error-page-card">
            <span class="error-page-code">404</span>
            <h1>העמוד לא נמצא</h1>
            <p>הדף שחיפשת לא קיים או הועבר לכתובת אחרת.</p>
            <a class="primary-button" href="{{ url('/') }}">חזרה לדף הבית</a>
        </div>
    </section>
</body>
</html>
