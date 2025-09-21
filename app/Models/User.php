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
}
