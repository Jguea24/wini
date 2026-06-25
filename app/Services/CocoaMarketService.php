<?php

namespace App\Services;

use App\Models\CocoaMarketPrice;
use App\Repositories\Contracts\CocoaMarketPriceRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler;

class CocoaMarketService
{
    public function __construct(
        private readonly CocoaMarketPriceRepositoryInterface $prices,
    ) {
    }

    public function refresh(bool $force = false): CocoaMarketPrice
    {
        $ttl = max(60, (int) config('cocoa_market.cache_ttl', 3600));

        if (! $force && Cache::has('cocoa-market.latest')) {
            return Cache::get('cocoa-market.latest');
        }

        try {
            $snapshot = $this->fetchSnapshot();
            $price = $this->prices->storeSnapshot($snapshot);

            Cache::put('cocoa-market.latest', $price, $ttl);
            Cache::forget('cocoa-market.dashboard');

            return $price;
        } catch (\Throwable $exception) {
            Log::error('No se pudo actualizar el precio internacional del cacao.', [
                'provider' => config('cocoa_market.provider'),
                'message' => $exception->getMessage(),
            ]);

            $latest = $this->prices->latest();

            if ($latest) {
                return $latest;
            }

            throw $exception;
        }
    }

    public function dashboardData(): array
    {
        return Cache::remember('cocoa-market.dashboard', 600, function () {
            $latest = $this->prices->latest();
            $history = $this->prices->lastDays(30);
            $dailyVariation = $this->variationAgainst($latest, now()->subDay()->toDateString())
                ?? $latest?->change_percent;
            $weeklyVariation = $this->variationAgainst($latest, now()->subWeek()->toDateString())
                ?? $this->variationFromHistory($history)
                ?? $latest?->change_percent;

            return [
                'latest' => $latest,
                'dailyVariation' => $dailyVariation !== null ? round((float) $dailyVariation, 2) : null,
                'weeklyVariation' => $weeklyVariation !== null ? round((float) $weeklyVariation, 2) : null,
                'history' => $history->map(fn (CocoaMarketPrice $price) => [
                    'date' => $price->quoted_at->format('Y-m-d'),
                    'price' => (float) $price->price,
                ])->values(),
            ];
        });
    }

    private function fetchSnapshot(): array
    {
        if (filled(config('cocoa_market.api_url'))) {
            return $this->fetchFromApi();
        }

        return $this->scrapeFromInvesting();
    }

    private function fetchFromApi(): array
    {
        try {
            $response = Http::timeout((int) config('cocoa_market.timeout', 15))
                ->acceptJson()
                ->when(config('cocoa_market.api_key'), fn ($http) => $http->withToken(config('cocoa_market.api_key')))
                ->get(config('cocoa_market.api_url'));
        } catch (ConnectionException $exception) {
            throw new RuntimeException('No hubo conexion con la API financiera.', previous: $exception);
        }

        if (! $response->successful()) {
            throw new RuntimeException('La API financiera respondio con estado '.$response->status().'.');
        }

        $payload = $response->json();
        $price = data_get($payload, 'price') ?? data_get($payload, 'last') ?? data_get($payload, 'data.price');

        if (! is_numeric($price)) {
            throw new RuntimeException('La API financiera no retorno un precio valido.');
        }

        return [
            'provider' => (string) config('cocoa_market.provider', 'api'),
            'symbol' => (string) config('cocoa_market.symbol', 'US Cocoa'),
            'price' => round((float) $price, 2),
            'change_value' => $this->nullableFloat(data_get($payload, 'change') ?? data_get($payload, 'data.change')),
            'change_percent' => $this->nullableFloat(data_get($payload, 'change_percent') ?? data_get($payload, 'data.change_percent')),
            'currency' => (string) config('cocoa_market.currency', 'USD'),
            'unit' => (string) config('cocoa_market.unit', 'tonelada'),
            'quoted_at' => $this->parseDate(data_get($payload, 'updated_at') ?? data_get($payload, 'timestamp')),
            'raw_payload' => $payload,
        ];
    }

    private function scrapeFromInvesting(): array
    {
        $url = (string) config('cocoa_market.scraping_url');

        try {
            $response = Http::timeout((int) config('cocoa_market.timeout', 15))
                ->withHeaders([
                    'User-Agent' => (string) config('cocoa_market.user_agent'),
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8',
                ])
                ->get($url);
        } catch (ConnectionException $exception) {
            throw new RuntimeException('No hubo conexion con Investing.com.', previous: $exception);
        }

        if (! $response->successful()) {
            throw new RuntimeException('Investing.com respondio con estado '.$response->status().'.');
        }

        $html = $response->body();
        $crawler = new Crawler($html);
        $price = $this->extractPrice($crawler, $html);
        $changePercent = $this->extractChangePercent($crawler, $html);

        return [
            'provider' => 'investing',
            'symbol' => (string) config('cocoa_market.symbol', 'US Cocoa'),
            'price' => $price,
            'change_value' => null,
            'change_percent' => $changePercent,
            'currency' => (string) config('cocoa_market.currency', 'USD'),
            'unit' => (string) config('cocoa_market.unit', 'tonelada'),
            'quoted_at' => now(),
            'raw_payload' => [
                'source_url' => $url,
                'scraped_at' => now()->toIso8601String(),
            ],
        ];
    }

    private function extractPrice(Crawler $crawler, string $html): float
    {
        // Investing cambia clases con frecuencia; se priorizan atributos data-test y se deja regex como respaldo.
        $selectors = [
            '[data-test="instrument-price-last"]',
            '[data-test="instrument-header-details"] [data-test="instrument-price-last"]',
            '.instrument-price_last__KQzyA',
            '.text-5xl',
        ];

        foreach ($selectors as $selector) {
            $nodes = $crawler->filter($selector);

            if ($nodes->count() > 0) {
                $price = $this->normalizeNumber($nodes->first()->text(''));

                if ($price !== null) {
                    return $price;
                }
            }
        }

        if (preg_match('/instrument-price-last[^>]*>\s*([^<]+)/i', $html, $matches)) {
            $price = $this->normalizeNumber($matches[1]);

            if ($price !== null) {
                return $price;
            }
        }

        throw new RuntimeException('No se pudo extraer el precio del cacao desde Investing.com.');
    }

    private function extractChangePercent(Crawler $crawler, string $html): ?float
    {
        $selectors = [
            '[data-test="instrument-price-change-percent"]',
            '[data-test="instrument-header-details"] span',
        ];

        foreach ($selectors as $selector) {
            foreach ($crawler->filter($selector) as $node) {
                $text = trim($node->textContent ?? '');

                if (str_contains($text, '%')) {
                    return $this->normalizeNumber($text);
                }
            }
        }

        if (preg_match('/([-+]?\d+[.,]?\d*)\s*%/', $html, $matches)) {
            return $this->normalizeNumber($matches[1]);
        }

        return null;
    }

    private function variationAgainst(?CocoaMarketPrice $latest, string $date): ?float
    {
        if (! $latest) {
            return null;
        }

        $previous = $this->prices->latestBeforeDate($date);

        if (! $previous || (float) $previous->price <= 0) {
            return null;
        }

        return round((((float) $latest->price - (float) $previous->price) / (float) $previous->price) * 100, 2);
    }

    private function variationFromHistory(\Illuminate\Support\Collection $history): ?float
    {
        if ($history->count() < 2) {
            return null;
        }

        $first = $history->first();
        $last = $history->last();

        if (! $first instanceof CocoaMarketPrice || ! $last instanceof CocoaMarketPrice || (float) $first->price <= 0) {
            return null;
        }

        return round((((float) $last->price - (float) $first->price) / (float) $first->price) * 100, 2);
    }

    private function normalizeNumber(?string $value): ?float
    {
        if ($value === null) {
            return null;
        }

        $clean = trim(strip_tags($value));
        $clean = str_replace(["\xc2\xa0", ' ', '%', '+'], '', $clean);
        $clean = preg_replace('/[^0-9,.\-]/', '', $clean);

        if ($clean === '' || $clean === '-') {
            return null;
        }

        // Soporta formato ingles 10,333.00 y formato espanol 10.333,00.
        if (str_contains($clean, ',') && str_contains($clean, '.')) {
            $lastComma = strrpos($clean, ',');
            $lastDot = strrpos($clean, '.');
            $clean = $lastComma > $lastDot
                ? str_replace(',', '.', str_replace('.', '', $clean))
                : str_replace(',', '', $clean);
        } elseif (str_contains($clean, ',') && ! str_contains($clean, '.')) {
            $clean = str_replace(',', '.', $clean);
        }

        return is_numeric($clean) ? round((float) $clean, 4) : null;
    }

    private function nullableFloat(mixed $value): ?float
    {
        return is_numeric($value) ? round((float) $value, 4) : null;
    }

    private function parseDate(mixed $value): Carbon
    {
        if (! $value) {
            return now();
        }

        return is_numeric($value)
            ? Carbon::createFromTimestamp((int) $value)
            : Carbon::parse((string) $value);
    }
}
