# Wini

Sistema Laravel para control financiero de compra y venta de cacao.

## Modulos

- Dashboard financiero.
- Clientes.
- Ventas por cliente.
- Gastos.
- Inversiones.
- Facturas.
- Reportes en pantalla, Excel y PDF.
- Administracion de usuarios y configuracion.

## Requisitos

- PHP 8.2 o superior.
- Composer 2.
- Node.js 20 o superior.
- MySQL 8 o MariaDB compatible.
- Extensiones PHP: `gd`, `zip`, `xml`, `mbstring`, `openssl`, `pdo_mysql`, `fileinfo`.

## Instalacion local

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

En Windows PowerShell, si `npm run build` esta bloqueado por politicas de ejecucion, usa:

```bash
npm.cmd run build
```

## Usuario inicial

El seeder crea un usuario de prueba usando las variables `ADMIN_NAME`, `ADMIN_EMAIL` y `ADMIN_PASSWORD` del `.env`.

Valores por defecto:

- Correo: `admin@wini.local`
- Clave: `password`

Cambia esa clave antes de usar el sistema con datos reales.

## Verificacion

```bash
composer check-platform-reqs
php artisan test
npm.cmd run build
```

## Produccion

Configura `.env` con valores reales:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com
APP_FORCE_HTTPS=true
MAIL_MAILER=smtp
```

Ejecuta:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
