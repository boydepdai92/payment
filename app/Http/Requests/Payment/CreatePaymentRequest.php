<?php

namespace App\Http\Requests\Payment;

use App\Models\Order;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'amount'   => 'required|integer',
            'currency' => ['required', Rule::in([Order::CURRENCY_USD, Order::CURRENCY_GPB])],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required'   => __('Amount is required'),
            'amount.integer'    => __('Amount must be a integer'),
            'currency.required' => __('Currency is required'),
            'currency.in'       => __('Currency not supported'),
        ];
    }
}
