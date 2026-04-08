<?php return array (
  4 => 'concurrency',
  'app' => 
  array (
    'name' => 'PUBLIC DIGIT',
    'env' => 'local',
    'debug' => true,
    'url' => 'http://127.0.0.1:8000',
    'frontend_url' => 'http://localhost:3000',
    'asset_url' => NULL,
    'timezone' => 'UTC',
    'locale' => 'de',
    'fallback_locale' => 'de',
    'faker_locale' => 'en_US',
    'cipher' => 'AES-256-CBC',
    'key' => 'base64:m6bkNPNLHG8AV1rYe9+yMa++28V1gASx93I9RQU8bpw=',
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
      28 => 'App\\Providers\\SEOServiceProvider',
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
        'write' => 
        array (
          'host' => '127.0.0.1',
        ),
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
        'sticky' => true,
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
    'voting_time_in_minutes' => '30',
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
    'voter_cache_ttl' => 300,
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
        'url' => 'http://127.0.0.1:8000/storage',
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
      'private' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\app/private',
        'visibility' => 'private',
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
  'inertia' => 
  array (
    'ssr' => 
    array (
      'enabled' => false,
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
  'login-routing' => 
  array (
    'cache' => 
    array (
      'dashboard_resolution_ttl' => 300,
      'organisation_data_ttl' => 300,
      'voting_session_ttl' => 30,
      'cache_key_prefix' => 'dashboard_resolution:',
    ),
    'timeouts' => 
    array (
      'max_seconds' => 5,
      'query_max_seconds' => 2,
      'cache_max_seconds' => 1,
    ),
    'fallback' => 
    array (
      'max_failures_before_emergency' => 3,
      'alert_failures_per_hour' => 100,
      'use_static_html_fallback' => true,
      'log_fallback_usage' => true,
    ),
    'session' => 
    array (
      'freshness_threshold' => 60,
      'validate_freshness' => true,
      'activity_column' => 'last_activity_at',
    ),
    'analytics' => 
    array (
      'enabled' => true,
      'channel' => 'login',
      'track_queries' => false,
      'track_cache_metrics' => true,
      'performance_thresholds' => 
      array (
        'warning_ms' => 2000,
        'critical_ms' => 5000,
      ),
    ),
    'maintenance' => 
    array (
      'check_enabled' => true,
      'redirect_route' => 'maintenance',
      'allow_user_ids' => 
      array (
      ),
    ),
    'rate_limiting' => 
    array (
      'enabled' => true,
      'max_attempts' => 10,
      'window_minutes' => 60,
    ),
    'emergency_dashboard' => 
    array (
      'route' => 'dashboard.emergency',
      'show_basic_actions' => true,
      'cache_view' => true,
      'cache_ttl' => 600,
    ),
    'debug' => 
    array (
      'log_decisions' => false,
      'log_queries' => false,
      'log_cache' => false,
      'show_timings' => false,
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
  'membership' => 
  array (
    'notifications' => 
    array (
      'application_submitted' => 
      array (
        0 => 'mail',
        1 => 'database',
      ),
      'application_approved' => 
      array (
        0 => 'mail',
      ),
      'application_rejected' => 
      array (
        0 => 'mail',
      ),
      'renewal_reminder' => 
      array (
        0 => 'mail',
      ),
      'payment_confirmation' => 
      array (
        0 => 'mail',
      ),
    ),
    'grace_period_days' => 30,
    'self_renewal_window_days' => 90,
    'application_expiry_days' => 30,
  ),
  'meta' => 
  array (
    'title' => 'Public Digit | Secure Online Voting Platform',
    'title_separator' => ' | ',
    'site_name' => 'Public Digit',
    'description' => 'Secure, anonymous and verifiable online voting for organisations. GDPR compliant, end-to-end encrypted.',
    'keywords' => 'online voting, digital elections, secure voting, anonymous voting, GDPR voting',
    'author' => 'Public Digit',
    'robots' => 'index, follow',
    'googlebot' => 'index, follow',
    'content_language' => 'en',
    'og_type' => 'website',
    'og_url' => 'http://127.0.0.1:8000',
    'og_title' => 'Public Digit | Secure Online Voting',
    'og_description' => 'Secure, anonymous and verifiable online voting for organisations.',
    'og_image' => 'http://127.0.0.1:8000/images/og-home.jpg',
    'og_image_width' => '1200',
    'og_image_height' => '630',
    'og_image_alt' => 'Public Digit — Secure Online Voting Platform',
    'og_site_name' => 'Public Digit',
    'og_locale' => 'en_US',
    'twitter_card' => 'summary_large_image',
    'twitter_site' => '@publicdigit',
    'twitter_creator' => '@publicdigit',
    'twitter_title' => 'Public Digit | Secure Online Voting',
    'twitter_description' => 'Secure, anonymous and verifiable online voting for organisations.',
    'twitter_image' => 'http://127.0.0.1:8000/images/og-home.jpg',
    'theme_color' => '#4F46E5',
    'mobile_app_capable' => 'yes',
    'canonical' => 'http://127.0.0.1:8000',
    'publisher' => 'Public Digit',
    'google_verification' => '',
    'bing_verification' => '',
    'yandex_verification' => '',
    'site_url' => 'http://127.0.0.1:8000',
    'default_locale' => 'de',
    'supported_locales' => 
    array (
      0 => 'de',
      1 => 'en',
      2 => 'np',
    ),
    'og_locales' => 
    array (
      'de' => 'de_DE',
      'en' => 'en_US',
      'np' => 'ne_NP',
    ),
    'og' => 
    array (
      'type' => 'website',
      'image' => '/images/og-home.png',
      'site_name' => 'Public Digit',
      'width' => 1200,
      'height' => 630,
    ),
    'twitter' => 
    array (
      'card' => 'summary_large_image',
      'site' => '@publicdigit',
      'creator' => '@publicdigit',
      'image' => '/images/og-home.png',
    ),
    'images' => 
    array (
      'logo' => '/images/logo-2.png',
      'favicon' => '/images/favicon.ico',
      'og_default' => '/images/og-home.png',
    ),
    'cache' => 
    array (
      'enabled' => true,
      'ttl' => '3600',
      'key_prefix' => 'meta:',
    ),
    'performance' => 
    array (
      'log_slow_generation' => false,
      'slow_threshold_ms' => '200',
    ),
    'social' => 
    array (
      'facebook' => 
      array (
        'image' => '/images/social/fb-og.jpg',
        'image_width' => 1200,
        'image_height' => 630,
      ),
      'twitter' => 
      array (
        'image' => '/images/social/twitter-card.jpg',
        'image_width' => 800,
        'image_height' => 418,
      ),
      'linkedin' => 
      array (
        'image' => '/images/social/linkedin-og.jpg',
        'image_width' => 1200,
        'image_height' => 627,
      ),
      'whatsapp' => 
      array (
        'image' => '/images/social/whatsapp-share.jpg',
        'image_width' => 300,
        'image_height' => 300,
      ),
    ),
    'organisation' => 
    array (
      'name' => 'Public Digit',
      'legal_name' => 'Public Digit GmbH',
      'url' => 'http://127.0.0.1:8000',
      'logo' => 'http://127.0.0.1:8000/images/logo-2.png',
      'founding_date' => '2023',
      'email' => 'info@publicdigit.com',
      'address' => 
      array (
        'street_address' => '',
        'address_locality' => '',
        'address_region' => '',
        'postal_code' => '',
        'address_country' => 'DE',
      ),
      'same_as' => 
      array (
      ),
    ),
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
      0 => 'localhost',
      1 => '127.0.0.1',
      2 => 'publicdigit.com',
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
    'time_in_minutes' => 30,
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
  'excel' => 
  array (
    'exports' => 
    array (
      'chunk_size' => 1000,
      'pre_calculate_formulas' => false,
      'strict_null_comparison' => false,
      'csv' => 
      array (
        'delimiter' => ',',
        'enclosure' => '"',
        'line_ending' => '
',
        'use_bom' => false,
        'include_separator_line' => false,
        'excel_compatibility' => false,
        'output_encoding' => '',
        'test_auto_detect' => true,
      ),
      'properties' => 
      array (
        'creator' => '',
        'lastModifiedBy' => '',
        'title' => '',
        'description' => '',
        'subject' => '',
        'keywords' => '',
        'category' => '',
        'manager' => '',
        'company' => '',
      ),
    ),
    'imports' => 
    array (
      'read_only' => true,
      'ignore_empty' => false,
      'heading_row' => 
      array (
        'formatter' => 'slug',
      ),
      'csv' => 
      array (
        'delimiter' => NULL,
        'enclosure' => '"',
        'escape_character' => '\\',
        'contiguous' => false,
        'input_encoding' => 'guess',
      ),
      'properties' => 
      array (
        'creator' => '',
        'lastModifiedBy' => '',
        'title' => '',
        'description' => '',
        'subject' => '',
        'keywords' => '',
        'category' => '',
        'manager' => '',
        'company' => '',
      ),
      'cells' => 
      array (
        'middleware' => 
        array (
        ),
      ),
    ),
    'extension_detector' => 
    array (
      'xlsx' => 'Xlsx',
      'xlsm' => 'Xlsx',
      'xltx' => 'Xlsx',
      'xltm' => 'Xlsx',
      'xls' => 'Xls',
      'xlt' => 'Xls',
      'ods' => 'Ods',
      'ots' => 'Ods',
      'slk' => 'Slk',
      'xml' => 'Xml',
      'gnumeric' => 'Gnumeric',
      'htm' => 'Html',
      'html' => 'Html',
      'csv' => 'Csv',
      'tsv' => 'Csv',
      'pdf' => 'Dompdf',
    ),
    'value_binder' => 
    array (
      'default' => 'Maatwebsite\\Excel\\DefaultValueBinder',
    ),
    'cache' => 
    array (
      'driver' => 'memory',
      'batch' => 
      array (
        'memory_limit' => 60000,
      ),
      'illuminate' => 
      array (
        'store' => NULL,
      ),
      'default_ttl' => 10800,
    ),
    'transactions' => 
    array (
      'handler' => 'db',
      'db' => 
      array (
        'connection' => NULL,
      ),
    ),
    'temporary_files' => 
    array (
      'local_path' => 'C:\\Users\\nabra\\OneDrive\\Desktop\\roshyara\\xamp\\nrna\\nrna-eu\\storage\\framework/cache/laravel-excel',
      'local_permissions' => 
      array (
      ),
      'remote_disk' => NULL,
      'remote_prefix' => NULL,
      'force_resync_remote' => NULL,
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
