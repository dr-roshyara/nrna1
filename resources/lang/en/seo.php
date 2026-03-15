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
        'description' => 'Secure digital voting platform for diaspora communities, organisations, and NGOs worldwide. GDPR-compliant, end-to-end encrypted online elections.',
        'keywords' => 'online voting, digital elections, diaspora voting, NRNA elections, secure voting platform, electronic voting system',
    ],

    'pages' => [
        'home' => [
            'title' => 'Secure Digital Voting | Public Digit Elections',
            'description' => 'Empower your organisation with secure, transparent online voting. Public Digit offers GDPR-compliant elections for diaspora communities, NGOs, and membership organisations worldwide.',
            'keywords' => 'online voting, digital elections, secure voting, diaspora elections, NRNA',
        ],

        'pricing' => [
            'title' => 'Pricing Plans | Public Digit Elections',
            'description' => 'Transparent pricing for organisations of all sizes. Choose a plan that fits your election needs. No hidden fees, scalable solutions for NGOs and diaspora groups.',
            'keywords' => 'election pricing, voting software cost, online voting platform, election solution pricing',
        ],

        'organisations.show' => [
            'title' => '{organizationName} | Elections & Members | Public Digit',
            'description' => '{organizationName}: {memberCount} members, {electionCount} elections. Secure digital voting platform for organisations and diaspora communities.',
            'keywords' => '{organizationName}, elections, voting, digital democracy',
        ],

        'elections.index' => [
            'title' => 'Active Elections | Public Digit',
            'description' => 'Browse active elections across Public Digit platform. Participate in secure, transparent voting for organisations worldwide.',
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

        'login' => [
            'title'       => 'Sign In | Public Digit',
            'description' => 'Sign in to your Public Digit account to access your elections, vote, or manage your organisation.',
            'keywords'    => 'login, sign in, public digit account, voting platform login',
            'robots'      => 'index, follow',
        ],

        'register' => [
            'title'       => 'Create Account | Public Digit',
            'description' => 'Register for a Public Digit account to start using secure online voting for your organisation.',
            'keywords'    => 'register, create account, sign up, public digit registration',
            'robots'      => 'index, follow',
        ],

        'about' => [
            'title'       => 'About Public Digit | Secure Digital Voting Platform',
            'description' => 'Learn about Public Digit\'s mission to make digital democracy secure, transparent, and accessible for organisations, NGOs, and diaspora communities worldwide.',
            'keywords'    => 'about public digit, digital voting mission, secure elections platform, diaspora voting',
            'robots'      => 'index, follow',
        ],

        'faq' => [
            'title'       => 'FAQ | Frequently Asked Questions | Public Digit',
            'description' => 'Find answers to common questions about online voting, security, privacy, and how Public Digit works for your organisation.',
            'keywords'    => 'faq, frequently asked questions, online voting help, voting platform questions',
            'robots'      => 'index, follow',
        ],

        'security' => [
            'title'       => 'Secure & Anonymous Online Voting | Public Digit',
            'description' => 'Five-layer security architecture protecting your elections. Complete voter anonymity, cryptographic verification, and multi-tenant isolation for associations, NGOs, and organisations.',
            'keywords'    => 'secure online voting, anonymous voting, election security, digital voting platform, voter anonymity, GDPR voting',
            'robots'      => 'index, follow',
        ],

        'demo' => [
            'title'       => 'Try Demo Election | Public Digit',
            'description' => 'Experience secure online voting firsthand with our interactive demo election. No registration required.',
            'keywords'    => 'demo election, try voting, online voting demo, test election platform',
            'robots'      => 'index, follow',
        ],

        'dashboard' => [
            'title'       => 'Dashboard | Public Digit',
            'description' => 'Access your elections, voting activities, and account management from your personal dashboard.',
            'keywords'    => 'dashboard, my elections, voting dashboard',
            'robots'      => 'noindex, nofollow',
        ],

        'profile' => [
            'title'       => 'Your Profile | Public Digit',
            'description' => 'Manage your Public Digit account settings, notifications, and preferences.',
            'keywords'    => 'profile, account settings, user profile',
            'robots'      => 'noindex, nofollow',
        ],
    ],
];
