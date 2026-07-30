<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>¡Bienvenido a WINI!</title>
</head>
<body style="margin:0;padding:0;background:#f5f3ef;color:#1c1917;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.6;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f3ef;width:100%;margin:0;padding:36px 14px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border:1px solid #e7e5e4;border-radius:12px;overflow:hidden;">
                    <tr>
                        <td style="background:#1c1917;padding:30px 36px;text-align:center;">
                            @if (file_exists(public_path('images/wini-logo.png')))
                                <img src="{{ $message->embed(public_path('images/wini-logo.png')) }}" alt="Wini" width="118" style="display:block;width:118px;max-width:118px;height:auto;margin:0 auto 14px;">
                            @else
                                <div style="margin:0 auto 14px;color:#fde68a;font-size:28px;font-weight:800;">WINI</div>
                            @endif
                            <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:800;">¡Bienvenido a WINI!</h1>
                            <p style="margin:6px 0 0;color:#fde68a;font-size:13px;">Control financiero del cacao</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px 38px;">
                            <h2 style="margin:0 0 18px;color:#1c1917;font-size:22px;font-weight:800;">Hola, {{ $user->name }}.</h2>
                            <p style="margin:0 0 16px;color:#44403c;">Te confirmamos que tu registro en la plataforma <strong>WINI</strong> se completó exitosamente.</p>
                            <p style="margin:0 0 16px;color:#44403c;">Gracias por registrarte. Nos alegra darte la bienvenida a nuestra plataforma.</p>
                            <p style="margin:0 0 26px;color:#44403c;">Ya puedes iniciar sesión y utilizar WINI para gestionar tu información y tus operaciones de manera organizada.</p>

                            <div style="text-align:center;margin:30px 0;">
                                <a href="{{ $loginUrl }}" style="display:inline-block;background:#78350f;color:#ffffff;text-decoration:none;border-radius:6px;padding:13px 28px;font-weight:700;">Iniciar sesión</a>
                            </div>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:28px;background:#fafaf9;border-left:4px solid #92400e;border-radius:6px;">
                                <tr>
                                    <td style="padding:14px 16px;color:#57534e;font-size:13px;">
                                        <strong style="color:#1c1917;">Datos de tu cuenta</strong><br>
                                        Nombre: {{ $user->name }}<br>
                                        Correo: {{ $user->email }}<br>
                                        Estado: Activa
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#1c1917;padding:22px 36px;text-align:center;color:#d6d3d1;font-size:12px;">
                            <p style="margin:0 0 8px;font-weight:700;color:#ffffff;">Sistema WINI</p>
                            <p style="margin:0;">Este es un mensaje automático. Por favor no respondas directamente a este correo.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
