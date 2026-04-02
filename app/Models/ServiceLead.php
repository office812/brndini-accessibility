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
        'goal',
        'message',
        'preferred_contact',
        'status',
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
            'goal' => trim((string) $validated['goal']),
            'message' => trim((string) $validated['message']),
            'preferred_contact' => $validated['preferred_contact'],
            'status' => 'new',
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

        return (object) [
            'update_key' => (string) ($lead['key'] ?? ('service-' . ($lead['id'] ?? 'x'))),
            'reference_code' => $lead['reference_code'] ?? 'BRN-RUNTIME',
            'service_type' => $lead['service_type'] ?? 'general',
            'goal' => $lead['goal'] ?? '',
            'message' => $lead['message'] ?? '',
            'preferred_contact' => $lead['preferred_contact'] ?? 'email',
            'status' => $lead['status'] ?? 'new',
            'user_name' => $lead['user_name'] ?? null,
            'user_email' => $lead['user_email'] ?? null,
            'site_name' => $lead['site_name'] ?? null,
            'site_domain' => $lead['site_domain'] ?? null,
            'last_activity_label' => $lastActivityAt?->diffForHumans() ?? 'נוצר עכשיו',
            'sort_timestamp' => $lastActivityAt?->timestamp ?? 0,
        ];
    }
}
