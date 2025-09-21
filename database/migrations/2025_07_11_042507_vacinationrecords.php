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
        if (Schema::hasTable('vaccination_records')) {
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

                // Compatibilidad con campos antiguos
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
        if (Schema::hasTable('vaccination_records')) {
            Schema::table('vaccination_records', function (Blueprint $table) {
                $table->dropColumn([
                    'record_type',
                    'vaccine_name',
                    'date',
                    'time',
                    'document_path',
                    'next_date',
                    'diagnosis',
                    'treatment',
                    'observations',
                    'vet_name',
                    'location',
                    'file_path',
                    'vaccination_date',
                    'vaccine_type',
                    'notes',
                ]);
            });
        }
    }
};
