<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoxHistoryRequest extends FormRequest
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
            "box_id" => [
                'numeric',
                'exists:boxes,id',
                'required'
            ],
            "user_id" =>['nullable','exists:users,id','numeric'],
            "in_storage" => ['required','boolean'],
            "out_storage" => ['required','boolean'],
            "returned" => ['required','boolean'],
            "per_pc_meter" => "required|numeric",
            "pc" => "required|numeric",
            "commentary" => "nullable|string|max:255"
        ];
    }
}
