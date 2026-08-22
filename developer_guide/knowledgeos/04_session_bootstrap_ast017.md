# 04 — Session Bootstrap & Responsibility Resolution (AST-017)

> **A governed AI session must be able to resolve its own registered lane / role /
> workflow state / authority deterministically at session start.** The bootstrap
> answers that — one read-only command, one machine-readable report — and it
> deliberately does **not** weaken a single governance rule.
>
> ⛔ **The sentence every AST-017 artifact carries:** *Resolution is not
> activation.* The report creates no authority, no ownership, no state change —
> G-3 gates are untouched.

## Purpose

Explain `.claude/scripts/session-bootstrap.php` (AST-017) — the command, the
six-way block schema, the single V-3 bounded read, the fail-closed verdicts, and
how a Claude/DeepSeek harness consumes it at session start. This is the
**developer how-to**; the canonical rule text lives once in
`docs/knowledgeos/reviews/2026-08-22-KOS-SESSION-BOOTSTRAP-001-implementation-boundary-proposal.md`,
and the observed defect it corrects lives in `docs/knowledgeos/backlog/EKS-07-multi-process-coordination.md`.

## Where it fits

```
.claude/scripts/
  workflow-state.php        → AST-015 — the AUTHORITATIVE workflow interpreter (UNTOUCHED)
  session-resolve.php       → AST-016 — discovery-only resolver (UNTOUCHED)
  session-bootstrap.php     → AST-017 — THIS guide's subject: read-only bootstrap + responsibility resolution
.claude/platform/registry.yaml          → CMP-004 registry; AST-017 registered `planned` → `verify`
tests/Unit/Platform/WorkflowEngine/
  SessionBootstrapContractTest.php      → S-1…S-17 (hermetic, RED-by-absence discipline)
```

**Delegation is the contract (AMENDMENT 2).** AST-017 holds no fold loop, no
transition state machine, no state derivation. Every workflow fact comes from
AST-015 invoked as a subprocess (`fold` / `identity` / `authorized`). If the
mechanism is unavailable, the bootstrap **cannot** determine workflow state and
fails closed (`UNRESOLVABLE`) — it never falls back to reading records itself.

**The one bounded exception (V-3).** Open finding V-3: no AST-015 read command
exposes the predecessor-handoff fact. AST-017 performs exactly one raw read, in
the named function `v3HandoffRead()`, for **its own consumption** — the single
HANDOFF-to/from-lane fact (presence + token/tokenRef + successor target). It
derives **nothing else** from raw JSON. Regression S-16 poisons the raw record
with decoy `mutationOwner` / `workItemState` / `sessions.*` fields and asserts the
bootstrap reports the fold-derived truth — proving raw access is the handoff fact
and nothing else. The FULL remedy (an AST-015 read command) is EKS-07 FOLLOW-UP,
recorded, not done here.

## Key files

| File | Role |
|---|---|
| `.claude/scripts/session-bootstrap.php` | the resolver (read-only) |
| `.claude/scripts/workflow-state.php` | the qualified mechanism it delegates to |
| `tests/Unit/Platform/WorkflowEngine/SessionBootstrapContractTest.php` | S-1…S-17 contract suite |
| `.claude/platform/registry.yaml` | registry-first home (AST-017 entry, AST-016 V-3 annotation) |

## Design decisions

- **Six-way separation, never collapsed.** `identity ≠ role ≠ eligibility ≠
  authorization ≠ ownership ≠ continuation` — each is a first-class block of the
  report. Identity is **evidence-only** (`INV-ATTR-1`): reported, never a grant
  input (`INV-ATTR-2` — self-declared until attested).
- **Fail-closed by construction.** AMBIGUOUS / UNRESOLVED / UNRESOLVABLE all exit
  `0` with `operable=false`, `authorized_to_act=false`,
  `current_session_can_continue=false`, and `meta.unresolved_message` naming the
  missing fact · its source · the responsible next actor.
- **Attribution gates authorization, never discovers.** The process label
  (`--process-label` or `CLAUDE_CODE_SESSION_ID`) is matched against the lane's
  registered `executionContext` labels. MISMATCH/UNKNOWN ⇒ no authorization,
  routed to a Governance act. The bootstrap **never adopts another process's
  identity**.
- **G-3 conjunction surfaced truthfully.** `missing_for_start` lists exactly the
  ABSENT conjuncts of START (predecessor HANDOFF with token+tokenRef · recorded
  human START act). A recorded handoff is never listed as missing (S-2b).
- **Scope coverage is a gate, not a suggestion (R6/D-2).** `--scope` must be
  passed for grant-scoped work; without it, `authorized_within_scope` is UNKNOWN
  and `authorized_to_act` is lane-continuation semantics only — an honest
  first-class answer, never an error.
- **ON_DEMAND, pointer-only.** AST-017 is registered with
  `runtime_moments: [ON_DEMAND]` and is **not** wired into SESSION_START — that
  wiring is a fresh governed slice after the V-3 full remedy (V-3 binding).
- **Adoption not claimed.** Registry stays `planned → verify` at slice close;
  adoption follows the governance path after independent verification (PO/ARB
  condition).

## How it works

The command:

```bash
php .claude/scripts/session-bootstrap.php \
    --process-label=8a525719 --work-item=KOS-AIP-GOV-STATE-DURABILITY-ADR --json
```

Steps, in order:

1. **Parse args** (`--dir` · `--work-item` · `--process-label` · `--session` ·
   `--role` · `--scope` · `--json`). Unknown option ⇒ exit `64`.
2. **Fold each record THROUGH the mechanism** — `askMechanism(fold <wi>)`; a
   non-zero exit from the mechanism means *not interpretable*, never hand-derived.
3. **Select the lane.** Explicit `--session` → that lane (never a silent pick if
   the id occurs in >1 record); otherwise token-scan each candidate's registered
   labels for the current process label: 0 → UNRESOLVED, >1 → AMBIGUOUS, 1 →
   RESOLVED.
4. **Attribution.** `MATCH` / `MISMATCH` / `UNKNOWN` — evidence-only.
5. **V-3 bounded read** — `v3HandoffRead()` answers only "is there a HANDOFF
   to/from this lane, with token + tokenRef?".
6. **Mechanism facts** — `identity` (authorizationLinkage) and, when `--scope` is
   given, `authorized` (scope-string equality).
7. **Derive gates & continuation** deterministically; assemble the six blocks.

The report shape (`--json`):

```json
{
  "verdict": "RESOLVED",
  "operable": true,
  "identity":        { "attribution": "MATCH", "process_labels_referenced": ["8a525719"], "..." : "..." },
  "assignment":      { "lane": "S5-...", "role": "architecture", "workflow_state": "HANDED_OFF", "..." : "..." },
  "activation_prerequisites": { "predecessor_handoff_present": true, "missing_for_start": [], "..." : "..." },
  "grant":           { "authorization_linkage": "G-...", "authorized_within_scope": null, "..." : "..." },
  "mutation_owner":  { "session": "S5-...", "is_this_lane": false },
  "gates":           { "authorized_to_act": false, "human_decision_required": true, "..." : "..." },
  "continuation":    { "current_session_can_continue": false, "recommended_next_actor": { "role": "...", "..." } },
  "meta":            { "candidates": [ "..." ], "interpreter": { "path": "...", "is_default": true }, "..." }
}
```

## How to use / extend

- **A governed session at start** runs the command (ON_DEMAND), consumes
  `bootstrapping_status`/`gates.authorized_to_act` before acting, and on any
  AMBIGUOUS/UNRESOLVED/UNRESOLVABLE **STOPs, stays read-only, escalates to
  Governance** with the `unresolved_message`.
- **`--scope` is mandatory for grant-scoped work.** Without it the report is
  honest-but-incomplete on scope coverage.
- **Extending** means extending the *mechanism*, never adding a second fold to
  this script. If a new fact is needed, it belongs as an AST-015 read command —
  which is exactly the recorded EKS-07 FOLLOW-UP for V-3.

## Testing

`vendor/bin/phpunit tests/Unit/Platform/WorkflowEngine/SessionBootstrapContractTest.php`

- S-1…S-7 mission states → verdict/gates (T-1…T-7). S-8 determinism (byte-identical).
- S-9 read purity across all four verdicts. S-10…S-12 fail-closed paths.
- S-13 C-1 interpreter identity on every verdict. S-14 no-label → UNKNOWN.
- S-15 exit contract (all verdicts 0, usage 64).
- **S-16 the V-3 boundary regression** (PO/ARB condition 2): poison the raw
  record, assert fold-derived truth + handoff family only.
- **S-17 cross-provider conformance** (PO/ARB condition 3): Claude-shaped vs
  DeepSeek-shaped provider env, same repo/records/CLI, byte-identical JSON.

## Pitfalls

- **Never fold in this script.** A second fold is the EKS-08 §1b regression the
  delegation rule exists to prevent.
- **Never widen the V-3 read.** The moment a second fact is derived from raw JSON,
  the Single-Authority-Resolver invariant is broken and the boundary is gone.
- **Never invent a role.** `recommended_next_actor.role` comes from the declared
  role vocabulary or the human authority (`po/arb` / `governance`) — never a novel
  label.
- **`--scope` scope-string equality is exact.** A requested scope must match the
  grant's scope string byte-for-byte (R6/D-2).
- **Exit 0 ≠ authorized.** All four verdicts exit 0; `gates.authorized_to_act` is
  the gate, not the exit code.

**Traceability:** work item `KOS-SESSION-BOOTSTRAP-001` · PO/ARB plan approval
2026-08-22 (`.claude/plans/sequential-leaping-koala.md`, three binding conditions)
· boundary proposal (rules-live-once) · AST-017 registry entry (`planned`) · V-3
partial-remedy annotation on AST-016 `finding_open` · EKS-07 addendum · S-16/S-17
PO/ARB conditions · AMENDMENT 2 delegation · `INV-ATTR-1/2` · `G-3` · `R6` · `D-2`
· `C-1`/`C-2` · `ES-004.3` · Definition-of-Done developer guide · placement:
`scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`, exit 0)
