# Hepsiburada Marketplace API

Bu Laravel paketi, [Hepsiburada Marketplace API](https://developers.hepsiburada.com/) ile entegrasyon sağlamak için geliştirilmiştir. Paket, Hepsiburada'nın satıcı paneli üzerinden erişilebilen API'leri Laravel uygulamalarınızda kolayca kullanmanızı sağlar.

## Özellikler

- Kategori bilgilerini sorgulama ve kategori özelliklerini alma
- Ürün bilgilerini gönderme ve sorgulama
- Listing (ürün listeleme) işlemleri
- Sipariş yönetimi
- Talep ve iade yönetimi (Claims)
- Finans ve muhasebe işlemleri
- Raporlama
- Taşıma ve lojistik işlemleri
- Kolay entegrasyon ve kullanım
- Laravel 10+ ve PHP 8.3+ desteği

## Kurulum

Composer ile paketi projenize ekleyin:

```bash
composer require wiensa/hepsiburada-sp-api
```

Laravel 10.x ve üzeri için otomatik olarak servis sağlayıcı kaydedilecektir. Laravel 10'dan önceki sürümleri kullanıyorsanız, `config/app.php` dosyasına aşağıdaki servis sağlayıcıyı manuel olarak ekleyin:

```php
'providers' => [
    // ...
    HepsiburadaApi\HepsiburadaSpApi\Providers\HepsiburadaApiServiceProvider::class,
],

'aliases' => [
    // ...
    'HepsiburadaApi' => HepsiburadaApi\HepsiburadaSpApi\Facades\HepsiburadaApi::class,
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
use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;

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
    'packageQuantity' => 1,
    'deci' => 3.5,
    'lineItems' => [
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

### Talep ve İade İşlemleri (Yeni)

```php
// Tüm talepleri listele
$claims = HepsiburadaApi::claims()->getClaims();

// Talep detayını görüntüle
$claimDetails = HepsiburadaApi::claims()->getClaimDetails('claim_id');

// Talebe yanıt gönder
$response = HepsiburadaApi::claims()->respondToClaim([
    'claimId' => 'claim_id',
    'merchantId' => 'merchant_id',
    'response' => 'ACCEPTED',
    'message' => 'Talebiniz onaylandı'
]);

// İade taleplerini listele
$returnRequests = HepsiburadaApi::claims()->getReturnRequests();
```

### Finans ve Muhasebe İşlemleri (Yeni)

```php
// İşlem geçmişini listele
$transactions = HepsiburadaApi::finances()->getTransactions([
    'beginDate' => '2023-01-01',
    'endDate' => '2023-12-31'
]);

// Ödeme özeti al
$paymentSummary = HepsiburadaApi::finances()->getPaymentSummary();

// Ödeme detayını görüntüle
$paymentDetails = HepsiburadaApi::finances()->getPaymentDetails('payment_id');

// Fatura bilgilerini listele
$invoices = HepsiburadaApi::finances()->getInvoices();
```

### Raporlama İşlemleri (Yeni)

```php
// Satış performans raporu al
$salesReport = HepsiburadaApi::reports()->getSalesPerformance();

// Sipariş raporu al
$orderReport = HepsiburadaApi::reports()->getOrderReport([
    'beginDate' => '2023-01-01',
    'endDate' => '2023-12-31'
]);

// Ürün performans raporu al
$productReport = HepsiburadaApi::reports()->getProductPerformance();

// Özel rapor oluştur
$customReport = HepsiburadaApi::reports()->createCustomReport([
    'reportType' => 'CUSTOM',
    'dateRange' => 'LAST_30_DAYS',
    'fields' => ['orderNumber', 'orderDate', 'status']
]);
```

### Taşıma ve Lojistik İşlemleri (Yeni)

```php
// Kargo şirketlerini listele
$carriers = HepsiburadaApi::logistics()->getCarriers();

// Kargo takip bilgilerini güncelle
$trackingUpdate = HepsiburadaApi::logistics()->updateTrackingInfo([
    'packageNumber' => 'package_number',
    'trackingNumber' => 'tracking_number',
    'carrierCode' => 'ARAS'
]);

// Kargo etiketi oluştur
$shippingLabel = HepsiburadaApi::logistics()->createShippingLabel([
    'packageNumber' => 'package_number',
    'format' => 'PDF'
]);
```

## Lisans

Bu paket MIT lisansı altında lisanslanmıştır. Daha fazla bilgi için [LICENSE](LICENSE) dosyasına bakın. 