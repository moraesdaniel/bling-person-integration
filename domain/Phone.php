<?php

namespace Domain;

use DomainException;

class Phone
{
    private string $phoneNumber;
    private string $phoneType;

    CONST PHONE = 'Fixo';
    CONST CEL_PHONE = 'Celular';

    public function __construct(string $phoneNumber)
    {
        $phoneNumber = trim($phoneNumber, "0");

        $phoneNumberLength = strlen($phoneNumber);

        if (!in_array($phoneNumberLength, [8, 9, 10, 11])) {
            throw new DomainException("Número de telefone inválido: $phoneNumber");
        }

        //2 a 5 fixo maior que 5 celular
        if (in_array($phoneNumber[-8], ['8', '9'])) {
            $this->phoneType = Self::CEL_PHONE;
        } else {
            $this->phoneType = Self::PHONE;
        }

        if ($this->phoneType == Self::CEL_PHONE) {
            if ($phoneNumberLength == 8) {
                $phoneNumber = '9' . $phoneNumber;
            } elseif ($phoneNumberLength == 10) {
                $phoneNumber = substr($phoneNumber, 0, 2) . '9' . substr($phoneNumber, 2, 8);
            }
        } else {
            if (!in_array($phoneNumberLength, [8, 10])) {
                throw new DomainException("Número de telefone fixo inválido: $phoneNumber");
            }

            echo "Achei um fixo: " . $phoneNumber . PHP_EOL;
        }

        $this->phoneNumber = $phoneNumber;
    }

    public function __toString(): string
    {
        return $this->phoneNumber . " [$this->phoneType]";
    }
}