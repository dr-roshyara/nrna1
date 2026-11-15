# 27: The authority contract, aligned with Governance's AuthorityCheck (lifecycle S2-3a), PBDIGIT-354

**Decisions:** D-238 (Q-3 Option A) · accepted Governance completion D-208 §1–§2, C-1a, C-1b · D-203 (`ownAct`) · D-167, D-181 (Undetermined ≠ No) · RA line 72 (authority evidence on the fact) · blueprint G-3.
**Plan:** `docs/plans/20261115-0510-release-1-build-slice-s2-membership-lifecycle-plan.md`, §7b and S2-3.
**Evidence:** `docs/publicdigit/membership/evidence/2026-11-15-pbdigit354-s2-3a/`.

## Business purpose

- **Before a member can resign by themselves (S2-3b), Governance must be told whose membership it is.** It then answers "the person acting is the member concerned", and Membership must be able to record that answer as such.
- **S2-3a changes how Membership asks and what it records, not who may do what.** Every existing act behaves as before, with one deliberate exception: **an exclusion can no longer be requested directly.** The accepted design reserves it for a decision pushed from Governance (S2-6).

## The contract

**Request** (`Application/Organisational/Ports/AuthorityRequest`):

| Field | Meaning |
|---|---|
| `act` | `MembershipAct`, by Governance's names: `admit-member`, `record-resignation`, `record-death`, `renew-membership`, `suspend-membership`, `restore-membership` |
| `actor` | the acting `PersonRef` (from Identity, R-1) |
| `subject` | the member concerned (`requestRef`). Null only when the member is unknown |
| `period` | the period acted on, where the command names one (End) |
| `at` | the decision date the authority is evaluated for (D-12) |
| `declaredEffectiveAt` | a resignation's declared effective date; **only** for `record-resignation` |
| `requestId` | the command's idempotency key |

**Answer** (`AuthorityDecision` wrapping `Domain/Organisational/AuthorityEvidence`):
- **The six ratified fields, unchanged:** `actor`, `capacity`, `ruleRef`, `policyVersion`, `result`, `checkedAt`. Plus `competenceVerified`, as before.
- **Plus `basis()`** (`AuthorityBasis`), or null:

| Basis field | Meaning |
|---|---|
| `outcome` | `AuthorityOutcome`: Authorised · NotAuthorised · **Undetermined** |
| `reason` | required for NotAuthorised and Undetermined; absent for Authorised |
| `evaluatedFor` | the date evaluated for (`at`) |
| `competenceKind` | `CompetenceKind`: worked-out, asserted, gap-declared, contradiction, not-established, interim-role, **own-act**. Required when Authorised |
| `decisionRef`, `evidence` | the Resolution part or act; references or fingerprints |

**Consistency:** `result` must agree with the basis outcome; otherwise the evidence cannot be built, and a stored row fails to load.

## The loop (`DecideAndAppend::run`), per attempt

```
find (READ-ONLY) → request (or a boundary refusal) → authorise → [create, admission only] → decide → append
```

| Step | Rule |
|---|---|
| find | read-only. **Nothing is created before the answer:** a refused admission leaves no Member record |
| request | a `RefusalReason` here refuses **before anyone is asked**: a term-less admission or renewal; End with an unknown kind, an **exclusion** (`ExclusionIsPushOnly`) or **`legacy`** |
| authorise | NotAuthorised → `ApplicationRefusal::NotAuthorised`; Undetermined → `AuthorityUndetermined`. Both come **before** "member not found", so an unauthorised actor cannot probe whether a member exists |
| `ownAct` | counts **only** for `record-resignation` with actor = subject (D-203); otherwise `OwnActNotApplicable` |
| create | admission only, after authorisation (INV-M) |

End's act by kind:

| Kind | Asked |
|---|---|
| resignation | `record-resignation`, with `declaredEffectiveAt` = the End's date |
| death | `record-death` |
| exclusion | **nothing:** `ExclusionIsPushOnly` |
| legacy | **nothing:** `LegacyEndRequiresAttestedHistory` (as before) |

## Persistence: no migration

- The basis is stored **inside the existing `provenance` jsonb**, under `authorityEvidence.basis` (`ProvenanceJson`).
- **Historical rows have no `basis` key and are read with `basis() === null`: "not recorded".** Nothing is inferred, backfilled or rewritten.
- With no basis, `AuthorityDecision::outcome()` follows `result` (true → Authorised, false → NotAuthorised). **Undetermined is never inferred.**
- A damaged basis (unknown outcome or kind, wrong types, `result` disagreeing with the outcome) fails as `\UnexpectedValueException`, so the adapter raises `StoredMemberLogInvalid` (as for any damaged provenance).

## Adapters

- **`InterimRoleAuthority`** (unbound, G-3): answers with a basis:
  - an admission by an owner or admin → Authorised, `interim-role`;
  - everything else → NotAuthorised with a reason;
  - never Undetermined, never `ownAct`.
- **No production binding:** none of the six use cases can be built by the container until Governance S3 (`MembershipThroughRoutingTest`).
- **`FakeAuthority`** (tests) records **no** basis on purpose, so the bulk of the tests exercise the historical shape. `AuthorityContractTest` covers answers with a basis.

## Testing

| Suite | What |
|---|---|
| `tests/Unit/Contexts/Membership/Domain/Organisational/AuthorityBasisTest.php` (10) | the value object, outcomes, kinds, consistency |
| `tests/Unit/…/Application/Organisational/AuthorityContractTest.php` (12) | request fields per act, three outcomes, `ownAct`, End's act per kind, pull exclusion, no existence leak, no premature creation |
| `tests/Unit/…/Infrastructure/Organisational/ProvenanceJsonTest.php` (+11) | basis round trip; historical shape; 7 damaged bases refused |
| `tests/Unit/…/Infrastructure/Organisational/InterimRoleAuthorityTest.php` | the adapter's basis |
| `tests/Feature/Contexts/Membership/Organisational/PostgresAuthorityEvidenceTest.php` (3) | jsonb storage and reload; a historical-shape row loads; a pull exclusion writes nothing |
| `tests/Feature/Contexts/Platform/MembershipThroughRoutingTest.php::test_s2_3a_…` | in A's own database through routing; B untouched; every use case fail-closed |

## Pitfalls

- **Do not add a `MembershipAct` for `exclude-member`.** Exclusion arrives by push (S2-6).
- **A new act kind must be answered by `InterimRoleAuthority`** (total `match`; `test_every_act_kind_has_an_answer`).
- **In raw SQL over jsonb, use `jsonb_exists()`, not `?`:** PDO reads `?` as a placeholder.
- **Never construct a basis to "repair" old rows.** Their absence is the historical truth.

## Next: S2-3b (after review)

Resign now (self-service via `ownAct`, or by a recorder), and record a death with an evidence reference, on this contract.

**Traceability:** PBDIGIT-354 S2-3a · D-238 · D-208 · D-203 · D-167 · D-181 · D-216 (G-3).
