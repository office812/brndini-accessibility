<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'A11Y Bridge' }}</title>
    <link rel="stylesheet" href="{{ url('/platform.css') }}">
</head>
<body>
    <a class="skip-link" href="#main-content">דלג לתוכן הראשי</a>

    <div class="page-shell">
        <header class="site-header">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark" aria-hidden="true">AB</span>
                <span>
                    <strong>A11Y Bridge</strong>
                    <small>Hosted accessibility widget platform</small>
                </span>
            </a>

            <nav class="site-nav" aria-label="ניווט ראשי">
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="nav-button" type="submit">התנתקות</button>
                    </form>
                @else
                    <a href="#signup-form">פתיחת חשבון</a>
                    <a href="#login-form">התחברות</a>
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
