<?php

namespace App\Models;

use App\Support\RuntimeStore;
use App\Support\SiteSettings;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'site_name',
        'domain',
        'statement_url',
        'public_key',
        'license_status',
        'purchase_url',
        'billing_settings',
        'audit_snapshot',
        'alert_settings',
        'license_expires_at',
        'last_audited_at',
        'last_seen_at',
        'last_seen_url',
        'service_mode',
        'widget_settings',
    ];

    protected $casts = [
        'widget_settings' => 'array',
        'billing_settings' => 'array',
        'audit_snapshot' => 'array',
        'alert_settings' => 'array',
        'license_expires_at' => 'datetime',
        'last_audited_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function widgetConfig(): array
    {
        return SiteSettings::sanitizeWidget($this->widget_settings ?? []);
    }

    public function billingConfig(): array
    {
        return SiteSettings::sanitizeBilling(
            $this->billing_settings ?? [],
            ($this->license_status ?? 'active') === 'active'
        );
    }

    public function alertConfig(): array
    {
        return SiteSettings::sanitizeAlertSettings($this->alert_settings ?? []);
    }

    public function auditConfig(): array
    {
        return SiteSettings::sanitizeAuditSnapshot($this->audit_snapshot ?? []);
    }

    public static function tableAvailable(): bool
    {
        return Schema::hasTable('sites');
    }

    public static function columnsAvailable(array $columns): bool
    {
        foreach ($columns as $column) {
            if (! Schema::hasColumn('sites', $column)) {
                return false;
            }
        }

        return true;
    }

    public static function filterPersistablePayload(array $payload): array
    {
        if (! static::tableAvailable()) {
            return [];
        }

        return collect($payload)
            ->filter(fn ($_value, $column) => Schema::hasColumn('sites', $column))
            ->all();
    }

    public static function createForUser(User $user, array $payload): static
    {
        $site = $user->sites()->create(static::filterPersistablePayload($payload));
        $site->storeRuntimeOverrides($payload);

        return $site->loadRuntimeOverrides();
    }

    public function persistPayload(array $payload): void
    {
        $persisted = static::filterPersistablePayload($payload);

        if ($persisted !== []) {
            static::query()->whereKey($this->id)->update($persisted);
            $this->forceFill($persisted);
            $this->syncOriginalAttributes(array_keys($persisted));
        }

        $this->storeRuntimeOverrides($payload);
    }

    public function runtimeScope(): string
    {
        return 'site-' . $this->id;
    }

    public function runtimeOverridesCacheKey(): string
    {
        return 'site:' . $this->id . ':runtime_overrides';
    }

    public function storeRuntimeOverrides(array $payload): void
    {
        $missingColumnPayload = collect($payload)
            ->filter(fn ($_value, $column) => ! Schema::hasColumn('sites', $column))
            ->all();

        if ($missingColumnPayload === []) {
            return;
        }

        $current = RuntimeStore::get($this->runtimeScope(), $this->runtimeOverridesCacheKey(), []);

        RuntimeStore::put(
            $this->runtimeScope(),
            $this->runtimeOverridesCacheKey(),
            array_merge(is_array($current) ? $current : [], $missingColumnPayload)
        );
    }

    public function loadRuntimeOverrides(): static
    {
        $overrides = RuntimeStore::get($this->runtimeScope(), $this->runtimeOverridesCacheKey(), []);

        if (! is_array($overrides) || $overrides === []) {
            return $this;
        }

        $applicable = collect($overrides)
            ->filter(fn ($_value, $column) => ! Schema::hasColumn('sites', $column))
            ->all();

        if ($applicable === []) {
            return $this;
        }

        return $this->applyRuntimeOverrides($applicable);
    }

    public function applyRuntimeOverrides(array $overrides): static
    {
        foreach ([
            'statement_url',
            'license_status',
            'purchase_url',
            'billing_settings',
            'audit_snapshot',
            'alert_settings',
            'license_expires_at',
            'last_audited_at',
            'last_seen_at',
            'last_seen_url',
        ] as $attribute) {
            if (array_key_exists($attribute, $overrides)) {
                $this->setAttribute($attribute, $overrides[$attribute]);
            }
        }

        return $this;
    }
}
