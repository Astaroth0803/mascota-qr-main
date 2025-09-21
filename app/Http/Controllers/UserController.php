<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\User;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCredentialsMail;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
 // Actualizar la contraseña de un usuario
    public function updatePassword(Request $request, $id)
{
    $request->validate([
        'password' => 'required|string|min:8|confirmed',
    ], [
        'password.required'  => 'El campo contraseña es obligatorio.',
        'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
    ]);

    $usuario = User::findOrFail($id);
    $usuario->password = Hash::make($request->password);
    $usuario->save();

    return redirect()->route('dashboard.usuarios')->with('success', '¡Contraseña actualizada correctamente!');
}
    public function editPassword($id)
    {
        $usuario = User::findOrFail($id);
        return view('dashboard.edit-password', compact('usuario'));
    }


    // Listar usuarios (para el administrador)
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');

        $query = User::query()
            ->with(['roles', 'permissions']); // Eager loading de roles y permisos

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            });
        }

        $usuarios = $query->orderBy('created_at', 'desc')
                        ->paginate(10);

        return view('dashboard.usuarios', compact('usuarios'));
    }
    
    

    // Crear usuario y mascota desde una solicitud
    public function createUserFromSolicitud($id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $password = Str::random(10);

        $user = User::create([
            'name' => "$solicitud->nombre_owner $solicitud->apellido_owner",
            'email' => $solicitud->correo_owner,
            'password' => Hash::make($password),
        ]);

        $user->assignRole('cliente_qr');

        Pet::create([
            'nombre' => $solicitud->nombre,
            'especie' => $solicitud->especie,
            'raza' => $solicitud->raza,
            'edad' => $solicitud->edad,
            'sexo' => $solicitud->sexo,
            'nombre_owner' => $solicitud->nombre_owner,
            'apellido_owner' => $solicitud->apellido_owner,
            'telefono_owner' => $solicitud->telefono_owner,
            'correo_owner' => $solicitud->correo_owner,
            'user_id' => $user->id,
        ]);

        Mail::to($solicitud->correo_owner)->send(new UserCredentialsMail($solicitud->correo_owner, $password));
        $solicitud->delete();

        return redirect()->route('dashboard.solicitudes')->with('success', 'Solicitud aceptada. Usuario y mascota creados.');
    }

    // Método para mostrar el formulario de creación de usuario
    public function create()
    {
        $roles = Role::all(); // Obtener todos los roles
        return view('dashboard.create-user', compact('roles')); // Pasar los roles a la vista
    }

    // Crear un nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'roles.array' => 'Los roles deben ser una lista válida.',
            'roles.*.exists' => 'Uno de los roles seleccionados no es válido.',
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Asignar roles si se proporcionaron
        if ($request->has('roles') && !empty($request->roles)) {
            $user->assignRole($request->roles);
        }

        // Enviar credenciales por email
        try {
            Mail::to($user->email)->send(new UserCredentialsMail($user, $request->password));
        } catch (\Exception $e) {
            // Log del error pero no fallar la creación del usuario
            \Log::error('Error enviando credenciales por email: ' . $e->getMessage());
        }

        return redirect()->route('dashboard.usuarios')->with('success', 'Usuario creado exitosamente. Las credenciales han sido enviadas por email.');
    }

    // Ver roles de un usuario
    public function showRoles($id)
    {
        $user = User::findOrFail($id);
        return view('dashboard.user_roles', ['user' => $user, 'roles' => $user->getRoleNames()]);
    }

    // Actualizar roles de un usuario
    public function updateRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);
        
        $user->syncRoles($request->roles);
        return redirect()->route('dashboard.usuarios')->with('success', 'Roles actualizados correctamente.');
    }
    public function destroy($id)
    {
        // Busca el usuario por su ID
        $user = User::findOrFail($id);

        // Elimina el usuario
        $user->delete();

        // Redirige a una página de tu elección, con un mensaje de éxito
        return redirect()->route('dashboard.usuarios')->with('success', 'Usuario eliminado correctamente.');
    }
}
