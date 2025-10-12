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
        Schema::create('appointment_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained('vaccination_records')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('veterinarian_id')->constrained('users')->onDelete('cascade');
            $table->date('requested_date');
            $table->time('requested_time')->nullable();
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('vet_notes')->nullable();
            $table->timestamp('vet_response_at')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['status', 'veterinarian_id']);
            $table->index(['client_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_change_requests');
    }
};