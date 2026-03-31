@extends('layouts.auth')

@php($isLogin = $mode === 'login')

@section('content')
    <section class="auth-screen">
        <div class="auth-screen-panel">
            <a class="auth-brand" href="{{ route('home') }}">
                <span class="brand-mark" aria-hidden="true">AB</span>
                <span>
                    <strong>A11Y Bridge</strong>
                    <small>Hosted accessibility management platform</small>
                </span>
            </a>

            <div class="auth-screen-copy">
                <p class="eyebrow">{{ $isLogin ? 'Login' : 'Start free trial' }}</p>
                <h1>{{ $isLogin ? 'התחברות לחשבון שלך' : 'פתיחת חשבון חדש' }}</h1>
                <p class="hero-text">
                    {{ $isLogin
                        ? 'כניסה מהירה ל־dashboard, למסך ההתקנה, למסגור ה־compliance ולאזור החשבון.'
                        : 'פתיחת workspace חדש עם hosted widget, install center, statement management וקוד הטמעה קבוע.' }}
                </p>
            </div>

            @if ($isLogin)
                <form class="stack-form auth-form" method="POST" action="{{ route('login') }}">
                    @csrf

                    <label for="login_email">אימייל</label>
                    <input id="login_email" name="email" type="email" value="{{ old('email') }}" required>

                    <label for="login_password">סיסמה</label>
                    <input id="login_password" name="password" type="password" required>

                    <button class="primary-button auth-submit" type="submit">להתחבר</button>
                </form>

                <div class="auth-links-row">
                    <a href="{{ route('register.show') }}">אין לך חשבון? פתח חשבון</a>
                    <a href="{{ route('home') }}">חזרה לאתר</a>
                </div>
            @else
                <form class="stack-form auth-form" method="POST" action="{{ route('register') }}">
                    @csrf

                    <label for="company_name">שם החברה</label>
                    <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" required>

                    <label for="signup_email">אימייל</label>
                    <input id="signup_email" name="email" type="email" value="{{ old('email') }}" required>

                    <label for="signup_password">סיסמה</label>
                    <input id="signup_password" name="password" type="password" minlength="8" required>

                    <label for="site_name">שם האתר</label>
                    <input id="site_name" name="site_name" type="text" value="{{ old('site_name') }}" required>

                    <label for="domain">דומיין</label>
                    <input id="domain" name="domain" type="text" value="{{ old('domain') }}" placeholder="https://your-site.com" required>

                    <button class="primary-button auth-submit" type="submit">ליצור חשבון</button>
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
                            <span class="auth-showcase-label">Hosted widget</span>
                            <strong>Install once</strong>
                            <p>snippet קבוע עם configuration שנמשך מהפלטפורמה.</p>
                        </div>
                        <div class="auth-showcase-stat">
                            <span class="auth-showcase-label">Compliance</span>
                            <strong>Statement + governance</strong>
                            <p>statement URL, service mode ומסגור ברור לשירות.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="auth-showcase-footer">
                <div class="auth-showcase-quote">
                    <h2>Build a stronger accessibility experience.</h2>
                    <p>
                        A11Y Bridge נבנתה כדי לתת ללקוחות, לסוכנויות ולמיישמים חוויה שנראית
                        כמו מוצר accessibility רציני כבר מהרגע הראשון.
                    </p>
                </div>

                <div class="auth-showcase-testimonial">
                    <strong>“הדבר החזק פה הוא לא רק ה־widget, אלא כל שכבת הניהול שמסביב.”</strong>
                    <span>Agency partner</span>
                </div>
            </div>
        </aside>
    </section>
@endsection
