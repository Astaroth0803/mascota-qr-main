{{-- 🔔 Componente de Campana de Notificaciones --}}
@if(auth()->check())
<div class="relative" data-user-id="{{ auth()->id() }}">
    <button 
        id="notification-bell" 
        class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full"
        onclick="toggleNotifications()"
    >
        {{-- Icono de campana --}}
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        
        {{-- Badge de contador --}}
        <span 
            class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden"
            data-notification-count="0"
        >
            0
        </span>
    </button>

    {{-- Dropdown de notificaciones --}}
    <div 
        id="notification-dropdown" 
        class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
    >
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Notificaciones</h3>
                <button 
                    onclick="markAllAsRead()" 
                    class="text-sm text-blue-600 hover:text-blue-800"
                >
                    Marcar todas como leídas
                </button>
            </div>
        </div>
        
        {{-- Lista de notificaciones --}}
        <div id="notification-list" class="max-h-96 overflow-y-auto">
            {{-- Las notificaciones se cargarán aquí dinámicamente --}}
            <div class="p-4 text-center text-gray-500">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500 mx-auto mb-2"></div>
                Cargando notificaciones...
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="p-4 border-t border-gray-200">
            <a 
                href="{{ route('notifications.index') }}" 
                class="block text-center text-blue-600 hover:text-blue-800 text-sm font-medium"
            >
                Ver todas las notificaciones
            </a>
        </div>
    </div>
</div>
@else
{{-- Mostrar solo si el usuario no está autenticado --}}
<div class="relative">
    <button class="relative p-2 text-gray-400 cursor-not-allowed" disabled>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
    </button>
</div>
@endif

<script>
// Funciones globales para el componente
function toggleNotifications() {
    const dropdown = document.getElementById('notification-dropdown');
    const isHidden = dropdown.classList.contains('hidden');
    
    if (isHidden) {
        dropdown.classList.remove('hidden');
        loadNotifications();
    } else {
        dropdown.classList.add('hidden');
    }
}

function loadNotifications() {
    const list = document.getElementById('notification-list');
    
    fetch('/api/notifications/unread')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayNotifications(data.notifications);
            }
        })
        .catch(error => {
            console.error('Error cargando notificaciones:', error);
            list.innerHTML = '<div class="p-4 text-center text-red-500">Error cargando notificaciones</div>';
        });
}

function displayNotifications(notifications) {
    const list = document.getElementById('notification-list');
    
    if (notifications.length === 0) {
        list.innerHTML = '<div class="p-4 text-center text-gray-500">No hay notificaciones nuevas</div>';
        return;
    }
    
    list.innerHTML = notifications.map(notification => `
        <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer" 
             onclick="markAsRead(${notification.id})">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L3 7v11h14V7l-7-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="text-sm font-medium text-gray-900">${notification.title}</h4>
                    <p class="text-sm text-gray-600 mt-1">${notification.message}</p>
                    <p class="text-xs text-gray-400 mt-1">${formatDate(notification.created_at)}</p>
                </div>
                ${!notification.is_read ? '<div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>' : ''}
            </div>
        </div>
    `).join('');
}

function markAsRead(notificationId) {
    if (window.notificationManager) {
        window.notificationManager.markAsRead(notificationId);
    }
    
    // Cerrar dropdown después de marcar como leída
    setTimeout(() => {
        document.getElementById('notification-dropdown').classList.add('hidden');
    }, 500);
}

function markAllAsRead() {
    if (window.notificationManager) {
        window.notificationManager.markAllAsRead();
    }
    
    // Recargar lista
    loadNotifications();
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    
    if (diff < 60000) { // Menos de 1 minuto
        return 'Hace un momento';
    } else if (diff < 3600000) { // Menos de 1 hora
        const minutes = Math.floor(diff / 60000);
        return `Hace ${minutes} minuto${minutes > 1 ? 's' : ''}`;
    } else if (diff < 86400000) { // Menos de 1 día
        const hours = Math.floor(diff / 3600000);
        return `Hace ${hours} hora${hours > 1 ? 's' : ''}`;
    } else {
        return date.toLocaleDateString('es-ES');
    }
}

// Cerrar dropdown al hacer click fuera
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notification-dropdown');
    const bell = document.getElementById('notification-bell');
    
    if (!bell.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>
