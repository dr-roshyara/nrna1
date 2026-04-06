<?php

return [
    'required' => 'This field is required',
    'email' => 'This field must be a valid email address',
    'string' => 'This field must be a string',
    'min' => 'This field must be at least :min characters',
    'max' => 'This field may not be greater than :max characters',
    'regex' => 'The :attribute format is invalid',
    'size' => 'This field must be exactly :size characters',
    'in' => 'The selected :attribute is invalid',
    'accepted' => 'The :attribute field must be accepted',
    'unique' => 'The :attribute has already been taken',
    'dns' => ':attribute is not a valid domain',

    'failed' => 'Validation failed',

    /*
    |--------------------------------------------------------------------------
    | Organization Validation Messages
    |--------------------------------------------------------------------------
    */
    'organization' => [
        'name' => [
            'required' => 'The organization name is required',
            'unique' => 'An organization with this name already exists',
            'min' => 'The organization name must be at least 3 characters',
            'max' => 'The organization name may not be greater than 255 characters',
        ],
        'email' => [
            'required' => 'The email address is required',
            'email' => 'Please enter a valid email address',
            'invalid' => 'The email address is invalid',
            'unique' => 'This email address is already registered',
        ],
        'zip' => [
            'regex' => 'The postal code must be a 5-digit number',
            'format' => 'The postal code must be a 5-digit number',
        ],
        'gdpr' => [
            'required' => 'You must consent to GDPR-compliant data processing',
        ],
        'terms' => [
            'required' => 'You must accept the terms of service',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Organisation-Specific Validation Messages (UK spelling)
    |--------------------------------------------------------------------------
    */
    'organisation' => [
        'name' => [
            'required' => 'The organisation name is required.',
            'unique' => 'An organisation with this name already exists.',
        ],
        'email' => [
            'required' => 'The email address is required.',
            'invalid' => 'The email address is invalid.',
            'unique' => 'An organisation with this email address already exists.',
        ],
        'zip' => [
            'format' => 'The postal code must be 5 digits.',
        ],
        'gdpr' => [
            'required' => 'You must accept GDPR-compliant data processing.',
        ],
        'terms' => [
            'required' => 'You must accept the terms and conditions.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */
    'attributes' => [
        'name' => 'Name',
        'email' => 'Email Address',
        'organization_name' => 'Organization Name',
        'organisation_name' => 'Organisation Name',
        'organization_email' => 'Organization Email',
        'organisation_email' => 'Organisation Email',
        'street' => 'Street Address',
        'city' => 'City',
        'zip_code' => 'Postal Code',
        'country' => 'Country',
        'representative_name' => 'Representative Name',
        'representative_role' => 'Representative Role',
    ],
];