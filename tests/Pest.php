<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| Pest test sınıfı ve yardımcı fonksiyonlar.
|
*/

use HepsiburadaApi\HepsiburadaSpApi\Tests\TestCase;

uses(TestCase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| Pest beklentileri için makrolar.
|
*/

expect()->extend('toBeResponseArray', function () {
    return $this->toBeArray()
        ->toHaveKey('success');
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| Testlerde kullanılabilecek yardımcı fonksiyonlar.
|
*/

function getConfig(): array
{
    return [
        'base_url' => 'https://marketplace-api.hepsiburada.com',
        'username' => 'test_username',
        'password' => 'test_password',
        'merchant_id' => 'test_merchant_id',
    ];
} 