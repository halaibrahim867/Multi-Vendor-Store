<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyConverter
{

    private $apiKey;

    protected $baseUrl='https://currencyconversionapi.com/api/v1/live?';
    public function __construct(string $apiKey)
    {
        $this->apiKey=$apiKey;
    }

    public function convert(string $from, string $to , float $amount=1) : float
    {
        //$q ="{$from}_{$to}";
        $response=Http::baseUrl($this->baseUrl)
            ->get('',[
                'access_key' => $this->apiKey,
                'from' => $from,
                'to' => $to,
                'amount' => $amount,
            ]);
        $result=$response->json();

       // dd($result);
        //return $result[$q] * $amount;

        $quoteKey = strtoupper($from . $to);

        // Get the conversion rate
        $conversionRate = $result['quotes'][$quoteKey] ?? null;

        if (!$conversionRate) {
            throw new \Exception("Conversion rate for {$from} to {$to} not found.");
        }

        // Return the converted amount
        return $conversionRate * $amount;
    }
}
