# Prezo sale system

## Descripción
Este proyecto gestiona las ventas de un restaurante, incluyendo la creación de ventas, el cálculo de márgenes de beneficio y la identificación de días con mayor y menor volumen de ventas.

## Requisitos
- PHP >= 8.0
- Laravel >= 11.0
- Composer
- SQLite

## Instalación

1. Instala las dependencias:
    ```bash
    composer install
    ```
2. Configura el archivo `.env` para la base de datos SQLite:
    ```ini
    DB_CONNECTION=sqlite
    DB_DATABASE=./database/database.sqlite
    ```
3. Ejecuta las migraciones para crear la base de datos:
    ```bash
    php artisan migrate
    ```

4. Rellenar la base de datos:
    ```bash
    php artisan db:seed
    ```

    o

    ```bash
    php artisan db:seed --class=ProductsSeeder
    php artisan db:seed --class=SalesSeeder
    ```

5. Para acceder a la base de datos:
    ```bash
    sqlite3 database/database.sqlite
    ```