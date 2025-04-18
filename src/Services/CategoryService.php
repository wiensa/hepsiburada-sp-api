<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Services;

use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;
use HepsiburadaApi\HepsiburadaSpApi\Traits\ApiRequest;

class CategoryService
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
     * CategoryService sınıfı yapıcı fonksiyonu
     */
    public function __construct(HepsiburadaApi $api)
    {
        $this->api = $api;
        $this->http_client = $api->getHttpClient();
    }

    /**
     * Kategori bilgilerini getirir
     *
     * @param string|null $leaf_id Kategori leaf ID
     * @param int $page Sayfa numarası
     * @param int $size Sayfa boyutu
     * @return array|null
     */
    public function getCategories(?string $leaf_id = null, int $page = 0, int $size = 100): ?array
    {
        $query = [
            'page' => $page,
            'size' => $size,
        ];

        if ($leaf_id) {
            $query['leaf_id'] = $leaf_id;
        }

        return $this->get('/product/api/categories', $query);
    }

    /**
     * Kategori özelliklerini getirir
     *
     * @param string $category_id Kategori ID
     * @return array|null
     */
    public function getCategoryAttributes(string $category_id): ?array
    {
        return $this->get("/product/api/categories/{$category_id}/attributes");
    }

    /**
     * Özellik değerini getirir
     *
     * @param string $attribute_id Özellik ID
     * @return array|null
     */
    public function getAttributeValues(string $attribute_id): ?array
    {
        return $this->get("/product/api/attributes/{$attribute_id}/values");
    }
} 