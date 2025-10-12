/**
 * 🔥 Sistema de Notificaciones en Tiempo Real
 * Maneja notificaciones push, contadores y actualizaciones automáticas
 */

class NotificationManager {
    constructor() {
        this.userId = this.getUserId();
        this.unreadCount = 0;
        this.init();
    }

    /**
     * Inicializar el sistema de notificaciones
     */
    init() {
        if (!this.userId || !window.Echo) {
            console.warn('NotificationManager: Usuario no autenticado o Echo no disponible');
            return;
        }

        this.setupChannels();
        this.loadInitialCount();
    }

    /**
     * Obtener ID del usuario autenticado
     */
    getUserId() {
        // Buscar el ID del usuario en el DOM o en una variable global
        const userElement = document.querySelector('[data-user-id]');
        if (userElement) {
            return userElement.getAttribute('data-user-id');
        }
        
        // Fallback: buscar en window
        if (window.Laravel && window.Laravel.user) {
            return window.Laravel.user.id;
        }
        
        // Fallback: buscar en meta tags
        const metaUserId = document.querySelector('meta[name="user-id"]');
        if (metaUserId) {
            return metaUserId.getAttribute('content');
        }
        
        // Fallback: buscar en el body
        const bodyUserId = document.body.getAttribute('data-user-id');
        if (bodyUserId) {
            return bodyUserId;
        }
        
        console.warn('⚠️ No se pudo encontrar user-id. Asegúrate de incluir data-user-id en el DOM');
        return null;
    }

    /**
     * Configurar canales de escucha
     */
    setupChannels() {
        // Canal privado del usuario para notificaciones
        window.Echo.private(`user.${this.userId}`)
            .listen('.notification.sent', (e) => {
                this.handleNewNotification(e);
            })
            .listen('.notification.count.updated', (e) => {
                this.updateNotificationCount(e.unread_count);
            });

        // Canal privado del veterinario para solicitudes
        window.Echo.private(`veterinarian.${this.userId}`)
            .listen('.vet.request.received', (e) => {
                this.handleVetRequest(e);
            })
            .listen('.appointment.request.received', (e) => {
                this.handleAppointmentRequest(e);
            });
    }

    /**
     * Cargar contador inicial de notificaciones
     */
    async loadInitialCount() {
        try {
            const response = await fetch('/notifications/unread-count', {
                method: 'GET',
                credentials: 'same-origin', // Incluir cookies de sesión
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.updateNotificationCount(data.count);
            }
        } catch (error) {
            console.error('Error cargando contador de notificaciones:', error);
        }
    }

    /**
     * Cargar notificaciones para el dropdown
     */
    async loadNotifications() {
        try {
            const response = await fetch('/notifications/unread', {
                method: 'GET',
                credentials: 'same-origin', // Incluir cookies de sesión
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.updateNotificationCount(data.count);
                this.updateNotificationContent(data.notifications);
            }
        } catch (error) {
            console.error('Error cargando notificaciones:', error);
            this.showErrorInDropdown();
        }
    }

    /**
     * Manejar nueva notificación
     */
    handleNewNotification(data) {
        console.log('🔔 Nueva notificación:', data);
        
        // Mostrar notificación toast
        this.showToast(data.notification);
        
        // Actualizar contador
        this.updateNotificationCount(this.unreadCount + 1);
        
        // Actualizar lista de notificaciones si está visible
        this.refreshNotificationList();
    }

    /**
     * Manejar nueva solicitud de veterinario
     */
    handleVetRequest(data) {
        console.log('🐕 Nueva solicitud de veterinario:', data);
        
        this.showToast({
            title: 'Nueva Solicitud',
            message: `${data.request.cliente_nombre} solicita atención para ${data.request.mascota_nombre}`,
            type: 'vet_request'
        });
        
        // Actualizar contador
        this.updateNotificationCount(this.unreadCount + 1);
    }

    /**
     * Manejar nueva solicitud de cita
     */
    handleAppointmentRequest(data) {
        console.log('📅 Nueva solicitud de cita:', data);
        
        this.showToast({
            title: 'Nueva Cita',
            message: `${data.appointment.client_name} solicita cita para ${data.appointment.pet_name}`,
            type: 'appointment_request'
        });
        
        // Actualizar contador
        this.updateNotificationCount(this.unreadCount + 1);
    }

    /**
     * Actualizar contador de notificaciones
     */
    updateNotificationCount(count) {
        this.unreadCount = count;
        
        // Actualizar elementos del DOM
        const countElements = document.querySelectorAll('[data-notification-count]');
        countElements.forEach(element => {
            element.textContent = count;
            element.style.display = count > 0 ? 'inline' : 'none';
        });

        // Actualizar badge de notificaciones
        const badgeElements = document.querySelectorAll('.notification-badge');
        badgeElements.forEach(badge => {
            badge.textContent = count;
            badge.classList.toggle('hidden', count === 0);
        });
    }

    /**
     * Mostrar notificación toast
     */
    showToast(notification) {
        // Crear elemento toast
        const toast = document.createElement('div');
        toast.className = 'notification-toast fixed top-4 right-4 bg-white border-l-4 border-blue-500 shadow-lg rounded-lg p-4 max-w-sm z-50';
        toast.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L3 7v11h14V7l-7-5z"/>
                            </svg>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="text-sm font-medium text-gray-900">${notification.title || 'Nueva notificación'}</h4>
                    <p class="text-sm text-gray-600 mt-1">${notification.message}</p>
                </div>
                <button class="ml-4 text-gray-400 hover:text-gray-600" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        `;

        // Agregar al DOM
        document.body.appendChild(toast);

        // Auto-remover después de 5 segundos
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 5000);

        // Animación de entrada
                setTimeout(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        }, 100);
    }

    /**
     * Actualizar contenido del dropdown de notificaciones
     */
    updateNotificationContent(notifications) {
        const content = document.getElementById('notifications-content');
        if (!content) return;
        
        if (!notifications || notifications.length === 0) {
            content.innerHTML = `
                <div class="text-center py-8">
                    <svg class="mx-auto h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.5 7.5 0 1 0-15 0v5h5l-5 5-5-5h5v-5a7.5 7.5 0 1 1 15 0v5z"></path>
                    </svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">No hay notificaciones</p>
                </div>
            `;
            return;
        }
        
        let html = '';
        notifications.forEach(notification => {
            const isUnread = !notification.is_read;
            const timeAgo = new Date(notification.created_at).toLocaleString('es-ES', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            html += `
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 ${isUnread ? 'bg-blue-50 dark:bg-blue-900/20' : ''}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2L3 7v11h14V7l-7-5z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">${notification.title}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">${notification.message}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">${timeAgo}</p>
                        </div>
                        ${isUnread ? '<div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>' : ''}
                    </div>
                </div>
            `;
        });
        
        content.innerHTML = html;
    }

    /**
     * Mostrar error en el dropdown
     */
    showErrorInDropdown() {
        const content = document.getElementById('notifications-content');
        if (!content) return;
        
        content.innerHTML = `
            <div class="text-center py-8">
                <svg class="mx-auto h-8 w-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-red-500 mt-2">Error cargando notificaciones</p>
            </div>
        `;
    }

    /**
     * Refrescar lista de notificaciones
     */
    refreshNotificationList() {
        // Si hay una función de refresh en la página, llamarla
        if (typeof window.refreshNotifications === 'function') {
            window.refreshNotifications();
        }
    }

    /**
     * Marcar notificación como leída
     */
    async markAsRead(notificationId) {
        try {
            // Determinar la ruta correcta basada en el rol del usuario
            const isVeterinario = document.body.getAttribute('data-user-role') === 'veterinario';
            const route = isVeterinario 
                ? `/dashboard/veterinario/notifications/${notificationId}/mark-as-read`
                : `/dashboard/cliente/notifications/${notificationId}/mark-as-read`;
                
            const response = await fetch(route, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.success) {
                this.updateNotificationCount(data.unread_count);
            }
        } catch (error) {
            console.error('Error marcando notificación como leída:', error);
        }
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    async markAllAsRead() {
        try {
            const response = await fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.success) {
                this.updateNotificationCount(0);
            }
        } catch (error) {
            console.error('Error marcando todas las notificaciones como leídas:', error);
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.notificationManager = new NotificationManager();
});

// Exportar para uso global
window.NotificationManager = NotificationManager;