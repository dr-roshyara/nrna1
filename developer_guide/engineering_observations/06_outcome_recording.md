# 06 — Outcome Recording (Phase 4)

## Purpose
Join a recommendation and its decision to metric reality N commits later:
baseline → current → delta. **Raw evidence only — the recorder never judges.**
"Did the outcome support the recommendation?" belongs to the ASSESSMENT stage
(deterministic first, statistical later), and that separation is pinned by test.

## Where it fits
The fourth contract in the chain: Observation → Recommendation → Decision →
Rationale → **Outcome** — the record type that references BOTH the
recommendation and the decision, enabling "accepted→improved?" and
"ignored→unnecessary?" questions later.

## Key files
- `scripts/observations/OutcomeRecorder.php` — pure `record(...)`; refuses `commits_since < 1` (premature evidence is noise)
- `scripts/observations/outcome-record.php` — runner: `php scripts/observations/outcome-record.php <REC-id>`
- `engineering/verification/observations/outcomes.jsonl` — append-only
- `tests/Unit/OutcomeRecorderTest.php` — 3 tests / 13 assertions (TDD-first; assessment-free pinned: no successful/status/improved/verdict keys possible)

## How it works
Looks up the recommendation → its latest decision → baseline from the lcom4
observation at-or-before issuance → re-collects the CURRENT value from the
subject's file → counts commits since the decision (`commit` field when
present; decision-ts fallback for older records) → appends the raw record.

## Honest limitations (v1)
- **R1 (LCOM4) recommendations only** — extend per rule on evidence of need.
- Subject renames/moves are not tracked (fails honestly, record manually).
- "Last decision wins" is a LOOKUP convenience here, not domain semantics — RD-7's exactly-once vs last-wins question remains open.

## Pitfalls
- Δ+0 after N commits is a REAL datum (the codebase moved, the subject didn't) — never treat it as a failed run.
- Never add a success/verdict field. Assessment is a separate stage — the test will fail you first.

## Traceability
Authorized 2026-08-04 ("Build Outcome Recording next") · first real record same
day: REC-eaf245474a · Election · 29 → 29 · Δ+0 after 1 commit — the first
structurally complete recommendation→decision→outcome chain.
