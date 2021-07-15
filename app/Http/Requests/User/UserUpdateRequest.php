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
            "image" => "nullable|string",
            "total_coins" => "nullable|numeric",
            "premium_type" => "nullable|string|min:3",
            "has_premium" => "nullable|boolean",
            "last_date" => "nullable|string",
        ];
    }
}
