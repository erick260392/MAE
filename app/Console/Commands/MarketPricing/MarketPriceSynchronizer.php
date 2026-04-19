<?php

namespace App\Console\Commands\MarketPricing;

use App\Models\Product;
use Illuminate\Support\Str;

final class MarketPriceSynchronizer
{
    public function __construct(
        protected PahusaPriceProvider $pahusa,
        protected CasaMyersPriceProvider $casaMyers,
        protected XaguarAutomationPriceProvider $xaguar,
    ) {}

    /**
     * @return array<int, string>
     */
    public function availableSources(): array
    {
        return array_keys($this->providers());
    }

    /**
     * @return array<int, string>
     */
    public function availableStrategies(): array
    {
        return ['median', 'average', 'minimum', 'maximum'];
    }

    /**
     * @param  array<int, string>  $requestedSources
     * @return array<int, string>
     */
    public function resolveSources(array $requestedSources): array
    {
        if ($requestedSources === []) {
            return $this->availableSources();
        }

        $requested = collect($requestedSources)
            ->filter(fn (mixed $source): bool => is_string($source) && trim($source) !== '')
            ->map(fn (string $source): string => Str::lower(trim($source)))
            ->unique()
            ->values()
            ->all();

        return array_values(array_intersect($this->availableSources(), $requested));
    }

    public function normalizeStrategy(string $strategy): ?string
    {
        $strategy = Str::lower(trim($strategy));

        return in_array($strategy, $this->availableStrategies(), true) ? $strategy : null;
    }

    /**
     * @param  array<int, string>  $sources
     * @return array{
     *     matched: bool,
     *     price: float|null,
     *     candidates: array<int, MarketPriceCandidate>,
     *     selected: array<int, MarketPriceCandidate>
     * }
     */
    public function resolvePrice(Product $product, array $sources, string $strategy): array
    {
        $sku = trim((string) $product->sku);

        if ($sku === '') {
            return [
                'matched' => false,
                'price' => null,
                'candidates' => [],
                'selected' => [],
            ];
        }

        $searchQueries = $this->searchQueriesForSku($sku);
        $matchingCodes = $this->matchingCodesForSku($sku);
        $candidates = [];

        foreach ($sources as $source) {
            $provider = $this->providers()[$source] ?? null;

            if ($provider === null) {
                continue;
            }

            $providerCandidates = [];

            foreach ($searchQueries as $query) {
                foreach ($provider->search($query) as $candidate) {
                    $qualifiedCandidate = $this->qualifyCandidate($candidate, $matchingCodes);

                    if ($qualifiedCandidate !== null) {
                        $providerCandidates[$this->candidateKey($qualifiedCandidate)] = $qualifiedCandidate;
                    }
                }

                if ($providerCandidates !== []) {
                    break;
                }
            }

            foreach ($providerCandidates as $candidateKey => $candidate) {
                $candidates[$candidateKey] = $candidate;
            }
        }

        if ($candidates === []) {
            return [
                'matched' => false,
                'price' => null,
                'candidates' => [],
                'selected' => [],
            ];
        }

        $selected = [];

        foreach (collect($candidates)->groupBy('provider') as $providerCandidates) {
            $bestCandidate = null;

            foreach ($providerCandidates as $candidate) {
                if (! $candidate instanceof MarketPriceCandidate) {
                    continue;
                }

                if ($bestCandidate === null) {
                    $bestCandidate = $candidate;

                    continue;
                }

                if ($candidate->confidence > $bestCandidate->confidence) {
                    $bestCandidate = $candidate;

                    continue;
                }

                if ($candidate->confidence === $bestCandidate->confidence && $candidate->price < $bestCandidate->price) {
                    $bestCandidate = $candidate;
                }
            }

            if ($bestCandidate instanceof MarketPriceCandidate) {
                $selected[] = $bestCandidate;
            }
        }

        return [
            'matched' => $selected !== [],
            'price' => $selected === [] ? null : $this->aggregatePrice($selected, $strategy),
            'candidates' => $candidates,
            'selected' => $selected,
        ];
    }

    /**
     * @return array<string, PahusaPriceProvider|CasaMyersPriceProvider|XaguarAutomationPriceProvider>
     */
    protected function providers(): array
    {
        return [
            'pahusa' => $this->pahusa,
            'casa-myers' => $this->casaMyers,
            'xaguar' => $this->xaguar,
        ];
    }

    /**
     * @param  array<int, string>  $matchingCodes
     */
    protected function qualifyCandidate(MarketPriceCandidate $candidate, array $matchingCodes): ?MarketPriceCandidate
    {
        if ($matchingCodes === []) {
            return null;
        }

        $normalizedProviderSku = $this->normalizeCode($candidate->providerSku);

        if ($normalizedProviderSku !== '' && in_array($normalizedProviderSku, $matchingCodes, true)) {
            return $candidate->withMatch(100, 'provider-sku');
        }

        foreach ($matchingCodes as $matchingCode) {
            if ($this->canMatchByTitle($matchingCode) && $this->titleContainsSku($candidate->title, $matchingCode)) {
                return $candidate->withMatch(90, 'title-code');
            }
        }

        return null;
    }

    /**
     * @return array<int, string>
     */
    protected function searchQueriesForSku(string $sku): array
    {
        return $this->skuVariants($sku);
    }

    /**
     * @return array<int, string>
     */
    protected function matchingCodesForSku(string $sku): array
    {
        return collect($this->skuVariants($sku))
            ->map(fn (string $variant): string => $this->normalizeCode($variant))
            ->filter(fn (string $variant): bool => $variant !== '')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    protected function skuVariants(string $sku): array
    {
        $normalizedSku = trim($sku);
        $strippedVariant = preg_match('/^S[0-9]/i', $normalizedSku) === 1 ? substr($normalizedSku, 1) : null;

        $variants = $this->prefersStrippedSearch($normalizedSku)
            ? [$strippedVariant, $normalizedSku]
            : [$normalizedSku, $strippedVariant];

        return collect($variants)
            ->filter(fn (?string $variant): bool => is_string($variant) && trim($variant) !== '')
            ->unique()
            ->values()
            ->all();
    }

    protected function prefersStrippedSearch(string $sku): bool
    {
        return preg_match('/^S(?:[0-9]{4,}|[0-9]+-)/i', $sku) === 1;
    }

    protected function candidateKey(MarketPriceCandidate $candidate): string
    {
        return implode('|', [
            $candidate->provider,
            $this->normalizeCode($candidate->providerSku),
            $this->normalizeCode($candidate->title),
            number_format($candidate->price, 2, '.', ''),
            $candidate->url,
        ]);
    }

    protected function canMatchByTitle(string $normalizedSku): bool
    {
        return str_contains($normalizedSku, '-') && strlen($normalizedSku) >= 6;
    }

    protected function titleContainsSku(string $title, string $normalizedSku): bool
    {
        return str_contains($this->normalizeCode($title), $normalizedSku);
    }

    protected function normalizeCode(?string $value): string
    {
        $value = Str::upper(Str::ascii((string) $value));

        return preg_replace('/[^A-Z0-9-]+/', '', $value) ?? '';
    }

    /**
     * @param  array<int, MarketPriceCandidate>  $candidates
     */
    protected function aggregatePrice(array $candidates, string $strategy): float
    {
        $prices = array_map(
            fn (MarketPriceCandidate $candidate): float => round($candidate->price, 2),
            $candidates,
        );

        sort($prices, SORT_NUMERIC);

        return match ($strategy) {
            'average' => round(array_sum($prices) / count($prices), 2),
            'minimum' => round(min($prices), 2),
            'maximum' => round(max($prices), 2),
            default => $this->median($prices),
        };
    }

    /**
     * @param  array<int, float>  $prices
     */
    protected function median(array $prices): float
    {
        $count = count($prices);
        $middle = intdiv($count, 2);

        if ($count % 2 === 1) {
            return round($prices[$middle], 2);
        }

        return round(($prices[$middle - 1] + $prices[$middle]) / 2, 2);
    }
}
