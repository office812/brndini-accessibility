@extends('layouts.auth')

@php($isLogin = $mode === 'login')
@php($signupStep = old('signup_step', '1'))

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
                <h1>{{ $isLogin ? 'התחברות לחשבון שלך' : 'פתיחת חשבון חדש' }}</h1>
                <p class="hero-text">
                    {{ $isLogin
                        ? 'כניסה מהירה ללוח הניהול, למסך ההתקנה, למרכז הציות ולאזור החשבון.'
                        : 'פתיחת סביבת עבודה חדשה עם וידג׳ט נגישות, מרכז התקנה, יוצר הצהרה בסיסית וקוד הטמעה קבוע.' }}
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
                <form class="stack-form auth-form auth-form-onboarding" method="POST" action="{{ route('register') }}" data-signup-wizard>
                    @csrf
                    <input type="hidden" name="signup_step" value="{{ $signupStep }}" data-signup-step-input>

                    <div class="signup-stepper" aria-label="התקדמות בהרשמה">
                        <span class="signup-step {{ $signupStep === '1' ? 'is-active' : '' }}"></span>
                        <span class="signup-step {{ $signupStep === '2' ? 'is-active' : '' }}"></span>
                        <span class="signup-step {{ $signupStep === '3' ? 'is-active' : '' }}"></span>
                    </div>

                    <div class="signup-stage {{ $signupStep === '1' ? 'is-active' : '' }}" data-signup-stage="1">
                        <label for="domain">הדומיין של האתר</label>
                        <input id="domain" name="domain" type="text" value="{{ old('domain') }}" placeholder="https://your-site.com" required>

                        <button class="primary-button auth-submit" type="button" data-signup-next>המשך</button>
                        <p class="signup-note">ניסיון חינם, בלי כרטיס אשראי. בהמשך נוסיף את שאר הפרטים.</p>
                    </div>

                    <div class="signup-stage {{ $signupStep === '2' ? 'is-active' : '' }}" data-signup-stage="2">
                        <label for="site_name">שם האתר</label>
                        <input id="site_name" name="site_name" type="text" value="{{ old('site_name') }}" required>

                        <label for="company_name">שם החברה</label>
                        <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" required>

                        <div class="signup-actions-row">
                            <button class="ghost-button" type="button" data-signup-prev>חזרה</button>
                            <button class="primary-button" type="button" data-signup-next>המשך</button>
                        </div>
                    </div>

                    <div class="signup-stage {{ $signupStep === '3' ? 'is-active' : '' }}" data-signup-stage="3">
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

                        <div class="signup-actions-row">
                            <button class="ghost-button" type="button" data-signup-prev>חזרה</button>
                            <button class="primary-button auth-submit" type="submit">ליצור חשבון</button>
                        </div>
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
                    <h2>בנה חוויית נגישות ברורה, יציבה ומנוהלת.</h2>
                    <p>
                        A11Y Bridge נבנתה כדי לתת לבעלי אתרים, לסוכנויות ולמיישמים חוויה שנראית
                        כמו מוצר נגישות רציני כבר מהרגע הראשון.
                    </p>
                </div>

                <div class="auth-showcase-testimonial">
                    <strong>“הדבר החזק פה הוא לא רק הווידג׳ט, אלא כל שכבת הניהול שמסביב.”</strong>
                    <span>שותף סוכנות</span>
                </div>
            </div>
        </aside>
    </section>

    @unless($isLogin)
        <script>
            (function () {
                const form = document.querySelector('[data-signup-wizard]');

                if (!form) {
                    return;
                }

                const stages = Array.from(form.querySelectorAll('[data-signup-stage]'));
                const stepInput = form.querySelector('[data-signup-step-input]');
                const steps = Array.from(form.querySelectorAll('.signup-step'));
                let current = Number(stepInput?.value || 1);

                function validateStage(stageNumber) {
                    const stage = form.querySelector('[data-signup-stage="' + stageNumber + '"]');

                    if (!stage) {
                        return true;
                    }

                    const fields = Array.from(stage.querySelectorAll('input[required], select[required], textarea[required]'));
                    let isValid = true;

                    fields.forEach((field) => {
                        if (!field.reportValidity()) {
                            isValid = false;
                        }
                    });

                    if (!isValid) {
                        return false;
                    }

                    if (stageNumber === 3) {
                        const password = form.querySelector('#signup_password');
                        const confirmation = form.querySelector('#signup_password_confirmation');

                        if (password && confirmation && password.value !== confirmation.value) {
                            confirmation.setCustomValidity('הסיסמאות חייבות להיות זהות.');
                            confirmation.reportValidity();
                            return false;
                        }

                        if (confirmation) {
                            confirmation.setCustomValidity('');
                        }
                    }

                    return true;
                }

                function render() {
                    stages.forEach((stage, index) => {
                        stage.classList.toggle('is-active', index + 1 === current);
                    });

                    steps.forEach((step, index) => {
                        step.classList.toggle('is-active', index + 1 === current);
                    });

                    if (stepInput) {
                        stepInput.value = String(current);
                    }
                }

                form.querySelectorAll('[data-signup-next]').forEach((button) => {
                    button.addEventListener('click', function () {
                        if (!validateStage(current)) {
                            return;
                        }

                        current = Math.min(current + 1, stages.length);
                        render();
                    });
                });

                form.querySelectorAll('[data-signup-prev]').forEach((button) => {
                    button.addEventListener('click', function () {
                        current = Math.max(current - 1, 1);
                        render();
                    });
                });

                render();
            })();
        </script>
    @endunless
@endsection
