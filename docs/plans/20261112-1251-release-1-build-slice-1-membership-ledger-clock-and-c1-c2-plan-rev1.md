# Release 1, build slice 1: Membership ledger clock (D-209) and the C1 / C2 provider

| | |
|---|---|
| **Status** | **PROPOSED, awaiting Principal approval (EP-01).** Nothing is implemented until approved |
| **Date** | 2026-11-12 |
| **Story** | **PBDIGIT-343** (to be created on approval): "Membership ledger clock conformance (D-209) and the C1 / C2 provider". *(PBDIGIT-342 is reserved by the unapproved 2026-11-10 plan.)* |
| **Authority for the design** | **D-162** (C1/C2 spec) as amended by **D-209** (ledger clock); **D-215** (K-C1); **D-161** (`inForce`, `suspended` in C1/C2); **D-160** (ledger organisations only); D-73; D-210 §4 (K-1, K-2), §11; RA (ratified Membership architecture) |
| **Design texts** | `docs/publicdigit/reviews/2026-11-10-membership-cr01-c1-c2-implementation-specification.md` §3, §5, §6, §8, §11; `docs/publicdigit/architecture/reviews/2026-11-12-c1-c2-ledger-clock-amendment-proposal.md` §2; blueprint rev 4 §6 (G-1a … G-2, K-C1, G-1d) |
| **Supersedes** | **only §3 (build order)** of `docs/plans/20261110-1751-minimum-package-build-and-organisation-structure-slice-1-plan.md` (unapproved). Its slice content (Organisation structure) is kept as build slice 3 below |

## 1. Objective

**Make the committed Membership kernel conform to the accepted ledger clock (D-209), and provide the accepted C1 feed and C2 query** (D-162, D-161), proved on the isolated PostgreSQL test cluster.

**What becomes true afterwards:**
- every membership fact's record time is the organisation database's clock reading, **never adjusted**;
- Membership can state a completeness point, `closedThrough`;
- **C2 answers "what membership did this person have, as known at τ" completely, or refuses**;
- C1 announces every change of coverage, `inForce` or `suspended`, in the same transaction as the fact.

## 2. Readiness review (EP-03, derived from the repository)

| Question | Answer | Source |
|---|---|---|
| **Business capability** | provable membership history "as known at τ". Forum access, newsletter audiences and placement depend on it (via Organisation K-4) | D-161, D-162; D-210 §4 |
| **Owning context** | **Membership.** Other contexts affected: **Organisation** consumes C1/C2 (its side is a later slice); **no change** to it here | D-73, D-142 |
| **Published Language?** | **yes, the existing accepted C1/C2 contracts; shapes unchanged** | D-162, D-209 §3 |
| **Ownership or context-map change** | **none** | baseline v1.1 (D-214) |
| **Canonical discovery** | the kernel already has `Member::resolve()`, `knownAt(τ)`, `ResolvedPeriod::lifetime()`, `ResolvedMember::isMember()` and a private `inForce()`. The append loop `DecideAndAppend` (bounded, 3 attempts). The shared outbox table `outbox_events` and relay `ProcessOutboxEvents`. The isolated cluster suite `phpunit.membership-pg.xml` (PBDIGIT-324). **Reused, not duplicated** | files in §11 |
| **Stewardship** | **stable:** RA invariants, C1/C2 shapes, `closedThrough` meaning. **May change:** the repository's clock mechanism (D-209 authorises it) | D-209 §3 |
| **Gates** | this plan's approval; isolated cluster available (`scripts/testing/membership-pg.sh`); **no dev-database migration** | CLAUDE.md DB rules |

## 3. What the code does today (repository facts)

| # | Fact | Evidence |
|---|---|---|
| F-1 | `recordedAt = max(application clock, last + 1 µs)` **per membership** | `PostgresMemberRepository.php:216–220`; constructor `\Closure $clock` (`:50–51`) |
| F-2 | append order: idempotency → valid time → **version compare-and-set** → fact insert → command row | same file, `appendInTransaction` |
| F-3 | **no clock row, `closedThrough`, C1 or C2** | B-05 evidence file (`git grep`, HEAD `a53ba8ea8`) |
| F-4 | retry loop retries **only `ConcurrencyConflict`** | `DecideAndAppend.php:46–70`; `AppendOutcome.php` |
| F-5 | **the shared `OutboxWriter` and all context outbox adapters write through Eloquent `OutboxEvent` (default connection)**, not the caller's connection | `Shared/Infrastructure/Outbox/OutboxWriter.php`; `Contestation/…/ChallengeOutboxAdapter.php` |
| F-6 | no source-of-truth flag (gate C-1) exists | `git grep` for a membership-store flag: none |
| F-7 | seven test classes construct the repository with a clock closure | `tests/Feature/Contexts/Membership/Organisational/Postgres*Test.php` |
| F-8 | the ledger is **not live**: its migrations exist but are not rolled out; live membership uses the legacy store | C1/C2 spec §0 finding 1 |

## 4. Scope

**In:**
1. **Kernel (pure PHP):**
   - `ResolvedMember::coverage()` (INV-COV: ⋃ period lifetimes);
   - a public `inForceIntervals()` (term ∩ lifetime);
   - `suspendedIntervals()`. **This is always empty until Suspend is built (slice 5);** it is stated in the C2 answer as an empty interval set, not omitted.
2. **Schema** (a new migration; applied **only** on the isolated cluster by the test suite): `org_membership_ledger_clocks(organisation_id PK, last_recorded_at timestamptz NULL, closed_through timestamptz NULL)`. The migration also backfills `last_recorded_at` from the latest fact per organisation.
3. **Database clock port:** `LedgerClockReader`. **Production adapter:** `SELECT clock_timestamp()` on **the repository's own connection**, inside the locked transaction.
4. **Append rewrite (D-209 + K-C1):** see §5 D-2.
5. **Retryable refusal (G-1d):** `AppendOutcome::RecordTimeRefused`, mapped to a fresh attempt; on exhaustion `ApplicationRefusal::RecordTimeUnavailable`.
6. **`closedThrough` step:** `LedgerClockCloser`, with hook (a) for due scheduled resignations. **In this slice it has no declarations (the aggregate comes in slice 5); LC-4 is proved there.**
7. **C1 writer:** `MembershipCoverageResolved` (schemaVersion 2), written **through the repository's connection** into `outbox_events`, same transaction, only when coverage, `inForce` or `suspended` changes.
8. **C2 query service** `CoverageKnownAt` (Membership application), with Published Language DTOs:
   - refusals `BOUNDARY_NOT_CLOSED`, `SOURCE_NOT_LEDGER`, cross-tenant;
   - **close first, then read in new statements** (A3);
   - `membershipIds[] | personRefs[]`; a batch limit.
9. **Source-of-truth port** `MembershipSourceOfTruth`:
   - **fail-closed default ("not ledger")**;
   - a test adapter;
   - **no production flag storage here** (it is set at joining, D-160: Platform, slice 2).
10. **Architecture tests;** the developer guide.

**Out:**
- Organisation's consumer side (spec §7);
- C1 delivery to Organisation;
- per-organisation routing (slice 2);
- the gate C-1 flag storage;
- Renew / Suspend / Restore, scheduled resignation and the D-205 guard exception (slice 5);
- Governance AuthorityCheck (slice 4; tests keep doubles);
- **rolling the migrations out to the development or production database** (gate C-10: a separate Principal approval);
- HTTP or UI;
- any change to RA invariants or to the C1/C2 shapes.

## 5. Design decisions for the slice

| # | Decision | Basis |
|---|---|---|
| **D-1** | **Record time comes only from `LedgerClockReader`.** The constructor's `\Closure $clock` is removed from record time. **Tests that need a regressing clock inject a scripted reader; the concurrency proofs use the real database clock.** An architecture test fails if production binds anything but the PostgreSQL reader | D-209; LC-5; G1-1 |
| **D-2** | **Append order (one transaction, READ COMMITTED, as today):**<br>1. idempotency answer;<br>2. valid-time check;<br>3. **ensure** the clock row (`INSERT … ON CONFLICT DO NOTHING`);<br>4. **lock the clock row `FOR UPDATE`**;<br>5. `t` = clock reading;<br>6. **refuse** (`RecordTimeRefused`) if `t ≤ closed_through`, `t < last_recorded_at`, or **`t` equals this membership's last `recordedAt` (K-C1)**;<br>7. **version compare-and-set** (with the IDEM-3 re-read kept);<br>8. insert the fact with `recordedAt = t`;<br>9. set `last_recorded_at = t`;<br>10. C1 row if the state changed;<br>11. command row;<br>12. commit | D-209 §2; spec §11 correction note (clock lock first); D-215 |
| **D-3** | **Clock regression is flagged** by a structured log warning (`membership.ledger_clock.regression`, organisation id, readings; no personal data). Infrastructure only | D-209 §4 (monitoring [EXT] C-04) |
| **D-4** | **`closedThrough` step:**<br>- its own transaction (READ COMMITTED); **never nested** (it refuses inside a transaction, like append F-5);<br>- `FOR UPDATE NOWAIT`. **Busy → the stored value. Free →** `t`; **if `t < last_recorded_at` or `t < closed_through`: no advance, flag, return the stored value;**<br>- (a) due declarations (none in this slice);<br>- (b) `closed_through = max(closed_through, t)`; commit | D-209 §2; LC-6 |
| **D-5** | **C1 rows are written with the repository's `Connection`** into `outbox_events`, in the shape the relay already reads. **Not through `OutboxWriter` (F-5),** so the event is atomic with the fact on whatever connection the organisation uses. `event_id` = a new UUID per row; the payload holds the CR-01 / CR-02 fields | spec §5, §11 CR-02; F-5 |
| **D-6** | **C2 procedure:**<br>1. tenant;<br>2. source port (`SOURCE_NOT_LEDGER`, fail closed);<br>3. **closing step committed**;<br>4. if `τ > closedThrough` → `BOUNDARY_NOT_CLOSED`;<br>5. then **new statements** load the facts; `knownAt(τ)`; per key `{seq_τ, coverage, inForce, suspended}`.<br>An unknown `personRef` → empty, `seq` 0. An unknown `membershipId` → error. **Batch limit 1 000 keys** [TU]. **Never partial** | spec §6; §11 K-2 A3, K-3, K-5 |
| **D-7** | **Time unit and interval convention** in the DTOs: microseconds, `[from, to)` (spec K-6) | spec §11 K-6 |
| **D-8** | **No dev-database migration.** Tests run only on `membership_pg_isolated` through the PBDIGIT-324 guard | CLAUDE.md; PBDIGIT-324 |

## 6. Tests first (RED before GREEN) — all on the isolated cluster unless "unit"

| # | Test | Proof id |
|---|---|---|
| 1 | kernel property tests:<br>- coverage = {t : some lifetime contains t};<br>- INV-COV;<br>- `knownAt` uses a prefix (INV-14);<br>- Annul and Void restore coverage; Correct moves boundaries;<br>- `inForce` = {t : `isMember(t)`};<br>- suspension never changes coverage or `inForce` (vacuous until slice 5, kept) | spec §3, §11 (unit) |
| 2 | no stored `recordedAt` differs from its clock reading | LC-1 |
| 3 | scripted regression: append refused and retried; never ≤ `closed_through` | LC-2 |
| 4 | **two appends and a reader interleaved** (real clock); a reported `closedThrough` never contradicted; **a reader between a writer's lock and its commit**; **REPEATABLE READ misuse fails closed** | LC-3 = A1 … A3; spec K-2 |
| 5 | no application clock reaches `recorded_at` or `closed_through` (architecture test + runtime) | LC-5, G1-1 |
| 6 | the `closedThrough` path does not advance when `closed_through < t < last_recorded_at`; the stored value is unchanged; it may advance at `t = last_recorded_at` | LC-6 |
| 7 | same-membership equal reading → refused and retried | KC1-1 |
| 8 | **different memberships may share a reading** | KC1-2 |
| 9 | the refusal enters the bounded loop, re-loads, re-decides; exhaustion → `RecordTimeUnavailable`; no duplicate acceptance (IDEM) | G1d-1 |
| 10 | fact, clock update and C1 row are atomic (rollback leaves none); C1 only on change; **C1 written on the repository's connection** | spec §8 |
| 11 | **two organisations' clocks independent** | spec §8; K-4 |
| 12 | C2: `BOUNDARY_NOT_CLOSED`; `SOURCE_NOT_LEDGER` (the default port refuses); cross-tenant refused; **the CR-01 §7 scenario** (τ before / after End, Annul, Correct); `personRefs[]`; unknown person → empty | spec §8 |
| 13 | the existing suites (7 classes, F-7) pass with the new reader (adapted, not weakened) | regression |

**LC-4 (scheduled resignations)** is a slice-5 test; the hook (a) is tested here only for "no declarations → no effect".

## 7. Task checklist

- [ ] 0. Create PBDIGIT-343; open `developer_guide/membership/` step guide (next number).
- [ ] 1. RED: kernel tests (test 1) → GREEN: `coverage()`, `inForceIntervals()`, `suspendedIntervals()`.
- [ ] 2. RED: schema test → GREEN: migration (clock table + backfill). Isolated cluster only.
- [ ] 3. RED: tests 2, 5 → GREEN: `LedgerClockReader` (PostgreSQL adapter + scripted test double); replace `\Closure $clock` in the repository; adapt the seven test classes.
- [ ] 4. RED: tests 3, 6 (append side), 7, 8, 10, 11 → GREEN: append rewrite (D-2), D-3 flag, C1 writer (D-5).
- [ ] 5. RED: test 9 → GREEN: `RecordTimeRefused`, the loop mapping, `RecordTimeUnavailable`.
- [ ] 6. RED: tests 4, 6 (closing side) → GREEN: `LedgerClockCloser` (D-4).
- [ ] 7. RED: test 12 → GREEN: C2 service, DTOs, `MembershipSourceOfTruth` (fail closed).
- [ ] 8. Architecture tests (D-1, domain purity, Published Language only).
- [ ] 9. VERIFY: the full Membership PostgreSQL suite and unit suite; `git diff` review.
- [ ] 10. Developer guide; session evidence; **ACCEPT by the Principal** (engineering never accepts its own work).

## 8. Progress

- 2026-11-12: plan drafted. **Awaiting approval.**

## 9. Risks

| # | Risk | Mitigation |
|---|---|---|
| R-1 | appends of one organisation serialise on the clock row | low volume (spec §4 cost); measured in test 4 |
| R-2 | `NOWAIT` contention makes `closedThrough` lag, so C2 refuses | fail closed by design (D-209 §4); a short retry is [TU], not added now |
| R-3 | a real host clock regression blocks appends until the clock passes | the flag (D-3); monitoring is [EXT] C-04 before launch |
| **R-4** | **the outbox relay `ProcessOutboxEvents` reads through the default connection** | correct today (one database). **Under per-organisation databases (slice 2) the relay must iterate organisation connections: recorded for slice 2** |
| R-5 | the scripted clock double leaks into production | architecture test (D-1) |
| R-6 | adapting the seven test classes weakens them | adaptation only replaces the clock source; assertions unchanged; reviewed in the diff |
| R-7 | the backfill on a database with facts | the ledger is not live (F-8); the backfill is still tested |
| R-8 | the isolated cluster not available | the PBDIGIT-324 guard refuses the run; nothing falls back to another database |

## 10. Open questions

**For the Principal:**
1. **Approve this plan.**
2. **Approve the revised build order** (it supersedes §3 of the 2026-11-10 plan):

   | Slice | Content |
   |---|---|
   | **1** | this plan |
   | **2** | Platform routing and isolation; the gate C-1 flag at joining (D-160); the relay per organisation (R-4); founding records and registrar bootstrap (D-148, D-193 M-3) |
   | **3** | Organisation structure (the 2026-11-10 plan's slice) |
   | **4** | Governance (AuthorityCheck, G-Q1 boundary, K-7, T-1) |
   | **5** | Membership lifecycle with real authority + Organisation belonging, T-2, T-4 |
   | **6** | Forum |
   | **7** | Newsletter |

**Technical, decided here** [TU]:
- batch limit 1 000;
- no NOWAIT retry;
- the flag is a log warning.

## 11. Next actions and traceability

- **On approval:** create PBDIGIT-343; start task 0.
- **Commits:** one per task group, subject `…(PBDIGIT-343)`; **never pushed without approval.**
- **Traceability:** D-160, D-161, D-162, D-209, D-210, D-214, D-215; IR-R1C-01, -R1, -R2; blueprint rev 4 (`c08ebc35…`, accepted D-215 / D-216); B-05 evidence `2026-11-12-release-1-blueprint-b05-repository-evidence.md`. Supersedes §3 of `20261110-1751-minimum-package-build-and-organisation-structure-slice-1-plan.md`.
