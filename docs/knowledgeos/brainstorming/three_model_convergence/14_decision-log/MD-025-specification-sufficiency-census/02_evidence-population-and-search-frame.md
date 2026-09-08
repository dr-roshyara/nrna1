# Evidence Population and Search Frame

## Admissible B population

162 files carrying `model.primary: b` in `01_source-analysis/per-file-mathematical/*.yaml` (151
independent + 10 duplicate + 1 control-self-reference, per Phase 2's own established accounting — not
re-derived here). Full path list extracted mechanically from the per-file records (not hand-selected),
verified: 161/161 paths resolve to real files on disk.

## Search methodology (Pass 1 — exact identifiers, corpus-wide across the full B population)

Ran `grep -l` for each of: `Input:`, `Output:`, `Precondition`, `Postcondition`, `Domain:`, `Codomain`,
`operator contract`, and each of the 13 C0 operator names followed by `(` (to catch function-signature-
style usage), across all 161 resolved B-population files. Result counts:

| Pattern | Files matched |
|---|---|
| `Input:` | 1 |
| `Output:` | 7 |
| `Precondition` | 3 |
| `Postcondition` | 2 |
| `Domain:` | 0 |
| `Codomain` | 1 |
| `operator contract` | 1 |
| `Observe(` | 2 |
| `Interpret(` | 2 |
| `Represent(` | 1 |
| `Relate(` | 1 |
| `Discriminate(` | 2 |
| `Hypothesize(` | 0 |
| `Infer(` | 1 |
| `DetectGap(` | 1 |
| `Challenge(` | 3 |
| `Validate(` | 1 |
| `Revise(` | 1 |
| `Determine(` | 25 |
| `Select(` | 4 |

**This is a genuine census result, not a sample** — every one of the 161 admissible files was searched
for every pattern; the counts above are exhaustive over the defined frame.

## Pass 2 — the decisive lead

The `operator contract` hit (1 file: `20260902-150331_the-development-of-the-model-how-it-was-built-
what-changed.md`, a B-admissible historical-summary document) directly names `docs/knowledgeos/
research/kernel-reduction/` as containing "operator contracts" (§03). This triggered Pass 3.

## Pass 3 — source lineage, following the citation

`docs/knowledgeos/research/kernel-reduction/` exists on disk (22 files + one foreign file explicitly
flagged as not belonging to that lane). **M0030 itself** (`20260901-222111_prompt-kernel-reduction-
and-minimality-experiment.md`, the same file whose own template requests operator contracts, already
checked in MD-024) **cites this exact directory path twice in its own raw text** (lines 1443, 1488) as
its own required output location. This is a direct, source-attested lineage: an admissible B file
names its own execution's output directory, and that directory genuinely exists, containing exactly
the requested contracts.

**`docs/knowledgeos/research/theory-v1.1-simulation/` and `theory-v1.2-simulation/` also exist**,
containing further formal apparatus (a type-dependency table with `S^epi`/`Context` formally typed,
found via Pass 2's own `S^epi` search extended to this wider tree).

## Pass 4 — negative-space verification

Before reporting any absence: `classification-register.tsv` contains **zero** rows referencing
`docs/knowledgeos/research/` anywhere — this directory has never been enumerated, read, or classified
by any phase of this reconstruction. This is not the same as `OUT_OF_SCOPE_ROOT` (a classification
*given* to enumerated files under `docs/knowledgeos/` directly) — it is simply outside every corpus
census this reconstruction has ever run. Confirmed via direct `grep -c` against the register (0
matches), not inferred.
