<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Providers;

use Illuminate\Support\ServiceProvider;
use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;

class HepsiburadaApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishConfig();
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerBindings();
    }

    // Boot methods :
    private function publishConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                dirname(__DIR__, 2).'/config/hepsiburada-api.php' => config_path('hepsiburada-api.php'),
            ], 'hepsiburada-api-config');
        }
    }

    // Register methods :
    private function registerConfig(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__, 2).'/config/hepsiburada-api.php', 'hepsiburada-api');
    }

    private function registerBindings(): void
    {
        $this->app->singleton(HepsiburadaApi::class, function ($app) {
            return new HepsiburadaApi(config('hepsiburada-api'));
        });
    }
}
