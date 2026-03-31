@extends('layouts.app')

@php($title = 'Dashboard | A11Y Bridge')

@section('content')
    <section class="dashboard-header">
        <div>
            <p class="eyebrow">Dashboard</p>
            <h1>{{ $user->name }}</h1>
            <p class="hero-text">
                כאן מגדירים את ה־widget, שומרים שפת מותג אחידה, ומעתיקים קוד הטמעה קבוע.
                כל שינוי נשמר מיד ונמשך לפי <code>site key</code>.
            </p>

            <div class="metric-grid">
                <div class="metric-card">
                    <strong>Hosted widget</strong>
                    <span>האתר מקבל שינויים אוטומטית מאותו סקריפט קבוע.</span>
                </div>
                <div class="metric-card">
                    <strong>Live branding</strong>
                    <span>צבע, גודל, מיקום ושפה מעודכנים מתוך הדשבורד.</span>
                </div>
                <div class="metric-card">
                    <strong>Governance</strong>
                    <span>הצהרת נגישות והעדפות תצוגה באותה שכבת ניהול.</span>
                </div>
            </div>
        </div>

        <div class="code-card">
            <span class="meta-label">Site key</span>
            <strong>{{ $site->site_name }}</strong>
            <p class="inline-note">המזהה הציבורי של האתר: <code>{{ $site->public_key }}</code></p>
            <span class="meta-label">Embed script</span>
            <code id="embed-code">{{ $embedCode }}</code>
            <button class="copy-button" type="button" data-copy-target="embed-code">העתק קוד הטמעה</button>
        </div>
    </section>

    <section class="command-strip" aria-label="Workspace signals">
        <article class="command-card">
            <span class="command-label">Active domain</span>
            <strong>{{ $site->domain }}</strong>
            <p>זה הדומיין שמקבל כרגע את ה־configuration מתוך הפלטפורמה.</p>
        </article>
        <article class="command-card">
            <span class="command-label">Service mode</span>
            <strong>{{ $serviceModes[$site->service_mode] ?? 'Managed accessibility layer' }}</strong>
            <p>כך המוצר ממסגר את רמת השירות, ה־governance והליווי סביב ה־widget.</p>
        </article>
        <article class="command-card">
            <span class="command-label">Statement status</span>
            <strong>{{ $site->statement_url ? 'Connected' : 'Needs action' }}</strong>
            <p>{{ $site->statement_url ? 'הצהרת הנגישות מחוברת כבר ל־widget.' : 'כדאי לחבר statement URL כדי לסגור את החוויה.' }}</p>
        </article>
    </section>

    <section class="dashboard-grid">
        <form class="panel-card stack-form" method="POST" action="{{ route('dashboard.update') }}">
            @csrf

            <p class="eyebrow">Company</p>
            <h2>פרטי מותג ואתר</h2>
            <p class="panel-intro">מכאן מנהלים את כל מה שהלקוח רואה בפועל: פרטי האתר, מסגור השירות, והגדרות ה־widget עצמו.</p>

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

            <div class="form-divider"></div>
            <p class="eyebrow">Widget</p>
            <h2>התנהגות ועיצוב ה־widget</h2>
            <p class="panel-intro">הגדרות אלה נשמרות בפלטפורמה ונמשכות לאתר בכל טעינה, בלי צורך להחליף שוב את קוד ההטמעה.</p>

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

            <button class="primary-button" type="submit">לשמור הגדרות</button>
        </form>

        <aside class="panel-card">
            <p class="eyebrow">Preview</p>
            <h2>איך זה ייראה אצל הלקוח</h2>
            <p class="panel-intro">הצד הימני כאן מתפקד כמו סביבת preview: הוא מראה את מראה ה־widget ואת ההיגיון שמאחורי ההטמעה.</p>

            <div class="preview-stage">
                <div class="preview-window">
                    <div class="preview-content">
                        <strong>{{ $site->site_name }}</strong>
                        <p>כך נראה ה־widget בצד האתר. השינויים שתשמור כאן נמשכים מהשרת בזמן טעינה.</p>

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

            <div class="info-card info-card-tight">
                <h3>מה הלקוח מדביק באתר</h3>
                <p>
                    שורת הסקריפט נשארת קבועה. כל שינוי בדשבורד הזה מעדכן את אותו widget בלי
                    צורך להחליף קוד שוב.
                </p>
            </div>

            <div class="info-card info-card-tight">
                <h3>הערת compliance</h3>
                <p>
                    ה־widget נותן העדפות תצוגה, גישה להצהרת נגישות ומסגרת ניהול. ציות מלא עדיין
                    תלוי גם בקוד האתר, בתוכן ובבדיקות ידניות.
                </p>
            </div>

            <div class="info-card info-card-tight">
                <h3>Operator guidance</h3>
                <p>
                    נסה לעבוד תמיד בסדר הזה: site details, widget, statement URL, ואז בדיקת live באתר עצמו.
                </p>
            </div>
        </aside>
    </section>
@endsection
