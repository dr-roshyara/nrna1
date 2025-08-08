# Standard import with confirmation
php artisan users:import user_info.csv

# Skip confirmation prompt
php artisan users:import user_info.csv --force

# Import different file
php artisan users:import voters_2024.csv