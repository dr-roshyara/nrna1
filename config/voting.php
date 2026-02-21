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
];
