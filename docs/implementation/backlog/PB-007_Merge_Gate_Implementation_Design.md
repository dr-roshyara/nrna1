# PB-007 (Greenfield Merge Gate) — Implementation Design Document (IDD)

**Status:** **FROZEN** — ARB approved 2026-07-10 (A-1..A-3 granted §2a; two freeze refinements applied §2c). Changes only via a new ARB ruling. PB-007 proceeds through its remaining slices under this approved governance model.
**Grounds:** Discovery `docs/implementation/PB-007_Discovery_Findings.md` (4 ARB rulings applied: gate taxonomy Architecture/Behaviour/Engineering/Improvement/Reporting · Local/CI per gate · single entry point · DoD-warning classified with evidence).

## 1. Purpose
Harden the engineering process that protects the qualified architecture: one executable **Greenfield Merge Gate** where **Architecture + Behaviour + Engineering gates block** and **Improvement/Reporting gates trend**. PB-007 proves conformance protection — it changes no product behaviour.

## 2. Slices (each: evidence → stop at the boundary)
- **7A — Deptrac (Architecture gate):** install the binary (**A-1**: `composer require --dev deptrac/deptrac` — lockfile + network, explicit approval requested); widen `deptrac.yaml` scope to **Election** (a complete hexagonal context since PB-004); add a **Shared-Messaging layering decision** (**A-2** proposal: Shared is a platform layer that contexts' Application/Infrastructure may depend on; Shared's own purity is covered by fitness tests, so Deptrac rules for Shared = context-isolation only). First run in **report mode**; genuine violations become **classified ARB findings — never silent fixes**; then flip to fail mode.
- **7B — CorrelationId-minting fitness test (deferred from PB-006):** executable rule — only chain-origin code may call `EventProvenance::start()`; reacting handlers/adapters must use `fromConsumed()`. Hosted in the Messaging fitness suite (owner-hosts-the-guard, ADR-MP-03). RED-first.
- **7C — Infection (Engineering Improvement):** `infection.json` scoped to the greenfield Core (Contestation/Adjudication/Election + Shared Inbox/Outbox/Messaging); one run → record the **baseline MSI**; **A-3** ratchet policy (raise deliberately at milestones; never retroactive; no enforcement inside PB-007).
- **7D — Single entry point + CI:** composer scripts **`merge-gate`** (fitness suites → Deptrac → greenfield PHPStan → widened regression; fail-fast; one PASS/FAIL) and **`quality-gate`** (Infection, coverage). New workflow **`greenfield-merge-gate.yml`** running `composer merge-gate` on PRs; quality tier separate/scheduled; existing workflows untouched.
- **7E — Qualification + Completion Review:** Architecture + DDD + Trustworthiness (the gate must not weaken any invariant — it only enforces) · EP-02 → **STOP** (the retrospective follows, not started).

## 2a. ARB refinements (binding, applied 2026-07-10)
- **R1 — Rules from architecture, not filesystem:** Deptrac rules are derived from the APPROVED architectural model (bounded contexts, hexagonal layers, approved dependencies) — never from the current package layout alone. The tool must not codify a temporary implementation detail.
- **R2 — Ownership:** PB-007 only HOSTS the CorrelationId-minting fitness test; the invariant remains OWNED by the Messaging Platform architecture (ADR-MP-06; owner-hosts-the-guard ADR-MP-03).
- **R3 — Stable interface:** `composer merge-gate` is a STABLE PUBLIC INTERFACE to the engineering system — internal tooling may change; the command contract must not.
- **Slice reorder (ARB):** the CI workflow orchestrates FINISHED gates, so it moves last: **7A Deptrac → 7B minting fitness → 7C Infection → 7D composer entry point → 7E CI workflow → Qualification/Completion**.
- **Approvals granted:** A-1 ✅ (report mode first, lockfile change isolated to this slice) · A-2 ✅ conditionally (Shared = infrastructure/platform layer; Deptrac enforces only APPROVED dependency rules, infers no new architecture) · A-3 ✅.
- **Rollout order (never inverted):** Install → Report → Classify violations (ARB findings, never silent fixes) → Fix intentionally → Fail mode.

## 2b. Recorded future refinements (ARB @ 7B acceptance — NOT blocking, NOT scheduled)
- Replace the minting-guard file-path allowlist with a semantic marker (annotation/interface, e.g. `OriginProducer`) so file moves never touch the architecture test.
- Strengthen the provenance rule: reacting producers must ALWAYS propagate (not only never mint).
- Detector hardening: ignore string literals as well as comments.
- Complementary fitness rules toward a complete constitutional provenance specification: every produced event carries a CorrelationId; every consumed event exposes one.
- **(ARB @ 7C)** Split Infection reports by bounded context (Election MSI · Contestation MSI · Adjudication MSI · Messaging MSI) as an architectural health indicator. Future, not now.
- **(ARB @ 7C)** Highest-value mutation targets for the election-trust argument: election state transitions, correction loop, constitutional invariants, messaging provenance, anonymity. Mutation testing there proves *constitutional invariants cannot be trivially broken without tests failing* — a stronger statement than coverage.
- **(ARB @ IDD freeze)** Candidate engineering principle for formal adoption (e.g. in the process doc / an ADR): *"Quality gates are permitted to reveal latent defects. They are not responsible for introducing them."* — summarizes 7A (Deptrac revealed, didn't create, violations) and 7C (Infection revealed, didn't create, the coverage incompatibility).
- **(ARB @ IDD freeze)** Engineering Trust Dashboard: one health view over the gates (Architecture PASS · Messaging PASS · DDD PASS · Regression PASS · Mutation trend · Coverage trend). Explicitly deferred — "not now, later."

## 2c. 7C evidence record (ARB ruling applied, 2026-07-10)

Two **different classes** of finding are recorded here (ARB freeze refinement 1 — never conflate them): **platform compatibility repairs** belong to PB-007; **pre-existing repository debt** belongs to historical cleanup (retrospective), not to this ticket.

### 2c-i. Platform compatibility repairs (PB-007-owned)

**PHPUnit configuration change — classification (ARB-required note):**
> **PB-007 did not introduce a new coverage policy. It repaired the project's PHPUnit configuration so that the existing PHPUnit 11 installation can actually generate coverage. Infection merely exposed the latent incompatibility.**

`phpunit.xml` still carried the PHPUnit-9-era `<coverage processUncoveredFiles>` + `<include>` form; installed PHPUnit is 11.5.6, whose XSD rejects it. The defect was latent because Infection is the **first automated consumer of executable coverage** in this repository. Classification: **platform compatibility repair** (Observed → Engineering repair → Recorded — not an Infection implementation detail, not a behaviour change).

**Coverage-driver substitution (engineering evidence, deptrac-shim precedent):**
- Observed: no pcov/xdebug extension on this machine; the plan was phpdbg (verified present, PHP 8.3.24).
- Observed: **PHPUnit 11 dropped phpdbg support** (`php-code-coverage` ≥ 10 supports only Xdebug/PCOV) — the phpdbg route fails with "No code coverage driver available" even when hosted by `phpdbg -qrr`. The earlier "phpdbg suffices, no environment change needed" conclusion is corrected by this evidence.
- Repair: `php_xdebug-3.5.3-8.3-ts-vs16-x86_64.dll` (exact build match: PHP 8.3.24 · TS · VS16 · x64) placed in the PHP extension dir; **enabled per-invocation only** via `--initial-tests-php-options="-d zend_extension=… -d xdebug.mode=coverage"`. No `php.ini` change; default PHP unaffected (verified: `php -m` shows no xdebug); mutant test runs execute on plain PHP (no coverage overhead). Reversible by deleting one DLL.
- **Stable-interface principle (ARB):** *the stable interface (`composer merge-gate` / `quality-gate`) owns the invocation semantics.* Developers never care whether the implementation uses phpdbg, Xdebug, or PCOV — the interface stays constant; the driver is an implementation detail hidden inside 7D's composer scripts.

### 2c-ii. Pre-existing repository debt (historical — recorded, NOT repaired; all → retrospective list)

**Excluded behaviour-preservingly:**
| # | Finding | Impact observed | 7C action |
|---|---|---|---|
| F-7C-1 | `tests/Unit/Domain/Election/Security/OverlaySignalCategoryTest.php` declares class `OverlayInfluenceTest` (mismatch; legacy `89914ce3a`) — PHPUnit cannot load the class, so its tests are **already dead** in every suite run | Suite-load warning; Infection's generated config sets `stopOnDefect="true"` + `failOnWarning="true"`, so the warning aborted the initial run with "No tests executed" | Excluded from the Unit testsuite (zero executed tests removed — behaviour-preserving) |
| F-7C-2 | `app/Domain/Election/Models/ElectionUser.php` is a scaffold *document*: comment header + a second `<?php` at line 11 — parse error; never autoloaded, so latent | Coverage static analyzer aborts ("Cannot parse … unexpected '<'") | Excluded from coverage `<source>` (never coverable — behaviour-preserving) |
| F-7C-3 | `app/Http/Middleware/VoterSlugStep.php` is a **markdown document saved as .php** (git-recovery batch `86468d3d7`) — parse error | Same coverage-parse abort class | Excluded from coverage `<source>` (same rationale) |

Scan evidence: `php -l` over all **1,511** files under `app/` → exactly these **2** parse failures (so individual exclusion, not source-narrowing, is the minimal repair; the coverage source policy stays `./app`, unchanged).

**Exclusion-integrity statement (ARB freeze refinement 2):** *Coverage exclusions were applied only because the files are non-executable repository artifacts (dead scaffold or malformed recovery artifact). Coverage policy for executable production code remains unchanged.*

**Further pre-existing debt observed by the full-suite run (recorded, NOT repaired; → retrospective):**
| # | Finding | Evidence |
|---|---|---|
| F-7C-4 | `Tests\Unit\Middleware\ValidateVotingIpTest::it_blocks_request_when_ip_does_not_match` **fails standalone in normal order** (middleware returns `Response`, test expects `RedirectResponse`) — legacy area, files unmodified; Infection's initial run was the first process to execute the full 6,109-test suite in one pass, which is why per-slice regression (qualified greenfield suites) never saw it | Reproduced standalone; `git status` clean on both files |
| F-7C-5 | Contexts **outside** the greenfield core carry broken unit/feature tests: Membership (stale constructor signatures — e.g. `AssignMemberToCommitteeHandler` now takes 6 args, tests pass 5), older `Elections` context handler tests — 69 errors + 5 failures when swept into one run | Broad-suite run: 997 tests, 69 errors, 5 failures, all in Membership/Elections/Geography/Governance |
| F-7C-6 | **57 risky tests** in the greenfield Feature suites: "test code or tested code removed error/exception handlers other than its own" — framework-level (Laravel app teardown/rebuild vs PHPUnit 11 handler bookkeeping); no `set_error_handler`/`restore_*` calls exist in `app/` or `tests/` (grep-verified) | GreenfieldCore run: 179 tests, 0 failures, 57 risky, exit 0 |

### 2c-iii. 7C engineering decisions (in-scope, recorded)

- **`GreenfieldCore` testsuite (additive, phpunit.xml):** exactly the tests that can *kill* greenfield-core mutants — Unit+Feature of Contestation/Adjudication/Election/Shared + Replay, matching the A-3 mutation scope. Deliberately excluded: other contexts (outside A-3 scope, carrying F-7C-5 debt) and `tests/Architecture` (file-scanning fitness tests read source from disk — they cannot detect in-memory mutations, zero mutation signal). Existing suites unchanged.
- **Greenfield harness-convention repair (in greenfield scope, so repaired not deferred):** `AdjudicationServiceIntegrationTest` (PB-004-era) asserted **global** `outbox_events`/`determinations` counts — the exact failure class PB-006 repaired by convention (harness has no per-test rollback → scoped assertions, never global counts). Both assertions scoped to the test's unique organisation; "exactly one event" semantics preserved. GreenfieldCore suite: **179 tests, 477 assertions, 0 failures, exit 0** after repair.
- **Two-step Infection invocation (pre-generated coverage):** Infection's generated initial-run config **forces** `stopOnDefect="true"` (verified in `XmlConfigurationManipulator::setStopOnFailureOrDefect`), and PHPUnit 11 stops on **risky** tests too (`TestResult\Facade`) — so F-7C-6's 57 risky tests would silently truncate the initial coverage run (worse than failing: a wrong baseline). Repair: use Infection's documented pre-generated-coverage interface — `phpunit --coverage-xml --log-junit` under project semantics (risky ≠ fail), then `infection --coverage=… --skip-initial-tests`. The 7D `composer quality-gate` script encodes both steps; the stable interface owns the invocation semantics.

**Baseline metrics (ARB-required set — MSI alone is insufficient) — MEASURED 2026-07-10 (Infection 0.29.10, greenfield-core scope, GreenfieldCore suite, 7m16s, 8 threads). STATUS: REJECTED (F-7D-2 evidence validation, 2026-07-10) — the escaped-mutant identity experiment proved concurrency influenced this measurement (202 false kills incl. 65 migration-file mutants); superseded by the validated 1-thread baseline in §2e; retained as history only:**
| Metric | Value |
|---|---|
| **MSI** | **75%** |
| **Mutation Code Coverage** | **78%** |
| **Covered Code MSI (Test Strength)** | **96%** |
| Mutants generated | 892 |
| Killed by tests | 638 |
| Killed by per-mutant timeout (60s) | 48 *(counted as detected; some may be environment-slow rather than genuinely trapped — caveat recorded)* |
| **Escaped (covered but undetected — highest risk)** | **22** |
| Not covered by tests | 184 |
| Errors / syntax errors | 0 / 0 |

**Escaped-mutant distribution (Measured; input for future ratchet work, NOT fixed in PB-007):**
`ChallengeReactionOutcomeTranslator` 4 · `DeterminationLacksElectionScope` 3 · `CannotApplyDeterminationToUnknownElection` 3 · `Challenge` 3 · `OutboxEventProcessor` 2 · `ChallengeResolvedHydrator` 2 · `Election`, `TargetId`, `RaiserStandingRef`, `ElectionId` (Contestation), `ChallengeAdjudicationReaction` 1 each. **Interpreted:** the largest single concentration sits on the business-condition→inbox-marker translation boundary (`ChallengeReactionOutcomeTranslator`) — squarely in the constitutional correction loop, i.e. exactly the ARB's named highest-value mutation territory; first candidate when the ratchet begins.

**Reading the baseline (Interpreted):** Test Strength 96% says code the greenfield tests actually cover is very hard to mutate silently; the MSI gap to 75% is dominated by the 184 *uncovered* mutants (Mutation Code Coverage 78%), not by weak assertions. Raising MSI = extending coverage into the uncovered 22%, then hunting the 22 escapees.

**Ratchet policy (A-3, restated):** baseline measured and recorded first; `min-msi` thresholds are raised **deliberately at milestones** — never retroactively, never lowered; **no enforcement inside PB-007** (Infection stays in the quality tier, not the merge gate).

**7C ACCEPTED by ARB (2026-07-10).** Rulings recorded with acceptance: the two-step invocation is the keeper insight (*the platform owns orchestration, not the third-party tool* — Infection must not own PHPUnit execution semantics); the 75% MSI is read via Test Strength 96% (coverage expansion, not weak assertions); escaped mutants are NOT fixed in PB-007 — ratchet work is a future backlog item (recorded as **ENG-004 Mutation Ratchet 1**, first target `ChallengeReactionOutcomeTranslator`); the cross-session collision is an operational race condition, not a test defect. 7D authorized.

## 2d. Gate tiers (ARB clarification @ 7C acceptance — binding for 7D)

The stable interface exposes the taxonomy as **two tiers**, so future maintainers can see WHY mutation testing is measured but not enforced:

| Tier | Gates | Command | Semantics |
|---|---|---|---|
| **Blocking** | Architecture (fitness suites, Deptrac **fail mode**) · Behaviour (widened regression) · Engineering (greenfield PHPStan) | `composer merge-gate` | fail-fast; one PASS/FAIL; merges blocked on failure |
| **Non-blocking** | Improvement/Reporting (Infection MSI · coverage · trends) | `composer quality-gate` | measures + records; NEVER blocks a merge in PB-007 (A-3 ratchet applies) |

## 2e. 7D evidence record (2026-07-10 — STOP for ARB review before 7E)

**Delivered:** `composer.json` scripts `merge-gate` + `quality-gate` (+ `scripts-descriptions` naming the tiers per §2d) · Deptrac **fail-mode flip executed** (the blocking gate consumes the exit code — governance change scheduled for 7D at 7A acceptance) · developer guides `developer_guide/merge_gate/00_index.md` + `01_stable_interface.md` (ARB-refined: stable architectural contract, no time-dependent values).

**Verification (Observed):**
- `composer merge-gate` → **PASS**, single command, fail-fast, exit 0: Architecture fitness **146✔/626 assertions/1 skip** → Deptrac fail mode (0 violations) → greenfield PHPStan clean → GreenfieldCore regression **179✔/477 assertions/0 failed** (57 risky = F-7C-6).
- `composer quality-gate` → runs end-to-end through the stable interface: coverage artifacts generated (`build/coverage/`), Infection consumes them `--skip-initial-tests`, metric set reported.

**F-7D-1 — Composer process timeout (classification: Engineering Platform Repair, ARB-confirmed):** Composer's default 300s per-process timeout killed Infection mid-analysis on the first `quality-gate` run. Repair: `Composer\Config::disableProcessTimeout` (documented Composer mechanism) prepended to both gate scripts. The product did not change; the engineering capability did. The gate discovered a latent defect in its own harness — the same class as 7C's PHPUnit-11 coverage repair ("quality gates reveal latent defects; they don't introduce them").

**7D ACCEPTED by ARB (2026-07-10).** Stable interface · gate-tier separation · Engineering Platform Repair classification · documentation · implementation discipline — all approved. **F-7D-2 reclassified by the ARB as an EVIDENCE INTEGRITY FINDING** (not a mutation-testing observation): it sits between **Measurement** and **Evidence** in the six-level lifecycle — the architectural question is *whether the 8-thread mutation result qualifies as verified evidence*; until that transition is validated it must not influence qualification or future ratchets. Consequently the §2c baseline is **TENTATIVE, not Approved**. **7E blocked only narrowly (ARB refinement):** CI *wiring* may technically be implemented (CI executes `composer merge-gate` regardless of Infection), but engineering **qualification must not treat the MSI as authoritative** until the evidence validation completes. **Completion Review instruction (ARB):** state explicitly that the experiment validated (or rejected) the measurement *before it entered the Evidence stage*. **This finding required an explicit validation of the measurement before it was accepted as evidence; the six-level lifecycle remains unchanged — the validation is part of establishing Evidence, not an additional lifecycle stage.** **Experiment stance (ARB):** outcome is UNKNOWN in advance — no predictive language; the evidence determines the verdict. No additional platform changes authorized.

**F-7D-2 — Evidence Integrity Finding (ARB-classified; one experiment authorized):**
| Invocation (same coverage artifacts, same 892 mutants) | MSI | Covered-Code MSI | Killed | Escaped | Not covered |
|---|---|---|---|---|---|
| 8 threads (baseline method) | 75% | 96% | 622 (+1 timeout) | 20 | 184 |
| 1 thread (accidental: `--threads=max` silently degraded to 1 in Infection 0.29.10) | 50% | 65% | 416 (+3) | 224 | 184 |

Identical uncovered sets ⇒ same mutant population and coverage mapping; only *detection* diverges — ~200 feature-covered mutants flip killed↔escaped with thread count. Competing hypotheses (Interpreted, undecided): (a) 8-thread parallelism produces **false kills** — concurrent per-mutant phpunit processes each `migrate:fresh` the shared `nrna_test`, so sibling table-drops fail tests for reasons unrelated to the mutation (a failed test counts as a kill), meaning the recorded baseline's Test Strength 96% may be optimistic for feature-covered code; or (b) sequential execution produces **false escapes** via cross-run state accumulation. Discriminating experiment identified (hand-apply one 1-thread-escaped mutant; run its covering feature test standalone; pass ⇒ (a), fail ⇒ (b)). Candidate remedy if (a): per-thread test databases via Infection's `TEST_TOKEN` convention — engineering work for ENG-004/its own item, never an ad-hoc 7D fix. Interim: the interface pins `--threads=8` (**an explicit thread count is part of the measurement semantics**, never `max` — silently degrades to 1 in 0.29.10). **What this finding determines (ARB wording):** *whether the current measurement qualifies as verified evidence suitable for establishing a future ratchet baseline* — not which number to ratchet against. **Authorized experiment (exactly one; no redesign, no tooling changes, no platform work):** same commit, same coverage artifacts — compare **escaped-mutant identities** (not merely MSI) between the 8-thread run and isolated (1-thread) execution. If the escaped set changes with concurrency → concurrency influences the measurement → the measurement fails verification and a new baseline is established under the validated execution model; if only execution time changes → the baseline is trustworthy and is recorded as verified evidence.

**EXPERIMENT EXECUTED (2026-07-10) — VERDICT: MEASUREMENT REJECTED (Outcome B).**
- **Preconditions verified before execution:** no `app/`/`tests/` changes since coverage generation (docs/scripts commits only) · no concurrent test activity · same coverage artifacts · logs preserved per leg.
- **Identity comparison (Observed):** every one of the 8-thread escapes also escapes at 1 thread (**perfect subset, 0 escaped only at 8T**); **202 mutants flip killed→escaped** when concurrency is removed. Distribution of the flipped 202: **65 in database-migration files** (e.g. column-length `Increment/DecrementInteger`, schema `MethodCallRemoval` — assertions on varchar lengths do not exist; only concurrency chaos can "kill" these) and the remainder concentrated in **messaging/persistence infrastructure** (`ChallengeOutboxAdapter` 22 · `DeterminationMapper` 15 · `InboxExecutionEngine` 14 · `OutboxEventProcessor` 11 · `RedriveParkedInboxEvents` 10 · hydrators 30 across 5 files) — all feature-test-covered.
- **Verdict (mechanical, per the pre-committed criterion):** the escaped set changes with concurrency ⇒ concurrency influences the measurement ⇒ **the 8-thread measurement fails verification and is REJECTED as evidence**. Mechanism confirmed as hypothesis (a): concurrent per-mutant phpunit processes each `migrate:fresh` the shared `nrna_test`; sibling failures count as kills. Per the Completion Review instruction: this measurement was validated and rejected **before** it entered the Evidence stage.
- **New baseline under the VALIDATED execution model (isolated 1-thread; itself reproducible — two independent runs: 416/418 killed, 224 escaped both times):** **MSI 50% · Mutation Code Coverage 77% · Covered Code MSI (Test Strength) 65%** · 892 mutants = 418 killed + 1 timeout-detected + 65 skipped (est. runtime > 60s cap) + 224 escaped + 184 not covered · runtime ≈ 1h17m. The §2c 8-thread table is **superseded (REJECTED)** and stands as history only.
- **Honest reading (Interpreted; ARB-refined wording):** *Under the validated execution model, Covered-Code MSI (Test Strength) is measured as 65%. The previously reported 96% value is retained as historical output from an execution model that did not pass evidence validation and therefore is not suitable as the baseline.* The "assertions are strong, only coverage is missing" story was an artifact of that unvalidated execution model. Escapes concentrate exactly where the ARB named the highest-value mutation territory: the messaging/persistence boundary of the constitutional correction loop. ENG-004 (Mutation Ratchet 1) baselines against THESE validated numbers.
- **Consequences:** (1) `quality-gate` must run the validated execution model — `--threads=8` → `--threads=1` (change gated behind EP-01-Light approval); (2) restoring parallel speed WITH validity = per-thread databases (Infection `TEST_TOKEN`) — future engineering work (ENG-004 or its own item), not PB-007; (3) no other changes — architecture untouched, exactly as the outcome branches predicted.

## 2f. 7E evidence record (2026-07-10 — STOP for ARB review before qualification)

**Delivered:** `.github/workflows/greenfield-merge-gate.yml` (blocking tier: every PR + main-branch pushes + manual; single step `composer merge-gate`; postgres service mirroring the `tests/bootstrap-test-database.php` forced credentials; 40-min timeout) · `.github/workflows/greenfield-quality-tier.yml` (non-blocking tier: weekly schedule + manual; single step `composer quality-gate` under the **validated 1-thread execution model**; Xdebug via setup-php ini on Linux — the script's per-invocation Windows DLL flag degrades to a startup notice, `-d xdebug.mode=coverage` applies either way; mutation reports uploaded as artifacts) · guide `developer_guide/merge_gate/02_ci_workflows.md`. Existing workflows untouched (knowledge-lint · membership-architecture · regression-detector).

**Design honored:** the workflows contain **no gate logic** — each invokes exactly one stable command (R3), so CI can never drift from the local gate; quality tier separate/scheduled, never on the merge path (§2d).

**Verification (Observed):** both workflow files parse as valid YAML (Symfony Yaml); the invoked commands are the ones verified in §2e (merge-gate PASS locally; quality-gate end-to-end). **Evidence limit (honest):** "CI workflow green" (§5) requires an actual PR/dispatch run on the forge — achievable only after push; recorded as the remaining §5 item, not claimed.

## 3. Boundaries
No domain/aggregate/platform-logic changes · pre-existing findings recorded, not fixed (7A report-mode findings go to the ARB) · no new patterns · the frozen `.claude` platform untouched.

## 4. Approvals requested with this IDD
- **A-1:** `composer require --dev deptrac/deptrac` (lockfile + network change).
- **A-2:** the Shared-layer Deptrac proposal (§7A) — or rule Shared differently.
- **A-3:** Infection baseline-then-ratchet policy (no enforcement in PB-007 itself).

## 5. Evidence at completion
Single-command `composer merge-gate` PASS output · Deptrac report (violations classified or zero) · minting fitness test RED→GREEN · baseline MSI recorded · CI workflow green · widened regression exact numbers · triple qualification · Completion Review.

## 6. Triple Qualification + EP-02 Completion Review (2026-07-10) — STOP: awaiting formal ARB closure

### 6.1 Architecture Qualification — PASS (executed, not cited)
Fresh `composer merge-gate` run, exit 0, single command, fail-fast: Architecture fitness **146✔ / 626 assertions / 1 skip** (hexagonal completeness · event ownership · anonymity AT-Q7 · CorrelationId minting guard) → Deptrac fail mode **[OK] No errors** (15 approved dependency directions; architecture-derived rules, R1) → greenfield PHPStan clean → widened regression **179✔ / 477 assertions / 0 failed** (57 risky = F-7C-6, exit-code semantics per §2d).

### 6.2 DDD Qualification — PASS
PB-007 built **no domain code**: no bounded context gained, lost, or changed a concept; no aggregate, event, or published-language element touched. Ownership remained where the architecture put it — the CorrelationId-minting invariant is OWNED by Messaging and only HOSTED by PB-007's guard (ADR-MP-03/06, R2); Deptrac rules derive from the approved model, not the filesystem (R1); Shared stayed a platform layer with the admission rule intact. No candidate pattern was promoted; every engineering concern stayed outside the bounded contexts. The qualification statement is deliberately *"DDD ownership remained unchanged"* — stronger than "DDD passed."

### 6.3 Trustworthiness Qualification — PASS
**Product trustworthiness: unchanged. Confidence in the engineering qualification evidence: increased** (ARB-refined — PB-007 demonstrated better evidence, better measurement discipline, and reproducible qualification; it did not improve every aspect of the engineering platform). The constitutional properties (anonymity CI-5/Q7 · forward-only correction · event immutability · replay safety · causal ordering/provenance · tenant isolation) are exactly what they were — PB-007 weakened none and now **continuously enforces** their executable guards on every merge through the blocking tier. The increase is in confidence in the engineering evidence that verifies them: **the engineering qualification process rejected a measurement that failed evidence validation** (F-7D-2 — guided by the documented procedure, decided by escaped-mutant identity comparison, ruled by the ARB) and established a reproducible baseline under a validated execution model. For an election platform, the demonstrated willingness to reject an attractive-but-untrustworthy number (Test Strength 96% → validated 65%) is itself trust evidence.

### 6.4 EP-02 Completion Review — did we implement the approved plan?
**Yes, with two recorded findings and zero silent deviations.** Slice conformance against the FROZEN IDD + ARB-amended order: **7A** Deptrac report→classify→fail-mode rollout as designed (fail flip landed in 7D as scheduled) · **7B** minting fitness test RED-first, Messaging-owned/PB-007-hosted · **7C** baseline measured, two-class findings scheme (§2c-i/ii/iii), two-step invocation decision · **7D** stable interface delivered + verified, tiers per §2d · **7E** CI as pure invocations of the stable interface. **Deviations (both recorded, both ruled):** F-7D-1 Composer process timeout — Engineering Platform Repair, approved; F-7D-2 — **the mutation measurement was validated and REJECTED before it entered the Evidence stage** (six-level lifecycle unchanged; validation is part of establishing Evidence, not a new stage); validated 1-thread baseline established (MSI 50 · MCC 77 · Covered-Code MSI 65), interface repaired to the validated model (`threads=1`), fast+valid path (TEST_TOKEN per-thread DBs) recorded as future work. **Scope discipline:** no mutation thresholds (A-3) · no platform expansion (all ideas recorded-not-adopted in the retrospective inbox) · existing workflows untouched · frozen `.claude` platform untouched. **Open evidence item (honest):** "CI workflow green" (§5) — requires the first real PR/dispatch run after push; everything else in §5 is delivered.

**Lifecycle:** implementation COMPLETE · qualification PASSED (6.1–6.3) · EP-02 PASSED (6.4).

### 6.5 ARB closure ruling (2026-07-10) — PB-007 APPROVED FOR CLOSURE
> **PB-007: APPROVED FOR CLOSURE.** The implementation conforms to the approved design. Architecture Qualification, DDD Qualification, Trustworthiness Qualification, and the EP-02 Completion Review are all supported by executed evidence. The only remaining operational evidence is the first real CI execution after push, which is correctly recorded as a post-implementation verification rather than a prerequisite for closure. EPIC-001 may be closed following the planned one-time synchronization of program status and the retrospective, after which implementation should proceed with EPIC-002.

Post-closure governance (ARB, recorded): the engineering platform is treated as a production subsystem — bug fixes · compatibility fixes · retrospective promotions; no architectural evolution unless implementation demonstrates a real deficiency (implementation-first default, `.claude/MEMORY.md`).

## ARB ruling (2026-07-10) — IDD approved and FROZEN
> PB-007 IDD is approved. The document correctly models the Greenfield Merge Gate as an architectural qualification rather than a tooling exercise. The separation of Architecture, Behaviour, Engineering, Improvement, and Reporting gates is clear. The stable `composer merge-gate` interface is an appropriate architectural abstraction, and the recorded platform compatibility repair accurately distinguishes a latent PHPUnit 11 coverage incompatibility from behaviour introduced by Infection. Two editorial refinements applied before freeze: (1) platform compatibility repairs separated from pre-existing repository debt (§2c-i/§2c-ii); (2) exclusion-integrity statement added (§2c-ii). No further architectural changes required. PB-007 may proceed through its remaining implementation slices under the approved governance model.
