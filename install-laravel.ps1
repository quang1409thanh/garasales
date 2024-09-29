# Bật tính năng dừng script khi có lỗi
$ErrorActionPreference = "Stop"

# Bước 1: Install Composer Packages
Write-Host "Installing Composer dependencies..."
composer install

# Bước 2: Copy .env file
Write-Host "Copying .env file..."
Copy-Item ".env.example" ".env"

# Bước 3: Generate app key
Write-Host "Generating app key..."
php artisan key:generate

# Bước 4: Setting up database credentials
Write-Host "Setting up your database credentials in the .env file..."
# Bạn có thể thêm logic để tự động thay đổi file .env theo cấu hình database mong muốn

# Bước 5: Seed Database
Write-Host "Migrating and seeding database..."
php artisan migrate:fresh --seed

# Bước 6: Create storage link
Write-Host "Creating storage link..."
php artisan storage:link

# Bước 7: Install NPM dependencies
Write-Host "Installing NPM dependencies..."
npm install
npm run dev

# Bước 8: Run the Laravel server
Write-Host "Starting Laravel development server..."
php artisan serve

Write-Host "Laravel setup completed!"
