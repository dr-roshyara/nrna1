# Phase 5A — Corpus-Wide Census

**Complete population, no sampling.** Every table below covers all 1,185 `PRIMARY`-tier main-corpus
rows and/or all 401 math-lane rows — joined directly from `classification-register.tsv`,
`reading-manifest.tsv` (`corpus_tier`), and the per-file YAML records' own already-recorded fields.
No row was newly read or interpreted to build these tables — every value is **Level 2 (machine-
observable corpus fact)** as defined in `00_index.md`.

---

## Census A — `initial_primary` × `kernel_content` (main corpus, 1,185 `PRIMARY`-tier rows)

| Classification | `kernel_content: true` | `false` | missing/`None` | Total |
|---|---:|---:|---:|---:|
| `engineering_knowledgeos` | 644 | 68 | 7 | 719 |
| `meta_research` | **306** | 52 | 1 | 359 |
| `gita` | 63 | 21 | 0 | 84 |
| `cross_model` | 17 | 2 | 0 | 19 |
| `mathematics` | 2 | 0 | 0 | 2 |
| `epistemic_knowledgeos` | 1 | 0 | 0 | 1 |
| `not_applicable_product_content` | 0 | 1 | 0 | 1 |
| **Total** | **1,033** | **144** | **8** | **1,185** |

**The single most important number in this table: 306 `meta_research`-tagged rows carry
`kernel_content: true`** — nearly half as many again as the 644 already counted inside Model C1's own
719-row main-corpus population (Phase 4). This population was **not examined by Phase 4 at all**,
since Phase 4's own evidence-population rule was `initial_primary == engineering_knowledgeos`
specifically. This is a machine-observable fact, not an interpretation — whether this 306-row
population contains genuinely C1-like, C2-like, or neither kind of content is a **Level 3** question,
addressed on a defined sample only (§ `03_boundary-observations-and-open-questions.md`).

## Census B — `initial_primary` × `mathematical_content` (main corpus)

| Classification | `true` | `false` | missing | Total |
|---|---:|---:|---:|---:|
| `engineering_knowledgeos` | 531 | 181 | 7 | 719 |
| `meta_research` | 246 | 112 | 1 | 359 |
| `gita` | 42 | 42 | 0 | 84 |
| `cross_model` | 3 | 16 | 0 | 19 |
| `mathematics` | 2 | 0 | 0 | 2 |
| `epistemic_knowledgeos` | 1 | 0 | 0 | 1 |
| `not_applicable_product_content` | 0 | 1 | 0 | 1 |

## Census C — `initial_primary` × `gita_content` (main corpus)

| Classification | `true` | `false` | missing | Total |
|---|---:|---:|---:|---:|
| `engineering_knowledgeos` | 230 | 482 | 7 | 719 |
| `meta_research` | 173 | 185 | 1 | 359 |
| `gita` | 78 | 6 | 0 | 84 |
| `cross_model` | 12 | 7 | 0 | 19 |
| `mathematics` | 0 | 2 | 0 | 2 |
| `epistemic_knowledgeos` | 0 | 1 | 0 | 1 |

**230 `engineering_knowledgeos`-tagged rows carry `gita_content: true`** — consistent with, and
quantifying corpus-wide, Phase 4's own qualitative finding (§H of its concept register) that a
Sañjaya/Sārathi/Krishna engineering-tagged parallel track runs independently of Model A's own
gita-tagged evidence. **173 `meta_research`-tagged rows also carry `gita_content: true`** — a
population overlapping neither Model A's 84-file evidence base nor Model C1's 719-file evidence base.

## Census D — date-bucket × classification (main corpus), split at MD-006's date (2026-09-01)

| Date bucket | `engineering_knowledgeos` | `meta_research` | `gita` | `cross_model` | `mathematics` | `epistemic_knowledgeos` |
|---|---:|---:|---:|---:|---:|---:|
| pre-2026-09-01 | 696 | 261 | 78 | 16 | 0 | 0 |
| on-or-after-2026-09-01 | 23 | 98 | 6 | 3 | 2 | 1 |

**A machine-observable temporal pattern, reported without interpretation of cause**: after MD-006
formally introduced the C1/C2 split (2026-09-01), main-corpus classification activity shifted
sharply toward `meta_research` (98 further rows) relative to `engineering_knowledgeos` (only 23
further rows), and produced exactly **one** `epistemic_knowledgeos` row in total. The split's
introduction did not correspond to increased use of the `epistemic_knowledgeos` tag specifically.
This is a fact about classification behavior over time; it does not, on its own, establish why the
pattern occurred (a Level-3 question, not attempted here).

## Census E — math lane (401 files, complete population)

`model.primary` × `kernel_content`-presence (see the schema-consistency finding below for why
"presence," not "true/false," is the correct census statistic here):

| `model.primary` | has recorded content (bool `true`/narrative text) | `false`/none | Total |
|---|---:|---:|---:|
| `KR-SIM` | 123 (narrative text) | 76 (`None`) | 199 |
| `b` | 89 (`true`) | 16 (`false`) + 57 (`None`) | 162 |
| `x` | 8 (`true`) | 3 (`false`) + 5 (`None`) | 16 |
| `c1` | 7 (`true`) | 1 (`false`) + 5 (`None`) | 13 |
| `g` | 1 (`true`) | 2 (`false`) + 3 (`None`) | 6 |
| `c` | 4 (`true`) | 1 (`false`) | 5 |

**Schema-consistency finding**: the math lane's `kernel_content` field is **boolean** (`true`/`false`)
for `b`/`x`/`c1`/`g`/`c`-tagged rows but a **free-text narrative string** for `KR-SIM`-tagged rows —
a genuine schema inconsistency within the same lane, discovered mechanically (the first census query
against this field raised a Python type error before being corrected). Recorded here as its own
data-quality finding, not resolved or normalized.

**All 401 math-lane rows are dated on-or-after 2026-09-01** (the math lane began after MD-006's
split date) — the pre/post temporal comparison in Census D is therefore not meaningful for the math
lane and is not attempted there.

---

## What this census does and does not establish

**Establishes** (Level 2, machine-observable, complete-population facts): the size and content-flag
distribution of every classification bucket in the corpus; a temporal pattern in classification
activity; a schema inconsistency in one field. **Does not establish** (would require Level 3
judgment, not attempted at census scale): whether any specific `meta_research`-tagged row is
"really" C1 or C2 content; whether the temporal pattern reflects a deliberate governance choice, an
absence of C2-eligible source material, or classifier behavior; whether `kernel_content: true` implies
a *new* Kernel-definition proposal or merely a reference to an existing one. These questions are
addressed, on defined samples only, in the remaining artifacts.
