<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['note', 'call', 'email', 'meeting', 'task'];
        
        // For polymorphic relationships, we'll need to set these when creating
        // Default to Company, but this should be overridden when seeding
        return [
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
            'subject_type' => Company::class,
            'subject_id' => Company::factory(),
            'type' => $this->faker->randomElement($types),
            'due_at' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
            'completed_at' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'description' => $this->faker->paragraph(),
        ];
    }
}
