<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Infrastructure\Organisational\StoredMemberLogInvalid;
use PHPUnit\Framework\TestCase;

/**
 * COR-4 review condition C-2 (PBDIGIT-329, F-2; PO 2026-11-03: as recommended): the message names the Member, the
 * organisation and the class of the cause, never the cause's message, which can echo stored payload (provenance
 * values, dates, ids). The cause stays available as the previous exception for diagnosis.
 */
final class StoredMemberLogInvalidTest extends TestCase
{
    public function test_the_message_never_carries_the_causes_stored_payload(): void
    {
        $cause = new \UnexpectedValueException("Not a calendar date: 'PAYLOAD-in-trim'.");

        $e = StoredMemberLogInvalid::because('org-1', 'M1', $cause);

        $this->assertSame('Stored history of Member M1 (organisation org-1) is invalid (UnexpectedValueException).', $e->getMessage());
        $this->assertStringNotContainsString('PAYLOAD', $e->getMessage());
        $this->assertStringContainsString('M1', $e->getMessage());
        $this->assertStringContainsString('org-1', $e->getMessage());
        $this->assertStringContainsString(\UnexpectedValueException::class, $e->getMessage());
        $this->assertSame($cause, $e->getPrevious(), 'the cause is kept for diagnosis');
        $this->assertInstanceOf(\RuntimeException::class, $e, 'an infrastructure failure (rule 8)');
    }
}
