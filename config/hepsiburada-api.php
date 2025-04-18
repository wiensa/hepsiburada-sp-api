<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Hepsiburada API Bağlantı Ayarları
    |--------------------------------------------------------------------------
    |
    | Hepsiburada Marketplace API ile bağlantı kurabilmek için gerekli
    | temel ayarlar. Bu değerleri .env dosyanızda yapılandırabilirsiniz.
    |
    */

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

    /*
    |--------------------------------------------------------------------------
    | Hepsiburada API İstek Ayarları
    |--------------------------------------------------------------------------
    |
    | API istekleri için gerekli yapılandırma değerleri. Burada zaman aşımı,
    | yeniden deneme gibi ayarları yapabilirsiniz.
    |
    */

    /**
     * Sayfalama için varsayılan öğe sayısı
     */
    'pagination' => [
        'default_size' => env('HEPSIBURADA_API_DEFAULT_PAGE_SIZE', 100),
    ],

    /**
     * API istekleri için varsayılan zaman aşımı (saniye)
     * İsteğin tamamlanması için beklenecek maksimum süre
     */
    'timeout' => env('HEPSIBURADA_API_TIMEOUT', 30),

    /**
     * API istekleri için bağlantı zaman aşımı (saniye)
     * Sunucuya bağlanma için beklenecek maksimum süre
     */
    'connect_timeout' => env('HEPSIBURADA_API_CONNECT_TIMEOUT', 10),

    /**
     * API istekleri için yeniden deneme sayısı
     * Hata durumunda istek kaç kez tekrarlanacak
     */
    'retry_attempts' => env('HEPSIBURADA_API_RETRY_ATTEMPTS', 3),

    /**
     * API istekleri arasındaki bekleme süresi (ms)
     * Her yeniden deneme arasında beklenecek süre (milisaniye)
     */
    'retry_delay' => env('HEPSIBURADA_API_RETRY_DELAY', 1000),

    /*
    |--------------------------------------------------------------------------
    | Hepsiburada API Loglama Ayarları
    |--------------------------------------------------------------------------
    |
    | API isteklerinin loglanmasıyla ilgili ayarlar.
    |
    */

    /**
     * API isteklerinin loglanması
     * API istekleri ve yanıtlar loglanacak mı?
     */
    'enable_logging' => env('HEPSIBURADA_API_ENABLE_LOGGING', true),

    /**
     * Sadece hataların loglanması
     * true olduğunda sadece hatalar loglanır, false olduğunda tüm istekler loglanır
     */
    'log_only_errors' => env('HEPSIBURADA_API_LOG_ONLY_ERRORS', true),
]; 