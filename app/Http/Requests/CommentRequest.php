<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CommentRequest extends FormRequest
{
    /**
     * Xác thực nếu người dùng có quyền thực hiện yêu cầu này.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Đảm bảo người dùng có quyền thực hiện yêu cầu
    }

    /**
     * Quy tắc xác thực.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // 'user_id' => 'required|integer|exists:users,id',
            // 'product_id'=>'required|integer|exists:products,id',
            'product_id' => [
                'required',
                'integer',
                Rule::exists('order_details', 'products_id')->where(function ($query) {
                    $query->whereExists(function ($subQuery) {
                        $subQuery->select('id')
                            ->from('orders')
                            ->whereColumn('orders.id', 'order_details.orders_id') // Liên kết 2 bảng
                            ->where('orders.user_id', Auth::id()); // Kiểm tra user_id
                    });
                }),
            ],
            'content' => 'required|string|max:1000',
            'rating' => 'required|numeric|between:1,5',
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
