<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccinationRecord extends Model
{
    use HasFactory;

    // Constantes para los tipos de registros
    const TYPE_VACCINATION = 'vacuna';
    const TYPE_CHECKUP = 'checkeo';
    const TYPE_GROOMING = 'peluqueria';
    const TYPE_SURGERY = 'operacion';
    const TYPE_EMERGENCY = 'emergencia';
    const TYPE_DENTAL = 'dental';
    const TYPE_DERMATOLOGY = 'dermatologia';
    const TYPE_NEUROLOGY = 'neurologia';
    const TYPE_CARDIOLOGY = 'cardiologia';

    /**
     * Obtener las opciones de tipos de registros médicos
     */
    public static function getTypeOptions()
    {
        return [
            self::TYPE_VACCINATION => 'Vacunación',
            self::TYPE_CHECKUP => 'Cita de control',
            self::TYPE_GROOMING => 'Peluquería/Estética',
            self::TYPE_SURGERY => 'Operación/Cirugía',
            self::TYPE_EMERGENCY => 'Emergencia',
            self::TYPE_DENTAL => 'Consulta dental',
            self::TYPE_DERMATOLOGY => 'Dermatología',
            self::TYPE_NEUROLOGY => 'Neurología',
            self::TYPE_CARDIOLOGY => 'Cardiología'
        ];
    }

    protected $fillable = [
        'pet_id',
        'veterinarian_id', // ID del veterinario asignado
        'record_type',   // Tipo de registro (vacuna, checkeo, peluquería, operación)
        'vaccine_name',  // Para vacunas
        'date',          // Fecha del procedimiento
        'time',          // Hora del procedimiento
        'document_path', // Ruta del documento o imagen de evidencia
        'next_date',     // Próxima fecha programada
        'diagnosis',     // Diagnóstico (para checkeos/operaciones)
        'treatment',     // Tratamiento prescrito
        'observations',  // Observaciones generales
        'vet_name',      // Nombre del veterinario
        'location',      // Lugar donde se realizó
        'file_path',     // Campo anterior para compatibilidad
        'vaccination_date', // Campo anterior para compatibilidad
        'vaccine_type',    // Campo anterior para compatibilidad
        'notes',           // Campo anterior para compatibilidad
        // Campos específicos para vacunación
        'nombre_tecnico',
        'nombre_comercial',
        'lote',
        'laboratorio',
        'fecha_caducidad',
        'fecha_expedicion',
        'fecha_aplicacion',
        'consulta_subtipo'
    ];

    protected $casts = [
        'date' => 'date',
        'vaccination_date' => 'date',
        'next_date' => 'date',
        'time' => 'datetime:H:i:s',
        'fecha_caducidad' => 'date',
        'fecha_expedicion' => 'date',
        'fecha_aplicacion' => 'date'
    ];

    protected $dates = [
        'date',
        'vaccination_date',
        'next_date',
        'time',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the pet that owns the vaccination record.
     */
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    /**
     * Get the veterinarian assigned to this record.
     */
    public function veterinarian()
    {
        return $this->belongsTo(User::class, 'veterinarian_id');
    }

    /**
     * Relación con solicitudes de cambio
     */
    public function changeRequests()
    {
        return $this->hasMany(AppointmentChangeRequest::class, 'appointment_id');
    }

    /**
     * Obtener solicitudes pendientes de cambio
     */
    public function pendingChangeRequests()
    {
        return $this->changeRequests()->pending();
    }

    /**
     * Obtener vacunas comunes por especie
     */
    public static function getVaccinesBySpecies($species)
    {
        $vaccines = [
            'perro' => [
                'Rabia',
                'DHPP (Distemper, Hepatitis, Parvovirus, Parainfluenza)',
                'Bordetella (Tos de las perreras)',
                'Leptospirosis',
                'Lyme (Enfermedad de Lyme)',
                'Giardia',
                'Coronavirus',
                'Adenovirus Tipo 2',
                'Parainfluenza',
                'Bordetella Bronchiseptica'
            ],
            'gato' => [
                'Rabia',
                'FVRCP (Rinotraqueitis, Calicivirus, Panleucopenia)',
                'Leucemia Felina (FeLV)',
                'Inmunodeficiencia Felina (FIV)',
                'Chlamydia',
                'Bordetella',
                'Giardia',
                'Peritonitis Infecciosa Felina (PIF)',
                'Herpesvirus Felino',
                'Calicivirus Felino'
            ]
        ];

        return $vaccines[strtolower($species)] ?? [];
    }
}
