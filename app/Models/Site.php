<?php

namespace App\Models;

use App\Support\SiteSettings;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
}
