<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Tests\Unit;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use HepsiburadaApi\HepsiburadaSpApi\Contracts\HepsiburadaApiInterface;
use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;
use HepsiburadaApi\HepsiburadaSpApi\Services\LogisticsService;

test('LogisticsService sınıfı doğru şekilde başlatılır', function () {
    $api = new HepsiburadaApi(getConfig());
    $logistics_service = new LogisticsService($api);

    expect($logistics_service)->toBeInstanceOf(LogisticsService::class);
});

test('getCarriers doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $mock_api = Mockery::mock(HepsiburadaApiInterface::class);
    
    $expected_response = [
        'companies' => [
            [
                'id' => 1,
                'name' => 'Aras Kargo',
                'code' => 'ARAS',
                'trackingUrlPattern' => 'https://kargotakip.aras.com.tr?id={tracking_number}',
            ],
            [
                'id' => 2,
                'name' => 'Yurtiçi Kargo',
                'code' => 'YK',
                'trackingUrlPattern' => 'https://www.yurticikargo.com/track?code={tracking_number}',
            ],
        ],
    ];
    
    $response = new Response(200, [], json_encode($expected_response));
    
    $mock_api->shouldReceive('getHttpClient')
        ->andReturn($mock_client);
    
    $mock_api->shouldReceive('getMerchantId')
        ->andReturn('test_merchant_id');
    
    $mock_client->shouldReceive('request')
        ->once()
        ->with(
            'GET', 
            '/logistics/merchant/carriers', 
            Mockery::on(function($options) {
                return isset($options['query']['merchantId']) && 
                       $options['query']['merchantId'] === 'test_merchant_id';
            })
        )
        ->andReturn($response);
    
    $logistics_service = new LogisticsService($mock_api);
    $result = $logistics_service->getCarriers();
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('companies');
    expect($result['companies'])->toHaveCount(2);
    expect($result['companies'][0]['name'])->toBe('Aras Kargo');
});

test('getShippingRates doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $mock_api = Mockery::mock(HepsiburadaApiInterface::class);
    
    $expected_response = [
        'rates' => [
            [
                'id' => 1,
                'shippingCompanyId' => 1,
                'regionCode' => 'TR-34',
                'weightRangeMin' => 0,
                'weightRangeMax' => 5,
                'rate' => 15.90,
            ],
            [
                'id' => 2,
                'shippingCompanyId' => 1,
                'regionCode' => 'TR-34',
                'weightRangeMin' => 5,
                'weightRangeMax' => 10,
                'rate' => 22.50,
            ],
        ],
    ];
    
    $response = new Response(200, [], json_encode($expected_response));
    
    $mock_api->shouldReceive('getHttpClient')
        ->andReturn($mock_client);
    
    $mock_api->shouldReceive('getMerchantId')
        ->andReturn('test_merchant_id');
    
    $mock_client->shouldReceive('request')
        ->once()
        ->with(
            'GET', 
            '/logistics/merchant/shipping-rates', 
            Mockery::on(function($options) {
                return isset($options['query']['merchantId']) && 
                       $options['query']['merchantId'] === 'test_merchant_id' &&
                       isset($options['query']['shippingCompanyId']) &&
                       $options['query']['shippingCompanyId'] === '1';
            })
        )
        ->andReturn($response);
    
    $logistics_service = new LogisticsService($mock_api);
    $result = $logistics_service->getShippingRates(['shippingCompanyId' => '1']);
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('rates');
    expect($result['rates'])->toHaveCount(2);
    expect($result['rates'][0]['rate'])->toBe(15.90);
});

test('createShippingLabel doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $mock_api = Mockery::mock(HepsiburadaApiInterface::class);
    
    $expected_response = [
        'success' => true,
        'message' => 'Kargo etiketi başarıyla oluşturuldu',
        'trackingNumber' => 'TRK123456',
        'labelUrl' => 'https://example.com/labels/TRK123456.pdf',
    ];
    
    $response = new Response(200, [], json_encode($expected_response));
    
    $mock_api->shouldReceive('getHttpClient')
        ->andReturn($mock_client);
    
    $mock_api->shouldReceive('getMerchantId')
        ->andReturn('test_merchant_id');
    
    $label_data = [
        'merchantId' => 'test_merchant_id',
        'orderId' => 'ORD123456',
        'packageNumber' => 'PKG123456',
        'shippingCompanyId' => 1,
    ];
    
    $mock_client->shouldReceive('request')
        ->once()
        ->with(
            'POST', 
            '/logistics/merchant/shipping-label', 
            Mockery::on(function($options) use ($label_data) {
                return isset($options['json']) && 
                       $options['json'] === $label_data;
            })
        )
        ->andReturn($response);
    
    $logistics_service = new LogisticsService($mock_api);
    $result = $logistics_service->createShippingLabel($label_data);
    
    expect($result)->toBeArray();
    expect($result['success'])->toBeTrue();
    expect($result['trackingNumber'])->toBe('TRK123456');
    expect($result['labelUrl'])->toBe('https://example.com/labels/TRK123456.pdf');
}); 