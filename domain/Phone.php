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
        $phoneNumber = trim(ltrim($phoneNumber, " 0"));

        $phoneNumberLength = strlen($phoneNumber);

        if (!in_array($phoneNumberLength, [8, 9, 10, 11])) {
            throw new DomainException("Quantidade de caracteres fora do padrão: $phoneNumberLength");
        }

        if ($phoneNumber[-8] == '1') {
            throw new DomainException("Número de telefone inválido: $phoneNumber");
        }

        if (in_array($phoneNumber[-8], ['2', '3', '4', '5'])) {
            $this->phoneType = Self::PHONE;
        } else {
            $this->phoneType = Self::CEL_PHONE;
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

        }

        $this->phoneNumber = $phoneNumber;
    }

    public function __toString(): string
    {
        return $this->phoneNumber . " [$this->phoneType]";
    }
}