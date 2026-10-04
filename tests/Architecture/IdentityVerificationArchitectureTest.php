<?php

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-213 C3 — fitness functions for identity admission.
 *
 * - The admission/authority domain and application code is framework-free.
 * - The ONE entitlement decision reads no legacy or voting-process flag:
 *   users.can_vote, codes.can_vote_now and has_voted are not entitlement inputs
 *   (brief 20261004_1802 §2–§5; ADR_20261004_1549 inv. 8).
 * - The verification controller delegates to the application commands; it does not
 *   write evidence or decide authority itself.
 * - The entitlement call sites do not read identity evidence themselves.
 */
final class IdentityVerificationArchitectureTest extends TestCase
{
    private const PURE_FILES = [
        'app/Contexts/Elections/Domain/ValueObjects/VerifierRole.php',
        'app/Contexts/Elections/Domain/ValueObjects/ElectionLeadershipRole.php',
        'app/Contexts/Elections/Domain/ValueObjects/RevocationReason.php',
        'app/Contexts/Elections/Domain/Policies/VerificationAuthority.php',
        'app/Contexts/Elections/Domain/Exceptions/VoterNotVerifiable.php',
        'app/Contexts/Elections/Domain/Exceptions/VerifierNotAuthorised.php',
        'app/Contexts/Elections/Application/Ports/VerificationAuthoritySource.php',
        'app/Contexts/Elections/Application/Ports/VoterRegistrationQuery.php',
        'app/Contexts/Elections/Application/Ports/IdentityEvidenceStore.php',
        'app/Contexts/Elections/Application/Commands/RecordIdentityVerificationCommand.php',
        'app/Contexts/Elections/Application/Commands/RevokeIdentityVerificationCommand.php',
        'app/Contexts/Elections/Application/Handlers/RecordIdentityVerificationHandler.php',
        'app/Contexts/Elections/Application/Handlers/RevokeIdentityVerificationHandler.php',
    ];

    private const ENTITLEMENT_DECISION_FILES = [
        'app/Contexts/Elections/Domain/Policies/VotingEntitlementPolicy.php',
        'app/Contexts/Elections/Domain/ValueObjects/VotingEntitlementContext.php',
        'app/Contexts/Elections/Infrastructure/Queries/EloquentVotingEntitlementQuery.php',
    ];

    private const LEGACY_FLAGS = ['can_vote', 'can_vote_now', 'has_voted', 'is_voter'];

    private const ENTITLEMENT_CALL_SITES = [
        'app/Traits/EnsuresVoterMembership.php',
        'app/Http/Controllers/ElectionVotingController.php',
        'app/Http/Middleware/EnsureElectionVoter.php',
        'app/Http/Middleware/VoteEligibility.php',
    ];

    private function root(): string
    {
        return dirname(__DIR__, 2);
    }

    private function read(string $relative): string
    {
        $path = $this->root() . '/' . $relative;
        $this->assertFileExists($path);

        return file_get_contents($path);
    }

    public function test_identity_admission_domain_and_application_are_framework_free(): void
    {
        $violations = [];

        foreach (self::PURE_FILES as $relative) {
            $path = $this->root() . '/' . $relative;

            if (! is_file($path)) {
                $violations[] = "{$relative} does not exist";
                continue;
            }

            if (preg_match('~use\s+Illuminate\\\\|\\\\Illuminate\\\\Support\\\\Facades~', file_get_contents($path))) {
                $violations[] = "{$relative} depends on Illuminate";
            }
        }

        $this->assertEmpty($violations, implode("\n", $violations));
    }

    public function test_the_entitlement_decision_reads_no_legacy_or_voting_process_flag(): void
    {
        $violations = [];

        foreach (self::ENTITLEMENT_DECISION_FILES as $relative) {
            $content = $this->read($relative);

            foreach (self::LEGACY_FLAGS as $flag) {
                if (preg_match('/\b' . preg_quote($flag, '/') . '\b/', $content)) {
                    $violations[] = "{$relative} reads '{$flag}'";
                }
            }
        }

        $this->assertEmpty($violations, "VotingEntitlement must not depend on legacy/voting-process flags:\n" . implode("\n", $violations));
    }

    public function test_the_entitlement_query_reads_identity_evidence(): void
    {
        $this->assertStringContainsString('voter_verifications', $this->read('app/Contexts/Elections/Infrastructure/Queries/EloquentVotingEntitlementQuery.php'),
            'admission evidence must be part of the one entitlement decision');
    }

    public function test_the_verification_controller_delegates_to_the_application_commands(): void
    {
        $content = $this->read('app/Http/Controllers/Election/VoterVerificationController.php');

        $this->assertStringContainsString('RecordIdentityVerificationHandler', $content);
        $this->assertStringContainsString('RevokeIdentityVerificationHandler', $content);

        foreach (['VoterVerification::create', 'VoterVerification::query', 'ElectionOfficer::', 'ElectionMembership::'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $content, "the controller must not do '{$forbidden}' itself");
        }
    }

    public function test_entitlement_call_sites_do_not_read_identity_evidence_themselves(): void
    {
        $violations = [];

        foreach (self::ENTITLEMENT_CALL_SITES as $relative) {
            $content = $this->read($relative);

            foreach (['VoterVerification', 'voter_verifications'] as $forbidden) {
                if (str_contains($content, $forbidden)) {
                    $violations[] = "{$relative} reads {$forbidden}";
                }
            }
        }

        $this->assertEmpty($violations, implode("\n", $violations));
    }
}
