#!/bin/bash

# Install Composer Packages
echo "Installing Composer dependencies..."
composer install

# Copy .env file
echo "Copying .env file..."
cp .env.example .env

# Generate app key
echo "Generating app key..."
php artisan key:generate

# Set up database credentials
echo "Setting up your database credentials in the .env file..."
# Manual edit may be required here

# Migrate and seed database
echo "Migrating and seeding database..."
php artisan migrate:fresh --seed

# Create storage link
echo "Creating storage link..."
php artisan storage:link

# Install NPM dependencies
echo "Installing NPM dependencies..."
npm install
npm run dev

# Run the Laravel server
echo "Starting Laravel development server..."
php artisan serve
