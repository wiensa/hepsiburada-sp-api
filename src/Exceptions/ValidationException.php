<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Exceptions;

class ValidationException extends ApiException
{
    /**
     * Doğrulama hataları
     *
     * @var array
     */
    protected array $errors;

    /**
     * ValidationException sınıfı yapıcı fonksiyonu
     *
     * @param string $message Hata mesajı
     * @param array $errors Doğrulama hataları
     * @param int $code HTTP durum kodu
     * @param string|null $api_code API hata kodu
     * @param array|null $response API yanıtı
     * @param \Throwable|null $previous Önceki istisna
     */
    public function __construct(
        string $message = 'Hepsiburada API doğrulama hatası',
        array $errors = [],
        int $code = 422,
        ?string $api_code = null,
        ?array $response = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $api_code, $response, $previous);
        $this->errors = $errors;
    }

    /**
     * Doğrulama hatalarını döndürür
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
} 