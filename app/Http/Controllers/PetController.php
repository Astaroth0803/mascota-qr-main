<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Pet;
use App\Models\Payment;
use App\Services\PaymentService;
use App\PaymentMethods\YappyPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Storage;
use App\Models\VaccinationRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Services\QRCodeService;

class PetController extends Controller
{
    protected $paymentService;
    protected $qrCodeService;

    public function __construct(PaymentService $paymentService, QRCodeService $qrCodeService)
    {
        $this->paymentService = $paymentService;
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Muestra el formulario para crear una nueva mascota
     */
    public function create()
    {
        return view('mascotas.create');
    }

    // Método para crear la mascota, mantenemos el mismo
    public function createPet(Request $request, $userId)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raza' => 'required|string|max:255',
            'edad' => 'required|string|max:255',
            'sexo' => 'required|string|max:255',
            'id_pago_yappy' => 'required|string|max:255',
        ]);

        // Crear la mascota y asociarla al usuario
        $pet = Pet::create([
            'nombre' => $validated['nombre'],
            'especie' => $validated['especie'],
            'raza' => $validated['raza'],
            'edad' => $validated['edad'],
            'sexo' => $validated['sexo'],
            'nombre_owner' => $request->input('nombre_owner'),
            'apellido_owner' => $request->input('apellido_owner'),
            'telefono_owner' => $request->input('telefono_owner'),
            'correo_owner' => $request->input('correo_owner'),
            'user_id' => $userId,
        ]);

        // Registrar la creación de la mascota
        Log::info('Mascota creada', [
            'id' => $pet->id,
            'nombre' => $pet->nombre,
            'dueño' => $pet->nombre_owner . ' ' . $pet->apellido_owner,
        ]);

        // Procesar el pago con Yappy
        $this->paymentService->setPaymentMethod(new YappyPayment());
        $paymentResult = $this->paymentService->processPayment(100.00, [ // Monto fijo para el ejemplo
            'payment_id' => $validated['id_pago_yappy'],
        ]);

        // Registrar el resultado del pago
        Log::info('Pago procesado', $paymentResult);

        // Crear el registro de pago
        $payment = Payment::create([
            'pet_id' => $pet->id,
            'payment_method' => 'yappy',
            'payment_id' => $validated['id_pago_yappy'],
            'status' => $paymentResult['success'] ? Payment::STATUS_VERIFIED : Payment::STATUS_REJECTED,
        ]);

        // Registrar la creación del pago
        Log::info('Pago creado', [
            'id' => $payment->id,
            'mascota_id' => $payment->pet_id,
            'payment_id' => $payment->payment_id,
            'status' => $payment->status,
        ]);

        return $pet;
    }

    // Método para el dashboard del cliente
    public function dashboardCliente()
    {
        // Verificar si el usuario tiene el permiso 'ver_mascotas'
        if (!Auth::user()->can('ver_mascotas')) {
            return redirect()->route('dashboard.cliente')->with('error', 'No tienes permiso para ver tus mascotas.');
        }

        $user = Auth::user();
        $userId = $user->id;
        $userEmail = $user->email;

        $pets = Pet::with(['payment', 'vaccinationRecords'])
            ->where(function ($query) use ($userId, $userEmail) {
                $query->where('user_id', $userId)
                      ->orWhere('correo_owner', $userEmail);
            })
            ->get();

        $statistics = [
            'total_pets' => $pets->count(),
            'pending_vaccinations' => VaccinationRecord::whereHas('pet', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->count(),
            'upcoming_appointments' => [],
            'recent_activities' => []
        ];

        return view('dashboard.cliente', compact('pets', 'statistics'));
    }

    // Método para el dashboard del administrador
    public function adminDashboard()
    {
        // Solo los administradores pueden acceder aquí
        $pets = Pet::with('payment')->get(); // Obtener todas las mascotas

        // Obtener el total de usuarios
        $userCount = User::count();

        // Obtener el total de solicitudes pendientes
        $solicitudCount = Solicitud::count();

        // Datos de ejemplo para los gráficos (deberás reemplazarlos con datos reales)
        $userDataForChart = [15, 18, 16, 19, 17, 20, 18]; // Ejemplo de actividad de usuarios por día/semana/mes
        $petDistributionData = ['Perros' => 10, 'Gatos' => 5, 'Otros' => 3]; // Ejemplo de distribución por especie

        return view('dashboard.administrador', compact('pets', 'userCount', 'solicitudCount', 'userDataForChart', 'petDistributionData'));
    }

    // Método para mostrar las solicitudes pendientes (solo para administradores)
    public function showPendingRequests(Request $request)
    {
        // Obtener las solicitudes con filtros
        $query = Pet::with('payment');

        // Filtrar por nombre de mascota o dueño
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('nombre_owner', 'like', "%{$search}%")
                  ->orWhere('apellido_owner', 'like', "%{$search}%");
            });
        }

        // Filtrar por estado del pago
        if ($request->has('status')) {
            $status = $request->input('status');
            $query->whereHas('payment', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        // Obtener las solicitudes pendientes
        $pendingCount = Pet::whereHas('payment', function ($query) {
            $query->where('status', 'pending');
        })->count();

        // Paginar los resultados
        $pets = $query->paginate(10);

        return view('dashboard.solicitudes', compact('pets', 'pendingCount'));
    }

    /**
     * Muestra los detalles de una mascota específica (con caché ajustada).
     */
    public function show($id)
    {
        // Primero, obtener la mascota para tener su ID y updated_at
        $pet = Pet::with('payment')->findOrFail($id);

         // Opcional: Verificar que el usuario tenga permiso para ver esta mascota ANTES de cachear
        if (!Auth::user()->can('ver_mascotas') ||
            (Auth::user()->id !== $pet->user_id && Auth::user()->email !== $pet->correo_owner)) {
            return redirect()->route('dashboard.cliente')
                ->with('error', 'No tienes permiso para ver esta mascota.');
        }

        // Obtener el timestamp de updated_at de la mascota.
        // Acceder al atributo y convertir a Carbon si no es nulo, luego obtener el timestamp.
        // Aunque Eloquent suele castear updated_at a Carbon, esta es una capa adicional de seguridad.
        $petUpdatedTimestamp = null;
        if ($pet->updated_at) {
            $petUpdatedTimestamp = Carbon::parse($pet->updated_at)->timestamp;
        }

        // Generar una clave de caché basada en el ID de la mascota y su timestamp de última actualización
        $cacheKey = 'pet_details:' . $pet->id . ($petUpdatedTimestamp ? ':' . $petUpdatedTimestamp : ':no_timestamp');

        // Usar Cache::remember para obtener o almacenar los datos de la mascota
        $cachedPet = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($pet) {
             // Recargar la mascota con todas las relaciones necesarias si no está en caché
             // Usamos fresh() para asegurar que obtenemos la última versión de la DB si la caché expiró
             $pet->load(['payment', 'vaccinationRecords']); // Añade aquí todas las relaciones que show necesita
             return $pet;
        });

        // Usar $cachedPet en la vista
        return view('mascotas.show', ['pet' => $cachedPet]);
    }

    /**
     * Muestra el formulario para editar una mascota
     */
    public function edit($id)
    {
        $pet = Pet::findOrFail($id);

        // Verificar que el usuario tenga permiso para editar esta mascota
        if (!Auth::user()->can('ver_mascotas') ||
            (Auth::user()->id !== $pet->user_id && Auth::user()->email !== $pet->correo_owner)) {
            return redirect()->route('dashboard.cliente')
                ->with('error', 'No tienes permiso para editar esta mascota.');
        }

        return view('mascotas.edit', compact('pet'));
    }

    /**
     * Actualiza los datos de una mascota
     */
    public function update(Request $request, Pet $pet)
    {
        // Verificar que el usuario tenga permiso para editar esta mascota
        if (!Auth::user()->can('ver_mascotas') ||
            (Auth::user()->id !== $pet->user_id && Auth::user()->email !== $pet->correo_owner)) {
            return redirect()->route('dashboard.cliente')
                ->with('error', 'No tienes permiso para editar esta mascota.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raza' => 'required|string|max:255',
            'edad_anios' => 'required|integer|min:0|max:30',
            'edad_meses' => 'required|integer|min:0|max:11',
            'sexo' => 'required|string|in:Macho,Hembra',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vaccine_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240'
        ]);

        // Manejar la imagen de perfil
        if ($request->hasFile('profile_image')) {
            // Eliminar la imagen anterior si existe
            if ($pet->profile_image) {
                Storage::delete($pet->profile_image);
            }
            // Guardar la nueva imagen
            $path = $request->file('profile_image')->store('pets/profile-images', 'public');
            $pet->profile_image = $path;
        }

        // Manejar el archivo de vacunas
        if ($request->hasFile('vaccine_file')) {
            // Eliminar el archivo anterior si existe
            if ($pet->vaccine_file) {
                Storage::delete($pet->vaccine_file);
            }
            // Guardar el nuevo archivo
            $path = $request->file('vaccine_file')->store('pets/vaccine-files', 'public');
            $pet->vaccine_file = $path;
        }

        // Actualizar los demás campos
        $pet->update([
            'nombre' => $validated['nombre'],
            'especie' => $validated['especie'],
            'raza' => $validated['raza'],
            'edad_anios' => $validated['edad_anios'],
            'edad_meses' => $validated['edad_meses'],
            'sexo' => $validated['sexo']
        ]);

        return redirect()->route('dashboard.cliente.mascotas.show', $pet)
            ->with('success', 'Mascota actualizada correctamente.');
    }

    public function updateImage(Request $request, Pet $pet)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('profile_image')) {
            // Eliminar la imagen anterior si existe
            if ($pet->profile_image) {
                Storage::delete($pet->profile_image);
            }

            // Guardar la nueva imagen
            $path = $request->file('profile_image')->store('pets/profile-images', 'public');
            $pet->profile_image = $path;
            $pet->save();
        }

        return redirect()->back()->with('success', 'Imagen de perfil actualizada correctamente');
    }

    /**
     * Almacena una nueva mascota en la base de datos
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raza' => 'required|string|max:255',
            'edad' => 'required|string|max:255',
            'sexo' => 'required|string|in:Macho,Hembra',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        // Crear la mascota
        $pet = new Pet();
        $pet->nombre = $validated['nombre'];
        $pet->especie = $validated['especie'];
        $pet->raza = $validated['raza'];
        $pet->edad = $validated['edad'];
        $pet->sexo = $validated['sexo'];
        $pet->user_id = $user->id;
        $pet->nombre_owner = $user->name;
        $pet->apellido_owner = $user->lastname ?? '';
        $pet->correo_owner = $user->email;
        $pet->telefono_owner = $user->phone ?? '';

        // Manejar la imagen de perfil si se proporciona
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('pets/profile-images', 'public');
            $pet->profile_image = $path;
        }

        $pet->save();

        return redirect()->route('dashboard.cliente')
            ->with('success', 'Mascota registrada correctamente.');
    }

    /**
     * Muestra el historial de vacunación de una mascota específica.
     */
    public function showVaccinationHistory(Pet $pet)
    {
        // Opcional: Verificar que el usuario tenga permiso para ver esta mascota
        if (!Auth::user()->can('ver_mascotas') ||
            (Auth::user()->id !== $pet->user_id && Auth::user()->email !== $pet->correo_owner)) {
            return redirect()->route('dashboard.cliente')
                ->with('error', 'No tienes permiso para ver el historial de esta mascota.');
        }

        // Limpiar la caché para asegurar que vemos los datos más recientes
        $cacheKey = 'pet_records:' . $pet->id;
        Cache::forget($cacheKey);

        // Recargar la mascota con los registros de vacunación relacionados frescos
        $pet = $pet->fresh(['vaccinationRecords' => function($query) {
            $query->orderBy('created_at', 'desc'); // Más recientes primero
        }]);

        // Para depuración - comenta en producción
        \Illuminate\Support\Facades\Log::info('Registros cargados para mascota ID ' . $pet->id, [
            'total_records' => $pet->vaccinationRecords->count(),
            'records_data' => $pet->vaccinationRecords->toArray()
        ]);

        // Definir los tipos de registro para el formulario
        $recordTypes = [
            'vacuna' => 'Vacunación',
            'checkeo' => 'Cita de control',
            'peluqueria' => 'Peluquería/Estética',
            'operacion' => 'Operación/Cirugía'
        ];

        return view('mascotas.vaccination-history', compact('pet', 'recordTypes'));
    }

       /**
     * Guarda un nuevo registro de vacunación para una mascota.
     */
    /**
     * Método de depuración para verificar registros directamente en la base de datos
     */
    public function debugRecords(Pet $pet)
    {
        // Verificar permisos
        if (!Auth::user()->can('ver_mascotas') ||
            (Auth::user()->id !== $pet->user_id && Auth::user()->email !== $pet->correo_owner)) {
            return redirect()->route('dashboard.cliente')
                ->with('error', 'No tienes permiso para ver esta información.');
        }

        // Obtener registros directamente con SQL query builder
        $sqlRecords = \DB::table('vaccination_records')
            ->where('pet_id', $pet->id)
            ->get();

        // Registrar información para depuración
        Log::info('Depuración de registros médicos', [
            'pet_id' => $pet->id,
            'sql_records_count' => $sqlRecords->count(),
            'eloquent_records_count' => $pet->vaccinationRecords()->count()
        ]);

        return view('mascotas.debug-records', compact('pet', 'sqlRecords'));
    }

    /**
     * Método para refrescar la caché y relaciones
     */
    public function debugRefreshCache(Pet $pet)
    {
        // Limpiar todas las cachés relacionadas
        Cache::flush();

        // Forzar una recarga del modelo y sus relaciones
        $pet = Pet::with('vaccinationRecords')->findOrFail($pet->id);

        // Registrar en el log
        Log::info('Caché limpiada para mascota', ['pet_id' => $pet->id]);

        return redirect()->route('dashboard.cliente.mascotas.debug-records', $pet)
            ->with('success', 'Caché limpiada correctamente');
    }

    public function storeVaccinationRecord(Request $request, Pet $pet)
    {
        // Opcional: Verificar permisos
        if (!Auth::user()->can('ver_mascotas') ||
            (Auth::user()->id !== $pet->user_id && Auth::user()->email !== $pet->correo_owner)) {
            return redirect()->route('dashboard.cliente')
                ->with('error', 'No tienes permiso para agregar registros a esta mascota.');
        }

        // Validación común para todos los tipos de registros
        $commonRules = [
            'record_type' => 'required|string|in:vacuna,checkeo,peluqueria,operacion',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'vet_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'observations' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ];

        // Reglas específicas según el tipo de registro
        $specificRules = [];
        switch ($request->input('record_type')) {
            case 'vacuna':
                $specificRules = [
                    'vaccine_name' => 'required|string|max:255',
                    'next_date' => 'nullable|date|after:date',
                ];
                break;

            case 'checkeo':
                $specificRules = [
                    'diagnosis' => 'nullable|string',
                    'treatment' => 'nullable|string',
                    'next_date' => 'nullable|date|after:date',
                ];
                break;

            case 'operacion':
                $specificRules = [
                    'diagnosis' => 'required|string',
                    'treatment' => 'required|string',
                    'next_date' => 'nullable|date|after:date',
                ];
                break;

            case 'peluqueria':
                $specificRules = [
                    'observations' => 'required|string',
                    'next_date' => 'nullable|date|after:date',
                ];
                break;
        }

        // Combinar y validar
        $validated = $request->validate(array_merge($commonRules, $specificRules));

        // Crear el registro con los valores de manera explícita para evitar errores de columnas
        $record = new VaccinationRecord();
        $record->pet_id = $pet->id;
        $record->record_type = $validated['record_type'];

        // Manejar la fecha y hora
        $record->date = Carbon::parse($validated['date']);
        $record->time = $validated['time'];

        // Registrar la operación para depuración
        Log::info('Creando registro médico', [
            'pet_id' => $pet->id,
            'record_type' => $validated['record_type'],
            'date' => $validated['date'],
            'time' => $validated['time']
        ]);

        // Campos opcionales
        if (isset($validated['vet_name'])) {
            $record->vet_name = $validated['vet_name'];
        }
        if (isset($validated['location'])) {
            $record->location = $validated['location'];
        }
        if (isset($validated['observations'])) {
            $record->observations = $validated['observations'];
        }
        if (isset($validated['next_date']) && !empty($validated['next_date'])) {
            $record->next_date = Carbon::parse($validated['next_date']);
            Log::info('Estableciendo fecha próxima', ['next_date' => $validated['next_date']]);
        }

        // Campos específicos por tipo
        if ($validated['record_type'] == 'vacuna' && isset($validated['vaccine_name'])) {
            $record->vaccine_name = $validated['vaccine_name'];
        }
        if (in_array($validated['record_type'], ['checkeo', 'operacion']) && isset($validated['diagnosis'])) {
            $record->diagnosis = $validated['diagnosis'];
        }
        if (in_array($validated['record_type'], ['checkeo', 'operacion']) && isset($validated['treatment'])) {
            $record->treatment = $validated['treatment'];
        }

        // Guardar el documento si se proporciona
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('pets/records', 'public');
            $record->document_path = $path;
        }

        try {
            // Insertar directamente en la base de datos para evitar problemas de Eloquent
            $data = [
                'pet_id' => $pet->id,
                'record_type' => $validated['record_type'],
                'date' => $validated['date'],
                'time' => $validated['time'],
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Agregar campos opcionales si existen
            if (isset($validated['vet_name'])) $data['vet_name'] = $validated['vet_name'];
            if (isset($validated['location'])) $data['location'] = $validated['location'];
            if (isset($validated['observations'])) $data['observations'] = $validated['observations'];
            if (isset($validated['next_date'])) $data['next_date'] = $validated['next_date'];

            // Campos específicos por tipo
            if ($validated['record_type'] == 'vacuna' && isset($validated['vaccine_name'])) {
                $data['vaccine_name'] = $validated['vaccine_name'];
            }
            if (in_array($validated['record_type'], ['checkeo', 'operacion'])) {
                if (isset($validated['diagnosis'])) $data['diagnosis'] = $validated['diagnosis'];
                if (isset($validated['treatment'])) $data['treatment'] = $validated['treatment'];
            }

            // Guardar documento si existe
            if ($request->hasFile('document')) {
                $path = $request->file('document')->store('pets/records', 'public');
                $data['document_path'] = $path;
            }

            // Insertar directamente en la base de datos
            $recordId = \DB::table('vaccination_records')->insertGetId($data);

            Log::info('Registro médico insertado directamente', [
                'record_id' => $recordId,
                'data' => $data
            ]);

            // Verificar que el registro se haya guardado correctamente
            $savedRecord = \DB::table('vaccination_records')->where('id', $recordId)->first();
            if ($savedRecord) {
                Log::info('Verificación: Registro encontrado en base de datos', [
                    'record_id' => $recordId,
                    'record_data' => (array)$savedRecord
                ]);
            } else {
                Log::error('Verificación: Registro NO encontrado en base de datos a pesar de ID exitoso', [
                    'record_id' => $recordId
                ]);
            }

            // Limpiar cualquier caché relacionada con esta mascota
            Cache::flush(); // Limpiar toda la caché para estar seguros

            // Actualizar el timestamp de la mascota para invalidar cachés basadas en él
            $pet->touch();

        } catch (\Exception $e) {
            // Registrar el error con detalles completos
            Log::error('Error al guardar registro médico', [
                'pet_id' => $pet->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'sql_state' => $e->getCode(),
                'data' => $validated
            ]);

            return redirect()->back()->with('error', 'Error al guardar el registro: ' . $e->getMessage());
        }

        // Redirigir a la página de depuración para verificar inmediatamente
        return redirect()->route('dashboard.cliente.mascotas.debug-records', $pet)
            ->with('success', 'Registro médico agregado. Verifique los resultados en esta página de diagnóstico.');
    }

    /**
     * Genera un código QR para una mascota específica
     */
    public function generateQRCode(Pet $pet)
    {
        // Verificar permisos
        if (!Auth::user()->can('ver_mascotas') &&
            (Auth::user()->id !== $pet->user_id && Auth::user()->email !== $pet->correo_owner)) {
            return redirect()->route('dashboard.cliente')
                ->with('error', 'No tienes permiso para generar el código QR de esta mascota.');
        }

        $qrCode = $this->qrCodeService->assignQRCode($pet);
        $publicUrl = $this->qrCodeService->generatePublicUrl($pet);

        return response()->json([
            'success' => true,
            'pet_name' => $pet->nombre, // 👈 agrega esto
            'qr_code' => $qrCode,
            'public_url' => $publicUrl,
            'qr_image_url' => $this->qrCodeService->generateQRImageUrl($publicUrl, 300),
            'message' => 'Código QR generado correctamente'
        ]);
    }

    /**
     * Muestra la vista para generar códigos QR (para administradores)
     */
    public function qrGenerator()
    {
        // Solo administradores pueden acceder
        if (!Auth::user()->hasAnyRole(['administrador', 'super_admin'])) {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a esta función.');
        }

        $pets = Pet::with('user')->get();
        $solicitudCount = Solicitud::count();
        
        return view('admin.qr-generator', compact('pets', 'solicitudCount'));
    }

    /**
     * Genera códigos QR para múltiples mascotas
     */
    public function generateMultipleQRCodes(Request $request)
    {
        // Solo administradores pueden acceder
        if (!Auth::user()->hasAnyRole(['administrador', 'super_admin'])) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción.'], 403);
        }

        $request->validate([
            'pet_ids' => 'required|array',
            'pet_ids.*' => 'exists:pets,id'
        ]);

        $results = $this->qrCodeService->generateQRForMultiplePets($request->pet_ids);

        return response()->json([
            'success' => true,
            'results' => $results,
            'message' => 'Códigos QR generados correctamente'
        ]);
    }

    /**
     * Muestra las mascotas de un usuario específico (para administradores)
     */
    public function showUserPets($userId)
    {
        // Solo administradores pueden acceder
        if (!Auth::user()->hasAnyRole(['administrador', 'super_admin'])) {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a esta función.');
        }

        $user = User::findOrFail($userId);
        $pets = Pet::where('user_id', $userId)
            ->with(['payment', 'vaccinationRecords'])
            ->get();

        // Obtener el total de solicitudes pendientes para el sidebar
        $solicitudCount = Solicitud::count();

        return view('admin.user-pets', compact('user', 'pets', 'solicitudCount'));
    }
}




