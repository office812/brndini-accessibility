@extends('layouts.auth')

@php($isLogin = $mode === 'login')
@php($signupStep = old('flow_step', old('signup_step', '1')))

@section('content')

{{-- ═══════════════════════════ LOGIN ═══════════════════════════════ --}}
@if ($isLogin)
<section class="auth-screen auth-screen-login">
    <div class="auth-screen-panel">
        <a class="auth-brand" href="{{ route('home') }}">
            <span class="brand-mark" aria-hidden="true">
                <img class="brand-logo-image" src="{{ url('/inn-logo.png') }}" alt="">
            </span>
            <span>
                <strong>A11Y Bridge</strong>
                <small>כלי חינמי להטמעת וידג׳ט נגישות</small>
            </span>
        </a>

        <div class="auth-screen-copy">
            <p class="eyebrow">התחברות</p>
            <h1>חזרה מהירה לסביבת העבודה</h1>
            <p class="hero-text">כניסה ישירה לדשבורד, להטמעה, להצהרה ולתמיכה.</p>
        </div>

        <form class="stack-form auth-form" method="POST" action="{{ route('login') }}">
            @csrf
            <label for="login_email">אימייל</label>
            <input id="login_email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email">

            <label for="login_password">סיסמה</label>
            <div class="password-field">
                <input id="login_password" name="password" type="password" required autocomplete="current-password">
                <button class="password-toggle" type="button" data-password-toggle="login_password" aria-label="הצג סיסמה">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>
                </button>
            </div>

            <button class="primary-button auth-submit" type="submit">כניסה לחשבון</button>
        </form>

        <div class="auth-links-row">
            <a href="{{ route('register.show') }}">אין לך חשבון? פתח חשבון חינם</a>
            <a href="{{ route('home') }}">חזרה לאתר</a>
        </div>
    </div>

    <aside class="auth-screen-showcase">
        <div class="auth-showcase-media">
            <div class="auth-showcase-glow"></div>
            <div class="auth-showcase-window">
                <div class="auth-showcase-header"><span></span><span></span><span></span></div>
                <div class="auth-showcase-body">
                    <div class="auth-showcase-stat">
                        <span class="auth-showcase-label">ווידג׳ט מנוהל</span>
                        <strong>מתקינים פעם אחת</strong>
                        <p>קוד הטמעה קבוע עם הגדרות שנמשכות מהפלטפורמה.</p>
                    </div>
                    <div class="auth-showcase-stat">
                        <span class="auth-showcase-label">ציות</span>
                        <strong>הצהרה + עמוד ציבורי</strong>
                        <p>קישור להצהרה, פרטי קשר וניסוח בסיסי ברור לגולשים.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="auth-showcase-footer">
            <div class="auth-showcase-quote">
                <h2>פותחים חשבון, מטמיעים קוד אחד, ומתחילים לנהל.</h2>
            </div>
            <div class="auth-showcase-testimonial">
                <strong>מוצר חינמי שמוביל מהר לאתר ראשון מחובר, בלי בלבול.</strong>
                <span>A11Y Bridge</span>
            </div>
        </div>
    </aside>
</section>

{{-- ═══════════════════════════ SIGNUP ══════════════════════════════ --}}
@else
<div class="onb-page">

    {{-- Header --}}
    <header class="onb-header">
        <a class="onb-brand" href="{{ route('home') }}">
            <span class="brand-mark" aria-hidden="true">
                <img class="brand-logo-image" src="{{ url('/inn-logo.png') }}" alt="">
            </span>
            <strong>A11Y Bridge</strong>
        </a>
        <a class="onb-login-link" href="{{ route('login.show') }}">כבר יש לך חשבון? התחבר</a>
    </header>

    {{-- Card --}}
    <main class="onb-main">
        <div class="onb-card">

            {{-- Progress dots --}}
            <div class="onb-progress" aria-label="שלבי ההרשמה" role="list">
                <button class="onb-dot is-done {{ $signupStep === '1' ? 'is-active' : '' }}" type="button" data-flow-step="1" role="listitem" aria-current="{{ $signupStep === '1' ? 'step' : 'false' }}">
                    <span class="onb-dot-num">1</span>
                    <span class="onb-dot-label">האתר</span>
                </button>
                <span class="onb-dot-line {{ in_array($signupStep, ['2','3']) ? 'is-done' : '' }}" aria-hidden="true"></span>
                <button class="onb-dot {{ $signupStep === '2' ? 'is-active' : '' }} {{ $signupStep === '3' ? 'is-done' : '' }}" type="button" data-flow-step="2" role="listitem" aria-current="{{ $signupStep === '2' ? 'step' : 'false' }}">
                    <span class="onb-dot-num">2</span>
                    <span class="onb-dot-label">השם</span>
                </button>
                <span class="onb-dot-line {{ $signupStep === '3' ? 'is-done' : '' }}" aria-hidden="true"></span>
                <button class="onb-dot {{ $signupStep === '3' ? 'is-active' : '' }}" type="button" data-flow-step="3" role="listitem" aria-current="{{ $signupStep === '3' ? 'step' : 'false' }}">
                    <span class="onb-dot-num">3</span>
                    <span class="onb-dot-label">החשבון</span>
                </button>
            </div>

            {{-- Form --}}
            <form class="onb-form" method="POST" action="{{ route('register') }}" data-flow-wizard novalidate>
                @csrf
                <input type="hidden" name="signup_step" value="{{ $signupStep }}">
                <input type="hidden" name="flow_step" value="{{ $signupStep }}" data-flow-step-input>

                {{-- ── Step 1: Domain ──────────────────────────────────── --}}
                <div class="onb-step {{ $signupStep === '1' ? 'is-active' : '' }}"
                     data-flow-stage="1"
                     data-flow-caption="הדומיין מייצר סביבת עבודה וקוד הטמעה ייחודי לאתר.">

                    <div class="onb-step-head">
                        <h1 class="onb-question">מה הדומיין של האתר?</h1>
                        <p class="onb-hint">הכתובת הזו תהפוך לסביבת העבודה שלך</p>
                    </div>

                    <div class="onb-field">
                        <input class="onb-input" id="domain" name="domain" type="text"
                               value="{{ old('domain') }}"
                               placeholder="https://my-site.co.il"
                               autocomplete="url"
                               required>
                    </div>

                    <button class="primary-button onb-cta" type="button" data-flow-next>
                        המשך
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>

                    <p class="onb-reassure">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        חינמי לחלוטין · ללא כרטיס אשראי · ללא התחייבות
                    </p>
                </div>

                {{-- ── Step 2: Site name ───────────────────────────────── --}}
                <div class="onb-step {{ $signupStep === '2' ? 'is-active' : '' }}"
                     data-flow-stage="2"
                     data-flow-caption="שם ברור לדשבורד ולהצהרת הנגישות שתיווצר.">

                    <div class="onb-step-head">
                        <h1 class="onb-question">מה שם האתר?</h1>
                        <p class="onb-hint">ישמש לדשבורד ולהצהרת הנגישות שתיווצר</p>
                    </div>

                    <div class="onb-field">
                        <label class="onb-label" for="site_name">שם האתר</label>
                        <input class="onb-input" id="site_name" name="site_name" type="text"
                               value="{{ old('site_name') }}"
                               placeholder="לדוגמה: My Shop"
                               required>
                    </div>

                    <div class="onb-field">
                        <label class="onb-label onb-label-optional" for="company_name">
                            שם החברה
                            <span class="onb-optional">אופציונלי</span>
                        </label>
                        <input class="onb-input" id="company_name" name="company_name" type="text"
                               value="{{ old('company_name', old('site_name')) }}"
                               placeholder="לדוגמה: Brndini Ltd">
                    </div>

                    <div class="onb-actions">
                        <button class="ghost-button onb-back" type="button" data-flow-prev>← חזרה</button>
                        <button class="primary-button onb-cta" type="button" data-flow-next>
                            המשך
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ── Step 3: Account ─────────────────────────────────── --}}
                <div class="onb-step {{ $signupStep === '3' ? 'is-active' : '' }}"
                     data-flow-stage="3"
                     data-flow-caption="פרטי הגישה — ואז הקוד שלך מוכן.">

                    <div class="onb-step-head">
                        <h1 class="onb-question">יוצרים את החשבון</h1>
                        <p class="onb-hint">אחרי זה — קוד ההטמעה שלך מוכן מיד</p>
                    </div>

                    <div class="onb-field">
                        <label class="onb-label" for="signup_email">אימייל</label>
                        <input class="onb-input" id="signup_email" name="email" type="email"
                               value="{{ old('email') }}"
                               placeholder="you@example.com"
                               autocomplete="email"
                               required>
                    </div>

                    <div class="onb-field">
                        <label class="onb-label" for="signup_password">סיסמה</label>
                        <div class="password-field">
                            <input class="onb-input" id="signup_password" name="password" type="password"
                                   placeholder="לפחות 8 תווים"
                                   minlength="8"
                                   autocomplete="new-password"
                                   required>
                            <button class="password-toggle" type="button" data-password-toggle="signup_password" aria-label="הצג סיסמה">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="onb-field">
                        <label class="onb-label" for="signup_password_confirmation">אימות סיסמה</label>
                        <div class="password-field">
                            <input class="onb-input" id="signup_password_confirmation" name="password_confirmation" type="password"
                                   placeholder="הזן שוב את הסיסמה"
                                   minlength="8"
                                   autocomplete="new-password"
                                   required>
                            <button class="password-toggle" type="button" data-password-toggle="signup_password_confirmation" aria-label="הצג סיסמה">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="onb-consents">
                        {{-- Combined terms + privacy --}}
                        <label class="onb-consent-row">
                            <input type="checkbox" name="accepted_terms" value="1" @checked(old('accepted_terms')) required>
                            <input type="hidden" name="accepted_privacy" value="1">
                            <span>קראתי ואני מאשר/ת את <a href="{{ route('legal.terms') }}" target="_blank" rel="noopener">תנאי השימוש</a> ו<a href="{{ route('legal.privacy') }}" target="_blank" rel="noopener">מדיניות הפרטיות</a></span>
                        </label>
                        <label class="onb-consent-row">
                            <input type="checkbox" name="acknowledged_self_service" value="1" @checked(old('acknowledged_self_service')) required>
                            <span>הבנתי שזהו כלי self-service — הכלי לא מחליף ייעוץ נגישות מקצועי</span>
                        </label>
                    </div>

                    <div class="onb-actions">
                        <button class="ghost-button onb-back" type="button" data-flow-prev>← חזרה</button>
                        <button class="primary-button onb-cta onb-submit" type="submit">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/></svg>
                            צור חשבון חינם
                        </button>
                    </div>
                </div>

            </form>
        </div>

        {{-- Social proof strip --}}
        <div class="onb-trust">
            <span class="onb-trust-item">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/></svg>
                קוד הטמעה מוכן מיד
            </span>
            <span class="onb-trust-item">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/></svg>
                ללא כרטיס אשראי
            </span>
            <span class="onb-trust-item">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/></svg>
                פחות מ-2 דקות
            </span>
        </div>

        <p class="onb-back-link">
            <a href="{{ route('home') }}">← חזרה לאתר A11Y Bridge</a>
        </p>
    </main>

</div>
@endif

@endsection
