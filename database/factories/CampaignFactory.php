<?php

namespace Database\Factories;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CampaignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Campaign::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'campaign_type' => $this->faker->numberBetween(1,3),
            'channel_url' => $this->faker->url,
            'is_state_busy' => $this->faker->boolean,
            'player_id' => Str::random(10),
            'required_count' => $this->faker->numberBetween(1,10),
            'required_time' => $this->faker->numberBetween(1,60),
            'user_id'=> $this->faker->numberBetween(1,10) ,
            'video_url' => $this->faker->url,
        ];
    }
}
