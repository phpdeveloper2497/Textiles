<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBoxHistoryRequest extends FormRequest
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
            "box_id" => "required",
            "user_id" => "required",
            "in_storage" => "required",
            "out_storage" => "required",
            "returned" => "required",
            "per_pc_meter" => "required",
            "pc" => "required",
            "commentary" => "required|string|max:255"
        ];
    }
}
