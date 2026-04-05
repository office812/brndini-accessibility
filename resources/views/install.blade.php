@extends('layouts.app')

@php($title = 'התקנה והתאמת הווידג׳ט | A11Y Bridge')

@section('content')
    <section class="domain-shell">
        @include('partials.domain-sidebar', ['activeSection' => 'install'])

        <div class="domain-shell-main">
            <section class="domain-shell-header">
                <h1>הטמעה והגדרת הווידג׳ט</h1>
                <p class="domain-shell-header-intro">
                    כל מה שצריך כדי לחבר את האתר הפעיל נמצא כאן: snippet, סטטוס זיהוי,
                    תצורת widget והצעדים שצריך לסגור כדי שהשכבה תעבוד חלק.
                </p>
            </section>

            <section class="domain-card domain-hero-card">
                <div>
                    <p class="eyebrow">מרכז התקנה</p>
                    <h2>{{ $site->site_name }}</h2>
                    <p class="panel-intro">הקוד הזה שייך רק לאתר הזה. אם פותחים אתר נוסף, הוא יקבל site key ורישיון משלו. כאן מטפלים רק באתר הפעיל.</p>
                </div>

                <div class="billing-hero-meta">
                    <span class="status-pill {{ $licenseStatus === 'active' ? 'is-good' : 'is-warn' }}">{{ $licenseStatus === 'active' ? 'מוכן להטמעה' : 'דורש הפעלת רישיון' }}</span>
                    <span class="status-pill {{ $installationTone === 'good' ? 'is-good' : ($installationTone === 'neutral' ? 'is-neutral' : 'is-warn') }}">{{ $installationLabel }}</span>
                    <span class="status-pill is-neutral">{{ $widgetPresetLabels[$widget['preset']] ?? $widget['preset'] }}</span>
                    <span class="status-pill is-neutral">{{ $widgetLayoutLabels[$widget['panelLayout']] ?? $widget['panelLayout'] }}</span>
                </div>
            </section>

            <section class="workspace-snapshot">
                <article class="workspace-snapshot-item">
                    <span class="meta-label">מצב התקנה</span>
                    <strong>{{ $installationLabel }}</strong>
                    <p>{{ $installationSeenLabel }}</p>
                </article>
                <article class="workspace-snapshot-item">
                    <span class="meta-label">רישיון</span>
                    <strong>{{ $licenseStatus === 'active' ? 'מוכן להטמעה' : 'דורש הפעלה' }}</strong>
                    <p>{{ $licenseStatus === 'active' ? 'אפשר להתקין ולבדוק זיהוי באתר.' : 'כדאי להפעיל רישיון לפני שהווידג׳ט נטען באמת.' }}</p>
                </article>
                <article class="workspace-snapshot-item">
                    <span class="meta-label">תצורה</span>
                    <strong>{{ $widgetPresetLabels[$widget['preset']] ?? $widget['preset'] }}</strong>
                    <p>{{ $widgetLayoutLabels[$widget['panelLayout']] ?? $widget['panelLayout'] }} · {{ $widget['position'] === 'bottom-left' ? 'שמאל למטה' : 'ימין למטה' }}</p>
                </article>
                <article class="workspace-snapshot-item">
                    <span class="meta-label">פקדים פעילים</span>
                    <strong>{{ $featureCount }}</strong>
                    <p>התאמות זמינות כרגע למבקרים באתר דרך הווידג׳ט.</p>
                </article>
            </section>

            @include('partials.service-recommendations-panel', [
                'heading' => 'אם כבר נוגעים באתר, אפשר גם לקדם אותו',
                'intro' => 'הטמעת הווידג׳ט נשארת חינמית, אבל אם האתר צריך שדרוג רחב יותר, אחסון, SEO או תחזוקה, אפשר לפתוח מכאן פנייה עסקית מסודרת ל־Brndini.',
            ])

            <section class="dashboard-workspace dashboard-workspace-inline domain-tab-workspace" data-dashboard-tabs>
                <div class="dashboard-tab-content">
                    <div class="dashboard-tab-nav domain-inline-tab-nav" aria-label="לשוניות התקנה">
                        <button class="dashboard-tab-button is-active" type="button" data-dashboard-tab-button="install-status">סטטוס התקנה</button>
                        <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="install-code">קוד הטמעה</button>
                        <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="install-config">תצורת ווידג׳ט</button>
                    </div>

                    <div class="dashboard-tab-panel is-active" data-dashboard-tab-panel="install-status">
                        <section class="domain-card install-state-hero-card">
                            @php($installState = $installationTone === 'good' ? 'installed' : ($installationPageUrl ? 'stale' : 'pending'))
                            <div class="install-state-visual install-state-{{ $installState }}">
                                <div class="install-state-icon" aria-hidden="true">
                                    @if($installState === 'installed')
                                        <svg width="36" height="36" fill="none" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    @elseif($installState === 'stale')
                                        <svg width="36" height="36" fill="none" viewBox="0 0 24 24"><path d="M12 8v4l3 3" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/></svg>
                                    @else
                                        <svg width="36" height="36" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><path d="M12 8v4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/><circle cx="12" cy="16" r="0.8" fill="currentColor"/></svg>
                                    @endif
                                </div>
                                <div class="install-state-text">
                                    <strong class="install-state-label">{{ $installationLabel }}</strong>
                                    <p class="install-state-summary">{{ $installationSummary }}</p>
                                    @if($installationPageUrl)
                                        <span class="install-state-url">
                                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                            {{ $installationPageUrl }}
                                        </span>
                                    @endif
                                    @if($installState === 'pending')
                                        <div class="pending-hint">
                                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M13 16h-1v-4h-1m1-4h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/></svg>
                                            טיפ: פתח את האתר בטאב חדש אחרי ההטמעה — המצב יתעדכן אוטומטית תוך דקה.
                                        </div>
                                        <a class="install-state-action primary-button" href="#" onclick="document.querySelector('[data-dashboard-tab-button=install-code]').click(); return false;">העתק קוד הטמעה</a>
                                    @endif
                                </div>
                            </div>
                            <div class="install-state-meta">
                                <span>זיהוי אחרון: <strong>{{ $installationSeenLabel }}</strong></span>
                            </div>
                        </section>

                        <section class="domain-card">
                            <h2>מדריך התקנה מהיר</h2>
                            <ol class="install-steps-list">
                                <li class="install-step {{ $installState !== 'pending' ? 'is-done' : '' }}">
                                    <span class="install-step-num">1</span>
                                    <div>
                                        <strong>ודא שאתה עובד על האתר הנכון</strong>
                                        <p>בדוק שה־site key בקוד ההטמעה תואם לאתר שרוצים לחבר: <code>{{ $site->domain }}</code></p>
                                    </div>
                                </li>
                                <li class="install-step {{ $installState !== 'pending' ? 'is-done' : '' }}">
                                    <span class="install-step-num">2</span>
                                    <div>
                                        <strong>העתק את קוד ההטמעה</strong>
                                        <p>לחץ על "קוד הטמעה" בלשוניות מעלה והעתק את קטע הקוד.</p>
                                    </div>
                                </li>
                                <li class="install-step {{ in_array($installState, ['installed', 'stale']) ? 'is-done' : '' }}">
                                    <span class="install-step-num">3</span>
                                    <div>
                                        <strong>הדבק לפני תגית הסגירה של <code>&lt;/body&gt;</code></strong>
                                        <p>ב-WordPress: Appearance → Theme Editor. בשופיפיי: Themes → Edit Code. בוויקס: Settings → Custom Code.</p>
                                    </div>
                                </li>
                                <li class="install-step {{ $installState === 'installed' ? 'is-done' : '' }}">
                                    <span class="install-step-num">4</span>
                                    <div>
                                        <strong>פתח את האתר ובדוק שהווידג׳ט מופיע</strong>
                                        <p>לאחר הטמעה, חזור לכאן — המצב יתעדכן אוטומטית לאחר שהדפדפן יטען את הקוד.</p>
                                    </div>
                                </li>
                            </ol>
                        </section>

                        @if ($licenseStatus !== 'active')
                            <section class="domain-card">
                                <h2>הפעלת הרישיון</h2>
                                <p class="panel-intro">כרגע זה אתר שנוצר כרישיון חדש ועדיין לא הופעל. לפני שהווידג׳ט יעבוד באמת באתר, צריך להפעיל את הרישיון הזה.</p>
                                <form method="POST" action="{{ route('dashboard.account.activate', ['site' => $site->id]) }}">
                                    @csrf
                                    <input type="hidden" name="site_id" value="{{ $site->id }}">
                                    <button class="primary-button" type="submit">הפעל רישיון לאתר הזה</button>
                                </form>
                            </section>
                        @endif
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="install-code">
                        <section class="domain-card">
                            <h2>קוד הטמעה</h2>
                            <div class="domain-code-block">
                                <code id="install-embed-code">{{ $embedCode }}</code>
                                <button class="copy-button" type="button" data-copy-target="install-embed-code">העתק קוד הטמעה</button>
                            </div>
                            <p class="panel-intro">מטמיעים פעם אחת לפני <code>&lt;/body&gt;</code>. אם האתר לא פעיל, הכפתור יוצג באדום ויפנה לעמוד רכישה במקום לפתוח את הווידג׳ט.</p>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="install-config">
                        <section class="domain-card">
                            <h2>תצורת הווידג׳ט של האתר</h2>
                            <div class="domain-info-list">
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
                        </section>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
