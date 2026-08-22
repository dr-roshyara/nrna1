# 05 — The Operating Model: business language on top of governed mechanics

## Purpose

A person running this platform speaks **business language** — *"the next step requires a fresh session"*,
*"I need your permission"*, *"the work is ready for adoption."* They should **never** have to read
`REGISTER`/`HANDOFF`/`START`, mutation owners, predecessors, session UUIDs, or transition JSON. This guide
covers **`KOS-OPERATING-MODEL-001`**, the human-facing operating layer that renders the exactly-one business
outcome (§11 of the commission) and the six human cases (§29) over the existing workflow estate.

The division of labour:

| Asset | Answers | Writes? |
|---|---|---|
| `AST-015` `workflow-state.php` | *What is recorded, and is this write legal?* — single workflow authority | yes |
| `AST-017` `session-bootstrap.php` | *What is the current governed situation?* — read-only | no |
| `AST-018` `next-actor-orchestration.php` | *Given the human decision, what governed action should now be executed?* | appoint/stop only |
| **`operating-model.php` (this asset)** | ***What is the one business outcome, in business language, for this human?*** | **no — read-only presenter** |

The five business outcomes are a **rendering** of AST-018's `result` values — never a second state
vocabulary, never a second workflow engine.

## Where it fits

- **Layer:** Runtime Platform (`.claude/scripts/`), component `CMP-004` (workflow_engine), the **fifth** implementation asset — the operating model's minimal supporting capability.
- **Authority:** none. It delegates every decision to AST-018 / AST-017 as subprocesses. It knows **no store path**, performs **no fold**, and has **no write path**.
- **Status:** **IMPLEMENTED — NOT VERIFIED — NOT ADOPTED** (work item `KOS-OPERATING-MODEL-001`; independent verification and the governance path come first, R-34/EP-02).

## Key files

| File | Role |
|---|---|
| `.claude/scripts/operating-model.php` | the asset (CMP-004) |
| `docs/knowledgeos/governance/2026-08-22-KOS-OPERATING-MODEL-001-final-operating-model.md` | the operating model — **the primary deliverable** (40 sections) |
| `docs/knowledgeos/reviews/2026-08-22-KOS-OPERATING-MODEL-001-implementation-prompt.md` | the commission, verbatim |
| `docs/plans/20260822-2126-kos-operating-model-001-implementation-plan.md` | the operational breakdown (D-1…D-9) |
| `tests/Unit/Platform/WorkflowEngine/OperatingModelContractTest.php` | the contract (`test_om_*`, OM-1…OM-36 + human cases) |
| `docs/knowledgeos/reviews/…-KOS-OPERATING-MODEL-001-APPOINTMENT-registration.md` / `…-commission-registration.md` | governance registrations |

## How it works

```
php .claude/scripts/operating-model.php <command> <workItem> [options]

outcome <workItem>   the exactly-one business outcome (§11) + the §29 case
session <workItem>   MATCH / MISMATCH / governance presentation (§18–§20)

options:  --dir=<records>       forwarded verbatim to the mechanisms (or their defaults)
          --process-label=<lbl> the process label to attribute (else CLAUDE_CODE_SESSION_ID)
          --json                machine-readable payload (default is human rendering)
          --show-mechanics      the ONLY way technical detail surfaces (§30, §35 diagnostics)

exit 0 = report produced · 64 = usage · 65 = a canonical mechanism refused
```

### The two commands

**`outcome`** calls AST-018 `next-actor`, then renders its `result` as exactly one business outcome:

| AST-018 `result` | Business outcome (§11) | §29 case | Human rendering |
|---|---|---|---|
| `LANE_ACTIVE` | `CONTINUE` | 1 | "I can perform the next step." |
| `ACTIVATION_PENDING` | `PERMISSION_REQUIRED` | 2 | "…but I need your permission." 1. Yes · 2. Write a prompt |
| `NEXT_ACTOR_REQUIRED` | `FRESH_SESSION_REQUIRED` | 3 | "The next step requires a fresh session." + `prepare-next-session` prompt, paste / edit |
| `HUMAN_DECISION_REQUIRED` (+`DECIDE`) | `GOVERNANCE_DECISION_REQUIRED` | 6 | "The work is ready for adoption." 1. Accept/Adopt · 2. Return · 3. Stop |
| `HUMAN_DECISION_REQUIRED` (other) | `GOVERNANCE_DECISION_REQUIRED` | 5 | "A Governance decision is required." + options |
| `AMBIGUOUS` | `GOVERNANCE_DECISION_REQUIRED` | 5 | same |
| `WORK_ITEM_STOPPED` | `STOP` | — (§35) | what happened · why · who must act next · options |

**`session`** calls AST-017 `session-bootstrap` + AST-018 `next-actor`, then maps **reported facts only**:
`verdict=RESOLVED + attribution=MATCH` → assigned session (MATCH · CASE 1/2); `attribution=MISMATCH` →
wrong session (MISMATCH · CASE 4 + recovery); unresolved + no existing appointment →
`GOVERNANCE_DECISION_REQUIRED` (CASE 5). **Candidate status is never inferred** — it rests on
authoritative eligibility/appointment facts that follow identity declaration.

### The rules it exists to protect

1. **The human never operates workflow mechanics** (§1, §29, §30). The paste prompt is embedded for the
   fresh-session case, but the human is never *required* to know or construct `REGISTER`/`HANDOFF`/`START`,
   a session UUID, `mutationOwner`, or `predecessor`. `--show-mechanics` is the only technical escape hatch.
2. **FRESH_SESSION_REQUIRED = prepare prompt + start a fresh session**, never an appointment invitation —
   the future process identity is not yet known. The human is never asked to "appoint and activate" here,
   and never asked for a session UUID.
3. **Human authority is an input, never an inference** (G-3). This script has **no write path**; every
   write (appointment, prompt preparation, stop) is an AST-018 command invoked directly by the human or the
   Communication Engineer. Nothing in the presenter stands in for a person saying yes.

## How to use / extend

- **Add a business-language phrase** → edit `renderHuman()` in `operating-model.php`; keep the JSON payload
  stable (the contract test pins both).
- **Add a rendered case** → only if a new §29 case is governed into existence. The five outcomes (§11) and
  six cases (§29) are the contract — do not invent new vocabulary.
- **Change what AST-018 returns** → that is an **AST-018 slice** change, never fixed here. This asset
  consumes AST-018; if the mechanism needs changing, record a follow-up on the AST-018 slice.

## Testing

```
./vendor/bin/phpunit --no-coverage tests/Unit/Platform/WorkflowEngine/OperatingModelContractTest.php
```

43 tests, `test_om_*` prefix (distinct from the `n/r/p/s/t/usage` suites). Highlights: OM-19/21 (no second
engine, no store path, no raw file access), OM-20 (human START recorded verbatim), OM-22 (AST-017 stays
read-only), OM-37 (all four canonical assets byte-unchanged vs HEAD), OM-HUMAN-01…05 (the human-facing
acceptance: continue / permission / paste-not-appointment / recovery / no-UUID).

## Pitfalls

- **Punctuation**: the fresh-session prompt must be delivered *between* the delimiters and followed by
  "You may use this prompt unchanged or edit it." — a test failure on `FRESH_SESSION_REQUIRED` almost always
  means the presenter dropped or reordered the prompt.
- **Inference creep**: never let the presenter claim a session is an *eligible candidate* from
  `UNRESOLVED + NEXT_ACTOR_REQUIRED`. Candidate status is a governance fact, not a derivation.
- **Writing by accident**: if you are tempted to add a write to this script, you are re-implementing
  AST-018. Stop and delegate.

**Traceability:** `KOS-OPERATING-MODEL-001` · implementation prompt (verbatim, 40 sections) · plan
`20260822-2126-kos-operating-model-001-implementation-plan.md` (D-1…D-9) · operating-model document
`…-final-operating-model.md` · `OperatingModelContractTest` · AST-015 · AST-017 · AST-018 · G-3 · P-3 ·
EP-02/R-34 · ES-004.3 · adopted six-role operating model (2026-08-19).
