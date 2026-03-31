<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Site;
use App\Support\SiteSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use Throwable;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.session', [
            'mode' => 'login',
            'title' => 'התחברות לחשבון | A11Y Bridge',
        ]);
    }

    public function showRegister(): View
    {
        return view('auth.session', [
            'mode' => 'register',
            'title' => 'פתיחת חשבון | A11Y Bridge',
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:120', 'confirmed'],
            'site_name' => ['required', 'string', 'max:160'],
            'domain' => ['required', 'string', 'max:190'],
        ]);

        $domain = SiteSettings::normalizeUrl($validated['domain']);

        if (! filter_var($domain, FILTER_VALIDATE_URL)) {
            return back()
                ->withErrors(['domain' => 'צריך להזין דומיין תקין.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        try {
            $user = DB::transaction(function () use ($validated, $domain) {
                $isFirstUser = User::query()->count() === 0;
                $isSuperAdmin = strtolower($validated['email']) === strtolower((string) config('services.a11y_bridge.super_admin_email', 'office@brndini.co.il'));

                $userPayload = [
                    'name' => $validated['company_name'],
                    'email' => strtolower($validated['email']),
                    'password' => Hash::make($validated['password']),
                ];

                if (Schema::hasColumn('users', 'contact_email')) {
                    $userPayload['contact_email'] = strtolower($validated['email']);
                }

                if (Schema::hasColumn('users', 'is_admin')) {
                    $userPayload['is_admin'] = $isFirstUser || $isSuperAdmin;
                }

                $user = User::create($userPayload);

                $sitePayload = [
                    'site_name' => $validated['site_name'],
                    'domain' => $domain,
                    'public_key' => SiteSettings::generatePublicKey(),
                    'service_mode' => 'audit_and_fix',
                    'widget_settings' => SiteSettings::defaultWidget(),
                    'license_status' => 'active',
                    'billing_settings' => SiteSettings::defaultBilling(true),
                    'audit_snapshot' => SiteSettings::defaultAuditSnapshot(),
                    'alert_settings' => SiteSettings::defaultAlertSettings(),
                    'license_expires_at' => Carbon::now()->addYear(),
                ];

                $site = Site::createForUser($user, $sitePayload);

                $user->setRelation('sites', collect([$site]));

                return $user;
            });
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withErrors([
                    'register' => 'לא הצלחנו ליצור את החשבון כרגע. בדוק שהשדות תקינים ונסה שוב בעוד רגע.',
                ])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('dashboard', ['site' => optional($user->sites->first())->id])
            ->with('status', 'החשבון נוצר. עכשיו אפשר להגדיר את ה-widget ולקבל קוד הטמעה.');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt(['email' => strtolower($credentials['email']), 'password' => $credentials['password']])) {
            return back()
                ->withErrors(['login' => 'האימייל או הסיסמה לא נכונים.'])
                ->withInput($request->except('password'));
        }

        $request->session()->regenerate();

        return redirect()
            ->route('dashboard')
            ->with('status', 'התחברת בהצלחה.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('status', 'התנתקת מהמערכת.');
    }
}
