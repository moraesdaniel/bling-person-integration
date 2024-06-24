<?php

namespace Domain\ReceivableInvoices;

use DateTimeImmutable;
use Domain\PaymentMethods\PaymentMethodsService;
use Error;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Http;

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

        if ($invoices->successful()) {
            $invoices = $invoices->json();

            $paymentMethodsService = new PaymentMethodsService();

            $qtyTotal = 0;
            $qtyCreditCard = 0;
            foreach ($invoices['retorno']['contasreceber'] as $invoice) {
                $paymentMethodId = (int) $invoice['contaReceber']['idFormaPagamento'];
                $invoiceValue = (float) $invoice['contaReceber']['valor'];
                if ($paymentMethodsService->isCreditCard($paymentMethodId)) {
                    $taxValue = round($invoiceValue * $paymentMethodsService->getTaxById($paymentMethodId) / 100, 2);
                    $invoiceId = $invoice['contaReceber']['id'];
                    $dataLiquidacao = new DateTimeImmutable($invoice['contaReceber']['vencimento']);
                    if (($invoiceId == '20350409458') or (  $invoiceId == '20350409471')) {
                        echo "ID: $invoiceId" . PHP_EOL;
                        echo 'Valor: ' . $invoice['contaReceber']['valor'] . PHP_EOL;
                        echo 'Taxa de cartão: ' . $taxValue . PHP_EOL;
                        echo 'Cliente: ' . $invoice['contaReceber']['cliente']['nome'] . PHP_EOL;
                        echo 'Vencimento: ' . $dataLiquidacao->format('d/m/Y') . PHP_EOL;
                        echo 'Forma de Pgto.: ' . $paymentMethodsService->getDescriptionById((int) $invoice['contaReceber']['idFormaPagamento']) . PHP_EOL;
                        echo '############################################################' . PHP_EOL;
                        $qtyCreditCard += 1;

                        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
                        $xml .= '<contareceber>';
                        $xml .= '<dataLiquidacao>' . $dataLiquidacao->format('d/m/Y') . '</dataLiquidacao>';
                        $xml .= '<tarifa>' . number_format($taxValue, 2, '.') . '</tarifa>';
                        $xml .= '</contareceber>';

                        echo $xml . PHP_EOL;

                        $httpClient = new Client();

                        try {
                            $response = $httpClient->put(
                                $blingBaseUrl . "/contareceber/$invoiceId/json?apikey=$apiKey&xml=" . urlencode($xml)
                            );
                            echo "Título baixado com sucesso" . PHP_EOL;
                        } catch (ClientException $e) {
                            echo "ERROR: " . $e->getCode() . " Messsage: " . $e->getMessage();
                        }
                    }
                }
                $qtyTotal += 1;

            }

        } else {
            throw new Exception('Falha ao buscar as contas a receber ' . $invoices->status());
        }

        echo "Quantidade Total: $qtyTotal / Cartão: $qtyCreditCard" . PHP_EOL;

        //A conta da taxa é um round
    }
}
