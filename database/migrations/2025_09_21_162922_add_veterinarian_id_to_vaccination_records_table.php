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
        Schema::table('vaccination_records', function (Blueprint $table) {
            $table->unsignedBigInteger('veterinarian_id')->nullable()->after('pet_id');
            $table->foreign('veterinarian_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['veterinarian_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vaccination_records', function (Blueprint $table) {
            $table->dropForeign(['veterinarian_id']);
            $table->dropIndex(['veterinarian_id', 'date']);
            $table->dropColumn('veterinarian_id');
        });
    }
};