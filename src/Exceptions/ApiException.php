<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Exceptions;

use Exception;

class ApiException extends Exception
{
    /**
     * API hata kodu
     *
     * @var string|null
     */
    protected ?string $api_code;

    /**
     * API yanıtı
     *
     * @var array|null
     */
    protected ?array $response;

    /**
     * ApiException sınıfı yapıcı fonksiyonu
     *
     * @param string $message Hata mesajı
     * @param int $code HTTP durum kodu
     * @param string|null $api_code API hata kodu
     * @param array|null $response API yanıtı
     * @param \Throwable|null $previous Önceki istisna
     */
    public function __construct(
        string $message = 'Hepsiburada API hatası',
        int $code = 500,
        ?string $api_code = null,
        ?array $response = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->api_code = $api_code;
        $this->response = $response;
    }

    /**
     * API hata kodunu döndürür
     *
     * @return string|null
     */
    public function getApiCode(): ?string
    {
        return $this->api_code;
    }

    /**
     * API yanıtını döndürür
     *
     * @return array|null
     */
    public function getResponse(): ?array
    {
        return $this->response;
    }
} 