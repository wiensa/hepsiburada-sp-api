<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Providers;

use Illuminate\Support\ServiceProvider;
use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;
use HepsiburadaApi\HepsiburadaSpApi\Contracts\HepsiburadaApiInterface;
use HepsiburadaApi\HepsiburadaSpApi\Console\InstallCommand;
use HepsiburadaApi\HepsiburadaSpApi\Console\TestConnectionCommand;

class HepsiburadaApiServiceProvider extends ServiceProvider
{
    /**
     * Servis sağlayıcısına ilişkilendirilmiş konfigürasyon dosyalarının yolu
     * 
     * @var string
     */
    protected string $config_path;

    /**
     * Servis sağlayıcısı örneği oluşturur
     * 
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct($app)
    {
        parent::__construct($app);
        $this->config_path = dirname(__DIR__, 2) . '/config/hepsiburada-api.php';
    }

    /**
     * Bootstrap metodu - paketi uygulama için hazırlar
     * 
     * @return void
     */
    public function boot(): void
    {
        $this->publishConfig();
        $this->registerCommands();
    }

    /**
     * Register metodu - paketin servislerini kaydeder
     * 
     * @return void
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerBindings();
    }

    /**
     * Konfigürasyon dosyalarını yayınlar
     * 
     * @return void
     */
    private function publishConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->config_path => config_path('hepsiburada-api.php'),
            ], 'hepsiburada-api-config');
        }
    }

    /**
     * Artisan komutlarını kaydeder
     * 
     * @return void
     */
    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                TestConnectionCommand::class,
            ]);
        }
    }

    /**
     * Konfigürasyon dosyalarını kaydeder
     * 
     * @return void
     */
    private function registerConfig(): void
    {
        $this->mergeConfigFrom($this->config_path, 'hepsiburada-api');
    }

    /**
     * Servis bağlamalarını kaydeder
     * 
     * @return void
     */
    private function registerBindings(): void
    {
        $this->app->singleton(HepsiburadaApiInterface::class, function ($app) {
            return new HepsiburadaApi(config('hepsiburada-api'));
        });

        // HepsiburadaApi sınıfını da kaydeder
        $this->app->singleton(HepsiburadaApi::class, function ($app) {
            return $app->make(HepsiburadaApiInterface::class);
        });

        // Paketin alias olarak kısa yoldan erişimini sağlar
        $this->app->alias(HepsiburadaApiInterface::class, 'hepsiburada-api');
    }
}
