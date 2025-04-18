<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Traits;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

trait ApiRequest
{
    /**
     * HTTP GET isteği gönderir
     *
     * @param string $endpoint API endpoint
     * @param array $query URL sorgu parametreleri
     * @param array $headers İsteğe özel HTTP başlıkları
     * @return array|null İstek yanıtı 
     */
    protected function get(string $endpoint, array $query = [], array $headers = []): ?array
    {
        return $this->request('GET', $endpoint, [], $query, $headers);
    }

    /**
     * HTTP POST isteği gönderir
     *
     * @param string $endpoint API endpoint
     * @param array $data İstek gövdesi verileri
     * @param array $query URL sorgu parametreleri
     * @param array $headers İsteğe özel HTTP başlıkları
     * @return array|null İstek yanıtı
     */
    protected function post(string $endpoint, array $data = [], array $query = [], array $headers = []): ?array
    {
        return $this->request('POST', $endpoint, $data, $query, $headers);
    }

    /**
     * HTTP PUT isteği gönderir
     *
     * @param string $endpoint API endpoint
     * @param array $data İstek gövdesi verileri
     * @param array $query URL sorgu parametreleri
     * @param array $headers İsteğe özel HTTP başlıkları
     * @return array|null İstek yanıtı
     */
    protected function put(string $endpoint, array $data = [], array $query = [], array $headers = []): ?array
    {
        return $this->request('PUT', $endpoint, $data, $query, $headers);
    }

    /**
     * HTTP DELETE isteği gönderir
     *
     * @param string $endpoint API endpoint
     * @param array $data İstek gövdesi verileri
     * @param array $query URL sorgu parametreleri
     * @param array $headers İsteğe özel HTTP başlıkları
     * @return array|null İstek yanıtı
     */
    protected function delete(string $endpoint, array $data = [], array $query = [], array $headers = []): ?array
    {
        return $this->request('DELETE', $endpoint, $data, $query, $headers);
    }

    /**
     * HTTP isteği gönderir
     *
     * @param string $method HTTP metodu
     * @param string $endpoint API endpoint
     * @param array $data İstek gövdesi verileri
     * @param array $query URL sorgu parametreleri
     * @param array $headers İsteğe özel HTTP başlıkları
     * @return array|null İstek yanıtı
     */
    protected function request(string $method, string $endpoint, array $data = [], array $query = [], array $headers = []): ?array
    {
        $options = [
            'headers' => $headers,
            'query' => $query,
            'timeout' => config('hepsiburada-api.timeout', 30),
        ];

        if (!empty($data)) {
            $options['json'] = $data;
        }

        try {
            $response = $this->http_client->request($method, $endpoint, $options);
            $contents = $response->getBody()->getContents();
            
            return json_decode($contents, true);
        } catch (GuzzleException $e) {
            $this->handleRequestException($e, $method, $endpoint, $options);
            return null;
        }
    }

    /**
     * İstek hatalarını yönetir ve kaydeder
     *
     * @param GuzzleException $exception Yakalanan hata
     * @param string $method HTTP metodu
     * @param string $endpoint İstek yapılan endpoint
     * @param array $options İstek seçenekleri
     * @return void
     */
    protected function handleRequestException(GuzzleException $exception, string $method, string $endpoint, array $options): void
    {
        Log::error('Hepsiburada API İstek Hatası', [
            'method' => $method,
            'endpoint' => $endpoint,
            'options' => $options,
            'error' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ]);
    }
} 