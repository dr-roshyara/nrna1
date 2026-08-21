# KnowledgeOS Target Architecture — Cross-Theme Reconciliation

> ⛔ **PROPOSED · NON-AUTHORITATIVE · REQUIRES ARCHITECTURE/GOVERNANCE REVIEW.**
>
> Part of the **Architecture Review Set** (see [00 — Index](00-KnowledgeOS-Architecture-Review-Index.md)).
> **This is an architecture reconciliation, not a synthesis.** It consolidates the
> claims of documents 01–06 into a **claim register** (Established / Proposed /
> Rejected / Open, each with its source), separates **what KnowledgeOS is today** from
> **what the proposed target could become**, surfaces **contradictions and tensions**
> **unresolved**, traces the **evolution chain**, and ends with **Decision Candidates**
> — every candidate explicitly requiring **human approval**. **Nothing here adopts,
> freezes, or promotes any KnowledgeOS architecture.**

## 1 · Scope & method

- This document classifies the **record**, never adopts on the human's behalf.
  `Rejected` appears only where the corpus explicitly considered and declined a claim.
  `Established` requires direct repository evidence as current fact or decision.
  `Proposed` is a plausible design/hypothesis. `Open` is insufficient evidence or a
  pending decision.
- Four claim levels are kept separate throughout (item 6): `[DOMAIN]` ·
  `[PATTERN]` · `[TECH]` · `[PRODUCT]`.
- The `_misc/` sources are integrated per the commission instruction (kernel/OS
  model, Digitalization Robot, IPO/IP/commercial) and are cited as first-class
  sources below.

## 2 · Claim register

### 2.1 Domain claims (`[DOMAIN]`)

| # | Claim | Status | Source(s) |
|---|---|---|---|
| D1 | KnowledgeOS is a governed knowledge(-product) platform; core domain = creating, governing, validating, evolving, delivering trusted Knowledge Products | **ESTABLISHED** (position, corpus-wide) | `…204431`, `…205757`, `…204018` |
| D2 | Named seven bounded contexts: Governance, Product, Evidence, Semantic, Delivery, Intelligence, Platform Administration | **PROPOSED** (validation workshop pending) | `…204431` |
| D3 | `KnowledgeProduct` as single main aggregate owning Decision/Method/Binding/EvidenceReference | **PROPOSED** (challenged) | `…204431`/`…204018` |
| D4 | Multiple aggregates (Product + KnowledgeElement + GovernanceCase + EvidenceRecord + KnowledgeRequest + UsageAuthorization) | **PROPOSED** (challenges D3) | `…205757` |
| D5 | Evidence is a **separate bounded context** with its own lifecycle — "Product owns Evidence" is explicitly declined | **PROPOSED** (declined-framing recorded) | `…205757`, `…220806` |
| D6 | R-CONFLICT is a domain event/invariant owned by Evidence (preserve provenance/sequence/reconstruction) | **PROPOSED** (as domain claim) | `…220806`, `KOS-AIP-GOV-STATE-DURABILITY-ADR` |
| D7 | Authority: *the mechanism records authority; it does not create authority*; AI cannot create authority | **ESTABLISHED** (principle) | `…204714`, `…204431` |
| D8 | Rule ≠ Decision ≠ Recommendation ≠ Permission; unknown applicability ≠ non-applicability | **ESTABLISHED** (principle) | `…204714` |
| D9 | Separation: source of execution vs evidence of execution vs authoritative decision record (B′ relocation, R-CONFLICT) | **DECIDED (PO/ARB 2026-08-19, D1–D2)** — recorded, not re-decided here | `KOS-AIP-GOV-STATE-DURABILITY-{ADR,DECISION}` |
| D10 | Durability boundary discovery: Execution vs Governance-Evidence are distinct domains sharing a storage path — "same storage ≠ same bounded context" | **ESTABLISHED** (problem), **PROPOSED** (boundary) | `…220806` |
| D11 | KnowledgeOS (T1) context set vs PKS (T3) context set must **not** be merged | **ESTABLISHED** (reconciliation invariant) | `…204431` (T1) vs `knowledge_tranfer/…0841` (T3) |

### 2.2 Architecture-pattern claims (`[PATTERN]`)

| # | Claim | Status | Source(s) |
|---|---|---|---|
| P1 | Hexagonal / Ports & Adapters adopted for KnowledgeOS | **ESTABLISHED** (lived practice, asserted) — no formal ADR recorded | `…123902`, `…205757` |
| P2 | Layered/MVC rejected | **REJECTED** (explicitly) | `…204018` |
| P3 | ADR-KOS-001 "DDD + Hexagonal, Knowledge Product as Core Aggregate" | **PROPOSED** (declined to freeze as-written — conflates levels) | `…204431`, `…205757` |
| P4 | Governed command loop: Command → Application Boundary → Aggregate → Domain Rule → Domain Event; outbox; internal vs integration events | **PROPOSED** | `…142748` |
| P5 | Assurance as cross-cutting warn-only capability (G-1…G-6) | **PROPOSED** | `…120633` |
| P6 | Assurance as a new bounded context | **PROPOSED** (conflicts with P5) | `…142748` |
| P7 | Modular monolith vs microservices | **OPEN** | `…123902` vs `_misc/…224159` |
| P8 | Composition-root / DI principle (Spring idea, framework-independent) | **PROPOSED** | `…220924` |
| P9 | LCOM4 cohesion measurement for multi-language binding | **PROPOSED** | `…213516` |
| P10 | Kernel/OS model: kernel primitives + adapters, distributions → products | **PROPOSED** (positioning) | `_misc/…224159` |

### 2.3 Technology claims (`[TECH]`)

| # | Claim | Status | Source(s) |
|---|---|---|---|
| T1 | Current platform is PHP/Laravel + TypeScript/Vue, `.claude/platform/` components | **ESTABLISHED** (current state) | `…121929`, project CLAUDE.md |
| T2 | Rust kernel recommendation | **PROPOSED** (never adopted) | `_misc/…224159` |
| T3 | Spring Boot/Kotlin-Java enterprise runtime | **PROPOSED** (never adopted) | `_misc/…224159` |
| T4 | Python intelligence; TypeScript studio | **PROPOSED** (never adopted) | `_misc/…224159` |
| T5 | Event Sourcing / Kafka broker | **PROPOSED** (options only) | `…231930`, `…142748` |
| T6 | Gradle/Cargo build/binding; plugin architecture | **PROPOSED** (options only) | `_misc/…224159`, `…213516` |
| T7 | `knowledge-lint` hard-scoped to `docs/knowledge/` — zero coverage of `docs/knowledgeos/` | **ESTABLISHED** (gap) | `…120633` |

### 2.4 Product / business claims (`[PRODUCT]`)

| # | Claim | Status | Source(s) |
|---|---|---|---|
| B1 | "Not a Knowledge Management Platform" (Confluence/SharePoint class) | **ESTABLISHED** (corpus position); NOT what KnowledgeOS is | `…204431` |
| B2 | KnowledgeOS as "operating system for organizational knowledge" | **PROPOSED** (positioning hypothesis) | `_misc/…224159` |
| B3 | Digitalization Robot as the platform's top execution layer; Automation Context; authority modes | **PROPOSED** | `_misc/…221159`, `…142748` |
| B4 | Business Translator capability (deferred) | **PROPOSED** (timing open) | `…121929` |
| B5 | Six-role / four-session operating model | **PROPOSED** | `…145153`, `…104823` |
| B6 | POA-vs-DDD decision hierarchy | **PROPOSED** / **OPEN** | `…115444` |
| B7 | Open methodology (A) vs proprietary platform (B) | **OPEN** (corpus poses, undecided) | `_misc/…225858`, `_misc/…225329` |
| B8 | IPO / stock-market path | **PROPOSED** (product hypothesis) | `_misc/…225858`, `_misc/…225332` |
| B9 | IP-protection strategy (secrecy vs patent vs open) | **PROPOSED** (product hypothesis) | `_misc/…225329` |

## 3 · What KnowledgeOS IS today (current state, evidence-grounded)

> **Item 11 discipline:** every row below is a current-state fact with a source. Rows
> the corpus merely *proposes* are excluded here and appear in §4.

- **[ESTABLISHED]** A **governed knowledge platform inside the PublicDigit
  engineering workflow** (T1), operating a documentation/knowledge lifecycle with
  ADRs, reviews, evidence logs, grants, and role separation —
  `…204431`, `…204714`, CLAUDE.md.
- **[ESTABLISHED]** Implemented as **PHP/Laravel + TypeScript/Vue** with
  `.claude/platform/` components: `composition_root` · `session_manager` ·
  `knowledge_manager` · `workflow_engine` · `verification_engine` · `review_engine` ·
  `drafting_studio` · `platform_registry` — `…121929`, `…145153`.
- **[ESTABLISHED]** **Request–response, session-based execution** — **not**
  event-driven (no event runtime, no outbox, no broker) — `…121929`,
  `03-component-knowledgeos.puml`.
- **[ESTABLISHED]** An **append-only authority transition log** under `.claude/runtime/`
  (untracked; 210→216 transitions, 99→114 grants; derived state folded, never
  persisted) — `KOS-AIP-GOV-STATE-DURABILITY-ADR`.
- **[ESTABLISHED]** **Hexagonal architecture is lived** (adapters: Git/PostgreSQL/
  storage/AI-model; domain stable) but **not recorded as a formal ADR** —
  `…123902`, `…205757`.
- **[ESTABLISHED]** **Deterministic assurance exists**: `knowledge-lint` (S1–S5 +
  harness S6 + adapters S7), Phase 0 authorized/executed/pass (220 tests), Phase 1
  author-side handoff implemented/verified; **warn-only, no authority manufacture**;
  hard-scoped to `docs/knowledge/` → **no coverage of `docs/knowledgeos/`** —
  `…120633`, `docs/plans/20260821-1138-…`, `20260821-1641-…`.
- **[ESTABLISHED]** **Durability decision recorded**: PO/ARB decision act
  (2026-08-19) = D1 B′ relocation adopted · D2 R-CONFLICT adopted · D3 placement
  governance. **The migration plan is PROPOSED, NOT executed — 0 lines of
  implementation** — `KOS-AIP-GOV-STATE-DURABILITY-{DECISION,PLAN}`.
- **[ESTABLISHED]** **Methodology freeze (2026-08-01)** governs all KnowledgeOS
  process proposals — `…120633`, `…120810`.
- **[ESTABLISHED]** **Not** an operating system, **not** a kernel, **not**
  event-driven, **not** a Digitalization Robot, **not** a company or product, **no**
  Rust/Spring — these exist only as proposals (§4) — current-state negation of
  items 2/5/13.

## 4 · What the proposed target could become (hypotheses, never promoted)

| Hypothesis | Level | Core source | Gates itself behind |
|---|---|---|---|
| Seven bounded contexts (Governance/Product/Evidence/Semantic/Delivery/Intelligence/Platform-Admin) | DOMAIN | `…204431`, `…220806` | **validation workshop** (its own condition) |
| Knowledge Product as atomic unit + lifecycle | DOMAIN | `…204018`, `…205757` | context freeze |
| Evidence as separate context + evidence lifecycle (Observation → … → Product Binding) | DOMAIN | `…205757`, `…220806` | context freeze |
| Kernel/OS model (kernel primitives, adapters, distributions→robots) | DOMAIN+PATTERN | `_misc/…224159` | kernel definition decision |
| Digitalization Robot + Automation Context + authority modes | PRODUCT/DOMAIN | `_misc/…221159`, `…142748` | robot boundary + context freeze |
| Business Translator layer | DOMAIN/PRODUCT | `…121929` | capability-vs-project decision |
| Event-driven governed command loop + outbox | PATTERN | `…142748` | consistency decision |
| Assurance Context (or capability) | DOMAIN | `…142748` vs `…120633` | boundary decision |
| Rust kernel / Spring runtime / Python / TS; Gradle-Cargo; microservices; plugin; Event Sourcing; Kafka | TECH | `_misc/…224159`, `…231930`, `…213516` | technology decision (all unestablished) |
| Six-role operating model / POA hierarchy | PRODUCT | `…145153`, `…104823`, `…115444` | operating-model decision |
| Open methodology (A) vs proprietary platform (B) · IPO · IP protection | PRODUCT | `_misc/…225858`, `_misc/…225329`, `_misc/…225332` | commercial decision |

## 5 · Contradictions & tensions (surfaced — **not resolved here**)

| # | Tension | Where it appears |
|---|---|---|
| C1 | **Kernel-vs-platform**: "minimal kernel, no AI" vs Digitalization Robot as top layer | `_misc/…224159` vs `_misc/…221159`, `…142748` |
| C2 | **Bounded-context boundaries**: single `KnowledgeProduct` aggregate vs multi-aggregate; context chain vs context map; freeze vs validate | `…204431`/`…204018` vs `…205757` vs `…220806` |
| C3 | **Evidence-vs-Governance ownership**: Governance "defines authority rules" vs Evidence "is the source of truth governance evaluates" | `…220806` (rounds 1–2) |
| C4 | **Assurance as context vs capability**: new Assurance Context vs cross-cutting warn-only capability | `…142748` vs `…120633` |
| C5 | **Event-driven vs strongly-consistent governance**: async propagation vs synchronous authority-critical transitions | `…142748` (internal) |
| C6 | **Event sourcing vs durable state + events**: full source-of-truth stream vs append-only transition log with folded state | `…231930` vs `KOS-AIP-GOV-STATE-DURABILITY-ADR` |
| C7 | **Modular monolith vs microservices**: current-platform incremental evolution vs kernel-OS decomposition | `…123902` vs `_misc/…224159` |
| C8 | **Rust kernel vs enterprise runtime**: greenfield rewrite recommendation vs existing PHP/Laravel current state | `_misc/…224159` vs current state |
| C9 | **Open-source vs commercial**: open-core credibility (A) vs proprietary capture + IP secrecy (B) | `_misc/…224159`, `_misc/…225329`, `_misc/…225858` |
| C10 | **Digitalization Robot boundary**: robot as consumer/operator of governed knowledge vs the platform *being* the robot | `_misc/…221159`, `…142748` |
| C11 | **ADR status reads both ways**: ADR artifact PROPOSED vs decision act DECIDED — must be read together | `KOS-AIP-GOV-STATE-DURABILITY-ADR` vs `-DECISION` |
| C12 | **Cost optimisation vs methodology freeze**: corpus proposes then freezes the same levers | `…120810` (internal) |
| C13 | **T1 vs T3 conflation risk**: KnowledgeOS context set vs PKS target-architecture context set (CBC-1…4) | `…204431` vs `knowledge_tranfer/…0841` |
| C14 | **Role-first vs DDD discipline**: six-role operating model risks roles becoming aggregates | `…145153`/`…104823` vs `…092449` |
| C15 | **Hexagonal "adopted" vs unrecorded**: asserted as adopted but no ADR — lived vs formal status | `…123902` vs ADR record |

## 6 · Architecture evolution chain

**Current State (evidence)** → **Proposed Evolution (hypotheses)** →
**Decisions Required (human)** → **Future Target (conditional)**

```
Current State (ESTABLISHED)                Proposed Evolution (PROPOSED)
──────────────────────────                 ─────────────────────────────
PHP/Laravel + TS platform     ──▶         Kernel/OS model · adapters ·
request/response, no events               event-driven governed command loop
authority log in .claude/runtime          evidence boundary B′ relocation (DECIDED, not executed)
knowledge-lint scoped to docs/knowledge   assurance extended to docs/knowledgeos
six named roles? / current operating set  six-role / four-session operating model
not a product, no IP strategy             open (A) vs proprietary (B) · IPO · IP strategy
                                           │
                                           ▼
                           ┌──────────────────────────────────────┐
                           │   DECISIONS REQUIRED (human only)    │
                           │  §7 Decision Candidates — each needs  │
                           │  Architecture/Governance approval     │
                           └──────────────────────────────────────┘
                                           │
                                           ▼
Future Target (conditional on decisions)
  a validated bounded-context set (core: Governance/Evidence/Product)
  a recorded durability-consistent evidence boundary (B′ executed)
  an assurance profile covering the KnowledgeOS corpus
  a defined kernel boundary (if adopted) with a technology decision
  an operating model and a commercial direction (if adopted)
```

## 7 · Decision Candidates (each requires **human approval**)

> Recommendation = **the strongest position the evidence defends**, offered as
> AI-review evidence, never as a ruling. Human approval required: **yes** for all.

**DC-1 — Bounded-context set**
- Question: adopt the seven proposed contexts (or a reduced Governance/Evidence/Product core) as the KnowledgeOS context map?
- Options: (a) adopt as proposed · (b) adopt a validated subset after a workshop · (c) keep current state.
- Evidence: `…204431` (seven contexts) · `…205757` (verification verdict; Product/Governance/Evidence strong, Intelligence must-not-own-authority) · `…220806` (own condition: **do not freeze; validate**).
- Recommendation: option (b) — run the validation workshop the corpus itself mandates before any freeze; the corpus's strongest self-position.
- Consequence: freezing without validation hardens unvalidated boundaries (C2, C3).

**DC-2 — Evidence boundary execution (durability)**
- Question: execute the PROPOSED migration plan for B′/R-CONFLICT, now that D1–D2 are DECIDED?
- Options: (a) execute the approved plan · (b) re-scope · (c) hold.
- Evidence: decision act D1–D3 (DECIDED) · migration plan (PROPOSED, **0 lines executed**) · R-CONFLICT invariants `…220806`.
- Recommendation: (a) — the decision is made; only execution remains. Plan is byte-preserving copy, never parse-and-rewrite.
- Consequence: executing makes the authority record durable; holding keeps the inversion (I-10) open.

**DC-3 — Deterministic assurance coverage**
- Question: extend `knowledge-lint` coverage (profile + root) to `docs/knowledgeos/`?
- Options: (a) extend · (b) keep hard scope · (c) new engine.
- Evidence: `…120633` ("zero mechanical coverage"; fix is profile + root, not a new engine) · Phase 0/1 executed · guardrails G-1…G-6.
- Recommendation: (a) — profile + root, reusing the existing checker; consistent with the approved phase 1.
- Consequence: mechanical coverage of the corpus; warn-only discipline preserved; no authority manufactured.

**DC-4 — ADR-KOS-001 / -002 form**
- Question: adopt ADR-KOS-001 as written, or split per `…205757`?
- Options: (a) as-written · (b) split into strategic + tactical + implementation ADRs · (c) defer.
- Evidence: `…205757` declines to freeze as-written (conflates levels) · `…220806` proposes ADR-KOS-002 with a validation condition.
- Recommendation: (b) — the record's own verification verdict.
- Consequence: a cleaner decision record; avoids freezing conflated claims.

**DC-5 — Event-driven runtime**
- Question: adopt the governed command loop + outbox for downstream reactions?
- Options: (a) adopt for reactions, keep authority transitions synchronous · (b) full event-driven · (c) stay request–response.
- Evidence: `…142748` (loop + outbox + internal/integration split) · `…204431` event vocabulary · current state is not event-driven.
- Recommendation: (a) — matches the corpus's own consistency stance (authority stays synchronous; C5).
- Consequence: sets the sync/async line; downstream projections/cache/translator become consumers.

**DC-6 — Kernel definition**
- Question: define the "KnowledgeOS Kernel" as an architecture boundary, or treat it as a positioning metaphor?
- Options: (a) define kernel boundary (minimal, no AI) · (b) metaphor only · (c) defer.
- Evidence: `_misc/…224159` (kernel definition + no-AI-start) · `…123902` (existing pattern practice).
- Recommendation: (a) — the corpus asserts the definition as "the missing step", and it is testable against existing components.
- Consequence: a clear core/periphery line; the Digitalization-Robot and Automation-Context questions (C1, C10) then resolve against it.

**DC-7 — Technology platform**
- Question: adopt a multi-language stack (Rust kernel / Spring runtime / Python / TS) or evolve the existing PHP/Laravel + TS platform?
- Options: (a) greenfield multi-language · (b) incremental evolution of current platform · (c) hybrid.
- Evidence: `_misc/…224159` (recommendation only) · current state is PHP/Laravel + TS (ESTABLISHED) · `…123902` hexagonal already lived.
- Recommendation: (b)/(c) — no corpus evidence elevates Rust/Spring beyond a brainstorm recommendation; a rewrite is unsupported.
- Consequence: protects working platform; keeps kernel-vs-runtime (C8) open as an informed decision.

**DC-8 — Operating model**
- Question: adopt the six-role (four-session-refined) operating model?
- Options: (a) adopt · (b) adopt after role-vs-domain de-risking · (c) keep current.
- Evidence: `…145153`, `…104823` (roles) · `…092449` (role ≠ context; roles become aggregates risk).
- Recommendation: (b) — adopt the separation-of-duties property, validate role names do not leak into aggregates (C14).
- Consequence: explicit author/owner/reviewer separation; cost questions follow (C12).

**DC-9 — Commercial / IP direction**
- Question: choose the strategic fork — open methodology (A) vs proprietary platform (B), incl. IP and IPO path?
- Options: (a) open · (b) proprietary · (c) defer until the product thesis is evidenced.
- Evidence: `_misc/…225858`, `_misc/…225329`, `_misc/…225332` (all hypotheses) · current state is not a product (ESTABLISHED).
- Recommendation: (c) — the corpus itself leaves the fork undecided; promoting it now would exceed the evidence.
- Consequence: preserves the fork (C9); the IP/IPO questions stay open product hypotheses.

**DC-10 — Business Translator**
- Question: implement the Business Translator now, or add it as a capability candidate?
- Options: (a) new project now · (b) capability candidate, deferred · (c) discard.
- Evidence: `…121929` ("add as future capability candidate, not a new project").
- Recommendation: (b) — the source's own timing position.
- Consequence: business-facing status remains a translation layer over governed state; uncertainty is preserved, not simplified.

## 8 · What this document is NOT

- ⛔ Not an architecture decision, adoption, or freeze (item 2, evidence-not-authority).
- ⛔ Not a synthesis that silently reconciles the C1–C15 tensions.
- ⛔ Not a migration plan or an implementation design.
- ⛔ Not a claim that KnowledgeOS *is* an OS/kernel/event-driven/robot/product
  (item 13) — those are §4 hypotheses unless evidenced in §3.
- ⛔ Not a governance ruling: rule, role, and authority are untouched.

## Traceability

Synthesis of Review-Set [00](00-KnowledgeOS-Architecture-Review-Index.md) ·
[01](01-KnowledgeOS-Domain-and-Context-Architecture.md) ·
[02](02-KnowledgeOS-Kernel-and-Platform-Architecture.md) ·
[03](03-KnowledgeOS-Evidence-Assurance-and-Governance.md) ·
[04](04-KnowledgeOS-Event-and-Integration-Architecture.md) ·
[05](05-KnowledgeOS-Architecture-Patterns-and-Technology.md) ·
[06](06-KnowledgeOS-Operating-Model-and-Product-Architecture.md) · corpus sources
per `brainstorming/00_INDEX.md` (renames, provenance, duplicates) · cross-refs
`KOS-AIP-GOV-STATE-DURABILITY-{ADR,IMPLEMENTATION-DESIGN,MIGRATION-PLAN}.md` +
decision act · assurance plans `docs/plans/20260821-1138-…`, `20260821-1641-…` ·
`reviews/2026-08-21-cost-optimization-governance-assurance-review.md` ·
`knowledge_tranfer/…0841+…1044+…1023` (T3, comparison only) · plan D-6/D-7 ·
corpus sort commit `9a57a7cb`.
