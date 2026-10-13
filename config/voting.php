<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Voting System Configuration
    |--------------------------------------------------------------------------
    |
    | TWO_CODES_SYSTEM: 
    |   0 = Simple mode - Code1 used for both form access and vote verification
    |   1 = Strict mode - Code1 for form access, Code2 for vote verification
    |
    */
    'two_codes_system' => env('TWO_CODES_SYSTEM', 0),

    /*
    | Check if system is in strict mode
    */
    'is_strict' => env('TWO_CODES_SYSTEM', 0) == 1,

    /*
    |--------------------------------------------------------------------------
    | Voting Session Duration
    |--------------------------------------------------------------------------
    |
    | Controls how long a voting session remains valid (in minutes).
    | Applies to BOTH:
    |   - VoterSlug expires_at   (session window for the entire flow)
    |   - voting_time_in_minutes (window after code1 is verified)
    |
    | Set via: VOTING_TIME_IN_MINUTES=30 in .env
    |
    */
    // No (int) cast (PBDIGIT-274 S6, O-10 = B): the raw value reaches the strict rule, so '20min' or '5.9' fail
    // closed instead of being truncated. Read it through App\Services\Voting\VotingDuration::minutes().
    'time_in_minutes' => env('VOTING_TIME_IN_MINUTES', 30),

    /*
    |--------------------------------------------------------------------------
    | Per-IP vote limit (PBDIGIT-288, read through App\Services\Voting\IpVoteLimit)
    |--------------------------------------------------------------------------
    |
    | ipv6_vote_group_prefix: IPv6 addresses count together per network of this prefix (default /64; 1–128).
    | ip_lock_timeout_ms:     how long a vote waits for another vote from the same network to finish; after
    |                         that it is refused safely and may be retried.
    |
    */
    'ipv6_vote_group_prefix' => env('VOTING_IPV6_VOTE_GROUP_PREFIX', 64),
    'ip_lock_timeout_ms' => env('VOTING_IP_LOCK_TIMEOUT_MS', 5000),
];
