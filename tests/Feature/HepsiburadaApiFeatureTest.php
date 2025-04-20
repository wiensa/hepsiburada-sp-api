<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Tests\Feature;

use HepsiburadaApi\HepsiburadaSpApi\Contracts\HepsiburadaApiInterface;

test('Servis sağlayıcısı doğru şekilde yüklenmiştir', function () {
    expect($this->app->bound(HepsiburadaApiInterface::class))->toBeTrue();
    expect($this->app->bound('hepsiburada-api'))->toBeTrue();
});

test('Facade doğru şekilde çalışır', function () {
    $api = app(HepsiburadaApiInterface::class);
    expect($api)->toBeInstanceOf(\HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi::class);
});

test('DI Container üzerinden API nesnesine erişilebilir', function () {
    $api = app(HepsiburadaApiInterface::class);
    expect($api)->toBeInstanceOf(\HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi::class);
    
    $merchant_id = $api->getMerchantId();
    expect($merchant_id)->toBe('test_merchant_id');
}); 