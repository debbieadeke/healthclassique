<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductionBatchPhaseDetailUpdateRequest extends FormRequest
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
            'production_batch_phase_id' => ['required', 'integer'],
            'product_id' => ['required', 'integer'],
            'percentage' => ['required', 'integer'],
            'weight' => ['required', 'integer'],
            'pack_size_id' => ['required', 'integer'],
        ];
    }
}
