# KnowledgeOS Domain & Bounded Context Architecture

> ⛔ **PROPOSED · NON-AUTHORITATIVE · REQUIRES ARCHITECTURE/GOVERNANCE REVIEW.**
>
> Part of the **Architecture Review Set** (see [00 — Index](00-KnowledgeOS-Architecture-Review-Index.md)).
> Consolidates what the corpus claims about KnowledgeOS's **domain and bounded
> contexts** — the named contexts, aggregates, invariants, and ownership — as
> **evidence for an architecture decision, not a decision itself**. Every claim is
> level-tagged (`[DOMAIN]` / `[PATTERN]` / `[TECH]` / `[PRODUCT]`) and
> status-tagged (`ESTABLISHED` / `PROPOSED` / `REJECTED` / `OPEN`).

## 1 · Sources

| Source (renamed corpus) | Type | Content |
|---|---|---|
| `brainstorming/20260819-204018-kos-product-architecture-v2.md` | architecture-proposal | Knowledge Product OS v2: product-first framing, lifecycle, hexagon, runtime |
| `brainstorming/20260819-204431-kos-ddd-architecture-review-v3.md` | architecture-proposal | DDD v3: seven bounded contexts, aggregates, ADR-KOS-001 |
| `brainstorming/20260819-205541-kos-ddd-architecture-v3-duplicate.md` | duplicate ⩲ of v3 | byte-identical to v3 |
| `brainstorming/20260819-220806-kos-3-0-state-durability-ddd-boundary.md` | architecture-proposal | 3-round Principal-Architect review: Evidence vs Governance boundary, R-CONFLICT, ADR-KOS-002 |
| `brainstorming/20260816-204714-track2-eks-semantic-discovery.md` | analysis | Track-2 (EKS) exploratory DDD: Rule model, Scope, authority-gap analysis |

**Cross-references:** `docs/knowledge_tranfer/20260816_0841_target_architecture_v3.md` +
`…0922_aggregate_boundaries` + `…1023_invariant_aggregate_matrix` — ⚠️ **these are the
T3 PKS target architecture, not KnowledgeOS (T1)**; cited for comparison only
(see §7).

## 2 · The domain as proposed (level + status tagged)

### 2.1 Product-first framing
- `[PRODUCT][PROPOSED]` KnowledgeOS should move from "knowledge repository
  architecture" to a **Knowledge Product Operating System**; the Knowledge Product is
  the atomic unit — `…204018`.
- `[DOMAIN][PROPOSED]` Three principles: *Knowledge Product is the atomic unit;
  KnowledgeOS Runtime is the governance/enforcement layer; AI Agents are consumers,
  not owners, of knowledge* — `…204018`.
- `[DOMAIN][PROPOSED]` "KnowledgeOS is not primarily a knowledge storage platform …
  It is a **Knowledge Governance Domain Platform**"; core domain = creating, governing,
  validating, evolving, and delivering trusted Knowledge Products — `…204431`; refined
  in `…205757`: "a governed knowledge-product platform whose core domain is the
  creation, evolution, and authorized delivery of trusted organizational knowledge".
- `[PRODUCT][REJECTED-framing]` Explicitly **NOT** a "Knowledge Management Platform"
  (that framing would place it beside Confluence/SharePoint) — `…204431`.

### 2.2 Named bounded contexts
- `[DOMAIN][PROPOSED]` **Seven contexts** (v3, `…204431`): Knowledge Governance,
  Knowledge Product, Knowledge Evidence, Knowledge Semantic, Knowledge Delivery,
  Knowledge Intelligence, Platform Administration.
- `[DOMAIN][PROPOSED]` Knowledge Product = **Core Domain**; main aggregate
  `KnowledgeProduct` (ProductId, Name, Owner, LifecycleState, Version, Scope,
  Consumers, UsagePolicy, KnowledgeElements[]) — `…204431`.
- `[DOMAIN][PROPOSED]` v2 (`…204018`) and the 3.0 review (`…220806`) emphasise a
  **Governance Context vs Evidence Context vs Product Context** split: Governance
  answers *"who has authority to decide / what makes something authoritative"*,
  Evidence answers *"what evidence supports this claim"*, Product answers *"what
  reusable organizational knowledge is produced"* — `…220806` (rounds 1–2).
- `[DOMAIN][PROPOSED]` Verification verdict (`…205757`) grades each context:
  Product (strong core candidate), Governance (strong), Evidence (strong), Semantic
  (plausible supporting/projection), Delivery (strong; own resolution/authorization/
  delivery contracts), Intelligence (supporting; **must not own authority**), Platform
  Administration (supporting/generic).
- `[DOMAIN][PROPOSED]` Track-2/EKS (`…204714`) adds **nothing** to the context map —
  it is deliberately exploratory and refuses to decide any architecture until the
  current baseline (KOS-ARCH-BASELINE-001) is reconstructed/verified/accepted
  `[ESTABLISHED]` for its self-bound.

### 2.3 Aggregates, lifecycle, invariants
- `[DOMAIN][PROPOSED]` `KnowledgeProduct` contains Decision, Method, Binding, Rule,
  Constraint, EvidenceReference; Method/Binding/Evidence described as "your unique
  innovation" (Claim→Reason→Application→Proof) — `…204431`, `…204018`.
- `[DOMAIN][PROPOSED][TENSION]` v3 review (`…205757`) **challenges** this: the
  single `KnowledgeProduct` aggregate is "too large" (contention, coupling,
  versioning, large events, awkward review); better candidates: KnowledgeProduct,
  KnowledgeElement, GovernanceCase, EvidenceRecord, KnowledgeRequest,
  UsageAuthorization.
- `[DOMAIN][PROPOSED]` Aggregate-ownership rule: *a Knowledge Product owns product
  identity/scope/version/lifecycle/membership; Knowledge Elements own their own
  content and local invariants; Governance determines whether a product version may
  be promoted* — `…205757`.
- `[DOMAIN][PROPOSED]` Lifecycle: Discovery → Candidate → Validated → Approved →
  Active → Deprecated → Archived (`…204018`); refined to a **ProductVersion**
  lifecycle (candidate/validated/approved/active/deprecated) because the ACTIVE
  invariant attaches to a release candidate, not the abstract product — `…205757`.
- `[DOMAIN][PROPOSED]` Governance invariant: *a KnowledgeProduct cannot become
  ACTIVE without owner, evidence, approval, validation* (`…204431`); runtime enforces
  lifecycle as a condition of consumption (DRAFT blocks AI; APPROVED read-only;
  ACTIVE allows reasoning) — `…204018`.
- `[DOMAIN][ESTABLISHED]` **Rule** definition (EKS/Track-2): *"a standing,
  authoritative obligation governing behaviour within a defined scope and period; a
  deviation is non-conformance unless an authorized exception permits it."* Rule ≠
  Decision ≠ Recommendation ≠ Permission — `…204714`.
- `[DOMAIN][ESTABLISHED]` Scope invariant: *missing scope must not silently broaden a
  Rule; unknown applicability must never be treated as non-applicability; UNKNOWN ≠
  DOES NOT APPLY ≠ EMPTY SCOPE* — `…204714`.
- `[DOMAIN][ESTABLISHED]` Authority principle: *"the mechanism records authority; it
  does not create authority"* (20 live grants all point to a human act; Governance
  records the reference) — `…204714`.

### 2.4 Domain events (context-level)
- `[DOMAIN][PROPOSED]` Event vocabulary proposed: KnowledgeProductCreated,
  KnowledgeValidated, KnowledgeApproved, KnowledgeActivated, KnowledgeDeprecated,
  EvidenceAdded, DecisionChanged, PolicyChanged; DecisionChanged → Impact Analysis →
  Affected Products → AI cache invalidation — `…204431`.
- `[PATTERN][PROPOSED]` Refinement (`…205757`): distinguish **internal** domain
  events from **cross-context** integration events (e.g. `ProductVersionApproved`
  → integration `KnowledgeProductVersionApproved` via outbox); "AI cache
  invalidation" is a downstream reaction, not a core domain event. (See also
  [04 — Event & Integration](04-KnowledgeOS-Event-and-Integration-Architecture.md).)

### 2.5 Evidence & authority ownership
- `[DOMAIN][PROPOSED]` Evidence is a **separate bounded context**; "KnowledgeProduct
  owns Evidence" is explicitly **not** the model — evidence has its own lifecycle —
  `…204431`.
- `[DOMAIN][PROPOSED]` Three evidence concepts to keep apart: EvidenceRecord
  (durable source-backed), EvidenceReference (relationship), EvidenceBundle
  (point-in-time evidence for a decision/response/execution) — `…205757`.
- `[DOMAIN][PROPOSED]` "AI cannot create authority"; authority comes from the
  Governance context; AI is a capability, not the core domain — `…204431`, retained
  in `…205757`.
- `[DOMAIN][PROPOSED]` Control chain: *AI proposes → Domain rules validate →
  Authorized actors approve → Runtime policy enforces → Evidence records the result*
  — `…205757`.

### 2.6 State-durability boundary (the 3.0 review's core claim)
- `[DOMAIN][ESTABLISHED]` The durability issue is "the discovery of a missing domain
  boundary: separating **Knowledge Execution** from **Knowledge Governance Evidence**"
  — `.claude/runtime` mixes execution state + governance-authority records: "same
  storage ≠ same bounded context" — `…220806`.
- `[DOMAIN][PROPOSED]` Evidence lifecycle: Observation → Evidence Candidate →
  Qualified Evidence → Governance Accepted Evidence → Knowledge Product Binding
  (`…220806`); an AI agent does not create knowledge directly — it creates
  observations/proposals/evidence candidates.
- `[DOMAIN][PROPOSED]` `R-CONFLICT` is a **domain event/invariant**, not a Git
  merge problem: conflict resolution MUST preserve provenance, sequence integrity,
  and reconstruction capability; owned by the Evidence context — `…220806`.
- `[DOMAIN][PROPOSED]` ADR-KOS-002 (proposed): "Separate Operational Execution from
  Durable Governance Memory"; **do not freeze the new bounded contexts yet — run a
  validation workshop** — `…220806` (final condition).

### 2.7 Architecture-pattern adjacent claims
- `[PATTERN][PROPOSED]` Hexagonal/Ports & Adapters recommended for KnowledgeOS
  (dependency rule flows inward; ADR/Decision/Receipt core → Ports →
  PostgreSQL/Git/AI-Agent adapters) — `…204018`.
- `[PATTERN][REJECTED]` Layered/MVC rejected (DB/UI depending on business logic
  violates the dependency rule) — `…204018`.
- `[PATTERN][PROPOSED]` ADR-KOS-001 proposed: *"Adopt DDD + Hexagonal Architecture
  with Knowledge Product as the Core Domain Aggregate"* — `…204431`.
- `[PATTERN][REJECTED-as-written]` v3 review (`…205757`) declines to freeze
  ADR-KOS-001 as written (it conflates strategic framing + tactical aggregate design +
  implementation architecture); proposes a safer ADR + separate ADRs.
- `[PATTERN][PROPOSED]` Hexagonal is "compatible but not a defining consequence of
  DDD"; a bounded context need not be a microservice — `…205757`.

## 3 · Contradictions & tensions (surfaced, not resolved)

| # | Tension | Where it appears |
|---|---|---|
| T1 | **Single `KnowledgeProduct` aggregate vs multiple aggregates** (product + element + governance-case + evidence-record + …) | `…204431`/`…204018` (single) vs `…205757` (challenges) |
| T2 | **Context-shaped sequential chain vs context map** — "Governance→Product→Semantic→Delivery→Intelligence" treated as layers vs a relationship map (conformist/partnership/customer-supplier/projection/ACL) | `…205757` |
| T3 | **Evidence ownership** — "Product owns Evidence" (v2) vs "Evidence is a separate context with its own lifecycle" (v3, 3.0) | `…204018` vs `…204431`/`…220806` |
| T4 | **Bounded-context freeze** — the 3.0 review approves with the condition "do not freeze; validate" vs the v3 framing that reads as a stable proposal | `…220806` |
| T5 | **T1 KnowledgeOS vs T3 PKS context sets** — the corpus names Product/Evidence/Governance/… for KnowledgeOS; the `knowledge_tranfer` cluster names CBC-1…4 (Assessment/Projection/Normative Governance/Work Management) for the **product knowledge space**. These are different systems and must not be merged | `…204431` (T1) vs `…0841_target_architecture_v3` (T3) |

## 4 · Open questions

- `[OPEN]` Which KnowledgeOS invariants require **synchronous** consistency vs
  which may propagate asynchronously via events/projections — this decides the
  aggregate split (product vs product+version vs broader) — `…205757`.
- `[OPEN]` Governance/Evidence authority gaps (Track-2): no temporal ending on
  authority; delegation observed but not represented; ownership ≠ authority;
  provenance needs stronger immutable addressing; rule changes must connect to the
  authority mechanism — `…204714`.
- `[OPEN]` EKS vocabulary gaps: environment vocabulary (no governed vocabulary yet),
  rule-relationship vocabulary (supersedes/refines/exception-to/contradicts/
  duplicates/supports), lifecycle meanings, and a knowledge vocabulary without one
  generic `KnowledgeItem` — `…204714`.

## 5 · What is NOT decided here

None of the proposed contexts, aggregates, invariants, or ADR-KOS-001/-002 are
adopted by this document. The v3 review's own conditions — *"do not freeze the new
bounded contexts yet; run a validation workshop"* — are recorded and **remain
open** for the human Architecture/Governance authority.

## Traceability

Corpus sources in §1 (renamed, provenance in `brainstorming/00_INDEX.md`) ·
cross-refs `docs/knowledge_tranfer/20260816_0841_target_architecture_v3.md` +
`…0922_aggregate_boundaries` + `…1023_invariant_aggregate_matrix` ·
Review-Set [00](00-KnowledgeOS-Architecture-Review-Index.md) ·
[03](03-KnowledgeOS-Evidence-Assurance-and-Governance.md) ·
[07](07-KnowledgeOS-Target-Architecture-Review.md) · plan D-6/D-7 ·
corpus sort commit `9a57a7cb`.
