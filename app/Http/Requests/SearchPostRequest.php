<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchPostRequest extends FormRequest
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
            'q' => ['string', 'max:255',],
            'sort' => ['in:title,price,created_at',],
            'order' => ['in:asc,desc,'],
            'per_page' => ['integer', 'max:100',],
            'tags' => ['array',],
            'tags.*' => ['integer', 'exists:tags,id',],
        ];
    }
}
