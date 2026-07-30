<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar la contraseña - Wini</title>
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
                            <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:800;">Sistema Wini</h1>
                            <p style="margin:6px 0 0;color:#fde68a;font-size:13px;">Control financiero del cacao</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px 38px;">
                            <h2 style="margin:0 0 18px;color:#1c1917;font-size:21px;font-weight:800;">Recuperar contraseña</h2>
                            <p style="margin:0 0 16px;color:#44403c;">Hola <strong>{{ mb_strtoupper($user->name) }}</strong>,</p>
                            <p style="margin:0 0 16px;color:#44403c;">Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en <strong>Wini</strong>.</p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:22px 0;background:#fafaf9;border-left:4px solid #92400e;border-radius:6px;">
                                <tr>
                                    <td style="padding:14px 16px;color:#57534e;font-size:13px;">
                                        <strong style="color:#1c1917;">Correo electrónico:</strong>
                                        <a href="mailto:{{ $user->email }}" style="color:#78350f;text-decoration:underline;">{{ $user->email }}</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 22px;color:#44403c;">Para crear una nueva contraseña, haz clic en el siguiente botón:</p>

                            <div style="text-align:center;margin:28px 0 30px;">
                                <a href="{{ $resetUrl }}" style="display:inline-block;background:#78350f;color:#ffffff;text-decoration:none;border-radius:6px;padding:13px 28px;font-weight:700;">Restablecer contraseña</a>
                            </div>

                            <p style="margin:0 0 14px;color:#44403c;">Si tú no realizaste esta solicitud, puedes ignorar este mensaje. Tu contraseña permanecerá segura.</p>
                            <p style="margin:0;color:#44403c;">
                                <strong>Importante:</strong> Este enlace estará disponible durante
                                <strong>{{ $expiresIn >= 60 ? (int) ($expiresIn / 60) : $expiresIn }} hora(s)</strong>.
                                Después de ese tiempo será necesario solicitar uno nuevo.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#1c1917;padding:22px 36px;text-align:center;color:#d6d3d1;font-size:12px;">
                            <p style="margin:0 0 8px;font-weight:700;color:#ffffff;">Sistema Wini</p>
                            <p style="margin:0 0 6px;">Si tienes inconvenientes para acceder a tu cuenta, comunícate con el administrador del sistema.</p>
                            <p style="margin:0;font-weight:700;">Por favor, no respondas a este correo.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
