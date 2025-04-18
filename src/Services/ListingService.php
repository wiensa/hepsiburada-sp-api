<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Services;

use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;
use HepsiburadaApi\HepsiburadaSpApi\Traits\ApiRequest;

class ListingService
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
     * ListingService sınıfı yapıcı fonksiyonu
     */
    public function __construct(HepsiburadaApi $api)
    {
        $this->api = $api;
        $this->http_client = $api->getHttpClient();
    }

    /**
     * Listing bilgilerini sorgulama
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getListings(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
            'page' => 0,
            'size' => 100,
        ], $params);

        return $this->get('/listings/merchantid', $query);
    }

    /**
     * Listing envanter güncelleme
     *
     * @param array $inventory_data Envanter verileri
     * @return array|null
     */
    public function updateInventory(array $inventory_data): ?array
    {
        return $this->post('/listings/inventory', $inventory_data);
    }

    /**
     * Listing stok güncelleme
     *
     * @param array $stock_data Stok verileri
     * @return array|null
     */
    public function updateStock(array $stock_data): ?array
    {
        return $this->post('/listings/stocks', $stock_data);
    }

    /**
     * Listing fiyat güncelleme
     *
     * @param array $price_data Fiyat verileri
     * @return array|null
     */
    public function updatePrice(array $price_data): ?array
    {
        return $this->post('/listings/prices', $price_data);
    }

    /**
     * Listing teslimat güncelleme
     *
     * @param array $delivery_data Teslimat verileri
     * @return array|null
     */
    public function updateDelivery(array $delivery_data): ?array
    {
        return $this->post('/listings/delivery', $delivery_data);
    }

    /**
     * Listing ek bilgiler güncelleme
     *
     * @param array $additional_data Ek bilgi verileri
     * @return array|null
     */
    public function updateAdditionalInfo(array $additional_data): ?array
    {
        return $this->post('/listings/additional', $additional_data);
    }

    /**
     * Listing aktifleştirme
     *
     * @param array $activation_data Aktivasyon verileri
     * @return array|null
     */
    public function activateListing(array $activation_data): ?array
    {
        return $this->post('/listings/activate', $activation_data);
    }

    /**
     * Listing deaktif etme
     *
     * @param array $deactivation_data Deaktivasyon verileri
     * @return array|null
     */
    public function deactivateListing(array $deactivation_data): ?array
    {
        return $this->post('/listings/deactivate', $deactivation_data);
    }

    /**
     * Buybox sıralama sorgulama
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getBuyboxRanking(array $params): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
        ], $params);

        return $this->get('/buybox', $query);
    }

    /**
     * Listing tekil fiyat/stok güncelleme
     *
     * @param array $update_data Güncelleme verileri
     * @return array|null
     */
    public function updateSingleListing(array $update_data): ?array
    {
        return $this->post('/listings/merchantid/updatepricequantity', $update_data);
    }

    /**
     * Listing silme
     *
     * @param string $listing_id Listing ID
     * @param string|null $merchant_id Satıcı ID (opsiyonel, belirtilmezse config'ten alınır)
     * @return array|null
     */
    public function deleteListing(string $listing_id, ?string $merchant_id = null): ?array
    {
        $query = [
            'merchantId' => $merchant_id ?? $this->api->getMerchantId(),
            'listingId' => $listing_id,
        ];

        return $this->delete('/listings/delete', [], $query);
    }

    /**
     * Toplu kilit kaldırma
     *
     * @param array $unlock_data Kilit kaldırma verileri
     * @return array|null
     */
    public function bulkUnlock(array $unlock_data): ?array
    {
        return $this->post('/listings/bulklockunlock', $unlock_data);
    }
} 