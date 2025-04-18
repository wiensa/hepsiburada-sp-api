# HepsiburadaApi Test Paketi

Bu klasör, Hepsiburada API entegrasyonu için test dosyalarını içerir. Test paketi, PHPUnit ve Pest frameworkleri kullanılarak geliştirilmiştir.

## Test Yapısı

Test paketi iki ana bölümden oluşur:

1. **Unit Tests**: Bileşenlerin yalıtılmış olarak test edildiği birim testleri
2. **Feature Tests**: Paketin Laravel uygulaması içindeki entegrasyonunu test eden özellik testleri

## Testleri Çalıştırma

Testleri aşağıdaki komutlar ile çalıştırabilirsiniz:

```bash
# Tüm testleri çalıştır
composer test

# Sadece birim testlerini çalıştır
composer test:unit

# Sadece özellik testlerini çalıştır
composer test:feature

# Kod kapsama raporu ile testleri çalıştır
composer test:coverage
```

## Test Ortamı

Test ortamı, varsayılan olarak aşağıdaki test değişkenlerini kullanır:

- **Base URL**: `https://marketplace-api.hepsiburada.com`
- **Username**: `test_username`
- **Password**: `test_password`
- **Merchant ID**: `test_merchant_id`

Bu değerler `phpunit.xml` dosyasında veya `.env.testing` dosyasında değiştirilebilir.

## Mocklar

API çağrıları için mock'lar, `TestCase` sınıfında sağlanan yardımcı fonksiyonlar ile oluşturulabilir:

```php
// HTTP yanıtı oluştur
$response = $this->mockResponse(200, ['success' => true]);

// HTTP istemcisi oluştur
$client = $this->mockHttpClient([$response]);
```

## Yeni Test Eklemek

Yeni bir test eklerken, ilgili sınıfın işlevselliğine göre Unit veya Feature dizini altında uygun bir dosyada oluşturulması gerekir. Test adlandırması, aşağıdaki şablonu izlemelidir:

- Birim testleri için: `SınıfAdıTest.php`
- Özellik testleri için: `ÖzellikAdıFeatureTest.php`

Pest ile yeni bir test oluşturmak için:

```php
test('Özellik doğru şekilde çalışır', function () {
    // Test kodları
    expect(true)->toBeTrue();
});
``` 