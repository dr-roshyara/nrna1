# Standard import with confirmation
php artisan users:import user_info.csv

# Skip confirmation prompt
php artisan users:import user_info.csv --force

# Import different file
php artisan users:import voters_2024.csv
# Default comma delimiter
php artisan users:import voter_list.csv

# Semicolon delimiter (like your original function)
php artisan users:import voter_list.csv --delimiter=; --inspect 

# Tab separated
php artisan users:import voter_list.csv --delimiter=tab