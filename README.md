# Laravel Filament CRM

A comprehensive Customer Relationship Management (CRM) system built with Laravel and Filament.

## Features

### Core CRM Functionality
- **Company Management**: Complete company profile management with contact information, addresses, and notes
- **Contact Management**: Detailed contact management system with company associations
- **Deal Pipeline**: Sales pipeline management with stages, statuses, and deal tracking
- **Activity Tracking**: Comprehensive activity logging for companies, contacts, and deals
- **Team-Based Multi-Tenancy**: Multi-tenant architecture with team isolation

### User Management
- **Dual Panel System**: Separate admin and user interfaces
- **Admin Panel**: Full administrative control with complete resource management
- **User Panel**: User-friendly interface for managing personal CRM data
- **Team Management**: Multi-team support with personal and shared teams

### Administrative Features
- **User Administration**: Complete user management system
- **Team Administration**: Team creation and management
- **Data Isolation**: Team-based data separation and security
- **Profile Management**: User profile customization

### Technical Features
- **Laravel Framework**: Built on Laravel with modern PHP practices
- **Filament Admin**: Beautiful admin interface powered by Filament
- **Database Seeding**: Comprehensive seeders for development and testing
- **Factory Support**: Model factories for all entities
- **Multi-Tenancy**: Team-based multi-tenant architecture

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
- `app/Filament/Resources/` - Shared resources accessible from both panels
- `database/factories/` - Model factories for testing
- `database/seeders/` - Database seeders
- `routes/` - Application routes

## License

MIT License

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.
