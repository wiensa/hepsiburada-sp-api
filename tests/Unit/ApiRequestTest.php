<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Tests\Unit;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use HepsiburadaApi\HepsiburadaSpApi\Traits\ApiRequest;

// ApiRequest trait'ini test etmek için geçici sınıf
class ApiRequestTestClass
{
    use ApiRequest;

    public $http_client;

    public function __construct(Client $client)
    {
        $this->http_client = $client;
    }

    public function testGet(string $endpoint, array $query = [])
    {
        return $this->get($endpoint, $query);
    }

    public function testPost(string $endpoint, array $data = [])
    {
        return $this->post($endpoint, $data);
    }

    public function testPut(string $endpoint, array $data = [])
    {
        return $this->put($endpoint, $data);
    }

    public function testDelete(string $endpoint, array $data = [])
    {
        return $this->delete($endpoint, $data);
    }
}

test('GET isteği doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $response = new Response(200, [], json_encode(['success' => true, 'data' => ['test' => 'value']]));

    $mock_client->shouldReceive('request')
        ->once()
        ->with('GET', '/test-endpoint', Mockery::type('array'))
        ->andReturn($response);

    $api_request = new ApiRequestTestClass($mock_client);
    $result = $api_request->testGet('/test-endpoint', ['param' => 'value']);

    expect($result)->toBeArray();
    expect($result)->toHaveKey('success');
    expect($result['success'])->toBeTrue();
    expect($result['data'])->toHaveKey('test');
    expect($result['data']['test'])->toBe('value');
});

test('POST isteği doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $response = new Response(200, [], json_encode(['success' => true]));

    $mock_client->shouldReceive('request')
        ->once()
        ->with('POST', '/test-endpoint', Mockery::type('array'))
        ->andReturn($response);

    $api_request = new ApiRequestTestClass($mock_client);
    $result = $api_request->testPost('/test-endpoint', ['data' => 'value']);

    expect($result)->toBeArray();
    expect($result)->toHaveKey('success');
    expect($result['success'])->toBeTrue();
});

test('PUT isteği doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $response = new Response(200, [], json_encode(['success' => true]));

    $mock_client->shouldReceive('request')
        ->once()
        ->with('PUT', '/test-endpoint', Mockery::type('array'))
        ->andReturn($response);

    $api_request = new ApiRequestTestClass($mock_client);
    $result = $api_request->testPut('/test-endpoint', ['data' => 'value']);

    expect($result)->toBeArray();
    expect($result)->toHaveKey('success');
    expect($result['success'])->toBeTrue();
});

test('DELETE isteği doğru şekilde çalışır', function () {
    $mock_client = Mockery::mock(Client::class);
    $response = new Response(200, [], json_encode(['success' => true]));

    $mock_client->shouldReceive('request')
        ->once()
        ->with('DELETE', '/test-endpoint', Mockery::type('array'))
        ->andReturn($response);

    $api_request = new ApiRequestTestClass($mock_client);
    $result = $api_request->testDelete('/test-endpoint', ['id' => 1]);

    expect($result)->toBeArray();
    expect($result)->toHaveKey('success');
    expect($result['success'])->toBeTrue();
});

test('Bağlantı hatası durumunda yeniden deneme yapılır', function () {
    // Düzgün çalışan log facade mocklaması
    $this->mock(\Illuminate\Support\Facades\Log::class, function ($mock) {
        $mock->shouldReceive('warning')->andReturn(true);
        $mock->shouldReceive('error')->andReturn(true);
    });

    // Config mocklaması
    $this->mock('config', function ($mock) {
        $mock->shouldReceive('get')
            ->with('hepsiburada-api.retry_attempts', Mockery::any())
            ->andReturn(2);
        $mock->shouldReceive('get')
            ->with('hepsiburada-api.retry_delay', Mockery::any())
            ->andReturn(1);
        $mock->shouldReceive('get')
            ->with('hepsiburada-api.timeout', Mockery::any())
            ->andReturn(5);
        $mock->shouldReceive('get')
            ->with('hepsiburada-api.connect_timeout', Mockery::any())
            ->andReturn(3);
    });

    $mock_client = Mockery::mock(Client::class);
    $request = new Request('GET', '/test-endpoint');
    $exception = new ConnectException('Connection timed out', $request);
    $response = new Response(200, [], json_encode(['success' => true]));

    $mock_client->shouldReceive('request')
        ->once()  // İlk istek
        ->andThrow($exception);

    $mock_client->shouldReceive('request')
        ->once()  // Yeniden deneme isteği
        ->andReturn($response);

    $api_request = new ApiRequestTestClass($mock_client);
    $result = $api_request->testGet('/test-endpoint');

    expect($result)->toBeArray();
    expect($result)->toHaveKey('success');
    expect($result['success'])->toBeTrue();
});

test('Yeniden deneme sayısı aşıldığında null döndürülür', function () {
    // Düzgün çalışan log facade mocklaması
    $this->mock(\Illuminate\Support\Facades\Log::class, function ($mock) {
        $mock->shouldReceive('warning')->andReturn(true);
        $mock->shouldReceive('error')->andReturn(true);
    });

    // Config mocklaması
    $this->mock('config', function ($mock) {
        $mock->shouldReceive('get')
            ->with('hepsiburada-api.retry_attempts', Mockery::any())
            ->andReturn(1);
        $mock->shouldReceive('get')
            ->with('hepsiburada-api.retry_delay', Mockery::any())
            ->andReturn(1);
        $mock->shouldReceive('get')
            ->with('hepsiburada-api.timeout', Mockery::any())
            ->andReturn(5);
        $mock->shouldReceive('get')
            ->with('hepsiburada-api.connect_timeout', Mockery::any())
            ->andReturn(3);
    });

    $mock_client = Mockery::mock(Client::class);
    $request = new Request('GET', '/test-endpoint');
    $exception = new ConnectException('Connection timed out', $request);

    $mock_client->shouldReceive('request')
        ->twice()  // İlk istek ve yeniden deneme
        ->andThrow($exception);

    $api_request = new ApiRequestTestClass($mock_client);
    $result = $api_request->testGet('/test-endpoint');

    expect($result)->toBeNull();
}); 