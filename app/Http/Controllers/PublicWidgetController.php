<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Support\RuntimeStore;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PublicWidgetController extends Controller
{
    public const PLATFORM_WIDGET_KEY = 'platform-premium';

    public function show(string $publicKey): JsonResponse
    {
        if ($this->isPlatformWidgetKey($publicKey)) {
            return response()
                ->json([
                    'success' => true,
                    'data' => $this->platformWidgetPayload(),
                ])
                ->withHeaders([
                    'Access-Control-Allow-Origin' => '*',
                    'Cache-Control' => 'no-store',
                ]);
        }

        $site = Site::where('public_key', $publicKey)->first();

        if (! $site) {
            return response()
                ->json([
                    'success' => false,
                    'inactive' => true,
                    'error' => 'Unknown site key.',
                    'purchaseUrl' => route('home') . '#pricing',
                ], 404)
                ->withHeaders([
                    'Access-Control-Allow-Origin' => '*',
                    'Cache-Control' => 'no-store',
                ]);
        }

        $site = $site->loadRuntimeOverrides();

        if (! $site->licenseActive()) {
            return response()
                ->json([
                    'success' => false,
                    'inactive' => true,
                    'message' => 'License inactive.',
                    'siteName' => $site->site_name,
                    'purchaseUrl' => $site->purchase_url ?: route('home') . '#pricing',
                    'widget' => $site->widgetConfig(),
                ], 403)
                ->withHeaders([
                    'Access-Control-Allow-Origin' => '*',
                    'Cache-Control' => 'no-store',
                ]);
        }

        return response()
            ->json([
                'success' => true,
                'data' => [
                    'siteKey' => $site->public_key,
                    'siteName' => $site->site_name,
                    'domain' => $site->domain,
                    'statementUrl' => $this->statementUrlForSite($site),
                    'licenseStatus' => $site->licenseStatus(),
                    'purchaseUrl' => $site->purchase_url,
                    'billing' => $site->billingConfig(),
                    'audit' => $site->auditConfig(),
                    'widget' => $site->widgetConfig(),
                    'updatedAt' => optional($site->updated_at)->toIso8601String(),
                ],
            ])
            ->withHeaders([
                'Access-Control-Allow-Origin' => '*',
                'Cache-Control' => 'no-store',
            ]);
    }

    public function track(string $publicKey, Request $request): JsonResponse
    {
        if ($this->isPlatformWidgetKey($publicKey)) {
            return response()->json(['ok' => true])->withHeaders([
                'Access-Control-Allow-Origin' => '*',
                'Cache-Control' => 'no-store',
            ]);
        }

        $site = Site::where('public_key', $publicKey)->first();

        if (! $site) {
            return response()->json(['ok' => false], 404)->withHeaders([
                'Access-Control-Allow-Origin' => '*',
                'Cache-Control' => 'no-store',
            ]);
        }

        $pageUrl = (string) $request->input('pageUrl', '');
        $siteHost = $this->normalizeHost(parse_url($site->domain, PHP_URL_HOST) ?: $site->domain);
        $pageHost = $this->normalizeHost(parse_url($pageUrl, PHP_URL_HOST) ?: '');

        if ($siteHost !== '' && $pageHost !== '' && $siteHost === $pageHost) {
            $site->markWidgetSeen($pageUrl);
        }

        return response()->json(['ok' => true])->withHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Cache-Control' => 'no-store',
        ]);
    }

    private function normalizeHost(string $host): string
    {
        $normalized = strtolower(trim($host));

        if (str_starts_with($normalized, 'www.')) {
            return substr($normalized, 4);
        }

        return $normalized;
    }

    private function statementUrlForSite(Site $site): ?string
    {
        if (filled($site->statement_url)) {
            return $site->statement_url;
        }

        $statementBuilder = RuntimeStore::get($site->runtimeScope(), 'statement_builder');

        if (is_array($statementBuilder) && ($statementBuilder['organization_name'] ?? '') !== '') {
            return route('statement.show', $site->public_key);
        }

        return null;
    }

    private function isPlatformWidgetKey(string $publicKey): bool
    {
        return $publicKey === self::PLATFORM_WIDGET_KEY;
    }

    private function platformWidgetPayload(): array
    {
        return [
            'siteKey' => self::PLATFORM_WIDGET_KEY,
            'siteName' => 'A11Y Bridge',
            'domain' => request()->getHost(),
            'statementUrl' => null,
            'licenseStatus' => 'active',
            'purchaseUrl' => route('pricing'),
            'billing' => [
                'plan' => 'premium',
                'cycle' => 'yearly',
                'status' => 'active',
                'amount' => 249,
                'currency' => 'USD',
            ],
            'audit' => [
                'score' => 100,
                'status' => 'healthy',
                'summary' => 'ווידג׳ט הפרימיום של הפלטפורמה זמין בכל עמוד באתר.',
                'checks' => [],
                'alerts' => [],
            ],
            'widget' => [
                'position' => 'bottom-left',
                'color' => '#2563eb',
                'size' => 'comfortable',
                'label' => 'נגישות',
                'language' => 'he',
                'buttonMode' => 'icon-label',
                'buttonStyle' => 'solid',
                'icon' => 'figure',
                'preset' => 'high-tech',
                'panelLayout' => 'stacked',
                'showContrast' => true,
                'showFontScale' => true,
                'showUnderlineLinks' => true,
                'showReduceMotion' => true,
            ],
            'updatedAt' => now()->toIso8601String(),
        ];
    }
}
