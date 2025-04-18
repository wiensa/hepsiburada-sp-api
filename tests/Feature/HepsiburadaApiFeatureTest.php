<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Tests\Feature;

test('Servis sağlayıcısı doğru şekilde yüklenmiştir', function () {
    expect($this->app->bound('HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi'))->toBeTrue();
    expect($this->app->bound('hepsiburada-api'))->toBeTrue();
});

test('Facade doğru şekilde çalışır', function () {
    // HepsiburadaApi facade'ina alias üzerinden erişilebilir mi?
    $facade_root = \Illuminate\Support\Facades\Facade::getFacadeRoot('HepsiburadaApi');
    expect($facade_root)->toBeInstanceOf(\HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi::class);
});

test('HepsiburadaApi facade üzerinden servislere erişilebilir', function () {
    // API servislerine erişilebilir mi?
    $categories = \HepsiburadaApi::categories();
    $products = \HepsiburadaApi::products();
    $listings = \HepsiburadaApi::listings();
    $orders = \HepsiburadaApi::orders();
    
    expect($categories)->toBeInstanceOf(\HepsiburadaApi\HepsiburadaSpApi\Services\CategoryService::class);
    expect($products)->toBeInstanceOf(\HepsiburadaApi\HepsiburadaSpApi\Services\ProductService::class);
    expect($listings)->toBeInstanceOf(\HepsiburadaApi\HepsiburadaSpApi\Services\ListingService::class);
    expect($orders)->toBeInstanceOf(\HepsiburadaApi\HepsiburadaSpApi\Services\OrderService::class);
});

test('DI Container üzerinden API nesnesine erişilebilir', function () {
    $api = app(\HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi::class);
    expect($api)->toBeInstanceOf(\HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi::class);
    
    $merchant_id = $api->getMerchantId();
    expect($merchant_id)->toBe('test_merchant_id');
}); 