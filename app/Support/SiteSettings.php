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
    ];

    public const BUTTON_MODES = [
        'icon-label',
        'label-only',
        'icon-only',
    ];

    public const BUTTON_STYLES = [
        'solid',
        'soft',
        'glass',
        'midnight',
    ];

    public const ICONS = [
        'figure',
        'spark',
        'shield',
        'pulse',
    ];

    public static function defaultWidget(): array
    {
        return [
            'position' => 'bottom-right',
            'color' => '#0f6a73',
            'size' => 'comfortable',
            'label' => 'נגישות',
            'language' => 'he',
            'buttonMode' => 'icon-label',
            'buttonStyle' => 'solid',
            'icon' => 'figure',
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
            'buttonMode' => in_array($widget['buttonMode'] ?? null, self::BUTTON_MODES, true) ? $widget['buttonMode'] : $defaults['buttonMode'],
            'buttonStyle' => in_array($widget['buttonStyle'] ?? null, self::BUTTON_STYLES, true) ? $widget['buttonStyle'] : $defaults['buttonStyle'],
            'icon' => in_array($widget['icon'] ?? null, self::ICONS, true) ? $widget['icon'] : $defaults['icon'],
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
