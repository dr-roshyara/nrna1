<?php return array (
  'App\\Providers\\EventServiceProvider' => 
  array (
    'Illuminate\\Auth\\Events\\Registered' => 
    array (
      0 => 'Illuminate\\Auth\\Listeners\\SendEmailVerificationNotification',
      1 => 'App\\Listeners\\CreateUserOrganisationRole',
    ),
    'App\\Events\\Event' => 
    array (
      0 => 'App\\Listeners\\EventListener',
    ),
    'App\\Events\\Membership\\MembershipApplicationApproved' => 
    array (
      0 => 'App\\Listeners\\InvalidateMembershipDashboardCache',
    ),
    'App\\Events\\Membership\\MembershipApplicationRejected' => 
    array (
      0 => 'App\\Listeners\\InvalidateMembershipDashboardCache',
    ),
    'App\\Events\\Membership\\MembershipFeePaid' => 
    array (
      0 => 'App\\Listeners\\InvalidateMembershipDashboardCache',
      1 => 'App\\Listeners\\Membership\\RecalculateMemberFeeStatus',
    ),
    'App\\Events\\Membership\\MembershipRenewed' => 
    array (
      0 => 'App\\Listeners\\InvalidateMembershipDashboardCache',
    ),
  ),
  'Illuminate\\Foundation\\Support\\Providers\\EventServiceProvider' => 
  array (
    'Illuminate\\Auth\\Events\\Registered' => 
    array (
      0 => 'App\\Listeners\\CreateUserOrganisationRole@handle',
    ),
    'App\\Events\\Event' => 
    array (
      0 => 'App\\Listeners\\EventListener@handle',
    ),
    'App\\Events\\Membership\\MembershipFeePaid' => 
    array (
      0 => 'App\\Listeners\\Membership\\RecalculateMemberFeeStatus@handle',
    ),
    'App\\Events\\Newsletter\\NewsletterEmailSent' => 
    array (
      0 => 'App\\Listeners\\Newsletter\\UpdateNewsletterCounters@handleSent',
    ),
    'App\\Events\\Newsletter\\NewsletterEmailFailed' => 
    array (
      0 => 'App\\Listeners\\Newsletter\\UpdateNewsletterCounters@handleFailed',
    ),
  ),
);