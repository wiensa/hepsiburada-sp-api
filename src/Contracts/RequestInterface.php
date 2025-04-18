<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Contracts;

interface RequestInterface
{
    /**
     * İstek verilerini dizi olarak döndürür
     *
     * @return array
     */
    public function toArray(): array;
} 