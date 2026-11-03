# PBDIGIT-264 Part 2: independent review and PO verdict

**Status:**
- §2 is the review report, recorded **verbatim** as returned (2026-11-02).
- §3 records the PO's decisions: "A, fix C1 and C2 first" (2026-11-02), then "commit, then add E4-L as step 4b", then **"accept PBDIGIT-264" (2026-11-03)**.

**Reviewer:** a fresh general-purpose Claude sub-agent, commissioned on 2026-11-02 by session `93000efc-ad03-4c1d-9b3f-40966981efc3` on the PO's instruction ("review Part 2").
- It was read-only on the repository.
- It used its own clean worktree.
- It ran PostgreSQL only through the pinned PBDIGIT-324 container.

**Reviewed:** commits `f9455ee96`, `820ae5a52`, `1e16d89c7`, `1c8611a86`, `73265d4af`, at HEAD `bab0cb286` (code identical to `73265d4af`).

**Spot-checked** by the commissioning session:
- the F-4b join includes `seq`, but only seq-1 answers were tested (R-1);
- the classifier matches the English server text (R-3);
- the Slice 1b contract still showed the old schema and `ON CONFLICT` (R-2);
- no container or worktree was left behind.

## 1. Commission (summary)

AC-1…AC-9 (including the AC-6 qualification); the correctness of F-5, N1, the SET, F-4b (join, migration, test restore) and step 4a (`ON CONFLICT` without a target, F-3, parity); regression risk; the release gate; evidence consistency; the Slice 1b re-entry criteria. Report sections A–G.

## 2. Report (verbatim)

### A. Verdict recommendation: ACCEPT WITH CONDITIONS

The committed code is correct on every path I checked, all 9 ACs are met or met-in-substance (two qualified), the full PostgreSQL suite reproduces green at the committed code (54 tests / 423 assertions, exit 0), and the evidence hashes verify and match their claims. Conditions (none requires a production-code change; none blocks local merge):
- **C1 (test-only, before ACC-260):** add one regression test that a key for a later fact (e.g. an End at seq 2) is answered with that fact's `fact_id`/`recordedAt` — no test currently pins the `seq` term of the F-4b join (finding R-1).
- **C2 (docs-only, before ACC-260):** amend the Slice 1b contract (§5 schema, §6 `create`/`appliedCommand`/`append` N1) and the Slice 1a F-3 clause ("PostgreSQL: the primary-key violation"), which still describe pre-Part-2 behaviour (R-2).
- **C3 (release gate, already open):** before the F-4b migration reaches any persistent database: OQ-01 + the §10.2 preflight (0/0/0) **plus** `SHOW lc_messages` on the target (R-3).

### B. AC-1 … AC-9

| AC | Status | Evidence |
|---|---|---|
| AC-1 classifier | **Met** | VERIFIED: `PgUniqueViolationTest` passes (Infrastructure suite 22 OK), incl. decoy and "no constraint on first line"; `append()` has no `->getMessage()` (classifier call at `PostgresMemberRepository.php:166`). |
| AC-2 N1 | **Met** | VERIFIED: check at `:151-153` before any statement; `test_f5_an_append_inside_an_open_transaction…` asserts zero statements and zero rows; green in my run at HEAD. |
| AC-3 RR enforced | **Met** | VERIFIED: SET at `:159` is the closure's first statement (BEGIN via PDO); both `test_f5_*` RR races run under an RR session; my race group green ×2 (7 tests/240 assertions); guide 03 states the enforced level. |
| AC-4 F-4b schema/answers | **Met in substance; under-tested** | VERIFIED columns test, orphan → 23503, join correct at `:128-137`. But mutant M6 (join without `f.seq`) **survives the entire suite (54 OK)** while a probe shows it returns the wrong fact for an End key → "appliedCommand answers unchanged" is only tested for single-fact Members. See R-1. |
| AC-5 migration round-trip & abort | **Met** | VERIFIED up→down→up and planted `fact_id` mismatch tests green; mutant M2 (abort removed) killed. Gap: no test plants a `recorded_at`-only mismatch — mutant M1 (that clause removed) survives; my probe confirms the committed migration *does* abort on it. |
| AC-6 F-2 + sensitivity | **Met as worded; qualification understated** | F-2(a)/(b) green in all my runs. Mutant (b) caught by F-2(b) (REPORTED evidence). Mutant (a) — my reproduction: race group run 1 → **caught** (identical-retries @ round 3 and RR identical-retries @ round 0); runs 2 and 3 → **not caught**. It is caught **deterministically** by the database-free `ConcurrencyAndRaceTest::test_an_identical_retry_committed_between_the_pre_check_and_the_load…`. So PG detection is probabilistic; the suite as a whole always catches it. The evidence claim "caught by the identical-retries race" rests on one run; F-2(a)'s insensitivity is correctly stated. |
| AC-7 regression | **Met** | VERIFIED: PG 54 OK; DB-free Domain 117, Application 61, Infrastructure 22, Membership arch (7 files) 97, `tests/Unit/Testing` 168; reference-model property test green. |
| AC-8 evidence | **Met (one gap)** | VERIFIED `sha256sum -c` OK for all 14 folders; each `run.txt` has commit/digest/server_version/system_identifier. Gap: `run.txt` records the *base* commit while step 2–5 runs used uncommitted copied changes, and phpunit args aren't recorded. My committed-code run closes this. |
| AC-9 scope | **Met** | VERIFIED `git diff --stat f9455ee96^..73265d4af`: code only in the 4 infra files, the migration, 2 architecture tests, 3 Feature tests, 1 Unit test; rest docs. The only schema change is the F-4b migration. |

### C. Findings

| ID | Severity | Location | Description | Disposition |
|---|---|---|---|---|
| R-1 | major (test gap) | `PostgresMemberRepository.php:128-134`; F-4b tests | No test checks `appliedCommand` for a fact with seq > 1. Mutant M6 (join on org+membership only) passes all 54 tests. VERIFIED by probe: under M6 the End key answers `fact=A-M1 seq=2`; committed code answers `fact=E1 seq=2`. Committed code correct. | C1: one invariants/contract test (Admit, End, the End key's answer equals the End's Appended). |
| R-2 | major (doc/governance) | Slice 1b contract §5 (lines 105-114), §6 `create` (line 131), `appliedCommand` "select by the primary key"; Slice 1a F-3 clause | Contracts still show `fact_id`/`recorded_at` in commands, no fact FK, `ON CONFLICT (organisation_id, person_ref)`, and "PostgreSQL: the primary-key violation". The pack said F-4b "changes the approved Slice 1b schema". Guide 03 is updated. | C2: docs-only contract amendment. |
| R-3 | major (latent; release) | `PgUniqueViolation.php:29-30` | Classifier parses the English text `violates unique constraint "…"`. With non-English `lc_messages`, a real commands-PK collision would no longer be recognised → it surfaces as an exception (fail-closed, no bad data) instead of `IdempotencyKeyConflict`/`AlreadyApplied`. The old substring test was locale-independent. INFERRED (the pinned image cannot set `de_DE`: VERIFIED `22023 invalid value`; the test cluster uses `en_US.utf8`, VERIFIED). The design follows the pack's prescription, so not an implementation deviation. | Release preflight `SHOW lc_messages`; backlog a locale-robust parse or a documented server requirement. |
| R-4 | minor | `PostgresMemberRepositoryInvariantsTest` F-4b abort test | Only a `fact_id` mismatch is planted; mutant M1 survives. VERIFIED the code aborts on a `recorded_at`-only (+1µs) mismatch with FK rolled back. | Add the `recorded_at` case with C1. |
| R-5 | minor | plan §11, evidence README, guide 03 mutant-(a) wording | "Caught by the identical-retries race" overstates determinism (1 of 3 in my runs); the deterministic catch is the DB-free unit test. | Correct the wording. |
| R-6 | minor (evidence) | `scripts/testing/membership-pg.sh:89,106` | `run.txt` records `git rev-parse HEAD` even with a dirty tree and not the phpunit args. | Already PBDIGIT-325 F5/F9. |
| R-7 | minor (regression surface) | `phpunit.xml` Feature suite | Pre-existing: the Membership PG tests also run in the default suite on `nrna_test`; Part 2 adds DDL round-trip tests and RR-session races there. Restore logic is sound, but it conflicts with the intent of ARB-SEC-01/BR-20. | Record; consider excluding these classes from the default suite (separate story). |
| R-8 | info | `create()` `:101-121` | VERIFIED by probe: same person different id → holderExists; same id+person again → holderExists; id held by another holder while this person has a Member → holderExists(own); F-3 → `MemberIdHeldByAnotherHolder`, 2 rows, new person unmapped; org missing → FK 23503 propagates. In-memory parity holds. Mutant M3 killed by the F-3 contract test. | None. |
| R-9 | info | `append()` `:151` | N1 relies on Laravel's `transactionLevel()`; a raw BEGIN outside Laravel would bypass it and the SET would then fail closed (25001). No caller does; the adapter is wired nowhere (VERIFIED grep); handlers and the property test call it top-level. | None. |
| R-10 | info | migration + adapter | Deploy coupling: the new write path requires the migration; rollback requires `down()` plus the old code. No runtime impact today (adapter unwired). | Release runbook note. |

Other answers: the SET-first claim holds; retries N/A (Laravel `transaction()` defaults to 1 attempt); savepoints impossible under N1. The migrator wraps each migration in a transaction on PostgreSQL (`Migrator.php:448-451`, `PostgresGrammar::$transactions = true`), so an abort leaves no FK. `up()`/`down()` match §10.2.

### D. Release gate and regression risk

**Release gate:** guarded by docblock + process only (migration docblock, guide 03, plan §7, branch not pushed: VERIFIED no remote contains `73265d4af`, branch ahead 185). A code guard is impossible (DB-1 forbids environment/driver conditions; the architecture test enforces it). Once merged it runs on the next `php artisan migrate`; safe-on-failure (abort inside a transaction), but it then blocks later migrations of that run. Before merge/deploy the PO must know: (1) on dev, the 1b migration was pending, so both would run together, and the 1b contract says those tables must not yet reach dev/prod — this covers F-4b too; (2) OQ-01 is open; (3) add `SHOW lc_messages` to the preflight; (4) code and migration must deploy (and roll back) together.

**Regression risk:** CI `membership-architecture.yml` starts no PostgreSQL service and triggers only on main/postgressql/phase-*; GATE 7 runs `tests/Feature` with `|| true` → no outcome change (INFERRED from the workflow text). GATE 1 untouched. Default suite on `nrna_test`: the F-4b migration now runs in every `migrate:fresh` on empty tables, the same class as the 1b migration; plus R-7. No change outside Membership.

### E. Slice 1b re-entry criteria

1. Green on an isolated database with command/name/output/exit captured: **Met** (caveat: phpunit args not recorded, R-6).
2. Far-date fixed test-first: **Met**; PG run VERIFIED in the step-0 testdox.
3. Applicable 264 items dispositioned: **Met once the PO accepts** (C1 and C2 recommended first).
4. Persistence and security: **Not met** — persistence substantially complete (subject to C1); security (PBDIGIT-266) open; the dispositions record also lists C-1 F-1 (`encode()` typing) as open, assigned to C-4.

### F. Commands run (summary as reported)

Clean worktree `/tmp/rv264` at `bab0cb286` (code identical to `73265d4af`), `composer install --no-scripts`. DB-free: Domain 117, Application 61, Infrastructure 22, arch 97, `tests/Unit/Testing` 168 OK (the whole `tests/Unit/Contexts/Membership` without configuration gives 69 errors, all in older Laravel-dependent tests outside Organisational, unrelated). PostgreSQL via the pinned image: full suite `--testdox` 54/423 exit 0; race group ×2 7/240 exit 0; probes (classifier and `lc_messages`, `create()` semantics, `recorded_at`-only abort). Mutants (scratch, restored, `cmp`): M1 survived; M2 killed; M3 killed; M6 survived (wrong answer by probe); mutant (a) killed 1/3 in the race group, always by the DB-free unit test. All 14 evidence folders verify; the invalid run is excluded; no credential-shaped strings. Worktree removed; `podman ps -a` empty before and after; `bootstrap/cache/config.php` untouched; the main tree changed only by another session's KnowledgeOS files.

### G. Residual risks if accepted

1. Join correctness for seq > 1 protected only by review and probe until C1.
2. The classifier depends on the server's message locale (fail-closed).
3. The migration runs on the next `migrate` once merged; the gate relies on process.
4. Contract drift until C2.
5. Race-test detection is probabilistic; deterministic coverage of the `DecideAndAppend` re-read is in the DB-free suite.
6. PostgreSQL 15.19 only (W2-2 provisional); `run.txt` does not prove a clean tree (PBDIGIT-325 F5).
7. The default suite still runs these PostgreSQL tests, now including DDL, on the shared `nrna_test` (R-7).

## 3. PO decisions and verdict

### Decision 1 (2026-11-02): "A, fix C1 and C2 first"

Implemented in `b3b05c616`.

- **C1:**
  - a shared contract test: a later fact's key answers with its own fact;
  - a `recorded_at`-only abort case (R-4).
  - **Both surviving mutants are now caught:** M6 by the C1 test, M1 by the R-4 case.
- **C2:** contract amendments to Slice 1b (§5, §6, N1, READ COMMITTED, the classifier and R-3) and to Slice 1a (the F-3 mechanism).
- **R-5:** wording corrected.

**Gap found during C2:** the PostgreSQL E4-L test, approved on 2026-10-10 as Part 2 scope, had been omitted from the plan.

### Decision 2 (2026-11-02): "commit, then add E4-L as step 4b"

Done in `9c11c5193`.
- E4-L is green: **57 tests, 436 assertions**.
- Sensitivity: with the date check moved first, the test fails.

### Decision 3 (2026-11-03): "accept PBDIGIT-264"

**PBDIGIT-264 is ACCEPTED WITH EXPLICIT RESIDUAL RISKS.** It covers F-1 + Option E and F-3 (done 2026-10-10), plus Part 2 (F-5, F-4b, F-2, step 4a, step 4b, C1, C2).

**Dispositions:**

| Item | Disposition |
|---|---|
| R-1, R-2, R-4, R-5 | fixed (`b3b05c616`) |
| E4-L gap | fixed (`9c11c5193`) |
| R-3 (locale-robust classifier), R-7 (exclude these PostgreSQL tests from the default `phpunit.xml` suite) | **PBDIGIT-326** (backlog; not authorised) |
| R-6 | PBDIGIT-325 |
| R-8, R-9, R-10 | recorded (R-10 → release runbook) |
| **C3 / release gate** | stays open. Before the F-4b migration (and the 1b migration) reaches any persistent database: OQ-01; the §10.2 preflight (expected 0 / 0 / 0); `SHOW lc_messages` = an English locale; code and migration deployed together |

**Accepted residual risks:**
- the classifier depends on the server's message locale (fail-closed);
- the migration runs on the next `migrate` once merged; the gate relies on process;
- PostgreSQL race detection is probabilistic, with deterministic database-free coverage;
- PostgreSQL 15 only (W2-2 provisional);
- the default suite still runs these PostgreSQL tests on `nrna_test` (until PBDIGIT-326).

**Scope:** the PostgreSQL adapter and its migration, as tested on the PBDIGIT-324 cluster. **Not** ACC-260. Slice 1b's re-entry criterion 4 (security, PBDIGIT-266; C-1 F-1 assigned to COR-4) remains open.
