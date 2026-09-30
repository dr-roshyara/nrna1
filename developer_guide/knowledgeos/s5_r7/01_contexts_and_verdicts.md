# 01: Bounded contexts, the composer, and layered verdicts

## Purpose

Revision 7 turns S5 batch verification into a **composition of predicates** owned by separate bounded contexts, so that no single module decides "truth" (addendum §0, DR-13, DR-15).

## Where it fits

All modules sit under `docs/knowledgeos/chronological-read/scripts/`. This is research tooling, not application code.

| Context (question) | Module | Predicate / tag |
|---|---|---|
| Universe (what is allowed?) | `p3b_s5_r7_universe.py` | U / `R7-U` |
| Witness (what happened?) | `p3b_s5_r7_witness.py` (on `p3b_transcript_syntax.py`) | W / `R7-W` |
| Evidence (which source bytes were observed and cited?) | `p3b_s5_r7_evidence.py` | E / `R7-E` |
| Reconstruction (what follows?) | `p3b_s5_r7_reconstruction.py` | R / `R7-R` |
| Statistics (is the estimand defined?) | `p3b_s5_r7_stats.py` | S (statistics layer) |
| Composer | `p3b_s5_r7_verify.py` | none: it holds no rule of its own |

The dependency graph is acyclic: Witness → Universe; Evidence → Witness, Universe; Reconstruction → Evidence, Universe; Statistics → Universe; Composer → all.

## How the verdict is formed

`p3b_s5_verify.verify` routes a manifest with `contract.revision == 7` to `p3b_s5_r7_verify.verify_r7`:

```python
def compose(values):
    if "F" in values.values():
        return "BATCH-FAIL"
    if "U" in values.values():
        return "BATCH-UNDETERMINED"
    return "BATCH-PASS"
```

- **Strong Kleene:** F dominates U, and U is never PASS.
- **U** means the context's own input is unavailable. Example: the transcript archive is absent, so W = U and E = U (Evidence is derived from the witness).
- **Historical gates** (SLICES, SCHEMA, G-xx, …) still run. The gate assigns their failures to predicates:
  - SLICES, SCHEMA, G-01, G-02, G-07 → U;
  - READ-COVERAGE, AUDIT → E;
  - the rest → R.
  - With no witness, the read-dependent gates (G-04, READ-COVERAGE, AUDIT) are recorded as UNDETERMINED notes, never as failures.
- The report's `result` is **only** a batch verdict. There is never a bare `PASS`.

**The statistics and programme layers are separate:**

```python
estimate_v7(frozen_record, anchor, frame, sample, outcomes)   # STATISTICS-VALID | -NOT-ESTIMABLE | -REJECTED
program_accepted(batch_verdicts, statistics_verdict)          # ∀b BATCH-PASS ∧ STATISTICS-VALID
```

`program_accepted` is a mechanical precondition for governance acceptance, never the act itself.

## Revision routing (gate edits in `p3b_s5_verify.py`)

| Revision | Behaviour |
|---|---|
| < 5 | historical behaviour, unchanged |
| 5 | withdrawn (message unchanged) |
| **6** | **"R7-U contract revision 6 is superseded by revision 7"**. The R6 path still runs, so its failures stay visible (addendum §10) |
| non-integer | `R7-U binding …`, never an exception (TB6) |

## Pitfalls

- Do not add a rule to the composer. Put it in the owning context and its predicate.
- A new consumer of an object field must be declared in the frozen addendum's `CONSUMERS` table (F2). Reading a META field would otherwise upgrade it silently.

**Traceability:** addendum §0, §10 · DR-13, DR-15 · G-LOG-0088 · tests `test_p3b_s5_r7_full.PositiveControl`, `test_p3b_s5_r7_properties.Kleene`.
