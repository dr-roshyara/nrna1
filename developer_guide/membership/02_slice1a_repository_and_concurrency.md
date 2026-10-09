# 02: Slice 1a, repository port, concurrency and idempotency

**Story:** `PBDIGIT-259` · **Contract:** `docs/publicdigit/reviews/2026-10-09-membership-slice1a-contract.md` · **Namespace:** `App\Contexts\Membership\Application\Organisational`

## Purpose

Slice 1a puts the first application boundary around the pure Slice 0 kernel:
- **a repository port**;
- **two command handlers** (Admit, End);
- **the rules that keep commands correct under concurrency and retries.**

**There is still no database.** The port is proven against an in-memory reference adapter and a reusable contract test suite. The Slice 1b database adapter must pass that same suite.

## Where it fits

```text
HTTP / jobs (later)
   ↓  AdmitMember / EndMembership (DTOs)
Application/Organisational   ← this slice: handlers, DecideAndAppend, ports
   ↓  Member::decideAdmit / decideEnd (pure, Slice 0)
Domain/Organisational        ← Slice 0 kernel (unchanged)
   ↑  MemberRepositoryPort
Infrastructure               ← Slice 1b (database adapter); tests/Doubles/InMemoryMemberRepository now
```

## Key files

| File | Role |
|---|---|
| `Ports/MemberRepositoryPort.php` | `load`, `findByHolder`, `create` (atomic, INV-M), `appliedCommand`, `append` (version-checked, idempotency-checked, append-only) |
| `Ports/AppendResult.php`, `AppendOutcome.php`, `CreateResult.php`, `AppliedCommand.php` | port results |
| `Ports/AuthorityCheckPort.php`, `AuthorityDecision.php`, `MembershipAct.php` | consumer-side authority port. Governance implements it later (DS-4) |
| `AdmitMember.php`, `EndMembership.php` | command DTOs; `fingerprint()` covers everything except the key |
| `AdmitMemberHandler.php`, `EndMembershipHandler.php` | load or create the Member, then run the loop |
| `DecideAndAppend.php` | the shared bounded loop |
| `CommandOutcome.php`, `ApplicationRefusal.php`, `Appended.php`, `IdempotencyKey.php` | results and value objects |

## How it works

```text
appliedCommand(key)?  same fingerprint → return the original Appended      (IDEM-1: before deciding)
                      other fingerprint → Refused(IdempotencyKeyConflict)  (IDEM-2)
repeat ≤ 3:
   authority.check(actor, act)            → Refused(NotAuthorised)
   load (Admit: findByHolder | create; a lost race continues on the winner's Member)
   member.decide…(…)                       → Refused(domain RefusalReason)
   repository.append(decision, key, fp)
      Appended | AlreadyApplied           → done
      ConcurrencyConflict                 → discard the decision; start again from authorise
Refused(ConcurrentModification)
```

**The repository's append** does three things:
- **refuses a decision whose `basedOnVersion` is not the current version,** so a stale decision can never be stored;
- **checks the key again atomically** (IDEM-3);
- **assigns `seq = version + 1`** and a `recordedAt` that never decreases.

## Design decisions

- **The port lives in Application, not Domain.** It carries idempotency, an application concern, and the domain does not need a repository: `decide` is pure. This follows the precedent `Application/Membership/Ports/*RepositoryPort`.
- **No `based_on_version` column.** `seq = basedOnVersion + 1` holds by construction (contract S1-2).
- **Only successful appends are remembered under a key.** A refusal can be retried once the state changes (IDEM-4).
- **INV-FID vs retries:** the idempotency check runs before `decide`, so a retry returns its original result. A new key with a used FactId is a genuine `DuplicateFactId`.
- **Re-authorisation on every attempt:** the authority may have changed while the conflict happened.

## Testing

| Test | Proves |
|---|---|
| `MemberRepositoryContract` (abstract) / `InMemoryMemberRepositoryTest` | the port contract: 14 tests; reused by Slice 1b |
| `MembershipCommandHandlersTest` | handlers: creation, re-entry, refusals, authority, MemberNotFound, tenant isolation, IDEM-1 / 2 / 4, INV-FID |
| `ConcurrencyAndRaceTest` + `Support/InterleavingRepository` | a deterministic concurrent writer: re-decide, re-authorise, no reuse, creation race, concurrent retries, bounded retries |
| `ReferenceModelPropertyTest` + `Support/ReferenceMembershipModel` | 200 seeds × 40 commands against an independent array model |
| `tests/Architecture/MembershipSlice1aBoundaryTest` | dependencies, no framework, no other-context vocabulary, no update / delete on the port |

```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Application/Organisational/
```

## Pitfalls

- **Never "retry the append".** Always restart from authorise and load (mutation-tested).
- **A Slice 1b adapter must make `create` and `append` atomic** (unique (tenant, holder); version check + insert + key in one transaction). The in-memory adapter relies on single-threaded PHP.
- **Keys are tenant-scoped.** Do not reuse them across tenants as global ids.

**Traceability:** PBDIGIT-259 · contract R-1a-1…5, INV-M, INV-FID, IDEM-1…4 · Slice 0 contract §22 · EP-01 Rev 1 R3 / R9 / R14.
