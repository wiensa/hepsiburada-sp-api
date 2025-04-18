<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Services;

use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;
use HepsiburadaApi\HepsiburadaSpApi\Traits\ApiRequest;

class OrderService
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
     * OrderService sınıfı yapıcı fonksiyonu
     */
    public function __construct(HepsiburadaApi $api)
    {
        $this->api = $api;
        $this->http_client = $api->getHttpClient();
    }

    /**
     * Ödemesi tamamlanmış siparişleri listeler
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getCompletedOrders(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
            'page' => 0,
            'size' => 100,
        ], $params);

        return $this->get('/orders/merchant/completed-orders', $query);
    }

    /**
     * İptal sipariş bilgileri listeler
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getCancelledOrders(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
            'page' => 0,
            'size' => 100,
        ], $params);

        return $this->get('/orders/merchant/cancelled-orders', $query);
    }

    /**
     * Ödemesi beklenen siparişleri listeler
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getPendingOrders(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
            'page' => 0,
            'size' => 100,
        ], $params);

        return $this->get('/orders/merchant/pending-orders', $query);
    }

    /**
     * Teslim edilen siparişleri listeler
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getDeliveredOrders(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
            'page' => 0,
            'size' => 100,
        ], $params);

        return $this->get('/orders/merchant/delivered-orders', $query);
    }

    /**
     * Kargoya verilen siparişleri listeler
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getShippedOrders(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
            'page' => 0,
            'size' => 100,
        ], $params);

        return $this->get('/orders/merchant/shipped-orders', $query);
    }

    /**
     * Bozulan (Unpack) paket bilgilerini listeler
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getUnpackedOrders(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
            'page' => 0,
            'size' => 100,
        ], $params);

        return $this->get('/orders/merchant/unpacked-orders', $query);
    }

    /**
     * Teslim edilemeyen siparişleri listeler
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getUndeliveredOrders(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
            'page' => 0,
            'size' => 100,
        ], $params);

        return $this->get('/orders/merchant/undelivered-orders', $query);
    }

    /**
     * Siparişe ait detayları listeler
     *
     * @param string $order_number Sipariş numarası
     * @param string|null $merchant_id Satıcı ID (opsiyonel, belirtilmezse config'ten alınır)
     * @return array|null
     */
    public function getOrderDetails(string $order_number, ?string $merchant_id = null): ?array
    {
        $query = [
            'merchantId' => $merchant_id ?? $this->api->getMerchantId(),
            'orderNumber' => $order_number,
        ];

        return $this->get('/orders/merchant/order-detail', $query);
    }

    /**
     * Paket için kargo bilgilerini listeler
     *
     * @param string $package_number Paket numarası
     * @param string|null $merchant_id Satıcı ID (opsiyonel, belirtilmezse config'ten alınır)
     * @return array|null
     */
    public function getPackageShippingInfo(string $package_number, ?string $merchant_id = null): ?array
    {
        $query = [
            'merchantId' => $merchant_id ?? $this->api->getMerchantId(),
            'packageNumber' => $package_number,
        ];

        return $this->get('/orders/merchant/shared-packages', $query);
    }

    /**
     * Ortak barkod oluşturur
     *
     * @param string $package_number Paket numarası
     * @param string|null $merchant_id Satıcı ID (opsiyonel, belirtilmezse config'ten alınır)
     * @return array|null
     */
    public function createSharedBarcode(string $package_number, ?string $merchant_id = null): ?array
    {
        $query = [
            'merchantId' => $merchant_id ?? $this->api->getMerchantId(),
            'packageNumber' => $package_number,
        ];

        return $this->get('/orders/merchant/generate-shared-package-barcode', $query);
    }

    /**
     * Paketleme işlemi yapar
     *
     * @param array $packaging_data Paketleme verileri
     * @return array|null
     */
    public function packageItems(array $packaging_data): ?array
    {
        return $this->post('/orders/merchant/package', $packaging_data);
    }

    /**
     * Teslimat statüsü iletir (Teslim Edildi)
     *
     * @param array $delivery_data Teslimat verileri
     * @return array|null
     */
    public function markAsDelivered(array $delivery_data): ?array
    {
        return $this->post('/orders/merchant/update-package-status/delivered', $delivery_data);
    }

    /**
     * Teslimat statüsü iletir (Kargoda)
     *
     * @param array $shipping_data Kargolama verileri
     * @return array|null
     */
    public function markAsShipped(array $shipping_data): ?array
    {
        return $this->post('/orders/merchant/update-package-status/intransit', $shipping_data);
    }

    /**
     * Teslimat statüsü iletir (Teslim Edilemedi)
     *
     * @param array $undelivered_data Teslim edilemedi verileri
     * @return array|null
     */
    public function markAsUndelivered(array $undelivered_data): ?array
    {
        return $this->post('/orders/merchant/update-package-status/undelivered', $undelivered_data);
    }

    /**
     * Fatura linki gönderir
     *
     * @param array $invoice_data Fatura verileri
     * @return array|null
     */
    public function sendInvoiceLink(array $invoice_data): ?array
    {
        return $this->put('/orders/merchant/invoice-link', $invoice_data);
    }

    /**
     * Paket bölme işlemi yapar
     *
     * @param array $split_data Bölme verileri
     * @return array|null
     */
    public function splitPackage(array $split_data): ?array
    {
        return $this->post('/orders/merchant/split-package', $split_data);
    }

    /**
     * Paket bozma işlemi yapar
     *
     * @param array $unpack_data Bozma verileri
     * @return array|null
     */
    public function unpackPackage(array $unpack_data): ?array
    {
        return $this->post('/orders/merchant/unpack', $unpack_data);
    }

    /**
     * İptal bilgisi gönderir
     *
     * @param array $cancel_data İptal verileri
     * @return array|null
     */
    public function sendCancellationInfo(array $cancel_data): ?array
    {
        return $this->post('/orders/merchant/cancel', $cancel_data);
    }
} 