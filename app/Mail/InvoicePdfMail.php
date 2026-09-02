<?php

namespace App\Mail;

use App\Models\Factura;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class InvoicePdfMail extends Mailable
{
    public function __construct(
        public Factura $factura,
        public string $nombreCliente,
        public string $nombreEmpresa,
        private readonly string $pdfContent,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Factura de su compra N.º {$this->factura->numero}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.facturas.invoice-pdf',
            with: [
                'factura' => $this->factura,
                'nombreCliente' => $this->nombreCliente,
                'nombreEmpresa' => $this->nombreEmpresa,
            ],
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => $this->pdfContent,
                "factura-{$this->factura->numero}.pdf",
            )->withMime('application/pdf'),
        ];
    }
}
