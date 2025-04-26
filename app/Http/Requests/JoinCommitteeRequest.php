<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class JoinCommitteeRequest extends FormRequest
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

     public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => "Validation Error",
            'error' => $validator->errors()->first(),
        ],401));
     }
    public function rules(): array
    {
        return [
            'committee_id' => 'required|integer|exists:committees,id',
            'payment_method' => 'required|string|in:cash,bank_transfer,online_payment',
        ];
    }
}
