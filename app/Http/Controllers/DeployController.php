<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeployController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $secret = config('services.deploy.webhook_secret');

        if (! $secret) {
            return response()->json(['error' => 'Webhook secret not configured'], 500);
        }

        $signature = $request->header('X-Hub-Signature-256');

        if (! $signature) {
            return response()->json(['error' => 'Missing signature'], 403);
        }

        $expected = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);

        if (! hash_equals($expected, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $payload = $request->json();
        $ref = $payload->get('ref', '');

        if ($ref !== 'refs/heads/main') {
            return response()->json(['skipped' => true, 'reason' => 'Not main branch']);
        }

        $appPath = base_path();
        $log = [];

        $commands = [
            'git fetch origin main 2>&1',
            'git reset --hard origin/main 2>&1',
            'php artisan optimize:clear 2>&1',
            'php artisan config:cache 2>&1',
            'php artisan route:cache 2>&1',
            'php artisan view:cache 2>&1',
        ];

        foreach ($commands as $cmd) {
            $output = [];
            $code = 0;
            exec("cd {$appPath} && {$cmd}", $output, $code);
            $log[] = [
                'cmd' => $cmd,
                'output' => implode("\n", $output),
                'exit' => $code,
            ];
        }

        return response()->json([
            'deployed' => true,
            'ref' => $ref,
            'log' => $log,
        ]);
    }
}
