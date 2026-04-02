@extends('layouts.app')

@php($title = 'התקנה והתאמת הווידג׳ט | A11Y Bridge')

@section('content')
    <section class="domain-shell">
        @include('partials.domain-sidebar', ['activeSection' => 'install'])

        <div class="domain-shell-main">
            <section class="domain-shell-header">
                <h1>התקנה והתאמת הווידג׳ט</h1>
            </section>

            <section class="domain-card domain-hero-card">
                <div>
                    <p class="eyebrow">מרכז התקנה</p>
                    <h2>{{ $site->site_name }}</h2>
                    <p class="panel-intro">הקוד הזה שייך רק לאתר הזה. אם מוסיפים אתר נוסף, צריך לפתוח עבורו רישיון חדש ולהטמיע קוד חדש שמתאים ל־site key שלו.</p>
                </div>

                <div class="billing-hero-meta">
                    <span class="status-pill {{ $licenseStatus === 'active' ? 'is-good' : 'is-warn' }}">{{ $licenseStatus === 'active' ? 'מוכן להטמעה' : 'דורש הפעלת רישיון' }}</span>
                    <span class="status-pill {{ $installationTone === 'good' ? 'is-good' : ($installationTone === 'neutral' ? 'is-neutral' : 'is-warn') }}">{{ $installationLabel }}</span>
                    <span class="status-pill is-neutral">{{ $widgetPresetLabels[$widget['preset']] ?? $widget['preset'] }}</span>
                    <span class="status-pill is-neutral">{{ $widgetLayoutLabels[$widget['panelLayout']] ?? $widget['panelLayout'] }}</span>
                </div>
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
                        <section class="domain-card">
                            <h2>סטטוס ההטמעה</h2>
                            <div class="domain-info-list">
                                <div class="domain-info-row">
                                    <span>מצב נוכחי</span>
                                    <strong>{{ $installationLabel }}</strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>זיהוי אחרון</span>
                                    <strong>{{ $installationSeenLabel }}</strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>פירוש המצב</span>
                                    <strong>{{ $installationSummary }}</strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>עמוד שזוהה</span>
                                    <strong>{{ $installationPageUrl ?: 'עדיין לא זוהתה טעינה מהאתר' }}</strong>
                                </div>
                            </div>
                        </section>

                        <section class="domain-card">
                            <h2>רשימת התקנה</h2>
                            <div class="domain-info-list">
                                <div class="domain-info-row">
                                    <span>שלב 1</span>
                                    <strong>ודא שאתה עובד על האתר הנכון ועל ה־site key הנכון</strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>שלב 2</span>
                                    <strong>העתק את קוד ההטמעה הייחודי של האתר הזה</strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>שלב 3</span>
                                    <strong>הדבק לפני תגית הסגירה של body או באזור scripts של המערכת</strong>
                                </div>
                                <div class="domain-info-row">
                                    <span>שלב 4</span>
                                    <strong>שנה preset או צבע בדשבורד וודא שהעדכון נמשך אוטומטית באתר</strong>
                                </div>
                            </div>
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
