<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCore;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Event\AppointmentsAwaited;
use App\Contexts\Election\Domain\OperatingCore\Event\GateSatisfied;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Port\HistoryKind;
use App\Contexts\Election\Domain\OperatingCore\Port\ProtocolAppend;
use App\Contexts\Election\Domain\OperatingCore\Port\ProtocolEntry;
use App\Contexts\Election\Domain\OperatingCore\Port\RefusalRecord;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;
use PHPUnit\Framework\TestCase;

/**
 * `ProtocolAppend` port contract — F-PROTO-1 properties expressed by the contract
 * itself, exercised against an in-memory test double (no storage technology is
 * chosen — G-6; test doubles are this increment's ceiling).
 * Properties: append-only in meaning · refusal recordable independently of any
 * state success (property 6) · lifecycle vs progression-decision histories
 * distinguishable (property 7, P-2H) · opportunity-bound (property 4).
 */
final class ProtocolAppendContractTest extends TestCase
{
    private function double(): ProtocolAppend
    {
        return new class implements ProtocolAppend {
            /** @var list<ProtocolEntry> */
            public array $entries = [];

            public function append(ProtocolEntry $entry): void
            {
                $this->entries[] = $entry;
            }
        };
    }

    private function at(int $epoch): RecordedInstant
    {
        return RecordedInstant::fromEpochSeconds($epoch);
    }

    /** Property 7 / P-2H: the two histories are distinguishable inside the record and never merged. */
    public function test_lifecycle_and_progression_decision_histories_are_distinguishable(): void
    {
        $protocol = $this->double();
        $electionId = ElectionId::fromString('election-1');

        $protocol->append(ProtocolEntry::event(
            HistoryKind::Lifecycle,
            new AppointmentsAwaited($electionId, ['Election Chief', 'Deputy Election Officer'], $this->at(1_000)),
            $this->at(1_000),
        ));
        $protocol->append(ProtocolEntry::event(
            HistoryKind::ProgressionDecision,
            new GateSatisfied($electionId, GateDesignation::First, $this->at(2_000)),
            $this->at(2_000),
        ));

        $kinds = array_map(static fn (ProtocolEntry $e) => $e->kind, $protocol->entries);
        $this->assertSame([HistoryKind::Lifecycle, HistoryKind::ProgressionDecision], $kinds);
        $this->assertCount(2, HistoryKind::cases(), 'Exactly two histories exist (P-2H).');
    }

    /** Property 6: a refusal is recordable before, or independently of, any state-transition success. */
    public function test_a_refusal_is_recordable_independently_of_state_success(): void
    {
        $protocol = $this->double();

        // No aggregate changed state; the refusal is still a recordable material fact (EM-GOV-005).
        $protocol->append(ProtocolEntry::refusal(
            HistoryKind::ProgressionDecision,
            new RefusalRecord('express-committee-position', 'seat already expressed a position for this decision', $this->at(1_000)),
            $this->at(1_000),
        ));

        $this->assertCount(1, $protocol->entries);
        $this->assertTrue($protocol->entries[0]->isRefusal());
        $this->assertSame(
            'seat already expressed a position for this decision',
            $protocol->entries[0]->refusal?->reason,
            'A refusal carries its reason (EM-GOV-005; F-PROTO-1 property 6).'
        );
    }

    /** Property 2/5: the contract is append-only in meaning — it exposes no removal, rewrite or truncation. */
    public function test_the_contract_is_append_only_in_meaning(): void
    {
        $methods = (new \ReflectionClass(ProtocolAppend::class))->getMethods();

        $this->assertSame(['append'], array_map(static fn ($m) => $m->getName(), $methods));

        foreach ($methods as $method) {
            $this->assertDoesNotMatchRegularExpression(
                '/delete|remove|truncate|rewrite|update|replace|purge/i',
                $method->getName(),
                'The protocol records; nothing erases (EM-GOV-005; F-PROTO-1 properties 2 and 5).'
            );
        }
    }

    /** Property 4: an entry can be opportunity-bound. */
    public function test_an_entry_can_be_opportunity_bound(): void
    {
        $entry = ProtocolEntry::event(
            HistoryKind::Lifecycle,
            new AppointmentsAwaited(ElectionId::fromString('election-1'), ['Election Chief'], $this->at(1_000)),
            $this->at(1_000),
            'opportunity-42',
        );

        $this->assertSame('opportunity-42', $entry->opportunityReference);
    }
}
