<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'A11Y Bridge' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'A11Y Bridge היא פלטפורמה חינמית להטמעת וידג׳ט נגישות עם דשבורד, קוד הטמעה קבוע וגישה נוחה להצהרה בסיסית.' }}">
    <link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">
    <meta property="og:title" content="{{ $title ?? 'A11Y Bridge' }}">
    <meta property="og:description" content="{{ $metaDescription ?? 'פלטפורמה חינמית להטמעת וידג׳ט נגישות עם דשבורד ניהול וקוד הטמעה קבוע.' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonicalUrl ?? url()->current() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;700;800;900&family=IBM+Plex+Sans+Hebrew:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('/platform.css') }}?v={{ $assetVersion ?? '1' }}">
    {!! $globalTrackingScripts['google_analytics_head'] ?? '' !!}
    {!! $globalTrackingScripts['google_tag_manager_head'] ?? '' !!}
    {!! $globalTrackingScripts['meta_pixel_head'] ?? '' !!}
    {!! $globalTrackingScripts['custom_head_scripts'] ?? '' !!}
</head>
@php($bodyClass = collect([
    auth()->check() ? 'app-page' : 'public-page',
    request()->routeIs('home') ? 'page-home' : null,
])->filter()->implode(' '))
<body class="{{ $bodyClass }}">
    {!! $globalTrackingScripts['google_tag_manager_body'] ?? '' !!}
    {!! $globalTrackingScripts['custom_body_scripts'] ?? '' !!}
    @php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))
    <div class="top-progress" aria-hidden="true" data-top-progress>
        <span class="top-progress-bar"></span>
    </div>
    <a class="skip-link" href="#main-content">דלג לתוכן הראשי</a>
    @php($siteRouteParams = isset($site) ? ['site' => $site->id] : [])

    <div class="page-shell {{ auth()->check() ? 'page-shell-app' : 'page-shell-public' }} {{ request()->routeIs('home') ? 'page-shell-home' : '' }}">
        @auth
            <header class="app-header">
                <div class="app-header-brand">
                    <a class="app-logo" href="{{ route('dashboard', $siteRouteParams) }}" aria-label="לוח הבקרה של A11Y Bridge">
                        <span class="brand-mark brand-mark-app" aria-hidden="true">
                            <img class="brand-logo-image" src="{{ url('/inn-logo.png') }}" alt="">
                        </span>
                        <span class="app-brand-copy">
                            <strong>A11Y Bridge</strong>
                            <small>סביבת ניהול וידג׳ט</small>
                        </span>
                    </a>
                </div>

                <button class="header-menu-toggle" type="button" aria-expanded="false" aria-label="פתח תפריט" aria-controls="app-menu-panel" data-header-menu-toggle="app-menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <div class="app-header-menu" id="app-menu-panel" data-header-menu-panel="app-menu">
                    <div class="app-header-left">
                        <nav class="app-nav" aria-label="ניווט מערכת">
                            <a class="{{ request()->routeIs('dashboard') ? 'is-current' : '' }}" href="{{ route('dashboard', $siteRouteParams) }}">הרישיונות שלי</a>
                            <a class="{{ request()->routeIs('dashboard.services') ? 'is-current' : '' }}" href="{{ route('dashboard.services', $siteRouteParams) }}">השירותים שלי</a>
                            <a class="{{ request()->routeIs('dashboard.compliance') ? 'is-current' : '' }}" href="{{ route('dashboard.compliance', $siteRouteParams) }}">דוחות ובקרה</a>
                            <a class="{{ request()->routeIs('dashboard.account') ? 'is-current' : '' }}" href="{{ route('dashboard.account', $siteRouteParams) }}">החשבון</a>
                            @if (($user ?? Auth::user())?->isSuperAdmin())
                                <a class="{{ request()->routeIs('dashboard.super-admin') ? 'is-current' : '' }}" href="{{ route('dashboard.super-admin') }}">סופר־אדמין</a>
                            @endif
                        </nav>
                    </div>

                    <div class="app-header-right">
                        <a class="app-header-cta" href="{{ route('dashboard.support', $siteRouteParams) }}">תמיכה טכנית</a>
                        <div class="app-user-pill">
                            <span class="app-user-avatar">{{ strtoupper(mb_substr($user->name ?? Auth::user()->name, 0, 1)) }}</span>
                            <span>{{ $user->name ?? Auth::user()->name }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="app-logout-button" type="submit">התנתקות</button>
                        </form>
                    </div>
                </div>
                <button class="header-menu-backdrop" type="button" aria-label="סגור תפריט" data-header-menu-backdrop="app-menu"></button>
            </header>
        @else
            <header class="site-header {{ request()->routeIs('home') ? 'site-header-public' : '' }}">
                <a class="brand" href="{{ route('home') }}">
                    <span class="brand-mark" aria-hidden="true">
                        <img class="brand-logo-image" src="{{ url('/inn-logo.png') }}" alt="">
                    </span>
                    <span>
                        <strong>A11Y Bridge</strong>
                        <small>כלי חינמי self-service להטמעת וידג׳ט נגישות</small>
                    </span>
                </a>

                <button class="header-menu-toggle" type="button" aria-expanded="false" aria-label="פתח תפריט" aria-controls="site-menu-panel" data-header-menu-toggle="site-menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <div class="site-header-menu" id="site-menu-panel" data-header-menu-panel="site-menu">
                    <nav class="site-nav" aria-label="ניווט ראשי">
                        @if (request()->routeIs('home'))
                            <a href="#features">פיצ׳רים</a>
                            <a href="{{ route('how-it-works') }}">איך זה עובד</a>
                            <a href="{{ route('articles.index') }}">מרכז ידע</a>
                            <a href="#faq">שאלות נפוצות</a>
                            <a class="nav-button-secondary nav-button-hub" href="{{ route('login.show') }}">התחברות</a>
                            <a class="nav-button nav-button-primary" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                        @else
                            <a class="{{ request()->routeIs('home') ? 'is-current' : '' }}" href="{{ route('home') }}">בית</a>
                            <a class="{{ request()->routeIs('how-it-works') ? 'is-current' : '' }}" href="{{ route('how-it-works') }}">איך זה עובד</a>
                            <a class="{{ request()->routeIs('free-tool') || request()->routeIs('pricing') ? 'is-current' : '' }}" href="{{ route('free-tool') }}">מה כלול בחינם</a>
                            <a class="{{ request()->routeIs('articles.*') ? 'is-current' : '' }}" href="{{ route('articles.index') }}">מרכז ידע</a>
                            <a class="{{ request()->routeIs('faq') ? 'is-current' : '' }}" href="{{ route('faq') }}">שאלות נפוצות</a>
                            <a class="{{ request()->routeIs('about') ? 'is-current' : '' }}" href="{{ route('about') }}">אודות</a>
                            <a class="nav-button-secondary nav-button-hub {{ request()->routeIs('brndini.home') || request()->routeIs('products') || request()->routeIs('brndini.services') ? 'is-current' : '' }}" href="{{ route('brndini.home', $marketingParams) }}">Brndini</a>
                            <a class="nav-button-secondary" href="{{ route('login.show') }}">התחברות</a>
                            <a class="nav-button nav-button-primary" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                        @endif
                    </nav>
                </div>
                <button class="header-menu-backdrop" type="button" aria-label="סגור תפריט" data-header-menu-backdrop="site-menu"></button>
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

        @guest
            <footer class="site-footer">
                <div class="site-footer-top">
                    <div class="site-footer-brand">
                        <a class="brand brand-footer" href="{{ route('home') }}">
                            <span class="brand-mark" aria-hidden="true">
                                <img class="brand-logo-image" src="{{ url('/inn-logo.png') }}" alt="">
                            </span>
                            <span>
                            <strong>A11Y Bridge</strong>
                                <small>כלי חינמי, שקט וברור לניהול שכבת נגישות טכנית.</small>
                            </span>
                        </a>
                        <p>
                            A11Y Bridge נותנת התחלה מהירה: חשבון, אתר, קוד הטמעה קבוע, דשבורד,
                            הצהרה בסיסית ותמיכה טכנית בלבד.
                        </p>
                    </div>

                    <div class="site-footer-links">
                        @if (request()->routeIs('home'))
                            <div class="footer-link-group">
                                <h3>מוצר</h3>
                                <a href="#features">פיצ׳רים</a>
                                <a href="{{ route('how-it-works') }}">איך זה עובד</a>
                                <a href="{{ route('faq') }}">שאלות נפוצות</a>
                            </div>
                            <div class="footer-link-group">
                                <h3>משאבים</h3>
                                <a href="{{ route('articles.index') }}">מרכז ידע</a>
                                <a href="{{ route('about') }}">אודות</a>
                                <a href="{{ route('login.show') }}">התחברות</a>
                            </div>
                            <div class="footer-link-group">
                                <h3>משפטי</h3>
                                <a href="{{ route('legal.terms') }}">תנאי שימוש</a>
                                <a href="{{ route('legal.privacy') }}">פרטיות</a>
                                <a href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                            </div>
                        @else
                            <div class="footer-link-group">
                                <h3>A11Y Bridge</h3>
                                <a href="{{ route('home') }}">בית</a>
                                <a href="{{ route('how-it-works') }}">איך זה עובד</a>
                                <a href="{{ route('free-tool') }}">מה כלול בחינם</a>
                                <a href="{{ route('faq') }}">שאלות נפוצות</a>
                            </div>
                            <div class="footer-link-group">
                                <h3>Brndini</h3>
                                <a href="{{ route('brndini.home', $marketingParams) }}">Brndini</a>
                                <a href="{{ route('brndini.services', $marketingParams) }}">שירותים עסקיים</a>
                                <a href="{{ route('about') }}">אודות</a>
                                <a href="{{ route('articles.index') }}">מרכז ידע</a>
                            </div>
                            <div class="footer-link-group">
                                <h3>משפטי</h3>
                                <a href="{{ route('legal.terms') }}">תנאי שימוש</a>
                                <a href="{{ route('legal.privacy') }}">פרטיות</a>
                                <a href="{{ route('login.show') }}">התחברות</a>
                                <a href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="site-footer-bottom">
                    <span>© {{ now()->year }} A11Y Bridge. כלי חינמי self-service. תמיכה טכנית בלבד.</span>
                    <div class="footer-inline-links">
                        <a href="{{ route('home') }}">בית</a>
                        <a href="{{ route('how-it-works') }}">איך זה עובד</a>
                        <a href="{{ route('free-tool') }}">מה כלול בחינם</a>
                        <a href="{{ route('articles.index') }}">מרכז ידע</a>
                        <a href="{{ route('brndini.home', $marketingParams) }}">Brndini</a>
                        <a href="{{ route('brndini.services', $marketingParams) }}">שירותים עסקיים</a>
                        <a href="{{ route('faq') }}">שאלות נפוצות</a>
                        <a href="{{ route('legal.terms') }}">תנאי שימוש</a>
                        <a href="{{ route('legal.privacy') }}">פרטיות</a>
                        <a href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                    </div>
                </div>
            </footer>
        @endguest
    </div>

    <!-- Command Palette -->
<div class="cmd-palette-overlay" id="cmd-palette" aria-modal="true" role="dialog" aria-label="חיפוש מהיר" hidden>
    <div class="cmd-palette-panel">
        <div class="cmd-palette-search">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" aria-hidden="true" class="cmd-search-icon"><circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/><path d="m21 21-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            <input type="text" id="cmd-input" placeholder="חפש עמוד, פעולה..." autocomplete="off" dir="rtl">
            <kbd class="cmd-esc-hint">ESC</kbd>
        </div>
        <ul class="cmd-results" id="cmd-results" role="listbox"></ul>
        <div class="cmd-palette-footer">
            <span><kbd>↑↓</kbd> ניווט</span>
            <span><kbd>↵</kbd> מעבר</span>
            <span><kbd>ESC</kbd> סגירה</span>
        </div>
    </div>
</div>

    <script src="{{ url('/platform.js') }}?v={{ $assetVersion ?? '1' }}" defer></script>

    @if (!empty($platformWidgetSiteKey) && auth()->check())
        <script
            async
            src="{{ url('/widget.js') }}?v={{ $assetVersion ?? '1' }}"
            data-a11y-bridge="{{ $platformWidgetSiteKey }}"
            data-a11y-position="bottom-left"
        ></script>
    @endif
</body>
</html>
