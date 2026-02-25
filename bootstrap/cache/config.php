<?php return array (
  4 => 'concurrency',
  'app' => 
  array (
    'name' => 'PUBLIC DIGIT',
    'env' => 'local',
    'debug' => true,
    'url' => 'https://publicdigit.com',
    'frontend_url' => 'http://localhost:3000',
    'asset_url' => NULL,
    'timezone' => 'UTC',
    'locale' => 'de',
    'fallback_locale' => 'de',
    'faker_locale' => 'en_US',
    'cipher' => 'AES-256-CBC',
    'key' => 'base64:kKQ+bo7cRHiUviC9hNkVPgE2pvrOf2C9RBzTi7gv4FM=',
    'previous_keys' => 
    array (
    ),
    'maintenance' => 
    array (
      'driver' => 'file',
      'store' => 'database',
    ),
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      15 => 'Illuminate\\Queue\\QueueServiceProvider',
      16 => 'Illuminate\\Redis\\RedisServiceProvider',
      17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'App\\Providers\\AppServiceProvider',
      23 => 'App\\Providers\\AuthServiceProvider',
      24 => 'App\\Providers\\EventServiceProvider',
      25 => 'Spatie\\Permission\\PermissionServiceProvider',
      26 => 'Laravel\\Socialite\\SocialiteServiceProvider',
      27 => 'App\\Providers\\RepositoryServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Arr' => 'Illuminate\\Support\\Arr',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'Date' => 'Illuminate\\Support\\Facades\\Date',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Http' => 'Illuminate\\Support\\Facades\\Http',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'RateLimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'Str' => 'Illuminate\\Support\\Str',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Socialite' => 'Laravel\\Socialite\\Facades\\Socialite',
    ),
    'logo' => '/images/logo-2.png',
    'max_use_clientIP' => '5',
    'select_all_required' => 'yes',
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'api' => 
      array (
        'driver' => 'token',
        'provider' => 'users',
        'hash' => false,
      ),
      'sanctum' => 
      array (
        'driver' => 'sanctum',
        'provider' => NULL,
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\User',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_resets',
        'expire' => 60,
        'throttle' => 60,
      ),
    ),
    'password_timeout' => 10800,
  ),
  'broadcasting' => 
  array (
    'default' => 'log',
    'connections' => 
    array (
      'reverb' => 
      array (
        'driver' => 'reverb',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
          'host' => NULL,
          'port' => 443,
          'scheme' => 'https',
          'useTLS' => true,
        ),
        'client_options' => 
        array (
        ),
      ),
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => '',
        'secret' => '',
        'app_id' => '',
        'options' => 
        array (
          'cluster' => 'mt1',
          'useTLS' => true,
        ),
      ),
      'ably' => 
      array (
        'driver' => 'ably',
        'key' => NULL,
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
    ),
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'array' => 
      array (
        'driver' => 'array',
        'serialize' => false,
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
        'lock_connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'cache',
        'lock_connection' => 'default',
      ),
      'dynamodb' => 
      array (
        'driver' => 'dynamodb',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'table' => 'cache',
        'endpoint' => NULL,
      ),
      'octane' => 
      array (
        'driver' => 'octane',
      ),
      'apc' => 
      array (
        'driver' => 'apc',
      ),
    ),
    'prefix' => 'public_digit_cache',
  ),
  'cors' => 
  array (
    'paths' => 
    array (
      0 => 'api/*',
      1 => 'sanctum/csrf-cookie',
    ),
    'allowed_methods' => 
    array (
      0 => '*',
    ),
    'allowed_origins' => 
    array (
      0 => '*',
    ),
    'allowed_origins_patterns' => 
    array (
    ),
    'allowed_headers' => 
    array (
      0 => '*',
    ),
    'exposed_headers' => 
    array (
    ),
    'max_age' => 0,
    'supports_credentials' => false,
  ),
  'database' => 
  array (
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'url' => NULL,
        'database' => 'nrna_de',
        'prefix' => '',
        'foreign_key_constraints' => true,
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'nrna_de',
        'username' => 'nrna',
        'password' => 'Nrna%2025%Germany',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'mariadb' => 
      array (
        'driver' => 'mariadb',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'nrna_de',
        'username' => 'nrna',
        'password' => 'Nrna%2025%Germany',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'nrna_de',
        'username' => 'nrna',
        'password' => 'Nrna%2025%Germany',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'schema' => 'public',
        'sslmode' => 'prefer',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'nrna_de',
        'username' => 'nrna',
        'password' => 'Nrna%2025%Germany',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
      ),
      'testing' => 
      array (
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'nrna_test',
        'username' => 'nrna',
        'password' => 'Nrna%2025%Germany',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'client' => 'phpredis',
      'options' => 
      array (
        'cluster' => 'redis',
        'prefix' => 'public_digit_database_',
      ),
      'default' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => '6379',
        'database' => '0',
      ),
      'cache' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => '6379',
        'database' => '1',
      ),
    ),
  ),
  'election' => 
  array (
    'is_active' => true,
    'voting_timeout_minutes' => '20',
    'allow_vote_verification' => true,
    'start_date' => '2026-08-10 00:00:00',
    'end_date' => '2026-08-31 23:59:59',
    'voting_time_in_minutes' => 20,
    'committee_permissions' => 
    array (
      0 => 'approve_voters',
      1 => 'manage_candidates',
      2 => 'view_results',
      3 => 'export_data',
    ),
    'max_candidates_per_post' => '1',
    'require_email_verification' => true,
    'use_slug_path' => true,
  ),
  'election_steps' => 
  array (
    1 => 'slug.code.create',
    2 => 'slug.code.agreement',
    3 => 'slug.vote.create',
    4 => 'slug.vote.verify',
    5 => 'slug.vote.complete',
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\app',
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\public',
        'url' => 'https://publicdigit.com/storage',
        'visibility' => 'public',
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'bucket' => '',
        'url' => NULL,
        'endpoint' => NULL,
        'use_path_style_endpoint' => false,
      ),
    ),
    'links' => 
    array (
      'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\public\\storage' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\app/public',
      'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\public\\images' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\app/public/images',
      'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\public\\pdffiles' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\app/public/pdfiles',
      'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\public\\profile-photos' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\app/public/profile-photos',
    ),
  ),
  'flare' => 
  array (
    'key' => NULL,
    'reporting' => 
    array (
      'anonymize_ips' => true,
      'collect_git_information' => false,
      'report_queries' => true,
      'maximum_number_of_collected_queries' => 200,
      'report_query_bindings' => true,
      'report_view_data' => true,
      'grouping_type' => NULL,
      'report_logs' => true,
      'maximum_number_of_collected_logs' => 200,
      'censor_request_body_fields' => 
      array (
        0 => 'password',
      ),
    ),
    'send_logs_as_events' => true,
    'censor_request_body_fields' => 
    array (
      0 => 'password',
    ),
  ),
  'fortify' => 
  array (
    'guard' => 'web',
    'passwords' => 'users',
    'username' => 'email',
    'email' => 'email',
    'home' => '/dashboard/roles',
    'prefix' => '',
    'domain' => NULL,
    'middleware' => 
    array (
      0 => 'web',
    ),
    'limiters' => 
    array (
      'login' => 'login',
      'two-factor' => 'two-factor',
    ),
    'views' => false,
    'features' => 
    array (
    ),
  ),
  'google-calendar' => 
  array (
    'default_auth_profile' => 'service_account',
    'auth_profiles' => 
    array (
      'service_account' => 
      array (
        'credentials_json' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\app/google-calendar/service-account-credentials.json',
      ),
      'oauth' => 
      array (
        'credentials_json' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\app/google-calendar/oauth-credentials.json',
        'token_json' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\app/google-calendar/oauth-token.json',
      ),
    ),
    'calendar_id' => NULL,
    'user_to_impersonate' => NULL,
  ),
  'hashing' => 
  array (
    'driver' => 'bcrypt',
    'bcrypt' => 
    array (
      'rounds' => 10,
    ),
    'argon' => 
    array (
      'memory' => 1024,
      'threads' => 2,
      'time' => 2,
    ),
    'rehash_on_login' => true,
  ),
  'image' => 
  array (
    'driver' => 'gd',
  ),
  'logging' => 
  array (
    'default' => 'stack',
    'deprecations' => 
    array (
      'channel' => 'null',
      'trace' => false,
    ),
    'channels' => 
    array (
      'stack' => 
      array (
        'driver' => 'stack',
        'channels' => 
        array (
          0 => 'single',
        ),
        'ignore_exceptions' => false,
      ),
      'single' => 
      array (
        'driver' => 'single',
        'path' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\logs/laravel.log',
        'level' => 'debug',
      ),
      'daily' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\logs/laravel.log',
        'level' => 'debug',
        'days' => 14,
      ),
      'slack' => 
      array (
        'driver' => 'slack',
        'url' => NULL,
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'debug',
      ),
      'papertrail' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\SyslogUdpHandler',
        'handler_with' => 
        array (
          'host' => NULL,
          'port' => NULL,
        ),
      ),
      'stderr' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\StreamHandler',
        'formatter' => NULL,
        'with' => 
        array (
          'stream' => 'php://stderr',
        ),
      ),
      'syslog' => 
      array (
        'driver' => 'syslog',
        'level' => 'debug',
      ),
      'errorlog' => 
      array (
        'driver' => 'errorlog',
        'level' => 'debug',
      ),
      'null' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\NullHandler',
      ),
      'emergency' => 
      array (
        'path' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\logs/laravel.log',
      ),
      'voting_audit' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\logs/voting_audit.log',
        'level' => 'debug',
        'days' => 90,
      ),
      'voting_security' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\logs/voting_security.log',
        'level' => 'debug',
        'days' => 365,
      ),
    ),
  ),
  'mail' => 
  array (
    'default' => 'smtp',
    'mailers' => 
    array (
      'smtp' => 
      array (
        'transport' => 'smtp',
        'host' => 'smtp.hostinger.com',
        'port' => '465',
        'encryption' => 'ssl',
        'username' => 'info@publicdigit.com',
        'password' => 'Rathaus#4!',
        'timeout' => NULL,
        'auth_mode' => NULL,
        'stream' => 
        array (
          'ssl' => 
          array (
            'allow_self_signed' => true,
            'verify_peer' => false,
            'verify_peer_name' => false,
          ),
        ),
      ),
      'ses' => 
      array (
        'transport' => 'ses',
      ),
      'postmark' => 
      array (
        'transport' => 'postmark',
      ),
      'resend' => 
      array (
        'transport' => 'resend',
      ),
      'sendmail' => 
      array (
        'transport' => 'sendmail',
        'path' => '/usr/sbin/sendmail -bs',
      ),
      'log' => 
      array (
        'transport' => 'log',
        'channel' => NULL,
      ),
      'array' => 
      array (
        'transport' => 'array',
      ),
      'failover' => 
      array (
        'transport' => 'failover',
        'mailers' => 
        array (
          0 => 'smtp',
          1 => 'log',
        ),
      ),
      'roundrobin' => 
      array (
        'transport' => 'roundrobin',
        'mailers' => 
        array (
          0 => 'ses',
          1 => 'postmark',
        ),
      ),
      'mailgun' => 
      array (
        'transport' => 'mailgun',
      ),
    ),
    'from' => 
    array (
      'address' => 'info@publicdigit.com',
      'name' => 'Public Digit',
    ),
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\resources\\views/vendor/mail',
      ),
    ),
  ),
  'meta' => 
  array (
    'title' => 'PUBLIC DIGIT - Secure Digital Voting Platform for Nepali Diaspora',
    'title_separator' => ' | ',
    'site_name' => 'Public Digit',
    'description' => 'Public Digit is a secure digital voting platform for the Nepali Diaspora worldwide. Empowering democratic participation with transparent, secure, and accessible online elections for Non-Resident Nepali Association (NRNA) members globally.',
    'keywords' => 'Public Digit, Digital Voting, Online Election, NRNA, Non Resident Nepali Association, Nepali Diaspora, Nepalese Abroad, Secure Voting Platform, Democratic Elections, Nepal Community, NRN Voting, Electronic Voting System, Nepali Network',
    'content_language' => 'en',
    'locale' => 'en_US',
    'alternate_locales' => 
    array (
      0 => 'de_DE',
      1 => 'ne_NP',
    ),
    'og_type' => 'website',
    'og_title' => 'Public Digit - Secure Digital Voting Platform',
    'og_description' => 'Empowering democratic participation for the Nepali Diaspora with secure, transparent digital voting. Join thousands of NRNA members participating in elections worldwide.',
    'og_url' => 'https://publicdigit.com',
    'og_site_name' => 'Public Digit',
    'og_locale' => 'en_US',
    'og_image' => 'https://publicdigit.com/images/og-image.jpg',
    'og_image_width' => '1200',
    'og_image_height' => '630',
    'og_image_alt' => 'Public Digit - Secure Digital Voting Platform',
    'twitter_card' => 'summary_large_image',
    'twitter_site' => '@publicdigit',
    'twitter_creator' => '@publicdigit',
    'twitter_title' => 'Public Digit - Secure Digital Voting',
    'twitter_description' => 'Secure digital voting platform for Nepali Diaspora. Participate in NRNA elections from anywhere in the world.',
    'twitter_image' => 'https://publicdigit.com/images/twitter-card.jpg',
    'author' => 'Public Digit Team',
    'publisher' => 'Public Digit',
    'copyright' => 'Copyright © 2026 Public Digit. All rights reserved.',
    'canonical' => 'https://publicdigit.com',
    'robots' => 'noindex, nofollow',
    'googlebot' => 'index, follow',
    'theme_color' => '#1e40af',
    'mobile_app_capable' => 'yes',
    'organization' => 
    array (
      'name' => 'Public Digit',
      'legal_name' => 'Public Digit Technology Solutions',
      'url' => 'https://publicdigit.com',
      'logo' => 'https://publicdigit.com/images/logo.png',
      'founding_date' => '2020',
      'email' => 'info@publicdigit.com',
      'address' => 
      array (
        'street_address' => '',
        'address_locality' => 'Frankfurt',
        'address_region' => 'Hessen',
        'postal_code' => '',
        'address_country' => 'DE',
      ),
      'same_as' => 
      array (
        0 => 'https://www.facebook.com/publicdigit',
        1 => 'https://twitter.com/publicdigit',
        2 => 'https://www.linkedin.com/company/publicdigit',
      ),
    ),
    'google_verification' => '',
    'bing_verification' => '',
    'yandex_verification' => '',
    'google_analytics_id' => 'GTM-MH39X8L',
    'facebook_pixel_id' => '',
  ),
  'permission' => 
  array (
    'models' => 
    array (
      'permission' => 'Spatie\\Permission\\Models\\Permission',
      'role' => 'Spatie\\Permission\\Models\\Role',
    ),
    'table_names' => 
    array (
      'roles' => 'roles',
      'permissions' => 'permissions',
      'model_has_permissions' => 'model_has_permissions',
      'model_has_roles' => 'model_has_roles',
      'role_has_permissions' => 'role_has_permissions',
    ),
    'column_names' => 
    array (
      'model_morph_key' => 'model_id',
    ),
    'register_permission_check_method' => true,
    'register_octane_reset_listener' => false,
    'teams' => false,
    'team_resolver' => 'Spatie\\Permission\\DefaultTeamResolver',
    'use_passport_client_credentials' => false,
    'display_permission_in_exception' => false,
    'display_role_in_exception' => false,
    'enable_wildcard_permission' => false,
    'cache' => 
    array (
      'expiration_time' => 
      \DateInterval::__set_state(array(
         'from_string' => true,
         'date_string' => '8 hours',
      )),
      'key' => 'spatie.permission.cache',
      'model_key' => 'name',
      'store' => 'default',
    ),
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
        'after_commit' => false,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => 0,
        'after_commit' => false,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => '',
        'secret' => '',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'default',
        'suffix' => NULL,
        'region' => 'us-east-1',
        'after_commit' => false,
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => NULL,
        'after_commit' => false,
      ),
    ),
    'batching' => 
    array (
      'database' => 'mysql',
      'table' => 'job_batches',
    ),
    'failed' => 
    array (
      'driver' => 'database-uuids',
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'sanctum' => 
  array (
    'stateful' => 
    array (
      0 => 'publicdigit.com',
    ),
    'guard' => 
    array (
      0 => 'web',
    ),
    'expiration' => NULL,
    'token_prefix' => '',
    'middleware' => 
    array (
      'verify_csrf_token' => 'App\\Http\\Middleware\\VerifyCsrfToken',
      'encrypt_cookies' => 'App\\Http\\Middleware\\EncryptCookies',
    ),
  ),
  'services' => 
  array (
    'postmark' => 
    array (
      'token' => NULL,
    ),
    'ses' => 
    array (
      'key' => '',
      'secret' => '',
      'region' => 'us-east-1',
    ),
    'resend' => 
    array (
      'key' => NULL,
    ),
    'slack' => 
    array (
      'notifications' => 
      array (
        'bot_user_oauth_token' => NULL,
        'channel' => NULL,
      ),
    ),
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
      'endpoint' => 'api.mailgun.net',
    ),
    'google' => 
    array (
      'client_id' => NULL,
      'client_secret' => NULL,
      'redirect' => NULL,
      'redirect_uri' => NULL,
      'webhook_uri' => NULL,
      'scopes' => 
      array (
        0 => 'https://www.googleapis.com/auth/userinfo.email',
        1 => 'https://www.googleapis.com/auth/calendar',
      ),
      'approval_prompt' => 'force',
      'access_type' => 'offline',
      'include_granted_scopes' => true,
    ),
    'facebook' => 
    array (
      'client_id' => NULL,
      'client_secret' => NULL,
      'redirect' => NULL,
    ),
  ),
  'session' => 
  array (
    'driver' => 'database',
    'lifetime' => '120',
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'public_digit_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => true,
    'http_only' => true,
    'same_site' => 'lax',
    'partitioned' => false,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\resources\\views',
    ),
    'compiled' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\framework\\views',
  ),
  'voting' => 
  array (
    'two_codes_system' => '0',
    'is_strict' => false,
  ),
  'voting_security' => 
  array (
    'control_ip_address' => '1',
    'ip_validation_mode' => 'strict',
    'ip_mismatch_action' => 'block',
    'logging' => 
    array (
      'enabled' => true,
      'log_successful_matches' => false,
      'log_mismatches' => true,
      'log_bypassed_checks' => false,
    ),
    'messages' => 
    array (
      'ip_mismatch_english' => 'You can only vote from your registered IP address. Your current IP does not match.',
      'ip_mismatch_nepali' => 'तपाईं आफ्नो दर्ता गरिएको IP ठेगानाबाट मात्र मतदान गर्न सक्नुहुन्छ। तपाईंको हालको IP मेल खाँदैन।',
      'contact_support' => 'If you believe this is an error, please contact the election committee.',
    ),
    'trust_proxies' => false,
  ),
  'concurrency' => 
  array (
    'default' => 'process',
  ),
  'inertia' => 
  array (
    'ssr' => 
    array (
      'enabled' => true,
      'url' => 'http://127.0.0.1:13714',
    ),
    'testing' => 
    array (
      'ensure_pages_exist' => true,
      'page_paths' => 
      array (
        0 => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\resources\\js/Pages',
      ),
      'page_extensions' => 
      array (
        0 => 'js',
        1 => 'jsx',
        2 => 'svelte',
        3 => 'ts',
        4 => 'tsx',
        5 => 'vue',
      ),
    ),
    'history' => 
    array (
      'encrypt' => false,
    ),
  ),
  'query-builder' => 
  array (
    'parameters' => 
    array (
      'include' => 'include',
      'filter' => 'filter',
      'sort' => 'sort',
      'fields' => 'fields',
      'append' => 'append',
    ),
    'count_suffix' => 'Count',
    'exists_suffix' => 'Exists',
    'disable_invalid_filter_query_exception' => false,
    'disable_invalid_sort_query_exception' => false,
    'disable_invalid_includes_query_exception' => false,
    'convert_relation_names_to_snake_case_plural' => true,
    'convert_relation_table_name_strategy' => false,
    'convert_field_names_to_snake_case' => false,
  ),
  'tinker' => 
  array (
    'commands' => 
    array (
    ),
    'alias' => 
    array (
    ),
    'dont_alias' => 
    array (
      0 => 'App\\Nova',
    ),
  ),
);
