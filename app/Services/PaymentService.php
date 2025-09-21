<?php

namespace App\Services;

use App\PaymentMethods\PaymentMethod;

class PaymentService
{
    protected $paymentMethod;

    /**
     * Establecer el método de pago.
     *
     * @param PaymentMethod $paymentMethod
     */
    public function setPaymentMethod(PaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * Procesar el pago.
     *
     * @param float $amount
     * @param array $data
     * @return array
     */
    public function processPayment(float $amount, array $data): array
    {
        return $this->paymentMethod->processPayment($amount, $data);
    }
}