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
        Schema::create('appointment_notifications', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('appointment_request_id')->constrained('appointment_requests')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Usuario que recibe la notificación
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null'); // Usuario que envía la notificación
            
            // Contenido de la notificación
            $table->string('type'); // 'request_created', 'request_accepted', 'request_rejected', 'appointment_rescheduled', 'appointment_cancelled'
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Datos adicionales (fechas, razones, etc.)
            
            // Estado de la notificación
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['user_id', 'is_read']);
            $table->index(['appointment_request_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_notifications');
    }
};
