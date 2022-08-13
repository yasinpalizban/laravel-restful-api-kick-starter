<?php

namespace Modules\Common\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'key' => fake()->name(),
            'value' => fake()->name(),
            'description' => fake()->text,
            'status' => fake()->boolean,
            'created_at' => fake()->dateTime,
        ];
    }



}
