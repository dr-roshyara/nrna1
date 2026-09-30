# Phase 2 — Theory Construction Output

⛔ **EMPTY BY DESIGN. Step 2 has not been executed.**

Binding output location for every Step-2 artifact, per
`../prompts/knowledge_os_step2_theory_construction_protocol.md` §15.0.

## What Phase 2 produces

> **A research reconstruction package from which a book can later be generated.**
> ⛔ **Not the book.**

```
Phase 1  historical reconstruction   "what actually happened?"
Phase 2  theory construction         "what theory emerges?"   <- HERE
Phase 3  synthesis / exposition      "how is it explained?"   <- the book begins
Phase 4+ validation / canonicalization
```

`THEORY-SEED.md` is a **provisional scientific theory specification**, not a book.
`HISTORICAL-STORY.md` is the **historical narrative the theory emerged from**, not a book.

## Layout

See protocol §15.0b for the full tree. Top level:

`HISTORICAL-STORY.md` · `THEORY-SEED.md` · `THEORY-CONSTRUCTION.jsonl` ·
`THEORY-EVOLUTION.jsonl` · `COMPETING-INTERPRETATIONS.jsonl` ·
`ADVERSARIAL-FRAMING.jsonl` · `VERIFICATION-RESULTS.jsonl` · `FAILED-TESTS.jsonl` ·
`SEED-REVISIONS.jsonl` · `FORMALIZATION/` · `ARCHITECTURE/` · `GAP-ANALYSIS/` ·
`OPEN-QUESTIONS.md` · `RESEARCH-AGENDA.md` · `SURPRISE-DISCOVERIES.md` ·
`DISTANCE-ASSESSMENT.md` · `PROVENANCE-INDEX.jsonl` · `STEP2-CHECKPOINT.md` ·
`STEP2-STATE.json`

## ⚠️ Same-name files

`THEORY-OBJECTS.jsonl`, `THEORY-THREADS.jsonl`, `DERIVATION-INSTANCES.jsonl` and
`CONTRADICTIONS.jsonl` appear BOTH in `../` (Step 1) and here (Step 2).

| | `../` | here |
|---|---|---|
| Contains | found in the corpus | **constructed in Phase 2** |
| Level | L0/L1 | L2/L3/L4 |

Every file here carries `"origin": "STEP2"` and references Step-1 records by id —
never copies them (`Q26`). ⛔ Joining across the two without checking `origin`
fuses evidence with hypothesis.

## Boundary

Step 2 writes nothing outside this folder (`Q18`) and never writes a Step-1
artifact (`Q17`). Reads unrestricted. Deleting this folder restores a clean
Step-1 state with no residue.

**Start condition:** Class A prerequisites — today `RO-0014` alone.
