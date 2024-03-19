<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\ExchangeRateThreshold;
use App\Repository\ExchangeRateThresholdRepository;
use App\Service\Provider\Providers;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'add-threshold',
)]
class AddThresholdCommand extends Command
{
    use LockableTrait;

    public function __construct(
        private readonly Providers $providers,
        private readonly ExchangeRateThresholdRepository $exchangeRateThresholdRepository,
        private ValidatorInterface $validator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Add exchange rate threshold.')
            ->addArgument(
                'from',
                InputArgument::REQUIRED,
                'Currency from.',
            )
            ->addArgument(
                'to',
                InputArgument::REQUIRED,
                'Currency to.',
            )
            ->addArgument(
                'rate',
                InputArgument::REQUIRED,
                'Threshold rate',
            )
            ->addArgument(
                'mode',
                InputArgument::REQUIRED,
                'Mode - > or < than rate.'
            );
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

        $newThreshold = new ExchangeRateThreshold();
        $newThreshold->setFromCurrency($input->getArgument('from'));
        $newThreshold->setToCurrency($input->getArgument('to'));
        $newThreshold->setRate($input->getArgument('rate'));
        $newThreshold->setMode($input->getArgument('mode'));
        $newThreshold->setPair($newThreshold->getFromCurrency(), $newThreshold->getToCurrency());

        $errors = $this->validator->validate($newThreshold);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;

            $output->writeln($errorsString);

            return 1;
        }

        $this->exchangeRateThresholdRepository->updateOrAdd($newThreshold);

        $output->writeln('Threshold was added/updated.');

        return 0;
    }
}
