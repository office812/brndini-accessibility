@extends('layouts.app')

@php($title = 'Dashboard | A11Y Bridge')

@section('content')
    @php($statementConnected = filled($site->statement_url))
    @php($serviceLabel = $serviceModes[$site->service_mode] ?? 'Managed accessibility layer')

    <section class="portal-welcome">
        <div>
            <p class="eyebrow">Partner workspace</p>
            <h1>{{ $user->name }}, ברוך הבא ל־A11Y Bridge</h1>
            <p class="hero-text">
                מכאן מנהלים את ה־widget, ההטמעה וה־compliance messaging של הלקוח שלך במקום אחד,
                עם אותו <code>site key</code> קבוע שמתעדכן מרחוק.
            </p>
        </div>
    </section>

    <section class="portal-hero-grid">
        <article class="portal-hero-card">
            <div class="portal-hero-copy">
                <p class="portal-hero-kicker">Hosted accessibility workspace</p>
                <h2>נהל את שכבת הנגישות של האתר, בלי להחליף שוב קוד.</h2>
                <p>
                    כל שינוי בצבע, מיקום, שפה, statement או framing שירות נשמר בפלטפורמה ונמשך
                    מיידית לאתר דרך הסקריפט הקבוע שכבר הוטמע.
                </p>

                <div class="portal-hero-actions">
                    <a class="primary-button" href="{{ route('dashboard.install') }}">Open install center</a>
                    <a class="secondary-button" href="{{ route('dashboard.compliance') }}">Review compliance</a>
                </div>
            </div>

            <div class="portal-hero-visual" aria-hidden="true">
                <div class="portal-window">
                    <div class="portal-window-bar">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="portal-window-grid">
                        <article>
                            <span>Site key</span>
                            <strong>{{ $site->public_key }}</strong>
                            <small>public id for remote sync</small>
                        </article>
                        <article>
                            <span>Mode</span>
                            <strong>{{ $serviceLabel }}</strong>
                            <small>service framing</small>
                        </article>
                        <article>
                            <span>Domain</span>
                            <strong>{{ $site->domain }}</strong>
                            <small>active production destination</small>
                        </article>
                        <article>
                            <span>Statement</span>
                            <strong>{{ $statementConnected ? 'Connected' : 'Needs action' }}</strong>
                            <small>{{ $statementConnected ? 'linked to widget panel' : 'add URL to complete flow' }}</small>
                        </article>
                    </div>
                </div>
            </div>
        </article>

        <aside class="portal-side-rail">
            <div class="portal-side-card portal-side-card-support">
                <span class="meta-label">We are here for you</span>
                <h3>יש שאלות על ההטמעה?</h3>
                <p>פתח את מסך ההתקנה או את אזור ה־compliance כדי להשלים את החיבור ללקוח.</p>
                <a class="text-link" href="{{ route('dashboard.install') }}">Go to installation</a>
            </div>

            <div class="portal-side-card">
                <span class="meta-label">Statement</span>
                <h3>{{ $statementConnected ? 'הצהרת הנגישות מחוברת' : 'צריך לחבר statement' }}</h3>
                <p>{{ $statementConnected ? 'יש כבר URL פעיל בתוך ה־widget.' : 'כדאי להוסיף URL כדי לסגור חוויית משתמש ומסגור שירות.' }}</p>
            </div>

            <div class="portal-side-card portal-side-card-dark">
                <span class="meta-label">Account mode</span>
                <h3>{{ $serviceLabel }}</h3>
                <p>המסלול הזה שולט על framing השירות, ה־governance והצגת השכבה ללקוח.</p>
            </div>
        </aside>
    </section>

    <section class="portal-stat-strip" aria-label="Workspace summary">
        <article class="portal-stat-card">
            <span class="portal-stat-icon">◎</span>
            <div>
                <strong>1</strong>
                <p>Active hosted widget</p>
            </div>
        </article>
        <article class="portal-stat-card">
            <span class="portal-stat-icon">⌘</span>
            <div>
                <strong>{{ $statementConnected ? 'Ready' : 'Pending' }}</strong>
                <p>Statement connection</p>
            </div>
        </article>
        <article class="portal-stat-card">
            <span class="portal-stat-icon">◔</span>
            <div>
                <strong>{{ $site->domain }}</strong>
                <p>Production domain</p>
            </div>
        </article>
        <article class="portal-stat-card">
            <span class="portal-stat-icon">✦</span>
            <div>
                <strong>{{ $widget['language'] === 'en' ? 'English' : 'עברית' }}</strong>
                <p>Widget interface language</p>
            </div>
        </article>
    </section>

    <section class="portal-content-grid">
        <div class="portal-main-column">
            <article class="portal-content-card portal-content-card-code">
                <div class="portal-card-head">
                    <div>
                        <p class="eyebrow">Embed</p>
                        <h2>קוד הטמעה וה־site key</h2>
                    </div>
                    <a class="secondary-button" href="{{ route('dashboard.install') }}">Install guide</a>
                </div>

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

            <form class="panel-card stack-form portal-form-card" method="POST" action="{{ route('dashboard.update') }}">
            @csrf

                <div class="portal-card-head">
                    <div>
                        <p class="eyebrow">Workspace settings</p>
                        <h2>פרטי מותג, אתר ו־widget</h2>
                    </div>
                    <button class="primary-button" type="submit">לשמור הגדרות</button>
                </div>
                <p class="panel-intro">מכאן מנהלים את כל מה שהלקוח רואה בפועל: פרטי האתר, מסגור השירות והגדרות ה־widget עצמו.</p>

                <div class="portal-form-section">
                    <p class="eyebrow">Company</p>
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
                    <p class="eyebrow">Widget controls</p>
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
                        <option value="en" @selected(old('widget.language', $widget['language']) === 'en')>English</option>
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
        </div>

        <aside class="portal-side-column">
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
                                <span class="preview-pill">Hosted configuration</span>
                                <span class="preview-pill">{{ $widget['language'] === 'en' ? 'English UI' : 'עברית' }}</span>
                            </div>
                        </div>

                        <div
                            class="preview-widget preview-{{ $widget['position'] }} preview-size-{{ $widget['size'] }}"
                            id="widget-preview"
                        >
                            <div class="preview-shell">
                                <strong>Accessibility settings</strong>
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

            <div class="info-card info-card-tight portal-note-card">
                <h3>מה הלקוח מדביק באתר</h3>
                <p>
                    שורת הסקריפט נשארת קבועה. כל שינוי בדשבורד הזה מעדכן את אותו widget בלי
                    צורך להחליף קוד שוב.
                </p>
            </div>

            <div class="info-card info-card-tight portal-note-card">
                <h3>הערת compliance</h3>
                <p>
                    ה־widget נותן העדפות תצוגה, גישה להצהרת נגישות ומסגרת ניהול. ציות מלא עדיין
                    תלוי גם בקוד האתר, בתוכן ובבדיקות ידניות.
                </p>
            </div>

            <div class="info-card info-card-tight portal-note-card">
                <h3>Operator guidance</h3>
                <p>
                    נסה לעבוד תמיד בסדר הזה: site details, widget, statement URL, ואז בדיקת live באתר עצמו.
                </p>
            </div>
        </aside>
    </section>
@endsection
