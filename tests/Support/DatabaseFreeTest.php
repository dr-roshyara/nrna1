<?php

declare(strict_types=1);

namespace Tests\Support;

/**
 * Marker for a test that boots Laravel but must never reach a database (PBDIGIT-283 R1, decision D-283-3).
 *
 * Tests\CreatesApplication::createApplication() sees it and, before bootstrap/app.php is required, points every
 * database target at a non-existent socket (DatabaseFreeEnvironment), refusing to boot if that fails; the
 * environment is restored after bootstrap. Any query then fails loudly instead of reaching a real database.
 */
interface DatabaseFreeTest
{
}
