<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactForm extends Controller
{
    public function store(Request $request)
{
    // Validación de los datos
    $validated = $request->validate([
        'email' => 'required|email',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);

    try {
        // Simular el envío de correo
        Mail::raw($validated['message'], function ($mail) use ($validated) {
            $mail->from($validated['email']);
            $mail->to('elawebapp@gmail.com') 
                ->subject($validated['subject']);
        });

        // Redirigir con mensaje de éxito
        return redirect()->route('contactanos')->with('Success', '¡Mensaje enviado con éxito!');
    } catch (\Exception $e) {
        // Redirigir con mensaje de error
        return redirect()->route('contactanos')->withErrors(['Danger' => 'Hubo un problema al enviar el mensaje. Intenta de nuevo.']);
    }
}
}
