<?php

namespace Tests\Unit\Contexts\Elections;

use App\Contexts\Elections\Application\Commands\BulkAssignVotersCommand;
use App\Contexts\Elections\Application\Handlers\BulkAssignVotersHandler;
use App\Contexts\Elections\Domain\Policies\VoterEligibilityPolicy;
use App\Contexts\Elections\Domain\Repositories\VoterRepositoryInterface;
use App\Domain\Election\Enum\VoterSourceStrategy;
use App\Models\DeadLetterEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * BulkAssignVotersHandlerTest
 *
 * Phase C.3: Bulk voter assignment (TDD unit tests)
 * - Policy filtering (single query per mode)
 * - Exclude already assigned
 * - Chunked processing (500 per chunk)
 * - Per-chunk transactions
 * - Dead-letter queue on chunk failure
 * - Result counts: success, already_existing, invalid, failed
 */
class BulkAssignVotersHandlerTest extends TestCase
{
    use RefreshDatabase;

    private \PHPUnit\Framework\MockObject\MockObject $policyMock;
    private \PHPUnit\Framework\MockObject\MockObject $repositoryMock;
    private BulkAssignVotersHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policyMock = $this->createMock(VoterEligibilityPolicy::class);
        $this->repositoryMock = $this->createMock(VoterRepositoryInterface::class);

        $this->handler = new BulkAssignVotersHandler($this->policyMock, $this->repositoryMock);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function filters_ineligible_users_before_any_writes(): void
    {
        // Only 2 of 3 are eligible
        $this->policyMock
            ->expects($this->once())
            ->method('qualifyingSubset')
            ->with(
                ['user-1', 'user-2', 'user-3'],
                'org-456',
                VoterSourceStrategy::ImportedVoterRegistry
            )
            ->willReturn(['user-1', 'user-2']); // user-3 filtered out

        $this->repositoryMock
            ->expects($this->once())
            ->method('existingVoterIds')
            ->with('election-789')
            ->willReturn([]);

        $this->repositoryMock
            ->expects($this->once())
            ->method('bulkInsert');

        $command = new BulkAssignVotersCommand(
            userIds: ['user-1', 'user-2', 'user-3'],
            electionId: 'election-789',
            organisationId: 'org-456',
            mode: VoterSourceStrategy::ImportedVoterRegistry,
            assignedBy: 'admin-001',
        );

        $result = $this->handler->handle($command);

        $this->assertEquals(2, $result['success']);
        $this->assertEquals(1, $result['invalid']);
        $this->assertEquals(0, $result['already_existing']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function excludes_already_assigned_users(): void
    {
        // All 3 are eligible, but 1 is already assigned
        $this->policyMock
            ->method('qualifyingSubset')
            ->willReturn(['user-1', 'user-2', 'user-3']);

        $this->repositoryMock
            ->expects($this->once())
            ->method('existingVoterIds')
            ->with('election-789')
            ->willReturn(['user-2']); // user-2 already assigned

        $this->repositoryMock
            ->expects($this->once())
            ->method('bulkInsert');

        $command = new BulkAssignVotersCommand(
            userIds: ['user-1', 'user-2', 'user-3'],
            electionId: 'election-789',
            organisationId: 'org-456',
            mode: VoterSourceStrategy::ImportedVoterRegistry,
        );

        $result = $this->handler->handle($command);

        $this->assertEquals(2, $result['success']);
        $this->assertEquals(1, $result['already_existing']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function returns_correct_success_already_existing_invalid_counts(): void
    {
        // Simulate: 3 eligible but 1 already assigned = 2 new + 1 already = 3 total valid
        // Total input: 5. So 3 valid - 1 already = 2 new, 2 ineligible
        $this->policyMock
            ->method('qualifyingSubset')
            ->willReturn(['user-1', 'user-2', 'user-3']);

        $this->repositoryMock
            ->method('existingVoterIds')
            ->willReturn(['user-1']);

        $this->repositoryMock
            ->method('bulkInsert');

        $command = new BulkAssignVotersCommand(
            userIds: ['user-1', 'user-2', 'user-3', 'user-4', 'user-5'],
            electionId: 'election-789',
            organisationId: 'org-456',
            mode: VoterSourceStrategy::ImportedVoterRegistry,
        );

        $result = $this->handler->handle($command);

        $this->assertEquals(2, $result['success']);
        $this->assertEquals(1, $result['already_existing']);
        $this->assertEquals(2, $result['invalid']);
        $this->assertArrayHasKey('failed', $result);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function processes_large_input_in_chunks_of_500(): void
    {
        // 1000 eligible users, none assigned
        $users = array_map(fn($i) => "user-$i", range(1, 1000));

        $this->policyMock
            ->method('qualifyingSubset')
            ->willReturn($users);

        $this->repositoryMock
            ->method('existingVoterIds')
            ->willReturn([]);

        // Expect bulkInsert to be called twice (2 chunks of 500)
        $this->repositoryMock
            ->expects($this->exactly(2))
            ->method('bulkInsert');

        $command = new BulkAssignVotersCommand(
            userIds: $users,
            electionId: 'election-789',
            organisationId: 'org-456',
            mode: VoterSourceStrategy::ImportedVoterRegistry,
            chunkSize: 500,
        );

        $result = $this->handler->handle($command);

        $this->assertEquals(1000, $result['success']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function allows_custom_chunk_size(): void
    {
        // 300 users, 100-user chunks = 3 chunks
        $users = array_map(fn($i) => "user-$i", range(1, 300));

        $this->policyMock
            ->method('qualifyingSubset')
            ->willReturn($users);

        $this->repositoryMock
            ->method('existingVoterIds')
            ->willReturn([]);

        // Expect bulkInsert to be called 3 times (3 chunks of 100)
        $this->repositoryMock
            ->expects($this->exactly(3))
            ->method('bulkInsert');

        $command = new BulkAssignVotersCommand(
            userIds: $users,
            electionId: 'election-789',
            organisationId: 'org-456',
            mode: VoterSourceStrategy::ImportedVoterRegistry,
            chunkSize: 100,
        );

        $result = $this->handler->handle($command);

        $this->assertEquals(300, $result['success']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function writes_failed_chunk_rows_to_dead_letter_queue(): void
    {
        // 1000 users: first chunk succeeds, second chunk fails
        $users = array_map(fn($i) => "user-$i", range(1, 1000));
        $orgId = '11111111-1111-1111-1111-111111111111';
        $electionId = '22222222-2222-2222-2222-222222222222';

        $this->policyMock
            ->method('qualifyingSubset')
            ->willReturn($users);

        $this->repositoryMock
            ->method('existingVoterIds')
            ->willReturn([]);

        // First call succeeds, second call throws exception
        $this->repositoryMock
            ->method('bulkInsert')
            ->will($this->onConsecutiveCalls(
                $this->returnValue(null),
                $this->throwException(new \Exception('Database constraint violation')),
            ));

        $command = new BulkAssignVotersCommand(
            userIds: $users,
            electionId: $electionId,
            organisationId: $orgId,
            mode: VoterSourceStrategy::ImportedVoterRegistry,
            chunkSize: 500,
        );

        $result = $this->handler->handle($command);

        // 500 succeeded (first chunk), 500 failed (second chunk)
        $this->assertEquals(500, $result['success']);
        $this->assertEquals(500, $result['failed']);

        // Verify second chunk rows were written to DLQ
        $dlqEntries = DeadLetterEntry::where('organisation_id', $orgId)
            ->where('queue_name', 'voter_bulk_assign')
            ->get();

        $this->assertEquals(500, $dlqEntries->count());

        // Verify payload structure
        $firstEntry = $dlqEntries->first();
        $this->assertEquals('user-501', $firstEntry->payload['user_id']);
        $this->assertEquals($electionId, $firstEntry->payload['election_id']);
        $this->assertStringContainsString('Database constraint violation', $firstEntry->error_message);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function continues_processing_after_chunk_failure(): void
    {
        // 1500 users: first chunk succeeds, second fails, third succeeds
        $users = array_map(fn($i) => "user-$i", range(1, 1500));
        $orgId = '33333333-3333-3333-3333-333333333333';
        $electionId = '44444444-4444-4444-4444-444444444444';

        $this->policyMock
            ->method('qualifyingSubset')
            ->willReturn($users);

        $this->repositoryMock
            ->method('existingVoterIds')
            ->willReturn([]);

        // First call succeeds, second throws, third succeeds
        $this->repositoryMock
            ->method('bulkInsert')
            ->will($this->onConsecutiveCalls(
                $this->returnValue(null),
                $this->throwException(new \Exception('Temporary failure')),
                $this->returnValue(null),
            ));

        $command = new BulkAssignVotersCommand(
            userIds: $users,
            electionId: $electionId,
            organisationId: $orgId,
            mode: VoterSourceStrategy::ImportedVoterRegistry,
            chunkSize: 500,
        );

        $result = $this->handler->handle($command);

        // 500 succeeded (first chunk) + 500 succeeded (third chunk) = 1000
        // 500 failed (second chunk)
        $this->assertEquals(1000, $result['success']);
        $this->assertEquals(500, $result['failed']);

        // Verify only the failed chunk is in DLQ
        $dlqEntries = DeadLetterEntry::where('organisation_id', $orgId)->get();
        $this->assertEquals(500, $dlqEntries->count());
    }
}
