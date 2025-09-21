# Instrucciones para aplicar la migración

Para resolver el error "SQLSTATE[42703]: Undefined column: 7 ERROR: no existe la columna «record_type» en la relación «vaccination_records»", necesitas ejecutar la migración que hemos creado para añadir estos campos a la tabla.

Sigue estos pasos:

1. Abre una terminal en la raíz del proyecto

2. Ejecuta el siguiente comando para aplicar la migración:

```bash
php artisan migrate
```

Esto creará las columnas necesarias en la tabla `vaccination_records`.

3. Si experimentas algún problema con la migración, puedes forzar su recreación completa con:

```bash
php artisan migrate:fresh --path=database/migrations/2025_06_09_000000_create_vaccination_records_table.php
```

**ADVERTENCIA**: `migrate:fresh` eliminará todas las tablas y datos existentes, solo úsalo en un entorno de desarrollo.

4. O puedes ejecutar específicamente la nueva migración:

```bash
php artisan migrate --path=database/migrations/2025_06_09_000000_create_vaccination_records_table.php
```

Después de ejecutar la migración, la funcionalidad de historial médico debería funcionar correctamente.
