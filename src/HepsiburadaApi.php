<?php

namespace HepsiburadaApi\HepsiburadaSpApi;

use HepsiburadaApi\HepsiburadaSpApi\Traits\ApiRequest;
use HepsiburadaApi\HepsiburadaSpApi\Services\CategoryService;
use HepsiburadaApi\HepsiburadaSpApi\Services\ProductService;
use HepsiburadaApi\HepsiburadaSpApi\Services\ListingService;
use HepsiburadaApi\HepsiburadaSpApi\Services\OrderService;

final class HepsiburadaApi
{
    use ApiRequest;

    /**
     * API temel URL
     */
    private string $base_url;

    /**
     * API kullanıcı adı
     */
    private string $username;

    /**
     * API şifresi
     */
    private string $password;

    /**
     * Satıcı ID
     */
    private string $merchant_id;

    /**
     * HTTP Client instance
     */
    private $http_client;

    /**
     * Kategori servisi
     */
    private ?CategoryService $category_service = null;

    /**
     * Ürün servisi
     */
    private ?ProductService $product_service = null;

    /**
     * Listing servisi
     */
    private ?ListingService $listing_service = null;

    /**
     * Sipariş servisi
     */
    private ?OrderService $order_service = null;

    /**
     * HepsiburadaApi sınıfı yapıcı fonksiyonu
     */
    public function __construct(array $config = [])
    {
        $this->base_url = $config['base_url'] ?? config('hepsiburada-api.base_url');
        $this->username = $config['username'] ?? config('hepsiburada-api.username');
        $this->password = $config['password'] ?? config('hepsiburada-api.password');
        $this->merchant_id = $config['merchant_id'] ?? config('hepsiburada-api.merchant_id');

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
     * HTTP istemcisini döndürür
     */
    public function getHttpClient(): \GuzzleHttp\Client
    {
        return $this->http_client;
    }

    /**
     * Satıcı ID değerini döndürür
     */
    public function getMerchantId(): string
    {
        return $this->merchant_id;
    }

    /**
     * Kategori servisini döndürür
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
     */
    public function orders(): OrderService
    {
        if (!$this->order_service) {
            $this->order_service = new OrderService($this);
        }

        return $this->order_service;
    }
}
