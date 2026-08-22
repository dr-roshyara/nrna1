# 06 — ActivateCommissionedFreshSession: binding a fresh session to a commissioned responsibility

## Purpose

A fresh session arrives with a **runtime identity** (`CLAUDE_CODE_SESSION_ID`) but no governed lane. The
old rule said *"a session must never register itself"* — which left the human unable to act when a new
session was the correct next actor and the human had already commissioned that responsibility. This guide
covers **`AST-019`**, the capability that closes that gap: it lets a **fresh** session bind its own
runtime identity to a responsibility **already established** by the human's business instruction or an
existing governed commission — through the canonical mechanism.

**Unified binding model (PO/ARB 2026-08-22):**

| Declaration | Declared by | Mechanically |
|---|---|---|
| intended responsibility (work item + role) | **HUMAN** | `--work-item` + `--requested-role` (+ `--human-act`) |
| process identity | **RUNTIME** (never the human, never a CLI arg) | `CLAUDE_CODE_SESSION_ID` env |
| the binding | the governed bootstrap | REGISTER → HANDOFF → START via AST-015 `append` |

The division of labour it completes:

| Asset | Answers | Writes? |
|---|---|---|
| `AST-015` `workflow-state.php` | *What is recorded, and is this write legal?* — single workflow authority | yes (the ONLY writer) |
| `AST-017` `session-bootstrap.php` | *What is the current governed situation?* — read-only fresh-session declaration | no |
| `AST-018` `next-actor-orchestration.php` | *Given the human decision, what governed action should be executed next?* | appoint/stop only |
| **`AST-019` `activate-commissioned-fresh-session.php` (this asset)** | ***May a fresh session bind its runtime identity to this responsibility, and is it now active?*** | **REGISTER→HANDOFF→START only, through AST-015 `append`** |

## Where it fits

- **Layer:** Runtime Platform (`.claude/scripts/`), component `CMP-004` (workflow_engine), its **fifth** implementation asset.
- **Authority:** none of its own. Every workflow fact comes from a subprocess — AST-015 (fold/append), AST-017 (fresh declaration), AST-018 (commission). It knows **no store path**, performs **no local fold**, and writes **only** through AST-015 `append`.
- **Adoption:** registry entry is `verify`. **Implemented is not adopted** — independent verification and the governance path come first (R-34/EP-02).

## Key files

| File | Role |
|---|---|
| `.claude/scripts/activate-commissioned-fresh-session.php` | the capability (AST-019) |
| `tests/Unit/Platform/WorkflowEngine/ActivateCommissionedFreshSessionContractTest.php` | the contract (`test_go_*`, GO-01…GO-25) |
| `docs/plans/20260822-2309-activate-commissioned-fresh-session-plan.md` | the EP-01 plan (design, V1–V10, D-1…D-8) |
| `docs/knowledgeos/reviews/2026-08-22-KOS-OPERATING-MODEL-001-AMENDMENT-001-ActivateCommissionedFreshSession.md` | the L1 amendment record |
| `.claude/platform/registry.yaml` | AST-019 registry entry (`adoption: verify`) |

## How it works

Two subcommands:

```
php .claude/scripts/activate-commissioned-fresh-session.php activate \
      --work-item=<id> --requested-role=<role> \
      [--human-act=<verbatim human business instruction>] \
      [--exclude=<barred identity>]... [--dir=<records>] [--json] [--show-mechanics]
php .claude/scripts/activate-commissioned-fresh-session.php check \
      --work-item=<id> --requested-role=<role> [--human-act=<verbatim>] \
      [--exclude=<barred identity>]... [--dir=<records>] [--json]
```

- **`activate`** = the write path. **`check`** = the identical validation phase, read-only (`transitionWritten:false`).
- **Identity is NEVER a CLI arg** — read from `CLAUDE_CODE_SESSION_ID` env only; any `--session=` is rejected by the generic unknown-option path (exit 64).
- **`--requested-role`** = the role the session's prompt claims (the "prompt" side of prompt≠commission).
- **`--human-act`** = the recorded human business instruction (G-3), required for any write.
- **Exit codes:** `0` activated / produced check report · `64` usage · `65` refused / INCOMPLETE_SEQUENCE.

### Validation order (fail-closed; nothing written on any refusal)

```
V1  identity present                (env, never CLI)                 → NO_RUNTIME_IDENTITY
V2  work item exists                (AST-015 fold)                  → WORK_ITEM_UNKNOWN / _UNREADABLE
V3  requested role ∈ declared roles (fold.roles)                    → ROLE_UNDECLARED
V5a eligibility / independence      (fold session keys ∪ --exclude) → NOT_ELIGIBLE
V5b fresh-session declaration       (AST-017 must be UNRESOLVED)    → NOT_ELIGIBLE / AMBIGUOUS_COMMISSION
V4  requested role == commission    (AST-018 next-actor)            → MISMATCH
V6  no conflicting assignment       (LANE_ACTIVE / ACTIVATION_PENDING) → CONFLICTING_ASSIGNMENT
V7  workflow legality               (STOPPED is an honest blocker)  → WORK_ITEM_STOPPED
V8  human order exists              (--human-act non-empty)         → HUMAN_DECISION_REQUIRED
V10 four-state separation           (reports ACTIVATED, never ADOPTED/AUTHORIZED)
```

The order matters: **V5a runs before commission resolution** so a barred identity is `NOT_ELIGIBLE`
(GO-12), never a conflicting-assignment, and **V3 runs before the commission** so an undeclared role is
`ROLE_UNDECLARED` (GO-06).

### Commission resolution (the authoritative role source)

The commission is AST-018 `next-actor` (read-only):

- `NEXT_ACTOR_REQUIRED` (role=`verification`) → require `--requested-role=verification`, else **MISMATCH → STOP**.
- `HUMAN_DECISION_REQUIRED` + **empty session set** → **first binding**: the commission *is* the human
  business order; the requested role must be **named** in `--human-act`
  (`"I want Governance Engineer."` ⇒ requested-role `governance`). This is the mechanical encoding of
  prompt≠commission for the first session.
- `HUMAN_DECISION_REQUIRED` + `DECIDE` option → **ADOPTION_IS_HUMAN_DECISION** — the adoption decision is
  a person's; no fresh binding implied.
- `WORK_ITEM_STOPPED` → exit 65, honest Inv-E blocker — **the capability never writes CONTINUATION**.
- `LANE_ACTIVE` / `ACTIVATION_PENDING` → `CONFLICTING_ASSIGNMENT`.
- `AMBIGUOUS` → `AMBIGUOUS_COMMISSION`, governance escalation.

### The write path (the ONLY allowed write)

Through AST-015 `append`:

1. **REGISTER** `{type, session:<identity>, role:<requested-role>, predecessor:<fold.mutationOwner|null>, executionContext:<canonical string>, recordedBy:'governance'}` — the `predecessor` **key must exist** (`null` is a valid value); `executionContext` embeds `claude-code-session:<identity>` **followed by whitespace, never punctuation** (the N-16 pitfall — a trailing `.` would be captured into the id by AST-017's extractor).
2. **HANDOFF** `{type, from:<fold.mutationOwner|null>, to:<identity>, token:'T-<WORKITEM>-<ROLE>', tokenRef:'Recorded human business order: <humanAct>', recordedBy:'governance'}` — `from: null` is valid only while `mutationOwner === null`.
3. **START** `{type, session:<identity>, humanAct:<verbatim>, recordedBy:'human'}` — requires the fold to already have `handoffsTo[session]` true (G-3 conjunction).

Then **verify** the outcome from the authoritative fold (`state === 'ACTIVE'`, `mutationOwner === identity`)
— never assume. A mid-sequence failure is honestly reported as **INCOMPLETE_SEQUENCE** (what was written +
who acts next), exit 65.

## How to use it

As a fresh session with a commission, run `activate` with the role the commission names and your human
instruction. The capability discovers your identity from the environment:

```bash
CLAUDE_CODE_SESSION_ID=go-series-id php .claude/scripts/activate-commissioned-fresh-session.php activate \
  --work-item=WI-001 --requested-role=verification \
  --human-act='I want a Verification session for WI-001.' --json
```

The human-facing (non-`--json`) rendering is pure business language — no `REGISTER`/`HANDOFF`/`START`,
no mutation owners, no UUIDs, no `claude-code-session:` tokens (GO-19). `--show-mechanics` is the only
escape hatch.

## Testing

`php artisan test --filter=ActivateCommissionedFreshSessionContractTest` — GO-01..GO-25 (first binding,
identity discovery, subsequent commissions, the Governance-adoption-review case GO-05, prompt≠commission
MISMATCH, eligibility bars, source-inspection for the canonical-writer constraint, read-only byte-purity
for AST-017/AST-018/operating-model, determinism, provider-independence). All fixtures are built **through
AST-015** into hermetic temp dirs — never hand-written JSON.

## Pitfalls

1. **`--session=` / `--identity=` are rejected** — identity is evidence the runtime declares; a CLI-supplied
   identity is a spoofing vector and is refused (GO-02).
2. **A STOPPED work item is not a bypass** — the capability refuses (exit 65) and never writes
   CONTINUATION; reopening requires governance/human (Inv E).
3. **prompt ≠ commission** — the requested role must match AST-018 `next-actor`'s commissioned role (or be
   named in the human business order for a first binding). A mismatch is a hard STOP, not a hint.
4. **`claude-code-session:<id>` must be followed by whitespace** in any executionContext you construct —
   a trailing period becomes part of the id and silently breaks attribution (the N-16 pitfall).
5. **The capability must never become a second appointment engine** — it consumes AST-018 read-only
   (`next-actor`), never `appoint`, and never writes CONTINUATION (GO-17/GO-21).
6. **Separation of duties** — an identity already holding a lane on the work item is `NOT_ELIGIBLE`
   (producer/verifier/correction-author bars; R-34/EP-02 extended).

## Traceability

Work item `KOS-OPERATING-MODEL-001` · amendment `G-KOS-OPERATING-MODEL-001-AMENDMENT-001` · EP-01 plan
`docs/plans/20260822-2309-activate-commissioned-fresh-session-plan.md` · L1 amendment
`docs/knowledgeos/reviews/2026-08-22-KOS-OPERATING-MODEL-001-AMENDMENT-001-ActivateCommissionedFreshSession.md`
· registry `.claude/platform/registry.yaml` AST-019 (`adoption: verify`) · contract GO-01..GO-25
(`ActivateCommissionedFreshSessionContractTest`, 25 passed / 270 assertions) · AST-015/016/017/018 +
`operating-model.php` byte-unchanged · G-3 · Inv C/D/E · R8 · R-34/EP-02 · INV-ATTR-1/2 · ES-005.4 · P-3
