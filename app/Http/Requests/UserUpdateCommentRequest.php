<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UserUpdateCommentRequest extends FormRequest
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
            'product_id'=>'nullable|integer|exists:products,id',
            'content' => 'nullable|string|max:1000',
            'rating' => 'nullable|integer|between:1,5',
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
