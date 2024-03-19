<?php

declare(strict_types=1);

namespace App\Service\Provider\MonoBank\Response;

abstract class BaseResponse implements Response
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        private array $data
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function data(): array
    {
        return $this->data;
    }
}
