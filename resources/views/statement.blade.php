@extends('layouts.app')

@php($title = ($statementPreview['title'] ?? 'הצהרת נגישות') . ' | A11Y Bridge')
@php($metaDescription = 'הצהרת נגישות עבור ' . ($site->site_name ?? $site->domain ?? 'האתר') . '. מוצגת על ידי A11Y Bridge.')

@section('content')
    <section class="legal-shell">
        <article class="legal-card statement-public-card">
            <div class="statement-public-head">
                <p class="eyebrow">הצהרת נגישות</p>
                <h1>{{ $statementPreview['title'] }}</h1>
                <p class="panel-intro">{{ $statementPreview['summary'] }}</p>

                <div class="statement-badge-row">
                    @foreach ($statementPreview['badges'] as $badge)
                        <span class="status-pill is-neutral">{{ $badge }}</span>
                    @endforeach
                </div>
            </div>

            <div class="statement-public-body">
                @foreach ($statementPreview['sections'] as $section)
                    <section class="statement-public-section">
                        <h2>{{ $section['title'] }}</h2>
                        <p>{{ $section['body'] }}</p>
                    </section>
                @endforeach
            </div>

            <div class="statement-public-meta">
                <span>האתר: {{ parse_url($site->domain, PHP_URL_HOST) ?: $site->domain }}</span>
                <span>עודכן לאחרונה: {{ $statementPreview['last_reviewed_label'] }}</span>
            </div>
        </article>
    </section>
@endsection
