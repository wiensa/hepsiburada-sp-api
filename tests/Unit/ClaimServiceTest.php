<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Tests\Unit;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use HepsiburadaApi\HepsiburadaSpApi\Contracts\HepsiburadaApiInterface;
use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;
use HepsiburadaApi\HepsiburadaSpApi\Services\ClaimService;

test('ClaimService sınıfı doğru şekilde başlatılır', function () {
    $api = new HepsiburadaApi(getConfig());
    $claim_service = new ClaimService($api);

    expect($claim_service)->toBeInstanceOf(ClaimService::class);
});

test('getClaims doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $mock_api = Mockery::mock(HepsiburadaApiInterface::class);
    
    $expected_response = [
        'content' => [
            [
                'id' => 1,
                'claimNumber' => 'CLM12345',
                'claimDate' => '2023-08-01',
                'status' => 'OPEN',
            ],
            [
                'id' => 2,
                'claimNumber' => 'CLM67890',
                'claimDate' => '2023-08-02',
                'status' => 'CLOSED',
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
            '/claims/merchant/list', 
            Mockery::on(function($options) {
                return isset($options['query']['merchantId']) && 
                       $options['query']['merchantId'] === 'test_merchant_id' &&
                       isset($options['query']['page']) &&
                       isset($options['query']['size']);
            })
        )
        ->andReturn($response);
    
    $claim_service = new ClaimService($mock_api);
    $result = $claim_service->getClaims();
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('content');
    expect($result['content'])->toHaveCount(2);
    expect($result['content'][0]['claimNumber'])->toBe('CLM12345');
});

test('getClaimDetails doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $mock_api = Mockery::mock(HepsiburadaApiInterface::class);
    
    $expected_response = [
        'claimNumber' => 'CLM12345',
        'claimDate' => '2023-08-01',
        'status' => 'OPEN',
        'reason' => 'Ürün hasarlı geldi',
        'orderNumber' => 'ORD123456',
        'customerNotes' => 'Kutu içinde hasarlıydı',
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
            '/claims/merchant/details', 
            Mockery::on(function($options) {
                return isset($options['query']['merchantId']) && 
                       $options['query']['merchantId'] === 'test_merchant_id' &&
                       isset($options['query']['claimId']) &&
                       $options['query']['claimId'] === 'CLM12345';
            })
        )
        ->andReturn($response);
    
    $claim_service = new ClaimService($mock_api);
    $result = $claim_service->getClaimDetails('CLM12345');
    
    expect($result)->toBeArray();
    expect($result['claimNumber'])->toBe('CLM12345');
    expect($result['reason'])->toBe('Ürün hasarlı geldi');
});

test('respondToClaim doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $mock_api = Mockery::mock(HepsiburadaApiInterface::class);
    
    $expected_response = [
        'success' => true,
        'message' => 'Talep yanıtı başarıyla gönderildi',
    ];
    
    $response = new Response(200, [], json_encode($expected_response));
    
    $mock_api->shouldReceive('getHttpClient')
        ->andReturn($mock_client);
    
    $mock_api->shouldReceive('getMerchantId')
        ->andReturn('test_merchant_id');
    
    $claim_data = [
        'merchantId' => 'test_merchant_id',
        'claimNumber' => 'CLM12345',
        'responseType' => 'ACCEPT',
        'notes' => 'Müşteri haklı, ürün değişimi yapılacak',
    ];
    
    $mock_client->shouldReceive('request')
        ->once()
        ->with(
            'POST', 
            '/claims/merchant/respond', 
            Mockery::on(function($options) use ($claim_data) {
                return isset($options['json']) && 
                       $options['json'] === $claim_data;
            })
        )
        ->andReturn($response);
    
    $claim_service = new ClaimService($mock_api);
    $result = $claim_service->respondToClaim($claim_data);
    
    expect($result)->toBeArray();
    expect($result['success'])->toBeTrue();
    expect($result['message'])->toBe('Talep yanıtı başarıyla gönderildi');
}); 