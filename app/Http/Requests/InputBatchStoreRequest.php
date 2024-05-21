<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InputBatchStoreRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'input_id' => ['required', 'integer'],
            'supplier_id' => ['nullable', 'integer'],
            'buying_price' => ['nullable', 'numeric', 'between:-999999.99,999999.99'],
            'selling_price' => ['nullable', 'numeric', 'between:-999999.99,999999.99'],
            'date_supplied' => ['required'],
            'quantity_purchased' => ['required', 'integer'],
            'quantity_remaining' => ['required', 'integer'],
            'pack_size_id' => ['required', 'integer'],
        ];
    }
}
