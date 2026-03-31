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

            $configuredSiteKey = config('services.a11y_bridge.platform_widget_site_key');
            $requestHost = $this->normalizeHost(request()->getHost() ?? '');

            $platformSite = null;

            if (is_string($configuredSiteKey) && trim($configuredSiteKey) !== '') {
                $platformSite = Site::query()
                    ->where('public_key', trim($configuredSiteKey))
                    ->first();
            }

            if (! $platformSite && $requestHost !== '') {
                $platformSite = Site::query()
                    ->orderByDesc('id')
                    ->get()
                    ->first(function (Site $site) use ($requestHost) {
                        $siteHost = $this->normalizeHost(parse_url($site->domain, PHP_URL_HOST) ?: $site->domain);

                        if ($siteHost === '' || $siteHost !== $requestHost) {
                            return false;
                        }

                        if (Schema::hasColumn('sites', 'license_status') && ($site->license_status ?? 'active') !== 'active') {
                            return false;
                        }

                        return true;
                    });
            }

            $view->with('platformWidgetSiteKey', $platformSite?->public_key);
        });
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
