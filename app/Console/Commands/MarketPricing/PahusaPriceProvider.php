<?php

namespace App\Console\Commands\MarketPricing;

final class PahusaPriceProvider extends ShopifySearchPriceProvider
{
    protected function baseUrl(): string
    {
        return 'https://pahusa.com';
    }

    protected function providerName(): string
    {
        return 'pahusa';
    }
}
