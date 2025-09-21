<?php
namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\User;
use App\Models\Payment;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\UserCredentialsMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SolicitudController extends Controller
{
    // Listar solicitudes (para el administrador)
    public function index(Request $request)
    {
        // Obtener los filtros de la solicitud
        $search = $request->input('search');

        $query = Solicitud::query();

        // Filtrar por búsqueda (nombre de mascota, nombre del dueño, o apellido del dueño)
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('nombre', 'like', '%' . $search . '%')
                      ->orWhere('nombre_owner', 'like', '%' . $search . '%')
                      ->orWhere('apellido_owner', 'like', '%' . $search . '%')
                      ->orWhere('solicitudes.id_pago_yappy', 'like', '%' . $search . '%');
            });
        }

        // Paginación de las solicitudes filtradas con eager loading
        $solicitudes = $query->select('solicitudes.*')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        // Retornar la vista 'dashboard.solicitudes' con las solicitudes filtradas
        return view('dashboard.solicitudes', compact('solicitudes'));
    }

    public function reject($id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $solicitud->delete();

        return redirect()->route('dashboard.solicitudes')
            ->with('success', 'Solicitud rechazada exitosamente.');
    }
    // Aceptar la solicitud: crea el usuario y la mascota, luego elimina la solicitud
    public function accept($id)
    {
        $solicitud = Solicitud::findOrFail($id);
    
        // Generar contraseña aleatoria
        $password = Str::random(10);
    
        // Verificar si el correo ya está registrado
        if (User::where('email', $solicitud->correo_owner)->exists()) {
            return redirect()->route('dashboard.solicitudes')
                ->with('error', 'El correo electrónico ya está registrado.');
        }
    
        // Crear el usuario con los datos del dueño
        $user = User::create([
            'name' => $solicitud->nombre_owner . ' ' . $solicitud->apellido_owner,
            'email' => $solicitud->correo_owner,
            'password' => Hash::make($password),
        ]);
    
        // Verificar que el usuario se creó correctamente
        if (!$user) {
            return redirect()->route('dashboard.solicitudes')
                ->with('error', 'No se pudo crear el usuario, verifique los datos.');
        }
    
        // Asignar rol al usuario
        $user->assignRole('cliente_qr');
    
        // Verificar que el usuario se creó correctamente antes de crear la mascota
        if (!$user->id) {
            return redirect()->route('dashboard.solicitudes')
                ->with('error', 'El usuario no se creó correctamente.');
        }
    
        // Crear la mascota asociada al usuario
        $pet = Pet::create([
            'nombre' => $solicitud->nombre,
            'especie' => $solicitud->especie,
            'raza' => $solicitud->raza,
            'edad_anios' => $solicitud->edad_anios,
            'edad_meses' => $solicitud->edad_meses,
            'sexo' => $solicitud->sexo,
            'nombre_owner' => $solicitud->nombre_owner,
            'apellido_owner' => $solicitud->apellido_owner,
            'telefono_owner' => $solicitud->telefono_owner,
            'correo_owner' => $solicitud->correo_owner,
            'user_id' => $user->id,
        ]);
    
        // Si la creación de la mascota falla
        if (!$pet) {
            return redirect()->route('dashboard.solicitudes')
                ->with('error', 'No se pudo crear la mascota.');
        }
    
        // Enviar correo con credenciales usando cola
        Mail::to($solicitud->correo_owner)
            ->queue(new UserCredentialsMail($solicitud->correo_owner, $password));
    
        // Eliminar la solicitud
        $solicitud->delete();
    
        return redirect()->route('dashboard.solicitudes')
            ->with('success', 'Solicitud aceptada. Usuario y mascota creados.');
    }
    

    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raza' => 'required|string|max:255',
            'otra_raza' => 'nullable|string|max:255',
            'edad_anios' => 'nullable|integer|min:0|max:30',
            'edad_meses' => 'nullable|integer|min:0|max:11',
            'sexo' => 'required|string|max:10',
            'nombre_owner' => 'required|string|max:255',
            'apellido_owner' => 'required|string|max:255',
            'telefono_owner' => 'required|string|max:15',
            'correo_owner' => 'required|email',
            'id_pago_yappy' => 'required|string|max:255',
        ], [
            'nombre.required' => 'El nombre de la mascota es obligatorio.',
            'especie.required' => 'La especie es obligatoria.',
            'raza.required' => 'La raza es obligatoria.',
            'edad_anios.max' => 'La edad en años no puede ser mayor a 30.',
            'edad_meses.max' => 'La edad en meses no puede ser mayor a 11.',
            'sexo.required' => 'El sexo es obligatorio.',
            'nombre_owner.required' => 'El nombre del dueño es obligatorio.',
            'apellido_owner.required' => 'El apellido del dueño es obligatorio.',
            'telefono_owner.required' => 'El teléfono es obligatorio.',
            'correo_owner.required' => 'El correo electrónico es obligatorio.',
            'correo_owner.email' => 'El correo electrónico no es válido.',
            'id_pago_yappy.required' => 'El ID de pago Yappy es obligatorio.',
        ]);

        // Validar que al menos uno de los dos campos de edad esté presente
        if (empty($validated['edad_anios']) && empty($validated['edad_meses'])) {
            return back()->withErrors(['edad_anios' => 'Debe ingresar al menos la edad en años o meses.', 'edad_meses' => 'Debe ingresar al menos la edad en años o meses.'])->withInput();
        }

        // Si la raza es "Otro", usar el campo otra_raza
        $razaFinal = $validated['raza'] === 'Otro' ? ($validated['otra_raza'] ?? '') : $validated['raza'];
        if ($validated['raza'] === 'Otro' && empty($validated['otra_raza'])) {
            return back()->withErrors(['otra_raza' => 'Debe especificar la raza si selecciona "Otro".'])->withInput();
        }

        // Crear la solicitud
        $solicitud = Solicitud::create([
            'nombre' => $validated['nombre'],
            'especie' => $validated['especie'],
            'raza' => $razaFinal,
            'edad_anios' => $validated['edad_anios'] ?? null,
            'edad_meses' => $validated['edad_meses'] ?? null,
            'sexo' => $validated['sexo'],
            'nombre_owner' => $validated['nombre_owner'],
            'apellido_owner' => $validated['apellido_owner'],
            'telefono_owner' => $validated['telefono_owner'],
            'correo_owner' => $validated['correo_owner'],
            'id_pago_yappy' => $validated['id_pago_yappy'],
        ]);

        // Redirigir al usuario con un mensaje de éxito
        return redirect()->route('comprarealizada')->with('success', 'Solicitud recibida correctamente. Un administrador verificará el pago.');
    }

    // Método para mostrar los detalles de una solicitud
    public function show($id)
    {
        $solicitud = Solicitud::findOrFail($id);
        return view('dashboard.show-solicitud', compact('solicitud'));
    }
}
