<?php

namespace App\Services\Contracts;

interface PaymentInterface
{
    public function create(array $params);
}
