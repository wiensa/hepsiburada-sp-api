<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Services;

use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;
use HepsiburadaApi\HepsiburadaSpApi\Traits\ApiRequest;

class ProductService
{
    use ApiRequest;
    
    /**
     * API istemcisi
     *
     * @var HepsiburadaApi
     */
    protected HepsiburadaApi $api;
    
    /**
     * HTTP istemcisi
     */
    protected $http_client;

    /**
     * ProductService sınıfı yapıcı fonksiyonu
     */
    public function __construct(HepsiburadaApi $api)
    {
        $this->api = $api;
        $this->http_client = $api->getHttpClient();
    }

    /**
     * Ürün bilgisini gönderir
     *
     * @param array $product_data Ürün verileri
     * @return array|null
     */
    public function sendProductData(array $product_data): ?array
    {
        return $this->post('/product/api/products', $product_data);
    }

    /**
     * Hızlı ürün yükleme
     *
     * @param array $product_data Ürün verileri
     * @return array|null
     */
    public function quickUpload(array $product_data): ?array
    {
        return $this->post('/product/api/products/quick-upload', $product_data);
    }

    /**
     * Aksiyon bekleyen ürün silme
     *
     * @param string $tracking_id Takip ID
     * @return array|null
     */
    public function deleteAwaitingActionProduct(string $tracking_id): ?array
    {
        return $this->post('/product/api/products/delete', [
            'trackingId' => $tracking_id,
            'merchantId' => $this->api->getMerchantId(),
        ]);
    }

    /**
     * Ürün durumu sorgulama
     *
     * @param string $barcode Barkod
     * @param string|null $merchant_id Satıcı ID (opsiyonel, belirtilmezse config'ten alınır)
     * @return array|null
     */
    public function getProductStatus(string $barcode, ?string $merchant_id = null): ?array
    {
        $query = [
            'barcode' => $barcode,
            'merchantId' => $merchant_id ?? $this->api->getMerchantId(),
        ];
        
        return $this->get('/product/api/products/status', $query);
    }

    /**
     * TrackingId geçmişini sorgulama
     *
     * @param string $tracking_id Takip ID
     * @return array|null
     */
    public function getTrackingHistory(string $tracking_id): ?array
    {
        return $this->get("/product/api/products/tracking-history/{$tracking_id}");
    }

    /**
     * Ürüne ait statü bilgisi çekme
     *
     * @param array $barcode_list Barkod listesi
     * @param string|null $merchant_id Satıcı ID (opsiyonel, belirtilmezse config'ten alınır)
     * @return array|null
     */
    public function getProductStatuses(array $barcode_list, ?string $merchant_id = null): ?array
    {
        $data = [
            'barcodes' => $barcode_list,
            'merchantId' => $merchant_id ?? $this->api->getMerchantId(),
        ];

        return $this->post('/product/api/products/statuses', $data);
    }

    /**
     * Statü bazlı ürün bilgisi çekme
     *
     * @param string $status Statü
     * @param int $page Sayfa numarası
     * @param int $size Sayfa boyutu
     * @param string|null $merchant_id Satıcı ID (opsiyonel, belirtilmezse config'ten alınır)
     * @return array|null
     */
    public function getProductsByStatus(
        string $status, 
        int $page = 0, 
        int $size = 100, 
        ?string $merchant_id = null
    ): ?array {
        $query = [
            'status' => $status,
            'page' => $page,
            'size' => $size,
            'merchantId' => $merchant_id ?? $this->api->getMerchantId(),
        ];

        return $this->get('/product/api/products', $query);
    }

    /**
     * Eşleşen statü onay
     *
     * @param string $barcode Barkod
     * @param string|null $merchant_id Satıcı ID (opsiyonel, belirtilmezse config'ten alınır)
     * @return array|null
     */
    public function approveMatchedStatus(string $barcode, ?string $merchant_id = null): ?array
    {
        $data = [
            'barcode' => $barcode,
            'merchantId' => $merchant_id ?? $this->api->getMerchantId(),
        ];

        return $this->post('/product/api/products/match-approve', $data);
    }

    /**
     * Eşleşen statü reddet
     *
     * @param string $barcode Barkod
     * @param string|null $merchant_id Satıcı ID (opsiyonel, belirtilmezse config'ten alınır)
     * @return array|null
     */
    public function rejectMatchedStatus(string $barcode, ?string $merchant_id = null): ?array
    {
        $data = [
            'barcode' => $barcode,
            'merchantId' => $merchant_id ?? $this->api->getMerchantId(),
        ];

        return $this->post('/product/api/products/match-reject', $data);
    }

    /**
     * Mağaza bazlı ürün bilgisi listeleme
     *
     * @param int $page Sayfa numarası
     * @param int $size Sayfa boyutu
     * @param string|null $merchant_id Satıcı ID (opsiyonel, belirtilmezse config'ten alınır)
     * @return array|null
     */
    public function getProductsByMerchant(
        int $page = 0, 
        int $size = 100, 
        ?string $merchant_id = null
    ): ?array {
        $query = [
            'page' => $page,
            'size' => $size,
            'merchantId' => $merchant_id ?? $this->api->getMerchantId(),
        ];

        return $this->get('/product/api/products/merchant-products', $query);
    }

    /**
     * Aksiyon bekleyen ürün silme işlem kontrolü
     *
     * @param string $tracking_id Takip ID
     * @return array|null
     */
    public function checkDeleteStatus(string $tracking_id): ?array
    {
        return $this->get("/product/api/products/tracking-history/{$tracking_id}");
    }
} 