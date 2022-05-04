<?php

namespace App\Http\Controllers;

use App\Constant\Result;
use Illuminate\Http\JsonResponse;
use App\Services\Contracts\PaymentInterface;
use App\Http\Resources\Payment\OrderResource;
use App\Http\Requests\Payment\CreatePaymentRequest;

class ChargesController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentInterface $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function create(CreatePaymentRequest $request): JsonResponse
    {
        $param = $request->only(['amount', 'currency']);

        $result = $this->paymentService->create($param);

        if (!isset($result['code'])) {
            return response()->json([
                'code'    => Result::CODE_FAIL,
                'message' => Result::getMessage(Result::CODE_FAIL)
            ]);
        }

        if (Result::CODE_OK == $result['code'] && !empty($result['data'])) {
            return response()->json([
                'code'    => Result::CODE_OK,
                'message' => Result::getMessage(Result::CODE_OK),
                'data'    => new OrderResource($result['data'])
            ]);
        }

        return response()->json([
            'code'    => $result['code'],
            'message' => Result::getMessage($result['code']),
        ]);
    }
}
