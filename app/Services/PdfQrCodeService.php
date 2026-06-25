<?php

namespace App\Services;

use BaconQrCode\Renderer\GDLibRenderer;
use BaconQrCode\Writer;

class PdfQrCodeService
{
    public function dataUri(string $payload, int $size = 180): string
    {
        $renderer = new GDLibRenderer($size);
        $writer = new Writer($renderer);
        $png = $writer->writeString($payload);

        return 'data:image/png;base64,'.base64_encode($png);
    }
}
