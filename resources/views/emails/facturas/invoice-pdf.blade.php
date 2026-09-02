<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Factura {{ $factura->numero }}</title>
</head>
<body style="margin:0;padding:0;background:#f5f3ef;color:#1c1917;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.6;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f3ef;width:100%;margin:0;padding:36px 14px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px;background:#ffffff;border:1px solid #e7e5e4;border-radius:10px;overflow:hidden;">
                    <tr>
                        <td style="background:#1c1917;padding:28px 36px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:800;">{{ $nombreEmpresa }}</h1>
                            <p style="margin:6px 0 0;color:#fde68a;font-size:13px;">Factura {{ $factura->numero }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px 38px;">
                            <p style="margin:0 0 16px;color:#44403c;">Estimado(a) {{ $nombreCliente }},</p>
                            <p style="margin:0 0 16px;color:#44403c;">Gracias por su compra.</p>
                            <p style="margin:0 0 16px;color:#44403c;">Adjuntamos la factura correspondiente a su compra en formato PDF.</p>
                            <p style="margin:0 0 26px;color:#44403c;">Si tiene alguna consulta sobre su compra o requiere asistencia, estaremos encantados de atenderle.</p>
                            <p style="margin:0;color:#44403c;">Saludos cordiales,</p>
                            <p style="margin:6px 0 0;color:#1c1917;font-weight:700;">{{ $nombreEmpresa }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#1c1917;padding:20px 36px;text-align:center;color:#d6d3d1;font-size:12px;">
                            Este es un mensaje automatico. Por favor no responda directamente a este correo.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
