@extends('layouts.app')

@php($title = 'לוח ניהול | A11Y Bridge')

@section('content')
    @php($statementConnected = filled($site->statement_url))
    @php($serviceLabel = $serviceModes[$site->service_mode] ?? 'Managed accessibility layer')

    <section class="licenses-shell">
        <aside class="licenses-sidebar">
            <div class="licenses-sidebar-block">
                <h2>המוצרים שלי</h2>
                <nav class="licenses-product-nav" aria-label="Products">
                    <a class="is-current" href="{{ route('dashboard') }}">
                        <span class="licenses-product-icon">◉</span>
                        <span>accessWidget</span>
                    </a>
                    <a href="{{ route('dashboard.compliance') }}">
                        <span class="licenses-product-icon">◇</span>
                        <span>accessFlow</span>
                    </a>
                </nav>
            </div>

            <div class="licenses-sidebar-block licenses-sidebar-help">
                <span class="meta-label">סטטוס מהיר</span>
                <h3>{{ $statementConnected ? 'הצהרה מחוברת' : 'חסרה הצהרה' }}</h3>
                <p>{{ $statementConnected ? 'החשבון מוכן עם הצהרת נגישות פעילה.' : 'כדאי לחבר הצהרת נגישות כדי לסגור את חוויית ההטמעה.' }}</p>
            </div>
        </aside>

        <div class="licenses-main">
            <section class="licenses-welcome">
                <p class="eyebrow">הרישיונות שלי</p>
                <h1>{{ $user->name }}, ברוך הבא ל־A11Y Bridge</h1>
            </section>

            <section class="licenses-hero-card">
                <div class="licenses-hero-copy">
                    <h2>נגישות אתרים אוטומטית, פשוטה וברורה.</h2>
                    <p>
                        נהל את ה־widget hosted, את ההטמעה ואת מסגרת ה־compliance מתוך מקום אחד,
                        בלי להחליף שוב את קוד הסקריפט באתר.
                    </p>

                    <div class="licenses-hero-actions">
                        <a class="primary-button" href="{{ route('dashboard.install') }}">התחל התקנה</a>
                        <a class="secondary-button" href="{{ route('dashboard.compliance') }}">למידע על ציות</a>
                    </div>
                </div>

                <div class="licenses-hero-visual" aria-hidden="true">
                    <div class="licenses-widget-orb"></div>
                    <div class="licenses-device-stage">
                        <div class="licenses-device-screen licenses-device-screen-back"></div>
                        <div class="licenses-device-screen licenses-device-screen-front">
                            <div class="licenses-device-topbar">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <div class="licenses-device-list">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="licenses-toolbar">
                <div>
                    <h2>רישיונות accessWidget <small>| סך הכול 1</small></h2>
                </div>
                <div class="licenses-toolbar-actions">
                    <a class="secondary-button" href="{{ route('dashboard.install') }}">ניהול מרוכז</a>
                    <a class="primary-button" href="{{ route('dashboard.install') }}">הוסף אתר חדש</a>
                </div>
            </section>

            <section class="licenses-table-card">
                <div class="licenses-filters">
                    <label class="licenses-search">
                        <span>⌕</span>
                        <input type="text" value="{{ parse_url($site->domain, PHP_URL_HOST) ?: $site->domain }}" aria-label="Search domain">
                    </label>
                    <select aria-label="סינון לפי סטטוס">
                        <option>פעיל</option>
                        <option>ממתין</option>
                    </select>
                </div>

                <div class="licenses-table-wrap">
                    <table class="licenses-table">
                        <thead>
                            <tr>
                                <th>דומיין</th>
                                <th>תאריך סיום</th>
                                <th>סטטוס</th>
                                <th>מסלול</th>
                                <th>פעולה</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ parse_url($site->domain, PHP_URL_HOST) ?: $site->domain }}</td>
                                <td>-</td>
                                <td><span class="status-pill is-good">פעיל</span></td>
                                <td><span class="status-pill is-neutral">{{ $serviceLabel }}</span></td>
                                <td><a class="licenses-manage-link" href="{{ route('dashboard.account') }}">ניהול</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="licenses-lower-grid" id="license-management">
                <article class="portal-content-card portal-content-card-code">
                    <div>
                        <p class="eyebrow">ניהול רישיון</p>
                        <h2>קוד הטמעה וה־site key</h2>
                    </div>
                    <p class="panel-intro">החלק הזה מחליף את מסך ה־manage: הוא מחזיק את ה־site key, את הסקריפט ואת סטטוס החיבור.</p>

                    <div class="portal-code-grid">
                        <div class="portal-code-block">
                            <span class="meta-label">Site key</span>
                            <strong>{{ $site->site_name }}</strong>
                            <p class="inline-note">המזהה הציבורי של האתר: <code>{{ $site->public_key }}</code></p>
                        </div>
                        <div class="portal-code-block portal-code-block-dark">
                            <span class="meta-label">Embed script</span>
                            <code id="embed-code">{{ $embedCode }}</code>
                            <button class="copy-button" type="button" data-copy-target="embed-code">העתק קוד הטמעה</button>
                        </div>
                    </div>
                </article>
            </section>

            <form class="panel-card stack-form portal-form-card" method="POST" action="{{ route('dashboard.update') }}">
                @csrf

                <div class="portal-card-head">
                    <div>
                        <p class="eyebrow">הגדרות סביבת עבודה</p>
                        <h2>פרטי מותג, אתר ו־widget</h2>
                    </div>
                    <button class="primary-button" type="submit">לשמור הגדרות</button>
                </div>
                <p class="panel-intro">מכאן מנהלים את כל מה שהלקוח רואה בפועל: פרטי האתר, מסגור השירות והגדרות ה־widget עצמו.</p>

                <div class="portal-form-section">
                    <p class="eyebrow">חברה</p>
                    <label for="company_name">שם החברה</label>
                    <input id="company_name" name="company_name" type="text" value="{{ old('company_name', $user->name) }}" required>

                    <label for="contact_email">אימייל ליצירת קשר</label>
                    <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $user->contact_email) }}" required>

                    <label for="site_name">שם האתר</label>
                    <input id="site_name" name="site_name" type="text" value="{{ old('site_name', $site->site_name) }}" required>

                    <label for="domain">דומיין</label>
                    <input id="domain" name="domain" type="text" value="{{ old('domain', $site->domain) }}" required>

                    <label for="statement_url">קישור להצהרת נגישות</label>
                    <input id="statement_url" name="statement_url" type="text" value="{{ old('statement_url', $site->statement_url) }}" placeholder="https://your-site.com/accessibility">

                    <label for="service_mode">מסלול שירות</label>
                    <select id="service_mode" name="service_mode">
                        @foreach ($serviceModes as $value => $label)
                            <option value="{{ $value }}" @selected(old('service_mode', $site->service_mode) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-divider"></div>

                <div class="portal-form-section">
                    <p class="eyebrow">הגדרות ווידג׳ט</p>
                    <label for="widget_position">מיקום</label>
                    <select id="widget_position" name="widget[position]" data-preview="position">
                        <option value="bottom-right" @selected(old('widget.position', $widget['position']) === 'bottom-right')>ימין למטה</option>
                        <option value="bottom-left" @selected(old('widget.position', $widget['position']) === 'bottom-left')>שמאל למטה</option>
                    </select>

                    <label for="widget_color">צבע כפתור</label>
                    <input id="widget_color" name="widget[color]" type="color" value="{{ old('widget.color', $widget['color']) }}" data-preview="color" required>

                    <label for="widget_size">גודל</label>
                    <select id="widget_size" name="widget[size]" data-preview="size">
                        <option value="compact" @selected(old('widget.size', $widget['size']) === 'compact')>קומפקטי</option>
                        <option value="comfortable" @selected(old('widget.size', $widget['size']) === 'comfortable')>רגיל</option>
                        <option value="large" @selected(old('widget.size', $widget['size']) === 'large')>גדול</option>
                    </select>

                    <label for="widget_label">טקסט על הכפתור</label>
                    <input id="widget_label" name="widget[label]" type="text" value="{{ old('widget.label', $widget['label']) }}" data-preview="label" required>

                    <label for="widget_language">שפה</label>
                    <select id="widget_language" name="widget[language]">
                        <option value="he" @selected(old('widget.language', $widget['language']) === 'he')>עברית</option>
                    </select>

                    <div class="toggle-grid">
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
                </div>
            </form>

            <section class="licenses-lower-grid">
                <article class="panel-card portal-preview-card">
                    <p class="eyebrow">Preview</p>
                    <h2>איך זה ייראה אצל הלקוח</h2>
                    <p class="panel-intro">תצוגה מקדימה חיה של ה־widget לפי ההגדרות הנוכחיות.</p>

                    <div class="preview-stage">
                        <div class="preview-window">
                            <div class="preview-content">
                                <strong>{{ $site->site_name }}</strong>
                                <p>כך נראה ה־widget באתר. השינויים שתשמור כאן נמשכים מהשרת בזמן טעינה.</p>

                                <div class="preview-details">
                                    <span class="preview-pill">הגדרה מנוהלת</span>
                                    <span class="preview-pill">ממשק בעברית</span>
                                </div>
                            </div>

                            <div
                                class="preview-widget preview-{{ $widget['position'] }} preview-size-{{ $widget['size'] }}"
                                id="widget-preview"
                            >
                                <div class="preview-shell">
                                    <strong>הגדרות נגישות</strong>
                                    <p>פאנל קומפקטי, שקט וברור עם פעולות חשובות בלבד ובלי עומס מיותר.</p>
                                </div>
                                <button
                                    class="preview-badge"
                                    id="widget-preview-button"
                                    type="button"
                                    style="background-color: {{ $widget['color'] }}"
                                >
                                    {{ $widget['label'] }}
                                </button>
                            </div>
                        </div>
                    </div>
                </article>

                <aside class="portal-side-column">
                    <div class="info-card info-card-tight portal-note-card">
                        <h3>סטטוס הווידג׳ט</h3>
                        <p>הסקריפט קבוע והווידג׳ט ממשיך להתעדכן אוטומטית מאותו site key.</p>
                    </div>

                    <div class="info-card info-card-tight portal-note-card">
                        <h3>ציות וניהול</h3>
                        <p>
                            ה־widget נותן העדפות תצוגה, גישה להצהרת נגישות ומסגרת ניהול. ציות מלא עדיין
                            תלוי גם בקוד האתר, בתוכן ובבדיקות ידניות.
                        </p>
                    </div>

                    <div class="info-card info-card-tight portal-note-card">
                        <h3>הנחיית מפעיל</h3>
                        <p>סדר העבודה המומלץ: site details, widget, statement URL, ואז בדיקת live באתר עצמו.</p>
                    </div>
                </aside>
            </section>
        </div>
    </section>
@endsection
