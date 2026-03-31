<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'A11Y Bridge' }}</title>
    <meta name="description" content="A11Y Bridge היא פלטפורמת נגישות לאתרים עם widget hosted, dashboard ניהול וקוד הטמעה קבוע.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('/platform.css') }}">
</head>
<body class="auth-page">
    <div class="top-progress" aria-hidden="true" data-top-progress>
        <span class="top-progress-bar"></span>
    </div>
    <main class="auth-shell">
        @yield('content')
    </main>

    <script src="{{ url('/platform.js') }}" defer></script>

    @if (!empty($platformWidgetSiteKey))
        <script
            async
            src="{{ url('/widget.js') }}"
            data-a11y-bridge="{{ $platformWidgetSiteKey }}"
            data-a11y-position="bottom-left"
        ></script>
    @endif
</body>
</html>
