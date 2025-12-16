<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Laravel\Prompts\Exceptions\NonInteractiveValidationException;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            Artisan::call('shield:generate', [
                '--all' => true,
                '-n' => true,
            ]);
        } catch (NonInteractiveValidationException $e) {
            // Shield generate requires interactive input and cannot be run in non-interactive mode
            // Permissions should be generated manually using: php artisan shield:generate --all
            $this->command->warn('Skipping permission generation. Run "php artisan shield:generate --all" manually to generate permissions.');
        }
    }
}
