<?php

namespace HepsiburadaApi\HepsiburadaSpApi;

use HepsiburadaApi\HepsiburadaSpApi\Contracts\HepsiburadaApiInterface;
use HepsiburadaApi\HepsiburadaSpApi\Traits\ApiRequest;
use HepsiburadaApi\HepsiburadaSpApi\Services\CategoryService;
use HepsiburadaApi\HepsiburadaSpApi\Services\ClaimService;
use HepsiburadaApi\HepsiburadaSpApi\Services\FinanceService;
use HepsiburadaApi\HepsiburadaSpApi\Services\ListingService;
use HepsiburadaApi\HepsiburadaSpApi\Services\LogisticsService;
use HepsiburadaApi\HepsiburadaSpApi\Services\OrderService;
use HepsiburadaApi\HepsiburadaSpApi\Services\ProductService;
use HepsiburadaApi\HepsiburadaSpApi\Services\ReportService;

final class HepsiburadaApi implements HepsiburadaApiInterface
{
    use ApiRequest;

    /**
     * API temel URL
     *
     * @var string
     */
    private string $base_url;

    /**
     * API kullanıcı adı
     *
     * @var string
     */
    private string $username;

    /**
     * API şifresi
     *
     * @var string
     */
    private string $password;

    /**
     * Satıcı ID
     *
     * @var string
     */
    private string $merchant_id;

    /**
     * HTTP Client instance
     *
     * @var \GuzzleHttp\Client
     */
    private $http_client;

    /**
     * Kategori servisi
     *
     * @var CategoryService|null
     */
    private ?CategoryService $category_service = null;

    /**
     * Ürün servisi
     *
     * @var ProductService|null
     */
    private ?ProductService $product_service = null;

    /**
     * Listing servisi
     *
     * @var ListingService|null
     */
    private ?ListingService $listing_service = null;

    /**
     * Sipariş servisi
     *
     * @var OrderService|null
     */
    private ?OrderService $order_service = null;
    
    /**
     * Talep servisi
     *
     * @var ClaimService|null
     */
    private ?ClaimService $claim_service = null;
    
    /**
     * Finans servisi
     *
     * @var FinanceService|null
     */
    private ?FinanceService $finance_service = null;
    
    /**
     * Raporlama servisi
     *
     * @var ReportService|null
     */
    private ?ReportService $report_service = null;
    
    /**
     * Lojistik servisi
     *
     * @var LogisticsService|null
     */
    private ?LogisticsService $logistics_service = null;

    /**
     * HepsiburadaApi sınıfı yapıcı fonksiyonu
     *
     * @param array $config Konfigürasyon parametreleri
     */
    public function __construct(array $config = [])
    {
        $this->base_url = $config['base_url'] ?? config('hepsiburada-api.base_url');
        $this->username = $config['username'] ?? config('hepsiburada-api.username');
        $this->password = $config['password'] ?? config('hepsiburada-api.password');
        $this->merchant_id = $config['merchant_id'] ?? config('hepsiburada-api.merchant_id');

        $this->initHttpClient();
    }

    /**
     * HTTP istemcisini başlatır
     * 
     * @return void
     */
    private function initHttpClient(): void
    {
        $this->http_client = new \GuzzleHttp\Client([
            'base_uri' => $this->base_url,
            'auth' => [$this->username, $this->password],
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * HTTP istemcisini yeni kimlik bilgileriyle yeniden yapılandırır
     * 
     * @param string|null $username Yeni kullanıcı adı
     * @param string|null $password Yeni şifre
     * @param string|null $merchant_id Yeni satıcı ID
     * @param string|null $base_url Yeni temel URL
     * @return self
     */
    public function reconnect(
        ?string $username = null,
        ?string $password = null,
        ?string $merchant_id = null,
        ?string $base_url = null
    ): HepsiburadaApiInterface {
        if ($username !== null) {
            $this->username = $username;
        }
        
        if ($password !== null) {
            $this->password = $password;
        }
        
        if ($merchant_id !== null) {
            $this->merchant_id = $merchant_id;
        }
        
        if ($base_url !== null) {
            $this->base_url = $base_url;
        }

        $this->initHttpClient();
        
        // Servisleri sıfırla
        $this->category_service = null;
        $this->product_service = null;
        $this->listing_service = null;
        $this->order_service = null;
        $this->claim_service = null;
        $this->finance_service = null;
        $this->report_service = null;
        $this->logistics_service = null;
        
        return $this;
    }

    /**
     * HTTP istemcisini döndürür
     *
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient(): \GuzzleHttp\Client
    {
        return $this->http_client;
    }

    /**
     * Satıcı ID değerini döndürür
     *
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->merchant_id;
    }

    /**
     * Kategori servisini döndürür
     *
     * @return CategoryService
     */
    public function categories(): CategoryService
    {
        if (!$this->category_service) {
            $this->category_service = new CategoryService($this);
        }

        return $this->category_service;
    }

    /**
     * Ürün servisini döndürür
     *
     * @return ProductService
     */
    public function products(): ProductService
    {
        if (!$this->product_service) {
            $this->product_service = new ProductService($this);
        }

        return $this->product_service;
    }

    /**
     * Listing servisini döndürür
     *
     * @return ListingService
     */
    public function listings(): ListingService
    {
        if (!$this->listing_service) {
            $this->listing_service = new ListingService($this);
        }

        return $this->listing_service;
    }

    /**
     * Sipariş servisini döndürür
     *
     * @return OrderService
     */
    public function orders(): OrderService
    {
        if (!$this->order_service) {
            $this->order_service = new OrderService($this);
        }

        return $this->order_service;
    }

    /**
     * Talep servisini döndürür
     *
     * @return ClaimService
     */
    public function claims(): ClaimService
    {
        if (!$this->claim_service) {
            $this->claim_service = new ClaimService($this);
        }

        return $this->claim_service;
    }

    /**
     * Finans servisini döndürür
     *
     * @return FinanceService
     */
    public function finances(): FinanceService
    {
        if (!$this->finance_service) {
            $this->finance_service = new FinanceService($this);
        }

        return $this->finance_service;
    }

    /**
     * Rapor servisini döndürür
     *
     * @return ReportService
     */
    public function reports(): ReportService
    {
        if (!$this->report_service) {
            $this->report_service = new ReportService($this);
        }

        return $this->report_service;
    }

    /**
     * Lojistik servisini döndürür
     *
     * @return LogisticsService
     */
    public function logistics(): LogisticsService
    {
        if (!$this->logistics_service) {
            $this->logistics_service = new LogisticsService($this);
        }

        return $this->logistics_service;
    }
}
