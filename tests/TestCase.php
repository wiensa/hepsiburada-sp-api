<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use HepsiburadaApi\HepsiburadaSpApi\Providers\HepsiburadaApiServiceProvider;

class TestCase extends Orchestra
{
    /**
     * Test ortamını ayarlar
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Laravel servis sağlayıcılarını ayarlar
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            HepsiburadaApiServiceProvider::class,
        ];
    }

    /**
     * Laravel Facades'ları ayarlar
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app): array
    {
        return [
            'HepsiburadaApi' => \HepsiburadaApi\HepsiburadaSpApi\Facades\HepsiburadaApi::class,
        ];
    }

    /**
     * Test ortamı için çevre ayarlarını tanımlar
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('hepsiburada-api.base_url', 'https://marketplace-api.hepsiburada.com');
        $app['config']->set('hepsiburada-api.username', 'test_username');
        $app['config']->set('hepsiburada-api.password', 'test_password');
        $app['config']->set('hepsiburada-api.merchant_id', 'test_merchant_id');
        $app['config']->set('hepsiburada-api.timeout', 5);
        $app['config']->set('hepsiburada-api.connect_timeout', 3);
        $app['config']->set('hepsiburada-api.retry_attempts', 1);
    }

    /**
     * Mock HTTP yanıtı oluşturur
     *
     * @param int $status_code HTTP durum kodu
     * @param array $data Yanıt verileri
     * @return \GuzzleHttp\Psr7\Response
     */
    protected function mockResponse(int $status_code = 200, array $data = ['success' => true]): \GuzzleHttp\Psr7\Response
    {
        return new \GuzzleHttp\Psr7\Response(
            $status_code,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );
    }

    /**
     * Sahte HTTP istemcisi oluşturur
     *
     * @param array $responses Yanıt koleksiyonu
     * @return \GuzzleHttp\Client
     */
    protected function mockHttpClient(array $responses = []): \GuzzleHttp\Client
    {
        $mock = new \GuzzleHttp\Handler\MockHandler($responses);
        $handler = \GuzzleHttp\HandlerStack::create($mock);
        
        return new \GuzzleHttp\Client(['handler' => $handler]);
    }
} 