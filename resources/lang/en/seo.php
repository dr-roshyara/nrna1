<?php

/**
 * SEO Translations - English
 *
 * Used for server-side fallback meta tags in app.blade.php
 * These are also mirrored in resources/js/locales/en.json for client-side useMeta()
 *
 * Keep in sync with:
 * - resources/lang/de/seo.php (German)
 * - resources/lang/np/seo.php (Nepali)
 * - resources/js/locales/en.json (Vue i18n)
 * - resources/js/locales/de.json (Vue i18n)
 * - resources/js/locales/np.json (Vue i18n)
 */

return [
    'site' => [
        'title' => 'Public Digit',
        'description' => 'Secure digital voting platform for diaspora communities, organizations, and NGOs worldwide. GDPR-compliant, end-to-end encrypted online elections.',
        'keywords' => 'online voting, digital elections, diaspora voting, NRNA elections, secure voting platform, electronic voting system',
    ],

    'pages' => [
        'home' => [
            'title' => 'Secure Digital Voting | Public Digit Elections',
            'description' => 'Empower your organization with secure, transparent online voting. Public Digit offers GDPR-compliant elections for diaspora communities, NGOs, and membership organizations worldwide.',
            'keywords' => 'online voting, digital elections, secure voting, diaspora elections, NRNA',
        ],

        'pricing' => [
            'title' => 'Pricing Plans | Public Digit Elections',
            'description' => 'Transparent pricing for organizations of all sizes. Choose a plan that fits your election needs. No hidden fees, scalable solutions for NGOs and diaspora groups.',
            'keywords' => 'election pricing, voting software cost, online voting platform, election solution pricing',
        ],

        'organizations.show' => [
            'title' => '{organizationName} | Elections & Members | Public Digit',
            'description' => '{organizationName}: {memberCount} members, {electionCount} elections. Secure digital voting platform for organizations and diaspora communities.',
            'keywords' => '{organizationName}, elections, voting, digital democracy',
        ],

        'elections.index' => [
            'title' => 'Active Elections | Public Digit',
            'description' => 'Browse active elections across Public Digit platform. Participate in secure, transparent voting for organizations worldwide.',
            'keywords' => 'active elections, upcoming votes, election list, voting opportunities',
        ],

        'elections.show' => [
            'title' => '{electionName} | {organizationName} | Public Digit',
            'description' => 'Election information for {electionName} by {organizationName}. Secure, transparent voting platform with full audit trail.',
            'keywords' => '{electionName}, {organizationName}, voting, election results',
        ],

        'election.result' => [
            'title' => '{electionName} Results | Public Digit Elections',
            'description' => 'Final results for {electionName}. View election outcomes, candidate standings, and complete voting statistics.',
            'keywords' => '{electionName}, election results, voting results, election outcomes',
        ],
    ],
];
