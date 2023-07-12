<?php

namespace App\Http\Requests;

use App\Models\Vehicle;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string> // @phpcs:ignore
     */
    public function rules(): array
    {
        return [
            'make' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'registration' => [
                'string',
                'max:255',
                Rule::unique(Vehicle::class)->ignore($this->vehicle->id),
            ],
        ];
    }
}
