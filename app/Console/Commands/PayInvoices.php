<?php

namespace App\Console\Commands;

use DateTimeImmutable;
use Domain\ReceivableInvoices\ReceivableInvoicesService;
use Illuminate\Console\Command;

class PayInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pay-invoices';

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
        $service = new ReceivableInvoicesService();
        $service->payInvoices(
            new DateTimeImmutable('2024-05-22'),
            new DateTimeImmutable('2024-05-22'),
        );
    }
}
