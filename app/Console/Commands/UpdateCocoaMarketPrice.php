<?php

namespace App\Console\Commands;

use App\Services\CocoaMarketService;
use Illuminate\Console\Command;

class UpdateCocoaMarketPrice extends Command
{
    protected $signature = 'market:cocoa:update {--force : Ignora cache y consulta el proveedor externo}';

    protected $description = 'Actualiza el precio internacional del cacao y lo almacena en el historico.';

    public function handle(CocoaMarketService $market): int
    {
        try {
            $price = $market->refresh((bool) $this->option('force'));
        } catch (\Throwable $exception) {
            $this->error('No se pudo actualizar el mercado del cacao: '.$exception->getMessage());

            return self::FAILURE;
        }

        $this->info(sprintf(
            'Cacao actualizado: %s %s (%s)',
            number_format((float) $price->price, 2),
            $price->currency,
            $price->quoted_at->format('Y-m-d H:i')
        ));

        return self::SUCCESS;
    }
}
