<?php

namespace App\Console\Commands;

use App\Console\Commands\MarketPricing\MarketPriceSynchronizer;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SyncMarketPrices extends Command
{
    protected $signature = 'app:sync-market-prices
                            {--sku=* : Sync only the provided SKU values}
                            {--limit=0 : Maximum number of products to inspect}
                            {--from-id= : Only include products with id greater than or equal to this value}
                            {--to-id= : Only include products with id less than or equal to this value}
                            {--source=* : Providers to use: pahusa, casa-myers, xaguar}
                            {--strategy=median : Aggregation strategy: median, average, minimum, maximum}
                            {--all : Include products that already have a price}
                            {--dry-run : Preview changes without saving}';

    protected $description = 'Sync high-confidence product prices from Mexican market providers';

    public function handle(MarketPriceSynchronizer $synchronizer): int
    {
        $sources = $synchronizer->resolveSources($this->option('source'));

        if ($sources === []) {
            $this->components->error('No valid sources were provided.');

            return self::FAILURE;
        }

        $strategy = $synchronizer->normalizeStrategy((string) $this->option('strategy'));

        if ($strategy === null) {
            $this->components->error('Invalid strategy. Use one of: '.implode(', ', $synchronizer->availableStrategies()).'.');

            return self::FAILURE;
        }

        $query = Product::query()
            ->whereNotNull('sku')
            ->where('sku', '!=', '')
            ->orderBy('id');

        if (! $this->option('all')) {
            $query->where('price', 0);
        }

        $skus = collect($this->option('sku'))
            ->filter(fn (mixed $sku): bool => is_string($sku) && trim($sku) !== '')
            ->map(fn (string $sku): string => trim($sku))
            ->values();

        if ($skus->isNotEmpty()) {
            $query->whereIn('sku', $this->expandSkuFilters($skus));
        }

        $fromId = $this->integerOption('from-id');

        if ($fromId !== null) {
            $query->where('id', '>=', $fromId);
        }

        $toId = $this->integerOption('to-id');

        if ($toId !== null) {
            $query->where('id', '<=', $toId);
        }

        $limit = max((int) $this->option('limit'), 0);

        if ($limit > 0) {
            $query->limit($limit);
        }

        $products = $query->get();

        if ($products->isEmpty()) {
            $this->components->info('No products matched the selected filters.');

            return self::SUCCESS;
        }

        $this->components->info(sprintf(
            'Processing %d product(s) with sources: %s. Strategy: %s%s',
            $products->count(),
            implode(', ', $sources),
            $strategy,
            $this->option('dry-run') ? ' (dry run)' : '',
        ));

        $progressBar = $this->output->createProgressBar($products->count());
        $progressBar->start();

        $processed = 0;
        $matched = 0;
        $changed = 0;
        $unchanged = 0;
        $examples = [];

        foreach ($products as $product) {
            $processed++;

            $result = $synchronizer->resolvePrice($product, $sources, $strategy);

            if (! $result['matched'] || $result['price'] === null) {
                $progressBar->advance();

                continue;
            }

            $matched++;
            $newPrice = round($result['price'], 2);
            $currentPrice = round((float) $product->price, 2);

            if ($currentPrice === $newPrice) {
                $unchanged++;
                $progressBar->advance();

                continue;
            }

            if (! $this->option('dry-run')) {
                $product->forceFill(['price' => $newPrice])->save();
            }

            $changed++;

            if (count($examples) < 10) {
                $providers = collect($result['selected'])
                    ->map(fn ($candidate): string => sprintf('%s:%s', $candidate->provider, number_format($candidate->price, 2)))
                    ->implode(', ');

                $examples[] = [
                    $product->sku,
                    number_format($currentPrice, 2),
                    number_format($newPrice, 2),
                    $providers,
                ];
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->table(
            ['Metric', 'Value'],
            [
                ['Processed', (string) $processed],
                ['Matched', (string) $matched],
                [$this->option('dry-run') ? 'Would Update' : 'Updated', (string) $changed],
                ['Unchanged', (string) $unchanged],
                ['No Match', (string) ($processed - $matched)],
            ],
        );

        if ($examples !== []) {
            $this->newLine();
            $this->table(['SKU', 'Current', 'New', 'Sources'], $examples);
        }

        return self::SUCCESS;
    }

    /**
     * @param  Collection<int, string>  $skus
     * @return array<int, string>
     */
    protected function expandSkuFilters(Collection $skus): array
    {
        return $skus
            ->flatMap(function (string $sku): array {
                $variants = [$sku];

                if (preg_match('/^S[0-9]/i', $sku) === 1) {
                    $variants[] = substr($sku, 1);
                }

                if (preg_match('/^[0-9]/', $sku) === 1) {
                    $variants[] = 'S'.$sku;
                }

                return $variants;
            })
            ->filter(fn (string $sku): bool => trim($sku) !== '')
            ->unique()
            ->values()
            ->all();
    }

    protected function integerOption(string $option): ?int
    {
        $value = $this->option($option);

        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }
}
