<?php

namespace Domain\PaymentMethods;

class PaymentMethodsService
{
    private array $paymentMethods;

    public function __construct()
    {
        $this->paymentMethods = [
            // VISA
            5712455 => [
                'descricao' => 'Visa Crédito À Vista',
                'tarifa' => 1.34
            ],
            5712458 => [
                'descricao' => 'Visa Parcelado Até 6x',
                'tarifa' => 1.81
            ],
            5712462 => [
                'descricao' => 'Visa Débito',
                'tarifa' => 0.89
            ],
            // MASTER
            5713317 => [
                'descricao' => 'Master Crédito À Vista',
                'tarifa' => 1.34
            ],
            5713318 => [
                'descricao' => 'Master Parcelado Até 6x',
                'tarifa' => 1.81
            ],
            5713324 => [
                'descricao' => 'Master Débito',
                'tarifa' => 0.89
            ],
            // ELO
            5713390 => [
                'descricao' => 'Elo Crédito À Vista',
                'tarifa' => 1.79
            ],
            5713391 => [
                'descricao' => 'Elo Parcelado Até 6x',
                'tarifa' => 2.09
            ],
            5713392 => [
                'descricao' => 'Elo Débito',
                'tarifa' => 1.29
            ],
            // AMEX
            5713404 => [
                'descricao' => 'Amex Crédito À Vista',
                'tarifa' => 2.29
            ],
            5713411 => [
                'descricao' => 'Amex Parcelado Até 3x',
                'tarifa' => 2.59
            ],
            5713417 => [
                'descricao' => 'Amex Parcelado Até 6x',
                'tarifa' => 2.59
            ],
            //Hipercard
            5791603 => [
                'descricao' => 'Hipercard Parcelado Até 6x',
                'tarifa' => 2.59
            ],
            5796669 => [
                'descricao' => 'Hipercard Crédito À Vista',
                'tarifa' => 2.29
            ]
        ];
    }

    public function getTaxById(int $paymentMethodId): float
    {
        if ($this->isCreditCard($paymentMethodId)) {
            return $this->paymentMethods[$paymentMethodId]['tarifa'];
        } else {
            return 0;
        }
    }

    public function getDescriptionById(int $paymentMethodId): string
    {
        if (array_key_exists($paymentMethodId, $this->paymentMethods)) {
            return $this->paymentMethods[$paymentMethodId]['descricao'];
        } else {
            return '';
        }
    }

    public function isCreditCard(int $paymentMethodId): bool
    {
        return array_key_exists($paymentMethodId, $this->paymentMethods);
    }
}
