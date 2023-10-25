<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'transaction_requests' => 'required|array',
            'transactions.*.invoice_number' => 'required|string',
            'transactions.*.product_id' => 'required|exists:products,id',
            'transactions.*.price' => 'required|numeric|min:0',
            'transactions.*.quantity' => 'required|integer|min:1',
            'transactions.*.transaction_date' => 'required|date',
        ];
    }
}
