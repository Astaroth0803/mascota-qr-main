<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Mail\UserCredentialsMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class PaymentController extends Controller
{public function verifyPayment($petId)
    {
        // Obtener el pago asociado a la mascota
        $payment = Payment::where('pet_id', $petId)->firstOrFail();
    
        // Marcar el pago como verificado
        $payment->update(['status' => Payment::STATUS_VERIFIED]);
    
        // Actualizar el estado de la mascota si lo deseas (opcional)
        $pet = $payment->pet;
        $pet->user->update(['verificado' => true]); // Cambia el campo 'verificado' a true
    
        // Registrar la verificación del pago
        Log::info('Pago verificado', [
            'id' => $payment->id,
            'mascota_id' => $payment->pet_id,
            'payment_id' => $payment->payment_id,
            'status' => $payment->status,
        ]);
    
        // Enviar correo electrónico con los datos de acceso
        Mail::to($payment->pet->correo_owner)->send(new UserCredentialsMail($payment->pet->correo_owner, $payment->pet->user->password));
    
        // Registrar el envío del correo
        Log::info('Correo enviado', [
            'destinatario' => $payment->pet->correo_owner,
            'mascota_id' => $payment->pet_id,
        ]);
    
        return redirect()->route('dashboard.solicitudes')->with('success', 'Pago verificado y correo enviado.');
    }
    

    public function rejectRequest($petId)
    {
        // Obtener el pago asociado a la mascota
        $payment = Payment::where('pet_id', $petId)->firstOrFail();

        // Marcar el pago como rechazado
        $payment->update(['status' => Payment::STATUS_REJECTED]);

        // Registrar el rechazo del pago
        Log::info('Pago rechazado', [
            'id' => $payment->id,
            'mascota_id' => $payment->pet_id,
            'payment_id' => $payment->payment_id,
            'status' => $payment->status,
        ]);

        // Eliminar la mascota y el usuario
        $payment->pet->delete();
        $payment->pet->user->delete();

        // Registrar la eliminación de la mascota y el usuario
        Log::info('Mascota y usuario eliminados', [
            'mascota_id' => $payment->pet_id,
            'usuario_id' => $payment->pet->user_id,
        ]);

        return redirect()->route('dashboard.solicitudes')->with('success', 'Solicitud rechazada y datos eliminados permanentemente.');
    }
}
