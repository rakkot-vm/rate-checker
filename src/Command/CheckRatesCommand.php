<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\ExchangeRateThreshold;
use App\Repository\ExchangeRateThresholdRepository;
use App\Service\ExchangeRateThresholdChecker;
use App\Service\Mailer\Mailer;
use App\Service\Provider\Providers;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'check-rates',
)]
class CheckRatesCommand extends Command
{
    use LockableTrait;

    public function __construct(
        private readonly Providers $providers,
        private readonly ExchangeRateThresholdRepository $exchangeRateThresholdRepository,
        private readonly ExchangeRateThresholdChecker $exchangeRateThresholdChecker,
        private readonly Mailer $mailer,
    ) {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->lock()) {
            $output->writeln('The command is already running in another process.');

            return 1;
        }

        $thresholds = $this->exchangeRateThresholdRepository->findAllIndexedByPair();
        $reachedThresholds = [];

        if (count($thresholds) === 0) {
            $output->writeln('There are no rate thresholds, please use <fg=green>set-rate-threshold</> command.');

            return 1;
        }

        foreach ($this->providers->getProviders() as $provider) {
            $reachedThresholds = $this->exchangeRateThresholdChecker->getReachedTresholds(
                $provider->getExchangeRates(),
                $thresholds
            );

            $thresholds = $this->unsetReachedThresholds($reachedThresholds, $thresholds);

            if (count($thresholds) === 0) {
                break;
            }
        }

        if (count($reachedThresholds) > 0) {
            $this->mailer->sendExchangeRateThresholdsReachedEmail($reachedThresholds);
        }

        dd('ERRRROOOOOOOOOORRRRRR');

        return 0;
    }

    /**
     * @param ExchangeRateThreshold[] $reachedThresholds
     * @param ExchangeRateThreshold[] $thresholds
     * @return ExchangeRateThreshold[]
     */
    private function unsetReachedThresholds(array $reachedThresholds, mixed $thresholds): array
    {
        foreach ($reachedThresholds as $reachedPair => $reachedThreshold) {
            unset($thresholds[$reachedPair]);
        }

        return $thresholds;
    }
}
