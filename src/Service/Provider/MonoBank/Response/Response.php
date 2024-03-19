<?php

declare(strict_types=1);

namespace App\Service\Provider\MonoBank\Response;

interface Response
{
    public function data(): array;
}
