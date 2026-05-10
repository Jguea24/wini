<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1c1917; font-size: 12px; }
        .header { width: 100%; border-bottom: 2px solid #065f46; padding-bottom: 14px; margin-bottom: 18px; }
        .logo { width: 112px; vertical-align: middle; }
        .brand { display: inline-block; vertical-align: middle; margin-left: 18px; }
        h1 { color: #065f46; margin: 0 0 4px; }
        table { border-collapse: collapse; width: 100%; margin-top: 24px; }
        th, td { border: 1px solid #d6d3d1; padding: 10px; text-align: left; }
        th { background: #f5f5f4; }
        .total { font-size: 18px; font-weight: bold; color: #065f46; }
    </style>
</head>
<body>
    <div class="header">
        @if (file_exists(public_path('images/wini-logo.png')))
            <img src="{{ public_path('images/wini-logo.png') }}" class="logo" alt="Wini">
        @endif
        <div class="brand">
            <h1>Reporte financiero Wini</h1>
            <p>Periodo: {{ $desde }} a {{ $hasta }}</p>
        </div>
    </div>

    <table>
        <tr><th>Concepto</th><th>Valor</th></tr>
        <tr><td>Total libras vendidas</td><td>{{ number_format($totalLibrasVendidas, 2) }}</td></tr>
        <tr><td>Total ingresos</td><td>${{ number_format($totalIngresos, 2) }}</td></tr>
        <tr><td>Total gastos</td><td>${{ number_format($totalGastos, 2) }}</td></tr>
        <tr><td>Ganancia neta</td><td class="total">${{ number_format($gananciaNeta, 2) }}</td></tr>
    </table>

    <table>
        <tr><th colspan="2">Ventas por cliente</th></tr>
        @forelse ($ventasPorCliente as $ventaCliente)
            <tr><td>{{ $ventaCliente->cliente?->nombre_comercial ?? 'Sin cliente' }}</td><td>${{ number_format($ventaCliente->total_cliente, 2) }}</td></tr>
        @empty
            <tr><td colspan="2">Sin ventas en el periodo.</td></tr>
        @endforelse
    </table>

    <table>
        <tr><th colspan="2">Gastos por tipo</th></tr>
        @forelse ($gastosPorTipo as $gastoTipo)
            <tr><td>{{ str_replace('_', ' ', ucfirst($gastoTipo->tipo)) }}</td><td>${{ number_format($gastoTipo->total_tipo, 2) }}</td></tr>
        @empty
            <tr><td colspan="2">Sin gastos en el periodo.</td></tr>
        @endforelse
    </table>
</body>
</html>
