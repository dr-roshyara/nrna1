# 05 — Activating a Commissioned Fresh Session (AST-019), and the ownership-provenance defect class

> **A fresh session may self-bind its identity; it may never self-choose role,
> scope, work item, or authority.** `AST-019` binds a runtime identity to a
> commissioned responsibility through the canonical mechanics — `REGISTER` →
> `HANDOFF` → human `START`, all via `AST-015 append`.
>
> ⛔ **The sentence this guide exists for:** *ownership facts are derived from the
> authoritative fold, never hand-derived and never defaulted.* Getting that
> wrong twice — once by hand (`ASD-001`), once by a silent `null` (`F-1`) — is
> what produced `REPAIR-001`.

## Purpose

The developer how-to for `.claude/scripts/activate-commissioned-fresh-session.php`
(`AST-019`) after `REPAIR-001`: what the write path derives, why it derives it
that way, and how to write a test that can actually see a defect on it. Canonical
decision text lives once elsewhere — commission
`docs/knowledgeos/reviews/2026-08-22-KOS-OPERATING-MODEL-001-AMENDMENT-001-ActivateCommissionedFreshSession.md`,
verdict `…-2026-08-23-…-INDEPENDENT-VERIFICATION.md`, plan
`docs/plans/20260824-0058-KOS-OPERATING-MODEL-001-AMENDMENT-001-REPAIR-001-plan.md`.

## Where it fits

```
.claude/scripts/
  workflow-state.php                       → AST-015 · sole interpreter AND sole writer (UNTOUCHED)
  session-resolve.php                      → AST-016 · discovery-only resolver     (UNTOUCHED)
  session-bootstrap.php                    → AST-017 · read-only declaration       (UNTOUCHED)
  next-actor-orchestration.php             → AST-018 · authoritative commission    (UNTOUCHED)
  activate-commissioned-fresh-session.php  → AST-019 · THIS guide's subject
tests/Unit/Platform/WorkflowEngine/
  ActivateCommissionedFreshSessionContractTest.php   → GO-01…GO-25 (commission) + GO-26…GO-30 (REPAIR-001)
```

`AST-019` owns **no** workflow knowledge. It invokes the qualified mechanisms as
subprocesses and consumes what they report: one fold from `AST-015`, one
declaration from `AST-017`, one commission from `AST-018`.

## How it works

```
analyze()                                   writeActivation()
  V1 runtime identity (env only)              REGISTER { predecessor: fold.mutationOwner }
  V2 fold  ── AST-015 ─────────┐              HANDOFF  { from:        fold.mutationOwner }
  V3 role declared             │              START    { humanAct, recordedBy: human }
  V5a eligibility              │              re-fold → verify ACTIVE + owner, never assume
  V5b declaration ── AST-017   │
  commission     ── AST-018    │
  V8 human act (G-3)           │
  return [... 'fold' => $fold ]┘  ← the REPAIR-001 data-flow fix (F-1)
```

The fold travels **from the analysis to the write** because it is the same
observed fact the analysis validated. It is not re-folded downstream: one fold
per analysis keeps `AST-015` the single interpreter. If ownership moves between
analysis and write, `AST-015`'s `HANDOFF` guard refuses and the sequence reports
`INCOMPLETE_SEQUENCE` — fail-closed by design, not by accident.

## Design decisions

| # | Decision | Why |
|---|---|---|
| D-1 | `analyze()` returns `'fold' => $fold` | `writeActivation()` already read `$a['fold']`; the key never existed, so `$owner` was silently `null` (`F-1`). Passing the observed fold forward is a data-flow repair, not a redesign. |
| — | **Rejected:** re-fold inside `writeActivation()` | That is a *second fold*. `AST-015` is the sole interpreter; a second read would also silently change the concurrency semantics. |
| D-2 | `incompleteSequence()` returns `'ok' => false` | The docblock promised `array{ok:bool,…}` and the caller tests `$written['ok']`. It worked only because a missing key reads as falsy — one strict-mode refactor away from reporting a partial write as a full activation (`F-3`). |
| D-3 | `transitionWritten` derived (`$written !== []`) at **both** sites | A governance-record field must state what was persisted, not what was hoped (`F-4`). |
| D-4 | No warning suppression for `F-2` | The `--json` contract broke because of two undefined-key reads. Removing the reads removes the warnings; `@`, output buffering or `ini_set` would have hidden the defect instead. |

## The pitfall this subsystem keeps producing

**Ownership provenance.** Twice now, an appointment/activation path has been
wrong about who the predecessor was:

- `ASD-001` — a Governance process hand-composed `REGISTER`/`HANDOFF` instead of
  using `AST-018 appoint`, which derives `predecessor`/`from` from the fold.
- `F-1` — `AST-019` *attempted* to derive them and silently didn't.

Same defect class, opposite directions. When you touch any code that writes a
`predecessor` or a `HANDOFF.from`, the question to ask is not *"is it set?"* but
**"which recorded fact is it derived from, and what happens if that fact is
absent?"** A `?? null` on an ownership field is a defect, not a default.

## Testing: how to write a test that can see it

`GO-01…GO-25` were all green and structurally **could not** detect `F-1` (`O-1`).
Every fixture used an empty item, a first lane, or a `COMPLETED` predecessor —
and in `AST-015`'s reducer both `COMPLETE` and `HANDOFF` clear `$owner`. The
write path was only ever exercised where the owner is legitimately `null`.

**`FAIL` and `CANCEL` do not clear the owner.** That is the seam:

```php
$this->record($wi, 'IMPL', 'implementation', 'COMPLETED', 'IMPLARM'); // COMPLETE clears the owner
$this->recordLane($wi, 'OWNER', 'architecture', 'FAILED');            // START→FAIL keeps it
// ⇒ mutationOwner = OWNER · no ACTIVE/CREATED/HANDED_OFF lane · AST-018: NEXT_ACTOR_REQUIRED
```

Anything else is refused earlier (`ACTIVE`/`CREATED`/`HANDED_OFF` → `GO-13`,
`STOPPED` → `GO-21`) and never reaches the write path.

Two harness notes worth keeping:

- **`display_errors` is an environment default, so assert under it.** `GO-29`/`GO-30`
  run the capability with `-d display_errors=1`. On a host with `display_errors=Off`
  a PHP warning goes to STDERR and the suite stays green while the `--json`
  contract is broken for every consumer on a normal dev/CI box.
- **Identify the post-write fold by content, not by call order.** `AST-017` also
  honours `KOS_MECHANISM_PATH`, so a proxy stub sees folds from the bootstrap too.
  `GO-27` doctors the fold that reports the fresh identity as `ACTIVE` — the only
  one that can be the post-write verification.

## Status — do not overstate it

`AST-019` is **IMPLEMENTED** and **NOT VERIFIED · NOT ADOPTED · NOT AUTHORIZED**.
`REPAIR-001` was authorized to implement; authorization is not adoption and a
repair is not a verdict. Re-verification is a separate, independent act, barred
to every process that produced, scoped, verified or repaired this asset.

**Traceability:** `G-REPAIR-001` · `KOS-OPERATING-MODEL-001-AMENDMENT-001` · `AST-019`
· plan `docs/plans/20260824-0058-KOS-OPERATING-MODEL-001-AMENDMENT-001-REPAIR-001-plan.md`
· `…-INDEPENDENT-VERIFICATION.md` (`F-1`…`F-5`, `O-1`) · `…-APPOINTMENT-SEQUENCING-DEFECT-001.md`
· `GO-26`…`GO-30` · `Inv B`/`Inv C`/`Inv D`/`Inv E` · `G-3` · `R-34`/`EP-02`
