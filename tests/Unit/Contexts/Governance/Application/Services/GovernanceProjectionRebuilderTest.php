<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Application\Services;

use App\Contexts\Governance\Application\DTOs\RebuildResult;
use App\Contexts\Governance\Application\Ports\CommitteeGovernanceProjectorInterface;
use App\Contexts\Governance\Application\Ports\CommitteeProjectionRebuildRepository;
use App\Contexts\Governance\Application\Ports\GovernanceClock;
use App\Contexts\Governance\Application\Ports\LockInterface;
use App\Contexts\Governance\Application\Ports\RebuildRunRepository;
use App\Contexts\Governance\Application\Services\GovernanceProjectionRebuilder;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;

final class GovernanceProjectionRebuilderTest extends TestCase
{
    private CommitteeGovernanceProjectorInterface $projector;
    private CommitteeProjectionRebuildRepository $rebuildRepository;
    private RebuildRunRepository $rebuildRunRepository;
    private LockInterface $lock;
    private GovernanceClock $clock;
    private GovernanceProjectionRebuilder $rebuilder;

    protected function setUp(): void
    {
        $this->projector = $this->createMock(CommitteeGovernanceProjectorInterface::class);
        $this->rebuildRepository = $this->createMock(CommitteeProjectionRebuildRepository::class);
        $this->rebuildRunRepository = $this->createMock(RebuildRunRepository::class);
        $this->lock = $this->createMock(LockInterface::class);
        $this->clock = $this->createMock(GovernanceClock::class);

        $this->clock
            ->method('now')
            ->willReturn(new DateTimeImmutable('2026-05-10T12:00:00Z'));

        $this->rebuilder = new GovernanceProjectionRebuilder(
            $this->projector,
            $this->rebuildRepository,
            $this->rebuildRunRepository,
            $this->lock,
            $this->clock,
        );
    }

    public function test_rebuilds_all_committees(): void
    {
        $this->lock
            ->expects($this->once())
            ->method('acquire')
            ->willReturn(true);

        $this->lock
            ->expects($this->once())
            ->method('release');

        $this->rebuildRunRepository
            ->expects($this->once())
            ->method('start');
        $this->rebuildRunRepository
            ->expects($this->once())
            ->method('complete');

        $committee = (object) ['id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV', 'name' => 'ICC Global', 'organisation_id' => 'org-1'];

        $this->rebuildRepository
            ->expects($this->once())
            ->method('countCommittees')
            ->with(null)
            ->willReturn(1);

        $this->rebuildRepository
            ->expects($this->once())
            ->method('streamCommitteeIds')
            ->with(null)
            ->willReturn([$committee]);

        $this->projector
            ->expects($this->once())
            ->method('rebuild');

        $result = $this->rebuilder->rebuildAll(null, new NullOutput());

        $this->assertInstanceOf(RebuildResult::class, $result);
        $this->assertSame(1, $result->rebuilt);
        $this->assertSame(0, $result->failed);
        $this->assertSame('completed', $result->status);
    }

    public function test_rebuilds_filtered_by_tenant(): void
    {
        $tenantId = \App\Contexts\Shared\Domain\ValueObjects\TenantId::fromString('org-123');

        $this->lock
            ->method('acquire')
            ->willReturn(true);

        $this->lock
            ->method('release');

        $this->rebuildRunRepository
            ->expects($this->once())
            ->method('start');
        $this->rebuildRunRepository
            ->expects($this->once())
            ->method('complete');

        $this->rebuildRepository
            ->expects($this->once())
            ->method('countCommittees')
            ->with($this->callback(fn($t) => $t?->value() === 'org-123'))
            ->willReturn(0);

        $this->rebuildRepository
            ->expects($this->once())
            ->method('streamCommitteeIds')
            ->with($this->callback(fn($t) => $t?->value() === 'org-123'))
            ->willReturn([]);

        $result = $this->rebuilder->rebuildAll($tenantId);

        $this->assertInstanceOf(RebuildResult::class, $result);
        $this->assertSame(0, $result->rebuilt);
    }

    public function test_skips_when_lock_held(): void
    {
        $this->lock
            ->expects($this->once())
            ->method('acquire')
            ->willReturn(false);

        $this->lock
            ->expects($this->never())
            ->method('release');

        $this->rebuildRepository
            ->expects($this->never())
            ->method('streamCommitteeIds');

        $result = $this->rebuilder->rebuildAll();

        $this->assertInstanceOf(RebuildResult::class, $result);
        $this->assertSame(0, $result->rebuilt);
        $this->assertSame('skipped: lock held', $result->status);
    }

    public function test_failure_isolation(): void
    {
        $this->lock
            ->method('acquire')
            ->willReturn(true);

        $this->lock
            ->method('release');

        $this->rebuildRunRepository
            ->expects($this->once())
            ->method('start');
        $this->rebuildRunRepository
            ->expects($this->once())
            ->method('complete');

        $committees = [
            (object) ['id' => '01ARZ3NDEKTSV4RRFFQ69G5FA1', 'name' => 'Committee A', 'organisation_id' => 'org-1'],
            (object) ['id' => '01ARZ3NDEKTSV4RRFFQ69G5FA2', 'name' => 'Committee B', 'organisation_id' => 'org-1'],
            (object) ['id' => '01ARZ3NDEKTSV4RRFFQ69G5FA3', 'name' => 'Committee C', 'organisation_id' => 'org-1'],
        ];

        $this->rebuildRepository
            ->method('countCommittees')
            ->willReturn(3);

        $this->rebuildRepository
            ->method('streamCommitteeIds')
            ->willReturn($committees);

        $this->projector
            ->method('rebuild')
            ->willReturnCallback(function (CommitteeId $id) {
                if ($id->value() === '01ARZ3NDEKTSV4RRFFQ69G5FA2') {
                    throw new \RuntimeException('Simulated failure');
                }
            });

        $result = $this->rebuilder->rebuildAll(null, new NullOutput());

        $this->assertSame(2, $result->rebuilt, 'Two committees should succeed');
        $this->assertSame(1, $result->failed, 'One committee should fail');
    }

    public function test_reports_progress(): void
    {
        $this->lock
            ->method('acquire')
            ->willReturn(true);

        $this->lock
            ->method('release');

        $this->rebuildRunRepository
            ->expects($this->once())
            ->method('start');
        $this->rebuildRunRepository
            ->expects($this->once())
            ->method('complete');

        $committee = (object) ['id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV', 'name' => 'ICC Global', 'organisation_id' => 'org-1'];

        $this->rebuildRepository
            ->method('countCommittees')
            ->willReturn(1);

        $this->rebuildRepository
            ->method('streamCommitteeIds')
            ->willReturn([$committee]);

        $this->projector
            ->method('rebuild');

        $output = $this->createMock(NullOutput::class);
        $output
            ->expects($this->atLeastOnce())
            ->method('writeln');

        $this->rebuilder->rebuildAll(null, $output);
    }

    public function test_generation_is_generated(): void
    {
        $reflection = new \ReflectionClass(GovernanceProjectionRebuilder::class);
        $method = $reflection->getMethod('generateGeneration');
        $method->setAccessible(true);

        $gen1 = $method->invoke($this->rebuilder);
        $gen2 = $method->invoke($this->rebuilder);

        $this->assertNotEmpty($gen1);
        $this->assertNotEmpty($gen2);
        $this->assertNotSame($gen1, $gen2, 'Each generation must be unique');
    }
}
