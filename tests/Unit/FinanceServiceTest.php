<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Tests\Unit;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use HepsiburadaApi\HepsiburadaSpApi\Contracts\HepsiburadaApiInterface;
use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;
use HepsiburadaApi\HepsiburadaSpApi\Services\FinanceService;

test('FinanceService sınıfı doğru şekilde başlatılır', function () {
    $api = new HepsiburadaApi(getConfig());
    $finance_service = new FinanceService($api);

    expect($finance_service)->toBeInstanceOf(FinanceService::class);
});

test('getTransactions doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $mock_api = Mockery::mock(HepsiburadaApiInterface::class);
    
    $expected_response = [
        'content' => [
            [
                'id' => 1,
                'transactionId' => 'TRX12345',
                'transactionDate' => '2023-08-01',
                'amount' => 149.99,
                'type' => 'SALE',
            ],
            [
                'id' => 2,
                'transactionId' => 'TRX67890',
                'transactionDate' => '2023-08-02',
                'amount' => -15.50,
                'type' => 'COMMISSION',
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
            '/finance/merchant/transactions', 
            Mockery::on(function($options) {
                return isset($options['query']['merchantId']) && 
                       $options['query']['merchantId'] === 'test_merchant_id' &&
                       isset($options['query']['page']) &&
                       isset($options['query']['size']) &&
                       isset($options['query']['startDate']) &&
                       isset($options['query']['endDate']);
            })
        )
        ->andReturn($response);
    
    $finance_service = new FinanceService($mock_api);
    $result = $finance_service->getTransactions([
        'startDate' => '2023-08-01',
        'endDate' => '2023-08-31',
    ]);
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('content');
    expect($result['content'])->toHaveCount(2);
    expect($result['content'][0]['transactionId'])->toBe('TRX12345');
});

test('getPaymentSummary doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $mock_api = Mockery::mock(HepsiburadaApiInterface::class);
    
    $expected_response = [
        'balance' => 1235.75,
        'currency' => 'TRY',
        'lastUpdateDate' => '2023-08-15',
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
            '/finance/merchant/payment-summary', 
            Mockery::on(function($options) {
                return isset($options['query']['merchantId']) && 
                       $options['query']['merchantId'] === 'test_merchant_id';
            })
        )
        ->andReturn($response);
    
    $finance_service = new FinanceService($mock_api);
    $result = $finance_service->getPaymentSummary();
    
    expect($result)->toBeArray();
    expect($result['balance'])->toBe(1235.75);
    expect($result['currency'])->toBe('TRY');
});

test('getPaymentDetails doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $mock_api = Mockery::mock(HepsiburadaApiInterface::class);
    
    $expected_response = [
        'success' => true,
        'message' => 'Ödeme detayları',
        'paymentId' => 'PAY12345',
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
            '/finance/merchant/payment-details', 
            Mockery::on(function($options) {
                return isset($options['query']['merchantId']) && 
                       $options['query']['merchantId'] === 'test_merchant_id' &&
                       isset($options['query']['paymentId']) &&
                       $options['query']['paymentId'] === 'PAY12345';
            })
        )
        ->andReturn($response);
    
    $finance_service = new FinanceService($mock_api);
    $result = $finance_service->getPaymentDetails('PAY12345');
    
    expect($result)->toBeArray();
    expect($result['success'])->toBeTrue();
    expect($result['paymentId'])->toBe('PAY12345');
}); 