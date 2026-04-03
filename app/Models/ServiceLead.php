<?php

namespace App\Models;

use App\Support\RuntimeStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class ServiceLead extends Model
{
    protected $fillable = [
        'user_id',
        'site_id',
        'reference_code',
        'service_type',
        'contact_name',
        'contact_email',
        'contact_phone',
        'goal',
        'message',
        'business_type',
        'team_size',
        'timeframe',
        'budget_range',
        'urgency_level',
        'callback_window',
        'preferred_contact',
        'status',
        'source',
        'entry_point',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'referrer_url',
        'internal_note',
        'follow_up_at',
        'last_activity_at',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    public static function tableAvailable(): bool
    {
        return Schema::hasTable('service_leads');
    }

    public static function runtimeScope(): string
    {
        return 'service-leads';
    }

    public static function runtimeLeads(): Collection
    {
        $leads = RuntimeStore::get(static::runtimeScope(), 'items', []);

        return collect(is_array($leads) ? $leads : []);
    }

    public static function storeRuntime(User $user, Site $site, array $validated): void
    {
        $scope = static::runtimeScope();
        $nextId = (int) RuntimeStore::get($scope, 'next_id', 1);
        $now = Carbon::now();

        $lead = [
            'id' => $nextId,
            'key' => 'service-' . $nextId,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'site_id' => $site->id,
            'site_name' => $site->site_name,
            'site_domain' => $site->domain,
            'reference_code' => 'BRN-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT),
            'service_type' => $validated['service_type'],
            'contact_name' => $user->name,
            'contact_email' => $user->email,
            'contact_phone' => static::normalizePhone($validated['contact_phone'] ?? null),
            'goal' => trim((string) $validated['goal']),
            'message' => trim((string) $validated['message']),
            'business_type' => static::normalizeText($validated['business_type'] ?? null),
            'team_size' => static::normalizeText($validated['team_size'] ?? null),
            'timeframe' => static::normalizeText($validated['timeframe'] ?? null),
            'budget_range' => static::normalizeText($validated['budget_range'] ?? null),
            'urgency_level' => static::normalizeText($validated['urgency_level'] ?? null),
            'callback_window' => static::normalizeText($validated['callback_window'] ?? null),
            'preferred_contact' => $validated['preferred_contact'],
            'status' => 'new',
            'source' => 'dashboard',
            'entry_point' => $validated['entry_point'] ?? 'dashboard-services',
            'utm_source' => null,
            'utm_medium' => null,
            'utm_campaign' => null,
            'referrer_url' => null,
            'internal_note' => '',
            'created_at' => $now->toIso8601String(),
            'last_activity_at' => $now->toIso8601String(),
        ];

        RuntimeStore::putMany($scope, [
            'next_id' => $nextId + 1,
            'items' => static::runtimeLeads()->push($lead)->values()->all(),
        ]);
    }

    public static function storePublicRuntime(array $validated): void
    {
        $scope = static::runtimeScope();
        $nextId = (int) RuntimeStore::get($scope, 'next_id', 1);
        $now = Carbon::now();
        $website = trim((string) ($validated['website'] ?? ''));

        $lead = [
            'id' => $nextId,
            'key' => 'service-public-' . $nextId,
            'user_id' => null,
            'user_name' => trim((string) $validated['name']),
            'user_email' => trim((string) $validated['email']),
            'contact_name' => trim((string) $validated['name']),
            'contact_email' => trim((string) $validated['email']),
            'contact_phone' => static::normalizePhone($validated['contact_phone'] ?? null),
            'site_id' => null,
            'site_name' => $website === '' ? 'ליד ציבורי' : $website,
            'site_domain' => $website,
            'reference_code' => 'BRN-P' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT),
            'service_type' => $validated['service_type'],
            'goal' => trim((string) $validated['goal']),
            'message' => trim((string) $validated['message']),
            'business_type' => static::normalizeText($validated['business_type'] ?? null),
            'team_size' => static::normalizeText($validated['team_size'] ?? null),
            'timeframe' => static::normalizeText($validated['timeframe'] ?? null),
            'budget_range' => static::normalizeText($validated['budget_range'] ?? null),
            'urgency_level' => static::normalizeText($validated['urgency_level'] ?? null),
            'callback_window' => static::normalizeText($validated['callback_window'] ?? null),
            'preferred_contact' => $validated['preferred_contact'],
            'status' => 'new',
            'source' => 'public',
            'entry_point' => $validated['entry_point'] ?? 'public-services',
            'utm_source' => static::normalizeText($validated['utm_source'] ?? null),
            'utm_medium' => static::normalizeText($validated['utm_medium'] ?? null),
            'utm_campaign' => static::normalizeText($validated['utm_campaign'] ?? null),
            'referrer_url' => static::normalizeUrl($validated['referrer_url'] ?? null),
            'internal_note' => '',
            'created_at' => $now->toIso8601String(),
            'last_activity_at' => $now->toIso8601String(),
        ];

        RuntimeStore::putMany($scope, [
            'next_id' => $nextId + 1,
            'items' => static::runtimeLeads()->push($lead)->values()->all(),
        ]);
    }

    public static function presentRuntime(array $lead): object
    {
        $lastActivityAt = isset($lead['last_activity_at']) ? Carbon::parse($lead['last_activity_at']) : null;
        $source = $lead['source'] ?? 'dashboard';
        $contactName = $lead['contact_name'] ?? $lead['user_name'] ?? null;
        $contactEmail = $lead['contact_email'] ?? $lead['user_email'] ?? null;
        $contactEmail = filled($contactEmail) ? trim((string) $contactEmail) : null;
        $contactPhone = static::normalizePhone($lead['contact_phone'] ?? null);
        $siteName = $lead['site_name'] ?? null;
        $subject = 'פנייה לגבי ' . ($siteName ?: 'שירותי Brndini');
        $mailTo = $contactEmail
            ? 'mailto:' . rawurlencode($contactEmail) . '?subject=' . rawurlencode($subject)
            : null;
        $phoneDigits = $contactPhone ? preg_replace('/\D+/', '', $contactPhone) : null;
        $whatsAppDigits = $phoneDigits
            ? (str_starts_with($phoneDigits, '0') ? '972' . substr($phoneDigits, 1) : $phoneDigits)
            : null;
        $phoneHref = $phoneDigits ? 'tel:' . $phoneDigits : null;
        $whatsappHref = $whatsAppDigits
            ? 'https://wa.me/' . $whatsAppDigits . '?text=' . rawurlencode('היי, זאת Brndini לגבי הפנייה ' . ($lead['reference_code'] ?? ''))
            : null;
        $preferredContact = $lead['preferred_contact'] ?? 'email';
        $entryPoint = (string) ($lead['entry_point'] ?? ($source === 'public' ? 'public-services' : 'dashboard-services'));
        $missingPreferredContactDetail = in_array($preferredContact, ['phone', 'whatsapp'], true) && ! filled($contactPhone);
        $utmSource = static::normalizeText($lead['utm_source'] ?? null);
        $utmMedium = static::normalizeText($lead['utm_medium'] ?? null);
        $utmCampaign = static::normalizeText($lead['utm_campaign'] ?? null);
        $referrerUrl = static::normalizeUrl($lead['referrer_url'] ?? null);
        $referrerHost = $referrerUrl ? (parse_url($referrerUrl, PHP_URL_HOST) ?: null) : null;
        $businessType = static::normalizeText($lead['business_type'] ?? null);
        $teamSize = static::normalizeText($lead['team_size'] ?? null);
        $timeframe = static::normalizeText($lead['timeframe'] ?? null);
        $budgetRange = static::normalizeText($lead['budget_range'] ?? null);
        $urgencyLevel = static::normalizeText($lead['urgency_level'] ?? null);
        $callbackWindow = static::normalizeText($lead['callback_window'] ?? null);
        $opportunity = static::opportunityMeta(
            (string) ($lead['service_type'] ?? 'general'),
            (string) ($lead['goal'] ?? ''),
            (string) ($lead['message'] ?? ''),
            $contactPhone,
            $contactEmail,
            (string) ($lead['site_domain'] ?? ''),
            $utmSource,
            $utmMedium,
            $utmCampaign,
            $referrerHost,
            $businessType,
            $teamSize,
            $timeframe,
            $budgetRange,
            $urgencyLevel,
            $callbackWindow
        );

        $freshnessKey = 'fresh';
        $freshnessLabel = 'חדש';
        $freshnessTone = 'good';
        $followUpAt = isset($lead['follow_up_at']) && filled($lead['follow_up_at'])
            ? Carbon::parse($lead['follow_up_at'])
            : null;
        $followUpStatus = static::followUpMeta($followUpAt);

        if ($lastActivityAt && $lastActivityAt->lt(now()->subDays(3))) {
            $freshnessKey = 'stale';
            $freshnessLabel = 'דורש חזרה';
            $freshnessTone = 'warn';
        } elseif ($lastActivityAt && $lastActivityAt->lt(now()->subDay())) {
            $freshnessKey = 'aging';
            $freshnessLabel = 'בהמתנה';
            $freshnessTone = 'neutral';
        }

        return (object) [
            'intent_key' => ($lead['service_type'] ?? null) === 'ecosystem_access' ? 'ecosystem' : 'service',
            'intent_label' => ($lead['service_type'] ?? null) === 'ecosystem_access' ? 'עניין במוצרים הבאים' : 'ליד שירות',
            'update_key' => (string) ($lead['key'] ?? ('service-' . ($lead['id'] ?? 'x'))),
            'reference_code' => $lead['reference_code'] ?? 'BRN-RUNTIME',
            'service_type' => $lead['service_type'] ?? 'general',
            'goal' => $lead['goal'] ?? '',
            'message' => $lead['message'] ?? '',
            'business_type' => $businessType,
            'business_type_label' => static::businessTypeOptions()[$businessType] ?? 'לא צוין',
            'team_size' => $teamSize,
            'team_size_label' => static::teamSizeOptions()[$teamSize] ?? 'לא צוין',
            'timeframe' => $timeframe,
            'timeframe_label' => static::timeframeOptions()[$timeframe] ?? 'לא צוין',
            'budget_range' => $budgetRange,
            'budget_range_label' => static::budgetRangeOptions()[$budgetRange] ?? 'לא צוין',
            'urgency_level' => $urgencyLevel,
            'urgency_level_label' => static::urgencyOptions()[$urgencyLevel] ?? 'לא צוין',
            'callback_window' => $callbackWindow,
            'callback_window_label' => static::callbackWindowOptions()[$callbackWindow] ?? 'לא צוין',
            'preferred_contact' => $lead['preferred_contact'] ?? 'email',
            'status' => $lead['status'] ?? 'new',
            'internal_note' => $lead['internal_note'] ?? '',
            'source' => $source,
            'source_label' => static::sourceLabel($source, $entryPoint),
            'entry_point' => $entryPoint,
            'entry_label' => static::entryLabel($entryPoint),
            'utm_source' => $utmSource,
            'utm_medium' => $utmMedium,
            'utm_campaign' => $utmCampaign,
            'referrer_url' => $referrerUrl,
            'referrer_host' => $referrerHost,
            'marketing_label' => static::marketingLabel($utmSource, $utmMedium, $utmCampaign),
            'opportunity_score' => $opportunity['score'],
            'opportunity_key' => $opportunity['key'],
            'opportunity_label' => $opportunity['label'],
            'opportunity_tone' => $opportunity['tone'],
            'user_name' => $lead['user_name'] ?? $contactName,
            'user_email' => $lead['user_email'] ?? $contactEmail,
            'contact_name' => $contactName,
            'contact_email' => $contactEmail,
            'contact_phone' => $contactPhone,
            'site_name' => $siteName,
            'site_domain' => $lead['site_domain'] ?? null,
            'mail_to' => $mailTo,
            'phone_href' => $phoneHref,
            'whatsapp_href' => $whatsappHref,
            'freshness_key' => $freshnessKey,
            'freshness_label' => $freshnessLabel,
            'freshness_tone' => $freshnessTone,
            'follow_up_at' => $followUpAt?->toDateString(),
            'follow_up_label' => $followUpStatus['label'],
            'follow_up_tone' => $followUpStatus['tone'],
            'preferred_contact_key' => $preferredContact,
            'missing_preferred_contact_detail' => $missingPreferredContactDetail,
            'next_step_label' => static::nextStepLabel(
                $lead['status'] ?? 'new',
                $preferredContact,
                $lead['service_type'] ?? null,
                $urgencyLevel
            ),
            'last_activity_label' => $lastActivityAt?->diffForHumans() ?? 'נוצר עכשיו',
            'sort_timestamp' => $lastActivityAt?->timestamp ?? 0,
        ];
    }

    protected static function nextStepLabel(string $status, string $preferredContact, ?string $serviceType, ?string $urgencyLevel): string
    {
        if ($status === 'won') {
            return 'להעביר לטיפול ומסירה';
        }

        if ($status === 'closed') {
            return 'אין פעולה פתוחה כרגע';
        }

        if ($status === 'proposal') {
            return 'לעקוב אחרי ההצעה';
        }

        if ($status === 'qualified') {
            return $serviceType === 'ecosystem_access' ? 'לתייג לרשימת גישה מוקדמת' : 'להכין הצעה מתאימה';
        }

        if ($status === 'contacted') {
            return $serviceType === 'ecosystem_access' ? 'לבדוק עניין במוצר הבא' : 'להבין התאמה עסקית';
        }

        if ($urgencyLevel === 'urgent') {
            return match ($preferredContact) {
                'phone' => 'לחזור עוד היום בטלפון',
                'whatsapp' => 'לחזור עוד היום בווטסאפ',
                default => 'לחזור עוד היום במייל',
            };
        }

        return match ($preferredContact) {
            'phone' => 'לתאם שיחה ראשונית',
            'whatsapp' => 'לחזור בווטסאפ',
            default => $serviceType === 'ecosystem_access' ? 'לשלוח עדכון גישה מוקדמת' : 'לשלוח מייל היכרות',
        };
    }

    public static function sourceOptions(): array
    {
        return [
            'public' => 'האתר הציבורי',
            'dashboard' => 'הדשבורד',
        ];
    }

    public static function entryOptions(): array
    {
        return [
            'dashboard-services' => 'שירותים בדשבורד',
            'dashboard-recommendations' => 'המלצות בדשבורד',
            'home-ecosystem' => 'אקו־סיסטם בעמוד הבית',
            'products-page' => 'עמוד המוצרים',
            'services-cards' => 'כרטיסי שירותים',
            'public-services' => 'טופס שירותים ציבורי',
        ];
    }

    public static function timeframeOptions(): array
    {
        return [
            'urgent' => 'מיידית',
            'month' => 'בחודש הקרוב',
            'quarter' => 'ברבעון הקרוב',
            'exploring' => 'רק בודק אפשרויות',
        ];
    }

    public static function businessTypeOptions(): array
    {
        return [
            'local_business' => 'עסק מקומי',
            'ecommerce' => 'איקומרס / חנות',
            'agency' => 'סוכנות / סטודיו',
            'saas' => 'SaaS / מוצר דיגיטלי',
            'content' => 'תוכן / מדיה',
            'service_provider' => 'נותן שירותים',
            'nonprofit' => 'עמותה / ארגון',
            'other' => 'אחר',
        ];
    }

    public static function teamSizeOptions(): array
    {
        return [
            'solo' => 'אדם אחד',
            'small' => '2–5 עובדים',
            'medium' => '6–20 עובדים',
            'large' => '21–50 עובדים',
            'enterprise' => '50+ עובדים',
        ];
    }

    public static function budgetRangeOptions(): array
    {
        return [
            'unknown' => 'עדיין לא סגור',
            'small' => 'עד 2,500 ש"ח',
            'medium' => '2,500–7,500 ש"ח',
            'large' => '7,500–20,000 ש"ח',
            'enterprise' => '20,000+ ש"ח',
        ];
    }

    public static function urgencyOptions(): array
    {
        return [
            'info' => 'רק בודק כרגע',
            'soon' => 'רוצה להתקדם בקרוב',
            'urgent' => 'צריך טיפול מהיר',
        ];
    }

    public static function callbackWindowOptions(): array
    {
        return [
            'morning' => 'בוקר',
            'noon' => 'צהריים',
            'afternoon' => 'אחר הצהריים',
            'evening' => 'ערב',
            'anytime' => 'כל שעה נוחה',
        ];
    }

    public static function sourceLabel(string $source, string $entryPoint): string
    {
        if ($source === 'dashboard') {
            return 'פנייה מתוך הדשבורד';
        }

        return match ($entryPoint) {
            'home-ecosystem' => 'פנייה מעמוד הבית',
            'products-page' => 'פנייה מעמוד המוצרים',
            'services-cards' => 'פנייה מכרטיסי השירותים',
            default => 'פנייה מהאתר הציבורי',
        };
    }

    public static function entryLabel(string $entryPoint): string
    {
        return static::entryOptions()[$entryPoint] ?? 'כניסה כללית';
    }

    public static function marketingLabel(?string $utmSource, ?string $utmMedium, ?string $utmCampaign): ?string
    {
        $parts = array_values(array_filter([$utmSource, $utmMedium, $utmCampaign]));

        if ($parts === []) {
            return null;
        }

        return implode(' / ', $parts);
    }

    protected static function opportunityMeta(
        string $serviceType,
        string $goal,
        string $message,
        ?string $contactPhone,
        ?string $contactEmail,
        string $siteDomain,
        ?string $utmSource,
        ?string $utmMedium,
        ?string $utmCampaign,
        ?string $referrerHost,
        ?string $businessType,
        ?string $teamSize,
        ?string $timeframe,
        ?string $budgetRange,
        ?string $urgencyLevel,
        ?string $callbackWindow
    ): array {
        $score = 0;

        $score += $serviceType === 'ecosystem_access' ? 18 : 28;
        $score += filled(trim($siteDomain)) ? 18 : 0;

        $goalLength = mb_strlen(trim($goal));
        if ($goalLength >= 50) {
            $score += 14;
        } elseif ($goalLength >= 20) {
            $score += 8;
        }

        $messageLength = mb_strlen(trim($message));
        if ($messageLength >= 120) {
            $score += 20;
        } elseif ($messageLength >= 60) {
            $score += 12;
        } elseif ($messageLength >= 20) {
            $score += 6;
        }

        if (filled($contactPhone)) {
            $score += 18;
        } elseif (filled($contactEmail)) {
            $score += 10;
        }

        if (filled($utmCampaign)) {
            $score += 10;
        }

        if (filled($utmSource) || filled($utmMedium)) {
            $score += 6;
        }

        if (filled($referrerHost)) {
            $score += 6;
        }

        $score += match ($businessType) {
            'ecommerce', 'agency', 'saas' => 10,
            'service_provider', 'content' => 7,
            'local_business', 'nonprofit', 'other' => 4,
            default => 0,
        };

        $score += match ($teamSize) {
            'enterprise' => 12,
            'large' => 10,
            'medium' => 8,
            'small' => 5,
            'solo' => 2,
            default => 0,
        };

        $score += match ($timeframe) {
            'urgent' => 16,
            'month' => 12,
            'quarter' => 7,
            'exploring' => 2,
            default => 0,
        };

        $score += match ($budgetRange) {
            'enterprise' => 16,
            'large' => 12,
            'medium' => 8,
            'small' => 4,
            'unknown' => 2,
            default => 0,
        };

        $score += match ($urgencyLevel) {
            'urgent' => 14,
            'soon' => 8,
            'info' => 2,
            default => 0,
        };

        $score += match ($callbackWindow) {
            'morning', 'noon', 'afternoon', 'evening' => 4,
            'anytime' => 6,
            default => 0,
        };

        $score = min(100, $score);

        if ($score >= 72) {
            return [
                'score' => $score,
                'key' => 'hot',
                'label' => 'ליד חם',
                'tone' => 'good',
            ];
        }

        if ($score >= 42) {
            return [
                'score' => $score,
                'key' => 'warm',
                'label' => 'ליד איכותי',
                'tone' => 'warn',
            ];
        }

        return [
            'score' => $score,
            'key' => 'cold',
            'label' => 'עניין ראשוני',
            'tone' => 'neutral',
        ];
    }

    protected static function normalizePhone(mixed $value): ?string
    {
        $phone = trim((string) ($value ?? ''));

        return $phone === '' ? null : $phone;
    }

    protected static function normalizeText(mixed $value): ?string
    {
        $text = trim((string) ($value ?? ''));

        return $text === '' ? null : $text;
    }

    protected static function normalizeUrl(mixed $value): ?string
    {
        $url = trim((string) ($value ?? ''));

        if ($url === '') {
            return null;
        }

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        return $url;
    }

    protected static function followUpMeta(?Carbon $followUpAt): array
    {
        if (! $followUpAt) {
            return [
                'label' => 'ללא מועד חזרה',
                'tone' => 'neutral',
            ];
        }

        $today = now()->startOfDay();
        $date = $followUpAt->copy()->startOfDay();

        if ($date->lt($today)) {
            return [
                'label' => 'מועד חזרה עבר',
                'tone' => 'warn',
            ];
        }

        if ($date->equalTo($today)) {
            return [
                'label' => 'לחזור היום',
                'tone' => 'good',
            ];
        }

        return [
            'label' => 'לחזור ב־' . $followUpAt->format('d/m'),
            'tone' => 'neutral',
        ];
    }

    public static function updateRuntime(string $leadKey, User $admin, array $validated): void
    {
        $scope = static::runtimeScope();

        $updated = static::runtimeLeads()->map(function (array $lead) use ($leadKey, $admin, $validated) {
            $currentKey = (string) ($lead['key'] ?? ('service-' . ($lead['id'] ?? 'x')));

            if ($currentKey !== $leadKey) {
                return $lead;
            }

            $lead['status'] = $validated['status'];
            $lead['internal_note'] = trim((string) ($validated['internal_note'] ?? ''));
            $lead['follow_up_at'] = filled($validated['follow_up_at'] ?? null)
                ? Carbon::parse($validated['follow_up_at'])->toDateString()
                : null;
            $lead['updated_by_name'] = $admin->name;
            $lead['updated_by_email'] = $admin->email;
            $lead['last_activity_at'] = Carbon::now()->toIso8601String();

            return $lead;
        })->values()->all();

        RuntimeStore::put($scope, 'items', $updated);
    }
}
