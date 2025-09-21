<php
use Spatie\Permission\Models\Role;

$role = Role::where('name', 'cliente_qr')->first();
dd($role); // Debe mostrar el rol 'cliente_qr'