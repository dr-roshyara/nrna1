<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notification Channels
    |--------------------------------------------------------------------------
    |
    | Define which channels fire for each membership lifecycle event.
    | Supported: "mail", "database"
    |
    */
    'notifications' => [
        'application_submitted' => ['mail', 'database'],
        'application_approved'  => ['mail'],
        'application_rejected'  => ['mail'],
        'renewal_reminder'      => ['mail'],
        'payment_confirmation'  => ['mail'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Grace Period
    |--------------------------------------------------------------------------
    |
    | Days after membership_expires_at during which members remain eligible
    | to participate in elections. Beyond this window they are ineligible.
    |
    */
    'grace_period_days' => (int) env('MEMBERSHIP_GRACE_PERIOD_DAYS', 30),

    /*
    |--------------------------------------------------------------------------
    | Self-Renewal Window
    |--------------------------------------------------------------------------
    |
    | Members may self-renew up to this many days after their expiry date.
    | After this window, only an admin can renew the membership.
    |
    */
    'self_renewal_window_days' => (int) env('MEMBERSHIP_SELF_RENEWAL_WINDOW_DAYS', 90),

    /*
    |--------------------------------------------------------------------------
    | Application Expiry
    |--------------------------------------------------------------------------
    |
    | Pending applications are auto-rejected after this many days if not
    | reviewed. The daily ProcessMembershipExpiryJob enforces this.
    |
    */
    'application_expiry_days' => (int) env('MEMBERSHIP_APPLICATION_EXPIRY_DAYS', 30),

];
