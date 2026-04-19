<?php

namespace App\Console\Commands\MarketPricing;

final class MarketPriceCandidate
{
    public function __construct(
        public string $provider,
        public string $query,
        public string $title,
        public float $price,
        public string $url,
        public ?string $providerSku = null,
        public int $confidence = 0,
        public ?string $matchedBy = null,
    ) {}

    public function withMatch(int $confidence, string $matchedBy): self
    {
        return new self(
            provider: $this->provider,
            query: $this->query,
            title: $this->title,
            price: $this->price,
            url: $this->url,
            providerSku: $this->providerSku,
            confidence: $confidence,
            matchedBy: $matchedBy,
        );
    }

    /**
     * @return array{
     *     provider: string,
     *     query: string,
     *     title: string,
     *     price: float,
     *     url: string,
     *     provider_sku: string|null,
     *     confidence: int,
     *     matched_by: string|null
     * }
     */
    public function toArray(): array
    {
        return [
            'provider' => $this->provider,
            'query' => $this->query,
            'title' => $this->title,
            'price' => $this->price,
            'url' => $this->url,
            'provider_sku' => $this->providerSku,
            'confidence' => $this->confidence,
            'matched_by' => $this->matchedBy,
        ];
    }
}
