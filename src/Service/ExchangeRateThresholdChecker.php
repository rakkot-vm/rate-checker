<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ExchangeRateThreshold;
use App\Service\Provider\Dto\ExchangeRate;

class ExchangeRateThresholdChecker
{
    /**
     * @param ExchangeRate[] $rates
     * @param ExchangeRateThreshold[] $thresholds
     * @return ExchangeRateThreshold[]
     */
    public function getReachedTresholds(array $rates, array $thresholds): array
    {
        $matchedRates = array_intersect_key($rates, $thresholds);
        $reachedThresholds = [];

        foreach ($matchedRates as $pair => $matchedRate) {
            if ($thresholds[$pair]->getMode() === '>') {
                $thresholdReached = $matchedRate->getRate() > $thresholds[$pair]->getRate();
            } else {
                $thresholdReached = $matchedRate->getRate() < $thresholds[$pair]->getRate();
            }

            if ($thresholdReached === true) {
                $reachedThresholds[$pair] = $thresholds[$pair];
            }
        }

        return $reachedThresholds;
    }
}
