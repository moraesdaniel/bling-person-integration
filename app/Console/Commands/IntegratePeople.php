<?php

namespace App\Console\Commands;

use Domain\Person\Person;
use Illuminate\Console\Command;

class IntegratePeople extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:integrate-people';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //$this->info("Integrando pessoas...");
//
        //$csvFile = '/var/www/DadosClientes.csv';
        //if (($handle = fopen($csvFile, 'r')) !== FALSE) {
        //    $qtyRegisters = 0;
        //    $qtyRegistersValids = 0;
        //    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        //        if ($data[0] != 'Código') {
        //            try {
        //                $person = new Person($data[1], $data[3], $data[18], $data[4], $data[7]);
        //                if ($person->getStatus() == 'Ativo') $qtyRegistersValids++;
        //            } catch (Exception $e) {
        //                $this->error($e->getMessage());
        //            }
        //            $qtyRegisters++;
        //        }
        //    }
        //    // Fecha o arquivo
        //    $this->info("Total de registros: $qtyRegisters / Válidos: $qtyRegistersValids");
        //    fclose($handle);
        //} else {
        //    $this->error("Não foi possível abrir o arquivo CSV.");
        //}
    }
}
