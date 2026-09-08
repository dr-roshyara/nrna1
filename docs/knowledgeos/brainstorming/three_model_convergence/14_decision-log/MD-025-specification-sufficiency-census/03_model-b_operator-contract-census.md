# Model B Operator Contract Census — the Central Finding

## What exists, and exactly where

`docs/knowledgeos/research/kernel-reduction/04-operator-contracts.md` (read in full) contains:

- **An explicit Input/Output convention**, stated once, common to all operators: *"Input = carriers
  required by some derivation rule; Output = the derived carrier; State effects = none, except
  `Revise`; Information effects = governed by the data processing inequality; Dependencies = whatever
  the type rules demand."*
- **A 14-atom vocabulary** (`world-contact`, `meaning-assignment`, `symbolic-encoding`,
  `relational-linking`, `difference-decision`, `content-generation`, `entailment`, `norm-comparison`,
  `adversarial-negation`, `warrant-assessment`, `state-mutation`, `closure-judgment`,
  `preference-over-actions`, `evidential-qualification`), each with a one-line meaning, deliberately
  allowing shared atoms (only 2 are shared, both explicitly named and analyzed).
- **A full contract table** for all 13 C0 operators plus `Qualify` — each row: operator name, its
  atom(s), its claimed "non-reducible responsibility," and a **"corpus support" strength rating**
  (`STRONG`/`MODERATE`/`WEAK`/`WEAK–MOD`) per operator.
- **An anti-circularity device**: operators are defined by their *declared atom set*, never by name —
  explicitly designed so "redefine Y so that Y also does X" is *mechanically detectable* as a model
  change, not a composition.

## Formal status, per the E1–E4 taxonomy

**E1 — DIRECTLY SPECIFIED, but outside the admissible search frame.** This is not a gap in the
taxonomy's own design (§8 of the authorization defines E1–E4 for the admissible frame specifically) —
it is a genuinely new category this census surfaces: the specification is complete, rigorous, and
directly relevant, but sits in a directory this reconstruction has never brought into scope. Recorded
as **`E1-OUT-OF-SCOPE`** throughout this study, distinct from both "found within scope" and "not
evidenced anywhere."

## Provenance chain (source → observation → interpretation → status)

1. **Source**: M0030 (`20260901-222111_prompt-kernel-reduction-and-minimality-experiment.md`),
   admissible B evidence, `[DIRECT EVIDENCE]`.
2. **Observation**: M0030's own raw text names `docs/knowledgeos/research/kernel-reduction/` as its own
   output directory, twice, by exact path — `[DIRECT EVIDENCE]`, machine-verified via grep.
3. **Observation**: that directory exists on disk, contains 22 files including `04-operator-
   contracts.md`, and its own `00-INDEX.md` explicitly labels the whole lane `[EXP]` — *"research
   experiment — NOT canonical, NOT architecture, NOT governance"* — `[DIRECT EVIDENCE]`.
4. **Interpretation**: this directory is the actual execution output M0030 requested and M0035 (the
   B-admissible "confirmed execution" record) summarizes narratively without reproducing the full
   contracts — `[RECONSTRUCTED PROVENANCE]`, not asserted as source-stated, since no B-admissible file
   explicitly states "M0035 summarizes the research/kernel-reduction/ output" in those words; the
   inference rests on matching subject matter, matching trial counts (M0035's own "~150,000 trials"
   language matches `research/kernel-reduction/12-randomized-results.md`'s own "150 000 trials"),
   and M0030's own directory citation.
5. **Status**: `[STUDY-LOCAL CONSTRUCT]` — the E1-OUT-OF-SCOPE classification is this study's own
   analytical device, not a corpus-native category.

## What this does NOT establish

This does not make `docs/knowledgeos/research/kernel-reduction/` part of Model B's own admissible
evidence. It does not retroactively supply MD-024's own composition tests with the missing operator
contracts. It does not establish that this external material is itself free of error, superseded, or
authoritative — its own `19-directive-adoption-and-research-restructure.md` document (per its own
index entry) *"SUPERSEDES THE HEADLINE"* result of the very directory it sits in, meaning even this
external material has its own internal revision history this study does not adjudicate.
