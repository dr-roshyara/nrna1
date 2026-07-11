# Project Knowledge — Strategic Model (Bounded Context · Ubiquitous Language · Domain Model · Context Assembly · Platform Relationship)

**Kind:** strategic DDD modeling — conceptual only. NOT design: no folders, templates, software, classes, repositories, or implementation. **Status:** COMPLETE — presented for ARB review. **STOP before any design.**
**Foundation:** the General Knowledge Constitution (Knowledge Space, 10 invariants, 10 rules, lifecycle, flow algebra) — this bounded context is a **specialization**: weights, never new rules. Evidence base closed (RQ-002 chain + charter + Engineering Platform reference architecture).
**Mission (the domain's one sentence):** *How does project knowledge become available to an engineer or AI exactly when needed, with the minimum sufficient context, while remaining canonical, traceable, and evolvable?*

---

## 1. Bounded Context Definition

| Question | Answer |
|---|---|
| **Purpose** | The mission sentence above. This context is about *understanding the project* — documentation is merely one representation within it. |
| **Owns** | The project's ubiquitous language and its maintenance · the product's context boundaries as knowledge · decision knowledge (ADR/IDD-class content) · business rules as knowledge (their encoding is shared with Product) · domain models · implementation knowledge (the "how it's built" claims) · project memory (the historical region) · context assembly · onboarding paths · knowledge evolution. |
| **Observes** | Engineering Platform outputs (qualification records, gate results, retrospectives — consumed as evidence claims) · Runtime observations (session logs — mined, never owned) · Product implementation (read-only; the code is the highest-authority knowledge carrier, owned by Product). |
| **Never touches** | Engineering execution (EEP, gates, qualification machinery) · Product code (read-only always) · Tacit knowledge (succession mechanics only — constitutional theorem) · Runtime session state. |
| **Relation to Engineering Platform** | **Sibling bounded context under the same constitution** — not parent, not child, not part of Engineering (§5). |
| **Relation to Runtime** | Consumes runtime observations as memory input; supplies assembled context *to* the runtime's session-start gesture; owns neither. |

## 2. Ubiquitous Language (candidates validated or falsified)

| Term | Verdict | Definition (as validated) |
|---|---|---|
| **Project Knowledge** | ✅ | The specialization of the Knowledge Space populated by claims about *this project's meaning and construction*. |
| **Business Concept** | ✅ | A named thing in the business domain (Election, Challenge, Determination) — the anchor to which definitions, rules, and decisions attach. |
| **Context** (product bounded context) | ✅ | The scope within which a concept's definition holds (constitutional: Definition nature is context-bound; term drift signals a boundary). |
| **Definition** | ✅ | A claim fixing a concept's meaning within one context — Definition×(Conversational+Code) primary; glossary prose is derivative, never authoritative (evidence: living-glossary findings). |
| **Decision** | ✅ | A recorded choice with rationale, alternatives, consequences — immutable, superseded by link; canonical only while owned and bound. |
| **Rule** | ✅ | A constraint governing behavior — Constraint nature; authority follows representation (encoded > prose, PK-P4). |
| **Scenario** | ⚠ VALIDATED WITH CAUTION | A concrete event/interaction sequence. Evidence warning: prose and workshop scenarios die by design (EventStorming boards, Domain Stories); executable scenarios mostly failed as business-facing artifacts (SBE's own 10-year survey). A scenario is durable knowledge only as an Executable representation maintained by its consuming population, else it is conversation scaffolding — valuable and disposable. |
| **Model** (domain model) | ✅ as composite | A structured bundle of Definition + Description claims about a domain's structure and behavior; governed at claim granularity (a model is "fresh" only claim-by-claim). |
| **Implementation Knowledge** | ✅ | Claims linking concepts to their construction ("Challenge is an aggregate in Contestation; its outbox is…") — Description nature, code-proximate representations survive. |
| **Project Memory** | ✅ REFRAMED | Not a store: **the append-only historical region of the space** (superseded decisions, session observations, qualification history), reached by mining existing records (git, logs, registers) — never a new curated repository (invariant 8). |
| **Context Assembly** | ✅ FIRST-CLASS | The domain's central act: composing the minimal sufficient Working Context for a task (§4). |
| **Knowledge Retrieval** | ❌ FALSIFIED as peer term — DEMOTED | Retrieval is a *mechanism inside* assembly (the coordinate-matching step). Keeping it as a peer term reintroduces document-centric thinking ("find documents") that the evidence rejects ("assemble understanding"). |
| **Knowledge Evolution** | ✅ | Constitutional lifecycle applied to this specialization (regime = f(nature)). |
| **Working Context** *(added — the assembly output needed a name)* | ✅ NEW, evidence-forced | The ephemeral, task-scoped set of claims produced by Context Assembly, with statuses visible and contradictions surfaced. |
| **Knowledge Claim · Coordinate · Canonical Set** | ✅ inherited | From the constitution, used unchanged. |

## 3. Domain Model (conceptual — entities, value objects, aggregates, services; NO classes)

**Value objects:** **KnowledgeCoordinate** (Nature × Representation × Status × Scope — identity by value; exists independently of artifacts; ✅ but as VO, not entity — corrected from the candidate list) · **Provenance** (producer, time, context) · **FreshnessStamp** (owner, last-verified, domain half-life) · **Scent** (label, summary, address) · **QueryPredicate** (a coordinate region + concept anchors; ✅ KnowledgeQuery corrected to VO).

**Aggregates (conceptual):**
- **KnowledgeClaim** — root of the specialization. The atomic unit (constitutional): content + Coordinate + Provenance + Status + Scent. Invariants 2, 3, 7 enforced here. Composite artifacts (a model, a guide) are *bundles referencing claims*, not super-aggregates.
- **DecisionRecord** — an immutable claim-bundle of Decision nature with supersession links; canonical ⟺ accepted ∧ unsuperseded ∧ owned ∧ bound (evidence: the Watson-Discovery binding condition).
- **UbiquitousTerm** — a context-bound Definition claim whose validation is conversational+code; carries its owning context; drift is a boundary event, not an edit.
- **WorkingContext** — **ephemeral** aggregate: the assembly result. Invariants: minimal sufficiency (PK-P6) · every member's status visible (inv. 2) · contradictions surfaced as ranked coexistence, never silently resolved (inv. 4) · **discarded at task end** — persistence would create a curated second population; ephemerality is how the aggregate satisfies invariant 8 *by construction*.
- **ProjectMemory** — ❌ NOT an aggregate (corrected from the candidate list): it is the historical *region* of the space, accessed by mining; modeling it as an aggregate would invite building the store the evidence forbids. **KnowledgeFlow** — likewise not an aggregate: a flow is an observed path (recoverable from provenance links), not a managed thing. **KnowledgeSpecialization** — a descriptive concept, not a domain object.

**Domain services (conceptual):** **ContextAssembly** (Task Need → Working Context; §4) · **IntakeQualification** (born-stale gate + claim evaluation at creation) · **Supersession** (link-demote-preserve; never edit) · **TransitionService** (deliberate representation transitions — the rationale mining path lives here).

**Domain events (conceptual):** ClaimRecorded · ClaimSuperseded (with reason) · ClaimDeprecated (with reason) · ContradictionDetected · ContextAssembled · TransitionPerformed (from-representation, to-representation, nature preserved).

## 4. Context Assembly Model (the first-class act)

```text
Task Need ("implement voting eligibility")
   ↓  concept extraction            — which Business Concepts and Contexts does the task touch?
   ↓  predicate formation           — coordinate region: canonical Definitions for those concepts ·
                                      governing Decisions (accepted∧unsuperseded, in scope) ·
                                      applicable Rules (executable representation preferred, PK-P4) ·
                                      relevant Implementation Knowledge · pertinent memory (prior
                                      reports/observations). Purpose acts HERE, as the selector.
   ↓  discovery                     — over LIVE sources by default (PK-AD8): agentic search + scent;
                                      indexes only where staleness is governed
   ↓  contradiction surfacing       — coexisting ranked claims presented as such (inv. 4);
                                      the assembler never silently picks
   ↓  WORKING CONTEXT               — minimal sufficient (PK-P6), statuses visible (inv. 2),
                                      provenance attached (inv. 3), EPHEMERAL
   ↓  validation by use             — the task's outcome is the context's qualification
                                      (consumption feedback closes the constitutional loop)
   ↓  feedback capture              — decisions made DURING the task are new Decision claims captured
                                      at their creation window (the tacit→recorded transition at
                                      near-zero gesture cost — mined from the session, not authored)
```

Trigger: any task (ticket, review, question, session start — extending the existing session-start gesture, per the same-population law). **The reflexive risk is answered structurally:** the Working Context is *ephemeral* — no persistence, no maintenance population, no second-population artifact. What persists are only the new claims the task itself produced, in their owners' existing homes.

## 5. Relationship to the Engineering Platform

```text
              General Knowledge Constitution        (shared kernel: space · invariants · rules · lifecycle)
                        │
          ┌─────────────┴──────────────┐
          │                            │
   Engineering Platform         Project Knowledge          (SIBLING bounded contexts —
   "how work is performed"      "what the project means     independent, same constitution)
    + evidence of work           + assembly of understanding"
          │                            │
          └─────────────┬──────────────┘
                        │
              Product Implementation                 (both serve it; neither owns it)
```

| Aspect | Relationship |
|---|---|
| Governance | Siblings under the constitution — neither governs the other. |
| Consumption (PK ← EP) | PK consumes qualification records, gate results, retrospectives as *evidence claims* (Measurement/Finding natures). |
| Production (PK → EP) | PK produces the understanding engineering work runs on: assembled Working Contexts for the Engineer role; ADR/IDD-class decision knowledge that the EEP's "Governing decision" plan field references. |
| Process | PK *work* (creating/superseding claims) is performed **under the EEP** — the process governs the work; the knowledge context owns the content. |
| Boundary sentence | **Engineering owns how work flows and the evidence of work; Project Knowledge owns what the project means and the assembly of understanding.** Session logs are engineering/runtime observations that PK *mines* (the rationale transition); the EKP is the incumbent partial implementation of PK's governance capability (verdict pending E-1). |
| Shared infrastructure | Permitted (same repository, same tooling) — the domain models remain separate; shared infrastructure never merges the contexts. |

---

**Constraint check:** conceptual only; every term and entity validated or falsified with evidence pointers; three candidates corrected (Coordinate→VO, Query→VO, ProjectMemory→region-not-aggregate), one falsified (Retrieval as peer term), one added under evidence pressure (WorkingContext, ephemeral by constitutional necessity). **STOP — ARB review precedes any design or implementation.**
