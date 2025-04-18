<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Services;

use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;
use HepsiburadaApi\HepsiburadaSpApi\Traits\ApiRequest;

class ReportService
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
     * ReportService sınıfı yapıcı fonksiyonu
     */
    public function __construct(HepsiburadaApi $api)
    {
        $this->api = $api;
        $this->http_client = $api->getHttpClient();
    }

    /**
     * Satış performans raporunu alır
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getSalesPerformance(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
        ], $params);

        return $this->get('/reports/merchant/sales-performance', $query);
    }

    /**
     * Sipariş raporunu alır
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getOrderReport(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
            'beginDate' => date('Y-m-d', strtotime('-30 days')),
            'endDate' => date('Y-m-d'),
        ], $params);

        return $this->get('/reports/merchant/orders', $query);
    }

    /**
     * Ürün performans raporunu alır
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getProductPerformance(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
        ], $params);

        return $this->get('/reports/merchant/product-performance', $query);
    }

    /**
     * Stok durumu raporunu alır
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getInventoryReport(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
        ], $params);

        return $this->get('/reports/merchant/inventory', $query);
    }

    /**
     * Özel rapor oluşturur
     *
     * @param array $report_data Rapor verileri
     * @return array|null
     */
    public function createCustomReport(array $report_data): ?array
    {
        return $this->post('/reports/merchant/custom-report', $report_data);
    }

    /**
     * Rapor durumunu sorgular
     *
     * @param string $report_id Rapor ID
     * @param string|null $merchant_id Satıcı ID
     * @return array|null
     */
    public function getReportStatus(string $report_id, ?string $merchant_id = null): ?array
    {
        $query = [
            'merchantId' => $merchant_id ?? $this->api->getMerchantId(),
            'reportId' => $report_id,
        ];

        return $this->get('/reports/merchant/status', $query);
    }

    /**
     * Rapor indirme bağlantısını alır
     *
     * @param string $report_id Rapor ID
     * @param string|null $merchant_id Satıcı ID
     * @return array|null
     */
    public function getReportDownloadLink(string $report_id, ?string $merchant_id = null): ?array
    {
        $query = [
            'merchantId' => $merchant_id ?? $this->api->getMerchantId(),
            'reportId' => $report_id,
        ];

        return $this->get('/reports/merchant/download', $query);
    }
} 