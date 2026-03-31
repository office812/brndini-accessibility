@php($domainLabel = parse_url($site->domain, PHP_URL_HOST) ?: $site->domain)

<aside class="domain-shell-sidebar">
    <a class="domain-back-link" href="{{ route('dashboard', ['site' => $site->id]) }}">כל הרישיונות</a>

    <div class="domain-select-pill">
        <span>דומיין: {{ $domainLabel }}</span>
        <span aria-hidden="true">⌄</span>
    </div>

    <nav class="domain-side-nav" aria-label="ניהול דומיין">
        <a class="{{ ($activeSection ?? '') === 'account' ? 'is-current' : '' }}" href="{{ route('dashboard.account', ['site' => $site->id]) }}">
            <span>⌘</span>
            <span>תוכנית ותשלומים</span>
        </a>
        <a class="{{ ($activeSection ?? '') === 'compliance' ? 'is-current' : '' }}" href="{{ route('dashboard.compliance', ['site' => $site->id]) }}">
            <span>◎</span>
            <span>הצהרת נגישות</span>
        </a>
        <a class="{{ ($activeSection ?? '') === 'install' ? 'is-current' : '' }}" href="{{ route('dashboard.install', ['site' => $site->id]) }}">
            <span>✎</span>
            <span>התקנה והתאמת הווידג׳ט</span>
        </a>
        <a href="{{ route('dashboard.compliance', ['site' => $site->id]) }}#remediation-report">
            <span>▣</span>
            <span>דוח תיקונים</span>
        </a>
        <a href="{{ route('dashboard.compliance', ['site' => $site->id]) }}#impact-report">
            <span>◫</span>
            <span>דוח השפעה</span>
        </a>
        <a href="{{ route('dashboard.compliance', ['site' => $site->id]) }}#audit-report">
            <span>⟡</span>
            <span>דוח ביקורת</span>
        </a>
        <a href="{{ route('dashboard.account', ['site' => $site->id]) }}#license-owner">
            <span>◌</span>
            <span>פרטי בעל הרישיון</span>
        </a>
        <a href="{{ route('dashboard.compliance', ['site' => $site->id]) }}#proof-toolkit">
            <span>⋄</span>
            <span>ערכת הוכחת מאמץ</span>
        </a>
    </nav>
</aside>
