<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deal>
 */
class DealFactory extends Factory
{
    protected $model = Deal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $stages = ['new', 'qualified', 'proposal', 'negotiation', 'won', 'lost'];
        $statuses = ['open', 'won', 'lost'];

        return [
            'team_id' => Team::factory(),
            'company_id' => Company::factory(),
            'contact_id' => Contact::factory(),
            'owner_id' => User::factory(),
            'name' => $this->faker->catchPhrase(),
            'stage' => $this->faker->randomElement($stages),
            'status' => $this->faker->randomElement($statuses),
            'amount' => $this->faker->randomFloat(2, 1000, 1000000),
            'close_date' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'description' => $this->faker->optional()->paragraph(),
        ];
    }
}
