<?php

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-213 C1 — fitness function: ONE authoritative voting-entitlement decision.
 *
 * ADR_20261004_1549 §2.2 invariants 8 and 10. Before C1 the entitlement predicate
 * (role = voter, status = active, …) was re-implemented at five sites with
 * diverging scope rules. Those sites must now delegate to the
 * VotingEntitlementQuery port; none may re-state the predicate.
 *
 * Property tested (not class names): the entitlement predicate text does not
 * appear at the call sites, and the domain decision imports no framework code.
 */
final class VotingEntitlementSinglePointTest extends TestCase
{
    /** Files that decide voting entitlement for a request and must delegate. */
    private const DELEGATING_SITES = [
        'app/Traits/EnsuresVoterMembership.php',
        'app/Http/Controllers/ElectionVotingController.php',
        'app/Http/Middleware/EnsureElectionVoter.php',
        'app/Http/Middleware/VoteEligibility.php',
    ];

    /** Fragments that re-state the membership entitlement predicate. */
    private const PREDICATE_FRAGMENTS = [
        "->where('status', 'active')",
        "->where('role', 'voter')",
        "->role !== 'voter'",
        "->status === 'removed'",
        "->status !== 'removed'",
    ];

    private const DOMAIN_FILES = [
        'app/Contexts/Elections/Domain/Policies/VotingEntitlementPolicy.php',
        'app/Contexts/Elections/Domain/ValueObjects/VotingEntitlementContext.php',
        'app/Contexts/Elections/Domain/ValueObjects/VotingEntitlementReason.php',
    ];

    private function root(): string
    {
        return dirname(__DIR__, 2);
    }

    public function test_entitlement_sites_do_not_restate_the_membership_predicate(): void
    {
        $violations = [];

        foreach (self::DELEGATING_SITES as $relative) {
            $content = file_get_contents($this->root() . '/' . $relative);

            foreach (self::PREDICATE_FRAGMENTS as $fragment) {
                if (str_contains($content, $fragment)) {
                    $violations[] = sprintf('%s re-states the entitlement predicate: %s', $relative, $fragment);
                }
            }
        }

        $this->assertEmpty($violations, "Delegate to VotingEntitlementQuery instead:\n" . implode("\n", $violations));
    }

    public function test_user_is_voter_in_election_delegates_to_the_authoritative_query(): void
    {
        $content = file_get_contents($this->root() . '/app/Models/User.php');

        $this->assertSame(1, preg_match('/function isVoterInElection\(.*?\n    \}\n/s', $content, $match),
            'User::isVoterInElection() must exist (its signature is kept for existing callers)');

        $body = $match[0];
        $this->assertStringContainsString('VotingEntitlementQuery', $body);

        foreach (self::PREDICATE_FRAGMENTS as $fragment) {
            $this->assertStringNotContainsString($fragment, $body);
        }
    }

    public function test_entitlement_domain_imports_no_framework_code(): void
    {
        $violations = [];

        foreach (self::DOMAIN_FILES as $relative) {
            $path = $this->root() . '/' . $relative;

            if (! is_file($path)) {
                $violations[] = sprintf('%s does not exist', $relative);
                continue;
            }

            if (preg_match('~use\s+Illuminate\\\\~', file_get_contents($path))) {
                $violations[] = sprintf('%s imports Illuminate', $relative);
            }
        }

        $this->assertEmpty($violations, implode("\n", $violations));
    }
}
