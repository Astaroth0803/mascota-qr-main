<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VaccinationRecord;
use App\Services\DataEncryptionService;
use Illuminate\Support\Facades\Log;

class Pet extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'slug',
        'especie',
        'raza',
        'otra_raza',
        'edad_anios',
        'edad_meses',
        'peso',
        'sexo',
        'nombre_owner',
        'apellido_owner',
        'telefono_owner',
        'correo_owner',
        'id_pago_yappy',
        'vaccine_file',
        'profile_image',
        'qr_code',
        'user_id',
    ];

    // Campos que deben ser encriptados
    protected $encrypted = [
        'telefono_owner',
        'id_pago_yappy'
    ];

    // Campos que deben ser desencriptados automáticamente
    protected $casts = [
        'edad_anios' => 'integer',
        'edad_meses' => 'integer',
        'peso' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    /**
     * Genera un slug único basado en el nombre de la mascota
     */
    public function generateSlug()
    {
        $slug = \Str::slug($this->nombre);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Obtiene la ruta de la mascota usando el slug
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Relación con el modelo Payment (uno a uno)
    public function payment()
    {
        return $this->hasOne(Payment::class);  // Esto asocia un pago a cada mascota
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con veterinarios asignados
     */
    public function veterinarios()
    {
        return $this->belongsToMany(User::class, 'mascota_veterinario', 'mascota_id', 'veterinario_id')
                    ->withPivot(['fecha_asignacion', 'activo', 'tipo_asignacion', 'notas'])
                    ->withTimestamps();
    }

    /**
     * Veterinario principal asignado
     */
    public function veterinarioPrincipal()
    {
        return $this->veterinarios()
                    ->wherePivot('tipo_asignacion', 'principal')
                    ->wherePivot('activo', true);
    }

    /**
     * Veterinarios activos asignados
     */
    public function veterinariosActivos()
    {
        return $this->veterinarios()->wherePivot('activo', true);
    }

    /**
     * Asignaciones de veterinarios (tabla pivot)
     */
    public function asignacionesVeterinarios()
    {
        return $this->hasMany(MascotaVeterinario::class, 'mascota_id');
    }

    /**
     * Get the vaccination records for the pet.
     */
    public function vaccinationRecords()
    {
        return $this->hasMany(VaccinationRecord::class)->orderBy('created_at', 'desc');
    }
    
    /**
     * Relación con las citas médicas
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class)->orderBy('scheduled_datetime', 'desc');
    }

    /**
     * Sobrescribe el método load para forzar un reseteo de relaciones cuando
     * se cargan los registros de vacunación
     */
    public function load($relations)
    {
        // Si estamos cargando vaccinationRecords, forzar una limpieza de la caché de relaciones
        if (is_string($relations) && $relations === 'vaccinationRecords' || 
            (is_array($relations) && in_array('vaccinationRecords', $relations))) {
            $this->unsetRelation('vaccinationRecords');
        }

        return parent::load($relations);
    }

    /**
     * Boot del modelo para manejar encriptación automática
     */
    protected static function boot()
    {
        parent::boot();

        // Generar slug automáticamente
        static::creating(function ($pet) {
            if (empty($pet->slug)) {
                $pet->slug = $pet->generateSlug();
            }
        });

        static::updating(function ($pet) {
            if ($pet->isDirty('nombre') && empty($pet->slug)) {
                $pet->slug = $pet->generateSlug();
            }
        });

        // Encriptar datos sensibles antes de guardar
        static::saving(function ($pet) {
            $encryptionService = new DataEncryptionService();
            
            foreach ($pet->encrypted as $field) {
                if (isset($pet->attributes[$field]) && !empty($pet->attributes[$field])) {
                    try {
                        $pet->attributes[$field] = $encryptionService->encrypt($pet->attributes[$field]);
                    } catch (\Exception $e) {
                        Log::error("Error al encriptar campo {$field} en mascota {$pet->id}", [
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
        });

        // Desencriptar datos sensibles después de cargar
        static::retrieved(function ($pet) {
            $encryptionService = new DataEncryptionService();
            
            foreach ($pet->encrypted as $field) {
                if (isset($pet->attributes[$field]) && !empty($pet->attributes[$field])) {
                    try {
                        $pet->attributes[$field] = $encryptionService->decrypt($pet->attributes[$field]);
                    } catch (\Exception $e) {
                        Log::error("Error al desencriptar campo {$field} en mascota {$pet->id}", [
                            'error' => $e->getMessage()
                        ]);
                        // Mantener valor encriptado si falla la desencriptación
                    }
                }
            }
        });
    }

    /**
     * Obtiene un atributo desencriptado
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        
        if (in_array($key, $this->encrypted) && !empty($value)) {
            try {
                $encryptionService = new DataEncryptionService();
                return $encryptionService->decrypt($value);
            } catch (\Exception $e) {
                Log::error("Error al desencriptar atributo {$key}", [
                    'pet_id' => $this->id,
                    'error' => $e->getMessage()
                ]);
                return $value; // Retornar valor original si falla
            }
        }
        
        return $value;
    }
}
