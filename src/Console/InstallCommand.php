<?php

namespace HepsiburadaApi\HepsiburadaSpApi\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    /**
     * Komut ismi
     *
     * @var string
     */
    protected $signature = 'hepsiburada:install';

    /**
     * Komut açıklaması
     *
     * @var string
     */
    protected $description = 'Hepsiburada API paketini kurar ve yapılandırır';

    /**
     * Komutu çalıştırır
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('Hepsiburada API paketi kuruluyor...');

        // Konfigürasyon dosyasını yayınla
        $this->call('vendor:publish', [
            '--tag' => 'hepsiburada-api-config',
            '--force' => true,
        ]);

        // .env dosyasına gerekli değişkenleri ekleyelim
        $this->updateEnvironmentFile();

        // Kullanıcıdan API bilgilerini alın
        $this->configureApiCredentials();

        $this->info('Hepsiburada API paketi başarıyla kuruldu!');
        $this->info('Aşağıdaki komutla bağlantınızı test edebilirsiniz:');
        $this->comment('php artisan hepsiburada:test-connection');

        return 0;
    }

    /**
     * .env dosyasını günceller
     *
     * @return void
     */
    private function updateEnvironmentFile(): void
    {
        $env_file = $this->laravel->environmentFilePath();
        $env_content = File::get($env_file);

        $variables = [
            'HEPSIBURADA_API_BASE_URL' => 'https://marketplace-api.hepsiburada.com',
            'HEPSIBURADA_API_USERNAME' => '',
            'HEPSIBURADA_API_PASSWORD' => '',
            'HEPSIBURADA_MERCHANT_ID' => '',
        ];

        foreach ($variables as $key => $default_value) {
            if (!preg_match("/^{$key}=/m", $env_content)) {
                $env_content .= PHP_EOL . "{$key}={$default_value}";
                $this->line("<info>{$key}</info> değişkeni .env dosyasına eklendi.");
            }
        }

        File::put($env_file, $env_content);
    }

    /**
     * API kimlik bilgilerini yapılandırır
     *
     * @return void
     */
    private function configureApiCredentials(): void
    {
        $this->line('');
        $this->line('API kimlik bilgilerini yapılandıralım...');

        if ($this->confirm('API kimlik bilgilerini şimdi yapılandırmak istiyor musunuz?', true)) {
            $base_url = $this->ask('Hepsiburada API Base URL', 'https://marketplace-api.hepsiburada.com');
            $username = $this->ask('Hepsiburada API Kullanıcı Adı');
            $password = $this->secret('Hepsiburada API Şifresi');
            $merchant_id = $this->ask('Hepsiburada Satıcı ID');

            // .env dosyasını güncelle
            $this->updateEnvironmentVariables([
                'HEPSIBURADA_API_BASE_URL' => $base_url,
                'HEPSIBURADA_API_USERNAME' => $username,
                'HEPSIBURADA_API_PASSWORD' => $password,
                'HEPSIBURADA_MERCHANT_ID' => $merchant_id,
            ]);

            $this->info('API kimlik bilgileri başarıyla yapılandırıldı.');
        } else {
            $this->comment('Kimlik bilgilerini daha sonra manuel olarak yapılandırabilirsiniz.');
            $this->comment('config/hepsiburada-api.php dosyasını düzenleyin veya .env dosyasına aşağıdaki değişkenleri ekleyin:');
            $this->line('HEPSIBURADA_API_BASE_URL=https://marketplace-api.hepsiburada.com');
            $this->line('HEPSIBURADA_API_USERNAME=kullanıcı_adınız');
            $this->line('HEPSIBURADA_API_PASSWORD=şifreniz');
            $this->line('HEPSIBURADA_MERCHANT_ID=satıcı_id');
        }
    }

    /**
     * Belirtilen değişkenleri .env dosyasında günceller
     *
     * @param array $variables
     * @return void
     */
    private function updateEnvironmentVariables(array $variables): void
    {
        $env_file = $this->laravel->environmentFilePath();
        $env_content = File::get($env_file);

        foreach ($variables as $key => $value) {
            if (preg_match("/^{$key}=/m", $env_content)) {
                $env_content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $env_content);
            } else {
                $env_content .= PHP_EOL . "{$key}={$value}";
            }
        }

        File::put($env_file, $env_content);
    }
} 