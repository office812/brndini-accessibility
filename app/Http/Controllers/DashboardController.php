<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Article;
use App\Models\ServiceLead;
use App\Models\Site;
use App\Models\SupportTicket;
use App\Models\User;
use App\Support\RuntimeStore;
use App\Support\SiteSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

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

    public function publicServices(Request $request): View
    {
        return view('public-services', [
            'title' => 'שירותי Brndini | אחסון, SEO, קמפיינים, תחזוקה ואוטומציות',
            'metaDescription' => 'שירותי Brndini לעסקים: אחסון, SEO, קמפיינים, תחזוקת אתר, שדרוג אתר קיים, דפי נחיתה ואוטומציות. הווידג׳ט נשאר חינמי, והשירותים זמינים כשצריך צמיחה ותפעול חכם.',
            'canonicalUrl' => route('brndini.services'),
            'serviceCatalog' => $this->serviceCatalog(),
            'serviceLeadBusinessTypeLabels' => ServiceLead::businessTypeOptions(),
            'serviceLeadTeamSizeLabels' => ServiceLead::teamSizeOptions(),
            'serviceLeadTimeframeLabels' => ServiceLead::timeframeOptions(),
            'serviceLeadBudgetLabels' => ServiceLead::budgetRangeOptions(),
            'serviceLeadUrgencyLabels' => ServiceLead::urgencyOptions(),
            'serviceLeadCallbackWindowLabels' => ServiceLead::callbackWindowOptions(),
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

    public function services(Request $request): View
    {
        return view('services', $this->buildDashboardData($request->user()));
    }

    public function statementPage(string $publicKey): View
    {
        $site = Site::where('public_key', $publicKey)->firstOrFail();
        $site = $this->applySiteRuntimeOverrides($site);
        $statementBuilder = $this->statementBuilderData($site, $site->user);
        $statementPreview = $this->buildStatementPreview($site, $statementBuilder);

        return view('statement', [
            'title' => 'הצהרת נגישות | ' . $site->site_name . ' | A11Y Bridge',
            'canonicalUrl' => route('statement.show', $site->public_key),
            'metaDescription' => 'הצהרת נגישות עבור ' . $site->site_name . ' בפלטפורמת A11Y Bridge.',
            'site' => $site,
            'statementPreview' => $statementPreview,
            'statementBuilder' => $statementBuilder,
        ]);
    }

    public function superAdmin(Request $request): View
    {
        $this->ensureSuperAdmin($request->user());

        return view('admin', $this->buildSuperAdminData($request->user()));
    }

    public function exportServiceLeads(Request $request): StreamedResponse
    {
        $this->ensureSuperAdmin($request->user());

        $serviceCatalog = $this->serviceCatalog();
        $preferredContactLabels = $this->servicePreferredContactLabels();
        $statusLabels = $this->serviceLeadStatusLabels();
        $leads = $this->collectAdminServiceLeads();
        $fileName = 'brndini-service-leads-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($leads, $serviceCatalog, $preferredContactLabels, $statusLabels) {
            $handle = fopen('php://output', 'w');

            if (! $handle) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'קוד פנייה',
                'סוג פנייה',
                'שירות',
                'סטטוס',
                'איכות ליד',
                'ציון',
                'קשר עסקי',
                'שירותים נוספים שביקש',
                'זכיות קודמות',
                'פניות חוזרות מאותו איש קשר',
                'פניות חוזרות מאותו אתר',
                'ערוץ פנייה מומלץ',
                'דרך גישה מומלצת',
                'נוסח פתיחה',
                'שווי משוער',
                'שווי משוקלל',
                'מקור',
                'נקודת כניסה',
                'שם',
                'אימייל',
                'טלפון',
                'אתר',
                'דומיין',
                'מטרה',
                'פירוט',
                'סוג עסק',
                'גודל צוות',
                'זמן התחלה',
                'תקציב',
                'דחיפות',
                'חלון חזרה',
                'ערוץ חזרה',
                'הפעולה הבאה',
                'מועד חזרה',
                'נוצר',
                'גיל ליד',
                'ימים בלי נגיעה',
                'תגובה ראשונית',
                'חסמים תפעוליים',
                'סיבת סגירה',
                'עודכן לאחרונה',
                'UTM',
                'אתר מפנה',
                'הערה פנימית',
            ]);

            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->reference_code,
                    $lead->intent_label,
                    $serviceCatalog[$lead->service_type]['label'] ?? $lead->service_type,
                    $statusLabels[$lead->status] ?? $lead->status,
                    $lead->opportunity_label,
                    $lead->opportunity_score,
                    $lead->relationship_label,
                    $lead->related_services_label,
                    $lead->prior_won_count ?? 0,
                    $lead->repeat_contact_count ?? 1,
                    $lead->repeat_site_count ?? 1,
                    $lead->recommended_contact_channel_label,
                    $lead->playbook_label,
                    $lead->opening_line,
                    $lead->budget_estimate_label,
                    $lead->weighted_estimate_label,
                    $lead->source_label,
                    $lead->entry_label,
                    $lead->user_name ?? $lead->contact_name,
                    $lead->user_email ?? $lead->contact_email,
                    $lead->contact_phone,
                    $lead->site_name,
                    $lead->site_domain,
                    $lead->goal,
                    $lead->message,
                    $lead->business_type_label,
                    $lead->team_size_label,
                    $lead->timeframe_label,
                    $lead->budget_range_label,
                    $lead->urgency_level_label,
                    $lead->callback_window_label,
                    $preferredContactLabels[$lead->preferred_contact_key ?? $lead->preferred_contact] ?? ($lead->preferred_contact ?? ''),
                    $lead->next_step_label,
                    $lead->follow_up_at,
                    $lead->created_at_label,
                    $lead->age_bucket_label,
                    $lead->inactive_bucket_label,
                    $lead->first_touch_label,
                    collect($lead->operational_blockers ?? [])->pluck('label')->implode(' | '),
                    $lead->close_reason_label,
                    $lead->last_activity_label,
                    $lead->marketing_label,
                    $lead->referrer_host,
                    $lead->internal_note,
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
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

        $this->persistUserPayload($user, [
            'name' => $validated['company_name'],
            'contact_email' => strtolower($validated['contact_email']),
        ]);

        $this->persistSitePayload($site, [
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
            'service_mode' => 'audit_only',
            'widget_settings' => SiteSettings::defaultWidget(),
        ];

        $site = Site::createForUser($request->user(), $sitePayload);

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
        $this->persistSitePayload($site, $site->billingUpdatePayload(
            $validated['billing_plan'],
            $validated['billing_cycle']
        ));

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
        $this->persistSitePayload($site, $site->activationPayload());

        $site = $this->applySiteRuntimeOverrides($site->fresh());

        $snapshot = $this->generateAuditSnapshot($site);
        $site->storeAuditSnapshot($snapshot, Carbon::now());

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
            $site->storeAuditSnapshot($snapshot, Carbon::now());

            return redirect()
                ->route('dashboard.compliance', ['site' => $site->id])
                ->with('status', 'הבדיקה הורצה ונשמרה במצב זמני עד שעדכון מסד הנתונים יושלם בשרת.');
        }

        $snapshot = $this->generateAuditSnapshot($site);

        $site->storeAuditSnapshot($snapshot, Carbon::now());

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
            $site->storeAlertConfig($incomingAlerts);
            $previewSite = clone $site;
            $previewSite->alert_settings = $incomingAlerts;
            $snapshot = $this->generateAuditSnapshot($previewSite);
            $site->storeAuditSnapshot($snapshot, $site->lastAuditedAt() ?? Carbon::now());

            return redirect()
                ->route('dashboard.compliance', ['site' => $site->id])
                ->with('status', 'ההתראות נשמרו במצב זמני עד שעדכון מסד הנתונים יושלם בשרת.');
        }

        $site->storeAlertConfig($incomingAlerts);

        $snapshot = $this->generateAuditSnapshot($site->fresh());

        $site->storeAuditSnapshot($snapshot, $site->lastAuditedAt() ?? Carbon::now());

        return redirect()
            ->route('dashboard.compliance', ['site' => $site->id])
            ->with('status', 'התראות האתר עודכנו. המסך ימשיך להבליט רק את מה שבאמת חשוב לעקוב אחריו.');
    }

    public function updateStatementBuilder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_id' => ['required', 'integer'],
            'statement.organization_name' => ['required', 'string', 'max:160'],
            'statement.organization_type' => ['required', Rule::in(array_keys($this->statementOrganizationTypes()))],
            'statement.website_purpose' => ['nullable', 'string', 'max:220'],
            'statement.service_scope' => ['required', Rule::in(array_keys($this->statementServiceScopes()))],
            'statement.accessibility_owner' => ['nullable', 'string', 'max:160'],
            'statement.contact_email' => ['required', 'email', 'max:190'],
            'statement.contact_phone' => ['nullable', 'string', 'max:80'],
            'statement.contact_form_url' => ['nullable', 'string', 'max:190'],
            'statement.response_time' => ['required', Rule::in(array_keys($this->statementResponseTimes()))],
            'statement.last_reviewed_at' => ['nullable', 'date'],
            'statement.testing_manual_keyboard' => ['nullable', 'boolean'],
            'statement.testing_screen_reader' => ['nullable', 'boolean'],
            'statement.testing_automation' => ['nullable', 'boolean'],
            'statement.testing_content_review' => ['nullable', 'boolean'],
            'statement.physical_location_accessible' => ['nullable', 'boolean'],
            'statement.accessible_parking' => ['nullable', 'boolean'],
            'statement.additional_arrangements' => ['nullable', 'string', 'max:700'],
            'statement.known_limitations' => ['nullable', 'string', 'max:1500'],
            'statement.additional_note' => ['nullable', 'string', 'max:700'],
        ]);

        $site = $this->resolveSite($request, $request->user(), (int) $validated['site_id']);
        $contactFormUrl = SiteSettings::normalizeOptionalUrl(data_get($validated, 'statement.contact_form_url'));

        if ($contactFormUrl !== null && ! filter_var($contactFormUrl, FILTER_VALIDATE_URL)) {
            return back()->withErrors([
                'statement.contact_form_url' => 'צריך להזין קישור תקין לטופס יצירת קשר.',
            ])->withInput();
        }

        $statementData = [
            'organization_name' => trim((string) data_get($validated, 'statement.organization_name')),
            'organization_type' => (string) data_get($validated, 'statement.organization_type'),
            'website_purpose' => trim((string) data_get($validated, 'statement.website_purpose', '')),
            'service_scope' => (string) data_get($validated, 'statement.service_scope'),
            'accessibility_owner' => trim((string) data_get($validated, 'statement.accessibility_owner', '')),
            'contact_email' => strtolower(trim((string) data_get($validated, 'statement.contact_email'))),
            'contact_phone' => trim((string) data_get($validated, 'statement.contact_phone', '')),
            'contact_form_url' => $contactFormUrl,
            'response_time' => (string) data_get($validated, 'statement.response_time'),
            'last_reviewed_at' => data_get($validated, 'statement.last_reviewed_at')
                ? Carbon::parse((string) data_get($validated, 'statement.last_reviewed_at'))->format('Y-m-d')
                : now()->format('Y-m-d'),
            'testing_manual_keyboard' => $request->boolean('statement.testing_manual_keyboard'),
            'testing_screen_reader' => $request->boolean('statement.testing_screen_reader'),
            'testing_automation' => $request->boolean('statement.testing_automation'),
            'testing_content_review' => $request->boolean('statement.testing_content_review'),
            'physical_location_accessible' => $request->boolean('statement.physical_location_accessible'),
            'accessible_parking' => $request->boolean('statement.accessible_parking'),
            'additional_arrangements' => trim((string) data_get($validated, 'statement.additional_arrangements', '')),
            'known_limitations' => trim((string) data_get($validated, 'statement.known_limitations', '')),
            'additional_note' => trim((string) data_get($validated, 'statement.additional_note', '')),
        ];

        $site->storeStatementBuilder($statementData);

        $statementUrl = route('statement.show', $site->public_key);

        $this->persistSitePayload($site, [
            'statement_url' => $statementUrl,
        ]);

        return redirect()
            ->to(route('dashboard.compliance', ['site' => $site->id]) . '#tab-statement-builder')
            ->with('status', 'הצהרת הנגישות נשמרה. עכשיו יש לך ניסוח מוכן, קישור ציבורי ותצוגה מקדימה שאפשר לשתף.');
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

        if (! SupportTicket::tableAvailable()) {
            SupportTicket::storeRuntime($request->user(), $site, $validated);

            return redirect()
                ->route('dashboard.support', ['site' => $site->id])
                ->with('status', 'הפנייה נפתחה ונשמרה במצב גיבוי עד שהשרת ישלים את מיגרציות מרכז התמיכה.');
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

    public function storeServiceLead(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_id' => ['required', 'integer'],
            'service_type' => ['required', Rule::in(array_keys($this->serviceCatalog()))],
            'goal' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'min:20', 'max:4000'],
            'business_type' => ['nullable', Rule::in(array_keys(ServiceLead::businessTypeOptions()))],
            'team_size' => ['nullable', Rule::in(array_keys(ServiceLead::teamSizeOptions()))],
            'timeframe' => ['nullable', Rule::in(array_keys(ServiceLead::timeframeOptions()))],
            'budget_range' => ['nullable', Rule::in(array_keys(ServiceLead::budgetRangeOptions()))],
            'urgency_level' => ['nullable', Rule::in(array_keys(ServiceLead::urgencyOptions()))],
            'callback_window' => ['nullable', Rule::in(array_keys(ServiceLead::callbackWindowOptions()))],
            'preferred_contact' => ['required', Rule::in(array_keys($this->servicePreferredContactLabels()))],
            'contact_phone' => ['nullable', 'required_if:preferred_contact,phone,whatsapp', 'string', 'max:40'],
            'entry_point' => ['nullable', Rule::in(['dashboard-services', 'dashboard-recommendations'])],
        ], [
            'contact_phone.required_if' => 'אם בחרת טלפון או ווטסאפ, צריך להוסיף מספר לחזרה.',
        ]);

        $site = $this->resolveSite($request, $request->user(), (int) $validated['site_id']);
        ServiceLead::storeRuntime($request->user(), $site, $validated);

        return redirect()
            ->route('dashboard.services', ['site' => $site->id])
            ->with('status', 'פניית השירות נשלחה ונשמרה. עכשיו אפשר לחזור אליך בנושא ' . ($this->serviceCatalog()[$validated['service_type']]['label'] ?? 'השירות שביקשת') . '.');
    }

    public function storePublicServiceLead(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'website' => ['nullable', 'string', 'max:255'],
            'service_type' => ['required', Rule::in(array_keys($this->serviceCatalog()))],
            'goal' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'min:20', 'max:4000'],
            'business_type' => ['nullable', Rule::in(array_keys(ServiceLead::businessTypeOptions()))],
            'team_size' => ['nullable', Rule::in(array_keys(ServiceLead::teamSizeOptions()))],
            'timeframe' => ['nullable', Rule::in(array_keys(ServiceLead::timeframeOptions()))],
            'budget_range' => ['nullable', Rule::in(array_keys(ServiceLead::budgetRangeOptions()))],
            'urgency_level' => ['nullable', Rule::in(array_keys(ServiceLead::urgencyOptions()))],
            'callback_window' => ['nullable', Rule::in(array_keys(ServiceLead::callbackWindowOptions()))],
            'preferred_contact' => ['required', Rule::in(array_keys($this->servicePreferredContactLabels()))],
            'contact_phone' => ['nullable', 'required_if:preferred_contact,phone,whatsapp', 'string', 'max:40'],
            'entry_point' => ['nullable', Rule::in(['public-services', 'home-ecosystem', 'products-page', 'services-cards'])],
            'utm_source' => ['nullable', 'string', 'max:120'],
            'utm_medium' => ['nullable', 'string', 'max:120'],
            'utm_campaign' => ['nullable', 'string', 'max:180'],
            'referrer_url' => ['nullable', 'url', 'max:500'],
        ], [
            'contact_phone.required_if' => 'אם בחרת טלפון או ווטסאפ, צריך להוסיף מספר לחזרה.',
        ]);

        ServiceLead::storePublicRuntime($validated);

        return redirect()
            ->route('brndini.services')
            ->withFragment('public-service-form')
            ->with('status', 'הפנייה נשלחה בהצלחה. צוות Brndini יוכל לחזור אליך לגבי ' . ($this->serviceCatalog()[$validated['service_type']]['label'] ?? 'השירות שביקשת') . '.');
    }

    public function updateSupportTicketAdmin(Request $request, string $ticketKey): RedirectResponse
    {
        $admin = $request->user();
        $this->ensureSuperAdmin($admin);

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys($this->supportStatusLabels()))],
            'priority' => ['required', Rule::in(array_keys($this->supportPriorityLabels()))],
            'admin_response' => ['nullable', 'string', 'max:4000'],
        ]);

        if (SupportTicket::tableAvailable() && ctype_digit($ticketKey)) {
            $ticket = SupportTicket::query()->findOrFail((int) $ticketKey);

            $ticket->update([
                'status' => $validated['status'],
                'priority' => $validated['priority'],
                'last_activity_at' => Carbon::now(),
            ]);

            if (SupportTicket::columnsAvailable(['assigned_user_id'])) {
                $ticket->update([
                    'assigned_user_id' => $admin->id,
                ]);
            }

            $response = trim((string) ($validated['admin_response'] ?? ''));

            if (SupportTicket::columnsAvailable(['admin_response'])) {
                $ticket->update([
                    'admin_response' => $response === '' ? null : $response,
                ]);
            } else {
                RuntimeStore::put('support-ticket-' . $ticket->id, 'admin_response', $response === '' ? null : $response);
            }
        } else {
            SupportTicket::updateRuntime($ticketKey, $admin, $validated);
        }

        return redirect()
            ->route('dashboard.super-admin')
            ->with('status', 'הפנייה עודכנה ונשמרה ממרכז הסופר־אדמין.');
    }

    public function updateServiceLeadAdmin(Request $request, string $leadKey): RedirectResponse
    {
        $admin = $request->user();
        $this->ensureSuperAdmin($admin);

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys($this->serviceLeadStatusLabels()))],
            'internal_note' => ['nullable', 'string', 'max:4000'],
            'assigned_admin_email' => ['nullable', 'email'],
            'follow_up_at' => ['nullable', 'date'],
            'close_reason' => ['nullable', Rule::in(array_keys(ServiceLead::closeReasonOptions()))],
        ]);

        ServiceLead::updateRuntime($leadKey, $admin, $validated);

        return redirect()
            ->route('dashboard.super-admin', ['tab' => 'leads'])
            ->with('status', 'הליד עודכן ונשמר ממרכז הסופר־אדמין.');
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
            'service_mode' => 'audit_only',
            'widget_settings' => SiteSettings::defaultWidget(),
        ];

        $site = Site::createForUser($user, $sitePayload);

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
        $installationSignal = $this->installationSignal($site);
        $widget = $site->widgetConfig();
        $billing = $site->billingConfig();
        $alertSettings = $site->alertConfig();
        $auditSnapshot = $site->auditConfig();
        if (($auditSnapshot['checks'] ?? []) === [] && ($auditSnapshot['alerts'] ?? []) === [] && ($auditSnapshot['score'] ?? null) === 72) {
            $auditSnapshot = $this->generateAuditSnapshot($site);
        }
        $statementBuilder = $this->statementBuilderData($site, $user);
        $statementPreview = $this->buildStatementPreview($site, $statementBuilder);
        $statementUrl = $this->statementUrlForSite($site);
        $statementConnected = $this->siteHasStatement($site);
        $serviceModes = $this->serviceModes();
        $featureCount = count(array_filter([
            $widget['showContrast'],
            $widget['showFontScale'],
            $widget['showUnderlineLinks'],
            $widget['showReduceMotion'],
        ]));
        $planCatalog = SiteSettings::billingCatalog();
        $activeAlerts = collect($auditSnapshot['alerts'] ?? [])->filter(fn (array $alert) => ($alert['state'] ?? 'open') !== 'resolved')->values();
        $currentPlanMeta = $site->billingPlanMeta();
        $installationSummary = match ($installationSignal['status']) {
            'installed' => 'הווידג׳ט זוהה בפועל באתר וממשיך לדווח חזרה לפלטפורמה.',
            'stale' => 'הווידג׳ט זוהה בעבר, אבל לא התקבלה טעינה חדשה לאחרונה. כדאי לבדוק שהוא עדיין מוטמע ונטען.',
            default => 'הקוד עדיין לא זוהה באתר, ולכן חשוב להשלים הטמעה לפני שמסתמכים על הציון.',
        };
        $dbSupportTickets = SupportTicket::tableAvailable()
            ? $user->supportTickets()
                ->with('site')
                ->where(function ($query) use ($site) {
                    $query->where('site_id', $site->id)->orWhereNull('site_id');
                })
                ->latest('last_activity_at')
                ->latest('created_at')
                ->get()
                ->map(fn (SupportTicket $ticket) => $ticket->present())
            : collect();
        $runtimeSupportTickets = SupportTicket::runtimeTickets()
            ->filter(fn (array $ticket) => (int) ($ticket['user_id'] ?? 0) === $user->id)
            ->filter(fn (array $ticket) => (int) ($ticket['site_id'] ?? 0) === $site->id || empty($ticket['site_id']))
            ->map(fn (array $ticket) => SupportTicket::presentRuntime($ticket));
        $supportTickets = $dbSupportTickets
            ->concat($runtimeSupportTickets)
            ->sortByDesc('sort_timestamp')
            ->values();
        $serviceLeads = ServiceLead::runtimeLeads()
            ->filter(fn (array $lead) => (int) ($lead['user_id'] ?? 0) === $user->id)
            ->filter(fn (array $lead) => (int) ($lead['site_id'] ?? 0) === $site->id || empty($lead['site_id']))
            ->map(fn (array $lead) => ServiceLead::presentRuntime($lead))
            ->sortByDesc('sort_timestamp')
            ->values();
        $serviceRecommendations = $this->serviceRecommendationsForSite(
            $site,
            $installationSignal,
            $activeAlerts->count(),
            $statementConnected,
            $site->billingActive()
        );
        $openTickets = $supportTickets->whereIn('status', ['open', 'pending']);
        $urgentTickets = $supportTickets->where('priority', 'urgent');
        $activeSitesCount = $sites->filter(fn (Site $candidate) => $candidate->licenseActive())->count();
        $platformReadiness = $this->platformReadiness();

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
            'licenseStatus' => $site->licenseStatus(),
            'installationStatus' => $installationSignal['status'],
            'installationLabel' => $installationSignal['label'],
            'installationTone' => $installationSignal['tone'],
            'installationEverSeen' => $installationSignal['ever_seen'],
            'installationSeenLabel' => $installationSignal['last_seen_at']?->diffForHumans() ?? 'עדיין לא זוהתה טעינה מהאתר',
            'installationPageUrl' => $installationSignal['page_url'],
            'installationSummary' => $installationSummary,
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
            'statementStatus' => $statementConnected ? 'connected' : 'missing',
            'statementUrl' => $statementUrl,
            'statementBuilder' => $statementBuilder,
            'statementPreview' => $statementPreview,
            'statementOrganizationTypes' => $this->statementOrganizationTypes(),
            'statementServiceScopes' => $this->statementServiceScopes(),
            'statementResponseTimes' => $this->statementResponseTimes(),
            'featureCount' => $featureCount,
            'auditSnapshot' => $auditSnapshot,
            'auditChecks' => $auditSnapshot['checks'] ?? [],
            'openAlerts' => $activeAlerts,
            'openAlertsCount' => $activeAlerts->count(),
            'alertSettings' => $alertSettings,
            'auditActionsAvailable' => $this->siteColumnsAvailable(['audit_snapshot', 'last_audited_at']),
            'alertSettingsAvailable' => $this->siteColumnsAvailable(['alert_settings', 'audit_snapshot']),
            'licenseExpiresLabel' => $site->license_expires_at ? $site->license_expires_at->timezone(config('app.timezone'))->format('d.m.Y') : 'טרם הופעל',
            'lastAuditedLabel' => $site->lastAuditedAt() ? $site->lastAuditedAt()->diffForHumans() : 'עדיין לא הורצה בדיקה',
            'siteSwitcherOptions' => $sites->map(function (Site $candidate) {
                return [
                    'id' => $candidate->id,
                    'label' => $candidate->site_name . ' · ' . (parse_url($candidate->domain, PHP_URL_HOST) ?: $candidate->domain),
                    'url' => route(request()->route()?->getName() ?: 'dashboard', ['site' => $candidate->id]),
                ];
            })->all(),
            'activeSitesCount' => $activeSitesCount,
            'supportAvailable' => true,
            'supportUsesRuntimeFallback' => ! SupportTicket::tableAvailable(),
            'supportTickets' => $supportTickets,
            'supportCategories' => $this->supportCategories(),
            'supportPriorityLabels' => $this->supportPriorityLabels(),
            'supportStatusLabels' => $this->supportStatusLabels(),
            'supportSummary' => [
                'open' => $openTickets->count(),
                'urgent' => $urgentTickets->count(),
                'resolved' => $supportTickets->where('status', 'resolved')->count(),
                'lastActivity' => $supportTickets->first()?->last_activity_label ?? 'עדיין לא נפתחה פנייה',
            ],
            'serviceCatalog' => $this->serviceCatalog(),
            'servicePreferredContactLabels' => $this->servicePreferredContactLabels(),
            'serviceLeadStatusLabels' => $this->serviceLeadStatusLabels(),
            'serviceLeadBusinessTypeLabels' => ServiceLead::businessTypeOptions(),
            'serviceLeadTeamSizeLabels' => ServiceLead::teamSizeOptions(),
            'serviceLeadTimeframeLabels' => ServiceLead::timeframeOptions(),
            'serviceLeadBudgetLabels' => ServiceLead::budgetRangeOptions(),
            'serviceLeadUrgencyLabels' => ServiceLead::urgencyOptions(),
            'serviceLeadCallbackWindowLabels' => ServiceLead::callbackWindowOptions(),
            'serviceLeads' => $serviceLeads,
            'serviceRecommendations' => $serviceRecommendations,
            'serviceLeadSummary' => [
                'total' => $serviceLeads->count(),
                'new' => $serviceLeads->where('status', 'new')->count(),
                'proposal' => $serviceLeads->where('status', 'proposal')->count(),
                'won' => $serviceLeads->where('status', 'won')->count(),
                'qualifiedBusinesses' => $serviceLeads->filter(fn ($lead) => ! in_array($lead->business_type ?? null, [null, ''], true))->count(),
                'urgent' => $serviceLeads->where('timeframe', 'urgent')->count(),
                'budgeted' => $serviceLeads->filter(fn ($lead) => ! in_array($lead->budget_range ?? null, [null, '', 'unknown'], true))->count(),
                'needsFastReply' => $serviceLeads->where('urgency_level', 'urgent')->count(),
                'scheduledToday' => $serviceLeads->where('follow_up_tone', 'good')->count(),
                'lastActivity' => $serviceLeads->first()?->last_activity_label ?? 'עדיין לא נשלחה פנייה',
            ],
            'platformReadiness' => $platformReadiness,
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

        $supportAvailable = SupportTicket::tableAvailable();

        $usersQuery = User::query()
            ->withCount(['sites'])
            ->orderByDesc('id');

        if ($supportAvailable) {
            $usersQuery->withCount(['supportTickets']);
        }

        $users = $usersQuery->get();
        $runtimeTickets = SupportTicket::runtimeTickets();
        $runtimeTicketCounts = $runtimeTickets->countBy(fn (array $ticket) => (int) ($ticket['user_id'] ?? 0));

        $users->each(function (User $adminUser) use ($supportAvailable, $runtimeTicketCounts): void {
            $baseCount = $supportAvailable ? (int) $adminUser->support_tickets_count : 0;
            $adminUser->setAttribute('support_tickets_count', $baseCount + (int) ($runtimeTicketCounts[$adminUser->id] ?? 0));
        });

        $sites = Site::query()
            ->with('user')
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get()
            ->map(function (Site $site) {
                $site = $this->applySiteRuntimeOverrides($site);
                $signal = $this->installationSignal($site);
                $site->setAttribute('last_seen_at', $signal['last_seen_at']);
                $site->setAttribute('last_seen_url', $signal['page_url']);

                return $site;
            });

        $tickets = $supportAvailable
            ? SupportTicket::query()
                ->with(['user', 'site', 'assignedUser'])
                ->latest('last_activity_at')
                ->latest('created_at')
                ->get()
                ->map(fn (SupportTicket $ticket) => $ticket->present())
            : collect();
        $tickets = $tickets
            ->concat($runtimeTickets->map(fn (array $ticket) => SupportTicket::presentRuntime($ticket)))
            ->sortByDesc('sort_timestamp')
            ->values();
        $serviceCatalog = $this->serviceCatalog();
        $serviceLeads = $this->collectAdminServiceLeads();
        $serviceLeadSourceSummary = collect(ServiceLead::sourceOptions())
            ->map(fn (string $label, string $key) => [
                'key' => $key,
                'label' => $label,
                'count' => $serviceLeads->where('source', $key)->count(),
            ])
            ->values();
        $serviceLeadEntrySummary = collect(ServiceLead::entryOptions())
            ->map(fn (string $label, string $key) => [
                'key' => $key,
                'label' => $label,
                'count' => $serviceLeads->where('entry_point', $key)->count(),
            ])
            ->filter(fn (array $item) => $item['count'] > 0)
            ->sortByDesc('count')
            ->values();
        $serviceLeadMarketingSummary = collect([
            [
                'key' => 'attributed',
                'label' => 'עם UTM',
                'count' => $serviceLeads->filter(fn ($lead) => filled($lead->marketing_label ?? null))->count(),
            ],
            [
                'key' => 'campaigns',
                'label' => 'עם קמפיין',
                'count' => $serviceLeads->filter(fn ($lead) => filled($lead->utm_campaign ?? null))->count(),
            ],
            [
                'key' => 'referrers',
                'label' => 'עם אתר מפנה',
                'count' => $serviceLeads->filter(fn ($lead) => filled($lead->referrer_host ?? null))->count(),
            ],
        ])->values();
        $serviceLeadOpportunitySummary = collect([
            [
                'key' => 'hot',
                'label' => 'לידים חמים',
                'count' => $serviceLeads->where('opportunity_key', 'hot')->count(),
            ],
            [
                'key' => 'warm',
                'label' => 'לידים איכותיים',
                'count' => $serviceLeads->where('opportunity_key', 'warm')->count(),
            ],
            [
                'key' => 'cold',
                'label' => 'עניין ראשוני',
                'count' => $serviceLeads->where('opportunity_key', 'cold')->count(),
            ],
        ])->values();
        $serviceLeadTimeframeSummary = collect(ServiceLead::timeframeOptions())
            ->map(fn (string $label, string $key) => [
                'key' => $key,
                'label' => $label,
                'count' => $serviceLeads->where('timeframe', $key)->count(),
            ])
            ->filter(fn (array $item) => $item['count'] > 0)
            ->values();
        $serviceLeadBusinessSummary = collect(ServiceLead::businessTypeOptions())
            ->map(fn (string $label, string $key) => [
                'key' => $key,
                'label' => $label,
                'count' => $serviceLeads->where('business_type', $key)->count(),
            ])
            ->filter(fn (array $item) => $item['count'] > 0)
            ->values();
        $serviceLeadTeamSummary = collect(ServiceLead::teamSizeOptions())
            ->map(fn (string $label, string $key) => [
                'key' => $key,
                'label' => $label,
                'count' => $serviceLeads->where('team_size', $key)->count(),
            ])
            ->filter(fn (array $item) => $item['count'] > 0)
            ->values();
        $serviceLeadBudgetSummary = collect(ServiceLead::budgetRangeOptions())
            ->map(fn (string $label, string $key) => [
                'key' => $key,
                'label' => $label,
                'count' => $serviceLeads->where('budget_range', $key)->count(),
            ])
            ->filter(fn (array $item) => $item['count'] > 0)
            ->values();
        $serviceLeadUrgencySummary = collect(ServiceLead::urgencyOptions())
            ->map(fn (string $label, string $key) => [
                'key' => $key,
                'label' => $label,
                'count' => $serviceLeads->where('urgency_level', $key)->count(),
            ])
            ->filter(fn (array $item) => $item['count'] > 0)
            ->values();
        $serviceLeadCallbackWindowSummary = collect(ServiceLead::callbackWindowOptions())
            ->map(fn (string $label, string $key) => [
                'key' => $key,
                'label' => $label,
                'count' => $serviceLeads->where('callback_window', $key)->count(),
            ])
            ->filter(fn (array $item) => $item['count'] > 0)
            ->values();
        $serviceLeadAssigneeSummary = $serviceLeads
            ->groupBy(fn ($lead) => $lead->assigned_admin_email ?: 'unassigned')
            ->map(function ($group, $key) {
                $first = $group->first();

                return [
                    'key' => $key,
                    'label' => $key === 'unassigned' ? 'לא משויך' : ($first->assigned_admin_name ?? $first->assigned_admin_email ?? 'לא משויך'),
                    'count' => $group->count(),
                ];
            })
            ->sortByDesc('count')
            ->values();
        $serviceLeadStageSummary = collect($this->serviceLeadStatusLabels())
            ->map(fn (string $label, string $key) => [
                'key' => $key,
                'label' => $label,
                'count' => $serviceLeads->where('status', $key)->count(),
            ])
            ->filter(fn (array $item) => $item['count'] > 0)
            ->values();
        $serviceLeadServiceSummary = collect($serviceCatalog)
            ->map(fn (array $service, string $key) => [
                'key' => $key,
                'label' => $service['label'],
                'count' => $serviceLeads->where('service_type', $key)->count(),
            ])
            ->filter(fn (array $item) => $item['count'] > 0)
            ->sortByDesc('count')
            ->values();
        $serviceLeadActionQueue = $serviceLeads
            ->filter(fn ($lead) => in_array($lead->status, ['new', 'contacted', 'qualified', 'proposal'], true))
            ->filter(fn ($lead) => $this->serviceLeadNeedsActionNow($lead))
            ->sortByDesc(fn ($lead) => $this->serviceLeadActionPriority($lead))
            ->values();
        $serviceLeadRepeatSummary = collect([
            [
                'key' => 'repeat_contacts',
                'label' => 'פניות חוזרות מאותו איש קשר',
                'count' => $serviceLeads->filter(fn ($lead) => (int) ($lead->repeat_contact_count ?? 1) > 1)->count(),
            ],
            [
                'key' => 'repeat_sites',
                'label' => 'אתרים שחוזרים שוב',
                'count' => $serviceLeads->filter(fn ($lead) => (int) ($lead->repeat_site_count ?? 1) > 1)->count(),
            ],
            [
                'key' => 'hot_repeat_contacts',
                'label' => 'חוזרים חמים',
                'count' => $serviceLeads->filter(fn ($lead) => (int) ($lead->repeat_contact_count ?? 1) > 1 && ($lead->opportunity_key ?? null) === 'hot')->count(),
            ],
        ])
            ->filter(fn (array $item) => $item['count'] > 0)
            ->values();
        $serviceLeadRelationshipSummary = collect([
            [
                'key' => 'existing_customers',
                'label' => 'לקוחות קיימים שחזרו',
                'count' => $serviceLeads->filter(fn ($lead) => ($lead->relationship_key ?? null) === 'existing_customer')->count(),
            ],
            [
                'key' => 'cross_sell',
                'label' => 'הזדמנויות Cross-sell',
                'count' => $serviceLeads->filter(fn ($lead) => (int) ($lead->distinct_service_count ?? 1) > 1)->count(),
            ],
            [
                'key' => 'returning_after_win',
                'label' => 'חזרו אחרי זכייה',
                'count' => $serviceLeads->filter(fn ($lead) => (int) ($lead->prior_won_count ?? 0) > 0)->count(),
            ],
        ])
            ->filter(fn (array $item) => $item['count'] > 0)
            ->values();
        $serviceLeadNextServiceSummary = collect($serviceCatalog)
            ->map(function (array $service, string $key) use ($serviceLeads) {
                return [
                    'key' => $key,
                    'label' => $service['label'],
                    'count' => $serviceLeads->where('recommended_service_type', $key)->count(),
                ];
            })
            ->filter(fn (array $item) => $item['count'] > 0)
            ->sortByDesc('count')
            ->values();
        $serviceLeadPlaybookSummary = collect([
            'discovery_call' => 'שיחת גילוי קצרה',
            'quick_quote' => 'הצעה מהירה',
            'technical_review' => 'בדיקה טכנית קצרה',
            'upsell_bundle' => 'הצעת המשך / הרחבה',
            'nurture_track' => 'חימום ועדכון',
        ])->map(function (string $label, string $key) use ($serviceLeads) {
            return [
                'key' => $key,
                'label' => $label,
                'count' => $serviceLeads->where('playbook_key', $key)->count(),
            ];
        })
            ->filter(fn (array $item) => $item['count'] > 0)
            ->sortByDesc('count')
            ->values();
        $serviceLeadChannelSummary = collect([
            'phone' => 'טלפון',
            'whatsapp' => 'ווטסאפ',
            'email' => 'מייל',
        ])->map(function (string $label, string $key) use ($serviceLeads) {
            return [
                'key' => $key,
                'label' => $label,
                'count' => $serviceLeads->where('recommended_contact_channel_key', $key)->count(),
            ];
        })
            ->filter(fn (array $item) => $item['count'] > 0)
            ->sortByDesc('count')
            ->values();
        $serviceLeadValueSummary = [
            'pipeline_total' => $serviceLeads->sum(fn ($lead) => (int) ($lead->budget_estimate_amount ?? 0)),
            'weighted_pipeline_total' => $serviceLeads->sum(fn ($lead) => (int) ($lead->weighted_estimate_amount ?? 0)),
            'won_total' => $serviceLeads
                ->where('status', 'won')
                ->sum(fn ($lead) => (int) ($lead->budget_estimate_amount ?? 0)),
            'hot_total' => $serviceLeads
                ->where('opportunity_key', 'hot')
                ->sum(fn ($lead) => (int) ($lead->weighted_estimate_amount ?? 0)),
            'budgeted_count' => $serviceLeads->filter(fn ($lead) => (int) ($lead->budget_estimate_amount ?? 0) > 0)->count(),
        ];
        $serviceLeadPerformanceSummary = [
            'win_rate' => $this->formatLeadRate($serviceLeads->where('status', 'won')->count(), $serviceLeads->count()),
            'qualified_rate' => $this->formatLeadRate(
                $serviceLeads->filter(fn ($lead) => in_array($lead->status, ['qualified', 'proposal', 'won'], true))->count(),
                $serviceLeads->count()
            ),
            'proposal_rate' => $this->formatLeadRate(
                $serviceLeads->filter(fn ($lead) => in_array($lead->status, ['proposal', 'won'], true))->count(),
                $serviceLeads->count()
            ),
            'public_share' => $this->formatLeadRate($serviceLeads->where('source', 'public')->count(), $serviceLeads->count()),
        ];
        $serviceLeadSourcePerformance = collect(ServiceLead::sourceOptions())
            ->map(function (string $label, string $key) use ($serviceLeads) {
                $subset = $serviceLeads->where('source', $key)->values();

                return [
                    'key' => $key,
                    'label' => $label,
                    'count' => $subset->count(),
                    'won' => $subset->where('status', 'won')->count(),
                    'rate' => $this->formatLeadRate($subset->where('status', 'won')->count(), $subset->count()),
                ];
            })
            ->filter(fn (array $item) => $item['count'] > 0)
            ->sortByDesc('count')
            ->values();
        $serviceLeadServicePerformance = collect($this->serviceCatalog())
            ->map(function (array $service, string $key) use ($serviceLeads) {
                $subset = $serviceLeads->where('service_type', $key)->values();

                return [
                    'key' => $key,
                    'label' => $service['label'],
                    'count' => $subset->count(),
                    'won' => $subset->where('status', 'won')->count(),
                    'weighted' => $subset->sum(fn ($lead) => (int) ($lead->weighted_estimate_amount ?? 0)),
                    'rate' => $this->formatLeadRate($subset->where('status', 'won')->count(), $subset->count()),
                ];
            })
            ->filter(fn (array $item) => $item['count'] > 0)
            ->sortByDesc('weighted')
            ->values();
        $serviceLeadCloseReasonSummary = collect(ServiceLead::closeReasonOptions())
            ->map(function (string $label, string $key) use ($serviceLeads) {
                return [
                    'key' => $key,
                    'label' => $label,
                    'count' => $serviceLeads->where('close_reason', $key)->count(),
                ];
            })
            ->filter(fn (array $item) => $item['count'] > 0)
            ->sortByDesc('count')
            ->values();
        $serviceLeadOperationalBlockerSummary = collect([
            'no_assignee' => 'לא משויכים למטפל',
            'no_follow_up' => 'אין מועד חזרה',
            'overdue_follow_up' => 'מועד חזרה עבר',
            'missing_phone' => 'חסר טלפון לחזרה',
            'missing_domain' => 'חסר דומיין',
            'missing_budget' => 'אין תקציב מוגדר',
            'inactive' => 'תקועים בלי נגיעה',
        ])->map(function (string $label, string $key) use ($serviceLeads) {
            return [
                'key' => $key,
                'label' => $label,
                'count' => $serviceLeads->filter(function ($lead) use ($key) {
                    return collect($lead->operational_blockers ?? [])->contains(fn ($item) => ($item['key'] ?? null) === $key);
                })->count(),
            ];
        })
            ->filter(fn (array $item) => $item['count'] > 0)
            ->sortByDesc('count')
            ->values();
        $serviceLeadFirstTouchSummary = collect([
            'touched' => 'טופלו ראשונית',
            'waiting' => 'ממתינים למגע ראשון',
            'overdue' => 'חרגו SLA למגע ראשון',
        ])->map(function (string $label, string $key) use ($serviceLeads) {
            return [
                'key' => $key,
                'label' => $label,
                'count' => $serviceLeads->where('first_touch_key', $key)->count(),
            ];
        })
            ->filter(fn (array $item) => $item['count'] > 0)
            ->values();
        $serviceLeadAgeSummary = collect([
            'today' => 'נוצרו היום',
            'recent' => 'חדשים 1–3 ימים',
            'aging' => 'יושבים 4–7 ימים',
            'stale' => 'וותיקים 8+ ימים',
        ])->map(function (string $label, string $key) use ($serviceLeads) {
            return [
                'key' => $key,
                'label' => $label,
                'count' => $serviceLeads->where('age_bucket', $key)->count(),
            ];
        })
            ->filter(fn (array $item) => $item['count'] > 0)
            ->values();
        $serviceLeadInactivitySummary = collect([
            'today' => 'טופלו היום',
            'active' => 'נגיעה ב־48 שעות',
            'waiting' => 'ללא נגיעה 3–5 ימים',
            'stuck' => 'תקועים 6+ ימים',
        ])->map(function (string $label, string $key) use ($serviceLeads) {
            return [
                'key' => $key,
                'label' => $label,
                'count' => $serviceLeads->where('inactive_bucket', $key)->count(),
            ];
        })
            ->filter(fn (array $item) => $item['count'] > 0)
            ->values();

        $superAdminUsersCount = $users->filter(fn (User $adminUser) => $adminUser->isSuperAdmin())->count();
        $adminUsersCount = $users->filter(fn (User $adminUser) => $adminUser->is_admin && ! $adminUser->isSuperAdmin())->count();
        $clientUsersCount = max($users->count() - $superAdminUsersCount - $adminUsersCount, 0);
        $installedSitesCount = $sites->filter(fn (Site $site) => $site->installationSignal()['status'] === 'installed')->count();
        $staleInstallSitesCount = $sites->filter(fn (Site $site) => $site->installationSignal()['status'] === 'stale')->count();
        $pendingInstallSitesCount = $sites->filter(fn (Site $site) => $site->installationSignal()['status'] === 'pending')->count();
        $trackingScriptsActiveCount = AppSetting::activeCount($tracking);

        return [
            'title' => 'מרכז סופר־אדמין | A11Y Bridge',
            'user' => $user,
            'trackingScripts' => $tracking,
            'trackingScriptsActiveCount' => $trackingScriptsActiveCount,
            'adminUsers' => $users,
            'adminSites' => $sites,
            'adminSupportTickets' => $tickets,
            'adminServiceLeads' => $serviceLeads,
            'serviceLeadSourceSummary' => $serviceLeadSourceSummary,
            'serviceLeadEntrySummary' => $serviceLeadEntrySummary,
            'serviceLeadMarketingSummary' => $serviceLeadMarketingSummary,
            'serviceLeadOpportunitySummary' => $serviceLeadOpportunitySummary,
            'serviceLeadBusinessSummary' => $serviceLeadBusinessSummary,
            'serviceLeadTeamSummary' => $serviceLeadTeamSummary,
            'serviceLeadTimeframeSummary' => $serviceLeadTimeframeSummary,
            'serviceLeadBudgetSummary' => $serviceLeadBudgetSummary,
            'serviceLeadUrgencySummary' => $serviceLeadUrgencySummary,
            'serviceLeadCallbackWindowSummary' => $serviceLeadCallbackWindowSummary,
            'serviceLeadAssigneeSummary' => $serviceLeadAssigneeSummary,
            'serviceLeadStageSummary' => $serviceLeadStageSummary,
            'serviceLeadServiceSummary' => $serviceLeadServiceSummary,
            'serviceLeadActionQueue' => $serviceLeadActionQueue,
            'serviceLeadRepeatSummary' => $serviceLeadRepeatSummary,
            'serviceLeadRelationshipSummary' => $serviceLeadRelationshipSummary,
            'serviceLeadNextServiceSummary' => $serviceLeadNextServiceSummary,
            'serviceLeadPlaybookSummary' => $serviceLeadPlaybookSummary,
            'serviceLeadChannelSummary' => $serviceLeadChannelSummary,
            'serviceLeadValueSummary' => $serviceLeadValueSummary,
            'serviceLeadPerformanceSummary' => $serviceLeadPerformanceSummary,
            'serviceLeadSourcePerformance' => $serviceLeadSourcePerformance,
            'serviceLeadServicePerformance' => $serviceLeadServicePerformance,
            'serviceLeadCloseReasonSummary' => $serviceLeadCloseReasonSummary,
            'serviceLeadAgeSummary' => $serviceLeadAgeSummary,
            'serviceLeadInactivitySummary' => $serviceLeadInactivitySummary,
            'serviceLeadFirstTouchSummary' => $serviceLeadFirstTouchSummary,
            'serviceLeadOperationalBlockerSummary' => $serviceLeadOperationalBlockerSummary,
            'serviceCatalog' => $serviceCatalog,
            'servicePreferredContactLabels' => $this->servicePreferredContactLabels(),
            'serviceLeadStatusLabels' => $this->serviceLeadStatusLabels(),
            'serviceLeadBusinessTypeLabels' => ServiceLead::businessTypeOptions(),
            'serviceLeadTeamSizeLabels' => ServiceLead::teamSizeOptions(),
            'serviceLeadTimeframeLabels' => ServiceLead::timeframeOptions(),
            'serviceLeadBudgetLabels' => ServiceLead::budgetRangeOptions(),
            'serviceLeadUrgencyLabels' => ServiceLead::urgencyOptions(),
            'serviceLeadCallbackWindowLabels' => ServiceLead::callbackWindowOptions(),
            'serviceLeadCloseReasonLabels' => ServiceLead::closeReasonOptions(),
            'superAdminUsersCount' => $superAdminUsersCount,
            'adminUsersCount' => $adminUsersCount,
            'clientUsersCount' => $clientUsersCount,
            'installedSitesCount' => $installedSitesCount,
            'staleInstallSitesCount' => $staleInstallSitesCount,
            'pendingInstallSitesCount' => $pendingInstallSitesCount,
            'supportCategories' => $this->supportCategories(),
            'supportPriorityLabels' => $this->supportPriorityLabels(),
            'supportStatusLabels' => $this->supportStatusLabels(),
            'adminAssignableUsers' => $users
                ->filter(fn (User $adminUser) => $adminUser->is_admin || $adminUser->isSuperAdmin())
                ->map(fn (User $adminUser) => [
                    'name' => $adminUser->name,
                    'email' => $adminUser->email,
                ])
                ->values(),
            'supportAvailable' => true,
            'supportUsesRuntimeFallback' => ! $supportAvailable,
            'adminSummary' => [
                'users' => $users->count(),
                'sites' => $sites->count(),
                'active_sites' => $sites->filter(fn (Site $site) => $site->licenseActive())->count(),
                'tickets_open' => $tickets->whereIn('status', ['open', 'pending'])->count(),
                'service_leads' => $serviceLeads->count(),
                'service_leads_needing_action' => $serviceLeadActionQueue->count(),
                'service_leads_due_today' => $serviceLeads->where('follow_up_tone', 'good')->count(),
                'service_leads_stuck' => $serviceLeads->where('inactive_bucket', 'stuck')->count(),
                'service_leads_overdue_first_touch' => $serviceLeads->where('first_touch_key', 'overdue')->count(),
                'service_leads_repeat_contacts' => $serviceLeads->filter(fn ($lead) => (int) ($lead->repeat_contact_count ?? 1) > 1)->count(),
                'service_leads_repeat_sites' => $serviceLeads->filter(fn ($lead) => (int) ($lead->repeat_site_count ?? 1) > 1)->count(),
                'service_leads_existing_customers' => $serviceLeads->filter(fn ($lead) => ($lead->relationship_key ?? null) === 'existing_customer')->count(),
                'service_leads_cross_sell' => $serviceLeads->filter(fn ($lead) => (int) ($lead->distinct_service_count ?? 1) > 1)->count(),
                'service_leads_with_recommendation' => $serviceLeads->filter(fn ($lead) => filled($lead->recommended_service_type ?? null))->count(),
                'service_leads_with_playbook' => $serviceLeads->filter(fn ($lead) => filled($lead->playbook_key ?? null))->count(),
            ],
            'platformReadiness' => $this->platformReadiness(),
        ];
    }

    private function serviceLeadNeedsActionNow(object $lead): bool
    {
        return in_array($lead->follow_up_tone ?? null, ['good', 'warn'], true)
            || ($lead->urgency_level ?? null) === 'urgent'
            || ($lead->opportunity_key ?? null) === 'hot'
            || ($lead->freshness_key ?? null) === 'stale'
            || ($lead->inactive_bucket ?? null) === 'stuck';
    }

    private function serviceLeadActionPriority(object $lead): int
    {
        $priority = 0;

        if (($lead->urgency_level ?? null) === 'urgent') {
            $priority += 60;
        }

        if (($lead->follow_up_tone ?? null) === 'good') {
            $priority += 45;
        } elseif (($lead->follow_up_tone ?? null) === 'warn') {
            $priority += 25;
        }

        if (($lead->opportunity_key ?? null) === 'hot') {
            $priority += 35;
        } elseif (($lead->opportunity_key ?? null) === 'warm') {
            $priority += 15;
        }

        if (($lead->freshness_key ?? null) === 'stale') {
            $priority += 20;
        }

        if (($lead->inactive_bucket ?? null) === 'stuck') {
            $priority += 30;
        } elseif (($lead->inactive_bucket ?? null) === 'waiting') {
            $priority += 10;
        }

        if (($lead->status ?? null) === 'new') {
            $priority += 10;
        } elseif (($lead->status ?? null) === 'proposal') {
            $priority += 5;
        }

        if ((int) ($lead->repeat_contact_count ?? 1) > 1) {
            $priority += 20;
        }

        if ((int) ($lead->repeat_site_count ?? 1) > 1) {
            $priority += 12;
        }

        if ((int) ($lead->prior_won_count ?? 0) > 0) {
            $priority += 22;
        }

        if ((int) ($lead->distinct_service_count ?? 1) > 1) {
            $priority += 16;
        }

        return $priority + (int) ($lead->opportunity_score ?? 0);
    }

    private function formatLeadRate(int $part, int $total): string
    {
        if ($total <= 0) {
            return '0%';
        }

        return (string) round(($part / $total) * 100) . '%';
    }

    private function serviceModes(): array
    {
        return [
            'audit_only' => 'בסיסי',
            'audit_and_fix' => 'מורחב',
            'managed_service' => 'מלא',
        ];
    }

    private function collectAdminServiceLeads()
    {
        $serviceLeads = ServiceLead::runtimeLeads()
            ->map(fn (array $lead) => ServiceLead::presentRuntime($lead))
            ->sortByDesc('sort_timestamp')
            ->values();
        $serviceCatalog = $this->serviceCatalog();

        $repeatContactCounts = $serviceLeads
            ->flatMap(function ($lead) {
                $keys = array_filter([
                    $this->normalizeLeadEmailKey($lead->contact_email ?? $lead->user_email ?? null),
                    $this->normalizeLeadPhoneKey($lead->contact_phone ?? null),
                ]);

                return collect(array_unique($keys))->map(fn ($key) => ['key' => $key, 'lead' => $lead]);
            })
            ->groupBy('key')
            ->map(fn ($group) => $group->pluck('lead.update_key')->unique()->count());

        $repeatSiteCounts = $serviceLeads
            ->mapWithKeys(function ($lead) {
                $key = $this->normalizeLeadSiteKey($lead->site_domain ?? null);

                return $key ? [$lead->update_key => $key] : [];
            });

        $repeatSiteCounts = $repeatSiteCounts
            ->groupBy(fn ($siteKey) => $siteKey)
            ->map(fn ($group) => $group->count());

        return $serviceLeads
            ->map(function ($lead) use ($repeatContactCounts, $repeatSiteCounts) {
                $lead = clone $lead;

                $contactKeys = array_filter([
                    $this->normalizeLeadEmailKey($lead->contact_email ?? $lead->user_email ?? null),
                    $this->normalizeLeadPhoneKey($lead->contact_phone ?? null),
                ]);
                $contactCount = collect(array_unique($contactKeys))
                    ->map(fn ($key) => (int) ($repeatContactCounts[$key] ?? 1))
                    ->max() ?: 1;

                $siteCount = 1;
                $siteKey = $this->normalizeLeadSiteKey($lead->site_domain ?? null);
                if ($siteKey) {
                    $siteCount = (int) ($repeatSiteCounts[$siteKey] ?? 1);
                }

                $lead->repeat_contact_count = $contactCount;
                $lead->repeat_site_count = $siteCount;
                $lead->repeat_contact_label = $contactCount > 1 ? 'פנייה חוזרת · ' . $contactCount . ' פניות' : null;
                $lead->repeat_site_label = $siteCount > 1 ? 'אתר חוזר · ' . $siteCount . ' פניות' : null;
                $lead->repeat_contact_tone = $contactCount > 1 ? 'warn' : 'neutral';
                $lead->repeat_site_tone = $siteCount > 1 ? 'warn' : 'neutral';

                $leadTags = collect($lead->lead_tags ?? []);
                if ($contactCount > 1) {
                    $leadTags->push([
                        'label' => 'פנייה חוזרת',
                        'tone' => 'warn',
                    ]);
                }
                if ($siteCount > 1) {
                    $leadTags->push([
                        'label' => 'אתר חוזר',
                        'tone' => 'neutral',
                    ]);
                }
                $lead->lead_tags = $leadTags
                    ->unique(fn ($tag) => ($tag['label'] ?? '') . '|' . ($tag['tone'] ?? ''))
                    ->values()
                    ->all();

                return $lead;
            })
            ->map(function ($lead) use ($serviceLeads, $serviceCatalog) {
                $lead = clone $lead;

                $contactKeySet = array_filter([
                    $this->normalizeLeadEmailKey($lead->contact_email ?? $lead->user_email ?? null),
                    $this->normalizeLeadPhoneKey($lead->contact_phone ?? null),
                ]);
                $siteKey = $this->normalizeLeadSiteKey($lead->site_domain ?? null);

                $relatedLeads = $serviceLeads
                    ->filter(function ($candidate) use ($lead, $contactKeySet, $siteKey) {
                        if (($candidate->update_key ?? null) === ($lead->update_key ?? null)) {
                            return false;
                        }

                        $candidateContactKeys = collect([
                            $this->normalizeLeadEmailKey($candidate->contact_email ?? $candidate->user_email ?? null),
                            $this->normalizeLeadPhoneKey($candidate->contact_phone ?? null),
                        ])->filter();

                        $candidateMatchesContact = $candidateContactKeys->intersect($contactKeySet)->isNotEmpty();
                        $candidateMatchesSite = $siteKey && $this->normalizeLeadSiteKey($candidate->site_domain ?? null) === $siteKey;

                        return $candidateMatchesContact || $candidateMatchesSite;
                    })
                    ->values();

                $currentServiceLabel = $serviceCatalog[$lead->service_type]['label'] ?? $lead->service_type;
                $relatedServiceLabels = $relatedLeads
                    ->pluck('service_type')
                    ->filter()
                    ->push($lead->service_type)
                    ->unique()
                    ->map(fn ($serviceType) => $serviceCatalog[$serviceType]['label'] ?? $serviceType)
                    ->values();

                $crossSellLabels = $relatedServiceLabels
                    ->reject(fn ($label) => $label === $currentServiceLabel)
                    ->values();

                $priorWonCount = $relatedLeads->where('status', 'won')->count();

                $lead->distinct_service_count = $relatedServiceLabels->count();
                $lead->related_service_labels = $relatedServiceLabels->all();
                $lead->related_services_label = $crossSellLabels->isNotEmpty()
                    ? 'ביקש גם: ' . $crossSellLabels->take(3)->implode(' · ')
                    : null;
                $lead->prior_won_count = $priorWonCount;
                $lead->recommended_service_type = $this->recommendedNextServiceType(
                    $lead->service_type,
                    $relatedLeads->pluck('service_type')->filter()->push($lead->service_type)->unique()->values()->all(),
                    $priorWonCount
                );
                $lead->recommended_service_label = $lead->recommended_service_type
                    ? ($serviceCatalog[$lead->recommended_service_type]['label'] ?? $lead->recommended_service_type)
                    : null;
                $lead->recommended_contact_channel_key = $this->recommendedLeadContactChannel($lead);
                $lead->recommended_contact_channel_label = match ($lead->recommended_contact_channel_key) {
                    'phone' => 'טלפון',
                    'whatsapp' => 'ווטסאפ',
                    default => 'מייל',
                };
                $playbook = $this->recommendedLeadPlaybook($lead);
                $lead->playbook_key = $playbook['key'];
                $lead->playbook_label = $playbook['label'];
                $lead->playbook_note = $playbook['note'];
                $lead->opening_line = $this->buildLeadOpeningLine($lead, $serviceCatalog);

                if ($priorWonCount > 0) {
                    $lead->relationship_key = 'existing_customer';
                    $lead->relationship_label = 'לקוח קיים · ' . $priorWonCount . ' זכיות קודמות';
                    $lead->relationship_tone = 'good';
                } elseif (($lead->repeat_contact_count ?? 1) > 1) {
                    $lead->relationship_key = 'returning_contact';
                    $lead->relationship_label = 'קשר חוזר';
                    $lead->relationship_tone = 'warn';
                } elseif (($lead->repeat_site_count ?? 1) > 1) {
                    $lead->relationship_key = 'returning_site';
                    $lead->relationship_label = 'אתר חוזר';
                    $lead->relationship_tone = 'neutral';
                } else {
                    $lead->relationship_key = 'new_relationship';
                    $lead->relationship_label = 'קשר חדש';
                    $lead->relationship_tone = 'neutral';
                }

                $leadTags = collect($lead->lead_tags ?? []);
                if ($priorWonCount > 0) {
                    $leadTags->push([
                        'label' => 'לקוח קיים',
                        'tone' => 'good',
                    ]);
                }
                if ($crossSellLabels->isNotEmpty()) {
                    $leadTags->push([
                        'label' => 'Cross-sell',
                        'tone' => 'warn',
                    ]);
                }
                $lead->lead_tags = $leadTags
                    ->unique(fn ($tag) => ($tag['label'] ?? '') . '|' . ($tag['tone'] ?? ''))
                    ->values()
                    ->all();

                return $lead;
            })
            ->values();
    }

    private function normalizeLeadEmailKey(?string $email): ?string
    {
        $email = strtolower(trim((string) ($email ?? '')));

        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }

    private function normalizeLeadPhoneKey(?string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) ($phone ?? ''));

        return strlen($digits) >= 7 ? $digits : null;
    }

    private function normalizeLeadSiteKey(?string $siteDomain): ?string
    {
        $siteDomain = trim((string) ($siteDomain ?? ''));

        if ($siteDomain === '') {
            return null;
        }

        if (! str_starts_with($siteDomain, 'http://') && ! str_starts_with($siteDomain, 'https://')) {
            $siteDomain = 'https://' . ltrim($siteDomain, '/');
        }

        $host = parse_url($siteDomain, PHP_URL_HOST);
        if (! is_string($host) || $host === '') {
            return null;
        }

        return strtolower(preg_replace('/^www\./', '', $host));
    }

    private function recommendedNextServiceType(string $currentServiceType, array $seenServiceTypes, int $priorWonCount): ?string
    {
        $seen = collect($seenServiceTypes)->filter()->unique()->values();

        $paths = [
            'hosting' => ['maintenance', 'website_upgrade', 'seo'],
            'maintenance' => ['hosting', 'website_upgrade', 'seo'],
            'website_upgrade' => ['seo', 'campaigns', 'hosting'],
            'seo' => ['campaigns', 'landing_pages', 'automations'],
            'campaigns' => ['landing_pages', 'automations', 'seo'],
            'landing_pages' => ['campaigns', 'automations', 'seo'],
            'automations' => ['campaigns', 'seo', 'hosting'],
            'ecosystem_access' => ['hosting', 'seo', 'campaigns'],
        ];

        $candidates = $paths[$currentServiceType] ?? ['website_upgrade', 'seo', 'hosting'];

        if ($priorWonCount > 0) {
            array_unshift($candidates, 'automations');
        }

        foreach (collect($candidates)->unique() as $candidate) {
            if (! $seen->contains($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function recommendedLeadContactChannel(object $lead): string
    {
        if (($lead->urgency_level ?? null) === 'urgent' && filled($lead->contact_phone ?? null)) {
            return ($lead->preferred_contact_key ?? $lead->preferred_contact ?? null) === 'whatsapp'
                ? 'whatsapp'
                : 'phone';
        }

        if (($lead->relationship_key ?? null) === 'existing_customer' && filled($lead->contact_phone ?? null)) {
            return ($lead->preferred_contact_key ?? $lead->preferred_contact ?? null) === 'phone'
                ? 'phone'
                : 'whatsapp';
        }

        if (($lead->preferred_contact_key ?? $lead->preferred_contact ?? null) === 'phone' && filled($lead->contact_phone ?? null)) {
            return 'phone';
        }

        if (($lead->preferred_contact_key ?? $lead->preferred_contact ?? null) === 'whatsapp' && filled($lead->contact_phone ?? null)) {
            return 'whatsapp';
        }

        return 'email';
    }

    private function recommendedLeadPlaybook(object $lead): array
    {
        if (($lead->service_type ?? null) === 'ecosystem_access') {
            return [
                'key' => 'nurture_track',
                'label' => 'חימום ועדכון',
                'note' => 'להכניס לרשימת המתנה, לשלוח עדכון קצר ולבדוק עניין מחודש בהמשך.',
            ];
        }

        if (($lead->relationship_key ?? null) === 'existing_customer' || (int) ($lead->distinct_service_count ?? 1) > 1) {
            return [
                'key' => 'upsell_bundle',
                'label' => 'הצעת המשך / הרחבה',
                'note' => 'להציג המשך הגיוני לשירות קיים או חבילה רחבה יותר במקום להתחיל מאפס.',
            ];
        }

        if (in_array(($lead->service_type ?? null), ['website_upgrade', 'hosting', 'maintenance'], true)) {
            return [
                'key' => 'technical_review',
                'label' => 'בדיקה טכנית קצרה',
                'note' => 'להתחיל באבחון טכני קצר של האתר ואז להציע טיפול/שדרוג מדויק.',
            ];
        }

        if (in_array(($lead->service_type ?? null), ['seo', 'campaigns', 'landing_pages', 'automations'], true)
            && in_array(($lead->urgency_level ?? null), ['urgent', 'high'], true)
        ) {
            return [
                'key' => 'quick_quote',
                'label' => 'הצעה מהירה',
                'note' => 'הלקוח כנראה כבר בשל. עדיף לשלוח כיוון מהיר ותמחור ראשוני במקום להכביד.',
            ];
        }

        return [
            'key' => 'discovery_call',
            'label' => 'שיחת גילוי קצרה',
            'note' => 'להבין צרכים, תקציב ודחיפות לפני הצעה מסודרת.',
        ];
    }

    private function buildLeadOpeningLine(object $lead, array $serviceCatalog): string
    {
        $name = trim((string) ($lead->contact_name ?? $lead->user_name ?? ''));
        $firstName = $name !== '' ? preg_split('/\s+/u', $name)[0] : 'היי';
        $serviceLabel = $serviceCatalog[$lead->service_type]['label'] ?? 'השירות';
        $goal = trim((string) ($lead->goal ?? ''));
        $goalSnippet = $goal !== '' ? ' לגבי ' . $goal : '';

        if (($lead->service_type ?? null) === 'ecosystem_access') {
            return $firstName . ', תודה על העניין במוצרים הבאים של Brndini. רציתי לעדכן איך נוכל לצרף אותך מוקדם ולבדוק מה הכי רלוונטי עבורך בהמשך.';
        }

        if (($lead->relationship_key ?? null) === 'existing_customer') {
            return $firstName . ', ראיתי שחזרת אלינו הפעם סביב ' . $serviceLabel . $goalSnippet . '. יש לנו כבר היכרות טובה, אז אפשר להתקדם מהר ולבדוק אם נכון להרחיב את מה שכבר בנינו.';
        }

        if (($lead->playbook_key ?? null) === 'technical_review') {
            return $firstName . ', תודה על הפנייה לגבי ' . $serviceLabel . $goalSnippet . '. לפני שמציעים כיוון, הייתי פותח איתך בדיקה טכנית קצרה כדי להבין מה הכי נכון לאתר שלך.';
        }

        if (($lead->playbook_key ?? null) === 'quick_quote') {
            return $firstName . ', ראיתי את הפנייה לגבי ' . $serviceLabel . $goalSnippet . '. כדי לחסוך זמן, אפשר לשלוח לך כבר כיוון ראשוני והצעת מסגרת מהירה להמשך.';
        }

        if (($lead->playbook_key ?? null) === 'upsell_bundle') {
            return $firstName . ', ראיתי שהפנייה הנוכחית נוגעת ל־' . $serviceLabel . $goalSnippet . '. נראה שיש פה מקום להרחבה חכמה למה שכבר ביקשת או למה שכבר עשינו יחד.';
        }

        return $firstName . ', תודה על הפנייה לגבי ' . $serviceLabel . $goalSnippet . '. אשמח לעשות איתך שיחת היכרות קצרה כדי להבין מה הכי נכון לצעד הבא.';
    }

    private function siteColumnsAvailable(array $columns): bool
    {
        return Site::columnsAvailable($columns);
    }

    private function ensureSuperAdmin(User $user): void
    {
        abort_unless($user->isSuperAdmin(), 403);
    }

    private function supportsMultipleSites(): bool
    {
        if (! Site::tableAvailable() || ! Schema::hasColumn('sites', 'user_id')) {
            return false;
        }

        if ($this->siteUserUniqueConstraintExists()) {
            return false;
        }

        if (! Schema::hasTable('migrations')) {
            return true;
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

    private function serviceCatalog(): array
    {
        return [
            'hosting' => [
                'label' => 'אחסון וניהול שרת',
                'description' => 'מעבר לאחסון יציב, תחזוקה שוטפת, גיבויים וניהול תשתית במקום אחד.',
                'highlights' => ['אחסון מהיר', 'גיבויים', 'ניהול שרת', 'זמינות גבוהה'],
            ],
            'seo' => [
                'label' => 'SEO וקידום אורגני',
                'description' => 'שיפור מהירות, היררכיית תוכן, מטא, מאמרים ועמודי נחיתה לצמיחה אורגנית.',
                'highlights' => ['מחקר מילות מפתח', 'תוכן', 'אופטימיזציה', 'דוחות'],
            ],
            'campaigns' => [
                'label' => 'קמפיינים ופרסום',
                'description' => 'ניהול קמפיינים, דפי נחיתה, מדידה ושיפור המרות סביב העסק שלך.',
                'highlights' => ['Meta', 'Google Ads', 'דפי נחיתה', 'מדידה'],
            ],
            'maintenance' => [
                'label' => 'תחזוקת אתר',
                'description' => 'עדכונים, שיפורים, תיקונים שוטפים ושקט תפעולי לאתר הקיים שלך.',
                'highlights' => ['עדכונים', 'תיקונים', 'שיפורים', 'זמינות'],
            ],
            'website_upgrade' => [
                'label' => 'שדרוג אתר קיים',
                'description' => 'ריענון עיצוב, שיפור חוויית משתמש, מהירות והמרה בלי להתחיל מאפס.',
                'highlights' => ['עיצוב', 'UX', 'ביצועים', 'המרה'],
            ],
            'landing_pages' => [
                'label' => 'דפי נחיתה',
                'description' => 'דפים ממירים לקמפיינים, מבצעים או מוצרים חדשים עם חיבור למדידה.',
                'highlights' => ['עיצוב ממיר', 'CTA', 'מדידה', 'טפסים'],
            ],
            'automations' => [
                'label' => 'אוטומציות ותהליכים',
                'description' => 'חיבורים, CRM, אוטומציות שיווק וייעול תהליכים פנימיים.',
                'highlights' => ['CRM', 'מיילים', 'אינטגרציות', 'תהליכים'],
            ],
            'ecosystem_access' => [
                'label' => 'גישה מוקדמת לכלי Brndini הבאים',
                'description' => 'הצטרפות מוקדמת לרשימת המתעניינים בכלים הבאים של Brndini: ניתוח אתרים, אוטומציות, SEO וכלי צמיחה נוספים.',
                'highlights' => ['גישה מוקדמת', 'רשימת המתנה', 'מוצרים חדשים', 'עדכונים'],
            ],
        ];
    }

    private function servicePreferredContactLabels(): array
    {
        return [
            'email' => 'אימייל',
            'phone' => 'טלפון',
            'whatsapp' => 'ווטסאפ',
        ];
    }

    private function serviceLeadStatusLabels(): array
    {
        return [
            'new' => 'חדש',
            'contacted' => 'נוצר קשר',
            'qualified' => 'רלוונטי',
            'proposal' => 'נשלחה הצעה',
            'won' => 'נסגר כלקוח',
            'closed' => 'נסגר',
        ];
    }

    private function serviceRecommendationsForSite(
        Site $site,
        array $installationSignal,
        int $openAlertsCount,
        bool $statementConnected,
        bool $billingActive
    ): array {
        $catalog = $this->serviceCatalog();
        $candidates = [];

        if ($installationSignal['status'] !== 'installed') {
            $candidates[] = [
                'service_type' => 'maintenance',
                'title' => 'צריך יד טכנית על האתר?',
                'reason' => 'הווידג׳ט עדיין לא זוהה באתר, וזה בדרך כלל סימן שחסרה עבודה טכנית באתר עצמו.',
                'cta' => 'לבקש תחזוקת אתר',
            ];
        }

        if ($openAlertsCount > 0 || ! $statementConnected) {
            $candidates[] = [
                'service_type' => 'website_upgrade',
                'title' => 'רוצה לשפר את האתר כולו?',
                'reason' => 'יש כמה נקודות פתוחות במערכת. לפעמים זה הזמן לרענן את האתר, את התוכן ואת חוויית המשתמש.',
                'cta' => 'לבקש שדרוג אתר',
            ];
        }

        if (! $billingActive || $site->licenseStatus() !== 'active') {
            $candidates[] = [
                'service_type' => 'hosting',
                'title' => 'מחפש תשתית יותר יציבה?',
                'reason' => 'אחסון ותחזוקת שרת מסודרים יכולים להוריד הרבה חיכוך תפעולי ולשמור על אתר יציב יותר.',
                'cta' => 'לבקש פרטי אחסון',
            ];
        }

        if ($installationSignal['status'] === 'installed' && $openAlertsCount <= 1) {
            $candidates[] = [
                'service_type' => 'seo',
                'title' => 'האתר כבר חי. רוצה יותר תנועה?',
                'reason' => 'אחרי שהבסיס הטכני במקום, אפשר להשתמש באתר כדי לייצר יותר חשיפות, תוכן ולידים.',
                'cta' => 'לבקש שירותי SEO',
            ];
            $candidates[] = [
                'service_type' => 'campaigns',
                'title' => 'מוכן לקמפיינים והמרות?',
                'reason' => 'אם האתר כבר זמין ועובד טוב, אפשר להוסיף דפי נחיתה, קמפיינים ומדידה מסודרת.',
                'cta' => 'לבקש קמפיינים',
            ];
        }

        $fallbackOrder = ['maintenance', 'website_upgrade', 'hosting', 'seo', 'campaigns', 'landing_pages', 'automations', 'ecosystem_access'];

        foreach ($fallbackOrder as $serviceType) {
            if (count($candidates) >= 3) {
                break;
            }

            $alreadyIncluded = collect($candidates)->contains(fn (array $candidate) => $candidate['service_type'] === $serviceType);
            if ($alreadyIncluded || ! isset($catalog[$serviceType])) {
                continue;
            }

            $candidates[] = [
                'service_type' => $serviceType,
                'title' => 'שירות נוסף של Brndini',
                'reason' => $catalog[$serviceType]['description'],
                'cta' => 'לשלוח פנייה',
            ];
        }

        return collect($candidates)
            ->take(3)
            ->map(function (array $candidate) use ($catalog) {
                $meta = $catalog[$candidate['service_type']] ?? null;

                return [
                    'service_type' => $candidate['service_type'],
                    'label' => $meta['label'] ?? $candidate['service_type'],
                    'description' => $meta['description'] ?? '',
                    'highlights' => array_slice($meta['highlights'] ?? [], 0, 3),
                    'title' => $candidate['title'],
                    'reason' => $candidate['reason'],
                    'cta' => $candidate['cta'],
                ];
            })
            ->values()
            ->all();
    }

    private function installationSignal(Site $site): array
    {
        return $site->installationSignal();
    }

    private function recommendedPlanForSite(Site $site, int $featureCount): array
    {
        if ($site->licenseActive() && $site->billingPlan() === 'premium') {
            return [
                'name' => 'פרימיום פעיל',
                'description' => 'האתר כבר מחובר למסלול הפרימיום ופתוח לכל היכולות המתקדמות.',
            ];
        }

        if ($site->licenseActive() && $site->billingPlan() === 'free') {
            return [
                'name' => 'שדרוג לפרימיום',
                'description' => 'הבסיס כבר פעיל. השדרוג הבא יפתח את הכלים המתקדמים, הפרופילים וההתאמות המורחבות.',
            ];
        }

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

        $licenseActive = $site->licenseActive();
        $widgetInstalled = $installationSignal['status'] === 'installed';
        $widgetSeenButStale = $installationSignal['status'] === 'stale';
        $statementConnected = $this->siteHasStatement($site);
        $billingActive = $site->billingActive();

        $checks[] = $this->buildCheck(
            'הטמעה באתר',
            $widgetInstalled ? 'pass' : ($widgetSeenButStale ? 'warn' : 'fail'),
            $widgetInstalled
                ? 'הווידג׳ט זוהה בפועל באתר והפלטפורמה קיבלה טעינה אמיתית מה־site key הזה.'
                : ($widgetSeenButStale
                    ? 'הווידג׳ט זוהה בעבר, אבל לא התקבלה טעינה חדשה לאחרונה. כדאי לבדוק שהוא עדיין מוטמע ונטען.'
                    : 'עדיין לא זוהתה טעינה אמיתית של הווידג׳ט באתר. צריך להטמיע את הקוד ואז לרענן את האתר.')
        );

        $score += $widgetInstalled ? 12 : ($widgetSeenButStale ? -8 : -26);

        if (! $widgetInstalled) {
            $alerts[] = $this->buildAlert(
                'install',
                $widgetSeenButStale ? 'הווידג׳ט לא זוהה לאחרונה' : 'הווידג׳ט עדיין לא הוטמע',
                $widgetSeenButStale ? 'medium' : 'high',
                $widgetSeenButStale
                    ? 'הווידג׳ט זוהה בעבר, אבל הפלטפורמה לא ראתה טעינה חדשה לאחרונה. כדאי לבדוק שהקוד עדיין מוטמע ושהאתר נטען כרגיל.'
                    : 'לפני שמסתמכים על ציון או על מצב האתר, צריך קודם להדביק את קוד ההטמעה באתר ולוודא שהווידג׳ט נטען בפועל.'
            );
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
            $alerts[] = $this->buildAlert('statement', 'חסרה הצהרת נגישות', 'high', 'מומלץ לחבר עמוד הצהרה כדי להציג לגולשים מידע ברור ופרטי קשר רלוונטיים.');
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
                ? 'החבילה מסומנת כפעילה עם מסלול ' . ($site->billingPlanMeta()['label'] ?? $site->billingPlan()) . '.'
                : 'האתר עדיין לא עבר הפעלה מלאה במסלול שנבחר.'
        );

        $score += $billingActive ? 10 : 2;

        $lastAuditedAt = $site->lastAuditedAt();
        $hasRecentAudit = $lastAuditedAt && $lastAuditedAt->gt(Carbon::now()->subDays(21));

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

        if ($lastAuditedAt && $site->updated_at && $site->updated_at->gt($lastAuditedAt) && $alertSettings['sync']) {
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
            'healthy' => 'האתר הזה נראה במצב טוב. נשאר רק להמשיך לעקוב אחרי שינויים ולעדכן בדיקות לפי הצורך.',
            'monitoring' => 'הבסיס חזק, אבל עדיין יש ' . $alertCount . ' נקודות שכדאי לעקוב אחריהן כדי לשמור על התקנה יציבה וברורה.',
            default => 'יש פעולות פתוחות שצריך להשלים לפני שאפשר להציג את האתר הזה כסט אפ בריא ויציב. ברוב המקרים זה מתחיל מהשלמת ההטמעה באתר עצמו.',
        };
    }

    private function statementOrganizationTypes(): array
    {
        return [
            'business' => 'עסק / חברה',
            'agency' => 'סוכנות / סטודיו',
            'ecommerce' => 'מסחר אלקטרוני',
            'public' => 'גוף ציבורי / מוניציפלי',
            'nonprofit' => 'עמותה / ארגון חברתי',
            'content' => 'אתר תוכן / מגזין',
        ];
    }

    private function statementServiceScopes(): array
    {
        return [
            'website_only' => 'אתר ציבורי בלבד',
            'website_account' => 'אתר ציבורי + אזור אישי / טפסים',
            'managed_service' => 'אתר ציבורי + אזור אישי + חלקים מתקדמים',
        ];
    }

    private function statementResponseTimes(): array
    {
        return [
            '2_business_days' => 'עד שני ימי עסקים',
            '5_business_days' => 'עד חמישה ימי עסקים',
            'custom' => 'מענה מותאם לפי סוג הפנייה',
        ];
    }

    private function statementBuilderDefaults(Site $site, ?User $user = null): array
    {
        $owner = $user ?? $site->user;

        return [
            'organization_name' => $site->site_name ?: ($owner?->name ?? 'הארגון'),
            'organization_type' => 'business',
            'website_purpose' => 'מתן מידע, שירותים דיגיטליים וגישה נוחה לתוכן וליצירת קשר.',
            'service_scope' => 'website_only',
            'accessibility_owner' => $owner?->name ?? '',
            'contact_email' => $owner?->contact_email ?: ($owner?->email ?? ''),
            'contact_phone' => '',
            'contact_form_url' => '',
            'response_time' => '2_business_days',
            'last_reviewed_at' => now()->format('Y-m-d'),
            'testing_manual_keyboard' => true,
            'testing_screen_reader' => true,
            'testing_automation' => true,
            'testing_content_review' => true,
            'physical_location_accessible' => false,
            'accessible_parking' => false,
            'additional_arrangements' => '',
            'known_limitations' => '',
            'additional_note' => '',
        ];
    }

    private function statementBuilderData(Site $site, ?User $user = null): array
    {
        return $site->statementBuilderData($this->statementBuilderDefaults($site, $user));
    }

    private function statementUrlForSite(Site $site): ?string
    {
        return $site->statementUrlValue();
    }

    private function siteHasStatement(Site $site): bool
    {
        return filled($this->statementUrlForSite($site));
    }

    private function buildStatementPreview(Site $site, array $statement): array
    {
        $organizationTypes = $this->statementOrganizationTypes();
        $serviceScopes = $this->statementServiceScopes();
        $responseTimes = $this->statementResponseTimes();

        $testingMethods = collect([
            $statement['testing_manual_keyboard'] ? 'בדיקות מקלדת וזרימת פוקוס' : null,
            $statement['testing_screen_reader'] ? 'בדיקות עם קוראי מסך וכלי עזר' : null,
            $statement['testing_automation'] ? 'בדיקות אוטומטיות וכלי סריקה' : null,
            $statement['testing_content_review'] ? 'סקירה ידנית של תוכן, קישורים וטפסים' : null,
        ])->filter()->values()->all();

        $locationSummary = $statement['physical_location_accessible']
            ? 'קיימים גם הסדרי נגישות פיזיים' . ($statement['accessible_parking'] ? ', כולל חניה נגישה' : '') . '.'
            : 'הצהרה זו מתמקדת כרגע בנגישות האתר והשירותים הדיגיטליים.';

        if ($statement['additional_arrangements'] !== '') {
            $locationSummary .= ' ' . $statement['additional_arrangements'];
        }

        $contactChannels = collect([
            filled($statement['contact_email']) ? 'בדוא״ל: ' . $statement['contact_email'] : null,
            filled($statement['contact_phone']) ? 'בטלפון: ' . $statement['contact_phone'] : null,
            filled($statement['contact_form_url']) ? 'בטופס יצירת קשר: ' . $statement['contact_form_url'] : null,
        ])->filter()->values()->all();

        return [
            'title' => 'הצהרת נגישות עבור ' . $statement['organization_name'],
            'summary' => $statement['organization_name'] . ' פועלת להנגשת האתר ' . (parse_url($site->domain, PHP_URL_HOST) ?: $site->domain) . ' ולשיפור חוויית השימוש לכלל המשתמשים.',
            'badges' => [
                $organizationTypes[$statement['organization_type']] ?? 'ארגון',
                $serviceScopes[$statement['service_scope']] ?? 'אתר ציבורי בלבד',
                $responseTimes[$statement['response_time']] ?? 'עד שני ימי עסקים',
            ],
            'sections' => [
                [
                    'title' => 'מחויבות לנגישות',
                    'body' => $statement['organization_name'] . ' רואה חשיבות רבה בהנגשת האתר ומתייחסת לנגישות כחלק בלתי נפרד מחוויית השירות. אנו פועלים לשפר באופן שוטף את השימושיות, הקריאות, הניווט וההתאמה לטכנולוגיות מסייעות.',
                ],
                [
                    'title' => 'היקף השירות והאתר',
                    'body' => 'האתר נועד עבור ' . ($statement['website_purpose'] !== '' ? $statement['website_purpose'] : 'הצגת מידע ושירותים דיגיטליים') . ' היקף השירות הנוכחי הוא: ' . ($serviceScopes[$statement['service_scope']] ?? 'אתר ציבורי בלבד') . '.',
                ],
                [
                    'title' => 'בדיקות והתאמות שבוצעו',
                    'body' => $testingMethods === []
                        ? 'בוצעו התאמות נגישות שוטפות, אך חשוב להמשיך בבדיקות ועדכונים בהתאם לשינויים באתר.'
                        : 'במהלך העבודה בוצעו או מבוצעות באופן שוטף ההתאמות והבדיקות הבאות: ' . implode(' · ', $testingMethods) . '.',
                ],
                [
                    'title' => 'יצירת קשר ומשוב',
                    'body' => 'אם נתקלת בקושי, נשמח שתעדכן אותנו כדי שנוכל לבדוק ולטפל. ניתן ליצור קשר ' . ($contactChannels === [] ? 'באמצעי הקשר שמופיעים באתר' : implode(' · ', $contactChannels)) . '. זמן המענה הצפוי: ' . ($responseTimes[$statement['response_time']] ?? 'עד שני ימי עסקים') . '.',
                ],
                [
                    'title' => 'הסדרים נוספים',
                    'body' => $locationSummary,
                ],
                [
                    'title' => 'מגבלות ידועות והערות',
                    'body' => $statement['known_limitations'] !== ''
                        ? $statement['known_limitations']
                        : 'למרות המאמצים להנגיש את כלל חלקי האתר, ייתכן שחלקים מסוימים עדיין דורשים שיפור או התאמה נוספת. אם מצאת רכיב שאינו נגיש, נשמח לקבל משוב ולפעול לתיקון.',
                ],
                [
                    'title' => 'מידע נוסף',
                    'body' => $statement['additional_note'] !== ''
                        ? $statement['additional_note']
                        : 'הצהרה זו עודכנה כחלק מתהליך ניהול הנגישות השוטף של האתר.',
                ],
            ],
            'last_reviewed_label' => Carbon::parse($statement['last_reviewed_at'])->format('d.m.Y'),
        ];
    }

    private function persistSitePayload(Site $site, array $payload): void
    {
        $site->persistPayload($payload);
    }

    private function applySiteRuntimeOverrides(Site $site): Site
    {
        return $site->loadRuntimeOverrides();
    }

    private function persistUserPayload(User $user, array $payload): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        $persisted = collect($payload)
            ->filter(fn ($_value, $column) => Schema::hasColumn('users', $column))
            ->all();

        if ($persisted === []) {
            return;
        }

        User::query()->whereKey($user->id)->update($persisted);
        $user->forceFill($persisted);
        $user->syncOriginalAttributes(array_keys($persisted));
    }

    private function siteUserUniqueConstraintExists(): bool
    {
        try {
            return match (DB::getDriverName()) {
                'mysql' => ! empty(DB::select(
                    "SHOW INDEX FROM sites WHERE Key_name = 'sites_user_id_unique'"
                )),
                'sqlite' => collect(DB::select("PRAGMA index_list('sites')"))
                    ->contains(fn ($index) => ($index->name ?? null) === 'sites_user_id_unique'),
                default => ! Schema::hasTable('migrations')
                    || ! DB::table('migrations')
                        ->where('migration', '2026_03_31_000005_allow_multiple_sites_per_user')
                        ->exists(),
            };
        } catch (Throwable $exception) {
            report($exception);

            return ! Schema::hasTable('migrations')
                || ! DB::table('migrations')
                    ->where('migration', '2026_03_31_000005_allow_multiple_sites_per_user')
                    ->exists();
        }
    }

    private function platformReadiness(): array
    {
        $checks = [
            'ריבוי אתרים לחשבון' => $this->supportsMultipleSites(),
            'חיוב, בדיקות והתראות' => $this->siteColumnsAvailable(['billing_settings', 'audit_snapshot', 'alert_settings']),
            'זיהוי הטמעה באתר' => $this->siteColumnsAvailable(['last_seen_at', 'last_seen_url']),
            'מרכז תמיכה' => SupportTicket::tableAvailable(),
            'קודי מעקב גלובליים' => Schema::hasTable('app_settings'),
        ];

        $missing = collect($checks)
            ->filter(fn (bool $ready) => ! $ready)
            ->keys()
            ->values()
            ->all();

        return [
            'ready' => $missing === [],
            'checks' => $checks,
            'missing' => $missing,
            'summary' => $missing === []
                ? 'כל רכיבי הליבה של הפלטפורמה זמינים ושומרים למסד כרגיל.'
                : 'יש עדיין רכיבים שרצים במצב גיבוי עד שהשרת ישלים מיגרציות: ' . implode(' · ', $missing),
        ];
    }

}
