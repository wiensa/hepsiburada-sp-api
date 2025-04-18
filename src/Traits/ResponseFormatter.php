<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Traits;

trait ResponseFormatter
{
    /**
     * API yanıtını formatlar
     *
     * @param array|null $response API yanıtı
     * @param string|null $key Ana veri anahtarı
     * @return array|null Formatlanmış yanıt
     */
    protected function formatResponse(?array $response, ?string $key = null): ?array
    {
        if ($response === null) {
            return null;
        }

        // Başarı durumunu kontrol et
        $is_successful = $response['success'] ?? false;
        if (!$is_successful) {
            return [
                'success' => false,
                'message' => $response['message'] ?? 'API yanıtı başarısız',
                'code' => $response['code'] ?? null,
                'data' => null,
            ];
        }

        // Belirtilen anahtarı kontrol et
        if ($key !== null && isset($response['data'][$key])) {
            return [
                'success' => true,
                'data' => $response['data'][$key],
                'totalElements' => $response['data']['totalElements'] ?? null,
                'totalPages' => $response['data']['totalPages'] ?? null,
            ];
        }

        // Varsayılan olarak tüm veriyi döndür
        return [
            'success' => true,
            'data' => $response['data'] ?? $response,
        ];
    }

    /**
     * Sayfalandırılmış API yanıtını formatlar
     *
     * @param array|null $response API yanıtı
     * @param string $key Veri anahtarı
     * @return array|null Formatlanmış yanıt
     */
    protected function formatPaginatedResponse(?array $response, string $key): ?array
    {
        if ($response === null) {
            return null;
        }

        // Başarı durumunu kontrol et
        $is_successful = $response['success'] ?? false;
        if (!$is_successful) {
            return [
                'success' => false,
                'message' => $response['message'] ?? 'API yanıtı başarısız',
                'code' => $response['code'] ?? null,
                'data' => null,
            ];
        }

        // Sayfalama bilgilerini ayıkla
        $items = $response['data'][$key] ?? [];
        $total_elements = $response['data']['totalElements'] ?? 0;
        $total_pages = $response['data']['totalPages'] ?? 0;
        $page = $response['data']['page'] ?? 0;
        $size = $response['data']['size'] ?? count($items);

        return [
            'success' => true,
            'data' => $items,
            'pagination' => [
                'totalElements' => $total_elements,
                'totalPages' => $total_pages,
                'page' => $page,
                'size' => $size,
            ],
        ];
    }
} 