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
    <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('/platform.css') }}">
</head>
<body>
    <a class="skip-link" href="#main-content">דלג לתוכן הראשי</a>

    <div class="page-shell {{ auth()->check() ? 'page-shell-app' : '' }}">
        @auth
            <header class="app-header">
                <div class="app-header-brand">
                    <a class="app-logo" href="{{ route('dashboard') }}" aria-label="A11Y Bridge dashboard">
                        <span class="brand-mark brand-mark-app" aria-hidden="true">AB</span>
                        <span class="app-brand-copy">
                            <strong>A11Y Bridge</strong>
                            <small>סביבת ניהול נגישות</small>
                        </span>
                    </a>
                </div>

                <div class="app-header-left">
                    <nav class="app-nav" aria-label="ניווט מערכת">
                        <a class="{{ request()->routeIs('dashboard') ? 'is-current' : '' }}" href="{{ route('dashboard') }}">הרישיונות שלי</a>
                        <a class="{{ request()->routeIs('dashboard.install') ? 'is-current' : '' }}" href="{{ route('dashboard.install') }}">השירותים שלי</a>
                        <a class="{{ request()->routeIs('dashboard.compliance') ? 'is-current' : '' }}" href="{{ route('dashboard.compliance') }}">דוחות ובקרה</a>
                        <a class="{{ request()->routeIs('dashboard.account') ? 'is-current' : '' }}" href="{{ route('dashboard.account') }}">החשבון</a>
                    </nav>
                </div>

                <div class="app-header-right">
                    <a class="app-header-cta" href="{{ route('dashboard.install') }}">יצירת קשר</a>
                    <a class="app-header-icon" href="{{ route('dashboard.compliance') }}" aria-label="מרכז תמיכה">?</a>
                    <span class="app-header-icon" aria-hidden="true">•</span>
                    <div class="app-user-pill">
                        <span class="app-user-avatar">{{ strtoupper(mb_substr($user->name ?? Auth::user()->name, 0, 1)) }}</span>
                        <span>{{ $user->name ?? Auth::user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="app-logout-button" type="submit">התנתקות</button>
                    </form>
                </div>
            </header>
        @else
            <header class="site-header {{ request()->routeIs('home') ? 'site-header-public' : '' }}">
                <a class="brand" href="{{ route('home') }}">
                    <span class="brand-mark" aria-hidden="true">AB</span>
                    <span>
                        <strong>A11Y Bridge</strong>
                        <small>פלטפורמה לניהול נגישות אתר, widget hosted והטמעה קבועה</small>
                    </span>
                </a>

                <nav class="site-nav" aria-label="ניווט ראשי">
                    <a href="#solutions">פתרונות</a>
                    <a href="#platform-flow">איך זה עובד</a>
                    <a href="#articles">משאבים</a>
                    <a href="{{ route('login.show') }}">Login</a>
                    <a class="nav-button nav-button-primary" href="{{ route('register.show') }}">Start free trial</a>
                </nav>
            </header>
        @endauth

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
