<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\JsonResponse;

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
}
