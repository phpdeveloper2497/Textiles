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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024'
            ];

    }

    public function messages()
    {
        return [
            "name.required" => "Mahsulot nomi kiritilishi shart",
            "sort_by.required" => "Omborda joy nomi kiritilishi shart",
            "name.unique" => "Bu nom allaqachon yaratilgan",
            "sort_by.unique" => "Omborxonada bunday nomli joy allaqachon yaratilgan",
            'sort_by.string' => 'Saralash boʻyicha beriladigan maydon qatori so\'z bo\'lishi kerak.',
            "image.max" => "Rasm maydoni 1024 kilobaytdan oshmasligi kerak",
            "image.mimes" => "Rasm jpeg,png,jpg,gif,svg formatlarida bo'lishi kerak"
        ];
    }
}
