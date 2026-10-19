# V-2 — Reviewer instruction and comparison rules (execution material)

| Field | Value |
|---|---|
| Plan | M-03 §5 V-2 |
| State | **READY, NOT RUN.** Needs Principal decisions D-1 (MRP-1.1 candidate), D-5 (reviewer), D-6 (adjudicator) |
| Material | 7 RQ-002 background files (below). **No corpus content** |

## 1. Source set (hashes fixed 2026-10-18)

| File | sha256 | Lines |
|---|---|---|
| `docs/implementation/RQ-002_General_Knowledge_Architecture_Constitutional_Model.md` | `869d06fe3389976823a228173a381aea980f0451099bdd2479657a4544db4a00` | 111 |
| `docs/implementation/RQ-002_Knowledge_Meta_Model.md` | `f07bc81472b5e9cdaa302e1061e04162126a2e2528a93fc0751f0513cb97502a` | 88 |
| `docs/implementation/RQ-002_Knowledge_Taxonomy.md` | `90d2b7f1235adfd462628b9cf6bb3e5d2faffd4f29aae27fcee710eb168a1534` | 141 |
| `docs/implementation/RQ-002_Project_Knowledge_Domain.md` | `0669b0d2e57a3a52e280db0a78beb6e30f779f6a2902bd21cabd1d56b323a867` | 109 |
| `docs/implementation/RQ-002_Strategic_Discovery_Report.md` | `81d61aba6c7149b1d73b81aa531ecd13ca30023188d77b8f6765a44a0e88df9f` | 116 |
| `docs/implementation/RQ-002_Architectural_Synthesis.md` | `e271fb4a42137b2c6bfb30164aba63c4634748740f9b595bc7b2c189458ab26a` | 177 |
| `docs/implementation/RQ-002_Research_Findings_Raw.md` | `47490d402b061b80aac816dacb043ce0a6b5b318312e8aef724475a8aca6cfc6` | 83 |

## 2. Unit of count (fixed before either extraction)

One **item** = one of the following, at one line anchor:

| Type | What qualifies |
|---|---|
| DEF | a definition, including by example or by contrast |
| ASM | a stated or clearly required assumption (required assumptions are marked [I]) |
| CLM | a proposition or claim the text asserts, with its own strength word |
| INV | an invariant or rule |
| MATH | a formula, structure or formal relation |
| REV | a revision, rejection or retraction of an earlier idea |
| REL | a stated relation between two concepts |

A sentence carrying two propositions yields two items. Repetitions of an item already recorded are not new items.

## 3. Reviewer prompt (send verbatim)

> You are an independent reviewer. Read **only** the seven files listed below, in full. Do not read any other file, directory listing or search result. Do not use web access.
>
> For each file, extract every item as defined in the attached unit-of-count table. For each item give: id, type, file, line range, a verbatim quote (exact text from the stated lines), and a one-sentence statement of its meaning in your words. Mark anything you infer, rather than read, with [I].
>
> Then list, per file, every line range you did not extract and why (front matter, boilerplate, repetition of another item, navigation, off-topic with a one-line description).
>
> Write your output as one Markdown file to the path given below, and nothing else. Do not compare with, or look for, any other extraction.

Attached: §1 table, §2 table, and the output format below.

**Output format (amended 2026-10-18, before the reviewer started, so both extractions can be checked by `quote_anchor_check.py`, which takes one source per record):** one file per source, `05-validation/v2-reviewer/E-<nn>.md` (nn = 01…07 in §1 order), each beginning with a fenced YAML header holding `source_path:` and `sha256:` from §1; one item as a quote line `> Q<n> L<a>-<b>: "<verbatim text>"` followed by `- Q<n> · <TYPE> · <meaning>`; a final table of NOT-EXTRACTED line ranges with reasons. Professor's own extraction uses the same format.

## 4. Procedure

1. **Professor extracts first** to `05-validation/v2-professor/EXTRACTION.md`, in the same format, without consulting B-08 or any earlier note. The sha256 is recorded in `00-access/READ-LOG.tsv` (purpose "V-2 freeze").
2. **Reviewer extracts** in a fresh context with the prompt above; the output sha256 is recorded the same way.
3. **Access audit.** From the reviewer's transcript, list every tool call. Any read outside the seven files or its own output path makes V-2 **INCONCLUSIVE**. Counts and paths only.
4. **Quote check.** `tools/quote_anchor_check.py` runs on both extractions. Known limitation: TF-1/TF-2 (M-03 §4.9) are unfixed, so line numbers can diverge for files containing U+2028 or similar characters. The seven files must be checked for such characters before running.
5. **Matching** by the adjudicator, not Professor: two items match when they have the same type, overlapping line ranges, and the adjudicator judges them to state the same proposition. Each unmatched or differing item is a row in `V2-DISAGREEMENTS.tsv`, class OMISSION · MISREADING · LABEL-ERROR · SCOPE · BOTH-DEFENSIBLE.
6. **Capture–recapture.** n₁, n₂, m and Chapman's N̂ with interval, reported with the dependence direction stated (same-model reviewer: positive dependence likely; N̂ is then a lower bound).

## 5. Result (M-03 §5)

- **SUCCESS:** all disagreements adjudicated; no Professor LABEL-ERROR; no adjudicated MISREADING of a definition or invariant.
- **FAILURE:** any of those.
- **INCONCLUSIVE:** an access audit breach, or the matching rule cannot be applied.
