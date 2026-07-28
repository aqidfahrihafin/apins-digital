<?php

namespace App\Providers;

use App\Models\Service;
use App\Models\SiteConfigs;
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
        View::composer('layouts.app', function ($view): void {
            $siteConfig = [];
            $footerServices = collect();

            if (Schema::hasTable('site_configs')) {
                $siteConfig = SiteConfigs::query()
                    ->pluck('config_value', 'config_key')
                    ->all();
            }

            if (Schema::hasTable('services')) {
                $footerServices = Service::active()
                    ->orderBy('order_index')
                    ->limit(6)
                    ->get(['name']);
            }

            $view->with(compact('siteConfig', 'footerServices'));
        });
    }
}
