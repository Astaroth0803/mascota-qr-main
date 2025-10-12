<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BroadcastingController extends Controller
{
    /**
     * Autenticar usuario para canales privados de broadcasting
     */
    public function auth(Request $request)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        
        // Generar token de autenticación para el canal
        $channelName = $request->input('channel_name');
        
        // Verificar que el canal sea privado del usuario
        if (strpos($channelName, 'user.') === 0) {
            $channelUserId = str_replace('user.', '', $channelName);
            
            if ($channelUserId != $user->id) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }
        
        // Verificar que el canal sea privado del veterinario
        if (strpos($channelName, 'veterinarian.') === 0) {
            $channelVetId = str_replace('veterinarian.', '', $channelName);
            
            if ($channelVetId != $user->id) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }
        
        // Generar respuesta de autenticación
        $authResponse = [
            'auth' => base64_encode($user->id . ':' . $channelName),
            'user_id' => $user->id,
            'user_info' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ];
        
        return response()->json($authResponse);
    }
}