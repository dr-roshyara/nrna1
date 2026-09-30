# `KOS-CONTRACT-NEUTRALITY-001` — D-1 incorporation: registered

**Date:** 2026-09-28 · **Decision:** Option A — formally incorporate `D-1` now, made by the
human, on `2026-09-28-KOS-D1-governance-decision-preparation.md`'s presented options.
**Recorded by:** `claude-code-session:e8f324f1-...` (executing the decision, not making it).

---

## What was done

`scripts/observations/examples/lcom4/expected.json._variant_decisions_pinned` gained one new
key, `d1_computed_method_name_representation`, immediately after `intra_class_calls` (the key
it directly extends). **Exactly the scope the decision-preparation document's §3 described —
nothing broader:**

- Names the `IndeterminateBehaviourReference` representation and its no-field schema.
- States the rule is fulfilling, not changing, `intra_class_calls`'s existing exclusion text.
- Cites the real-corpus evidence (`DebugVoterSlug.php`; `pdb.py`, `pydoc.py`).
- **Explicitly excludes `D-4`/`D-5`** from this incorporation — states plainly that the
  callable-array form has no adopted representation and no genuine occurrence found, so
  nothing about it is incorporated here. This boundary was drawn deliberately, matching the
  human's own decision scope ("D-1 now"), not `D-4`/`D-5` by omission-as-accident.

**JSON validity confirmed** (`python3 -m json.tool`). **Full Cohesion suite re-run: 188/188
green**, unchanged from before this edit — this was a prose-only addition; no fixture value
key was touched, so no test outcome could change, and none did.

## What this does not do

Does not modify any code, adapter, `EdgeRules`, test, or fixture value. Does not open the
`G-KOS-CONTRACT-ARTIFACT-UPDATE` gate's machine-tracked workflow state (if any such state
exists in `.claude/runtime/workflow` — not inspected or transitioned by this act; this
document records a contract-text change only, not a workflow-state transition, which is a
separate governed mechanism this act does not attempt). Does not re-open `D-4`/`D-5`, `L3`
parity, or `LCOM4` research.

## Consequence

Per `2026-09-28-KOS-D1-governance-decision-preparation.md` §8 (Option A's stated
consequence): golden-fixture expected-evidence for `D-1` is now describable against a
governed text, not only against unit-test behavior. Whether to actually author such a
fixture is separate, future, unauthorized-by-this-act work.

---

**Traceability:** `2026-09-28-KOS-D1-governance-decision-preparation.md` (the options this
executes) · `2026-09-28-KOS-D1-implementation-results.md` (the evidence cited) ·
`2026-09-28-KOS-D4-characterization-closure.md` (why `D-4`/`D-5` are explicitly excluded) ·
`scripts/observations/examples/lcom4/expected.json` (diff: +1 line, one new pinned key) ·
commits `0727c9342`, `52783467a` (the implementation this text now describes).
