<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['string', 'max:255',],
            'price' => ['numeric', 'gte:0', 'lt:1000000'],
            'description' => ['string', 'max:10000',],
            'tags' => ['array', 'min:1',],
            'tags.*' => ['integer', 'distinct', 'exists:tags,id',],
        ];
    }
}
