<?php

namespace App\Http\Requests;

use App\Models\Resource;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property float $amount
 * @property string $resource_type_id
 */
class UpdateResourceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'amount' => [
                'required',
                'numeric',
                'min:0',
                'max:' . Resource::MAX_AMOUNT,
            ],
            'resource_type_id' => [
                'required',
                'uuid',
                'exists:resource_types,id',
            ],
        ];
    }
}
