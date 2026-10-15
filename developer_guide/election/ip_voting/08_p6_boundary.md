# 08: No vote beyond the limit: the System B boundary (PBDIGIT-286 P6)

**Story:** `PBDIGIT-286` phase P6 (AC-11; decisions D7, D7a) · **Plan:** `docs/plans/20261014-1038-pbdigit286-constitutional-ip-limit-plan.md` · **Status:** boundary step (D7, D7a) and count step (D6) implemented and tested 2026-10-15; committed `2f998b8ae` (not pushed); **not accepted**; not released.

**Code:**
- `app/Domain/Election/Security/NetworkTrustEvidence.php`: `exceedsLimit()` uses `>=`
- `app/Domain/Election/Constitution/NetworkThresholdInterpreter.php`: Initial = `max(1, ⌊N/2⌋)`

**Tests (database-free):**
- `tests/Unit/Domain/Election/Security/NetworkTrustEvidenceTest.php`
- `tests/Unit/Application/Election/Security/Policies/NetworkBindingPolicyTest.php`
- `tests/Unit/Domain/Election/Constitution/NetworkThresholdInterpreterTest.php`
- `tests/Unit/Application/Election/Security/TrustPolicyAttestationEvidenceTest.php` (P5 with P6)

## Purpose
- **D7:** with effective limit L, the next voter is refused once **L completed votes** exist. No vote beyond L.
- **Before this step,** `votesFromThisIp > L` admitted the voter at L, so a network completed **L + 1** votes.

## Why `>=` is the right comparison
- **What the count is:** `votesFromThisIp` comes from `TrustLegitimacySecurityVerdict::assess`. It counts `codes` in this election with `has_voted = true` from the request IP.
- **The current voter isn't in the count:** their code isn't voted yet, both at code entry and at the cast.
- **So the count is the votes already completed.** Refusing when it reaches L lets exactly L votes complete.

```php
// NetworkTrustEvidence::exceedsLimit()
$effectiveLimit = $this->effectiveLimit($trustLevel);
return $this->votesFromThisIp >= $effectiveLimit;
```

- **`remainingVotes()` is unchanged.** At L it already returned 0; it now agrees with the refusal.

## Effect in numbers
Votes a network can complete before System B refuses:

| N | Unverified L = max(1, ⌊N/2⌋) | Attested L = N |
|---|---|---|
| 1 | 1 (was 2) | 1 (was 2) |
| 6 (default) | 3 (was 4) | 6 (was 7 with P5) |
| 50 | 25 (was 26) | 50 (was 51) |

**This is System B only.** System A (`IpVoteLimit`, guide 01) stays separate and can refuse earlier; the stricter one applies at code entry.

## D7a: the interpreter's minimum
- `NetworkThresholdInterpreter::interpret()` now gives Initial `max(1, ⌊N/2⌋)`, the same as `NetworkTrustEvidence`.
- **It has no production caller.** `app/` calls `interpret()` nowhere; it's referenced only in a docblock in the `Simplified` namespace. The change keeps the two definitions identical.

## D6: what System B counts
```php
// TrustLegitimacySecurityVerdict::assess()
$votesFromThisIp = IpVoteLimit::votesFrom($election, $context->clientIp);
```
- **The same count as System A** (`IpVoteLimit::votesFrom`, guide 03), ADR-C §3.2:
  - completed codes (`has_voted = true`) in this election only;
  - **IPv4:** one address, with the plain and `::ffff:` spellings together (no IPv4 network grouping);
  - **IPv6:** its /64 (`voting.ipv6_vote_group_prefix`);
  - **an unreadable value:** exact match.
- **No tenant scope (`withoutGlobalScopes`).** The old query went through `Code`'s tenant scope. With no tenant context that scope filters to the platform organisation, so a tenant election counted **0** (proved in RED). The election was already resolved and authorised; its UUID keeps the count in this election.
- **The current voter isn't counted:** the assessment runs before their code is marked voted (`VoteController.php:1491` vs `:1827`).
- **One count source, one meaning.** Both code entry and the cast use this assessment.

## Not changed by this step
- **The demo vote's own count** (`DemoVoteController::store`): it still uses `Code::where(...)`. The demo trust result is only logged, never enforced, and demo elections are outside System A.
- **The `Simplified` shadow pipeline:** its `NetworkBindingPolicy` compares `votesFromThisIp <= maxVotesPerIp` (raw N). It only produces a shadow signal (`simplified_overlay_signal`) and doesn't enforce.
- **`ParticipationDensityObservation`:** an observation overlay against raw N, not the refusal.
- **The finding's `limit_for_trust_level`:** it still reports N, not the effective limit. That's P7 (record: the finding reports the effective limit).

## Testing
```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Domain/Election/Security/NetworkTrustEvidenceTest.php
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Application/Election/Security/Policies/NetworkBindingPolicyTest.php
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Domain/Election/Constitution/NetworkThresholdInterpreterTest.php
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Application/Election/Security/TrustPolicyAttestationEvidenceTest.php
```
- **AC-11 boundaries:** one below / at / one above L, for N = 1, 6, 50, Unverified and Attested (data provider).
- **P5 with P6:** the record built from the evidence port drives `NetworkBindingPolicy`. At N = 6:
  - Unverified is refused at 3;
  - Attested is admitted at 3 and 5, and refused at 6.
- **Evidence:** `docs/publicdigit/election/evidence/2026-10-15-pbdigit286-p6-evidence/`.
  - The baseline before the change, 23 files: all OK.
  - RED: 12 failures across 4 files, all "at L" or the interpreter minimum.
  - GREEN plus regression: 24 files, all exit 0. The 3 deprecations are `PDO::MYSQL_ATTR_SSL_CA` (PHP 8.5), not this code.
- **Not run** (needs G-AUTH-T for `nrna_test`): the `nrna_test` tests the plan lists as changing at P6 (e.g. `SecurityWhitelistPolicyTest`: above the limit, 4 becomes 3). The isolated tests are in the next section.

## Pitfalls
- **Don't add the current voter to the count.** If a caller ever counted a vote before assessing, `>=` would refuse one vote early.
- **Don't change the two definitions of L separately** (`NetworkTrustEvidence::effectiveLimit`, `NetworkThresholdInterpreter`).

## Isolated tests (G-ISO for P6, 2026-10-15)
- **`SystemBNetworkCountTest` (D6):** the decision is the observation. With N = 2 (Unverified L = 1), one counting vote refuses the voter.
  - **Doesn't count:** another /64, another IPv4 address, another election, an unfinished code.
  - **Counts:** the same /64, the mapped IPv4 spelling, with no tenant context or a different one.
  - **At N = 6 with grouped IPv6 votes:** Unverified admitted at 0 and 2, refused at 3; Attested admitted at 3 and 5, refused at 6 and 7.
  - **RED:** 4 failures, all the defect. **GREEN:** 5 tests, 14 assertions.
- **`VerificationStoreFailureTest`** (P5 D3-E, the callers): with a throwing verification store, and each test proving the store was asked:
  - code entry gets the generic refusal;
  - the real vote stores no vote and doesn't consume participation;
  - the demo vote stores nothing and rolls back;
  - no internal detail reaches the voter;
  - a control vote with a working store counts.

  4 tests, 25 assertions.
- **Regression:**
  - `TrustPolicyEvaluatorTest` 5/9, `TrustPolicyEvaluatorD4Test` 3/7;
  - `IpVoteLimitFlowTest` 6/47, `IpVoteLimitAtCastTest` 8/34, `VoterAttestationEvidenceTest` 8/12;
  - all exit 0, guard confirmed.
- **Raw output:** evidence `04b`–`12`.

### Gap closure (2026-10-15, Principal authorisations C, A, B′, T1, T2)
- **Mutation proof (`23`–`26`):** three faults in the P5 adapter were each caught by their target test:
  - M1: a swallowed read failure;
  - M2: no `status` filter;
  - M3: no `election_id` filter.

  The adapter was restored after each, with sha256 `9e6bd98e…18ee`, and the restored adapter passes 9/23 (database-free) and 8/12 (isolated).
- **`VotingSecurityVerdictAdapterTest` (`14`):** its oracle uses `votesFrom` (D6). `a02` = 2 votes → Allowed, `a02b` = 3 votes → Denied (D7, N = 6, Unverified L = 3).
- **The plan's `nrna_test` P6 tests, run on the isolated database instead (`15`–`22`):** 7 of 8 files pass.
- **`SecurityEventRecorderTest`:** 3 of 5 fail because no event row is written.
  - **Probable cause (code reading, not runtime-confirmed):** PBDIGIT-42. The recorder's insert omits NOT NULL, no-default columns, and the recorder swallows the error (ADR_20260806_1340 §7).
  - **Separate from the IP limit:** no IP-limit decision depends on the event row.
- **Unreadable IP (`28`):** a value that isn't an IP address counts only by exact match. The same value counts; a different unreadable value doesn't. 1 test, 2 assertions.
- **Database-free regression (`27`):** 30 files, exit 0.

**Limitations:**
- not run: the full isolated suite, and anything on `nrna_test`;
- production enforcement not verified (the commit is not pushed, the deployment route is unknown);
- not accepted by the Principal.

**Traceability:** `PBDIGIT-286` P6, 2026-10-15; decision record §3.2 D6, D7, D7a; ADR-C §3.2; plan AC-10, AC-11. Not done: P7, P8, G-REL; the `nrna_test` tests (G-AUTH-T).
