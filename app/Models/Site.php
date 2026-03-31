<?php

namespace App\Models;

use App\Support\RuntimeStore;
use App\Support\SiteSettings;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
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
            $this->licenseActive()
        );
    }

    public function billingStatus(): string
    {
        return $this->billingConfig()['status'] ?? 'inactive';
    }

    public function billingActive(): bool
    {
        return in_array($this->billingStatus(), ['active', 'trial'], true);
    }

    public function billingPlan(): string
    {
        return $this->billingConfig()['plan'] ?? 'free';
    }

    public function billingCycle(): string
    {
        return $this->billingConfig()['cycle'] ?? 'yearly';
    }

    public function billingAmount(): int
    {
        return (int) ($this->billingConfig()['amount'] ?? 0);
    }

    public function billingPlanMeta(): array
    {
        $catalog = SiteSettings::billingCatalog();

        return $catalog[$this->billingPlan()] ?? $catalog['free'];
    }

    public function billingUpdatePayload(string $plan, string $cycle): array
    {
        $billing = $this->billingConfig();
        $billing['plan'] = SiteSettings::normalizeBillingPlan($plan);
        $billing['cycle'] = in_array($cycle, SiteSettings::BILLING_CYCLES, true) ? $cycle : $billing['cycle'];
        $billing['amount'] = SiteSettings::billingPriceFor($billing['plan'], $billing['cycle']);
        $billing['status'] = $this->licenseActive() ? 'active' : 'inactive';

        return [
            'billing_settings' => SiteSettings::sanitizeBilling($billing, $this->licenseActive()),
        ];
    }

    public function nextLicenseExpiry(?string $cycle = null): Carbon
    {
        $billingCycle = in_array($cycle, SiteSettings::BILLING_CYCLES, true) ? $cycle : $this->billingCycle();

        return $billingCycle === 'monthly' ? Carbon::now()->addMonth() : Carbon::now()->addYear();
    }

    public function activationPayload(): array
    {
        $billing = $this->billingConfig();
        $billing['status'] = 'active';

        return [
            'license_status' => 'active',
            'purchase_url' => null,
            'billing_settings' => SiteSettings::sanitizeBilling($billing, true),
            'license_expires_at' => $this->nextLicenseExpiry($billing['cycle'] ?? null),
        ];
    }

    public function alertConfig(): array
    {
        return SiteSettings::sanitizeAlertSettings($this->alert_settings ?? []);
    }

    public function auditConfig(): array
    {
        return SiteSettings::sanitizeAuditSnapshot($this->audit_snapshot ?? []);
    }

    public function licenseStatus(): string
    {
        return ($this->license_status ?? 'active') === 'active' ? 'active' : 'inactive';
    }

    public function licenseActive(): bool
    {
        return $this->licenseStatus() === 'active';
    }

    public function markWidgetSeen(?string $pageUrl = null): void
    {
        $payload = [
            'last_seen_at' => now(),
            'last_seen_url' => $pageUrl,
        ];

        if (static::columnsAvailable(['last_seen_at', 'last_seen_url'])) {
            $this->persistPayload($payload);

            return;
        }

        RuntimeStore::putMany($this->runtimeScope(), [
            'widget_seen_at' => now()->toIso8601String(),
            'widget_seen_url' => $pageUrl,
        ]);
    }

    public function installationSignal(): array
    {
        if (static::columnsAvailable(['last_seen_at', 'last_seen_url'])) {
            return [
                'installed' => filled($this->last_seen_at),
                'last_seen_at' => $this->last_seen_at,
                'page_url' => filled($this->last_seen_url) ? $this->last_seen_url : null,
            ];
        }

        $lastSeenAt = RuntimeStore::get($this->runtimeScope(), 'widget_seen_at');
        $pageUrl = RuntimeStore::get($this->runtimeScope(), 'widget_seen_url');

        return [
            'installed' => is_string($lastSeenAt) && trim($lastSeenAt) !== '',
            'last_seen_at' => is_string($lastSeenAt) ? Carbon::parse($lastSeenAt) : null,
            'page_url' => is_string($pageUrl) && trim($pageUrl) !== '' ? $pageUrl : null,
        ];
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
