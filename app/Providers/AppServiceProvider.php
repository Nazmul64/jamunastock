<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('site_settings')) {
                $siteTitle = setting('title');
                if ($siteTitle) {
                    config(['app.name' => $siteTitle]);
                }
            }
        } catch (\Throwable $e) {
            // Silence exceptions during initial migrations or CLI commands
        }
    }
}
