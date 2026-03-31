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

    public const PRESETS = [
        'classic',
        'high-tech',
        'elegant',
        'bold',
    ];

    public const PANEL_LAYOUTS = [
        'stacked',
        'split',
    ];

    public const BILLING_PLANS = [
        'free',
        'premium',
    ];

    public const BILLING_CYCLES = [
        'monthly',
        'yearly',
    ];

    public const BILLING_STATUSES = [
        'active',
        'inactive',
        'past_due',
        'trial',
    ];

    public const AUDIT_STATUSES = [
        'healthy',
        'monitoring',
        'action_required',
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
            'preset' => 'classic',
            'panelLayout' => 'stacked',
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
            'preset' => in_array($widget['preset'] ?? null, self::PRESETS, true) ? $widget['preset'] : $defaults['preset'],
            'panelLayout' => in_array($widget['panelLayout'] ?? null, self::PANEL_LAYOUTS, true) ? $widget['panelLayout'] : $defaults['panelLayout'],
            'showContrast' => filter_var($widget['showContrast'] ?? $defaults['showContrast'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $defaults['showContrast'],
            'showFontScale' => filter_var($widget['showFontScale'] ?? $defaults['showFontScale'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $defaults['showFontScale'],
            'showUnderlineLinks' => filter_var($widget['showUnderlineLinks'] ?? $defaults['showUnderlineLinks'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $defaults['showUnderlineLinks'],
            'showReduceMotion' => filter_var($widget['showReduceMotion'] ?? $defaults['showReduceMotion'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $defaults['showReduceMotion'],
        ];
    }

    public static function defaultBilling(bool $isActive = true): array
    {
        return [
            'plan' => 'free',
            'cycle' => 'yearly',
            'status' => $isActive ? 'active' : 'inactive',
            'amount' => $isActive ? 0 : 0,
            'currency' => 'USD',
        ];
    }

    public static function sanitizeBilling(array $billing, bool $isActiveLicense = true): array
    {
        $defaults = self::defaultBilling($isActiveLicense);
        $normalizedPlan = self::normalizeBillingPlan($billing['plan'] ?? null);

        return [
            'plan' => in_array($normalizedPlan, self::BILLING_PLANS, true) ? $normalizedPlan : $defaults['plan'],
            'cycle' => in_array($billing['cycle'] ?? null, self::BILLING_CYCLES, true) ? $billing['cycle'] : $defaults['cycle'],
            'status' => in_array($billing['status'] ?? null, self::BILLING_STATUSES, true) ? $billing['status'] : $defaults['status'],
            'amount' => is_numeric($billing['amount'] ?? null) ? max(0, (int) $billing['amount']) : $defaults['amount'],
            'currency' => is_string($billing['currency'] ?? null) && trim($billing['currency']) !== '' ? strtoupper(trim($billing['currency'])) : $defaults['currency'],
        ];
    }

    public static function billingCatalog(): array
    {
        return [
            'free' => [
                'label' => 'חינם',
                'description' => 'מסלול שמכסה בערך 70% מהיכולות: התאמות טקסט, ניגודיות, ניווט בסיסי, קוד הטמעה קבוע וחוויית נגישות טובה לרוב האתרים.',
                'prices' => ['monthly' => 0, 'yearly' => 0],
            ],
            'premium' => [
                'label' => 'פרימיום',
                'description' => 'עוד 30% מהיכולות הקריטיות: פרופילי שימוש, מדריך קריאה, הסתרת תמונות, התאמות מתקדמות יותר וחוויית widget עשירה יותר.',
                'prices' => ['monthly' => 49, 'yearly' => 249],
            ],
        ];
    }

    public static function billingPriceFor(?string $plan, ?string $cycle): int
    {
        $catalog = self::billingCatalog();
        $normalizedPlan = self::normalizeBillingPlan($plan);
        $normalizedCycle = in_array($cycle, self::BILLING_CYCLES, true) ? $cycle : 'yearly';

        return (int) ($catalog[$normalizedPlan]['prices'][$normalizedCycle] ?? 0);
    }

    public static function defaultAlertSettings(): array
    {
        return [
            'license' => true,
            'statement' => true,
            'audit' => true,
            'sync' => true,
        ];
    }

    public static function sanitizeAlertSettings(array $alerts): array
    {
        $defaults = self::defaultAlertSettings();

        return [
            'license' => filter_var($alerts['license'] ?? $defaults['license'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $defaults['license'],
            'statement' => filter_var($alerts['statement'] ?? $defaults['statement'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $defaults['statement'],
            'audit' => filter_var($alerts['audit'] ?? $defaults['audit'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $defaults['audit'],
            'sync' => filter_var($alerts['sync'] ?? $defaults['sync'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $defaults['sync'],
        ];
    }

    public static function defaultAuditSnapshot(): array
    {
        return [
            'score' => 72,
            'status' => 'monitoring',
            'summary' => 'יש בסיס טוב, אבל עדיין יש כמה פעולות פתוחות כדי לסגור תמונת ציות בריאה.',
            'checks' => [],
            'alerts' => [],
        ];
    }

    public static function sanitizeAuditSnapshot(array $audit): array
    {
        $defaults = self::defaultAuditSnapshot();
        $score = is_numeric($audit['score'] ?? null) ? (int) $audit['score'] : $defaults['score'];

        return [
            'score' => max(0, min(100, $score)),
            'status' => in_array($audit['status'] ?? null, self::AUDIT_STATUSES, true) ? $audit['status'] : $defaults['status'],
            'summary' => is_string($audit['summary'] ?? null) && trim($audit['summary']) !== '' ? trim($audit['summary']) : $defaults['summary'],
            'checks' => is_array($audit['checks'] ?? null) ? array_values($audit['checks']) : $defaults['checks'],
            'alerts' => is_array($audit['alerts'] ?? null) ? array_values($audit['alerts']) : $defaults['alerts'],
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

    public static function normalizeBillingPlan(mixed $plan): string
    {
        return match ($plan) {
            'starter' => 'free',
            'growth', 'agency' => 'premium',
            'premium', 'free' => $plan,
            default => 'free',
        };
    }
}
