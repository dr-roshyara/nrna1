# `KOS-SESSION-BOOTSTRAP-001-CORRECTION-001` — Architecture correction · evidence summary

**Produced by:** the appointed Architecture correction actor — `claude-code-session:b51dba91-6fc4-4110-b2f2-ac76ad0f3808` *(identity resolved mechanically; lane REGISTERED → HANDOFF → human STARTED 2026-08-22; `authorized_to_act = true` at the moment of acting)*
**Act:** CORRECTION-001 architecture correction 2026-08-22 — V-1 / V-3 / V-5 · **authoring only, bounded by the AUTHORING commission; the author accepts, adopts and closes nothing.**

---

## 1 · Status — ⚠️ CORRECTED / READY FOR INDEPENDENT RE-VERIFICATION

> **V-1 ∧ V-3 ∧ V-5 are corrected and ready for independent technical re-verification.**
> **NOT** ACCEPTED · **NOT** ADOPTED · **NOT** CLOSED — the author reports, the author never self-verifies/self-accepts (producer bar, `EP-02`/`R-34`).

## 2 · Commission boundary honored

**Corrected:** `V-1` · `V-3` · `V-5` only. **NOT touched:** `V-2` · `V-4` · `V-6` · `V-7` beyond observation · V-8 re-open · AST-015 redesign · V-3 FULL remedy · `SESSION_START` wiring · automatic REGISTER/HANDOFF/START · handoff automation · `EKS-07` · migration · Phase 3/5.

**Files changed by this correction (complete list):**

| File | Change |
|---|---|
| `.claude/scripts/session-bootstrap.php` | **+11 / −3** — V-1 derivation fix + V-3 match-set tracking/emission |
| `tests/Unit/Platform/WorkflowEngine/SessionBootstrapContractTest.php` | **+83** — regressions S-18/S-19/S-20 (V-1/V-3/V-5) + docblock |
| `AGENTS.md` | V-5 consumer pointer → `activation_prerequisites` |
| `.claude/CLAUDE.md` | V-5 consumer pointer → `activation_prerequisites` |
| `developer_guide/knowledgeos/04_session_bootstrap_ast017.md` | V-5 consumer pointer → `activation_prerequisites` |
| `docs/knowledgeos/reviews/…-implementation-boundary-proposal.md` | V-5 canonical §5 schema row + §9 + §11 pointers → `activation_prerequisites` |
| this evidence + CORRECTION-001 session completion report + `.claude/CONTEXT.md` + session log | deliverables |

`.codex/README.md` — **verified**: references the resolver *script*, neither field name; nothing to update. Historical finding records (`-INDEPENDENT-VERIFICATION.md` · `-independent-verification-registration.md` · `-AUTHORING-commission-registration.md`) describe the defect **as found** and are left untouched (`ES-004.3` — history is never rewritten).

## 3 · V-1 — recorded_human_start_act is never inferred from "left CREATED"

**Defect (verification finding V-1, l.360):** `$recordedHumanStartAct = $selected['state'] !== 'CREATED'` claimed a human START for any lane that left `CREATED` — false for a terminal lane reaching `CANCELLED` from `CREATED` without a START.

**Smallest correction (`.claude/scripts/session-bootstrap.php`):**

```php
// BEFORE
$recordedHumanStartAct = $selected['state'] !== 'CREATED';
// AFTER — ACTIVE is the fold state AST-015 sets only on START/CONTINUATION
$recordedHumanStartAct = $selected['state'] === 'ACTIVE';
```

**Authority:** derives from the AST-015 `fold` state — an AST-015-exposed fact (`workflow-state.php` l.137–139: `START`/`CONTINUATION` → `ACTIVE`; l.156–157: `CANCEL` → `CANCELLED`). No second raw read; the single bounded V-3 read stays exactly one HANDOFF fact. G-3 is **not** weakened — `$activated` (`state === 'ACTIVE' && handoff && recordedHumanStartAct`, l.385) is unchanged in effect for ACTIVE lanes.

**RED → GREEN:** `test_s18_v1_cancelled_lane_without_start_never_claims_human_start` — REGISTER → CANCEL, no HANDOFF, no START. Before: `recorded_human_start_act=true` (RED, failed). After: `recorded_human_start_act=false`, `authorized_to_act=false` (GREEN). `G-3` untouched.

**Semantics note (documented contract, boundary §5):** `recorded_human_start_act` is defined `=ACTIVE⇒true`. Terminal lanes that passed through START (e.g. COMPLETED) now report `false` — per the documented contract, not a regression; the deterministic table routes terminal lanes to `po/arb` (`R8`) regardless, and no existing test asserts otherwise.

## 4 · V-3 — disambiguation lists ONLY the actual matches

**Defect (finding V-3, "62 listed where 2 matched"):** `meta.disambiguation_required` rendered `describeLanes($candidates)` — every discovered candidate, not the ones that actually matched the process selector.

**Smallest correction:** track the actual match set and render only it.

```php
$ambiguousMatches = []; // both AMBIGUOUS paths assign it:
// --session multi-record  → $ambiguousMatches = $candidates;   (every record carrying that lane id IS a match)
// process-label multi-match → $ambiguousMatches = $matching;    (ONLY the lanes referencing the label — V-3)
'disambiguation_required' => … . describeLanes($ambiguousMatches) // was describeLanes($candidates)
```

**RED → GREEN:** `test_s19_v3_disambiguation_lists_only_actual_matches` — **3 total candidates, exactly 2 matching** the label. Before: the message named the non-matching `V3LANE-C` (RED). After: it names exactly `V3LANE-A` + `V3LANE-B`; `V3LANE-C` absent (GREEN); `verdict=AMBIGUOUS`, `authorized_to_act=false`, no silent selection (P-3).

**Live (critical preservation):** `a8ce5a39 → AMBIGUOUS` **remains AMBIGUOUS** — verified live on the real record; `authorized_to_act=false`. The correction is diagnostic-only: it now lists exactly the 2 matching lanes (`S5` + `S6`), it does **not** choose one. **Do NOT "fix" the live ambiguity by picking a lane** — the V-3 requirement is truthful explanation, not resolution.

## 5 · V-5 — ONE canonical bootstrap field name

**Defect (finding V-5):** harness consumers referenced `bootstrapping_status`; the report emits `activation_prerequisites`. Mechanism and consumers disagreed.

**Canonical name — `activation_prerequisites`** (evidence-supported: the implementation, the contract tests S-2/S-2b/S-16, and the developer guide already use it; the block carries the **G-3 prerequisites**, not a status — `verdict`/`operable` are top-level report keys; choosing it changes **zero** working code vs. the largest consumer churn of the alternative). **One name; no duplicate alias.**

**Corrections applied (consumers aligned):** `AGENTS.md` · `.claude/CLAUDE.md` · `developer_guide/…/04_session_bootstrap_ast017.md` · canonical boundary doc §5 schema row (also corrected the row's field list — `status`/`operable` are top-level, not block fields) + §9 + §11. `.codex/README.md` verified: references neither name — nothing to update. Implementation: **no change needed** — it already emits `activation_prerequisites` and emits no `bootstrapping_status` key.

**Regression (contract pin):** `test_s20_v5_canonical_field_is_activation_prerequisites` — asserts `activation_prerequisites` exists with the promised semantics (`predecessor_handoff_present` · `predecessor_handoff_token_ref` · `recorded_human_start_act` · `successor_handoff_present` · `successor_lane` · `missing_for_start[]`) **and** that `bootstrapping_status` is **not** emitted. This test was GREEN from the start because the implementation already carried the canonical name — the V-5 defect lived in the consumers; the regression pins the contract going forward.

## 6 · Preservation conditions — all eight survive

| # | Property | Evidence |
|---|---|---|
| 1 | AST-015 remains the single authority for workflow interpretation | all state via `askMechanism(fold/identity/authorized)`; `workflow-state.php` untouched |
| 2 | AST-017 remains read-only | no write calls by source; S-9 fingerprint GREEN; live store fingerprint identical before/after |
| 3 | V-3 raw-read exception = exactly one HANDOFF fact | only `v3HandoffRead()` reads a record; S-16 GREEN (poisoned record → fold-derived truth) |
| 4 | No second fold / engine | no fold added; the two corrections are derivation + emission only |
| 5 | Provider independence | S-17 GREEN — Claude-shaped vs DeepSeek-shaped env, byte-identical JSON |
| 6 | Ambiguity fail-closed | `verdict=AMBIGUOUS` paths unchanged; live a8ce5a39 still AMBIGUOUS, `authorized_to_act=false` |
| 7 | Identity evidence-only, never authority | `identity` block untouched; attribution MATCH/MISMATCH/UNKNOWN reporting unchanged |
| 8 | Human START remains a human authority boundary | G-3 conjunction intact; `recorded_human_start_act` now *truthful* about it |

## 7 · Test results (all suites GREEN)

| Suite | Result |
|---|---|
| AST-017 contract (S-1…S-20, incl. S-2b) | ✅ **21/21 · 254 assertions** (was 18/18 · 210 — +3 regressions, +44 assertions) |
| AST-016 `SessionAssignmentResolverContractTest` | ✅ **17/17 · 170** — untouched (1 pre-existing PHPUnit doc-comment deprecation in `test_t5`) |
| AST-015 `WorkflowStateRecordContractTest` | ✅ **11/11 · 120** — untouched |
| `Pbdigit6569ReplayTest` (product) | ✅ **1/1** — unrelated |
| **Directory total** | ✅ **50/50 · 566 assertions** |

## 8 · Live verification (read-only, on the real store)

| Check | Result |
|---|---|
| `a8ce5a39` → `AMBIGUOUS`, `authorized_to_act=false`, disambiguation lists **exactly** S5 + S6 | ✅ preserved + truthful |
| current lane `b51dba91` → RESOLVED · ACTIVE · MATCH · `recorded_human_start_act=true` · `authorized_to_act=true` | ✅ V-1 fix does not regress a legitimate START |
| `.claude/runtime/workflow/` fingerprint before == after (`e95411b2…`) | ✅ **no workflow-state mutation** — no transition appended, no lane, no grant |
| `git status` on `.claude/runtime/workflow/` | ✅ gitignored; no tracked change |

## 9 · Read-purity by source

`grep` for `file_put_contents`/`mkdir`/`rename`/`unlink` in `session-bootstrap.php` → **none**. All `fwrite` sites are `STDOUT`/`STDERR` (report output). The only record read is `v3HandoffRead()` — the single bounded V-3 fact.

## 10 · Boundary re-statement (this correction is NOT)

⛔ an adoption decision · ⛔ an acceptance · ⛔ a closure · ⛔ a self-review (the author does not independently verify its own output) · ⛔ an AST-015/AST-016 change · ⛔ a V-3 FULL remedy · ⛔ `SESSION_START` wiring · ⛔ handoff automation · ⛔ `EKS-07` implementation · ⛔ a migration change · ⛔ V-2/V-4/V-6 work · ⛔ V-7 beyond observation.

## 11 · Next actor

**FRESH INDEPENDENT VERIFIER** — to re-verify V-1 / V-3 / V-5 against this evidence (per `CORRECTION-001` §10 sequence: correction → **STOP** → fresh independent verifier → technical re-verification → Governance adoption review → PO/ARB adoption decision). See the CORRECTION-001 Session Completion Report.

---

**Traceability:** AUTHORING commission registration (`…-CORRECTION-001-AUTHORING-commission-registration.md`, §4 findings · §5 preservation · §7 TDD · §9 status rule · §10 sequence · §12 STOP) · appointment ruling (`…-APPOINTMENT-b51dba91-registration.md`) · candidate eligibility report (`…-CANDIDATE-ELIGIBILITY-b51dba91-….md`) · independent verification artifact (verifier `d1612e03`, finding V-1/V-3/V-5) · implementation boundary proposal (`…-implementation-boundary-proposal.md`, §5 canonical schema) · `AST-017` · `AST-015` (fold l.137–139/156–157, `CANCEL` precondition l.266–268) · `G-3` · `Inv D` · `P-3` · `R8` · `R6`/`D-2` · `INV-ATTR-1/2` · `ES-004.3` · `EP-02`/`R-34` · placement: `scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`, exit 0)
