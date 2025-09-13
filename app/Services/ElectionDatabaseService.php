<?php

namespace App\Services;

use App\Models\Election;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Complete ElectionDatabaseService with ALL election tables
 * Based on actual NRNA election system table structures
 */
class ElectionDatabaseService
{
    /**
     * Get COMPLETE table creation SQL for election databases
     * Based on your actual table structures
     */
    private static function getElectionTableSchemas(): array
    {
        return [
            // 1. USERS TABLE - Complete with all your fields
            'users' => "
                CREATE TABLE `users` (
                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                    `user_id` varchar(255) DEFAULT NULL,
                    `facebook_id` varchar(255) DEFAULT NULL,
                    `name` varchar(255) NOT NULL,
                    `email` varchar(255) NOT NULL,
                    `email_verified_at` timestamp NULL DEFAULT NULL,
                    `password` varchar(255) NOT NULL,
                    `two_factor_secret` text,
                    `two_factor_recovery_codes` text,
                    `remember_token` varchar(100) DEFAULT NULL,
                    `voting_ip` varchar(255) DEFAULT NULL,
                    `current_team_id` bigint unsigned DEFAULT NULL,
                    `profile_photo_path` varchar(2048) DEFAULT NULL,
                    `profile_bg_photo_path` varchar(255) DEFAULT NULL,
                    `profile_icon_photo_path` varchar(255) DEFAULT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    `first_name` varchar(255) DEFAULT NULL,
                    `middle_name` varchar(255) DEFAULT NULL,
                    `last_name` varchar(255) DEFAULT NULL,
                    `gender` varchar(255) DEFAULT NULL,
                    `region` varchar(255) DEFAULT NULL,
                    `country` varchar(255) DEFAULT NULL,
                    `state` varchar(255) DEFAULT NULL,
                    `street` varchar(255) DEFAULT NULL,
                    `housenumber` varchar(255) DEFAULT NULL,
                    `postalcode` varchar(255) DEFAULT NULL,
                    `city` varchar(255) DEFAULT NULL,
                    `additional_address` varchar(255) DEFAULT NULL,
                    `nrna_id` varchar(255) DEFAULT NULL,
                    `telephone` varchar(255) DEFAULT NULL,
                    `is_voter` tinyint(1) NOT NULL DEFAULT '0',
                    `name_prefex` varchar(255) DEFAULT NULL,
                    `approvedBy` varchar(255) DEFAULT NULL,
                    `approved_at` timestamp NULL DEFAULT NULL,
                    `suspendedBy` varchar(255) DEFAULT NULL,
                    `suspended_at` timestamp NULL DEFAULT NULL,
                    `has_candidacy` tinyint(1) NOT NULL DEFAULT '0',
                    `lcc` varchar(255) DEFAULT NULL,
                    `designation` varchar(255) DEFAULT NULL,
                    `google_id` varchar(255) DEFAULT NULL,
                    `social_id` varchar(255) DEFAULT NULL,
                    `social_type` varchar(255) DEFAULT NULL,
                    `is_committee_member` tinyint(1) NOT NULL DEFAULT '0',
                    `committee_name` varchar(255) DEFAULT NULL,
                    `user_ip` varchar(255) DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `users_user_id_unique` (`user_id`),
                    UNIQUE KEY `users_facebook_id_unique` (`facebook_id`),
                    UNIQUE KEY `users_email_unique` (`email`),
                    UNIQUE KEY `users_profile_bg_photo_path_unique` (`profile_bg_photo_path`),
                    UNIQUE KEY `users_profile_icon_photo_path_unique` (`profile_icon_photo_path`),
                    UNIQUE KEY `users_nrna_id_unique` (`nrna_id`),
                    UNIQUE KEY `users_telephone_unique` (`telephone`),
                    UNIQUE KEY `users_google_id_unique` (`google_id`),
                    KEY `users_is_voter_index` (`is_voter`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                COMMENT='Election-specific user records with complete profile data'
            ",
            
            // 2. CANDIDACIES TABLE - Your actual structure
            'candidacies' => "
                CREATE TABLE `candidacies` (
                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                    `candidacy_id` varchar(255) NOT NULL,
                    `user_id` varchar(255) NOT NULL,
                    `post_id` varchar(255) NOT NULL,
                    `proposer_id` varchar(255) DEFAULT NULL,
                    `supporter_id` varchar(255) DEFAULT NULL,
                    `image_path_1` varchar(255) DEFAULT NULL,
                    `image_path_2` varchar(255) DEFAULT NULL,
                    `image_path_3` varchar(255) DEFAULT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `candidacies_candidacy_id_unique` (`candidacy_id`),
                    UNIQUE KEY `candidacies_user_id_unique` (`user_id`),
                    UNIQUE KEY `candidacies_proposer_id_unique` (`proposer_id`),
                    UNIQUE KEY `candidacies_supporter_id_unique` (`supporter_id`),
                    KEY `candidacies_post_id_index` (`post_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                COMMENT='Election candidates with proposer/supporter system'
            ",
            
            // 3. POSTS TABLE - Your actual structure  
            'posts' => "
                CREATE TABLE `posts` (
                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                    `post_id` varchar(255) NOT NULL,
                    `name` varchar(255) NOT NULL,
                    `nepali_name` varchar(255) NOT NULL,
                    `is_national_wide` tinyint(1) NOT NULL DEFAULT '1',
                    `state_name` varchar(255) DEFAULT NULL,
                    `required_number` int NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `posts_post_id_unique` (`post_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                COMMENT='Election positions/posts'
            ",
            
            // 4. CODES TABLE - Your comprehensive voting code system
            'codes' => "
                CREATE TABLE `codes` (
                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                    `session_name` varchar(255) DEFAULT NULL,
                    `user_id` bigint unsigned NOT NULL,
                    `code1` varchar(255) DEFAULT NULL,
                    `code2` varchar(255) DEFAULT NULL,
                    `vote_show_code` varchar(255) DEFAULT NULL,
                    `is_code1_usable` tinyint(1) NOT NULL DEFAULT '0',
                    `code1_sent_at` timestamp NULL DEFAULT NULL,
                    `is_code2_usable` tinyint(1) NOT NULL DEFAULT '0',
                    `code2_sent_at` timestamp NULL DEFAULT NULL,
                    `can_vote` tinyint(1) NOT NULL DEFAULT '0',
                    `can_vote_now` tinyint(1) NOT NULL DEFAULT '0',
                    `has_voted` tinyint(1) NOT NULL DEFAULT '0',
                    `voting_time_in_minutes` int unsigned DEFAULT NULL,
                    `vote_last_seen` date DEFAULT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    `vote_completed_at` datetime DEFAULT NULL,
                    `code1_used_at` timestamp NULL DEFAULT NULL,
                    `code2_used_at` timestamp NULL DEFAULT NULL,
                    `vote_submitted` tinyint(1) NOT NULL DEFAULT '0',
                    `vote_submitted_at` timestamp NULL DEFAULT NULL,
                    `has_code1_sent` tinyint(1) NOT NULL DEFAULT '0',
                    `has_code2_sent` tinyint(1) NOT NULL DEFAULT '0',
                    `client_ip` varchar(255) NOT NULL,
                    `has_agreed_to_vote` tinyint(1) NOT NULL DEFAULT '0',
                    `has_agreed_to_vote_at` timestamp NULL DEFAULT NULL,
                    `voting_started_at` timestamp NULL DEFAULT NULL,
                    `has_used_code1` tinyint(1) NOT NULL DEFAULT '0',
                    `has_used_code2` tinyint NOT NULL DEFAULT '0',
                    `is_codemodel_valid` tinyint(1) DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `codes_user_id_foreign` (`user_id`),
                    CONSTRAINT `codes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                COMMENT='Comprehensive voting code and session management'
            ",
            
            // 5. RESULTS TABLE - Vote results tracking
            'results' => "
                CREATE TABLE `results` (
                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                    `vote_id` bigint unsigned NOT NULL,
                    `post_id` varchar(255) NOT NULL,
                    `candidacy_id` varchar(255) NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `results_vote_id_foreign` (`vote_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                COMMENT='Vote results processing and storage'
            ",
            
            // 6. VOTES TABLE - Your comprehensive 60-candidate system
            'votes' => "
                CREATE TABLE `votes` (
                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                    `no_vote_option` tinyint(1) NOT NULL DEFAULT '0',
                    `voting_code` varchar(255) NOT NULL,
                    `vote_session_name` varchar(255) DEFAULT NULL,
                    `country` varchar(255) DEFAULT NULL,
                    `region` varchar(255) DEFAULT NULL,
                    `city` varchar(255) DEFAULT NULL,
                    `candidate_01` json DEFAULT NULL,
                    `candidate_02` json DEFAULT NULL,
                    `candidate_03` json DEFAULT NULL,
                    `candidate_04` json DEFAULT NULL,
                    `candidate_05` json DEFAULT NULL,
                    `candidate_06` json DEFAULT NULL,
                    `candidate_07` json DEFAULT NULL,
                    `candidate_08` json DEFAULT NULL,
                    `candidate_09` json DEFAULT NULL,
                    `candidate_10` json DEFAULT NULL,
                    `candidate_11` json DEFAULT NULL,
                    `candidate_12` json DEFAULT NULL,
                    `candidate_13` json DEFAULT NULL,
                    `candidate_14` json DEFAULT NULL,
                    `candidate_15` json DEFAULT NULL,
                    `candidate_16` json DEFAULT NULL,
                    `candidate_17` json DEFAULT NULL,
                    `candidate_18` json DEFAULT NULL,
                    `candidate_19` json DEFAULT NULL,
                    `candidate_20` json DEFAULT NULL,
                    `candidate_21` json DEFAULT NULL,
                    `candidate_22` json DEFAULT NULL,
                    `candidate_23` json DEFAULT NULL,
                    `candidate_24` json DEFAULT NULL,
                    `candidate_25` json DEFAULT NULL,
                    `candidate_26` json DEFAULT NULL,
                    `candidate_27` json DEFAULT NULL,
                    `candidate_28` json DEFAULT NULL,
                    `candidate_29` json DEFAULT NULL,
                    `candidate_30` json DEFAULT NULL,
                    `candidate_31` json DEFAULT NULL,
                    `candidate_32` json DEFAULT NULL,
                    `candidate_33` json DEFAULT NULL,
                    `candidate_34` json DEFAULT NULL,
                    `candidate_35` json DEFAULT NULL,
                    `candidate_36` json DEFAULT NULL,
                    `candidate_37` json DEFAULT NULL,
                    `candidate_38` json DEFAULT NULL,
                    `candidate_39` json DEFAULT NULL,
                    `candidate_40` json DEFAULT NULL,
                    `candidate_41` json DEFAULT NULL,
                    `candidate_42` json DEFAULT NULL,
                    `candidate_43` json DEFAULT NULL,
                    `candidate_44` json DEFAULT NULL,
                    `candidate_45` json DEFAULT NULL,
                    `candidate_46` json DEFAULT NULL,
                    `candidate_47` json DEFAULT NULL,
                    `candidate_48` json DEFAULT NULL,
                    `candidate_49` json DEFAULT NULL,
                    `candidate_50` json DEFAULT NULL,
                    `candidate_51` json DEFAULT NULL,
                    `candidate_52` json DEFAULT NULL,
                    `candidate_53` json DEFAULT NULL,
                    `candidate_54` json DEFAULT NULL,
                    `candidate_55` json DEFAULT NULL,
                    `candidate_56` json DEFAULT NULL,
                    `candidate_57` json DEFAULT NULL,
                    `candidate_58` json DEFAULT NULL,
                    `candidate_59` json DEFAULT NULL,
                    `candidate_60` json DEFAULT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                COMMENT='Vote storage with 60 candidate positions'
            ",
            
            // 7. PUBLISHERS TABLE - Result authorization system
            'publishers' => "
                CREATE TABLE `publishers` (
                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                    `publisher_id` varchar(255) NOT NULL,
                    `user_id` bigint unsigned NOT NULL,
                    `name` varchar(255) NOT NULL,
                    `title` varchar(255) NOT NULL,
                    `should_agree` tinyint(1) NOT NULL DEFAULT '1',
                    `authorization_password` varchar(255) NOT NULL,
                    `is_active` tinyint(1) NOT NULL DEFAULT '1',
                    `priority_order` int NOT NULL DEFAULT '1',
                    `notes` text,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    `agreed` tinyint(1) NOT NULL DEFAULT '0',
                    `agreed_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `publishers_publisher_id_unique` (`publisher_id`),
                    KEY `publishers_user_id_foreign` (`user_id`),
                    KEY `publishers_should_agree_index` (`should_agree`),
                    KEY `publishers_priority_order_index` (`priority_order`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                COMMENT='Result publishers for authorization system'
            ",
            
            // 8. ROLES TABLE - Permission system
            'roles' => "
                CREATE TABLE `roles` (
                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                    `name` varchar(255) NOT NULL,
                    `guard_name` varchar(255) NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `roles_name_index` (`name`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                COMMENT='User roles for permission system'
            ",
            
            // 9. PERMISSIONS TABLE (need to add this)
            'permissions' => "
                CREATE TABLE `permissions` (
                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                    `name` varchar(255) NOT NULL,
                    `guard_name` varchar(255) NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                COMMENT='System permissions'
            ",
            
            // 10. ROLE_HAS_PERMISSIONS TABLE - Role permission mappings
            'role_has_permissions' => "
                CREATE TABLE `role_has_permissions` (
                    `permission_id` bigint unsigned NOT NULL,
                    `role_id` bigint unsigned NOT NULL,
                    PRIMARY KEY (`permission_id`,`role_id`),
                    KEY `role_has_permissions_role_id_foreign` (`role_id`),
                    CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
                    CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                COMMENT='Role to permission mappings'
            ",
            
            // 11. MODEL_HAS_ROLES TABLE (need to add this)
            'model_has_roles' => "
                CREATE TABLE `model_has_roles` (
                    `role_id` bigint unsigned NOT NULL,
                    `model_type` varchar(255) NOT NULL,
                    `model_id` bigint unsigned NOT NULL,
                    PRIMARY KEY (`role_id`,`model_id`,`model_type`),
                    KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
                    CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                COMMENT='User to role assignments'
            ",
            
            // 12. SESSIONS TABLE - Session management
            'sessions' => "
                CREATE TABLE `sessions` (
                    `id` varchar(255) NOT NULL,
                    `user_id` bigint unsigned DEFAULT NULL,
                    `ip_address` varchar(255) DEFAULT NULL,
                    `user_agent` longtext,
                    `payload` longtext NOT NULL,
                    `last_activity` int NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `sessions_user_id_index` (`user_id`),
                    KEY `sessions_last_activity_index` (`last_activity`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                COMMENT='User sessions for election'
            ",
            
            // 13. RESULT_AUTHORIZATIONS TABLE - Result authorization tracking
            'result_authorizations' => "
                CREATE TABLE `result_authorizations` (
                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                    `election_id` bigint unsigned NOT NULL,
                    `publisher_id` bigint unsigned NOT NULL,
                    `authorization_session_id` varchar(255) NOT NULL,
                    `agreed` tinyint(1) NOT NULL DEFAULT '0',
                    `agreed_at` timestamp NULL DEFAULT NULL,
                    `password_verified` tinyint(1) NOT NULL DEFAULT '0',
                    `ip_address` varchar(255) DEFAULT NULL,
                    `user_agent` varchar(255) DEFAULT NULL,
                    `concerns` text,
                    `is_valid` tinyint(1) NOT NULL DEFAULT '1',
                    `expires_at` timestamp NULL DEFAULT NULL,
                    `verification_data` json DEFAULT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `result_authorizations_election_id_foreign` (`election_id`),
                    KEY `result_authorizations_publisher_id_foreign` (`publisher_id`),
                    KEY `result_authorizations_authorization_session_id_index` (`authorization_session_id`),
                    KEY `result_authorizations_agreed_at_index` (`agreed_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                COMMENT='Publisher authorization tracking for results'
            ",
            
            // 14. SETTINGS TABLE - Election-specific settings
            'settings' => "
                CREATE TABLE `settings` (
                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                    `key` varchar(255) NOT NULL,
                    `value` text,
                    `description` varchar(255) DEFAULT NULL,
                    `type` varchar(255) NOT NULL DEFAULT 'string',
                    `is_public` tinyint(1) NOT NULL DEFAULT '0',
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `settings_key_unique` (`key`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                COMMENT='Election-specific configuration settings'
            "
        ];
    }
    
    /**
     * Your existing methods remain exactly the same
     * Just call this method for complete table schemas
     */
    public static function createElectionDatabase(Election $election): array
    {
        try {
            $databaseName = self::generateElectionDatabaseName($election);
            
            // Create the database
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // Update election with database info
            $election->update([
                'database_name' => $databaseName,
                'database_host' => config('database.connections.mysql.host'),
                'database_port' => config('database.connections.mysql.port'),
                'database_username' => config('database.connections.mysql.username'),
                'database_connection_name' => "election_{$election->id}"
            ]);
            
            // Register the connection
            $connectionName = self::registerElectionConnection($election);
            
            // Setup ALL tables (now includes all 14 tables)
            self::setupElectionDatabaseSchema($connectionName);
            
            // Add default data for new election
            self::seedElectionDefaults($connectionName);
            
            Log::info("Complete election database created successfully", [
                'election_id' => $election->id,
                'database_name' => $databaseName,
                'connection_name' => $connectionName,
                'total_tables' => count(self::getElectionTableSchemas())
            ]);
            
            return [
                'success' => true,
                'database_name' => $databaseName,
                'connection_name' => $connectionName,
                'message' => "Complete election database created with " . count(self::getElectionTableSchemas()) . " tables",
                'tables_created' => array_keys(self::getElectionTableSchemas())
            ];
            
        } catch (Exception $e) {
            Log::error("Failed to create complete election database", [
                'election_id' => $election->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => "Failed to create complete election database"
            ];
        }
    }
    
    /**
     * Setup database schema for election with ALL tables
     */
    private static function setupElectionDatabaseSchema(string $connectionName): void
    {
        $tables = self::getElectionTableSchemas();
        
        foreach ($tables as $tableName => $sql) {
            try {
                DB::connection($connectionName)->statement($sql);
                Log::info("Created table in election database", [
                    'connection' => $connectionName,
                    'table' => $tableName
                ]);
            } catch (Exception $e) {
                Log::error("Failed to create table in election database", [
                    'connection' => $connectionName,
                    'table' => $tableName,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        }
    }
    
    /**
     * Seed default data for new election database
     */
    private static function seedElectionDefaults(string $connectionName): void
    {
        try {
            // Insert default roles
            $defaultRoles = [
                ['name' => 'admin', 'guard_name' => 'web'],
                ['name' => 'committee', 'guard_name' => 'web'],
                ['name' => 'voter', 'guard_name' => 'web'],
                ['name' => 'candidate', 'guard_name' => 'web'],
                ['name' => 'publisher', 'guard_name' => 'web']
            ];
            
            foreach ($defaultRoles as $role) {
                DB::connection($connectionName)->table('roles')->insert(array_merge($role, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
            
            // Insert default permissions
            $defaultPermissions = [
                ['name' => 'manage_users', 'guard_name' => 'web'],
                ['name' => 'approve_voters', 'guard_name' => 'web'],
                ['name' => 'manage_candidates', 'guard_name' => 'web'],
                ['name' => 'view_results', 'guard_name' => 'web'],
                ['name' => 'publish_results', 'guard_name' => 'web'],
                ['name' => 'vote', 'guard_name' => 'web']
            ];
            
            foreach ($defaultPermissions as $permission) {
                DB::connection($connectionName)->table('permissions')->insert(array_merge($permission, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
            
            // Insert default settings
            $defaultSettings = [
                ['key' => 'election_name', 'value' => 'NRNA Election', 'type' => 'string', 'is_public' => 1],
                ['key' => 'voting_time_limit', 'value' => '20', 'type' => 'integer', 'is_public' => 0],
                ['key' => 'max_candidates_per_post', 'value' => '60', 'type' => 'integer', 'is_public' => 0],
                ['key' => 'require_voter_approval', 'value' => '1', 'type' => 'boolean', 'is_public' => 0]
            ];
            
            foreach ($defaultSettings as $setting) {
                DB::connection($connectionName)->table('settings')->insert(array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
            
            Log::info("Election database seeded with default data", [
                'connection' => $connectionName
            ]);
            
        } catch (Exception $e) {
            Log::error("Failed to seed election database defaults", [
                'connection' => $connectionName,
                'error' => $e->getMessage()
            ]);
            // Don't throw - seeding is optional
        }
    }
    
    /**
     * Get comprehensive database statistics for ALL tables
     */
    public static function getElectionDatabaseStats(Election $election): array
    {
        try {
            $connectionName = self::registerElectionConnection($election);
            
            $stats = [
                'connection_name' => $connectionName,
                'database_name' => $election->database_name,
                'is_connected' => self::testElectionDatabaseConnection($election),
                'tables' => [],
                'total_records' => 0,
                'table_count' => 0
            ];
            
            if ($stats['is_connected']) {
                $tables = array_keys(self::getElectionTableSchemas());
                
                foreach ($tables as $table) {
                    try {
                        $count = DB::connection($connectionName)->table($table)->count();
                        $stats['tables'][$table] = $count;
                        $stats['total_records'] += $count;
                        $stats['table_count']++;
                    } catch (Exception $e) {
                        $stats['tables'][$table] = 'Error: ' . $e->getMessage();
                    }
                }
                
                // Get vote statistics
                if (isset($stats['tables']['votes']) && is_numeric($stats['tables']['votes'])) {
                    $stats['vote_statistics'] = self::getVoteStatistics($connectionName);
                }
            }
            
            return $stats;
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage(),
                'is_connected' => false
            ];
        }
    }
    
    /**
     * Get detailed vote statistics
     */
    private static function getVoteStatistics(string $connectionName): array
    {
        try {
            $voteStats = [
                'total_votes' => 0,
                'candidates_with_votes' => 0,
                'popular_positions' => []
            ];
            
            $totalVotes = DB::connection($connectionName)->table('votes')->count();
            $voteStats['total_votes'] = $totalVotes;
            
            if ($totalVotes > 0) {
                // Count votes per candidate position
                for ($i = 1; $i <= 60; $i++) {
                    $candidateField = "candidate_" . str_pad($i, 2, '0', STR_PAD_LEFT);
                    $count = DB::connection($connectionName)
                        ->table('votes')
                        ->whereNotNull($candidateField)
                        ->where($candidateField, '!=', 'null')
                        ->count();
                        
                    if ($count > 0) {
                        $voteStats['candidates_with_votes']++;
                        $voteStats['popular_positions'][$candidateField] = $count;
                    }
                }
                
                // Sort by popularity
                arsort($voteStats['popular_positions']);
                $voteStats['popular_positions'] = array_slice($voteStats['popular_positions'], 0, 10, true);
            }
            
            return $voteStats;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
     /**
     * Register database connection for an election with enhanced configuration
     * 
     * @param Election $election
     * @return string
     */
    public static function registerElectionConnection(Election $election): string
    {
        $connectionName = "election_{$election->id}";
        
        // Skip if already registered and working
        if (self::isConnectionRegistered($connectionName) && self::testConnection($connectionName)) {
            return $connectionName;
        }

        // Enhanced database configuration
        $config = [
            'driver' => 'mysql',
            'host' => $election->database_host ?? config('database.connections.mysql.host'),
            'port' => $election->database_port ?? config('database.connections.mysql.port'),
            'database' => $election->database_name,
            'username' => $election->database_username ?? config('database.connections.mysql.username'),
            'password' => $election->database_password ?? config('database.connections.mysql.password'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
            // Enhanced connection options
            'options' => [
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_STRINGIFY_FETCHES => false,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_TIMEOUT => 30,
            ],
            // Connection pool settings
            'pool' => [
                'min_connections' => 1,
                'max_connections' => 10,
                'connect_timeout' => 10,
                'wait_timeout' => 3,
            ]
        ];
        
        // Register connection in Laravel's database manager
        Config::set("database.connections.{$connectionName}", $config);
        
        // Test the connection with retry logic
        $maxRetries = 3;
        $retryDelay = 1; // seconds
        
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                DB::connection($connectionName)->getPdo();
                
                Log::info("Election database connection registered successfully", [
                    'election_id' => $election->id,
                    'connection_name' => $connectionName,
                    'database' => $election->database_name,
                    'attempt' => $attempt
                ]);
                
                return $connectionName;
                
            } catch (Exception $e) {
                if ($attempt === $maxRetries) {
                    Log::error("Failed to connect to election database after {$maxRetries} attempts", [
                        'election_id' => $election->id,
                        'database' => $election->database_name,
                        'error' => $e->getMessage(),
                        'attempts' => $maxRetries
                    ]);
                    throw new Exception("Cannot connect to election database after {$maxRetries} attempts: {$e->getMessage()}");
                }
                
                Log::warning("Connection attempt {$attempt} failed, retrying...", [
                    'election_id' => $election->id,
                    'error' => $e->getMessage(),
                    'retry_delay' => $retryDelay
                ]);
                
                sleep($retryDelay);
                $retryDelay *= 2; // Exponential backoff
            }
        }
        
        return $connectionName;
    }
    
    /**
     * Enhanced database schema setup with proper error handling
     * 
     * @param string $connectionName
     * @return void
     */
    private static function setupElectionDatabaseSchema(string $connectionName): void
    {
        $tables = self::getElectionTableSchemas();
        $createdTables = [];
        
        try {
            DB::connection($connectionName)->transaction(function () use ($connectionName, $tables, &$createdTables) {
                foreach ($tables as $tableName => $sql) {
                    try {
                        DB::connection($connectionName)->statement($sql);
                        $createdTables[] = $tableName;
                        
                        Log::debug("Created table in election database", [
                            'connection' => $connectionName,
                            'table' => $tableName
                        ]);
                        
                    } catch (Exception $e) {
                        Log::error("Failed to create table in election database", [
                            'connection' => $connectionName,
                            'table' => $tableName,
                            'error' => $e->getMessage()
                        ]);
                        throw $e;
                    }
                }
                
                // Create additional indexes for performance
                self::createPerformanceIndexes($connectionName);
                
                // Create triggers if needed
                self::createDatabaseTriggers($connectionName);
            });
            
            Log::info("Election database schema setup completed", [
                'connection' => $connectionName,
                'tables_created' => $createdTables,
                'total_tables' => count($tables)
            ]);
            
        } catch (Exception $e) {
            Log::error("Database schema setup failed", [
                'connection' => $connectionName,
                'created_tables' => $createdTables,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

        /**
     * Create additional performance indexes
     * 
     * @param string $connectionName
     * @return void
     */
    private static function createPerformanceIndexes(string $connectionName): void
    {
        $indexes = [
            "CREATE INDEX `users_approval_status_index` ON `users` (`can_vote`, `approved_at`)",
            "CREATE INDEX `codes_voting_status_index` ON `codes` (`can_vote_now`, `has_voted`, `voting_started_at`)",
            "CREATE INDEX `votes_timeline_index` ON `votes` (`created_at`, `client_ip`)",
            "CREATE INDEX `candidacies_post_approval_index` ON `candidacies` (`post_id`, `is_approved`, `display_order`)"
        ];
        
        foreach ($indexes as $indexSql) {
            try {
                DB::connection($connectionName)->statement($indexSql);
            } catch (Exception $e) {
                // Index might already exist, log but don't fail
                Log::debug("Index creation skipped (might already exist)", [
                    'connection' => $connectionName,
                    'sql' => $indexSql,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
    
    /**
     * Create database triggers for audit and integrity
     * 
     * @param string $connectionName
     * @return void
     */
    private static function createDatabaseTriggers(string $connectionName): void
    {
        // Example: Update vote count when vote is inserted
        $triggers = [
            "
            CREATE TRIGGER `update_vote_count_after_insert` 
            AFTER INSERT ON `votes` 
            FOR EACH ROW 
            BEGIN 
                UPDATE `users` SET `updated_at` = NOW() WHERE `id` = NEW.user_id;
            END
            "
        ];
        
        foreach ($triggers as $triggerSql) {
            try {
                DB::connection($connectionName)->statement($triggerSql);
            } catch (Exception $e) {
                Log::debug("Trigger creation skipped", [
                    'connection' => $connectionName,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
    
    /**
     * Test election database connection with comprehensive checks
     * 
     * @param Election $election
     * @return bool
     */
    public static function testElectionDatabaseConnection(Election $election): bool
    {
        try {
            $connectionName = self::registerElectionConnection($election);
            
            // Test basic connection
            DB::connection($connectionName)->select('SELECT 1 as test');
            
            // Test table existence
            $tables = ['users', 'codes', 'votes', 'posts', 'candidacies'];
            foreach ($tables as $table) {
                DB::connection($connectionName)->select("SELECT 1 FROM {$table} LIMIT 1");
            }
            
            return true;
            
        } catch (Exception $e) {
            Log::warning("Election database connection test failed", [
                'election_id' => $election->id,
                'database_name' => $election->database_name,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Get comprehensive database statistics for an election
     * 
     * @param Election $election
     * @return array
     */
    public static function getElectionDatabaseStats(Election $election): array
    {
        try {
            $connectionName = self::registerElectionConnection($election);
            
            $stats = [
                'connection_name' => $connectionName,
                'database_name' => $election->database_name,
                'is_connected' => false,
                'tables' => [],
                'total_records' => 0,
                'database_size' => 0,
                'last_activity' => null
            ];
            
            // Test connection
            $stats['is_connected'] = self::testConnection($connectionName);
            
            if ($stats['is_connected']) {
                $tables = ['users', 'codes', 'votes', 'posts', 'candidacies'];
                
                foreach ($tables as $table) {
                    try {
                        $count = DB::connection($connectionName)->table($table)->count();
                        $stats['tables'][$table] = $count;
                        $stats['total_records'] += $count;
                        
                        // Get last activity for this table
                        if ($count > 0 && in_array($table, ['users', 'codes', 'votes'])) {
                            $lastUpdate = DB::connection($connectionName)
                                ->table($table)
                                ->max('updated_at');
                            
                            if ($lastUpdate && ($stats['last_activity'] === null || $lastUpdate > $stats['last_activity'])) {
                                $stats['last_activity'] = $lastUpdate;
                            }
                        }
                        
                    } catch (Exception $e) {
                        $stats['tables'][$table] = 'Error: ' . $e->getMessage();
                    }
                }
                
                // Get database size
                try {
                    $sizeQuery = "
                        SELECT 
                            ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS database_size_mb
                        FROM information_schema.tables 
                        WHERE table_schema = ?
                    ";
                    $result = DB::connection($connectionName)->select($sizeQuery, [$election->database_name]);
                    $stats['database_size'] = $result[0]->database_size_mb ?? 0;
                } catch (Exception $e) {
                    $stats['database_size'] = 'Unknown';
                }
            }
            
            return $stats;
            
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage(),
                'is_connected' => false,
                'election_id' => $election->id
            ];
        }
    }
    
    /**
     * Drop election database with enhanced safety checks
     * 
     * @param Election $election
     * @return array
     */
    public static function dropElectionDatabase(Election $election): array
    {
        try {
            // Safety checks
            if (!$election->database_name) {
                return [
                    'success' => false,
                    'error' => 'No database configured for this election',
                    'message' => 'Election does not have a database to drop'
                ];
            }
            
            // Additional safety: check if election is completed
            if (!in_array($election->status, ['completed', 'cancelled', 'archived'])) {
                return [
                    'success' => false,
                    'error' => 'Election is still active',
                    'message' => 'Cannot drop database for active election'
                ];
            }
            
            $databaseName = $election->database_name;
            $connectionName = "election_{$election->id}";
            
            // Backup before dropping (optional)
            $backupResult = self::createDatabaseBackup($election);
            
            // Drop the database
            DB::statement("DROP DATABASE IF EXISTS `{$databaseName}`");
            
            // Remove connection config
            Config::forget("database.connections.{$connectionName}");
            DB::purge($connectionName);
            
            // Clear database info from election
            $election->update([
                'database_name' => null,
                'database_host' => null,
                'database_port' => null,
                'database_username' => null,
                'database_password' => null,
                'database_connection_name' => null,
                'database_status' => 'dropped'
            ]);
            
            Log::warning("Election database dropped", [
                'election_id' => $election->id,
                'database_name' => $databaseName,
                'backup_created' => $backupResult['success'] ?? false
            ]);
            
            return [
                'success' => true,
                'database_name' => $databaseName,
                'message' => "Election database '{$databaseName}' dropped successfully",
                'backup_info' => $backupResult
            ];
            
        } catch (Exception $e) {
            Log::error("Failed to drop election database", [
                'election_id' => $election->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => "Failed to drop election database: {$e->getMessage()}"
            ];
        }
    }
    
    /**
     * Create database backup before dropping
     * 
     * @param Election $election
     * @return array
     */
    public static function createDatabaseBackup(Election $election): array
    {
        try {
            $backupPath = storage_path('app/election-backups');
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            $timestamp = now()->format('Y-m-d_H-i-s');
            $backupFile = "{$backupPath}/election_{$election->id}_{$timestamp}.sql";
            
            $command = sprintf(
                'mysqldump -h%s -P%s -u%s -p%s %s > %s',
                escapeshellarg($election->database_host ?? config('database.connections.mysql.host')),
                escapeshellarg($election->database_port ?? config('database.connections.mysql.port')),
                escapeshellarg($election->database_username ?? config('database.connections.mysql.username')),
                escapeshellarg($election->database_password ?? config('database.connections.mysql.password')),
                escapeshellarg($election->database_name),
                escapeshellarg($backupFile)
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && file_exists($backupFile)) {
                return [
                    'success' => true,
                    'backup_file' => $backupFile,
                    'file_size' => filesize($backupFile)
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Backup command failed',
                    'return_code' => $returnCode
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate database name for election with enhanced validation
     * 
     * @param Election $election
     * @return string
     */
    private static function generateElectionDatabaseName(Election $election): string
    {
        $year = date('Y');
        $constituency = strtolower($election->constituency ?? 'general');
        
        // Clean constituency name
        $constituency = preg_replace('/[^a-z0-9]/', '_', $constituency);
        $constituency = trim($constituency, '_');
        
        $name = "nrna_election_{$election->id}_{$year}_{$constituency}";
        
        // Ensure database name is valid MySQL identifier
        $name = preg_replace('/[^a-zA-Z0-9_]/', '_', $name);
        $name = substr($name, 0, 64); // MySQL database name limit
        
        return $name;
    }
    
    /**
     * Validate election is ready for database creation
     * 
     * @param Election $election
     * @return bool
     */
    private static function validateElectionForDatabase(Election $election): bool
    {
        // Election must have valid status
        if (!in_array($election->status, ['draft', 'active', 'setup'])) {
            return false;
        }
        
        // Election must not already have a database
        if ($election->database_name && $election->database_status === 'ready') {
            return false;
        }
        
        return true;
    }
    
    /**
     * Create physical database with proper error handling
     * 
     * @param string $databaseName
     * @return void
     */
    private static function createPhysicalDatabase(string $databaseName): void
    {
        $sql = "CREATE DATABASE IF NOT EXISTS `{$databaseName}` 
                CHARACTER SET utf8mb4 
                COLLATE utf8mb4_unicode_ci 
                COMMENT 'NRNA Election Database - Created on " . now()->toDateTimeString() . "'";
                
        DB::statement($sql);
    }
    
    /**
     * Update election with database configuration
     * 
     * @param Election $election
     * @param string $databaseName
     * @return void
     */
    private static function updateElectionDatabaseConfig(Election $election, string $databaseName): void
    {
        $election->update([
            'database_name' => $databaseName,
            'database_host' => config('database.connections.mysql.host'),
            'database_port' => config('database.connections.mysql.port'),
            'database_username' => config('database.connections.mysql.username'),
            'database_password' => config('database.connections.mysql.password'), // Consider encryption
            'database_connection_name' => "election_{$election->id}",
            'database_status' => 'created'
        ]);
    }
    
    /**
     * Validate database setup after creation
     * 
     * @param string $connectionName
     * @return bool
     */
    private static function validateDatabaseSetup(string $connectionName): bool
    {
        try {
            $requiredTables = ['users', 'codes', 'votes', 'posts', 'candidacies'];
            
            foreach ($requiredTables as $table) {
                $exists = DB::connection($connectionName)
                    ->select("SHOW TABLES LIKE '{$table}'");
                    
                if (empty($exists)) {
                    Log::error("Required table missing in election database", [
                        'connection' => $connectionName,
                        'table' => $table
                    ]);
                    return false;
                }
            }
            
            return true;
            
        } catch (Exception $e) {
            Log::error("Database validation failed", [
                'connection' => $connectionName,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Cleanup on failed database creation
     * 
     * @param Election $election
     * @return void
     */
    private static function cleanupFailedDatabaseCreation(Election $election): void
    {
        try {
            if ($election->database_name) {
                DB::statement("DROP DATABASE IF EXISTS `{$election->database_name}`");
            }
            
            $connectionName = "election_{$election->id}";
            Config::forget("database.connections.{$connectionName}");
            DB::purge($connectionName);
            
            $election->update([
                'database_name' => null,
                'database_status' => 'failed'
            ]);
            
        } catch (Exception $e) {
            Log::error("Cleanup after failed database creation also failed", [
                'election_id' => $election->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Test if connection is working
     * 
     * @param string $connectionName
     * @return bool
     */
    private static function testConnection(string $connectionName): bool
    {
        try {
            DB::connection($connectionName)->select('SELECT 1');
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Check if connection is already registered
     * 
     * @param string $connectionName
     * @return bool
     */
    private static function isConnectionRegistered(string $connectionName): bool
    {
        return Config::has("database.connections.{$connectionName}");
    }
    
    /**
     * Get all election databases
     * 
     * @return array
     */
    public static function getAllElectionDatabases(): array
    {
        try {
            $databases = DB::select('SHOW DATABASES');
            $electionDatabases = [];
            
            foreach ($databases as $db) {
                $dbName = $db->Database;
                if (str_starts_with($dbName, 'nrna_election_')) {
                    $electionDatabases[] = $dbName;
                }
            }
            
            return $electionDatabases;
            
        } catch (Exception $e) {
            Log::error("Failed to get election databases list", [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }
    
    /**
     * Cleanup orphaned election databases
     * 
     * @return array
     */
    public static function cleanupOrphanedDatabases(): array
    {
        try {
            $allElectionDbs = self::getAllElectionDatabases();
            $activeElections = Election::whereNotNull('database_name')
                ->pluck('database_name')
                ->toArray();
                
            $orphanedDbs = array_diff($allElectionDbs, $activeElections);
            $cleaned = [];
            
            foreach ($orphanedDbs as $dbName) {
                try {
                    DB::statement("DROP DATABASE IF EXISTS `{$dbName}`");
                    $cleaned[] = $dbName;
                    
                    Log::info("Cleaned up orphaned election database", [
                        'database_name' => $dbName
                    ]);
                    
                } catch (Exception $e) {
                    Log::error("Failed to cleanup orphaned database", [
                        'database_name' => $dbName,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            return [
                'success' => true,
                'cleaned_databases' => $cleaned,
                'total_cleaned' => count($cleaned)
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }


    // All your existing methods remain the same...
    // registerElectionConnection, testElectionDatabaseConnection, 
    // dropElectionDatabase, generateElectionDatabaseName, etc.
}