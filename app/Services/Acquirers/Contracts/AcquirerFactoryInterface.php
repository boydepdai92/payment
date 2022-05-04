<?php

namespace App\Services\Acquirers\Contracts;

interface AcquirerFactoryInterface
{
    public function getAcquirerByCurrency(string $currency);
}
