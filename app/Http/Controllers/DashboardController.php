<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\SiteSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function home(Request $request): View|RedirectResponse
    {
        if ($request->user()) {
            return redirect()->route('dashboard');
        }

        return view('home', [
            'serviceModes' => $this->serviceModes(),
        ]);
    }

    public function show(Request $request): View
    {
        $user = $request->user();

        return view('dashboard', $this->buildDashboardData($user));
    }

    public function install(Request $request): View
    {
        return view('install', $this->buildDashboardData($request->user()));
    }

    public function compliance(Request $request): View
    {
        return view('compliance', $this->buildDashboardData($request->user()));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:160'],
            'contact_email' => ['required', 'email', 'max:190'],
            'site_name' => ['required', 'string', 'max:160'],
            'domain' => ['required', 'string', 'max:190'],
            'statement_url' => ['nullable', 'string', 'max:190'],
            'service_mode' => ['required', Rule::in(SiteSettings::SERVICE_MODES)],
            'widget.position' => ['required', Rule::in(SiteSettings::POSITIONS)],
            'widget.color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'widget.size' => ['required', Rule::in(SiteSettings::SIZES)],
            'widget.label' => ['required', 'string', 'max:80'],
            'widget.language' => ['required', Rule::in(SiteSettings::LANGUAGES)],
            'widget.showContrast' => ['nullable', 'boolean'],
            'widget.showFontScale' => ['nullable', 'boolean'],
            'widget.showUnderlineLinks' => ['nullable', 'boolean'],
            'widget.showReduceMotion' => ['nullable', 'boolean'],
        ]);

        $domain = SiteSettings::normalizeUrl($validated['domain']);
        $statementUrl = SiteSettings::normalizeOptionalUrl($validated['statement_url'] ?? null);

        if (! filter_var($domain, FILTER_VALIDATE_URL)) {
            return back()
                ->withErrors(['domain' => 'צריך להזין דומיין תקין.'])
                ->withInput();
        }

        if ($statementUrl !== null && ! filter_var($statementUrl, FILTER_VALIDATE_URL)) {
            return back()
                ->withErrors(['statement_url' => 'צריך להזין קישור תקין להצהרת נגישות.'])
                ->withInput();
        }

        $user = $request->user();
        $site = $this->ensureSite($user);

        $user->update([
            'name' => $validated['company_name'],
            'contact_email' => strtolower($validated['contact_email']),
        ]);

        $site->update([
            'site_name' => $validated['site_name'],
            'domain' => $domain,
            'statement_url' => $statementUrl,
            'service_mode' => $validated['service_mode'],
            'widget_settings' => SiteSettings::sanitizeWidget([
                'position' => $validated['widget']['position'],
                'color' => $validated['widget']['color'],
                'size' => $validated['widget']['size'],
                'label' => $validated['widget']['label'],
                'language' => $validated['widget']['language'],
                'showContrast' => $request->boolean('widget.showContrast'),
                'showFontScale' => $request->boolean('widget.showFontScale'),
                'showUnderlineLinks' => $request->boolean('widget.showUnderlineLinks'),
                'showReduceMotion' => $request->boolean('widget.showReduceMotion'),
            ]),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('status', 'הגדרות ה-widget נשמרו. כל הטמעה עם אותו site key תתעדכן אוטומטית.');
    }

    private function ensureSite(User $user)
    {
        return $user->site()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'site_name' => $user->name . ' Site',
                'domain' => 'https://example.com',
                'public_key' => SiteSettings::generatePublicKey(),
                'service_mode' => 'audit_and_fix',
                'widget_settings' => SiteSettings::defaultWidget(),
            ]
        );
    }

    private function buildDashboardData(User $user): array
    {
        $site = $this->ensureSite($user);
        $widget = $site->widgetConfig();
        $serviceModes = $this->serviceModes();
        $embedScriptUrl = url('/widget.js');
        $featureCount = count(array_filter([
            $widget['showContrast'],
            $widget['showFontScale'],
            $widget['showUnderlineLinks'],
            $widget['showReduceMotion'],
        ]));

        return [
            'user' => $user,
            'site' => $site,
            'widget' => $widget,
            'serviceModes' => $serviceModes,
            'serviceModeLabel' => $serviceModes[$site->service_mode] ?? $site->service_mode,
            'embedScriptUrl' => $embedScriptUrl,
            'embedCode' => sprintf(
                '<script async src="%s" data-a11y-bridge="%s"></script>',
                $embedScriptUrl,
                $site->public_key
            ),
            'statementStatus' => $site->statement_url ? 'connected' : 'missing',
            'featureCount' => $featureCount,
        ];
    }

    private function serviceModes(): array
    {
        return [
            'audit_only' => 'Audit only',
            'audit_and_fix' => 'Audit + safe fixes',
            'managed_service' => 'Managed accessibility service',
        ];
    }
}
