<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SoldHandkerchiefRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "sold_out" => ['required','boolean'],
            "sold_products" => "nullable|numeric",
            "sold_defective_products" =>"nullable|numeric"
        ];
    }
}
