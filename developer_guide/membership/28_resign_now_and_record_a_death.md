# 28: Resign now and record a death (lifecycle S2-3b), PBDIGIT-354

**Decisions:** D-194, D-201 (resign now; a later date is S2-4), D-203 (`ownAct`), accepted D-208 §2 (`record-resignation`, `record-death`), C-1a, GC-1 … GC-4 · D-205 (3) · D-238 (the contract, guide 27) · M-TDD-01 §5.1.
**Plan:** `docs/plans/20261115-0510-release-1-build-slice-s2-membership-lifecycle-plan.md`, step S2-3.
**Evidence:** `docs/publicdigit/membership/evidence/2026-11-15-pbdigit354-s2-3b/`.

## Business purpose

- **A member can resign by themselves,** effective on the day they declare it. Governance answers "it is the member's own act" (`ownAct`). Membership accepts that answer **only** when the person acting is the member concerned.
- **An officer can record a resignation** with their own recording authority.
- **An officer can record a death,** and **must cite the evidence** (a reference to a certificate or a notice), as the accepted design requires (GC-4). Without it, nothing is asked and nothing is recorded.
- **Death is not resignation.** It is a separate act (`record-death`), never authorised by `ownAct`, and evaluated for the recording date.

## Components

| Layer | Class | Role |
|---|---|---|
| Application | `ResignMembership` / `ResignMembershipHandler` | resign now: effective = the declaration date; the period is the current one at that date |
| | `RecordDeath` / `RecordDeathHandler` | record a death: date of death (effective), recording date (decision), **evidence reference (required)** |
| | `EndMembershipHandler` | unchanged for a recorder's resignation; **`death` is now refused** (`DeathEvidenceRequired`): it carries no evidence; use `RecordDeath` |
| | `Ports/AuthorityRequest` | gains `evidenceRef`: **required for `record-death`, forbidden otherwise** |
| Domain | `RefusalReason::DeathEvidenceRequired` | the boundary refusal |
| Persistence | `ProvenanceJson` (unchanged) | the death's evidence reference is the fact's provenance `declarationRef`: the document the record rests on |

**No migration.** `End` with kinds `resignation` / `death` already exists; the provenance jsonb already has `declarationRef`.

## The two acts

| | Resign now | Record a death |
|---|---|---|
| act asked | `record-resignation` | `record-death` |
| subject / period | the member / the current period at the declaration date | the member / the current period at the date of death |
| evaluated for (`at`) | the declaration date | **the recording date** |
| declared effective date | the declaration date | — |
| evidence reference | — | **required** |
| `ownAct` | accepted only if actor = member | **never** |
| fact | `End(resignation)` effective on the declaration date | `End(death)` effective on the date of death; **not recorded before it happened** (`EffectiveDateAfterDecisionDate`) |
| no current period | `PeriodNotFound` (before admission, or already ended) | `PeriodNotFound` |

**Unchanged from guide 27:**
- NotAuthorised and **Undetermined** record nothing; Undetermined is its own refusal ("the fact waits", GC-4);
- an unauthorised actor cannot learn whether a member exists;
- idempotency per key;
- a lapsed membership can be resigned: lapsed is not ended (INV-3).

## Decisions taken in the code (reviewable)

1. **`evidenceRef` added to `AuthorityRequest`.** D-208 §2 names the evidence reference in the `record-death` request. **S2-3a had omitted it: a gap, closed additively.**
2. **The death evidence is stored as `declarationRef`**, the provenance field for "the document the act rests on" (RA line 72). No new column.
3. **Two resignation commands:** `ResignMembership` (resign now) and `EndMembership(resignation)` (a recorder, with an explicit period and a possibly earlier effective date). Both ask `record-resignation`.

## Usage

```php
(new ResignMembershipHandler($repository, $authority))->handle(new ResignMembership(
    tenantId: $organisationId, memberId: 'M1', factId: 'E1', declarationDate: $today,
    actor: $membersPersonRef, decidedAt: null, idempotencyKey: $key,
));

(new RecordDeathHandler($repository, $authority))->handle(new RecordDeath(
    tenantId: $organisationId, memberId: 'M1', factId: 'E2', dateOfDeath: $dayOfDeath, recordingDate: $today,
    evidenceRef: 'death-certificate:2026/481', actor: $officerPersonRef, decidedAt: null, idempotencyKey: $key2,
));
```

**Production:** neither handler can be built until Governance S3 binds `AuthorityCheckPort` (`MembershipThroughRoutingTest`).

## Testing

| Suite | What |
|---|---|
| `tests/Unit/Contexts/Membership/Application/Organisational/ResignAndRecordDeathTest.php` (13) | both acts, `ownAct`, recorder, evidence, outcomes, no leak, idempotency, isolation, `EndMembership(death)` refused |
| `tests/Feature/Contexts/Membership/Organisational/PostgresResignAndDeathTest.php` (3) | stored and reloaded; evidence in the jsonb; C1 ends the coverage; a refused death writes nothing |
| `MembershipThroughRoutingTest::test_s2_3b_…` | a death in A's own database; B's member unaffected; every use case fail-closed |

## Pitfalls

- **Read optional request fields null-safely in tests** (`?->`). A mutant once crashed a test instead of failing it.
- **Do not route a death through `EndMembership`:** it has no evidence field, by design.

## Next

S2-4: resignation from a later date (scheduled; withdrawal; execution in the closing step; D-205's one exception; D-223).

**Traceability:** PBDIGIT-354 S2-3b · D-194 · D-201 · D-203 · D-208 · D-238 · D-205.
