---
task: KSME-20 (P3a Reliability Diagnosis, per user commission)
scope: corpus-wide chronological-read pipeline (P3a)
derived_from: [prompts/20260911_0221_prompt3-optimized.md, unwitnessed_mechanical.json, unwitnessed_sample.json,
  unwitnessed_sample_classified.json, P3A-UNDEFINED-RELATIONSHIP-PROVENANCE*.{jsonl,md}, KSME-20 Fork 2]
---

# KSME-20 — P3a Error Taxonomy (UNWITNESSED vs. INDEPENDENT, and the decision-rule contract)

## 1. Canonical definitions (verbatim, master protocol v3.5, Phase 3 / A11)

> "relationship ∈ {SAME, REFINEMENT, EXTENSION, REDEFINITION, REPLACEMENT, SPECIALIZATION, DERIVED-FROM,
> CONTINUATION, HOMONYM, INDEPENDENT, UNWITNESSED} ... **default: UNWITNESSED / NONE.** Insufficient alone:
> same symbol, same name, same author, same document, similar wording, temporal proximity, similar purpose.
> ... **NEGATIVE-VERDICT BAR**: INDEPENDENT may be concluded only from a corpus-wide search (all
> families/groups) for a connecting claim. A search bounded to the current group/batch that finds nothing
> yields UNWITNESSED, labelled NEGATIVE-BOUNDED, never INDEPENDENT. Only a search stated as corpus-wide and
> empty yields INDEPENDENT, labelled NEGATIVE-CENSUS."

**UNWITNESSED and INDEPENDENT are cleanly distinguishable in principle** — the axis is search *scope*
(bounded vs. corpus-wide), not evidence content. The distinguishing mechanism (`negative_verdict_label`:
NEGATIVE-BOUNDED/NEGATIVE-CENSUS) exists as a schema slot but **is never populated: 0 of 684 UNWITNESSED
records carry it.** INDEPENDENT usage itself was checked and found fully conformant (all 4 production
instances correctly carry corpus-wide search notes) — the originally-suspected "UNWITNESSED vs INDEPENDENT
mix-up" does not manifest as agents choosing INDEPENDENT wrongly. **The conflation is entirely internal to
UNWITNESSED**, which silently absorbs several genuinely different epistemic states.

REDEFINITION/REPLACEMENT/EXTENSION and the REFINEMENT/SPECIALIZATION/CONTINUATION/DERIVED-FROM cluster are
**not separately defined** anywhere beyond enum membership — Prompt 2 explicitly defers this ("only later
decide whether the relationship is REFINEMENT / EXTENSION / REDEFINITION / or something else," no criteria
given). This is a genuine, protocol-level under-specification, not an execution error.

## 2. UNWITNESSED breakdown (684/1,793 = 38.1% of all pairs)

Mechanical triage (`unwitnessed_mechanical.json`, rule-based, no judgment):
- **25 pairs**: mechanically zero-content on one side — a P1 completeness gap, not a real UNWITNESSED verdict.
- **10 pairs**: `basis != NONE` (real cited evidence exists but no enum value fits, or documents an
  unresolved contradiction).
- **649 pairs**: remainder, requiring judgment.

Stratified sample of 120/649, independently classified:

| Category | Meaning | n | % |
|---|---|--:|--:|
| A | Genuinely searched, nothing found — correct use | 85 | 70.8% |
| B | Search perfunctory/insufficient (placeholder misuse) | 20 | 16.7% |
| F | Real relationship found, no enum slot fits (ontology gap) | 12 | 10.0% |
| D | Corpus itself contested/contradictory, misfiled as absence | 2 | 1.7% |
| E | Zero real content, missed by mechanical check | 1 | 0.8% |

~29.2% of the sampled remainder (35/120) is not the clean "correctly used" case — extrapolated, roughly a
third of all 684 UNWITNESSED verdicts are not what the label implies at face value. Supporting detail: of
47 sample pairs where truncation was applicable, only 11 (23%) show a full-row check — most Category-B
reasoning explicitly worked from a minority of available evidence rows while still concluding absence.

## 3. The separate, causally-overlapping implementation defect (UNDEFINED-RELATIONSHIP-PROVENANCE)

Distinct from the labeling-discipline problem above: `derive_reconciliation.py`'s pairing/serialization code
(`row_brief()`) **discards `dependencies[]`, `lineage_claims[]`, `invariants[]`, `assumptions[]`, and
file-level provenance from every row before any reviewer sees it**, and cross-group pairing only matches
exact label strings (misses bare source-ID targets and paraphrases). Corpus-wide: **1,466 distinct
label-pairs (2,318 signals) carry real structural relationship evidence the pairing script never used** —
1,104 (75.3%) trace to PRIMARY (non-synthesis) files. Of these 1,466: 257 already exist as judged P3a
verdicts (judged on evidence that omitted this signal), 1,207 were never even constituted as pairs.
**65 of the 257 (9.5% of ALL 684 UNWITNESSED verdicts) are demonstrably wrong specifically because of this
evidence-loss bug, not ontology confusion.** This is a different root-cause category (implementation
defect) from the labeling-discipline problem in §2, and it is broader (affects pairs of every relationship
value, not just UNWITNESSED).

## 4. Is UNWITNESSED doing double duty? Confirmed — precisely characterized

- **(a) Genuine semantic verdict** (Category A, ~71%): a real, stated search found nothing. The intended
  use, and the majority case.
- **(b) Procedural placeholder**, multiple distinct sub-modes currently collapsed into one label:
  - Perfunctory/truncated search (Category B, ~17%).
  - Evidence-pipeline loss (≥65 pairs / 9.5%, §3) — the reviewer's call was "correct" given what it saw,
    wrong relative to the corpus.
  - Taxonomy-coverage gap (Category F, ~10%) — real, quoted, specific relationships (recurring patterns:
    "co-integrative/symmetric sibling," "meta-commentary on lineage," "consumption/authority-boundary
    without genealogy") for which none of the 11 values is correct — an ontology gap, not an evidence gap,
    filed as UNWITNESSED for lack of anywhere else to put it.
  - Corpus-level contestation (Category D, ~2%) — the source material itself is unresolved/contradictory,
    a different epistemic state (CONTESTED) misfiled as "no evidence."

`P3A-IMPLEMENTATION-CONFORMANCE-AUDIT.md` §7 states this precisely: "the protocol has the vocabulary to
distinguish [NEGATIVE-BOUNDED from NEGATIVE-CENSUS], but nothing in the implementation uses it."

## 5. Decision procedure — does the protocol provide one?

No formal decision tree. Ordered guidance exists (evidence sources to consult, the "insufficient alone"
exclusion list, the NEGATIVE-BOUNDED/CENSUS bar for INDEPENDENT specifically) but the actual choice among
the 11 values — especially the REFINEMENT/EXTENSION/REDEFINITION/SPECIALIZATION/CONTINUATION/DERIVED-FROM
cluster, which sit close together semantically — is left to unconstrained reviewer judgment. This directly
supports "inconsistent adjudication procedure between passes" as a real, secondary cause (confirmed:
RP0647's independent reviewer applied an undocumented rule not evidenced in the original pass).

## 6. Proposed decision-rule contract (per commission §4 — separate dimensions, not one axis)

For any pair (x, y), evaluate these **separately**, not as one collapsed judgment:

| Dimension | Question | Existing enum coverage |
|---|---|---|
| Identity | x = y (same object, different name)? | SAME |
| Semantic equivalence | x ≡ y under declared scope? | SAME (no separate slot — gap) |
| Refinement | x ⪯ y (y is x made more precise)? | REFINEMENT, SPECIALIZATION |
| Extension | does x add capability/constraints to y? | EXTENSION |
| Chronological continuation | is x the direct next step of the same thread as y? | CONTINUATION |
| Derivation | is x derived from y as a distinct consequence? | DERIVED-FROM |
| Redefinition/Replacement | does x supersede y's meaning/role? | REDEFINITION, REPLACEMENT |
| Homonymy | same name, unrelated referents? | HOMONYM |
| Independence | corpus-wide search, no connection (NEGATIVE-CENSUS)? | INDEPENDENT |
| Unwitnessed (bounded) | local/group search only, nothing found (NEGATIVE-BOUNDED)? | UNWITNESSED |
| **Operationally-connected-but-architecturally-independent** (RP0440's case) | connected in use/citation but not in formal lineage | **NO SLOT — confirmed gap** |
| **Symmetric/co-integrative sibling** (Category F pattern) | mutually reinforcing, neither derives from the other | **NO SLOT — confirmed gap** |

**Recommendation, not a unilateral decision**: the two starred rows are real, evidence-confirmed gaps
(recurring independently in the UNWITNESSED sample and the high-stakes probe). Closing them requires either
a 12th+13th enum value or a sub-typed status field — this is a governance decision, named here per the
Missing-Source/never-invent discipline, not resolved by this pass.

## 7. Bottom-line assessment

**Not "the whole 11-value ontology is broken."** A compound, layered, mostly mechanically-fixable defect:
(1) the `row_brief()` evidence-loss bug — dominant by volume, fixable without re-adjudication, once
repaired; (2) the never-enforced NEGATIVE-BOUNDED/CENSUS labeling — fixable by a script change, requires no
new judgment; (3) a genuine, bounded ontology-coverage gap (~7-10%) — requires an actual governance
decision, not a mechanical fix; (4) ordinary reviewer disagreement on soft, never-disambiguated boundaries —
a small residual.
