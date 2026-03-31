<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Article;
use App\Models\Site;
use App\Models\SupportTicket;
use App\Models\User;
use App\Support\SiteSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function home(Request $request): View|RedirectResponse
    {
        return view('home', [
            'articles' => Article::published()->latest('published_at')->take(6)->get(),
            'metaDescription' => 'פלטפורמת נגישות לאתרים עם widget hosted, קוד הטמעה קבוע, dashboard ניהול והצהרת נגישות במקום אחד.',
            'canonicalUrl' => route('home'),
        ]);
    }

    public function show(Request $request): View
    {
        return view('dashboard', $this->buildDashboardData($request->user()));
    }

    public function install(Request $request): View
    {
        return view('install', $this->buildDashboardData($request->user()));
    }

    public function compliance(Request $request): View
    {
        return view('compliance', $this->buildDashboardData($request->user()));
    }

    public function account(Request $request): View
    {
        return view('account', $this->buildDashboardData($request->user()));
    }

    public function support(Request $request): View
    {
        return view('support', $this->buildDashboardData($request->user()));
    }

    public function superAdmin(Request $request): View
    {
        $this->ensureSuperAdmin($request->user());

        return view('admin', $this->buildSuperAdminData($request->user()));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_id' => ['required', 'integer'],
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
            'widget.buttonMode' => ['required', Rule::in(SiteSettings::BUTTON_MODES)],
            'widget.buttonStyle' => ['required', Rule::in(SiteSettings::BUTTON_STYLES)],
            'widget.icon' => ['required', Rule::in(SiteSettings::ICONS)],
            'widget.preset' => ['required', Rule::in(SiteSettings::PRESETS)],
            'widget.panelLayout' => ['required', Rule::in(SiteSettings::PANEL_LAYOUTS)],
            'widget.showContrast' => ['nullable', 'boolean'],
            'widget.showFontScale' => ['nullable', 'boolean'],
            'widget.showUnderlineLinks' => ['nullable', 'boolean'],
            'widget.showReduceMotion' => ['nullable', 'boolean'],
        ]);

        $domain = SiteSettings::normalizeUrl($validated['domain']);
        $statementUrl = SiteSettings::normalizeOptionalUrl($validated['statement_url'] ?? null);

        if (! filter_var($domain, FILTER_VALIDATE_URL)) {
            return back()->withErrors(['domain' => 'צריך להזין דומיין תקין.'])->withInput();
        }

        if ($statementUrl !== null && ! filter_var($statementUrl, FILTER_VALIDATE_URL)) {
            return back()->withErrors(['statement_url' => 'צריך להזין קישור תקין להצהרת נגישות.'])->withInput();
        }

        $user = $request->user();
        $site = $this->resolveSite($request, $user, (int) $validated['site_id']);

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
                'buttonMode' => $validated['widget']['buttonMode'],
                'buttonStyle' => $validated['widget']['buttonStyle'],
                'icon' => $validated['widget']['icon'],
                'preset' => $validated['widget']['preset'],
                'panelLayout' => $validated['widget']['panelLayout'],
                'showContrast' => $request->boolean('widget.showContrast'),
                'showFontScale' => $request->boolean('widget.showFontScale'),
                'showUnderlineLinks' => $request->boolean('widget.showUnderlineLinks'),
                'showReduceMotion' => $request->boolean('widget.showReduceMotion'),
            ]),
        ]);

        return redirect()
            ->route('dashboard', ['site' => $site->id])
            ->with('status', 'הגדרות הווידג׳ט נשמרו. הפריסט, הלייאאוט והעיצוב יתעדכנו אוטומטית בכל אתר שמטמיע את אותו site key.');
    }

    public function storeSite(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:160'],
            'domain' => ['required', 'string', 'max:190'],
        ]);

        $domain = SiteSettings::normalizeUrl($validated['domain']);

        if (! filter_var($domain, FILTER_VALIDATE_URL)) {
            return back()->withErrors(['domain' => 'צריך להזין דומיין תקין.'])->withInput();
        }

        if (! $this->supportsMultipleSites() && $request->user()->sites()->exists()) {
            return back()->withErrors([
                'site_name' => 'כדי להוסיף אתר נוסף לחשבון צריך קודם להשלים את עדכון המסד בשרת. כרגע הסביבה תומכת באתר אחד בלבד.',
            ])->withInput();
        }

        $sitePayload = [
            'site_name' => $validated['site_name'],
            'domain' => $domain,
            'public_key' => SiteSettings::generatePublicKey(),
            'license_status' => 'inactive',
            'purchase_url' => route('home') . '#pricing',
            'billing_settings' => SiteSettings::defaultBilling(false),
            'audit_snapshot' => SiteSettings::defaultAuditSnapshot(),
            'alert_settings' => SiteSettings::defaultAlertSettings(),
            'service_mode' => 'audit_and_fix',
            'widget_settings' => SiteSettings::defaultWidget(),
        ];

        $site = $request->user()->sites()->create($this->filterSitePayload($sitePayload));
        $this->storeRuntimeOverrides($site, $sitePayload);

        return redirect()
            ->route('dashboard', ['site' => $site->id])
            ->with('status', 'נוצר אתר חדש עם רישיון נפרד. כרגע הוא במצב לא פעיל עד שתבחר מסלול ותפעיל את הרישיון עבור האתר הזה.');
    }

    public function updateBilling(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_id' => ['required', 'integer'],
            'billing_plan' => ['required', Rule::in(SiteSettings::BILLING_PLANS)],
            'billing_cycle' => ['required', Rule::in(SiteSettings::BILLING_CYCLES)],
        ]);

        $site = $this->resolveSite($request, $request->user(), (int) $validated['site_id']);
        $billing = $site->billingConfig();

        $billing['plan'] = $validated['billing_plan'];
        $billing['cycle'] = $validated['billing_cycle'];
        $billing['amount'] = $this->planCatalog()[$validated['billing_plan']]['prices'][$validated['billing_cycle']] ?? $billing['amount'];
        $billing['status'] = ($site->license_status ?? 'active') === 'active' ? 'active' : 'inactive';

        $payload = [
            'billing_settings' => SiteSettings::sanitizeBilling($billing, ($site->license_status ?? 'active') === 'active'),
        ];

        if ($this->siteColumnsAvailable(['billing_settings'])) {
            $site->update($payload);
        } else {
            $this->storeRuntimeOverrides($site, $payload);
        }

        return redirect()
            ->route('dashboard.account', ['site' => $site->id])
            ->with('status', 'פרטי החבילה עודכנו. אם זה אתר חדש, צריך גם להפעיל את הרישיון כדי שהווידג׳ט יעבוד באתר.');
    }

    public function activateLicense(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_id' => ['required', 'integer'],
        ]);

        $site = $this->resolveSite($request, $request->user(), (int) $validated['site_id']);
        $billing = $site->billingConfig();

        $billing['status'] = 'active';

        $licensePayload = [
            'license_status' => 'active',
            'purchase_url' => null,
            'billing_settings' => SiteSettings::sanitizeBilling($billing, true),
            'license_expires_at' => $this->calculateExpiry($billing['cycle']),
        ];

        $persistedLicensePayload = $this->filterSitePayload($licensePayload);

        if ($persistedLicensePayload !== []) {
            $site->update($persistedLicensePayload);
        }

        $this->storeRuntimeOverrides($site, $licensePayload);

        $site = $this->applySiteRuntimeOverrides($site->fresh());

        $snapshot = $this->generateAuditSnapshot($site);

        if ($this->siteColumnsAvailable(['audit_snapshot', 'last_audited_at'])) {
            $site->update([
                'audit_snapshot' => $snapshot,
                'last_audited_at' => Carbon::now(),
            ]);
        } else {
            Cache::put($this->auditSnapshotCacheKey($site), $snapshot, now()->addDays(30));
            Cache::put($this->auditTimestampCacheKey($site), Carbon::now()->toIso8601String(), now()->addDays(30));
        }

        return redirect()
            ->route('dashboard.account', ['site' => $site->id])
            ->with('status', 'הרישיון הופעל. הקוד של האתר הזה פעיל עכשיו, והווידג׳ט יחזור להיטען כרגיל.');
    }

    public function runAudit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_id' => ['required', 'integer'],
        ]);

        $site = $this->resolveSite($request, $request->user(), (int) $validated['site_id']);

        if (! $this->siteColumnsAvailable(['audit_snapshot', 'last_audited_at'])) {
            $snapshot = $this->generateAuditSnapshot($site);
            Cache::put($this->auditSnapshotCacheKey($site), $snapshot, now()->addDays(30));
            Cache::put($this->auditTimestampCacheKey($site), Carbon::now()->toIso8601String(), now()->addDays(30));

            return redirect()
                ->route('dashboard.compliance', ['site' => $site->id])
                ->with('status', 'הבדיקה הורצה ונשמרה במצב זמני עד שעדכון מסד הנתונים יושלם בשרת.');
        }

        $snapshot = $this->generateAuditSnapshot($site);

        $site->update([
            'audit_snapshot' => $snapshot,
            'last_audited_at' => Carbon::now(),
        ]);

        return redirect()
            ->route('dashboard.compliance', ['site' => $site->id])
            ->with('status', 'הבדיקה עודכנה. עכשיו אפשר לראות ציון, התראות ופעולות פתוחות עבור האתר הזה.');
    }

    public function updateAlerts(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_id' => ['required', 'integer'],
            'alerts.license' => ['nullable', 'boolean'],
            'alerts.statement' => ['nullable', 'boolean'],
            'alerts.audit' => ['nullable', 'boolean'],
            'alerts.sync' => ['nullable', 'boolean'],
        ]);

        $site = $this->resolveSite($request, $request->user(), (int) $validated['site_id']);
        $incomingAlerts = SiteSettings::sanitizeAlertSettings([
            'license' => $request->boolean('alerts.license'),
            'statement' => $request->boolean('alerts.statement'),
            'audit' => $request->boolean('alerts.audit'),
            'sync' => $request->boolean('alerts.sync'),
        ]);

        if (! $this->siteColumnsAvailable(['alert_settings', 'audit_snapshot'])) {
            Cache::put($this->alertSettingsCacheKey($site), $incomingAlerts, now()->addDays(30));
            $previewSite = clone $site;
            $previewSite->alert_settings = $incomingAlerts;
            $snapshot = $this->generateAuditSnapshot($previewSite);
            Cache::put($this->auditSnapshotCacheKey($site), $snapshot, now()->addDays(30));

            return redirect()
                ->route('dashboard.compliance', ['site' => $site->id])
                ->with('status', 'ההתראות נשמרו במצב זמני עד שעדכון מסד הנתונים יושלם בשרת.');
        }

        $site->update([
            'alert_settings' => $incomingAlerts,
        ]);

        $snapshot = $this->generateAuditSnapshot($site->fresh());

        $site->update([
            'audit_snapshot' => $snapshot,
        ]);

        return redirect()
            ->route('dashboard.compliance', ['site' => $site->id])
            ->with('status', 'התראות האתר עודכנו. המסך ימשיך להבליט רק את מה שבאמת חשוב לעקוב אחריו.');
    }

    public function storeSupportTicket(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_id' => ['required', 'integer'],
            'category' => ['required', Rule::in(array_keys($this->supportCategories()))],
            'priority' => ['required', Rule::in(array_keys($this->supportPriorityLabels()))],
            'subject' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'min:20', 'max:4000'],
        ]);

        $site = $this->resolveSite($request, $request->user(), (int) $validated['site_id']);

        if (! Schema::hasTable('support_tickets')) {
            return redirect()
                ->route('dashboard.support', ['site' => $site->id])
                ->withErrors(['support' => 'אזור התמיכה עוד לא זמין בשרת הזה. צריך להשלים את עדכון מסד הנתונים, ואז פתיחת פנייה תעבוד רגיל.']);
        }

        $ticket = SupportTicket::create([
            'user_id' => $request->user()->id,
            'site_id' => $site->id,
            'reference_code' => 'TMP-' . uniqid(),
            'subject' => trim($validated['subject']),
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'status' => 'open',
            'message' => trim($validated['message']),
            'last_activity_at' => Carbon::now(),
        ]);

        $ticket->update([
            'reference_code' => 'SUP-' . str_pad((string) $ticket->id, 5, '0', STR_PAD_LEFT),
        ]);

        return redirect()
            ->route('dashboard.support', ['site' => $site->id])
            ->with('status', 'הפנייה נפתחה בהצלחה. צוות התמיכה יוכל לחזור אליך מתוך סביבת הניהול של האתר הזה.');
    }

    public function updateSupportTicketAdmin(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $admin = $request->user();
        $this->ensureSuperAdmin($admin);

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys($this->supportStatusLabels()))],
            'priority' => ['required', Rule::in(array_keys($this->supportPriorityLabels()))],
            'admin_response' => ['nullable', 'string', 'max:4000'],
        ]);

        $ticket->update([
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'last_activity_at' => Carbon::now(),
        ]);

        if (Schema::hasColumn('support_tickets', 'assigned_user_id')) {
            $ticket->update([
                'assigned_user_id' => $admin->id,
            ]);
        }

        $response = trim((string) ($validated['admin_response'] ?? ''));

        if (Schema::hasColumn('support_tickets', 'admin_response')) {
            $ticket->update([
                'admin_response' => $response === '' ? null : $response,
            ]);
        } else {
            Cache::put('support_ticket:' . $ticket->id . ':admin_response', $response === '' ? null : $response, now()->addDays(30));
        }

        return redirect()
            ->route('dashboard.super-admin')
            ->with('status', 'הפנייה עודכנה ונשמרה ממרכז הסופר־אדמין.');
    }

    public function updateGlobalTracking(Request $request): RedirectResponse
    {
        $this->ensureSuperAdmin($request->user());

        $validated = $request->validate([
            'google_analytics_head' => ['nullable', 'string', 'max:20000'],
            'google_tag_manager_head' => ['nullable', 'string', 'max:20000'],
            'google_tag_manager_body' => ['nullable', 'string', 'max:20000'],
            'meta_pixel_head' => ['nullable', 'string', 'max:20000'],
            'custom_head_scripts' => ['nullable', 'string', 'max:40000'],
            'custom_body_scripts' => ['nullable', 'string', 'max:40000'],
        ]);

        AppSetting::putMany($validated);

        return redirect()
            ->route('dashboard.super-admin')
            ->with('status', 'קודי המעקב וההטמעות הגלובליות עודכנו.');
    }

    private function ensureSite(User $user): Site
    {
        $site = $user->sites()->orderBy('id')->first();

        if ($site) {
            return $site;
        }

        $sitePayload = [
            'site_name' => $user->name . ' Site',
            'domain' => 'https://example.com',
            'public_key' => SiteSettings::generatePublicKey(),
            'license_status' => 'active',
            'billing_settings' => SiteSettings::defaultBilling(true),
            'audit_snapshot' => SiteSettings::defaultAuditSnapshot(),
            'alert_settings' => SiteSettings::defaultAlertSettings(),
            'license_expires_at' => Carbon::now()->addYear(),
            'service_mode' => 'audit_and_fix',
            'widget_settings' => SiteSettings::defaultWidget(),
        ];

        $site = $user->sites()->create($this->filterSitePayload($sitePayload));
        $this->storeRuntimeOverrides($site, $sitePayload);

        return $site;
    }

    private function resolveSite(Request $request, User $user, ?int $siteId = null): Site
    {
        $selectedId = $siteId ?? (int) $request->integer('site');

        if ($selectedId > 0) {
            $site = $user->sites()->whereKey($selectedId)->first();

            if ($site) {
                return $this->applySiteRuntimeOverrides($site);
            }
        }

        return $this->applySiteRuntimeOverrides($this->ensureSite($user));
    }

    private function buildDashboardData(User $user): array
    {
        $request = request();
        $site = $this->applySiteRuntimeOverrides($this->resolveSite($request, $user));
        $sites = $user->sites()
            ->orderBy('id')
            ->get()
            ->map(fn (Site $candidate) => $this->applySiteRuntimeOverrides($candidate));
        $cachedAlerts = Cache::get($this->alertSettingsCacheKey($site));
        $cachedAudit = Cache::get($this->auditSnapshotCacheKey($site));
        $cachedAuditTimestamp = Cache::get($this->auditTimestampCacheKey($site));

        if (! $this->siteColumnsAvailable(['alert_settings']) && is_array($cachedAlerts)) {
            $site->alert_settings = $cachedAlerts;
        }

        if (! $this->siteColumnsAvailable(['audit_snapshot']) && is_array($cachedAudit)) {
            $site->audit_snapshot = $cachedAudit;
        }

        if (! $this->siteColumnsAvailable(['last_audited_at']) && is_string($cachedAuditTimestamp)) {
            $site->last_audited_at = Carbon::parse($cachedAuditTimestamp);
        }

        $installationSignal = $this->installationSignal($site);
        $widget = $site->widgetConfig();
        $billing = $site->billingConfig();
        $alertSettings = $site->alertConfig();
        $auditSnapshot = $site->audit_snapshot ? $site->auditConfig() : $this->generateAuditSnapshot($site);
        $serviceModes = $this->serviceModes();
        $featureCount = count(array_filter([
            $widget['showContrast'],
            $widget['showFontScale'],
            $widget['showUnderlineLinks'],
            $widget['showReduceMotion'],
        ]));
        $planCatalog = $this->planCatalog();
        $activeAlerts = collect($auditSnapshot['alerts'] ?? [])->filter(fn (array $alert) => ($alert['state'] ?? 'open') !== 'resolved')->values();
        $currentPlanMeta = $planCatalog[$billing['plan']] ?? $planCatalog['free'];
        $supportAvailable = Schema::hasTable('support_tickets');
        $supportTickets = $supportAvailable
            ? $user->supportTickets()
                ->with('site')
                ->where(function ($query) use ($site) {
                    $query->where('site_id', $site->id)->orWhereNull('site_id');
                })
                ->latest('last_activity_at')
                ->latest('created_at')
                ->get()
            : collect();
        $openTickets = $supportTickets->whereIn('status', ['open', 'pending']);
        $urgentTickets = $supportTickets->where('priority', 'urgent');
        $activeSitesCount = $sites->filter(function (Site $candidate) {
            return ($candidate->license_status ?? 'active') === 'active';
        })->count();

        return [
            'user' => $user,
            'site' => $site,
            'sites' => $sites,
            'widget' => $widget,
            'widgetPresetLabels' => $this->widgetPresetLabels(),
            'widgetLayoutLabels' => $this->widgetLayoutLabels(),
            'serviceModes' => $serviceModes,
            'serviceModeLabel' => $serviceModes[$site->service_mode] ?? $site->service_mode,
            'embedScriptUrl' => url('/widget.js'),
            'embedCode' => sprintf('<script async src="%s" data-a11y-bridge="%s"></script>', url('/widget.js'), $site->public_key),
            'licenseStatus' => $site->license_status ?? 'active',
            'installationStatus' => $installationSignal['installed'] ? 'installed' : 'pending',
            'installationLabel' => $installationSignal['installed'] ? 'הוטמע באתר' : 'ממתין להטמעה',
            'installationSeenLabel' => $installationSignal['last_seen_at']?->diffForHumans() ?? 'עדיין לא זוהתה טעינה מהאתר',
            'installationPageUrl' => $installationSignal['page_url'],
            'billing' => $billing,
            'billingPlans' => $planCatalog,
            'currentPlan' => [
                'name' => $currentPlanMeta['label'],
                'price' => ($currentPlanMeta['prices'][$billing['cycle']] ?? 0) === 0
                    ? 'ללא עלות'
                    : '$' . $currentPlanMeta['prices'][$billing['cycle']] . ' / ' . ($billing['cycle'] === 'yearly' ? 'שנה' : 'חודש'),
                'description' => $currentPlanMeta['description'],
            ],
            'recommendedPlan' => $this->recommendedPlanForSite($site, $featureCount),
            'statementStatus' => $site->statement_url ? 'connected' : 'missing',
            'featureCount' => $featureCount,
            'auditSnapshot' => $auditSnapshot,
            'auditChecks' => $auditSnapshot['checks'] ?? [],
            'openAlerts' => $activeAlerts,
            'openAlertsCount' => $activeAlerts->count(),
            'alertSettings' => $alertSettings,
            'auditActionsAvailable' => $this->siteColumnsAvailable(['audit_snapshot', 'last_audited_at']),
            'alertSettingsAvailable' => $this->siteColumnsAvailable(['alert_settings', 'audit_snapshot']),
            'licenseExpiresLabel' => $site->license_expires_at ? $site->license_expires_at->timezone(config('app.timezone'))->format('d.m.Y') : 'טרם הופעל',
            'lastAuditedLabel' => $site->last_audited_at ? $site->last_audited_at->diffForHumans() : 'עדיין לא הורצה בדיקה',
            'siteSwitcherOptions' => $sites->map(function (Site $candidate) {
                return [
                    'id' => $candidate->id,
                    'label' => $candidate->site_name . ' · ' . (parse_url($candidate->domain, PHP_URL_HOST) ?: $candidate->domain),
                    'url' => route(request()->route()?->getName() ?: 'dashboard', ['site' => $candidate->id]),
                ];
            })->all(),
            'activeSitesCount' => $activeSitesCount,
            'supportAvailable' => $supportAvailable,
            'supportTickets' => $supportTickets,
            'supportCategories' => $this->supportCategories(),
            'supportPriorityLabels' => $this->supportPriorityLabels(),
            'supportStatusLabels' => $this->supportStatusLabels(),
            'supportSummary' => [
                'open' => $openTickets->count(),
                'urgent' => $urgentTickets->count(),
                'resolved' => $supportTickets->where('status', 'resolved')->count(),
                'lastActivity' => $supportTickets->first()?->last_activity_at?->diffForHumans() ?? 'עדיין לא נפתחה פנייה',
            ],
        ];
    }

    private function buildSuperAdminData(User $user): array
    {
        $tracking = AppSetting::getMany([
            'google_analytics_head',
            'google_tag_manager_head',
            'google_tag_manager_body',
            'meta_pixel_head',
            'custom_head_scripts',
            'custom_body_scripts',
        ]);

        $supportAvailable = Schema::hasTable('support_tickets');

        $usersQuery = User::query()
            ->withCount(['sites'])
            ->orderByDesc('id');

        if ($supportAvailable) {
            $usersQuery->withCount(['supportTickets']);
        }

        $users = $usersQuery->get();

        if (! $supportAvailable) {
            $users->each(function (User $adminUser): void {
                $adminUser->setAttribute('support_tickets_count', 0);
            });
        }

        $sites = Site::query()
            ->with('user')
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get();

        $tickets = $supportAvailable
            ? SupportTicket::query()
                ->with(['user', 'site', 'assignedUser'])
                ->latest('last_activity_at')
                ->latest('created_at')
                ->get()
            : collect();

        return [
            'title' => 'מרכז סופר־אדמין | A11Y Bridge',
            'user' => $user,
            'trackingScripts' => $tracking,
            'adminUsers' => $users,
            'adminSites' => $sites,
            'adminSupportTickets' => $tickets,
            'supportCategories' => $this->supportCategories(),
            'supportPriorityLabels' => $this->supportPriorityLabels(),
            'supportStatusLabels' => $this->supportStatusLabels(),
            'supportAvailable' => $supportAvailable,
            'adminSummary' => [
                'users' => $users->count(),
                'sites' => $sites->count(),
                'active_sites' => $sites->filter(fn (Site $site) => ($site->license_status ?? 'active') === 'active')->count(),
                'tickets_open' => $tickets->whereIn('status', ['open', 'pending'])->count(),
            ],
        ];
    }

    private function auditSnapshotCacheKey(Site $site): string
    {
        return 'site:' . $site->id . ':audit_snapshot_fallback';
    }

    private function auditTimestampCacheKey(Site $site): string
    {
        return 'site:' . $site->id . ':last_audited_fallback';
    }

    private function alertSettingsCacheKey(Site $site): string
    {
        return 'site:' . $site->id . ':alert_settings_fallback';
    }

    private function serviceModes(): array
    {
        return [
            'audit_only' => 'ביקורת בלבד',
            'audit_and_fix' => 'ביקורת + תיקונים בטוחים',
            'managed_service' => 'שירות נגישות מנוהל',
        ];
    }

    private function siteColumnsAvailable(array $columns): bool
    {
        foreach ($columns as $column) {
            if (! Schema::hasColumn('sites', $column)) {
                return false;
            }
        }

        return true;
    }

    private function ensureSuperAdmin(User $user): void
    {
        abort_unless($user->isSuperAdmin(), 403);
    }

    private function supportsMultipleSites(): bool
    {
        if (! Schema::hasTable('migrations')) {
            return false;
        }

        return DB::table('migrations')
            ->where('migration', '2026_03_31_000005_allow_multiple_sites_per_user')
            ->exists();
    }

    private function widgetPresetLabels(): array
    {
        return [
            'classic' => 'קלאסי',
            'high-tech' => 'הייטק',
            'elegant' => 'אלגנטי',
            'bold' => 'נועז',
        ];
    }

    private function widgetLayoutLabels(): array
    {
        return [
            'stacked' => 'מדורג',
            'split' => 'מפוצל',
        ];
    }

    private function supportCategories(): array
    {
        return [
            'general' => 'שאלה כללית',
            'billing' => 'חיוב ורישיון',
            'install' => 'הטמעה באתר',
            'widget' => 'עיצוב והתנהגות הווידג׳ט',
            'compliance' => 'בדיקות, הצהרה וציות',
            'technical' => 'בעיה טכנית',
        ];
    }

    private function supportPriorityLabels(): array
    {
        return [
            'low' => 'נמוכה',
            'normal' => 'רגילה',
            'high' => 'גבוהה',
            'urgent' => 'דחופה',
        ];
    }

    private function supportStatusLabels(): array
    {
        return [
            'open' => 'פתוחה',
            'pending' => 'ממתינה למענה',
            'answered' => 'נענתה',
            'resolved' => 'נסגרה',
        ];
    }

    private function installationSignal(Site $site): array
    {
        if ($this->siteColumnsAvailable(['last_seen_at', 'last_seen_url'])) {
            return [
                'installed' => filled($site->last_seen_at),
                'last_seen_at' => $site->last_seen_at,
                'page_url' => filled($site->last_seen_url) ? $site->last_seen_url : null,
            ];
        }

        $lastSeenAt = Cache::get('site:' . $site->id . ':widget_seen_at');
        $pageUrl = Cache::get('site:' . $site->id . ':widget_seen_url');

        return [
            'installed' => is_string($lastSeenAt) && trim($lastSeenAt) !== '',
            'last_seen_at' => is_string($lastSeenAt) ? Carbon::parse($lastSeenAt) : null,
            'page_url' => is_string($pageUrl) && trim($pageUrl) !== '' ? $pageUrl : null,
        ];
    }

    private function planCatalog(): array
    {
        return [
            'free' => [
                'label' => 'חינם',
                'description' => 'מסלול שמכסה בערך 70% מהיכולות: התאמות טקסט, ניגודיות, ניווט בסיסי, קוד הטמעה קבוע וחוויית נגישות טובה לרוב האתרים.',
                'prices' => ['monthly' => 0, 'yearly' => 0],
            ],
            'premium' => [
                'label' => 'פרימיום',
                'description' => 'עוד 30% מהיכולות הקריטיות: פרופילי שימוש, מדריך קריאה, הסתרת תמונות, התאמות מתקדמות יותר וחוויית widget עשירה יותר.',
                'prices' => ['monthly' => 49, 'yearly' => 249],
            ],
        ];
    }

    private function recommendedPlanForSite(Site $site, int $featureCount): array
    {
        if ($site->sites_count ?? null) {
            return [];
        }

        if ($featureCount >= 4 || $site->service_mode === 'managed_service') {
            return [
                'name' => 'פרימיום',
                'description' => 'מתאים לאתר שצריך את כל שכבת הווידג׳ט המתקדמת, כולל פרופילים, מדריך קריאה וכלי נראות מתקדמים.',
            ];
        }

        return [
            'name' => 'פרימיום',
            'description' => 'אם אתה רוצה לפתוח את כל היכולות המתקדמות של הווידג׳ט ולהציג חוויה מלאה יותר למבקרים, זה השדרוג הבא.',
        ];
    }

    private function generateAuditSnapshot(Site $site): array
    {
        $installationSignal = $this->installationSignal($site);
        $widget = $site->widgetConfig();
        $billing = $site->billingConfig();
        $alertSettings = $site->alertConfig();
        $featureCount = count(array_filter([
            $widget['showContrast'],
            $widget['showFontScale'],
            $widget['showUnderlineLinks'],
            $widget['showReduceMotion'],
        ]));

        $score = 56;
        $checks = [];
        $alerts = [];

        $licenseActive = ($site->license_status ?? 'active') === 'active';
        $widgetInstalled = $installationSignal['installed'];
        $statementConnected = filled($site->statement_url);
        $billingActive = ($billing['status'] ?? 'inactive') === 'active';

        $checks[] = $this->buildCheck(
            'הטמעה באתר',
            $widgetInstalled ? 'pass' : 'fail',
            $widgetInstalled
                ? 'הווידג׳ט זוהה בפועל באתר והפלטפורמה קיבלה טעינה אמיתית מה־site key הזה.'
                : 'עדיין לא זוהתה טעינה אמיתית של הווידג׳ט באתר. צריך להטמיע את הקוד ואז לרענן את האתר.'
        );

        $score += $widgetInstalled ? 12 : -26;

        if (! $widgetInstalled) {
            $alerts[] = $this->buildAlert('install', 'הווידג׳ט עדיין לא הוטמע', 'high', 'לפני שמסתמכים על ציון או על מצב האתר, צריך קודם להדביק את קוד ההטמעה באתר ולוודא שהווידג׳ט נטען בפועל.');
        }

        $checks[] = $this->buildCheck(
            'רישיון והטמעה',
            $licenseActive ? 'pass' : 'fail',
            $licenseActive ? 'הווידג׳ט רשאי להיטען באתר והטמעה חיה פעילה.' : 'הרישיון לא פעיל. כרגע הכפתור באתר צריך להפנות לרכישה.'
        );

        $score += $licenseActive ? 18 : -8;

        if (! $licenseActive && $alertSettings['license']) {
            $alerts[] = $this->buildAlert('license', 'רישיון לא פעיל', 'critical', 'האתר הזה עדיין לא הופעל ולכן הכפתור באתר מוצג באדום ומפנה לרכישה.');
        }

        $checks[] = $this->buildCheck(
            'הצהרת נגישות',
            $statementConnected ? 'pass' : 'warn',
            $statementConnected ? 'יש קישור חי להצהרה מתוך הפלטפורמה ומהווידג׳ט.' : 'אין כרגע הצהרת נגישות מחוברת לאתר הזה.'
        );

        $score += $statementConnected ? 14 : 0;

        if (! $statementConnected && $alertSettings['statement']) {
            $alerts[] = $this->buildAlert('statement', 'חסרה הצהרת נגישות', 'high', 'מומלץ לחבר statement URL כדי לסגור את מסגרת השירות והציות מול הלקוח.');
        }

        $checks[] = $this->buildCheck(
            'עושר הווידג׳ט',
            $featureCount >= 4 ? 'pass' : ($featureCount >= 2 ? 'warn' : 'fail'),
            $featureCount >= 4
                ? 'כל ארבעת הפקדים המרכזיים זמינים למשתמש באתר.'
                : 'כרגע רק ' . $featureCount . ' פקדים פעילים. אפשר להרחיב את הווידג׳ט כדי לתת חוויה שלמה יותר.'
        );

        $score += min(12, $featureCount * 3);

        $checks[] = $this->buildCheck(
            'Billing',
            $billingActive ? 'pass' : 'warn',
            $billingActive
                ? 'החבילה מסומנת כפעילה עם מסלול ' . ($this->planCatalog()[$billing['plan']]['label'] ?? $billing['plan']) . '.'
                : 'האתר עדיין לא עבר הפעלה מלאה במסלול שנבחר.'
        );

        $score += $billingActive ? 10 : 2;

        $hasRecentAudit = $site->last_audited_at && $site->last_audited_at->gt(Carbon::now()->subDays(21));

        $checks[] = $this->buildCheck(
            'בדיקה אחרונה',
            $hasRecentAudit ? 'pass' : 'warn',
            $hasRecentAudit
                ? 'הבדיקה רצה לאחרונה ונותנת תמונת מצב טרייה.'
                : 'כדאי להריץ בדיקה חדשה כדי לעדכן ציון והתראות מול מצב ההגדרות הנוכחי.'
        );

        $score += $hasRecentAudit ? 10 : 3;

        if (! $hasRecentAudit && $alertSettings['audit']) {
            $alerts[] = $this->buildAlert('audit', 'הבדיקה דורשת רענון', 'medium', 'לא רצה בדיקה עדכנית לאחרונה. הפעל בדיקה חדשה כדי לרענן ציון והתראות.');
        }

        if ($site->last_audited_at && $site->updated_at && $site->updated_at->gt($site->last_audited_at) && $alertSettings['sync']) {
            $alerts[] = $this->buildAlert('sync', 'יש שינויים מאז הבדיקה האחרונה', 'low', 'בוצעו עדכוני widget או פרטי אתר אחרי הבדיקה האחרונה. מומלץ לרענן את הדוח.');
        }

        if ($site->service_mode === 'managed_service') {
            $score += 8;
        } elseif ($site->service_mode === 'audit_and_fix') {
            $score += 5;
        }

        $score = max(0, min(100, $score));
        $status = ! $widgetInstalled
            ? 'action_required'
            : ($score >= 85 ? 'healthy' : ($score >= 70 ? 'monitoring' : 'action_required'));

        return SiteSettings::sanitizeAuditSnapshot([
            'score' => $score,
            'status' => $status,
            'summary' => $this->statusSummary($status, count($alerts)),
            'checks' => $checks,
            'alerts' => $alerts,
        ]);
    }

    private function buildCheck(string $label, string $status, string $detail): array
    {
        return [
            'label' => $label,
            'status' => $status,
            'detail' => $detail,
        ];
    }

    private function buildAlert(string $key, string $title, string $severity, string $detail): array
    {
        return [
            'key' => $key,
            'title' => $title,
            'severity' => $severity,
            'detail' => $detail,
            'state' => 'open',
        ];
    }

    private function statusSummary(string $status, int $alertCount): string
    {
        return match ($status) {
            'healthy' => 'האתר הזה נראה במצב טוב. נשאר רק להמשיך לעקוב אחרי שינויים ולעדכן audits לפי הצורך.',
            'monitoring' => 'הבסיס חזק, אבל עדיין יש ' . $alertCount . ' נקודות שכדאי לעקוב אחריהן כדי לשמור על סביבת נגישות יציבה.',
            default => 'יש פעולות פתוחות שדורשות טיפול לפני שאפשר להציג את האתר הזה כסט אפ בריא ומנוהל היטב. ברוב המקרים זה מתחיל מהשלמת ההטמעה באתר עצמו.',
        };
    }

    private function calculateExpiry(string $cycle): Carbon
    {
        return $cycle === 'monthly' ? Carbon::now()->addMonth() : Carbon::now()->addYear();
    }

    private function filterSitePayload(array $payload): array
    {
        if (! Schema::hasTable('sites')) {
            return [];
        }

        return collect($payload)
            ->filter(fn ($_value, $column) => Schema::hasColumn('sites', $column))
            ->all();
    }

    private function runtimeOverridesCacheKey(Site $site): string
    {
        return 'site:' . $site->id . ':runtime_overrides';
    }

    private function storeRuntimeOverrides(Site $site, array $payload): void
    {
        $missingColumnPayload = collect($payload)
            ->filter(fn ($_value, $column) => ! Schema::hasColumn('sites', $column))
            ->all();

        if ($missingColumnPayload === []) {
            return;
        }

        $current = Cache::get($this->runtimeOverridesCacheKey($site), []);

        Cache::put(
            $this->runtimeOverridesCacheKey($site),
            array_merge(is_array($current) ? $current : [], $missingColumnPayload),
            now()->addDays(30)
        );
    }

    private function applySiteRuntimeOverrides(Site $site): Site
    {
        $overrides = Cache::get($this->runtimeOverridesCacheKey($site), []);

        if (! is_array($overrides) || $overrides === []) {
            return $site;
        }

        $applicable = collect($overrides)
            ->filter(fn ($_value, $column) => ! Schema::hasColumn('sites', $column))
            ->all();

        if ($applicable === []) {
            return $site;
        }

        return $site->applyRuntimeOverrides($applicable);
    }
}
