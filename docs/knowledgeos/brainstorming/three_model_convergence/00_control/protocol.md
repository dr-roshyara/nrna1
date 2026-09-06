# 00_control — Binding Research Protocol

Derived from `prompts/202609011141_prompt.md`. This file is the operative restatement the
analysis runs against. Where this file and the prompt differ, **the prompt governs**.

## Non-negotiables

1. **Reading order is the log order.** No browsing, no reordering, no skipping "repetitive" files.
2. **No premature merge.** Three models are reconstructed independently *before* comparison (§2).
3. **No circular validation.** Gītā ⇏ Mathematics ⇏ Kernel ⇏ Gītā (§40).
4. **No hindsight bias.** An early document is read in the state of the research at its date (§39).
5. **No silent promotion.** hypothesis ↛ definition; analogy ↛ mathematics; interpretation ↛ fact;
   probability ↛ knowledge; philosophical concept ↛ operator (§3).
6. **Falsification is mandatory, not optional** (§41).
7. **The strongest statement made must never exceed the strength of the available evidence.**
8. **The original corpus is read-only.** No file under `brainstorming/` outside
   `three_model_convergence/` is created, moved, edited, or overwritten (§42).

## Epistemic status vocabulary (§3)

`[SR]` source-derived · `[DR]` derived · `[DF]` formal definition · `[HP]` hypothesis ·
`[CG]` conjecture · `[PR]` proposition · `[TH]` theorem · `[EX]` experimental ·
`[AN]` analogical · `[UN]` undefined · `[OP]` open problem · `[RF]` refuted · `[CT]` contradictory

## Classification is TWO-STAGE (MD-004)

```
pass 1 (sequential, log order)  →  PROVISIONAL classification
        ...entire corpus read...
global reclassification         →  FINAL / CANONICAL classification
```

**No file is permanently classified during pass 1.** The three-model boundaries are **not decided**
until the whole corpus has been read and the three models reconstructed. A provisional
classification is **never erased**; a revision is recorded beside it in
`01_source-analysis/revisions.md` and carried to `00_control/classification-register.tsv`, which
holds `initial_classification · final_classification · classification_change · reason_for_change`
for every file.

### Primary classification (exactly one per file, §4 STEP 4 — revised by MD-006)

| Code | Value | Scope |
|---|---|---|
| **MODEL-A** | `gita` | Gītā / philosophical-epistemic |
| **MODEL-B** | `mathematics` | mathematical / statistical |
| **MODEL-C1** | `engineering_knowledgeos` | EKS · PKS · product binding · portability · engineering governance · **engineering kernel** |
| **MODEL-C2** | `epistemic_knowledgeos` | Knowledge Space · Knowledge Element · `K_t` · dimensions/values · **epistemic Kernel** · Buddhi · operators · purification · Moksha |
| **MODEL-X** | `cross_model` | bridge material connecting lineages |
| **MODEL-M** | `meta_research` | methodology · process · research governance |
| — | `foundational` | retained from §4 STEP 4 |
| — | `experimental` | retained from §4 STEP 4 |
| — | `ambiguous` | **preferred over forcing a decision** when lineage is undeterminable from the file |

⛔ **`kernel_ddd` is RETIRED as a forward value** (retained as historical metadata on records 0001–0009).

> ### ⛔ THE TERM-COLLISION RULE
> **`Kernel_engineering ≠ Kernel_epistemic`** unless later corpus evidence establishes a relationship.
> A document is **never** classified `epistemic_knowledgeos` merely because it contains the word
> *KnowledgeOS* or *Kernel*. C1 documents are **never** retrospectively reinterpreted as if they had
> originally defined C2 — the genealogy of the idea is itself a research object (§39).

Classification is assigned **after reading**, never from filename, path, or keywords (§4 STEP 2),
and every assignment carries a written reason (§4 STEP 5).

## Records are TWO-TIER (MD-004)

| Tier | Applies to | Contents |
|---|---|---|
| **Tier 1** | **every file, no exception** | source identity · summary · primary provisional classification · secondary · **reason** · concepts · contribution · relationships · undefined concepts · status · importance · provenance |
| **Tier 2** | **only where theoretical significance warrants** | mathematical derivation · formal definitions · operator analysis · contradictions · cross-model implications · detailed equations · topology · probability · Kernel implications · Gītā interpretation |

⛔ **Depth is never reduced because the corpus is large.** Tier 2 fires on *theoretical
significance* — never on file size, budget, or apparent novelty — and a non-firing is recorded
**with its reason**, because the non-firing is itself a checkable claim.

Template: `01_source-analysis/per-file-template.md`.

## Maturity (§4 STEP 10)

`ESTABLISHED` · `DEVELOPING` · `HYPOTHETICAL` · `SPECULATIVE` · `UNDEFINED` · `CONTRADICTORY`

## Importance (§4 STEP 11)

`CRITICAL` · `HIGH` · `MEDIUM` · `LOW` — with reason.

## Artifact contract

| Artifact | Path | Written when |
|---|---|---|
| Per-file record | `01_source-analysis/per-file/NNNN.md` | immediately after reading file NNNN |
| Cumulative ledger | `01_source-analysis/research-ledger.md` | appended as concepts first appear / change |
| Classification matrix | `01_source-analysis/file-classification.md` | appended per file |
| Corpus map | `01_source-analysis/corpus-map.md` | after the full pass |
| Progress state | `00_control/progress.tsv` | updated per file — **the resume point** |
| Classification register | `00_control/classification-register.tsv` | initial in pass 1; final in global reclassification |
| Revision log | `01_source-analysis/revisions.md` | append-only; whenever a later file revises an earlier reading |

**All cumulative state lives on disk.** Conversation context is treated as volatile and is never
the system of record. Any interruption resumes from `progress.tsv`.

## Stage gate

```
01_source-analysis (pass 1, provisional)
     ──gate──▶ GLOBAL RECLASSIFICATION (final classification assigned here, not before)
     ──gate──▶ 02/03/04 canonical models ──gate──▶ 05 cross-model
     ──gate──▶ 06 gap-analysis ──gate──▶ 07 formalization ──gate──▶ 08 kernel
     ──gate──▶ 09 dynamics ──gate──▶ 10 computational ──gate──▶ 11 validation
     ──gate──▶ 12 canonical-theory (guarded; see 12_canonical-theory/STATUS.md)
```

A gate opens only when the prior stage is complete. `12_canonical-theory/` is explicitly
unauthorized until every prior gate has opened.


## What the pass is FOR

The sequential pass is **not** a file-categorisation exercise. Its output is the input to the
research objective:

```
Gītā (A) · Mathematics/statistics (B) · Engineering KnowledgeOS (C1) · Epistemic KnowledgeOS (C2)
        ↓
pairwise bridges  →  common core(s)
        ↓
contradictions · missing mathematical structures · missing Kernel structures · unresolved concepts
```

**The C1↔C2 question is deferred to synthesis and answered from evidence, never assumed:** are they
*independent models*, *evolutionary stages of one model*, *partially overlapping*, or *connected by
identifiable conceptual bridges*? The transition itself may be one of the most important discoveries
in the research — and it is detectable only if the two lineages are kept apart during the pass.

⛔ **The unified Kernel is not constructed until that process is complete.**

## MD-014 — anchor documents (added 2026-09-01)

A file that produces an explicit, decision-shaped candidate (e.g. a numbered minimal-kernel proposal)
may be designated an **anchor document**. Designation does **not** promote it toward canonical status
— an anchor's `kernel_candidate_status` stays `candidate` through the whole of this research; only the
explicit staged progression `candidate → cross-corpus corroboration → formalization → falsification →
canonical kernel` can move it, each stage evidenced separately, never as a byproduct of continued
reading. From the point of designation onward, later primary-corpus files are additionally checked
against the anchor's 8-point `anchor_test` (see `01_source-analysis/machine-record-schema.md` MD-014
addendum) whenever they materially bear on it; results are recorded per file only when triggered.
Historical-emergence sequences observed across files (e.g. "math lens preceded the kernel candidate in
reading order") are recorded as `research_arc: {status: observed, causal_interpretation: unproven}` —
documentary sequence is never treated as evidence of causation. Full registry:
`01_source-analysis/research-ledger.md` → "MD-014 — ANCHOR DOCUMENT REGISTRY". First anchor: **0087**.

## MD-015 — dimension lifecycle registry (added 2026-09-01)

Every named candidate kernel dimension/invariant gets one tracked entry in
`01_source-analysis/dimension-registry.md`: first appearance, name variants, refinements,
contradictions, independent corroboration (per MD-012's bar), current position on the five-stage
maturity ladder (`semantic_definition → state_representation → constraint → operator →
invariant_preservation_test`), and status — always `candidate` or `contested`, **never**
`canonical` during this pass. Differently-named dimensions are never merged into one entry on semantic
resemblance alone; a `possible_correspondence` field records a hypothesis of relatedness, not an
identity — merging is a synthesis-stage (§29) decision, never an implicit pass-1 one. A "Candidate
Cross-Model Correspondence Table" and a three-level Kernel target formulation (`K_t` / `I(K_t)` / `δ:
K_t×O_t→K_{t+1}`) are recorded in the ledger as this research's own working hypothesis for where the
eventual formal theory may go — explicitly not canonical, not placed in `12_canonical-theory/` (still
gated). Full text: `14_decision-log/model-boundary-decisions.md` → MD-015.

## MD-016 — consolidation phase, 0080-0094 (added 2026-09-01)

**Reading paused at 0094** for a consolidation/falsification audit over already-read evidence — see
`01_source-analysis/checkpoint-0080-0094-consolidation.md`. No new philosophical-lens file is read
until this audit is reviewed; resuming at 0095 requires a fresh, separate authorization. The maturity
ceiling (no candidate advances past stage 3 without a stated operator AND an invariant-preservation
test) is now a standing rule, not merely an observation. Full text:
`14_decision-log/model-boundary-decisions.md` → MD-016. **Repaired 2026-09-01** (adversarial review +
repair, see `checkpoint-0080-0094-review.md` and `checkpoint-0080-0094-repair-verification.md`,
verdict REPAIRED). Sequential pass resumed at 0095 on explicit human instruction.

## MD-017 — six-way relationship taxonomy; proliferation treated as evidence (added 2026-09-01)

From file 0098 onward, every apparent relationship between a new candidate and an existing registry
entry is classified as exactly one of: `new_concept` / `new_representation` / `new_decomposition` /
`refinement` / `contradiction` / `unresolved_equivalence` (see `machine-record-schema.md` MD-017
addendum for the schema). **`unresolved_equivalence` is the default for a plausible-but-unconfirmed
correspondence — candidate IDs are never merged on resemblance alone.** The growing number of
candidate-ID schemes and anchor reshapings is treated as a research finding to analyze (possibly
orthogonal representations of one deeper structure — a `φ_ij: R_i→R_j` structure-preserving-
transformation question, recorded as hypothesis only), not a problem requiring another consolidation
checkpoint. Full text: `14_decision-log/model-boundary-decisions.md` → MD-017.

## MD-018 — Identity 6-tuple as candidate representation; acquisition-vs-purification operator hypothesis; status chain (added 2026-09-01)

0099's `I=(Entity,Property,Context,Relation,Time,Authority)` is a candidate formal representation, NOT
equated with the anchor's six original dimensions — a tentative field mapping is tracked as hypothesis
with named open doubts, never adopted. The five Context representations remain one
`unresolved_equivalence` family; the open question is what invariant is common to all five, not which
is correct. A candidate operator distinction (`T_acquire` vs `T_purify`, from "how is ignorance
removed?") is tracked as hypothesis only, never introduced as canonical. Every registry entry now
additionally carries a `status_chain` field (`candidate → supported → corroborated →
formally_defined → operationally_defined → canonical`), distinct from MD-015's maturity ladder — no
entry has left `candidate`. Full text: `14_decision-log/model-boundary-decisions.md` → MD-018.

## MD-019 — autonomous sequential execution mode (added 2026-09-01)

**Milestones are checkpoints, not approval gates.** Continue automatically, file by file, until
N_primary=1,155 is exhausted or a stop condition occurs: unrecoverable technical error,
`resume.py`-detected corpus-integrity failure that cannot be resolved by re-reading the affected
record, genuine unsafe ambiguity, or explicit user interruption. Never stop merely because a finding is
interesting. No routine progress narration, no per-file "continuing to NNNN" announcements, no
confirmation requests during autonomous processing — persist milestones to disk, surface only critical
discoveries or a stop condition. Research methodology (MD-001..MD-018) is unchanged by this decision.
Full text: `14_decision-log/model-boundary-decisions.md` → MD-019.
