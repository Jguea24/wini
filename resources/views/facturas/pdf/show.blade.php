<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1c1917; font-size: 12px; }
        .header { border-bottom: 2px solid #065f46; padding-bottom: 14px; margin-bottom: 20px; }
        .logo { width: 90px; vertical-align: middle; }
        .brand { display: inline-block; vertical-align: middle; margin-left: 16px; }
        h1, h2 { margin: 0; color: #065f46; }
        table { border-collapse: collapse; width: 100%; margin-top: 18px; }
        th, td { border: 1px solid #d6d3d1; padding: 9px; text-align: left; }
        th { background: #f5f5f4; }
        .right { text-align: right; }
        .total { font-size: 16px; font-weight: bold; color: #065f46; }
        .muted { color: #57534e; }
        .signature { margin-top: 34px; width: 280px; text-align: center; }
        .signature img { max-width: 220px; max-height: 90px; margin: 0 auto 8px; }
        .signature-line { border-top: 1px solid #78716c; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        @if (file_exists(public_path('images/wini-logo.png')))
            <img src="{{ public_path('images/wini-logo.png') }}" class="logo" alt="Wini">
        @endif
        <div class="brand">
            <h1>{{ $company['name'] }}</h1>
            <p class="muted">Factura {{ $factura->numero }}</p>
            <p class="muted">RUC/Cédula: {{ $company['ruc'] ?: 'No registrado' }}</p>
            <p class="muted">{{ $company['address'] ?: 'Dirección no registrada' }}</p>
            <p class="muted">{{ $company['phone'] ?: 'Teléfono no registrado' }} {{ $company['email'] ? '| '.$company['email'] : '' }}</p>
        </div>
    </div>

    <table>
        <tr><th>Fecha emisión</th><td>{{ $factura->fecha_emision->format('Y-m-d') }}</td><th>Estado</th><td>{{ ucfirst($factura->estado) }}</td></tr>
        <tr><th>Cliente</th><td>{{ $factura->venta->cliente->nombre_comercial }}</td><th>Método de pago</th><td>{{ ucfirst($factura->venta->metodo_pago) }}</td></tr>
        <tr><th>RUC/Cédula</th><td>{{ $factura->venta->cliente->identificacion ?: 'No registrado' }}</td><th>Teléfono</th><td>{{ $factura->venta->cliente->telefono ?: 'No registrado' }}</td></tr>
        <tr><th>Dirección</th><td>{{ $factura->venta->cliente->direccion ?: 'No registrada' }}</td><th>Correo</th><td>{{ $factura->venta->cliente->correo ?: 'No registrado' }}</td></tr>
    </table>

    <table>
        <tr><th>Detalle</th><th class="right">Libras</th><th class="right">Precio/lb</th><th class="right">Total</th></tr>
        <tr>
            <td>Venta de cacao</td>
            <td class="right">{{ number_format($factura->venta->libras, 2) }}</td>
            <td class="right">${{ number_format($factura->venta->precio_por_libra, 2) }}</td>
            <td class="right">${{ number_format($factura->subtotal, 2) }}</td>
        </tr>
    </table>

    <table>
        <tr><td>Subtotal</td><td class="right">${{ number_format($factura->subtotal, 2) }}</td></tr>
        <tr><td>Descuento</td><td class="right">${{ number_format($factura->descuento, 2) }}</td></tr>
        <tr><td>Impuesto</td><td class="right">${{ number_format($factura->impuesto, 2) }}</td></tr>
        <tr><td>Total</td><td class="right total">${{ number_format($factura->total, 2) }}</td></tr>
    </table>

    <p class="muted">{{ $footer }}</p>
    <div class="signature">
        @if ($signaturePath && file_exists($signaturePath))
            <img src="{{ $signaturePath }}" alt="Firma">
        @endif
        <div class="signature-line">
            <strong>Firmado por: {{ $signatureName ?: 'Johnny Grefa' }}</strong><br>
            <span class="muted">{{ $signatureRole ?: 'CEO de Wini' }}</span>
        </div>
    </div>
    <p class="muted">Creada por: {{ $factura->user?->name ?? 'Sin usuario' }}. Actualizada por: {{ $factura->actualizador?->name ?? 'Sin cambios' }}. Anulada por: {{ $factura->anulador?->name ?? 'No anulada' }}.</p>
</body>
</html>
