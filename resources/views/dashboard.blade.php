@extends('layouts.app')

@php($title = 'לוח ניהול | A11Y Bridge')

@section('content')
    @php($statementConnected = $statementStatus === 'connected')
    @php($serviceLabel = $serviceModes[$site->service_mode] ?? 'תצורת מערכת בסיסית')
    @php($isPremiumPlan = ($currentPlan['name'] ?? '') === 'פרימיום')

    <section class="licenses-shell" data-dashboard-tabs>
        <aside class="licenses-sidebar">
            <div class="licenses-sidebar-block">
                <h2>המוצרים שלי</h2>
                <nav class="licenses-product-nav" aria-label="מוצרים">
                    <div class="licenses-product-group is-current">
                        <a class="is-current" href="{{ route('dashboard', ['site' => $site->id]) }}">
                            <span class="licenses-product-icon">◉</span>
                            <span>ווידג׳ט נגישות</span>
                        </a>

                        <div class="licenses-product-subnav dashboard-tab-nav" aria-label="תתי עמודים של ווידג׳ט נגישות">
                            <button class="dashboard-tab-button is-active" type="button" data-dashboard-tab-button="overview">סקירה</button>
                            <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="licenses">רישיונות</button>
                            <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="new-site">אתר חדש</button>
                            <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="install">הטמעה</button>
                            <button class="dashboard-tab-button" type="button" data-dashboard-tab-button="widget">עיצוב ווידג׳ט</button>
                        </div>
                    </div>

                    <a href="{{ route('dashboard.compliance', ['site' => $site->id]) }}">
                        <span class="licenses-product-icon">◇</span>
                        <span>ציות ובקרה</span>
                    </a>
                </nav>
            </div>

            <div class="licenses-sidebar-block licenses-sidebar-help">
                <span class="meta-label">אתר פעיל</span>
                <h3>{{ $site->site_name }}</h3>
                <p>{{ parse_url($site->domain, PHP_URL_HOST) ?: $site->domain }}</p>
                <div class="mini-status-list">
                    <span class="status-pill {{ $licenseStatus === 'active' ? 'is-good' : 'is-warn' }}">{{ $licenseStatus === 'active' ? 'רישיון פעיל' : 'רישיון לא פעיל' }}</span>
                    <span class="status-pill {{ $installationTone === 'good' ? 'is-good' : ($installationTone === 'neutral' ? 'is-neutral' : 'is-warn') }}">{{ $installationLabel }}</span>
                    <span class="status-pill {{ $statementConnected ? 'is-good' : 'is-neutral' }}">{{ $statementConnected ? 'הצהרה מחוברת' : 'הצהרה חסרה' }}</span>
                </div>
            </div>
        </aside>

        <div class="licenses-main">
            <section class="licenses-welcome">
                <p class="eyebrow">ניהול רישיונות ונגישות</p>
                <h1>{{ $user->name }}, סביבת הניהול שלך מוכנה לעבודה</h1>
            </section>

            @unless($platformReadiness['ready'])
                <section class="alert-strip">
                    <strong>מצב שרת:</strong>
                    <span>{{ $platformReadiness['summary'] }}</span>
                </section>
            @endunless

            <section class="dashboard-workspace dashboard-workspace-inline">
                <div class="dashboard-tab-content">
                    <div class="dashboard-tab-panel is-active" data-dashboard-tab-panel="overview">
                        <section class="steward-overview-grid">
                            <div class="steward-main-stack">
                                <section class="steward-hero-card">
                                    <div class="steward-hero-copy">
                                        <span class="portal-hero-kicker">לוח בקרה מרכזי</span>
                                        <h2>הופכים את האתר לנגיש, מנוהל ומוכן לעבודה.</h2>
                                        <p>
                                            כל מה שחשוב באמת נמצא כאן: הטמעה, רישיון, בדיקות, הצהרה בסיסית ותמיכה טכנית.
                                            פחות גלילה, יותר שליטה, ובמבט אחד על סטטוס האתר הפעיל.
                                        </p>

                                        <div class="steward-hero-actions">
                                            <a class="primary-button" href="{{ route('dashboard.install', ['site' => $site->id]) }}">התקנה והטמעה</a>
                                            <a class="secondary-button" href="{{ route('dashboard.compliance', ['site' => $site->id]) }}">בדיקות והצהרה</a>
                                        </div>
                                    </div>

                                    <div class="steward-hero-visual" aria-hidden="true">
                                        <div class="steward-window">
                                            <div class="steward-window-bar">
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                            </div>
                                            <div class="steward-window-grid">
                                                <article>
                                                    <small>הטמעה</small>
                                                    <strong>{{ $installationLabel }}</strong>
                                                </article>
                                                <article>
                                                    <small>מסלול</small>
                                                    <strong>{{ $currentPlan['name'] }}</strong>
                                                </article>
                                                <article>
                                                    <small>ציון</small>
                                                    <strong>{{ $auditSnapshot['score'] }}</strong>
                                                </article>
                                                <article>
                                                    <small>הצהרה</small>
                                                    <strong>{{ $statementConnected ? 'מחוברת' : 'חסרה' }}</strong>
                                                </article>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section class="steward-stat-grid">
                                    <article class="steward-stat-card">
                                        <span class="portal-stat-icon">◎</span>
                                        <div>
                                            <span class="meta-label">רישיונות פעילים</span>
                                            <strong>{{ $activeSitesCount }}</strong>
                                            <p>מתוך {{ $sites->count() }} אתרים במערכת</p>
                                        </div>
                                    </article>

                                    <article class="steward-stat-card">
                                        <span class="portal-stat-icon">↺</span>
                                        <div>
                                            <span class="meta-label">סטטוס התקנה</span>
                                            <strong>{{ $installationLabel }}</strong>
                                            <p>{{ $installationSeenLabel }}</p>
                                        </div>
                                    </article>

                                    <article class="steward-stat-card">
                                        <span class="portal-stat-icon">₪</span>
                                        <div>
                                            <span class="meta-label">חבילה נוכחית</span>
                                            <strong>{{ $currentPlan['name'] }}</strong>
                                            <p>{{ $currentPlan['price'] }}</p>
                                        </div>
                                    </article>

                                    <article class="steward-stat-card">
                                        <span class="portal-stat-icon">✓</span>
                                        <div>
                                            <span class="meta-label">בדיקה אחרונה</span>
                                            <strong>{{ $auditSnapshot['score'] }}</strong>
                                            <p>{{ $lastAuditedLabel }}</p>
                                        </div>
                                    </article>
                                </section>

                                <section class="steward-content-grid">
                                    <article class="steward-feed-card">
                                        <div class="steward-feed-head">
                                            <h3>מה חשוב לדעת</h3>
                                            <a class="text-link" href="{{ route('dashboard.compliance', ['site' => $site->id]) }}">למרכז הציות</a>
                                        </div>

                                        <div class="steward-feed-list">
                                            <a class="steward-feed-item" href="{{ route('dashboard.install', ['site' => $site->id]) }}">
                                                <span class="steward-feed-thumb">IN</span>
                                                <div>
                                                    <strong>הטמעה חיה באתר</strong>
                                                    <p>{{ $installationSummary }}</p>
                                                </div>
                                            </a>

                                            <a class="steward-feed-item" href="{{ route('dashboard.account', ['site' => $site->id]) }}">
                                                <span class="steward-feed-thumb">PL</span>
                                                <div>
                                                    <strong>מסלול ורישוי</strong>
                                                    <p>האתר הזה נמצא כרגע במסלול {{ $currentPlan['name'] }}. אפשר לעדכן מסלול, חיוב או סטטוס רישיון מתוך החשבון.</p>
                                                </div>
                                            </a>

                                            <a class="steward-feed-item" href="{{ route('dashboard.compliance', ['site' => $site->id]) }}">
                                                <span class="steward-feed-thumb">WC</span>
                                                <div>
                                                    <strong>בדיקות והצהרה</strong>
                                                    <p>{{ $statementConnected ? 'הצהרת הנגישות כבר מחוברת וניתן להמשיך ללטש אותה.' : 'עדיין חסרה הצהרת נגישות מחוברת, וכדאי להשלים אותה מהיוצר המובנה.' }}</p>
                                                </div>
                                            </a>
                                        </div>
                                    </article>

                                    <article class="steward-feed-card">
                                        <div class="steward-feed-head">
                                            <h3>מה כדאי לעשות עכשיו</h3>
                                            <span class="meta-label">צעדים מומלצים</span>
                                        </div>

                                        <div class="steward-feed-list">
                                            <a class="steward-feed-item" href="{{ route('dashboard.install', ['site' => $site->id]) }}">
                                                <span class="steward-feed-thumb">01</span>
                                                <div>
                                                    <strong>בדוק שהקוד באמת מותקן</strong>
                                                    <p>פתח את מסך ההטמעה, העתק את הקוד הייעודי לאתר הזה וודא שהווידג׳ט נטען בפועל.</p>
                                                </div>
                                            </a>

                                            <a class="steward-feed-item" href="{{ route('dashboard', ['site' => $site->id]) }}#tab-widget">
                                                <span class="steward-feed-thumb">02</span>
                                                <div>
                                                    <strong>לטש את עיצוב הווידג׳ט</strong>
                                                    <p>בחר פריסט, מבנה פאנל, אייקון וצבע כדי להתאים את הווידג׳ט למותג של הלקוח.</p>
                                                </div>
                                            </a>

                                            <a class="steward-feed-item" href="{{ route('dashboard.support', ['site' => $site->id]) }}">
                                                <span class="steward-feed-thumb">03</span>
                                                <div>
                                                    <strong>פתח פנייה אם צריך</strong>
                                                    <p>אם משהו לא נטען או לא נשמר, התמיכה הטכנית זמינה עם מעקב מסודר אחר כל פנייה.</p>
                                                </div>
                                            </a>
                                        </div>
                                    </article>
                                </section>
                            </div>

                            <aside class="steward-side-stack">
                                <article class="steward-side-card">
                                    <span class="steward-side-icon">?</span>
                                    <div>
                                        <h3>יש שאלות?</h3>
                                        <p>התמיכה עוזרת בשימוש במערכת: הטמעה, חיבור הצהרה, תקלות רישוי והגדרת וידג׳ט.</p>
                                        <a class="secondary-button" href="{{ route('dashboard.support', ['site' => $site->id]) }}">פתח פנייה</a>
                                    </div>
                                </article>

                                <article class="steward-side-card">
                                    <span class="steward-side-icon">!</span>
                                    <div>
                                        <h3>מצב האתר</h3>
                                        <div class="mini-status-list">
                                            <span class="status-pill {{ $licenseStatus === 'active' ? 'is-good' : 'is-warn' }}">{{ $licenseStatus === 'active' ? 'רישיון פעיל' : 'רישיון לא פעיל' }}</span>
                                            <span class="status-pill {{ $installationTone === 'good' ? 'is-good' : ($installationTone === 'neutral' ? 'is-neutral' : 'is-warn') }}">{{ $installationLabel }}</span>
                                            <span class="status-pill {{ $statementConnected ? 'is-good' : 'is-neutral' }}">{{ $statementConnected ? 'הצהרה מחוברת' : 'הצהרה חסרה' }}</span>
                                        </div>
                                        <p>{{ $openAlertsCount > 0 ? 'יש כרגע התראות פתוחות שכדאי לבדוק.' : 'כרגע אין התראות פתוחות לאתר הזה.' }}</p>
                                    </div>
                                </article>

                                <article class="steward-side-card steward-side-card-accent">
                                    <span class="steward-side-icon">★</span>
                                    <div>
                                        <p class="eyebrow">{{ $isPremiumPlan ? 'מסלול פעיל' : 'גישה מתקדמת' }}</p>
                                        <h3>{{ $isPremiumPlan ? 'פרימיום כבר פעיל באתר הזה' : 'שדרוג לפרימיום' }}</h3>
                                        <p>{{ $isPremiumPlan ? 'כל הפיצ׳רים המתקדמים פתוחים: פרופילים, מדריך קריאה, שליטה רחבה יותר ועיצוב מורחב.' : ($recommendedPlan['description'] ?? 'פתח את היכולות המתקדמות ביותר של הווידג׳ט והצג חוויה מלאה יותר למבקרים.') }}</p>
                                        <a class="primary-button" href="{{ route('dashboard.account', ['site' => $site->id]) }}">{{ $isPremiumPlan ? 'לניהול המסלול' : 'לשדרוג לפרימיום' }}</a>
                                    </div>
                                </article>
                            </aside>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="licenses">
                        <section class="licenses-toolbar">
                            <div>
                                <h2>רישיונות הווידג׳ט <small>| סך הכול {{ $sites->count() }}</small></h2>
                            </div>
                            <div class="licenses-toolbar-actions">
                                <a class="secondary-button" href="{{ route('dashboard.compliance', ['site' => $site->id]) }}">ביקורות והתראות</a>
                                <button class="primary-button" type="button" data-dashboard-tab-link="new-site">הוסף אתר חדש</button>
                            </div>
                        </section>

                        <section class="licenses-table-card">
                            <div class="licenses-filters">
                                <label class="licenses-search">
                                    <span>⌕</span>
                                    <input type="text" value="{{ parse_url($site->domain, PHP_URL_HOST) ?: $site->domain }}" aria-label="חיפוש דומיין" readonly>
                                </label>
                                <select aria-label="סינון לפי סטטוס">
                                    <option>{{ $licenseStatus === 'active' ? 'פעיל' : 'לא פעיל' }}</option>
                                    <option>כל הרישיונות</option>
                                </select>
                            </div>

                            <div class="licenses-table-wrap">
                                <table class="licenses-table">
                                    <thead>
                                        <tr>
                                            <th>דומיין</th>
                                            <th>רישיון</th>
                                            <th>מסלול</th>
                                            <th>ציון</th>
                                            <th>פעולה</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sites as $licenseSite)
                                            @php($licenseBilling = $licenseSite->billingConfig())
                                            @php($licenseAudit = $licenseSite->auditConfig())
                                            <tr>
                                                <td data-label="דומיין">{{ parse_url($licenseSite->domain, PHP_URL_HOST) ?: $licenseSite->domain }}</td>
                                                <td data-label="רישיון">
                                                    <span class="status-pill {{ $licenseSite->licenseActive() ? 'is-good' : 'is-warn' }}">
                                                        {{ $licenseSite->licenseActive() ? 'פעיל' : 'לא פעיל' }}
                                                    </span>
                                                </td>
                                                <td data-label="מסלול"><span class="status-pill is-neutral">{{ $billingPlans[$licenseBilling['plan']]['label'] ?? $licenseBilling['plan'] }}</span></td>
                                                <td data-label="ציון">{{ $licenseAudit['score'] ?? 0 }}</td>
                                                <td class="table-actions-cell" data-label="פעולה">
                                                    <a class="licenses-manage-link" href="{{ route('dashboard.account', ['site' => $licenseSite->id]) }}">ניהול</a>
                                                    @if (! $licenseSite->licenseActive())
                                                        <form method="POST" action="{{ route('dashboard.account.activate', ['site' => $licenseSite->id]) }}">
                                                            @csrf
                                                            <input type="hidden" name="site_id" value="{{ $licenseSite->id }}">
                                                            <button class="table-inline-button" type="submit">הפעל עכשיו</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="new-site">
                        <section class="licenses-lower-grid licenses-lower-grid-single" id="new-license-form">
                            <article class="portal-content-card">
                                <div>
                                    <p class="eyebrow">רישיון חדש</p>
                                    <h2>פתיחת אתר חדש כרישיון נפרד</h2>
                                </div>
                                <p class="panel-intro">כל אתר חדש מקבל מפתח אתר משלו, חיוב משלו, בדיקה משלו, התראות משלו וקוד הטמעה עצמאי. אין יותר קוד אחד שמכסה את כל החשבון.</p>

                                <form class="stack-form" method="POST" action="{{ route('dashboard.sites.store') }}">
                                    @csrf
                                    <label for="new_site_name">שם האתר</label>
                                    <input id="new_site_name" name="site_name" type="text" value="{{ old('site_name') }}" required>

                                    <label for="new_domain">דומיין</label>
                                    <input id="new_domain" name="domain" type="text" value="{{ old('domain') }}" placeholder="https://example.com" required>

                                    <button class="primary-button" type="submit">צור רישיון לאתר חדש</button>
                                </form>
                            </article>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="install">
                        <section class="licenses-lower-grid licenses-lower-grid-single">
                            <article class="portal-content-card portal-content-card-code">
                                <div>
                                    <p class="eyebrow">הרישיון הפעיל</p>
                                    <h2>קוד הטמעה ומפתח האתר</h2>
                                </div>
                                <p class="panel-intro">הסקריפט תמיד נשאר קבוע עבור האתר הזה בלבד. כל שינוי בהגדרות או בפריסט נמשך אוטומטית מהפלטפורמה.</p>

                                <div class="portal-code-grid">
                                    <div class="portal-code-block">
                                        <span class="meta-label">מזהה אתר</span>
                                        <strong>{{ $site->site_name }}</strong>
                                        <p class="inline-note">המזהה הציבורי של האתר: <code>{{ $site->public_key }}</code></p>
                                    </div>
                                    <div class="portal-code-block portal-code-block-dark">
                                        <span class="meta-label">קוד הטמעה</span>
                                        <code id="embed-code">{{ $embedCode }}</code>
                                        <button class="copy-button" type="button" data-copy-target="embed-code">העתק קוד הטמעה</button>
                                    </div>
                                </div>

                                @if ($licenseStatus !== 'active')
                                    <form class="inline-form-actions" method="POST" action="{{ route('dashboard.account.activate', ['site' => $site->id]) }}">
                                        @csrf
                                        <input type="hidden" name="site_id" value="{{ $site->id }}">
                                        <button class="primary-button" type="submit">הפעל את הרישיון של האתר הזה</button>
                                    </form>
                                @endif
                            </article>
                        </section>
                    </div>

                    <div class="dashboard-tab-panel" data-dashboard-tab-panel="widget">
                        <section class="widget-builder-grid">
                            <form class="panel-card stack-form portal-form-card widget-builder-form" method="POST" action="{{ route('dashboard.update') }}">
                                @csrf
                                <input type="hidden" name="site_id" value="{{ $site->id }}">

                                <div class="portal-card-head">
                                    <div>
                                        <p class="eyebrow">סביבת עבודה</p>
                                        <h2>מותג, אתר והווידג׳ט של האתר הזה</h2>
                                    </div>
                                    <button class="primary-button" type="submit">לשמור הגדרות</button>
                                </div>
                                <p class="panel-intro">מכאן מגדירים פריסטים, מבני פאנל, צבעים, פקדים ומידע עסקי עבור אתר אחד בלבד. התצוגה המקדימה משמאל נשארת מול העיניים לאורך כל העריכה.</p>

                                <div class="widget-compact-meta">
                                    <span class="widget-meta-pill">פריסט: {{ $widgetPresetLabels[$widget['preset']] ?? $widget['preset'] }}</span>
                                    <span class="widget-meta-pill">פאנל: {{ $widgetLayoutLabels[$widget['panelLayout']] ?? $widget['panelLayout'] }}</span>
                                    <span class="widget-meta-pill">דומיין: {{ $site->domain }}</span>
                                </div>

                                <div class="widget-pane-shell" data-widget-pane-root>
                                    <div class="widget-pane-switcher" role="tablist" aria-label="הגדרות ווידג׳ט">
                                        <button class="widget-pane-button is-active" type="button" data-widget-pane-button="button" aria-selected="true">
                                            <span class="widget-pane-icon">◎</span>
                                            <span>כפתור</span>
                                        </button>
                                        <button class="widget-pane-button" type="button" data-widget-pane-button="appearance" aria-selected="false">
                                            <span class="widget-pane-icon">◧</span>
                                            <span>מראה</span>
                                        </button>
                                        <button class="widget-pane-button" type="button" data-widget-pane-button="features" aria-selected="false">
                                            <span class="widget-pane-icon">✓</span>
                                            <span>פקדים</span>
                                        </button>
                                        <button class="widget-pane-button" type="button" data-widget-pane-button="site" aria-selected="false">
                                            <span class="widget-pane-icon">⌘</span>
                                            <span>אתר ומותג</span>
                                        </button>
                                    </div>

                                    <section class="widget-pane is-active" data-widget-pane="button">
                                        <div class="widget-section-head">
                                            <div>
                                                <p class="eyebrow">כפתור</p>
                                                <h3>איך הכפתור עצמו נראה ומתנהג</h3>
                                            </div>
                                            <span class="widget-section-chip">מה שרואים קודם</span>
                                        </div>

                                        <div class="widget-form-grid">
                                            <div>
                                                <label for="widget_position">מיקום</label>
                                                <select id="widget_position" name="widget[position]" data-preview="position">
                                                    <option value="bottom-right" @selected(old('widget.position', $widget['position']) === 'bottom-right')>ימין למטה</option>
                                                    <option value="bottom-left" @selected(old('widget.position', $widget['position']) === 'bottom-left')>שמאל למטה</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="widget_size">גודל</label>
                                                <select id="widget_size" name="widget[size]" data-preview="size">
                                                    <option value="compact" @selected(old('widget.size', $widget['size']) === 'compact')>קומפקטי</option>
                                                    <option value="comfortable" @selected(old('widget.size', $widget['size']) === 'comfortable')>רגיל</option>
                                                    <option value="large" @selected(old('widget.size', $widget['size']) === 'large')>גדול</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="widget_button_mode">תצוגת כפתור</label>
                                                <select id="widget_button_mode" name="widget[buttonMode]" data-preview="button-mode">
                                                    <option value="icon-label" @selected(old('widget.buttonMode', $widget['buttonMode']) === 'icon-label')>אייקון וטקסט</option>
                                                    <option value="label-only" @selected(old('widget.buttonMode', $widget['buttonMode']) === 'label-only')>טקסט בלבד</option>
                                                    <option value="icon-only" @selected(old('widget.buttonMode', $widget['buttonMode']) === 'icon-only')>אייקון בלבד</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="widget_label">טקסט על הכפתור</label>
                                                <input id="widget_label" name="widget[label]" type="text" value="{{ old('widget.label', $widget['label']) }}" data-preview="label" required>
                                            </div>
                                        </div>
                                    </section>

                                    <section class="widget-pane" data-widget-pane="appearance" hidden>
                                        <div class="widget-section-head">
                                            <div>
                                                <p class="eyebrow">מראה</p>
                                                <h3>פריסט, לייאאוט, אייקון וצבע</h3>
                                            </div>
                                            <span class="widget-section-chip">עיצוב חי</span>
                                        </div>

                                        <div class="widget-form-grid">
                                            <div>
                                                <label for="widget_preset">פריסט מוכן</label>
                                                <select id="widget_preset" name="widget[preset]" data-preview="preset">
                                                    <option value="classic" @selected(old('widget.preset', $widget['preset']) === 'classic')>{{ $widgetPresetLabels['classic'] }}</option>
                                                    <option value="high-tech" @selected(old('widget.preset', $widget['preset']) === 'high-tech')>{{ $widgetPresetLabels['high-tech'] }}</option>
                                                    <option value="elegant" @selected(old('widget.preset', $widget['preset']) === 'elegant')>{{ $widgetPresetLabels['elegant'] }}</option>
                                                    <option value="bold" @selected(old('widget.preset', $widget['preset']) === 'bold')>{{ $widgetPresetLabels['bold'] }}</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="widget_panel_layout">מבנה פאנל</label>
                                                <select id="widget_panel_layout" name="widget[panelLayout]" data-preview="panel-layout">
                                                    <option value="stacked" @selected(old('widget.panelLayout', $widget['panelLayout']) === 'stacked')>{{ $widgetLayoutLabels['stacked'] }}</option>
                                                    <option value="split" @selected(old('widget.panelLayout', $widget['panelLayout']) === 'split')>{{ $widgetLayoutLabels['split'] }}</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="widget_icon">סוג אייקון</label>
                                                <select id="widget_icon" name="widget[icon]" data-preview="icon">
                                                    <option value="figure" @selected(old('widget.icon', $widget['icon']) === 'figure')>דמות נגישות</option>
                                                    <option value="spark" @selected(old('widget.icon', $widget['icon']) === 'spark')>ניצוץ</option>
                                                    <option value="shield" @selected(old('widget.icon', $widget['icon']) === 'shield')>מגן</option>
                                                    <option value="pulse" @selected(old('widget.icon', $widget['icon']) === 'pulse')>פולס</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="widget_button_style">סגנון כפתור</label>
                                                <select id="widget_button_style" name="widget[buttonStyle]" data-preview="button-style">
                                                    <option value="solid" @selected(old('widget.buttonStyle', $widget['buttonStyle']) === 'solid')>מודגש</option>
                                                    <option value="soft" @selected(old('widget.buttonStyle', $widget['buttonStyle']) === 'soft')>רך ובהיר</option>
                                                    <option value="glass" @selected(old('widget.buttonStyle', $widget['buttonStyle']) === 'glass')>זכוכית</option>
                                                    <option value="midnight" @selected(old('widget.buttonStyle', $widget['buttonStyle']) === 'midnight')>כהה יוקרתי</option>
                                                </select>
                                            </div>

                                            <div class="widget-color-field">
                                                <label for="widget_color">צבע כפתור</label>
                                                <input id="widget_color" name="widget[color]" type="color" value="{{ old('widget.color', $widget['color']) }}" data-preview="color" required>
                                            </div>

                                            <div>
                                                <label for="widget_language">שפה</label>
                                                <select id="widget_language" name="widget[language]">
                                                    <option value="he" @selected(old('widget.language', $widget['language']) === 'he')>עברית</option>
                                                </select>
                                            </div>
                                        </div>
                                    </section>

                                    <section class="widget-pane" data-widget-pane="features" hidden>
                                        <div class="widget-section-head">
                                            <div>
                                                <p class="eyebrow">פקדים פעילים</p>
                                                <h3>איזה כלים הלקוח יראה בפאנל</h3>
                                            </div>
                                            <span class="widget-section-chip">נגישות בפועל</span>
                                        </div>

                                        <div class="widget-toggle-grid">
                                            <label class="toggle-row">
                                                <input type="hidden" name="widget[showContrast]" value="0">
                                                <input type="checkbox" name="widget[showContrast]" value="1" @checked(old('widget.showContrast', $widget['showContrast']))>
                                                <span>הצג כפתור ניגודיות</span>
                                            </label>

                                            <label class="toggle-row">
                                                <input type="hidden" name="widget[showFontScale]" value="0">
                                                <input type="checkbox" name="widget[showFontScale]" value="1" @checked(old('widget.showFontScale', $widget['showFontScale']))>
                                                <span>הצג שינוי גודל טקסט</span>
                                            </label>

                                            <label class="toggle-row">
                                                <input type="hidden" name="widget[showUnderlineLinks]" value="0">
                                                <input type="checkbox" name="widget[showUnderlineLinks]" value="1" @checked(old('widget.showUnderlineLinks', $widget['showUnderlineLinks']))>
                                                <span>הצג הדגשת קישורים</span>
                                            </label>

                                            <label class="toggle-row">
                                                <input type="hidden" name="widget[showReduceMotion]" value="0">
                                                <input type="checkbox" name="widget[showReduceMotion]" value="1" @checked(old('widget.showReduceMotion', $widget['showReduceMotion']))>
                                                <span>הצג הפחתת תנועה</span>
                                            </label>
                                        </div>
                                    </section>

                                    <section class="widget-pane" data-widget-pane="site" hidden>
                                        <div class="widget-section-head">
                                            <div>
                                                <p class="eyebrow">אתר ומותג</p>
                                                <h3>הנתונים העסקיים שמחוברים לווידג׳ט</h3>
                                            </div>
                                            <span class="widget-section-chip">אתר אחד בלבד</span>
                                        </div>

                                        <div class="widget-form-grid">
                                            <div>
                                                <label for="company_name">שם החברה</label>
                                                <input id="company_name" name="company_name" type="text" value="{{ old('company_name', $user->name) }}" required>
                                            </div>

                                            <div>
                                                <label for="contact_email">אימייל ליצירת קשר</label>
                                                <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $user->contact_email) }}" required>
                                            </div>

                                            <div>
                                                <label for="site_name">שם האתר</label>
                                                <input id="site_name" name="site_name" type="text" value="{{ old('site_name', $site->site_name) }}" required>
                                            </div>

                                            <div>
                                                <label for="domain">דומיין</label>
                                                <input id="domain" name="domain" type="text" value="{{ old('domain', $site->domain) }}" required>
                                            </div>

                                            <div class="widget-field-wide">
                                                <label for="statement_url">קישור להצהרת נגישות</label>
                                                <input id="statement_url" name="statement_url" type="text" value="{{ old('statement_url', $site->statement_url) }}" placeholder="https://your-site.com/accessibility">
                                            </div>

                                            <div>
                                                <label for="service_mode">תצורת מערכת</label>
                                                <select id="service_mode" name="service_mode">
                                                    @foreach ($serviceModes as $value => $label)
                                                        <option value="{{ $value }}" @selected(old('service_mode', $site->service_mode) === $value)>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class="widget-form-actions">
                                    <span class="meta-note">השינויים נשמרים רק לאתר הפעיל שמופיע בצד שמאל, והתצוגה המקדימה מתעדכנת מיד.</span>
                                    <button class="primary-button" type="submit">לשמור הגדרות</button>
                                </div>
                            </form>

                            <article class="panel-card portal-preview-card widget-builder-preview">
                                <p class="eyebrow">תצוגה מקדימה</p>
                                <h2>כך זה ייראה באתר של הלקוח</h2>
                                <p class="panel-intro">שינוי כאן משתקף מיד בצד שמאל: פריסט, לייאאוט, צבע, אייקון, מצב כפתור ופקדים פעילים.</p>

                                <div class="preview-stage">
                                    <div class="preview-window">
                                        <div class="preview-content">
                                            <strong>{{ $site->site_name }}</strong>
                                                <p>האתר הזה עובד עם רישיון עצמאי, חיוב עצמאי וציון ביקורת עצמאי.</p>

                                            <div class="preview-details">
                                                <span class="preview-pill">{{ $currentPlan['name'] }}</span>
                                                <span class="preview-pill">ציון {{ $auditSnapshot['score'] }}</span>
                                            </div>
                                        </div>

                                        <div class="preview-widget preview-{{ $widget['position'] }} preview-size-{{ $widget['size'] }}" id="widget-preview">
                                            <div class="preview-shell preview-preset-{{ $widget['preset'] }} preview-layout-{{ $widget['panelLayout'] }}" id="widget-preview-panel">
                                                <div class="preview-shell-head">
                                                    <strong>הגדרות נגישות</strong>
                                                    <span class="preview-shell-chip">{{ $widgetLayoutLabels[$widget['panelLayout']] ?? $widget['panelLayout'] }}</span>
                                                </div>
                                                <p>פאנל שמדגים פריסטים שונים ושני מבנים שונים, עם אותו מפתח אתר ואותן הגדרות שנשמרות לשרת.</p>
                                                <div class="preview-shell-grid">
                                                    <article>
                                                        <span>ניגודיות</span>
                                                        <small>פעיל</small>
                                                    </article>
                                                    <article>
                                                        <span>טקסט</span>
                                                        <small>{{ $widget['size'] === 'large' ? 'גדול' : 'רגיל' }}</small>
                                                    </article>
                                                    <article>
                                                        <span>הצהרה</span>
                                                        <small>{{ $statementConnected ? 'מחוברת' : 'חסרה' }}</small>
                                                    </article>
                                                    <article>
                                                        <span>חיוב</span>
                                                        <small>{{ $billingPlans[$billing['plan']]['label'] ?? $billing['plan'] }}</small>
                                                    </article>
                                                </div>
                                            </div>

                                            <button
                                                class="preview-badge preview-mode-{{ $widget['buttonMode'] }} preview-style-{{ $widget['buttonStyle'] }} preview-preset-{{ $widget['preset'] }}"
                                                id="widget-preview-button"
                                                type="button"
                                                style="--preview-widget-color: {{ $widget['color'] }}"
                                            >
                                                <span class="preview-badge-icon" id="widget-preview-icon" data-icon="{{ $widget['icon'] }}"></span>
                                                <span class="preview-badge-label" id="widget-preview-label">{{ $widget['label'] }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </section>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
