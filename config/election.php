<?php

// Create this file: config/election.php

return [
    /*
    |--------------------------------------------------------------------------
    | Election System Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the election system.
    |
    */

    // ✅ Is the election system currently active?
    'is_active' => env('ELECTION_IS_ACTIVE', true),

    // ✅ Are election results published?
    'results_published' => env('ELECTION_RESULTS_PUBLISHED', false),

    // ✅ Voting session timeout (in minutes)
    'voting_timeout_minutes' => env('ELECTION_VOTING_TIMEOUT', 20),

    // ✅ Allow voters to view their votes after voting?
    'allow_vote_verification' => env('ELECTION_ALLOW_VOTE_VERIFICATION', true),

    // ✅ Election dates
    'start_date' => env('ELECTION_START_DATE', null),
    'end_date' => env('ELECTION_END_DATE', null),

    // ✅ Committee permissions
    'committee_permissions' => [
        'approve_voters',
        'manage_candidates', 
        'view_results',
        'export_data'
    ],

    // ✅ Voting rules
    'max_candidates_per_post' => env('ELECTION_MAX_CANDIDATES_PER_POST', 1),
    'require_email_verification' => env('ELECTION_REQUIRE_EMAIL_VERIFICATION', true),
];