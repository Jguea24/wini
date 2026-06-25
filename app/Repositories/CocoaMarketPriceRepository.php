<?php

namespace App\Repositories;

use App\Models\CocoaMarketPrice;
use App\Repositories\Contracts\CocoaMarketPriceRepositoryInterface;
use Illuminate\Support\Collection;

class CocoaMarketPriceRepository implements CocoaMarketPriceRepositoryInterface
{
    public function latest(): ?CocoaMarketPrice
    {
        return CocoaMarketPrice::query()
            ->latest('quoted_at')
            ->latest('id')
            ->first();
    }

    public function storeSnapshot(array $data): CocoaMarketPrice
    {
        return CocoaMarketPrice::query()->create($data);
    }

    public function lastDays(int $days = 30): Collection
    {
        return CocoaMarketPrice::query()
            ->where('quoted_at', '>=', now()->subDays($days)->startOfDay())
            ->orderBy('quoted_at')
            ->get();
    }

    public function latestBeforeDate(string $date): ?CocoaMarketPrice
    {
        return CocoaMarketPrice::query()
            ->whereDate('quoted_at', '<=', $date)
            ->latest('quoted_at')
            ->latest('id')
            ->first();
    }
}
