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

## 6. Authority

`AuthorityCheckPort` (application, consumer-side): `check(TenantId, actor, act, MemberId?)` → granted + `AuthorityEvidence`.
- **No Governance implementation** (DS-4); tests use doubles.
- It is called again on every attempt (R-1a-2).

## 7. Scope / non-scope

- **In:** the port; the Admit / End application handlers; the in-memory reference adapter (test double); the reusable contract test suite; an independent reference-model property test; architecture tests.
- **Out:** database tables / Eloquent (Slice 1b); Correct / Annul / Void / Renew / ChangeType / Suspend / Restore; number; type; standing; finance; election; governance; committees; UI / API; migration; event sourcing; CQRS / projection / message bus.
