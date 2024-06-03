<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHandkerchiefRequest extends FormRequest
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
            "box_id" =>[
                "required",
                "exists:boxes,id",
                "numeric"
            ],
            "name" => ["required","string","unique:handkerchiefs,name"],
            "sort_plane" => ["required","string","unique:handkerchiefs,sort_plane"],
        ];
    }
    public function messages()
    {
        return [
            "name.unique" => "Bu nom allaqachon yaratilgan",
            "sort_plane.unique" => "Omborxonada bunday nomli joy allaqachon yaratilgan",
            'sort_plane.string' => 'Saralash boʻyicha beriladigan maydon qatori so\'z bo\'lishi kerak.',

        ];
    }
}
