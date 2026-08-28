# `KOS-OPERATING-MODEL-001-AMENDMENT-001` — **INDEPENDENT VERIFICATION of `AST-019`**

**Work item:** `KOS-OPERATING-MODEL-001-AMENDMENT-001` · **Role:** `verification` · **Subject:** `AST-019` / `ActivateCommissionedFreshSession` (`.claude/scripts/activate-commissioned-fresh-session.php`, 636 lines, commit `98575324`)
**Domain concept verified:** `BindRuntimeToRequestedResponsibility`
**Verifier:** `claude-code-session:84c0f6f6-795e-4c89-a382-733f2c7b7caf` — self-declared, **not attestable** (`INV-ATTR-1/2`, `G-2`)
**Date:** 2026-08-23 · **Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

---

## 1 · Executive verdict

# ⛔ FAIL

**`AST-019` carries a reachable correctness defect on its production write path.** On a reachable input it records a **factually wrong `predecessor`** into an **append-only** governed record, is then refused the `HANDOFF` by `AST-015`, and leaves a **permanent orphan assignment** — *after its own read-only `check` reported `READY`*.

The root cause is single and precisely located: **`analyze()` never returns a `'fold'` key, and `writeActivation()` reads `$a['fold']` (line 401).** The mutation owner is therefore forced to `null` on every activation.

**GO-01 … GO-25 all pass (25 tests / 270 assertions), and they cannot detect this** — no contract test reaches the write path with a non-null `mutationOwner` (§12, `O-1`). This is the commission's own warning made concrete: *reported numbers are not evidence.*

Everything else verified **PASS**: sole-writer discipline, the `CONTINUATION` prohibition, the human `START` boundary, adoption separation, identity handling, all eight refusal paths, partial-write honesty, determinism, provider independence, read purity, human-facing behaviour, and the byte-integrity of the adopted layers and of `AST-015/016/017/018`.

> **This verdict is narrow.** The defect is one missing array key. Everything built on top of it is sound. A corrected `analyze()` return plus a contract test that exercises the write path with a live owner should be sufficient, and re-verification can be narrowly scoped.

**States, kept distinct (§38):** `AST-019` is **IMPLEMENTED · VERIFICATION COMPLETED WITH RESULT FAIL · NOT ADOPTED · NOT AUTHORIZED.** Adoption and authorization remain PO/ARB decisions and are **not** reached by this report.

---

## 2 · Scope

**In scope:** independent re-execution of the contract suite and the full `WorkflowEngine` regression · source inspection against `AST-019`'s own documented claims · the production write path in hermetic stores · the `AST-015` sole-writer boundary · `AST-017`/`AST-018` boundaries · the human `START` boundary · the `CONTINUATION` prohibition · failure and partial-write behaviour · identity and eligibility · determinism · provider independence · read purity · human-facing output · byte-integrity of adopted `L1`/`L2`/`L3` · independent assessment of `ASD-001`.

**Out of scope / not done:** no modification of `AST-019` or its tests · no modification of adopted `L1`/`L2`/`L3` · no modification of `AST-015/016/017/018` · `KOS-OPERATING-MODEL-001` not reopened · **`activate` never invoked against any real work item** · no adoption · no authorization · no self-acceptance (`R-34`/`EP-02`) · findings **not** repaired during verification (§30).

---

## 3 · Identity and independence

| Fact | Value |
|---|---|
| Runtime identity | `84c0f6f6-795e-4c89-a382-733f2c7b7caf`, from `CLAUDE_CODE_SESSION_ID` — never from a prompt or CLI argument |
| Producer bar (`F-3`, `R-34`/`EP-02`) | producer is `1899d8bf-2688-4bf3-9787-b4114ddaeec8` — **this is not that process** |
| The 16 recorded bars | **PASS on all 16** |
| Corroboration | `git grep 84c0f6f6` · `grep -rl` over `.claude/runtime/`, `.claude/sessions/`, `docs/` · `git log --all --grep` → **no hits anywhere in the estate** |
| Disclosed ordering deviation | ~120 lines of the subject read before declaring; disclosed unprompted; **PO/ARB disposition `D-i`** (accept, non-disqualifying) |
| Lane authorization | `AST-017`: `RESOLVED · attribution=MATCH · role=verification · workflow_state=ACTIVE · authorized_to_act=true · mutation_owner.is_this_lane=true · recorded_human_start_act=true` |
| Self-acceptance | **none** — this report recommends; it decides nothing |

---

## 4 · Evidence reviewed

`AST-019` source in full (636 lines) · `ActivateCommissionedFreshSessionContractTest.php` (773 lines, GO-01..GO-25) · `AST-015` `workflow-state.php` (`foldSessions`, `assertTransitionAllowed`, command surface) · `AST-017` `session-bootstrap.php` · `AST-018` `next-actor-orchestration.php` (`determineNextActor`, `appointReviewer`, `PROGRESSION`) · adopted `L1` §22 and its diff history · all 23 production workflow records · `ASD-001` defect record · the appointment, producer-identity, and activation registrations.

**Nothing in this report is taken on the implementation's word.** Every claim below is re-derived from execution or from source.

---

## 5 · Test execution — independently re-run

| Run | Command | Result |
|---|---|---|
| Contract suite | `./vendor/bin/phpunit tests/Unit/Platform/WorkflowEngine/ActivateCommissionedFreshSessionContractTest.php --testdox` | **OK — 25 tests, 270 assertions**, 0 failures, 0 errors, 0 skips, 0 warnings. GO-01…GO-25 each individually green. `00:11.273` |
| Full `WorkflowEngine` regression | `./vendor/bin/phpunit tests/Unit/Platform/WorkflowEngine/` | **OK — 147 tests, 1577 assertions**, 0 failures. 1 PHPUnit deprecation. `00:52.364` |
| Per-file isolation | each of the 7 files run alone | 25+29+43+1+17+21+11 = **147** ✓ consistent |

**Environment:** PHP `8.5.8` (NTS, gcc x86_64) · PHPUnit `11.5.6` · config `phpunit.xml` · `display_errors=Off`, `error_reporting=22527`, `log_errors=On`.

**The deprecation is not `AST-019`'s.** Isolated to `SessionAssignmentResolverContractTest` (`AST-016`); `AST-019`'s own file reports zero issues. Pre-existing and unrelated.

**The reported count is confirmed accurate** — but see `O-1`: accuracy of the count says nothing about coverage of the write path.

---

## 6 · Production write-path verification — the commissioned centre

`AST-019` had never written a transition in production (`F-4` of the parent review). Four hermetic stores were built through `AST-015` only. **No real work item was touched.**

### 6.1 · First binding — `HUMAN_BUSINESS_ORDER` (owner `null`) → ✅ correct

`HERMETIC-AST019-A`, fresh item, identity `aaaaaaaa-…`, role `governance`: exit `0`, `ACTIVATED`, three transitions. Authoritative read-back: lane exists · role `governance` · `ACTIVE`.

### 6.2 · Subsequent binding — `NEXT_ACTOR_REQUIRED` (COMPLETED predecessor, owner `null`) → ✅ correct

`HERMETIC-AST019-D` (implementation lane COMPLETED, `mutationOwner: null`), identity `dddddddd-…`, role `verification`: exit `0`, `ACTIVATED`.

| Property verified from the fold | Result |
|---|---|
| session exists · role · state | ✓ `verification` · **`ACTIVE`** |
| `mutationOwner` is this session | ✓ |
| `predecessor` | `null` — **correct here**, the prior owner was genuinely `null` |
| `humanAct` preserved verbatim | ✓ `"I want a Verification session for D."` |
| `recordedBy` on `START` | ✓ **`human`** |
| duplicate registration | ✓ none — exactly one `REGISTER` |
| unexpected transitions | ✓ none — exactly `REGISTER`, `HANDOFF`, `START` |

### 6.3 · Binding with a live `mutationOwner` → ⛔ **`F-1`, the blocking defect**

`HERMETIC-AST019-C` was driven into the one state that arms the defect: lane `AAA` implementation **COMPLETED**, lane `BBB` verification **STARTed then FAILed**. `FAIL` does **not** clear `$owner` in `foldSessions`, so:

```
mutationOwner: BBB      workItemState: OPEN      states: {AAA: COMPLETED, BBB: FAILED}
AST-018 next-actor  →  NEXT_ACTOR_REQUIRED | role: verification
```

`AST-019 check` (read-only) → **`READY`**. Then `AST-019 activate`:

```
EXIT=65
result:        INCOMPLETE_SEQUENCE
written:       ["REGISTER"]
whatIsMissing: the handoff could not be recorded — refused: bootstrap handoff
               (from=null) is only valid while no owner exists
STDERR:        PHP Warning: Undefined array key "fold" ... on line 401
               PHP Warning: Undefined array key "ok"   ... on line 620

record after:  seq 9  REGISTER  cccccccc-…  predecessor: None   ← orphan, wrong, permanent
```

**`check` promised `READY`; `activate` half-wrote and stopped.** The record is append-only — `R1` (no `CLAIM_OWNERSHIP`), `R8` (role immutable), no rollback edge — so the orphan assignment cannot be removed, only worked around by Governance.

**`AST-015` prevented worse.** Its `HANDOFF` guard (`from === null` valid *only* while no owner exists) caught the forced-`null`. Sole-writer discipline did its job: the defect surfaced as a refusal instead of silent ownership corruption. That is a credit to `AST-015`, not to `AST-019`.

### 6.4 · Reachability, measured rather than asserted

Arming condition: `mutationOwner ≠ null` **and** no `ACTIVE` lane **and** no `CREATED`/`HANDED_OFF` lane **and** item not `STOPPED` — i.e. a session that started and then **`FAIL`ed or was `CANCEL`led while holding ownership**, with ≥1 `COMPLETED` lane to imply a progression. (`ACTIVE` → `GO-13` refuses; `CREATED`/`HANDED_OFF` → `GO-13` refuses; `STOPPED` → `GO-21` refuses. Those guards are correct and they cover every *other* non-null-owner case.)

I replayed **all 23 production records at every transition prefix**:

```
work items whose history ever entered the trap state: 0
```

`FAIL`/`CANCEL` transitions **do** exist in production (`KOS-OQ-001`, `KOS-CONTRACT-NEUTRALITY-001`), and 6 of 23 items currently hold a non-null owner — but in every case an `ACTIVE` lane exists, so `GO-13` refuses first. **The defect is reachable and proven triggerable, and has never yet materialized.** It is latent, not theoretical.

---

## 7 · `AST-015` / `AST-017` / `AST-018` boundary verification

### 7.1 · `AST-015` is the sole writer — ✅ PASS

| Check | Result |
|---|---|
| `file_put_contents` · `rename` · `unlink` · `mkdir` · `fopen` · `file_get_contents` | **absent** |
| `fwrite` | only `STDERR` (usage, line 70) and `STDOUT` (render, line 518) |
| Store-path knowledge | **none** — no `runtime/workflow`, no `recordPath`, no `$path` |
| Subprocess targets | exactly three: `workflow-state.php`, `session-bootstrap.php`, `next-actor-orchestration.php`, all via `proc_open` with an **array** argv (no shell) |
| `shell_exec` / `exec` / `system` / `passthru` | **absent** |
| Second fold / state machine / authority engine | **none** — every workflow fact comes from a subprocess; `foldSessions` is not reimplemented |
| Decoy tolerance | `GO-18` poisons the raw record with top-level `mutationOwner`/`workItemState`/`sessions` decoys; the fold-derived truth wins |

### 7.2 · `AST-017` read-only — ✅ PASS

Consumed via `freshDeclaration()` for one fact (the verdict). Byte-unchanged: `git diff` = **0** vs both commit `98575324` and `HEAD`. A bootstrap run on a hermetic fixture left the store byte-identical.

### 7.3 · `AST-018` boundary — ✅ PASS, and not a hidden replacement

`AST-019` invokes **only** `next-actor` (read-only). It never invokes `appoint`; the strings `'appoint'`/`"appoint"` are absent from the source. **The responsibility boundary itself holds, not merely the outcome:** `AST-018 appoint` *decides who* (validating a candidate against `eligibilityOf` and recording the eligibility conclusion into `executionContext`), whereas `AST-019` *binds a runtime that is already commissioned* and takes the role from `AST-018`'s report, refusing `MISMATCH` when the request disagrees. Different inputs, different authority, different failure modes. Confirmed empirically: role mismatch → `MISMATCH`, exit `65`, nothing written.

### 7.4 · Byte-integrity of neighbours and adopted layers — ✅ PASS

| Asset | vs `98575324` | vs `HEAD` |
|---|---|---|
| `workflow-state.php` (`AST-015`) · `session-resolve.php` (`AST-016`) · `session-bootstrap.php` (`AST-017`) · `next-actor-orchestration.php` (`AST-018`) | 0 | 0 |
| `operating-model.php` (**L2**) · `OperatingModelContractTest.php` (**L3**) | 0 | 0 |
| `…final-operating-model.md` (**L1**) | 12 lines | **0** |

**The L1 delta is a status annotation only** — commits `9502168a` / `e89b3b41` recording the PO/ARB adoption and authorization acts per `ES-004.3`. The **40 normative sections are unchanged**, and **§22 `REVIEW_INDEPENDENCE_POLICY` is textually unchanged** (still the deliberate placeholder at line 207). Not made by this verification. **The adopted layers are untouched; `KOS-OPERATING-MODEL-001` was not reopened.**

---

## 8 · Human `START` boundary — ✅ PASS

`AST-019` cannot fabricate a human act. `V8` refuses without `--human-act` (`HUMAN_DECISION_REQUIRED`, nothing written — confirmed empirically). The `START` transition carries `recordedBy: 'human'` and `humanAct` = the verbatim string, preserved byte-for-byte in the record (§6.2). `ADOPTION_IS_HUMAN_DECISION` refuses when `AST-018` reports the remaining step is a human `DECIDE`. No path constructs a human act from an assistant message.

**`CONTINUATION` prohibition — ✅ PASS, and structural.** The token `CONTINUATION` appears **zero times** in `AST-019`. The only transition types it can construct are `REGISTER` (413), `HANDOFF` (426), `START` (439). `Inv E` is therefore guaranteed by construction, not merely by `GO-21`. A `STOPPED` item is refused honestly: *"this capability never writes a continuation itself, so nothing was written"* — naming the human as next actor with options `Continue` / `Leave it stopped`. Verified: the stopped record received **no** transition of any kind.

**Adoption boundary — ✅ PASS.** No `ADOPTED`/`AUTHORIZED` claim anywhere. Every payload carries *"Activation is not adoption, and no authority is claimed; adoption and authorization remain human decisions."*

**Identity — ✅ PASS.** From `CLAUDE_CODE_SESSION_ID` only. `--session=`, `--identity=`, `--claude-code-session-id=` are each rejected `exit 64` by the generic unknown-option path. Missing identity → `NO_RUNTIME_IDENTITY`, nothing written. The bound identity is cleanly attributable afterwards — `AST-017` resolves the lane to exactly `aaaaaaaa-1111-2222-3333-444444444444`, with no punctuation or surrounding prose absorbed (the `executionContext` places whitespace, never punctuation, after the id).

---

## 9 · Failure and partial-write verification — ✅ PASS (honest in all four cases)

Driven with a controlled mechanism that fails a chosen append. `AST-019` **never claimed full activation when only part was recorded**:

| Injected failure | `result` | `written` | Names what failed / who acts next |
|---|---|---|---|
| `REGISTER` (1st) | `INCOMPLETE_SEQUENCE` | `[]` | ✓ / `governance` |
| `HANDOFF` (2nd) | `INCOMPLETE_SEQUENCE` | `["REGISTER"]` | ✓ / `governance` |
| `START` (3rd) | `INCOMPLETE_SEQUENCE` | `["REGISTER","HANDOFF"]` | ✓ / `governance` |
| all 3 written, post-write fold not `ACTIVE` | `INCOMPLETE_SEQUENCE` | `["REGISTER","HANDOFF","START"]` | ✓ / `governance` |
| control (healthy) | `ACTIVATED` | — | — |

Every case explains that the record is append-only and cannot be undone. **Nothing is hidden.** The outcome is verified from the authoritative fold rather than assumed — the fourth row proves it: three successful appends still yield `INCOMPLETE_SEQUENCE`, never `ACTIVATED`, when the fold disagrees.

**Honest error reporting — ✅ PASS.** All eight refusals produced the correct reason with the record **byte-unchanged**, `exit 65` on `activate` and `exit 0` on `check`:

`WORK_ITEM_STOPPED` · `CONFLICTING_ASSIGNMENT` (both `LANE_ACTIVE` and `ACTIVATION_PENDING`) · `NOT_ELIGIBLE` (duplicate binding, and human-declared `--exclude` bar) · `WORK_ITEM_UNKNOWN` · `HUMAN_DECISION_REQUIRED` · `MISMATCH` · `MECHANISM_UNAVAILABLE` (via an unreachable mechanism path).

**The two refusals observed in practice are correct for the right reasons.** `CONFLICTING_ASSIGNMENT` fires because `AST-018` reports `LANE_ACTIVE`/`ACTIVATION_PENDING` — activation of an assigned actor is a human act, so a fresh session may not bind over it. `NOT POSSIBLE` on a stopped item fires because `Inv E` makes `STOPPED` sticky and the capability structurally cannot emit the only exit edge. Neither is a coincidence of ordering.

---

## 10 · Determinism, provider independence, read purity — ✅ PASS

| Property | Method | Result |
|---|---|---|
| Determinism | `check` run 5× identically, SHA-256 compared | **one unique non-empty hash** |
| Provider independence | `check` under Claude-shaped vs DeepSeek-shaped env (`ANTHROPIC_MODEL`/`BASE_URL`/`AUTH_TOKEN`) | **byte-identical**, 395 bytes each |
| Read purity | store fingerprinted before/after 7 `check` runs | **byte-identical — `check` wrote nothing** |

No time-dependent content, no randomness, no environment-dependent semantics. *An earlier determinism run of mine produced empty output because an unquoted `--human-act` split into stray arguments and hit the usage path; that measurement was invalid and was discarded and redone. The results above are the valid ones.*

---

## 11 · Human-facing behaviour — ✅ PASS

Default output is business language with **no mechanics**. A leak scan for `REGISTER|HANDOFF|START|mutationOwner|predecessor|<uuid>` over both the success and refusal renderings returned **nothing**.

*Activation* answers what happened / why it was allowed / what happens next, plus the non-adoption caveat. *Refusal* answers why it cannot proceed / who must act next / what the human can choose. Mechanics appear only under `--show-mechanics`.

---

## 12 · Findings

### `F-1` · ⛔ BLOCKING · correctness · the write path forces `mutationOwner` to `null`

- **Observation.** `analyze()` returns `['ready','identity','role','commissionSource','independentOf','humanAct']` (lines 377–385) — **no `'fold'` key**. `writeActivation()` opens with `$fold = $a['fold'];` (line 401) and derives `$owner = $fold['mutationOwner'] ?? null` (402). `$owner` is therefore **always `null`**, and it feeds both `REGISTER.predecessor` and `HANDOFF.from`.
- **Evidence.** Source lines 377–385 vs 401–402. `HERMETIC-AST019-C`: `check` → `READY`; `activate` → exit `65`, `INCOMPLETE_SEQUENCE`, `written: ["REGISTER"]`, `AST-015` refusal *"bootstrap handoff (from=null) is only valid while no owner exists"*; orphan `seq 9 REGISTER … predecessor: None` left permanently. `PHP Warning: Undefined array key "fold" … line 401` on **every** activation, including the two successful ones.
- **Impact.** Whenever a non-null `mutationOwner` coexists with a `NEXT_ACTOR_REQUIRED` commission: (i) `REGISTER` records a **wrong `predecessor`**, breaking the `Inv B` predecessor chain in substance while satisfying it formally; (ii) the `HANDOFF` is refused; (iii) an **unrecoverable orphan assignment** is left in an append-only store; (iv) **`check` and `activate` disagree** — the read-only sibling exists precisely to predict the write, and it mispredicts. Reachable via `FAIL`/`CANCEL`; never yet armed in 23 production records (§6.4).
- **Recommendation.** Return the fold from `analyze()` (e.g. add `'fold' => $fold`) and add a contract test that reaches the write path with a **live non-null `mutationOwner`**, asserting `REGISTER.predecessor === owner` and `HANDOFF.from === owner`. **Not repaired here** (§30).
- **Blocks verification: YES.**

### `F-2` · non-blocking · same root cause · PHP warnings break the `--json` contract

- **Observation.** Two warnings on the write path: `Undefined array key "fold"` (401, every activation) and `Undefined array key "ok"` (620, every `INCOMPLETE_SEQUENCE`).
- **Evidence.** Under this host's `display_errors=Off` they go to STDERR and STDOUT stays valid JSON. Under `php -d display_errors=1` the warning is emitted to **STDOUT ahead of the payload** and parsing fails: `INVALID JSON -> Expecting value: line 2 column 1`.
- **Impact.** In any environment with `display_errors=On` — an ordinary dev/CI default — a **successful** activation is unparseable to a programmatic consumer. `AST-019`'s own `run()` idiom (`json_decode($raw, true)`, `null` ⇒ failure) is exactly such a consumer, so a composing capability would read success as failure while all three transitions had in fact been written.
- **Recommendation.** Fix `F-1` (removes both), and consider asserting a clean STDERR in the contract suite.

### `F-3` · non-blocking · type contract · `writeActivation()` return shape is inconsistent

- **Observation.** Docblock promises `array{ok:bool,written:array,payload?:array,exit?:int}`, and the caller tests `$written['ok']` (620). The failure path returns `incompleteSequence()`, which has the `refusal()` shape `{ready,exit,payload}` — **no `ok`**.
- **Evidence.** Lines 189–204 vs 396–458 vs 620. The `Undefined array key "ok"` warning is its symptom; behaviour is correct only because `null` is falsy.
- **Impact.** Latent. A future refactor that makes `$written['ok']` strict, or supplies a default `true`, would invert the branch and report a partial write as a full activation.
- **Recommendation.** Return `['ok' => false, 'payload' => …, 'exit' => …]` from the failure path, or have the caller test `!empty($written['ok'])`.

### `F-4` · non-blocking · honesty · `transitionWritten: true` when nothing was written

- **Observation.** `incompleteSequence()` hard-codes `'transitionWritten' => true`. When `REGISTER` itself fails, nothing was written.
- **Evidence.** Injected `REGISTER` failure → `transitionWritten: True` with `written: []`.
- **Impact.** A governance-record field is factually wrong. It errs in the **safe direction** (over-reporting mutation prompts escalation rather than concealing it), and `written: []` carries the truth alongside it.
- **Recommendation.** Derive it: `'transitionWritten' => $written !== []`.

### `F-5` · non-blocking · architecture · `KOS_MECHANISM_PATH` redirects the "sole writer"

- **Observation.** `mechanismPath()` returns `getenv('KOS_MECHANISM_PATH') ?: MECHANISM` (line 91). Both reads and writes go wherever it points. The `AST-017` and `AST-018` paths are hard constants — the seam is **asymmetric**.
- **Evidence.** Pointing it at a foreign script: the script was invoked for `fold` and its **fabricated** facts were consumed as authoritative (`AST-019` refused `NOT_ELIGIBLE` on the spy's invented lane).
- **Impact.** The docblock's unconditional *"AST-015 is the sole interpreter and sole writer"* is not literally true. This is **not** a privilege escalation — anyone who can set the environment can already execute code — and it is the seam `GO-20` needs. But no `GO` test bounds it, and a reader of the contract would not know it exists.
- **Recommendation.** State the override in the docblock as a bounded test seam, and/or gate it behind an explicit test-mode flag.

---

## 13 · Risks

| Risk | Assessment |
|---|---|
| **`F-1` fires in production before it is fixed** | Low probability today (never armed in 23 records), **high consequence** — a permanent orphan assignment in an append-only store, requiring Governance workaround. Probability rises the moment `FAIL`/`CANCEL` is used to retire an owning lane, which this estate already does. |
| **`check` is trusted as a preflight** | `F-1` makes `check → READY` unsound in exactly the case where the write will fail. Any operator or capability that gates on `check` inherits the mispredict. |
| **The 25/25 green result is read as coverage** | `O-1`: the suite is accurate and passes, and still cannot see `F-1`. The gap is structural, not a matter of test count. |
| **`F-2` in a `display_errors=On` environment** | A composing capability reads a successful activation as failure and may retry, hitting `NOT_ELIGIBLE` (duplicate) — a confusing but fail-closed outcome. |
| **`F-5` masks the sole-writer guarantee** | Low in practice; matters if `AST-019` is ever run under an environment an operator does not control. |

---

## 14 · Explicit non-findings

Verified and **not** defective: sole-writer discipline (no filesystem primitives, no store-path knowledge, no second fold, array-argv subprocesses only) · `CONTINUATION` structurally impossible · human `START` never fabricated, `recordedBy: human`, `humanAct` verbatim · adoption never claimed and never implied · identity from the environment only, never a CLI argument, no punctuation absorption, missing identity fails closed · all eight refusal paths correct with the record byte-unchanged and correct exit codes · partial-write reporting honest in all four injected failures, outcome verified from the fold rather than assumed · determinism · provider independence · read purity of `check` · human-facing output free of mechanics · `AST-015/016/017/018`, `L2`, `L3` byte-unchanged, `L1` normative text unchanged, parent not reopened · the reported test numbers accurate · the `SessionAssignmentResolverContractTest` deprecation unrelated to `AST-019`.

### Observations (not findings — no defect claimed)

- **`O-1` · why 25/25 green missed `F-1`.** No contract test reaches the write path with a non-null `mutationOwner`: `GO-01/03/05/15/18` use an empty or first-lane item (`owner = null`); `GO-04/06/23/24/25` use a **COMPLETED** predecessor, and `COMPLETE` clears `$owner`; every genuinely non-null-owner scenario is refused earlier by `GO-13`. The write path is exercised **only** where `owner` is legitimately `null` — which is precisely where the bug is invisible.
- **`O-2` · what `GO-15`/`GO-16`/`GO-17` actually prove.** `GO-15`'s sole-writer argument is largely source-string absence (`file_put_contents`, `rename(`, `/runtime/workflow`); an `fopen`/`fwrite` pair would evade it. I confirmed the property holds **in fact** independently. `GO-16`/`GO-17` assert `AST-017`/`AST-018` are byte-unchanged **vs `HEAD`** — a working-tree cleanliness check that passes trivially once any modification is committed; it does not establish that `AST-019` never writes to them (my source inspection does).
- **`O-3` · `ASD-001` corroborates `F-1` from the opposite direction** (§15).

---

## 15 · `ASD-001` — independent assessment (disclosed, assessed, not repaired)

**Disclosure.** This lane's activation was **hand-composed** through `AST-015 append` (`REGISTER` + bootstrap `HANDOFF` + human `START`, seq 1–3) rather than performed through `AST-018 appoint`, recorded as `ASD-001`.

**Does it invalidate this lane, or this verdict? No.**

| Property | Independent finding |
|---|---|
| Formal validity | All three transitions satisfied every `AST-015` precondition (`Inv B` predecessor present · `Inv D` token + tokenRef · `Inv C` bootstrap `from=null` valid while no owner existed · `Inv F`/`G-3` conjunction). Re-read from the raw record. |
| Human authority | **Genuine.** `seq 3` carries `recordedBy: human` and the PO/ARB's verbatim order. Not manufactured. |
| Attributability | `AST-017` → `RESOLVED · MATCH · verification · ACTIVE · authorized_to_act=true`. |
| Substantive independence | **Re-established by me, not inherited** — four searches, no hits. |
| What was actually lost | The **provenance** of the eligibility check, not the eligibility. `AST-018 appoint` would have recorded the eligibility conclusion into `executionContext` as a governed act; a hand-composed append reproduces the transitions but not that record. |

**The `NOT_ELIGIBLE` refusal the human hit is correct, and I confirm it is correct for the right reason.** `appointReviewer()` guards `if (isset($fold['sessions'][$candidate]))` — a **pre-appointment** guard, distinct from the substantive `eligibilityOf()` check. In `AST-018`'s intended sequence `appoint` is *what creates the lane*, so a pre-existing lane soundly implies prior participation. The out-of-band `REGISTER` falsified that premise. It is a re-appointment guard firing correctly, **not** a finding that `84c0f6f6` lacks independence. Conflating the two would be the real error.

**`O-3` — the part that matters for `AST-019`, and it is not favourable.** `ASD-001`'s own root-cause analysis credits `AST-018 appoint` with deriving *"`predecessor`/`from` from the fold's current owner rather than from an author's judgement."* **That is exactly the property `F-1` shows `AST-019` fails to deliver.** `ASD-001` and `F-1` are the same defect class from opposite directions: a Governance process hand-derived the ownership facts, and `AST-019` attempts to derive them and silently doesn't. The estate has now produced two independent instances of the appointment/activation path being wrong about ownership provenance. That is corroboration, not coincidence.

**Two further conclusions.** (i) Using `AST-019` for this lane would have produced an **identical** record — the owner was `null` and this is the first lane — so the bypass **concealed nothing** about `F-1`, and neither path would have exposed it. (ii) `AST-019` could not have *repaired* `ASD-001` either: its `V5a` guard refuses an identity already holding a lane (`GO-07`), the same shape as `AST-018`'s guard. Both mechanisms correctly refuse to re-bind an existing lane.

**`ASD-001` remedy is not mine to choose** and is left to the PO/ARB. It is disclosed here as commissioned, and it does not change the verdict — which rests on `F-1`, a property of the subject, established from the subject.

---

## 16 · State separation — the four states are never collapsed

| State | `AST-019` | Basis |
|---|---|---|
| **IMPLEMENTED** | ✅ yes | commit `98575324`; 636 lines; GO-01..GO-25 present and green |
| **VERIFIED** | ⛔ **NO — verification completed, result FAIL** | `F-1`, a reachable correctness defect on the production write path (§6.3, §12) |
| **ADOPTED** | ⛔ **NOT ADOPTED** | a PO/ARB decision; **not reached by this report** |
| **AUTHORIZED** | ⛔ **NOT AUTHORIZED** | a separate PO/ARB decision, never implied by adoption; **not reached by this report** |

**Unchanged by this verification:** `KOS-OPERATING-MODEL-001` remains **ADOPTED · AUTHORIZED** (L1 + L2 + L3), untouched and not reopened. `AST-015/016/017/018` unmodified. `AST-019`'s source and tests unmodified — **findings were not repaired during verification** (§30).

**This report decides nothing.** It supplies evidence and a verdict; it does not accept its own work (`R-34`/`EP-02`).

---

## 17 · Next actor

```yaml
session_completion:
  status: verification COMPLETE — result FAIL
  completed_work: >
    GO-01..GO-25 re-executed (25/270, green) · full WorkflowEngine regression
    (147/1577, green) · full source inspection · production write path exercised
    in 4 hermetic stores incl. the first-ever live-owner case · boundaries ·
    human START · CONTINUATION prohibition · 8 refusal paths · 4 injected
    partial-write failures · determinism · provider independence · read purity ·
    human-facing output · adopted-layer byte-integrity · ASD-001 assessed
  evidence: this report, sections 5-15
  open_items: >
    F-1 (blocking) · F-2 · F-3 · F-4 · F-5 · O-1 test-coverage gap ·
    ASD-001 remedy (PO/ARB) · REVIEW_INDEPENDENCE_POLICY §22 / F-5 of the parent

next_actor:
  recommended_role: governance
  reason: >
    A FAIL verdict returns the work item to Governance. The correct sequence is
    Finding -> Architecture Decision -> RED -> GREEN -> re-verification, not
    Finding -> Implementation -> Certification. The verifier must not fix what
    it verified (R-34/EP-02), and a fresh producer/verifier split applies to
    the repair.
  blocking_condition: >
    PO/ARB disposition of the FAIL verdict and of the ASD-001 remedy; then an
    authorized repair slice for F-1 (+F-2/F-3/F-4 same root) with a contract
    test covering the live-owner write path; then re-verification.

authorization:
  current_session_can_continue: false   # capability, NOT authorization
  authorized_to_act: false              # scope discharged; verification complete
  requires_human_decision: true
```

**Traceability:** `AST-019` commit `98575324` · contract test GO-01..GO-25 · appointment registration `…-AMENDMENT-001-VERIFIER-APPOINTMENT-registration.md` · candidate declaration + `D-i` disposition `…-AMENDMENT-001-VERIFIER-CANDIDATE-DECLARATION-84c0f6f6.md` · activation `…-AMENDMENT-001-VERIFIER-ACTIVATION-84c0f6f6.md` · `ASD-001` `…-AMENDMENT-001-APPOINTMENT-SEQUENCING-DEFECT-001.md` · producer identity `…-AMENDMENT-001-PRODUCER-IDENTITY-registration.md` (`F-3`) · parent adoption review `F-2/F-4/F-5` · `AST-015` `Inv A-H`, `R1`/`R8` · `G-2`/`G-3` · `INV-ATTR-1/2` · `R-34`/`EP-02` · `ES-004.2/.3` · `ES-005.4` · `ES-006.1`
