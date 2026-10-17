# PBDIGIT-286 constitutional IP limit: current state and decisions (investigation only)

**Date:** 2026-10-14 · **Author:** Claude (read-only investigation) · **HEAD:** `87f76d4e9`
**Status:** RESEARCH / INVESTIGATION. Authority: generated. Not approved; decides nothing.

**Note on scope:**
- The user stopped the commission part-way through ("not supposed to give you the task") and asked for the report in read-only mode.
- Nothing was implemented, staged or committed, and no tests were run.
- This file records the investigation at the user's request.
- §B is only partly worked out because the investigation stopped early.
- Election is otherwise out of scope for the cross-domain work.

**Evidence labels:**
- `[S]` verified from current source;
- `[T]` from another session's transcript;
- `[I]` inference;
- `[U]` not verified.

---

## A. Current state

**Git state at HEAD `87f76d4e9`:**
- **PBDIGIT-286 has no code changes.** The only commit for it is the backlog item (`bec1005b6`, created 2026-10-13, status OPEN, needs a PO decision).
- **PBDIGIT-287 is committed** (`bb06acb4d`, `f16eb8ba7`, `46354578c`). Not reopened.
- **The per-IP limit work from PBDIGIT-288 is already in the code** `[S]`: the lock at the vote, the count of the IP at the vote, and the `ip_blocked` audit entry.
- **The only uncommitted change outside docs** is the agreed deletion of `app/Http/Middleware/VoterSlugStep.php`. Left alone.

**Where D1–D10 come from:** they are not in the repository `[T]`. Their wording was taken from another session's transcript (`42e0287e…`):
- the proposal of 2026-10-13 18:15;
- its correction at 20:29;
- a follow-up on 2026-10-14 07:08 that proposed a new D11.

### Verification of the eight findings

All from source code; no tests were run.

| # | Finding | Result | Evidence |
|---|---|---|---|
| 1 | The constitutional limit defaults to 6 | **Confirmed** `[S]` | Column default 6 in migrations `2026_05_26_000000…:14` and `2026_05_27_120000…:22`. Code falls back to `?? 6` in `TrustPolicyEvaluator.php:143` and `Election.php:2383` (the frozen-at-creation snapshot). |
| 2 | The trust policy hard-codes "attested = false" and never reads verification records | **Confirmed** `[S]` | `TrustPolicyEvaluator::buildAttestationRecord()` sets `$attested = false` ("populated by controller from VoterVerification record", which never happens), no registrar and `revoked: false`. `required` reads `voter_verification_required`; that column probably doesn't exist (the migrations add `voter_verification_mode`) `[I]`. |
| 3 | The limit is scaled by trust level | **Confirmed** `[S]` | `NetworkTrustEvidence.php:64-69`: Unverified max(1, ⌊N/2⌋), Attested N, ContinuityVerified ⌊3N/2⌋, RegistrarAttested 2N. The trust level comes from `VerificationAttestationRecord::trustLevel()` (`:50-61`), so every voter is **Unverified**. A second scaling table, `NetworkThresholdInterpreter.php:27-30`, has no minimum of 1, so the two differ only when N = 1. |
| 4 | The constitutional check uses `>`; the per-IP limit (`IpVoteLimit`) uses `>=` | **Confirmed** `[S]` | `NetworkTrustEvidence.php:32` (`votes > limit`) vs `IpVoteLimit.php:154` (it refuses once votes ≥ limit). |
| 5 | Admins can't see or set the limit | **Confirmed** `[S]` | Outside the Election model, the trust evaluator and the snapshot, nothing in `app`, `routes` or `resources/js` reads or writes `max_votes_per_ip` or `network_binding_strategy`. Neither column is mass-assignable. The settings request only validates the per-IP limit (`UpdateElectionSettingsRequest.php:26`, range 1–50). |
| 6 | It is switched on independently of the admin's "IP restriction" toggle | **Confirmed** `[S]` | It is on whenever `network_binding_strategy !== 'none'` (`TrustPolicyEvaluator.php:145`, default `ip_count`). The toggle (`ip_restriction_enabled`) only affects `IpVoteLimit` (`:58-59`). |
| 7 | Voters get a vague message, and the refusal isn't audited | **Confirmed** `[S]` | Code entry: "…cannot be granted based on constitutional verification…" (`CodeController.php:751`). The vote: same wording (`VoteController.php:1524`). Only application logs record it (the `CodeController` entry-refusal warning; `VoteController.php:1502,1532`). There is **no `ip_blocked` audit entry** for admins; only `IpVoteLimit::recordRefusal` writes one. The policy's report shows `limit_for_trust_level = N`, not the effective limit (`NetworkBindingPolicy.php:58`). |
| 8 | Neither setting is locked once the election is ready for voting | **Confirmed** `[S]` | `Election::SECURITY_POLICY_FIELDS` (`Election.php:63-69`) lists neither column. The snapshot is frozen at creation (`:2378-2395`), but enforcement reads the live column (`TrustPolicyEvaluator.php:143`). |

### Order of the checks `[S]`

- **Code entry:** the constitutional check runs first (`refuseEntry`, `CodeController.php:727`), then the per-IP limit (`verifyCode`, `:923`). So "the stricter wins" holds here.
- **The vote:** the constitutional assessment runs at the top of `store()` (`VoteController.php:1491`), **outside the transaction and the lock**. The per-IP limit runs later, under the per-network advisory lock (`:1812`, real elections only).

### How the two limits count `[S]`

- The constitutional limit counts completed votes whose `codes.client_ip` **exactly** equals the request IP (`TrustLegitimacySecurityVerdict.php:68-71`).
- The per-IP limit groups IPv6 addresses by /64.

### Verification records (`voter_verifications`) `[S]`

- **One row per voter per election** (unique on `election_id, user_id`). Status is `active` or `revoked`, with `revoked_by`, `revoked_at` and `revocation_reason`.
- **Revocation reasons:** `suspension_confirmed` and `revoked_by_officer`.
- **Method:** `officer_attestation` or `officer_bulk_attestation`; verifier role: Chief or Deputy Chief.
- **There is no expiry and no registrar field.** RegistrarAttested can't be told apart from Attested, and ContinuityVerified is never produced.
- **Re-verification overwrites the row** (`updateOrCreate`, `EloquentIdentityEvidenceStore.php:29`), so earlier method and revocation history is lost.
- **Rows that existed before 2026-10-04** got the column default `officer_attestation` (`2026_10_04_000002…:25`), so their method is not reliable evidence of an individual check `[I]`.
- **Existing rule (PBDIGIT-213 C4-D2, `VerificationMethod.php:6-11`):** both methods have the *same admission effect*, and the method "is never an input of the entitlement decision". That rule covers admission only. It does not decide the trust level.

### Election creation `[S]`

`ElectionManagementController::store` (`:124-157`) accepts **no security fields**. Setting the value at creation would need a new field, new validation, and an explicit assignment, because the column isn't mass-assignable.

---

## B. D1–D10 decisions (original wording abbreviated)

| # | Original question / recommendation `[T]` | Corrected facts | Needs approval |
|---|---|---|---|
| D1 | When admins can set it: (a) at creation only, (b) until locked, re-taking the snapshot (needs an ADR), (c) until locked without re-taking it (not recommended). Recommendation: (a). | Under (a), existing elections keep 6 forever, and the creation form must gain the field. | **Yes** |
| D2 | A new narrow `NetworkLimitReached` verdict with no number (a second exception to E2-D4, like IP binding), or the per-IP wording with the number. | Unchanged. | Previously accepted; confirm |
| D3 | Officer verification = Attested (N) or RegistrarAttested (2N)? Bulk the same as individual? Recommendation: Attested, bulk the same. | Registrar isn't representable, so Attested is the only option backed by data. Bulk vs individual is a **policy choice**. The data records the method, except older rows (see §A). The PBDIGIT-213 precedent covers admission, not trust. | **Yes** |
| D4 | A revoked verification counts as "not attested", not a denial. | Must stay `revoked: false` in the record. Setting it true would trigger the CRITICAL denial in `VerificationAttestationPolicy`. | Previously accepted |
| D5 | The live scaling table (minimum 1) is the official one; `NetworkThresholdInterpreter` follows it. | They differ only at N = 1. | Previously accepted |
| D6 | Keep the default N = 6 and show the effective numbers. | — | Previously accepted |
| D7 | Align to "up to N" (`>=`) or keep `>` (N + 1). Recommendation: align. | Votes allowed per network today = effective limit + 1. Aligning makes it **exactly one vote stricter at every trust level** (table below). | **Yes** |
| D8 | IPv6 grouped per /64. | Today it counts exact addresses. | Previously accepted |
| D9 | The race at the vote: (i) accept it, or (ii) re-count under the lock (needs an ADR, touches PBDIGIT-240). Recommendation: (i). | **Correction:** the constitutional check runs *outside* the lock. Option (i) relies on the per-IP limit under its lock. Its limitation: when the constitutional limit is the stricter one, voters on one network voting at the same moment can exceed it, up to the per-IP limit. | **Yes** |
| D10 | Show the effective limits and the both-limits note next to the IP restriction block. | — | Previously accepted |
| (D11) | Proposed later: may admins switch the constitutional limit off (`network_binding_strategy = none`)? | Not part of the original ten. | Open |

### D7 in numbers

Votes allowed from one network before the next voter is refused:

| Trust level (N = 6) | Today (`>`) | Comparison changed only (D7) | Wiring only (D3) | Wiring + D7 |
|---|---|---|---|---|
| Unverified (3) | 4 | 3 | 4 | 3 |
| Attested (6) | — (nobody reaches it) | — | 7 | 6 |
| ContinuityVerified (9) / RegistrarAttested (12) | unreachable | unreachable | unreachable | unreachable |

- **With N = 1:** today 2, after D7 1, for every level.
- **The general rule:** the effective limit L becomes L instead of L + 1.
- **The two changes pull in opposite directions:** wiring verification *loosens* the limit for verified voters, and D7 *tightens* it by one.

### Readiness safeguard (whatever D1 decides)

- Add both columns to `SECURITY_POLICY_FIELDS`.
- **Invariant:** once the election is locked, changing either column raises a `LogicException`, and the settings page refuses the change.
- **Tests:** an attempted change after the election is ready for voting is refused and leaves the value unchanged. The same change before that is allowed only if D1 allows editing.

### The invariant at the vote

"Both limits stay separate and the stricter wins" holds at code entry. At the vote, under concurrency, only the per-IP limit is guaranteed, because only it runs under the lock (D9). The resolution is either to document this (D9 (i)) or to write an ADR (D9 (ii)). It must not be changed silently.

---

## C. Implementation plan (outline only; not approved)

1. **Attestation wiring:** an active verification counts as attested, using the same rule as admission. Then the bulk policy (D3) and revoked = not attested (D4).
2. **Thresholds:** D7 and D5 together, with IPv6 /64 grouping (D8).
3. **New verdict and messages** (D2), plus an `ip_blocked` entry with `limit_source: constitutional` and the trust level, and the corrected effective limit in the report.
4. **Settings and creation:** per D1, plus the readiness lock and the explanation text (D10).

**Tests that must change on purpose (RED → GREEN):**
- `SecurityPolicySurfaceCharacterizationTest` (C2);
- both `NetworkBindingPolicyTest` files;
- `NetworkThresholdInterpreterTest`;
- `TrustPolicyEvaluatorNetworkRestrictionTest`;
- `CodeEntryAuthorizationTest`;
- `ConstitutionalImmutabilityTest`.

**New boundary tests:** one vote below, at, and above the limit for each trust level; missing, revoked and re-verified records; bulk vs individual; two elections with different N; whitelist and IP binding unchanged; per-IP limit regressions; concurrent attempts at the vote (to document the D9 limitation).

**Database:** only `pbdigit274_isolated_test`, with a database-identity guard before any setup.

---

## D. Decisions needed

| # | Decision | Recommendation |
|---|---|---|
| **D1** | When admins can set the limit | (a) at creation only, unless admins need to change it later; then (b) with an ADR |
| **D3** | Bulk vs individual attestation | Bulk counts the same as individual: simplest and consistent with admission. The alternative (bulk = Unverified) is stricter and needs the old rows' method treated as unknown. |
| **D7** | Threshold comparison | Align to `>=`, decided together with D3 |
| **D9** | The race at the vote | Accept it and document the invariant as above. The alternative needs an ADR. |
| **D11** | May admins switch the limit off? | Open |

---

## E. Stop point and limits

- Nothing is implemented; there are no commits.
- PBDIGIT-286 remains OPEN.
- **Not verified `[U]`:**
  - the whitelist "known defect" note in `TrustLegitimacySecurityVerdict.php:38` (it may be out of date);
  - which columns the snapshot immutability guard (`Election.php:~2312`) covers;
  - whether `voter_verification_required` exists in the deployed schema.
