<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Services;

use HepsiburadaApi\HepsiburadaSpApi\Contracts\HepsiburadaApiInterface;
use HepsiburadaApi\HepsiburadaSpApi\Traits\ApiRequest;

class OrderService
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
     * OrderService sınıfı yapıcı fonksiyonu
     */
    public function __construct(HepsiburadaApiInterface $api)
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
     * İade edilmiş siparişleri listeler
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getReturnedOrders(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
            'page' => 0,
            'size' => 100,
        ], $params);

        return $this->get('/orders/merchant/returned-orders', $query);
    }

    /**
     * Siparişe ait detayları getirir
     *
     * @param string $order_number Sipariş numarası
     * @param string|null $merchant_id Satıcı ID (opsiyonel, belirtilmezse config'ten alınır)
     * @return array|null
     */
    public function getOrderDetail(string $order_number, ?string $merchant_id = null): ?array
    {
        $query = [
            'merchantId' => $merchant_id ?? $this->api->getMerchantId(),
            'orderNumber' => $order_number,
        ];

        return $this->get('/orders/merchant/order-detail', $query);
    }

    /**
     * Paket için kargo bilgilerini getirir
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
     * Sipariş paketini teslim edildi olarak işaretler
     *
     * @param array $delivery_data Teslimat verileri
     * @return array|null
     */
    public function markAsDelivered(array $delivery_data): ?array
    {
        return $this->post('/orders/merchant/update-package-status/delivered', $delivery_data);
    }

    /**
     * Sipariş paketini kargoda olarak işaretler
     *
     * @param array $shipping_data Kargolama verileri
     * @return array|null
     */
    public function shipOrderItems(array $shipping_data): ?array
    {
        return $this->post('/orders/merchant/update-package-status/intransit', $shipping_data);
    }
    
    /**
     * Sipariş paketini teslim edilemedi olarak işaretler
     *
     * @param array $undelivered_data Teslim edilememe verileri
     * @return array|null
     */
    public function markAsUndelivered(array $undelivered_data): ?array
    {
        return $this->post('/orders/merchant/update-package-status/undelivered', $undelivered_data);
    }

    /**
     * Fatura linkini gönderir
     *
     * @param array $invoice_data Fatura verileri
     * @return array|null
     */
    public function sendInvoiceLink(array $invoice_data): ?array
    {
        return $this->post('/orders/merchant/invoice-link', $invoice_data);
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
        return $this->post('/orders/merchant/unpack-package', $unpack_data);
    }

    /**
     * İptal bilgisi gönderir
     *
     * @param array $cancel_data İptal verileri
     * @return array|null
     */
    public function cancelOrder(array $cancel_data): ?array
    {
        return $this->post('/orders/merchant/cancel-order-line-items', $cancel_data);
    }

    /**
     * İade onaylama işlemi yapar
     *
     * @param array $return_approval_data İade onay verileri
     * @return array|null
     */
    public function approveReturn(array $return_approval_data): ?array
    {
        return $this->post('/returns/merchant/approve', $return_approval_data);
    }

    /**
     * İade reddetme işlemi yapar
     *
     * @param array $return_rejection_data İade red verileri
     * @return array|null
     */
    public function rejectReturn(array $return_rejection_data): ?array
    {
        return $this->post('/returns/merchant/reject', $return_rejection_data);
    }

    /**
     * İade detaylarını getirir
     *
     * @param string $return_id İade ID
     * @param string|null $merchant_id Satıcı ID (opsiyonel, belirtilmezse config'ten alınır)
     * @return array|null
     */
    public function getReturnDetail(string $return_id, ?string $merchant_id = null): ?array
    {
        $query = [
            'merchantId' => $merchant_id ?? $this->api->getMerchantId(),
            'returnId' => $return_id,
        ];

        return $this->get('/returns/merchant/detail', $query);
    }

    /**
     * Sipariş özetini getirir
     *
     * @param array $params Sorgu parametreleri
     * @return array|null
     */
    public function getOrderSummary(array $params = []): ?array
    {
        $query = array_merge([
            'merchantId' => $this->api->getMerchantId(),
        ], $params);

        return $this->get('/orders/merchant/order-summary', $query);
    }
} 