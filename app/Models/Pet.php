<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VaccinationRecord;

class Pet extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'especie',
        'raza',
        'otra_raza',
        'edad_anios',
        'edad_meses',
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
     * Get the vaccination records for the pet.
     */
    public function vaccinationRecords()
    {
        return $this->hasMany(VaccinationRecord::class)->orderBy('created_at', 'desc');
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
}
