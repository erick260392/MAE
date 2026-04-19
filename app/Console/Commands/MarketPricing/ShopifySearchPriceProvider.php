<?php

namespace App\Console\Commands\MarketPricing;

use Illuminate\Http\Client\Factory as HttpFactory;
use Throwable;

abstract class ShopifySearchPriceProvider
{
    public function __construct(protected HttpFactory $http) {}

    abstract protected function baseUrl(): string;

    abstract protected function providerName(): string;

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
                ->get($this->baseUrl().'/search', [
                    'q' => $query,
                    'type' => 'product',
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
        $metaJson = $this->extractMetaJson($html);

        if ($metaJson === null) {
            return [];
        }

        $meta = json_decode($metaJson, true);

        if (! is_array($meta)) {
            return [];
        }

        $products = $meta['products'] ?? null;

        if (! is_array($products)) {
            return [];
        }

        $candidates = [];

        foreach ($products as $product) {
            if (! is_array($product)) {
                continue;
            }

            $productTitle = $this->string($product['title'] ?? null);
            $productUrl = $this->productUrl($product);
            $variants = $product['variants'] ?? [];

            if (! is_array($variants)) {
                continue;
            }

            foreach ($variants as $variant) {
                if (! is_array($variant)) {
                    continue;
                }

                $rawPrice = $variant['price'] ?? null;

                if (! is_numeric($rawPrice)) {
                    continue;
                }

                $title = $this->string($variant['name'] ?? null);

                if ($title === '' || $title === 'Default Title') {
                    $title = $productTitle;
                }

                if ($title === '' || $productUrl === '') {
                    continue;
                }

                $candidates[] = new MarketPriceCandidate(
                    provider: $this->providerName(),
                    query: $query,
                    title: $title,
                    price: round(((float) $rawPrice) / 100, 2),
                    url: $productUrl,
                    providerSku: $this->string($variant['sku'] ?? null),
                );
            }
        }

        return $candidates;
    }

    /**
     * @param  array<string, mixed>  $product
     */
    protected function productUrl(array $product): string
    {
        $url = $this->absoluteUrl($this->string($product['url'] ?? null));

        if ($url !== '') {
            return $url;
        }

        $handle = $this->string($product['handle'] ?? null);

        if ($handle === '') {
            return '';
        }

        return $this->absoluteUrl('/products/'.$handle);
    }

    protected function extractMetaJson(string $html): ?string
    {
        $markerPosition = strpos($html, 'var meta = ');

        if ($markerPosition === false) {
            return null;
        }

        $jsonStart = strpos($html, '{', $markerPosition);

        if ($jsonStart === false) {
            return null;
        }

        $depth = 0;
        $inString = false;
        $isEscaped = false;
        $length = strlen($html);

        for ($index = $jsonStart; $index < $length; $index++) {
            $character = $html[$index];

            if ($inString) {
                if ($isEscaped) {
                    $isEscaped = false;

                    continue;
                }

                if ($character === '\\') {
                    $isEscaped = true;

                    continue;
                }

                if ($character === '"') {
                    $inString = false;
                }

                continue;
            }

            if ($character === '"') {
                $inString = true;

                continue;
            }

            if ($character === '{') {
                $depth++;

                continue;
            }

            if ($character !== '}') {
                continue;
            }

            $depth--;

            if ($depth === 0) {
                return substr($html, $jsonStart, ($index - $jsonStart) + 1);
            }
        }

        return null;
    }

    protected function absoluteUrl(string $path): string
    {
        if ($path === '') {
            return '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return rtrim($this->baseUrl(), '/').'/'.ltrim($path, '/');
    }

    protected function string(mixed $value): string
    {
        if (! is_string($value)) {
            return '';
        }

        return trim(html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }
}
