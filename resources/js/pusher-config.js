/**
 * 🔥 Configuración de Pusher con Credenciales Reales
 * Este archivo sobrescribe la configuración para usar las credenciales reales
 */

// Esperar a que el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si Echo ya está configurado
    if (window.Echo && window.Echo.connector) {
        console.log('🔄 Reconfigurando Pusher con credenciales reales...');
        
        // Desconectar la instancia anterior
        window.Echo.disconnect();
    }
    
    // Crear nueva instancia con configuración correcta
    try {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: 'cffab9fc9e9dee0dbb7f',
            cluster: 'us2',
            forceTLS: true,
            enabledTransports: ['ws', 'wss'],
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            }
        });
        
        console.log('✅ Pusher configurado correctamente con credenciales reales');
        console.log('🔑 Key:', 'cffab9fc9e9dee0dbb7f');
        console.log('🌍 Cluster:', 'us2');
        
    } catch (error) {
        console.error('❌ Error configurando Pusher:', error);
    }
});
