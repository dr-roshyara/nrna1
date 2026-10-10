# Membership: G3 (C-1) and Slice 0 / 1a / 1b dispositions, evidence and decision sections

**Authority:** Human PO / ARB decision **ARB-MEM-03** (2026-10-10, approved, limited scope): one concise document with four separately identifiable decision sections. The Principal Reviewer's recommendation (Section 1) and the S1-1 / S1-3 confirmations (Section 3) are recorded under **ARB-MEM-04** (2026-10-10, approved, limited scope). **No acceptance is recorded here.** Every verdict field is left for its owner.
**Baseline:** branch `knowelegeos-modelling`, HEAD `ce8bf3109db3acfcc7551c6baa2f609078b2f659`.

**Labels:**
- **VERIFIED:** checked in the repository or in captured output during this work;
- **INDEP:** verified by the independent reviewer (`2026-10-10-membership-c1-correction-independent-review.md`);
- **REPORTED:** stated by an earlier review or session and not re-checked here;
- **DECISION:** a recorded human decision;
- **OPEN:** unresolved;
- **PROPOSED:** a proposal, not a decision.

## Shared evidence index

| ID | Evidence | Label |
|---|---|---|
| E-RUN | Targeted rerun, 2026-10-10 18:27, HEAD `ce8bf3109`, PHP 8.5.11, PHPUnit 11.5.6, clean `app/` and `tests/`. Domain `tests/Unit/Contexts/Membership/Domain/Organisational/`: **exit 0**, OK (89 tests, 112487 assertions), output SHA-256 `24895ae8…`. Application `…/Application/Organisational/`: **exit 0**, OK (34, 93), `b94b6cde…`. Architecture (the 3 Membership files): **exit 0**, OK (12, 18), `6ebb4e45…`. Command form: `vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php <path>`; exit code captured from `$?` | VERIFIED |
| E-RUN-LOC | The full outputs and exit files are in the session scratchpad (`…/scratchpad/g3-rerun/`), **not in the repository**, so this location is temporary | gap G-6 |
| E-IND | Independent review of the C-1 correction: ACCEPT WITH EXPLICIT CONDITIONS; criteria traced; M0 – M9 genuine assertion kills | INDEP |
| E-ACC | Acceptance review §11 (correction evidence) and §12 (condition dispositions) | REPORTED (developer) |
| E-INF | Infection 0.29.10: 129 mutants, 119 killed, MSI 92 %, covered 95 % (scope: Member / Resolution / Annul / VoidFact) | REPORTED (developer only) |
| E-WIDE | The wider Membership unit directories: Domain 27 errors / 265 tests, Application 29 / 81, in files C-1 did not touch | REPORTED (E-IND C7); not re-investigated |
| E-REG | Gate decision record rows 1 – 6; contract §16; Human ARB record (ARB-MEM-01, ARB-SEC-01, ARB-DEC-01, ARB-MEM-02, ARB-TEST-01) | DECISION |

---

## Section 1: C-1 / G3 (PBDIGIT-268)

**Scope:** Annul / Void in the kernel (contract §14.3 / §15.7). **Commits:**
- implementation `f7c251354` … `5765b9fa3`;
- acceptance correction `9ae69b7bc`, `fccf5b778`, `e96d12a5d`, `d90bf8af9`;
- governance `b171768cc`, `21a2ff22f`, `76b1192de`, `ce8bf3109`.

**Production scope:** `git diff 099587851^ 5765b9fa3 -- app/` touches only `app/Contexts/Membership/Domain/Organisational/` (VERIFIED). All tests below are in `tests/Unit/Contexts/Membership/Domain/Organisational/`, and all pass in E-RUN.

| Criterion | Test(s) (names VERIFIED at HEAD) | Label | Limitation / action |
|---|---|---|---|
| K-A1 Annul accepted, target no longer live | `AnnulVoidTest::test_annulling_an_admission_removes_its_period`, `…test_annulling_the_later_admission_first_lets_the_end_be_annulled`; differential | INDEP + E-RUN | — |
| K-A2 non-decision provenance refused | `…test_annulling_a_fact_without_decision_provenance_is_refused` | INDEP + E-RUN | — |
| K-A3 Annul(Void) refused | `…test_annulling_a_void_is_refused` | INDEP + E-RUN | — |
| K-A4 `DecidedAtRequired` | `…test_annulling_without_decided_at_is_refused_and_changes_nothing` | INDEP + E-RUN | its "nothing appended" assertions are always true (E-IND); the real evidence is the refused decision |
| K-A5 / K-V2 `TargetNotLive` | `…test_reversing_a_fact_that_is_not_live_is_refused` | INDEP + E-RUN | **D-COR-2 is not in the independent oracle** (added by the developer's wrapper) |
| K-V1 Void accepted | `…test_voiding_an_admission_removes_its_period`, `…test_voiding_a_fact_without_decision_provenance_is_accepted`; differential | INDEP + E-RUN | — |
| K-R1 appeal restores | `…test_reversing_a_reversal_restores_the_period`; `C1PropertyTest::test_reversing_the_last_fact_restores_the_previous_state` | INDEP + E-RUN | **H-2**: no RED phase |
| K-R2 Void(Annul), Void(Void) restore | `…test_reversing_a_reversal_restores_the_period` | INDEP + E-RUN | H-2 |
| K-R3 overlapping restoration refused | `…test_restoring_an_admission_that_would_overlap_a_revived_period_is_refused` | INDEP + E-RUN | H-2 |
| K-G1 G\* refuses the INV-9 post-state | `…test_annulling_an_end_whose_revival_overlaps_a_later_period_is_refused`; `C1PropertyTest::test_a_reversal_resolves_as_deleting_its_target_and_dependants` | INDEP + E-RUN | H-2 |
| K-H1 P-KNOWN | `…test_a_reversal_leaves_earlier_knowledge_unchanged`; `C1PropertyTest::test_known_at_equals_the_oracle_and_is_prefix_stable` | INDEP + E-RUN | H-2; bounded (H = 5, L ≤ 5); **equal `recordedAt` not enumerated** |
| K-E1 the decision carries the event data | `…test_an_accepted_annul_carries_its_fact_data`, `…test_an_accepted_void_carries_its_fact_data` | INDEP (M1 – M4) + E-RUN | emission is C-3 |
| Guard order (plan P-3) | `…test_a_reused_fact_id_is_refused_first`, `…test_reversing_an_unknown_fact_is_refused`, the 3 pairwise guard-order tests | INDEP (M5 – M7) + E-RUN | — |
| K-X1 Admit / End-only behaviour unchanged | E-RUN (all 3 groups exit 0) | VERIFIED | only the targeted groups; the **wider suite is not green** (E-WIDE) |
| K-X2 exhaustive differential | `C1ExhaustiveDifferentialTest::test_the_kernel_equals_the_g6_port_within_bounds` | INDEP + E-RUN | bounded (15530 decisions) |
| §15.7(1) RED first for each criterion | commit history | DECISION **H-2** (deviation accepted as documented) | K-R1, K-R2, K-R3, K-G1, K-H1 |
| §15.7(2) oracle port + golden fixture | `G6CorrectionOracleGoldenTest::test_the_php_port_reproduces_the_python_oracle_stream` | INDEP | transcription fidelity only |
| §15.7(3) differential | as K-X2 | INDEP | bounded |
| §15.7(4) properties (§14.4) | the 6 `C1PropertyTest` property tests | INDEP + E-RUN | bounded |
| §15.7(5) mutation testing | E-INF; kit M0 – M9 | E-INF REPORTED; kit INDEP | runner weaknesses (PBDIGIT-269, OPEN) |
| §15.7(6) regression | E-RUN | VERIFIED | E-WIDE stays separate |
| §15.7(7) guide, review, **PR decision** | guides 04 / 05; the implementation, acceptance and independent reviews | guides and reviews VERIFIED; **PR decision OPEN** | **required for G3** |
| §15.7(8) out of scope respected | production diff confined to `Domain/Organisational` | VERIFIED | — |
| Persistence of Annul / Void | — | out of C-1 scope (C-AMEND-1 C-4 criteria) | **not resolved by the scope exclusion**: a downstream C-4 dependency |

**Residual risks:**
- bounded evidence, not a proof;
- the D-COR-2 oracle gap;
- equal `recordedAt` not enumerated;
- Infection developer-only;
- runner classification weaknesses;
- E-WIDE errors with no owner;
- persistence unverified (C-4).

**Principal Reviewer acceptance recommendation (§15.7(7)): ACCEPT WITH EXPLICIT RESIDUAL RISKS.** Source: Principal Reviewer review supplied in session 2026-10-10 (`docs/publicdigit/membership/architecture/brainstorming/202610101901_implementation.md`, not tracked in git). A reviewer recommendation, not a Human PO / ARB decision.

The residual risks recorded by the reviewer:
- D-COR-2 is not covered by the independent oracle;
- equal `recordedAt` values have not been enumerated;
- Infection results are developer-reported, not independently rerun;
- the wider Membership suite errors remain unclassified and have no confirmed owner;
- persistence remains unverified and is a downstream C-4 dependency;
- the mutation-testing runner weaknesses remain open under PBDIGIT-269;
- the differential / property evidence is bounded and is not a proof of correctness.

The recommendation does not mean that the wider Membership suite passes or that persistence is proven.

**Human PO / ARB G3 decision** (accept with explicit residual risks / defer for evidence / reject with reasons): _[to be completed by the Human PO / ARB]_

---

## Section 2: Slice 0 (PBDIGIT-258)

| Criterion (story AC) | Evidence | Label |
|---|---|---|
| AC 1 pure domain, architecture test | `MembershipSemanticKernelPurityTest` (part of E-RUN architecture, exit 0) | VERIFIED |
| AC 2 – 4 Admit / End decisions, pure Resolution, invariants tested | Slice 0 kernel tests (part of E-RUN domain); Slice 0 review §11 – §12 | VERIFIED (pass) + REPORTED (review) |
| AC 5 oracle, exhaustive, property, metamorphic, differential | Slice 0 review lines 51, 93, §11 – §12 ("0 mismatches") | REPORTED |
| AC 6 review + developer guide | `2026-10-09-membership-slice0-tdd-implementation-review.md`; `developer_guide/membership/01_…` | VERIFIED |
| Condition D-1 (`DuplicateFactId` guard) | the Slice 1a contract line 4: the reviewer's instruction "confirms D-1 = INV-FID"; ARB-DEC-01: "Preserve Slice 1a D-1 … (INV-FID)" | **DECISION:** satisfied (ARB-MEM-03 §3). This closes the condition only |
| Formal acceptance act | none | OPEN |

**Proposed disposition:** ACCEPT. No open condition remains (PROPOSED).

**Human PO / ARB verdict:** _[to be completed]_

---

## Section 3: Slice 1a (PBDIGIT-259)

| Item | Evidence | Label |
|---|---|---|
| AC 1 – 5 (port, handlers, contract suite, concurrency, idempotency, tenant isolation, architecture) | application tests (E-RUN, exit 0); `MembershipSlice1aBoundaryTest` (E-RUN architecture) | VERIFIED (pass) |
| **S1-1:** "no database in 1a; the contract suite is ready for 1b" (1a review §H) | Slice 1b was later built as the database adapter, consistent with S1-1 | consistency INFERRED; **reviewer confirmation: CONFIRMED** (see below) |
| **S1-3:** IDEM-2 "same key with another fingerprint → `IdempotencyKeyConflict`"; IDEM-4 "only appended outcomes are remembered" (1a contract lines 61 – 63) | defined as technical rules; "reviewer to confirm" (1a review §H) | **reviewer confirmation: CONFIRMED** (see below). Passing tests do not substitute (ARB-MEM-03 §3) |
| Administrative condition 3 (README rows 258 / 259) | `docs/publicdigit/backlog/README.md` lines 137 – 138 | VERIFIED (done) |

The Principal Reviewer's confirmation of S1-1 and S1-3 is recorded below, under ARB-MEM-04. What remains is the Human PO / ARB verdict.

**Options:**
- (a) accept (S1-1 / S1-3 confirmed under ARB-MEM-04);
- (b) defer, with stated reasons.

No option is proposed on your behalf.

**Principal Reviewer confirmation of S1-1 / S1-3: CONFIRMED** (for review purposes). Source: Principal Reviewer review supplied in session 2026-10-10 (`docs/publicdigit/membership/architecture/brainstorming/202610101901_implementation.md`, not tracked in git). A reviewer recommendation, not a Human PO / ARB decision.

What is confirmed:
- **S1-1:** Slice 1a is the database-independent contract boundary; persistence is supplied by the subsequent adapter layer (Slice 1b). Cited: the 1a review §H (S1-1) and "Final status" condition 1.
- **S1-3:** IDEM-2 rejects the same idempotency key with a different fingerprint as `IdempotencyKeyConflict`; IDEM-4 remembers only appended outcomes. Cited: the 1a contract lines 61 – 63; the 1a review §H (S1-3) and "Final status" condition 2.

The confirmation rests on the reviewer's reading of the contract and review, not on passing tests alone.

**Human PO / ARB verdict:** _[to be completed]_

---

## Section 4: Slice 1b (PBDIGIT-260 / ARB-MEM-02)

**Status: DEFERRED** (ARB-MEM-03 §3; DECISION). It stays conditionally accepted under ARB-MEM-02 and must not be described as formally accepted.

| Item | Evidence | Label |
|---|---|---|
| Contract tests T1 – T13 (1b contract §8) | the 4 PostgreSQL files in `tests/Feature/Contexts/Membership/Organisational/`: `PostgresConcurrencyRaceTest` (3 methods), `PostgresMemberRepositoryInvariantsTest` (9), `PostgresReferenceModelPropertyTest` (1), `PostgresMemberRepositoryTest` (the inherited `MemberRepositoryContract` suite) | files VERIFIED; the method count of the inherited suite INFERRED |
| Execution on an isolated database | `phpunit.xml:70` targets the **shared** `nrna_test` (ARB-SEC-01 gate FAIL) | **OPEN** |
| Far-date defect (PBDIGIT-264 F-1) | `DateOrdinal::toDate()` formats ordinal ≥ 2932897 as `10000-01-01`, which `fromDate()` rejects, so the Member becomes unloadable | REPORTED (1b acceptance review); a **real defect** |
| PBDIGIT-264 F-2 to F-5 | race-test gaps, adapter parity, the FK (F-4b migration), SQLSTATE detection | OPEN |
| C-1 F-1 (`encode()` typing) | static only; no runtime path (C-1 acceptance review §5) | OPEN, assigned to C-4 (H-3) |
| Security on the same database (PBDIGIT-266) | — | OPEN |

**Re-entry criteria for reconsideration:**
1. the PostgreSQL suite passes on an explicitly isolated database, with the command, database name, output and exit code captured;
2. the far-date defect is fixed test-first (RED, then GREEN), with no persistence of out-of-range dates;
3. the applicable 264 items are dispositioned;
4. the relevant persistence and security acceptance conditions are complete (ARB-MEM-04 §5).

Each needs its own approved implementation plan. The first is drafted in `docs/plans/20261010-1830-membership-pg-verification-and-far-date-plan.md` and awaits review and approval.

**Human PO / ARB verdict (when reconsidered):** _[to be completed]_

---

## Evidence gaps and owners

| # | Gap | Owner | Next action |
|---|---|---|---|
| G-1 | the PR's C-1 recommendation | Principal Reviewer | **done 2026-10-10** (Section 1): ACCEPT WITH EXPLICIT RESIDUAL RISKS |
| G-2 | S1-1 / S1-3 confirmation | Principal Reviewer | **done 2026-10-10** (Section 3): CONFIRMED |
| G-3 | an isolated PostgreSQL database | Human PO / ARB, infrastructure | provision; unlocks Slice 1b and 266 |
| G-4 | far-date defect | engineering (approved plan) | TDD fix per the 1830 plan |
| G-5 | E-WIDE errors with no owner | engineering, then a PO owner | a separately authorised read-only triage |
| G-6 | E-RUN outputs are only in the scratchpad | Human PO / ARB | authorise copying the 3 outputs + `env.txt` into the repository, or accept the hashes as the reference |
| G-7 | Infection not independently rerun | Principal Reviewer (optional) | — |

**Not done here (ARB-MEM-03 §4):**
- no acceptance;
- no code or test change;
- no test run;
- no 264 / 266 / 267 / 269 work;
- no C-2 to C-4 or Slices 2 – 15;
- no contract change;
- no commit or push.
