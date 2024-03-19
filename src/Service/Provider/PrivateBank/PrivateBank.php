<?php

declare(strict_types=1);

namespace App\Service\Provider\PrivateBank;

use App\Service\Provider\Dto\ExchangeRate;
use App\Service\Provider\Interfaces\BankProvider;
use App\Service\Provider\PrivateBank\Response\ExchangeRatesResponse;

class PrivateBank implements BankProvider
{
    public function __construct(
        private readonly PrivateBankHttpClient $client
    ) {
    }

    /**
     * @return ExchangeRate[]
     */
    public function getExchangeRates(): array
    {
        $rates = $this->client->getExchangeRates();

        return $this->transformRatesToDto($rates);
    }

    /**
     * @return ExchangeRate[]
     */
    private function transformRatesToDto(ExchangeRatesResponse $rates): array
    {
        $ratesDto = [];

        foreach ($rates->data() as $rate) {
            $rateDto = new ExchangeRate(
                $rate['ccy'],
                $rate['base_ccy'],
                $rate['buy']
            );
            $ratesDto[$rateDto->getPair()] = $rateDto;

            $rateDto = new ExchangeRate(
                $rate['base_ccy'],
                $rate['ccy'],
                $rate['sale']
            );
            $ratesDto[$rateDto->getPair()] = $rateDto;
        }

        return $ratesDto;
    }
}
