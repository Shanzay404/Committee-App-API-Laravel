<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
// use Illuminate\Support\Facades\Validator;

class StoreCommitteeRequest extends FormRequest
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



     public function failedValidation(Validator $validator)
     {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => "Validation Error",
            'errors' => $validator->errors()->first(),
        ],401));
     }
    public function rules(): array
    {
        return [
            'committee_name' => 'required|string|min:5|max:50',
            'committee_type' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'no_of_members' => 'required|integer|min:1',
            'draw_frequency' => 'required|string|in:weekly,monthly,yearly',
            'payment_amount' => 'required|numeric|min:0',
            'payment_cycle' => 'required|string|in:weekly,monthly,yearly',
            'payment_method' => 'required|string|in:cash,bank_transfer,online_payment',
        ];
    }
}
