<?php

namespace HepsiburadaApi\HepsiburadaSpApi\DTOs\Order;

class PackageItemsRequest
{
    /**
     * Satıcı ID
     *
     * @var string
     */
    public string $merchant_id;
    
    /**
     * Paket parça sayısı
     *
     * @var int
     */
    public int $package_quantity;
    
    /**
     * Paket deci bilgisi
     *
     * @var float
     */
    public float $deci;
    
    /**
     * Paketlenecek ürün kalemleri
     *
     * @var array
     */
    public array $line_items;
    
    /**
     * Yapıcı metod
     *
     * @param string $merchant_id Satıcı ID
     * @param int $package_quantity Paket parça sayısı
     * @param float $deci Paket deci bilgisi
     * @param array $line_items Paketlenecek ürün kalemleri
     */
    public function __construct(
        string $merchant_id,
        int $package_quantity,
        float $deci,
        array $line_items
    ) {
        $this->merchant_id = $merchant_id;
        $this->package_quantity = $package_quantity;
        $this->deci = $deci;
        $this->line_items = $line_items;
    }
    
    /**
     * Nesneyi dizi formatında döndürür
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'merchantId' => $this->merchant_id,
            'packageQuantity' => $this->package_quantity,
            'deci' => $this->deci,
            'lineItems' => $this->line_items,
        ];
    }
} 