# KOS-SESSION-BOOTSTRAP-001 / AST-017 — Independent Architecture Verification

**Work item:** `KOS-SESSION-BOOTSTRAP-001` · **Asset:** `AST-017` (`.claude/scripts/session-bootstrap.php`) · **Component:** `CMP-004` (workflow_engine)
**Review type:** FALSIFICATION review — independent technical verification, **not** adoption
**Date:** 2026-08-22
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

> **⛔ This document adopts nothing.** It records what an independent process could and could not falsify. Adoption remains a human governance act. `Resolution is not activation` — and neither is verification.

---

## 1 · Reviewer identity and independence declaration

| Fact | Value |
|---|---|
| Verifying process (`CLAUDE_CODE_SESSION_ID`) | `d1612e03-7df3-4969-9e7e-881ccbed5641` |
| Producing process (per boundary proposal + registry notes) | `claude-code-session:8a525719` |
| Producer ≠ verifier | ✅ **satisfied** — different process |
| Author of AST-017 / the boundary proposal / the acceptance material | ❌ not this process |
| Registered verification lane for this work item | ⚠️ **none exists** — see below |

**Independence gate — result: PARTIAL, declared honestly rather than assumed.**

The gate asks the verifier to confirm its assigned verification lane from the authoritative workflow record. It cannot:

- `.claude/runtime/workflow/` holds **18** work-item records; **`KOS-SESSION-BOOTSTRAP-001.json` is not among them.**
- Running the asset under test against the live store with this process's own identity returns `UNRESOLVED` (verified — §15, L1).

**Consequence, stated plainly:** this process holds **no registered governed lane**, therefore **no workflow-derived authority**. This verification is performed **under direct human instruction** and is **advisory technical evidence only**. It creates no authority, transfers no ownership, and is not a governance act. The *substantive* independence requirement (the producer must not verify its own work) **is** satisfied; the *formal* one (a REGISTERed verification lane) **is not available to satisfy**, because the work item has no record. That absence is itself recorded as finding **V-8**.

---

## 2 · Sources consumed

| Source | Read |
|---|---|
| `.claude/scripts/session-bootstrap.php` (AST-017, 696 lines) | in full |
| `tests/Unit/Platform/WorkflowEngine/SessionBootstrapContractTest.php` (541 lines) | in full |
| `.claude/scripts/workflow-state.php` (AST-015) | delegation surface, `foldSessions()`, HANDOFF/START validation, grant storage |
| `.claude/scripts/session-resolve.php` (AST-016) | existence + non-modification |
| `docs/knowledgeos/reviews/2026-08-22-…-implementation-boundary-proposal.md` | in full |
| `docs/knowledgeos/reviews/2026-08-22-…-diagnostic-and-activation-registration.md` | consulted |
| `docs/knowledgeos/backlog/EKS-07-multi-process-coordination.md` | status + §12 addendum |
| `.claude/platform/registry.yaml` | AST-016 `finding_open`, AST-017 entry |
| `.claude/CLAUDE.md` · `AGENTS.md` · `.codex/README.md` · `.claude/settings.json` | harness wiring |
| `docs/knowledgeos/governance/2026-08-22-…-session-completion.md` · `developer_guide/knowledgeos/04_session_bootstrap_ast017.md` | existence + next-actor |

**The producer's completion report was treated as a claim to be falsified, never as proof.** Every green result below was independently re-executed.

---

## 3 · Verification methodology

For each invariant: state it → attempt to break it → execute → record evidence → verdict. Probes are labelled `F1…F14` (falsification) and `L1…L3` (live record). Hermetic fixtures were built **through AST-015**, never by hand-writing JSON, and live records were fingerprinted before and after.

---

## 4 · AST-015 single-source principle — **PASS**

**Invariant:** AST-017 derives no workflow fact independently.

**Falsification attempt:** source scan for a second fold, local state derivation, or raw-schema interpretation.

- Every workflow fact routes through `askMechanism()` → `proc_open(['php', $mechanism, …])` — `fold` (l.245), `authorized` (l.373), `identity` (l.379).
- **No** transition state machine, **no** fold loop, **no** local state derivation exists in the file.
- Mechanism unavailable ⇒ `UNRESOLVABLE`; it never falls back to reading records itself (verified F5, S-11).
- Only two `getenv()` calls in the entire asset: `KOS_MECHANISM_PATH`, `CLAUDE_CODE_SESSION_ID`.

**Verdict: PASS.** Delegation is structural, not conventional.

---

## 5 · V-3 bounded exception — **PASS (the decisive test)**

**Invariant:** raw JSON yields the handoff family and nothing else.

**First — the premise was checked, not assumed.** `foldSessions()` (AST-015 l.118–166) *does* compute `handoffsTo`, but the `fold` **command output** exposes only `workItem, workflow, roles, sessions, mutationOwner, workItemState, grants` — `handoffsTo` is **absent**; `identity` does not carry it either. **The V-3 premise is accurate as stated:** the fact exists internally and is exposed by no read command.

**Falsification attempt (F9) — deliberately harder than S-16.** Into a valid record I injected: `mutationOwner=EVIL-OWNER`, `workItemState=EVIL-STATE`, a fake `sessions` dict (`state=COMPLETED, role=evil-role, predecessor=EVIL-PRED, executionContext=claude-code-session:EVIL`), `roles=[evil]`, `workflow=evil-workflow`, a top-level `authorizationLinkage=G-EVIL`, and **a decoy `handoffs` array outside `transitions`** carrying `tokenRef=FAKE-REF`.

| Reported field | Value | Raw decoy | Outcome |
|---|---|---|---|
| `workflow_state` | `ACTIVE` | `COMPLETED` | decoy ignored |
| `role` | `implementation` | `evil-role` | decoy ignored |
| `predecessor` | `null` | `EVIL-PRED` | decoy ignored |
| `work_item_state` | `OPEN` | `EVIL-STATE` | decoy ignored |
| `mutation_owner` | `S-p` | `EVIL-OWNER` | decoy ignored |
| `attribution` | `MATCH` | ctx `…:EVIL` | decoy ignored |
| `predecessor_handoff_token_ref` | `REAL-REF` | `FAKE-REF` (top-level `handoffs`) | **decoy ignored — only `transitions` is read** |

**One result required disambiguation before it could be called a finding.** Poisoning `grants` *did* change `authorization_linkage`/`authorized_within_scope`. Investigation shows this is **not** an AST-017 breach: a clean record persists exactly `schema, workItem, workflow, roles, transitions, grants`. `sessions`/`mutationOwner`/`workItemState` are **derived caches that do not exist on disk** (hence ignoring them is correct), while **`grants` is authoritative storage** — AST-015's "Record 2 — Authority State, never merged" (l.297). Editing it is editing the authoritative record, and AST-015 — not AST-017 — read it. **No boundary breach.**

**Directional safety check:** `v3HandoffRead()` additionally requires `token` **and** `tokenRef` (Inv D / R3a), which AST-015's `handoffsTo` does not. The bootstrap's handoff set is therefore a **subset** of the fold's — it can only ever *over*-report "not ready", never under-report.

**Verdict: PASS.** The exception is exactly one thing, and it survived a harder poison than the one the producer wrote. **PO/ARB condition 2 satisfied.**

---

## 6 · Six-way separation — **PASS with one naming observation**

`identity` · `assignment` · `activation_prerequisites` · `grant` · `mutation_owner` · `gates` · `continuation` are separate first-class blocks; no single "agent identity" field substitutes for the six. Identity never reaches the gate as a grant — it enters only as `attributionOk` (a *necessary*, never *sufficient*, conjunct).

**Observation (F13) → finding V-6:** `operable` is computed as `verdict===RESOLVED && state==='ACTIVE'` — **lane-state only, not identity-gated**. An ACTIVE lane queried by an unattributed process reports `operable=true` with `attribution=MISMATCH` and `authorized_to_act=false`. No rule is violated (the gate is correct), but `operable` is the top-line field a human reads first, and its name invites collapsing eligibility with authorization.

---

## 7 · Identity safety (INV-ATTR-1 / INV-ATTR-2) — **PASS**

- Registered reviewer `REGISTERED1` vs running `INTRUDER` → `MISMATCH`, `authorized_to_act=false`, next actor `governance`, caveat states no impersonation is performed (S-5, re-run).
- No identity supplied → `UNKNOWN`, `authorized_to_act=false` (S-14, re-run).
- No code path selects a lane *because* a label was self-declared; `--session` selects by lane id and still computes attribution independently (verified live, L3: `--session=S5…` with a matching label yields `MATCH`; with `INTRUDER`, `MISMATCH`).
- Identity is never inferred from prompt text — the only inputs are `--process-label` and `CLAUDE_CODE_SESSION_ID`.

**Verdict: PASS.** Findings **V-4** and **V-7** below concern the *legibility* of the identity block, not its safety.

---

## 8 · Ambiguity / no-silent-selection — **PASS (behaviour) · finding on the message**

| Case | Result | Evidence |
|---|---|---|
| 0 matches | `UNRESOLVED`, names the missing `REGISTER`, names Governance | L1, S-10 |
| 1 match | resolves | L3, S-1 |
| >1 matches | `AMBIGUOUS`, **no lane selected** (`assignment.lane = null`) | **L2 (live)**, S-4 |
| lane id in >1 record with explicit `--session` | `AMBIGUOUS`, never a silent pick | code l.290–295 |

**Live `a8ce5a39` case — the plan's prediction was wrong and the resolver was right.** The record genuinely references `a8ce5a39` in **two** lanes:

```
KOS-AIP-GOV-STATE-DURABILITY-ADR :: S5-architecture-dv-correction-review   [architecture] = HANDED_OFF
KOS-AIP-GOV-STATE-DURABILITY-ADR :: S6-architecture-dv-correction-rv-repair [architecture] = COMPLETED
```

→ `AMBIGUOUS`, `operable=false`, nothing chosen. Explicit `--session=S5-architecture-dv-correction-review` → `RESOLVED`, `HANDED_OFF`, `authorized_to_act=false`, next actor `po/arb`, and the V-3 handoff fact read truthfully (`tokenRef = docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-DV-CORRECTION-SUMMARY.md`).

**This is a correct fail-closed result, not a defect.** The producer recorded the deviation in the registry notes rather than concealing it — that is the right disposition and this review endorses it. **No change requested here.**

**But the message is wrong → finding V-3.** `meta.disambiguation_required` renders `describeLanes($candidates)` — **all** candidates, not the **matching** set. Live: it listed **62** lanes when **2** matched. S-4 cannot catch this: its fixture contains exactly two lanes total, so "all" and "matching" coincide. The live record exposed a test blind spot.

---

## 9 · G-3 / START semantics — **PASS on gating · finding on one reported fact**

- CREATED, no handoff → both conjuncts listed; `po/arb` (S-2).
- CREATED **with** a recorded handoff → **only** the human START listed — the V-3 partial remedy working as intended (S-2b, independently re-run).
- No write, no `START` synthesis, no `humanAct` manufacture, no ownership mutation anywhere (§11).

**Finding V-1 (F14).** `recorded_human_start_act` is **inferred** as `state !== 'CREATED'` (l.360). AST-015 accepts `CANCEL` directly from `CREATED`. Probe:

```
REGISTER S-c  →  CANCEL S-c        (no START ever recorded)
→ workflow_state = 'CANCELLED'
→ recorded_human_start_act = true      ← FALSE STATEMENT ABOUT THE RECORD
```

The registry's own V-3 text asserts truthfulness "by entailment (CREATED implies no START)". That entailment holds in the **forward** direction only; the implementation relies on the **reverse** (`not CREATED ⇒ START recorded`), which this probe falsifies. It **fails safe** — `authorized_to_act` additionally requires `state==='ACTIVE'`, so a CANCELLED lane can never be authorized — but an asset whose purpose is making the record legible should not emit a false statement about it.

---

## 10 · Authorization / scope — **PASS**

`operable` and `authorized_to_act` are distinct and separately computed. With `--scope`: covered → `true`; uncovered → `false` + `human_decision_required` + named grant gap (S-3, S-6, re-run). Without `--scope`: `scope_coverage_evaluable=false`, `authorized_within_scope=null` — **UNKNOWN is reported as UNKNOWN and never promoted to YES** (`$scopeOk = !$scopeEvaluable || …` keeps the unknown out of the affirmative path while `authorized_to_act` still requires ACTIVE + MATCH). Scope comparison is string equality per R6/D-2, with the caveat carried in-band.

---

## 11 · Continuation (F1) — **PASS**

`current_session_can_continue = (state==='ACTIVE' && attribution==='MATCH')` is a **capability** statement. It is computed independently of `gates.authorized_to_act`, is never consumed as an authorization input, and is `false` on every non-RESOLVED verdict. No code path converts YES into implicit authority.

---

## 12 · Provider independence — **PASS (structural, not merely tested)**

- **Structural:** the asset reads **no** provider or model variable. Exhaustive scan for `ANTHROPIC*`, `MODEL`, `BASE_URL`, `API_KEY`, `$_ENV`, `$_SERVER` returns **only** `KOS_MECHANISM_PATH` and `CLAUDE_CODE_SESSION_ID`. Provider-independence is a property of the code, not a property of a passing test.
- **Hermetic (S-17):** re-run — byte-identical.
- **Live (F10) — beyond what the suite covers:** the same command against the **real** record store under a Claude-shaped env (`ANTHROPIC_MODEL=claude-opus-5`, `…BASE_URL=api.anthropic.com`, auth token, api key) vs a DeepSeek-shaped env (`deepseek-chat`, `api.deepseek.com`, …) → **byte-identical, 6829 bytes**.

**PO/ARB condition 3 satisfied.** The original defect — two providers diverging inside one harness — cannot be reproduced through this asset.

---

## 13 · Read purity — **PASS (the strongest single result)**

**Static:** every `fwrite` in the file targets `STDOUT`/`STDERR` (18 occurrences, all enumerated). **Zero** `file_put_contents`, `fopen`, `mkdir`, `rename`, `unlink`, `copy`, `touch`, `ftruncate`, `exec`, `system`, `shell_exec`, or backticks. Record access is exactly one `file_get_contents` inside `v3HandoffRead()`. No `append`/`grant`/`init` invocation reaches AST-015 — only `fold`/`identity`/`authorized`.

**Dynamic:** SHA-256 fingerprint of all **18** live records captured before verification and re-captured after executing every verdict path against the live store (RESOLVED, AMBIGUOUS, UNRESOLVED, UNRESOLVABLE, determinism pairs, both provider runs) → **byte-identical**. Zero stray files beside the records. `git status` on `.claude/runtime`, `.claude/scripts`, `.claude/platform`, `tests/` shows **no modification** caused by this verification.

---

## 14 · Determinism — **PASS**

Live record, JSON and human rendering, run twice each → **byte-identical** (F4). Ordering is deterministic by construction (`sort($workItems)` after `glob`). No timestamps, no randomness, no unordered traversal in the output path.

**One qualification → finding V-2.** Output is deterministic for a *fixed* argument set, but one advisory field is **not stable across argument sets that select the same lane** (§18).

---

## 15 · Live-record verification — **PASS**

| # | Probe | Result |
|---|---|---|
| L1 | default invocation, this process's real uuid | `UNRESOLVED`, 18 records scanned, 62 lanes listed, names the missing `REGISTER` + Governance |
| L2 | `--process-label=a8ce5a39` | **`AMBIGUOUS`** — 2 genuine matches, nothing selected |
| L3 | `--session=S5-architecture-dv-correction-review` | `RESOLVED`, `HANDED_OFF`, `MATCH`, `authorized_to_act=false`, handoff `tokenRef` read truthfully |
| — | active lane, terminal lane, handed-off lane | exercised across live + hermetic fixtures |

**Reality differed from the plan and the record was not touched to make the expected answer appear.**

---

## 16 · Harness integration — **PASS on wiring · finding on the contract**

- `.claude/CLAUDE.md` (l.773), `AGENTS.md` (l.83–92), `.codex/README.md` — each a **pointer**, explicitly `ON_DEMAND`, citing the boundary proposal as canonical rule text. No duplicated algorithm, no alternate script.
- **No automatic execution exists.** Repository-wide scan across `*.json`, `*.sh`, `*.toml`, `*.yaml`, `*.yml`: the **only** non-documentation reference to `session-bootstrap.php` is the registry `path:` entry. The `SessionStart` hook does **not** invoke it.
- `inject-context.sh` and `.claude/settings.json` are **unmodified** since HEAD. `.codex/` contains only `config.toml` + `README.md` — no hooks, no scripts.

**No scope violation.** The runtime moment remains `ON_DEMAND`.

**Finding V-5.** The pointers instruct a session to *"consume `bootstrapping_status`"*. **The report emits no such key.** Actual top-level keys: `verdict, operable, identity, assignment, activation_prerequisites, grant, mutation_owner, gates, continuation, meta`. The string appears nowhere in the implementation and is asserted by no test. A session following the harness instruction literally would look for a field that does not exist. (Origin is traceable: boundary §5 names the block `bootstrapping_status / activation_prerequisites`; the implementation shipped the second name and the pointers kept the first.)

---

## 17 · Registry state — **PASS**

`adoption: verify` · `governance_tier: 2` · `runtime_moments: [ON_DEMAND]` · **`verified:` block absent** (correctly reserved for adopted assets) · notes state *"ADOPTION IS NOT CLAIMED"* and record the live `AMBIGUOUS` deviation. The AST-016 `finding_open` V-3 annotation correctly describes the partial remedy and preserves the full remedy as `EKS-07 FOLLOW-UP`. **No enum was advanced to `adopted`; this review advances nothing.**

---

## 18 · EKS-07 boundary — **PASS**

`EKS-07` status line still reads **`FUTURE ARCHITECTURE EXPLORATION / OBSERVED PROBLEM` — not commissioned**. The §12 addendum is append-only, amends nothing above it, states the outer limit ("authorizes the bootstrap correction ONLY"), and §12.3 explicitly declares EKS-07 **not solved**. No second engine, identity system, role model, authority model, completion protocol, bounded context, or autonomous actor routing was introduced. Deferred work is recorded as FOLLOW-UP (§12.5), not implemented.

---

## 19 · Regression results — **PASS**

| Suite | Result |
|---|---|
| `SessionBootstrapContractTest` (S-1…S-17 + S-2b) | **18 passed, 210 assertions** |
| Full `WorkflowEngine` suite (incl. AST-015 `WorkflowStateRecordContractTest` R-1…R-8, AST-016) | **47 passed, 522 assertions** |
| AST-015 / AST-016 modified? | **No** — unmodified since HEAD |

Independently executed. The producer's reported numbers are confirmed exactly.

---

## 20 · Deviations from the approved plan

| # | Deviation | Disposition |
|---|---|---|
| 1 | Plan predicted `--process-label=a8ce5a39` → `RESOLVED S5`; live record yields `AMBIGUOUS` (2 lanes) | **Correct behaviour.** The plan's assumption about live state was wrong; the resolver failed closed and the producer recorded it rather than tuning the code to match the prediction. **Endorsed — do not "fix".** |
| 2 | Boundary §5 names the block `bootstrapping_status`; implementation ships `activation_prerequisites` | Finding **V-5** — reconcile the name in one direction |

No unauthorized scope expansion was found.

---

## 21 · Findings and remaining risks

**None of the following is a boundary breach, a fail-open path, or a governance weakening. All fail safe.**

| # | Sev | Finding | Evidence | Direction |
|---|---|---|---|---|
| **V-1** | **Medium** | `recorded_human_start_act` inferred from `state !== 'CREATED'` is unsound: a lane CANCELLED from CREATED reports `true` with no START ever recorded — a false statement about the record | F14, l.360 | fails safe |
| **V-2** | **Medium** | `recommended_next_actor` is not stable across argument sets selecting the same lane: a HANDED_OFF lane yields `verification` (successor role) without `--session`, `po/arb` **with** `--session` — the successor-role lookup scans the *filtered* `$candidates` (l.428–435) | F1 | fails safe |
| **V-3** | **Medium** | `meta.disambiguation_required` lists **all** candidates, not the **matching** set — 62 listed where 2 matched. S-4's fixture cannot distinguish the two | L2 | legibility |
| **V-4** | **Low-Med** | `identity.registered_process_label` is contaminated: live value **`'a8ce5a39.'`** — the token regex `[A-Za-z0-9._-]+` captures a trailing sentence period. It is also `referenced[0]`, an arbitrary first-token pick from free text carrying 5 tokens | F3 (live) | legibility |
| **V-5** | **Medium** | Harness pointers tell sessions to consume `bootstrapping_status`; **no such field is emitted**, and no test asserts one | F12 | contract gap |
| **V-6** | **Low** | `operable=true` with `attribution=MISMATCH` — `operable` is lane-state-only; the name invites collapsing eligibility with authorization | F13 | naming |
| **V-7** | **Obs** | A **bare** (non-`claude-code-session:`-prefixed) full uuid in `executionContext` is never extracted as a full-uuid label — only its hex runs are. The colon form works correctly | F2 / F2b | fails closed |
| **V-8** | **Gov** | `KOS-SESSION-BOOTSTRAP-001` has **no authoritative workflow record** — no lane, no REGISTER, no G-3 gates for the work item that built the lane resolver. This is why §1's independence gate could not be formally satisfied | §1 | governance |

**Suggested follow-ups (for Governance to schedule, not for this review to authorize):** V-1 and V-3 are the two worth correcting before adoption; V-5 is a one-line reconciliation; V-2/V-4/V-6 are legibility improvements; V-7 argues for a documented `executionContext` convention; V-8 is a governance question about whether meta-work on the workflow engine must itself be recorded in it.

---

## 22 · Final verdict

### ⛔ RETURN FOR CORRECTION

**Scoped narrowly, and with the architecture explicitly accepted.**

**What passed — everything structural, including all three decisive tests:**

- **PO/ARB condition 1** — adoption not claimed; registry at `verify`, `verified:` unfilled ✅
- **PO/ARB condition 2** — the V-3 exception is exactly one thing, proven against a poison harder than S-16 ✅
- **PO/ARB condition 3** — cross-provider conformance byte-identical, hermetic **and live**, and structurally guaranteed ✅
- AST-015 delegation · read purity (static + dynamic) · determinism · fail-closed mapping · identity safety · no-silent-selection · G-3 gating · registry state · ON_DEMAND wiring · EKS-07 boundary · 65/65 tests ✅

**Why not ACCEPT.** The asset exists to make the authoritative record **legible and truthful**. Two findings go to exactly that purpose: **V-1** emits a factually false statement about the record, and **V-5** means a session following the documented instruction looks for a field that does not exist. **V-3** renders an ambiguity message that misstates the ambiguity set by a factor of 31 on the live record. These are small, bounded, and individually cheap to fix — but they are correctness defects in the asset's core deliverable, not polish.

**The correction is bounded and requires no redesign.** The architecture, the delegation contract, the V-3 boundary, and the provider-independence property all stand as built and **must not be changed**. Recommended correction set: **V-1, V-3, V-5** (V-2, V-4, V-6 at Governance's discretion), each with a regression test — S-4 and S-2 in particular have demonstrated blind spots that only the live record exposed.

**Explicitly not requested:** any change to the `a8ce5a39` → `AMBIGUOUS` behaviour. That result is correct and this review endorses it.

**This verdict adopts nothing, accepts nothing, and closes nothing.** It is technical evidence for a human governance decision.

---

## Traceability

Work item `KOS-SESSION-BOOTSTRAP-001` · `AST-017` · `AST-015` · `AST-016` (V-3 `finding_open`) · `CMP-004` · AMENDMENT 2 · `INV-ATTR-1`/`INV-ATTR-2` · `G-3` · `Inv D`/`R3a` · `Inv E` · `R6`/`D-2` · `R8` · `C-1` · `F1` · `ES-005.4` · `EKS-07` (FUTURE ARCHITECTURE EXPLORATION — unchanged) · S-1…S-17 (+S-2b) · commits `e850439a` · `b05cca61` · `170e3052` · `a71a1a42` · `0f1f7983` · `6bb6eeec` · `a2be9e0a` · placement `scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)
