# KnowledgeOS Reference Architecture v1.1 — CONSOLIDATED (r3)

> **DDD Bounded Context and Core Domain Model** — the artifact's established identity, revised in place: **r1** (produced) → **r2** (first HPA review condition applied) → **r3 CONSOLIDATED** → **r4** (architectural-direction annotations — the six negative-direction prohibitions · Expression↔Meaning Port Contract opened, 2026-08-22).

---

## 1 · Architectural status

| | |
|---|---|
| **Status** | ⭐ **CONSOLIDATED (r3) · PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE** |
| **Position** | Constitution v1.0 (FROZEN) → Reference Architecture v1.0 (PROPOSED) → **this v1.1** → Logical Architecture → Implementation Architecture → Systems |
| **Reviews** | First HPA review (2026-08-22 14:25) — **PASS CONDITIONALLY → condition applied in r2 → CLOSED** (confirmation, 15:27) · Second architectural review (14:59) — **PASS · CLARIFICATION ONLY → ACCEPTED as architect-side delivery** (15:23) |
| **This revision** | the **r3 change set applied** — C-1…C-5 · R-1 · A-1…A-3 — under the HPA consolidation commission (`docs/knowledgeos/reviews/20260822-1533-…-r3-Consolidation-commissioning-prompt.md`) |
| **r4 annotations** | the HPA's **architectural-direction steering** (2026-08-22) — the **six negative-direction prohibitions** as invariant interpretations (three already present ⟨C-1⟩; three added: **Probability→Truth · Canonicalization→Authority · Low entropy→Certainty**) · the **Expression↔Meaning Port Contract opened and authored** — authority: `docs/knowledgeos/reviews/20260822-1559-…-r4-and-Port-Contract-HPA-steering.md` |
| **Change authority** | **the r3 change set and nothing else** for r3 · **the HPA's 2026-08-22 steering and nothing else** for r4. No new architecture is introduced; anything outside the change sets is recorded as an observation (Appendix B), never incorporated |
| **Unchanged by r3** | the core-domain decision · the context map · the aggregate member set · the eleven invariants' wording · the ten domain events · the rejected set · the Semantic Compiler ruling · the LLM boundary · Zero's placement |
| **Constitution** | v1.0 **FROZEN** — satisfied, **never extended**. No article added, weakened, or reinterpreted |
| **Register** | **25+4 unchanged** · research phase **CLOSED** · P4 gate **unchanged** |
| **Opened by r4** | the **Expression↔Meaning Port Contract** — first Logical-Architecture deliverable, authored under the 2026-08-22 HPA steering (deliverable: `docs/knowledgeos/architecture/20260822-1559-KOS-Expression-Meaning-Port-Contract.md`) |
| **Not opened by this artifact** | Semantic Compiler / SNF implementation · the KOS-SCB v0.2 experiment (**OQ-4 unauthorized**) · KOS-SNF-ME v0.4 (**gated, not authorized**) · database · API · class · framework · model selection · Logical Architecture **beyond** the Expression↔Meaning Port Contract |
| **Deliberately open** | **OQ-1 … OQ-5** (§20) — in particular **OQ-2**, which this artifact records but does **not** answer (the Port Contract takes a position at contract altitude; the ruling remains the HPA's) |

**Canonical wording (D-1 ratified, HPA 2026-08-22):**

> **"The KnowledgeAggregate is the authoritative domain boundary at which constitutional admissibility of a state transition is determined."**

Used verbatim throughout. It is **not** to be replaced by *"constitutional administration"*, *"constitutional state"*, *"truth determination"*, or *"knowledge validation"*. The operative concept is `KnowledgeAggregate → state transition → constitutional admissibility` — **the aggregate is not a truth oracle**: it determines what transition is *admissible*, never what is *true*.

---

## 2 · Executive Summary

**What v1.1 is.** v1.0 was a layer architecture — four layers, eleven kernel services, six engines, six representation models — answering *"what boundaries must exist"* in the language of services. v1.1 asks the DDD question — *"what is the domain, and what is its smallest irreducible core?"* — and the answer **shrinks the architecture**: **one core domain (Knowledge Identity), one primary aggregate (KnowledgeAggregate) whose invariants are the eleven constitutional articles, one ConflictRecord, three small supporting aggregates, and every engine re-placed as an adapter at a port.**

| | v1.0 | v1.1 (consolidated) |
|---|---|---|
| Primary view | layers and services | **bounded contexts and one aggregate** |
| Core | eleven kernel services | **one core domain, one primary aggregate** |
| Enforceability | boundaries making forbiddances structurally impossible | the same forbiddances as **aggregate invariants** — the aggregate is the authoritative domain boundary at which constitutional admissibility of a state transition is determined |
| Supporting | (implicit) | three named contexts (Authority · Projection · Decision Boundary) |
| Mechanisms | six engines | the same engines as **adapters at ports** (hexagonal form of the kernel boundary) |
| LLM | governed generation mechanism | **external adapter at the Expression port** (Language Cortex) |
| Semantic Compiler | research candidate, unplaced | **not promoted** — candidate adapter at the Expression↔Meaning port |
| Semantic Normal Form | — | **representation (primary) / mechanism (secondary)** — never an identity authority (r3) |

**The reduction in one sentence:** v1.0 required eleven services to *be* the kernel; v1.1 requires **one aggregate** — the KnowledgeAggregate — whose invariants *are* the eleven articles. The kernel's content is unchanged; its **enforcement locus** is now a single named boundary.

**What r3 adds — and does not add.** r3 introduces **no concept, no context, no aggregate, no member, no event, and no invariant**. It sharpens the **Expression → Meaning boundary** with what the post-review evidence taught: a mechanism may *propose* meaning, and the sharper form of the same rule — **a canonical form is still a representation, so representation equality must never silently become identity**. Four members gain boundary notes (Meaning · EvidenceLinks · Relations · Confidence), Zero gains an operational reading, three falsification results are recorded, one forward deliverable is *named*, and two naming guards are set. The architecture becomes **more enforceable without becoming larger**.

**The decisive falsification result (A-1, Test F).** Remove natural language *entirely* — knowledge arriving only as structured submissions — and the core is unharmed: identity still assigned, evidence still linked, justification still preserved, agency still recorded, contradictions still coexisting, history still forward-only, states still first-class. Only *reach* is lost. **Therefore no mechanism whose reason for existing is natural language can be core** — which settles the altitude of semantic compilation, semantic invariance and SNF in one move (§10–§12).

**The one-line character is unchanged** (head 43, ratified): KnowledgeOS is a constitutional epistemic operating system that preserves the identity of a justified epistemic state through its justified life — *created, justified, transformed, challenged, evolved* — never as stored information, always as **a verified transition**.

---

## 3 · Architectural Principles

| # | Principle | Consequence in this architecture |
|---|---|---|
| **P-1** | **KnowledgeOS owns the identity and justified evolution of knowledge; it does not own the mechanism by which meaning is extracted from expression.** | Every parser, compiler, reasoner and language model is an adapter at a port (§16) |
| **P-2** | **A semantic mechanism may construct a meaning candidate; only the KnowledgeOS domain determines the constitutional admissibility of a state transition.** | The Verification Port is the only admission path (§7, INV-KOS-VERIFICATION-001) |
| **P-3** | **Canonical representation may support identity reasoning, but representation equality must never silently become identity.** | §12 (SNF) · C-1 · the Non-collapse gate (§19) |
| **P-4** | **Identity is assigned, never derived.** | KnowledgeId is assigned at creation; no similarity, coextension, observable match or canonical-form equality produces it |
| **P-5** | **The kernel does not reason.** | The aggregate admits or refuses transitions; it never generates a conclusion (§16) |
| **P-6** | **Absence of sufficient grounds must be representable without becoming a false positive.** | UNKNOWN is first-class and is the initial state (§9, §14) |
| **P-7** | **Satisfy the Constitution; never extend it.** | The eleven invariants are *renders* of the eleven frozen articles (§15) |
| **P-8** | **Mechanisms are replaceable; the boundary is not.** | The Replacement Test is a standing gate, not a one-time argument (§11, §19) |
| **P-9** | **Every altitude keeps its own vocabulary.** | Kernel / mechanism / representation never collapse (§16); benchmark names never become components (A-3) |

---

## 4 · Core Domain

> ### Core Domain = **Knowledge Identity** — the domain of identity-bearing justified epistemic states and their justified life.

```
                       KNOWLEDGEOS CORE

                    Knowledge Identity
                             │
                     justified lifecycle
                             │
                             ▼
                     KnowledgeAggregate
                             │
                             ▼
        constitutional admissibility of a state transition
```

**What makes this KnowledgeOS rather than something simpler.** Remove identity and the system is a document store, an embedding database, or a similarity engine. Remove the justified lifecycle and it is a snapshot store. Remove justification and it is a claim store. Each of those is a **Chapter IV refusal** — a simpler system KnowledgeOS must refuse to become (§17).

### 4.1 The four candidate cores, tested

| Candidate | Test | Ruling |
|---|---|---|
| **Knowledge Identity** | remove it → knowledge = documents / embeddings / similarity — all Ch IV refusals | **ADOPTED — the core domain** |
| **Epistemic Evolution** | remove it → static storage (Ch IV repository); but evolution is the *behavior of the identity-bearing object*, not a second domain over the same object | **FOLDED IN** as the aggregate's lifecycle |
| **Meaning Preservation** | remove it → representation becomes meaning (Article 1.2); but preserving meaning *is* what identity means | **FOLDED IN** as identity's defining facet |
| **Wisdom Formation** | no register row; the character ends at *trustworthy truth discovery*; adopting it would extend the frozen Constitution (V.3) | **REJECTED at the boundary** (§17; amendment-gated) |

### 4.2 The dimension challenge — KEEP / MODIFY / REMOVE

| Dimension | Ruling | Removal test | Landing |
|---|---|---|---|
| **Identity** | KEEP | documents? embeddings? similarity? → Ch IV | aggregate root (KnowledgeId) |
| **Context** | KEEP | claims become unbounded (Article 1.4) | ContextTuple member |
| **Evidence** | KEEP | knowledge = assertion (Article 6) | EvidenceLinks member |
| **Authority** | KEEP (assigned, never emergent) | self-authorization · source-role collapse (Article 3) | supporting Authority context; **referenced**, never held |
| **Transformation** | **MODIFY** — dimension → **lifecycle** | static storage → Ch IV repository | state transitions + domain events (§8) |
| **Temporal** | KEEP | freshness = truth · revision = deletion (Article 11) | TemporalValidity member |
| **Reasoning** | **MODIFY** — the *path* is kept, the *process* is external | opaque inference (Article 6.4) | JustificationPath member + Reasoning & Validation context |
| **Contradiction** | KEEP — as a **state**, not a dimension | premature TRUE/FALSE · weaker side deleted (Article 8) | EpistemicState (CONFLICTED) + ConflictRecord |
| **Agent** | **MODIFY** — epistemic **agency** kept; agent-as-actor external | anonymous knowledge (Article 10) | Agency member + external actor |

**Result:** nine dimensions → seven protected aggregate members + the state + history and relations, with the two *processes* (reasoning, transformation) correctly placed as behavior and adapters. The set is smaller because the processes were never dimensions of the object — they were the system's verbs.

### 4.3 Why this core and no larger one

- **No larger core:** evidence, authority, contradiction, projection and decision are each either *members of* the aggregate or *guards at its boundary*. Making any of them a core domain would place two domains over one object and split the invariants.
- **No smaller core:** remove identity → claim-store; remove the lifecycle → snapshot-store. Both are Ch IV refusals. **Identity + lifecycle (identity through change) is the irreducible unit.**
- **The core answers the system's own question:** *"what is justified to believe, by whom, based on what evidence, through what reasoning, at what time?"*

---

## 5 · Bounded Context Map

### 5.1 Domain stratification

| Stratum | Bounded context | Why it sits there |
|---|---|---|
| **CORE** | **KnowledgeCore** — identity-bearing justified epistemic states and their lifecycle | the only context whose removal changes what the system *is*. **Nothing else is core** |
| **SUPPORTING** | **Authority** — assigns authority as a recorded reference to a human act | guards Article 3; it authorizes states, it does not define them |
| **SUPPORTING** | **Projection** — derives regenerable, non-authoritative views | guards Article 5; presentation of knowledge, not knowledge |
| **SUPPORTING** | **Decision Boundary** — the governed interlock between knowledge and action | guards Article 4; terminates knowledge at recommendation |
| **GENERIC / EXTERNAL** | **Reasoning & Validation** — validation · reasoning · contradiction/debate · fallacy detection · history/revision · intent classification | **mechanisms**: they propose, transform and evaluate candidates. The kernel does not reason |
| **GENERIC / EXTERNAL** | **Expression** — the expression↔meaning translators: LLM as Language Cortex · Semantic Compiler as a *candidate* adapter · **normalizers producing canonical forms (SNF)** | **mechanisms**: they generate and translate expression. Positioned so expression can never become meaning (Article 1.2) |

**No new bounded context is created by r3.** Semantic Compiler, Semantic Invariance Layer and SNF fail all five context tests — no independent domain responsibility, no independent invariants, no independent lifecycle, no independent ownership, and their vocabulary is *mechanism* vocabulary that the existing **Expression** context already holds. Giving a mechanism the dignity of a domain would be architecture by accumulation.

### 5.2 The context map

```
                      ┌───────────────────────────────────────────────┐
                      │  EXPRESSION  (Generic/External — mechanisms)   │
                      │  LLM = Language Cortex · Semantic Compiler     │
                      │  (candidate adapter, NOT promoted)             │
                      │  · normalizers / canonical forms (SNF)         │
                      └───────────────┬───────────────────────────────┘
                                      │  Expression ↔ Meaning  (ACL — Article 1.2)
                                      │  may translate · never determines identity
                                      │  submits a MEANING CANDIDATE only
                                      ▼
┌──────────────┐   assigns   ┌───────────────────────────────────────────────┐   reads   ┌──────────────────┐
│  AUTHORITY   │◀───────────▶│              KNOWLEDGE CORE                    │──────────▶│   PROJECTION      │
│ (Supporting) │  authority  │  (CORE — the only core domain)                 │ read-only │  (Supporting)      │
│AuthorityGrant│ referenced  │  KnowledgeAggregate · ConflictRecord           │ (ACL —    │  DerivedView       │
└──────────────┘             │  invariants = the eleven articles              │  Art 5)   └──────────────────┘
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

**Reading the map:** every arrow into the core is a **port**; every arrow out is **advisory or projection**. The core is the only context that owns justified epistemic state; every other context either *feeds* it (Authority, Reasoning, Expression) or *reads* it (Projection, Decision). This is the hexagonal form of the kernel boundary.

### 5.3 The contexts, defined

| Context | Purpose | Authoritative language | Owns | Invariants it guards | Depends on | Relationship |
|---|---|---|---|---|---|---|
| **KnowledgeCore** (CORE) | preserve the identity and justified life of epistemic states | knowledge · identity · admitted meaning · context · evidence link · justification path · agency · epistemic state · temporal validity · history · relation · conflict | KnowledgeAggregate · ConflictRecord — **all eleven invariants** | all eleven | nothing (by design) | upstream of everything; consumes candidates via ports |
| **Authority** (SUPPORTING) | record the human act that assigns authority | authority grant · source role | AuthorityGrant | Article 3 | a human act | the core **references**, never holds authority |
| **Projection** (SUPPORTING) | derive regenerable, non-authoritative views | derived view · projection | DerivedView | Article 5 | the core (read-only) | downstream; **no write-back** |
| **Decision Boundary** (SUPPORTING) | the governed interlock between knowledge and action | decision record · recommendation | DecisionRecord | Article 4 | the core (advisory) | downstream; **informs, never executes** |
| **Reasoning & Validation** (GENERIC/EXTERNAL) | propose, transform, evaluate candidates | premise · rule · inference · verdict · fallacy | its own transient artifacts — **no domain state** | none of its own; **bound by the port contract** | the core's published language | adapter at the Verification Port |
| **Expression** (GENERIC/EXTERNAL) | translate between expression and meaning | expression · token · parse · canonical form · meaning candidate | its own transient artifacts — **no domain state** | none of its own; **bound by the port contract** | the core's published language | adapter at the Expression↔Meaning Port (ACL) |

---

## 6 · KnowledgeAggregate

**Aggregate root: Knowledge**, identified by its **KnowledgeId** — the identity-of-meaning, **assigned at creation, never derived** (Articles 1.1, 1.3).

> **The KnowledgeAggregate is the authoritative domain boundary at which constitutional admissibility of a state transition is determined.**

No transition is admitted without passing the aggregate's invariants, and no member is reachable from outside except through the root.

**Altitude note.** *Whether that boundary is realized as commands, methods, domain services, policy evaluation, or any other form is a Logical / Implementation Architecture decision, deliberately deferred.* v1.1 names the boundary, not the mechanism (forbidden actions: no APIs, no classes, no code). The phrase *"command surface"* is deliberately absent.

**The aggregate is not a bag containing everything about knowledge.** Each member below passed five tests: *who owns its lifecycle · who must enforce its invariants · must it change atomically with knowledge state · can it evolve independently · can it be replaced without changing KnowledgeOS's identity.*

| Member | Kind | Responsibility | Article |
|---|---|---|---|
| **KnowledgeId** | Value object | the identity of meaning — assigned once, stable through representation, expression, context and projection change; **never** derived from similarity, observable match, coextension, **or canonical-form equality** | 1 |
| **Meaning** | Value object | the **admitted** intensional content — *what the knowledge is* — the preservation target of identity. **⟨C-2⟩** The core holds *admitted* meaning; a mechanism's output is a **meaning candidate outside the boundary** and becomes Meaning only by admission through the Verification Port | 1 |
| **ContextTuple** | Value object | the delimiting conditions (entity · property · context · relation · time · authority) — the frame beyond which the claim is incomplete | 1.4 |
| **EvidenceLinks** | Collection | the justification: evidence **references** + acquisition method + reliability conditions; pseudo-evidence never admitted. **⟨C-3⟩** The core owns the **links**, never the evidence *content* — external systems own the artifacts. Holding content would drift the core toward the Ch IV **database** refusal | 6 |
| **JustificationPath** | Value object | the reasoning **path**: premises · rules · assumptions · inference rule (Vyapti-warranted) · conclusion — the reason the state is justified, never a black box. The *record* of reasoning; the *process* is external | 6.4 |
| **Authority** | Reference | the assigned authority — a recorded reference to a human act; **held by the Authority context, referenced by the aggregate** | 3 |
| **Agency** | Value object | the epistemic lineage: who observed · reasoned · validated · decided · under which authority — traceable, never a binding to a person. The one member whose **absence rejects creation** | 10 |
| **EpistemicState** | Value object | the state vocabulary: **VALIDATED · QUESTIONABLE · REJECTED · CONFLICTED · UNKNOWN · ABSENT · FALSE** — first-class negative and failure states. **No mechanism may ever produce this member** | 7, 8, 9 |
| **TemporalValidity** | Value object | valid-from · valid-until · superseded-by — freshness never truth, expiry never absence | 11 |
| **Confidence** | Value object (governed) | a **structured** epistemic attribute — never a scalar replacing epistemic structure (Article 2.3); carried as Temporal Epistemic Metadata (UM-46), not as a knowledge-quality score. **⟨R-1⟩ Boundary rule: Confidence is assigned INSIDE the boundary. A mechanism-supplied score — parser accuracy, model likelihood, match strength — must never cross the port and become Confidence.** A mechanism's *"74% confident"* is a statement about the mechanism, not about the knowledge | 2 |
| **History** | Value object (immutable) | the forward-only revision log — every prior state retained; supersession never overwrites | 11 |
| **Relations** | Collection | the relationship links to other aggregates (supports · contradicts · bounded-by) — knowledge is a relationship, never a detached object. **⟨C-4⟩ By KnowledgeId reference only, never containment** — so relations cannot create transactional coupling across aggregates | 1.5 |

**The aggregate boundary in one sentence:** nothing inside is reachable from outside except through the root; no state transition is admitted that violates an article; no member is modified in place without creating a new forward-only state.

### 6.1 ConflictRecord (second aggregate of the core)

Conflicting knowledge **coexists as CONFLICTED** until governed resolution, and the conflict record survives resolution forward-only (Article 8). Because two KnowledgeAggregates cannot own each other, the conflict is its own small aggregate.

- **Root: ConflictRecord** — references the conflicting aggregates **by identity**, never containing them.
- **Value objects:** ConflictState (CONFLICTED · RESOLVED) · Resolution (the governed determination + its authority reference).
- **Invariants:** conflict preserved until resolution · challenge never destroys identity (8.2) · resolution forward-only, the record survives (8.3).
- **Events:** ContradictionDetected · ContradictionResolved.

### 6.2 Supporting aggregates

| Aggregate | Context | Responsibility | Invariant |
|---|---|---|---|
| **AuthorityGrant** | Authority | the recorded reference to a human act assigning authority | never self-authorizes; source roles preserved (Article 3) |
| **DerivedView** | Projection | a regenerable, non-authoritative projection of a knowledge state | never its source; no write-back (Article 5) |
| **DecisionRecord** | Decision Boundary | the record that knowledge informed action at a time, advisory only | informs, never executes (Article 4) |

---

## 7 · Aggregate Invariants

The eleven articles, rendered as aggregate invariants. **A transition that would violate an invariant is not admitted, whatever the engine, mechanism, or representation behind it.** No article is weakened; none is extended; each is given an enforcement locus.

| Invariant | Statement | Enforcement locus |
|---|---|---|
| **INV-KOS-IDENTITY-001** | Identity is assigned, never derived; representation and expression never become meaning; **similarity never becomes identity** — and **⟨C-1⟩ canonical-form equality is a similarity claim, not an identity determination**; context is part of identity; knowledge is a relationship | the aggregate root: KnowledgeId assignment · every MeaningTranslated event · every context-less claim rejected as incomplete |
| **INV-KOS-DIMENSION-001** | Dimensions evolve independently; no implicit transition; **no scalar surrogate for structure** — **⟨R-1⟩ no mechanism-supplied score becomes Confidence** | the aggregate boundary: no member changes except by a governed, named transition; no single-score collapse |
| **INV-KOS-AUTHORITY-001** | Authority is assigned, never emergent; evidence, assessment and source never self-authorize; source roles preserved | the AuthorityGrant boundary; the aggregate references, never holds |
| **INV-KOS-DECISION-001** | Knowledge informs, never executes | the Decision Boundary port (ACL) |
| **INV-KOS-PROJECTION-001** | A projection is never its source | the Projection port (ACL); regenerable, no write-back |
| **INV-KOS-VERIFICATION-001** | No entry into knowledge without a preserved justification path; generation never becomes justification; observation never becomes inference | the **Verification Port — the only admission path** into the aggregate |
| **INV-KOS-FAILURE-001** | Failed reasoning is preserved as an explicit state; never knowledge, never silently discarded | EpistemicState (REJECTED) + History |
| **INV-KOS-CONTRADICTION-001** | Conflicting knowledge coexists as CONFLICTED until governed resolution; challenge never destroys identity | EpistemicState (CONFLICTED) + ConflictRecord |
| **INV-KOS-UNKNOWN-001** | UNKNOWN is first-class; unknown ≠ absent ≠ false; uncertainty is preserved, never flattened — **⟨C-5⟩ a mechanism's inability to determine meaning maps to UNKNOWN, never to ABSENT, FALSE, or a low-confidence accept** | EpistemicState (UNKNOWN · ABSENT · FALSE as distinct states) |
| **INV-KOS-AGENCY-001** | Every state preserves epistemic agency; knowledge is never anonymous | the Agency member; a state without agency is rejected at creation |
| **INV-KOS-HISTORY-001** | Revision never deletes; supersession is forward-only; freshness never truth, expiry never absence | the History member; every revision creates a new state |

---

## 8 · Domain Events

Each event is a named state transition of the KnowledgeAggregate (or ConflictRecord) and carries the invariant it honors. **Events are the lifecycle made visible.**

| Event | Trigger | Aggregate effect | Article |
|---|---|---|---|
| **KnowledgeCreated** | a candidate passed the Verification Gate with a preserved justification path | aggregate created; identity assigned; agency recorded; state set; timestamped | 1, 6, 10, 11 |
| **EvidenceAdded** | new authentic evidence linked | EvidenceLinks extended | 6 |
| **BeliefRevised** | new evidence or reasoning changes the justification | EpistemicState transitioned forward-only; prior state → History | 7, 11 |
| **MeaningTranslated** | an expression↔meaning translation is performed | meaning preserved; **KnowledgeId unchanged** | 1.2, 1.3 |
| **ContradictionDetected** | a claim conflicts with an existing state | CONFLICTED; ConflictRecord created | 8 |
| **ContradictionResolved** | a governed resolution is reached | ConflictRecord → RESOLVED; record retained | 8.3, 11 |
| **KnowledgeSuperseded** | a new version supersedes an old one | old state → History; forward-only | 11 |
| **KnowledgeRejected** | a candidate failed verification | preserved as REJECTED; never discarded, never knowledge | 7 |
| **AuthorityAssigned** | an authority grant is recorded | the aggregate's Authority reference updated | 3 |
| **DecisionInformed** | knowledge is made available to an authorized decision step | recommendation issued; **execution remains outside the core** | 4 |

**⟨A-3⟩ Event admission guard.** An event records a **domain state transition**, never a technical operation. **Not events:** a parse · a normalization · a canonical-form computation · a database write · an LLM response · a benchmark run. If nothing in the aggregate changes, there is no domain event. Candidate names such as *MeaningNormalized* or *SNFComputed* are therefore **not admitted**.

**⚠️ WisdomDerived — recorded for traceability, NOT admitted.** Wisdom is not a constitutional concept (no register row; the character ends at trustworthy truth discovery). A future Wisdom context would require a constitutional amendment, and V.3 forbids adding concepts. The discipline, not the concept, governs — and the same discipline governs the guard above.

---

## 9 · Epistemic Lifecycle

The justified life of one identity, in the domain's own vocabulary.

```
              (a candidate arrives at the Verification Port)
                                │
             ┌──────────────────┴──────────────────┐
             │ justification path preserved?        │
             │ agency present? context present?     │
             └──────────────────┬──────────────────┘
                     no │              │ yes
                        ▼              ▼
               KnowledgeRejected   KnowledgeCreated ── initial state: UNKNOWN
                (REJECTED, kept)         │
                                         │  EvidenceAdded · BeliefRevised · MeaningTranslated
                                         ▼
                       ┌───────────────────────────────────┐
                       │  VALIDATED · QUESTIONABLE ·        │
                       │  CONFLICTED · UNKNOWN ·            │
                       │  ABSENT · FALSE · REJECTED         │
                       └───────────────┬───────────────────┘
                                       │  ContradictionDetected / Resolved
                                       │  KnowledgeSuperseded
                                       ▼
                          every prior state → History
                          (forward-only; nothing overwritten)
```

- **UNKNOWN is the initial state**, not a failure state — the operational form of epistemic honesty (§14).
- **The seven states are distinct and none is a degree of another.** *unknown* (no grounds) ≠ *absent* (grounds that it does not exist) ≠ *false* (grounds that it is not so).
- **Transitions are named, governed and forward-only.** There is no implicit transition and no in-place edit; a revision creates a new state and retains the prior one.
- **Rejection is preserved, never discarded** — a failed candidate remains as evidence of the failure (INV-KOS-FAILURE-001).
- **Contradiction is a state, not an error.** Conflicting knowledge coexists until governed resolution; the weaker side is never deleted.
- *Note on vocabulary: the informal complement of UNKNOWN — "known" — is rendered by **VALIDATED**. No eighth state is introduced.*

---

## 10 · Expression → Meaning Boundary

The four levels, their owners, and the invariant that forbids each collapse.

```
EXPRESSION            many surface forms · any language · any word order
   │                  owner: Expression context (mechanisms, external)
   │  ── varies freely; carries no epistemic weight ──
   ▼
MEANING CANDIDATE     a PROPOSAL about what an expression means,
   │                  + its justification path, + its declared insufficiency
   │                  owner: the mechanism that produced it — OUTSIDE the boundary
   │  ══ Expression↔Meaning Port · ACL (Article 1.2) ══
   ▼
ADMITTED MEANING      the intensional content the core holds  (member: Meaning)
   │                  owner: KnowledgeCore
   │  ── identity is ASSIGNED here, never derived (Articles 1.1/1.3) ──
   ▼
KNOWLEDGE IDENTITY    KnowledgeId — stable across representation, expression,
   │                  context and projection change
   │  ══ Verification Port (Article 6) — the only admission path ══
   ▼
EPISTEMIC STATE       determined at the aggregate boundary, by the domain alone
```

| Question | Answer |
|---|---|
| Can many expressions refer to one meaning? | **Yes** — `MeaningTranslated` exists precisely for this, with **KnowledgeId unchanged** |
| Can meaning exist independently of a sentence? | **Yes** — Meaning is an intensional value object, not a string |
| What makes two meanings the same knowledge object? | **An assignment, never a computation** — not similarity, not observable match, not coextension, **not canonical-form equality** |
| What makes an identity-bearing meaning part of KnowledgeOS? | passage through the **Verification Port** with a preserved justification path |
| What determines the epistemic state? | the **aggregate boundary** — constitutional admissibility of a state transition |

**⟨A-2⟩ Named forward deliverable — the Expression↔Meaning Port Contract.** This architecture states what a mechanism may **not do**; it does not state what a mechanism must **declare** when it crosses the port. Closing that is the **first deliverable of the Logical Architecture stage** — **named here, not authored here, and not open** (§20). Its obligations are **renderings of existing invariants, never new law**:

| # | Obligation on any mechanism crossing the port | Invariant rendered |
|---|---|---|
| 1 | submits a **meaning candidate** only — never an epistemic state, never a verdict | INV-KOS-VERIFICATION-001 · Article 6.3 |
| 2 | carries its **justification path** — premises · rules · assumptions · inference rule; no black box | INV-KOS-VERIFICATION-001 (6.4) |
| 3 | **declares its own insufficiency** — abstention is a first-class output; *"I did not determine this"* is a valid answer | INV-KOS-UNKNOWN-001 |
| 4 | **never proposes or derives a KnowledgeId** — canonical-form equality is not an identity claim | INV-KOS-IDENTITY-001 |
| 5 | **never emits a scalar in place of epistemic structure** | INV-KOS-DIMENSION-001 · Article 2.3 |
| 6 | mechanism **failure or silence maps to UNKNOWN** — never ABSENT, never FALSE | INV-KOS-UNKNOWN-001 |

**Why obligation 3 is load-bearing.** A mechanism that cannot abstain will assert. Post-review evidence measured a mechanism asserting understanding where grounds were insufficient in roughly **30% of a small case set** — the empirical shape of *insufficient grounds converted into an assertion*. The architectural answer is not a better parser; it is a port that will not accept a candidate lacking a declared insufficiency.

**⟨r4⟩ Candidate payload vocabulary.** The candidate's accompanying metadata — **interpretation probabilities · uncertainty · provenance · transformation evidence** — is **port-contract vocabulary**, defined by the Expression↔Meaning Port Contract (Logical Architecture, authored under the 2026-08-22 HPA steering), **never aggregate members**. No member is added; the seven states, the Confidence boundary rule ⟨R-1⟩, and the Verification Port admission path are unchanged.

**The separation to keep visible:**

```
Semantic mechanism:   "This expression probably means X."
KnowledgeOS:          "X is UNKNOWN / QUESTIONABLE / VALIDATED / CONFLICTED / …"
```

A mechanism's output is **input to** the epistemic process, never its conclusion.

---

## 11 · Semantic Compiler placement

> **Ruling (unchanged): NOT promoted. A candidate adapter at the Expression↔Meaning port, for the Logical Architecture stage.**

| Option | Verdict | Why |
|---|---|---|
| **Core** | ❌ | the core is epistemic states; meaning *translation* is a mechanism serving identity preservation, not the domain. Making it core would make KnowledgeOS a **language engine** — a §17 rejected concept |
| **Supporting domain** | ❌ | a supporting domain owns state and aggregates; the compiler owns none — it is a transformation |
| **Generic / infrastructure** | ⚠️ not now | a generic mechanism it may become; the evidence is not at that altitude |
| **Future / candidate adapter** | ✅ **placed here** | recorded, not promoted. **Do not promote without evidence** |

### 11.1 The Replacement Test — applied explicitly

```
                              KNOWLEDGEOS
                                   ▲
                                   │  Meaning Candidate
          ┌────────────┬───────────┼───────────┬────────────┐
          │            │           │           │            │
    Pāṇinian-     dependency   symbolic    LLM semantic   human
    inspired        parser      parser       parser     interpretation
    compiler
```

Any of these can hand a meaning candidate to the same boundary. **Therefore none of them owns the Core Domain.** The mechanisms are replaceable; the boundary is not.

### 11.2 ⟨A-1⟩ Falsification results

| Test | Question | Result |
|---|---|---|
| **A** | remove **semantic invariance** — still KnowledgeOS? | **Yes** — intake becomes poorer; identity, justification and lifecycle are untouched → **not core** |
| **B** | remove **semantic compilation** — still KnowledgeOS? | **Yes** — human interpretation can feed the Verification Port → **not core** |
| **C** | remove **identity** | **No** — the aggregate becomes a **claim-store** (Ch IV refusal) → **core** |
| **D** | remove the **epistemic lifecycle** | **No** — a **snapshot-store** (Ch IV refusal) → **core** |
| **E** | remove the **LLM** | **Yes** — §13 → **external** |
| **F** | remove **natural language itself** | **Yes** — knowledge arrives as structured submissions; the core is unharmed and only *reach* is lost. **Therefore no mechanism whose reason for existing is natural language can be core.** This is the decisive placement argument |

### 11.3 ⟨A-3⟩ Naming guard

**Benchmark names are not architectural components.** Names arising from measurement work — *KOS-SCB* (semantic compilation) and *KOS-EV* (epistemic validation) — denote **experiments**, not elements of this architecture. The enforcement locus in the architecture is the **aggregate boundary**; a benchmark measures a mechanism against it. An observation must never become architecture by repetition.

---

## 12 · Semantic Normal Form placement

| Candidate classification | Verdict | Reason |
|---|---|---|
| **Representation** | ✅ **primary** | a canonical form of an expression — the third altitude (§16): a projection, freely changeable |
| **Mechanism** | ✅ **secondary** | the *normalization* that produces it is a transformation at the mechanism altitude |
| Domain concept | ❌ | the core already owns **Meaning**; SNF adds no domain responsibility, it encodes one |
| Bounded context | ❌ | fails all five context tests (§5.1) |
| Port-contract encoding | ⚠️ **candidate** | it *may* become the form in which candidates cross the port — a **Logical Architecture** decision, not decided here |
| Identity authority | ⛔ **explicitly not** | see below |

### 12.1 ⟨C-1⟩ The non-admission note

> **A canonical form is still a representation. Canonical-form equality is therefore a similarity claim in formal dress — and similarity never becomes identity.**

```
E1  "The architect approved the design because the security evidence was sufficient."
E2  "The design received approval from the architect after security-evidence validation."
E3  "Der Architekt genehmigte den Entwurf."
                        │ normalization
                        ▼
              SNF(E1) = SNF(E2) = SNF(E3)?      ← a claim about REPRESENTATION
                        │
      ⛔ "therefore the same knowledge object"   ← FORBIDDEN (INV-KOS-IDENTITY-001)
      ✅ "therefore a CANDIDATE for the same admitted meaning, offered to an
          authorized identity-assignment act, which may accept or refuse it"
```

**Why this note exists although the rule already existed.** The prohibition is old (Article 1.2 and 1.3); the *route into it* is new and attractive, because a canonical form **feels** like meaning in a way an embedding does not. The failure mode guarded against is precise: **replacing embedding similarity with "formal" canonical-form similarity and then treating the latter as identity.** Canonicalization must never become an identity shortcut.

**The two-sided property.** Invariance alone is insufficient. A normalizer that maps *approved* and *acknowledged* — or *sees* and *believes he sees* — to one form has not preserved meaning; it has destroyed a distinction. **Semantic invariance without semantic non-collapse is a lossy canonicalization.** This is why obligation 4 of the port contract is a *prohibition on identity claims* rather than a quality bar on the normalizer: the architecture does not require the normalizer to be perfect, because it can never assign identity.

---

## 13 · LLM boundary

KnowledgeOS is **NOT** an LLM replacement · **NOT** an LLM wrapper · **NOT** a truth oracle · **NOT** an autonomous intelligence · **NOT** a knowledge database.

| | Role | Owns |
|---|---|---|
| **LLM = Language Cortex** | expression generation — explanation · ambiguity resolution · conversation | **does not own truth**; generates candidates and expression |
| **Semantic mechanism** | expression↔meaning translation | **does not own identity**; translates, never determines identity (Article 1.2) |
| **KnowledgeOS** | epistemic state management — identity · evidence · justification · authority · uncertainty · history · contradiction | **owns the justified epistemic state** |

The LLM sits at the **Expression port** as an external adapter; its output enters the core only through the **Verification Port**, as a candidate with a captured justification path (Article 6.3). *The LLM speaks; the domain knows.*

**The LLM must never become:** the Knowledge Identity authority · an evidence authority · a truth oracle · the epistemic-state authority · a contradiction resolver on its own.

**Replacement test (E):** remove the LLM and the Core Domain still exists → **the LLM is external.** The two structural absences of a stateless generator — persistent epistemic identity and historical belief evolution — are matters of **placement**: identity and history live in the core.

---

## 14 · Zero placement

> **Zero is a meta-principle, not a domain object.** Not an aggregate · not an entity · not a service · not a repository · not a database object · **not an epistemic status**.

It sits **above** the architecture as the epistemic-honesty posture — *"I do not yet know"* is a valid first state — and is realized *through* existing structure: the aggregate's initial state is **UNKNOWN** (INV-KOS-UNKNOWN-001) and the negative-state discipline keeps *unknown ≠ absent ≠ false*. Placing Zero in the architecture as an element would be a category error: the principle of the system is not a part of the system.

**⟨C-5⟩ Operational reading (interpretation of existing discipline — not a new rule, and not permission for a "Zero component"):**

```
insufficient grounds  →  do not invent meaning  →  UNKNOWN
```

Extended to the boundary: **a mechanism's inability to determine meaning is a first-class output that maps to UNKNOWN** — never to ABSENT, never to FALSE, and never to a low-confidence acceptance. A mechanism that cannot say *"I did not determine this"* will assert instead; Zero, operationally, is the system's refusal to reward that.

---

## 15 · Constitutional Invariant Mapping

**The eleven aggregate invariants are renders of the eleven frozen articles.** No article added, weakened, or reinterpreted; no register row added or removed (**25+4 unchanged**).

| Article | Invariant | Register rows (evidence) |
|---|---|---|
| 1 | INV-KOS-IDENTITY-001 | INV-KOS-002 · H-KOS-Context-001 · H-KOS-Context-002 · H-KOS-Relationship-001 · H-KOS-Relation-001 |
| 2 | INV-KOS-DIMENSION-001 | INV-KOS-001 · H-KOS-NonInterference-001 |
| 3 | INV-KOS-AUTHORITY-001 | INV-001 · INV-003 · INV-KOS-Pramana-001 |
| 4 | INV-KOS-DECISION-001 | INV-002 · INV-KOS-Decision-001 |
| 5 | INV-KOS-PROJECTION-001 | INV-004 |
| 6 | INV-KOS-VERIFICATION-001 | INV-KOS-Inference-001 · INV-KOS-Justification-001 · H-KOS-Reasoning-Separation-001 · H-KOS-Reasoning-Provenance-001 · H-KOS-Vyapti-001 · H-KOS-EvidenceAuthenticity-001 |
| 7 | INV-KOS-FAILURE-001 | H-KOS-Fallacy-001 · H-KOS-Failure-001 |
| 8 | INV-KOS-CONTRADICTION-001 | H-KOS-Contradiction-001 · H-KOS-Dialogue-001 |
| 9 | INV-KOS-UNKNOWN-001 | H-ZERO-001 · H-KOS-Uncertainty-001 |
| 10 | INV-KOS-AGENCY-001 | INV-KOS-Agent-001 · H-KOS-Relationship-001 (knower-side) |
| 11 | INV-KOS-HISTORY-001 | INV-KOS-Revisability-001 · Temporal family |

**The non-collapse distinctions, traced — no new invariant is created for any of them:**

| Distinction | Where it already lives |
|---|---|
| Representation ≠ Identity · Expression ≠ Meaning · Similarity ≠ Identity | INV-KOS-IDENTITY-001 (Articles 1.2, 1.3) |
| Meaning ≠ Truth | INV-KOS-VERIFICATION-001 + EpistemicState (Articles 6, 9) |
| Evidence ≠ Authority | INV-KOS-AUTHORITY-001 (Article 3.1) |
| Revision ≠ Erasure | INV-KOS-HISTORY-001 (Article 11.1) |
| Inference ≠ Observation | INV-KOS-VERIFICATION-001 (Article 6) |
| Agent ≠ Truth | INV-KOS-AGENCY-001 (Article 10) + §13 |
| **Probability ≠ Truth** ⟨r4⟩ | INV-KOS-VERIFICATION-001 (verdict vocabulary, Articles 6, 9) + INV-KOS-DIMENSION-001 (Article 2.3) — an interpretation probability is candidate-side metadata, **never a truth verdict** |
| **Canonicalization ≠ Authority** ⟨r4⟩ | INV-KOS-AUTHORITY-001 (Article 3) — normalization confers **no authority** on the claim an expression carries |
| **Low entropy ≠ Certainty** ⟨r4⟩ | INV-KOS-DIMENSION-001 + INV-KOS-UNKNOWN-001 (Articles 2, 9) — a highly-normalized / low-entropy form is **not thereby more certain** |

**⟨r4⟩** The three measurement-side rows above are added under the 2026-08-22 HPA steering — **interpretations of existing invariants, no new invariant** (Probability→Truth · Canonicalization→Authority · Low entropy→Certainty). The other rows are unchanged.

**r3's effect on the invariants:** four are **strengthened** — IDENTITY-001 ⟨C-1⟩ · DIMENSION-001 ⟨R-1⟩ · UNKNOWN-001 ⟨C-5⟩ · VERIFICATION-001 ⟨A-2, via the port contract's obligations⟩ — because the post-review evidence supplied concrete failure modes for prohibitions that already existed. The other seven are unaffected. **None is added; none is weakened; no wording of an article changes.**

---

## 16 · Kernel / Mechanism / Representation separation

**Nothing moves between altitudes except by constitutional amendment.**

| Altitude | What it is | Members | May it change? |
|---|---|---|---|
| **KERNEL — must exist** | what KnowledgeOS must protect: the constitutional invariants and the boundary that enforces them | the eleven invariants · KnowledgeAggregate · ConflictRecord · the three small supporting aggregates | **No** — amendment by the HPA only; no article weakened |
| **MECHANISM — may change** | how responsibilities may be carried out | validation · reasoning · contradiction/debate · fallacy detection · history/revision · intent classification · **LLM** · **Semantic Compiler (candidate)** · **normalizers** | **Yes** — free evolution, subject only to *no evolution violates an article* |
| **REPRESENTATION — projection** | how information is encoded or projected | evidence records · context tuples · epistemic state model · absence taxonomy · verdict vocabulary · typed epistemic graph · derived views · **canonical forms (SNF)** | **Yes** — each is a projection, regenerable and non-authoritative |

**Placement summary:** Knowledge Identity → **Core Domain** · KnowledgeAggregate → **core consistency boundary** · Semantic Compiler → **mechanism** · SNF → **representation / mechanism** · LLM → **external mechanism / adapter** · Zero → **meta-principle**.

**The kernel does not reason.** The kernel altitude contains no reasoner. The aggregate enforces invariants; engines at its ports do the reasoning. A kernel member can refuse a transition, record a state, assign an identity, retain a history — it **cannot generate a conclusion**. In DDD terms: *the aggregate has no dependency on any engine*; every engine is an adapter at a port, replaceable without touching the core.

---

## 17 · Rejected / External Concepts — "Not KnowledgeOS"

| Concept | Why rejected | Article it would collapse |
|---|---|---|
| **Language engine** | knowledge is not expression; expression must never become meaning | 1.2 |
| **Database** | stores facts → knowledge is a verified transition, not stored information | 6 |
| **Chatbot** | conversation is generation, not epistemic state management | 6, 11 |
| **LLM wrapper** | generation is never justification; a generator lacks identity and history | 1, 6, 11 |
| **Ontology repository** | categories and relations are representations, not justified belief | 6 (and 1) |
| **Truth machine / oracle** | the system preserves the conditions of discovery; it does not answer with authority | 3, 9 |
| **Wisdom Formation as a core domain** | not a constitutional concept; adopting it would extend the frozen Constitution | V.3 |
| **Similarity or canonicalization as an identity authority** ⟨C-1⟩ | representation equality is not identity | 1.2, 1.3 |
| **Interpretation probability as a truth verdict** ⟨r4⟩ | a scalar never decides truth; a candidate-side probability is interpretation uncertainty, never a verdict | 6, 9 (and 2.3) |
| **Canonicalization as an authority grant** ⟨r4⟩ | normalization is not authorization; no mechanism confers authority by compressing an expression | 3 |
| **Low entropy / canonical-form compression as certainty** ⟨r4⟩ | representation compression is not epistemic certainty | 2, 9 |

**External by placement, not by rejection:** the LLM · semantic compilers and parsers · normalizers · reasoning and validation engines · evidence source systems · the human acts that assign authority and make decisions. These are *necessary collaborators the core does not own*.

**The rejected set is why the core is small.** Every entry is a *simpler system* KnowledgeOS must refuse to become; the discipline of one aggregate, eleven invariants and every engine at a port is what makes the refusals structural rather than aspirational.

---

## 18 · Domain Dependencies

**Dependency direction is the architecture's sovereignty statement.**

```
        Authority ──assigns──▶ ┌───────────────┐ ──read-only──▶ Projection
        (human act)            │ KNOWLEDGE CORE │ ──advisory───▶ Decision Boundary
                               │  depends on    │
   Expression mechanisms ─────▶│   NOTHING      │
   Reasoning mechanisms  ─────▶└───────────────┘
   (candidates, via ports)
```

| Rule | Statement |
|---|---|
| **D-1** | **The core depends on nothing.** No context, mechanism, representation or technology is required for the core's model to be well-formed |
| **D-2** | **Mechanisms depend on the core's published language** (verdict vocabulary · justification-path contract), never the reverse. The core names what may cross; mechanisms comply |
| **D-3** | **Supporting contexts serve the core** and hold what the core must not: Authority holds the grant, Projection holds derived views, Decision Boundary holds the record of advice |
| **D-4** | **Every inbound arrow is a port with an anti-corruption layer.** Nothing enters by shared model or shared storage |
| **D-5** | **Every outbound arrow is advisory or projection.** Nothing leaves as authority, and nothing written outside returns as knowledge |
| **D-6** | **Replaceability is a property, not a hope:** any mechanism may be swapped without touching the core (§11.1) |

---

## 19 · Architecture Quality Gates

| Gate | Question | Result |
|---|---|---|
| **Identity** | if every technology, database, engine and interface were replaced, is it still KnowledgeOS? | ✅ **Yes** — the core is implementation-free |
| **Replacement** | if the Semantic Compiler is replaced, is it still KnowledgeOS? | ✅ **Yes** — §11.1; any mechanism may feed the same boundary |
| **LLM** | if the LLM disappears, is it still KnowledgeOS? | ✅ **Yes** — §13, Test E |
| **Representation** | if SNF changes, is it still KnowledgeOS? | ✅ **Yes** — SNF is a representation at the freely-changeable altitude (§16) |
| **Aggregate** | does the KnowledgeAggregate remain the authoritative boundary for constitutional admissibility of state transitions? | ✅ **Yes** — §6, §7; the realization stays deferred |
| **Non-collapse** | can any representation, similarity score, parser confidence, authority signal or LLM output **directly become Knowledge Identity**? | ✅ **NO** — canonical form ⟨C-1⟩ · score ⟨R-1⟩ · authority (assigned, referenced) · LLM output (candidate only); and **⟨r4⟩ none of them becomes truth, authority, or certainty either** (probability ≠ truth · canonicalization ≠ authority · low entropy ≠ certainty). Identity is **assigned**, never computed |
| **Reduction** | did the architecture become smaller, clearer, more enforceable? | ✅ **Yes** — r3 adds **no** context, aggregate, member, event or invariant; it adds boundary notes that make four existing invariants enforceable at the port. Element count of *what must exist* is unchanged from r2 (one core domain · one primary aggregate · one ConflictRecord · three small supporting aggregates) |
| **Failure** | if the core principle disappears, does KnowledgeOS stop being KnowledgeOS? | ✅ **Yes** — remove identity-of-meaning → claim-store; remove the lifecycle → snapshot-store; both Ch IV refusals |
| **Constitution** | is the Constitution satisfied and never extended? | ✅ **Yes** — eleven invariants render eleven articles; no article added, weakened or reinterpreted; register **25+4 unchanged** |
| **No new architecture** | did the consolidation introduce anything outside the r3 change set? | ✅ **No** — every change traces to C-1…C-5 · R-1 · A-1…A-3 (Appendix A); everything else is an observation (Appendix B) |

---

## 20 · Explicit Deferred Decisions

**Nothing below is decided by this artifact. Each requires a separate authorized act.**

| # | Deferred | Altitude / owner |
|---|---|---|
| **DEF-1** | **How the aggregate boundary is realized** — commands · methods · domain services · policy evaluation, or another form | Logical / Implementation Architecture |
| **DEF-2** | **The Expression↔Meaning Port Contract** — named ⟨A-2⟩; **OPENED by the 2026-08-22 HPA steering (r4)** and authored at `docs/knowledgeos/architecture/20260822-1559-KOS-Expression-Meaning-Port-Contract.md` | Logical Architecture — **first deliverable, now authored** |
| **DEF-3** | **Semantic Compiler promotion** — remains a candidate adapter; promotion requires evidence and an HPA act | HPA |
| **DEF-4** | **SNF as the port encoding** — may become the form candidates take; undecided | Logical Architecture |
| **DEF-5** | Storage · schemas · APIs · classes · frameworks · message formats · model selection | Implementation Architecture / Systems |
| **OQ-1** | Does a mechanism's *declared insufficiency* need its own vocabulary at the port, or does the existing verdict vocabulary cover it? | Logical Architecture |
| **OQ-2** | **May SNF-equivalence be recorded as an EvidenceLink supporting an identity-assignment act, without itself becoming an identity mechanism?** **OPEN — deliberately unresolved here.** It is invariant-adjacent (INV-KOS-IDENTITY-001); the boundary is recorded, the answer is not invented. The Port Contract takes a **position at contract altitude** (collisions are evidence, never admission — its §Q8); the **ruling** on that position remains the HPA's | Expression↔Meaning Port Contract (delivered) — **HPA rule still required** |
| **OQ-3** | Is cross-language sameness (e.g. EN/DE) a claim about meaning or about translation? | Logical Architecture / experiment design |
| **OQ-4** | **The KOS-SCB v0.2 experiment — NOT AUTHORIZED.** Not to be run, commissioned, or corpus-built here; no accuracy claim, no performance projection. When authorized, its corpus must contain positive equivalence cases, **negative / non-collapse cases**, ambiguity, context variation, temporal variation, contradiction, UNKNOWN, and cross-expression transformations — otherwise it could merely demonstrate a lossy canonicalization function | **HPA act required** |
| **OQ-5** | Should Confidence remain an aggregate member at all, or become a derived read-side attribute? ⟨R-1⟩ makes it safe; it does not make it necessary | Logical Architecture |

---

## Appendix A · r2 → r3 change ledger (traceability)

**Every change traces to the frozen change set. Nothing else changed.**

| Ref | Class | Change | Applied at |
|---|---|---|---|
| **C-1** | CLARIFICATION | Canonical form is a representation; canonical-form / SNF equality is a **candidate** for the same admitted meaning, never an identity determination | §12.1 (note) · §6 (KnowledgeId) · §7 (INV-KOS-IDENTITY-001) · §17 · §19 (Non-collapse) |
| **C-2** | CLARIFICATION | **Meaning** = *admitted* intensional content; a mechanism's output is a candidate outside the boundary | §6 (Meaning) · §10 |
| **C-3** | CLARIFICATION | **EvidenceLinks** = references + acquisition method + reliability conditions; never evidence content (guards the Ch IV database refusal) | §6 (EvidenceLinks) |
| **C-4** | CLARIFICATION | **Relations** by KnowledgeId reference only, never containment | §6 (Relations) |
| **C-5** | CLARIFICATION | Zero's operational reading extended: a mechanism's inability to determine meaning maps to **UNKNOWN**, never ABSENT / FALSE / low-confidence accept | §14 · §7 (INV-KOS-UNKNOWN-001) · §9 |
| **R-1** | REFINEMENT | **Confidence** boundary rule: structured, assigned **inside** the boundary; no mechanism-supplied score crosses the port as confidence | §6 (Confidence) · §7 (INV-KOS-DIMENSION-001) · §19 |
| **A-1** | ADDITION (record) | Falsification results **A · B · F** recorded beside the existing C · D · E; **Test F is the decisive placement argument** | §11.2 · §2 |
| **A-2** | ADDITION (forward pointer) | **Expression↔Meaning Port Contract** named as the first Logical-Architecture deliverable, with six obligations each traced to an existing invariant | §10 · §20 (DEF-2) |
| **A-3** | GUARD | Benchmark names (*KOS-SCB* · *KOS-EV*) are **not** architectural components; **no domain event** for normalization or other mechanism steps | §11.3 · §8 (event admission guard) |

**Structural inventory, r2 → r3:** bounded contexts **6 → 6** · core domains **1 → 1** · aggregates **5 → 5** (KnowledgeAggregate · ConflictRecord · AuthorityGrant · DerivedView · DecisionRecord) · aggregate members **12 → 12** · domain events **10 → 10** · invariants **11 → 11** · constitutional articles **11 → 11** · register **25+4 → 25+4**. **Net structural change: none.** The document was reorganized into the twenty commissioned sections; §3, §9, §18 and §20 are new *sections* presenting already-accepted content (principles, lifecycle, dependency direction, deferrals), not new architecture.

**r4 change ledger (architectural-direction annotations — HPA steering 2026-08-22, `docs/knowledgeos/reviews/20260822-1559-…-r4-and-Port-Contract-HPA-steering.md`).** Every change traces to the steering act; nothing else changed.

| Ref | Class | Change | Applied at |
|---|---|---|---|
| **r4-1** | ADDITION (interpretation) | **Probability ≠ Truth** added to the non-collapse distinctions — an interpretation probability is candidate-side metadata, **never a truth verdict** | §15 · §17 · §19 |
| **r4-2** | ADDITION (interpretation) | **Canonicalization ≠ Authority** — normalization confers **no authority** | §15 · §17 · §19 |
| **r4-3** | ADDITION (interpretation) | **Low entropy ≠ Certainty** — compression is **not certainty** | §15 · §17 · §19 |
| **r4-4** | CLARIFICATION | Candidate **payload vocabulary** (interpretation probabilities · uncertainty · provenance · transformation evidence) is port-contract vocabulary, **never aggregate members** | §10 |
| **r4-5** | STATUS | **Expression↔Meaning Port Contract opened** (DEF-2) and authored under the steering act; OQ-2 position recorded at contract altitude | §20 · §1 |

**Structural inventory, r3 → r4:** bounded contexts **6 → 6** · core domains **1 → 1** · aggregates **5 → 5** · aggregate members **12 → 12** · domain events **10 → 10** · invariants **11 → 11** · constitutional articles **11 → 11** · register **25+4 → 25+4**. **Net structural change: none.** The r4 annotations are **interpretations of existing invariants**, not new law.

## Appendix B · Observations recorded, NOT incorporated

Per the commission: *"If you discover something that is not covered by the frozen change set: STOP and record it as an observation. Do not silently incorporate it."*

| # | Observation | Disposition |
|---|---|---|
| **OBS-1** | The commissioning brief's **illustrative** event list names three events absent from the accepted set — *MeaningAdmitted · JustificationEstablished · EpistemicStateChanged*. The r3 change set contains **no event change**, and ⟨A-3⟩ is a guard *against* adding events. The accepted **ten** events are retained unchanged. *(The named concepts are already covered: admission is `KnowledgeCreated` at the Verification Port, justification is a precondition of that admission rather than a separate transition, and state change is carried by `BeliefRevised` / `ContradictionDetected` / `KnowledgeSuperseded` / `KnowledgeRejected`.)* | **NOT incorporated.** An event change would need its own HPA act |
| **OBS-2** | The first HPA review's prose listed **KNOWN** alongside the state vocabulary; the accepted member set has **seven** states without it. Retained as seven; *known* is rendered by **VALIDATED** (noted inline at §9) | **NOT incorporated** — no eighth state |
| **OBS-3** | The brief lists *"contradiction state"* among what the aggregate owns. In the accepted model this is **EpistemicState (CONFLICTED)** inside the aggregate **plus** the separate **ConflictRecord** aggregate — because two KnowledgeAggregates cannot own each other. Recorded as a mapping, not a change | **No change** — the accepted structure already satisfies it |

---

## Traceability

- **Consolidation commission:** `docs/knowledgeos/reviews/20260822-1533-KOS-EP01-Reference-Architecture-v1.1-r3-Consolidation-commissioning-prompt.md` (HPA act — **r3 unfrozen · D-1 ratified**).
- **r4 steering (this revision):** HPA **architectural-direction steering** 2026-08-22 (`docs/knowledgeos/reviews/20260822-1559-…-r4-and-Port-Contract-HPA-steering.md`) — the six negative-direction prohibitions · Expression↔Meaning Port Contract **opened**; deliverable `docs/knowledgeos/architecture/20260822-1559-KOS-Expression-Meaning-Port-Contract.md` (first Logical-Architecture deliverable).
- **Review chain:** first HPA review `…20260822-1425-…-DDD-Refinement-HPA-Review.md` (**PASS CONDITIONALLY → CLOSED**, confirmation `…20260822-1527-…-FIRST-HPA-REVIEW-CLOSURE.md`) · second architectural review `…20260822-1459-…-Second-Architectural-Review-Semantic-Invariance.md` (**PASS · CLARIFICATION ONLY**, accepted `…20260822-1523-…-Second-Review-HPA-ACCEPTANCE.md`).
- **Prior instruments:** original commissioning prompt `…20260822-1402-…-DDD-Refinement-commissioning-prompt.md` · Constitution v1.0 `…20260822-0951-KOS-EP01-Constitution-v1.0.md` (**FROZEN**) · Reference Architecture v1.0 `…20260822-0955-KOS-EP01-Reference-Architecture-v1.0.md` · P4 Constitutional Invariant Map `…20260822-0915-…` · Kernel Decision `…20260822-0939-…`.
- **Evidence informing r3 (post-review):** the false-acceptance finding · the semantic-compilation / epistemic-validation measurement split · the semantic non-collapse dimension · the boundary refinement — recorded in `docs/knowledgeos/brainstorming/20260822-14{1513,3217,3421,3844}-*.md`. **Accuracy projections in that material are explicitly excluded as evidence** (a projection, refuted within its own composite source, and a forbidden action of this commission).
- **Revision history:** **r1** produced (DDD refinement of v1.0) → **r2** first-review condition applied (aggregate-boundary altitude) → **r3 CONSOLIDATED** (the r3 change set applied, document reorganized into the twenty commissioned sections) → **r4** architectural-direction annotations (the six negative-direction prohibitions · Port Contract opened, 2026-08-22).
- **Discipline honored:** apply only what was accepted · reopen no HPA decision · reinterpret nothing · invent no change · every r2→r3 delta traceable (Appendix A) · anything uncovered recorded as an observation (Appendix B) · Constitution satisfied never extended · no new philosophy, dimension, article, context, aggregate, member, event or invariant · no database, API, class, framework, model or technology decision · no promotion · the strongest statement never exceeds the evidence.
- **Status:** ⭐ **KnowledgeOS Reference Architecture v1.1 — CONSOLIDATED (r3) · ⟨r4 annotations⟩ · PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE.** Register **25+4 unchanged** · Constitution **FROZEN** · research **CLOSED** · P4 gate **unchanged** · Semantic Compiler **NOT promoted** · SNF research **paused** · Logical Architecture **open for the Expression↔Meaning Port Contract only** · Port Contract **authored** (first Logical-Architecture deliverable, under the 2026-08-22 HPA steering) · KOS-SNF-ME v0.4 **gated, not authorized** · **OQ-2 position recorded at contract altitude, HPA rule still required** · **OQ-4 unauthorized**. **Not v1.2. Not a Semantic Compiler Architecture.**
