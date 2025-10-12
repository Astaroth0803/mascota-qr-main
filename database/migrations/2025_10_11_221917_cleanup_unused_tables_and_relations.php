<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Eliminar tablas innecesarias y duplicadas
        
        // 1. Eliminar sistema de citas duplicado (mantener solo appointments)
        Schema::dropIfExists('citas');
        
        // 2. Eliminar sistema de notificaciones duplicado
        Schema::dropIfExists('custom_notifications');
        Schema::dropIfExists('notification_reads');
        
        // 3. Eliminar tablas de solicitudes iniciales (ya no se usan)
        Schema::dropIfExists('solicitudes');
        
        // 4. Eliminar tabla de vacunas separada (se maneja en vaccination_records)
        Schema::dropIfExists('vaccines');
        
        // 5. Eliminar tabla de logs de actividad (no se usa en lógica de negocio)
        Schema::dropIfExists('activity_logs');
        
        // 6. Eliminar tabla de solicitudes de cambio de citas (no se usa)
        Schema::dropIfExists('appointment_change_requests');
        
        // 7. Limpiar columnas innecesarias en appointments
        Schema::table('appointments', function (Blueprint $table) {
            // Eliminar columnas que no se usan
            if (Schema::hasColumn('appointments', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
            if (Schema::hasColumn('appointments', 'reschedule_reason')) {
                $table->dropColumn('reschedule_reason');
            }
            if (Schema::hasColumn('appointments', 'notes')) {
                $table->dropColumn('notes');
            }
        });
        
        // 8. Limpiar columnas innecesarias en appointment_requests
        Schema::table('appointment_requests', function (Blueprint $table) {
            // Eliminar columnas que no se usan
            if (Schema::hasColumn('appointment_requests', 'reschedule_reason')) {
                $table->dropColumn('reschedule_reason');
            }
        });
        
        // 9. Limpiar columnas innecesarias en pets
        Schema::table('pets', function (Blueprint $table) {
            // Eliminar columnas que no se usan
            if (Schema::hasColumn('pets', 'vaccine_file')) {
                $table->dropColumn('vaccine_file');
            }
        });
        
        // 10. Limpiar columnas innecesarias en users
        Schema::table('users', function (Blueprint $table) {
            // Eliminar columnas que no se usan
            if (Schema::hasColumn('users', 'verificado')) {
                $table->dropColumn('verificado');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recrear tablas eliminadas (solo para rollback, no recomendado en producción)
        
        // Recrear tabla de citas
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('veterinario_id');
            $table->unsignedBigInteger('mascota_id');
            $table->datetime('fecha_solicitada');
            $table->datetime('fecha_asignada')->nullable();
            $table->enum('estado', ['pendiente', 'agendada', 'finalizada', 'cancelada'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->text('motivo_rechazo')->nullable();
            $table->text('diagnostico_tratamiento')->nullable();
            $table->timestamps();
            
            $table->foreign('cliente_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('veterinario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('mascota_id')->references('id')->on('pets')->onDelete('cascade');
        });
        
        // Recrear tabla de solicitudes
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_owner');
            $table->string('apellido_owner');
            $table->string('correo_owner');
            $table->string('telefono_owner');
            $table->string('nombre');
            $table->string('especie');
            $table->string('raza');
            $table->integer('edad_anios');
            $table->integer('edad_meses');
            $table->string('sexo');
            $table->string('id_pago_yappy');
            $table->timestamps();
        });
        
        // Recrear tabla de vacunas
        Schema::create('vaccines', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_comercial');
            $table->string('nombre_tecnico');
            $table->string('laboratorio');
            $table->timestamps();
        });
        
        // Recrear tabla de logs de actividad
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->string('subject_type')->nullable();
            $table->string('subject_id')->nullable();
            $table->string('causer_type')->nullable();
            $table->string('causer_id')->nullable();
            $table->json('properties')->nullable();
            $table->timestamps();
            
            $table->index('log_name');
            $table->index(['subject_type', 'subject_id']);
            $table->index(['causer_type', 'causer_id']);
        });
        
        // Recrear tabla de solicitudes de cambio de citas
        Schema::create('appointment_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('veterinarian_id')->constrained('users')->onDelete('cascade');
            $table->datetime('requested_datetime');
            $table->datetime('new_datetime');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
        
        // Recrear tabla de notificaciones personalizadas
        Schema::create('custom_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type');
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
        
        // Recrear tabla de lecturas de notificaciones
        Schema::create('notification_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('notification_id')->constrained('custom_notifications')->onDelete('cascade');
            $table->timestamp('read_at');
            $table->timestamps();
            
            $table->unique(['user_id', 'notification_id']);
        });
        
        // Restaurar columnas eliminadas
        Schema::table('appointments', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable();
            $table->text('reschedule_reason')->nullable();
            $table->text('notes')->nullable();
        });
        
        Schema::table('appointment_requests', function (Blueprint $table) {
            $table->text('reschedule_reason')->nullable();
        });
        
        Schema::table('pets', function (Blueprint $table) {
            $table->string('vaccine_file')->nullable();
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('verificado')->default(false);
        });
    }
};