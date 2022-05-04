<?php

namespace App\Services\Acquirers\Contracts;

interface AcquirerInterface
{
    public function name();

    public function purchase(array $attributes);
}
