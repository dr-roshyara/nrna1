# III.3 · The Knowledge State and Its Eight Primitives

> **Edition 1 abstract** *(frozen baseline, verbatim opening)*: The model's most consequential
> structural humility is one inequality: `X_t ≠ Observed(X_t)` — invariant I-7. The world state is
> never fully observed; the model is never the reality. — *Full Edition-1 text:
> `../../../book/part-3-architecture/03-03-knowledge-and-the-world/chapter.md`.*

## 1 · Why a knowledge state, and why time-indexed — *status: fact [FA] + source evidence [E]*

[FA] Between the frame (III.2) and the gap (III.4) sits the thing the gap is computed OVER: the
**knowledge state K_t** — what the system currently holds, as distinct from what the world
currently is (I-7 keeps those permanently apart) and from what the contract requires (that
difference IS the gap). [E] The subscript t is not decoration; the source gives the state its
dynamics in one boxed equation:

```
K_{t+1} = Learn( K_t, Observations_t, Events_t, Policies_t, Outcomes_t )
```

— knowledge evolves by governed incorporation of what arrived, under what rules, with what
consequences; alongside it the source states the decision and world equations
(`A_t = Decision(K_t, S_t, Policy_t)`; `S_{t+1} = F(S_t, A_t, U_t)`), keeping three evolutions —
of knowledge, of choices, of the world — as three different functions. [IN] Time-indexing is thus
the model's memory discipline in miniature: there is no "the knowledge," only *the knowledge as
of t*, and every later chapter that says "current" means this subscript.

## 2 · What K_t is — *status: definition (explains existing authority [FA])*

> **Definition — KnowledgeState K_t**
> *Notation (ratified):* the state over the eight primitives
> `{Entity, State, Event, Observation, Proposition, Relation, Policy, Action}`.
> *Notation (source):* the candidate kernel `𝒦 = (E, S, T, O, P, R, Π, A)`.
> *Semantics:* everything the system currently holds, expressed entirely in the eight-primitive
> vocabulary — richer notions (evidence, claims, provenance, identity, uncertainty…) appear as
> **typed structure over** the primitives, never beside them.
> *Scope:* epistemic content only. K_t is not the world (I-7), not the contract (EC), not the gap
> (Zero), and not a beliefs-with-authority store — nothing in K_t binds anyone (A6 lives
> elsewhere).
> *Evidence/grade:* TESTED **(within scope)** — the ratified grade, resting on the source's twelve
> reduction-falsification experiments (§13) and the fifty-attack consistency audit of Step 050;
> the parenthesis is load-bearing and this book preserves it.
> *Relations:* owned frame above it (III.2); Zero computes against it (III.4); Evidence qualifies
> its observations (III.5); admission (III.6) governs what enters its proposition-side content.
> *Example:* §15. *Limitation:* the primitive basis is a **candidate** (§§12–13); and K_t's
> notation collides with two unrelated historical senses (§2a).

[H — §2a, naming register (D-FA-6)] `K_t` unqualified always means this formal state. The corpus
also contains `K(t) = M(t) + iA(t)` — a complex-number knowledge model from five days earlier,
same notation family, entirely different idea (registered ALT-09, historical only) — and the
engineering documents use "knowledge state" for an event-flow record. Three senses; the register
keeps them apart; this chapter is about the first alone.

## 3 · The reduction story — how many concepts became eight — *status: derivation RECONSTRUCTABLE [E], reproduced*

[E — RECONSTRUCTABLE, from the source's own sequence] Step 049 opens with an inventory problem:
"we have accumulated many concepts — are they truly distinct primitives, or different views of the
same structure?" Its first move is NOT the eight. It is a **stratification into four core
primitives plus six structure families**:

```
CORE:        Entity, State, Event, Observation
EPISTEMIC:   Evidence, Claim, Uncertainty
SEMANTIC:    Identity, Meaning, Context
RELATIONAL:  Provenance, Dependency, Causality
GOVERNANCE:  Policy, Authority, Decision
OPERATIONAL: Action, Outcome
EVOLUTION:   Learning, Revision, Drift
```

Then, family by family, the source interrogates each concept — *is this primitive, or
constructible?* — and most collapse: Evidence = a qualified observation (Observation + Relevance +
Context); Claim = a proposition; Identity = an identity **relation** over entities; Provenance =
typed relations (`Supports(E,C)`, `DerivedFrom(C₁,C₂)`); Causality and Dependency = more typed
relations; Uncertainty = an epistemic **annotation** `U(C)`, deliberately not assumed
probabilistic; Authority and Decision = governance structures over policy and action (the source
even pre-draws III.7's line: `DecisionProposal ≠ AuthorizedDecision`); Outcome = an observed
consequence; Learning/Revision/Drift = evolution structure (the §1 equation). What survives the
interrogation is the consolidation the source boxes twice — §49.29 ("at the deepest level…
everything else can be constructed from these") and the freeze at §49.75 — and names in §49.30:

**𝒦 = (E, S, T, O, P, R, Π, A)** — entities, states, temporal/event structure, observations,
propositions, typed relations, policies, actions: **the candidate mathematical kernel.**

[E] Note the historical shape honestly: the four-core-plus-families stratification and the
eight-tuple are BOTH in the source, in that order — the eight are the four cores *plus* the four
structure-carriers (Proposition, Relation, Policy, Action) that the reduction showed could not
themselves be constructed away. Nothing is hidden in that sequence, and the ratified model's
eight-name row is the endpoint of it, verbatim.

## 4 · The eight primitives — *status: definitions (explain source content [E] carried into ratified vocabulary [FA])*

> **Definition — Entity** · *Notation:* `e = (id, type, attributes)`. *Semantics:* an
> identity-bearing domain object. *Scope:* the thing knowledge is ABOUT. *Grade:* [E] 049 §49.3 —
> with the source's own honesty that Entity is "arguably a semantic abstraction over Identity +
> State," kept primitive "because DDD requires identity-bearing domain objects." *Relations:*
> States describe it; Relations connect it; the Identity relation individuates it. *Example:*
> election E; ballot box #17; the returning officer. *Limitation:* primitivity is pragmatic, by
> the source's own admission.

> **Definition — State** · *Notation:* `S_t = (x₁, …, x_n)`. *Semantics:* the condition of a
> domain object or system at time t. *Scope:* both world-side states (which K_t can only hold
> beliefs about — I-7) and knowledge-side states (K_t itself is one). *Grade:* [E] §49.4
> ("fundamental"). *Relations:* Events transition it; Actions change it; Observations sample it.
> *Example:* "district 4: reconciliation complete" as a held state-description. *Limitation:*
> the source does not fix a state ontology (what the xᵢ range over is domain work).

> **Definition — Event** · *Notation:* `e_t : S_t → S_{t+1}`. *Semantics:* a state transition, or
> a report that something happened — and the source explicitly splits **DomainEvent** (a
> transition) from **ObservationEvent** (an external report). *Scope:* the model's time atoms.
> *Grade:* [E] §49.5. *Relations:* feeds the Learn equation; Precedes-relations order it.
> *Example:* "recount completed" (domain) vs "wire service reports recount completed"
> (observation-event — a different thing, as III.5's dependency lesson demands). *Limitation:*
> event identity/individuation is not settled by the source.

> **Definition — Observation** · *Notation:* `O = (source, t, value)`. *Semantics:* "a source
> reported or measured something at a particular time" — with the source's italic warning kept
> attached: it does **not** say the interpretation is correct. *Scope:* the only way world-content
> enters K_t; the raw material Evidence qualifies (III.5). *Grade:* [E] §49.6. *Relations:* the
> Supports relation links its qualified form to Propositions. *Example:* the sealed transport
> log's scan record. *Limitation:* source-typing (sensor/expert/AI/document roles) lives in the
> Evidence layer, not here.

> **Definition — Proposition** · *Notation (source, as Claim):* `C : Ω → {True, False}` — "or,
> more realistically, a proposition whose truth status is uncertain." *Semantics:* a truth-apt
> content item; the thing statuses, uncertainty annotations, and the admission ladder attach to.
> *Scope:* content, never authority. *Grade:* [E] §49.8, with Claim = Proposition (§49.76).
> *Relations:* Supports/Contradicts target it; `U(C)` annotates it. *Example:* "result R is
> correct." *Limitation:* the source's Ω-notation here is vestigial sample-space shorthand — not
> the historical Ω of Part I.4, and this book does not import it (naming hygiene, D-FA-3's
> spirit). *(Recorded as a curiosity, not a finding: the symbol collision is inside one source
> formula and carries no architectural weight.)*

> **Definition — Relation** · *Notation:* the typed set
> `R = {Supports, DependsOn, Causes, Identifies, MapsTo, Contradicts, Precedes}`.
> *Semantics:* the ONLY connective tissue — and it must stay typed (§14). *Scope:* everything
> formerly "relational" (provenance, dependency, causality, identity, contradiction, ordering) is
> a typed relation, not a new primitive — the source calls this "a major semantic normalization."
> *Grade:* [E] §§49.16–49.19. *Relations:* n/a (it IS them). *Example:*
> `Supports(commissionTallyFile, "result R is correct")`; `DerivedFrom(wireSummary, wireReport)`.
> *Limitation:* the seven-element list is the source's working set, not a proven closure.

> **Definition — Policy (Π)** · *Notation:* `Policy(s, a) → {Permit, Deny}`, generalized to
> `P : State × Action → DecisionConstraint`. *Semantics:* a rule over states and actions.
> *Scope:* constraint content — and the source immediately separates it from Authority: "a policy
> can say ActionAllowed = True; the actor may still lack authority" (`Authority(actor, d)` is
> governance structure OVER the primitives, not a ninth primitive). *Grade:* [E] §§49.21–49.22.
> *Relations:* consumed by Decision structure; itself content-at-rest vs in-force (III.8's
> stratification). *Example:* "certification may be published only after all anomaly
> dispositions exist." *Limitation:* policy composition/conflict is later work (the corpus's
> governance algebra).

> **Definition — Action (A)** · *Notation:* `A : S_t → S_{t+1}`, stochastically
> `P(S_{t+1} | S_t, A)`. *Semantics:* what changes world or system state. *Scope:* the primitive
> the decision boundary guards — knowledge informs it and never executes it (Article 4, III.7);
> Outcome is its observed consequence, not a separate primitive. *Grade:* [E] §§49.24–49.25.
> *Relations:* selected by Decision structure; generates Outcome-observations that re-enter the
> Learn loop. *Example:* "publish the certification." *Limitation:* execution semantics beyond
> the boundary are the architecture's open edge (OQ-4).

## 12 · What is NOT primitive — the derived-structure layer — *status: source evidence [E]*

[E] The reduction's other half is a list of equations the source boxes at §§49.31/49.76 —
"everything else must be expressible as `Structure(𝒫)`":

```
Evidence    = QualifiedObservation            (⊆ O × Context; + Relevance)
Claim       = Proposition
Identity    = EntityIdentityRelation
Provenance  = TypedDependencyRelation         (Supports, DerivedFrom, …)
Uncertainty = epistemic annotation U(C)       (probabilistic OR not — 49.10's
                                               Probability ≠ EpistemicConfidence)
Authority   = governance structure over decisions   (Authority(actor, d))
Decision    = structure over (K, S, Policy) → A     (with Proposal ≠ Authorized kept apart)
Outcome     = observed consequence of Action
Learning/Revision/Drift = evolution structure       (the §1 Learn equation)
```

[IN] Two readings matter. For the practitioner: when modeling in this architecture, resist
inventing primitives — the reduction's whole yield is that richer notions are TYPED STRUCTURE, and
typed structure can be checked, queried and governed in ways ad-hoc primitives cannot. For the
reader tracking the programme: the later chapters' load-bearing objects — Evidence (III.5), the
statuses (III.6), the Decision Contract (III.7), even Authority itself — are all *derived-layer*
citizens over this kernel, which is exactly what "the model has a small formal core" means.

## 13 · Why eight — what is established and what is not (PF-2) — *status: split [E RECONSTRUCTABLE / U NOT ESTABLISHED]*

[E — RECONSTRUCTABLE] The reduction argument of §3 is genuinely in the source: each non-kernel
concept is individually shown constructible, the kernel is boxed, frozen, and then **stress-tested
by twelve falsification experiments** (§§49.78–49.89: take a rich concept, attempt to express it
in `Structure(𝒫)`, fail the kernel if it cannot be done) — that battery, with Step 050's fifty
attacks, is what the ratified grade "TESTED (within scope)" summarizes. "Why these eight" is
therefore NOT a bare assertion: a reconstructable argument exists, and this section just walked
it. [U — NOT ESTABLISHED] What the argument does not and cannot claim, and the source itself
refuses to claim: **completeness** (that no needed concept will ever fail to reduce),
**minimality** (that no seven-element basis would do — the source concedes Entity is "arguably"
Identity + State and keeps it for DDD-pragmatic reasons), and **uniqueness** (that no alternative
basis exists). The source's own word is the governing one: a **candidate** mathematical kernel.
Its verdict formulation is exactly calibrated — "KnowledgeOS does not need a gigantic mathematical
ontology; it needs a small, rigorously typed kernel" — a *design result*, not a theorem. [M] This
is production finding PF-2 taught in place: partially RECONSTRUCTABLE (the argument), open (the
metaproperties) — and never upgraded to a proof of minimality by this or any page.

## 14 · How primitives collapse — the source's own failure case — *status: source evidence [E] + illustration [IN]*

[E] The source demonstrates what mis-typing costs (§49.20). Collapse the typed relation set into a
single generic `RelatedTo(A, B)` and semantics die: an AI consumer, seeing `RelatedTo` everywhere,
infers `RelatedTo ⇒ Causes` — recreating "one of the fundamental problems we eliminated in Step
43." One typed set, seven meanings; one generic edge, license to hallucinate causality.
[IN — extended to the running example] The same collapse in election terms: let
`Supports(wireReport, recountCompleted)` and `DerivedFrom(wireSummary, wireReport)` blur into
`RelatedTo`, and the derived summary quietly becomes a second witness for the recount — III.5's dependency failure, seen now
as a *typing* failure at the primitive layer. The primitives are not pedantry; each mis-merge has
a named epistemic accident waiting behind it (Step 050's identity-collision and semantic-collision
attacks are two more, run deliberately against the state machine and survived).

## 15 · K_t in the model's web — and the running example — *status: fact [FA] + illustration [IN]*

[FA] The connections, now all definable precisely: the **frame** (III.2) stands above K_t —
nothing in the state may edit G or IdealState; **EC** confronts it — requirements are evaluated
against K_t's content; **Evidence** (III.5) is K_t's qualified-observation layer feeding
Proposition support; **Zero** (III.4) is the typed shortfall of K_t against EC; the **admission
ladder** (III.6) governs which Propositions attain which standing inside K_t — the state holds
Candidates and Accepted content alike, with their statuses, never blurring them.

[IN — running example, stage 4] The certification K_t at a glance, one instance per primitive:
**Entities** — election E, districts 1–5, ballot boxes, the commission; **States** — "district 4:
reconciled," "attestation: predates patch"; **Events** — recount completed (domain), wire report
received (observation-event); **Observations** — the commission tally file scan (source, t,
value); **Propositions** — "result R is correct" (Candidate, per III.6's ladder), "custody
continuous for box #17" (Conflicted-adjacent, pending III.6); **Relations** —
Supports(tallyFile, R), DerivedFrom(wireSummary, wireReport), Contradicts(anomalyReport,
custodyProposition), Precedes(count, recount); **Policies** — the certification policy of §4's
Policy block (content-at-rest until III.8's in-force machinery); **Actions** — "publish
certification" (not taken; awaiting III.7's contract). [IN] Everything the previous chapters did
abstractly is now visibly ONE state: III.5's evidence episode lives in the
Observation-plus-Relation cells; III.4's Zero vector was computed against exactly this K_t.

## 16 · Realization — L3 and L4, honestly — *status: fact [FA/E]*

[FA←E] At L3, the primitives are *vocabulary*, not services: the Reference Architecture's
representations carry them (the typed epistemic graph — "knowledge as typed nodes with preserved
relationships" — is the eight-plus-relations rendered as a representation model; Evidence Records
carry the qualified-observation structure; the state model carries statuses). No "K_t service"
exists, correctly: a state is what services operate ON. [E — L4] Nothing executable holds a K_t
today. The source's computability sections (§§49.32–49.67) are a genuine design study — finite
representation, graph queries, computational classes, a computability boundary, graceful
degradation, "no silent approximation" — but a design study is what they are: SPECIFIED, not
built. The ratified grade's parenthesis — TESTED **(within scope)** — refers to the reduction and
consistency batteries run on paper, not to running code, and this book will not let the
parenthesis be forgotten.

## 17 · Limitations and open questions — *status: register [U]*

- **Candidate-kernel metaproperties (PF-2): OPEN** — completeness, minimality, uniqueness of the
  eight; taught in §13 at exact strength; closing acts: a formal irreducibility analysis, or a
  governance selection on the formal side (this is OQ-2's object-level neighborhood, owned by
  III.9).
- **Relation-set closure: NOT ESTABLISHED** — the seven typed relations are a working set.
- **State/event ontology: deliberately unfixed** — domain modeling work, not kernel work.
- **OQ-4 adjacency** — Action's semantics beyond the boundary (owned by III.7).
- **Naming register** — the K_t triple-sense (§2a) remains a live hazard for readers of the
  corpus; the register, not this chapter, is the protection.

## 18 · Conclusion — *status: fact [FA], summary*

[FA] The knowledge state is the model's ontology done with restraint: eight primitives that
survived an explicit reduction, a typed relation set that refuses semantic mush, a derived layer
where the rich concepts live as checkable structure, and a time index that makes evolution
first-class. Its strongest claims carry a parenthesis — tested *within scope* — and its basis
carries the source's own modest word: *candidate*. What the state holds, the next chapter's
machinery governs: how a proposition inside K_t rises, stalls, conflicts, or is preserved in
failure — the statuses and the ladder.
