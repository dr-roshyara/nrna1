# KnowledgeOS Event & Integration Architecture

> ⛔ **PROPOSED · NON-AUTHORITATIVE · REQUIRES ARCHITECTURE/GOVERNANCE REVIEW.**
>
> Part of the **Architecture Review Set** (see [00 — Index](00-KnowledgeOS-Architecture-Review-Index.md)).
> Consolidates the corpus's **event-driven and integration** claims — domain events,
> event-driven collaboration, the governed command loop, consistency, outbox —
> as **evidence for an architecture decision, not a decision itself**. Every claim is
> level-tagged (`[DOMAIN]` / `[PATTERN]` / `[TECH]` / `[PRODUCT]`) and
> status-tagged (`ESTABLISHED` / `PROPOSED` / `REJECTED` / `OPEN`).

## 1 · Sources

| Source (renamed corpus) | Type | Content |
|---|---|---|
| `brainstorming/20260820-231930-event-driven-architecture-refinement.md` | architecture-proposal | Event-driven architecture refinement (article-derived): EDA patterns, event sourcing, consistency, Assurance Context |
| `brainstorming/20260821-142748-event-driven-domain-loop-positioning.md` | architecture-proposal | Positions the governed event loop: Command → Application Boundary → Aggregate → Domain Rule → Domain Event; outbox; internal vs integration events |
| `brainstorming/20260821-121929-business-translator-capability.md` | architecture-proposal | Business Translator capability (event relevance: translation layer over governed state) |

**Cross-references:** `docs/knowledge_tranfer/20260816_1044_domain_events.md` —
⚠️ **this is the T3 PKS event catalog, not KnowledgeOS (T1)**; cited for
comparison only · `docs/knowledgeos/architecture/03-component-knowledgeos.puml` ·
Review-Set [01](01-KnowledgeOS-Domain-and-Context-Architecture.md) §2.4.

## 2 · Current-state: how integration works today (ESTABLISHED)

- `[PATTERN][ESTABLISHED]` KnowledgeOS today is **not event-driven**: the existing
  platform is a **session/composition-root, request–response** execution model
  (`session_manager`, `workflow_engine`, `review_engine`, `verification_engine` act
  on the current request) — `…121929-business-translator`,
  `…145153-ai-engineering-platform-6-role-model`, `03-component-knowledgeos.puml`.
- `[DOMAIN][ESTABLISHED]` The durable **authority record is an append-only transition
  log** (210→216 transitions recorded) — sequence-proven, conflict-detectable
  (R-CONFLICT), read as evidence by assurance — `KOS-AIP-GOV-STATE-DURABILITY-ADR`,
  `…120633-kos-state-durability-assurance-integration`. This is a **log-shaped
  record**, but KnowledgeOS has **no event-driven runtime yet** — the log is written
  synchronously by the executing component, not consumed as an event stream.
- `[DOMAIN][ESTABLISHED]` The **rule/decision/authority model** is validated,
  synchronous decisions (approval grants, lifecycle transitions) — authority
  decisions are **strongly consistent** today — `…204714-track2-eks`,
  `…120633`.

## 3 · Event-driven claims (all PROPOSED unless marked)

### 3.1 The governed command loop (the corpus's core event claim)
- `[PATTERN][PROPOSED]` The proposed integration spine: **Command → Application
  Boundary → Aggregate → Domain Rule → Domain Event**. Any actor — a human, an AI
  agent, the Digitalization Robot — issues a **governed command**; the domain applies
  its rules; the outcome is a **domain event**; no actor may "Change Governance."
  directly — `…142748-event-driven-domain-loop-positioning`.
- `[DOMAIN][PROPOSED]` **Internal vs integration events**: a context-internal domain
  event (e.g. `ProductVersionApproved`) becomes a **cross-context integration event**
  (`KnowledgeProductVersionApproved`) via an **outbox**; "AI cache invalidation" is a
  downstream reaction, not a core domain event — `…142748`, aligning with the
  `…205757` refinement recorded in [01](01-KnowledgeOS-Domain-and-Context-Architecture.md) §2.4.
- `[PATTERN][PROPOSED]` Event vocabulary (context-level, v3): KnowledgeProductCreated,
  KnowledgeValidated, KnowledgeApproved, KnowledgeActivated, KnowledgeDeprecated,
  EvidenceAdded, DecisionChanged, PolicyChanged; `DecisionChanged → Impact Analysis →
  Affected Products` is proposed as a governed reaction chain — `…204431`.
- `[DOMAIN][PROPOSED]` R-CONFLICT is a **domain event/invariant** owned by the
  Evidence context (conflict resolution must preserve provenance, sequence
  integrity, reconstruction capability) — not a Git merge problem — `…220806-kos-3-0`.

### 3.2 The Assurance Context (new — a boundary question, not an adopted context)
- `[DOMAIN][PROPOSED]` The event-driven refinement proposes a **new Assurance
  Context** alongside the existing contexts — `…142748-event-driven-domain-loop-positioning`.
- `[DOMAIN][PROPOSED]` Contrast with the capability framing in
  [03](03-KnowledgeOS-Evidence-Assurance-and-Governance.md) §3: deterministic
  assurance is elsewhere treated as a **cross-cutting capability** (guardrails,
  warn-only checker, no authority manufacture) — `…120633`. **Whether assurance
  becomes a bounded context or stays a capability is an open boundary decision** (see
  [07](07-KnowledgeOS-Target-Architecture-Review.md) §T2).

### 3.3 Consistency model (the event-driven vs strongly-consistent tension)
- `[PATTERN][PROPOSED]` Event-driven, eventually-consistent propagation is proposed
  for **downstream reactions** (projections, notifications, cache invalidation,
  business-translator interpretations) — `…142748`, `…121929`.
- `[PATTERN][PROPOSED]` Authority-critical transitions (approval, grant, lifecycle
  promotion) are claimed to **remain synchronous / strongly consistent** — an
  "approve" that may silently fail to propagate is not an approval — `…142748`,
  `…204714`.
- `[TECH][PROPOSED]` Event sourcing and a broker (Kafka-class) appear as **options in
  the discussion** — never adopted; the corpus records them as open technology
  questions, not decisions — `…231930-event-driven-architecture-refinement`,
  `…142748`. ⚠️ Preservation note: **Event Sourcing and Kafka are hypotheses here,
  not established KnowledgeOS technology.**

## 4 · Contradictions & tensions (surfaced, not resolved)

| # | Tension | Where it appears |
|---|---|---|
| T1 | **Event-driven vs strongly-consistent governance**: async propagation for reactions vs synchronous authority-critical transitions — where the line falls is undecided | `…142748` (internal) |
| T2 | **Event sourcing vs durable state + events**: the existing record is a durable append-only **transition log with folded state** ("derived state is folded, never persisted"); event sourcing as a full source-of-truth stream is a different mechanism — compatible or competing is unresolved | `KOS-AIP-GOV-STATE-DURABILITY-ADR` vs `…231930` |
| T3 | **Assurance as context vs capability**: a new Assurance Context vs the warn-only cross-cutting capability | `…142748` vs `…120633` |
| T4 | **Outbox vs synchronous writes**: integration events via outbox imply async delivery; the current evidence log is written synchronously by the executor — two write paths for related facts | `…142748` vs current-state log (§2) |

## 5 · Open questions

- `[OPEN]` Which KnowledgeOS invariants require synchronous consistency vs which may
  propagate asynchronously — the aggregate-split decision depends on this —
  `…205757`, `…142748`.
- `[OPEN]` Event-carried state transfer vs event notification vs event sourcing for
  the cross-context contracts — undecided — `…231930`.
- `[OPEN]` Whether the transition log should become consumable as an event stream
  (a read-side projection) or remain a private evidence artifact — `…120633`,
  `KOS-AIP-GOV-STATE-DURABILITY-ADR`.
- `[OPEN]` Idempotency/replay strategy for integration events and downstream
  projections — not yet specified in the corpus.

## 6 · What is NOT decided here

No event-driven runtime, no event sourcing, no broker/Kafka, no outbox
infrastructure, and no Assurance Context are adopted by this document. The existing
request–response execution model remains the current state. All event-driven claims
are architectural hypotheses requiring human Architecture/Governance review.

## Traceability

Corpus sources in §1 (renamed, provenance in `brainstorming/00_INDEX.md`) ·
cross-refs `docs/knowledge_tranfer/20260816_1044_domain_events.md` (T3, comparison
only) · `docs/knowledgeos/architecture/03-component-knowledgeos.puml` ·
Review-Set [00](00-KnowledgeOS-Architecture-Review-Index.md) ·
[01](01-KnowledgeOS-Domain-and-Context-Architecture.md) ·
[03](03-KnowledgeOS-Evidence-Assurance-and-Governance.md) ·
[07](07-KnowledgeOS-Target-Architecture-Review.md) · plan D-6/D-7 ·
corpus sort commit `9a57a7cb`.
