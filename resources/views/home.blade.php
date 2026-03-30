@extends('layouts.app')

@php($title = 'A11Y Bridge')

@section('content')
    <section class="hero-section">
        <div class="hero-copy">
            <p class="eyebrow">Cloudways-ready Laravel platform</p>
            <h1>כל לקוח פותח חשבון, מגדיר widget, ומקבל קוד הטמעה קבוע.</h1>
            <p class="hero-text">
                ההטמעה באתר נשארת זהה. השינויים בצבע, בגודל, במיקום, בשפה ובקישור להצהרת נגישות
                נשמרים בפלטפורמה שלך ומתעדכנים אוטומטית בכל אתר שמטמיע את אותו <code>site key</code>.
            </p>
            <div class="hero-points">
                <div class="info-card">
                    <h2>חשבון לקוח עצמאי</h2>
                    <p>הרשמה, התחברות ודשבורד ניהול לכל לקוח.</p>
                </div>
                <div class="info-card">
                    <h2>הטמעה יציבה</h2>
                    <p>שורת סקריפט אחת בלבד שמושכת את ההגדרות העדכניות מהשרת.</p>
                </div>
                <div class="info-card">
                    <h2>שפה נכונה עסקית</h2>
                    <p>המערכת נותנת שכבת העדפות וניהול, לא הבטחת “תוסף שמתקן הכול”.</p>
                </div>
            </div>
        </div>

        <div class="auth-grid">
            <section class="panel-card" id="signup-form" aria-labelledby="signup-title">
                <p class="eyebrow">הרשמה</p>
                <h2 id="signup-title">פתיחת חשבון חדש</h2>

                <form class="stack-form" method="POST" action="{{ route('register') }}">
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

                    <button class="primary-button" type="submit">ליצור חשבון</button>
                </form>
            </section>

            <section class="panel-card" id="login-form" aria-labelledby="login-title">
                <p class="eyebrow">התחברות</p>
                <h2 id="login-title">כניסה לדשבורד קיים</h2>

                <form class="stack-form" method="POST" action="{{ route('login') }}">
                    @csrf

                    <label for="login_email">אימייל</label>
                    <input id="login_email" name="email" type="email" value="{{ old('email') }}" required>

                    <label for="login_password">סיסמה</label>
                    <input id="login_password" name="password" type="password" required>

                    <button class="secondary-button" type="submit">להיכנס</button>
                </form>
            </section>
        </div>
    </section>
@endsection
