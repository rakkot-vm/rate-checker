<?php

declare(strict_types=1);

namespace App\Service\Provider\MonoBank;

use App\Service\Provider\Dto\ExchangeRate;
use App\Service\Provider\Interfaces\BankProvider;
use App\Service\Provider\MonoBank\Response\ExchangeRatesResponse;
use Symfony\Component\Intl\Currencies;

class MonoBank implements BankProvider
{
    public function __construct(
        private readonly MonoBankHttpClient $client
    ) {
    }

    /**
     * @return ExchangeRate[]
     */
    public function getExchangeRates(): array
    {
        $rates = $this->client->getExchangeRates();
        dd($this->transformRatesToDto($rates));

        return $this->transformRatesToDto($rates);
    }

    /**
     * @return ExchangeRate[]
     */
    private function transformRatesToDto(ExchangeRatesResponse $rates): array
    {
        $ratesDto = [];

        foreach ($rates->data() as $rate) {
            if ($this->onlyCrossRate($rate)) {
                $rateDto = new ExchangeRate(
                    Currencies::forNumericCode($rate['currencyCodeA'])[0],
                    Currencies::forNumericCode($rate['currencyCodeB'])[0],
                    $rate['rateCross']
                );
                $ratesDto[$rateDto->getPair()] = $rateDto;
            } else {
                $rateDto = new ExchangeRate(
                    Currencies::forNumericCode($rate['currencyCodeA'])[0],
                    Currencies::forNumericCode($rate['currencyCodeB'])[0],
                    $rate['rateBuy']
                );
                $ratesDto[$rateDto->getPair()] = $rateDto;

                $rateDto = new ExchangeRate(
                    Currencies::forNumericCode($rate['currencyCodeB'])[0],
                    Currencies::forNumericCode($rate['currencyCodeA'])[0],
                    $rate['rateSell']
                );
                $ratesDto[$rateDto->getPair()] = $rateDto;
            }
        }

        return $ratesDto;
    }

    private function onlyCrossRate($rate): bool
    {
        return !key_exists('currencyCodeB', $rate);
    }
}
