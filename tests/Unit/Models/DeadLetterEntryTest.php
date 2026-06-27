<?php

namespace Tests\Unit\Models;

use App\Models\DeadLetterEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * DeadLetterEntryTest
 *
 * Phase C.6: Dead-letter queue for failed voter assignments
 * Tracks failed rows at granular level (not just failed chunks)
 */
class DeadLetterEntryTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function can_create_dead_letter_entry(): void
    {
        $orgId = '11111111-1111-1111-1111-111111111111';
        $electionId = '22222222-2222-2222-2222-222222222222';

        $entry = DeadLetterEntry::create([
            'queue_name' => 'voter_bulk_assign',
            'payload' => ['user_id' => 'user-123'],
            'error_message' => 'Foreign key constraint failed',
            'error_class' => 'QueryException',
            'organisation_id' => $orgId,
            'election_id' => $electionId,
        ]);

        $this->assertNotNull($entry->id);
        $this->assertEquals('voter_bulk_assign', $entry->queue_name);
        $this->assertEquals($orgId, $entry->organisation_id);
        $this->assertNull($entry->retried_at);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function payload_is_stored_as_json(): void
    {
        $payload = ['user_id' => 'user-123', 'election_id' => 'election-456'];
        $orgId = '33333333-3333-3333-3333-333333333333';
        $electionId = '44444444-4444-4444-4444-444444444444';

        $entry = DeadLetterEntry::create([
            'queue_name' => 'voter_bulk_assign',
            'payload' => $payload,
            'error_message' => 'Test error',
            'error_class' => 'Exception',
            'organisation_id' => $orgId,
            'election_id' => $electionId,
        ]);

        $this->assertEquals($payload, $entry->fresh()->payload);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function can_query_failed_entries_by_queue_and_org(): void
    {
        $orgA = '55555555-5555-5555-5555-555555555555';
        $orgB = '66666666-6666-6666-6666-666666666666';
        $electionId = '77777777-7777-7777-7777-777777777777';

        DeadLetterEntry::create([
            'queue_name' => 'voter_bulk_assign',
            'payload' => ['user_id' => 'user-1'],
            'error_message' => 'Error 1',
            'error_class' => 'Exception',
            'organisation_id' => $orgA,
            'election_id' => $electionId,
        ]);

        DeadLetterEntry::create([
            'queue_name' => 'voter_bulk_assign',
            'payload' => ['user_id' => 'user-2'],
            'error_message' => 'Error 2',
            'error_class' => 'Exception',
            'organisation_id' => $orgB,
            'election_id' => $electionId,
        ]);

        $org_a_entries = DeadLetterEntry::where('organisation_id', $orgA)
            ->where('queue_name', 'voter_bulk_assign')
            ->get();

        $this->assertEquals(1, $org_a_entries->count());
        $this->assertEquals('user-1', $org_a_entries->first()->payload['user_id']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function tracks_retry_timestamp(): void
    {
        $orgId = '88888888-8888-8888-8888-888888888888';
        $electionId = '99999999-9999-9999-9999-999999999999';

        $entry = DeadLetterEntry::create([
            'queue_name' => 'voter_bulk_assign',
            'payload' => ['user_id' => 'user-123'],
            'error_message' => 'Error',
            'error_class' => 'Exception',
            'organisation_id' => $orgId,
            'election_id' => $electionId,
        ]);

        $this->assertNull($entry->retried_at);

        $entry->update(['retried_at' => now()]);

        $this->assertNotNull($entry->fresh()->retried_at);
    }
}
