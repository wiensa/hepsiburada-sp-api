<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Facades;

use Illuminate\Support\Facades\Facade;
use HepsiburadaApi\HepsiburadaSpApi\Contracts\HepsiburadaApiInterface;

/**
 * @method static \GuzzleHttp\Client getHttpClient()
 * @method static string getMerchantId()
 * @method static \HepsiburadaApi\HepsiburadaSpApi\Contracts\HepsiburadaApiInterface reconnect(?string $username = null, ?string $password = null, ?string $merchant_id = null, ?string $base_url = null)
 * @method static \HepsiburadaApi\HepsiburadaSpApi\Services\CategoryService categories()
 * @method static \HepsiburadaApi\HepsiburadaSpApi\Services\ProductService products()
 * @method static \HepsiburadaApi\HepsiburadaSpApi\Services\ListingService listings()
 * @method static \HepsiburadaApi\HepsiburadaSpApi\Services\OrderService orders()
 * @method static \HepsiburadaApi\HepsiburadaSpApi\Services\ClaimService claims()
 * @method static \HepsiburadaApi\HepsiburadaSpApi\Services\FinanceService finances()
 * @method static \HepsiburadaApi\HepsiburadaSpApi\Services\ReportService reports()
 * @method static \HepsiburadaApi\HepsiburadaSpApi\Services\LogisticsService logistics()
 *
 * @see \HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi
 */
class HepsiburadaApi extends Facade
{
    /**
     * Facade için kullanılacak servis adını döndürür
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return HepsiburadaApiClass::class;
    }
} 