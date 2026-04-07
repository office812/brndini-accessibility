<?php

use App\Http\Controllers\PublicWidgetController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'ok' => true,
        'product' => 'A11Y Bridge Platform',
        'deploy' => 'webhook-active',
    ]);
});

Route::get('/public/widget-config/{publicKey}', [PublicWidgetController::class, 'show']);
Route::post('/public/widget-seen/{publicKey}', [PublicWidgetController::class, 'track']);

Route::post('/deploy', \App\Http\Controllers\DeployController::class);
