<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Services;

use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;
use HepsiburadaApi\HepsiburadaSpApi\Traits\ApiRequest;

class FinanceService
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
     * FinanceService sınıfı yapıcı fonksiyonu
     */
    public function __construct(HepsiburadaApi $api)
    {
        $this->api = $api;
        $this->http_client = $api->getHttpClient();
    }

    /**
     * İşlem geçmişini listeler
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getTransactions(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
            'page' => 0,
            'size' => 100,
        ], $params);

        return $this->get('/finance/merchant/transactions', $query);
    }

    /**
     * Ödeme özeti alır
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getPaymentSummary(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
        ], $params);

        return $this->get('/finance/merchant/payment-summary', $query);
    }

    /**
     * Ödeme detaylarını alır
     *
     * @param string $payment_id Ödeme ID
     * @param string|null $merchant_id Satıcı ID
     * @return array|null
     */
    public function getPaymentDetails(string $payment_id, ?string $merchant_id = null): ?array
    {
        $query = [
            'merchantId' => $merchant_id ?? $this->api->getMerchantId(),
            'paymentId' => $payment_id,
        ];

        return $this->get('/finance/merchant/payment-details', $query);
    }
    
    /**
     * Fatura bilgilerini alır
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getInvoices(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
            'page' => 0,
            'size' => 100,
        ], $params);

        return $this->get('/finance/merchant/invoices', $query);
    }

    /**
     * Komisyon bilgilerini alır
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getCommissions(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
        ], $params);

        return $this->get('/finance/merchant/commissions', $query);
    }
} 