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

        'demo.result' => [
            'title'       => 'Demo Election Results | Public Digit',
            'description' => 'View comprehensive results from the Public Digit demo election — vote counts, candidate rankings, and full voting statistics.',
            'keywords'    => 'demo election results, online voting results, candidate ranking, voting statistics',
            'robots'      => 'noindex, nofollow',
        ],

        'vereinswahlen' => [
            'title'       => 'Digital Online Elections for Associations | Public Digit',
            'description' => 'The platform for digital online elections for associations: ✓ Secret board elections ✓ Online voting ✓ Hybrid general meetings ✓ GDPR compliant ✓ End-to-end encrypted.',
            'keywords'    => 'digital elections for associations, online board election, association voting, hybrid general meeting, GDPR voting',
            'robots'      => 'index, follow',
        ],

        'hybrid' => [
            'title'       => 'Hybrid Elections for General Meetings | Public Digit',
            'description' => 'Combine in-person and online voting: Ideal for mixed general meetings with local and remote participants. Includes authentication and vote counting.',
            'keywords'    => 'hybrid elections, mixed general meeting, remote voting, in-person voting, online meeting',
            'robots'      => 'index, follow',
        ],

        'sicherheit' => [
            'title'       => 'Secure Online Elections with End-to-End Encryption | Public Digit',
            'description' => 'Bank-level security for your elections: End-to-end encryption, anonymous voting, tamper-proof audit logs, and GDPR compliance.',
            'keywords'    => 'election security, end-to-end encryption, anonymous voting, tamper-proof, GDPR elections',
            'robots'      => 'index, follow',
        ],

        'organisation-create-tutorial' => [
            'title'       => 'How to Create an Organisation with Geographic Scope | Public Digit',
            'description' => 'Complete step-by-step tutorial on setting up your organisation in Public Digit. Learn about committee structures, geographic scope configuration, and membership systems. Perfect for first-time users.',
            'keywords'    => 'create organisation, organisation setup, geographic scope, committee structure, online voting setup, tutorial, guide',
            'robots'      => 'index, follow',
        ],

        'governance-levels-tutorial' => [
            'title'       => 'Governance Levels Explained | Committee Hierarchy Guide | Public Digit',
            'description' => 'Learn what governance levels are, why they matter for your organisation, and how to configure committee hierarchies with geographic scopes. Includes real-world examples from NRNA, SPD, and more.',
            'keywords'    => 'governance levels, committee hierarchy, organisational structure, geographic scope, committee management, governance tutorial, NRNA governance',
            'robots'      => 'index, follow',
        ],

        'election-architecture' => [
            'title'       => 'Election Architecture & State Machine | Public Digit',
            'description' => 'Explore Public Digit\'s 5-phase election lifecycle: a tamper-proof state machine guaranteeing transparent, verifiable elections with immutable audit trails and cryptographic verification.',
            'keywords'    => 'election architecture, state machine, election lifecycle, tamper-proof elections, voting system architecture, digital election platform',
            'robots'      => 'index, follow',
        ],

        'election-security' => [
            'title'       => 'Election Security & State Machine Technology | Public Digit',
            'description' => 'Learn how our tamper-proof state machine guarantees election integrity through immutable audit trails, cryptographic verification, and verifiable results.',
            'keywords'    => 'election security, state machine, tamper-proof, audit trail, cryptographic, voting integrity',
            'robots'      => 'index, follow',
        ],

        'votingSecurity' => [
            'title'       => 'Five-Layer Security Architecture for Secure Online Voting | Public Digit',
            'description' => 'Conduct secure elections with Public Digit\'s five-layer security architecture. Complete voter anonymity, cryptographic verification, device fingerprinting, and multi-tenant isolation. GDPR-compliant end-to-end encrypted voting.',
            'keywords'    => 'secure online voting, election security, cryptographic voting, device fingerprinting, voter anonymity, secure elections online, multi-layer security, voting platform security',
            'robots'      => 'index, follow',
        ],

        'committee_tutorial' => [
            'title'       => 'Committee Management Tutorial | Public Digit',
            'description' => 'Learn how to create, edit, manage members, and remove members from committees. Step-by-step guide for democratic organizations.',
            'keywords'    => 'committee management, tutorial, guide, democratic governance, elections',
            'robots'      => 'index, follow',
        ],

        'demo-guide' => [
            'title'       => 'Online Voting Demo Guide — Public & Organisation Demo | Public Digit',
            'description' => 'Try our secure anonymous online election demo in 5 steps. No registration needed for the public demo. Organisation demo sends your code by email. 100% GDPR compliant, fully anonymous, verifiable.',
            'keywords'    => 'online voting demo, demo election, public demo election, organisation voting demo, secure online voting, anonymous voting system, how to vote online, election verification, vote anonymously, online election demo free, test online election, demo voting platform, secure election software',
            'robots'      => 'index, follow',
        ],

        'tutorials.election-settings' => [
            'title'       => 'Election Setup Guide - Configure Your Online Election | Public Digit',
            'description' => 'Learn how to configure every election setting on Public Digit: IP restrictions, ballot options, selection constraints, voter verification, and more. Step-by-step admin guide.',
            'keywords'    => 'election settings, online election setup, IP restriction voting, voter verification, ballot configuration, election administration, Public Digit guide',
            'robots'      => 'index, follow',
        ],

        'tutorials.membership-modes' => [
            'title'       => 'Membership Modes Guide - Full vs Election-Only | Public Digit',
            'description' => 'Understand Full Membership and Election-Only modes for your organisation. Learn when to use each mode, how they differ, and how to switch between them without losing data.',
            'keywords'    => 'membership modes, full membership, election-only voting, voter eligibility, membership requirements, election setup, organisation settings',
            'robots'      => 'index, follow',
        ],

        'elections.voters.import.tutorial' => [
            'title'       => 'Voter Import Tutorial & Complete Guide | Public Digit',
            'description' => 'Master voter importing for elections on Public Digit. Learn Full Membership vs Election-Only modes, CSV formats, bulk registration, step-by-step import process, and auto-create voter accounts.',
            'keywords'    => 'voter import, election voters, CSV import, bulk voter registration, voter management, election setup, import guide, membership modes, voter eligibility',
            'robots'      => 'index, follow',
        ],

        'organisations.settings' => [
            'title'       => 'Organisation Settings - {organisationName} | Public Digit',
            'description' => 'Configure membership mode, election settings, and organisation preferences for {organisationName}. Manage voter eligibility rules, Full Membership vs Election-Only mode, and member count: {memberCount} members.',
            'keywords'    => 'organisation settings, membership mode, full membership, election-only, voter eligibility, election configuration, member management, election security',
            'robots'      => 'index, follow',
        ],

        'newsletter-guide' => [
            'title'       => 'Complete Newsletter Guide for Organizations | Public Digit',
            'description' => 'Master newsletter campaigns for your organization. Learn audience segmentation, email composition, delivery tracking, and best practices for member and voter communication with Public Digit.',
            'keywords'    => 'newsletter, organization email, audience segmentation, email campaigns, member communication, election messaging, bulk email, email marketing',
            'robots'      => 'index, follow',
        ],

        'tutorials.voters-management' => [
            'title'       => 'Voters Management Guide - Complete Tutorial | Public Digit',
            'description' => 'Learn how to manage voters on Public Digit. Step-by-step guide for adding, importing, and managing voter rolls for your elections.',
            'keywords'    => 'voters management, voter rolls, voter import, election voters, member management, tutorial, guide',
            'robots'      => 'index, follow',
        ],

        // Thought-leadership articles — informational SEO targeting diaspora election governance
        'who-watch-the-watchmen' => [
            'title'       => 'Election Auditing for Diaspora Organisations | Public Digit',
            'description' => 'Who watches the watchmen? A clear look at independent election oversight and auditing for diaspora organisations that run secure online elections.',
            'keywords'    => 'election auditing, online elections for diaspora organisations, diaspora voting platform, independent election oversight, secure online voting, verifiable elections, NRNA elections, online election software for diaspora',
            'robots'      => 'index, follow',
        ],

        'ddd-article-part-one' => [
            'title'       => 'Building Trustworthy Online Elections | Public Digit',
            'description' => 'The question that changed everything: how a diaspora organisation rethought secure, verifiable online elections and the architecture of digital trust.',
            'keywords'    => 'online elections for diaspora organisations, diaspora voting platform, secure online voting, verifiable elections, online election software, NRNA elections, digital democracy',
            'robots'      => 'index, follow',
        ],
    ],
];
