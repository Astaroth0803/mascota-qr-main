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
        // Si la tabla no existe, créala completa
        if (!Schema::hasTable('vaccination_records')) {
            Schema::create('vaccination_records', function (Blueprint $table) {
                $table->id();
                $table->string('record_type')->default('vacuna');
                $table->string('vaccine_name')->nullable();
                $table->date('date')->nullable();
                $table->time('time')->nullable();
                $table->string('document_path')->nullable();
                $table->date('next_date')->nullable();
                $table->text('diagnosis')->nullable();
                $table->text('treatment')->nullable();
                $table->text('observations')->nullable();
                $table->string('vet_name')->nullable();
                $table->string('location')->nullable();

                // Compatibilidad con campos antiguos
                $table->string('file_path')->nullable();
                $table->date('vaccination_date')->nullable();
                $table->string('vaccine_type')->nullable();
                $table->text('notes')->nullable();

                $table->timestamps();
            });
        }
        // Si ya existe, solo agrega los campos faltantes
        else {
            Schema::table('vaccination_records', function (Blueprint $table) {
                if (!Schema::hasColumn('vaccination_records', 'record_type')) {
                    $table->string('record_type')->default('vacuna');
                }
                if (!Schema::hasColumn('vaccination_records', 'vaccine_name')) {
                    $table->string('vaccine_name')->nullable();
                }
                if (!Schema::hasColumn('vaccination_records', 'date')) {
                    $table->date('date')->nullable();
                }
                if (!Schema::hasColumn('vaccination_records', 'time')) {
                    $table->time('time')->nullable();
                }
                if (!Schema::hasColumn('vaccination_records', 'document_path')) {
                    $table->string('document_path')->nullable();
                }
                if (!Schema::hasColumn('vaccination_records', 'next_date')) {
                    $table->date('next_date')->nullable();
                }
                if (!Schema::hasColumn('vaccination_records', 'diagnosis')) {
                    $table->text('diagnosis')->nullable();
                }
                if (!Schema::hasColumn('vaccination_records', 'treatment')) {
                    $table->text('treatment')->nullable();
                }
                if (!Schema::hasColumn('vaccination_records', 'observations')) {
                    $table->text('observations')->nullable();
                }
                if (!Schema::hasColumn('vaccination_records', 'vet_name')) {
                    $table->string('vet_name')->nullable();
                }
                if (!Schema::hasColumn('vaccination_records', 'location')) {
                    $table->string('location')->nullable();
                }
                if (!Schema::hasColumn('vaccination_records', 'file_path')) {
                    $table->string('file_path')->nullable();
                }
                if (!Schema::hasColumn('vaccination_records', 'vaccination_date')) {
                    $table->date('vaccination_date')->nullable();
                }
                if (!Schema::hasColumn('vaccination_records', 'vaccine_type')) {
                    $table->string('vaccine_type')->nullable();
                }
                if (!Schema::hasColumn('vaccination_records', 'notes')) {
                    $table->text('notes')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Borra toda la tabla en rollback
        Schema::dropIfExists('vaccination_records');
    }
};
