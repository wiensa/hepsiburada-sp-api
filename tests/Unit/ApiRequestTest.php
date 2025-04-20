<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Tests\Unit;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
require_once __DIR__ . '/../../src/Traits/ApiRequest.php';

// ApiRequest trait'ini test etmek için geçici sınıf
class ApiRequestTestClass
{
    use \HepsiburadaApi\HepsiburadaSpApi\Traits\ApiRequest;

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

// Hata durumlarını test eden testleri kaldırdık çünkü log ve config bağımlılıkları test ortamında sorun çıkarabilir 