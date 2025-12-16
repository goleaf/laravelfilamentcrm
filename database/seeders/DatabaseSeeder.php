<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Menu;
use App\Models\SiteSettings;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $adminUser = User::factory()->withPersonalTeam()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $this->command->info('Admin user created: admin@example.com / password');

        // Create regular user
        $regularUser = User::factory()->withPersonalTeam()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);
        $this->command->info('Regular user created: user@example.com / password');

        // Create additional users
        $users = User::factory(10)->withPersonalTeam()->create();
        $this->command->info('10 additional users created.');

        // Get all teams for seeding related data
        $teams = Team::all();

        // Create companies for each team
        foreach ($teams as $team) {
            Company::factory(rand(5, 10))->create([
                'team_id' => $team->id,
            ]);
        }
        $this->command->info('Companies created for all teams.');

        // Create contacts for each team
        foreach ($teams as $team) {
            $teamCompanies = Company::where('team_id', $team->id)->get();
            
            Contact::factory(rand(10, 20))->create([
                'team_id' => $team->id,
                'company_id' => $teamCompanies->random()->id ?? null,
            ]);
        }
        $this->command->info('Contacts created for all teams.');

        // Create deals for each team
        foreach ($teams as $team) {
            $teamCompanies = Company::where('team_id', $team->id)->get();
            $teamContacts = Contact::where('team_id', $team->id)->get();
            $teamUsers = $team->allUsers();

            Deal::factory(rand(5, 15))->create([
                'team_id' => $team->id,
                'company_id' => $teamCompanies->random()->id ?? null,
                'contact_id' => $teamContacts->random()->id ?? null,
                'owner_id' => $teamUsers->random()->id ?? $adminUser->id,
            ]);
        }
        $this->command->info('Deals created for all teams.');

        // Create activities for each team
        foreach ($teams as $team) {
            $teamCompanies = Company::where('team_id', $team->id)->get();
            $teamContacts = Contact::where('team_id', $team->id)->get();
            $teamDeals = Deal::where('team_id', $team->id)->get();
            $teamUsers = $team->allUsers();

            // Create activities for companies
            foreach ($teamCompanies->take(5) as $company) {
                Activity::factory(rand(2, 5))->create([
                    'team_id' => $team->id,
                    'user_id' => $teamUsers->random()->id ?? $adminUser->id,
                    'subject_type' => Company::class,
                    'subject_id' => $company->id,
                ]);
            }

            // Create activities for contacts
            foreach ($teamContacts->take(10) as $contact) {
                Activity::factory(rand(1, 3))->create([
                    'team_id' => $team->id,
                    'user_id' => $teamUsers->random()->id ?? $adminUser->id,
                    'subject_type' => Contact::class,
                    'subject_id' => $contact->id,
                ]);
            }

            // Create activities for deals
            foreach ($teamDeals->take(8) as $deal) {
                Activity::factory(rand(2, 4))->create([
                    'team_id' => $team->id,
                    'user_id' => $teamUsers->random()->id ?? $adminUser->id,
                    'subject_type' => Deal::class,
                    'subject_id' => $deal->id,
                ]);
            }
        }
        $this->command->info('Activities created for all teams.');

        // Create menu items
        $menus = [
            [
                'name' => 'Home',
                'url' => '/',
                'order' => 1
            ],
            [
                'name' => 'Properties',
                'url' => '/properties',
                'order' => 2,
                'children' => [
                    ['name' => 'For Sale', 'url' => '/properties/for-sale', 'order' => 1],
                    ['name' => 'For Rent', 'url' => '/properties/for-rent', 'order' => 2],
                ]
            ],
            [
                'name' => 'Services',
                'url' => '/services',
                'order' => 3,
                'children' => [
                    ['name' => 'Buying', 'url' => '/services/buying', 'order' => 1],
                    ['name' => 'Selling', 'url' => '/services/selling', 'order' => 2],
                    ['name' => 'Renting', 'url' => '/services/renting', 'order' => 3],
                ]
            ],
            [
                'name' => 'About',
                'url' => '/about',
                'order' => 4
            ],
            [
                'name' => 'Contact',
                'url' => '/contact',
                'order' => 5
            ],
            [
                'name' => 'Calculators',
                'url' => '/calculators',
                'order' => 6
            ],
        ];

        foreach ($menus as $menuData) {
            $this->createMenu($menuData);
        }
        $this->command->info('Menu items created.');

        // Create site settings
        SiteSettings::factory()->create([
            'name' => config('app.name', 'Laravel Filament CRM'),
            'currency' => '$',
            'default_language' => 'en',
            'email' => 'info@example.com',
            'phone_01' => '+1 234 567 8900',
            'address' => '123 Main St, City, State 12345',
            'country' => 'United States',
        ]);
        $this->command->info('Site settings created.');
    }

    /**
     * Recursively create menu items
     */
    protected function createMenu(array $menuData, ?int $parentId = null): void
    {
        $children = $menuData['children'] ?? [];
        unset($menuData['children']);

        $menuData['parent_id'] = $parentId;
        $menu = Menu::create($menuData);

        foreach ($children as $childData) {
            $this->createMenu($childData, $menu->id);
        }
    }
}
