<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Contracts;

use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;

interface ServiceInterface
{
    /**
     * Servis sınıfı yapıcı fonksiyonu
     *
     * @param HepsiburadaApi $api API istemcisi
     */
    public function __construct(HepsiburadaApi $api);
    
    /**
     * API istemcisini döndürür
     *
     * @return HepsiburadaApi
     */
    public function getApi(): HepsiburadaApi;
} 