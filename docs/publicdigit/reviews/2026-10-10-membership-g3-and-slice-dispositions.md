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
| E-RUN-V2 | Clean-copy rerun of the V-2 fix, 2026-11-02 08:41 +01:00, at commit `7f3c9896b` in a fresh detached worktree (no working-tree changes; dependencies from the committed `composer.lock`, `composer install --no-scripts`). PHP 8.5.11, PHPUnit 11.5.6. Five suites, all **exit 0**; counts and SHA-256 in Section 1, "V-2 fix: evidence and review" | VERIFIED |
| E-RUN-V2-LOC | The outputs, commands, exit files and `SHA256SUMS` were produced in a session scratchpad (`…/scratchpad/d1-evidence/`). **Archived 2026-11-02 on the PO's G-8 decision** in `docs/publicdigit/membership/evidence/2026-11-02-v2-clean-copy/` (commit `5761bee52`); all hashes verified after copying | VERIFIED; G-8 closed |
| E-REV-V2 | Independent review of the V-2 fix (`115e031de`) and C-AMEND-2 (`7f3c9896b`), 2026-11-02, by a separate Claude session. The report was supplied in session and is **not tracked in git** | INDEP |
| E-REG | Gate decision record rows 1 – 7; contract §16; Human ARB record (ARB-MEM-01, ARB-SEC-01, ARB-DEC-01, ARB-MEM-02, ARB-TEST-01) | DECISION |

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

**Human PO / ARB G3 decision** (accept with explicit residual risks / defer for evidence / reject with reasons):

**DEFER FOR EVIDENCE.** Decided by the PO (Human PO / ARB role) on 2026-11-02, in session.

**Reason:** defect **V-2**. `decideAnnul` does not check the Annul's own decision provenance (`app/Contexts/Membership/Domain/Organisational/Member.php:156`), against correction contract §3.1 / BR-9a. The defect is confirmed at HEAD in `docs/publicdigit/reviews/2026-11-02-membership-ddd-status.md` §4.

**At the same time, the PO authorised the V-2 fix** (story draft MEM-K1) inside PBDIGIT-268. C-1 acceptance is to be reconsidered on that evidence.

### V-2 fix: evidence and review (2026-11-02)

**This subsection records evidence for the PO's reconsideration of G3. It records no acceptance.** The G3 decision above (DEFER FOR EVIDENCE) stands until the PO decides again.

**Commits:** fix `115e031de` (`Member::decideAnnul` guard, `RefusalReason::AnnulRequiresDecisionProvenance`, three tests, contract §3.1 C-AMEND-2 text); gate register row 7 `7f3c9896b`.

| Criterion | Test(s) in `AnnulVoidTest` (names VERIFIED at `7f3c9896b`) | Label | Limitation |
|---|---|---|---|
| C-AMEND-2 / BR-9a: the Annul's **own** provenance must be `decision` (`rule` and `attested` refused with `AnnulRequiresDecisionProvenance`) | `test_an_annul_without_decision_provenance_is_refused_and_changes_nothing` | E-RUN-V2 + E-REV-V2 | the target is decision-provenance `A1`, so a check aimed at the target's provenance would fail it. The "version / log / resolution unchanged" assertions are always true, because `decideAnnul` is pure and the helper does not commit (as for K-A4); the evidence is the refusal |
| C-AMEND-2: a decision-provenance Annul behaves as before (K-A1 characterisation) | `test_an_annul_with_decision_provenance_is_still_accepted` | E-RUN-V2 + E-REV-V2 | does not assert the accepted fact's provenance (K-E1 covers fact data) |
| C-AMEND-2 check position: after `DuplicateFactId`, `TargetNotFound`, `DecidedAtRequired`; before `AnnulOfVoidRefused`, `TargetNotLive`, `TargetHasNoDecisionProvenance` | `test_annul_provenance_guard_order` | E-RUN-V2 + E-REV-V2 | precedence is exercised with an `attested` Annul only |

**Reproduced at `7f3c9896b` (E-RUN-V2).** Command form `vendor/bin/phpunit --do-not-cache-result <path>`, run from the worktree root with the worktree's `phpunit.xml` (its bootstrap only pins environment variables; every test in these paths extends `PHPUnit\Framework\TestCase`, so no database is used). Exit code captured from `$?`.

| Suite (path) | Result | Exit | Output SHA-256 |
|---|---|---|---|
| `tests/Unit/Contexts/Membership/Domain/Organisational/AnnulVoidTest.php` | OK (24 tests, 159 assertions) | 0 | `b68caddb6f8367af47978ec0d55c37ee6f9d1bbe58ff0ec05407e17b0b3e9da9` |
| `tests/Unit/Contexts/Membership/Domain/Organisational` | OK (117 tests, 112565 assertions) | 0 | `7ba81fc9cd7b7bdf66668646406f71ef51b16320ec5ad61ad936a82a2943aa8a` |
| `tests/Unit/Contexts/Membership/Application/Organisational` | OK (61 tests, 226 assertions) | 0 | `1b3838b7c977a1910dbb0c372e380f0a062b42c305bc7de8f58649e33c3f098d` |
| `tests/Unit/Contexts/Membership/Infrastructure/Organisational` | OK (13 tests, 41 assertions) | 0 | `9a3141ab28a0d19dfd20ba5ae3ba32544c0dde23f1b1f329ef3550457bbf53a5` |
| the 7 `tests/Architecture/Membership*Test.php` files | OK (51 tests, 62 assertions) | 0 | `5a4c3a1bf2cdecbe0b24d1e441722955d4c9f2d396af9c15d20fbe20465127e1` |

**Historical, not reproduced (shared working tree, before the commit; outputs outside the repository):**
- `red.txt` (SHA-256 `d9a332e3…`): RED 1. Two tests error because `RefusalReason::AnnulRequiresDecisionProvenance` did not exist yet.
- `red2.txt` (`3a408bf5…`): RED 2, the reason present and the guard absent. Test 1 fails on its **`attested` iteration** (refusal `null`, i.e. the Annul was accepted). PHPUnit stops at the first failing assertion, so the **`rule` iteration was not observed failing**. Test 3 fails at "before `AnnulOfVoidRefused`": the refusal was `AnnulOfVoidRefused`, i.e. **wrong precedence, not acceptance**.
- `review-annulvoid.txt` (`3e270d96…`, 24 OK) and `review-domain.txt` (`f4630346…`, 117 OK): GREEN and regression, not tied to a commit. Superseded as evidence by E-RUN-V2. The Application, Infrastructure and architecture runs at commit time were REPORTED only (no output kept); E-RUN-V2 now covers them.
- No removal mutant was run. The independent review rates it optional: `red2.txt` already shows the tests failing without the guard.

**Independent review (E-REV-V2) rulings:** contract ACCEPTABLE; tests ACCEPTABLE; evidence ACCEPTABLE WITH CONDITIONS (D1); register and contract traceability ACCEPTABLE WITH CONDITIONS (D2); residual conditions ACCEPTABLE WITH CONDITIONS; the 2026-10-10 recommendation maintained with qualification. **Reviewer recommendation (§15.7(7)): ACCEPT WITH EXPLICIT RESIDUAL RISKS, subject to D1 and D2.** A reviewer recommendation, not a Human PO / ARB decision.

**Review conditions:**
- **D1** (clean-copy run at a named commit, outputs hashed and recorded): **done**, E-RUN-V2 above.
- **D2** (traceability):
  - this subsection: **done**;
  - contract §16 C-AMEND-2 row: **done** (summary of register row 7, no new decision);
  - contract §14.3 / §15.3 annotations (K-A2 means the **target's** provenance; K-A3 and K-A5 assume a decision-provenance Annul; §15.3 "holds" row): **done 2026-11-02 on the PO's decisions P1 (option B: annotation plus acceptance criterion K-A6) and P2**;
  - V-1 scope extended to the Annul's own provenance on load (verification plan, COR-4 entry criterion): **done 2026-11-02 on the PO's decision P3**, as roadmap R-7 (`768720438`).
- **D2 is therefore complete.**

**Residual risks added by V-2 (in addition to the 2026-10-10 list above):**
- **V-1:** `Member::fromLog` would also load an Annul whose own provenance is not `decision`, so C-AMEND-2 is enforced on the decision path only. No persistence path for Annul exists yet (V-4). The scope extension is recorded (roadmap R-7); **V-1 stays a residual risk until COR-4**;
- **V-3:** only source-level protection; one `new Annul` site in `app/` today. Stays a COR-3 entry criterion;
- **V-4:** Annul / Void cannot be stored; deferred to C-4 (C-AMEND-1 F-1);
- **V-5:** tied `recordedAt` values in KnownAt; assigned to COR-4;
- the `rule` iteration of RED 2 was not observed (see above).

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

**Human PO / ARB verdict: ACCEPTED.** Decided by the PO (Human PO / ARB role) on 2026-11-02, in session. This is the formal acceptance act for PBDIGIT-258.

**Evidence available at the decision:**
- this section;
- the database-free re-run on 2026-11-02 at HEAD `85f3bb746`: Domain suite 114 tests OK, `MembershipSemanticKernelPurityTest` OK (`docs/publicdigit/reviews/2026-11-02-membership-ddd-status.md` §3).

That run used the shared working tree; it is not the roadmap's W0 clean-copy run.

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

**Human PO / ARB verdict: ACCEPTED, option (a)** (S1-1 / S1-3 confirmed under ARB-MEM-04). Decided by the PO (Human PO / ARB role) on 2026-11-02, in session. This is the formal acceptance act for PBDIGIT-259.

**Evidence available at the decision:**
- this section;
- the database-free re-run on 2026-11-02 at HEAD `85f3bb746`: Application suite 61 tests OK, `MembershipSlice1aBoundaryTest` OK (`docs/publicdigit/reviews/2026-11-02-membership-ddd-status.md` §3).

That run used the shared working tree.

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
| G-8 | E-RUN-V2 outputs are only in a scratchpad | Human PO / ARB | as G-6: authorise copying the 5 outputs + `SHA256SUMS` into the repository, or accept the hashes as the reference. **CLOSED 2026-11-02:** the PO chose to archive (option B); `docs/publicdigit/membership/evidence/2026-11-02-v2-clean-copy/` (`5761bee52`) |

**Not done here (ARB-MEM-03 §4):**
- no acceptance;
- no code or test change;
- no test run;
- no 264 / 266 / 267 / 269 work;
- no C-2 to C-4 or Slices 2 – 15;
- no contract change;
- no commit or push.
