<?php

declare(strict_types=1);

namespace Tests\Evidence\Pbdigit283;

use Tests\TestCase;

/**
 * PBDIGIT-283 R6 negative evidence (G-RUN-NEG): the canary.
 *
 * A Tests\TestCase, so it carries RefreshDatabase: run with a non-test database configured, it is exactly the shape
 * of the 2026-10-13 incident. The R3 pre-provider check must refuse it in setUp(), before any provider is registered,
 * so the body is never reached and no reset runs.
 *
 * NOT part of any suite (tests/Evidence is not in phpunit.xml / phpunit-pgsql.xml). Run ONLY through the harness
 * tests/Evidence/Pbdigit283/negative-evidence.php, which first points every database target at a dead socket.
 */
final class ResetCanaryTest extends TestCase
{
    public function test_the_canary_body_is_never_reached(): void
    {
        $this->fail('the canary body was reached: the R3 pre-provider check did not refuse the unsafe configuration');
    }
}
