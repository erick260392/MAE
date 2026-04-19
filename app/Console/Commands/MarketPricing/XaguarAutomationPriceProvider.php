<?php

namespace App\Console\Commands\MarketPricing;

final class XaguarAutomationPriceProvider extends ShopifySearchPriceProvider
{
    protected function baseUrl(): string
    {
        return 'https://xaguarautomation.com';
    }

    protected function providerName(): string
    {
        return 'xaguar';
    }
}
