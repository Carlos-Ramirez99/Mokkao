# Mokkao

Aplicación web Take Away para pedir café y recogerlo en sucursal.

## Estado del proyecto

### Sprint 1 incluido

- Estructura base en Laravel 12.
- Registro, inicio y cierre de sesión.
- Catálogo de productos disponibles.
- Carrito en sesión con cantidad y notas.
- Confirmación de pedidos básicos con sucursal, fecha y hora de recogida.
- Consulta del estado y detalle de pedidos propios.

### Sprint 2 incluido

- Acceso restringido para administradores.
- Panel de administración con métricas básicas.
- Gestión de categorías.
- Gestión completa de productos: crear, editar, ocultar y eliminar.
- Gestión de pedidos: listado, filtrado, detalle y actualización de estado.

### Sprint 3 incluido

- Registro de pagos por pedido sin almacenar datos sensibles de tarjeta.
- Métodos de pago: tarjeta, Bizum, PayPal y efectivo.
- Reporte administrativo de ventas y pagos.
- Filtros de ventas por fecha, estado y método de pago.
- Resumen visual del checkout antes de confirmar el pedido.
- Pruebas de flujo de pago, reporte de ventas y permisos.

### Sprint 4 incluido

- Documentación final de instalación y despliegue.
- Checklist de mantenimiento inicial.
- Verificación de migraciones, seeders y pruebas desde cero.
- Configuración base preparada para XAMPP, Apache y MySQL.

## Stack

- PHP 8.2+
- Laravel 12
- XAMPP / Apache
- MySQL
- HTML y CSS

## Puesta en marcha local con XAMPP

1. Clonar o copiar el proyecto dentro de `C:\xampp\htdocs\Mokkao`.
2. Crear una base de datos MySQL llamada `mokkao`.
3. Copiar `.env.example` a `.env`.
4. Revisar en `.env`:

```env
APP_NAME=Mokkao
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost/Mokkao/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mokkao
DB_USERNAME=root
DB_PASSWORD=
```

5. Instalar dependencias y preparar Laravel:

```bash
composer install
php artisan key:generate
php artisan migrate --seed
```

6. Abrir la aplicación apuntando Apache a la carpeta `public` o usando un virtual host.

## Usuario administrador inicial

- Email: `admin@mokkao.test`
- Contraseña: `admin12345`

## Rutas principales

### Cliente

- `/` — Home
- `/about` — About
- `/menu` — Shop / catálogo
- `/carrito` — Carrito
- `/pedidos` — Pedidos del cliente
- `/perfil` — Perfil del usuario

### Administración

- `/admin` — Dashboard
- `/admin/productos` — Gestión de productos
- `/admin/pedidos` — Gestión de pedidos
- `/admin/ventas` — Reporte de ventas y pagos

## Comandos útiles

```bash
php artisan migrate:fresh --seed
php artisan test
php artisan config:clear
php artisan route:list
```

## Verificación final de entrega

Antes de entregar o desplegar:

```bash
php artisan config:clear
php artisan migrate:fresh --seed
php artisan test
```

Resultado esperado actual:

- Migraciones correctas.
- Seeders correctos.
- Tests pasando.

## Notas de seguridad

- No se almacenan datos sensibles de tarjeta.
- Los pagos online están simulados en Sprint 3.
- El panel de administración está protegido por autenticación y rol `administrador`.
- En producción se debe usar `APP_DEBUG=false`.
- En producción se debe cambiar la contraseña del administrador inicial.

Las guías locales de despliegue y mantenimiento pueden mantenerse fuera del repositorio si contienen notas específicas del entorno.
