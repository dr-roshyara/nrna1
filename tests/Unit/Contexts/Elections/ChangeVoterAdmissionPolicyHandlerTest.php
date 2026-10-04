<?php

namespace Tests\Unit\Contexts\Elections;

use App\Contexts\Elections\Application\Commands\ChangeVoterAdmissionPolicyCommand;
use App\Contexts\Elections\Application\Handlers\ChangeVoterAdmissionPolicyHandler;
use App\Contexts\Elections\Application\Ports\AdmissionImpactQuery;
use App\Contexts\Elections\Application\Ports\VoterAdmissionPolicyStore;
use App\Contexts\Elections\Domain\Exceptions\AdmissionPolicyLocked;
use App\Contexts\Elections\Domain\ValueObjects\AdmissionRequirement;
use App\Contexts\Elections\Domain\ValueObjects\VoterAdmissionPolicy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-213 C2 — ChangeVoterAdmissionPolicyHandler (application command).
 *
 * ADR_20261004_1549 D4: the policy may change only while the election is not
 * locked (Draft · SetupAdministration · SetupNomination). An identical proposal
 * is a no-op, even when locked. The change never touches memberships: admission
 * stays derived. Ports are mocked; no database.
 */
final class ChangeVoterAdmissionPolicyHandlerTest extends TestCase
{
    private const ELECTION = 'election-1';
    private const ACTOR    = 'chief-1';

    private VoterAdmissionPolicyStore&MockObject $store;
    private AdmissionImpactQuery&MockObject $impact;
    private ChangeVoterAdmissionPolicyHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->store   = $this->createMock(VoterAdmissionPolicyStore::class);
        $this->impact  = $this->createMock(AdmissionImpactQuery::class);
        $this->handler = new ChangeVoterAdmissionPolicyHandler($this->store, $this->impact);
    }

    private function identityRequired(): VoterAdmissionPolicy
    {
        return VoterAdmissionPolicy::requiring(AdmissionRequirement::OfficerIdentityVerification);
    }

    private function command(VoterAdmissionPolicy $proposed): ChangeVoterAdmissionPolicyCommand
    {
        return new ChangeVoterAdmissionPolicyCommand(self::ELECTION, $proposed, self::ACTOR);
    }

    public function test_an_unlocked_change_is_saved_and_reported(): void
    {
        $this->store->method('policyOf')->willReturn(VoterAdmissionPolicy::none());
        $this->store->method('isLocked')->willReturn(false);
        $this->impact->method('registeredVotersWithoutIdentityEvidence')->with(self::ELECTION)->willReturn(5);

        $this->store->expects($this->once())
            ->method('save')
            ->with(self::ELECTION, $this->callback(fn (VoterAdmissionPolicy $p) => $p->equals($this->identityRequired())));

        $change = $this->handler->handle($this->command($this->identityRequired()));

        $this->assertTrue($change->changed);
        $this->assertTrue($change->from->isEmpty());
        $this->assertTrue($change->to->equals($this->identityRequired()));
        $this->assertSame(5, $change->affectedVoters);
    }

    public function test_turning_the_requirement_off_is_also_a_change(): void
    {
        $this->store->method('policyOf')->willReturn($this->identityRequired());
        $this->store->method('isLocked')->willReturn(false);
        $this->impact->method('registeredVotersWithoutIdentityEvidence')->willReturn(2);
        $this->store->expects($this->once())->method('save');

        $change = $this->handler->handle($this->command(VoterAdmissionPolicy::none()));

        $this->assertTrue($change->changed);
        $this->assertTrue($change->to->isEmpty());
        $this->assertSame(2, $change->affectedVoters);
    }

    public function test_a_locked_election_refuses_a_change_and_saves_nothing(): void
    {
        $this->store->method('policyOf')->willReturn(VoterAdmissionPolicy::none());
        $this->store->method('isLocked')->willReturn(true);
        $this->store->expects($this->never())->method('save');

        $this->expectException(AdmissionPolicyLocked::class);

        $this->handler->handle($this->command($this->identityRequired()));
    }

    public function test_an_identical_proposal_is_a_no_op_even_when_locked(): void
    {
        $this->store->method('policyOf')->willReturn($this->identityRequired());
        $this->store->method('isLocked')->willReturn(true);
        $this->store->expects($this->never())->method('save');

        $change = $this->handler->handle($this->command($this->identityRequired()));

        $this->assertFalse($change->changed);
    }
}
