# Membership Slice 1a: Implementation Review

**Date:** 2026-10-09 · **Story:** `PBDIGIT-259` · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable). Engineering supplies the evidence; acceptance is the Principal Reviewer's.
**Contract:** `2026-10-09-membership-slice1a-contract.md`, with the disagreements S1-1 … S1-3 stated before implementation.
**Commits:**
- `def4f8e6f` (contract, story, plan);
- `2aa92e871` → `71139cfd3` (7 TDD commits).

Branch `knowelegeos-modelling`; nothing pushed.

## A. Implementation summary

`app/Contexts/Membership/Application/Organisational/` (21 files, pure PHP):

| Area | Classes |
|---|---|
| repository port | `MemberRepositoryPort` + `AppendResult`, `AppendOutcome`, `CreateResult`, `AppliedCommand` |
| authority port | `AuthorityCheckPort` + `AuthorityDecision`, `MembershipAct` |
| commands | `AdmitMember`, `EndMembership` |
| handlers | `AdmitMemberHandler`, `EndMembershipHandler`, sharing the loop `DecideAndAppend` |
| results | `CommandOutcome`, `ApplicationRefusal`, `Appended`, `IdempotencyKey` |

**Reference adapter:** `tests/Doubles/InMemoryMemberRepository`.

**Not implemented:**
- the database adapter (S1-1 → Slice 1b);
- every non-scope item of contract §7.

## B. Domain semantics preserved

- **Kernel untouched:** no file in `app/Contexts/Membership/Domain/Organisational/` changed in this slice (`git diff def4f8e6f -- app/Contexts/Membership/Domain/Organisational` is empty).
- **All 59 Slice 0 tests still pass** (101 tests across both slices + 9 architecture tests green).
- **The handlers only call** `Member::decideAdmit`, `decideEnd`, `fromLog`, `version` and `log`.
- **Replay equivalence:** IsMember on the reloaded log equals the independent reference model at every sampled time (§F).

## C. Persistence model

No tables exist yet (S1-1). The port fixes what any adapter must store:

| Datum | Why |
|---|---|
| (tenant, member id, holder), unique (tenant, holder) | INV-M; tenancy (R-1a-5) |
| per Member: ordered records (seq, recordedAt, fact) | the authoritative history (R-1a-4) |
| version = number of records | concurrency (R-1a-1) |
| per tenant: key → (fingerprint, Appended) | idempotency (IDEM-1…4) |

There is no `based_on_version` column (S1-2).

## D. Concurrency model

```text
normal:   authorise → load (v) → decide → append(expected v) → Appended(seq v+1)
conflict: append → ConcurrencyConflict → (discard the decision) → authorise → load (v+1) → decide → append(v+1)
          … bounded to 3 attempts → Refused(ConcurrentModification)
```

**Evidence (`ConcurrencyAndRaceTest`):**
- a concurrent End makes our End re-decide to `PeriodAlreadyEnded`, not a second End;
- the authority is called again (and a withdrawn authority refuses);
- every attempt carries a fresh decision object;
- creation race: one Member, and the loser continues on the winner's;
- concurrent identical retries: one fact;
- retries are bounded.

**Sensitivity:** reusing the stale decision → 4 failures; skipping re-authorisation → 3 failures (both reverted).

## E. Idempotency model

| Case | Result |
|---|---|
| same key, same command (fingerprint) | the original `Appended`; no new decision and no new fact (IDEM-1); also atomic inside append for concurrent retries (IDEM-3) |
| same key, different command | `IdempotencyKeyConflict` (IDEM-2) |
| new key, a FactId already in the Member's log | domain `DuplicateFactId` (INV-FID): a genuine duplicate, not a retry |
| a refused command retried later with the same key | decided afresh (IDEM-4: refusals are not remembered) |

## F. Test evidence

| Suite | Tests | Assertions |
|---|---:|---:|
| repository contract (in-memory) | 14 | 48 |
| handlers | 12 | 26 |
| concurrency and races | 6 | 14 |
| reference-model property (200 seeds × 40 commands; 8,000 commands) | 1 | (aggregated) |
| Slice 1a architecture | 4 | 4 |
| Slice 0 kernel + architecture (regression) | 59 + 5 | 5,675 + 11 |

**Property coverage** (counts of generated commands, not statistics):

| Outcome | Count |
|---|---:|
| appended | 2,282 |
| overlapping | 2,202 |
| member_not_found | 1,161 |
| end_not_after_period_start | 941 |
| period_already_ended | 568 |
| idempotency_key_conflict | 347 |
| period_not_found | 313 |
| duplicate_fact_id | 186 |

**Mutation of the fingerprint check:** detected at seed 1, step 16.

**There are no integration tests:** no database exists in this slice.

## G. Known failures

| Class | Result |
|---|---|
| **new Slice 1a failures** | **none** |
| pre-existing: `--testsuite Architecture` | 4 failures, identical on the baseline `def4f8e6f` (Election lifecycle / clock, ConstitutionalBoundary, C25) |
| pre-existing: `tests/Unit/Contexts/Membership` | 13 legacy test classes (committee, fee, legacy Member, lineage handlers), **the same set on the baseline**. Error counts 54 now vs 56 at the baseline; the difference is the known flaky `CommitteeSlugAvailabilityServiceTest` (database-dependent) |
| unrelated | none attributable to this slice |

## H. Architecture deviations

| # | Finding | Reason | Consequence | Recommendation | |
|---|---|---|---|---|---|
| S1-1 | the instruction asked for tables while forbidding Slice 1b | EP-01 Rev 1 R9 split; EP-Q6 open | no database in 1a; the contract suite is ready for 1b | Slice 1b = database adapter passing `MemberRepositoryContract` unchanged, after EP-Q6 and the organisation time zone | continue |
| S1-2 | `based_on_version` column | functionally dependent on seq | not stored | none | continue |
| S1-3 | idempotency key with a different command; refusals | unspecified | IDEM-2 / IDEM-4 defined as technical rules | reviewer to confirm | continue |
| H-1 | the authority port has no Governance implementation | DS-4 open | tests use a double; production wiring needs an adapter (Slice 2: the interim role-based adapter labelled technical-only) | as EP-01 Rev 1 R12 | continue |

**None of the stop conditions in instruction §25 occurred.**

## Final status

**GREEN WITH CONDITIONS.** All Slice 1a tests pass, the Slice 0 semantics are unchanged, and the regression shows no new failure. Conditions for acceptance:
1. the reviewer confirms S1-1 (no database in 1a);
2. the reviewer confirms S1-3 (IDEM-2 / IDEM-4 semantics);
3. administrative: the README index rows for PBDIGIT-258 / 259 (blocked by another session's uncommitted README edit).

**STOP.** Slice 1b and any Governance work are not started.
