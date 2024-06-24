<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHandkerchiefHistoryRequest extends FormRequest
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
            'user_id' => [
                "nullable",
                "exists:users,id",
                "numeric"
            ],
            'handkerchief_id' => [
                "required",
                "exists:handkerchiefs,id",
                "numeric"
            ],
            'all_products' => "required|numeric",
            'finished_products' => "required|numeric",
            'defective_products' => "required|numeric",
        ];
    }
}
