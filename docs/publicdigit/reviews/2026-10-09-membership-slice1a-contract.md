# Membership Slice 1a: Repository and Concurrency Contract

**Date:** 2026-10-09 · **Story:** `PBDIGIT-259` · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Instruction:** `docs/publicdigit/membership/architecture/brainstorming/202061009_0812_implementation_03.md` (Principal Reviewer). It confirms **D-1 = INV-FID** (FactIds unique within a Member's log).
**Baseline:**
- Slice 0 contract §22 (R-1a-1 … R-1a-5);
- EP-01 Rev 1 R3 / R9 / R14;
- the principal design review §17;
- the Slice 0 kernel `app/Contexts/Membership/Domain/Organisational/`.

## 0. Disagreements with the instruction (stated first; the slice follows the corrected scope)

| # | Instruction | Position | Reason |
|---|---|---|---|
| S1-1 | §7 / §13 / §23: create the tables `org_memberships` / `org_membership_facts`, with database-enforced uniqueness. But §28: "do not begin Slice 1b" | **Slice 1a has no database.** It delivers the repository **port**, the application handlers (conflict re-decide, idempotency, creation race) and a **reusable contract test suite**, run against an in-memory reference adapter. The database adapter is **Slice 1b** and must pass the *same* suite | EP-01 Rev 1 R9 (approved split 1a / 1b). 1b is gated by EP-Q6 (date vs instant) and an organisation time zone that is usable: the time-point representation must not be fixed in a table before that decision |
| S1-2 | §14: persist `based_on_version` | **Not a separate datum.** R-1a-1 makes `seq(f) = basedOnVersion + 1` at every successful append, so storing `seq` preserves it. A second column would be a functional-dependency duplicate | minimality |
| S1-3 | §11: "same idempotency key → same result" | made precise as two technical rules (not business rules): (a) same key + **different** command → refused `IdempotencyKeyConflict`; (b) only **appended** outcomes are remembered, so a refusal has no side effect and is not replayed | without (a) a key could silently mask a different command; without (b) a refusal would block a later valid retry |

## 1. Inputs

- **`AdmitMember`:** tenant, person, memberIdIfNew, factId, effectiveAt, initialTermUntil?, actor, declarationRef?, decidedAt?, idempotencyKey.
- **`EndMembership`:** tenant, memberId, factId, periodId, effectiveAt, endKind, actor, decidedAt?, idempotencyKey.

All ids are supplied by the caller (as in Slice 0).

## 2. Outputs

`CommandOutcome`, one of:
- **Appended**(memberId, factId, seq, recordedAt);
- **Refused**(domain `RefusalReason` | application reason ∈ {NotAuthorised, MemberNotFound, ConcurrentModification, IdempotencyKeyConflict}).

## 3. Port (`Application\Organisational\Ports\MemberRepositoryPort`)

```text
load(TenantId, MemberId)            → ?Member                       (only within the tenant)
findByHolder(TenantId, PersonRef)   → ?Member
create(TenantId, MemberId, PersonRef) → Created(Member) | HolderExists(MemberId)        (INV-M, atomic)
appliedCommand(TenantId, IdempotencyKey) → ?(fingerprint, Appended)
append(TenantId, MemberId, Decision accepted, IdempotencyKey, fingerprint)
    → Appended(seq = basedOnVersion+1, recordedAt)
    | ConcurrencyConflict                    (basedOnVersion ≠ current version)
    | AlreadyApplied(original Appended)      (key seen with the same fingerprint)
    | IdempotencyKeyConflict                 (key seen with another fingerprint)
    | MemberNotFound
    | ValidTimeNotStorable                   (a finite valid time outside 0001-01-01 … 9999-12-31; R-1a-6; amended 2026-10-10)
```

The port has no update and no delete.

## 4. Invariants and obligations

| ID | Obligation |
|---|---|
| R-1a-1 | an accepted decision is appended only at `basedOnVersion = current version`; `seq = version + 1` |
| R-1a-2 | **never reuse** a stale decision: on conflict, reload → re-authorise → re-guard (domain decide) → G\* → append; bounded to 3 attempts, then `ConcurrentModification` |
| R-1a-3 | `recordedAt` is assigned at append and is **non-decreasing in seq** per Member (the adapter clamps a backward clock) |
| R-1a-4 | append-only: an earlier record is never changed or removed |
| R-1a-5 | an explicit `TenantId` on every operation; nothing crosses tenants |
| R-1a-6 | **Storable valid time** (amended 2026-10-10). Every finite valid time of a new command's fact (Admit `effectiveAt`; Admit `initialTermUntil` when finite; End `effectiveAt`) lies within 0001-01-01 … 9999-12-31 inclusive (ordinals −719162 … 2932896, `Ports\StorableValidTime`). `append` checks this **after the idempotency-key lookup and before the version comparison**, so `ValidTimeNotStorable` takes precedence over `ConcurrencyConflict` and `MemberNotFound`. A technical persistence capability, not a business calendar rule; `TimePoint` is unchanged. The refusal is not remembered (IDEM-4) and is not retried |
| INV-M | at most one Member per (TenantId, PersonRef); `create` is atomic, and the loser receives `HolderExists` and proceeds with the existing Member |
| INV-FID | FactIds are unique within a Member's log (domain guard, Slice 0) |
| IDEM-1 | the idempotency check runs **before** decide: a retry returns the original `Appended` without a second fact. **Amended 2026-10-09 (PBDIGIT-260, PO-authorised):** the check is repeated after a domain refusal, because an identical command may have been applied between the pre-check and the load |
| IDEM-2 | the same key with another fingerprint → `IdempotencyKeyConflict` (S1-3a) |
| IDEM-3 | the key check is repeated **atomically inside append**, so two concurrent identical retries append once |
| IDEM-4 | only appended outcomes are remembered (S1-3b) |

## 5. Failure semantics

| Situation | Outcome |
|---|---|
| domain refusal (Slice 0 guards / invariants) | `Refused(RefusalReason)`; nothing appended; not remembered |
| actor not authorised (the AuthorityCheck port says no) | `Refused(NotAuthorised)` |
| End for an unknown Member (in this tenant) | `Refused(MemberNotFound)` |
| conflicts beyond 3 attempts | `Refused(ConcurrentModification)` |
| a valid time outside the storable range (R-1a-6; amended 2026-10-10) | `Refused(ValidTimeNotStorable)`; nothing appended; not retried; not remembered |

## 6. Authority

`AuthorityCheckPort` (application, consumer-side): `check(TenantId, actor, act, MemberId?)` → granted + `AuthorityEvidence`.
- **No Governance implementation** (DS-4); tests use doubles.
- It is called again on every attempt (R-1a-2).

## 7. Scope / non-scope

- **In:** the port; the Admit / End application handlers; the in-memory reference adapter (test double); the reusable contract test suite; an independent reference-model property test; architecture tests.
- **Out:** database tables / Eloquent (Slice 1b); Correct / Annul / Void / Renew / ChangeType / Suspend / Restore; number; type; standing; finance; election; governance; committees; UI / API; migration; event sourcing; CQRS / projection / message bus.

**F-3 clarification (Principal Reviewer disposition, 2026-10-09; implementation pending `PBDIGIT-264` authorisation):**
- `create` with a MemberId that already belongs to **another** holder is an **infrastructure failure** (an exception, no state change), not a port outcome.
- In `append`, the **idempotency-key lookup precedes** every other check.
- Source: `2026-10-09-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md` §10. The port file is unchanged.

## Amendment (2026-10-10): R-1a-6 storable valid time (Option E, PBDIGIT-264)

- **Authority:** Human PO / ARB decisions 1–6 and 8, recorded verbatim in the Option E gate register of `docs/plans/20261010-1830-membership-pg-verification-and-far-date-plan.md`.
- **Added:** R-1a-6 (§4), the outcome `ValidTimeNotStorable` (§3) and its failure row (§5).
- **Order in `append`:** idempotency-key lookup → R-1a-6 → version comparison → writes. The F-3 clause above is unchanged.
- **Parity scope:** the date contract only. The test double still checks `MemberNotFound` before the key; that ordering remains `PBDIGIT-264` F-3 (accepted as a limitation for this increment).
- **Tests:** E1–E5 in `MemberRepositoryContract`; E7, E8 in `MembershipCommandHandlersTest`; E9 `AppendOutcomeMappingTest`; E10 `tests/Architecture/MembershipStorableValidTimeCoverageTest`.
