# Hepsiburada Marketplace API

Bu Laravel paketi, [Hepsiburada Marketplace API](https://developers.hepsiburada.com/) ile entegrasyon sağlamak için geliştirilmiştir. Paket, Hepsiburada'nın satıcı paneli üzerinden erişilebilen API'leri Laravel uygulamalarınızda kolayca kullanmanızı sağlar.

## Özellikler

- Kategori bilgilerini sorgulama ve kategori özelliklerini alma
- Ürün bilgilerini gönderme ve sorgulama
- Listing (ürün listeleme) işlemleri
- Sipariş yönetimi
- Kolay entegrasyon ve kullanım
- Laravel 10+ ve PHP 8.3+ desteği

## Kurulum

Composer ile paketi projenize ekleyin:

```bash
composer require yourname/hepsiburada-sp-api
```

Laravel 10.x ve üzeri için otomatik olarak servis sağlayıcı kaydedilecektir. Laravel 10'dan önceki sürümleri kullanıyorsanız, `config/app.php` dosyasına aşağıdaki servis sağlayıcıyı manuel olarak ekleyin:

```php
'providers' => [
    // ...
    YourName\HepsiburadaApi\Providers\HepsiburadaApiServiceProvider::class,
],

'aliases' => [
    // ...
    'HepsiburadaApi' => YourName\HepsiburadaApi\Facades\HepsiburadaApi::class,
],
```

## Yapılandırma

Öncelikle yapılandırma dosyasını yayınlayın:

```bash
php artisan vendor:publish --tag=hepsiburada-api-config
```

Bu komutu çalıştırdıktan sonra, `config/hepsiburada-api.php` dosyası oluşturulacaktır. Burada API için gereken ayarları yapabilirsiniz.

Alternatif olarak, `.env` dosyanıza aşağıdaki değişkenleri ekleyebilirsiniz:

```
HEPSIBURADA_API_BASE_URL=https://marketplace-api.hepsiburada.com
HEPSIBURADA_API_USERNAME=kullanici_adiniz
HEPSIBURADA_API_PASSWORD=sifreniz
HEPSIBURADA_MERCHANT_ID=satici_id
```

## Kullanım

### Facade ile Kullanım

```php
use HepsiburadaApi;

// Kategorileri listele
$categories = HepsiburadaApi::categories()->getCategories();

// Ürün bilgilerini gönder
$response = HepsiburadaApi::products()->sendProductData([
    // Ürün verileri
]);

// Listing bilgilerini al
$listings = HepsiburadaApi::listings()->getListings();

// Sipariş bilgilerini al
$orders = HepsiburadaApi::orders()->getCompletedOrders();
```

### DI Container ile Kullanım

```php
use YourName\HepsiburadaApi\HepsiburadaApi;

class ProductController extends Controller
{
    protected $api;

    public function __construct(HepsiburadaApi $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        $products = $this->api->products()->getProductsByStatus('APPROVED');
        
        return view('products.index', compact('products'));
    }
}
```

## Örnekler

### Kategori İşlemleri

```php
// Tüm kategorileri listele
$categories = HepsiburadaApi::categories()->getCategories();

// Belirli bir kategorinin özelliklerini al
$attributes = HepsiburadaApi::categories()->getCategoryAttributes('category_id');

// Özellik değerlerini al
$attributeValues = HepsiburadaApi::categories()->getAttributeValues('attribute_id');
```

### Ürün İşlemleri

```php
// Ürün bilgisi gönder
$response = HepsiburadaApi::products()->sendProductData([
    'categoryId' => 'kategori_id',
    'merchant' => 'satici_id',
    'attributes' => [
        // Ürün özellikleri
    ],
    'images' => [
        // Ürün görselleri
    ],
    // Diğer ürün bilgileri
]);

// Ürün durumunu sorgula
$status = HepsiburadaApi::products()->getProductStatus('barkod');

// Ürün statüsüne göre listele
$products = HepsiburadaApi::products()->getProductsByStatus('APPROVED');
```

### Listing İşlemleri

```php
// Listing bilgilerini sorgula
$listings = HepsiburadaApi::listings()->getListings([
    'offset' => 0,
    'limit' => 50
]);

// Fiyat güncelle
$response = HepsiburadaApi::listings()->updatePrice([
    'listings' => [
        [
            'listingId' => 'listing_id',
            'price' => 99.99,
            'availableStock' => 10
        ]
    ]
]);

// Stok güncelle
$response = HepsiburadaApi::listings()->updateStock([
    'listings' => [
        [
            'listingId' => 'listing_id',
            'quantity' => 50
        ]
    ]
]);
```

### Sipariş İşlemleri

```php
// Tamamlanan siparişleri listele
$orders = HepsiburadaApi::orders()->getCompletedOrders([
    'beginDate' => '2023-01-01',
    'endDate' => '2023-12-31'
]);

// Sipariş detaylarını al
$orderDetails = HepsiburadaApi::orders()->getOrderDetails('siparis_numarasi');

// Ürünleri paketle
$response = HepsiburadaApi::orders()->packageItems([
    'merchantId' => 'satici_id',
    'lines' => [
        // Paketlenecek ürün kalemleri
    ]
]);

// Kargoya verme bilgisini ilet
$response = HepsiburadaApi::orders()->markAsShipped([
    'merchantId' => 'satici_id',
    'packageNumber' => 'paket_numarasi',
    'shippingCompany' => 'kargo_firmasi',
    'trackingNumber' => 'takip_numarasi'
]);
```

## Lisans

Bu paket MIT lisansı altında lisanslanmıştır. Daha fazla bilgi için [LICENSE](LICENSE) dosyasına bakın. 