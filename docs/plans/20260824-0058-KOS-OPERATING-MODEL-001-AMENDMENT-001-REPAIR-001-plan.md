# `REPAIR-001` — EP-01 implementation plan · `AST-019` conformance repair

**Work item:** `KOS-OPERATING-MODEL-001-AMENDMENT-001` · **Subject:** `AST-019` (`ActivateCommissionedFreshSession`) · **Grant:** `G-REPAIR-001` (`AUTHORIZED`)
**Plan type:** EP-01 implementation plan · **Status: DRAFT — AWAITING EXPLICIT HUMAN APPROVAL OF THE PLAN**
**Date:** 2026-08-24 · **Author (implementation lane):** `claude-code-session:84e5c1f7-bd51-42fd-83fc-1b58c7d90dd3`
*(self-declared, not attestable — `INV-ATTR-1/2`, `G-2`)*
**Placement:** `docs/plans/` per `ES-004.2` (`YYYYMMDD-HHMM-<subject>-plan.md`); `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0) governs *review/governance* artifacts, not plans — the plan root is the established one, matching the subject's own prior plan `docs/plans/20260822-2309-activate-commissioned-fresh-session-plan.md`.

> ⛔ **NO CODE HAS BEEN CHANGED BY THIS PLAN.** No test was added, edited or run. No workflow record was written. `AST-019` remains **IMPLEMENTED · NOT VERIFIED · NOT ADOPTED · NOT AUTHORIZED**.

---

## 1 · Objective

Bring `AST-019` into conformance with its own documented contract on the findings the independent verification recorded — `F-1` (blocking), `F-3`, `F-4`, `F-2` — and close the structural test-coverage gap `O-1` that let a 25/25-green suite miss `F-1`.

The objective is **conformance repair, not redesign**. The capability's domain model, validation order, refusal vocabulary, mechanism discipline and human-facing rendering are correct and stay as they are (verification §§7–11, all PASS). What is broken is that the write path never receives the fold it reads.

**Out of the objective:** making `AST-019` verified, adopted, or authorized. This slice produces an implementation and evidence; it produces no verdict.

## 2 · Governing authorization

| Gate | State | Evidence |
|---|---|---|
| Human `CONTINUATION` | ✅ `seq 5`, `recordedBy: human` | *"Accept the FAIL verdict. Continue the work item for REPAIR-001…"* |
| Slice grant | ✅ `G-REPAIR-001` · `AUTHORIZED` · `registeredBy: governance` | fold `grants[0]` |
| Candidate declaration | ✅ 2026-08-24 | `…-REPAIR-001-IMPLEMENTER-CANDIDATE-DECLARATION-prompt.md` (Phase 1) |
| Appointment (`AST-018 appoint`) | ✅ | `…-REPAIR-001-IMPLEMENTER-APPOINTMENT-84e5c1f7.md`; `seq 6` `REGISTER` `recordedBy: governance` |
| Human `START` (`G-3`) | ✅ `seq 8`, `recordedBy: human` | PO/ARB act 2026-08-24 |
| Lane | ✅ `implementation` · `ACTIVE` · `mutationOwner = 84e5c1f7…` · attribution `MATCH` · `authorized_to_act: true` | `workflow-state.php fold`; `session-bootstrap.php` |
| **Approved EP-01 plan** | ⬜ **THIS DOCUMENT — the remaining gate before any code moves** | — |

Verification history preserved and unrewritten: `seq 1–5` intact, `seq 4` `STOP` still carries **VERDICT: FAIL** verbatim, verification lane `84c0f6f6…` = `HANDED_OFF`.

## 3 · Current verified defect evidence

Established independently by this lane from the source (line numbers re-checked at `HEAD`), and corroborated by the verification report's hermetic evidence (`HERMETIC-AST019-C`).

| # | Site | What the source actually does |
|---|---|---|
| `F-1` | `activate-commissioned-fresh-session.php:378–385` vs `:401–402` | The ready-return of `analyze()` is `['ready','identity','role','commissionSource','independentOf','humanAct']` — **no `fold` key**. `writeActivation()` opens `$fold = $a['fold'];` then `$owner = $fold['mutationOwner'] ?? null`. `$owner` is therefore **always `null`**, and it feeds both `REGISTER.predecessor` and `HANDOFF.from`. |
| `F-3` | `:193–204` (`incompleteSequence()`) vs `:397` docblock vs `:620` | The docblock promises `array{ok:bool,…}`; the caller tests `$written['ok']`; the failure path returns the `refusal()` shape `{ready,exit,payload}` with **no `ok`**. Behaviour is correct only because `null` is falsy. |
| `F-4` | `:200` and `:626` | `transitionWritten` is **hard-coded `true`** in `incompleteSequence()` — including when `REGISTER` itself failed and `written` is `[]` — and hard-coded `true` again on the `ACTIVATED` payload. |
| `F-2` | consequence of `F-1` + `F-3` | Two `Undefined array key` warnings (`"fold"` at `:401` on **every** activation, `"ok"` at `:620` on every `INCOMPLETE_SEQUENCE`). Under `display_errors=On` PHP CLI writes them to **STDOUT ahead of the payload**, so `--json` output is unparseable. Confirmed environment-dependent: this host runs `display_errors=Off` (`/etc/php.ini`), which is why the suite is green and the warnings are invisible here. |
| `O-1` | `AST-015 workflow-state.php:129–158` | Structural cause of the blind spot: `COMPLETE` clears `$owner`, `HANDOFF` clears `$owner`; **`FAIL`/`CANCEL` do not**. Every GO fixture uses an empty item, a first lane, or a `COMPLETED` predecessor — all `owner = null`. Every other non-null-owner shape is refused earlier by `GO-13`/`GO-21`. The write path is exercised **only** where the bug is invisible. |

**Why `F-1` is blocking, in one line:** `AST-015`'s `HANDOFF` guard (`workflow-state.php:216–223`) refuses `from=null` while an owner exists, so the sequence half-writes — leaving an **unrecoverable orphan `REGISTER`** in an append-only store *after* `check` reported `READY`.

**The arming state (reachable, never yet armed in 23 production records):** `mutationOwner ≠ null` **and** no `ACTIVE` lane **and** no `CREATED`/`HANDED_OFF` lane **and** item not `STOPPED` **and** ≥1 `COMPLETED` lane implying a progression — i.e. a lane that `START`ed and then `FAIL`ed/`CANCEL`led while holding ownership.

## 4 · Scope

**Touchable — exactly two files:**
- `.claude/scripts/activate-commissioned-fresh-session.php` (`AST-019`)
- `tests/Unit/Platform/WorkflowEngine/ActivateCommissionedFreshSessionContractTest.php` (its contract test)

**Plus the authorized process artifacts:** this plan, `.claude/CONTEXT.md`, `.claude/sessions/2026-08-24.md`, a developer guide, and the completion report.

**Findings in scope:** `F-1` · `F-3` · `F-4` · `F-2` · `O-1`.

## 5 · Explicit exclusions

- **`F-5` (`KOS_MECHANISM_PATH`)** — HELD for a separate Architecture decision. It will **not** be fixed, mitigated, documented away, or given scope here. *(Note: `T-5`/`T-6` below **use** that seam as a test vehicle exactly as the already-adopted `GO-20` does. Using an existing seam is not repairing it, and nothing in this plan changes `mechanismPath()` or its docblock.)*
- No change to `AST-015` / `AST-016` / `AST-017` / `AST-018`, or `operating-model.php`.
- No change to adopted `L1` (`docs/knowledgeos/governance/2026-08-22-KOS-OPERATING-MODEL-001-final-operating-model.md`), `L2` (`operating-model.php`), `L3` (`OperatingModelContractTest.php`) — byte-integrity is an **acceptance criterion**.
- No reopening of the parent `KOS-OPERATING-MODEL-001`.
- No `ASD-001` remedy — it stays a recorded sequencing defect.
- No `REVIEW_INDEPENDENCE_POLICY §22` change. No `O-4` / `O-6` / `Q-1` / `Q-2` work.
- No reopening or rewriting of the verification history.
- No second workflow engine, no duplicated fold logic, no second owner source, no new capability (`ES-005.4`).
- No adoption, no authorization, no self-verification.

## 6 · Design / conformance approach

Four small, independent corrections. Each is the minimum that makes the code do what its own docblock already says.

**D-1 · `F-1`: return the fold the write path already reads.** Add `'fold' => $fold` to the ready-return of `analyze()`. The fold is **already fetched** at `V2` through the qualified mechanism (`foldOf()` → `AST-015 fold`); passing it forward is a data-flow repair.
*Rejected alternative:* re-folding inside `writeActivation()` — that would be **a second fold**, violating the mechanism discipline the capability's own docblock asserts and the verification confirmed as PASS.
*Deliberate property:* the owner is the one observed at analysis time. If ownership changes between analysis and write, `AST-015`'s `HANDOFF` guard refuses and the capability reports `INCOMPLETE_SEQUENCE` — fail-closed, unchanged. No locking, no re-read, no TOCTOU repair is in scope.

**D-2 · `F-3`: one result shape on both paths.** Add `'ok' => false` to the array returned by `incompleteSequence()` and correct its docblock. `incompleteSequence()` is called from `writeActivation()` only (4 sites), so the change is local. The caller at `:620` keeps testing `$written['ok']` — now a defined key on both paths.
*Rejected alternative:* changing the caller to `!empty($written['ok'])` — it hides the shape mismatch instead of fixing it, and leaves the docblock false.

**D-3 · `F-4`: derive `transitionWritten` from the writes.** `incompleteSequence()` → `'transitionWritten' => $written !== []`. The success payload at `:626` → derived from `$written['written'] !== []` rather than the literal `true`. Both sites stop asserting a fact they do not check.

**D-4 · `F-2`: no warnings, therefore clean STDOUT.** `F-2` has no separate fix: its two warnings are `F-1`'s and `F-3`'s. It closes **only when both D-1 and D-2 land**, and is proven by tests that run the capability under `display_errors=1` and parse STDOUT (`T-5`, `T-6`). No output-buffering, no `@`-suppression, no `ini_set` will be added — suppressing a warning would hide the defect rather than remove it.

**Not changed:** validation order `V1→V2→V3→V5a→V5b→commission→V8` · every refusal reason and its wording · the human rendering · the exit-code contract (`0`/`64`/`65`) · `CONTINUATION` structural impossibility · the human-`START` boundary · `check` read-purity · `mechanismPath()`.

**Expected diff size:** ~4 lines of behaviour in `AST-019`, plus docblock corrections; the substantive work is the tests.

## 7 · RED tests

Added to `ActivateCommissionedFreshSessionContractTest.php` as **`GO-26 … GO-30`**, marked `REPAIR-001`. **`GO-01 … GO-25` are not modified, not weakened, not deleted, not renumbered.** All fixtures stay hermetic (temp `--dir`, built **through `AST-015`** via the existing `initEmpty()`/`recordLane()` helpers, never hand-written JSON), and no test touches `.claude/runtime/workflow/`.

**The fixture that arms `F-1`** (built with existing helpers only):

```
initEmpty(WI)                                   roles: governance,architecture,implementation,verification
recordLane(WI,'IMPL','implementation','COMPLETED')   → owner cleared by COMPLETE
recordLane(WI,'OWNER','architecture','FAILED')       → REGISTER→HANDOFF(from=null, valid: owner is null)
                                                        →START (owner:=OWNER) →FAIL (owner STAYS OWNER)
⇒ fold: mutationOwner=OWNER · workItemState=OPEN · states {IMPL: COMPLETED, OWNER: FAILED}
⇒ AST-018 next-actor: NEXT_ACTOR_REQUIRED | role=verification   (last COMPLETED = implementation)
⇒ AST-019 activate --requested-role=verification, fresh identity
```

| Test | Finding | Asserts | RED on today's code |
|---|---|---|---|
| **`GO-26`** | `O-1` + `F-1` | exit `0`, `result: ACTIVATED`; fold `sessions[FRESH].predecessor === 'OWNER'`; the recorded `HANDOFF.from === 'OWNER'`; `check` on the same fixture predicts the same outcome | **YES** — today: exit `65`, `INCOMPLETE_SEQUENCE`, `written: ["REGISTER"]`, orphan `predecessor: null` |
| **`GO-27`** | `F-3` | every failure path returns a result carrying `ok === false` (asserted through observable behaviour: exit `65` + `INCOMPLETE_SEQUENCE` payload rendered, never `ACTIVATED`), for injected `REGISTER` / `HANDOFF` / `START` failure | YES for the shape assertion |
| **`GO-28`** | `F-4` | injected `REGISTER` failure → `transitionWritten === false` **and** `written === []`; injected `HANDOFF` failure → `transitionWritten === true` with `written === ["REGISTER"]`; success → `true` | **YES** on the first case (today `true` with `written: []`) |
| **`GO-29`** | `F-2` (success) | under `php -d display_errors=1 -d error_reporting=-1`: STDOUT parses as JSON, contains no `PHP Warning`/`Notice`/`Deprecated`, `result: ACTIVATED`; STDERR carries no PHP diagnostic | **YES** — today the `"fold"` warning precedes the payload on STDOUT |
| **`GO-30`** | `F-2` (partial write) | same, on an injected-failure path: STDOUT parses as JSON with `result: INCOMPLETE_SEQUENCE` | **YES** — today the `"ok"` warning contaminates it |

**Failure injection (`GO-27`/`GO-28`/`GO-30`).** A temp-dir PHP **proxy stub** is passed via the existing `KOS_MECHANISM_PATH` seam — the same seam `GO-20` already uses. It forwards every invocation to the real `AST-015` unchanged, except one chosen `append` type, which it refuses with a non-zero exit. It performs no writes of its own and knows no store path beyond the forwarded `--dir`. This mirrors the verifier's own "controlled mechanism" method (verification §9).

**Honest limitation, stated in advance:** the verifier's **fourth** injected case — all three transitions written but the post-write fold disagreeing — needs a stub that doctors the *second* `fold` response. If that proves to need more machinery than the finding is worth, it will be recorded as **covered by inspection only**, explicitly, in the completion report. **It will not be silently dropped, and the plan will not be quietly re-scoped.**

**RED discipline:** every one of `GO-26`…`GO-30` is run and observed to **fail against unmodified `AST-019`** before a single line of the capability changes. Their failure output is captured verbatim as evidence.

## 8 · GREEN implementation changes

| Task | Finding | File · site | Change | Expected behaviour | Test | Acceptance |
|---|---|---|---|---|---|---|
| **T-1** | `F-1` | `activate-commissioned-fresh-session.php:378–385` | add `'fold' => $fold,` to the ready-return | `writeActivation()` receives the authoritative fold; `$owner` is the real `mutationOwner` | `GO-26` | `REGISTER.predecessor === owner` **and** `HANDOFF.from === owner`; `GO-26` green; no warning at `:401` |
| **T-2** | `F-3` | `:193–204` + `:397` docblock | add `'ok' => false`; correct both docblocks | failure and success paths share one machine-readable shape | `GO-27` | `$written['ok']` defined on both paths; no warning at `:620`; `GO-27` green |
| **T-3** | `F-4` | `:200` and `:626` | `'transitionWritten' => $written !== []` / derived from `$written['written']` | the field states what was actually persisted | `GO-28` | nothing written → `false`; ≥1 written → `true`; `GO-28` green |
| **T-4** | `F-2` | (no separate edit) | closes as a consequence of T-1 + T-2 | STDOUT is valid JSON under `display_errors=On` | `GO-29`, `GO-30` | both green; zero PHP diagnostics on STDOUT on **both** paths |
| **T-5** | `O-1` | contract test | add `GO-26`…`GO-30` + the proxy-stub helper and an ini-flag variant of the `activate()` harness (new optional parameter; **existing call sites unchanged**) | the write path is exercised with a live non-null owner | themselves | `GO-01`…`GO-25` still green and textually unchanged |
| **T-6** | DoD | `developer_guide/workflow_engine/` | short guide: the ownership-provenance defect class and how the fixture arms it | — | — | guide committed with the slice |

Order: **T-5 (RED) → T-1 → T-2 → T-3 → T-4 (observe) → regression → integrity → T-6**.

## 9 · Failure / partial-write verification

After GREEN, re-run the verifier's §9 matrix through the proxy stub and record the table in the completion report:

| Injected failure | `result` | `written` | `transitionWritten` | `ok` |
|---|---|---|---|---|
| `REGISTER` | `INCOMPLETE_SEQUENCE` | `[]` | **`false`** *(today: `true` — the `F-4` repair)* | `false` |
| `HANDOFF` | `INCOMPLETE_SEQUENCE` | `["REGISTER"]` | `true` | `false` |
| `START` | `INCOMPLETE_SEQUENCE` | `["REGISTER","HANDOFF"]` | `true` | `false` |
| post-write fold mismatch | `INCOMPLETE_SEQUENCE` | all three | `true` | `false` | *(or recorded as inspection-only, per §7)* |
| control (healthy) | `ACTIVATED` | all three | `true` | `true` |

Every case must still name what failed, who acts next, and that the record is append-only. Exit codes unchanged: `activate` `65`, `check` `0`.

## 10 · Regression suite

```
vendor/bin/phpunit tests/Unit/Platform/WorkflowEngine --testdox
```

Covers `ActivateCommissionedFreshSessionContractTest` (`GO-01`…`GO-30`), `WorkflowStateRecordContractTest` (`AST-015`), `SessionAssignmentResolverContractTest` (`AST-016`), `SessionBootstrapContractTest` (`AST-017`), `NextActorOrchestrationContractTest` (`AST-018`), `OperatingModelContractTest` (**L3**), `Pbdigit6569ReplayTest`.

**Acceptance:** `GO-01`…`GO-25` green **and** byte-unchanged; `GO-26`…`GO-30` green; every neighbouring contract suite green with **no new failures or skips**. The pre-existing `SessionAssignmentResolverContractTest` deprecation noted by the verifier is unrelated and stays as it is. Baseline is captured **before** RED so "no new failures" is measured, not asserted.

No database is touched — these are plain `PHPUnit\Framework\TestCase` classes. No `migrate:fresh`, no seeding, no `--seed`.

## 11 · Byte-integrity checks

Run before commit; each must print nothing / report `0`:

```
git diff --stat -- .claude/scripts/workflow-state.php \
                   .claude/scripts/session-resolve.php \
                   .claude/scripts/session-bootstrap.php \
                   .claude/scripts/next-actor-orchestration.php \
                   .claude/scripts/operating-model.php \
                   tests/Unit/Platform/WorkflowEngine/OperatingModelContractTest.php \
                   docs/knowledgeos/governance/2026-08-22-KOS-OPERATING-MODEL-001-final-operating-model.md
```

plus a `sha256sum` comparison of each against `git show HEAD:<path>`, and:

- `git status --porcelain` shows **only** the two touchable files and the authorized process artifacts;
- `REVIEW_INDEPENDENCE_POLICY §22` textually unchanged;
- `.claude/runtime/workflow/` unchanged by the repair — the only writes to this work item's record are the governed lane transitions, and **none** is authored by the repair;
- `grep -c "'CONTINUATION'"` in `AST-019` remains `0` (`GO-21`'s structural assertion).

## 12 · Acceptance criteria

1. `GO-26`…`GO-30` **fail first** against unmodified `AST-019`, with captured output.
2. After GREEN: `REGISTER.predecessor === mutationOwner` and `HANDOFF.from === mutationOwner` for a reachable live-owner case; `check` and `activate` agree on it.
3. `writeActivation()` returns `ok` on both paths; no `Undefined array key` warning remains on any path.
4. `transitionWritten` is derived, never hard-coded, at both sites.
5. STDOUT is valid JSON under `display_errors=On` for **success and partial-write** paths.
6. `GO-01`…`GO-25` green and unmodified; the whole `WorkflowEngine` suite green with no new failures.
7. `AST-015/016/017/018`, `L1`, `L2`, `L3` byte-unchanged; parent not reopened.
8. `F-5` untouched — not fixed, not mitigated, not documented away.
9. Only the two authorized files changed, plus authorized process artifacts.
10. The completion report says **IMPLEMENTED**, never `VERIFIED`/`ADOPTED`/`AUTHORIZED` (`R-34`/`EP-02`).

## 13 · Verification handoff

On completion: `STOP` with evidence, a Session Completion Report, and no verdict. **Re-verification must be performed by a process that is none of:** `84c0f6f6…` (first verifier) · `1899d8bf…` (producer) · `5928b9f9…`/`e40f3fd0…` (repair scoper) · `930c65a4…` (selection recorder) · **`84e5c1f7…` (this implementer)**. The re-verifier should re-run `GO-26` against the *pre-repair* source to confirm the RED was real.

## 14 · Risks

| Risk | Mitigation |
|---|---|
| `GO-26`'s fixture does not actually arm the defect (a guard refuses earlier) | The arming state is derived from `AST-015`'s reducer and `AST-018`'s `determineNextActor` at source, and matches the verifier's independently-built `HERMETIC-AST019-C`. If the fixture refuses early, **the plan is wrong and I stop and revise it**, rather than adjusting the capability to fit the test. |
| The proxy stub becomes a second mechanism | It forwards verbatim and never writes; it lives only in a temp dir; it is the vehicle `GO-20` already established. If it starts needing workflow logic of its own, **stop**. |
| Passing the analysis-time fold masks a concurrent ownership change | Accepted and documented: `AST-015`'s `HANDOFF` guard refuses, and the capability reports `INCOMPLETE_SEQUENCE`. Fail-closed. Out of scope to do more. |
| Fixing `F-4` breaks a hidden assertion elsewhere | The full `WorkflowEngine` suite runs; any `GO-01`…`GO-25` change is an automatic **STOP**. |
| Scope creep into `F-5`, `ASD-001` or `§22` | Enumerated in §5; verified by the §11 integrity checks before commit. |
| The repair is read as verification | §16 status model; the completion report never self-accepts. |

## 15 · Rollback / stop conditions

**Rollback** is ordinary: both touched files are plain source under git — `git checkout -- <two paths>` restores `HEAD`. Nothing in this slice writes to an append-only governed store, so no rollback is ever *needed* there.

**STOP immediately and return to the human — do not improvise — if:**
1. the repair appears to require a change to `AST-015/016/017/018` or `L1/L2/L3`;
2. any `GO-01`…`GO-25` test would have to be weakened, skipped or deleted to go green;
3. `F-1` turns out **not** to be repairable by returning the fold (i.e. the design assumption in D-1 is wrong);
4. `F-5` becomes load-bearing for any fix;
5. a new defect outside `G-REPAIR-001` is discovered — it becomes a **recorded observation**, never an unauthorized fix;
6. the plan and the implementation diverge for any reason (`EP-02`: present a revised plan and wait).

## 16 · Completion status model

| State | After this slice | Who decides |
|---|---|---|
| `AST-019` **IMPLEMENTED** | ✅ yes (repaired) | implementation |
| `AST-019` **VERIFIED** | ⛔ **NO** — the recorded result is still **FAIL**; only a fresh independent verifier can change it | verification |
| `AST-019` **ADOPTED** | ⛔ **NO** | PO/ARB (human) |
| `AST-019` **AUTHORIZED FOR FUTURE USE** | ⛔ **NO** | PO/ARB (human) |
| `REPAIR-001` | **AUTHORIZED TO IMPLEMENT** (`G-REPAIR-001`) — *authorization is not adoption, and not a verdict* | — |

`ASD-001` remains a preserved recorded sequencing defect. `F-5`, `O-4`, `Q-1`, `Q-2` and the `§22` placeholder remain open, untouched by this slice.

---

**Traceability:** `G-REPAIR-001` (fold, `AUTHORIZED`) · `seq 5` human `CONTINUATION` · `seq 6–8` `REGISTER`/`HANDOFF`/human `START` for `84e5c1f7…` · `…-REPAIR-001-AUTHORIZATION.md` · `…-REPAIR-001-IMPLEMENTER-SELECTION-DECISION.md` · `…-REPAIR-001-IMPLEMENTER-APPOINTMENT-84e5c1f7.md` · `…-AMENDMENT-001-INDEPENDENT-VERIFICATION.md` (FAIL; `F-1`…`F-5`, `O-1`…`O-3`) · `…-APPOINTMENT-SEQUENCING-DEFECT-001.md` (`ASD-001`, preserved) · `…-OBSERVATION-O-5-process-identity-continuity.md` · `docs/plans/20260822-2309-activate-commissioned-fresh-session-plan.md` (the subject's original plan) · `EP-01`/`EP-02` · `R-34` · `G-2`/`G-3` · `INV-ATTR-1/2` · `Inv B`/`Inv C`/`Inv D`/`Inv E` · `R8` · `ES-004.2`/`ES-004.3` · `ES-005.4`
