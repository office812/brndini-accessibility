@extends('layouts.app')

@php($title = 'תוכנית ותשלומים | A11Y Bridge')
@php($domainLabel = parse_url($site->domain, PHP_URL_HOST) ?: $site->domain)

@section('content')
    <section class="domain-shell">
        @include('partials.domain-sidebar', ['activeSection' => 'account'])

        <div class="domain-shell-main">
            <section class="domain-shell-header">
                <h1>תוכנית ותשלומים</h1>
            </section>

            <section class="domain-card domain-hero-card">
                <div>
                    <p class="eyebrow">חיוב לכל אתר</p>
                    <h2>{{ $domainLabel }}</h2>
                    <p class="panel-intro">לכל אתר בחשבון יש חיוב עצמאי, רישיון עצמאי ותאריך חידוש עצמאי. כאן מנהלים את המסלול הספציפי של האתר הפעיל.</p>
                </div>

                <div class="billing-hero-meta">
                    <span class="status-pill {{ $licenseStatus === 'active' ? 'is-good' : 'is-warn' }}">{{ $licenseStatus === 'active' ? 'רישיון פעיל' : 'רישיון לא פעיל' }}</span>
                    <span class="status-pill is-neutral">{{ $currentPlan['name'] }}</span>
                    <span class="status-pill is-neutral">חידוש: {{ $licenseExpiresLabel }}</span>
                </div>
            </section>

            <section class="dashboard-workspace dashboard-workspace-inline domain-tab-workspace" data-dashboard-tabs>
                <div class="dashboard-tab-content">
                    <div class="dashboard-tab-nav domain-inline-tab-nav" aria-label="לשוניות תוכנית ותשלומים">
                        <button class="dashboard-tab-button is-active" type="button" data-dashboard-tab-button="license-overview">סקירת רישיון</button>
                        <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="plans">חבילות</button>
                        <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="owner">בעל הרישיון</button>
                    </div>

                    <div class="dashboard-tab-panel is-active" data-dashboard-tab-panel="license-overview">
                        <section class="domain-card">
                            <div class="domain-card-head">
                                <div>
                                    <h2>מצב הרישיון</h2>
                                    <p class="panel-intro">אפשר לעבור בין חבילות, לבחור מחזור חיוב, ולהפעיל רישיון עבור אתרים חדשים שנוצרו במצב לא פעיל.</p>
                                </div>

                                @if ($licenseStatus !== 'active')
                                    <form method="POST" action="{{ route('dashboard.account.activate', ['site' => $site->id]) }}">
                                        @csrf
                                        <input type="hidden" name="site_id" value="{{ $site->id }}">
                                        <button class="primary-button" type="submit">הפעל רישיון</button>
                                    </form>
                                @endif
                            </div>

                            <div class="domain-info-list">
                                <div class="domain-info-row">
                                    <span>סטטוס</span>
                                    <strong><span class="status-pill {{ $licenseStatus === 'active' ? 'is-good' : 'is-warn' }}">{{ $licenseStatus === 'active' ? 'פעיל' : 'לא פעיל' }}</span></strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>מסלול</span>
                                    <strong>{{ $currentPlan['name'] }}</strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>מחזור חיוב</span>
                                    <strong>{{ $billing['cycle'] === 'yearly' ? 'שנתי' : 'חודשי' }}</strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>עלות</span>
                                    <strong>{{ $currentPlan['price'] }}</strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>תוקף</span>
                                    <strong>{{ $licenseExpiresLabel }}</strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>מפתח אתר ציבורי</span>
                                    <strong><code>{{ $site->public_key }}</code></strong>
                                </div>
                            </div>
                        </section>

                        <section class="domain-card">
                            <h2>חיוב וחיווי עסקי</h2>
                            <div class="domain-info-list">
                                <div class="domain-info-row">
                                    <span>קישור רכישה</span>
                                    <strong>{{ $site->purchase_url ?: 'הרישיון כבר פעיל' }}</strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>מסלול שירות</span>
                                    <strong>{{ $serviceModeLabel }}</strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>ציון ביקורת נוכחי</span>
                                    <strong>{{ $auditSnapshot['score'] }}</strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>התראות פתוחות</span>
                                    <strong>{{ $openAlertsCount }}</strong>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="plans">
                        <section class="domain-card">
                            <h2>בחירת חבילה</h2>
                            <div class="plan-card-grid">
                                @foreach ($billingPlans as $planKey => $plan)
                                    <article class="plan-choice-card {{ $billing['plan'] === $planKey ? 'is-current' : '' }}">
                                        <strong>{{ $plan['label'] }}</strong>
                                        <p>{{ $plan['description'] }}</p>
                                        <div class="plan-choice-prices">
                                            <span>{{ $plan['prices']['monthly'] === 0 ? 'ללא עלות' : '$' . $plan['prices']['monthly'] . ' / חודש' }}</span>
                                            <span>{{ $plan['prices']['yearly'] === 0 ? 'ללא עלות' : '$' . $plan['prices']['yearly'] . ' / שנה' }}</span>
                                        </div>

                                        @if ($planKey === 'free')
                                            <ul class="plan-choice-list">
                                                <li>כ־70% מיכולות הווידג׳ט פתוחות כבר מההתחלה</li>
                                                <li>התאמות טקסט, ניגודיות, קישורים, סמן גדול והפחתת תנועה</li>
                                                <li>קוד הטמעה קבוע וניהול מרחוק מתוך הפלטפורמה</li>
                                            </ul>
                                        @else
                                            <ul class="plan-choice-list">
                                                <li>עוד 30% מהיכולות הקריטיות והמתקדמות</li>
                                                <li>מדריך קריאה, הסתרת תמונות, מרווח אותיות ויישור תוכן</li>
                                                <li>פרופילים ייעודיים וחוויית נגישות עשירה יותר למבקרים</li>
                                            </ul>
                                        @endif

                                        <form class="stack-form compact-form" method="POST" action="{{ route('dashboard.account.billing', ['site' => $site->id]) }}">
                                            @csrf
                                            <input type="hidden" name="site_id" value="{{ $site->id }}">
                                            <input type="hidden" name="billing_plan" value="{{ $planKey }}">
                                            <label for="billing_cycle_{{ $planKey }}">מחזור חיוב</label>
                                            <select id="billing_cycle_{{ $planKey }}" name="billing_cycle">
                                                <option value="monthly" @selected($billing['plan'] === $planKey && $billing['cycle'] === 'monthly')>חודשי</option>
                                                <option value="yearly" @selected(($billing['plan'] !== $planKey && $billing['cycle'] === 'yearly') || ($billing['plan'] === $planKey && $billing['cycle'] === 'yearly'))>שנתי</option>
                                            </select>
                                            <button class="{{ $billing['plan'] === $planKey ? 'secondary-button' : 'primary-button' }}" type="submit">
                                                {{ $billing['plan'] === $planKey ? 'עדכן מחזור' : 'בחר מסלול' }}
                                            </button>
                                        </form>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="owner">
                        <section class="domain-card" id="license-owner">
                            <h2>פרטי בעל הרישיון</h2>

                            <div class="domain-info-list">
                                <div class="domain-info-row">
                                    <span>חברה</span>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>אימייל ליצירת קשר</span>
                                    <strong>{{ $user->contact_email }}</strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>סביבת עבודה</span>
                                    <strong>{{ $site->site_name }}</strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>דומיין ראשי</span>
                                    <strong>{{ $site->domain }}</strong>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
