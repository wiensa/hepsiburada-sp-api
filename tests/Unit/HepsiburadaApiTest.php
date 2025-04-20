<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Tests\Unit;

use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;
use HepsiburadaApi\HepsiburadaSpApi\Services\CategoryService;
use HepsiburadaApi\HepsiburadaSpApi\Services\ProductService;
use HepsiburadaApi\HepsiburadaSpApi\Services\ListingService;
use HepsiburadaApi\HepsiburadaSpApi\Services\OrderService;
use HepsiburadaApi\HepsiburadaSpApi\Services\ClaimService;
use HepsiburadaApi\HepsiburadaSpApi\Services\FinanceService;
use HepsiburadaApi\HepsiburadaSpApi\Services\ReportService;
use HepsiburadaApi\HepsiburadaSpApi\Services\LogisticsService;

test('HepsiburadaApi sınıfı doğru şekilde başlatılır', function () {
    $config = getConfig();
    $api = new HepsiburadaApi($config);

    expect($api)->toBeInstanceOf(HepsiburadaApi::class);
    expect($api->getMerchantId())->toBe($config['merchant_id']);
    expect($api->getHttpClient())->toBeInstanceOf(\GuzzleHttp\Client::class);
});

test('HepsiburadaApi servis metodları doğru tipte nesneler döndürür', function () {
    $api = new HepsiburadaApi(getConfig());

    expect($api->categories())->toBeInstanceOf(CategoryService::class);
    expect($api->products())->toBeInstanceOf(ProductService::class);
    expect($api->listings())->toBeInstanceOf(ListingService::class);
    expect($api->orders())->toBeInstanceOf(OrderService::class);
    expect($api->claims())->toBeInstanceOf(ClaimService::class);
    expect($api->finances())->toBeInstanceOf(FinanceService::class);
    expect($api->reports())->toBeInstanceOf(ReportService::class);
    expect($api->logistics())->toBeInstanceOf(LogisticsService::class);
});

test('HepsiburadaApi reconnect metoduyla kimlik bilgileri değiştirilebilir', function () {
    $api = new HepsiburadaApi(getConfig());
    $original_merchant_id = $api->getMerchantId();

    $new_merchant_id = 'new_merchant_id';
    $api->reconnect(null, null, $new_merchant_id);

    expect($api->getMerchantId())->toBe($new_merchant_id);
    expect($api->getMerchantId())->not->toBe($original_merchant_id);
});

test('Servis sınıfları tekrar tekrar çağrılsa bile aynı örnek döndürülür', function () {
    $api = new HepsiburadaApi(getConfig());

    $categories1 = $api->categories();
    $categories2 = $api->categories();

    $products1 = $api->products();
    $products2 = $api->products();

    $listings1 = $api->listings();
    $listings2 = $api->listings();

    $orders1 = $api->orders();
    $orders2 = $api->orders();
    
    $claims1 = $api->claims();
    $claims2 = $api->claims();
    
    $finances1 = $api->finances();
    $finances2 = $api->finances();
    
    $reports1 = $api->reports();
    $reports2 = $api->reports();
    
    $logistics1 = $api->logistics();
    $logistics2 = $api->logistics();

    expect($categories1)->toBe($categories2);
    expect($products1)->toBe($products2);
    expect($listings1)->toBe($listings2);
    expect($orders1)->toBe($orders2);
    expect($claims1)->toBe($claims2);
    expect($finances1)->toBe($finances2);
    expect($reports1)->toBe($reports2);
    expect($logistics1)->toBe($logistics2);
});

test('Reconnect sonrası servis örnekleri temizlenir ve yeniden oluşturulur', function () {
    $api = new HepsiburadaApi(getConfig());

    $categories1 = $api->categories();
    $products1 = $api->products();
    $claims1 = $api->claims();
    $finances1 = $api->finances();
    
    $api->reconnect('new_username', 'new_password');

    $categories2 = $api->categories();
    $products2 = $api->products();
    $claims2 = $api->claims();
    $finances2 = $api->finances();

    expect($categories1)->not->toBe($categories2);
    expect($products1)->not->toBe($products2);
    expect($claims1)->not->toBe($claims2);
    expect($finances1)->not->toBe($finances2);
}); 