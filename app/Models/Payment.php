<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'payment_method',
        'payment_id',
        'status',
    ];

    // Definir constantes para los estados
    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';

    // Relación con la mascota
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}