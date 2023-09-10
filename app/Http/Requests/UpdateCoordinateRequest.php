<?php

namespace App\Http\Requests;

use App\Models\Coordinate;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property float $x
 * @property float $y
 * @property float $z
 * @property string $t
 */
class UpdateCoordinateRequest extends FormRequest
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
            'x' => [
                'required',
                'numeric',
                'min:' . Coordinate::MIN_VALUE,
                'max:' . Coordinate::MAX_VALUE,
            ],
            'y' => [
                'required',
                'numeric',
                'min:' . Coordinate::MIN_VALUE,
                'max:' . Coordinate::MAX_VALUE,
            ],
            'z' => [
                'required',
                'numeric',
                'min:' . Coordinate::MIN_VALUE,
                'max:' . Coordinate::MAX_VALUE,
            ],
            't' => [
                'required',
                'date_format:Y-m-d H:i:s',
            ],
        ];
    }
}
