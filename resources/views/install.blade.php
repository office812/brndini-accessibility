@extends('layouts.app')

@php($title = 'הטמעת הווידג׳ט | A11Y Bridge')

@section('content')
    <section class="domain-shell">
        @include('partials.domain-sidebar', ['activeSection' => 'install'])

        <div class="domain-shell-main">

            {{-- ─── Welcome banner for new accounts ──────────────────────────── --}}
            @if(session('new_account'))
                <div class="install-welcome-banner">
                    <span class="install-welcome-icon" aria-hidden="true">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/></svg>
                    </span>
                    <div>
                        <strong>החשבון מוכן!</strong>
                        <span>שלב אחרון — העתק את הקוד והדבק אותו באתר שלך.</span>
                    </div>
                </div>
            @endif

            {{-- ─── HERO: Embed code ───────────────────────────────────────────── --}}
            <section class="install-code-hero">
                <div class="install-code-hero-header">
                    <h1>הקוד שלך מוכן</h1>
                    <p class="install-code-hero-sub">העתק את הקוד והדבק אותו לפני <code>&lt;/body&gt;</code> בכל עמוד באתר. זה הכל.</p>
                </div>

                <div class="install-code-block-wrap">
                    <div class="install-code-block" dir="ltr">
                        <pre id="install-embed-code" class="install-code-pre"><code>{{ $embedCode }}</code></pre>
                    </div>
                    <div class="install-code-actions">
                        <button class="primary-button install-copy-btn" type="button" data-copy-target="install-embed-code" id="install-copy-btn">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1" stroke="currentColor" stroke-width="2"/></svg>
                            העתק קוד
                        </button>
                        <a class="ghost-button install-verify-btn" href="{{ request()->fullUrlWithQuery(['_verify' => '1']) }}" id="install-verify-btn">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/></svg>
                            בדוק שהווידג׳ט פועל
                        </a>
                    </div>
                </div>

                {{-- Installation status pill --}}
                <div class="install-status-row">
                    <span class="status-pill {{ $installationTone === 'good' ? 'is-good' : ($installationTone === 'neutral' ? 'is-neutral' : 'is-warn') }}">
                        {{ $installationLabel }}
                    </span>
                    @if($installationPageUrl)
                        <span class="install-seen-url">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            זוהה ב: {{ $installationPageUrl }}
                        </span>
                    @endif
                    <span class="install-seen-label">זיהוי אחרון: {{ $installationSeenLabel }}</span>
                </div>
            </section>

            {{-- ─── Quick steps ────────────────────────────────────────────────── --}}
            <section class="install-steps-strip">
                <div class="install-step-card {{ $installationTone !== 'warn' ? 'is-done' : '' }}">
                    <span class="install-step-num">1</span>
                    <div>
                        <strong>העתק את הקוד</strong>
                        <p>לחץ "העתק קוד" למעלה</p>
                    </div>
                </div>
                <div class="install-steps-arrow" aria-hidden="true">→</div>
                <div class="install-step-card {{ in_array($installationTone, ['good', 'neutral']) ? 'is-done' : '' }}">
                    <span class="install-step-num">2</span>
                    <div>
                        <strong>הדבק לפני <code>&lt;/body&gt;</code></strong>
                        <p>WordPress, Wix, Shopify — בכל מקום</p>
                    </div>
                </div>
                <div class="install-steps-arrow" aria-hidden="true">→</div>
                <div class="install-step-card {{ $installationTone === 'good' ? 'is-done' : '' }}">
                    <span class="install-step-num">3</span>
                    <div>
                        <strong>לחץ "בדוק שהווידג׳ט פועל"</strong>
                        <p>אנחנו נסרוק ונאשר תוך שניות</p>
                    </div>
                </div>
            </section>

            {{-- ─── Platform guides ─────────────────────────────────────────────── --}}
            <section class="domain-card install-platform-card">
                <h2 class="install-section-title">איך מטמיעים לפי פלטפורמה</h2>
                <div class="install-platform-grid">
                    <div class="install-platform-item">
                        <strong>WordPress</strong>
                        <p>Appearance → Theme Editor → footer.php → לפני <code>&lt;/body&gt;</code></p>
                    </div>
                    <div class="install-platform-item">
                        <strong>Shopify</strong>
                        <p>Online Store → Themes → Edit Code → theme.liquid → לפני <code>&lt;/body&gt;</code></p>
                    </div>
                    <div class="install-platform-item">
                        <strong>Wix</strong>
                        <p>Settings → Custom Code → Add Custom Code → Body (end of body)</p>
                    </div>
                    <div class="install-platform-item">
                        <strong>כל פלטפורמה אחרת</strong>
                        <p>מצא את ה-template הראשי ובחפש <code>&lt;/body&gt;</code> — הדבק ממש לפניו</p>
                    </div>
                </div>
            </section>

            {{-- ─── Widget settings (collapsed details) ────────────────────────── --}}
            <details class="install-settings-details domain-card">
                <summary class="install-settings-summary">
                    <span>הגדרות ווידג׳ט</span>
                    <span class="install-settings-pills">
                        <span class="status-pill is-neutral">{{ $widgetPresetLabels[$widget['preset']] ?? $widget['preset'] }}</span>
                        <span class="status-pill is-neutral">{{ $widgetLayoutLabels[$widget['panelLayout']] ?? $widget['panelLayout'] }}</span>
                        <span class="status-pill is-neutral">{{ $featureCount }} פקדים פעילים</span>
                    </span>
                </summary>
                <div class="domain-info-list" style="margin-top: 1rem;">
                    <div class="domain-info-row">
                        <span>מיקום</span>
                        <strong>{{ $widget['position'] === 'bottom-left' ? 'שמאל למטה' : 'ימין למטה' }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>פריסט</span>
                        <strong>{{ $widgetPresetLabels[$widget['preset']] ?? $widget['preset'] }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>מבנה פאנל</span>
                        <strong>{{ $widgetLayoutLabels[$widget['panelLayout']] ?? $widget['panelLayout'] }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>טקסט כפתור</span>
                        <strong>{{ $widget['label'] }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>פקדים פעילים</span>
                        <strong>{{ $featureCount }} פעילים</strong>
                    </div>
                </div>
            </details>

            {{-- ─── License activation (if needed) ─────────────────────────────── --}}
            @if ($licenseStatus !== 'active')
                <section class="domain-card install-license-card">
                    <div class="install-license-icon" aria-hidden="true">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><path d="M12 8v4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/><circle cx="12" cy="16" r="0.8" fill="currentColor"/></svg>
                    </div>
                    <div>
                        <h2>הפעלת רישיון</h2>
                        <p>כדי שהווידג׳ט יופיע לגולשים, צריך להפעיל את הרישיון עבור אתר זה.</p>
                        <form method="POST" action="{{ route('dashboard.account.activate', ['site' => $site->id]) }}" style="margin-top: 0.75rem;">
                            @csrf
                            <input type="hidden" name="site_id" value="{{ $site->id }}">
                            <button class="primary-button" type="submit">הפעל רישיון</button>
                        </form>
                    </div>
                </section>
            @endif

            {{-- ─── Brndini promo (non-intrusive) ───────────────────────────────── --}}
            @include('partials.service-recommendations-panel', [
                'heading' => 'רוצה לשדרג את האתר בזמן שאתה כבר שם?',
                'intro' => 'הטמעת הווידג׳ט נשארת חינמית לצמיתות. אם האתר צריך עיצוב מחדש, SEO, או אחסון, אפשר לפתוח פנייה ל-Brndini מכאן.',
            ])

        </div>
    </section>
@endsection
