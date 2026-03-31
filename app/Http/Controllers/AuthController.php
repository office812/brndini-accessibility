<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\SiteSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

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
            'password' => ['required', 'string', 'min:8', 'max:120'],
            'site_name' => ['required', 'string', 'max:160'],
            'domain' => ['required', 'string', 'max:190'],
        ]);

        $domain = SiteSettings::normalizeUrl($validated['domain']);

        if (! filter_var($domain, FILTER_VALIDATE_URL)) {
            return back()
                ->withErrors(['domain' => 'צריך להזין דומיין תקין.'])
                ->withInput($request->except('password'));
        }

        $user = DB::transaction(function () use ($validated, $domain) {
            $isFirstUser = User::query()->count() === 0;

            $user = User::create([
                'name' => $validated['company_name'],
                'email' => strtolower($validated['email']),
                'contact_email' => strtolower($validated['email']),
                'is_admin' => $isFirstUser,
                'password' => Hash::make($validated['password']),
            ]);

            $site = $user->sites()->create([
                'site_name' => $validated['site_name'],
                'domain' => $domain,
                'public_key' => SiteSettings::generatePublicKey(),
                'license_status' => 'active',
                'billing_settings' => SiteSettings::defaultBilling(true),
                'audit_snapshot' => SiteSettings::defaultAuditSnapshot(),
                'alert_settings' => SiteSettings::defaultAlertSettings(),
                'license_expires_at' => Carbon::now()->addYear(),
                'service_mode' => 'audit_and_fix',
                'widget_settings' => SiteSettings::defaultWidget(),
            ]);

            $user->setRelation('sites', collect([$site]));

            return $user;
        });

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
