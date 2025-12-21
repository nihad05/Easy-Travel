<?php

namespace App\Http\Requests\Admin\Property\Image;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'property_id' => 'required|integer|exists:properties,id',
            'file' => 'required|array',
            'file.*' => 'required',
        ];
    }
}
