@php
    $serviceIcons = [
        'hosting' => '◈',
        'seo' => '↗',
        'campaigns' => '◎',
        'maintenance' => '⚙',
        'website_upgrade' => '✦',
        'landing_pages' => '▣',
        'automations' => '∞',
    ];
@endphp

@if (!empty($serviceRecommendations))
    <section class="domain-card service-recommendations-panel">
        <div class="steward-feed-head">
            <div>
                <p class="eyebrow">שירותים של Brndini</p>
                <h2>{{ $heading ?? 'אחד השירותים האלה כנראה יחסוך לך זמן' }}</h2>
                <p class="panel-intro">{{ $intro ?? 'המערכת מסתכלת על מצב האתר ומציעה שכבת שירותים עסקיים של Brndini שיכולה להתאים עכשיו. זו לא תמיכה טכנית של המערכת.' }}</p>
            </div>
            <a class="text-link" href="{{ route('dashboard.services', ['site' => $site->id]) }}">לכל השירותים</a>
        </div>

        <div class="service-recommendation-grid service-recommendation-grid-compact">
            @foreach ($serviceRecommendations as $recommendation)
                <article class="service-recommendation-card service-recommendation-card-compact">
                    <span class="service-recommendation-icon" aria-hidden="true">{{ $serviceIcons[$recommendation['service_type']] ?? '•' }}</span>
                    <div>
                        @if (!empty($recommendation['label']))
                            <span class="status-pill is-neutral">{{ $recommendation['label'] }}</span>
                        @endif
                        <h3>{{ $recommendation['title'] }}</h3>
                        <p>{{ $recommendation['reason'] }}</p>
                        @if (!empty($recommendation['highlights']))
                            <ul class="service-recommendation-list">
                                @foreach ($recommendation['highlights'] as $highlight)
                                    <li>{{ $highlight }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <a class="primary-button" href="{{ route('dashboard.services', ['site' => $site->id, 'service' => $recommendation['service_type']]) }}#tab-request">
                        {{ $recommendation['cta'] }}
                    </a>
                </article>
            @endforeach
        </div>
    </section>
@endif
