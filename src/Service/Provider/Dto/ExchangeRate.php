<?php

declare(strict_types=1);

namespace App\Service\Provider\Dto;

class ExchangeRate
{
    private string $pair;
    private string $from;

    private string $to;

    private string $rate;

    public function __construct(string $from, string $to, string $rate)
    {
        $this->pair = sprintf('%s-%s', $from, $to);
        $this->from = $from;
        $this->to = $to;
        $this->rate = $rate;
    }

    public function setPair(string $pair): void
    {
        $this->pair = $pair;
    }

    public function setFrom(string $from): void
    {
        $this->from = $from;
    }

    public function setTo(string $to): void
    {
        $this->to = $to;
    }

    public function setRate(string $rate): void
    {
        $this->rate = $rate;
    }

    public function getPair(): string
    {
        return $this->pair;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getRate(): string
    {
        return $this->rate;
    }
}
