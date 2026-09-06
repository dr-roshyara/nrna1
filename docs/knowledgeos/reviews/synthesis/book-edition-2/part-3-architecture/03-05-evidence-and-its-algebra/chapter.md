# III.5 · Evidence and Its Algebra

> **Edition 1 abstract** *(frozen baseline, verbatim opening)*: Evidence in the ratified model is
> structured, not scalar: it carries dependency structure and roles, and two invariants — the only
> two that are computationally TESTED rather than read — govern how it may combine. — *Full
> Edition-1 text: `../../../book/part-3-architecture/03-05-evidence-and-its-algebra/chapter.md`.*

## 1 · Why evidence needs an algebra at all — *status: fact [FA] + interpretation [IN] marked*

[FA] Chapter III.2 built the frame; III.4 will compute how the knowledge state falls short of it.
Between them sits the question this chapter owns: **what is the raw material of knowing, and by
what rules may pieces of it combine?** The question sounds administrative and is actually the
model's most dangerous edge, because every intuitive answer is wrong in a recorded way. More
copies feel like more support (they are not). A fluent restatement feels like a second witness (it
is not). A single confidence number feels like a summary of the evidence (it is a destruction of
it). [IN] The corpus's designers understood that an epistemic system dies here first — quietly,
by amplification and collapse, long before any grand architectural failure — and so this is the
one place in the whole model where a genuine adversarial experiment was designed, run, and
recorded. This chapter teaches that experiment at full depth: what evidence IS in the sources,
what the designed test suite demanded, what the executed matrix actually established, and what
the architecture concluded it could — and could not — claim.

## 2 · What evidence is — *status: definition (explains existing authority [FA] + source evidence [E])*

> **Definition — Evidence**
> *Notation (source, Step 049 §49.7):* `Evidence = Observation + Relevance + Context` — evidence
> is a **qualified observation**, not a primitive. *Notation (source, EXP-01 design, richer):*
> `e = (P, π, ρ, κ, τ, δ, …)` — an evidence item carries its **proposition** (what it bears on),
> **polarity** (for or against), **provenance** (where it came from), **quality**, **context**,
> **time**, and **dependency** (what it derives from).
> *Semantics:* an observation admitted as relevant support (or counter-support) for a proposition,
> carrying enough structure that combination rules can respect its origin, its direction, its age,
> and its ancestry.
> *Scope:* representation only — what an item IS. What items DO together is Layer-2 aggregation
> (§4); what conclusions follow is Layer-3 inference. The source is explicit that these three must
> never be conflated.
> *Evidence/grade:* the reduction `Evidence = QualifiedObservation` is READ (049 §§49.7/49.76,
> carried into the ratified derived-structure list); the seven-field representation is
> [E — source-specified], design-level: it framed the experiment and was never itself ratified
> as an L2 object signature.
> *Relations:* built on the primitive **Observation** (III.3); bears on **Propositions** (and the
> source equates Claim = Proposition); its dependency field δ is what invariants I-5/I-6 consume;
> its lifecycle runs through the L3 Evidence Record and Verification Gate (§11).
> *Example:* §§9–10. *Limitation:* "epistemically equivalent" (the ~ relation the duplicate rule
> needs) is used by the sources and never defined — the sources say so themselves (§13).

[FA] Two consequences of the definition do heavy work downstream. **Evidence is not a primitive**:
in the ratified model's reduction, evidence is derived structure over Observation — which is why
the model can insist that every evidence item has an ancestry (you can always ask *what
observation, under what qualification?*). And **evidence is directional**: the polarity field
means support-for and support-against are distinct citizens that no operation may net against
each other into a single number (criterion E, §5; Article 2's prohibition is the constitutional
shadow of the same point).

## 3 · Existence is not sufficiency — *status: fact [FA]*

[FA] The model separates two questions that ordinary language merges. **Existence:** is there an
admitted, qualified observation bearing on r? — a property of the evidence store. **Sufficiency:**
does what exists satisfy the requirement to the standard the frame set? — a property computed by
the gap function against the EpistemicContract, and adjudicated at admission under the
AcceptancePolicy. Evidence can exist abundantly and suffice for nothing (a hundred copies of one
observation); it can be sparse and suffice completely (one attested custody log where the contract
asks exactly for one). [FA] Nothing in this chapter's algebra ever answers "enough?" — that answer
belongs to III.4 (the typed gap) and III.6 (Determination). The algebra answers only: *what does
this collection of qualified observations legitimately amount to?*

## 4 · The three layers of the evidence calculus — *status: source evidence [E], design-level*

[E] The experiment's design begins with a separation the sources box as critical:

```
Layer 1  REPRESENTATION   e = (P, π, ρ, κ, τ, δ, …)      what an item is
Layer 2  AGGREGATION      ⊕ρ                              what items amount to together
Layer 3  INFERENCE        Γ ⊢ρ P   (or P(H|E))            what conclusions follow
```

with the rule **Aggregation ≠ Inference** — a "score formula" and an inferential framework are
different kinds of thing (the design notes Bayesian inference is "not merely an alternative score
formula"). [E] The subscript ρ matters: both aggregation and inference are **policy-indexed** —
criterion J below makes different policies legitimately produce different assessments of the same
evidence. [IN] This is the chapter's quiet link back to III.2 and forward to III.6: even the
mathematics of combination answers to a governed policy, which answers to the frame.

## 5 · The designed test suite — ten criteria, stated — *status: source evidence [E]; per-criterion evidentiary status per PF-4*

[E] The design document proposes ten properties any candidate aggregation operator must face.
Stated at source strength, with each criterion's ACTUAL evidentiary status (a distinction this
book owes the reader — see §7 for why the statuses differ):

- **A · Duplicate invariance.** If e₁ ~ e₂ (epistemically equivalent, same underlying evidence),
  then A(E ∪ {e₁,e₂}) = A(E ∪ {e₁}). Copies add nothing. *Matrix-tested ✓.*
- **B · Independent corroboration.** For genuinely independent e₁ ⊥ e₂:
  A(e₁,e₂) ≻ A(e₁) — independence must strengthen, under the policy's ordering. *Matrix-tested ✓.*
- **C · Order invariance.** A(e₁,e₂) = A(e₂,e₁): arrival order must not matter. *Matrix-tested ✓.*
- **D · Associativity.** A((e₁⊕e₂)⊕e₃) = A(e₁⊕(e₂⊕e₃)) — incremental processing must not change
  the outcome. *Designed; NO recorded matrix outcome (PF-4).*
- **E · Contradiction preservation.** Given e⁺ ⇒ P and e⁻ ⇒ ¬P, the result must retain BOTH —
  never `Score(P)=0.73` without Support(P) and Support(¬P) kept separately.
  *Designed; discussed narratively in the verdict (§8 below); not a matrix column (PF-4).*
- **F · Dependency awareness.** If e₂ = LLM(e₁) then e₂ ⊥̸ e₁ — a derived item must never count
  as independent corroboration. *Matrix-tested ✓ (and the verdict's strongest lesson, §7).*
- **G · Irrelevance.** If Relevance(e,P) = 0, e must not materially strengthen P. *Matrix-tested ✓
  — with the source's own footnote that for the raw MAX/mean operators this is handled by the
  normalization layer, not the operator.*
- **H · Temporal validity.** Stale evidence must not pass as current — **but stale ≠ nonexistent;
  it remains historically valuable.** *Matrix-tested ✓ (as "Stale retained").*
- **I · Context dependence.** The same evidence may legitimately assess differently under C₁ ≠ C₂
  — "not a mathematical defect; an explicit property of our model." *Designed; NO matrix outcome.*
- **J · Policy dependence.** A_ρ₁(E) ≠ A_ρ₂(E) may be legitimate. *Designed; NO matrix outcome.*

Plus two statistical cautions the design elevates: **Calibration** ("the most important
statistical test": if a system says P(H)=0.9, about 90% of such cases must actually hold —
otherwise "0.9 is merely a number pretending to be probability"; hence the source's refusal to
casually convert epistemic states into probabilities) and **conditional independence** (Bayesian
multiplication assumes it; real evidence chains violate it constantly). *Both designed as
concerns; neither a matrix column.* [E] The executed matrix additionally reports **Bounded [0,1]**
— an operator-hygiene column that appears in no designed criterion. The design-to-execution gap is
recorded as production finding PF-4 and taught here as it stands: **seven properties have recorded
outcomes; four designed criteria and calibration do not.** The ratified phrase "7 properties"
describes the executed matrix.

## 6 · The four candidates — *status: source evidence [E]*

[E] Four aggregation operators entered the experiment, all over a shared normalized evidence
universe (so representation-layer differences could not contaminate the comparison):

- **A₁ = max** — the strongest single item wins; more items never help beyond the best one.
- **A₂ = weighted mean** — items average, weighted by quality.
- **A₃ = 1 − ∏ᵢ(1 − sᵢ)** — "saturating" accumulation: each item closes part of the remaining
  distance to certainty; bounded, monotone, diminishing.
- **A₄ = Bayesian-like updating** — multiplicative belief revision, with the design's own caution
  that genuine Bayesian inference is a Layer-3 framework, not a Layer-2 formula, and assumes an
  independence structure real evidence rarely has.

## 7 · What the experiment established — the matrix and the verdict — *status: derivation RECONSTRUCTABLE [E]; verdict quoted at exact strength*

[E] The executed outcomes, per the experiment's own prose record (the authoritative one — §8):
**MAX** passes duplicates, dependency, order, staleness, boundedness — and **fails independent
corroboration** (a second independent witness adds nothing beyond the best item: the one thing
evidence must do, MAX cannot). **Weighted Mean fails duplicates, corroboration, and dependency**
(averaging dilutes corroboration and double-counts copies). **Saturating and Bayesian-like pass
all seven matrix properties.** And yet the recorded verdict is negative:

> **"No simple scalar operator is sufficient as the KnowledgeOS epistemic foundation."**

[E — derivation, RECONSTRUCTABLE, reproduced from the sources] Why survival did not mean
selection — the verdict's own reasoning, in three steps:

**Step 1 — the dependency argument ("the strongest result: dependency must come first").** Take
the verdict's canonical case: one API observation, read by two LLMs.

```
API observation ──┬── LLM 1 reading
                  └── LLM 2 reading
```

A naive system counts **3 pieces of evidence**. A dependency-aware system sees **1 underlying
observation + 2 derived representations**, hence the boxed source law
`EvidenceCount ≠ IndependentEvidenceCount`. Now the point that defeats every scalar operator *as
foundation*: whether A₃ or A₄ scores this case correctly depends entirely on work done BEFORE the
operator runs — resolving the dependency graph, collapsing the derived items into their ancestor.
The operator never sees the problem it is being graded on. Therefore the ordering is
architectural, not optional: **dependency resolution precedes aggregation** — ratified as
invariant **I-6**, grade TESTED.

**Step 2 — the duplicate argument.** The adversarial run generated ~100 copies of one item; the
normalized model returns essentially the original's contribution, boxed in the source as
`e ~ e′ ⇒ Contribution([e]) = Contribution([e′])` and glossed as one of the architecture's most
important **anti-amplification** properties: `100 × same evidence ⇏ 100 × knowledge`. Ratified as
invariant **I-5** (with corroboration's obligation as its other half), grade TESTED. [E] Note the
same pre-operator dependence: recognizing the hundred as copies requires the equivalence relation
~ — which is representation-layer work, and (§13) an admittedly undefined one.

**Step 3 — the preserved-structure argument.** The verdict keeps support-for and support-against
visible — its result shape is a pair (S⁺, S⁻, …), never a single netted score — and cautions that
the Bayesian candidate's clean matrix row assumes an independence model reality does not supply.
A scalar output, however well the scalar behaves, discards exactly the structure criteria E, I and
J exist to protect. [E] Hence the architectural conclusion the sources draw and the model
ratified as a **non-claim**: the calculus needs the properties; it selects **no operator**
(OQ-3, open). Two candidates remain candidates. The foundation is the property set plus the
ordering law — not any formula.

## 8 · The evidence about the evidence — the CSV affair — *status: method/governance [M/E]*

[E] The experiment's outcomes exist in two artifacts that disagree in three cells: the prose
verdict record (the reasoning above) and a bare CSV export found on the repository side
(BAYES/irrelevance, WM/duplicates, MAX/irrelevance). The programme adjudicated the conflict by
ruling: **the prose record prevails** — it is internally consistent under its own normalization
footnote, while the CSV is inconsistent under any single reading and carries no generator, no
code, and no provenance; the CSV is graded *unreliable-standalone*, and the negative verdict is
unaffected under either source. [IN] The affair is disclosed in full not as trivia but as the
chapter's own medicine applied to itself: two artifacts claiming the same experiment are not two
witnesses (they share one ancestry), the derived one without provenance loses, and the
adjudication is a recorded act, not a preference. One further disclosure (GN-45, AF-F-22): the
ratified Evidence row's grade-basis line reads "TESTED — EXP-01 **+ CSV**"; following GN-27, this
chapter rests the grade on the prose record alone — a visible, registered correction of that basis
citation, not a silent one. [M] It also leaves an honest wound: the
experiment has **no surviving executable** — its evidentiary status rests on the prose record's
internal argument, which is why this book grades the two invariants TESTED *on the recorded
artifact's authority* and never claims independent replication (a designed successor suite exists
and has never run — OQ-5).

## 9 · Worked example, negative case first — the amplification failure — *status: illustration [IN]/[EDITORIAL]; never evidence*

*(Running example, stage 2 — the frame and contract r₁–r₅ are III.2 §10's.)*

**The failure.** Overnight, requirement r₂ (*an independent recount reproduces the tally*) appears
to strengthen dramatically: forty-one supporting items arrive. A scalar dashboard would glow
green. The structured store reads their tuples instead: thirty-eight are syndicated copies of one
wire report about the recount (π: same provenance; δ: same ancestor — duplicates, criterion A:
joint contribution ≈ one item); two are LLM summaries *of that same report* (δ: derived —
criterion F: not independent, corroborate nothing); one is the recount commission's own signed
tally file (independent — criterion B: this one genuinely strengthens). Net epistemic movement:
**one real item, not forty-one** — and, because polarity is preserved, the store also still shows
the one custody-log anomaly (e⁻ against r₃) that the celebratory average would have drowned.
**Three voices:** *conceptual* — exactly I-5/I-6 at work; *architecture* — the L3 Evidence Record
carries the source-role and dependency fields that make the collapse computable, and the
Verification Gate refuses the thirty-eight a second admission as "new" support; *implementation* —
**no such store exists today** (§12); this scenario runs on paper only.

## 10 · Worked example, positive case — sufficiency without abundance — *status: illustration [IN]/[EDITORIAL]*

**The success.** Requirement r₃ (*custody log continuous*) has exactly two SUPPORTING evidence
items — the sealed transport log (sensor-role, high κ, current τ) and the receiving officer's
attestation (human-role, independent provenance) — plus §9's preserved counter-item (the anomaly
e⁻), which the polarity field keeps visible rather than netting away *(count corrected for
example-continuity under GN-45, AF-F-18)*. Criterion B says the pair is stronger than either alone;
criterion H would age them if the certification dragged on; nothing here is duplicated or derived.
Two items — and against the contract, r₃ stands better than r₂ did with forty-one. [IN] The
example is the §3 distinction made vivid: sufficiency is a relation to the contract, not a count —
and the reader now has both directions: abundant-insufficient and sparse-sufficient.

## 11 · Evidence in the architecture — L3 — *status: fact [FA←E]*

[FA←E] The repository architecture realizes this chapter's discipline as structure. The **Evidence
Record** representation carries evidence + acquisition method + reliability conditions, making the
source-role separation explicit (a sensor measurement ≠ an expert statement ≠ an AI inference ≠ a
historical document — Article 3's third clause as a data model). The **Verification Gate** is the
only admission path — evidence supports; the gate admits; engines (including the LLM,
architecturally a candidate-generator at the gate's mouth) may propose and never possess. The
**Contradiction Registry** holds the (S⁺,S⁻) structure criterion E demands, as CONFLICTED states
that coexist until governed resolution. [E] Constitutional convergence, recorded in the
conformance pass: Article 2 forbids the single quality scalar outright ("Knowledge Quality = 0.87"
is its named failure signature) — the same prohibition the experiment reached empirically, from
the other side of the programme.

## 12 · Implementation honesty — L4 — *status: evidence [E]*

[E] What exists in code today for THIS chapter: **nothing that aggregates.** No evidence store, no
dependency resolver, no operator implementation, no normalization layer. The chapter's only L4
artifact is the CSV of §8 — data without a generator, adjudicated unreliable-standalone. The
designed follow-up suites (EG-05 family) are SPECIFIED, NOT EXECUTED. [FA] Any implementation
claim beyond this sentence would violate the production authorization, and none is made. For a
builder, §5 IS the specification: the ten criteria (plus calibration) are acceptance tests waiting
for an implementation, I-6 is an architectural ordering constraint on any pipeline, and OQ-12
notes that even the repository's documents nowhere state the dependency-first rule — one sentence
of governed text is missing before any code should be.

## 13 · Limitations and open questions — *status: register [U] + source-level watch items*

- **OQ-3 — operator selection: OPEN.** Two candidates survived seven executed properties; the
  verdict's reasoning shows why survival ≠ selection; closing act: a designed successor experiment
  with dependency-first architecture (and, per PF-4, the unexecuted criteria D/E/I/J and
  calibration are the obvious backbone of such a suite).
- **OQ-5 — the specified suites have never run.** The two TESTED invariants rest on one recorded
  experiment's prose record; replication would either harden or embarrass them, and the
  architecture's own standards should welcome both possibilities.
- **OQ-12 — the missing sentence.** Dependency-resolution-precedes-aggregation is TESTED in the
  model and stated nowhere in the repository's governed documents.
- **Source-level watch item (ungoverned; PF-3):** *what exactly constitutes epistemic
  equivalence?* The duplicate rule, the ~ relation, and the dependency collapse all consume an
  equivalence the sources use and never define — the verdict itself flags it as its unresolved
  issue. It is not a governed OQ; it is recorded here at source strength, and it quietly touches
  III.2's frame-equivalence watch item: the model does not yet say when two things are "the same"
  — for evidence or for standards.
- **PF-4 (production finding):** the design-to-execution property gap taught in §5 — recorded for
  disposition, repaired nowhere.

## 14 · Conclusion — *status: fact [FA], summary*

[FA] Evidence in KnowledgeOS is a qualified observation with an ancestry, a direction, an age and
a context; its combination is governed by properties, not by a formula; the two properties that
are computationally tested — copies must not amplify, independence must strengthen, and dependency
must be resolved before anything is combined — are the architecture's empirical bedrock, held with
open eyes about the single recorded experiment they rest on. The calculus deliberately ends
without an operator: what the model ratified is the boundary of what evidence may claim, and the
next chapter computes what happens at that boundary — how a contract's requirements stand against
a state built from exactly this kind of evidence.
