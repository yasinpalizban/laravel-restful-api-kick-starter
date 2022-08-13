<?php

namespace Modules\Auth\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class IpActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'login' => fake()->name(),
            'success' => fake()->boolean,
            'ip_address' => fake()->ipv4,
            'user_agent' => fake()->userAgent,
            'date' => fake()->dateTime,
        ];
    }



}
