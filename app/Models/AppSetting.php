<?php

namespace App\Models;

use App\Support\RuntimeStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AppSetting extends Model
{
    public const CACHE_KEY = 'app:settings:fallback';

    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function getMany(array $keys): array
    {
        $fallback = RuntimeStore::all(self::CACHE_KEY);
        $values = [];

        foreach ($keys as $key) {
            $values[$key] = $fallback[$key] ?? null;
        }

        if (! Schema::hasTable('app_settings')) {
            return $values;
        }

        $stored = static::query()
            ->whereIn('key', $keys)
            ->pluck('value', 'key')
            ->all();

        foreach ($stored as $key => $value) {
            $values[$key] = $value;
        }

        return $values;
    }

    public static function putMany(array $settings): void
    {
        $filtered = [];

        foreach ($settings as $key => $value) {
            $filtered[$key] = is_string($value) ? trim($value) : null;
        }

        RuntimeStore::putMany(self::CACHE_KEY, $filtered);

        if (! Schema::hasTable('app_settings')) {
            return;
        }

        foreach ($filtered as $key => $value) {
            static::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value === '' ? null : $value]
            );
        }
    }
}
