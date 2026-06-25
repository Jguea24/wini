# Wini

Wini es un sistema web de gestion financiera y comercial para WINI S.A.S. Permite administrar clientes, ventas, gastos, inversiones, facturas, reportes y seguimiento del mercado internacional del cacao.

El proyecto esta construido con Laravel 12, Blade, Tailwind CSS, Alpine.js, Vite, MySQL y paquetes para exportacion PDF/Excel.

## Tabla de Contenido

- [Caracteristicas](#caracteristicas)
- [Tecnologias](#tecnologias)
- [Requisitos](#requisitos)
- [Instalacion local](#instalacion-local)
- [Configuracion del entorno](#configuracion-del-entorno)
- [Base de datos](#base-de-datos)
- [Usuario inicial](#usuario-inicial)
- [Modulos del sistema](#modulos-del-sistema)
- [Rutas principales](#rutas-principales)
- [Comandos utiles](#comandos-utiles)
- [Reportes y facturas](#reportes-y-facturas)
- [Mercado del cacao](#mercado-del-cacao)
- [Estructura del proyecto](#estructura-del-proyecto)
- [Verificacion](#verificacion)
- [Despliegue en produccion](#despliegue-en-produccion)
- [Notas de mantenimiento](#notas-de-mantenimiento)

## Caracteristicas

- Dashboard financiero con indicadores mensuales y generales.
- Gestion de clientes con historial comercial.
- Registro de ventas por cliente.
- Registro de gastos operativos.
- Registro de inversiones.
- Facturacion con datos legales y PDF.
- Reportes financieros en pantalla, Excel y PDF.
- Administracion de usuarios y configuracion empresarial.
- Cambio de idioma ES/EN.
- Perfil de usuario con foto.
- Seguimiento del precio internacional del cacao.
- Zona horaria configurada para Ecuador: `America/Guayaquil`.

## Tecnologias

### Backend

- PHP 8.2+
- Laravel 12
- MySQL / MariaDB
- Laravel Blade
- DomPDF (`barryvdh/laravel-dompdf`)
- Excel (`maatwebsite/excel`)
- QR para PDF (`bacon/bacon-qr-code`)

### Frontend

- Tailwind CSS
- Tailwind Forms
- Alpine.js
- Vite
- Blade Components

## Requisitos

- PHP 8.2 o superior.
- Composer 2.
- Node.js 20 o superior.
- MySQL 8 o MariaDB compatible.
- Servidor local: XAMPP, Laravel Valet, Herd o similar.
- Extensiones PHP:
  - `gd`
  - `zip`
  - `xml`
  - `mbstring`
  - `openssl`
  - `pdo_mysql`
  - `fileinfo`

## Instalacion local

1. Clonar o copiar el proyecto.

```bash
cd C:\xampp\htdocs\wini
```

2. Instalar dependencias PHP.

```bash
composer install
```

3. Instalar dependencias frontend.

```bash
npm install
```

4. Crear archivo de entorno.

```bash
cp .env.example .env
```

En Windows PowerShell tambien puedes copiar manualmente `.env.example` como `.env`.

5. Generar llave de aplicacion.

```bash
php artisan key:generate
```

6. Configurar base de datos en `.env`.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wini
DB_USERNAME=root
DB_PASSWORD=
```

7. Ejecutar migraciones y datos iniciales.

```bash
php artisan migrate --seed
```

8. Compilar assets.

```bash
npm.cmd run build
```

9. Levantar servidor local.

```bash
php artisan serve
```

URL local por defecto:

```text
http://127.0.0.1:8000
```

## Configuracion del entorno

Variables principales del archivo `.env`:

```env
APP_NAME=Wini
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
APP_FORCE_HTTPS=false
APP_TIMEZONE=America/Guayaquil

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_ES
```

Para produccion:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com
APP_FORCE_HTTPS=true
```

## Base de datos

La base de datos principal se llama:

```text
wini
```

Tablas principales:

- `users`: usuarios del sistema.
- `clientes`: clientes comerciales.
- `ventas`: ventas registradas por cliente.
- `gastos`: gastos operativos.
- `inversiones`: inversiones realizadas.
- `facturas`: facturas generadas.
- `settings`: configuracion empresarial y fiscal.
- `cocoa_market_prices`: precios historicos del mercado del cacao.
- `sessions`, `cache`, `jobs`: tablas internas de Laravel.

Para refrescar toda la base local:

```bash
php artisan migrate:fresh --seed
```

Advertencia: este comando borra todos los datos existentes.

## Usuario inicial

El seeder crea un usuario administrador usando estas variables:

```env
ADMIN_NAME=Administrador Wini
ADMIN_EMAIL=admin@wini.local
ADMIN_PASSWORD=password
```

Si no se definen, Laravel usa los valores por defecto:

- Correo: `admin@wini.local`
- Clave: `password`

Cambia la clave antes de usar el sistema con informacion real.

## Modulos del sistema

### Dashboard

Ruta:

```text
/dashboard
```

Muestra:

- Ingresos mensuales.
- Gastos mensuales.
- Ganancia mensual.
- Libras vendidas.
- Cliente principal.
- Mayor gasto del mes.
- Precio promedio por libra.
- Inversiones mensuales.
- Graficos de ventas, gastos, inversiones y tendencia mensual.
- Panel del mercado internacional del cacao.

### Clientes

Ruta:

```text
/clientes
```

Permite:

- Listar clientes.
- Crear clientes.
- Editar clientes.
- Ver historial comercial.
- Buscar por nombre, empresa, identificacion, correo o telefono.

Campos principales:

- Nombre.
- Empresa.
- RUC/Cedula.
- Telefono.
- Direccion.
- Correo electronico.

### Ventas

Ruta:

```text
/ventas
```

Permite:

- Registrar ventas por cliente.
- Controlar fecha, libras, precio por libra, total y metodo de pago.
- Relacionar cada venta con el usuario que la registra.

### Gastos

Ruta:

```text
/gastos
```

Permite:

- Registrar gastos operativos.
- Clasificar por tipo.
- Agregar descripcion.
- Controlar monto y fecha.

### Inversiones

Ruta:

```text
/inversiones
```

Permite:

- Registrar inversiones.
- Clasificar por tipo y concepto.
- Agregar descripcion y monto.
- Asociar usuario creador.

### Facturas

Ruta:

```text
/facturas
```

Permite:

- Listar facturas.
- Crear facturas desde ventas.
- Actualizar estado.
- Ver detalle.
- Generar PDF.

Ruta PDF:

```text
/facturas/{factura}/pdf
```

### Reportes

Ruta:

```text
/reportes
```

Permite:

- Consultar resumen financiero mensual.
- Exportar Excel.
- Exportar PDF.

Rutas de exportacion:

```text
/reportes/excel
/reportes/pdf
```

### Mercado del cacao

Ruta:

```text
/mercado-cacao
```

Permite:

- Consultar precio internacional del cacao.
- Ver historico registrado.
- Actualizar manualmente el precio.
- Consultar datos en vivo para el dashboard.

Rutas:

```text
/mercado-cacao
/mercado-cacao/live
/mercado-cacao/actualizar
```

### Administracion

Rutas bajo prefijo:

```text
/admin
```

Solo usuarios administradores.

Incluye:

- Gestion de usuarios.
- Configuracion del sistema.
- Datos empresariales.
- Datos de facturacion.
- Configuracion fiscal.

## Rutas principales

| Modulo | Ruta | Proteccion |
|---|---|---|
| Login | `/login` | Publica |
| Dashboard | `/dashboard` | Auth + verified |
| Clientes | `/clientes` | Auth |
| Ventas | `/ventas` | Auth |
| Gastos | `/gastos` | Auth |
| Inversiones | `/inversiones` | Auth |
| Facturas | `/facturas` | Auth |
| Reportes | `/reportes` | Auth |
| Mercado cacao | `/mercado-cacao` | Auth |
| Perfil | `/profile` | Auth |
| Usuarios admin | `/admin/users` | Auth + admin |
| Configuracion admin | `/admin/settings` | Auth + admin |

## Comandos utiles

### Desarrollo

```bash
php artisan serve
npm run dev
```

En Windows, si PowerShell bloquea `npm.ps1`, usa:

```bash
npm.cmd run dev
npm.cmd run build
```

### Compilar assets

```bash
npm.cmd run build
```

### Limpiar cache

```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Cache para produccion

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Migraciones

```bash
php artisan migrate
php artisan migrate --seed
php artisan migrate:fresh --seed
```

### Storage

```bash
php artisan storage:link
```

### Mercado del cacao

Actualizar precio de cacao:

```bash
php artisan market:cocoa:update
```

Forzar consulta externa ignorando cache:

```bash
php artisan market:cocoa:update --force
```

## Reportes y facturas

El sistema usa:

- `ReporteFinancieroService` para calcular resumen financiero.
- `ReporteMensualExport` para exportacion Excel.
- DomPDF para reportes y facturas PDF.
- `PdfQrCodeService` para codigos QR en documentos.

Los reportes consideran:

- Total de ingresos.
- Total de gastos.
- Total de inversiones.
- Ganancia neta.
- Flujo despues de inversion.
- Libras vendidas.
- Precio promedio por libra.

## Mercado del cacao

Configuracion en:

```text
config/cocoa_market.php
```

Variables disponibles:

```env
COCOA_MARKET_PROVIDER=investing
COCOA_MARKET_SYMBOL="US Cocoa"
COCOA_MARKET_CURRENCY=USD
COCOA_MARKET_UNIT=tonelada
COCOA_MARKET_CACHE_TTL=3600
COCOA_MARKET_API_URL=
COCOA_MARKET_API_KEY=
COCOA_MARKET_SCRAPING_URL=https://es.investing.com/commodities/us-cocoa
COCOA_MARKET_USER_AGENT="Mozilla/5.0 (compatible; WiniMarketBot/1.0; +https://wini.local)"
COCOA_MARKET_TIMEOUT=15
```

Los precios se guardan en:

```text
cocoa_market_prices
```

## Estructura del proyecto

```text
app/
  Console/Commands/         Comandos Artisan personalizados
  Exports/                  Exportadores Excel
  Http/Controllers/         Controladores web
  Http/Requests/            Validaciones por formulario
  Models/                   Modelos Eloquent
  Services/                 Servicios de dominio

config/
  app.php                   Configuracion general
  cocoa_market.php          Configuracion mercado cacao

database/
  migrations/               Migraciones
  seeders/                  Datos iniciales

resources/
  css/                      Estilos Tailwind
  js/                       JavaScript frontend
  views/                    Vistas Blade
    clientes/               Pantallas de clientes
    ventas/                 Pantallas de ventas
    gastos/                 Pantallas de gastos
    inversiones/            Pantallas de inversiones
    facturas/               Pantallas y PDFs de facturas
    reportes/               Pantallas y PDFs de reportes
    components/             Componentes Blade
    layouts/                Layouts principales

routes/
  web.php                   Rutas web
  console.php               Tareas programadas/comandos

public/
  build/                    Assets compilados por Vite
  storage/                  Enlace simbolico a storage/app/public
```

## Verificacion

Verificar dependencias PHP:

```bash
composer check-platform-reqs
```

Ejecutar pruebas:

```bash
php artisan test
```

Compilar frontend:

```bash
npm.cmd run build
```

Ver informacion del sistema:

```bash
php artisan about
```

Debe mostrar:

```text
Timezone: America/Guayaquil
Locale: es
Database: mysql
```

## Despliegue en produccion

1. Configurar `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com
APP_FORCE_HTTPS=true
APP_TIMEZONE=America/Guayaquil

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wini
DB_USERNAME=usuario
DB_PASSWORD=clave_segura
```

2. Instalar dependencias optimizadas:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

3. Ejecutar migraciones:

```bash
php artisan migrate --force
```

4. Crear enlace de storage:

```bash
php artisan storage:link
```

5. Cachear configuracion:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

6. Configurar el servidor web para apuntar a:

```text
public/
```

## Notas de mantenimiento

- No editar archivos dentro de `vendor/` ni `node_modules/`.
- Ejecutar `npm.cmd run build` despues de cambiar clases Tailwind o vistas con nuevas utilidades.
- Ejecutar `php artisan config:clear` despues de cambiar `.env`.
- Ejecutar `php artisan view:clear` si una vista no refleja cambios.
- Respaldar la base de datos antes de correr migraciones en produccion.
- Cambiar la clave del usuario administrador por defecto.
- Mantener `APP_DEBUG=false` en produccion.

## Comandos usados con frecuencia en Windows

```bash
php artisan serve
php artisan migrate --seed
php artisan optimize:clear
npm.cmd run build
```

## Licencia

Sistema interno para WINI S.A.S. Ajustar esta seccion segun la politica de propiedad intelectual de la empresa.
