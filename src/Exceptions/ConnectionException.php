<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Exceptions;

class ConnectionException extends ApiException
{
    /**
     * ConnectionException sınıfı yapıcı fonksiyonu
     *
     * @param string $message Hata mesajı
     * @param int $code HTTP durum kodu
     * @param string|null $api_code API hata kodu
     * @param array|null $response API yanıtı
     * @param \Throwable|null $previous Önceki istisna
     */
    public function __construct(
        string $message = 'Hepsiburada API bağlantı hatası',
        int $code = 500,
        ?string $api_code = null,
        ?array $response = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $api_code, $response, $previous);
    }
} 