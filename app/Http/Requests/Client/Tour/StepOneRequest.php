<?php

namespace App\Http\Requests\Client\Tour;

use Illuminate\Foundation\Http\FormRequest;

class StepOneRequest extends FormRequest
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
            'tourName' => 'required',
            'startLocation' => 'required', 'string',
            'price' => 'required', 'integer',
            'transports' => 'required|array',
            'transports.*' => 'integer',
            'places' => 'required|array',
            'places.*' => 'required|integer',
            'startDate' => 'required',
            'endDate' => 'required',
            'people' => 'required',
            'about' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png,avif',
        ];
    }
}
