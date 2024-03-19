<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ExchangeRateThresholdRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExchangeRateThresholdRepository::class)]
#[UniqueEntity('pair', groups: ['default'])]
class ExchangeRateThreshold
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Currency]
    #[Assert\Length(3)]
    #[ORM\Column(name: 'from_currency', type: 'string', length: 3)]
    private ?string $from = null;

    #[Assert\Currency]
    #[Assert\Length(3)]
    #[ORM\Column(name: 'to_currency', type: 'string', length: 3)]
    private ?string $to = null;

    #[Assert\Length(7)]
    #[ORM\Column(name: 'pair', type: 'string', length: 7)]
    private ?string $pair = null;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 10)]
    private ?string $rate = null;

    #[Assert\Choice(['>', '<'])]
    #[ORM\Column(type: 'string', length: 1)]
    private ?string $mode = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $updatedAt = null;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPair(): ?string
    {
        return $this->pair;
    }

    public function getFromCurrency(): ?string
    {
        return $this->from;
    }

    public function getToCurrency(): ?string
    {
        return $this->to;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return \DateTimeImmutable::createFromMutable($this->updatedAt);
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setFromCurrency(string $from): void
    {
        $this->from = $from;
    }

    public function setToCurrency(string $to): void
    {
        $this->to = $to;
    }

    public function setPair(string $from, string $to): void
    {
        $this->pair = sprintf('%s-%s', $from, $to);
    }

    public function setRate(string $rate): void
    {
        $this->rate = $rate;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }
}
