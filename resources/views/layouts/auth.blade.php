<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'A11Y Bridge' }}</title>
    <meta name="description" content="A11Y Bridge היא כלי חינמי self-service להטמעת וידג׳ט נגישות, עם דשבורד, קוד הטמעה קבוע, הצהרה בסיסית ותמיכה טכנית בלבד.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;700;800;900&family=IBM+Plex+Sans+Hebrew:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('/platform.css') }}?v={{ $assetVersion ?? '1' }}">
    {!! $globalTrackingScripts['google_analytics_head'] ?? '' !!}
    {!! $globalTrackingScripts['google_tag_manager_head'] ?? '' !!}
    {!! $globalTrackingScripts['meta_pixel_head'] ?? '' !!}
    {!! $globalTrackingScripts['custom_head_scripts'] ?? '' !!}
</head>
<body class="auth-page">
    {!! $globalTrackingScripts['google_tag_manager_body'] ?? '' !!}
    {!! $globalTrackingScripts['custom_body_scripts'] ?? '' !!}
    <div class="top-progress" aria-hidden="true" data-top-progress>
        <span class="top-progress-bar"></span>
    </div>
    <main class="auth-shell">
        @if (session('status'))
            <div class="flash flash-success" role="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="flash flash-error" role="alert">
                <strong>יש כמה שדות שצריך לתקן:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="{{ url('/platform.js') }}?v={{ $assetVersion ?? '1' }}" defer></script>

    @if (!empty($platformWidgetSiteKey))
        <script
            async
            src="{{ url('/widget.js') }}?v={{ $assetVersion ?? '1' }}"
            data-a11y-bridge="{{ $platformWidgetSiteKey }}"
            data-a11y-position="bottom-left"
        ></script>
    @endif
</body>
</html>
