# EP-01 PLAN — `ActivateCommissionedFreshSession` (KOS-OPERATING-MODEL-001 FOLLOW-UP / AMENDMENT)

**Canonical home after approval:** `docs/plans/20260822-2309-activate-commissioned-fresh-session-plan.md` (ES-004.2; timestamp `date +%Y%m%d-%H%M` = 20260822-2309)
**Work item:** `KOS-OPERATING-MODEL-001` (FINAL GOVERNANCE + COMMUNICATION OPERATING MODEL) — FOLLOW-UP/AMENDMENT slice
**Component:** `CMP-004` (workflow_engine) · **Asset ID (proposed):** `AST-019` (next in the AST-015/016/017/018 sequence; registry-first confirms it free)
**Plan status:** FINAL DRAFT — for explicit human approval (EP-01). **Approval authorizes the engineering implementation; it is NOT adoption or authorization of the capability.**
**Branch:** `election-review`

> **✅ IMPLEMENTED 2026-08-22 (EP-02 slice close):** human approval received (EP-01) · RED (GO-01..GO-25 by absence) → GREEN implemented → **full WorkflowEngine regression green: 147 passed (1577 assertions), baseline 122 + 25 new GO tests** · AST-015/016/017/018 + `operating-model.php` byte-unchanged (GO-15/16/17/25) · registry AST-019 → `adoption: verify`. **This plan is implemented; it does NOT verify, adopt, or authorize the capability** — those remain separate governed steps (independent verifier → governance adoption review → PO/ARB decision).

---

## 1 · Context — why this change exists

The human order (PO/ARB commission, 2026-08-22, verbatim) commissions the missing capability that makes the human-facing Governance model executable:

> **HUMAN GIVES BUSINESS ORDER → GOVERNANCE ENGINEER LISTENS → ANALYZES HONESTLY → DETERMINES LAWFUL EXECUTION PATH → CURRENT/FRESH SESSION BINDS ITS REAL RUNTIME IDENTITY TO THE REQUESTED RESPONSIBILITY → CANONICAL WORKFLOW MECHANICS → GOVERNANCE ENGINEER ACTIVE → HUMAN ORDER EXECUTED.**

**Current verified state of `KOS-OPERATING-MODEL-001`** (authoritative record `.claude/runtime/workflow/KOS-OPERATING-MODEL-001.json`, seq 1–9):
- **IMPLEMENTED** (3 layers) · **VERIFIED** (independent, fc59bb0a) · **NOT ADOPTED** · **NOT AUTHORIZED** · record **STOPPED** (seq 9)
- **No `role = governance` lane** on the work item for any process (`grants: []`). Lanes: `implementation` (259c1966, HANDED_OFF) · `verification` (fc59bb0a, STOPPED).
- **Two START GATE REFUSALs** (fc59bb0a verifier-bar; b51dba91 correction-author-bar): both turn on the **missing governed governance lane** + the would-be reviewer's own identity bar. The declared next step ("Governance adoption review") is a *step in a sequence, not a lane*.

**The recorded gap (follow-up observation — RECORDED·NOT DECIDED·NOT IMPLEMENTED):** under the current flow a genuinely fresh session checks reality, concludes *"I am a valid fresh candidate, but I am not yet authorized,"* and stops — self-registration is prohibited and the human cannot transport a pre-known session UUID. The refined architecture (PO/ARB 2026-08-22) corrects the old rule *"a session must never register itself"* as **too strong**:

> **A fresh session may register itself only when the desired role and work context are already established by the human's business instruction or an existing governed commission.**
> **Unified invariant: Human declares intended responsibility; runtime declares process identity; the governed bootstrap binds the two.**

**The commission re-issues the follow-up as a new governed slice.** Success criterion (§4 of the commission): the human order *"Start the Governance adoption review of KOS-OPERATING-MODEL-001"* must **not** produce *"no lane therefore refuse"* — a fresh eligible governance session must be able to bind its discovered runtime identity to the commissioned role through the canonical mechanism.

---

## 2 · Engineering Readiness Review (EP-03) — derived answers

| Question | Answer (derived from the repository + recorded commission) |
|---|---|
| **Business capability** | Enable a fresh session with a valid human business order / governed commission to bind its own runtime identity to the commissioned responsibility, through the canonical workflow mechanism. Business rules protected: the human never operates workflow mechanics; separation of duties (producer ≠ verifier ≠ adoption reviewer ≠ PO/ARB) is preserved; human START is a genuine human act. |
| **Strategic DDD** | The workflow engine (`CMP-004`) **owns** the transition invariants; the new capability **preserves** them (consumes AST-015/017/018, never re-implements). No new bounded context; no context-map change; no published-language interaction change. Owner of the *capability*: Governance Engineer (integration decision), Communication Engineer (routine mechanics) — existing responsibilities (P-3), not new roles. |
| **Canonical discovery (ES-005.4)** | The capability **already exists by name**: `ActivateCommissionedFreshSession` recorded in the follow-up observation + commit `c9762a90`. The commission's `BindRuntimeToRequestedResponsibility` is the domain-concept name (§16); implementation name stays `ActivateCommissionedFreshSession` — **never create a second**. |
| **Architecture** | New governed slice (asset AST-019) that consumes AST-015 (sole writer) / AST-017 (read-only) / AST-018 (next-actor) as subprocesses; validates the constrained binding contract; then appends REGISTER→HANDOFF→START **through AST-015 `append`** (the canonical mechanism). NOT a patch to AST-015/016/017/018 or operating-model.php. Decision recorded in this plan + L1 amendment doc. |
| **TDD** | GO-01..GO-25 hermetic contract tests (new series; minted via grep-collision + `identifier-check.php` INCONCLUSIVE evidence), RED-by-absence first, then GREEN. |
| **Design** | Constrained contract (all conjuncts must hold) → the ONLY allowed write `REGISTER {session=own identity, role=commissioned role}` → governed HANDOFF → human START (G-3). Fail-closed validation V1–V10. Business-language reporting, no UUID mechanics in human output. Verified mechanics (Part 3) pinned by the Plan agent against the scripts. |
| **Impact** | New files only (plan, script, contract test, L1 amendment, developer guide, registry entry, session log, CONTEXT, completion report). **No modification** to AST-015/016/017/018, operating-model.php, the verified implementation, or the authoritative record. No runtime state changes outside hermetic temp-dir fixtures. |
| **Verification** | GO-01..GO-25 RED-by-absence → GREEN; full WorkflowEngine regression stays green; read-only paths (AST-017/AST-018/operating-model) byte-identical (GO-25); determinism (GO-24); provider-independence (GO-23). |

**Authorization posture (honest, recorded):** this session's AST-017 bootstrap on `KOS-OPERATING-MODEL-001` is `UNRESOLVED` (no lane attributable — fail-closed, next actor `governance`). Per the standing rule, `UNRESOLVED` → **STOP, stay read-only, escalate** — producing this plan for the human is the escalation. **The human's commission + explicit approval of this plan = the business authorization for the engineering implementation (EP-01).** Approval authorizes *implementing* the capability; it does **not** authorize this session to adopt, verify, or authorize the capability, nor to claim any governance lane. The completion report records the four states separately; independent verification and the governance adoption review remain separate governed steps (the very capability this plan builds enables the latter).

---

## 3 · Capability design — `ActivateCommissionedFreshSession` (AST-019)

### 3.1 Naming (canonical discovery)

| Name | Use |
|---|---|
| `ActivateCommissionedFreshSession` | **Capability / implementation name** (recorded follow-up + commit `c9762a90`; ES-005.4 never-create-a-second) |
| `BindRuntimeToRequestedResponsibility` | Domain-concept name (§16 of the commission — the responsibility being bound) |
| `AST-019` | Asset ID (next in the AST-015/016/017/018 sequence) |
| `.claude/scripts/activate-commissioned-fresh-session.php` | Script asset |
| `ActivateCommissionedFreshSessionContractTest` | Contract test class (GO-01..GO-25) |

### 3.2 Constrained contract (the ONLY allowed write)

```
current runtime identity                     ← CLAUDE_CODE_SESSION_ID (env, never CLI)
    +  commissioned work item
    +  commissioned role
    +  valid fresh-session declaration
    +  eligibility / independence checks
    +  no existing conflicting assignment
        ↓
the ONLY allowed write:  REGISTER { session = own runtime identity, role = commissioned role }
        ↓  governed HANDOFF
        ↓  human START (G-3)
   <commissioned role> ACTIVE
```

**Invariant (verbatim):** *"A fresh session may self-bind identity; it may never self-choose role, scope, work item, or authority."*

**Safety rule:** role must come from the authoritative commission, never from the prompt alone: `prompt says Verification ∧ commission says Verification ∧ identity = me ∧ eligibility passes → ALLOWED`; `prompt says Architecture ∧ commission says Verification → MISMATCH → STOP`.

### 3.3 CLI surface (verified against the scripts)

```
php .claude/scripts/activate-commissioned-fresh-session.php activate \
      --work-item=<id> --requested-role=<role> \
      [--human-act=<verbatim human business instruction>] \
      [--exclude=<barred identity>]... [--dir=<records>] [--json] [--show-mechanics]
php .claude/scripts/activate-commissioned-fresh-session.php check \
      --work-item=<id> --requested-role=<role> [--human-act=<verbatim>] \
      [--exclude=<barred identity>]... [--dir=<records>] [--json]
```

- **`activate`** = the write path (REGISTER→HANDOFF→START). **`check`** = the identical validation phase, read-only, `transitionWritten:false` — the vehicle GO-23/GO-24/GO-25 use without mutating state (additive; mirrors AST-018 `next-actor` as the read sibling of `appoint`).
- **Runtime identity is NEVER a CLI arg** — read from `CLAUDE_CODE_SESSION_ID` env only (GO-02/GO-03); any `--session=`/`--identity=` is rejected by the generic unknown-option path, exit 64.
- **`--requested-role`** = the role the fresh session's prompt claims (the "prompt" side of prompt≠commission).
- **`--human-act`** = the recorded human business instruction (G-3), required for any write; without it → fail closed `HUMAN_DECISION_REQUIRED` (GO-14/GO-11).
- **`--exclude=<id>`** (repeatable) = human-declared cross-work-item independence bars; the capability **always adds** this work item's fold session keys (mirrors AST-018 `--exclude`).
- **Exit codes:** 0 accepted/activated (or a produced `check` report) · 64 usage · 65 refused / INCOMPLETE_SEQUENCE.

### 3.4 Validation contract V1–V10 (fail-closed; any failure stops with a business-language reason; zero writes)

| # | Validation | Fails closed when |
|---|---|---|
| V1 | Identity present | `CLAUDE_CODE_SESSION_ID` absent/unparseable → `NO_RUNTIME_IDENTITY` |
| V2 | Work item exists | record absent under the records dir (absence never implies ownership) |
| V3 | Requested role ∈ role set | `--requested-role` not in the record's `roles` |
| V4 | Requested role == authoritative commission | **prompt ≠ commission → MISMATCH → STOP** (see 3.5 commission resolution) |
| V5 | Eligibility / independence | identity ∈ (fold session keys ∪ `--exclude`) → `NOT_ELIGIBLE` (producer / verifier / correction-author / PO-ARB bars) |
| V6 | No conflicting assignment | identity already registered on this work item (R8); `LANE_ACTIVE` / `ACTIVATION_PENDING` next-actor result |
| V7 | Workflow legality | `fold.workItemState === STOPPED` → honest Inv-E blocker (GO-21); **the capability never writes CONTINUATION** |
| V8 | Human order/commission exists | no `--human-act` and no recorded commission → fail closed (GO-11) |
| V9 | Canonical write path available | AST-015 unusable / AST-017 `UNRESOLVABLE` → honest capability-gap report (GO-20), never a fabricated verdict |
| V10 | Four-state separation | bound lane reports `ACTIVATED`, never ADOPTED/AUTHORIZED (GO-22) |

### 3.5 Commission resolution (verified against AST-018 `next-actor`)

The authoritative commission comes from `AST-018 next-actor <workItem>` (payload: `workItem, result, role, freshIndependentActorRequired, reason, businessExplanation, requiredHumanDecision, options, transitionWritten, caveat`):

| `next-actor.result` | Commission source | Capability behavior |
|---|---|---|
| `NEXT_ACTOR_REQUIRED` | `role` field = the authoritative next role | require `--requested-role === role`, else **MISMATCH → STOP** (GO-08) |
| `HUMAN_DECISION_REQUIRED` + **empty session set** | **first-binding case** — no workflow-derived role; the commission is the **human business order itself** | require requested-role named in `--human-act` (bounded case-insensitive word-boundary lexical check) → **this is the mechanical encoding of prompt≠commission for the FIRST session** (`"I want Governance Engineer."`) |
| `HUMAN_DECISION_REQUIRED` + `options` contains `DECIDE` | adoption is the human's decision | no fresh binding implied → refuse (GO-21 escalation) |
| `WORK_ITEM_STOPPED` | — | exit 65 with the Inv-E blocker naming the required CONTINUATION by governance/human; **no CONTINUATION write** (GO-21) |
| `LANE_ACTIVE` / `ACTIVATION_PENDING` | — | conflicting assignment → exit 65 (GO-13) |
| `AMBIGUOUS` | — | exit 65, governance escalation (GO-21) |

**Fresh-session declaration (V5) via AST-017** `session-bootstrap --work-item=<wi> --process-label=<identity>`: must be `UNRESOLVED` (no lane attributes this process). `RESOLVED`/`AMBIGUOUS` → already bound / conflict → exit 65 (GO-12/GO-13). `UNRESOLVABLE` → honest capability-gap report (GO-20).

### 3.6 Write path (canonical — verified AST-015 transition contracts)

Only through `AST-015 append <workItem> --json='{...}' --dir=<records>` (sole writer; GO-15/GO-18). Exact required JSON fields:

1. **REGISTER** — `{type:'REGISTER', session:<identity>, role:<requested-role>, predecessor:<fold.mutationOwner|null>, executionContext:<canonical attribution string>, recordedBy:'governance'}`.
   - `predecessor` **key must exist** (`null` is a valid value — bootstrap lineage). Refused by R8 if `session` already registered (role immutable).
   - `executionContext` must embed `claude-code-session:<identity>` followed by whitespace, never punctuation (the N-16 pitfall) — plus the humanAct, the commission source, and the independence basis.
2. **HANDOFF** — `{type:'HANDOFF', from:<fold.mutationOwner|null>, to:<identity>, token:'T-<WORKITEM>-<ROLE>', tokenRef:'Recorded human business order: <humanAct>', recordedBy:'governance'}`.
   - `from: null` is valid **only while `mutationOwner === null`** (bootstrap form); otherwise `from` must equal the current `mutationOwner` (Inv C). `token` + `tokenRef` both non-empty (Inv D).
3. **START** — `{type:'START', session:<identity>, humanAct:<verbatim --human-act>, recordedBy:'human'}`.
   - Requires the fold to already have `handoffsTo[session]` true — **a recorded HANDOFF toward the session must precede START** (G-3 conjunction, both directions).
4. **Verify outcome** via `AST-015 fold` (new lane `state === 'ACTIVE'`, `mutationOwner === identity`) — never assume.
5. **Render.** `result: 'ACTIVATED'` (GO-22). Human output (default) hides REGISTER/HANDOFF/START/mutationOwner/predecessor/UUID vocabulary (GO-19); `--show-mechanics` is the only escape hatch. Mid-sequence failure → `INCOMPLETE_SEQUENCE` honestly reported (what was written + who acts next), exit 65 (AST-018 precedent).

**CONTINUATION is the one transition the capability must NEVER write** — `assertTransitionAllowed` (workflow-state.php) refuses every type on a STOPPED item except CONTINUATION, and CONTINUATION is reserved to `governance`/`human` (Inv E). A STOPPED item is an honest blocker (GO-21), never a bypass.

---

## 4 · Test matrix — GO-01..GO-25 (minimum acceptance tests, commission §28)

**GO-series minting (verified):** `GO` is **not** a governed register — `php scripts/identifier-check.php GO-01` returns `INCONCLUSIVE` exit 1 ("no criterion exists; absence of evidence is not PASS"). Minting = (1) grep-collision check `grep -rnE "GO-[0-9]{2}" --include="*.php" --include="*.md" --include="*.yaml" --include="*.json" .` → **confirmed zero existing occurrences**; (2) run `identifier-check.php GO-01` and record the INCONCLUSIVE verdict as evidence; (3) mint GO-01..GO-25 scoped to the contract-test file (consistent with the OM/N/R/S/P prefixes — none are governed registers either). No new governed register required.

All hermetic: `proc_open` subprocess against `.claude/scripts/activate-commissioned-fresh-session.php` with `--dir=sys_get_temp_dir()/kos-act-<hex>`, fixtures built **through AST-015** (init + append, never hand-written JSON), `assertFileExists(... 'RED by absence')`, `fingerprint()` byte-purity helper, `fold()` helper. Test-ID prefix `go_*` (scoped, non-colliding with n/r/p/s/t/om). Required scenario coverage:

| GO | Scenario (fixture → invocation → assertion) |
|---|---|
| **GO-01** | First fresh Governance session binds to governance — empty record (no lanes), `--requested-role=governance`, human-act *"I want Governance Engineer."* → exit 0, `ACTIVATED`, fold: role governance, ACTIVE, predecessor null, mutationOwner = env id (first-binding path) |
| **GO-02** | Human never supplies a session UUID — `--session=...` rejected (exit 64); successful activation's human rendering matches no UUID regex |
| **GO-03** | Runtime identity discovered automatically — identity read from `CLAUDE_CODE_SESSION_ID` only; REGISTER.session === env id; AST-017 resolves RESOLVED/MATCH after binding |
| **GO-04** | Human order creates intended responsibility — requested role parsed from the human business instruction → the binding uses it (subsequent-commission path) |
| **GO-05** | Governance adoption review starts from human order — work item with architecture+verification COMPLETED → `next-actor` NEXT_ACTOR_REQUIRED role=governance → fresh governance session binds (the commission's §29 current-problem test) |
| **GO-06** | Subsequent fresh Verification session self-binds — verification is the commissioned next role → binds; and a role NOT in the role set → exit 65, fingerprint unchanged |
| **GO-07** | Existing commission is honored — binding uses the authoritative next-role, not a self-chosen role; duplicate REGISTER of the same env → exit 65, no second lane (R8) |
| **GO-08** | Requested-role mismatch fails closed — prompt claims X, commission says Y → `MISMATCH`, exit 65, fingerprint unchanged |
| **GO-09** | Wrong work item fails closed — `--work-item` missing (exit 64) / record absent (exit 65, absence never implies ownership) |
| **GO-10** | Arbitrary role self-selection fails closed — role not in role set / empty identity → `NO_RUNTIME_IDENTITY`, nothing written |
| **GO-11** | No human order + no commission fails closed — no `--human-act`, no recorded commission → exit 65 `HUMAN_DECISION_REQUIRED` |
| **GO-12** | Barred identity fails eligibility — identity already a registered lane on the work item (producer/verifier bar; R8 backstop) → exit 65 `NOT_ELIGIBLE` |
| **GO-13** | Conflicting assignment fails closed — lane CREATED (assigned, not started) → `ACTIVATION_PENDING` → exit 65, fingerprint unchanged |
| **GO-14** | No humanAct is fabricated — no `--human-act` → exit 65, no START; with verbatim act → START.humanAct === verbatim, `recordedBy: human` |
| **GO-15** | Canonical AST-015 mechanism is used — source-inspection: script invokes `workflow-state.php`, contains no `runtime/workflow` path, no record `file_get/put_contents`/`fwrite(`/`rename(`; behavioral: post-activate fold via AST-015 shows ACTIVE |
| **GO-16** | AST-017 remains read-only — `git diff --quiet HEAD -- .claude/scripts/session-bootstrap.php` clean; a bootstrap run on the fixture leaves the directory fingerprint unchanged |
| **GO-17** | AST-018 remains within its boundary — `git diff --quiet HEAD -- .claude/scripts/next-actor-orchestration.php` clean; capability source does **not** invoke `appoint` |
| **GO-18** | No direct workflow JSON mutation — poison the raw record with decoy mutationOwner/workItemState/sessions (S-16 pattern) → activation succeeds via the fold, not decoys; the only file mutation is AST-015's append |
| **GO-19** | Human-facing output has no UUID mechanics — default rendering contains no REGISTER/HANDOFF/START/mutationOwner/predecessor/claude-code-session:/UUID |
| **GO-20** | Honest capability-gap reporting — corrupt record / missing `KOS_MECHANISM_PATH` → exit 65, report names the mechanism's refusal + responsible next actor, nothing written |
| **GO-21** | Governance escalation for genuine conflict — STOPPED work item → exit 65 naming the required CONTINUATION by governance/human, fingerprint unchanged (no CONTINUATION written); `DECIDE`-option → refusal |
| **GO-22** | Adoption stays separate from verification — bound lane reports `ACTIVATED`, never ADOPTED/AUTHORIZED; caveat states activation ≠ adoption |
| **GO-23** | Provider-independent behavior — `check` under Claude-shaped vs DeepSeek-shaped env → byte-identical JSON |
| **GO-24** | Deterministic behavior — `check` twice on the same fixture → byte-identical JSON |
| **GO-25** | Read-only paths remain byte-identical — AST-017 bootstrap + AST-018 next-actor + operating-model outcome/session raw JSON identical before/after `check`; `git diff --quiet HEAD` clean for AST-017/AST-018/operating-model.php |

*Per-GO assertion text is pinned in the contract test during the RED phase; the matrix above is the required scenario coverage (commission §28 minimums + the §29 current-problem case as GO-05).*

---

## 5 · Files to create / touch

**New (this slice):**
1. `docs/plans/20260822-2309-activate-commissioned-fresh-session-plan.md` — this plan, committed after approval (ES-004.2; supersedes/references the follow-up observation + operating-model plan).
2. `.claude/scripts/activate-commissioned-fresh-session.php` — the capability (**AST-019**; house style: `declare(strict_types=1)`, docblock header, subprocess delegation, exit 0/64/65).
3. `tests/Unit/Platform/WorkflowEngine/ActivateCommissionedFreshSessionContractTest.php` — GO-01..GO-25 hermetic contract test (pattern: `SessionBootstrapContractTest`).
4. L1 amendment document — `docs/knowledgeos/reviews/2026-08-22-KOS-OPERATING-MODEL-001-AMENDMENT-001-ActivateCommissionedFreshSession.md` (AMENDMENT-001 prefix free for this work item; pattern `...-NEXT-ACTOR-ORCHESTRATION-001-AMENDMENT-001-...`): gap rationale · constrained contract · unified binding model · **L1 operating-model amendments** (fresh-session §16–§20, role-transition §31, §30 hard-acceptance GO-series — additive, history never rewritten per ES-004.3) · AST-019 Layer-2 · GO-series Layer-3 · boundaries · non-actions ⛔ · `session_completion`. *Final placement re-derived via `php scripts/doc-placement.php` at implementation (exit 0 vs exit 2 → PENDING + escalate).*
5. Developer guide: `developer_guide/ai_platform/06_activate_commissioned_fresh_session.md` + update `developer_guide/ai_platform/00_index.md` (DoD; grounded in the committed script + test class).
6. `.claude/platform/registry.yaml` — **registry-first**: register AST-019 at `adoption: planned` BEFORE implementation, then `adoption: verify` (shape in §7).
7. Session log (append `.claude/sessions/2026-08-22.md`) + `.claude/CONTEXT.md` update (ES-004.3 synchronization).
8. Completion report — `docs/knowledgeos/reviews/2026-08-22-KOS-OPERATING-MODEL-001-AMENDMENT-001-session-completion.md` (IMPLEMENTED ≠ VERIFIED ≠ ADOPTED ≠ AUTHORIZED).

**Modified:** none of AST-015/016/017/018, `operating-model.php`, the verified implementation, or the authoritative workflow record. Read-only paths byte-unchanged (GO-25).

---

## 6 · Implementation order (RED → GREEN → doc → completion)

1. **Mint GO-series**: grep-collision check (confirmed empty) + `php scripts/identifier-check.php GO-01` → record INCONCLUSIVE (exit 1) as evidence; mint GO-01..GO-25.
2. **Registry-first**: add the AST-019 entry to `.claude/platform/registry.yaml` at `adoption: planned` (registered BEFORE implementation — registry-first is binding).
3. **RED**: write `ActivateCommissionedFreshSessionContractTest` with GO-01..GO-25; run → all fail by absence (`assertFileExists` on the missing script). Baseline: full WorkflowEngine suite green (GO-16/17/25 baseline).
4. **GREEN**: implement `.claude/scripts/activate-commissioned-fresh-session.php` (validation V1–V10 → commission resolution → AST-015 append REGISTER/HANDOFF/START → verify fold → business-language report). Run GO-01..GO-25 → green.
5. **Regression**: full WorkflowEngine suite (AST-015 R-*, AST-016, AST-017 S-*, AST-018, OperatingModel) stays green; read-only outputs byte-identical (GO-25).
6. **Docs**: L1 amendment doc (placement re-derived) · developer guide + 00_index · registry `adoption: verify` · session log · CONTEXT.
7. **Commit**: plan + implementation + docs (one story → one commit; subject `(KOS-OPERATING-MODEL-001-AMENDMENT-001)` if the amendment ID is minted, else the work-item ref).
8. **Completion report** (§8) — then **STOP**.

---

## 7 · Registry entry shape (`.claude/platform/registry.yaml`)

```yaml
  - id: AST-019
    path: .claude/scripts/activate-commissioned-fresh-session.php
    component: CMP-004
    adoption: planned              # registry-first: planned BEFORE implementation → verify after
    governance_tier: 2
    runtime_moments: [ON_DEMAND]   # no hook, no SESSION_START wiring
    trace:
      capability: CAP-05
      context: implementation-guidance
      decision: G-KOS-OPERATING-MODEL-001-AMENDMENT-001   # PO/ARB commissioning act
      adr: KOS-OPERATING-MODEL-001
    notes: "CMP-004's FIFTH implementation asset: constrained fresh-session self-binding. Consumes AST-015 (sole writer via append) + AST-017 (read-only fresh declaration) + AST-018 (next-actor commission). REGISTER→HANDOFF→START only; G-3; invariant: a fresh session may self-bind identity, never self-choose role/scope/work-item/authority; GO-01..GO-25 contract."
```

---

## 8 · Completion report expectations (§34 of the commission)

- Separate **IMPLEMENTED ≠ VERIFIED ≠ ADOPTED ≠ AUTHORIZED**.
- **IMPLEMENTED** — capability + GO-01..GO-25 green + docs delivered.
- **NOT VERIFIED** — producer bar (R-34/EP-02): this session does not verify its own capability; a fresh independent verifier is the next governed step.
- **NOT ADOPTED** — no governance adoption review performed; the adoption review of the amended operating model is a separate governed step (fresh eligible governance session + human continuation/START — the very capability this slice builds).
- **NOT AUTHORIZED** — no PO/ARB adoption decision claimed.
- `next_actor`: Governance (adoption-review path) — the human decides; `requires_human_decision: true`.

---

## 9 · Boundaries / STOP conditions

- ⛔ **No modification** of AST-015/016/017/018, `operating-model.php`, the verified implementation assets, or the authoritative record.
- ⛔ **No second workflow engine / no direct record write** (GO-15/GO-18).
- ⛔ **No CONTINUATION write by the capability** — STOPPED is an honest Inv-E blocker, resolved only by governance/human (GO-21).
- ⛔ **No automatic adoption, no automatic human START** (G-3; GO-14/GO-22).
- ⛔ **No self-role-choice / self-scope / self-authority** — the role is the commissioned role, validated against the authoritative commission (V4).
- ⛔ **No EKS-07 reopening** (no autonomous session creation / automatic actor replacement / automatic adoption).
- ⛔ This session does **not** verify or adopt its own capability; does not claim a governance lane.
- **STOP** if implementation invalidates this approved plan → explain, present the revised plan, wait for approval (EP-01).
- **STOP** if the capability cannot be expressed over the existing mechanism surface (it can — the Plan agent verified) — do not patch the mechanisms.

---

## 10 · Design decisions recorded in this plan (D-refs)

| D-ref | Decision |
|---|---|
| D-1 | Capability name `ActivateCommissionedFreshSession` (recorded follow-up; ES-005.4); `BindRuntimeToRequestedResponsibility` is the domain-concept name (§16) |
| D-2 | Asset ID AST-019 (next in sequence; registry-first confirms it free) |
| D-3 | Write path = direct `AST-015 append` REGISTER→HANDOFF→START after validation; NOT AST-018 `appoint` (the recorded design specifies the fresh session's own REGISTER as the only allowed write); AST-018 consumed read-only (`next-actor`) |
| D-4 | `--human-act` is the recorded human business instruction (G-3), required for the binding write; without it → fail closed (GO-14) |
| D-5 | **First-binding nuance (verified):** for an empty record, `next-actor.role` is null — the commission is the human business order itself; requested-role must be named in the human-act (bounded lexical check). This is a design consequence, not a mechanism change |
| D-6 | `check` subcommand = read-only validation sibling (vehicle for GO-23/24/25); additive, does not widen the write surface |
| D-7 | STOPPED work item → honest Inv-E blocker, never a CONTINUATION write (Inv E reserved to governance/human) |
| D-8 | recordedBy: governance for REGISTER/HANDOFF (governance-recording-by-appointed-actor precedent, NON-DISQUALIFYING), recordedBy: human for START with the verbatim humanAct |

---

## 11 · Open items (recorded, not blocking)

1. **Formal implementation lane for this slice** — the prior implementation lane (259c1966) was recorded on explicit PO/ARB direction ("record first and then start"). This commission does not contain such a direction; the plan proceeds under human commission + plan approval, recording the four states in the completion report. The human may direct a formal lane recording if desired.
2. **Adoption-review activation of the real work item** — executing the actual Governance adoption review of `KOS-OPERATING-MODEL-001` requires (a) the human's continuation decision (record STOPPED seq 9, Inv E) and (b) a fresh eligible governance session invoking the new capability. Both remain human-governed, separate steps — **out of scope** for this implementation slice.
3. **GO-series final minting** — confirmed during implementation (Step 1); no governed-register registration needed (GO is test-scoped, like OM/N/R/S/P).
