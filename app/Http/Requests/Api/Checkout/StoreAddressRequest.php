<?php

namespace App\Http\Requests\Api\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'label'       => ['nullable', 'string', 'max:50'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'street'      => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
            'location_id' => ['nullable', 'integer', 'exists:locations,id'],
            'is_default'  => ['sometimes', 'boolean'],
        ];
    }
}
