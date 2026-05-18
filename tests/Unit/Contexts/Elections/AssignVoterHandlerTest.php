<?php

namespace Tests\Unit\Contexts\Elections;

use App\Contexts\Elections\Application\Commands\AssignVoterCommand;
use App\Contexts\Elections\Application\Handlers\AssignVoterHandler;
use App\Contexts\Elections\Domain\Exceptions\DuplicateVoterException;
use App\Contexts\Elections\Domain\Exceptions\VoterNotEligibleException;
use App\Contexts\Elections\Domain\Policies\VoterEligibilityPolicy;
use App\Contexts\Elections\Domain\Repositories\VoterRepositoryInterface;
use App\Domain\Election\Enum\ElectionMode;
use App\Models\ElectionMembership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * AssignVoterHandlerTest
 *
 * Phase C.2b: Voter assignment orchestrator (TDD unit tests with test doubles)
 * - Eligibility check via policy
 * - Reject ineligible voters
 * - Prevent duplicate active assignments
 * - Restore soft-deleted membership on re-import
 * - Create new membership for new voters
 * - Dispatch domain event on success
 */
class AssignVoterHandlerTest extends TestCase
{
    use RefreshDatabase;

    private \PHPUnit\Framework\MockObject\MockObject $policyMock;
    private \PHPUnit\Framework\MockObject\MockObject $repositoryMock;
    private AssignVoterHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policyMock = $this->createMock(VoterEligibilityPolicy::class);
        $this->repositoryMock = $this->createMock(VoterRepositoryInterface::class);

        $this->handler = new AssignVoterHandler($this->policyMock, $this->repositoryMock);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function throws_voter_not_eligible_when_policy_rejects(): void
    {
        $this->expectException(VoterNotEligibleException::class);

        $this->policyMock
            ->expects($this->once())
            ->method('isEligible')
            ->with('user-123', 'org-456', ElectionMode::ElectionOnly)
            ->willReturn(false);

        $command = new AssignVoterCommand(
            userId: 'user-123',
            electionId: 'election-789',
            organisationId: 'org-456',
            mode: ElectionMode::ElectionOnly,
            assignedBy: 'admin-001'
        );

        $this->handler->handle($command);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function creates_new_membership_for_new_voter(): void
    {
        $this->policyMock
            ->method('isEligible')
            ->willReturn(true);

        $newMembership = ElectionMembership::factory()->make([
            'user_id' => 'user-123',
            'election_id' => 'election-789',
            'status' => 'active',
        ]);

        $this->repositoryMock
            ->expects($this->once())
            ->method('findWithTrashed')
            ->with('user-123', 'election-789')
            ->willReturn(null);

        $this->repositoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturnCallback(fn() => $newMembership);

        $command = new AssignVoterCommand(
            userId: 'user-123',
            electionId: 'election-789',
            organisationId: 'org-456',
            mode: ElectionMode::ElectionOnly,
            assignedBy: 'admin-001'
        );

        $result = $this->handler->handle($command);

        $this->assertEquals('user-123', $result->user_id);
        $this->assertEquals('election-789', $result->election_id);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function restores_soft_deleted_membership_instead_of_inserting_duplicate(): void
    {
        $this->policyMock
            ->method('isEligible')
            ->willReturn(true);

        $softDeletedMembership = ElectionMembership::factory()->make([
            'user_id' => 'user-123',
            'election_id' => 'election-789',
            'status' => 'inactive',
            'deleted_at' => now(),
        ]);

        $restoredMembership = ElectionMembership::factory()->make([
            'user_id' => 'user-123',
            'election_id' => 'election-789',
            'status' => 'active',
            'deleted_at' => null,
        ]);

        $this->repositoryMock
            ->expects($this->once())
            ->method('findWithTrashed')
            ->willReturn($softDeletedMembership);

        $this->repositoryMock
            ->expects($this->never())
            ->method('create');

        $this->repositoryMock
            ->expects($this->once())
            ->method('restoreAndUpdate')
            ->willReturnCallback(fn() => $restoredMembership);

        $command = new AssignVoterCommand(
            userId: 'user-123',
            electionId: 'election-789',
            organisationId: 'org-456',
            mode: ElectionMode::ElectionOnly
        );

        $result = $this->handler->handle($command);

        $this->assertNull($result->deleted_at);
        $this->assertEquals('active', $result->status);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function throws_duplicate_voter_exception_when_already_active(): void
    {
        $this->expectException(DuplicateVoterException::class);

        $this->policyMock
            ->method('isEligible')
            ->willReturn(true);

        $activeMembership = ElectionMembership::factory()->make([
            'user_id' => 'user-123',
            'election_id' => 'election-789',
            'status' => 'active',
            'deleted_at' => null,
        ]);

        $this->repositoryMock
            ->expects($this->once())
            ->method('findWithTrashed')
            ->willReturn($activeMembership);

        $command = new AssignVoterCommand(
            userId: 'user-123',
            electionId: 'election-789',
            organisationId: 'org-456',
            mode: ElectionMode::ElectionOnly
        );

        $this->handler->handle($command);
    }
}
