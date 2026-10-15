# 07: The trust level comes from real verification evidence (PBDIGIT-286 P5)

**Story:** `PBDIGIT-286` phase P5 (AC-10; decisions D3, D3a, D3-E, D3-P, D4) · **Plan:** `docs/plans/20261014-1038-pbdigit286-constitutional-ip-limit-plan.md` · **Status:** implemented 2026-10-14; committed `2f998b8ae` (not pushed); **not accepted** (awaiting the Principal's decision); not released

**Code:**
- `app/Application/Election/Security/VoterAttestationEvidence.php`: the trust-only read port (new)
- `app/Infrastructure/Election/Security/DatabaseVoterAttestationEvidence.php`: the adapter (new)
- `app/Application/Election/Security/TrustPolicyEvaluator.php`: the port is injected; `buildAttestationRecord()` asks it
- `app/Providers/AppServiceProvider.php`: binding and evaluator wiring

**Tests:**
- `tests/Unit/Application/Election/Security/TrustPolicyAttestationEvidenceTest.php` (database-free, fake port)
- `tests/Isolated/Pbdigit274/Database/VoterAttestationEvidenceTest.php` (isolated database, G-ISO)

> **Release dependency: P5 must not be released without P6.** P5 raises an attested voter's effective limit L from ⌊N/2⌋ to N, but the refusal is still `votesFromThisIp > L` (`NetworkTrustEvidence::exceedsLimit`), so a network completes **L + 1** votes. With P5 alone, an attested network completes **7** at N = 6, one more than the approved 6 (decision record §3.3). **Update 2026-10-15:** P6 (D7 `>=`, D7a, and the D6 count; [guide 08](08_p6_boundary.md)) is committed with P5 in `2f998b8ae`, not accepted, so the code completes **6 / 3**. The rule "P5 not released without P6" now means: not without P6 **accepted**.

## Purpose
- **Before P5,** `TrustPolicyEvaluator::buildAttestationRecord()` hard-coded `attested = false`, so every voter was **Unverified**. The effective limit was `max(1, ⌊N/2⌋)` (3 at the default N = 6) and, because of `>`, a network could complete **4** votes, even for officer-verified voters.
- **Since P5,** a voter with **active** verification evidence in the election is **Attested**, and their effective limit is **N** (D4).

## The rules (decision record §3.2)
| Evidence in `voter_verifications` (this election, this voter) | Trust level | Decision |
|---|---|---|
| active, method `officer_attestation` (individual) | Attested | D3 |
| active, method `officer_bulk_attestation` | Attested | D3 = A |
| active row from before 2026-10-04 (legacy) | Attested | D3a |
| revoked | Unverified, **no denial** | D3-E |
| none | Unverified, **no denial** | D3-E |
| the store can't be read | **error propagates → the callers fail closed** | D3-E |

`registrarId` and `revoked` on the attestation record are never set (D3-E). RegistrarAttested and ContinuityVerified stay unreachable.

## How it works
```php
// TrustPolicyEvaluator::buildAttestationRecord()
$attested = $election !== null && $user !== null && $this->attestationEvidence !== null
    && $this->attestationEvidence->hasActiveAttestation((string) $election->id, (string) $user->id);
```
- **The adapter's predicate is identical to admission's** (`EloquentVotingEntitlementQuery::isAdmitted`): an `active` row for this election and voter, any method, any date.
- **Admission keeps its own copy and is unchanged.** The duplication is accepted technical debt (D3-P).
- **A query failure isn't caught** in the adapter, the evaluator or `TrustLegitimacySecurityVerdict`.
- **Callers of the evaluator** (from reading the code; no caller-level test exercises a store failure):

  | Caller | Path | On a read failure |
  |---|---|---|
  | `CodeController` (code entry) | via `TrustLegitimacySecurityVerdict` | `catch (\Throwable)` → generic refusal |
  | `VoteController::store` (real vote) | via `TrustLegitimacySecurityVerdict` | `catch (\Throwable)` → `failCastClosed`, rollback |
  | `DemoVoteController::store` (demo vote, `:1498`) | calls `evaluate()` directly, inside `DB::beginTransaction()` | `catch (\Exception)` (a `QueryException` is one) → rollback, error redirect |

- **The demo vote:** its trust result is only logged (`:1512`), never enforced. P5 adds a `voter_verifications` read there, so a failing read now aborts a demo vote. That changes availability, not security.
- **The read is unconditional:** it runs for every evaluation with an election and a user, including demo elections, `network_binding_strategy = 'none'` and whitelisted IPs. Whether to keep that is an open Principal decision (second review N7).
- **Why the attestation change affects only the limit:** `elections.voter_verification_required` doesn't exist in any migration, so `required` is always false, `isSatisfied()` stays true, and the verification policy raises no finding. **Only the trust level, and with it System B's effective limit, changes.**
- **The evaluator's constructor parameter is optional.** Without a source, every voter stays Unverified, the stricter limit, as before P5. Production wires the adapter (`AppServiceProvider`); a test proves the binding.

## Effect in numbers (D4, N = default 6)
Votes a network can complete before System B refuses the next voter:

| Voter | Effective limit L | Before P5 | With P5, before P6 | **With P6 `>=` (current working tree, 2026-10-15)** |
|---|---|---|---|---|
| Attested (active evidence) | N = 6 | not reachable (4, as Unverified) | **7** | 6 |
| Unverified (missing/revoked evidence) | max(1, ⌊N/2⌋) = 3 | 4 | **4** | 3 |

- **Before P6 the count was L + 1** because the comparison was `>`: with `votesFromThisIp` = L the voter was still admitted.
- **P5 itself changes neither the comparison nor the counting.** The comparison changed in the P6 boundary step (guide 08); the counting (D6) is still open.

## Testing
- **Database-free** (`vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php <path>`): `TrustPolicyAttestationEvidenceTest`.
  - **RED:** 6 errors (the port missing) + 1 failure → **GREEN 7 tests, 17 assertions** (no raw output saved for these two runs).
  - **After the review (F4):** a test that the real adapter, with every database target at the dead socket, **throws** instead of returning false. Run 2026-10-14: **8 tests, 18 assertions, 3 deprecations, exit 0** (raw output: evidence `02`). The deprecations are `PDO::MYSQL_ATTR_SSL_CA` under PHP 8.5, in `config/database.php` and the framework's copy (evidence `05`), not P5 code.
  - **Fixture fix:** a first GREEN attempt failed because `new User()` boots `HasAuditFields` (`app/Models/User.php:45`), which queries the schema. `Election` doesn't use that trait. The `DatabaseFreeTest` marker turned the query into a loud dead-socket error. The test now builds both models without their constructor (the test's docblock still names `Election` too; corrected here, test file not edited).
- **Isolated** (G-ISO: `. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/VoterAttestationEvidenceTest.php`): **7 tests, 8 assertions, 3 deprecations, exit 0** (first run, evidence `01`).
  - **Guard:** `current_database() = 'pbdigit274_isolated_test' confirmed`.
  - **Cases:** individual, bulk and legacy active rows count; revoked and missing don't; another election's or voter's row doesn't; end to end, the evaluator returns Attested vs Unverified.
  - **Added after the review (F4):** rows with status `pending`, `suspended`, `ACTIVE` or `''` don't count (only exactly `active` does). Run 2026-10-14: **8 tests, 12 assertions, 3 deprecations, exit 0**, guard confirmed (evidence `06`).
  - **Order:** written after the adapter, so it verifies rather than drives it.
- **Regression, database-free, 2026-10-14:** `TrustPolicyEvaluatorNetworkRestrictionTest` 5 tests, 8 assertions, exit 0 (evidence `03`); `LaravelBootIsGuardedTest` 18 tests, 30 assertions, 3 deprecations, exit 0 (evidence `04`).
- **Not database-free, so not run with `--no-configuration`:** `TrustPolicyEvaluatorTest` and `TrustPolicyEvaluatorD4Test` extend `Tests\TestCase` (the D4 test also uses `RefreshDatabase`). Running them needs a database authorisation.
- **Evidence:** `docs/publicdigit/election/evidence/2026-10-14-pbdigit286-p5-evidence/`.

## Pitfalls
- **Don't instantiate `User` in database-free tests:** its `HasAuditFields` trait queries the schema on boot. Other models may be safe (`TrustPolicyEvaluatorNetworkRestrictionTest` does `new Election()`); check the model's traits first.
- **Keep the two predicates (admission and trust) identical** until D3-P's technical debt is paid. A change to one is a change to both.
- **Without the P6 boundary step, L isn't the number of votes allowed:** the network completes L + 1.
- **Don't release P5 on its own.** Without P6 it relaxes System B for attested networks (see the box at the top).

**Traceability:** `PBDIGIT-286` P5, 2026-10-14; G-ISO + P5 authorised by the Principal (2026-10-14, 18:54 UTC); review findings F1–F7 addressed 2026-10-14. Not authorised: O-3, G-ADR-A, G-MIG, G-AUD2, G-REL, P1–P4, P6–P8.
