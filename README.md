# Mokkao

Aplicación web Take Away para pedir café y recogerlo en sucursal.

## Sprint 1 incluido

- Estructura base en Laravel 12.
- Registro, inicio y cierre de sesión.
- Catálogo de productos disponibles.
- Carrito en sesión con cantidad y notas.
- Confirmación de pedidos básicos con sucursal, fecha y hora de recogida.
- Consulta del estado y detalle de pedidos propios.

## Stack

- PHP
- Laravel
- XAMPP / Apache
- MySQL
- HTML y CSS

## Puesta en marcha con XAMPP

1. Crear una base de datos MySQL llamada `mokkao`.
2. Copiar `.env.example` a `.env`.
3. Revisar en `.env`:
   - `DB_CONNECTION=mysql`
   - `DB_DATABASE=mokkao`
   - `DB_USERNAME=root`
   - `DB_PASSWORD=`
4. Ejecutar:

```bash
composer install
php artisan key:generate
php artisan migrate --seed
```

5. Apuntar Apache a la carpeta `public` del proyecto o acceder mediante un virtual host.

## Usuario administrador inicial

- Email: `admin@mokkao.test`
- Contraseña: `admin12345`

> El panel de administración se desarrollará en el Sprint 2; este usuario queda preparado desde la base de datos.
