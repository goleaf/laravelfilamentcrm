<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Friendship;
use App\Models\Like;
use App\Models\Menu;
use App\Models\Message;
use App\Models\Post;
use App\Models\Profile;
use App\Models\SiteSettings;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Laravel\Prompts\Exceptions\NonInteractiveValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Generate permissions using Filament Shield
        $this->generatePermissions();

        // Create roles
        $this->createRoles();

        // Create users
        $this->createUsers();

        // Create menus
        $this->createMenus();

        // Create site settings
        $this->createSiteSettings();

        // Create posts, comments, likes, profiles, friendships, messages
        $this->createSocialData();
    }

    /**
     * Generate permissions using Filament Shield
     */
    protected function generatePermissions(): void
    {
        try {
            Artisan::call('shield:generate', [
                '--all' => true,
                '-n' => true,
            ]);
            $this->command->info('Permissions generated successfully.');
        } catch (NonInteractiveValidationException $e) {
            $this->command->warn('Skipping permission generation. Run "php artisan shield:generate --all" manually to generate permissions.');
        }
    }

    /**
     * Create roles and assign permissions
     */
    protected function createRoles(): void
    {
        // Create admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $permissions = Permission::where('guard_name', 'web')->pluck('id')->toArray();
        $adminRole->syncPermissions($permissions);
        $this->command->info('Admin role created with all permissions.');

        // Create user role
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userPermissions = Permission::where('guard_name', 'web')->pluck('id')->toArray();
        $userRole->syncPermissions($userPermissions);
        $this->command->info('User role created with permissions.');
    }

    /**
     * Create users with roles
     */
    protected function createUsers(): void
    {
        // Create admin user
        $adminUser = User::factory()->withPersonalTeam()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $adminUser->assignRole('admin');
        $this->command->info('Admin user created: admin@example.com / password');

        // Create regular user
        $regularUser = User::factory()->withPersonalTeam()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);
        $regularUser->assignRole('user');
        $this->command->info('Regular user created: user@example.com / password');

        // Create additional users
        User::factory(10)->withPersonalTeam()->create()->each(function ($user) {
            $user->assignRole('user');
        });
        $this->command->info('10 additional users created.');
    }

    /**
     * Create menu items
     */
    protected function createMenus(): void
    {
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

    /**
     * Create site settings
     */
    protected function createSiteSettings(): void
    {
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
     * Create social data (posts, comments, likes, profiles, friendships, messages)
     */
    protected function createSocialData(): void
    {
        // Create profiles for existing users
        User::all()->each(function ($user) {
            Profile::factory()->create(['user_id' => $user->id]);
        });
        $this->command->info('Profiles created for all users.');

        // Create posts
        $posts = Post::factory(20)->create();
        $this->command->info('20 posts created.');

        // Create comments for posts
        $posts->each(function ($post) {
            Comment::factory(rand(2, 5))->create(['post_id' => $post->id]);
        });
        $this->command->info('Comments created for posts.');

        // Create likes for posts
        $posts->each(function ($post) {
            Like::factory(rand(1, 10))->create(['post_id' => $post->id]);
        });
        $this->command->info('Likes created for posts.');

        // Create friendships
        $users = User::all();
        foreach ($users->take(5) as $requester) {
            foreach ($users->where('id', '!=', $requester->id)->take(3) as $addressee) {
                Friendship::factory()->create([
                    'requester_id' => $requester->id,
                    'addressee_id' => $addressee->id,
                    'status' => fake()->randomElement(['pending', 'accepted', 'declined']),
                ]);
            }
        }
        $this->command->info('Friendships created.');

        // Create messages
        foreach ($users->take(5) as $sender) {
            foreach ($users->where('id', '!=', $sender->id)->take(2) as $receiver) {
                Message::factory(rand(1, 3))->create([
                    'sender_id' => $sender->id,
                    'receiver_id' => $receiver->id,
                ]);
            }
        }
        $this->command->info('Messages created.');
    }
}
