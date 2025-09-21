// database/migrations/2025_07_11_000002_add_pet_id_to_vaccination_records_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vaccination_records', function (Blueprint $table) {
            if (!Schema::hasColumn('vaccination_records', 'pet_id')) {
                $table->unsignedBigInteger('pet_id')->index()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vaccination_records', function (Blueprint $table) {
            if (Schema::hasColumn('vaccination_records', 'pet_id')) {
                $table->dropColumn('pet_id');
            }
        });
    }
};