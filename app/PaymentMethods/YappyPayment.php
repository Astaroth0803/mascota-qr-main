<?php 

namespace App\PaymentMethods;

class YappyPayment implements PaymentMethod
{
    public function processPayment(float $amount, array $data): array
    {
        // Simular el procesamiento del pago con Yappy
        $paymentId = $data['payment_id']; // ID de pago generado por Yappy

        return [
            'success' => true,
            'payment_id' => $paymentId,
            'message' => 'Pago procesado exitosamente con Yappy.',
        ];
    }
}