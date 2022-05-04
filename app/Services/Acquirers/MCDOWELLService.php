<?php

namespace App\Services\Acquirers;

use App\Constant\Result;
use App\Services\Acquirers\Contracts\AcquirerInterface;

class MCDOWELLService implements AcquirerInterface
{
    public function name(): string
    {
        return 'Mcdowell';
    }

    public function purchase(array $attributes): array
    {
        if (config('app.enableFailCase')) {
            return [
                'code' => Result::CODE_FAIL,
                'data' => [
                    'amount' => $attributes['amount'],
                    'currency' => $attributes['currency'],
                ]
            ];
        }

        return [
            'code' => Result::CODE_OK,
            'data' => [
                'id' => '123456',
                'amount' => $attributes['amount'],
                'currency' => $attributes['currency'],
            ]
        ];
    }
}
