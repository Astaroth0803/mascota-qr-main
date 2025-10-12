<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    // Constantes para tipos de veterinarios
    const TIPO_AUXILIAR = 'auxiliar';
    const TIPO_TECNICO = 'tecnico';
    const TIPO_LICENCIADO = 'licenciado';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guard_name = 'web';
    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo_veterinario',
        'slug',
        'ubicacion',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación con mascotas propias (como cliente)
     */
    public function pets()
    {
        return $this->hasMany(Pet::class, 'user_id');
    }

    /**
     * Genera un slug único basado en el nombre del usuario
     */
    public function generateSlug()
    {
        $slug = \Str::slug($this->name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Obtiene la ruta del usuario usando el slug
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Relación con mascotas asignadas como veterinario
     */
    public function mascotasAsignadas()
    {
        return $this->belongsToMany(Pet::class, 'mascota_veterinario', 'veterinario_id', 'mascota_id')
                    ->withPivot(['fecha_asignacion', 'activo', 'tipo_asignacion', 'notas'])
                    ->withTimestamps();
    }

    /**
     * Mascotas como veterinario principal
     */
    public function mascotasPrincipales()
    {
        return $this->mascotasAsignadas()
                    ->wherePivot('tipo_asignacion', 'principal')
                    ->wherePivot('activo', true);
    }

    /**
     * Mascotas activas asignadas
     */
    public function mascotasActivas()
    {
        return $this->mascotasAsignadas()->wherePivot('activo', true);
    }

    /**
     * Asignaciones como veterinario
     */
    public function asignacionesVeterinario()
    {
        return $this->hasMany(MascotaVeterinario::class, 'veterinario_id');
    }
    
    /**
     * Citas como veterinario
     */
    public function veterinarianAppointments()
    {
        return $this->hasMany(Appointment::class, 'veterinarian_id')->orderBy('scheduled_datetime', 'desc');
    }
    
    /**
     * Citas como cliente
     */
    public function clientAppointments()
    {
        return $this->hasMany(Appointment::class, 'client_id')->orderBy('scheduled_datetime', 'desc');
    }

    /**
     * Verificar si es veterinario
     */
    public function isVeterinario()
    {
        return $this->hasRole('veterinario');
    }

    /**
     * Verificar si es administrador
     */
    public function isAdmin()
    {
        return $this->hasRole(['administrador', 'super_admin']);
    }

    /**
     * Verificar si es cliente
     */
    public function isCliente()
    {
        return $this->hasRole('cliente_qr');
    }

    /**
     * Obtener las opciones de tipos de veterinarios
     */
    public static function getTiposVeterinarios()
    {
        return [
            self::TIPO_AUXILIAR => 'Auxiliar de Vet',
            self::TIPO_TECNICO => 'Tec. Veterinario',
            self::TIPO_LICENCIADO => 'Lic. Veterinario',
        ];
    }

    /**
     * Obtener el nombre del tipo de veterinario
     */
    public function getTipoVeterinarioNombreAttribute()
    {
        $tipos = self::getTiposVeterinarios();
        return $tipos[$this->tipo_veterinario] ?? 'No especificado';
    }

    /**
     * Verificar si es veterinario con tipo específico
     */
    public function isVeterinarioTipo($tipo)
    {
        return $this->isVeterinario() && $this->tipo_veterinario === $tipo;
    }

    /**
     * Notificaciones recibidas como veterinario
     */
    public function notificacionesVeterinario()
    {
        return $this->hasMany(VetRequestNotification::class, 'veterinario_id');
    }

    /**
     * Notificaciones enviadas como cliente
     */
    public function notificacionesCliente()
    {
        return $this->hasMany(VetRequestNotification::class, 'cliente_id');
    }

    /**
     * Obtener notificaciones no leídas como veterinario
     */
    public function notificacionesNoLeidasVeterinario()
    {
        return $this->notificacionesVeterinario()->noLeidas();
    }

    /**
     * Obtener notificaciones no leídas como cliente
     */
    public function notificacionesNoLeidasCliente()
    {
        return $this->notificacionesCliente()->noLeidas();
    }


    /**
     * Notificaciones de citas
     */
    public function appointmentNotifications()
    {
        return $this->hasMany(AppointmentNotification::class);
    }

    /**
     * Notificaciones no leídas de citas
     */
    public function unreadAppointmentNotifications()
    {
        return $this->appointmentNotifications()->unread();
    }

    /**
     * Boot del modelo para manejar generación automática de slug
     */
    protected static function boot()
    {
        parent::boot();

        // Generar slug automáticamente
        static::creating(function ($user) {
            if (empty($user->slug)) {
                $user->slug = $user->generateSlug();
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty('name') && empty($user->slug)) {
                $user->slug = $user->generateSlug();
            }
        });
    }
}
