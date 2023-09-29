<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $title
 * @property string $description
 * @property int $coordinate_id
 */
class StoreLocationRequest extends FormRequest
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
            'title' => [
                'required',
                'min:0',
                'max:255',
                'unique:locations,title',
            ],
            'description' => [
                'required',
                'min:0',
                'max:10000',
            ],
            'coordinate_id' => [
                'required',
                'int',
                'exists:coordinates,id',
            ],
        ];
    }
}
