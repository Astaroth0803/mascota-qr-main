<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentAcceptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // La autorización se maneja en el controlador con policies
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'scheduled_datetime' => 'required|date|after:now',
            'location' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'scheduled_datetime.required' => 'La fecha y hora de la cita es obligatoria.',
            'scheduled_datetime.date' => 'La fecha y hora debe ser una fecha válida.',
            'scheduled_datetime.after' => 'La fecha y hora debe ser posterior a la fecha actual.',
            'location.max' => 'La ubicación no puede exceder los 255 caracteres.',
        ];
    }
}
