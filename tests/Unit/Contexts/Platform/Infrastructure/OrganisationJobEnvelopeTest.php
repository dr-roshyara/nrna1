<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Platform\Infrastructure;

use App\Contexts\Platform\Infrastructure\Queue\EstablishOrganisationContext;
use App\Contexts\Platform\Infrastructure\Queue\OrganisationJob;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Platform\Queue\RecordVisitJob;

/**
 * PBDIGIT-345 task 7 (E-2; §3.10 Q-3, Q-4, Q-6; T-21, T-4): the organisation job ENVELOPE.
 * - it carries the organisation id (a UUID) and nothing that routes: no locator, no connection;
 * - its queue connection is fixed to `organisation` at construction;
 * - its middleware is fixed: EstablishOrganisationContext, which subclasses cannot remove.
 */
final class OrganisationJobEnvelopeTest extends TestCase
{
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';

    public function test_the_envelope_carries_the_organisation_id_and_fixes_the_connection_and_middleware(): void
    {
        $job = new RecordVisitJob(TenantId::fromString(self::A), 'x');

        $this->assertSame(self::A, $job->organisationId);
        $this->assertSame('organisation', $job->connection);
        $this->assertSame(OrganisationJob::CONNECTION, $job->connection);
        $this->assertEquals([new EstablishOrganisationContext()], $job->middleware());
        $this->assertTrue((new \ReflectionMethod(OrganisationJob::class, 'middleware'))->isFinal(), 'subclasses cannot drop the context middleware');
        $this->assertStringNotContainsString('DatabaseLocator', serialize($job), 'no routing fact in the payload (Q-5)');
    }

    public function test_t21_an_organisation_job_cannot_be_constructed_without_a_valid_organisation_id(): void
    {
        $this->assertTrue((new \ReflectionParameter([OrganisationJob::class, '__construct'], 0))->getType()?->allowsNull() === false);
        $this->expectException(\InvalidArgumentException::class);
        new RecordVisitJob(TenantId::fromString('not-a-uuid'), 'x');
    }
}
