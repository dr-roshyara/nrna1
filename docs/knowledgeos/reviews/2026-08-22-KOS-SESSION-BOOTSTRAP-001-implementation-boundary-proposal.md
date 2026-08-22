# KOS-SESSION-BOOTSTRAP-001 — Session Bootstrap & Responsibility Resolution (AST-017) · Implementation Boundary Proposal

**Work item:** `KOS-SESSION-BOOTSTRAP-001` · **Component:** `CMP-004` (workflow_engine) · **governance_tier 2** · **runtime_moments `[ON_DEMAND]`**
**Classification:** **OPERATIONAL CORRECTION** — an observed coordination/legibility deficiency (`EKS-07`), corrected minimally. **NOT** an `EKS-07` implementation.
**Status:** implementation **complete + self-verified** (PO/ARB-approved plan, 2026-08-22, three binding conditions). **ADOPTION IS NOT CLAIMED** — the registry entry moves `planned → verify` only; adoption follows the governance path after **independent** verification.
**Registered by:** the producing session (`claude-code-session:8a525719`-backed) — **self-declared, not attestable** (`INV-ATTR-1`/`INV-ATTR-2`).
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0).

> ### ⭐ The one-line thesis
> **A governed AI session — Claude or DeepSeek — must be able to resolve its own registered lane / role / workflow state / authority deterministically at session start, from the SAME authoritative state, WITHOUT weakening a single governance rule.**
> ⛔ **The sentence every AST-017 artifact carries:** *Resolution is not activation.* The report creates no authority, no ownership, no state change — G-3 gates are untouched.

---

## 1 · The PO/ARB act (authorization) — verbatim scope

**Act:** PO/ARB approved the plan `.claude/plans/sequential-leaping-koala.md` (2026-08-22) **with three binding conditions**, incorporated verbatim below. The plan is the implementation authorization.

| # | PO/ARB condition (binding) | Where encoded |
|---|---|---|
| **1** | **Adoption handling — do NOT mark AST-017 `adopted` during implementation.** Registry enum is `adopted \| verify \| deprecated \| planned` (ARB amendment 4) — there is no `implemented`/`verified` literal. Encoded as **`planned` → `verify`** at slice close; `notes` states implementation + self-verification are complete but **ADOPTION IS NOT CLAIMED**; the `verified:` evidence block (reserved for adopted assets) stays unfilled. | registry.yaml AST-017 `adoption` + `notes` · §10 |
| **2** | **The V-3 exception stays exactly one thing.** AST-017 reads raw JSON for **only** the HANDOFF-to/from-lane fact (presence + token/tokenRef + successor target). It derives **no** workflow state, ownership, authorization, role, lifecycle, or predecessor semantics from raw JSON — those come from `askMechanism(fold/identity/authorized)`. Enforced by a **hard regression test S16** (poison the raw record; assert fold-derived truth, not decoys). | `v3HandoffRead()` · §5 · S16 |
| **3** | **Cross-provider conformance is an explicit acceptance criterion.** **S17**: same repository / same records / same `--process-label` / same work-item / same CLI args; run once under a Claude-shaped provider env, once under a DeepSeek-shaped provider env; **assert byte-identical JSON stdout** — the harness, not the model endpoint, determines the result. | §7 · S17 |

## 2 · The observed deficiency this corrects (evidence — see the diagnostic registration)

A governed session could not deterministically resolve its own lane/role/state/authority at start. The evidence, recorded live:

- **The divergence is NOT model-dependent.** Both providers receive identical injected context (CLAUDE.md, CONTEXT.md, MEMORY.md, active plan, session log) and identical `.claude/runtime/workflow/*.json` records. The only difference is the env-level model endpoint (`ANTHROPIC_BASE_URL` / `ANTHROPIC_MODEL`).
- **Live reproduction:** `session-resolve.php` → `{"verdict":"AMBIGUOUS","operable":false}` with 4 candidates; `identity S5-architecture-dv-correction-review` returns an `executionContext` embedding `claude-code-session:a8ce5a39` in free text while the running processes were `8a525719`/`c11a5a12`.
- **The real defect is coordination legibility:** process identity lives in free-text `executionContext`; no read command exposes the predecessor-handoff fact (open finding **V-3**); no single machine-readable bootstrap result exists. The recorded refusals — *"I will not adopt another process's identity in order to become operable"* — were **correct under the rules**; the tooling gave those sessions no lawful way to proceed.

Full reproduction, comparison matrix and lawful routes: `2026-08-22-KOS-SESSION-BOOTSTRAP-001-diagnostic-and-activation-registration.md` (this directory). Backlog home of the underlying deficiency: `docs/knowledgeos/backlog/EKS-07-multi-process-coordination.md`.

## 3 · Framing — what this is and what it is NOT (⛔ binding)

| This work item IS | This work item is NOT |
|---|---|
| a **minimal operational correction** for a coordination/legibility deficiency | an `EKS-07` implementation. **`EKS-07` is NOT declared solved.** Its backlog status (FUTURE ARCHITECTURE EXPLORATION / OBSERVED PROBLEM) is unchanged |
| ONE authoritative resolver + ONE machine-readable bootstrap result | a second workflow engine, identity system, role model, authority model, completion protocol, bounded context, or autonomous authority transfer |
| a **consumer** of AST-015 (`workflow-state.php`) — all workflow interpretation delegated | a second `fold` loop; a state derivation; a write path |
| a pointer from both harnesses (Claude + DeepSeek), canonical rule text here | a wire-in to `SESSION_START` (V-3 binding — see §9) |
| registered `planned → verify` (adoption NOT claimed) | registered `adopted` |

## 4 · Delegation contract (AMENDMENT 2 — reuse, never a second engine)

AST-017 holds **no** fold loop, **no** transition state machine, **no** state derivation. Every workflow fact comes from AST-015 invoked as a subprocess:

| Fact block | Comes from |
|---|---|
| `assignment` (lane · role · predecessor · workflow_state · work_item_state) | `fold <wi>` + `identity <wi> --session=<lane>` |
| `grant` (authorizationLinkage · authorized_within_scope) | `identity` + `authorized <wi> --session=<lane> --scope=<s>` (scope-string equality, R6/D-2) |
| `mutation_owner` (session · is_this_lane) | `fold` verbatim |

**If the mechanism is unavailable, the bootstrap CANNOT determine workflow state and fails closed (`UNRESOLVABLE`) — it never falls back to reading records itself.** Every report names WHICH interpreter answered (`interpreter.path` / `available` / `is_default` — C-1).

### ⛔ The one bounded exception (V-3)

Open finding V-3: **no AST-015 read command exposes the predecessor-handoff fact.** AST-017 performs exactly ONE raw read, in the named function `v3HandoffRead()`, for **its own consumption** — the single HANDOFF-to/from-lane fact (presence + token/tokenRef + successor target). It derives **nothing else** from raw JSON. Regression **S16** poisons the raw record with decoy `mutationOwner` / `workItemState` / `sessions.<lane>.*` fields and asserts the bootstrap reports the **fold-derived** truth — proving raw access is the handoff family and nothing else. The FULL remedy (an AST-015 read command) is recorded as **`EKS-07 FOLLOW-UP`**, not done here.

## 5 · Output contract — six-way separation, never collapsed

**`identity ≠ role ≠ eligibility ≠ authorization ≠ ownership ≠ continuation`.** Each is a first-class block of the report.

| Block | Fields (summary) | Rule |
|---|---|---|
| `identity` | `current_process_uuid` (env `CLAUDE_CODE_SESSION_ID`), `current_process_label`, `registered_process_label`, `process_labels_referenced[]` (ALL tokens in the lane's executionContext), `attribution` MATCH/MISMATCH/UNKNOWN, `attribution_caveat` | **evidence-only** — reported, **never** a grant input (`INV-ATTR-1`/`INV-ATTR-2`: self-declared until attested) |
| `assignment` | `work_item` · `lane` · `role` (declared vocabulary) · `predecessor` · `workflow_state` · `work_item_state` | AST-015 verbatim |
| `bootstrapping_status` / `activation_prerequisites` | `status` RESOLVED/AMBIGUOUS/UNRESOLVED/UNRESOLVABLE · `operable` · `predecessor_handoff_present` · `predecessor_handoff_token_ref` · `successor_handoff_present` · `successor_lane` · `recorded_human_start_act` · `missing_for_start[]` | discovery + G-3 conjunction, deterministic |
| `grant` | `authorization_linkage` · `linkage_caveat` (D-2: grants carry no session/role linkage) · `scope_requested` · `scope_coverage_evaluable` · `authorized_within_scope` bool\|null · `grant_caveat` (R6) | AST-015 `authorized` — scope-string equality |
| `mutation_owner` | `session` (fold.mutationOwner) · `is_this_lane` | fold only; HANDOFF clears it (EKS-08 §1b) |
| `gates` | `authorized_to_act` + rationale · `human_decision_required` + detail | derived deterministically, fail-closed |
| `continuation` | `current_session_can_continue` (capability, F1) · `recommended_next_actor` {role, reason, blocking_condition} | deterministic table, §8 |
| `meta` | `record_directory` · `work_items_scanned` · `candidates[]` · `disambiguation_required` · `unresolved_message` (actionable) · `resolution_source` · `interpreter` {path, available, is_default} · `read_only_guarantee` · `reasons[]` · `caveat` | |

> ### ⚠️ Scope caveat (design note, binding)
> **Grant-scoped work MUST pass `--scope`.** Without it, `authorized_within_scope` is UNKNOWN (`scope_coverage_evaluable=false`) and `authorized_to_act` is lane-continuation semantics only — an honest first-class answer (R6/D-2), never an error.

## 6 · Resolution algorithm (as implemented)

1. Resolve inputs: `--dir` (default `.claude/runtime/workflow`) · `--work-item` · `--process-label` · `--session` · `--role` · `--scope` · `--json`; env `CLAUDE_CODE_SESSION_ID`. Unknown option ⇒ exit `64`.
2. **AMENDMENT 2:** mechanism availability (`KOS_MECHANISM_PATH` env override or repo AST-015). Unavailable ⇒ `UNRESOLVABLE` + reason, exit `0`.
3. Discover records; `fold` **each through AST-015** (`askMechanism`), never locally. Filter by `--role` / `--work-item` / `--session`.
4. **Session matching (discovery ≠ grant):** explicit `--session` → match by id (never a silent pick if the id occurs in >1 record); else token-scan each candidate's executionContext for the current process label/uuid. **0 matches ⇒ UNRESOLVED** (actionable: lists registered lanes + their labels, names Governance `REGISTER` as the only way a lane comes to exist). **>1 ⇒ AMBIGUOUS** (lists all + exact disambiguation required; never guess). Else match.
5. `identity` via AST-015 for role/state/predecessor/authorizationLinkage; `authorized` when `--scope` given.
6. **V-3 bounded read** — `v3HandoffRead()`: last `HANDOFF` with `to==lane` && token && tokenRef → `predecessor_handoff`; last `HANDOFF` with `from==lane` → `successor_handoff`. No other fact read from raw.
7. Derived facts: `humanStartPresent` (=`ACTIVE`); `missing_for_start[]` lists **exactly the ABSENT G-3 conjuncts** — a CREATED lane with a recorded handoff now lists **only** the human START (truthful under V-3; S2b).
8. `authorized_to_act` = attribution MATCH ∧ (ACTIVE ∧ handoff ∧ humanStart) ∧ (scope===null ∨ authorizedWithinScope). MISMATCH/UNKNOWN ⇒ false + governance-act caveat.
9. `human_decision_required` + `recommended_next_actor` by state (deterministic table §8). Identity mismatch ⇒ governance.
10. **Fail-closed mapping:** any AMBIGUOUS/UNRESOLVED/UNRESOLVABLE ⇒ `operable=false`, `authorized_to_act=false`, `current_session_can_continue=false`, `unresolved_message` = missing fact · source · responsible next actor. Never invent; never transition.

## 7 · Determinism & provider-independence (acceptance criteria)

The binary makes **no model call**. Both provider paths consume identical PHP + identical records, so resolution is identical under either provider. Pinned by:

- **S8 determinism:** run twice → raw stdout byte-identical (JSON + human).
- **S17 cross-provider conformance (PO/ARB condition 3):** Claude-shaped provider env vs DeepSeek-shaped provider env, same repo/records/CLI → **raw JSON stdout byte-identical**.
- **S9 read purity:** record-dir fingerprint byte-identical across all four verdict paths.
- This session (DeepSeek-backed env) exercises the DeepSeek path; a Claude-backed session resolves identically.

## 8 · Deterministic next-actor table

| State / condition | `recommended_next_actor.role` | Reason |
|---|---|---|
| `CREATED` (no handoff, no human start) | `po/arb` | START requires a recorded human act (`G-3`) |
| `ACTIVE` | the lane's role | role may continue; blocking = grant gap (named) |
| `HANDED_OFF` | successor role **or** `po/arb` | successor takes over; G-3 holds until START |
| `STOPPED` | `governance` | `Inv E` — only a recorded Governance/Human CONTINUATION exits |
| `COMPLETED` / `CANCELLED` / `FAILED` | `po/arb` | `R8` — role immutable; terminal ⇒ new assignment by governance |
| identity MISMATCH / UNKNOWN | `governance` | never adopt another process's identity |

Roles come from the **declared role vocabulary only** (PO/ARB · Governance · Independent Reviewer / Architecture · Verification · Knowledge · Communication · Implementation) or the human authority (`po/arb` / `governance`) — **never a novel label**.

## 9 · Runtime binding — ON_DEMAND + pointer-only (V-3 binding)

AST-017 is registered `runtime_moments: [ON_DEMAND]` and is **NOT** wired into `SESSION_START`. **That wiring is a fresh governed slice after the V-3 full remedy** (V-3 binding). The harness pointers (§11) tell a governed session to run the command at start and consume `bootstrapping_status` before acting — nothing auto-executes it.

## 10 · Adoption status — ⛔ NOT ADOPTED

| Registry (`registry.yaml`) | Value |
|---|---|
| `adoption` | `planned` → **`verify`** at slice close (PO/ARB condition 1) |
| `notes` | implementation + self-verification complete; **ADOPTION IS NOT CLAIMED** — it follows the governance path after **independent** verification |
| `verified:` block | **stays unfilled** (reserved for adopted assets) |

The registry enum is `adopted | verify | deprecated | planned` (ARB amendment 4); there is **no** `implemented`/`verified` literal. `verify` is the enum's pre-adoption verification state — precisely the honest position: built, tested, and waiting for the governance path.

## 11 · Harness pointers (canonical rule text — rules live here, once)

A governed AI session at start:

1. Run `php .claude/scripts/session-bootstrap.php` (ON_DEMAND) with its process label / work item / `--scope` (when grant-scoped). Consume `bootstrapping_status` and `gates.authorized_to_act` **before acting**.
2. `RESOLVED` + `authorized_to_act=true` → proceed within the authorized scope.
3. Any `AMBIGUOUS` / `UNRESOLVED` / `UNRESOLVABLE` → **STOP, stay read-only, escalate to Governance** with the `unresolved_message` (missing fact · source · responsible next actor). **Never invent identity or authorization; never create a transition.**
4. Attribution MISMATCH/UNKNOWN → **never adopt another process's identity in order to become operable.** Escalate.

Pointers exist in `.claude/CLAUDE.md`, `AGENTS.md`, `.codex/README.md` — one line each, pointing here. `.codex/` adds no hooks or scripts.

## 12 · What was NOT modified (⛔ scope guard, verified)

`inject-context.sh` · `.claude/settings.json` · `workflow-state.php` (AST-015) · `session-resolve.php` (AST-016) · migration plan · DV-1…DV-7 findings · RV-1…RV-7 dispositions · migration authority · Phase 3 · Phase 5 · existing human authority rules · `.gitignore`/`.gitattributes` · `executionContext` content. The full WorkflowEngine suite (47/47, 522 assertions) confirms AST-015/AST-016 untouched.

## 13 · Deliverables delivered & verified

| Deliverable | Location |
|---|---|
| AST-017 resolver (read-only) | `.claude/scripts/session-bootstrap.php` |
| Contract suite S1–S17 (+S2b) | `tests/Unit/Platform/WorkflowEngine/SessionBootstrapContractTest.php` — 18/18 GREEN, 210 assertions |
| Developer guide | `developer_guide/knowledgeos/04_session_bootstrap_ast017.md` (+ `00_index.md` row) |
| Registry entry + AST-016 V-3 annotation | `.claude/platform/registry.yaml` (`planned → verify`) |
| Boundary proposal (this doc — rules live here) | `docs/knowledgeos/reviews/2026-08-22-KOS-SESSION-BOOTSTRAP-001-implementation-boundary-proposal.md` |
| Diagnostic + activation registration | `docs/knowledgeos/reviews/2026-08-22-KOS-SESSION-BOOTSTRAP-001-diagnostic-and-activation-registration.md` |
| EKS-07 addendum (append-only) | `docs/knowledgeos/backlog/EKS-07-multi-process-coordination.md` |
| Session Completion Report | `docs/knowledgeos/governance/2026-08-22-KOS-SESSION-BOOTSTRAP-001-session-completion.md` |

**STOP after verification.** This slice does **not** continue into migration governance. Deeper gaps revealed → record `EKS-07 FOLLOW-UP` and stop.

## 14 · Traceability

Work item `KOS-SESSION-BOOTSTRAP-001` · PO/ARB plan approval 2026-08-22 (`.claude/plans/sequential-leaping-koala.md`, three binding conditions) · `EKS-07` backlog (FUTURE ARCHITECTURE EXPLORATION — NOT solved; addendum recorded) · AST-016 V-3 open finding (partial remedy: consumer-side bounded handoff read; FULL remedy = AST-015 read command = EKS-07 FOLLOW-UP) · AMENDMENT 2 delegation · `INV-ATTR-1`/`INV-ATTR-2` (identity evidence-only, self-declared) · `G-3` (START requires recorded human act + predecessor handoff) · `Inv E` (STOPPED sticky) · `R6`/`D-2` (scope-string equality) · `R8` (role immutable; terminal → new assignment) · `C-1` (report WHICH interpreter answered) · F1 (capability ≠ authorization) · `ES-005.4` (reuse the engine; no second) · `ES-004.3` (artifact lifecycle sync) · registry CMP-004 · commits `e850439a` (registry-first) · `b05cca61` (RED) · `170e3052` (GREEN) · placement: `scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`, exit 0)
