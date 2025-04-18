<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Facades;

use Illuminate\Support\Facades\Facade;
use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi as HepsiburadaApiClass;

/**
 * @method static \GuzzleHttp\Client getHttpClient()
 *
 * @see \HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi
 */
class HepsiburadaApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return HepsiburadaApiClass::class;
    }
} 