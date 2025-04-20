<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Services;

use HepsiburadaApi\HepsiburadaSpApi\Contracts\HepsiburadaApiInterface;
use HepsiburadaApi\HepsiburadaSpApi\Traits\ApiRequest;

class LogisticsService
{
    use ApiRequest;
    
    /**
     * API istemcisi
     *
     * @var HepsiburadaApiInterface
     */
    protected HepsiburadaApiInterface $api;
    
    /**
     * HTTP istemcisi
     */
    protected $http_client;

    /**
     * LogisticsService sınıfı yapıcı fonksiyonu
     */
    public function __construct(HepsiburadaApiInterface $api)
    {
        $this->api = $api;
        $this->http_client = $api->getHttpClient();
    }

    /**
     * Kargo şirketlerini listeler
     *
     * @return array|null
     */
    public function getCarriers(): ?array
    {
        $query = [
            'merchantId' => $this->api->getMerchantId(),
        ];

        return $this->get('/logistics/merchant/carriers', $query);
    }

    /**
     * Kargo takip bilgilerini günceller
     *
     * @param array $tracking_data Takip verileri
     * @return array|null
     */
    public function updateTrackingInfo(array $tracking_data): ?array
    {
        return $this->post('/logistics/merchant/tracking', $tracking_data);
    }

    /**
     * Kargo etiketi oluşturur
     *
     * @param array $label_data Etiket verileri
     * @return array|null
     */
    public function createShippingLabel(array $label_data): ?array
    {
        return $this->post('/logistics/merchant/shipping-label', $label_data);
    }

    /**
     * Teslimat bölgesi bilgilerini sorgular
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getDeliveryZones(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
        ], $params);

        return $this->get('/logistics/merchant/delivery-zones', $query);
    }

    /**
     * Kargo fiyatlarını sorgular
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getShippingRates(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
        ], $params);

        return $this->get('/logistics/merchant/shipping-rates', $query);
    }

    /**
     * Hepsilojistik entegrasyonu için bilgi alır
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getHepsilogisticsInfo(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
        ], $params);

        return $this->get('/logistics/merchant/hepsilogistics', $query);
    }
} 