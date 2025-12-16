<?php

namespace Database\Factories;

use App\Models\SiteSettings;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SiteSettings>
 */
class SiteSettingsFactory extends Factory
{
    protected $model = SiteSettings::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'currency' => $this->faker->randomElement(['$', '€', '£', '¥']),
            'default_language' => $this->faker->randomElement(['en', 'es', 'fr', 'de']),
            'address' => $this->faker->address(),
            'country' => $this->faker->country(),
            'email' => $this->faker->companyEmail(),
            'phone_01' => $this->faker->phoneNumber(),
            'phone_02' => $this->faker->optional()->phoneNumber(),
            'phone_03' => $this->faker->optional()->phoneNumber(),
            'facebook' => $this->faker->optional()->url(),
            'twitter' => $this->faker->optional()->url(),
            'github' => $this->faker->optional()->url(),
            'youtube' => $this->faker->optional()->url(),
        ];
    }
}


