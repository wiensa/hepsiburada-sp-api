<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Traits;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
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
     * @param int|null $retry_attempt Mevcut yeniden deneme sayısı
     * @return array|null İstek yanıtı
     */
    protected function request(
        string $method, 
        string $endpoint, 
        array $data = [], 
        array $query = [], 
        array $headers = [],
        ?int $retry_attempt = 0
    ): ?array {
        $options = [
            'headers' => $headers,
            'query' => $query,
            'timeout' => config('hepsiburada-api.timeout', 30),
            'connect_timeout' => config('hepsiburada-api.connect_timeout', 10),
        ];

        if (!empty($data)) {
            $options['json'] = $data;
        }

        try {
            $response = $this->http_client->request($method, $endpoint, $options);
            $contents = $response->getBody()->getContents();
            
            return json_decode($contents, true);
        } catch (ConnectException|RequestException $e) {
            return $this->handleRetriableException($e, $method, $endpoint, $data, $query, $headers, $retry_attempt, $options);
        } catch (GuzzleException $e) {
            $this->handleRequestException($e, $method, $endpoint, $options);
            return null;
        }
    }

    /**
     * Yeniden denenebilir istisnaları yönetir
     *
     * @param GuzzleException $exception Yakalanan hata
     * @param string $method HTTP metodu
     * @param string $endpoint İstek yapılan endpoint
     * @param array $data İstek gövdesi verileri
     * @param array $query URL sorgu parametreleri
     * @param array $headers İsteğe özel HTTP başlıkları
     * @param int $retry_attempt Mevcut yeniden deneme sayısı
     * @param array $options İstek seçenekleri
     * @return array|null İstek yanıtı
     */
    protected function handleRetriableException(
        GuzzleException $exception, 
        string $method, 
        string $endpoint, 
        array $data, 
        array $query, 
        array $headers, 
        int $retry_attempt, 
        array $options
    ): ?array {
        $max_attempts = config('hepsiburada-api.retry_attempts', 3);
        $retry_delay = config('hepsiburada-api.retry_delay', 1000);

        $status_code = $exception instanceof RequestException && $exception->hasResponse() 
            ? $exception->getResponse()->getStatusCode() 
            : 0;

        $should_retry = $retry_attempt < $max_attempts && 
                      ($exception instanceof ConnectException || 
                       in_array($status_code, [408, 429, 500, 502, 503, 504]));

        if ($should_retry) {
            Log::warning('Hepsiburada API isteği yeniden deneniyor', [
                'method' => $method,
                'endpoint' => $endpoint,
                'attempt' => $retry_attempt + 1,
                'max_attempts' => $max_attempts,
                'error' => $exception->getMessage(),
                'code' => $status_code,
            ]);

            // Yeniden denemeler arasında bekle (ms)
            $sleep_ms = $retry_delay * ($retry_attempt + 1);
            usleep($sleep_ms * 1000);

            return $this->request($method, $endpoint, $data, $query, $headers, $retry_attempt + 1);
        }

        $this->handleRequestException($exception, $method, $endpoint, $options);
        return null;
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
        $status_code = $exception instanceof RequestException && $exception->hasResponse() 
            ? $exception->getResponse()->getStatusCode() 
            : 0;

        $response_body = $exception instanceof RequestException && $exception->hasResponse() 
            ? $exception->getResponse()->getBody()->getContents() 
            : null;

        Log::error('Hepsiburada API İstek Hatası', [
            'method' => $method,
            'endpoint' => $endpoint,
            'options' => $options,
            'error' => $exception->getMessage(),
            'code' => $status_code,
            'response' => $response_body,
        ]);

        // Hata yanıtını JSON olarak çözümlemeyi dene
        if ($response_body) {
            $decoded_response = json_decode($response_body, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return;
            }
        }
    }
} 