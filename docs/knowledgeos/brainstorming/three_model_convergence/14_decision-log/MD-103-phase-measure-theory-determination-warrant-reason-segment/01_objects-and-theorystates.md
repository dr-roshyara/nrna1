# MD-103 §01 — Document→Object Impact Map and TheoryState(t) Updates

Method: for each document, all theory-bearing objects it touches are listed together (document-first,
multi-object), never serialized into single-object investigations. Every TheoryState update is a new
entry appended to that object's own trajectory — no prior entry (MD-100/101/102's own) is overwritten.

## Document→Object Impact Map

| Pos | File | Objects touched (born/refined/challenged) |
|---|---|---|
| 25 | extraction-retrieves-material... | `Extraction` (B), `Determination` (B), `Warrant` (B), `Fact` (R, formulation 4 of the running count), `Admission` (B), justification-ancestry-as-DAG (B) |
| 26 | refinement-of-the-model | `Reason` (B), `Justification` (B), `Fact` (R, formulations 5-6), `Warrant` (R, first formalized signature `Warrant(p\|R,C,t)`), Assessment(p,context,time,basis) (B, proposed), "Kernel preserves substrate/regimes interpret" principle (reinforced) |
| 27 | better-formulation-of-the-position | `Fact` (R, formulation 7, `F=(p,v)`), `E_t(p)` (B, graded epistemic evaluation of a proposition), "Knowledge ≠ Probability but may contain probabilistically assessed facts" (reinforced) |
| 28 | important-correction-to-the-framework | `Evidence` (challenged: conditional, not certain), `Reason`/`Reasoning` (R, conditional `R\|E,C,t`), `Determination` (R, `D(p\|E,R,C,t)`), `Fact` (R, formulation 8, `Fact_t(p\|C,R,E)`), `K_t^*` (CHALLENGED — second, competing gloss), `ℰ_t(p)` (B, near-duplicate notation to `E_t(p)`) |
| 29 | correction-to-the-synthesis-conclusion | `Evidence` (R, reclassified as relation `Supports(O,p,C,t)`), `Determination lineage`/"epistemic provenance" (B, proposed invariant candidate), `Knowledge Trajectory` (reinforced, not new), Question-A-vs-Question-B split (B) |
| 30 | conditional-evidence-problem-research-synthesis | KST apparatus (reinforced, external, not adopted), 8-mechanism evidence-combination landscape (B, reconstruction-adjacent commentary), Kernel/Regime/Projection architecture (reinforced) |

(B) = born this segment. (R) = refined/re-formulated this segment, prior formulation(s) preserved,
not overwritten. (CHALLENGED) = a competing, unreconciled second formulation recorded for an
already-tracked object.

## TheoryState(t) updates, per object

### `Extraction`
- **t(pos25)**: NAMED. Characterized only in contrast to `Determination` ("Extraction retrieves
  material"). No independent type signature given anywhere in this segment. Status: NAMED,
  informally characterized, NOT TYPED.

### `Determination`
- **t(pos25)**: NAMED, TYPED, DEFINED (informally). `F = D(O,P,M,A,H)` — `D`=determination process;
  `O`=observations, `A`=actions/doings, `P`=principles, `M`=methods, `H`=historical determinations.
  "Determination is an epistemic act."
- **t(pos28)**: NAMED, TYPED, DEFINED (informally, refined signature). `D(p|E,R,C,t)` — determination
  of proposition `p` conditional on evidence `E`, reasoning `R`, context `C`, time `t`. Relationship to
  the pos25 formulation: **apparent continuation/refinement within the same conversation, not an
  explicitly declared identity** — recorded per the discipline distinguishing "apparent continuation"
  from "explicit identity"; both formulations preserved as separate evidence events.
- Not DERIVED (no proof of any structural property), not COMPUTABLE (no algorithm), not EXECUTED, not
  VALIDATED, at either t.

### `Warrant`
- **t(pos25)**: NAMED (first lexical occurrence: "what that material warrants asserting"). No formal
  signature yet.
- **t(pos26)**: TYPED, DEFINED (informally). `Fact(p) ⇐ Warrant(p|R,C,t)` — Warrant is a relation
  taking a proposition, a reason, a context, and a time, and (if satisfied) licensing `Fact(p)`. Not
  DERIVED/COMPUTABLE/EXECUTED/VALIDATED — no computation rule for when `Warrant(p|R,C,t)` actually
  holds is given anywhere.

### `Reason`
- **t(pos26)**: NAMED, TYPED, DEFINED (informally, as a tuple). `Reason = (Evidence, Facts, Rules,
  Principles, Methods, Arguments, Context)`. Seven reason-types enumerated (Empirical, Logical,
  Historical, Procedural, Institutional, Legal/normative, Probabilistic) — explicitly, "Probability is
  one kind of reason. It is not the definition of reason." Distinguished from `Justification`
  ("why should this determination be made?" vs. "why is that reason legitimate?").
- **t(pos28)**: reasoning itself is asserted CONDITIONAL — `R|E,C,t`, and `(A∧C)⇒B` (an implication's
  own validity depends on background conditions `C`, not stated unconditionally). This is a
  REFINEMENT of the pos26 characterization, not a contradiction.

### `Justification`
- **t(pos26)**: NAMED, TYPED, DEFINED (informally). `J(R,C) ⇒ Valid(R)` — Justification is what
  establishes a Reason's legitimacy under applicable rules/principles, distinct from the Reason
  itself. Not DERIVED/COMPUTABLE/EXECUTED/VALIDATED.

### `Fact` (continuing MD-102's own trajectory: NAMED as central open problem, ten qualifying
criteria all UNRESOLVED as of MD-102)
- **t(pos25)**: further formulation — `F = D(O,P,M,A,H)` (same formula as `Determination`'s own
  signature; Fact here is essentially the OUTPUT of the Determination process). Five kinds of "fact"
  enumerated: Observed, Historical, Procedural, Normative, Derived — each distinguished by its own
  inference structure, none formalized beyond a short prose gloss.
- **t(pos26)**: `Fact(p) ⇐ Warrant(p|R,C,t)`; then `F = (p, reason, context, time, provenance)`,
  explicitly marked by the source itself as "a candidate representation... we should not yet freeze
  this tuple."
- **t(pos27)**: `F = (p,v)` — proposition plus a graded epistemic-assessment value `v`, "does not yet
  mean probability."
- **t(pos28)**: `Fact_t(p|C,R,E)` — fact-status now explicitly indexed to context/reasoning/evidence
  and time, revisable when context/evidence/reasoning change; "we should probably stop saying 'a fact
  is true.'"
- **Running count**: at least eight distinct structural formulations of `Fact` now on record across
  MD-102+MD-103 (MD-102's own two "Fact is the missing bridge" framings, plus these four/five here),
  none merged, none declared canonical. Status unchanged from MD-102: NAMED only, not TYPED with
  consensus, not DEFINED with consensus — the proliferation itself is the finding, not a resolution.
- **The Fact Problem's ten sub-questions (per the user's most recent instruction)**: (i) birth —
  unchanged, established MD-102 (positions 20-23), not reopened here. (ii) birth-state definition —
  unchanged. (iii) typed? — still informally only, multiple competing types now on record, none
  reconciled. (iv) relation to Observation — reinforced (`Observation → ... → Fact` chains recur in
  every file this segment, always informal). (v) relation to Evidence — SHARPENED this segment: `Fact`
  now explicitly depends on `Warrant`/`Reason`, which themselves depend on `Evidence` — a longer chain
  than MD-102 had, not a shorter one. (vi) relation to Knowledge — reinforced (`Fact → Knowledge State`
  chains recur). (vii) transformation/evaluation function introduced? — yes, `D`/`Determination` is
  now explicitly that function, across two signatures. (viii) qualifying criteria operational? —
  still NO; no computation rule for `Warrant`/`Determination`/`Fact_t` given anywhere this segment.
  (ix) later refinement/rejection/abandonment? — the segment IS a continuous refinement chain
  (pos25→26→27→28), no rejection of any prior formulation found. (x) explicit successor/
  implementation/abstraction/equivalence declared? — NO; each new formulation is offered as
  "better"/"a correction" in narrative terms, never as a proven successor.

### `Evidence`
- **t(MD-102 and earlier)**: treated informally as an object/input throughout.
- **t(pos28)**: characterized as itself CONDITIONAL — `E|C,t` — not certain/absolute.
- **t(pos29)**: RECLASSIFIED as a RELATION, not an object — `Supports(O,p,C,t)` — explicitly
  challenging "raw evidence" terminology ("An observation is not necessarily evidence until it is
  interpreted relative to a proposition/hypothesis"). Genuine DDD-significant finding: Evidence-as-
  Entity vs. Evidence-as-Relation is now a live, unreconciled distinction in this lane's own text.
  Kernel-preservation principle correspondingly revised: "Kernel preserves observations and their
  provenance/context" rather than "Kernel preserves raw evidence."

### `Acceptance`
- **SEARCHED, NOT FOUND** as a distinct named lexical/formal object anywhere in positions 25-30
  (bounded negative finding, this segment only). `Admission` appears instead (pos25: "Admission
  records the resulting epistemic status") — recorded as a possible terminology variant, NOT merged
  with `Acceptance` without further corpus evidence; both remain tracked as textually distinct terms.

### `Rule` / `Criterion` / `Material`
- All three remain component-role-only this segment: `Rule` appears inside `Reason`'s own tuple and
  inside the conditioning set `C` of `(A∧C)⇒B`; `Criterion`/"criteria" appears only via reference back
  to MD-102's own ten Fact-qualifying criteria (not re-derived here); `Material` appears only in the
  phrase "Extraction retrieves material" (pos25). None reaches independent TYPED/DEFINED status this
  segment — status: NAMED (component-role only).

### `K_t^*` (born MD-102: `Cn_{R_t}(S_t∪F_{≤t})`, NAMED/TYPED/DEFINED, an "ideal Knowledge state")
- **t(pos28)**: a SECOND, competing formulation recorded: `K_t^*(p|C_t)`, explicitly conditional and
  possibly irreducibly non-crisp — "the ideal Knowledge state may itself be conditional... may
  legitimately say `P(p|C_t,E_t)=0.73`. That does not necessarily mean our Knowledge is incomplete...
  Ideal Knowledge ≠ omniscience." Two distinguished gaps introduced: Gap 1 (epistemic incompleteness,
  `K̂_t < K_t^*`) vs. Gap 2 (irreducible uncertainty, even `K_t^*` itself is uncertain). **This
  formulation is NOT merged with MD-102's own closure-operator gloss** — both preserved as separate,
  unreconciled evidence events under the same symbol `K_t^*`. Status remains NAMED/TYPED/DEFINED at
  best for either gloss; neither DERIVED/COMPUTABLE/EXECUTED/VALIDATED.

### `E_t(p)` / `ℰ_t(p)` (new this segment)
- **t(pos27)**: `E_t(p)` born — "the epistemic evaluation of the proposition at time t," distinguished
  from the proposition `p` itself and from any particular probabilistic representation `P_t(p)`.
- **t(pos28)**: `ℰ_t(p)` born — "the epistemic state of proposition p" — near-identical role, distinct
  symbol, same short conversation/session.
- **t(pos29)**: `E_t(p)` reused again — `E_t(p) = P(p|O_{≤t},F_{<t},R,C_t)` under a probabilistic
  regime — reinforcing `E_t(p)` as the more stable of the two notations across this segment.
- **Relationship to `Φ` (MD-101/102) and `Status(k,t,C)` (MD-102)**: `Φ`/`Status(k,t,C)` operate over
  a knowledge item `k` or a knowledge-state `𝒦`/`K̂_t`; `E_t(p)`/`ℰ_t(p)` operate over a bare
  proposition `p`. Different argument types. Per the standing discipline (same-type≠same-semantics,
  chronological/notational continuity within one author's own conversation is not itself proof of
  identity), the relationship among `Φ`, `Status(k,t,C)`, `E_t(p)`, `ℰ_t(p)`, and `K_t^*` is recorded
  as **IDENTITY UNRESOLVED** across the whole family — a fifth entry in what is becoming a crowded,
  unreconciled "epistemic evaluation function" cluster.

### "Kernel preserves substrate; regimes interpret" principle
- Reinforced twice more this segment (pos26: "Kernel preserves the epistemic substrate; regimes
  provide mathematical interpretations"; pos29: "Kernel preserves observations and their provenance/
  context... Evidence is potentially a relation"; pos29 also explicitly splits Question A
  [mathematical: what is invariant across regimes] from Question B [Kernel: what substrate must be
  preserved]). **This is continuation within the same author's own single conversation/session, not
  independent cross-lane corroboration** — recorded explicitly as such, distinct from the earlier,
  genuinely cross-lane recurrence of a structurally similar principle noted in MD-096/098
  ("Kernel preserves; regimes reason").

### Position 30's own content (the uploaded synthesis)
- Classified per standing discipline: **reconstruction-adjacent commentary / in-programme AI
  synthesis**, not corpus-native theory, not independent external source (it discusses KST, Bayesian,
  Dempster-Shafer, fuzzy, non-monotonic, argumentation, epistemic logic, temporal logic, measurement
  theory — all EXTERNAL THEORY, explicitly not adopted, consistent with MD-101/102's own external-
  theory handling). Its own central open question ("what is invariant across different conditional
  evidence combination mechanisms applied to the same substrate?") is recorded as a research question
  this segment inherits and responds to (positions 28-29's own "conditional determination" framing),
  not as an answer.
