<?php

namespace App\Http\Requests\Admin\Property;

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
    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'stars' => 'required',
            'location' => 'required',
            'price' => 'required',
            'bed_count' => 'required',
            'bath_count' => 'required',
            'sqft_count' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png,gif',
        ];
    }
}
