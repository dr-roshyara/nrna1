# Membership Slice 1b: Contract (PostgreSQL database adapter)

**Date:** 2026-10-09 · **Story:** `PBDIGIT-260` · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Status:** ✅ **APPROVED by the PO (2026-10-09)** with clarifications 1–6 (migration wording updated in §5). Implementation authorised for Slice 1b only.
**Derived from:** readiness review `2026-10-09-membership-slice1b-readiness-review.md` (§E design, §I / §J decisions) · Slice 1a contract `2026-10-09-membership-slice1a-contract.md` · Slice 0 contract `2026-10-08-membership-slice0-semantic-design-contract.md` · EP-01 Rev 1.

---

## 1. Decisions applied (authoritative)

| ID | Decision | Source |
|---|---|---|
| **EP-Q6** | **T1, calendar dates.** "Membership admission, term boundaries and ending are date-based; intra-day membership transitions are not supported. `decidedAt` and `recordedAt` remain exact instants. Preserve the existing Slice 0 invariants and do not infer or migrate historical dates in Slice 1b." (Ratification remains the PO's.) | PO, 2026-10-09 (readiness review §J.1) |
| **DB-1** | **PostgreSQL only.** "Do not implement MySQL compatibility or dual-database support in Slice 1b … The adapter must fail fast with a clear configuration error if used with an unsupported database driver. Record the remaining MySQL fallback and legacy migration branches as separate technical-debt items." | PO, 2026-10-09 |
| B-1 | the port stays unchanged; a fact-id unique violation remains an infrastructure error | instruction `20261009_0922…` §4 point 2; readiness B-1 |
| B-2 | race evidence = single-statement atomicity + PostgreSQL row locks; an optional two-connection test is **additional**, not a substitute | instruction §4 point 3; readiness B-2 |
| B-3 | the shared contract suite becomes a trait (no semantic change) | instruction §7; readiness B-3 |
| B-4 | typed temporal columns; `jsonb` for provenance only | instruction §4; readiness B-4 |
| B-6 | **not included:** no database trigger (optional, not authorised) | — |

## 2. Scope

**In:**
- `PostgresMemberRepository implements MemberRepositoryPort`;
- one migration creating three tables;
- the temporal and provenance mappings;
- the trait refactor of the contract suite;
- database tests;
- architecture tests;
- two technical-debt backlog items (record only).

**Out:**
- any change to the domain kernel, the port, the handlers or `DecideAndAppend`;
- MySQL or dual support;
- legacy data migration or date inference;
- Eloquent models for the new tables;
- event sourcing, generic persistence, projections;
- triggers;
- Renew / ChangeType / Suspend / Restore / Correct / Annul / Void;
- number, type, standing;
- Governance, Committee, Participation, Position, Tenure, Appointment, Authority implementation;
- Election, Finance, UI, API;
- wiring into HTTP / service providers;
- running migrations on the development database.

## 3. Port (unchanged)

`MemberRepositoryPort` with R-1a-1 … R-1a-5, INV-M, INV-FID, IDEM-1 … IDEM-4, exactly as in the Slice 1a contract §3–§4. **No new outcome**, except `ValidTimeNotStorable` (R-1a-6), added by the 2026-10-10 amendment below.

## 4. Temporal mapping (EP-Q6 = T1)

| Domain | Database | Mapping |
|---|---|---|
| `TimePoint` (finite), as effectiveAt or the end of an initial term | `date` | ordinal = days since 1970-01-01 (proleptic Gregorian) ⇄ `YYYY-MM-DD`; exact round trip |
| `TimePoint::infinity()` | never stored as a date | only as `initial_term = 'unlimited'` |
| storable range (amended 2026-10-10) | 0001-01-01 … 9999-12-31 inclusive | a finite `TimePoint` outside it is refused by `append` with `ValidTimeNotStorable` (R-1a-6) before any write; `DateOrdinal` also throws, as a last safeguard |
| initial term | `initial_term` ∈ {`none`, `until`, `unlimited`} + `initial_term_until date` | `none` ⇔ Admit without a term (Lapsed, C-S0-2) · `until` ⇔ a date · `unlimited` ⇔ ∞ |
| `Instant` (decidedAt, recordedAt) | `timestamptz(6)` | ordinal = microseconds since the Unix epoch, UTC ⇄ timestamp; exact round trip at µs |
| recordedAt assignment | — | `max(clock(), max(recorded_at of this Member))` inside the append transaction (R-1a-3). The clock is injected (`Closure(): int`, epoch µs); production uses the system UTC time |

**Slice 0 invariants are unchanged in days:**
- End only if effectiveAt > start, i.e. at least the next day (C-S0-1);
- no initial term ⇒ Lapsed (C-S0-2);
- same-day re-entry is allowed (Lemma 2);
- no date is inferred from infrastructure timestamps.

## 5. Schema (one migration, PostgreSQL)

Ids are caller-supplied opaque strings in the domain (Slice 0), hence `text`. `organisation_id` is the real `organisations.id` (uuid).

```text
org_memberships
  organisation_id  uuid  NOT NULL  REFERENCES organisations(id) ON DELETE RESTRICT
  id               text  NOT NULL
  person_ref       text  NOT NULL                    (Identity reference; no cross-context FK)
  version          integer NOT NULL DEFAULT 0  CHECK (version >= 0)
  created_at       timestamptz(6) NOT NULL
  PRIMARY KEY (organisation_id, id)                  — the same MemberId may exist in two tenants
  UNIQUE (organisation_id, person_ref)               — INV-M

org_membership_facts                                 — append-only history
  organisation_id  uuid  NOT NULL
  membership_id    text  NOT NULL
  seq              integer NOT NULL  CHECK (seq >= 1)
  fact_id          text  NOT NULL
  kind             text  NOT NULL  CHECK (kind IN ('admit','end'))
  effective_at     date  NOT NULL
  initial_term     text  NULL      CHECK (initial_term IN ('none','until','unlimited'))
  initial_term_until date NULL
  period_fact_id   text  NULL
  end_kind         text  NULL
  decided_at       timestamptz(6) NULL
  recorded_at      timestamptz(6) NOT NULL
  provenance       jsonb NOT NULL
  PRIMARY KEY (organisation_id, membership_id, seq)
  UNIQUE (organisation_id, membership_id, fact_id)                         — INV-FID safety net (B-1)
  FOREIGN KEY (organisation_id, membership_id) REFERENCES org_memberships (organisation_id, id) ON DELETE RESTRICT
                                                                            — no fact can attach to another tenant's Member
  FOREIGN KEY (organisation_id, membership_id, period_fact_id) REFERENCES org_membership_facts (organisation_id, membership_id, fact_id)
                                                                            — an End refers to a fact of the same Member
  CHECK (kind = 'admit' AND initial_term IS NOT NULL AND period_fact_id IS NULL AND end_kind IS NULL
         OR kind = 'end' AND initial_term IS NULL AND initial_term_until IS NULL AND period_fact_id IS NOT NULL AND end_kind IS NOT NULL)
  CHECK ((initial_term = 'until') = (initial_term_until IS NOT NULL) OR initial_term IS NULL)

org_membership_commands                              — idempotency (IDEM-1…4): appended outcomes only
  organisation_id  uuid  NOT NULL
  idempotency_key  text  NOT NULL
  fingerprint      text  NOT NULL
  membership_id    text  NOT NULL
  fact_id          text  NOT NULL
  seq              integer NOT NULL
  recorded_at      timestamptz(6) NOT NULL
  PRIMARY KEY (organisation_id, idempotency_key)     — keys are tenant-scoped
  FOREIGN KEY (organisation_id, membership_id) REFERENCES org_memberships (organisation_id, id) ON DELETE RESTRICT
```

- **No** `updated_at`, soft deletes or `based_on_version` (S1-2).
- **Provenance `jsonb`:** all fields of `Provenance` and `AuthorityEvidence` (`checkedAt` as epoch µs). It must round-trip exactly.
- **Migration location:** `database/migrations/2026_10_09_100000_create_org_membership_tables.php`. **Clarified (PO approval, 2026-10-09):**
  - it is a **normal, deployable migration**: no environment conditions, no test-only fixture, no driver branches;
  - in this slice only the test suite applies it (`RefreshDatabase` → `migrate:fresh` on `nrna_test`);
  - it **must not yet be run against development or production**. That rollout is a separate, later step, outside Slice 1b.

## 6. Operations

| Operation | Implementation |
|---|---|
| construction | the adapter receives a `ConnectionInterface`. If `getDriverName() !== 'pgsql'`, it throws `UnsupportedMembershipDatabaseDriver` ("Membership persistence requires PostgreSQL (DB-1); connection '<name>' uses '<driver>'") (**fail fast**) |
| `load(t, id)` | one row from `org_memberships` (where the tenant and id match) + facts ordered by `seq` → `Member::fromLog` |
| `findByHolder(t, p)` | by `(organisation_id, person_ref)`, then as `load` |
| `create(t, id, p)` | `INSERT … ON CONFLICT (organisation_id, person_ref) DO NOTHING RETURNING id`. No row → select the existing id → `HolderExists(id)`. A primary-key collision on (t, id) with another holder is an infrastructure error (caller id reuse) |
| `appliedCommand(t, k)` | select by the primary key |
| `append(t, id, d, k, fp)` | **one transaction** (READ COMMITTED): (1) the key row exists → `AlreadyApplied` or `IdempotencyKeyConflict` by fingerprint; (1b, amended 2026-10-10) any finite valid time of the fact outside the storable range → `ValidTimeNotStorable` (R-1a-6), and no statement has modified state; (2) `UPDATE org_memberships SET version = version + 1 WHERE organisation_id = t AND id = id AND version = d.basedOnVersion RETURNING version` (row lock; re-evaluated after a lock wait); no row → (the Member exists in t ? `ConcurrencyConflict` : `MemberNotFound`); (3) `recorded_at` per §4; (4) insert the fact with `seq` = the returned version; (5) insert the command row. A unique violation in (5) means a concurrent use of the key for another Member of the tenant: roll back, re-read the key row, return `AlreadyApplied` / `IdempotencyKeyConflict`. Any other failure rolls back the whole transaction and propagates |
| a refused decision | `InvalidArgumentException` (as in the 1a contract) |

**Error mapping:**
- translated: holder conflict → `HolderExists`; version compare-and-set → `ConcurrencyConflict`; key → `AlreadyApplied` / `IdempotencyKeyConflict`; unknown Member → `MemberNotFound`;
- **everything else propagates**, including the fact-id unique violation and FK violations. There is no catch-all.

**The adapter never retries** (R-1a-2 stays with `DecideAndAppend`).

## 7. Code placement and boundaries

- **Adapter:** `app/Contexts/Membership/Infrastructure/Organisational/PostgresMemberRepository.php`, plus the mapping helpers in the same namespace: `DateOrdinal`, `InstantMicros`, `ProvenanceJson`, and the exception `UnsupportedMembershipDatabaseDriver`. Laravel's database layer is allowed here (Infrastructure); **there are no Eloquent models.**
- **Kernel and application:** unchanged. The existing fitness tests keep the kernel framework-free and the application free of infrastructure imports.

## 8. Tests (RED first; nothing may be implemented without a failing test)

| # | Test | Evidence for |
|---|---|---|
| T1 | `MemberRepositoryContract` → **trait**, used by `InMemoryMemberRepositoryTest` (unchanged results: 14) and `PostgresMemberRepositoryTest` (Laravel `TestCase`, `nrna_test`). Tenants come from a hook: real `organisations` rows in the database variant; the same labels in memory | the whole Slice 1a contract on PostgreSQL |
| T2 | the same MemberId in two tenants; the same PersonRef in two tenants | tenant-scoped identity |
| T3 | a direct insert of a fact for another tenant's Member is rejected (FK) | the database-level tenant guarantee |
| T4 | an Admit with initial term none / until / unlimited round-trips; resolution: Lapsed / InForce until the date / InForce | the initial-term states, C-S0-2 |
| T5 | date round trip (several dates incl. 1970-01-01, leap days, far future); decidedAt / recordedAt µs round trip; provenance round trip | the temporal mapping (T1) |
| T6 | recordedAt is monotone under a backward clock | R-1a-3 |
| T7 | a failure injected after the fact insert (e.g. a forced command-row violation) leaves version, facts and keys unchanged | atomic rollback |
| T8 | a duplicate fact id inserted raw → an infrastructure exception (not translated) | B-1 |
| T9 | there is no update / delete method on the adapter (reflection) | append-only through the adapter |
| T10 | a non-`pgsql` connection → `UnsupportedMembershipDatabaseDriver` | DB-1 fail-fast |
| T11 | handlers + the PostgreSQL adapter against `ReferenceMembershipModel` (reduced seeds, e.g. 20 × 25), predicates IsMember / InForce / Lapsed / Ended / CurrentPeriod at sampled dates | replay equivalence on the real database |
| T12 *(optional)* | two connections: interleaved creates and appends | additional race evidence (B-2); not a substitute |
| T13 | architecture: the adapter imports nothing from Election / Finance / Governance; no `extends Model` in `Infrastructure/Organisational`; the kernel and application unchanged | boundaries |
| E1–E5 *(amended 2026-10-10)* | in `MemberRepositoryContract`, so inherited by `PostgresMemberRepositoryTest`: unstorable valid times refused with version / log / key unchanged (E1a–d); the inclusive boundaries stored and read back (E2a–d); the key answered before R-1a-6 (E3, E4); R-1a-6 before a stale version (E5) | R-1a-6 on PostgreSQL, **in Part 2 only** |
| E10 *(amended 2026-10-10)* | architecture: the kernel facts with a valid time are exactly Admit and End | `StorableValidTime::admits()` coverage |
| F3a–F3c *(amended 2026-10-10, F-3)* | in `MemberRepositoryContract`, so inherited by `PostgresMemberRepositoryTest`: a MemberId collision with another holder → `RuntimeException` with holder / version / log / mapping / other tenant unchanged (F3a); an unknown MemberId with a used key → `AlreadyApplied` / `IdempotencyKeyConflict` (F3b-1 / -2); an unknown MemberId, unused key, unstorable valid time → `ValidTimeNotStorable` (F3c) | key-first and collision semantics on PostgreSQL, **in Part 2 only** (no per-test transaction on PostgreSQL, so the post-failure reads in F3a are valid there) |
| E4-L *(amended 2026-10-10, R3)* | PostgreSQL variant of the legacy replay: a raw pre-F-1 row (`10000-01-01`) and its key row; the identical retry → `AlreadyApplied`, original outcome, no new fact; asserted via `append` / `appliedCommand` only | IDEM-1 / IDEM-3 before R-1a-6 on legacy data, **in Part 2 only** |

**Regression:** Architecture suite + `tests/Unit/Contexts/Membership` + the new tests, compared with a baseline checkout before 1b. Pre-existing, flaky, environment and new failures are reported separately.

## 9. Technical debt recorded (not in Slice 1b)

- `PBDIGIT-261`: database-engine defaults contradict DB-1 (`config/database.php:18` falls back to `mysql`; `.env.example:15` `DB_CONNECTION=mysql`; `.ENVIRONMENT_CONFIG_MIGRATION.md:66`).
- `PBDIGIT-262`: MySQL branches in legacy migrations (5 files) are dead code under DB-1.

**PO clarification:** the MySQL application code has been removed; it was legacy. Both items are **legacy residue**, not supported paths.

**Deployment consistency:**
- CI (`.github/workflows/*`: `postgres:15`, `pgsql`), `phpunit.xml` and `CLAUDE.md` are consistent with DB-1.
- The **production `.env` cannot be verified by me.** It is an operator prerequisite before any production use of the adapter; the fail-fast guard (T10) covers a mismatch at runtime.

## 10. Definition of done and STOP

- **Tests:** T1–T11 and T13 green; T12 green or explicitly deferred.
- **Unchanged:** the kernel, the port and the handlers (an empty diff).
- **Regression:** the comparison is reported.
- **Records:** the implementation review, developer guide `03`, plan, story and session log; small TDD commits with `(PBDIGIT-260)`.
- **Then STOP:** no Slice 2, no migration slice, no Governance.

## Amendment (2026-10-09, after implementation): DV-1 recorded

During implementation, the real-process race test (T12) found an IDEM-3 defect in Slice 1a code: `DecideAndAppend` answered `DuplicateFactId` instead of the original outcome. That code is "application, unchanged" under §6 and §10.

- **PO decision, 2026-10-09:** the answer "Allow minimal fix" to the question raised in this session. It authorises **one** change outside the adapter: after a domain refusal, `DecideAndAppend` consults the idempotency key (commit `362c5ee03`).
- The §6 / §10 "application unchanged" clause therefore holds **with this single authorised exception**. The kernel, port and handlers are an empty diff.
- Details: implementation review §6 / §9 DV-1; Slice 1a contract IDEM-1 amendment.
- **Confirmation requested from the Principal Reviewer** (acceptance review 2026-10-09, §1 condition 3). The evidence of the PO act is this session's record, not a separate signed act.

## Amendment (2026-10-10): R-1a-6 storable valid time (Option E, PBDIGIT-264)

- **Authority:** Human PO / ARB decisions 1–6 and 8, recorded verbatim in the Option E gate register of `docs/plans/20261010-1830-membership-pg-verification-and-far-date-plan.md`.
- **Changed:** §3 (one outcome added), §4 (storable range row), §6 `append` step (1b), §8 (E1–E5, E10). The Slice 1a contract carries R-1a-6 itself.
- **Delivery P1:** the adapter check is implemented (`PostgresMemberRepository::appendInTransaction`, after `answerFromKey`, before the compare-and-set). **Its PostgreSQL behaviour is unverified until Part 2**; until then the adapter is not accepted, integrated or used in production.
- **Parity scope:** the date contract only; the test double's `MemberNotFound`-before-key ordering remains `PBDIGIT-264` F-3. See Slice 1a R-1a-6 for the adapter-specific `MemberNotFound` precedence *(added 2026-10-10, R1)*. **Superseded by F-3 (2026-10-10, PO decisions F3-1 … F3-4):** the double checks the key first and throws on a MemberId collision; R-1a-6 precedence over `MemberNotFound` is universal again. The PostgreSQL adapter was not changed by F-3; its conformance is expected from the code and verified only in Part 2 (F3a–c).
- **E4-L on PostgreSQL (Human PO / ARB R3, 2026-10-10: in Part 2 scope; Part 2 execution not authorised by it):** raw-insert a pre-F-1 fact row dated `10000-01-01` plus its key row; replay the same decision, key and fingerprint; assert `AlreadyApplied` with the original outcome and no new fact **through `append` and `appliedCommand` only** (`load()` cannot read year 10000, the original F-1 defect).
