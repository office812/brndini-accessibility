<?php

namespace App\Providers;

use App\Http\Controllers\PublicWidgetController;
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
            $view->with('globalTrackingScripts', $this->globalTrackingScripts());

            if (! Schema::hasTable('sites')) {
                $view->with('platformWidgetSiteKey', $this->platformWidgetSiteKey());

                return;
            }

            $configuredSiteKey = $this->platformWidgetSiteKey();
            $platformSite = null;

            if ($configuredSiteKey !== PublicWidgetController::PLATFORM_WIDGET_KEY) {
                $platformSite = Site::query()
                    ->where('public_key', $configuredSiteKey)
                    ->first();
            }

            $view->with('platformWidgetSiteKey', $platformSite?->public_key ?: PublicWidgetController::PLATFORM_WIDGET_KEY);
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
        // Use platform.css modification time as version — updates automatically on each deploy
        $cssPath = public_path('platform.css');
        if (File::exists($cssPath)) {
            return (string) File::lastModified($cssPath);
        }

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

    private function platformWidgetSiteKey(): string
    {
        $configuredSiteKey = trim((string) config('services.a11y_bridge.platform_widget_site_key'));

        return $configuredSiteKey !== '' ? $configuredSiteKey : PublicWidgetController::PLATFORM_WIDGET_KEY;
    }
}
