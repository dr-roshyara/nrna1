<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCore;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Condition\ElectionOperationalStatus;
use App\Contexts\Election\Domain\OperatingCore\Port\RecordedOperationalStatus;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionNamedType;

/**
 * `EM-DOM-001` Act B · `H-1` — the STRUCTURAL-ABSENCE RED.
 *
 * What is missing is not a concept: `ElectionOperationalStatus` is complete and
 * frozen, and nothing in the domain can obtain one for an identified election.
 * This test therefore pins exactly one thing: that the Domain DECLARES a
 * retrieval contract for the election's RECORDED operational status, keyed by
 * `ElectionId`, returning the frozen status type.
 *
 * ⛔ WHAT THIS TEST DELIBERATELY DOES NOT ASSERT (Gate-2 correction `C-4`):
 *  - it makes NO domain-invariant claim — the contract's existence and signature
 *    is its whole content, which is what `D1` authorizes and no more;
 *  - it does NOT assert "no resumption target is produced by any means" — that is
 *    not an executable assertion; `ResumptionTarget` (P-7) refuses `null` by
 *    signature, and that refusal is already frozen and already pinned;
 *  - it asserts NOTHING behavioural about halt retention, restoration, or the
 *    `w8` case. Those hold on the frozen type today (`V-2`/`V-3`), so a test of
 *    them would be a REGRESSION LOCK, never a RED (`H-2`), and counting one as
 *    the RED would falsify the RED-before-GREEN evidence;
 *  - it asserts nothing excluded by `H-3`: no Application normalization, no
 *    `ElectionRestored` representation (`D2` selects none), no permission
 *    invariant (`R-2`), no persistence round-trip (act C), no phase
 *    discriminator (`BND-1`).
 *
 * The non-nullable return type is a legitimate type-level encoding and breaches
 * neither `D3` nor `D4` (Gate-2 correction `C-3`): no phase concept and no
 * ownership is encoded by it.
 *
 * Fails, before Act-B GREEN, by ABSENCE: the contract does not exist.
 */
final class RecordedOperationalStatusRetrievalRedTest extends TestCase
{
    private const CONTRACT = RecordedOperationalStatus::class;

    /**
     * The absence assertion itself. Asserted rather than left to a load failure, so the
     * RED reports a clean per-test failure naming the missing contract — the estate's own
     * structural-absence form (`StructuralApplicationGuardsRedTest::assertGrantedSurfaceExists`).
     */
    private function assertContractIsDeclared(): void
    {
        $this->assertTrue(
            interface_exists(self::CONTRACT),
            'EM-DOM-001 Act B: the Domain declares NO contract by which the recorded operational '
            . 'status of an identified election can be obtained. `ElectionOperationalStatus` is '
            . 'complete (V-1) and unreachable (V-4): nothing in app/ constructs, stores or '
            . 'retrieves one, so recorded operational truth cannot enter any decision. '
            . 'Expected interface: ' . self::CONTRACT
        );
    }

    /** The contract exists, and it is an interface — a declared contract, not a mechanism. */
    public function test_the_recorded_operational_status_contract_is_declared_in_the_domain(): void
    {
        $this->assertContractIsDeclared();

        $reflection = new ReflectionClass(self::CONTRACT);

        $this->assertTrue($reflection->isInterface(), 'Act B creates a CONTRACT, never an implementation.');
        $this->assertSame(
            'App\\Contexts\\Election\\Domain\\OperatingCore\\Port',
            $reflection->getNamespaceName(),
            'The contract is Domain-owned and lives with the operating core\'s driven ports (G-2a: NOT Repository/, '
            . 'which repo Rule 9 reserves for aggregates — a fourth one there would assert the aggregate standing '
            . 'D1 and D4 withhold).'
        );
    }

    /** One question, one operation: keyed by `ElectionId`, answered with the frozen status type. */
    public function test_the_contract_declares_one_retrieval_operation_keyed_by_election_id(): void
    {
        $this->assertContractIsDeclared();

        $methods = (new ReflectionClass(self::CONTRACT))->getMethods();

        $this->assertSame(
            ['ofElection'],
            array_map(static fn ($m) => $m->getName(), $methods),
            'One semantic responsibility: "what is this election\'s RECORDED operational status?" — no second operation.'
        );

        $method = $methods[0];

        $this->assertCount(1, $method->getParameters(), 'The retrieval key is the election\'s own identity, and nothing else.');
        $parameterType = $method->getParameters()[0]->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $parameterType);
        $this->assertSame(ElectionId::class, $parameterType->getName(), 'ElectionId is the key all three BND-3 candidates share (G-2).');

        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame(
            ElectionOperationalStatus::class,
            $returnType->getName(),
            'The return type is the FROZEN status type, unchanged — both orthogonal recorded facts, never a re-classification.'
        );
        $this->assertFalse(
            $returnType->allowsNull(),
            'Total by type (C-3): a nullable return would carry the NEW meaning "no operational status is recorded", '
            . 'and whether an election has an overlay before it is constituted is a lifecycle-phase question (BND-1, deferred).'
        );
    }

    /** The contract carries no persistence or aggregate idiom — it asks a question, it prescribes no mechanism. */
    public function test_the_contract_exposes_no_persistence_or_aggregate_mechanism(): void
    {
        $this->assertContractIsDeclared();

        foreach ((new ReflectionClass(self::CONTRACT))->getMethods() as $method) {
            $this->assertDoesNotMatchRegularExpression(
                '/^(save|persist|store|put|write|delete|remove|flush|commit|find|all|query|filter)/i',
                $method->getName(),
                'Act B reaches the RETRIEVAL half only: no save (act C), no find() idiom, no collection query. '
                . 'BND-3 stays open — a distinct aggregate, membership in a lifecycle aggregate and a projection '
                . 'each satisfy this contract unchanged, so it selects none of them.'
            );
        }
    }

    /**
     * Satisfiability: the contract is implementable by a Domain-only double — no framework, no
     * store, no adapter. ⛔ This asserts the SHAPE is satisfiable; it asserts no domain behaviour,
     * and Act B authorizes no adapter and no caller.
     */
    public function test_the_contract_is_satisfiable_by_a_domain_only_double(): void
    {
        $this->assertContractIsDeclared();

        $double = new class implements RecordedOperationalStatus {
            public function ofElection(ElectionId $electionId): ElectionOperationalStatus
            {
                return ElectionOperationalStatus::operative();
            }
        };

        $this->assertInstanceOf(self::CONTRACT, $double);
        $this->assertInstanceOf(
            ElectionOperationalStatus::class,
            $double->ofElection(ElectionId::fromString('election-1')),
            'The declared shape is satisfiable inside the Domain alone; nothing here pins how a future implementor obtains the status.'
        );
    }
}
