<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Contracts;

use GuzzleHttp\Client;
use HepsiburadaApi\HepsiburadaSpApi\Services\CategoryService;
use HepsiburadaApi\HepsiburadaSpApi\Services\ClaimService;
use HepsiburadaApi\HepsiburadaSpApi\Services\FinanceService;
use HepsiburadaApi\HepsiburadaSpApi\Services\ListingService;
use HepsiburadaApi\HepsiburadaSpApi\Services\LogisticsService;
use HepsiburadaApi\HepsiburadaSpApi\Services\OrderService;
use HepsiburadaApi\HepsiburadaSpApi\Services\ProductService;
use HepsiburadaApi\HepsiburadaSpApi\Services\ReportService;

interface HepsiburadaApiInterface
{
    /**
     * HTTP istemcisini döndürür
     *
     * @return Client
     */
    public function getHttpClient(): Client;

    /**
     * Satıcı ID değerini döndürür
     *
     * @return string
     */
    public function getMerchantId(): string;

    /**
     * Kategori servisini döndürür
     *
     * @return CategoryService
     */
    public function categories(): CategoryService;

    /**
     * Ürün servisini döndürür
     *
     * @return ProductService
     */
    public function products(): ProductService;

    /**
     * Listing servisini döndürür
     *
     * @return ListingService
     */
    public function listings(): ListingService;

    /**
     * Sipariş servisini döndürür
     *
     * @return OrderService
     */
    public function orders(): OrderService;

    /**
     * Talep servisini döndürür
     *
     * @return ClaimService
     */
    public function claims(): ClaimService;

    /**
     * Finans servisini döndürür
     *
     * @return FinanceService
     */
    public function finances(): FinanceService;

    /**
     * Rapor servisini döndürür
     *
     * @return ReportService
     */
    public function reports(): ReportService;

    /**
     * Lojistik servisini döndürür
     *
     * @return LogisticsService
     */
    public function logistics(): LogisticsService;

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
    ): self;
} 