# Batch B0050 — Extraction Summary

**Files processed:** 40 (S2046–S2085), all read in full and extracted.
**Commit:** 39fdef05dc027c6264b6c349a26362a59191a35f
**Date range in files:** all dated 2026-08-31 (a single, extremely dense research day).

## What this batch contains

Two interleaved tracks, both centered on the same-day Bhagavad Gita ↔ KnowledgeOS
integration effort and the parallel Step 285/286 canonical-state-reconciliation research:

1. **Lane B (self-attested, unconditional) track** — S2046–S2053: eight per-chapter "HPA
   analysis" documents mapping individual Gita chapters (1–5, 16–18) onto KnowledgeOS
   constructs, each declaring its own results "Solved"/"Complete" without independent
   verification. S2059 escalates this into a full 18-chapter "complete derivation" claiming
   the Gita "is not an analogy, it is the source code," reasserting a five-operator
   transformation algebra that a same-day sibling document had already explicitly retracted.

2. **Lane A (disciplined, falsification-first) track** — the bulk of the batch (S2054–S2058,
   S2060–S2085): a rigorous, self-correcting research programme that (a) explicitly forbids
   treating Gita analogies as axioms, (b) builds executable scripts (t285_reconcile.py,
   t285_equality.py, e_equality.py) that actually test claims against the corpus, (c) twice
   catches and corrects its own over-derivation (the "equality-typing" defect found in D285-5
   is later found, self-critically, in the author's own D285-6 claim, and later still in the
   flagship non-injectivity result itself), and (d) closes with a quantified verdict: of
   roughly a dozen tested Gita-KnowledgeOS correspondences, most are independently derivable
   from KnowledgeOS alone (Gita corroborates but does not found them), a handful are corrected
   mis-mappings, a few are outright refuted (a universal knower is impossible under an
   information-theoretic theorem; a five-operator algebra fails type-checking; "Knowledge
   Atma" is redundant with existing provenance), and the two apparent genuine contributions
   (field/knower distinction; the Sanjaya observation layer) turn out to have entered the
   corpus/architecture BEFORE this research programme began.

An independent gap-discovery review (S2056/S2057) reaches the same conclusion via a different
route ("Gap effect: none... WRITE ONLY AS RESEARCH HISTORY"), directly adjudicating the
Lane-B series from outside it.

## Key findings worth flagging for later phases

- **CONTRADICTION**: S2059 (dated same day, later) reasserts the five-operator Theta algebra
  that S2058 (dated same day, earlier) explicitly named and withdrew.
- **Self-correction chain**: S2063's "CONFIRMED, REQUIRED" non-injectivity result is narrowed
  by S2070/S2072 (equality must be named) and then substantially weakened by S2080/S2081/S2083
  (a fourth corpus-native equality relation, provenance-sensitive, makes the same witness
  show injectivity — the original claim silently resolved an open corpus governance decision).
- **Genuine falsification**: the Gita's "universal knower" (13.3, Krishna as knower in all
  fields) is refuted by an independently-derived KnowledgeOS information-theoretic
  impossibility theorem (31.19) — the cleanest negative result in the batch.
- **Corpus-recovery finding**: the "(W,Ω) missing referent layer" blocking K-reconciliation
  turns out to already exist in the corpus as "Sanjaya," formalized five days before this
  research thread began (S2068).
- Two prompt/identifier defects were found and disambiguated rather than silently patched
  (H-K04/H-K06 identifier collisions, S2068).

## Self-checks

All six mandatory self-checks passed after two rounds of correction:
- Closed-list `types` validation: 0 invalid.
- Label registration against 11-OBJECT-INDEX.jsonl + this batch's own proposals: 0 unregistered.
- JSON validity: 294/294 contribution lines valid.
- `unknown_candidate`/`labels` consistency: 0 inconsistent (5 rows initially found using a
  specific label alongside `unknown_candidate`; corrected to `labels: ["UNKNOWN-OBJECT-CANDIDATE"]`
  per the exact schema requirement).
- `files.jsonl` field-shape check: 0 errors.
- `scope` enum check: 0 errors.

## Known data-entry correction made during extraction

Files S2080–S2083/S2085 were initially mis-numbered against the true batch order (an
off-by-one/two error while cross-referencing `print_batch.py`'s output). This was caught by
comparing `files.jsonl` against the expected `S2046..S2085` id set, which showed a missing id
(S2071) and, on inspecting the underlying script, a systematic shift in later ids. All
`source_id` fields were corrected via a scripted rename (S2080→S2081, S2081→S2082,
S2082→S2084, S2083→S2085), and the two files this left unrecorded (S2071
`OUT-t285-equality.txt`, S2080 `OUT-e-equality.txt`, S2083 `E1-E7-EQUALITY-INVESTIGATION.md`)
were then read and extracted properly under their correct ids. Final id-set verified complete
and duplicate-free (40/40).

## Output files

- `files.jsonl` — 40 records (one per source file).
- `contributions.jsonl` — 294 records.
- `index-proposals.jsonl` — 57 new object-label proposals (mostly Gita-chapter-specific
  formal objects and Step-285/286 research artifacts; several reuse or reference existing
  B0049-and-earlier objects such as `gita-field-knower-kshetra-kshetrajna-ontology`,
  `readiness-dependency-graph-minimum-implementable-kernel`, `o-core-ratification-gap-d0`,
  `reject-operation-i12-article8-tension`, `zero-lord-sarathi-guidance-cycle`,
  `sarathi-investigation-guide`, and `e-v-omega-symbol-overload`).

**Note on reading depth:** all 40 files were read in full (no file exceeded a size requiring
partial/representative reading). Several later files (S2060, S2066, S2071, S2079, S2080,
S2082's script) are near-verbatim consolidations of earlier files in this same batch; these
were extracted at lower density with explicit `in_file_overlap_claim: COPIES/REPEATS`
annotations rather than re-extracting already-captured content, per the batch's own overlap
discipline (Algorithm Step 4).
