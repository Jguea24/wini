# Despliegue en produccion - Wini

## Requisitos

- PHP 8.2 o superior.
- MySQL 8 o MariaDB compatible.
- Extensiones PHP: `gd`, `zip`, `xml`, `mbstring`, `openssl`, `pdo_mysql`, `fileinfo`.
- Composer 2.
- Node.js 20 o superior para compilar assets.
- Servidor web apuntando a la carpeta `public`.

## Configuracion inicial

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
cp .env.example .env
php artisan key:generate
```

Edita `.env` con dominio, base de datos y SMTP reales. En produccion usa:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com
APP_FORCE_HTTPS=true
```

## Base de datos

```bash
php artisan migrate --force
php artisan db:seed --force
```

Si no quieres datos de prueba, elimina o ajusta `DatabaseSeeder` antes de ejecutar el seeder.

## Optimizacion

```bash
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## Verificacion

```bash
composer check-platform-reqs
php artisan test
```

## Seguridad operativa

- Nunca subas `.env` al repositorio.
- Usa una cuenta MySQL sin privilegios globales, solo sobre la base `wini`.
- Configura HTTPS en el servidor web.
- Revisa permisos de escritura para `storage` y `bootstrap/cache`.
- Cambia la contrasena del usuario administrador inicial despues del primer ingreso.
