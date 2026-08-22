# Plan — KOS-SESSION-BOOTSTRAP-001: Session Bootstrap & Responsibility Resolution (AST-017)

**Work item (proposed):** `KOS-SESSION-BOOTSTRAP-001` · **Component:** `CMP-004` (workflow_engine) · **governance_tier 2** · **runtime_moments `[ON_DEMAND]`**
**Date:** 2026-08-22 · **Gate:** EP-01 plan-mode approval (this plan) is the implementation authorization.

## ⚖️ Approval amendments (PO/ARB, 2026-08-22 — binding, incorporated below)

APPROVED WITH CONDITIONS:
1. **Adoption handling — do NOT mark AST-017 `adopted` during implementation.** Registry enum (ARB amendment 4) is `adopted | verify | deprecated | planned` — there is no `implemented`/`verified` literal. The condition is encoded as **`planned` → `verify`** (the enum's pre-adoption verification state) at slice close, with the `notes` line stating implementation + self-verification are complete but **ADOPTION IS NOT CLAIMED** — it follows the governance path after **independent** verification. The `verified:` evidence block (reserved for adopted assets) stays unfilled.
2. **V-3 exception stays exactly one thing.** AST-017 reads raw JSON for **only** the HANDOFF-to/from-lane fact (presence + token/tokenRef + successor target). It derives **no** workflow state, ownership, authorization, role, lifecycle, or predecessor semantics from raw JSON — those all come from `askMechanism(fold/identity)`. Enforced by a **hard regression test S16** (poison the raw record with decoy `mutationOwner`/`workItemState`/`sessions.*` fields; assert the bootstrap reports the fold-derived truth, not the decoys).
3. **Cross-provider conformance is an explicit acceptance criterion.** New **S17**: same repository, same records, same `--process-label`, same work-item, same CLI args; run once under a Claude-shaped provider env and once under a DeepSeek-shaped provider env; **assert byte-identical JSON stdout** (proves the harness, not the model endpoint, determines the result).

---

## Context — why

The PO/ARB commissioned a **minimal operational correction** for the EKS-07 observed coordination deficiency: governed sessions (Claude and DeepSeek) cannot deterministically resolve their own registered lane / role / state / authority at session start. The evidence:

- **The divergence is NOT model-dependent.** Both providers receive identical injected context (CLAUDE.md, CONTEXT.md, MEMORY.md, active plan, session log) and identical `.claude/runtime/workflow/*.json` records. The only difference is the env-level model endpoint (`ANTHROPIC_BASE_URL`/`ANTHROPIC_MODEL`). Live reproduction: `session-resolve.php` → `{"verdict":"AMBIGUOUS","operable":false}` with 4 candidates; `identity S5-architecture-dv-correction-review` returns an `executionContext` that embeds `claude-code-session:a8ce5a39` in free text while the running processes were `8a525719`/`c11a5a12`.
- **The real defect is coordination legibility:** process identity lives in free-text `executionContext`; no read command exposes the predecessor-handoff fact (open finding V-3); no single machine-readable bootstrap result exists. The four recorded refusals ("**I will not adopt another process's identity in order to become operable.**") were *correct under the rules* — the tooling gave those sessions no lawful way to proceed.
- **Intended outcome:** one authoritative resolver + one machine-readable `session_bootstrap` result + one-line pointers from both harnesses, so the same authoritative state yields the same resolution under either provider.

**Framing (binding):** treat EKS-07 as observed deficiency + minimal correction. **Do NOT declare EKS-07 solved.** Reuse the existing workflow engine (AST-015) and declared role vocabulary. Do NOT create a second engine/identity/role/authority/completion/bounded-context or autonomous transfer. Identity is **evidence-only** (INV-ATTR-1/2), never a gate input. Fail-closed on any undeterminable fact.

---

## Recommended approach

**One new read-only resolver:** `.claude/scripts/session-bootstrap.php` (AST-017), delegating ALL interpretation to AST-015 (`workflow-state.php` fold/identity/authorized as subprocess) per AMENDMENT 2 — with **exactly one documented bounded exception**: a V-3 handoff read (scan of the append-only transition log for the single `HANDOFF`-to/from-lane fact, because no AST-015 read command exposes it). AST-017 performs **no fold**, no state derivation, no write calls.

### CLI & exit contract (mirrors AST-016)

```
php .claude/scripts/session-bootstrap.php [--dir=<records>] [--work-item=<id>]
    [--process-label=<claude-code-session:XXXX|XXXX>] [--session=<lane>]
    [--role=<role>] [--scope=<s>] [--json]
```
YAML default, `--json` for machine consumption (tests). **Every produced report** (RESOLVED / AMBIGUOUS / UNRESOLVED / UNRESOLVABLE) exits **0**; `64` usage; structurally write-free.

### Output schema — `session_bootstrap` (six-way separation, never collapsed)

| Block | Fields | Source / rule |
|---|---|---|
| `identity` | `current_process_uuid` (env `CLAUDE_CODE_SESSION_ID`), `current_process_label`, `registered_process_label`, `process_labels_referenced[]` (ALL tokens in the lane's executionContext), `attribution` MATCH/MISMATCH/UNKNOWN, `attribution_caveat` | evidence-only; reported, **never** a grant (INV-ATTR-1/2) |
| `assignment` | `work_item`, `lane`, `role` (from AST-015, declared vocabulary), `predecessor`, `workflow_state`, `work_item_state` | AST-015 `identity` + `fold` verbatim |
| `bootstrapping_status` / `activation_prerequisites` | status RESOLVED/AMBIGUOUS/UNRESOLVED/UNRESOLVABLE, `operable`; `predecessor_handoff_present`, `predecessor_handoff_token_ref`, `successor_handoff_present`, `successor_lane`, `recorded_human_start_act` (=ACTIVE⇒true, G-3), `missing_for_start[]` (exactly the ABSENT G-3 conjuncts) | discovery + G-3 conjunction, deterministic |
| `grant` | `authorization_linkage` (last AUTHORIZED grant id), `linkage_caveat` (D-2: grants carry no session/role linkage), `scope_requested`, `scope_coverage_evaluable`, `authorized_within_scope` bool|null, `grant_caveat` (R6) | AST-015 `identity` + `authorized` (scope-string equality) |
| `mutation_owner` | `session` (fold.mutationOwner), `is_this_lane` | fold only; HANDOFF clears it (EKS-08 §1b) |
| `gates` | `authorized_to_act` + rationale; `human_decision_required` + detail | derived deterministically, fail-closed |
| `continuation` | `current_session_can_continue` (capability, F1), `recommended_next_actor` {role, reason, blocking_condition} | deterministic; PO/ARB on escalation |
| meta | `record_directory`, `work_items_scanned`, `candidates[]` (verbatim fold), `disambiguation_required`, `unresolved_message` (actionable), `resolution_source`, `interpreter` {path, available, is_default} (C-1), `read_only_guarantee`, `reasons[]`, `caveat` ("Resolution is not activation…") | |

### Resolution algorithm (summary)

1. Resolve inputs: `--dir` (default `.claude/runtime/workflow`), `--work-item`, `--process-label`, `--session`, `--role`, `--scope`; env `CLAUDE_CODE_SESSION_ID`.
2. **AMENDMENT 2:** mechanism availability check (env `KOS_MECHANISM_PATH` or repo AST-015). Unavailable → `UNRESOLVABLE` + reason, exit 0.
3. Discover records; `fold` **each through AST-015** (`askMechanism`), never locally. Filter by `--role` / `--work-item` / `--session`.
4. **Session matching (discovery ≠ grant):** explicit lane → match by id; else token-scan each candidate's executionContext for the process label/uuid. **0 matches → UNRESOLVED** (actionable: lists registered lanes + their labels, names Governance REGISTER as the only way a lane comes to exist). **>1 → AMBIGUOUS** (lists all + exact disambiguation required; never guess). Else match.
5. `identity` via AST-015 for role/state/predecessor/authorizationLinkage; `authorized` when `--scope` given.
6. **V-3 bounded handoff read** (the ONE exception): last `HANDOFF` with `to==lane` && token && tokenRef → `predecessor_handoff`; last `HANDOFF` with `from==lane` → `successor_handoff`. Also collects `process_labels_referenced`. No other fact read from raw.
7. Derived facts: `humanStartPresent` (=ACTIVE), `missing_for_start[]` (a CREATED lane with a recorded handoff now lists **only** the human START — truthful under V-3).
8. `authorized_to_act` = attribution MATCH ∧ (ACTIVE ∧ handoff ∧ humanStart) ∧ (scope===null ∨ authorizedWithinScope). MISMATCH/UNKNOWN → false + governance-act caveat.
9. `human_decision_required` + `recommended_next_actor` by state (deterministic table: CREATED→po/arb for START; ACTIVE→role, blocking=grant gap; HANDED_OFF→successor role or po/arb (G-3); STOPPED→governance (Inv E); COMPLETED/CANCELLED/FAILED→po/arb (R8)). Identity mismatch → governance.
10. Fail-closed mapping: any AMBIGUOUS/UNRESOLVED/UNRESOLVABLE → `operable=false`, `authorized_to_act=false`, `current_session_can_continue=false`, `unresolved_message` = missing fact · source · responsible next actor. Never invent; never transition.

> **Scope caveat (design note):** grant-scoped work MUST pass `--scope`. Without it, `authorized_within_scope` is UNKNOWN (`scope_coverage_evaluable=false`) and `authorized_to_act` is lane-continuation semantics only (R6/D-2 honesty, first-class answer, never an error).

---

## Files to create / modify (in order)

1. **`.claude/platform/registry.yaml`** — registry-first (R-17): add AST-017 entry (`adoption: planned`, CMP-004, tier 2, ON_DEMAND, five-question trace `CAP-05`/implementation-guidance/`G-KOS-SESSION-BOOTSTRAP-001-IMPL`/`KOS-SESSION-BOOTSTRAP-001`); annotate AST-016 `finding_open` with the V-3 **partial remedy chosen** by AST-017 (consumer-side bounded read for its own consumption; FULL remedy — an AST-015 read command — deferred as EKS-07 FOLLOW-UP). **At slice close: flip AST-017 `adoption: planned → verify` (NOT `adopted`) with notes recording implementation + self-verification only; adoption is deferred to the governance path after independent verification (PO/ARB condition 1).**
2. **`.claude/scripts/session-bootstrap.php`** (NEW) — the resolver; docblock header (asset id, component, purpose, read-only guarantee, exit contract, Usage); `declare(strict_types=1)`; named `v3HandoffRead()` function with a comment citing V-3; no write calls.
3. **`tests/Unit/Platform/WorkflowEngine/SessionBootstrapContractTest.php`** (NEW) — RED-first (absent file → fail), then GREEN. Replicate the hermetic pattern of `SessionAssignmentResolverContractTest.php` (`sys_get_temp_dir()`, `proc_open`, records built **through** the mechanism, `requireBootstrap()` RED-by-absence guard, `directoryFingerprint()`); invoke with `--dir=$this->dir`.

| Test | Mission case | Core assertion |
|---|---|---|
| S1 normal active lane | T1 | RESOLVED, operable, attribution MATCH, owner=this lane, authorized_to_act=true (with `--scope`), can_continue=true, human_decision=false |
| S2 created reviewer lane | T2 | authorized_to_act=false, human_decision=true, `missing_for_start` = BOTH handoff AND human START, next_actor=po/arb |
| S2b created + recorded handoff | V-3 truthfulness | handoff_present=true, `missing_for_start` = ONLY human START (proves the handoff line is not unconditional) |
| S3 active architecture repair lane | T3 | `--scope=<covered>` → authorized_to_act=true; `--scope=<other>` → false (R6) |
| S4 multiple matching sessions | T4 | AMBIGUOUS, 2 candidates, no `selected`, disambiguation_required non-null, authorized=false |
| S5 identity mismatch | T5 | MISMATCH, authorized=false, caveat names governance act / no impersonation, next_actor=governance |
| S6 active, no covering grant | T6 | `--scope=<uncovered>` → authorized_within_scope=false, authorized=false, human_decision=true, next_actor rule-based |
| S7 completed session | T7 | RESOLVED, operable=false, can_continue=false, next_actor=po/arb, human_decision=true |
| S8 determinism | — | run twice, raw stdout byte-identical (JSON + YAML) |
| S9 read purity | — | record-dir fingerprint byte-identical across all four verdict paths |
| S10 no matching process | fail-closed | UNRESOLVED, actionable message: missing fact (REGISTER attributing process), source (Governance/commission), next actor (governance), lists lanes+labels |
| S11 absent mechanism | AMENDMENT 2 | UNRESOLVABLE, reason names mechanism |
| S12 no records dir | — | UNRESOLVABLE |
| S13 interpreter reported | C-1 | interpreter.path == repo AST-015 on every verdict path |
| S14 forced session, no label | fail-closed | attribution UNKNOWN, authorized=false, caution requires governance act / explicit label |
| S15 exit contract | — | all verdicts exit 0; bad flag → 64 |
| **S16 V-3 boundary — the ONLY raw derivation** | PO/ARB cond. 2 | build ACTIVE lane via mechanism; **poison the raw JSON** with decoy top-level `mutationOwner`, `workItemState`, and a fake `sessions.<lane>.state=COMPLETED`/`role=decoy`; assert bootstrap reports the **fold-derived** state/owner/role/itemState (ACTIVE, this lane), NOT the decoys; and the handoff fields (`predecessor_handoff_present`, `predecessor_handoff_token_ref`) DO come through — proving raw access is the handoff fact and nothing else |
| **S17 cross-provider conformance — byte-identical** | PO/ARB cond. 3 | same repo/records/`--process-label`/work-item/CLI; run once with `ANTHROPIC_MODEL=claude-sonnet-4-6` + Claude-shaped base URL, once with `ANTHROPIC_MODEL=deepseek-chat` + DeepSeek-shaped base URL + token; **assert raw JSON stdout byte-identical** — the harness, not the provider endpoint, determines the result |

4. **Governed docs** (placement derived: `php scripts/doc-placement.php` → product-specific·knowledgeos → `docs/knowledgeos`):
   - `docs/knowledgeos/reviews/2026-08-22-KOS-SESSION-BOOTSTRAP-001-implementation-boundary-proposal.md` — canonical rule text the harness pointers reference (rules-live-once); records the PO/ARB act + scope + non-actions.
   - `docs/knowledgeos/reviews/2026-08-22-KOS-SESSION-BOOTSTRAP-001-diagnostic-and-activation-registration.md` — the observed AMBIGUOUS reproduction, comparison matrix (Claude vs DeepSeek — identical state/context; only the model endpoint differs), lawful routes, activation act.
   - `docs/knowledgeos/governance/2026-08-22-KOS-SESSION-BOOTSTRAP-001-session-completion.md` — Session Completion Report per `SESSION-COMPLETION-HANDOFF-PROTOCOL.md` v1.1 (YAML `session_completion`/`next_actor`/`authorization`; F1 capability≠authorization; recommended next actor from the deterministic table).
   - **Dev guide** (Definition of Done): one step under `developer_guide/workflow_engine/` + update its `00_index.md`.
5. **Harness pointers** (one line each; pointer only, canonical = boundary doc):
   - `.claude/CLAUDE.md` (governed-session startup area) — run `session-bootstrap.php` at start (ON_DEMAND); consume `bootstrapping_status` before acting; UNRESOLVED/AMBIGUOUS → STOP, read-only, escalate to Governance.
   - `AGENTS.md` — same pointer (peer harness; no duplicate script).
   - `.codex/README.md` — same pointer (`.codex/` adds no hooks/scripts).
6. **`docs/knowledgeos/backlog/EKS-07-multi-process-coordination.md`** — append-only addendum: the PO/ARB activation act + scope; live reproduction as evidence; explicit statement that AST-017 is a **minimal operational correction, NOT an EKS-07 implementation** (EKS-07 remains FUTURE ARCHITECTURE EXPLORATION); V-3 partial-remedy note.
7. **`.claude/CONTEXT.md` + `.claude/sessions/2026-08-22.md`** — session state + log (End-of-Commission checklist).

**⛔ NOT modified this increment:** `inject-context.sh` and `.claude/settings.json` (V-3 SESSION_START prohibition; ON_DEMAND + pointer-only), `workflow-state.php`, `session-resolve.php`, migration plan, DV-1…DV-7 / RV-1…RV-7 findings/dispositions, migration authority, Phase 3 / Phase 5, human authority rules, `.gitignore`/`.gitattributes`, executionContext content.

---

## Verification

1. **Deterministic + provider-independent by construction:** the binary makes no model call — both provider paths consume identical PHP + identical records, so resolution is identical. Pinned by `test_s8` (byte-identical output) **and by `test_s17` (cross-provider conformance — the explicit acceptance criterion: Claude-shaped env run ≡ DeepSeek-shaped env run, byte-identical JSON)**. This session (DeepSeek-backed env) exercises the DeepSeek path; a Claude-backed session resolves identically.
2. **Live read-only check against the real record** (no writes):
   - `php .claude/scripts/session-bootstrap.php --work-item=KOS-AIP-GOV-STATE-DURABILITY-ADR --process-label=8a525719 --json` → **UNRESOLVED** (no lane references `8a525719`) with actionable message + lawful routes.
   - `php .claude/scripts/session-bootstrap.php --work-item=KOS-AIP-GOV-STATE-DURABILITY-ADR --process-label=a8ce5a39 --json` → **RESOLVED** lane `S5-architecture-dv-correction-review`, attribution MATCH (multi-label caveat — S5 references two labels), `workflow_state=HANDED_OFF`, authorized=false, can_continue=false, next actor from successor state.
   - `git status` before/after: `.claude/runtime/workflow/` untouched (read purity on the live store).
3. **Unit suite:** `vendor/bin/phpunit tests/Unit/Platform/WorkflowEngine/SessionBootstrapContractTest.php` (S1–S17), then re-run `SessionAssignmentResolverContractTest.php` + `WorkflowStateRecordContractTest.php` to prove AST-015/AST-016 untouched (byte-identical, no code change).
4. **Read-purity by source:** grep the new script for write calls (`file_put_contents`, `mkdir`, `rename`, `unlink` absent). **V-3 boundary by source:** the script's ONLY `file_get_contents`/`json_decode` on a record is inside the named `v3HandoffRead()` function, cited to the finding.
5. **Governance ceremony:** record the PO/ARB act + scope in the boundary doc. Flip AST-017 registry `adoption: planned → verify` (NOT `adopted`) — the `notes` line records implementation + self-verification; adoption deliberately awaits the governance path / independent verification. No migration/DV/RV artifact touched.
6. **Registry consistency:** AST-017 five-question trace present; `runtime_moment_enum` = `ON_DEMAND`.

## Commit discipline (standing constraints)

- Stage **only** the files of each slice — never `git add -A`.
- Suggested slices: (1) registry-first `registry.yaml`; (2) RED test file; (3) GREEN script + dev guide; (4) governed docs (boundary, diagnostic+activation, EKS-07 addendum); (5) harness pointers; (6) completion report + CONTEXT + session log.
- Subject carries the work-item ID where it drives the change (`(KOS-SESSION-BOOTSTRAP-001)`); `docs(knowledgeos): …` convention for docs slices; `Co-Authored-By: Claude <noreply@anthropic.com>` on every commit.

## Risks & STOP conditions (record `EKS-07 FOLLOW-UP` and stop on any)

- V-3 bounded read tension with the Single Authority Resolver invariant → kept to the ONE handoff fact, documented as the V-3 resolution; the clean alternative (an AST-015 read command) is a **separate governed slice** → recorded as follow-up, not done here.
- Prose `executionContext` (S5 references two labels) → `process_labels_referenced` + `attribution` evidence-only; fail closed on any multi-label/MISMATCH/UNKNOWN; never grants from identity (INV-ATTR-1).
- Second-fold regression (EKS-08 §1b) → no fold in AST-017; all state from `askMechanism(fold)`; V-3 read pinned by S2b.
- Auto-wiring creep (V-3 binding) → ON_DEMAND + pointer-only; wiring is a fresh governed slice after the V-3 full remedy.
- **STOP triggers:** needing to weaken any governance rule; needing a second engine/identity/role/authority/completion model; the V-3 full remedy becoming necessary *inside this increment*; any change to migration/DV/RV artifacts, `.gitignore`, or `executionContext`; pressure toward SESSION_START wiring; or evidence that the failure is a governance-rule gap rather than a coordination/legibility gap.

## Deliverables (mission)

Diagnostic report (comparison matrix) · AST-017 implementation · automated tests (S1–S17, incl. the V-3 boundary regression S16 and the cross-provider conformance S17) · Session Bootstrap contract/boundary doc · harness pointers · EKS-07 addendum · Session Completion Report with `next_actor` per the deterministic table · registry flip `planned → verify` (adoption NOT claimed). **STOP after verification** — do not auto-continue into migration governance.

---

## ✅ CLOSURE (2026-08-22) — all slices delivered, STOP honored

| Slice | Status | Commit |
|---|---|---|
| 1. Registry-first | done | `e850439a` |
| 2. RED test (S1–S17, +S2b) | done | `b05cca61` |
| 3. GREEN resolver + dev guide | done | `170e3052` |
| 4. Governed docs (boundary · diagnostic+activation · EKS-07 addendum · completion report) | done | `a71a1a42` |
| 5. Harness pointers (.claude/CLAUDE.md · AGENTS.md · .codex/README.md) | done | `0f1f7983` |
| 6. Ceremony: registry planned→verify + CONTEXT + session log | done | `6bb6eeec` |

**Verification:** contract suite **18/18 GREEN (210 assertions)** · full WorkflowEngine dir **47/47 (522)** — AST-015/AST-016 untouched (1 pre-existing deprecation in the untouched AST-016 sibling) · read purity by source + on live store · registry `planned → verify` (adoption NOT claimed, PO/ARB condition 1) · S16 (V-3 boundary) + S17 (cross-provider conformance) GREEN.

**⚠️ Recorded plan-prediction deviation (not a defect):** live check `--process-label=a8ce5a39` returned **AMBIGUOUS**, not the predicted RESOLVED S5 — because the real record references that label in **TWO** lanes (S5 **and** S6). The resolver correctly failed closed (P-3 no-silent-selection); explicit `--session=S5-architecture-dv-correction-review` yields the predicted RESOLVED S5 (HANDED_OFF, V-3 handoff fact read truthfully). This is the designed behavior and strengthens the fail-closed case.

**STOP honored:** no auto-continue into migration governance. `EKS-07` remains FUTURE ARCHITECTURE EXPLORATION (NOT solved). Recorded `EKS-07 FOLLOW-UP`: V-3 FULL remedy (AST-015 read command) · SESSION_START wiring (fresh slices). Next actor: **independent verification of AST-017** → governance path decides adoption.
