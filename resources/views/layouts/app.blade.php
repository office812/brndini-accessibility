<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'A11Y Bridge' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'A11Y Bridge היא פלטפורמת נגישות לאתרים עם widget hosted, dashboard ניהול, קוד הטמעה קבוע וגישה נוחה להצהרת נגישות.' }}">
    <link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">
    <meta property="og:title" content="{{ $title ?? 'A11Y Bridge' }}">
    <meta property="og:description" content="{{ $metaDescription ?? 'פלטפורמת נגישות לאתרים עם widget hosted, dashboard ניהול וקוד הטמעה קבוע.' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonicalUrl ?? url()->current() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Frank+Ruhl+Libre:wght@500;700&family=Heebo:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('/platform.css') }}">
</head>
<body>
    <a class="skip-link" href="#main-content">דלג לתוכן הראשי</a>

    <div class="page-shell">
        <header class="site-header {{ request()->routeIs('home') ? 'site-header-public' : '' }}">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark" aria-hidden="true">AB</span>
                <span>
                    <strong>A11Y Bridge</strong>
                    <small>Platform for hosted website accessibility management</small>
                </span>
            </a>

            <nav class="site-nav" aria-label="ניווט ראשי">
                @auth
                    <a class="{{ request()->routeIs('home') ? 'is-current' : '' }}" href="{{ route('home') }}">Home</a>
                    <a class="{{ request()->routeIs('dashboard') ? 'is-current' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="{{ request()->routeIs('dashboard.install') ? 'is-current' : '' }}" href="{{ route('dashboard.install') }}">Install</a>
                    <a class="{{ request()->routeIs('dashboard.compliance') ? 'is-current' : '' }}" href="{{ route('dashboard.compliance') }}">Compliance</a>
                    <a class="{{ request()->routeIs('dashboard.account') ? 'is-current' : '' }}" href="{{ route('dashboard.account') }}">Account</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="nav-button" type="submit">התנתקות</button>
                    </form>
                @else
                    <a href="#why-a11y-bridge">למה אנחנו</a>
                    <a href="#articles">מאמרים</a>
                    <a href="#signup-form">פתיחת חשבון</a>
                    <a class="nav-button nav-button-primary" href="#signup-form">פתח חשבון</a>
                @endauth
            </nav>
        </header>

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

        <main id="main-content">
            @yield('content')
        </main>
    </div>

    <script src="{{ url('/platform.js') }}" defer></script>
</body>
</html>
