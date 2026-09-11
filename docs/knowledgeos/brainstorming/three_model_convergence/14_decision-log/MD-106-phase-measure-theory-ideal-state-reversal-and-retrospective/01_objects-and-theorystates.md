# MD-106 §01 — Document→Object Impact Map and TheoryState(t) Updates

## Document→Object Impact Map

| Pos | File | Objects touched (born/refined/challenged) |
|---|---|---|
| 36 | business-example-of-the-conditional-problem | Three-level problem decomposition (B, Business/Scientific/KnowledgeOS), Kernel-as-audit-memory metaphor (B), `Information→Evidence→Reasoning→Determination→Knowledge` chain (reinforced) |
| 37 | major-clarification-of-the-model | `I_t` (B, Ideal/Reference State), `Î_t` (B, extracted/estimated ideal state), "Information about ideal state ≠ Knowledge of ideal state" (B), six candidate sources for `I_t` (B), Model Reversal itself (B, IDEAL-STATE-FIRST pipeline) |
| 38 | new-important-distinction-introduced | `𝒦_t` (R, infinite Knowledge Space, `I_t⊆𝒦_t`), `K_t=Π_t(I_t)` (B, projection formula), three completeness types (B: Explicit/Structural/Question-relative) |
| 39 | every-information-clarification | `K_t^A⊆I_t` (B, observer-indexed extracted state), three-way disambiguation of `I_t` (B: logically-possible/true/knowable) |
| 40 | example-exposes-flaw-in-the-previous-model | "Ideal state itself is Knowledge" (B, recursive structure), `Old(x)` relational property (B), Descriptive vs. Normative ideal state (B), `K_t=Compare(O_t,I_t,R_t)` (B, comparison formula) |
| 43 | very-important-refinement-of-the-model | `D_t`/`D̂_t` (B, dimension vs. discovered-dimension), two incompleteness types (B: value vs. dimension uncertainty), `C_fact`/`C_coverage`/`C_model` (B, three confidence types), `P(D,X\|O)` (B, joint discovery-estimation formula) |
| 44 | the-key-distinction | `D_t→D̂_t` (R, dimension-discovery problem) vs. `I_t(D̂_t)→K_t` (R, state-extraction problem) — formalized as two separate extraction problems; "is dimension the right primitive?" (B, open meta-question) |
| 45 | where-we-started-model-challenge | Landmark self-assessment (B: 18-row status table, 60-70%/20-30% self-rating); sixth-way Knowledge-kind taxonomy (B: Descriptive/Evaluative/Normative/Inferential/Probabilistic/Historical); `Fact = a determined proposition under specified conditions` (R, further Fact formulation); three-case empirical falsification test proposed (B, not executed) |

(B) = born this segment. (R) = refined, prior formulation(s) preserved, not overwritten.

## TheoryState(t) updates, per object

### `I_t` / Ideal-Reference-State (new major object family, born position 37)
- **t(pos37)**: NAMED, TYPED, DEFINED (informally). `I_t` = "the complete state of what is supposed to
  be known/true/valid... at that point in time." `Î_t` (our current extracted/estimated representation
  of it) explicitly distinguished: `Î_t ≠ I_t` in general. Six candidate sources enumerated (A: Reality,
  `I_t=Reality_t`; B: HumanModel; C: NormativeModel; D: DomainModel; E: mathematical ideal `I_t=I*`;
  F: combination `I_t=f(Reality,Rules,Principles,Domain,HumanKnowledge)`) — explicitly: "We should not
  assume which one it is."
- **t(pos38)**: `I_t ⊆ 𝒦_t` (subset of the infinite Knowledge Space), `I_t` may itself be infinite;
  `K_t = Π_t(I_t)` — a projection/extraction formula. Three completeness notions introduced: explicit
  (impossible if unbounded), structural (rules/relations sufficient to derive answers), question-
  relative (sufficient to answer all questions in a defined space) — "#2 and #3 will be much more
  important than #1."
- **t(pos39)**: `I_t` = "the totality of all possible information about the object/domain at time t";
  `K_t^A ⊆ I_t` (observer-indexed); a three-way disambiguation flagged as "the next critical question":
  logically-possible information vs. true information vs. knowable information.
- **t(pos40)**: "The ideal state itself is Knowledge" — a recursive structure (someone had to establish
  the reference value via its own prior Observation→Evidence→Determination chain); Descriptive ideal
  (`I_t^descriptive`) vs. Normative ideal (`I_t^normative`) distinguished as genuinely different;
  `Old(x) ⟺ x < ReferenceVersion_t` given as a worked example of a relational (not intrinsic) property;
  `K_t = Compare(O_t, I_t, R_t)` — a comparison-based Knowledge formula, distinct from pos38's
  projection formula `K_t=Π_t(I_t)` — BOTH preserved, not reconciled.
- **t(pos45, self-correction)**: "the phrase 'the ideal state' is dangerous" — explicitly retracts the
  assumption of a single universal ideal state; proposes domain-indexed variants instead
  (`I_t^business`, `I_t^architecture`, `I_t^security`, `I_t^legal`, `I_t^vendor`). Self-rated status:
  "Reference/ideal state is important" = 🟢 strong hypothesis; "One universal ideal state" = 🔴 NOT
  established; "Knowledge as subset of ideal state" = 🟠 interesting but unproven.
- **Relationship to prior tracked objects**: `I_t` is explicitly NOT merged with `K_t^*` (MD-102/103's
  closure-operator/conditional-assessment object), `S_Kernel` (MD-104's substrate hypothesis), or any
  prior Fact/Determination formulation — tracked as its own, competing pipeline-orientation family.
  The "Model Reversal" itself (Ideal-State-First vs. Observation-First) is recorded as a genuine
  alternative pipeline direction, not a refinement of the Observation-first chains tracked since
  MD-100 — both preserved as distinct, unreconciled formulations of the overall extraction process.

### `𝒦` / Infinite Knowledge Space (continuing MD-101's own trajectory)
- **t(pos38)**: reinforced as `𝒦_t`, with `I_t⊆𝒦_t`; explicitly still "a research concept, not a
  mathematical definition" per position 45's own retrospective self-rating (🟠 "useful hypothesis,
  undefined").

### `D_t` / `D̂_t` — Dimension / Discovered-Dimension (new object family, born position 43)
- **t(pos43)**: NAMED, TYPED (a set `D={d_1,d_2,...}`). Two incompleteness types distinguished: (1)
  value uncertainty — known dimension, unknown value, `P(value|O)`; (2) dimension uncertainty —
  unknown whether a dimension exists at all, `P(dimension relevance|O)`. `P(D,X|O)` proposed as a
  joint discovery-and-estimation formula, explicitly "a research hypothesis," not adopted as canonical.
- **t(pos44)**: formalized as two separate extraction problems: `D_t → D̂_t` (dimension discovery,
  "which dimensions exist/relevant?") and `I_t(D̂_t) → K_t` (state extraction, "what do we know about
  those dimensions?"). Open meta-question raised, not resolved: "is 'dimension' the right primitive at
  all, or is this more fundamentally a space of possible questions/propositions about the ideal
  state?" — flagged as potentially decisive for the eventual mathematical model, left unresolved.

### `C_fact` / `C_coverage` / `C_model` — three confidence types (new, born position 43, reinforced 44)
- NAMED, TYPED (three separate scalar confidences), DEFINED only informally: `C_fact` = confidence a
  particular determination is correct; `C_coverage` = confidence the relevant dimensions have been
  identified; `C_model` = confidence in the model of relationships between dimensions. Explicitly:
  "these are three different epistemic properties," not one composite number.

### Fact/Knowledge formula ledger (continuing MD-102-105's own trajectory — now at least eleven
distinct formulations)
- Adds `K_t=Π_t(I_t)` (pos38), `K_t=Compare(O_t,I_t,R_t)` (pos40), and position 45's own explicit
  restatement `Fact = a determined proposition under specified conditions` (a further, self-labeled
  "strong hypothesis, not yet proven across all types of knowledge") to the running ledger. None
  merged; none declared canonical.

### Sixth-way Knowledge-kind taxonomy (new, born position 45)
- Descriptive / Evaluative / Normative / Inferential / Probabilistic / Historical — explicitly: "we
  should not assume all of these are identical objects... Knowledge is a family of epistemic
  determinations, rather than one homogeneous object." Status: 🟢 "important finding" per the source's
  own self-rating.
- **Relationship to the five-kinds-of-Fact taxonomy** (position 25/MD-103: Observed/Historical/
  Procedural/Normative/Derived): NOT merged despite lexical overlap on `Historical` and `Normative` —
  the two taxonomies classify different things (kinds of Fact vs. kinds of Knowledge) and arose from
  different documents four days apart in the same lane; per the standing discipline, same-spelling
  does not imply same-object. Recorded as a homonym-risk, unresolved.

### The corpus's own landmark self-assessment (position 45) — transcribed as source evidence, not
re-derived by this reconstruction
- An 18-row table rating: Knowledge≠probability (🟢), Information≠Knowledge (🟢), Knowledge changes
  with time (🟢), Observation is context/comparison-dependent (🟢), Reference/ideal state important
  (🟢 hypothesis), one universal ideal state (🔴 not established), Knowledge as subset of ideal state
  (🟠 unproven), Infinite Knowledge Space (🟠 undefined), Probability as uncertainty regime (🟢),
  Probability as universal extraction mechanism (🔴 rejected), Dimension uncertainty (🟢), Value
  uncertainty (🟢), Determination (🟠 central but undefined), Fact as determination (🟠 strong
  hypothesis), Multiple kinds of knowledge (🟢), Epistemic equivalence (🔴 unresolved), Minimum
  substrate (🔴 not solved), KnowledgeOS Kernel (🔴 **not ready to define**).
- Explicit overall self-rating: **"perhaps 60–70% of the conceptual framing, but only 20–30% of the
  formal problem."**
- A three-case empirical falsification test proposed (Descriptive: "Nexus is running version 2.69";
  Evaluative: "Nexus 2.69 is not acceptable"; Predictive: "Nexus will probably fail under condition X")
  with an eleven-item documentation template per case (observed/assumed dimensions/discovered
  dimensions/prior knowledge/reference state/rules/uncertainty/determination/reasoning/time/change-
  trigger) — explicitly NOT executed within this file; a genuine open research proposal, its execution
  (if any) not yet located in this reconstruction's own reading.
- Restated research sequence (position 45's own closing diagram): Phenomenon → mechanisms → invariant
  → what-must-be-preserved → Kernel admission test → **only then** KnowledgeOS Kernel — directly
  consonant with (and independently reinforcing) the "Refusal" file's own nine-stage sequence already
  logged in MD-105 (seq 0353).
