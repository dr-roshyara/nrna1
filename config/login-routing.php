<?php

/**
 * Login Routing Configuration
 *
 * Centralized configuration for post-login dashboard resolution,
 * caching, timeouts, and fallback behavior.
 */

return [
    /**
     * Cache Configuration
     * Controls TTL and invalidation for dashboard resolution results
     */
    'cache' => [
        /**
         * Dashboard resolution result TTL in seconds
         * How long to cache the resolved dashboard for a user
         */
        'dashboard_resolution_ttl' => env('LOGIN_CACHE_TTL', 300), // 5 minutes

        /**
         * Organisation data cache TTL in seconds
         * How long to cache organisation metadata and relationships
         */
        'organisation_data_ttl' => env('LOGIN_ORG_CACHE_TTL', 300), // 5 minutes

        /**
         * Voting session cache TTL in seconds
         * How long to cache active voting session data (shorter TTL for freshness)
         */
        'voting_session_ttl' => env('LOGIN_VOTING_CACHE_TTL', 30), // 30 seconds

        /**
         * Cache key prefix for dashboard resolution
         * Allows cache versioning and organization
         */
        'cache_key_prefix' => 'dashboard_resolution:',
    ],

    /**
     * Timeout Configuration
     * Controls maximum execution time and query time limits
     */
    'timeouts' => [
        /**
         * Maximum total resolution time in seconds
         * If resolution takes longer, triggers fallback
         */
        'max_seconds' => env('LOGIN_TIMEOUT_SECONDS', 5),

        /**
         * Maximum database query time in seconds
         * If any single query exceeds this, log warning and continue
         */
        'query_max_seconds' => env('LOGIN_QUERY_TIMEOUT_SECONDS', 2),

        /**
         * Maximum cache retrieval time in seconds
         * Cache layer timeout
         */
        'cache_max_seconds' => 1,
    ],

    /**
     * Fallback Configuration
     * Controls when and how to trigger emergency fallback behavior
     */
    'fallback' => [
        /**
         * Fallback threshold: how many consecutive failures before triggering emergency mode
         * Helps prevent cascade failures during outages
         */
        'max_failures_before_emergency' => env('LOGIN_FALLBACK_MAX_FAILURES', 3),

        /**
         * Alert threshold: number of failures per hour that triggers alert
         * Helps operations team identify systematic issues
         */
        'alert_failures_per_hour' => env('LOGIN_ALERT_THRESHOLD', 100),

        /**
         * Whether to use static HTML fallback (no database)
         * Very last resort when database is unreachable
         */
        'use_static_html_fallback' => env('LOGIN_USE_STATIC_FALLBACK', true),

        /**
         * Whether to log fallback usage to separate channel
         * Helps track fallback frequency
         */
        'log_fallback_usage' => true,
    ],

    /**
     * Session Configuration
     * Controls session freshness validation
     */
    'session' => [
        /**
         * Session freshness threshold in seconds
         * Cache is only valid if session activity is fresher than this
         * Prevents stale routing after role/org changes during session
         */
        'freshness_threshold' => env('LOGIN_SESSION_FRESHNESS', 60), // 1 minute

        /**
         * Enable session freshness validation
         * When enabled, cached routing is invalidated if user activity is too old
         */
        'validate_freshness' => true,

        /**
         * Session activity column name
         * Which database column tracks when session was last active
         */
        'activity_column' => 'last_activity_at',
    ],

    /**
     * Analytics Configuration
     * Controls event tracking and monitoring
     */
    'analytics' => [
        /**
         * Enable analytics tracking for login events
         * Logs all login routing decisions with metrics
         */
        'enabled' => env('LOGIN_ANALYTICS_ENABLED', true),

        /**
         * Analytics channel name
         * Where login events are logged
         */
        'channel' => env('LOGIN_ANALYTICS_CHANNEL', 'login'),

        /**
         * Track individual queries
         * Can be verbose - disable in high-traffic environments
         */
        'track_queries' => env('LOGIN_TRACK_QUERIES', false),

        /**
         * Track cache hits/misses
         * Helps identify cache effectiveness
         */
        'track_cache_metrics' => true,

        /**
         * Performance thresholds (milliseconds)
         * Values above these trigger warnings in logs
         */
        'performance_thresholds' => [
            'warning_ms' => 2000,    // 2 seconds
            'critical_ms' => 5000,   // 5 seconds
        ],
    ],

    /**
     * Maintenance Mode
     * Allows graceful handling during system maintenance
     */
    'maintenance' => [
        /**
         * Check for maintenance mode before routing
         * If enabled and app is in maintenance, redirect to maintenance page
         */
        'check_enabled' => true,

        /**
         * Maintenance mode route name
         * Where to redirect if app is in maintenance
         */
        'redirect_route' => 'maintenance',

        /**
         * Allow specific users to bypass maintenance mode
         * List of user IDs who can still login during maintenance
         */
        'allow_user_ids' => [],
    ],

    /**
     * Emergency Dashboard Configuration
     * Configuration for fallback emergency dashboard
     */
    'emergency_dashboard' => [
        /**
         * Route name for emergency dashboard
         * Last resort if normal dashboard resolution completely fails
         */
        'route' => 'dashboard.emergency',

        /**
         * Whether to show basic actions in emergency mode
         * Logout, organisation switcher, etc.
         */
        'show_basic_actions' => true,

        /**
         * Whether to cache emergency dashboard view
         * Reduces database load during outages
         */
        'cache_view' => true,

        /**
         * TTL for emergency dashboard cache
         */
        'cache_ttl' => env('LOGIN_EMERGENCY_CACHE_TTL', 600), // 10 minutes
    ],

    /**
     * Debug Configuration
     * Only for development - disable in production!
     */
    'debug' => [
        /**
         * Log all resolution decisions (verbose)
         * ONLY for development!
         */
        'log_decisions' => env('LOGIN_DEBUG_DECISIONS', false),

        /**
         * Log all queries executed during resolution
         * ONLY for development!
         */
        'log_queries' => env('LOGIN_DEBUG_QUERIES', false),

        /**
         * Log all cache operations
         * ONLY for development!
         */
        'log_cache' => env('LOGIN_DEBUG_CACHE', false),

        /**
         * Display timing information in responses
         * For performance debugging
         */
        'show_timings' => env('LOGIN_DEBUG_TIMINGS', false),
    ],
];
