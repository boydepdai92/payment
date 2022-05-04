<?php

namespace App\Services;

use App\Models\Order;
use App\Constant\Result;
use Illuminate\Support\Facades\Log;
use App\Services\Contracts\PaymentInterface;
use App\Services\Acquirers\Contracts\AcquirerInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\Acquirers\Contracts\AcquirerFactoryInterface;

class PaymentService implements PaymentInterface
{
    protected $orderRepository;
    protected $acquirerFactory;

    public function __construct(OrderRepositoryInterface $orderRepository, AcquirerFactoryInterface $acquirerFactory)
    {
        $this->orderRepository = $orderRepository;
        $this->acquirerFactory = $acquirerFactory;
    }

    public function create(array $params): array
    {
        /** @var AcquirerInterface $acquirer */
        $acquirer = $this->acquirerFactory->getAcquirerByCurrency($params['currency']);

        $orderParam =  [
            'acquirer' => $acquirer->name(),
            'amount'   => $params['amount'],
            'currency' => $params['currency'],
            'status'   => Order::STATUS_CREATED
        ];

        /** @var Order $order */
        $order = $this->orderRepository->create($orderParam);

        if ($order) {
            $resultPay = $acquirer->purchase([
                'amount'   => $params['amount'],
                'currency' => $params['currency'],
                'order_id' => $order->id
            ]);

            if (!isset($resultPay['code'])) {
                Log::warning('Invalid data payment, result: ' . json_encode($resultPay));
                $order->status = Order::STATUS_FAIL;
                if ($order->save()) {
                    return [
                        'code' => Result::CODE_OK,
                        'data' => $order
                    ];
                }

                Log::warning('Update order fail with invalid result, result: ' . json_encode($resultPay));
                return [
                    'code' => Result::CODE_FAIL
                ];
            }

            $order->setDataFromAcquirer($resultPay);

            if ($order->save()) {
                return [
                    'code' => Result::CODE_OK,
                    'data' => $order
                ];
            }

            Log::warning('Update order fail, param: ' . json_encode($resultPay));

            return [
                'code' => Result::CODE_FAIL
            ];
        }

        Log::warning('Create order fail, param: ' . json_encode($orderParam));

        return [
            'code' => Result::CODE_FAIL,
        ];
    }
}
