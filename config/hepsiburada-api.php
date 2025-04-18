<?php

return [
    /**
     * API'nin temel URL adresi
     */
    'base_url' => env('HEPSIBURADA_API_BASE_URL', 'https://marketplace-api.hepsiburada.com'),

    /**
     * API kullanıcı adı (entegrasyon için)
     */
    'username' => env('HEPSIBURADA_API_USERNAME', ''),

    /**
     * API şifresi (entegrasyon için)
     */
    'password' => env('HEPSIBURADA_API_PASSWORD', ''),

    /**
     * Satıcı ID (merchantId)
     */
    'merchant_id' => env('HEPSIBURADA_MERCHANT_ID', ''),

    /**
     * Sayfalama için varsayılan öğe sayısı
     */
    'pagination' => [
        'default_size' => 100,
    ],

    /**
     * API istekleri için varsayılan zaman aşımı (saniye)
     */
    'timeout' => 30,

    /**
     * API istekleri için yeniden deneme sayısı
     */
    'retry_attempts' => 3,

    /**
     * API istekleri arasındaki bekleme süresi (ms)
     */
    'retry_delay' => 1000,
]; 