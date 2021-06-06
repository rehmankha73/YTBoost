<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class CampaignUpdateRequest extends FormRequest
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
            "campaign_type" => "required",
            "channel_url" => "required|string|min:3",
            "is_state_busy" => "required|boolean",
            "player_id" => "required",
            "required_count" => "required|numeric",
            "required_time" => "required|numeric",
            "user_id" => "required",
            "video_url" => "required|string|min:3",
        ];
    }
}
