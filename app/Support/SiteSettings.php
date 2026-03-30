<?php

namespace App\Support;

use Illuminate\Support\Str;

class SiteSettings
{
    public const SERVICE_MODES = [
        'audit_only',
        'audit_and_fix',
        'managed_service',
    ];

    public const POSITIONS = [
        'bottom-right',
        'bottom-left',
    ];

    public const SIZES = [
        'compact',
        'comfortable',
        'large',
    ];

    public const LANGUAGES = [
        'he',
        'en',
    ];

    public static function defaultWidget(): array
    {
        return [
            'position' => 'bottom-right',
            'color' => '#0f6a73',
            'size' => 'comfortable',
            'label' => 'נגישות',
            'language' => 'he',
            'showContrast' => true,
            'showFontScale' => true,
            'showUnderlineLinks' => true,
            'showReduceMotion' => true,
        ];
    }

    public static function sanitizeWidget(array $widget): array
    {
        $defaults = self::defaultWidget();

        $color = is_string($widget['color'] ?? null) ? trim($widget['color']) : $defaults['color'];

        return [
            'position' => in_array($widget['position'] ?? null, self::POSITIONS, true) ? $widget['position'] : $defaults['position'],
            'color' => preg_match('/^#[0-9A-Fa-f]{6}$/', $color) ? $color : $defaults['color'],
            'size' => in_array($widget['size'] ?? null, self::SIZES, true) ? $widget['size'] : $defaults['size'],
            'label' => is_string($widget['label'] ?? null) && trim($widget['label']) !== '' ? trim($widget['label']) : $defaults['label'],
            'language' => in_array($widget['language'] ?? null, self::LANGUAGES, true) ? $widget['language'] : $defaults['language'],
            'showContrast' => filter_var($widget['showContrast'] ?? $defaults['showContrast'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $defaults['showContrast'],
            'showFontScale' => filter_var($widget['showFontScale'] ?? $defaults['showFontScale'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $defaults['showFontScale'],
            'showUnderlineLinks' => filter_var($widget['showUnderlineLinks'] ?? $defaults['showUnderlineLinks'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $defaults['showUnderlineLinks'],
            'showReduceMotion' => filter_var($widget['showReduceMotion'] ?? $defaults['showReduceMotion'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $defaults['showReduceMotion'],
        ];
    }

    public static function normalizeUrl(?string $url): string
    {
        $value = trim((string) $url);

        if ($value === '') {
            return '';
        }

        if (! Str::startsWith($value, ['http://', 'https://'])) {
            $value = 'https://' . $value;
        }

        return rtrim($value, '/');
    }

    public static function normalizeOptionalUrl(?string $url): ?string
    {
        $value = self::normalizeUrl($url);

        return $value === '' ? null : $value;
    }

    public static function generatePublicKey(): string
    {
        return 'ab_' . Str::lower(Str::random(24));
    }
}
