<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->onDelete('cascade'); // Relación con la tabla pets
            $table->string('payment_method')->default('yappy'); // Método de pago (ej: yappy, stripe, paypal)
            $table->string('payment_id'); // ID de pago (Yappy, Stripe, etc.)
            $table->string('status')->default('pending'); // Estado del pago (pending, verified, rejected)
            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}