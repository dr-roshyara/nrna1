<?php

return [
    'hero' => [
        'title' => 'Enterprise-Grade Security Architecture',
        'subtitle' => 'Five independent security layers protect every vote from click to count',
        'promise' => 'Your vote is anonymous. Your election is secure. Your results are verifiable.',
    ],

    'layers' => [
        'layer1' => [
            'title' => 'Request Validation',
            'description' => 'Verifies voting link exists, belongs to user, and is active',
        ],
        'layer2' => [
            'title' => 'Temporal Validation',
            'description' => 'Checks session expiration and election timing',
        ],
        'layer3' => [
            'title' => 'Golden Rule Enforcement',
            'description' => 'Ensures voter belongs to correct organization',
        ],
        'layer4' => [
            'title' => 'Business Logic',
            'description' => 'Validates voting rules (one person, one vote)',
        ],
        'layer5' => [
            'title' => 'Data Persistence',
            'description' => 'Stores votes anonymously with cryptographic proof',
        ],
    ],

    'pillars' => [
        'anonymity' => [
            'title' => 'Complete Anonymity',
            'description' => 'No voter IDs stored with votes. Votes are completely anonymous while still being verifiable.',
        ],
        'verification' => [
            'title' => 'Cryptographic Verification',
            'description' => 'SHA256 hashes prove votes were counted without revealing voter identity.',
        ],
        'isolation' => [
            'title' => 'Multi-Tenant Isolation',
            'description' => 'Each organization\'s data is completely separated and independent.',
        ],
    ],

    'faq' => [
        'question1' => [
            'question' => 'How do you keep my vote anonymous?',
            'answer' => 'We don\'t store any voter identification with your vote. Our votes table has no user_id column. Votes contain only candidate selections and are identified only by cryptographic hashes, making voter-vote linkage mathematically impossible.',
        ],
        'question2' => [
            'question' => 'What prevents someone from voting twice?',
            'answer' => 'Our system enforces "one person, one vote" through multiple layers: voting codes are one-time use only, voting sessions are tied to specific users, and voting history is tracked. No code can be used twice.',
        ],
        'question3' => [
            'question' => 'Can organizations see each other\'s data?',
            'answer' => 'No. Each organization\'s data is completely isolated at the database level. Organizations cannot cross-query or access other organizations\' elections, voters, or results under any circumstances.',
        ],
        'question4' => [
            'question' => 'How can I verify my vote was counted?',
            'answer' => 'You receive a unique cryptographic hash of your vote immediately after voting. You can verify this hash is included in the final results without revealing your vote contents to anyone else.',
        ],
    ],

    'cta' => [
        'demo' => 'Start Secure Election',
        'whitepaper' => 'View Security Whitepaper',
    ],

    'badges' => [
        'security_tests' => '36 Security Tests',
        'anonymity' => '100% Anonymous',
        'protection' => '3-Layer Protection',
        'coverage' => '94% Test Coverage',
    ],
];
