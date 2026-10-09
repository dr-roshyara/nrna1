# Membership Slice 1b: Readiness Review (database adapter)

**Date:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Instruction:** `docs/publicdigit/membership/architecture/brainstorming/202610090914_implementation.md`. It accepts S1-1, S1-2 and S1-3, and asks for this review **before** any code.
**Status:** **STOP before implementation (instruction §28, condition 1): EP-Q6 is unresolved.** Nothing is created: no migration, model or story. The review and the decision request below are the deliverable.

---

## 0. Verdict

**NOT READY TO IMPLEMENT. One decision is missing: EP-Q6.** Everything else is ready, and the design below is complete except for the column types of the two valid-time values (`effectiveAt` and the initial term's end). Once EP-Q6 is answered, Slice 1b can start without further research.

**Correction of my own earlier gate.** The Slice 1a review said 1b was gated by "EP-Q6 **and** a usable organisation time zone". That was too broad:
- **storage** needs only the representation of `TimePoint`, which is EP-Q6;
- the time zone is needed where an *instant* must be turned into an organisation calendar date (a "today" in Slice 2 commands; the Election adapter's `asOf`). Those are boundary slices, not 1b.

So **1b is gated by EP-Q6 alone.**

## A. Agreement (consistent with the existing architecture)

| Instruction § | Agreed because |
|---|---|
| §3–§4 the port is the boundary; kernel and handlers unchanged | Slice 1a contract §3; the kernel diff since `def4f8e6f` is empty |
| §5 database allowed; no generic persistence framework | EP-01 Rev 1 R9 |
| §6 minimal model: Member, ordered facts, version; tenant + person → one Member; tenant + key → one result | the port's data (Slice 1a review §C) |
| §8 four separate time / ordering concepts | Slice 0 contract §9 |
| §9 no `based_on_version` | accepted S1-2 |
| §10 version check + insert + key registration + version advance in one transaction | R-1a-1, IDEM-3 |
| §11 the adapter never retries; the application re-decides | R-1a-2 (`DecideAndAppend`) |
| §12 idempotency semantics exactly as in 1a | IDEM-1 … IDEM-4 (accepted S1-3) |
| §13 INV-M by a database uniqueness constraint | INV-M |
| §15 tenant isolation, explicitly tested | R-1a-5 |
| §16 append-only; no ORM model that makes mutation easy | R-1a-4 |
| §17 no Event Sourcing | RA-13 |
| §18 the same contract suite for both adapters | Slice 1a design |
| §21–§22 no legacy migration; legacy schema recorded only | EP-01 §13 |
| §24 property test against the independent model | the Slice 1a reference model |
| §28 STOP conditions | applied here (condition 1) |

## B. Disagreements

**B-1: translating a unique-constraint violation into `DuplicateFactId` (§20)**
- **Existing rule:** INV-FID is decided by the **domain** before append (Slice 0 guard). The port has **no** DuplicateFactId outcome (Slice 1a contract §3).
- **Proposal:** "unique constraint → HolderExists / DuplicateFactId".
- **Conflict:** adding the outcome changes the port, which is STOP condition 4.
- **Analysis:**
  - with a compare-and-set version update **before** the insert (§E), a fact-id collision inside one Member is unreachable through the port;
  - a concurrent second fact either loses the version check, giving `ConcurrencyConflict`, after which re-deciding gives the domain `DuplicateFactId`, or it lands at a later version, where the domain guard sees the id;
  - so a database violation there means a defect.
- **Recommendation:**
  - keep `UNIQUE(membership_id, fact_id)` as a **safety net** that raises an infrastructure exception, not a translated outcome;
  - `HolderExists` stays a translated outcome, as the port defines.
- **Decision required:** Principal Reviewer (confirm).

**B-2: "concurrent create race" and "concurrent identical requests" tested against the real database (§19)**
- **Issue:** one PHPUnit process cannot interleave **inside** one adapter call.
- **Recommendation:** make each adapter operation a single atomic statement or transaction whose race behaviour is the database's own guarantee:
  - `create`: `INSERT … ON CONFLICT (organisation_id, person_ref) DO NOTHING RETURNING id`; nothing returned → `HolderExists`;
  - `append`: `UPDATE … SET version = version + 1 WHERE … AND version = :expected` (the row lock serialises writers).

  The sequential contract test then runs **the same statement** a race would run. This is proof by construction plus PostgreSQL's documented semantics. Add an **optional** two-connection test group (separate PDO connections, interleaved transactions) as extra evidence. It is excluded from the default suite if it is slow.
- **Decision required:** Principal Reviewer (accept the evidence level).

**B-3: the contract suite's base class**
- **Issue:** `MemberRepositoryContract` extends `PHPUnit\Framework\TestCase`. The database adapter needs `Tests\TestCase` (Laravel, `RefreshDatabase` on `nrna_test`).
- **Recommendation:** turn the contract into a **trait**, used by `InMemoryMemberRepositoryTest` (PHPUnit base) and `DatabaseMemberRepositoryTest` (Laravel base). No test semantics change.
- **Decision required:** none beyond accepting this test-infrastructure refactor.

**B-4: payload representation (EP-01 Rev 1 §10 said "payload jsonb")**
- **Recommendation:** typed columns for the **temporal** values (effectiveAt, initial-term end), so the database type follows EP-Q6 and can carry constraints; `jsonb` for provenance only. Later fact kinds add columns or jsonb by additive migrations.
- **Decision required:** Principal Reviewer (minor).

**B-5 (minor): who translates the creation race (§13)**
The instruction says "the application should translate the database race into HolderExists". In the 1a design **the adapter** translates it (port contract); the application already handles `HolderExists`. No change.

## C. Scope check

Proposed work = **the database adapter for `MemberRepositoryPort` plus its migrations, nothing else.**

None of these is touched: Governance, Committee, Participation, Position, Tenure, Appointment, Authority implementation, Election, Finance, UI, migration of legacy data, Correct / Annul / Void, Renew, ChangeType, Suspend, Restore. The kernel and the handlers are unchanged (§3–§4).

## D. The open decision: EP-Q6 (exactly)

**Question:** is the valid time of membership facts (`effectiveAt`, and the initial term's end) a **calendar date** in the organisation's calendar, or an **instant**?

| Option | Storage | Consequences |
|---|---|---|
| **T1: calendar date** (recommended in EP-01 Rev 1, EP-I1) | `date` columns; `TimePoint` ordinal = days since 1970-01-01 (proleptic Gregorian) | statutes and decisions are dated; "member until 31.12." = `until 01.01.`; an End is at least one day after admission (C-S0-1 in days); a same-day re-entry is allowed (Lemma 2); intra-day suspension or ordering is impossible beyond seq; legacy timestamps → dates needs the time zone (migration slice, not 1b) |
| **T2: instant** | `timestamptz(6)` UTC; ordinal = epoch microseconds | intra-day precision; every "member on 31.12." question needs an organisation time zone at query time; "until 31.12." becomes a choice of instant |

**Evidence (repository, not business truth):**
- **legacy membership columns are `timestamp`** (`create_members_table.php:20-22`; `create_membership_renewals_table.php:21-22`), but they are filled from `now()`: technical, not a business statement;
- **renewal uses calendar-month arithmetic** (`MembershipRenewalController.php:59`, `addMonths(duration_months)`);
- **fees use `date`** (`create_membership_fees_table.php:28`, `due_date`);
- **elections are instant-based with their own time zone** (`2026_05_19_211659…`).

**My recommendation: T1.**

**Not affected by EP-Q6 (already decided, PDR §11):** `decidedAt` and `recordedAt` are instants. Storage `timestamptz(6)` UTC; `Instant` ordinal = epoch microseconds. This is an implementation mapping inside a decided semantics.

**A third state the schema must encode for the initial term** (independent of EP-Q6):
- **none:** term = ∅, so the member is Lapsed (C-S0-2);
- **until x;**
- **unlimited (∞).**

`NULL` cannot represent both "none" and "∞". Hence an explicit `initial_term` kind column.

## E. Candidate design (to implement once EP-Q6 is answered)

**Tables** (placeholders per EP-I2; default connection; PostgreSQL as in the test configuration):

```text
org_memberships
  id                uuid PK
  organisation_id   uuid NOT NULL  FK organisations(id) ON DELETE RESTRICT
  person_ref        uuid NOT NULL                      (users.id; Identity reference)
  version           integer NOT NULL DEFAULT 0 CHECK (version >= 0)
  created_at        timestamptz NOT NULL
  UNIQUE (organisation_id, person_ref)                 — INV-M
  UNIQUE (organisation_id, id)                         — target of the tenant-safe composite FK

org_membership_facts                                  — append-only history
  organisation_id   uuid NOT NULL
  membership_id     uuid NOT NULL
  seq               integer NOT NULL CHECK (seq >= 1)
  fact_id           varchar NOT NULL
  kind              varchar NOT NULL CHECK (kind IN ('admit','end'))
  period_fact_id    varchar NULL                       (End: the Admit it ends)
  effective_at      <EP-Q6: date | timestamptz(6)> NOT NULL
  initial_term      varchar NULL CHECK (initial_term IN ('none','until','unlimited'))   (Admit only)
  initial_term_until <EP-Q6 type> NULL                  (only when initial_term = 'until')
  end_kind          varchar NULL                        (End only; opaque, BD-3)
  decided_at        timestamptz(6) NULL
  recorded_at       timestamptz(6) NOT NULL
  provenance        jsonb NOT NULL
  PRIMARY KEY (membership_id, seq)
  UNIQUE (membership_id, fact_id)                      — INV-FID safety net (B-1)
  FOREIGN KEY (organisation_id, membership_id) REFERENCES org_memberships (organisation_id, id) ON DELETE RESTRICT
  CHECK kind-specific columns (an admit has initial_term; an end has period_fact_id and end_kind)

org_membership_commands                               — idempotency (IDEM-1…4)
  organisation_id   uuid NOT NULL
  idempotency_key   varchar NOT NULL
  fingerprint       varchar NOT NULL
  membership_id     uuid NOT NULL
  fact_id           varchar NOT NULL
  seq               integer NOT NULL
  recorded_at       timestamptz(6) NOT NULL
  PRIMARY KEY (organisation_id, idempotency_key)
```

- **Tenant guarantee at the database level:** the composite FK means a fact can never be attached to another tenant's Member, even by a faulty query. Every adapter query also filters `organisation_id` explicitly (no `BelongsToTenant`; PDR R14).
- **Append-only:**
  - there is **no Eloquent model** for facts: inserts go through the query builder inside the adapter only;
  - there are no `updated_at` / soft-delete columns;
  - optionally a PostgreSQL trigger that rejects UPDATE / DELETE on `org_membership_facts` (decision B-6, optional safety net).

**`append` transaction:**

```text
BEGIN
  command := SELECT … FROM org_membership_commands WHERE organisation_id = :t AND idempotency_key = :k
  if command: ROLLBACK; return fingerprint equal ? AlreadyApplied(command) : IdempotencyKeyConflict
  n := UPDATE org_memberships SET version = version + 1
       WHERE organisation_id = :t AND id = :m AND version = :basedOn RETURNING version   (row lock)
  if n = 0: ROLLBACK; return (member exists in tenant ? ConcurrencyConflict : MemberNotFound)
  rec := GREATEST(:clockNow, (SELECT max(recorded_at) … WHERE membership_id = :m))      (R-1a-3)
  INSERT INTO org_membership_facts (… seq = n …)
  INSERT INTO org_membership_commands (…)
     — unique violation here = the same key used concurrently for another Member of the tenant:
       ROLLBACK, re-read the command row, return AlreadyApplied / IdempotencyKeyConflict by fingerprint
COMMIT
```

**Concurrent identical retries:**
1. Both pass the key read.
2. The first takes the row lock and commits.
3. The second's compare-and-set finds a stale version, so `ConcurrencyConflict`.
4. The application re-decides; its pre-check now finds the key and returns the original `Appended` (IDEM-1).
5. Result: exactly one fact.

**`create`:** `INSERT … ON CONFLICT (organisation_id, person_ref) DO NOTHING RETURNING id`; no row → `SELECT id` → `HolderExists(id)`.

**Error policy (§20):**
- translated: the version compare-and-set → `ConcurrencyConflict`; the holder conflict → `HolderExists`; the key conflict → `AlreadyApplied` / `IdempotencyKeyConflict`;
- everything else, including the INV-FID safety net, propagates as an infrastructure exception.

## F. Test plan

- **Contract:** the same `MemberRepositoryContract` (trait, B-3) runs against the database adapter (Laravel `TestCase`, `RefreshDatabase`, `nrna_test`).
- **Database-only:**
  - temporal round trip (the EP-Q6 mapping; `decidedAt` / `recordedAt` microseconds; the three initial-term states; ∞);
  - the composite FK rejects cross-tenant attachment (direct insert);
  - no update / delete path (and the trigger, if B-6 is accepted);
  - `recordedAt` monotone under a backward clock.
- **Property:** handlers + database adapter against `ReferenceMembershipModel`, with reduced seeds for speed. Predicates IsMember, InForce, Lapsed, Ended, CurrentPeriod at sampled times.
- **Optional:** a two-connection race group (B-2).
- **Architecture:**
  - the adapter lives in `Infrastructure`;
  - the kernel stays framework-free (existing test);
  - the application does not import the adapter.

## G. Legacy schema record (not migrated)

| Legacy | Target | Mapping | Status |
|---|---|---|---|
| `members` (identity, `organisation_user_id`, number, status, dates) | `org_memberships` (identity) + `org_membership_facts` (attested Admit / End) | §16 of the principal design review | **deferred** (migration slice) |
| `membership_renewals` | Renew facts | — | deferred (Slice 4) |
| `membership_fees`, `membership_payments` | Finance | — | other context (DS-1) |
| `membership_types` | catalogue (later `MembershipTypeRef`) | — | deferred (Slice 4) |
| `organisation_users`, `user_organisation_roles` | Organisation context (affiliation) | — | not Membership |
| `committee_*` | Governance | — | other track |
| `members.personal_info`, `residence_geo_unit_id`, `newsletter_*` | none (EP-Q4) | — | owner open |

The target schema takes nothing from them (§22).

## H. Decision request (all needed before / for Slice 1b)

| # | Decision | Recommendation | Blocks 1b? |
|---|---|---|---|
| **EP-Q6** | valid time = calendar date (T1) or instant (T2) | **T1** | **YES** |
| B-1 | fact-id unique violation stays an infrastructure error (no new port outcome) | confirm | no (the default follows the existing contract) |
| B-2 | evidence level for races: single-statement atomicity + an optional two-connection group | accept | no |
| B-3 | contract suite becomes a trait | accept | no |
| B-4 | typed temporal columns; jsonb for provenance only | accept | no |
| B-6 | PostgreSQL trigger forbidding UPDATE / DELETE on facts | optional | no |

**Story:** none is created until 1b is authorised. The next free ID was `PBDIGIT-260` at the time of writing.

**STOP.**

---

## I. Addendum (2026-10-09, instruction `20261009_0922_implementation_PublicDigit Membership.md`)

**Agreement:** I agree with the instruction in full. It orders: search for an authoritative EP-Q6 decision → otherwise a decision request → STOP. I follow it.

**I.1 Search for an authoritative EP-Q6 decision: none found.**

| Source | Content | Class |
|---|---|---|
| ratification act; MA §2 (`effectiveAt` = valid time) | valid time exists; its granularity is not stated | ratified, **silent** |
| FDR RA-15 | "imprecise legacy **dates** are normalised … precision is kept as metadata" | ratified; concerns migration precision, **not** the granularity of the model |
| `docs/publicdigit/membership/20260403_0003_membership_management.md:113,184,196` | "expiry **date**", "new expiry date calculated (extends from current expiry or today)" | user documentation of the legacy behaviour: **repository observation** |
| Election Manifesto (EM-\*) | nothing on membership time | — |
| EP-01 EP-I1, PDR §11, this review §D | recommend T1 | **hypotheses / recommendations** |

**Result: EP-Q6 is undecided.** The decision request in §D stands. T1 (calendar date) is recommended as a hypothesis only.

**I.2 Database engine and test setup (instruction §4.4): a discrepancy is reported, not adapted to.**

| Item | Finding |
|---|---|
| `config/database.php:18` | default `env('DB_CONNECTION', 'mysql')`: **the code's fallback is MySQL** |
| `phpunit.xml:67,70` | `pgsql`, `nrna_test` |
| `CLAUDE.md` (project overview) | "Database: PostgreSQL" |
| migrations | 13 driver-conditional migrations (MySQL and PostgreSQL branches) |
| PHP extensions | `pdo_pgsql` and `pdo_mysql` both present |
| test database | functional (`tests/Feature/Election/AdmissionEvidenceSchemaTest.php`: 4 tests OK on `nrna_test`) |
| production engine | **unknown to me**: it lives in `.env`, which I am not permitted to read |

**Consequence:** the candidate design (§E) uses PostgreSQL features: `ON CONFLICT … DO NOTHING RETURNING`, `jsonb`, `timestamptz`. If production is PostgreSQL, the design stands. If it is MySQL (or both must be supported), the adapter needs driver-specific statements (`INSERT IGNORE` / `json` / `datetime(6)` with an explicit UTC convention), and the race-evidence argument (B-2) must be redone for InnoDB.

**Decision / confirmation needed: DB-1, the production database engine.**

**I.3 Additions to the test plan from the instruction (§8):**
- **atomic rollback when part of an append fails:** inject a failure after the fact insert; the version and key rows must be unchanged;
- **prevention of fact updates / deletes through the adapter:** the adapter offers none; an attempted raw UPDATE is optionally blocked by the trigger (B-6).

**Status: STOP.** Waiting on EP-Q6 and DB-1. No story, migration or model was created.

---

## J. Decision record and the open DB-1 question (2026-10-09)

### J.1 EP-Q6: decided by the PO (verbatim)

> "EP-Q6: I favour T1 — calendar-date semantics — as the Membership domain rule, subject to my authority to ratify it. Membership admission, term boundaries and ending are date-based; intra-day membership transitions are not supported. `decidedAt` and `recordedAt` remain exact instants. Preserve the existing Slice 0 invariants and do not infer or migrate historical dates in Slice 1b."

**Consequences for the contract** (to be frozen only after DB-1):
- `effectiveAt` and the initial term's end are **calendar dates**. `TimePoint` ordinal = days since 1970-01-01 (proleptic Gregorian). ∞ exists only as "unlimited initial term", an explicit state, never a stored date.
- Slice 0 invariants are unchanged:
  - C-S0-1 becomes "End at least one day after the admission date";
  - C-S0-2 (no initial term ⇒ Lapsed) is unchanged;
  - same-day re-entry is allowed (Lemma 2).
- `decidedAt` / `recordedAt` are exact instants.
- Slice 1b infers or migrates **no** historical date.

### J.2 DB-1: the exact question (unresolved)

> **DB-1: Which database engine(s) must the Membership persistence adapter support in production: (a) PostgreSQL only, (b) MySQL only, or (c) both?**

**Evidence:**
- `config/database.php:18` falls back to `mysql`;
- `phpunit.xml` uses `pgsql` / `nrna_test`;
- `CLAUDE.md` says PostgreSQL;
- at least 5 migrations carry MySQL-specific branches (e.g. `2026_04_03_155711_add_ended_status_to_members_table.php`), so MySQL was supported historically;
- the `jsonb()` uses in 3 migrations are portable in Laravel, so they do **not** prove PostgreSQL-only;
- the production engine is in `.env` (not readable by me).

| Answer | What Slice 1b then builds | Consequences / risks |
|---|---|---|
| **(a) PostgreSQL only** | the design of §E as written: `INSERT … ON CONFLICT DO NOTHING RETURNING`; `date` (valid time); `timestamptz(6)` UTC (instants); `jsonb` provenance; composite tenant FK; version compare-and-set under PostgreSQL row locks (READ COMMITTED re-evaluates the `WHERE version = :expected` after a lock wait) | **tests run on the production engine** (`nrna_test` is PostgreSQL), so the race evidence (B-2) is directly valid. **Risk:** if any production or staging installation actually runs MySQL, the adapter fails there. Mitigation: the adapter checks the driver at construction and **fails fast** on a non-PostgreSQL connection, instead of misbehaving silently. The `mysql` fallback in `config/database.php` stays a latent misconfiguration risk (outside 1b; backlog item recommended) |
| **(b) MySQL only** | `INSERT IGNORE` / `ON DUPLICATE KEY` (no `RETURNING`, so create = insert + select); `json`; `datetime(6)` with an application-enforced UTC convention (no time-zone type); `date` | **the test database is PostgreSQL**, so the adapter would be verified on the wrong engine. It needs a MySQL test database and CI change first (a test-infrastructure slice). InnoDB's REPEATABLE READ default changes the race argument (snapshot reads for the key check, gap locks / deadlocks), so B-2 must be redone |
| **(c) both** | driver-specific branches (or a lowest-common-denominator SQL) behind the one port | the shared contract suite must run on **both** engines (two test databases in CI); about double the verification effort; race evidence per engine; more code in the adapter. Highest cost, needed only if both are really operated |

**Recommendation:** (a), **if** production is PostgreSQL. It matches the test database and the project documentation, and keeps the race evidence on the real engine. The recommendation is conditional on a fact only the operator can confirm (the production `.env` / `db:show`).

**Status:** the contract is **not frozen**; no story, migration or code exists. The next step after DB-1 is to freeze the Slice 1b contract (§E with J.1 applied) for your review, then TDD.
