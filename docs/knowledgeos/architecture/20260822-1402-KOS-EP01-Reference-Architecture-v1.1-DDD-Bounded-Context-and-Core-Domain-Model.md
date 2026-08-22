# KnowledgeOS Reference Architecture v1.1 — DDD Bounded Context and Core Domain Model

> **Position:** the **HPA review step of Reference Architecture v1.0**, executed as a **DDD-based architectural refinement**. Constitution v1.0 (FROZEN constraint system) → Reference Architecture v1.0 (PROPOSED, reviewed here) → **this v1.1** → Logical Architecture → Implementation Architecture → Systems. *"This should be the bridge from Research Phase → Architecture Phase."*
> **Commissioning instrument:** `docs/knowledgeos/reviews/20260822-1402-KOS-EP01-Reference-Architecture-v1.1-DDD-Refinement-commissioning-prompt.md`.
> **Strict instruction (standing):** *"Design an architecture that satisfies the Constitution. Do not extend the Constitution."* The Constitution v1.0 is **FROZEN**; the research phase is **CLOSED**.
> **Status:** ⭐ **PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE** — the DDD refinement of v1.0. **HPA review delivered 2026-08-22: PASS CONDITIONALLY** — one wording refinement (the aggregate boundary phrased at Reference-Architecture altitude, not as an implementation "command surface"); **refinement applied in this revision, awaiting HPA confirmation** of the applied wording. It adds **no law, no concept, no register row, no philosophy**; it re-renders the Constitution's content in DDD form. Register **25+4 unchanged**.

---

## 1 · Executive Summary

**What changed from v1.0.** v1.0 was a **layer architecture** — four layers, eleven kernel services, six engines, six representation models. It answered *"what boundaries must exist"* in the language of services and layers. v1.1 asks the DDD question — *"what is the domain, and what is its smallest irreducible core?"* — and the answer **shrinks the architecture**:

| | v1.0 (Reference Architecture) | v1.1 (DDD refinement) |
|---|---|---|
| Primary view | layers and services | **bounded contexts and one aggregate** |
| Core | eleven kernel services (PRESERVE) | **one core domain, one aggregate** (KnowledgeAggregate) |
| Enforceability | boundaries that make forbiddances *structurally impossible* | the same forbiddances as **aggregate invariants** — the aggregate is the authoritative domain boundary at which constitutional admissibility of a state transition is determined |
| Supporting | (implicit) | three named supporting contexts (Authority · Projection · Decision) |
| Mechanisms | six engines | the same engines, re-placed as **adapters at ports** (hexagonal form of the kernel boundary) |
| LLM | governed generation mechanism | **external adapter at the Expression port** (Language Cortex) — position unchanged, form made explicit |
| Semantic Compiler | (research candidate, unplaced) | **ruled: not promoted** — candidate adapter at the Expression port, for the Logical Architecture stage (BV-8) |

**The reduction in one sentence:** v1.0 required eleven services to *be* the kernel; v1.1 requires **one aggregate** — the KnowledgeAggregate — whose invariants *are* the eleven articles. The kernel's content is unchanged; its **enforcement locus** is now a single, named object — the authoritative domain boundary at which constitutional admissibility of a state transition is determined.

**The three gates, in advance:**

- **Reduction Test** — *Did KnowledgeOS become smaller and clearer?* **Yes.** Eleven kernel services → one core domain with one primary aggregate (plus one small ConflictRecord) and three supporting contexts with small aggregates. Of the nine dimensions, seven become protected aggregate members and two processes are correctly moved outside the object (§3.2).
- **Identity Test** — *If all implementations change, is it still KnowledgeOS?* **Yes.** The core domain — the identity of a justified epistemic state through representation, context, evidence, reasoning, and evolution — is implementation-free. No database, engine, or interface choice can change it (this is the same property v1.0 §8 asserted; DDD makes it structural).
- **Failure Test** — *If the core principle disappears, does KnowledgeOS stop being KnowledgeOS?* **Yes.** If identity-of-meaning disappears, knowledge becomes documents, embeddings, or similarity — the Ch IV refusals. The core is chosen precisely as that whose removal changes what the system *is* (§3.3).

**The one-line character is unchanged** (head 43, ratified): KnowledgeOS is a constitutional epistemic operating system that preserves the identity of a justified epistemic state through its justified life — *created, justified, transformed, challenged, evolved* — never as stored information, always as **a verified transition**.

---

## 2 · Final Bounded Context Map

### 2.1 Domain stratification

| Stratum | Bounded context | Why it sits there |
|---|---|---|
| **CORE** | **KnowledgeCore** — the domain of identity-bearing justified epistemic states and their lifecycle | The only context whose removal changes what the system *is*. Its aggregate's invariants are the eleven articles. **Nothing else is core.** |
| **SUPPORTING** | **Authority** — assigns authority as a recorded reference to a human act; preserves source roles | Guards Article 3; essential, but serves the core — it authorizes states, it does not define them |
| **SUPPORTING** | **Projection** — derives regenerable, non-authoritative views | Guards Article 5; presentation of knowledge, not knowledge itself |
| **SUPPORTING** | **Decision Boundary** — the governed interlock between knowledge and action | Guards Article 4; terminates knowledge at recommendation |
| **GENERIC / EXTERNAL** | **Reasoning & Validation** — the engines (validation · reasoning · contradiction/debate · fallacy detection · history/revision · intent classification) | **Mechanisms**, not a domain of KnowledgeOS: they propose, transform, and evaluate candidates; the kernel does not reason (Ch III.1). Plugged into the core's ports |
| **GENERIC / EXTERNAL** | **Expression** — the expression↔meaning translators (LLM as Language Cortex; Semantic Compiler as a *candidate* adapter) | **Mechanisms**, not a domain of KnowledgeOS: they generate and translate expression. Positioned so expression can never become meaning (Article 1.2) |

### 2.2 The context map

```
                      ┌───────────────────────────────────────────────┐
                      │  EXPRESSION  (Generic/External — mechanisms)   │
                      │  LLM = Language Cortex · Semantic Compiler     │
                      │  (candidate adapter, NOT promoted — BV-8)       │
                      └───────────────┬───────────────────────────────┘
                                      │  Expression ↔ Meaning  (ACL — Article 1.2)
                                      │  may translate · never determines identity
                                      ▼
┌──────────────┐   assigns   ┌───────────────────────────────────────────────┐   reads   ┌──────────────────┐
│  AUTHORITY   │◀───────────▶│              KNOWLEDGE CORE                    │──────────▶│   PROJECTION      │
│ (Supporting) │  authority  │  (CORE — the only core domain)                 │ read-only │  (Supporting)      │
│ AuthorityGrant│ referenced  │  KnowledgeAggregate · ConflictRecord          │ (ACL —    │  DerivedView       │
└──────────────┘             │  invariants = the eleven articles             │  Art 5)   └──────────────────┘
                             └───────────────┬───────────────────────────────┘
                                             │  advisory only (ACL — Article 4)
                                             ▼
                                  ┌──────────────────────────────┐
                                  │   DECISION BOUNDARY           │
                                  │  (Supporting — the interlock)  │
                                  └──────────────────────────────┘
                                             ▲
                    propose candidates       │  Verification Port (Article 6 — published language)
                    (justification path)     │  verdict vocabulary · justification-path contract
                                             │
                          ┌───────────────────────────────────────┐
                          │  REASONING & VALIDATION (engines)     │
                          │  Generic/External — mechanisms only    │
                          └───────────────────────────────────────┘
```

**Reading the map:** every arrow into the core is a **port**; every arrow out of the core is **advisory or projection**. The core is the only context that owns justified epistemic state; every other context either *feeds* it (Authority, Reasoning, Expression) or *reads* it (Projection, Decision). This is the hexagonal form of the kernel boundary: the kernel is the protected core, engines and representations are adapters at the ports — the same Ch III boundary, drawn as DDD.

### 2.3 The contexts, defined

#### KnowledgeCore (CORE)

- **Purpose:** preserve justified epistemic states with their identity, context, evidence, reasoning path, uncertainty, agency, and history across time — the domain that answers *"what is justified to believe, by whom, based on what evidence, through what reasoning, at what time?"*
- **Ubiquitous language:** Knowledge · KnowledgeAggregate · identity-of-meaning · justification path · epistemic state · verification · revision · supersession · CONFLICTED · UNKNOWN · REJECTED · absence · agency · temporal validity · projection (never a source) · frame (delimiting conditions)
- **Core entities:** Knowledge (aggregate root) · ConflictRecord
- **Value objects:** KnowledgeId · Meaning · ContextTuple · EvidenceLink · JustificationPath · EpistemicState · Agency · TemporalValidity · Confidence (governed attribute)
- **Aggregates:** KnowledgeAggregate (§4) · ConflictRecord (references two KnowledgeAggregates by identity; holds the CONFLICTED record and its forward-only resolution)
- **Domain events:** §5
- **Invariants:** the eleven articles as aggregate invariants (§6)
- **External dependencies:** Authority context (assigned authority, referenced never held) · Reasoning & Validation (candidate proposals at the Verification Port) · Expression (translation at the Expression↔Meaning port)

#### Authority (SUPPORTING)

- **Purpose:** assign authority as a **recorded reference to a human act** — never intrinsic, never emergent from content or assessment (Article 3).
- **Ubiquitous language:** authority grant · human act · assigner · source role · authorization · recorded reference
- **Core entity:** AuthorityGrant
- **Value objects:** SourceRole (sensor · expert · AI inference · historical document — the means of acquisition, preserved, never flattened)
- **Aggregate:** AuthorityGrant (the smallest: who assigned · to what · referencing which human act · forward-only)
- **Domain events:** AuthorityAssigned
- **Invariants:** evidence/assessment/source never self-authorize (Article 3.1) · source roles never collapse (Article 3.3)
- **External dependencies:** KnowledgeCore (grants are referenced by knowledge states; the core is the customer of assignments)

#### Projection (SUPPORTING)

- **Purpose:** derive regenerable, non-authoritative views of knowledge for presentation; a projection never becomes its source (Article 5).
- **Ubiquitous language:** projection · derived view · regenerable · source · no return path
- **Core entity:** DerivedView
- **Value object:** SourceReference (points at the knowledge it projects, never replaces it)
- **Aggregate:** DerivedView (regenerable from its source; carries no authority beyond the source's)
- **Domain events:** ProjectionMaterialized (regenerable at any time — not a stored truth, a view)
- **Invariants:** projection ≠ source · no write-back path (Article 5)
- **External dependencies:** KnowledgeCore (read-only)

#### Decision Boundary (SUPPORTING)

- **Purpose:** terminate knowledge flows at **recommendation**; knowledge informs action, never executes it (Article 4).
- **Ubiquitous language:** recommendation · advisory · interlock · decision · action · justification-at-time
- **Core entity:** DecisionRecord
- **Value object:** Recommendation (knowledge state + the authorized decision step it may inform)
- **Aggregate:** DecisionRecord (why this action was justified at that time)
- **Domain events:** DecisionInformed (advisory; the decision itself belongs to the actor, not to KnowledgeOS)
- **Invariants:** knowledge → recommendation only; execution requires an authorized decision step outside the core (Article 4)
- **External dependencies:** KnowledgeCore (advisory read)

#### Reasoning & Validation (GENERIC / EXTERNAL — mechanisms)

- **Purpose:** propose, transform, and evaluate candidates **within** the constitutional field; the kernel does not reason (Ch III.1).
- **Mechanisms:** validation engine · reasoning engines · contradiction/debate pipeline · fallacy detection · history/revision mechanics · intent classification · (LLM as governed generation)
- **Placement rule:** every output enters the core **only** through the Verification Port as a candidate with a preserved justification path (Article 6). Nothing an engine produces is knowledge by generation.
- **Port contract (Published Language with the core):** the verdict vocabulary (VALIDATED / QUESTIONABLE / REJECTED / CONFLICTED) and the justification-path contract — the shared kernel between the core and its engines.

#### Expression (GENERIC / EXTERNAL — mechanisms)

- **Purpose:** translate between expression and meaning; the boundary where **expression must never become meaning** (Article 1.2).
- **Mechanisms:** LLM (Language Cortex — generates expression; does not own truth) · Semantic Compiler (**candidate** adapter, not promoted — §7.3).
- **Placement rule:** identity is assigned by the core's identity, never derived by any expression translator; observable match at any degree never determines identity (Article 1.3).

---

## 3 · Core Domain Definition

### 3.1 The four candidate cores, tested

The HPA named four candidates. Each is tested against the two gates that define a core domain: **removal changes what the system is** (Failure Test) and **the constitution is satisfied, never extended**.

| Candidate core | Test | Ruling |
|---|---|---|
| **Knowledge Identity** | *If identity disappears: Knowledge = documents? = embeddings? = similarity?* — all three are Ch IV refusals (NOT a repository, NOT a similarity engine, and representation never equals identity). Removing identity collapses the system into a document/vector store. | **ADOPTED — the core domain.** The domain of identity-bearing justified epistemic states. |
| **Epistemic Evolution** | *If evolution disappears:* knowledge becomes static storage → the Ch IV repository refusal. But evolution is the **behavior of the identity-bearing object** — revision, contradiction, supersession are *state transitions of the one aggregate*, not a second domain with a second object. | **FOLDED INTO the core** as the aggregate's lifecycle. Not a second core; the verbs *created, justified, transformed, challenged, evolved* are the aggregate's lifecycle — its governed state transitions. |
| **Meaning Preservation** | *If meaning-preservation disappears:* representation becomes meaning (Article 1.2). But preserving meaning IS what identity means — identity is *identity of meaning through changing representations*. | **FOLDED INTO the core** as identity's defining facet. Not a second domain; the aggregate's identity IS the preserved meaning. |
| **Wisdom Formation** | The register has **no wisdom row**; the character ends at *"trustworthy truth discovery,"* not wisdom; the Gaṇeśa lifecycle's endpoint was studied but never entered the kernel. Adopting wisdom as the core would **extend the Constitution** (V.3 forbids new concepts). | **REJECTED at the boundary.** Recorded under §8; a hypothetical Wisdom context is future-gated behind the amendment discipline, not part of v1.1. |

**The smallest core:** **Knowledge Identity** — the domain of identity-bearing justified epistemic states and their justified life. Not "knowledge" (too large — a repository), not "wisdom" (not constitutional), not "evolution" or "meaning" alone (facets of the one object). One domain, one primary aggregate, eleven invariants.

### 3.2 The dimension challenge — KEEP / MODIFY / REMOVE

The v1.0 dimension set (Identity · Evidence · Authority · Context · Transformation · Temporal · Reasoning · Contradiction · Agent) is challenged by the removal test. **Each surviving dimension becomes a protected member of the aggregate or a boundary of its context.**

| Dimension | Ruling | Removal test | Landing |
|---|---|---|---|
| **Identity** | **KEEP** | knowledge = documents? embeddings? similarity? → Ch IV refusals | aggregate root (KnowledgeId) |
| **Context** | **KEEP** | claims become unbounded → over-extended knowledge (Article 1.4) | ContextTuple member |
| **Evidence** | **KEEP** | knowledge = assertion → Claim→Knowledge (Article 6) | EvidenceLinks member |
| **Authority** | **KEEP** (assigned, never emergent) | self-authorization · source-role collapse (Article 3) | supporting Authority context; referenced by the aggregate, never held |
| **Transformation** | **MODIFY** — from a *dimension* to the *lifecycle* of the aggregate | static storage → Ch IV repository | the aggregate's state transitions + domain events (§5) |
| **Temporal** | **KEEP** | freshness = truth · revision = deletion (Article 11) | TemporalValidity member |
| **Reasoning** | **MODIFY** — split: the **reasoning path** is kept as an aggregate member; the **reasoning process** is external (engines at the Verification Port) | opaque inference · black-box generation (Article 6.4) | JustificationPath member + Reasoning & Validation context |
| **Contradiction** | **KEEP** — as a *state* (CONFLICTED) and a ConflictRecord, not a standalone dimension | premature TRUE/FALSE · weaker side deleted (Article 8) | EpistemicState + ConflictRecord aggregate |
| **Agent** | **MODIFY** — epistemic **agency** is kept (the lineage, traceable not coupled); the agent-as-actor is external to the core | anonymous knowledge (Article 10) | Agency member + external actor |

**Result:** nine dimensions → **seven protected aggregate members** (Identity · Context · Evidence · Reasoning-path · Authority-ref · Temporal · Agency) **+ the state** (EpistemicState) **+ history and relations** — with the two processes (reasoning, transformation) correctly placed as *behavior and adapters*, not attributes. (These seven carry the nine dimensions; the aggregate's full member set also includes Meaning, Confidence, History, and Relations — §4.1.) The dimension set is **smaller** because the *processes* were never dimensions of the object — they were the system's verbs.

### 3.3 Why this core and no larger one

- **No larger core:** evidence, authority, contradiction, projection, decision — all are either *members of* the aggregate or *guards at its boundary*. Making any of them a core domain would place two domains over the same object, splitting the invariants.
- **No smaller core:** remove identity and the aggregate is a claim-store; remove the lifecycle and it is a snapshot-store. Both are Ch IV refusals. Identity + lifecycle (identity through change) is the irreducible unit — the same unit the research closure named: *"a knowledge state is a verified transition, not stored information."* This answers the HPA's central review question directly: **Knowledge Identity + justified lifecycle is the smallest irreducible domain that remains when every mechanism, representation, technology, language, database, LLM, and reasoning engine is removed** — remove either half and the remainder is a Ch IV refusal (a claim-store or a snapshot-store).
- **The core is the answer to the system's own question.** KnowledgeOS exists to answer *"what is justified to believe, by whom, based on what evidence, through what reasoning, at what time?"* The core domain is the domain in which that question has a well-formed, identity-bearing, history-carrying answer.

---

## 4 · Aggregate Model

### 4.1 The KnowledgeAggregate

**Aggregate root: Knowledge** — identified by its **KnowledgeId** (the identity-of-meaning, **assigned at creation, never derived** — Article 1.1, 1.3). The aggregate is the cluster of object-states treated as one unit: **it is the authoritative domain boundary at which constitutional admissibility of a state transition is determined** — no transition is admitted without passing the aggregate's invariants, and no member is reachable from outside except through the root. *(Whether that boundary is realized as commands, methods, domain services, or policy evaluation is a Logical / Implementation Architecture decision, deliberately deferred — v1.1 names the boundary, not the mechanism.)*

| Member | Kind | Responsibility | Constitutional article |
|---|---|---|---|
| **KnowledgeId** | Value object | the identity of meaning — assigned once, stable through representation, expression, context, and projection changes; **never** derived from similarity, observable match, or coextension | Article 1 |
| **Meaning** | Value object | the intensional content — *what the knowledge is* — the preservation target of identity | Article 1 |
| **ContextTuple** | Value object | the delimiting conditions (entity · property · context · relation · time · authority) — Avacchedaka; the frame beyond which the claim is incomplete | Article 1.4 |
| **EvidenceLinks** | Collection | the justification: evidence + acquisition method + reliability conditions; pseudo-evidence never admitted | Article 6 |
| **JustificationPath** | Value object | the reasoning path: premises · rules · assumptions · inference rule (Vyapti-warranted) · conclusion — the reason the state is justified, never a black box | Article 6.4 |
| **Authority** | Reference | the assigned authority — a recorded reference to a human act; **held by the Authority context, referenced by the aggregate** | Article 3 |
| **Agency** | Value object | the epistemic lineage: who observed · reasoned · validated · decided · under which authority — traceable, never a binding to a person | Article 10 |
| **EpistemicState** | Value object | the state vocabulary: VALIDATED · QUESTIONABLE · REJECTED · CONFLICTED · UNKNOWN · ABSENT · FALSE — first-class negative and failure states | Articles 7, 8, 9 |
| **TemporalValidity** | Value object | valid-from · valid-until · superseded-by — the time dimension; freshness never truth, expiry never absence | Article 11 |
| **Confidence** | Value object (governed) | a structured epistemic attribute — **never a scalar replacing epistemic structure** (Article 2.3); carried as the Temporal Epistemic Metadata mechanism (UM-46), not as a knowledge-quality score | Article 2 |
| **History** | Value object (immutable) | the forward-only revision log — every prior state retained; supersession never overwrites | Article 11 |
| **Relations** | Collection | the relationship links to other aggregates (supports · contradicts · bounded-by) — knowledge is a relationship, never a detached object | Article 1.5 |

**The aggregate boundary in one sentence:** nothing inside the aggregate is reachable from outside except through the root; no state transition is admitted that violates an article; no member may be modified in place without creating a new forward-only state.

### 4.2 The ConflictRecord (second aggregate of the core)

Conflicting knowledge **coexists as CONFLICTED** until governed resolution; the conflict record survives resolution forward-only (Article 8). Because two KnowledgeAggregates cannot own each other, the conflict is its own small aggregate:

- **Aggregate root: ConflictRecord** — references the two (or more) conflicting KnowledgeAggregates **by identity** (never containing them).
- **Value objects:** ConflictState (CONFLICTED · RESOLVED) · Resolution (the governed determination + its authority reference).
- **Invariants:** conflict is preserved until resolution; challenge never destroys identity (Article 8.2); resolution is forward-only, the conflict record survives (Article 8.3).
- **Domain events:** ContradictionDetected · ContradictionResolved.

### 4.3 Supporting aggregates (small, in their contexts)

| Aggregate | Context | Responsibility | Invariant |
|---|---|---|---|
| **AuthorityGrant** | Authority | the recorded reference to a human act that assigns authority | never self-authorizes; source roles preserved (Article 3) |
| **DerivedView** | Projection | a regenerable, non-authoritative projection of a knowledge state | never its source; no write-back (Article 5) |
| **DecisionRecord** | Decision Boundary | the record that knowledge informed action at a time, advisory only | informs, never executes (Article 4) |

---

## 5 · Domain Events

The events of the core's life. Each is a named state transition of the KnowledgeAggregate (or ConflictRecord), and each carries the invariant it honors. **Events are the lifecycle made visible** — the DDD form of the *"governs how knowledge is created, justified, transformed, challenged, and evolved"* verbs.

| Event | Trigger | Aggregate effect | Article honored |
|---|---|---|---|
| **KnowledgeCreated** | a candidate passed the Verification Gate with a preserved justification path | aggregate created; identity assigned; agency recorded; state set; timestamped | 1, 6, 10, 11 |
| **EvidenceAdded** | new authentic evidence linked | EvidenceLinks extended | 6 |
| **BeliefRevised** | new evidence or reasoning changes the justification | EpistemicState transitioned forward-only; prior state → History | 7, 11 |
| **MeaningTranslated** | an expression↔meaning translation is performed | meaning preserved; **KnowledgeId unchanged** | 1.2, 1.3 |
| **ContradictionDetected** | a claim conflicts with an existing state | CONFLICTED state; ConflictRecord created | 8 |
| **ContradictionResolved** | a governed resolution is reached | ConflictRecord → RESOLVED; conflict record retained | 8.3, 11 |
| **KnowledgeSuperseded** | a new version supersedes an old one | old state → History; supersession forward-only | 11 |
| **KnowledgeRejected** | a candidate failed verification | preserved as REJECTED; never discarded, never knowledge | 7 |
| **AuthorityAssigned** | an authority grant is recorded | aggregate's Authority reference updated (assignment, never emergence) | 3 |
| **DecisionInformed** | knowledge is made available to an authorized decision step | recommendation issued; **execution remains outside the core** | 4 |

**⚠️ WisdomDerived — recorded for traceability, NOT admitted.** The HPA named *WisdomDerived* as an example event. It is **not part of the v1.1 domain model**: wisdom is not a constitutional concept (the register has no wisdom row; the character ends at trustworthy truth discovery). A hypothetical future Wisdom context would require a constitutional amendment, and V.3 forbids adding new concepts. The event is therefore recorded here as the HPA's example, explicitly **not admitted** — the discipline, not the concept, governs.

---

## 6 · Constitutional Invariants

The eleven articles, rendered as **aggregate invariants**. This is v1.1's central move: the same content that v1.0 drew as architectural boundaries is now the aggregate's — **the KnowledgeAggregate is the authoritative domain boundary at which constitutional admissibility of a state transition is determined** (HPA review 2026-08-22, phrasing). A transition that would violate an invariant is not admitted, whatever the engine, mechanism, or representation behind it. **No article is weakened, none is extended**; each is given an enforcement locus.

| Aggregate invariant (canonical wording) | Article | Enforcement locus | Rendered register rows (evidence) |
|---|---|---|---|
| **INV-KOS-IDENTITY-001** — Identity is assigned, never derived; representation and expression never become meaning; similarity never becomes identity; context is part of identity; knowledge is a relationship | 1 | the aggregate root: KnowledgeId assignment; every MeaningTranslated event; every context-less claim rejected as incomplete | INV-KOS-002 · H-KOS-Context-001 · H-KOS-Context-002 · H-KOS-Relationship-001 · H-KOS-Relation-001 |
| **INV-KOS-DIMENSION-001** — Dimensions evolve independently; no implicit transition; no scalar surrogate for structure | 2 | the aggregate boundary: no member changes except by a governed, named transition; no single-score collapse | INV-KOS-001 · H-KOS-NonInterference-001 |
| **INV-KOS-AUTHORITY-001** — Authority is assigned, never emergent; evidence/assessment/source never self-authorize; source roles preserved | 3 | the AuthorityGrant boundary; the aggregate references, never holds, authority | INV-001 · INV-003 · INV-KOS-Pramana-001 |
| **INV-KOS-DECISION-001** — Knowledge informs, never executes | 4 | the Decision Boundary port (ACL); no knowledge → action path outside an authorized decision step | INV-002 · INV-KOS-Decision-001 |
| **INV-KOS-PROJECTION-001** — A projection is never its source | 5 | the Projection port (ACL); regenerable, no write-back | INV-004 |
| **INV-KOS-VERIFICATION-001** — No entry into knowledge without a preserved justification path; generation never becomes justification; observation never becomes inference | 6 | the Verification Port — the only admission path into the aggregate | INV-KOS-Inference-001 · INV-KOS-Justification-001 · H-KOS-Reasoning-Separation-001 · H-KOS-Reasoning-Provenance-001 · H-KOS-Vyapti-001 · H-KOS-EvidenceAuthenticity-001 |
| **INV-KOS-FAILURE-001** — Failed reasoning is preserved as an explicit state; never knowledge, never silently discarded | 7 | EpistemicState (REJECTED) and the History member | H-KOS-Fallacy-001 · H-KOS-Failure-001 |
| **INV-KOS-CONTRADICTION-001** — Conflicting knowledge coexists as CONFLICTED until governed resolution; challenge never destroys identity | 8 | EpistemicState (CONFLICTED) + ConflictRecord | H-KOS-Contradiction-001 · H-KOS-Dialogue-001 |
| **INV-KOS-UNKNOWN-001** — UNKNOWN is first-class; unknown ≠ absent ≠ false; uncertainty is preserved, never flattened | 9 | EpistemicState (UNKNOWN · ABSENT · FALSE as distinct states) | H-ZERO-001 · H-KOS-Uncertainty-001 |
| **INV-KOS-AGENCY-001** — Every state preserves epistemic agency; knowledge is never anonymous | 10 | the Agency member; a state without agency is rejected at creation | INV-KOS-Agent-001 · H-KOS-Relationship-001 (knower-side) |
| **INV-KOS-HISTORY-001** — Revision never deletes; supersession is forward-only; freshness never truth, expiry never absence | 11 | the History member; every revision creates a new state, never overwrites | INV-KOS-Revisability-001 · Temporal family |

**Discipline note.** The four example invariants the HPA named are all present, verbatim-in-substance: *"Representation must not become Identity"* = INV-KOS-IDENTITY-001 (Article 1.2) · *"Evidence must not become Authority"* = INV-KOS-AUTHORITY-001 (Article 3.1) · *"Revision must not erase History"* = INV-KOS-HISTORY-001 (Article 11.1) · *"Similarity must not become Equality"* = INV-KOS-IDENTITY-001 (Article 1.3). The eleven aggregate invariants are **renders of the frozen articles** — no register row added, none removed (register **25+4 unchanged**).

**Altitude note (HPA review 2026-08-22).** The phrase *"command surface"* is deliberately absent from this revision. At v1.1 the aggregate boundary is an **architecture-level statement** — constitutional admissibility of a state transition is determined at that boundary — and the concrete realization (commands, methods, domain services, policy evaluation, or any other form) is a **Logical / Implementation Architecture decision**, deliberately deferred. v1.1 must not pre-decide an implementation pattern (forbidden actions: no APIs, no classes, no code).

---

## 7 · Mechanisms — the three-way altitude

The Constitution's kernel/engine boundary, rendered as the three altitudes. **Nothing moves between altitudes except by constitutional amendment.**

| Altitude | What it is | Members | May it change? |
|---|---|---|---|
| **KERNEL — must exist** | the constitutional invariants and the aggregate that enforces them | the eleven invariants (§6) · the KnowledgeAggregate · ConflictRecord · the three small supporting aggregates | **No** — V.2: amendment by the HPA only; no article weakened |
| **MECHANISM — can change** | the engines that propose, transform, and evaluate within the field | validation · reasoning · contradiction/debate · fallacy detection · history/revision · intent classification · LLM (governed generation) | **Yes** — V.1: free evolution, subject only to "no evolution violates an article" |
| **REPRESENTATION — projection** | the expressions knowledge moves through; projections, never sources | evidence records · context tuples · epistemic state model · absence taxonomy · verdict vocabulary · typed epistemic graph · derived views | **Yes** — V.1; each is a projection of the kernel's content, regenerable, non-authoritative (Article 5) |

### 7.1 The kernel does not do the reasoning (Ch III.1, now structural)

The kernel altitude contains **no reasoner**. The KnowledgeAggregate enforces invariants; the engines at its ports do the reasoning. A kernel member can reject a state transition, record a state, assign an identity, retain a history — it **cannot generate a conclusion**. This is the same §16 boundary as v1.0; DDD renders it as *the aggregate has no dependency on any engine* — every engine is an adapter at a port, replaceable without touching the core.

### 7.2 LLM placement — the relationship, fixed

KnowledgeOS is **NOT** an LLM replacement · **NOT** a truth oracle · **NOT** an autonomous intelligence · **NOT** a knowledge database. The division of labour (BV-2, candidate hypothesis):

| | Role | Owns |
|---|---|---|
| **LLM = Language Cortex** | expression generation — explanation · creativity · ambiguity resolution · conversation | **does not own truth**; generates candidates and expression |
| **Semantic Compiler = Meaning intelligence** | expression↔meaning translation — the missing middle (*AST for meaning*) | **does not own identity**; translates, never determines identity (Article 1.2) |
| **KnowledgeOS = Epistemic intelligence** | epistemic state management — evidence · history · policies · confidence · *maintaining correct knowledge over time* | **owns the justified epistemic state** |

The LLM is placed at the **Expression port** as an external adapter (GOVERN): its output enters the core only through the Verification Port as a candidate with a captured justification path (Article 6.3). *The LLM speaks; the engine knows* (BV-4). The two structural absences of the LLM — persistent epistemic identity and historical belief evolution — are **placement**: identity and history live in the core, external to the LLM (v1.0 §4, re-affirmed).

### 7.3 Semantic Compiler placement — the ruling

> **Ruling: NOT promoted. Candidate adapter at the Expression port, for the Logical Architecture stage.**

| Option | Verdict | Why |
|---|---|---|
| **Core** | ❌ | the core is epistemic states; meaning *translation* is a mechanism serving identity preservation (Article 1.2), not the domain itself. Making it core would make KnowledgeOS a *language engine* — a §8 rejected concept |
| **Supporting (as a domain)** | ❌ | a supporting *domain* owns state and aggregates; the Semantic Compiler owns none — it is a transformation between Expression and Meaning |
| **Generic / Infrastructure** | ⚠️ not now | a generic mechanism it may become; the evidence is not yet at that altitude |
| **Future** | ✅ **placed here** | per BV-8, the Semantic Compiler / SIL family is *deepened + HPA-ratified as internal structure of already-recorded material* — **not new register rows** — and the Trustworthy AI Engine (LLM + Semantic Compiler + KnowledgeOS) is a **candidate architecture hypothesis for the Logical Architecture stage, not an established result**. **Do not promote without evidence** → it stays a candidate adapter at the Expression↔Meaning port of the core, recorded but not promoted |

### 7.4 Zero placement — the meta-principle

> **Zero is not an entity, aggregate, service, or database object.** It sits **above** the architecture: the epistemic-honesty posture — *"I do not yet know"* is a valid first state — is realized *through* the aggregate (its initial state is **UNKNOWN**, INV-KOS-UNKNOWN-001) and the Negative-State discipline (unknown ≠ absent ≠ false). Zero is the **meta-principle** that the system begins from not-knowing; it is not a component that does anything. Placing it in the architecture as an element would be category error — the principle of the system is not a part of the system.

---

## 8 · Rejected Concepts — "Not KnowledgeOS"

Each refusal is the enforcement-face of the articles (Ch IV); the DDD refinement re-states them at the boundary.

| Concept | Why it is rejected | Article it would collapse |
|---|---|---|
| **Language engine** | knowledge is not expression; expression must never become meaning | Article 1.2 |
| **Database** | stores facts → knowledge is a verified transition, not stored information | Article 6 |
| **Chatbot** | conversation is generation, not epistemic state management | Articles 6, 11 |
| **LLM wrapper** | generation is never justification; an LLM lacks identity and history | Articles 1, 6, 11 |
| **Ontology repository** | categories and relations are representations, not justified belief | Article 6 (and Article 1 — categories never become identity) |
| **Truth machine / truth oracle** | the system preserves the conditions of discovery; it does not answer with authority | Articles 3, 9 |

**The rejected set is why the core is small.** Every one of these is a *simpler system* that KnowledgeOS must refuse to become. The discipline of the core — one aggregate, eleven invariants, every engine at a port — is what makes the refusals structural rather than aspirational.

---

## Final Quality Gates

**1 · Reduction Test — *Did KnowledgeOS become smaller and clearer?*** **Passed.** v1.0: four layers, eleven kernel services, six engines, six representations. v1.1: **one core domain, one primary aggregate, three small supporting aggregates**, the engines re-placed as adapters. The element count of *what must exist* drops from eleven services to **one aggregate**. Nine dimensions → seven aggregate members with two processes correctly externalized. Clearer: the enforcement locus of every constitutional forbiddance is now a single named object — the aggregate at whose boundary constitutional admissibility of state transitions is determined.

**2 · Identity Test — *If all implementations change, is it still KnowledgeOS?*** **Passed.** The core domain — identity-bearing justified epistemic states through their justified life — is implementation-free: no storage, no engine, no interface choice can change it. The Identity Test's own instrument: *if identity disappears, Knowledge = documents / embeddings / similarity* — all three are the Ch IV refusals, and identity is the aggregate root.

**3 · Failure Test — *If this principle disappears, does KnowledgeOS stop being KnowledgeOS?*** **Passed for the core.** Remove the identity-of-meaning invariant (INV-KOS-IDENTITY-001) and the aggregate becomes a claim-store; remove the lifecycle (INV-KOS-HISTORY-001 + the domain events) and it becomes a snapshot-store. Both are Ch IV refusals. The core is exactly the set whose removal changes what the system is — nothing larger, nothing smaller.

**Mission check (verbatim-in-substance):** *"Refine KnowledgeOS from a collection of insights into a disciplined domain architecture where meaning, evidence, identity, reasoning, and evolution are protected through explicit boundaries and invariants."* — **met**: the boundaries are the context map (§2), the invariants are the eleven aggregate invariants (§6), and the protected objects are meaning, evidence, identity, reasoning, and evolution (§3, §4, §5).

---

## Traceability

- **Position:** the HPA review step of Reference Architecture v1.0, executed as a DDD refinement → produces Reference Architecture v1.1. *"Before any logical architecture or implementation discussion."*
- **Inputs:** commissioning instrument `20260822-1402-KOS-EP01-Reference-Architecture-v1.1-DDD-Refinement-commissioning-prompt.md` · Reference Architecture v1.0 (`20260822-0955-KOS-EP01-Reference-Architecture-v1.0.md`) · Constitution v1.0 (`20260822-0951-KOS-EP01-Constitution-v1.0.md` — FROZEN · the eleven articles · Ch III kernel boundary · Ch IV negative boundary · Ch V amendment discipline) · Research-Phase Closure Ratification (`20260822-1028`) · P4 Constitutional Invariant Map v1.0 · P5 Decision Matrix · step-⑤ kernel decision · HPA AI-engine vision blocks BV-1..BV-8 (recorded `.claude/sessions/2026-08-22.md`).
- **Constraints honored:** satisfies, never extends the Constitution · register **25+4 unchanged** · no new laws/concepts/rows/philosophy · **no database tables · no technologies · no APIs · no classes · no code** — the aggregate, value objects, and events are named domain concepts with responsibility and invariant, not implementation · research phase not reopened · the final rule (strongest statement never exceeds the evidence; the Semantic Compiler and the Trustworthy AI Engine remain candidate hypotheses).
- **What is deliberately deferred:** Logical Architecture (ports and adapters in detail; the Trustworthy AI Engine is named material for that stage — BV-8) → Implementation Architecture (technology) → Systems.
- **Review:** HPA review of v1.1 (`docs/knowledgeos/reviews/20260822-1425-KOS-EP01-Reference-Architecture-v1.1-DDD-Refinement-HPA-Review.md`) — **PASS CONDITIONALLY**; the condition (the aggregate boundary phrased at Reference-Architecture altitude, not as an implementation "command surface") applied in this revision.
- **Status:** ✅ **COMPLETED — Reference Architecture v1.1 (DDD Bounded Context and Core Domain Model) produced · PROPOSED · HPA review delivered 2026-08-22 (PASS CONDITIONALLY, one wording refinement — applied) · awaiting HPA confirmation of the applied wording. Next: HPA confirmation → Logical Architecture → Implementation Architecture → Systems.**
