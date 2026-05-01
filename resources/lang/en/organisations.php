<?php

return [
    'messages' => [
        'membership_mode_updated' => 'Membership mode updated successfully.',
        'language_updated' => 'Default language set to :language. New sessions will use this language.',
    ],
    'errors' => [
        'membership_mode_rate_limit' => 'Too many attempts to change membership mode. Please wait before trying again.',
        'pending_fees_block' => 'Cannot switch to election-only mode while :count members have pending fees.',
        'confirmation_required' => 'You must confirm this change when members exist.',
    ],
];
