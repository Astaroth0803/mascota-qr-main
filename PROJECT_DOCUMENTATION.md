
Buky World es una plataforma web innovadora desarrollada con Laravel 11 que se enfoca en el cuidado y gestión de mascotas mediante códigos QR.
El proyecto permite a los dueños de mascotas registrar información médica, gestionar citas veterinarias y mantener un historial completo de sus mascotas.

Objetivo Principal
Proporcionar una solución tecnológica integral para el cuidado de mascotas, facilitando el acceso a información médica, gestión de citas veterinarias y seguimiento del historial de salud mediante códigos QR únicos.

Stack Tecnológico
- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade Templates + Tailwind CSS
- **Base de Datos**: Postgresql (desarrollo)
- **Autenticación**: Laravel Breeze + Spatie Permissions
- **Panel Admin**: Filament 4.0
- **Componentes**: Livewire 3.6
- **Notificaciones**: Pusher
- **QR Codes**: Endroid QR Code
- **Pagos**: Yappy 
```
mascota-qr/
├── app/
│   ├── Console/Commands/          # Comandos Artisan
│   ├── Events/                    # Eventos del sistema
│   ├── Filament/Resources/        # Recursos Filament
│   ├── Http/Controllers/          # Controladores
│   ├── Listeners/                 # Escuchadores de eventos
│   ├── Livewire/                  # Componentes Livewire
│   ├── Mail/                      # Clases de correo
│   ├── Models/                    # Modelos Eloquent
│   ├── PaymentMethods/           # Métodos de pago
│   ├── Policies/                  # Políticas de autorización
│   ├── Providers/                 # Service Providers
│   ├── Services/                  # Servicios de negocio
│   └── Traits/                    # Traits reutilizables
├── database/
│   ├── migrations/               # Migraciones
│   └── seeders/                  # Seeders
├── resources/
│   ├── css/                      # Estilos CSS
│   ├── js/                       # JavaScript
│   └── views/                    # Vistas Blade
└── routes/                       # Definición de rutas
```

Modelos y Relaciones

Modelo Principal: Pet (Mascota)
```php
- id: Identificador único
- nombre: Nombre de la mascota
- slug: URL amigable
- especie: Perro, Gato, etc.
- raza: Raza específica
- edad_anios/edad_meses: Edad
- peso: Peso en kg
- sexo: Masculino/Femenino
- qr_code: Código QR único
- user_id: Dueño de la mascota
```

Relaciones Principales
- **User** ↔ **Pet**: Un usuario puede tener múltiples mascotas
- **Pet** ↔ **VaccinationRecord**: Una mascota tiene múltiples registros médicos
- **Pet** ↔ **Appointment**: Una mascota puede tener múltiples citas
- **User** ↔ **Appointment**: Usuarios pueden ser clientes o veterinarios
- **Pet** ↔ **Payment**: Una mascota tiene un pago asociado

Sistema de Roles y Permisos

Roles Disponibles
1. **super_admin**: Acceso completo al sistema
2. **administrador**: Gestión de usuarios y mascotas
3. **veterinario**: Gestión de mascotas asignadas y citas
4. **cliente_qr**: Gestión de sus propias mascotas

Permisos Específicos
- `ver-dashboard`: Acceso al dashboard
- `gestionar-mascotas`: CRUD de mascotas
- `asignar-veterinarios`: Asignar veterinarios a mascotas
- `gestionar-citas`: Crear y gestionar citas
- `ver-reportes`: Acceso a reportes y estadísticas

Funcionalidades Principales

1. Gestión de Mascotas
- **Registro**: Formulario completo con validaciones
- **Códigos QR**: Generación automática de códigos únicos
- **Perfil Público**: Acceso mediante QR para información básica
- **Historial Médico**: Registro de vacunas y tratamientos

2. Sistema de Citas
- **Solicitudes**: Clientes pueden solicitar citas
- **Aprobación**: Veterinarios aprueban/rechazan solicitudes
- **Calendario**: Vista de calendario para veterinarios
- **Notificaciones**: Sistema de notificaciones en tiempo real

3. Dashboard y Estadísticas
- **Dashboard Cliente**: Estadísticas de mascotas propias
- **Dashboard Veterinario**: Gestión de mascotas asignadas
- **Dashboard Admin**: Estadísticas globales del sistema

4. Sistema de Pagos
- **Yappy Integration**: Integración con sistema de pagos panameño
- **Verificación**: Proceso de verificación de pagos
- **Estados**: Pending, Verified, Rejected

Servicios Principales

DashboardService
```php
- getClientStats($userId): Estadísticas del cliente
- getAdminStats(): Estadísticas del administrador
- getSecurityAlerts(): Alertas de seguridad
- getClientNotifications($userId): Notificaciones del cliente
```

QRCodeService
```php
- generateUniqueQRCode($pet): Genera código QR único
- assignQRCode($pet): Asigna código QR a mascota
- generatePublicUrl($pet): Genera URL pública
- generateQRForMultiplePets($petIds): Genera QR para múltiples mascotas
```
 DataEncryptionService
```php
- encrypt($value): Encripta datos sensibles
- decrypt($encryptedValue): Desencripta datos
- encryptSensitiveData($data): Encripta array de datos
- isSensitiveField($fieldName): Verifica si campo es sensible
```
AppointmentNotificationService
```php
- notifyRequestCreated($appointmentRequest): Notifica nueva solicitud
- notifyRequestAccepted($appointmentRequest): Notifica aceptación
- notifyRequestRejected($appointmentRequest): Notifica rechazo
- getNotificationsForUser($userId): Obtiene notificaciones
```
Rutas y Controladores

Rutas Principales
- **Web Routes**: Páginas públicas y autenticación
- **Admin Routes**: Gestión administrativa
- **Veterinarian Routes**: Funcionalidades veterinarias
- **Client Routes**: Funcionalidades del cliente
- **API Routes**: Endpoints para AJAX

Controladores Clave
- **VeterinarioController**: Dashboard y gestión veterinaria
- **AppointmentRequestController**: Gestión de solicitudes
- **QRController**: Gestión de códigos QR
- **UserController**: Gestión de usuarios
- **PetController**: CRUD de mascotas

Flujo de Usuario

1. Registro de Cliente
1. Usuario se registra en el sistema
2. Se le asigna rol "cliente_qr"
3. Accede al dashboard del cliente

 2. Registro de Mascota
1. Cliente completa formulario de mascota
2. Sistema valida datos y crea registro
3. Administrador revisa y aprueba solicitud
4. Se genera código QR único
5. Mascota queda disponible en el sistema

 3. Solicitud de Cita
1. Cliente selecciona mascota y veterinario
2. Completa formulario de solicitud
3. Veterinario recibe notificación
4. Veterinario aprueba/rechaza solicitud
5. Cliente recibe notificación del resultado

 4. Gestión Veterinaria
1. Veterinario accede a dashboard
2. Ve mascotas asignadas
3. Gestiona historial médico
4. Programa y gestiona citas
5. Actualiza información médica

Seguridad y Privacidad

Encriptación de Datos
- **Campos Sensibles**: Teléfonos, IDs de pago, documentos
- **Método**: Laravel Crypt con claves personalizadas
- **Algoritmo**: AES-256-CBC

Validaciones
- **Input Sanitization**: Limpieza de datos de entrada
- **File Validation**: Validación de archivos subidos
- **QR Code Validation**: Validación de códigos QR

Políticas de Acceso
- **Autorización**: Spatie Permissions para control granular
- **Middleware**: Verificación de roles en rutas
- **Policies**: Control de acceso a recursos específicos

Métricas y Reportes

Dashboard Cliente
- Total de mascotas
- Mascotas con/sin QR
- Próximas citas
- Actividad reciente
- Distribución por especie/edad

Dashboard Veterinario
- Mascotas asignadas
- Citas del día
- Citas pendientes
- Estadísticas mensuales
- Gráficos de actividad
Dashboard Administrador
- Total de usuarios/mascotas
- Solicitudes pendientes
- Estadísticas de pagos
- Crecimiento del sistema
- Alertas de seguridad

Despliegue y Configuración

Requisitos del Sistema
- PHP 8.2+
- Composer
- Node.js y NPM
- SQLite/MySQL/PostgreSQL

Variables de Entorno
```env
APP_NAME="Buky World"
APP_ENV=production
APP_KEY=base64:...
DB_CONNECTION=pgsql
PUSHER_APP_ID=...
PUSHER_APP_KEY=...
PUSHER_APP_SECRET=...
```

Comandos de Instalación
```bash
composer install
npm install
php artisan migrate
php artisan db:seed
php artisan serve
```

Características Futuras

Funcionalidades Planificadas
- **App Móvil**: Aplicación móvil para escaneo de QR
- **Marketplace**: Tienda de productos para mascotas
- **Red Social**: Comunidad de dueños de mascotas

Mejoras Técnicas
- **API REST**: API completa para integraciones
- **Microservicios**: Arquitectura de microservicios
- **Cache Redis**: Sistema de caché avanzado
- **CDN**: Distribución de contenido global
- **Monitoreo**: Sistema de monitoreo y alertas
 Contacto y Soporte

- **Desarrollado por**: Ela Dev Projects
- **Email**: eladevopspa@gmail.com
- **Propósito**: Cuidado y bienestar de mascotas
- **Licencia**: Propietaria - Todos los derechos reservados

---

*Esta documentación refleja el estado actual del proyecto Buky World. Para actualizaciones y cambios, consultar el repositorio oficial.*
