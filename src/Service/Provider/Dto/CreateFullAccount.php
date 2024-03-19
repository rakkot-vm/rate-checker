<?php

declare(strict_types=1);

namespace App\Service\Provider\Dto;

use Rbk\Payments\DataBundle\Service\Account\ProviderName;
use Rbk\Payments\DataBundle\Service\Account\Type;

class CreateFullAccount
{
    private ?string $name = null;
    private ?ProviderName $providerName = null;
    private ?string $externalId = null;
    private ?Type $type = null;
    private ?string $currency = null;
    private ?string $countryCode = null;
    private ?array $requisites = null;
    private ?array $metadata = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $value): void
    {
        $this->name = $value;
    }

    public function getProviderName(): ?ProviderName
    {
        return $this->providerName;
    }

    public function setProviderName(ProviderName $value): void
    {
        $this->providerName = $value;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(string $value): void
    {
        $this->externalId = $value;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(Type $value): void
    {
        $this->type = $value;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $value): void
    {
        $this->currency = $value;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $value): void
    {
        $this->countryCode = $value;
    }

    public function getRequisites(): ?array
    {
        return $this->requisites;
    }

    public function setRequisites(array $value): void
    {
        $this->requisites = $value;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(array $value): void
    {
        $this->metadata = $value;
    }
}
