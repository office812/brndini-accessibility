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
        return SiteSettings::sanitizeAlertSettings($this->runtimeValue(
            'alert_settings',
            $this->alertSettingsCacheKey(),
            $this->alert_settings ?? []
        ));
    }

    public function auditConfig(): array
    {
        return SiteSettings::sanitizeAuditSnapshot($this->runtimeValue(
            'audit_snapshot',
            $this->auditSnapshotCacheKey(),
            $this->audit_snapshot ?? []
        ));
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
            $lastSeenAt = $this->last_seen_at;
            $pageUrl = filled($this->last_seen_url) ? $this->last_seen_url : null;
        } else {
            $cachedSeenAt = RuntimeStore::get($this->runtimeScope(), 'widget_seen_at');
            $cachedPageUrl = RuntimeStore::get($this->runtimeScope(), 'widget_seen_url');

            $lastSeenAt = is_string($cachedSeenAt) && trim($cachedSeenAt) !== ''
                ? Carbon::parse($cachedSeenAt)
                : null;
            $pageUrl = is_string($cachedPageUrl) && trim($cachedPageUrl) !== ''
                ? $cachedPageUrl
                : null;
        }

        if (! $lastSeenAt instanceof Carbon) {
            return [
                'status' => 'pending',
                'label' => 'ממתין להטמעה',
                'tone' => 'warn',
                'installed' => false,
                'ever_seen' => false,
                'last_seen_at' => null,
                'page_url' => $pageUrl,
            ];
        }

        $isRecent = $lastSeenAt->greaterThanOrEqualTo(now()->subDays(7));

        if ($isRecent) {
            return [
                'status' => 'installed',
                'label' => 'הוטמע באתר',
                'tone' => 'good',
                'installed' => true,
                'ever_seen' => true,
                'last_seen_at' => $lastSeenAt,
                'page_url' => $pageUrl,
            ];
        }

        return [
            'status' => 'stale',
            'label' => 'לא זוהה לאחרונה',
            'tone' => 'neutral',
            'installed' => false,
            'ever_seen' => true,
            'last_seen_at' => $lastSeenAt,
            'page_url' => $pageUrl,
        ];
    }

    public function lastAuditedAt(): ?Carbon
    {
        if (static::columnsAvailable(['last_audited_at'])) {
            return $this->last_audited_at;
        }

        $cached = RuntimeStore::get($this->runtimeScope(), $this->auditTimestampCacheKey());

        return is_string($cached) && trim($cached) !== '' ? Carbon::parse($cached) : null;
    }

    public function statementBuilderData(array $defaults = []): array
    {
        $cached = RuntimeStore::get($this->runtimeScope(), $this->statementBuilderCacheKey(), []);

        if (! is_array($cached)) {
            return $defaults;
        }

        return array_merge($defaults, $cached);
    }

    public function statementUrlValue(): ?string
    {
        if (filled($this->statement_url)) {
            return $this->statement_url;
        }

        $statementBuilder = RuntimeStore::get($this->runtimeScope(), $this->statementBuilderCacheKey());

        if (is_array($statementBuilder) && ($statementBuilder['organization_name'] ?? '') !== '') {
            return route('statement.show', $this->public_key);
        }

        return null;
    }

    public function storeAlertConfig(array $alerts): void
    {
        $payload = ['alert_settings' => SiteSettings::sanitizeAlertSettings($alerts)];

        if (static::columnsAvailable(['alert_settings'])) {
            $this->persistPayload($payload);

            return;
        }

        RuntimeStore::put($this->runtimeScope(), $this->alertSettingsCacheKey(), $payload['alert_settings']);
        $this->setAttribute('alert_settings', $payload['alert_settings']);
    }

    public function storeAuditSnapshot(array $snapshot, ?Carbon $auditedAt = null): void
    {
        $payload = [
            'audit_snapshot' => SiteSettings::sanitizeAuditSnapshot($snapshot),
            'last_audited_at' => $auditedAt ?? now(),
        ];

        if (static::columnsAvailable(['audit_snapshot', 'last_audited_at'])) {
            $this->persistPayload($payload);

            return;
        }

        RuntimeStore::putMany($this->runtimeScope(), [
            $this->auditSnapshotCacheKey() => $payload['audit_snapshot'],
            $this->auditTimestampCacheKey() => $payload['last_audited_at']->toIso8601String(),
        ]);
        $this->setAttribute('audit_snapshot', $payload['audit_snapshot']);
        $this->setAttribute('last_audited_at', $payload['last_audited_at']);
    }

    public function storeStatementBuilder(array $statementData): void
    {
        RuntimeStore::put($this->runtimeScope(), $this->statementBuilderCacheKey(), $statementData);
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

    public function auditSnapshotCacheKey(): string
    {
        return 'site:' . $this->id . ':audit_snapshot_fallback';
    }

    public function auditTimestampCacheKey(): string
    {
        return 'site:' . $this->id . ':last_audited_fallback';
    }

    public function alertSettingsCacheKey(): string
    {
        return 'site:' . $this->id . ':alert_settings_fallback';
    }

    public function statementBuilderCacheKey(): string
    {
        return 'site:' . $this->id . ':statement_builder';
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

    private function runtimeValue(string $column, string $cacheKey, mixed $default = null): mixed
    {
        if (static::columnsAvailable([$column])) {
            return $this->getAttribute($column);
        }

        return RuntimeStore::get($this->runtimeScope(), $cacheKey, $default);
    }
}
