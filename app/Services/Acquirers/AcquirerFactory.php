<?php

namespace App\Services\Acquirers;

use Exception;
use App\Models\Order;
use App\Services\Acquirers\Contracts\AcquirerInterface;
use App\Services\Acquirers\Contracts\AcquirerFactoryInterface;

class AcquirerFactory implements AcquirerFactoryInterface
{
    /**
     * @param string $currency
     * @return MCDOWELLService|POLKService
     * @throws Exception
     */
    public function getAcquirerByCurrency(string $currency): AcquirerInterface
    {
        if (Order::CURRENCY_USD == $currency) {
            return new POLKService();
        } else if (Order::CURRENCY_GPB == $currency) {
            return new MCDOWELLService();
        }

        throw new Exception('Currency not supported, currency: ' . $currency);
    }
}
