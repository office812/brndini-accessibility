<?php

namespace App\Providers;

use App\Models\Site;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.app', 'layouts.auth'], function ($view) {
            $platformSite = Site::query()
                ->where('license_status', 'active')
                ->orderByDesc('id')
                ->first();

            $view->with('platformWidgetSiteKey', $platformSite?->public_key);
        });
    }
}
