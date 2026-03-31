<?php

namespace App\Providers;

use App\Models\AppSetting;
use App\Models\Site;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

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
        $assetVersion = $this->assetVersion();
        $this->syncDeploymentCaches($assetVersion);

        View::composer(['layouts.app', 'layouts.auth'], function ($view) {
            $view->with('assetVersion', $this->assetVersion());

            if (! Schema::hasTable('sites')) {
                $view->with('platformWidgetSiteKey', null);
                $view->with('globalTrackingScripts', $this->globalTrackingScripts());

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
            $view->with('globalTrackingScripts', $this->globalTrackingScripts());
        });
    }

    private function syncDeploymentCaches(?string $commit): void
    {
        if ($this->app->runningInConsole() || blank($commit)) {
            return;
        }

        $markerPath = storage_path('framework/a11y-bridge-deploy-version');
        $previousCommit = File::exists($markerPath) ? trim((string) File::get($markerPath)) : null;

        if ($previousCommit === $commit) {
            return;
        }

        try {
            Artisan::call('optimize:clear');
        } catch (Throwable $exception) {
            report($exception);
        }

        File::ensureDirectoryExists(dirname($markerPath));
        File::put($markerPath, $commit);
    }

    private function assetVersion(): ?string
    {
        $headPath = base_path('.git/HEAD');

        if (! File::exists($headPath)) {
            return null;
        }

        $head = trim((string) File::get($headPath));

        if ($head === '') {
            return null;
        }

        if (str_starts_with($head, 'ref: ')) {
            $refPath = base_path('.git/' . trim(substr($head, 5)));

            if (! File::exists($refPath)) {
                return null;
            }

            return trim((string) File::get($refPath));
        }

        return $head;
    }

    private function globalTrackingScripts(): array
    {
        return AppSetting::getMany([
            'google_analytics_head',
            'google_tag_manager_head',
            'google_tag_manager_body',
            'meta_pixel_head',
            'custom_head_scripts',
            'custom_body_scripts',
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
