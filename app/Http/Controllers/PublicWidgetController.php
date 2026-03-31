<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class PublicWidgetController extends Controller
{
    public function show(string $publicKey): JsonResponse
    {
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

        if (($site->license_status ?? 'active') !== 'active') {
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
                    'statementUrl' => $site->statement_url,
                    'licenseStatus' => $site->license_status ?? 'active',
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
            Cache::put('site:' . $site->id . ':widget_seen_at', now()->toIso8601String(), now()->addDays(30));
            Cache::put('site:' . $site->id . ':widget_seen_url', $pageUrl, now()->addDays(30));
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
}
