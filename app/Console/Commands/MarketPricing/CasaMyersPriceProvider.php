<?php

namespace App\Console\Commands\MarketPricing;

use Illuminate\Http\Client\Factory as HttpFactory;
use Throwable;

final class CasaMyersPriceProvider
{
    public function __construct(protected HttpFactory $http) {}

    /**
     * @return array<int, MarketPriceCandidate>
     */
    public function search(string $query): array
    {
        try {
            $response = $this->http
                ->accept('text/html,application/xhtml+xml')
                ->connectTimeout(10)
                ->timeout(20)
                ->retry(2, 250)
                ->get('https://www.casamyers.com.mx/catalogsearch/result/', [
                    'q' => $query,
                ]);

            if ($response->failed()) {
                return [];
            }

            return $this->parse($query, $response->body());
        } catch (Throwable) {
            return [];
        }
    }

    /**
     * @return array<int, MarketPriceCandidate>
     */
    protected function parse(string $query, string $html): array
    {
        if (str_contains($html, "addClass('no-result')") || str_contains($html, 'catalogsearch_result_index_noresults')) {
            return [];
        }

        if (
            ! preg_match('/<meta property="product:price:amount" content="([0-9.]+)"/', $html, $priceMatch) ||
            ! preg_match('/<meta property="og:title" content="([^"]+)"/', $html, $titleMatch) ||
            ! preg_match('/<meta property="og:url" content="([^"]+)"/', $html, $urlMatch)
        ) {
            return [];
        }

        $providerSku = null;

        if (preg_match('/"sku":"([^"]+)"/', $html, $skuMatch)) {
            $providerSku = $this->string($skuMatch[1]);
        }

        $title = $this->string($titleMatch[1]);
        $url = $this->string($urlMatch[1]);

        if ($title === '' || $url === '') {
            return [];
        }

        return [
            new MarketPriceCandidate(
                provider: 'casa-myers',
                query: $query,
                title: $title,
                price: round((float) $priceMatch[1], 2),
                url: $url,
                providerSku: $providerSku,
            ),
        ];
    }

    protected function string(string $value): string
    {
        return trim(html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }
}
