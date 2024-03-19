<?php

declare(strict_types=1);

namespace App\Service\Provider;

use App\Service\Provider\Interfaces\BankProvider;
use Webmozart\Assert\Assert;

class Providers
{
    /**
     * @var BankProvider[]
     */
    private array $providers;

    /**
     * @param BankProvider[] $providers
     */
    public function __construct(array $providers)
    {
        Assert::allIsInstanceOf($providers, BankProvider::class);
        $this->providers = $providers;
    }

    /**
     * @return BankProvider[]
     */
    public function getProviders(): array
    {
        return $this->providers;
    }
}
