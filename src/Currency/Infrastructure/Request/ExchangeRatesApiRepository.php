<?php

namespace Cart\Currency\Infrastructure\Request;

use Cart\Currency\Domain\CurrencyCode;
use Cart\Currency\Domain\CurrencyRepository;
use Cart\Currency\Domain\Exceptions\InvalidExhangeRateExceptions;
use Symfony\Component\HttpClient\HttpClient;

class ExchangeRatesApiRepository implements CurrencyRepository
{
    const URL = 'https://api.exchangeratesapi.io/latest?base=EUR';

    public function get(CurrencyCode $code):string
    {

        $client = HttpClient::create();

        $response=$client->request('GET',self::URL);
        $exchangeRate=get_object_vars(json_decode($response->getContent()));
        $rates=get_object_vars($exchangeRate['rates']);

        if(!isset($rates[$code->value()])){
            throw new InvalidExhangeRateExceptions();
        }

        return $rates[$code->value()];
    }

    public function status()
    {
        $client = HttpClient::create();

        $response=$client->request('GET',self::URL);

        return $response->getStatusCode();
    }


}