<?php

namespace Tests\Unit\Contexts\Elections;

use App\Contexts\Elections\Application\Commands\RecordIdentityVerificationCommand;
use App\Contexts\Elections\Application\Commands\RevokeIdentityVerificationCommand;
use App\Contexts\Elections\Application\Handlers\RecordIdentityVerificationHandler;
use App\Contexts\Elections\Application\Handlers\RevokeIdentityVerificationHandler;
use App\Contexts\Elections\Application\Ports\IdentityEvidenceStore;
use App\Contexts\Elections\Application\Ports\VerificationAuthoritySource;
use App\Contexts\Elections\Application\Ports\VoterRegistrationQuery;
use App\Contexts\Elections\Domain\Exceptions\VerifierNotAuthorised;
use App\Contexts\Elections\Domain\Exceptions\VoterNotVerifiable;
use App\Contexts\Elections\Domain\Policies\VerificationAuthority;
use App\Contexts\Elections\Domain\ValueObjects\ElectionLeadershipRole;
use App\Contexts\Elections\Domain\ValueObjects\RevocationReason;
use App\Contexts\Elections\Domain\ValueObjects\VerifierRole;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-213 C3.2/C3.3 — recording and revoking identity evidence (application).
 *
 * ADR_20261004_1549 D2/D3, C3-D2: only a registered voter of this election (voter
 * role, not deleted, not removed) can be verified; only the election's Chief or
 * Deputy Chief may verify or revoke; the evidence records the verifier's role.
 * Ports are mocked; VerificationAuthority is the real domain policy.
 */
final class IdentityVerificationHandlersTest extends TestCase
{
    private const ELECTION = 'election-1';
    private const VOTER    = 'voter-1';
    private const OFFICER  = 'officer-1';

    private VoterRegistrationQuery&MockObject $registry;
    private VerificationAuthoritySource&MockObject $leadership;
    private IdentityEvidenceStore&MockObject $evidence;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registry   = $this->createMock(VoterRegistrationQuery::class);
        $this->leadership = $this->createMock(VerificationAuthoritySource::class);
        $this->evidence   = $this->createMock(IdentityEvidenceStore::class);
    }

    private function recorder(): RecordIdentityVerificationHandler
    {
        return new RecordIdentityVerificationHandler($this->registry, $this->leadership, new VerificationAuthority(), $this->evidence);
    }

    private function revoker(): RevokeIdentityVerificationHandler
    {
        return new RevokeIdentityVerificationHandler($this->leadership, new VerificationAuthority(), $this->evidence);
    }

    private function recordCommand(): RecordIdentityVerificationCommand
    {
        return new RecordIdentityVerificationCommand(
            electionId: self::ELECTION,
            voterId: self::VOTER,
            verifierId: self::OFFICER,
            verifiedIp: '10.0.0.1',
            notes: 'video call',
        );
    }

    // ── Record ───────────────────────────────────────────────────────────────

    public function test_a_target_who_is_not_a_registered_voter_cannot_be_verified(): void
    {
        $this->registry->method('isVerifiableVoter')->with(self::VOTER, self::ELECTION)->willReturn(false);
        $this->leadership->method('leadershipRoleOf')->willReturn(ElectionLeadershipRole::Chief);
        $this->evidence->expects($this->never())->method('recordActive');

        $this->expectException(VoterNotVerifiable::class);

        $this->recorder()->handle($this->recordCommand());
    }

    public function test_a_verifier_without_election_leadership_cannot_verify(): void
    {
        $this->registry->method('isVerifiableVoter')->willReturn(true);
        $this->leadership->method('leadershipRoleOf')->with(self::OFFICER, self::ELECTION)->willReturn(null);
        $this->evidence->expects($this->never())->method('recordActive');

        $this->expectException(VerifierNotAuthorised::class);

        $this->recorder()->handle($this->recordCommand());
    }

    /** @return array<string, array{ElectionLeadershipRole, VerifierRole}> */
    public static function leadership(): array
    {
        return [
            'chief'        => [ElectionLeadershipRole::Chief, VerifierRole::ElectionChief],
            'deputy chief' => [ElectionLeadershipRole::DeputyChief, VerifierRole::ElectionDeputyChief],
        ];
    }

    /** @dataProvider leadership */
    public function test_the_election_leadership_records_evidence_with_its_role(ElectionLeadershipRole $held, VerifierRole $expected): void
    {
        $this->registry->method('isVerifiableVoter')->willReturn(true);
        $this->leadership->method('leadershipRoleOf')->willReturn($held);

        $this->evidence->expects($this->once())
            ->method('recordActive')
            ->with($this->callback(fn ($record) => $record->electionId === self::ELECTION
                && $record->voterId === self::VOTER
                && $record->verifierId === self::OFFICER
                && $record->verifierRole === $expected
                && $record->verifiedIp === '10.0.0.1'
                && $record->notes === 'video call'))
            ->willReturn('verification-9');

        $recorded = $this->recorder()->handle($this->recordCommand());

        $this->assertSame('verification-9', $recorded->verificationId);
        $this->assertSame($expected, $recorded->verifierRole);
    }

    // ── Revoke ───────────────────────────────────────────────────────────────

    public function test_a_revoker_without_election_leadership_cannot_revoke(): void
    {
        $this->leadership->method('leadershipRoleOf')->willReturn(null);
        $this->evidence->expects($this->never())->method('revokeActive');

        $this->expectException(VerifierNotAuthorised::class);

        $this->revoker()->handle(new RevokeIdentityVerificationCommand(self::ELECTION, self::VOTER, self::OFFICER, RevocationReason::RevokedByOfficer));
    }

    public function test_the_leadership_ends_the_current_admission_effect_with_a_reason(): void
    {
        $this->leadership->method('leadershipRoleOf')->willReturn(ElectionLeadershipRole::DeputyChief);
        $this->evidence->expects($this->once())
            ->method('revokeActive')
            ->with(self::ELECTION, self::VOTER, self::OFFICER, RevocationReason::SuspensionConfirmed)
            ->willReturn('verification-9');

        $revoked = $this->revoker()->handle(
            new RevokeIdentityVerificationCommand(self::ELECTION, self::VOTER, self::OFFICER, RevocationReason::SuspensionConfirmed)
        );

        $this->assertSame('verification-9', $revoked);
    }

    public function test_revoking_when_no_active_evidence_exists_is_a_no_op(): void
    {
        $this->leadership->method('leadershipRoleOf')->willReturn(ElectionLeadershipRole::Chief);
        $this->evidence->method('revokeActive')->willReturn(null);

        $this->assertNull($this->revoker()->handle(
            new RevokeIdentityVerificationCommand(self::ELECTION, self::VOTER, self::OFFICER, RevocationReason::RevokedByOfficer)
        ));
    }

    public function test_revocation_reasons_have_stable_codes(): void
    {
        $this->assertSame('suspension_confirmed', RevocationReason::SuspensionConfirmed->value);
        $this->assertSame('revoked_by_officer', RevocationReason::RevokedByOfficer->value);
    }
}
