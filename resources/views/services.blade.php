@extends('layouts.app')

@php($title = 'שירותי Brndini | A11Y Bridge')
@php($domainLabel = parse_url($site->domain, PHP_URL_HOST) ?: $site->domain)

@section('content')
    <section class="domain-shell">
        @include('partials.domain-sidebar', ['activeSection' => 'services'])

        <div class="domain-shell-main">
            <section class="domain-shell-header">
                <h1>שירותי Brndini</h1>
            </section>

            <section class="domain-card domain-hero-card">
                <div>
                    <p class="eyebrow">שירותים נוספים לעסק שלך</p>
                    <h2>{{ $site->site_name }}</h2>
                    <p class="panel-intro">הווידג׳ט נשאר חינמי, אבל אם תרצה עזרה עם אחסון, SEO, קמפיינים, תחזוקה או שדרוג אתר, מכאן אפשר להשאיר פנייה מסודרת. זה אזור שירותי Brndini, לא תמיכה טכנית של המערכת.</p>
                </div>

                <div class="billing-hero-meta">
                    <span class="status-pill is-neutral">דומיין: {{ $domainLabel }}</span>
                    <span class="status-pill is-neutral">פניות שירות: {{ $serviceLeadSummary['total'] }}</span>
                    <span class="status-pill {{ $serviceLeadSummary['new'] > 0 ? 'is-warn' : 'is-good' }}">חדשות: {{ $serviceLeadSummary['new'] }}</span>
                </div>
            </section>

            <section class="dashboard-workspace dashboard-workspace-inline domain-tab-workspace" data-dashboard-tabs>
                <div class="dashboard-tab-content">
                    <div class="dashboard-tab-nav domain-inline-tab-nav" aria-label="לשוניות שירותי Brndini">
                        <button class="dashboard-tab-button is-active" type="button" data-dashboard-tab-button="catalog">השירותים</button>
                        <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="request">פתח פנייה</button>
                        <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="requests">הפניות שלי</button>
                    </div>

                    <div class="dashboard-tab-panel is-active" data-dashboard-tab-panel="catalog">
                        <section class="plan-card-grid">
                            @foreach ($serviceCatalog as $serviceKey => $service)
                                <article class="plan-choice-card">
                                    <strong>{{ $service['label'] }}</strong>
                                    <p>{{ $service['description'] }}</p>
                                    <ul class="plan-choice-list">
                                        @foreach ($service['highlights'] as $highlight)
                                            <li>{{ $highlight }}</li>
                                        @endforeach
                                    </ul>
                                    <button class="primary-button" type="button" data-dashboard-tab-link="request" data-service-type="{{ $serviceKey }}">
                                        רוצה פרטים על {{ $service['label'] }}
                                    </button>
                                </article>
                            @endforeach
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="request">
                        <section class="support-grid">
                            <section class="domain-card support-form-card">
                                <div class="domain-card-head">
                                    <div>
                                        <h2>פתח פנייה לשירותי Brndini</h2>
                                        <p class="panel-intro">פנייה כאן מיועדת לשירותים עסקיים של Brndini כמו אחסון, שדרוג אתר, SEO, קמפיינים ותחזוקה. זו לא תמיכה טכנית של המערכת.</p>
                                    </div>
                                </div>

                                <form class="stack-form" method="POST" action="{{ route('dashboard.services.store', ['site' => $site->id]) }}">
                                    @csrf
                                    <input type="hidden" name="site_id" value="{{ old('site_id', $site->id) }}">

                                    <label for="service_type">איזה שירות מעניין אותך?</label>
                                    <select id="service_type" name="service_type">
                                        @foreach ($serviceCatalog as $serviceKey => $service)
                                            <option value="{{ $serviceKey }}" @selected(old('service_type') === $serviceKey)>{{ $service['label'] }}</option>
                                        @endforeach
                                    </select>

                                    <label for="service_goal">מה המטרה שלך?</label>
                                    <input id="service_goal" name="goal" type="text" value="{{ old('goal') }}" placeholder="למשל: לשפר מהירות, להעלות לידים, להעביר לאחסון יציב">

                                    <label for="service_message">פרטים חשובים</label>
                                    <textarea id="service_message" name="message" rows="7" placeholder="ספר בקצרה מה העסק צריך, מה מצב האתר היום, ומה תרצה להשיג.">{{ old('message') }}</textarea>

                                    <label for="service_preferred_contact">איך נוח לך שנחזור?</label>
                                    <select id="service_preferred_contact" name="preferred_contact">
                                        @foreach ($servicePreferredContactLabels as $contactKey => $contactLabel)
                                            <option value="{{ $contactKey }}" @selected(old('preferred_contact', 'email') === $contactKey)>{{ $contactLabel }}</option>
                                        @endforeach
                                    </select>

                                    <div class="support-form-actions">
                                        <button class="primary-button" type="submit">שלח פנייה לשירות</button>
                                        <span class="meta-note">פניות כאן מועברות לאזור הלידים של Brndini, בנפרד מהתמיכה הטכנית של המערכת.</span>
                                    </div>
                                </form>
                            </section>

                            <aside class="support-side-stack">
                                <section class="domain-card">
                                    <h2>למה להשאיר פנייה כאן?</h2>
                                    <div class="domain-info-list">
                                        <div class="domain-info-row">
                                            <span>הקשר אתר</span>
                                            <strong>{{ $site->site_name }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>דומיין</span>
                                            <strong>{{ $domainLabel }}</strong>
                                        </div>
                                        <div class="domain-info-row">
                                            <span>פניות שנשלחו</span>
                                            <strong>{{ $serviceLeadSummary['total'] }}</strong>
                                        </div>
                                    </div>
                                </section>
                            </aside>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="requests">
                        <section class="domain-card">
                            <div class="domain-card-head">
                                <div>
                                    <h2>הפניות העסקיות של האתר הזה</h2>
                                    <p class="panel-intro">כאן תראה רק פניות לשירותי Brndini, כמו אחסון, SEO, קמפיינים או שדרוג אתר. פניות טכניות למערכת נשארות במרכז התמיכה.</p>
                                </div>
                            </div>

                            @if ($serviceLeads->isEmpty())
                                <div class="support-empty-state">
                                    <strong>עדיין לא נשלחה פנייה לשירות</strong>
                                    <p>ברגע שתפתח פנייה ראשונה, היא תופיע כאן עם השירות שביקשת, דרך החזרה המועדפת והזמן האחרון שבו עודכנה.</p>
                                </div>
                            @else
                                <div class="support-ticket-list">
                                    @foreach ($serviceLeads as $lead)
                                        <article class="support-ticket-card">
                                            <div class="support-ticket-head">
                                                <div>
                                                    <p class="support-ticket-code">{{ $lead->reference_code }}</p>
                                                    <h3>{{ $serviceCatalog[$lead->service_type]['label'] ?? $lead->service_type }}</h3>
                                                </div>

                                                <div class="support-ticket-pills">
                                                    <span class="status-pill is-neutral">חדש</span>
                                                    <span class="status-pill is-good">{{ $servicePreferredContactLabels[$lead->preferred_contact] ?? $lead->preferred_contact }}</span>
                                                </div>
                                            </div>

                                            <p class="support-ticket-meta">
                                                {{ $lead->goal }} · {{ $lead->last_activity_label }}
                                            </p>

                                            <p class="support-ticket-message">{{ $lead->message }}</p>
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                        </section>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
