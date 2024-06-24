<?php

namespace App\Console\Commands;

use DateTimeImmutable;
use Domain\ReceivableInvoices\ReceivableInvoicesService;
use Exception;
use Illuminate\Console\Command;

class PayInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pay-invoices {--initialDate=} {--finalDate=}';

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
        $initialDate = new DateTimeImmutable($this->option('initialDate'));
        $finalDate = new DateTimeImmutable($this->option('finalDate'));

        $service = new ReceivableInvoicesService();

        try {
            $service->payInvoices($initialDate, $finalDate);
        } catch (Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
