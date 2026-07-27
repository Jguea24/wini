<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 18px; }
        body { font-family: DejaVu Sans, sans-serif; color: #000; font-size: 8px; }
        .page { border: 1.5px solid #111; padding: 18px 18px 24px; min-height: 730px; }
        .top { width: 100%; border-collapse: separate; border-spacing: 10px 0; margin: 0 -10px; }
        .top td { vertical-align: top; width: 50%; }
        .logo-box { height: 136px; text-align: center; }
        .logo { max-width: 150px; max-height: 96px; margin-bottom: 8px; }
        .no-logo { color: #f00; font-size: 22px; font-weight: 800; letter-spacing: 1px; padding-top: 18px; }
        .company-box, .auth-box, .client-box, .mini-box, .totals-box { border: 1px solid #111; border-radius: 4px; }
        .company-box { margin-top: 10px; padding: 8px 10px; min-height: 95px; }
        .auth-box { padding: 8px 11px; min-height: 247px; }
        .label { font-weight: 700; }
        .auth-title { font-size: 10px; font-weight: 700; margin: 1px 0 9px; }
        .row { margin-bottom: 8px; }
        .two-col { width: 100%; border-collapse: collapse; }
        .two-col td { width: 50%; padding: 2px 0; vertical-align: top; }
        .barcode { height: 28px; margin: 9px 0 1px; white-space: nowrap; overflow: hidden; }
        .barcode span { display: inline-block; background: #000; margin-right: 1px; vertical-align: top; }
        .bar-0 { width: 1px; height: 28px; }
        .bar-1 { width: 2px; height: 28px; }
        .bar-2 { width: 3px; height: 28px; }
        .bar-3 { width: 1px; height: 22px; margin-top: 3px; }
        .bar-key { text-align: center; font-size: 5px; letter-spacing: .4px; }
        .client-box { margin-top: 18px; padding: 7px 9px; }
        table.grid { width: 100%; border-collapse: collapse; margin-top: 0; }
        table.grid th, table.grid td { border: 1px solid #111; padding: 5px 4px; }
        table.grid th { font-weight: 700; text-align: center; }
        .right { text-align: right; }
        .center { text-align: center; }
        .small { font-size: 6px; }
        .detail-wrap { margin-top: 0; }
        .bottom { width: 100%; border-collapse: separate; border-spacing: 10px 0; margin: 0 -10px; }
        .bottom td { vertical-align: top; }
        .left-bottom { width: 64%; }
        .right-bottom { width: 36%; }
        .mini-box { padding: 5px 7px; margin-top: 0; min-height: 58px; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { vertical-align: top; padding: 0; }
        .info-qr { width: 62px; text-align: right; }
        .info-qr img { width: 56px; height: 56px; }
        .totals-box { width: 100%; border-collapse: collapse; }
        .totals-box td { border: 1px solid #111; padding: 4px 5px; }
        .total-row td { font-weight: 800; }
        .muted { color: #333; }
        .footer { margin-top: 15px; font-size: 7px; color: #333; }
    </style>
</head>
<body>
    @php
        $cliente = $factura->venta->cliente;
        $subtotalSinImpuesto = max(0, (float) $factura->subtotal - (float) $factura->impuesto);
        $ice = 0;
        $irbpnr = 0;
        $propina = 0;
        $totalSinSubsidio = (float) $factura->total;
    @endphp

    <div class="page">
        <table class="top">
            <tr>
                <td>
                    <div class="logo-box">
                        @if (file_exists(public_path('images/wini-logo.png')))
                            <img src="{{ public_path('images/wini-logo.png') }}" class="logo" alt="Wini">
                        @else
                            <div class="no-logo">NO TIENE LOGO</div>
                        @endif
                    </div>

                    <div class="company-box">
                        <p class="label">{{ mb_strtoupper($company['name']) }}</p>
                        <p>{{ $company['address'] ?: 'Direccion matriz no registrada' }}</p>
                        <table class="two-col">
                            <tr>
                                <td><span class="label">Direccion Matriz:</span><br>{{ $company['address'] ?: 'No registrada' }}</td>
                                <td><span class="label">Direccion Sucursal:</span><br>{{ $company['branch_address'] ?: 'No registrada' }}</td>
                            </tr>
                            <tr>
                                <td><span class="label">Telefono:</span> {{ $company['phone'] ?: 'No registrado' }}</td>
                                <td><span class="label">Correo:</span> {{ $company['email'] ?: 'No registrado' }}</td>
                            </tr>
                        </table>
                        <p><span class="label">OBLIGADO A LLEVAR CONTABILIDAD</span> <span style="margin-left: 70px;">{{ $company['accounting_required'] }}</span></p>
                    </div>
                </td>

                <td>
                    <div class="auth-box">
                        <div class="row"><span class="label">R.U.C.:</span> <span style="margin-left: 30px;">{{ $company['ruc'] ?: 'No registrado' }}</span></div>
                        <div class="auth-title">{{ $invoiceMeta['document_type'] }}</div>
                        <div class="row"><span class="label">No.</span> <span style="margin-left: 30px;">{{ $factura->numero }}</span></div>
                        <div class="row"><span class="label">NUMERO DE AUTORIZACION</span></div>
                        <div class="row small">{{ $invoiceMeta['authorization_number'] }}</div>
                        <table class="two-col">
                            <tr>
                                <td><span class="label">FECHA Y HORA DE<br>AUTORIZACION:</span></td>
                                <td>{{ $invoiceMeta['issued_at'] }}</td>
                            </tr>
                            <tr>
                                <td><span class="label">AMBIENTE:</span></td>
                                <td>{{ $invoiceMeta['environment'] }}</td>
                            </tr>
                            <tr>
                                <td><span class="label">EMISION:</span></td>
                                <td>{{ $invoiceMeta['emission'] }}</td>
                            </tr>
                        </table>
                        <div class="row" style="margin-top: 10px;"><span class="label">CLAVE DE ACCESO</span></div>
                        <div class="barcode">
                            @foreach (str_split($invoiceMeta['access_key']) as $digit)
                                <span class="bar-{{ ((int) $digit) % 4 }}"></span>
                            @endforeach
                        </div>
                        <div class="bar-key">{{ $invoiceMeta['access_key'] }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="client-box">
            <table class="two-col">
                <tr>
                    <td><span class="label">Razon Social / Nombres y Apellidos:</span> {{ mb_strtoupper($cliente->nombre_comercial) }}</td>
                    <td><span class="label">Identificacion:</span> {{ $cliente->identificacion ?: 'No registrada' }}</td>
                </tr>
                <tr>
                    <td><span class="label">Fecha:</span> {{ $factura->fecha_emision->format('d/m/Y') }}</td>
                    <td><span class="label">Guia:</span> {{ $factura->numero }}</td>
                </tr>
                <tr>
                    <td colspan="2"><span class="label">Direccion:</span> {{ $cliente->direccion ?: 'No registrada' }}</td>
                </tr>
            </table>
        </div>

        <div class="detail-wrap">
            <table class="grid">
                <thead>
                    <tr>
                        <th style="width: 7%;">Cod.<br>Principal</th>
                        <th style="width: 7%;">Cod.<br>Auxiliar</th>
                        <th style="width: 9%;">Cantidad</th>
                        <th style="width: 23%;">Descripcion</th>
                        <th style="width: 15%;">Detalle Adicional</th>
                        <th style="width: 9%;">Precio Unitario</th>
                        <th style="width: 9%;">Subsidio</th>
                        <th style="width: 9%;">Precio sin Subsidio</th>
                        <th style="width: 7%;">Descuento</th>
                        <th style="width: 10%;">Precio Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="center">CACAO</td>
                        <td class="center">001</td>
                        <td class="right">{{ number_format($factura->venta->libras, 2) }}</td>
                        <td>VENTA DE CACAO</td>
                        <td>{{ ucfirst($factura->venta->metodo_pago) }}</td>
                        <td class="right">{{ number_format($factura->venta->precio_por_libra, 2) }}</td>
                        <td class="right">0.00</td>
                        <td class="right">0.00</td>
                        <td class="right">{{ number_format($factura->descuento, 2) }}</td>
                        <td class="right">{{ number_format($factura->subtotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <table class="bottom">
            <tr>
                <td class="left-bottom">
                    <div class="mini-box">
                        <table class="info-table">
                            <tr>
                                <td>
                                    <span class="label">Informacion Adicional</span><br>
                                    <span class="label">Email:</span> {{ $cliente->correo ?: 'No registrado' }}<br>
                                    <span class="label">Clave:</span> <span class="small">{{ $invoiceMeta['access_key'] }}</span>
                                </td>
                                <td class="info-qr">
                                    @isset($qrCodeDataUri)
                                        <img src="{{ $qrCodeDataUri }}" alt="Codigo QR">
                                    @endisset
                                </td>
                            </tr>
                        </table>
                    </div>

                    <table class="grid" style="margin-top: 6px;">
                        <tr>
                            <th>Forma de pago</th>
                            <th>Valor</th>
                        </tr>
                        <tr>
                            <td>{{ $invoiceMeta['payment_form'] }}</td>
                            <td class="right">{{ number_format($factura->total, 2) }}</td>
                        </tr>
                    </table>
                </td>

                <td class="right-bottom">
                    <table class="totals-box">
                        <tr><td>SUBTOTAL 15%</td><td class="right">{{ number_format($factura->impuesto > 0 ? $subtotalSinImpuesto : 0, 2) }}</td></tr>
                        <tr><td>SUBTOTAL NO OBJETO DE IVA</td><td class="right">0.00</td></tr>
                        <tr><td>SUBTOTAL EXENTO DE IVA</td><td class="right">0.00</td></tr>
                        <tr><td>SUBTOTAL SIN IMPUESTOS</td><td class="right">{{ number_format($factura->subtotal, 2) }}</td></tr>
                        <tr><td>TOTAL DESCUENTO</td><td class="right">{{ number_format($factura->descuento, 2) }}</td></tr>
                        <tr><td>ICE</td><td class="right">{{ number_format($ice, 2) }}</td></tr>
                        <tr><td>IVA 15%</td><td class="right">{{ number_format($factura->impuesto, 2) }}</td></tr>
                        <tr><td>IRBPNR</td><td class="right">{{ number_format($irbpnr, 2) }}</td></tr>
                        <tr><td>PROPINA</td><td class="right">{{ number_format($propina, 2) }}</td></tr>
                        <tr class="total-row"><td>VALOR TOTAL</td><td class="right">{{ number_format($factura->total, 2) }}</td></tr>
                        <tr><td>VALOR TOTAL SIN SUBSIDIO</td><td class="right">{{ number_format($totalSinSubsidio, 2) }}</td></tr>
                        <tr><td>AHORRO POR SUBSIDIO<br><span class="small">(Incluye IVA cuando corresponda)</span></td><td class="right">0.00</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="footer">
            {{ $footer }} | Estado: {{ ucfirst($factura->estado) }} | Creada por: {{ $factura->user?->name ?? 'Sin usuario' }}
        </div>
    </div>
</body>
</html>
