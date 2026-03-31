<?php

namespace App\Providers;

use App\Models\Site;
use Illuminate\Support\Facades\Schema;
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
            if (! Schema::hasTable('sites')) {
                $view->with('platformWidgetSiteKey', null);

                return;
            }

            $platformSiteQuery = Site::query()->orderByDesc('id');

            if (Schema::hasColumn('sites', 'license_status')) {
                $platformSiteQuery->where('license_status', 'active');
            }

            $platformSite = $platformSiteQuery->first();

            $view->with('platformWidgetSiteKey', $platformSite?->public_key);
        });
    }
}
