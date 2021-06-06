<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|min:3|max:255",
            "email" => "required|email|min:3|max:255",
            "password" => "required|string|min:8",
            "image" => "nullable|image|mines:jpg,jpeg,png,gif",
            "total_coins" => "required|min:1"
        ];
    }
}
