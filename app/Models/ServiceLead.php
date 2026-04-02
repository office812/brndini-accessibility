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
        'preferred_contact',
        'status',
        'source',
        'entry_point',
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
            'preferred_contact' => $validated['preferred_contact'],
            'status' => 'new',
            'source' => 'dashboard',
            'entry_point' => $validated['entry_point'] ?? 'dashboard-services',
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
            'preferred_contact' => $validated['preferred_contact'],
            'status' => 'new',
            'source' => 'public',
            'entry_point' => $validated['entry_point'] ?? 'public-services',
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
            'preferred_contact' => $lead['preferred_contact'] ?? 'email',
            'status' => $lead['status'] ?? 'new',
            'internal_note' => $lead['internal_note'] ?? '',
            'source' => $source,
            'source_label' => static::sourceLabel($source, $entryPoint),
            'entry_point' => $entryPoint,
            'entry_label' => static::entryLabel($entryPoint),
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
                $lead['service_type'] ?? null
            ),
            'last_activity_label' => $lastActivityAt?->diffForHumans() ?? 'נוצר עכשיו',
            'sort_timestamp' => $lastActivityAt?->timestamp ?? 0,
        ];
    }

    protected static function nextStepLabel(string $status, string $preferredContact, ?string $serviceType): string
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

    protected static function normalizePhone(mixed $value): ?string
    {
        $phone = trim((string) ($value ?? ''));

        return $phone === '' ? null : $phone;
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
