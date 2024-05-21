<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoxRequest extends FormRequest
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
            'name' => 'required|string|unique:boxes,name',
            'per_liner_meter' => 'required',
            'sort_by' => 'required|string|unique:boxes,sort_by',
            ];

    }

    public function messages()
    {
        return [
            "name.unique" => "Bu nom allaqachon kiritilgan",
            "sort_by.unique" => "Bu nomli joy allaqachon kiritilgan"
        ];
    }
}
