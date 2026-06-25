<?php

namespace App\Repositories\Contracts;

use App\Models\CocoaMarketPrice;
use Illuminate\Support\Collection;

interface CocoaMarketPriceRepositoryInterface
{
    public function latest(): ?CocoaMarketPrice;

    public function storeSnapshot(array $data): CocoaMarketPrice;

    public function lastDays(int $days = 30): Collection;

    public function latestBeforeDate(string $date): ?CocoaMarketPrice;
}
