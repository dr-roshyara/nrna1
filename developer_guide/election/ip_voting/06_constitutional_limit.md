# 06: The constitutional per-IP limit (System B) as it works today

**Story:** `PBDIGIT-286` (OPEN). **This guide describes the current, committed behaviour.** No PBDIGIT-286 change is implemented. The planned changes are in the decision record and the DRAFT plan; when they are implemented, this guide is updated in plan phase P9.

**Code:**
- `app/Application/Election/Security/TrustPolicyEvaluator.php`
- `app/Domain/Election/Security/NetworkTrustEvidence.php`
- `app/Application/Election/Security/Policies/NetworkBindingPolicy.php`
- `app/Contexts/Elections/Infrastructure/Security/TrustLegitimacySecurityVerdict.php`

**Tests:**
- database-free: `tests/Unit/Domain/Election/Security/NetworkTrustEvidenceTest.php`, `tests/Unit/Application/Election/Security/Policies/NetworkBindingPolicyTest.php`, `tests/Unit/Application/Election/Security/TrustPolicyEvaluatorNetworkRestrictionTest.php`
- `nrna_test` suite: `tests/Feature/Voting/SecurityWhitelistPolicyTest.php`, `tests/Feature/Voting/SecurityPolicySurfaceCharacterizationTest.php`

## Purpose
There are **two separate per-IP limits** (Human PO, 2026-10-13; R3, 2026-10-14):
- **System A** = `IpVoteLimit` (guides 01, 03, 04): the visible, platform-wide limit.
- **System B** = the constitutional limit inside the trust policy (this guide).

A vote is accepted only when **every** applicable control permits it. Each control uses its own count and threshold, so don't compare the two numbers to decide which one is "stricter".

## How it works

**1. The election's settings** (`TrustPolicyEvaluator::buildNetworkEvidence`):
```php
$maxVotesPerIp = $election?->max_votes_per_ip ?? 6;
$votes = $votesFromThisIp ?? 0; // Use provided value or default to 0
$restrictionEnabled = $election !== null && $election->network_binding_strategy !== 'none';
```
- **The base N** is `elections.max_votes_per_ip` (column default 6).
- **The switch** is `network_binding_strategy`: it is on unless the value is `none` (default `ip_count`).
- **Not the admin toggle:** the "IP restriction" setting (`ip_restriction_enabled`) only controls System A.
- **Not editable:** no request, controller or page writes these two columns.

**2. The count** (`TrustLegitimacySecurityVerdict::assess`): completed votes in this election from the **exact** request IP. The query uses `Code` with its tenant scope:
```php
$votesFromThisIp = Code::where('election_id', $election->id)
    ->where('client_ip', $currentIp)
    ->where('has_voted', true)
    ->count();
```
Since PBDIGIT-288, `codes.client_ip` holds the IP **at the vote**, so this count also counts the voting IP. Unlike System A, it has **no** IPv6 /64 grouping.

**3. The effective limit depends on the voter's trust level** (`NetworkTrustEvidence`):
```php
return match ($trustLevel) {
    TrustLevel::RegistrarAttested => $this->maxVotesPerIp * 2,
    TrustLevel::ContinuityVerified => intdiv($this->maxVotesPerIp * 3, 2),
    TrustLevel::Attested => $this->maxVotesPerIp,
    TrustLevel::Unverified => max(1, intdiv($this->maxVotesPerIp, 2)),
};
```
**Today every voter is Unverified**, because the evaluator never reads the verification records:
```php
$attested = false; // populated by controller from VoterVerification record
$registrarId = null; // populated by controller from VoterVerification
```

**4. The refusal** (`NetworkTrustEvidence::exceedsLimit`) uses `>`:
```php
return $this->votesFromThisIp > $effectiveLimit;
```
With N = 6 the effective limit is 3. Because the comparison is `>`, a network can complete **4** votes, and the 5th voter is refused. With N = 1 it's 2 votes. (System A uses `>=`: limit L allows L votes.)

**5. The policy** (`NetworkBindingPolicy::evaluate`) decides in this order:
1. **IP binding first.** It is never bypassed by the whitelist.
2. **Switched off** (`!restrictionEnabled`) → no finding.
3. **Whitelisted** → no finding.
4. **Over the limit** → a finding:
```php
if ($ctx->network->exceedsLimit($currentTrustLevel)) {
    return new PolicyFinding(
        concernLevel: ConstitutionalConcernLevel::HIGH,
        evidenceWeight: EvidenceWeightCategory::STRONG,
        constitutionalBasis: 'network_limit_exceeded',
        supportingFacts: [
            'trust_level' => $currentTrustLevel->value,
            'votes_from_ip' => $ctx->network->votesFromThisIp,
            'limit_for_trust_level' => $ctx->network->maxVotesPerIp,
        ],
        policyIdentifier: $this->identifier(),
    );
}
```
**Note:** `limit_for_trust_level` reports the base N, not the effective limit.

**6. Where it runs:**
- **Code entry:** inside the single security assessment, **before** System A (`CodeController.php:718`, `:727`).
- **The vote:** at the top of `VoteController::store` (`:1491`), inside the cast transaction but **before any lock**. System A's check runs later, under the per-network advisory lock (guide 03).

**7. What the voter sees:** the generic text *"…cannot be granted / submitted based on constitutional verification. Please contact the election committee."*
- **Forensic record:** each evaluation writes a row in `election_security_events` (`trust_denied`, reason, votes from the IP, IP hash; no user ID).
- **No admin record:** no `ip_blocked` entry is written, and no page shows these events.

## How to use and extend it
- **Don't change System B behaviour opportunistically.** PBDIGIT-286 has binding requirements (R1–R5) and 16 open decisions:
  - the freeze when voting opens;
  - frozen policy record;
  - verification wiring;
  - `>=`;
  - /64 grouping;
  - concurrency;
  - message and audit.

  Any change goes through the approved plan and its gates.
- **To exempt a network from both limits,** use the whitelist (guide 02). It never bypasses IP binding.
- **In tests, set the System B columns with `DB::table('elections')->update([...])`,** as the existing feature tests do. The columns are not mass-assignable.

## Testing
**Run the database-free tests** with:
```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php <file>
```
The three unit files above are plain PHPUnit and safe to run this way. 39 pinning tests passed on 2026-10-14. The feature tests use `Tests\TestCase` on `nrna_test` and need separate authorization.

## Pitfalls
- **The effective limit is not N.** Today it is ⌊N/2⌋ with a minimum of 1, and one more vote is allowed because of `>`.
- **Raising the admin's "IP restriction" setting doesn't relax System B.** Pinned by `SecurityPolicySurfaceCharacterizationTest` C2.
- **`NetworkThresholdInterpreter` is a second scaling table with no runtime caller.** Don't wire it in; it disagrees at N = 1.
- **The constitutional snapshot (`security_articles_snapshot`, `constitutional_hash`) is frozen at creation, but enforcement reads the live columns.** Nothing validates the hash.
- **Concurrency:** voters on one network voting at the same moment can exceed System B, up to System A's limit, because System B is checked before the lock.

**Traceability:** current behaviour at HEAD `202496531` · `PBDIGIT-286` decision record `docs/publicdigit/reviews/2026-10-14-pbdigit286-principal-decision-record.md` · plan (DRAFT) `docs/plans/20261014-1038-pbdigit286-constitutional-ip-limit-plan.md` · current state `docs/publicdigit/reviews/2026-10-14-pbdigit286-constitutional-ip-limit-current-state.md`.
