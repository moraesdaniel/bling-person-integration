<?php

namespace Domain\Person;

use DomainException;

class PersonBuilder
{
    private Person $person;

    public function __construct()
    {
        //$this->person = new Person();
    }

    public function reset(): void
    {
        //$this->person = new Person();
    }

    public function initialize(string $name, string $document, string $phones, string $personNature): PersonBuilder
    {
        $this->person->setName($name);
        $this->person->setDocument($document);
        $this->person->setPhones($phones);
        $this->person->setPersonNature($personNature);
        $this->person->setStatus('ATIVO');
        return $this;
    }

    public function getPerson(): Person
    {
        if (empty($this->person->getName())) {
            throw new DomainException('A classe ainda não está pronta. Invoque o método initialize().');
        }

        $result = $this->person;
        $this->reset();
        return $result;
    }
}
