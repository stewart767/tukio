<?php

namespace App\Providers {

    use App\Services\SystemSettingsService;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\View;
    use Illuminate\Support\ServiceProvider;

    class AppServiceProvider extends ServiceProvider
    {
        /**
         * Register any application services.
         */
        public function register(): void
        {
            $this->app->singleton(SystemSettingsService::class, function () {
                return new SystemSettingsService();
            });
        }

        /**
         * Bootstrap any application services.
         */
        public function boot(): void
        {
            // Hydrate settings and share with views
            $this->hydrateAndShareSettings();
        }

        protected function hydrateAndShareSettings(): void
        {
            try {
                /** @var SystemSettingsService $settingsService */
                $settingsService = $this->app->make(SystemSettingsService::class);
                $settingsService->hydrateRuntimeConfig();

                View::share('systemSettings', $settingsService->getPublicPayload());
            } catch (\Throwable $e) {
                Log::warning('Unable to hydrate system settings', [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}

namespace {
    if (!function_exists('storage_url')) {
        function storage_url($path)
        {
            if (empty($path)) {
                return '';
            }

            // If already a full URL, return as-is
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
                return $path;
            }

            // If already begins with asset paths like media/ or storage/
            if (str_starts_with($path, 'media/')) {
                return asset($path);
            }

            if (str_starts_with($path, 'storage/')) {
                return asset($path);
            }

            // Normalize path - remove 'public/' prefix if present
            $normalized = preg_replace('#^public/#', '', (string) $path);
            $normalized = ltrim($normalized, '/');

            // Use asset() for consistent relative URLs that work with .htaccess routing
            return asset('storage/' . $normalized);
        }
    }
}
