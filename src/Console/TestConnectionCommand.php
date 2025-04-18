<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Console;

use Exception;
use Illuminate\Console\Command;
use HepsiburadaApi\HepsiburadaSpApi\HepsiburadaApi;

class TestConnectionCommand extends Command
{
    /**
     * Komut ismi
     *
     * @var string
     */
    protected $signature = 'hepsiburada:test-connection';

    /**
     * Komut açıklaması
     *
     * @var string
     */
    protected $description = 'Hepsiburada API bağlantısını test eder';

    /**
     * API istemcisi
     *
     * @var HepsiburadaApi
     */
    protected HepsiburadaApi $api;

    /**
     * TestConnectionCommand sınıfı yapıcı fonksiyonu
     */
    public function __construct(HepsiburadaApi $api)
    {
        parent::__construct();
        $this->api = $api;
    }

    /**
     * Komutu çalıştırır
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('Hepsiburada API bağlantısı test ediliyor...');

        $this->table(['Ayar', 'Değer'], [
            ['API URL', config('hepsiburada-api.base_url')],
            ['Kullanıcı Adı', config('hepsiburada-api.username')],
            ['Şifre', config('hepsiburada-api.password') ? '********' : '<boş>'],
            ['Satıcı ID', config('hepsiburada-api.merchant_id')],
        ]);

        try {
            $this->testApiConnection();
            return 0;
        } catch (Exception $e) {
            $this->error('Bağlantı hatası: ' . $e->getMessage());
            $this->line('');
            $this->comment('API kimlik bilgilerinizi kontrol edin ve tekrar deneyin.');
            $this->comment('Kimlik bilgilerini güncellemek için `php artisan hepsiburada:install` komutunu çalıştırın.');
            return 1;
        }
    }

    /**
     * API bağlantısını test eder
     *
     * @return void
     * @throws Exception
     */
    private function testApiConnection(): void
    {
        if (empty(config('hepsiburada-api.username')) || empty(config('hepsiburada-api.password'))) {
            throw new Exception('API kimlik bilgileri eksik. Lütfen önce kimlik bilgilerinizi yapılandırın.');
        }

        $this->output->write('Kategori servisi test ediliyor... ');
        
        try {
            $categories = $this->api->categories()->getCategories();
            if (is_array($categories)) {
                $this->output->writeln('<info>✓ Başarılı</info>');
            } else {
                $this->output->writeln('<comment>? Yanıt alındı ama beklenen formatta değil</comment>');
            }
        } catch (Exception $e) {
            $this->output->writeln('<error>✗ Başarısız</error>');
            throw $e;
        }

        $this->output->write('Ürün servisi test ediliyor... ');
        
        try {
            // Sadece bağlantı testi yapılıyor, gerçek veri gönderilmiyor
            $response = $this->api->products()->getProductsByStatus('AVAILABLE', 0, 1);
            $this->output->writeln('<info>✓ Başarılı</info>');
        } catch (Exception $e) {
            $this->output->writeln('<error>✗ Başarısız</error>');
            throw $e;
        }

        $this->info('API bağlantısı başarılı! Sisteme erişebiliyorsunuz.');
    }
} 