<?php

/*
 * PBDIGIT-345 task 3: where organisation databases live (D-222; delta §3.3, §3.9).
 * A registry locator names a CLUSTER; this maps the name to its host and port. Logins are not here: each locator
 * holds a reference ("env:NAME") resolved from real environment variables NAME_USERNAME and NAME_PASSWORD.
 *
 * ORGANISATION_DB_CLUSTERS is JSON, for example:
 *   {"cluster-1": {"host": "db.internal", "port": 5432, "sslmode": "require"}}
 * Empty by default: no organisation routes until an administrator configures its cluster.
 */
return [
    'clusters' => json_decode((string) env('ORGANISATION_DB_CLUSTERS', '{}'), true) ?: [],

    /*
     * PBDIGIT-345 task 4 (tactical design §3.5, M-5): the ORGANISATION migration set, applied to each organisation's
     * own database through provisioning; never seen by the central `migrate` (the migrator does not recurse).
     * Each context owns its paths: Platform the organisation_identity; Membership its ledger in the organisation
     * schema (F-13). Plain paths (no helpers), so the architecture test can read them without booting Laravel.
     */
    'migration_paths' => [
        dirname(__DIR__) . '/database/migrations/organisation',
        dirname(__DIR__) . '/app/Contexts/Membership/Infrastructure/Database/OrganisationMigrations',
    ],

    /*
     * PBDIGIT-345 task 8 (E-3): the operations `php artisan platform:fan-out <operation>` may run, as
     * operation => OrganisationJob class (constructed with the organisation id only, idempotent: delivery is at least
     * once). Business operations arrive with their owning context's slice.
     * task 9b: `outbox.relay`, the per-organisation outbox relay (E-5, §3.4); schedule it every minute (admin notes).
     */
    'fan_out' => [
        'outbox.relay' => App\Contexts\Platform\Infrastructure\Queue\RelayOrganisationOutbox::class,
    ],
];
