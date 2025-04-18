<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Tests\Unit;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;
use HepsiburadaApi\HepsiburadaSpApi\Services\OrderService;

test('OrderService sınıfı doğru şekilde başlatılır', function () {
    $api = new HepsiburadaApi(getConfig());
    $order_service = new OrderService($api);

    expect($order_service)->toBeInstanceOf(OrderService::class);
});

test('getCompletedOrders doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $mock_api = Mockery::mock(HepsiburadaApi::class);
    
    $expected_response = [
        'content' => [
            [
                'id' => 1,
                'orderNumber' => 'HBT12345',
                'orderDate' => '2023-08-01',
                'status' => 'COMPLETED',
            ],
            [
                'id' => 2,
                'orderNumber' => 'HBT67890',
                'orderDate' => '2023-08-02',
                'status' => 'COMPLETED',
            ],
        ],
        'totalElements' => 2,
        'totalPages' => 1,
        'pageNumber' => 0,
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
            '/orders/merchant/completed-orders', 
            Mockery::on(function($options) {
                return isset($options['query']['merchantId']) && 
                       $options['query']['merchantId'] === 'test_merchant_id' &&
                       isset($options['query']['page']) &&
                       isset($options['query']['size']);
            })
        )
        ->andReturn($response);
    
    $order_service = new OrderService($mock_api);
    $result = $order_service->getCompletedOrders();
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('content');
    expect($result['content'])->toHaveCount(2);
    expect($result['content'][0]['orderNumber'])->toBe('HBT12345');
});

test('getOrderDetails doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $mock_api = Mockery::mock(HepsiburadaApi::class);
    
    $expected_response = [
        'orderNumber' => 'HBT12345',
        'orderDate' => '2023-08-01',
        'status' => 'COMPLETED',
        'items' => [
            [
                'id' => 101,
                'productName' => 'Test Ürün',
                'quantity' => 2,
                'price' => 149.99,
            ]
        ],
        'buyer' => [
            'name' => 'Test Müşteri',
            'email' => 'test@example.com',
        ],
        'shippingAddress' => [
            'city' => 'Istanbul',
            'district' => 'Kadıköy',
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
            '/orders/merchant/order-detail', 
            Mockery::on(function($options) {
                return isset($options['query']['merchantId']) && 
                       $options['query']['merchantId'] === 'test_merchant_id' &&
                       isset($options['query']['orderNumber']) &&
                       $options['query']['orderNumber'] === 'HBT12345';
            })
        )
        ->andReturn($response);
    
    $order_service = new OrderService($mock_api);
    $result = $order_service->getOrderDetails('HBT12345');
    
    expect($result)->toBeArray();
    expect($result['orderNumber'])->toBe('HBT12345');
    expect($result['items'])->toHaveCount(1);
    expect($result['buyer']['name'])->toBe('Test Müşteri');
});

test('markAsShipped doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $mock_api = Mockery::mock(HepsiburadaApi::class);
    
    $expected_response = [
        'success' => true,
        'message' => 'Package has been marked as shipped',
    ];
    
    $response = new Response(200, [], json_encode($expected_response));
    
    $mock_api->shouldReceive('getHttpClient')
        ->andReturn($mock_client);
    
    $shipping_data = [
        'merchantId' => 'test_merchant_id',
        'packageNumber' => 'PKG12345',
        'shippingCompany' => 'Test Kargo',
        'trackingNumber' => 'TRK789012',
    ];
    
    $mock_client->shouldReceive('request')
        ->once()
        ->with(
            'POST', 
            '/orders/merchant/update-package-status/intransit', 
            Mockery::on(function($options) use ($shipping_data) {
                return isset($options['json']) && 
                       $options['json'] === $shipping_data;
            })
        )
        ->andReturn($response);
    
    $order_service = new OrderService($mock_api);
    $result = $order_service->markAsShipped($shipping_data);
    
    expect($result)->toBeArray();
    expect($result['success'])->toBeTrue();
    expect($result['message'])->toBe('Package has been marked as shipped');
}); 