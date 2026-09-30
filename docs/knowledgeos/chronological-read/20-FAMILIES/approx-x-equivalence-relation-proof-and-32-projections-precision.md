# approx-x-equivalence-relation-proof-and-32-projections-precision

**Scope(s):** OBJECT · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** 32 subsets, at most 32 distinct relations, ≈_X is an equivalence relation for every X (pullback of equality) · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G0480: labels `approx-x-equivalence-relation-proof-and-32-projections-precision` and `sigma-five-axis-flattening-and-derived-repairs` — explicit agent-stated uncertainty (batch B0051): the two proven facts about the approx_X observational-equivalence family (genuine equivalence relation per subset; 32 subsets give 32 candidate projections, not necessarily 32 distinct partitions) are flagged as possibly relating to the sigma five-axis flattening/repairs object.

## Sources (how this label entered the ledger)
- PROPOSAL · batch B0051 · scope OBJECT — "Two proven mathematical facts about the ≈_X observational-equivalence-candidate family: (1) ≈_X is a genuine equivalence relation for every subset X⊆{A,S,R,V,C}, since it is the pullback of equality along the projection π_X (always reflexive/symmetric/transitive); (2) the 32 subsets of {A,S,R,V,C} give 32 candidate PROJECTIONS, not necessarily 32 distinct equivalence relations, since different subsets can induce the same partition when axes are degenerate."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2126 §"`≈_X(Σ₁,Σ₂) ⟺ π_X(Σ₁)=π_X(Σ₂)` | **Σ** | ✅ | **PASS as a construction.** Is it an equivalence relation? ✅ **YES for every `X`** — a pullback of equality along `π_X` is always reflexive, symmetric, transitive"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2126. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded. Since no retraction/supersession/contradiction evidence is present, this lifecycle label is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2126 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2126 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2126] types=[EXPERIMENTAL-RESULT, VALIDATION] scope=OBJECT — "Proves, as a genuinely new mathematical fact within this audit, that approx_X (defined as Sigma_1 approx_X Sigma_2 iff pi_X(Sigma_1)=pi_X(Sigma_2)) IS an equivalence relation for every subset X, since it is a pullback of equality along the projection pi_X, which is always reflexive, symmetric and transitive by construction -- this holds as a mathematical PASS regardless of whether X has any architectural meaning." (anchor: "`≈_X(Σ₁,Σ₂) ⟺ π_X(Σ₁)=π_X(Σ₂)` | **Σ** | ✅ | **PASS as a construction.** Is it an equivalence relation? ✅ **YES for every `X`** — a pullback of equality along `π_X` is always reflexive, symmetric, transitive")
- [S2126] types=[CORRECTION] scope=OBJECT — "Corrects the '32 candidate relations' claim: there are exactly 32 subsets of {A,S,R,V,C} and hence 32 candidate projections, but they are not necessarily 32 DISTINCT equivalence relations on Sigma, since different axis subsets X can induce the same partition when some axes are degenerate -- the precise phrasing is '32 candidate projections, inducing at most 32 distinct relations.'" (anchor: "⚠️ **Precision on '32':** `|𝒫({A,S,R,V,C})| = 32` **subsets**, hence **32 candidate relations** — but they are **not 32 distinct equivalence relations on `Σ`** in general, since different `X` can induce the same partition when axes are degenerate. **Correct phrasing: '32 candidate projections, inducing at most 32 distinct relations.'**")
- [S2126] types=[CORRECTION, RESTATEMENT] scope=CROSS-OBJECT — "Executes the P9 Step-288 boundary audit and produces a final wording-correction table of four sentences that could mislead a later engineer into thinking equality is solved, each now corrected: 'approx gets a derivable parameterisation' -> 'a DERIVED candidate form; selection of X remains normative'; 'K_{t+1}>K_t gets a product order' -> 'Sigma gets a candidate order; K gains nothing'; 'what closes it: Decision 3 + an axis subset' -> 'closes the currently identified branches -- not the complete contract'; '32 candidate relations' -> '32 candidate projections, inducing at most 32 distinct relations.' Also produces three explicit lists: what's CLOSED by 287, what's BOUNDED but open, and what's DEFERRED to Step 288 or later." (anchor: "| Was | Now | | *'`≈` gets a derivable parameterisation'* | *'a `DERIVED` **candidate** form; selection of `X` remains normative'* | | *'`K_{t+1} ≻ K_t` gets a product order'* | *'`Σ` gets a candidate order; `K` gains nothing'* | | *'what closes it: Decision 3 + an axis subset'* | *'closes the **currently identified branches** — not the complete contract'* | | *'32 candidate relations'* | *'32 candidate **projections**, inducing **at most** 32 distinct relations'* |")

## Notes for P3
(Own observation.) This label is a tight, self-contained wording-precision audit (a single source S2126 explicitly performing a "P9 Step-288 boundary audit") rather than a new construction: all three rows are corrections/precisions of prior overstated phrasings (a proof that stays a proof, a count claim narrowed from "32 relations" to "32 projections, at most 32 distinct relations", and three prior sentences rewritten to avoid implying the equality/composition question is closed). It reads as unusually careful/high-confidence for a DORMANT, single-source label — worth checking against `sigma-five-axis-flattening-and-derived-repairs` (G0480) since that label may contain the surrounding context (Decision 3, the axis-subset selection question) this one only references.
