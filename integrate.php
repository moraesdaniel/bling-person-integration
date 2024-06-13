<?php

require_once('vendor/autoload.php');

use Domain\Person\Person;

echo "Integrando pessoas...";

$csvFile = '/var/www/DadosClientes.csv';
if (($handle = fopen($csvFile, 'r')) !== FALSE) {
    $qtyRegisters = 0;
    $qtyRegistersValids = 0;
    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        if ($data[0] != 'Código') {
            try {
                $person = new Person($data[1], $data[3], $data[18], $data[4], $data[7]);
                if ($person->getStatus() == 'Ativo') $qtyRegistersValids++;
                //echo $person;
            } catch (Exception $e) {
                //echo $e->getMessage() . PHP_EOL;
            }
            $qtyRegisters++;
        }
    }
    echo "Total de registros: $qtyRegisters / Válidos: $qtyRegistersValids";
    // Fecha o arquivo
    fclose($handle);
} else {
    $this->error("Não foi possível abrir o arquivo CSV.");
}