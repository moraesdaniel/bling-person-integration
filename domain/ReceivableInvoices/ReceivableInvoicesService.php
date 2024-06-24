<?php

namespace Domain\ReceivableInvoices;

use DateTimeImmutable;
use Domain\PaymentMethods\PaymentMethodsService;
use Error;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReceivableInvoicesService
{
    public function __construct()
    {
    }

    public function payInvoices(DateTimeImmutable $initialDate, DateTimeImmutable $finalDate): void
    {
        $blingBaseUrl = 'https://www.bling.com.br/Api/v2';
        $apiKey = '3cb70f52bc70c85b20630159bc2508d09ca76b45776eb4c42117334ba32d5b4e5c83c60f';
        $dateInterval = $initialDate->format('d/m/Y') . " TO " . $finalDate->format('d/m/Y');

        $params = [
            'apikey' => $apiKey,
            'filters' => "dataEmissao[$dateInterval]; situacao[aberto]"
        ];

        $invoices = Http::get($blingBaseUrl . '/contasreceber/json', $params);

        if (($invoices->successful()) and (array_key_exists('contasreceber', $invoices['retorno']))) {
            
            $invoices = $invoices->json();

            $paymentMethodsService = new PaymentMethodsService();

            $qtyTotal = 0;
            $qtyCreditCard = 0;
            $qtyPaied = 0;
            $httpClient = new Client();
            foreach ($invoices['retorno']['contasreceber'] as $invoice) {
                $paymentMethodId = (int) $invoice['contaReceber']['idFormaPagamento'];
                
                if ($paymentMethodsService->isCreditCard($paymentMethodId)) {

                    $invoiceId = $invoice['contaReceber']['id'];
                    //if ($invoiceId == '20245674408') {
                        $invoiceValue = (float) $invoice['contaReceber']['valor'];
                        $taxValue = round($invoiceValue * $paymentMethodsService->getTaxById($paymentMethodId) / 100, 2);
                        $dataLiquidacao = new DateTimeImmutable($invoice['contaReceber']['vencimento']);
                        Log::info("Tentando baixar conta com ID: $invoiceId");
                        echo "Tentando baixar conta com ID: $invoiceId" . PHP_EOL;
    
                        $qtyCreditCard += 1;
    
                        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
                        $xml .= '<contareceber>';
                        $xml .= '<dataLiquidacao>' . $dataLiquidacao->format('d/m/Y') . '</dataLiquidacao>';
                        $xml .= '<tarifa>' . number_format($taxValue, 2, '.') . '</tarifa>';
                        $xml .= '</contareceber>';
    
                        try {
                            $response = $httpClient->put(
                                $blingBaseUrl . "/contareceber/$invoiceId/json?apikey=$apiKey&xml=" . urlencode($xml)
                            );
                            Log::info("Título com ID $invoiceId, baixado com sucesso");
                            echo "Título com ID $invoiceId, baixado com sucesso" . PHP_EOL;
                            $qtyPaied++;
                        } catch (ClientException $e) {
                            Log::error("ERROR: " . $e->getCode() . " Messsage: " . $e->getMessage());
                            echo "ERROR: " . $e->getCode() . " Messsage: " . $e->getMessage();
                        }
                    //}
                }
                $qtyTotal += 1;
            }
        } else {
            throw new Exception('Não foram encontradas contas para baixar. StatusCode: ' . $invoices->status());
        }

        echo "Quantidade Total: $qtyTotal / Cartão: $qtyCreditCard / Baixadas: $qtyPaied" . PHP_EOL;
    }
}
