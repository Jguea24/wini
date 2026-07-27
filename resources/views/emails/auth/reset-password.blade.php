<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperación de contraseña - Wini</title>
</head>
<body style="margin:0;background:#2b2b2b;color:#f2f2f2;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:1.6;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#2b2b2b;color:#f2f2f2;">
        <tr>
            <td style="padding:30px;">

                <h2 style="margin:0 0 20px;color:#ffffff;">
                    Sistema Wini
                </h2>

                <p style="margin:0 0 18px;">
                    Hola <strong>{{ mb_strtoupper($user->name) }}</strong>,
                </p>

                <p style="margin:0 0 18px;">
                    Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en <strong>Wini</strong>.
                </p>

                <p style="margin:0 0 8px;">
                    <strong>Correo electrónico:</strong> {{ $user->email }}
                </p>

                <p style="margin:25px 0;">
                    Para crear una nueva contraseña, haz clic en el siguiente enlace:
                </p>

                <p style="margin:0 0 30px;">
                    <a href="{{ $resetUrl }}"
                       style="display:inline-block;padding:12px 24px;background:#4f46e5;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:bold;">
                        Restablecer contraseña
                    </a>
                </p>

                <p style="margin:0 0 15px;">
                    Si tú no realizaste esta solicitud, puedes ignorar este mensaje. Tu contraseña permanecerá segura.
                </p>

                <p style="margin:0 0 25px;">
                    <strong>Importante:</strong> Este enlace estará disponible durante
                    <strong>{{ $expiresIn >= 60 ? (int) ($expiresIn / 60) : $expiresIn }} hora(s)</strong>.
                    Después de ese tiempo será necesario solicitar uno nuevo.
                </p>

                <hr style="border:0;border-top:1px solid #555;margin:30px 0;">

                <p style="margin:0;font-size:13px;color:#cccccc;">
                    Si tienes inconvenientes para acceder a tu cuenta, comunícate con el administrador del sistema.
                </p>

            </td>
        </tr>

        <tr>
            <td style="background:#1f1f1f;padding:20px;text-align:center;font-size:12px;color:#bfbfbf;">

                <strong>Sistema Wini</strong><br>
                Gestión Inteligente de Información<br><br>

                Este es un mensaje generado automáticamente.<br>
                <strong>Por favor, no respondas a este correo.</strong>

            </td>
        </tr>
    </table>
</body>
</html>