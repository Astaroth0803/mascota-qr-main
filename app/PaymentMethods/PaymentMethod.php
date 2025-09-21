<?php 
namespace App\PaymentMethods;

interface PaymentMethod
{
    /**
     * Procesar el pago.
     *
     * @param float $amount
     * @param array $data
     * @return array
     */
    public function processPayment(float $amount, array $data): array;
}