<?php

namespace App\Http\Requests;

use App\Rules\QuantityInStock;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ZaloPayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'transaction_id' => 'required|string|exists:orders,id',
            // 'user_id' => 'required|exists:users,id',
            'total_amount' => 'required',
            // 'order_number' => 'required',
            'address_id' => 'required|integer|exists:addresses,id',
            'coupon_id' => 'nullable|integer|exists:coupons,id',
            'cartItems' => 'required|array',
            'cartItems.*.product_id' => 'required|integer|exists:products,id',
            'cartItems.*.quantity' => [
                'required',
                'integer',
                'min:1',
                'max:100',
                new QuantityInStock(),  // Áp dụng custom rule
            ],
            // 'orderid'=>'required|string',
            // 'userid'=>'required|exists:users,id',
            // 'total'=>'required|numeric',
        ];
    }

    /**
     * Tùy chỉnh hành vi khi xác thực thất bại.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(
            response()->json(
                [
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors,
                ],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }
}
