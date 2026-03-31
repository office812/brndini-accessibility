@php($domainLabel = parse_url($site->domain, PHP_URL_HOST) ?: $site->domain)

<aside class="domain-shell-sidebar">
    <a class="domain-back-link" href="{{ route('dashboard') }}">All licenses</a>

    <div class="domain-select-pill">
        <span>Domain: {{ $domainLabel }}</span>
        <span aria-hidden="true">⌄</span>
    </div>

    <nav class="domain-side-nav" aria-label="Domain management">
        <a class="{{ ($activeSection ?? '') === 'account' ? 'is-current' : '' }}" href="{{ route('dashboard.account') }}">
            <span>⌘</span>
            <span>Plan and payments</span>
        </a>
        <a class="{{ ($activeSection ?? '') === 'compliance' ? 'is-current' : '' }}" href="{{ route('dashboard.compliance') }}">
            <span>◎</span>
            <span>Accessibility statement</span>
        </a>
        <a class="{{ ($activeSection ?? '') === 'install' ? 'is-current' : '' }}" href="{{ route('dashboard.install') }}">
            <span>✎</span>
            <span>Install and customize widget</span>
        </a>
        <a href="{{ route('dashboard.compliance') }}#remediation-report">
            <span>▣</span>
            <span>Remediation report</span>
        </a>
        <a href="{{ route('dashboard.compliance') }}#impact-report">
            <span>◫</span>
            <span>Impact report</span>
        </a>
        <a href="{{ route('dashboard.compliance') }}#audit-report">
            <span>⟡</span>
            <span>Audit report</span>
        </a>
        <a href="{{ route('dashboard.account') }}#license-owner">
            <span>◌</span>
            <span>License owner info</span>
        </a>
        <a href="{{ route('dashboard.compliance') }}#proof-toolkit">
            <span>⋄</span>
            <span>Proof of effort toolkit</span>
        </a>
    </nav>
</aside>
