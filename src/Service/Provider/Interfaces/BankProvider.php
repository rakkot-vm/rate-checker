<?php

declare(strict_types=1);

namespace App\Service\Provider\Interfaces;

use App\Service\Provider\Dto\ExchangeRate;

interface BankProvider
{
    /**
     * @return ExchangeRate[]
     */
    public function getExchangeRates(): array;
}
