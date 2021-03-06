<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // 'google' => [
    //     // 'client_id' => '242995957239-eqjp3enak044ij7jifgalvqsm4739otv.apps.googleusercontent.com',//'871065955282-eqbg9o2n96947qcj9r84mk36jje9fsh6.apps.googleusercontent.com',
    //     // 'client_secret' => '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',//'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',
    //     /***
    //      * client id for server
    //         GOOGLE_CLIENT_ID='617960766981-fdvvu501pu3ddld6d4ujkvvmus9cqo2q.apps.googleusercontent.com'
    //         GOOGLE_CLIENT_SECRET='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'
    //     **/
    //     'client_id'=> env('GOOGLE_CLIENT_ID'),
    //     'client_secret' => env('GOOGLE_CLIENT_SECRET'),

    //     // 'redirect' => 'http://127.0.0.1:8000/login/google/callback',
    //     // 'redirect' => 'https://nrna.eu/login/google/callback',
    //     'redirect'=>env('GOOGLE_REDIRECT')
    // ],
    'google' => [
        // Our Google API credentials.
        'client_id'=> env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'=>env('GOOGLE_REDIRECT'),

        // The URL to redirect to after the OAuth process.
        'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
        // The URL that listens to Google webhook notifications (Part 3).
        'webhook_uri' => env('GOOGLE_WEBHOOK_URI'),

        // Let the user know what we will be using from his Google account.
        'scopes' => [
            // Getting access to the user's email.
            \Google_Service_Oauth2::USERINFO_EMAIL,

            // Managing the user's calendars and events.
            \Google_Service_Calendar::CALENDAR,
        ],

        // Enables automatic token refresh.
        'approval_prompt' => 'force',
        'access_type' => 'offline',

        // Enables incremental scopes (useful if in the future we need access to another type of data).
        'include_granted_scopes' => true,
    ],
    'facebook' =>[
        'client_id'  => env('FACEBOOK_ID'),
        'client_secret'=>env('FACEBOOK_SECRET_KEY'),
        'redirect'=>env('FACEBOOK_REDIRECT')
    ],


];
