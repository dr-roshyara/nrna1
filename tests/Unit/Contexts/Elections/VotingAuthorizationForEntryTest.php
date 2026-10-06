<?php

namespace Tests\Unit\Contexts\Elections;

use App\Contexts\Elections\Application\DTO\EntryAttempt;
use App\Contexts\Elections\Application\DTO\EntryAuthorization;
use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Application\Services\VotingAuthorization;
use App\Contexts\Elections\Domain\ValueObjects\CastDecisionReason;
use App\Contexts\Elections\Domain\ValueObjects\EntryAuthorizationReason;
use App\Contexts\Elections\Domain\ValueObjects\SecurityVerdict;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-239 · Phase 5 Round 2A · RED — VotingAuthorization::forEntry() → EntryAuthorization.
 *
 * Blueprint: docs/publicdigit/reviews/2026-10-05-phase5-round2-forentry-blueprint.md §3, §4
 * ADR_20261005_0950 §3.1 (forEntry = authorization only: may this voter enter or continue now?),
 * §3.3 (Allowed the only success), I2 (admission evaluated once, inside VotingEntitlement), I6
 * (never "already voted").
 *
 * Composition, first failure wins:
 *   E1  VotingEntitlementQuery::decide() — standing incl. admission + lifecycle; exactly once, never standingOf()
 *   E2  the SecurityVerdict carried by the attempt — an authoritative fact supplied only by the one
 *       Security Verdict Authority (VotingSecurityVerdictQuery); forEntry() never manufactures it.
 * No process reasons (code value / code step / code validity / has_voted are outside authorization).
 */
final class VotingAuthorizationForEntryTest extends TestCase
{
    private const VOTER = '0198a1b2-0000-7000-8000-000000000001';
    private const ELECTION = '0198a1b2-0000-7000-8000-0000000000e1';

    // ── Fixtures ─────────────────────────────────────────────────────────────

    /** Entitlement port double that answers decide() / standingOf() with $reason and records every call. */
    private function entitlement(VotingEntitlementReason $reason): VotingEntitlementQuery
    {
        return new class($reason) implements VotingEntitlementQuery {
            /** @var list<array{string,string,string}> */
            public array $calls = [];

            public function __construct(private VotingEntitlementReason $reason) {}

            public function standingOf(string $userId, string $electionId): VotingEntitlementReason
            {
                $this->calls[] = ['standingOf', $userId, $electionId];

                return $this->reason;
            }

            public function decide(string $userId, string $electionId): VotingEntitlementReason
            {
                $this->calls[] = ['decide', $userId, $electionId];

                return $this->reason;
            }
        };
    }

    private function attempt(SecurityVerdict $security = SecurityVerdict::Allowed): EntryAttempt
    {
        return new EntryAttempt(voterId: self::VOTER, electionId: self::ELECTION, security: $security);
    }

    private function forEntry(EntryAttempt $attempt, VotingEntitlementReason $entitlement = VotingEntitlementReason::Entitled): EntryAuthorization
    {
        return (new VotingAuthorization($this->entitlement($entitlement)))->forEntry($attempt);
    }

    // ── Allowed is the only success ──────────────────────────────────────────

    public function test_an_entitled_and_secure_attempt_is_allowed(): void
    {
        $authorization = $this->forEntry($this->attempt());

        $this->assertSame(EntryAuthorizationReason::Allowed, $authorization->reason);
        $this->assertTrue($authorization->isAllowed());
    }

    public function test_allowed_is_the_only_reason_that_is_allowed(): void
    {
        foreach (EntryAuthorizationReason::cases() as $reason) {
            $this->assertSame(
                $reason === EntryAuthorizationReason::Allowed,
                (new EntryAuthorization($reason))->isAllowed(),
                "{$reason->name} must " . ($reason === EntryAuthorizationReason::Allowed ? '' : 'not ') . 'be allowed'
            );
        }
    }

    // ── E1: Voting Entitlement (forwarded 1:1) ───────────────────────────────

    public function test_every_entitlement_denial_is_forwarded_one_to_one(): void
    {
        foreach (VotingEntitlementReason::cases() as $entitlement) {
            if ($entitlement === VotingEntitlementReason::Entitled) {
                continue;
            }

            $authorization = $this->forEntry($this->attempt(), $entitlement);

            $this->assertSame($entitlement->name, $authorization->reason->name, "{$entitlement->name} forwarded by name");
            $this->assertSame($entitlement->value, $authorization->reason->value, "{$entitlement->name} forwarded by value");
            $this->assertFalse($authorization->isAllowed());
        }
    }

    public function test_entitlement_is_read_once_through_decide_which_includes_admission_and_the_lifecycle(): void
    {
        $port = $this->entitlement(VotingEntitlementReason::Entitled);

        (new VotingAuthorization($port))->forEntry($this->attempt());

        $this->assertSame([['decide', self::VOTER, self::ELECTION]], $port->calls,
            'exactly one fresh decide() call; standingOf() (cached navigation, no lifecycle) is never used');
    }

    public function test_entitlement_is_read_once_even_when_security_denies(): void
    {
        $port = $this->entitlement(VotingEntitlementReason::Entitled);

        (new VotingAuthorization($port))->forEntry($this->attempt(SecurityVerdict::Denied));

        $this->assertSame([['decide', self::VOTER, self::ELECTION]], $port->calls);
    }

    // ── E2: Voting Security Decision (the authoritative verdict as a fact) ───

    public function test_a_security_denial_is_security_denied(): void
    {
        $authorization = $this->forEntry($this->attempt(SecurityVerdict::Denied));

        $this->assertSame(EntryAuthorizationReason::SecurityDenied, $authorization->reason);
        $this->assertFalse($authorization->isAllowed());
    }

    public function test_a_security_review_requirement_is_a_denial_q2(): void
    {
        $authorization = $this->forEntry($this->attempt(SecurityVerdict::ReviewRequired));

        $this->assertSame(EntryAuthorizationReason::SecurityReviewRequired, $authorization->reason);
        $this->assertFalse($authorization->isAllowed(), 'Q2 = Deny: Deferred / Investigate never allow entry');
    }

    public function test_for_entry_consumes_the_supplied_verdict_and_never_obtains_or_manufactures_one(): void
    {
        // E2 is supplied by the one Security Verdict Authority (VotingSecurityVerdictQuery) through the attempt.
        // VotingAuthorization depends only on the entitlement port: it has no way to call a security policy.
        $constructor = (new \ReflectionClass(VotingAuthorization::class))->getConstructor();
        $types = array_map(fn (\ReflectionParameter $p) => (string) $p->getType(), $constructor->getParameters());

        $this->assertSame([VotingEntitlementQuery::class], $types,
            'VotingAuthorization is composed from the entitlement port only — no security evaluator, no verdict query');

        foreach (SecurityVerdict::cases() as $verdict) {
            $expected = match ($verdict) {
                SecurityVerdict::Allowed        => EntryAuthorizationReason::Allowed,
                SecurityVerdict::Denied         => EntryAuthorizationReason::SecurityDenied,
                SecurityVerdict::ReviewRequired => EntryAuthorizationReason::SecurityReviewRequired,
                SecurityVerdict::IpBindingDenied => EntryAuthorizationReason::IpBindingDenied,   // PBDIGIT-247
            };
            $this->assertSame($expected, $this->forEntry($this->attempt($verdict))->reason,
                "the supplied {$verdict->name} verdict is consumed as given");
        }
    }

    // ── Order: first failure wins ────────────────────────────────────────────

    public function test_entitlement_is_evaluated_before_security(): void
    {
        $this->assertSame(
            EntryAuthorizationReason::ElectionNotVotable,
            $this->forEntry($this->attempt(SecurityVerdict::Denied), VotingEntitlementReason::ElectionNotVotable)->reason
        );
    }

    // ── Vocabulary (ADR §3.3: authorization reasons only) ────────────────────

    public function test_the_reason_vocabulary_is_exactly_the_authorization_set(): void
    {
        $names = array_map(fn (EntryAuthorizationReason $r) => $r->name, EntryAuthorizationReason::cases());
        sort($names);

        $expected = [
            'Allowed',
            // E1 — forwarded entitlement reasons
            'NotRegistered', 'NotVoterRole', 'OrganisationMismatch', 'NotActive', 'NotAdmitted', 'ElectionNotVotable',
            // E2 — the one security authority's verdict
            'SecurityDenied', 'SecurityReviewRequired',
            // PBDIGIT-247 (PO 2026-10-06): IP Binding — the one narrow E2-D4 supersession
            'IpBindingDenied',
        ];
        sort($expected);

        $this->assertSame($expected, $names,
            'no process reason (code, code step, code validity, has_voted) and no "already voted" — those are outside authorization');
    }

    public function test_entry_reasons_share_values_with_the_cast_decision_authorization_subset(): void
    {
        foreach (EntryAuthorizationReason::cases() as $reason) {
            $this->assertSame($reason->value, CastDecisionReason::from($reason->value)->value,
                "{$reason->name}: one reason family (ADR §3.3) — same value as the CastDecisionReason case");
        }
    }

    public function test_from_entitlement_maps_every_non_entitled_reason(): void
    {
        foreach (VotingEntitlementReason::cases() as $entitlement) {
            if ($entitlement === VotingEntitlementReason::Entitled) {
                continue;
            }
            $this->assertSame($entitlement->value, EntryAuthorizationReason::fromEntitlement($entitlement)->value);
        }
    }
}
