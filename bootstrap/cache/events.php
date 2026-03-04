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
  ),
);