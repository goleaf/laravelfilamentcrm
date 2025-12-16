#!/bin/bash
# Setup script for the genealogy-laravel project.
#
# This script prepares the project environment by copying the .env.example to .env (if necessary),
# installing dependencies, generating application keys, running database migrations, seeding the database,
# and executing Laravel optimization commands. It ensures the application is ready for development or production use.
clear
echo "=================================="
echo "===== USER: [$(whoami)]"
echo "===== [PHP $(php -r 'echo phpversion();')]"
echo "=================================="
echo ""
echo ""
echo "=================================="
echo "===== PREPARING YOUR PROJECT..."
echo "=================================="
echo ""
# Setup the .env file
# Non-interactive mode: skip .env copy if .env already exists
copy=false
if [ ! -f .env ]; then
    echo -e "\e[92mCopying .env.example to .env \e[39m"
    cp .env.example .env
    copy=true
else
    echo -e "\e[92mUsing existing .env configuration \e[39m"
    copy=false
fi
echo ""
echo "=================================="
echo ""
echo ""
# Skip database credentials confirmation in non-interactive mode
if [ "$copy" = true ]; then
    echo -e "\e[92mPerfect let's continue with the setup\e[39m"
fi
echo ""
echo "=================================="
echo ""
echo ""
# Install laravel dependencies with composer
echo "🎬 DEV ---> COMPOSER INSTALL"
composer install
echo ""
echo "=================================="
echo ""
echo ""
# Generate larave key
echo "🎬 DEV ---> PHP ARTISAN KEY:GENERATE"
php artisan key:generate
echo ""
echo "=================================="
echo ""
echo ""
# Run database migrations
echo "🎬 DEV ---> php artisan migrate:fresh"
php artisan migrate:fresh
echo ""
echo ""
echo "=================================="
echo ""
echo ""
# Seeding database
echo "🎬 DEV ---> php artisan db:seed"
if ! php artisan db:seed; then
    echo "Database seeding failed."
    exit 1
fi
echo ""

  echo "🎬 DEV ---> Running PHPUnit tests"
  if ! php -d memory_limit=512M ./vendor/bin/phpunit; then
      echo "PHPUnit tests failed, but continuing..."
  fi
echo ""
echo "=================================="
echo ""
echo ""
# Run optimization commands for laravel
echo "🎬 DEV ---> php artisan optimize:clear"
php artisan optimize:clear
php artisan route:clear
echo ""
echo ""
echo "\e[92m==================================\e[39m"
echo "\e[92m============== DONE ==============\e[39m"
echo "\e[92m==================================\e[39m"
echo ""
echo ""
# Non-interactive mode: skip server start
echo -e "\e[92mSetup completed. You can start the server manually with: php artisan serve\e[39m"
