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
        'service_mode',
        'widget_settings',
    ];

    protected $casts = [
        'widget_settings' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function widgetConfig(): array
    {
        return SiteSettings::sanitizeWidget($this->widget_settings ?? []);
    }
}
