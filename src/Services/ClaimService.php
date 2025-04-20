<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Services;

use HepsiburadaApi\HepsiburadaSpApi\Contracts\HepsiburadaApiInterface;
use HepsiburadaApi\HepsiburadaSpApi\Traits\ApiRequest;

class ClaimService
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
     * ClaimService sınıfı yapıcı fonksiyonu
     */
    public function __construct(HepsiburadaApiInterface $api)
    {
        $this->api = $api;
        $this->http_client = $api->getHttpClient();
    }

    /**
     * Tüm talepleri listeler
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getClaims(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
            'page' => 0,
            'size' => 100,
        ], $params);

        return $this->get('/claims/merchant/list', $query);
    }

    /**
     * Talep detaylarını alır
     *
     * @param string $claim_id Talep ID
     * @param string|null $merchant_id Satıcı ID
     * @return array|null
     */
    public function getClaimDetails(string $claim_id, ?string $merchant_id = null): ?array
    {
        $query = [
            'merchantId' => $merchant_id ?? $this->api->getMerchantId(),
            'claimId' => $claim_id,
        ];

        return $this->get('/claims/merchant/details', $query);
    }

    /**
     * Talep yanıtı gönderir
     *
     * @param array $response_data Yanıt verileri
     * @return array|null
     */
    public function respondToClaim(array $response_data): ?array
    {
        return $this->post('/claims/merchant/respond', $response_data);
    }

    /**
     * İade taleplerini listeler
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getReturnRequests(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
            'page' => 0,
            'size' => 100,
        ], $params);

        return $this->get('/returns/merchant/list', $query);
    }

    /**
     * İade talebini onaylar
     *
     * @param array $approval_data Onay verileri
     * @return array|null
     */
    public function approveReturnRequest(array $approval_data): ?array
    {
        return $this->post('/returns/merchant/approve', $approval_data);
    }

    /**
     * İade talebini reddeder
     *
     * @param array $rejection_data Ret verileri
     * @return array|null
     */
    public function rejectReturnRequest(array $rejection_data): ?array
    {
        return $this->post('/returns/merchant/reject', $rejection_data);
    }
} 