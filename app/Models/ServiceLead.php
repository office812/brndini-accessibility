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
        'goal',
        'message',
        'preferred_contact',
        'status',
        'source',
        'internal_note',
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
            'goal' => trim((string) $validated['goal']),
            'message' => trim((string) $validated['message']),
            'preferred_contact' => $validated['preferred_contact'],
            'status' => 'new',
            'source' => 'dashboard',
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
        $siteName = $lead['site_name'] ?? null;
        $subject = 'פנייה לגבי ' . ($siteName ?: 'שירותי Brndini');
        $mailTo = $contactEmail
            ? 'mailto:' . rawurlencode($contactEmail) . '?subject=' . rawurlencode($subject)
            : null;
        $preferredContact = $lead['preferred_contact'] ?? 'email';

        $freshnessKey = 'fresh';
        $freshnessLabel = 'חדש';
        $freshnessTone = 'good';

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
            'update_key' => (string) ($lead['key'] ?? ('service-' . ($lead['id'] ?? 'x'))),
            'reference_code' => $lead['reference_code'] ?? 'BRN-RUNTIME',
            'service_type' => $lead['service_type'] ?? 'general',
            'goal' => $lead['goal'] ?? '',
            'message' => $lead['message'] ?? '',
            'preferred_contact' => $lead['preferred_contact'] ?? 'email',
            'status' => $lead['status'] ?? 'new',
            'internal_note' => $lead['internal_note'] ?? '',
            'source' => $source,
            'source_label' => $source === 'public' ? 'פנייה מהאתר הציבורי' : 'פנייה מתוך הדשבורד',
            'user_name' => $lead['user_name'] ?? $contactName,
            'user_email' => $lead['user_email'] ?? $contactEmail,
            'contact_name' => $contactName,
            'contact_email' => $contactEmail,
            'site_name' => $siteName,
            'site_domain' => $lead['site_domain'] ?? null,
            'mail_to' => $mailTo,
            'freshness_key' => $freshnessKey,
            'freshness_label' => $freshnessLabel,
            'freshness_tone' => $freshnessTone,
            'preferred_contact_key' => $preferredContact,
            'last_activity_label' => $lastActivityAt?->diffForHumans() ?? 'נוצר עכשיו',
            'sort_timestamp' => $lastActivityAt?->timestamp ?? 0,
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
            $lead['updated_by_name'] = $admin->name;
            $lead['updated_by_email'] = $admin->email;
            $lead['last_activity_at'] = Carbon::now()->toIso8601String();

            return $lead;
        })->values()->all();

        RuntimeStore::put($scope, 'items', $updated);
    }
}
