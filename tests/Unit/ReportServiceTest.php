<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Tests\Unit;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use HepsiburadaApi\HepsiburadaSpApi\Contracts\HepsiburadaApiInterface;
use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;
use HepsiburadaApi\HepsiburadaSpApi\Services\ReportService;

test('ReportService sınıfı doğru şekilde başlatılır', function () {
    $api = new HepsiburadaApi(getConfig());
    $report_service = new ReportService($api);

    expect($report_service)->toBeInstanceOf(ReportService::class);
});

test('getSalesPerformance doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $mock_api = Mockery::mock(HepsiburadaApiInterface::class);
    
    $expected_response = [
        'reports' => [
            [
                'id' => 1,
                'name' => 'Satış Raporu',
                'type' => 'SALES',
                'description' => 'Günlük satış raporu',
            ],
            [
                'id' => 2,
                'name' => 'Envanter Raporu',
                'type' => 'INVENTORY',
                'description' => 'Güncel stok raporu',
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
            '/reports/merchant/sales-performance', 
            Mockery::on(function($options) {
                return isset($options['query']['merchantId']) && 
                       $options['query']['merchantId'] === 'test_merchant_id';
            })
        )
        ->andReturn($response);
    
    $report_service = new ReportService($mock_api);
    $result = $report_service->getSalesPerformance();
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('reports');
    expect($result['reports'])->toHaveCount(2);
    expect($result['reports'][0]['name'])->toBe('Satış Raporu');
});

test('createCustomReport doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $mock_api = Mockery::mock(HepsiburadaApiInterface::class);
    
    $expected_response = [
        'success' => true,
        'message' => 'Rapor oluşturma talebi alındı',
        'reportId' => 'RPT12345',
        'estimatedCompletionTime' => '2023-08-01T15:30:00',
    ];
    
    $response = new Response(200, [], json_encode($expected_response));
    
    $mock_api->shouldReceive('getHttpClient')
        ->andReturn($mock_client);
    
    $mock_api->shouldReceive('getMerchantId')
        ->andReturn('test_merchant_id');
    
    $report_request = [
        'merchantId' => 'test_merchant_id',
        'reportType' => 'SALES',
        'startDate' => '2023-08-01',
        'endDate' => '2023-08-31',
        'format' => 'CSV',
    ];
    
    $mock_client->shouldReceive('request')
        ->once()
        ->with(
            'POST', 
            '/reports/merchant/custom-report', 
            Mockery::on(function($options) use ($report_request) {
                return isset($options['json']) && 
                       $options['json'] === $report_request;
            })
        )
        ->andReturn($response);
    
    $report_service = new ReportService($mock_api);
    $result = $report_service->createCustomReport($report_request);
    
    expect($result)->toBeArray();
    expect($result['success'])->toBeTrue();
    expect($result['reportId'])->toBe('RPT12345');
});

test('getReportStatus doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $mock_api = Mockery::mock(HepsiburadaApiInterface::class);
    
    $expected_response = [
        'reportId' => 'RPT12345',
        'status' => 'COMPLETED',
        'createdAt' => '2023-08-01T14:30:00',
        'completedAt' => '2023-08-01T14:35:00',
        'downloadUrl' => 'https://example.com/reports/RPT12345.csv',
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
            '/reports/merchant/status', 
            Mockery::on(function($options) {
                return isset($options['query']['merchantId']) && 
                       $options['query']['merchantId'] === 'test_merchant_id' &&
                       isset($options['query']['reportId']) &&
                       $options['query']['reportId'] === 'RPT12345';
            })
        )
        ->andReturn($response);
    
    $report_service = new ReportService($mock_api);
    $result = $report_service->getReportStatus('RPT12345');
    
    expect($result)->toBeArray();
    expect($result['reportId'])->toBe('RPT12345');
    expect($result['status'])->toBe('COMPLETED');
    expect($result['downloadUrl'])->toBe('https://example.com/reports/RPT12345.csv');
}); 