# Laravel Filament CRM

A comprehensive Customer Relationship Management (CRM) system built with Laravel and Filament, designed to help businesses manage their customer relationships, sales pipeline, and business operations efficiently.

## Features

### User Management
- **Role-Based Access Control**: Admin and User roles with granular permissions
- **User Profiles**: Comprehensive user profile management with customizable fields
- **Team Management**: Multi-tenant support with team-based organization
- **Authentication**: Secure authentication system with email verification

### Content Management
- **Posts**: Create, edit, and manage posts with rich content support
- **Comments**: Interactive commenting system for posts
- **Likes**: Social engagement features with like functionality
- **Media Support**: Image and media upload capabilities

### Social Features
- **Friendships**: User connection and friendship management system
- **Messaging**: Direct messaging between users
- **User Profiles**: Extended profile information with personal details

### Administrative Features
- **Admin Panel**: Comprehensive admin interface built with Filament
- **User Panel**: User-friendly interface for end users
- **Menu Management**: Dynamic menu system with hierarchical structure
- **Site Settings**: Centralized configuration and settings management
- **Module System**: Extensible modular architecture for custom features

### Technical Features
- **Laravel Framework**: Built on Laravel with modern PHP practices
- **Filament Admin**: Beautiful admin interface powered by Filament
- **Database Seeding**: Comprehensive seeders for development and testing
- **Factory Support**: Model factories for all entities
- **Multi-Tenancy**: Team-based multi-tenant architecture
- **API Ready**: RESTful API support for integrations

## Requirements

- PHP 8.3+
- Composer
- Node.js 20+ and npm
- SQLite (for quick start) or MySQL/PostgreSQL

## Installation

1. Clone the repository:
```bash
git clone https://github.com/goleaf/laravelfilamentcrm.git
cd laravelfilamentcrm
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Set up database:
```bash
# For SQLite (recommended for development)
touch database/database.sqlite
# Update .env: DB_CONNECTION=sqlite, DB_DATABASE=database/database.sqlite

# Or configure MySQL/PostgreSQL in .env
```

5. Run migrations and seeders:
```bash
php artisan migrate --seed
```

6. Build frontend assets:
```bash
npm run dev
# Or for production: npm run build
```

7. Start the development server:
```bash
php artisan serve
```

## Default Accounts

After seeding, you can access the application with:

- **Admin Panel** (`/admin`):
  - Email: `admin@example.com`
  - Password: `password`

- **User Panel** (`/app`):
  - Email: `user@example.com`
  - Password: `password`

## Development

### Running Tests
```bash
php artisan test
# Or
./vendor/bin/phpunit
```

### Code Formatting
```bash
./vendor/bin/pint
```

### Building Assets
```bash
npm run dev    # Development mode with hot reload
npm run build  # Production build
```

## Architecture

The application follows a modular architecture:

- `app/Models/` - Eloquent models
- `app/Filament/Admin/` - Admin panel resources and pages
- `app/Filament/App/` - User panel resources and pages
- `database/factories/` - Model factories for testing
- `database/seeders/` - Database seeders
- `routes/` - Application routes

## License

MIT License

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.
