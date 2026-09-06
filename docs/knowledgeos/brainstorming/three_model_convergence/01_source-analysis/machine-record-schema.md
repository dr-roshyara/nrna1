# Machine-Readable Per-File Record — Schema (`NNNN.yaml`)

Written for every file alongside `NNNN.md` (MD-005). Fields are **flat and queryable**; prose lives
in the `.md`. Unknown is expressed as `null` or `[]`, never as a guess.

```yaml
sequence:            int            # from the reading log; the canonical file id
path:                str            # exact corpus path
title:               str | null
date:                str | null     # document-stated date; else file mtime, marked
bytes:               int
doc_type:            str            # what the document IS, after reading

# --- classification: TWO-STAGE (MD-004) ---
primary_model_initial:   enum       # gita | mathematics | engineering_knowledgeos |
                                    # epistemic_knowledgeos | cross_model | meta_research |
                                    # foundational | experimental | ambiguous
                                    # (MD-006: `kernel_ddd` RETIRED as a forward value)
primary_model_initial_historical: str | null   # retired label, if this record predates MD-006
lineage_provisional:     enum | null # c1_engineering_knowledgeos | c2_epistemic_knowledgeos |
                                    # c1_c2_transition | not_applicable | undeterminable
lineage_evidence:        [str]      # what IN THIS FILE supports the lineage reading
primary_model_final:     enum | pending_global_reclassification
classification_change:   bool | pending
reason_for_change:       str | pending
confidence:              enum       # high | medium | low  — how firmly the PROVISIONAL class is held
secondary_models:        [str]      # §4 STEP 4 secondary vocabulary

# --- source role: INDEPENDENT of model classification (MD-009, from seq 0024 onward) ---
source_role:          enum | null   # PRIMARY_RESEARCH | FOUNDATIONAL | INDEPENDENT_RESEARCH | BRIDGE
                                    # | CRITICAL_REVIEW | VERIFICATION_RECONSTRUCTION | SESSION_LOG
                                    # | DUPLICATE_REPRODUCTION | IMPLEMENTATION | META
                                    # null for records 0001-0023 (field predates this file's writing)
canonical_source:     int | null    # sequence number this file reproduces/verifies, if determinable

status:              enum           # SR DR DF HP CG PR TH EX AN UN OP RF CT (dominant status)
maturity:            enum           # established | developing | hypothetical | speculative |
                                    # undefined | contradictory
importance:          enum           # critical | high | medium | low

# --- content extraction ---
introduces:          [str]          # new concepts/terminology
defines:             [str]          # explicit definitions (DF)
hypotheses:          [str]          # HP / CG
operators:           [str]          # ONLY if input/output/pre/post are actually given
formal_objects:      [str]          # spaces, measures, orders, types — only if actually formalised
undefined_concepts:  [str]          # UN — used but not defined. Do NOT invent a definition.
open_questions:      [str]          # OP

# --- relationships to PREVIOUSLY READ files (by sequence number) ---
depends_on:          [int|str]
extends:             [int|str]
refines:             [int|str]
renames:             [str]
contradicts:         [int|str]
supersedes:          [int|str]
repeats:             [int|str]
connects_to:         [int|str]

# --- bridges: DISCOVERED, NEVER ASSUMED (MD-005 C) ---
bridges:             [str]          # a_to_b | a_to_c1 | a_to_c2 | b_to_c1 | b_to_c2 |
                                    # c1_to_c2 | three_way | four_way
                                    # EMPTY unless the file supplies EVIDENCE of connecting work.
                                    # Lexical similarity is not evidence. NEVER populated from a
                                    # bridge_candidate alone (MD-012) - requires independent later
                                    # corpus confirmation.
bridge_candidates:   [str]          # MD-012: an observation SUGGESTING a possible cross-lens
                                    # relationship, not yet independently confirmed. State the
                                    # candidate + what would need to be true to promote it.
bridge_evidence:     [str]          # one line per bridge: what in THIS file supports it

# --- tier ---
tier2_triggered:     bool
tier2_reason:        str            # why it fired, or why it did not — a checkable claim either way
```

## Field discipline

- **`primary_model_final` stays `pending_global_reclassification` through the whole of pass 1.**
  Writing a final value during the sequential pass would defeat MD-004.
- **`operators` and `formal_objects` are near-always empty in the engineering-governance region of
  the corpus.** They are populated only where an operator actually has a signature or an object is
  actually formalised. An empty list is a finding, not an omission.
- **`undefined_concepts` is the highest-value field in the schema.** It is what makes §14's gap
  register derivable by query rather than by recollection.
- **`bridges` empty is the default and the honest state.**


## MD-006 addendum — the term-collision rule

`bridges: [c1_to_c2]` is **never** written because a file uses the word *Kernel* or *KnowledgeOS* in
both senses. It is written only when the file does **connecting work**: poses a C2 question *about* a
C1 construct, reformulates a C1 concept in C2 terms, or explicitly narrates the transition. The nine
candidate C1→C2 correspondences listed in MD-006 are `[HP]` and enter `bridges:` **per file, on
evidence**, never wholesale.

`lineage_provisional: undeterminable` and `primary_model_initial: ambiguous` are correct, preferred
answers when the file does not settle the question. Forcing a lineage is worse than recording that
the file does not decide it.

## MD-014 addendum — `research_arc` and `anchor_test`

**`research_arc`** (ledger-level, not per-file — recorded in `01_source-analysis/research-ledger.md`
only, when an observed multi-file sequence forms a plausible historical-emergence chain):

```yaml
research_arc:
  sequence: [str]                 # e.g. ["EKS/PKS/AIP", "Mathematics", "Zero", "Kernel boundary",
                                   #       "Gītā/Vedanta", "Pramāṇa", "Minimal Kernel Candidate"]
  observed_in: [str]              # sequence numbers, e.g. ["0079".."0087"]
  status: observed                # always "observed" — never "confirmed" or "causal"
  causal_interpretation: unproven # always "unproven" until independently evidenced otherwise —
                                   # documentary reading-order sequence is not evidence of causation
```

**`anchor_test`** (per-file, optional — populated only for files that materially bear on a designated
anchor document such as 0087; omitted, not filled with nulls, when a file has nothing to report):

```yaml
anchor_test:
  anchor: "0087"                  # which anchor document this test is against
  supports_dimension: []          # which of the anchor's dimensions this file independently supports
  refines_or_contradicts: []      # dimension name -> how
  alternative_minimal_kernel: null # or a short description if this file proposes a different one
  supplies_math_semantics: false
  supplies_operator_or_transition: false
  connects_pramana_vedanta_gita_to_c1c2: false   # true only if MORE than structural resemblance
  claim_absence_tension: unchanged  # unchanged | resolved | worsened
  invariant_id_scheme_reconciled: false
```

**Status discipline (reaffirms MD-006/MD-012 for this specific case):** a designated anchor's status
is `candidate` and stays `candidate` through the whole of this research, independent of MD-004's
provisional/final classification pass. Promotion of a candidate kernel to canonical status is never a
byproduct of corpus reading — it requires the explicit staged progression MD-014 §1 records, evidenced
separately at each stage.

## MD-017 addendum — six-way relationship taxonomy and `unresolved_equivalence`

Every time a new file's candidate appears related to an existing registry entry, classify the
relationship as exactly one of:

```yaml
relationship:
  type: new_concept | new_representation | new_decomposition | refinement |
        contradiction | unresolved_equivalence
  candidates: [str]              # the two (or more) entry names/IDs involved
  equivalence_status: unproven | proven_equivalent | proven_distinct   # unproven is the default
                                                                          # and near-always correct
  note: str                      # what would need to be shown to move equivalence_status
```

- **`unresolved_equivalence` is the default** for any plausible-but-unconfirmed correspondence — never
  silently upgrade to `new_representation` (which asserts sameness) or leave as a bare
  `possible_correspondence` prose note with no tracked relationship.
- **Never merge the two registry entries.** The relationship record lives alongside both entries,
  cross-referenced; neither entry is deleted, renamed, or folded into the other.
- **The mathematical target (research hypothesis, not canonical):** for competing representations
  `R_i(K)`, `R_j(K)` of a candidate kernel state `K`, ask whether a structure-preserving transformation
  `φ_ij: R_i → R_j` exists with `φ_ij(R_i(K)) ≅ R_j(K)`. Recording `unresolved_equivalence` is the
  bookkeeping step that keeps this question askable later — collapsing two entries early destroys the
  evidence needed to test it.

## MD-018 addendum — `status_chain` field

Every registry entry additionally carries a `status_chain` field, distinct from the MD-015 maturity
ladder (which tracks formal-semantics development). This tracks evidentiary/promotion status:

```yaml
status_chain: candidate | supported | corroborated | formally_defined | operationally_defined | canonical
```

No entry may skip a stage. As of file 0099, every entry in the registry is `candidate` — nothing has
been independently corroborated (MD-012's bar), formally defined with carrier sets/axioms, or
operationally defined with a working test. A tentative cross-model field mapping (e.g. an Identity
tuple's fields mapped onto the anchor's six original dimensions) is recorded as a hypothesis with named
open doubts, never silently treated as resolved correspondence.

## MD-015 addendum — dimension registry pointer and maturity ladder

Every named candidate dimension gets ONE entry in `01_source-analysis/dimension-registry.md` — never
duplicated inline per file. When a file materially bears on a registry entry, its `anchor_test` block
(or a per-file note, for non-anchor files) names which entry was updated, rather than restating history:

```yaml
anchor_test:
  ...                              # existing MD-014 fields
  registry_updates: [str]          # e.g. ["Evidence Integrity: added 0092 independent corroboration"]
```

**Maturity ladder (tracked per registry entry, never per file):**
`semantic_definition → state_representation → constraint → operator → invariant_preservation_test`.
A dimension's status line in the registry names the HIGHEST stage reached across all files so far —
this is cumulative corpus state, not a per-file property.

**Never collapse on resemblance.** Two differently-named candidates (e.g. "Evidence Integrity" and
"Pramana Grounding") get separate registry entries with a `possible_correspondence` cross-reference,
never a merged entry — merging is a synthesis-stage decision (§29), not something pass-1 reading may
do implicitly by choosing which registry entry to update.
