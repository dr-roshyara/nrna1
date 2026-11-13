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
];
