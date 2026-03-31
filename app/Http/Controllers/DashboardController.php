<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Site;
use App\Models\User;
use App\Support\SiteSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

        $site = $request->user()->sites()->create([
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
        ]);

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

        $site->update([
            'billing_settings' => SiteSettings::sanitizeBilling($billing, ($site->license_status ?? 'active') === 'active'),
        ]);

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

        $site->update([
            'license_status' => 'active',
            'purchase_url' => null,
            'billing_settings' => SiteSettings::sanitizeBilling($billing, true),
            'license_expires_at' => $this->calculateExpiry($billing['cycle']),
        ]);

        $snapshot = $this->generateAuditSnapshot($site->fresh());

        $site->update([
            'audit_snapshot' => $snapshot,
            'last_audited_at' => Carbon::now(),
        ]);

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
        $snapshot = $this->generateAuditSnapshot($site);

        $site->update([
            'audit_snapshot' => $snapshot,
            'last_audited_at' => Carbon::now(),
        ]);

        return redirect()
            ->route('dashboard.compliance', ['site' => $site->id])
            ->with('status', 'Audit snapshot עודכן. עכשיו אפשר לראות ציון, התראות ופעולות פתוחות עבור האתר הזה.');
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

        if (! $this->siteColumnsAvailable(['alert_settings', 'audit_snapshot'])) {
            return redirect()
                ->route('dashboard.compliance', ['site' => $site->id])
                ->withErrors(['alerts' => 'שמירת התראות עדיין לא זמינה בשרת הזה. צריך להשלים את עדכון מסד הנתונים, ואז הכפתור יעבוד רגיל.']);
        }

        $site->update([
            'alert_settings' => SiteSettings::sanitizeAlertSettings([
                'license' => $request->boolean('alerts.license'),
                'statement' => $request->boolean('alerts.statement'),
                'audit' => $request->boolean('alerts.audit'),
                'sync' => $request->boolean('alerts.sync'),
            ]),
        ]);

        $snapshot = $this->generateAuditSnapshot($site->fresh());

        $site->update([
            'audit_snapshot' => $snapshot,
        ]);

        return redirect()
            ->route('dashboard.compliance', ['site' => $site->id])
            ->with('status', 'התראות האתר עודכנו. המסך ימשיך להבליט רק את מה שבאמת חשוב לעקוב אחריו.');
    }

    private function ensureSite(User $user): Site
    {
        $site = $user->sites()->orderBy('id')->first();

        if ($site) {
            return $site;
        }

        return $user->sites()->create([
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
        ]);
    }

    private function resolveSite(Request $request, User $user, ?int $siteId = null): Site
    {
        $selectedId = $siteId ?? (int) $request->integer('site');

        if ($selectedId > 0) {
            $site = $user->sites()->whereKey($selectedId)->first();

            if ($site) {
                return $site;
            }
        }

        return $this->ensureSite($user);
    }

    private function buildDashboardData(User $user): array
    {
        $request = request();
        $site = $this->resolveSite($request, $user);
        $sites = $user->sites()->orderBy('id')->get();
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
        $currentPlanMeta = $planCatalog[$billing['plan']] ?? $planCatalog['starter'];

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
            'billing' => $billing,
            'billingPlans' => $planCatalog,
            'currentPlan' => [
                'name' => $currentPlanMeta['label'],
                'price' => '$' . $currentPlanMeta['prices'][$billing['cycle']] . ' / ' . ($billing['cycle'] === 'yearly' ? 'שנה' : 'חודש'),
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
            'alertSettingsAvailable' => $this->siteColumnsAvailable(['alert_settings', 'audit_snapshot']),
            'licenseExpiresLabel' => $site->license_expires_at ? $site->license_expires_at->timezone(config('app.timezone'))->format('d.m.Y') : 'טרם הופעל',
            'lastAuditedLabel' => $site->last_audited_at ? $site->last_audited_at->diffForHumans() : 'עדיין לא הורץ audit',
            'siteSwitcherOptions' => $sites->map(function (Site $candidate) {
                return [
                    'id' => $candidate->id,
                    'label' => $candidate->site_name . ' · ' . (parse_url($candidate->domain, PHP_URL_HOST) ?: $candidate->domain),
                    'url' => route(request()->route()?->getName() ?: 'dashboard', ['site' => $candidate->id]),
                ];
            })->all(),
        ];
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

    private function planCatalog(): array
    {
        return [
            'starter' => [
                'label' => 'Starter',
                'description' => 'לאתר אחד עם widget hosted, site key קבוע וקונפיגורציה בסיסית.',
                'prices' => ['monthly' => 29, 'yearly' => 149],
            ],
            'growth' => [
                'label' => 'Growth',
                'description' => 'למותגים שצריכים עוד presets, audits שוטפים והתראות אקטיביות.',
                'prices' => ['monthly' => 49, 'yearly' => 249],
            ],
            'agency' => [
                'label' => 'Agency',
                'description' => 'לניהול כמה אתרים, כמה רישיונות וזרימת עבודה של צוות או סוכנות.',
                'prices' => ['monthly' => 99, 'yearly' => 549],
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
                'name' => 'Growth',
                'description' => 'מתאים לאתר שמחזיק יותר יכולות widget, ביקורות שוטפות וניהול alerts ברמה גבוהה יותר.',
            ];
        }

        return [
            'name' => 'Agency',
            'description' => 'אם תמשיך להוסיף אתרים לחשבון, מסלול Agency יתאים יותר לשליטה מרוכזת בין כמה רישיונות.',
        ];
    }

    private function generateAuditSnapshot(Site $site): array
    {
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
        $statementConnected = filled($site->statement_url);
        $billingActive = ($billing['status'] ?? 'inactive') === 'active';

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
            'Audit אחרון',
            $hasRecentAudit ? 'pass' : 'warn',
            $hasRecentAudit
                ? 'ה־audit רץ לאחרונה ונותן תמונת מצב טרייה.'
                : 'כדאי להריץ audit חדש כדי לעדכן score והתראות מול מצב ההגדרות הנוכחי.'
        );

        $score += $hasRecentAudit ? 10 : 3;

        if (! $hasRecentAudit && $alertSettings['audit']) {
            $alerts[] = $this->buildAlert('audit', 'Audit דורש רענון', 'medium', 'לא רץ audit עדכני לאחרונה. הפעל בדיקה חדשה כדי לרענן score והתראות.');
        }

        if ($site->last_audited_at && $site->updated_at && $site->updated_at->gt($site->last_audited_at) && $alertSettings['sync']) {
            $alerts[] = $this->buildAlert('sync', 'יש שינויים מאז ה-audit האחרון', 'low', 'בוצעו עדכוני widget או פרטי אתר אחרי ה־audit האחרון. מומלץ לרענן את הדוח.');
        }

        if ($site->service_mode === 'managed_service') {
            $score += 8;
        } elseif ($site->service_mode === 'audit_and_fix') {
            $score += 5;
        }

        $score = max(0, min(100, $score));
        $status = $score >= 85 ? 'healthy' : ($score >= 70 ? 'monitoring' : 'action_required');

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
            default => 'יש פעולות פתוחות שדורשות טיפול לפני שאפשר להציג את האתר הזה כסט אפ בריא ומנוהל היטב.',
        };
    }

    private function calculateExpiry(string $cycle): Carbon
    {
        return $cycle === 'monthly' ? Carbon::now()->addMonth() : Carbon::now()->addYear();
    }
}
