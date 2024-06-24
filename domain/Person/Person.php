<?php

namespace Domain\Person;

use DateTimeImmutable;
use DomainException;
use Domain\Phone;
use Exception;
use Illuminate\Support\Facades\Log;
use LaravelLegends\PtBrValidator\Rules\Cpf;
use LaravelLegends\PtBrValidator\Rules\Cnpj;

class Person
{
    private string $name;
    private string $document;
    private string $personNature;
    private PersonStatus $status;
    private array $phones = [];
    private DateTimeImmutable $birth_date;
    private string $state;

    public function __construct(
        string $name,
        string $document,
        string $phones,
        string $personNature,
        string $status
    )
    {
        $this->setName($name);
        $this->setPersonNature($personNature);
        $this->setDocument($document);
        $this->setPhones($phones);
        $this->setStatus($status);
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setPersonNature(string $personNature): void
    {
        if (($personNature <> 'F') and ($personNature <> 'J')) {
            throw new DomainException('Natureza da pessoa inválida');
        }

        $this->personNature = $personNature;
    }

    public function setDocument(string $document): void
    {
        $cpfValidator = new Cpf();
        $cnpjValidator = new Cnpj();

        if (strlen($document) == 0) throw new DomainException("Sem número do CNPJ/CPF.");

        if ($this->personNature = 'F') {
            $document = str_pad($document, 11, '0', STR_PAD_LEFT);
            if (!$cpfValidator->passes('', $document)) throw new DomainException("Documento $document inválido");
        } else {
            $document = str_pad($document, 14, '0', STR_PAD_LEFT);
            if (!$cnpjValidator->passes('', $document)) throw new DomainException("Documento $document inválido");
        }

        $this->document = $document;
    }

    public function setName(string $name): void
    {
        if (str_word_count($name) < 2) throw new DomainException("Nome do cliente incompleto: $name");

        $this->name = ucwords(strtolower($name));
    }

    public function setPhones(string $phones): void
    {
        $phonesArray = explode(',', $phones);

        foreach ($phonesArray as $phoneNumber) {
            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            try {
                $phone = new Phone($phoneNumber);
                array_push($this->phones, $phone);
            } catch (Exception $e) {
                Log::error("Falha ao criar telefone: " . $e->getMessage());
            }
        }
    }

    public function getName():string
    {
        return $this->name;
    }

    public function getPhones(): array
    {
        return $this->phones;
    }

    public function __toString(): string
    {
        $strReturn = "Nome: $this->name / ";
        $strReturn .= ($this->personNature = 'F') ? "CPF: " : "CNPJ: ";
        $strReturn .= $this->document . " / ";

        $phones = "Telefones: ";
        foreach ($this->phones as $phone) {
            $phones .= $phone . " / ";
        }
        $strReturn .= $phones;

        $strReturn .= "Natureza: $this->personNature" . PHP_EOL;
        return $strReturn;
    }
}
