@extends('layouts.auth')

@php($isLogin = $mode === 'login')
@php($signupStep = old('flow_step', old('signup_step', '1')))

@section('content')
    <section class="auth-screen">
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
                <p class="eyebrow">{{ $isLogin ? 'התחברות' : 'פתיחת חשבון' }}</p>
                <h1>{{ $isLogin ? 'חזרה מהירה לסביבת העבודה' : 'פותחים סביבת עבודה פעילה בדקה' }}</h1>
                <p class="hero-text">
                    {{ $isLogin
                        ? 'כניסה ישירה ל־dashboard, למסך ההטמעה, ל־statement ולתמיכה הטכנית.'
                        : 'ההרשמה נועדה להוביל מהר ל־first connected site: אתר ראשון, snippet קבוע ו־dashboard ברור לעבודה.' }}
                </p>
            </div>

            @if ($isLogin)
                <form class="stack-form auth-form" method="POST" action="{{ route('login') }}">
                    @csrf

                    <label for="login_email">אימייל</label>
                    <input id="login_email" name="email" type="email" value="{{ old('email') }}" required>

                    <label for="login_password">סיסמה</label>
                    <div class="password-field">
                        <input id="login_password" name="password" type="password" required>
                        <button class="password-toggle" type="button" data-password-toggle="login_password" aria-label="הצג או הסתר סיסמה">👁</button>
                    </div>

                    <button class="primary-button auth-submit" type="submit">להתחבר</button>
                </form>

                <div class="auth-links-row">
                    <a href="{{ route('register.show') }}">אין לך חשבון? פתח חשבון</a>
                    <a href="{{ route('home') }}">חזרה לאתר</a>
                </div>
            @else
                <form class="stack-form auth-form auth-form-onboarding" method="POST" action="{{ route('register') }}" data-flow-wizard>
                    @csrf
                    <input type="hidden" name="signup_step" value="{{ $signupStep }}">
                    <input type="hidden" name="flow_step" value="{{ $signupStep }}" data-flow-step-input>

                    <div class="flow-wizard-intro flow-wizard-intro-compact flow-wizard-intro-public">
                        <div class="flow-wizard-intro-copy">
                            <p class="eyebrow">Setup flow</p>
                            <strong>מכינים אתר ראשון, חשבון ראשון ו־snippet ראשון</strong>
                            <p class="signup-note">שלושה שלבים קצרים כדי לעבור מהר מסקרנות להטמעה.</p>
                        </div>
                        <div class="flow-wizard-stepper" aria-label="התקדמות בהרשמה">
                            <button class="flow-wizard-step {{ $signupStep === '1' ? 'is-active' : '' }}" type="button" data-flow-step="1">
                                <span>1</span>
                                <strong>האתר</strong>
                            </button>
                            <button class="flow-wizard-step {{ $signupStep === '2' ? 'is-active' : '' }}" type="button" data-flow-step="2">
                                <span>2</span>
                                <strong>העסק</strong>
                            </button>
                            <button class="flow-wizard-step {{ $signupStep === '3' ? 'is-active' : '' }}" type="button" data-flow-step="3">
                                <span>3</span>
                                <strong>החשבון</strong>
                            </button>
                        </div>
                    </div>

                    <div class="flow-shell-grid flow-shell-grid-product">
                        <div class="flow-shell-main">
                            <div class="flow-progress-shell">
                                <div class="flow-progress-track" aria-hidden="true">
                                    <span class="flow-progress-fill" data-flow-progress-fill></span>
                                </div>
                                <div class="flow-progress-meta">
                                    <strong data-flow-progress-label>שלב 1 מתוך 3</strong>
                                    <span data-flow-progress-caption>מתחילים מהדומיין כדי להכין סביבת עבודה וקוד הטמעה קבוע.</span>
                                </div>
                            </div>

                            <div class="flow-stage flow-stage-product signup-stage {{ $signupStep === '1' ? 'is-active' : '' }}" data-flow-stage="1" data-flow-caption="מתחילים מהדומיין כדי להכין סביבת עבודה וקוד הטמעה קבוע.">
                                <div class="flow-stage-head">
                                    <strong>מתחילים מהאתר עצמו</strong>
                                    <p>הדומיין פותח את סביבת העבודה ומייצר את ה־snippet הראשוני לאתר.</p>
                                </div>
                                <label for="domain">הדומיין של האתר</label>
                                <input id="domain" name="domain" type="text" value="{{ old('domain') }}" placeholder="https://your-site.com" required>

                                <button class="primary-button auth-submit" type="button" data-flow-next>המשך</button>
                                <p class="signup-note">בלי כרטיס אשראי. קודם מחברים אתר, אחר כך ממשיכים.</p>
                            </div>

                            <div class="flow-stage flow-stage-product signup-stage {{ $signupStep === '2' ? 'is-active' : '' }}" data-flow-stage="2" data-flow-caption="נותנים שם ברור לאתר ולעסק, כדי שהמערכת תהיה מסודרת מהרגע הראשון.">
                                <div class="flow-stage-head">
                                    <strong>נותנים שם ברור לסביבת העבודה</strong>
                                    <p>שם האתר והעסק יעזרו לדשבורד, ל־statement ולניהול העתידי להישאר מסודרים.</p>
                                </div>
                                <label for="site_name">שם האתר</label>
                                <input id="site_name" name="site_name" type="text" value="{{ old('site_name') }}" required>

                                <label for="company_name">שם החברה</label>
                                <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" required>

                                <div class="signup-actions-row">
                                    <button class="ghost-button" type="button" data-flow-prev>חזרה</button>
                                    <button class="primary-button" type="button" data-flow-next>המשך</button>
                                </div>
                            </div>

                            <div class="flow-stage flow-stage-product signup-stage {{ $signupStep === '3' ? 'is-active' : '' }}" data-flow-stage="3" data-flow-caption="פרטי הגישה והאישורים האחרונים, ואז החשבון מוכן.">
                                <div class="flow-stage-head">
                                    <strong>יוצרים את החשבון וממשיכים ל־dashboard</strong>
                                    <p>עוד פרטי גישה בסיסיים, והמערכת מוכנה ל־install ולהגדרות הראשונות.</p>
                                </div>

                                <label for="signup_email">אימייל</label>
                                <input id="signup_email" name="email" type="email" value="{{ old('email') }}" required>

                                <label for="signup_password">סיסמה</label>
                                <div class="password-field">
                                    <input id="signup_password" name="password" type="password" minlength="8" required>
                                    <button class="password-toggle" type="button" data-password-toggle="signup_password" aria-label="הצג או הסתר סיסמה">👁</button>
                                </div>

                                <label for="signup_password_confirmation">אימות סיסמה</label>
                                <div class="password-field">
                                    <input id="signup_password_confirmation" name="password_confirmation" type="password" minlength="8" required>
                                    <button class="password-toggle" type="button" data-password-toggle="signup_password_confirmation" aria-label="הצג או הסתר סיסמה">👁</button>
                                </div>

                                <label class="toggle-row consent-row">
                                    <input type="checkbox" name="accepted_terms" value="1" @checked(old('accepted_terms')) required>
                                    <span>קראתי ואני מאשר/ת את <a href="{{ route('legal.terms') }}" target="_blank" rel="noopener">תנאי השימוש</a>.</span>
                                </label>

                                <label class="toggle-row consent-row">
                                    <input type="checkbox" name="accepted_privacy" value="1" @checked(old('accepted_privacy')) required>
                                    <span>קראתי ואני מאשר/ת את <a href="{{ route('legal.privacy') }}" target="_blank" rel="noopener">מדיניות הפרטיות</a>.</span>
                                </label>

                                <label class="toggle-row consent-row">
                                    <input type="checkbox" name="acknowledged_self_service" value="1" @checked(old('acknowledged_self_service')) required>
                                    <span>אני מבין/ה שזהו כלי self-service ותמיכה טכנית במערכת בלבד, ולא שירות נגישות, ייעוץ או התחייבות לציות מלא.</span>
                                </label>

                                <p class="signup-note signup-note-legal">
                                    המוצר נשאר self-service. התמיכה בפלטפורמה היא תמיכה טכנית בלבד.
                                </p>

                                <div class="signup-actions-row">
                                    <button class="ghost-button" type="button" data-flow-prev>חזרה</button>
                                    <button class="primary-button auth-submit" type="submit">ליצור חשבון</button>
                                </div>
                            </div>
                        </div>

                        <aside class="flow-shell-aside flow-shell-aside-product">
                            <div class="flow-summary-card flow-summary-card-compact flow-summary-card-sticky flow-summary-card-product">
                                <strong>מה נפתח עכשיו</strong>
                                <div class="flow-summary-grid">
                                    <div><span>דומיין</span><strong data-flow-summary-target="domain">עדיין לא הוזן</strong></div>
                                    <div><span>אתר</span><strong data-flow-summary-target="site_name">עדיין לא הוזן</strong></div>
                                    <div><span>חברה</span><strong data-flow-summary-target="company_name">עדיין לא הוזן</strong></div>
                                    <div><span>חשבון</span><strong data-flow-summary-target="email">יתווסף בשלב הזה</strong></div>
                                </div>
                            </div>

                            <div class="flow-support-card flow-support-card-product">
                                <p class="eyebrow">מה קורה מיד אחרי</p>
                                <strong>נכנסים ישר ל־dashboard עם אתר ראשון מוכן לעבודה</strong>
                                <ul class="compact-check-list">
                                    <li>קוד הטמעה קבוע לאתר הראשון</li>
                                    <li>דשבורד לניהול וידג׳ט, התקנה והצהרה</li>
                                    <li>גישה ישירה למסכי הטמעה, חשבון ותמיכה טכנית</li>
                                </ul>
                            </div>
                        </aside>
                    </div>
                </form>

                <div class="auth-links-row">
                    <a href="{{ route('login.show') }}">כבר יש לך חשבון? התחבר</a>
                    <a href="{{ route('home') }}">חזרה לאתר</a>
                </div>
            @endif
        </div>

        <aside class="auth-screen-showcase">
            <div class="auth-showcase-media">
                <div class="auth-showcase-glow"></div>
                <div class="auth-showcase-window">
                    <div class="auth-showcase-header">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>

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
                    <h2>פותחים חשבון, מטמיעים snippet אחד, ומתחילים לנהל.</h2>
                    <p>
                        A11Y Bridge בנויה כמו סביבת עבודה מוצרית: חשבון ברור, אתר ראשון, קוד הטמעה
                        קבוע ושכבת ניהול טכנית שנשארת מסודרת מהרגע הראשון.
                    </p>
                </div>

                <div class="auth-showcase-testimonial">
                    <strong>מוצר חינמי שמוביל מהר ל־first site, בלי בלבול מול Brndini.</strong>
                    <span>A11Y Bridge</span>
                </div>
            </div>
        </aside>
    </section>

@endsection
