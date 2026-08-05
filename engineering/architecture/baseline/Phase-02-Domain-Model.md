# AI Engineering Platform — Phase 2: Domain Model & Capability Architecture (Deliverables 1–9)

| Governance | |
|---|---|
| **Authority** | Generated (AI-produced; never authoritative without human review) |
| **Status** | **FROZEN — Baseline v1.0 (ADR-AIP-01, 2026-07-08).** ARB review history: approved 2026-07-07 with three observations; accepted 2026-07-08 (rulings R-1..R-14 in `Phase-02.5-Certification-Plan.md` §6). Changes only by supersession via a new ADR-AIP (AIP-11) |
| **Owner** | Architecture Review Board |
| **Promotion** | via ADR approval |
| **Date** | 2026-07-07 |
| **Depends on** | `Phase-01-Discovery.md` (accepted); capability model in `Phase-02.5-Certification-Plan.md` |
| **Evidence base** | `docs/implementation/Implementation_Process_v1.0.md` + `_v1.1_Draft.md` · `docs/architecture/patterns/Platform_Capability_Pattern.md` · `docs/architecture/principles/Platform_Governance_Principles.md` · `docs/architecture/Architecture_Knowledge_Base_v1.0.md` · `docs/knowledge/Knowledge-Constitution.md` + `_meta/lifecycle.md` · `docs/adr/ADR-T-LOG-Tactical-Implementation.md` · `docs/implementation/PushB_Architecture_Blueprint.md` §1.A · `docs/implementation/Messaging_Architecture_Verification.md` · `docs/implementation/PB-003_Architecture_Readiness_Report.md` · `architecture/knowledge_transfer/` Parts 1–4 · `docs/architecture/design/Round32_Design_Governance_Charter.md` |

> Scope discipline: this document models the **domain** of AI-assisted software engineering for PublicDigit. It designs **no** folders, prompts, agents, commands, CLAUDE.md, or settings — those are Phase 3. Per the Phase 2 brief, the AI Engineering Platform is modeled exactly as the Election domain was modeled.

---

## 1. Mission of the AI Engineering Platform

**The AI Engineering Platform owns the production, preservation, and verification of engineering *evidence* — never engineering *authority*.**

Stated with the project's own ownership vocabulary (PGP-02: Owns / Coordinates / Preserves / Observes / Does-NOT-own):

| Disposition | Responsibility |
|---|---|
| **Owns** | Generation of governed work products (decision drafts, capability models, implementation plans, adversarial review analyses, verification evidence, knowledge cards, session continuity records) — all entering the world as `authority: generated` or `provisional`. The executable guards over the platform's **own** rules (PGP-03: owner hosts the guard). |
| **Coordinates** | The flow of a ticket through the frozen 15-step process; the assembly of the right knowledge into the right working session. Mechanism, never policy. |
| **Preserves** | The audit trail: traceability chains, gate verdicts, session history, superseded knowledge. Nothing preserved may be rewritten. |
| **Observes** | The project's authoritative artifacts — approved ADRs, the frozen Implementation Process, Engineering Rules ER-01..05, PGP-01..05, constitutional invariants CI-1..CI-5, certified Platform Capabilities. Read as upstream facts; never mutated. |
| **Does NOT own** | **Approval and certification** (ARB adversarial certification; Chief Architect IDD approval; Sponsor value judgments — no platform state transition may substitute for a human decision event). **The governed artifacts themselves** (an approved ADR belongs to the decision corpus; a ticket/IDD to the implementation process — the platform holds references and drafts, not authoritative records). **Constitutional invariants and their guards** (CI-5's guard is hosted by the constitutional suite per PGP-03/AD-M1; the platform may observe a guard trip and escalate, never own, weaken, or auto-retry it). **Truth by assertion** (the Honesty Invariant: no score, status, or completion claim not derived from an executable check — the direct lesson of Phase 1's fabricated-metrics finding). |

The core of the mission is **evidence-bearing engineering support under separation of duties** — the project's own research-governance principle "discover ≠ decide ≠ check ≠ entrench" (Round39-RGA), applied to an AI actor.

**Core Domain (ARB ruling R-3, 2026-07-07): Architecture Governance** — Design & Decision Support plus the governance model itself. Verification & Evidence is indispensable but Supporting: it serves governance; it does not define the platform's unique value.

**Outside the platform, permanently:** architectural decisions, value judgments, freeze/unfreeze, certification, merge authority, knowledge authority promotion, and everything the election software's own governance owns (the platform is not a second governance system for the product; it is the engineering-process counterpart of one).

---

## 2. Bounded Contexts

### 2.0 Discovery method

The ten candidate contexts from the Phase 2 brief were clustered by **ubiquitous language**, then split only where the project itself has already drawn a linguistic boundary (D-11: verification ≠ certification; Round39: discover ≠ check; EKP: knowledge governance is one language). Merge/split verdicts:

| Candidate | Verdict | Reason |
|---|---|---|
| Knowledge Management + Documentation Governance | **MERGE** | Identical language (knowledge card, authority, status, hub, lint, graph). Documentation governance *is* the EKP applied to docs; no second model exists. |
| Implementation Guidance + Developer/TDD Coaching + Testing Guidance | **MERGE** | One language: ticket, IDD, RED/GREEN/REFACTOR, micro-slice, DoD box, WBS. Coaching and testing guidance are steps 4–9 of the same frozen 15-step workflow. |
| Release Governance | **MERGE into Implementation Guidance** | PR/Merge/lifecycle language is steps 14–15 of the same workflow; a separate context would share ~90% vocabulary — a false boundary. |
| Architecture Fitness + Quality Gates (+ Traceability) | **MERGE into Verification & Evidence** | One language per D-11: property, fitness function, falsifiability, gate class, verdict, matrix, evidence. |
| Review | **KEEP SEPARATE** | D-11 declares certification a different language from verification: judgment, finding, attempt-to-reject, the 7 reuse questions. |
| Architecture Governance Support | **KEEP SEPARATE, renamed Design & Decision Support** | Drafting/decision-modeling language (ADR, option, ownership matrix, capability pattern) is distinct from both verification and adversarial review; separation of duties *requires* this boundary. |
| Session Continuity | **KEEP SEPARATE** | Distinct language (session, bootstrap, single next action, "evidence beats memory"), the fastest rate of change in the domain, distinct lifecycle (ephemeral → historical). |
| One big "Governance" context | **REJECTED** | God-context: it would fuse four languages (deciding, checking, judging, recording) that the project's constitution keeps apart. |

**Result: six bounded contexts + two external domains.**

### BC-1 Knowledge Governance

| Aspect | Content |
|---|---|
| **Responsibilities** | Owns the knowledge-card registry and graph; the two orthogonal dimensions (status lifecycle × authority) and their legal transitions; Knowledge Packages; the knowledge-lint guard (it owns the knowledge rules, so it hosts the guard — PGP-03). Observes the *content* of governed documents (content belongs to each document's declared owner). Preserves superseded/archived knowledge. |
| **Ownership** | Platform (context team = the AI platform itself); the EKP **specification** is owned upstream by Project Governance — this context executes it. |
| **Consumers** | All other platform contexts (resolve knowledge by id); Session Continuity (packages); humans (portal). |
| **Dependencies** | Upstream: Project Governance (EKP spec, frozen). |
| **Published Language** | Knowledge card schema, Knowledge Package manifest, knowledge graph. |
| **Ubiquitous Language** | knowledge item, knowledge card, `knowledge_id`, authority (authoritative/derived/generated/historical/provisional), status (idea→…→frozen→superseded→archived), typed relationship, code_ref, hub, recipe, package, orphan, cycle, single-authority. |
| **Upstream / Downstream** | Downstream of Project Governance; upstream (OHS/PL) of every other platform context. |
| **Policies** | Single authoritative item per topic. AI output enters as `generated`; promotion requires a referenced human decision event (Authority Boundary). Supersede, never edit history. |

### BC-2 Session Continuity

| Aspect | Content |
|---|---|
| **Responsibilities** | Owns session records, context snapshots, bootstrap payload assembly. Preserves archived sessions (Historical, immutable). Does NOT own the facts themselves: every stable fact carries a repo reference; on conflict **the repo wins** ("evidence beats memory", KT Part 4 §4.4 — the context's defining invariant, not a slogan). |
| **Ownership** | Platform. |
| **Consumers** | The AI actor at session start; auditors reading session history. |
| **Dependencies** | Downstream of Knowledge Governance (Knowledge Packages — the Bootstrap Prompt of KT Part 4 is explicitly the human analogue) and of Implementation Guidance (active ticket, single next action). |
| **Published Language** | Session log format, context snapshot, bootstrap payload. |
| **Ubiquitous Language** | session, session log, stable fact (MEMORY), current state (CONTEXT), active ticket, **single next action**, bootstrap, rehydration, staleness, "repo is source of truth". |
| **Upstream / Downstream** | Downstream of BC-1 and BC-3. |
| **Policies** | Exactly one declared next action at all times. A session may not end without state synchronisation. Archived sessions are immutable. Fastest-changing context — a structural reason it cannot live inside any slower one. |

### BC-3 Implementation Guidance

| Aspect | Content |
|---|---|
| **Responsibilities** | Owns the per-ticket Implementation Plan, step tracking, **derivation** of progress from WBS, and coaching interventions (advisory). Coordinates movement through the 15 steps including PR/Merge (absorbed Release Governance). Observes tickets and IDDs (authoritative copies belong to the project's process/docs). **Conformist to the FROZEN Implementation Process v1.0 by constitution — no authority to reinterpret it.** |
| **Ownership** | Platform; the process model itself is owned upstream. |
| **Consumers** | The AI actor doing the work; Session Continuity (next action); Verification & Evidence (which gates apply at which step); humans reading progress. |
| **Dependencies** | Upstream: Project Governance (process), Verification & Evidence (gate verdicts). |
| **Published Language** | Implementation plan, WBS, derived progress report, step status. |
| **Ubiquitous Language** | ticket (PB-xxx), IDD (17 sections), 15 steps, micro-slice (PB-xxx-Cn), RED/GREEN/REFACTOR, WBS, derived progress, DoD (14 boxes), lifecycle state (Designed→…→Released), developer guide, gap sequence (Finding → Architecture Decision → RED → GREEN → Certification). |
| **Upstream / Downstream** | Downstream of Project Governance and BC-4; upstream of BC-2. |
| **Policies** | Lifecycle ⊥ progress (never conflated). Progress is a pure function of WBS — structurally unsettable by hand. Step order enforced (no GREEN without RED evidence; no PR without gate verdicts; no Done without 14/14 + developer guide). ER-01: on architecture drift, STOP and request ADR review. |

### BC-4 Verification & Evidence

| Aspect | Content |
|---|---|
| **Responsibilities** | Owns the fitness-function catalog (incl. RED-first falsifiability proof per function), the immutable ledger of gate verdicts and evidence, the verification matrix, and traceability links (ticket→IDD→commit→test→DoD). Coordinates guard execution — but per PGP-03 the guards themselves are hosted by the contexts that own the rules (knowledge-lint in BC-1; CI-5's guard in the software's constitutional suite). Records **verdicts as facts**; owns no foreign rules. Preserves all evidence, immutably. |
| **Ownership** | Platform. |
| **Consumers** | Adversarial Review Support (ER-02: certification consumes verification evidence, never claims), Implementation Guidance (gates in the workflow), humans (ARB reads the matrix). |
| **Dependencies** | Upstream: Project Governance (gate classes, verification-matrix pattern); the software's executable check suites (PHPStan greenfield gate, `tests/Architecture`, knowledge-lint, future Deptrac/Infection) as **observed evidence sources**. |
| **Published Language** | Gate Verdict, Evidence Record, Verification Matrix. |
| **Ubiquitous Language** | fitness function, property ("properties, never class names"), falsifiability, verification matrix, gate, gate class (Capability / Architecture / Engineering-Improvement), verdict, evidence record, traceability chain, honesty invariant. |
| **Upstream / Downstream** | Upstream (OHS/PL) of BC-3 and BC-5 for evidence. |
| **Policies** | **Honesty Invariant:** a verdict without executable-output provenance cannot exist. Verdicts are immutable; re-runs create new verdicts. No fitness function is active without a recorded RED run (falsifiability proven — mirrors `Messaging_Architecture_Verification.md`). |

### BC-5 Adversarial Review Support

| Aspect | Content |
|---|---|
| **Responsibilities** | Owns adversarial review **work products** — the analysis, never the verdict. Findings, rejection attempts, certification *recommendations* (`authority: generated`). Does NOT own certification (ARB-only) or the artifacts under review. |
| **Ownership** | Platform; the review **protocol** (e.g. the 7 reuse questions, attempt-to-reject method per `PB-003_Architecture_Readiness_Report.md`) is dictated by its Customer (ARB). |
| **Consumers** | ARB (Customer–Supplier: ARB dictates protocol), Chief Architect (IDD architecture reviews). |
| **Dependencies** | Consumes only **published** artifacts (via BC-1) and **verification evidence** (via BC-4). **Deliberate constraint:** no shared kernel and no back-channel with Design & Decision Support — the checker must not share the producer's internal model (separation of duties; the strongest argument these are two contexts). |
| **Published Language** | Review report, Finding, Recommended Verdict. |
| **Ubiquitous Language** | review pass, finding, severity, evidence ref, attempt-to-reject, rejection-attempt record, 7 reuse questions, certification recommendation, objection, residual risk (R-n) vs remediated finding (F-n). |
| **Upstream / Downstream** | Downstream of BC-1/BC-4; Supplier to Human Authority. |
| **Policies** | Every finding cites evidence (ER-02). Every protocol item records an explicit rejection attempt — an "all clear" without documented attempts is invalid. Producer ≠ reviewer (structural). "Certified" is unreachable inside this context — it exists only as an observed ARB event. |

### BC-6 Design & Decision Support

| Aspect | Content |
|---|---|
| **Responsibilities** | Owns decision **drafts** and capability **models** before approval — the "discover" duty. Observes approved ADRs, the Decision Log, certified capabilities (owned by Project Governance). Does NOT own the APPROVED state: a draft becomes an ADR only through a human decision event; the platform then downgrades its copy to an observation. |
| **Ownership** | Platform; the Platform Capability Pattern template (12 sections) is frozen upstream. |
| **Consumers** | Chief Architect and ARB (Customers of drafts); Implementation Guidance (IDD architecture sections trace to decisions); Knowledge Governance (drafts registered as `generated`). |
| **Dependencies** | Upstream: Project Governance (ADR discipline: one decision per ADR; ER-05 convergence; PGP vocabulary). |
| **Published Language** | Decision draft, capability model draft, convergence assessment. |
| **Ubiquitous Language** | decision, ADR, option, consequence, PROPOSED→REVIEW, capability, ownership matrix (Owns/Coordinates/Preserves/Observes/Does-NOT-own), invariant catalog taxonomy (Platform/Infrastructure/Constitutional/Business/Operational), extension model, convergence (ER-05), granular freeze. |
| **Upstream / Downstream** | Downstream of Project Governance; Supplier to Human Authority; **Separate Ways** from BC-5. |
| **Policies** | Exactly one decision per draft; ≥2 options with consequences; every draft carries an ER-05 convergence note (complexity delta); capability models validate exactly-one-owner per responsibility row at construction. |

### External Domain A — Project Governance (upstream, non-software)

The corpus of frozen/authoritative project artifacts: ER-01..05, PGP-01..05, Implementation Process v1.0, the EKP specification, the Platform Capability Pattern, CI-1..CI-5, approved ADRs (ADR-001.., ADR-T1..20, ADR-MP-01..05), the Decision Log (D-01..). Modeled as an **upstream Open Host Service with a Published Language** — the documents *are* the published language. Every platform context is a **Conformist** to it, deliberately without an anticorruption layer: the entire point is that the platform speaks the project's governance language natively. An ACL here would be a design smell — a licence to privately reinterpret frozen rules.

### External Domain B — Human Authority (ARB · Chief Architect · Sponsor)

Not a bounded context (no software model to own) and more than an actor. Modeled three ways at once:

1. **Authoritative event source** — humans emit the only authoritative facts in the domain: `ADRApproved`, `IDDApproved`, `CapabilityCertified`, `CertificationRefused`, `ArtifactFrozen`, `MergeApproved`, `ConstitutionalIncidentResolved`. Platform contexts are Conformists to these events.
2. **Customer–Supplier** — with the platform as **Supplier**: humans, as Customers, dictate the shape of deliverables (review protocol, ADR format, IDD sections).
3. **The Authority Boundary policy** — a domain policy enforced in every context: *no artifact transitions from `generated`/`provisional` toward `authoritative`, and no lifecycle gate is passed, without a referenced human decision event.* This is the platform's own constitutional invariant — the AI-domain analogue of CI-5.

---

## 3. Context Map

```
                    PROJECT GOVERNANCE  (External Domain A — upstream)
         ERs · PGPs · Frozen Process v1.0 · EKP spec · CI-1..5 · approved ADRs
                        [ Open Host Service / Published Language ]
          │ Conformist     │ Conformist      │ Conformist     │ Conformist
          ▼                ▼                 ▼                ▼
   ┌──────────────┐ ┌───────────────┐ ┌──────────────┐ ┌──────────────┐
   │ BC-6 Design &│ │ BC-3 Implemen-│ │ BC-4 Verifi- │ │ BC-1 Knowledge│──OHS/PL──┐
   │ Decision Sup.│ │ tation Guid.  │ │ cation & Ev. │ │ Governance    │          │
   └──────┬───────┘ └───────▲───────┘ └──────┬───────┘ └──────▲───────┘          ▼
          │ Supplier        │ gate verdicts  │ evidence       │ published  ┌──────────────┐
          │ (drafts)        │ (OHS/PL)       │ (OHS/PL)       │ artifacts  │ BC-2 Session │
          ▼                 │                ▼                │            │ Continuity   │
   HUMAN AUTHORITY ◄──Supplier──── ┌──────────────────────┐ ──┘            └──────▲───────┘
   (External Domain B:             │ BC-5 Adversarial     │      active ticket +  │
    ARB · Chief Architect ·        │ Review Support       │      single next      │
    Sponsor)                       └──────────────────────┘      action (C-S) ────┘
          │
          │  authoritative decision events
          │  (ADRApproved · IDDApproved · CapabilityCertified · ArtifactFrozen · MergeApproved)
          └────────────► ALL platform contexts (Conformist to decisions)

   BC-6 ◄— Separate Ways —► BC-5   (separation of duties: discover ≠ check;
                                    Review sees only *published* artifacts)
```

Relationship inventory:

| Upstream | Downstream | Strategic pattern | Note |
|---|---|---|---|
| Project Governance | all six platform contexts | **OHS/PL + Conformist** | The critical modeling answer: the AI platform is **downstream and conformist** to ADRs, EKP, and the Implementation Process. It never translates or reinterprets them. |
| Human Authority | all platform contexts | **Customer–Supplier (humans = Customer) + authoritative event feed** | Platform supplies drafts/evidence; humans supply decisions. |
| BC-1 Knowledge Governance | BC-2, BC-3, BC-5, BC-6 | **OHS/PL** | Cards, packages, graph. |
| BC-4 Verification & Evidence | BC-5 | **OHS/PL (Customer–Supplier flavour)** | ER-02: certification consumes executable evidence only. |
| BC-4 | BC-3 | **OHS/PL** | Gate verdicts advance/block workflow steps. |
| BC-3 | BC-2 | **Customer–Supplier** | Active ticket + single next action feed the snapshot. |
| BC-6 | BC-5 | **Separate Ways (deliberate)** | No shared model, no direct integration; encodes discover ≠ check. |
| Shared Kernel | — | **Identifier micro-kernel only** | `knowledge_id`, ticket id, ADR id, gate id + status/authority enums. Nothing behavioural — a larger kernel would recreate the god-context. Mirrors ADR-T16: identity crosses as strings; each context reconstructs its local VO. |
| Software's constitutional suite (CI-5/Q7 guard) | BC-4 | **Observes only** | BC-4 records `ConstitutionalGuardTripped` as an observed fact and escalates. The platform has **zero write-path toward constitutional guards**. |

---

## 4. Aggregates

Project artifacts (ADR, IDD, Ticket, Knowledge content) are **never platform aggregates** — the platform holds opaque references only (VOs), mirroring ADR-T16. Making them aggregates would steal ownership from the process/docs (PGP-02 violation).

### BC-1 Knowledge Governance
- **KnowledgeItem** (root) — identity `knowledge_id`. VOs: KnowledgeCard, Authority, Status, TypedRelationship, CodeRef. Invariants: single authoritative item per topic · status transitions follow the EKP state machine · status and authority vary independently but illegal combinations are rejected (e.g. `frozen`+`generated`) · `generated` promotes only with a referenced human decision event · supersession creates a new item and demotes the old to `historical` — never edits it.
- KnowledgePackage — **not an aggregate** (ARB ruling R-2, 2026-07-07): a curated documentation manifest (ordered `knowledge_id` refs + task type), validated by lint (all refs resolve, no archived items). Documentation is not a domain.
- KnowledgeGraph — **not an aggregate**: a Generated read model, rebuilt from items.

### BC-2 Session Continuity
- **Session** (root) — entities: SessionLogEntry. VOs: SessionId, WorkstreamRef, TicketRef. Invariants: at most one active session per workstream · archived session immutable · entries timestamped, append-only.
- **ContextSnapshot** (root) — VOs: ActiveTicketRef, **SingleNextAction**, StableFact (mandatory repo provenance). Invariants: exactly one SingleNextAction · a fact without provenance cannot enter the snapshot · snapshot references the plan step it points at.
- BootstrapPayload — **VO**, assembled from ContextSnapshot + a KnowledgePackage; never stored as truth.

### BC-3 Implementation Guidance
- **ImplementationPlan** (root, one per ticket) — entities: WbsItem, MicroSlice (PB-xxx-Cn). VOs: StepChecklist (15), DoDChecklist (14), LifecycleState, DerivedProgress. Invariants: progress is a pure function of WBS completion — **the aggregate exposes no setter for progress** ("never hand-written" made structural) · lifecycle ⊥ progress · step order enforced (no GREEN without RED evidence ref; no PR step without all gate verdicts) · each MicroSlice references a compile+pass verdict before the next opens · DoD box "developer guide" blocks Done like any other.
- TicketRef, IddRef — **VOs only** (observed artifacts).
- CoachingIntervention — **VO** (advisory text + rule ref + evidence ref); deliberately not an entity — guidance has no lifecycle to protect.

### BC-4 Verification & Evidence
- **FitnessFunction** (root) — VOs: PropertyStatement (a property, never a class name — checked structurally), ExecutableCheckRef, FalsifiabilityProof (a recorded failing run). Invariants: not `active` without a recorded RED run · the check is a runnable artifact ref, not prose.
- **GateVerdict** (root, immutable fact) — VOs: GateId, GateClass, Result, EvidenceRef (raw check output). Invariants: immutable once recorded · **a verdict without an EvidenceRef cannot be constructed** (Honesty Invariant as aggregate rule) · re-runs create new verdicts.
- **TraceabilityChain** (root) — entities: TraceLink (ticket→IDD→commit→test→DoD). Invariants: no orphan link · a "complete" claim requires every mandated hop · broken chains raise events, never silently repair.
- VerificationMatrix — Generated read model over verdicts, per capability.

### BC-5 Adversarial Review Support
- **AdversarialReview** (root) — entities: Finding (severity, mandatory EvidenceRef — a finding without evidence cannot be added, ER-02). VOs: TargetArtifactRef, ReviewProtocol, RejectionAttemptRecord, RecommendedVerdict (tagged `generated`). Invariants: ≥1 explicit rejection attempt per protocol item · producer ≠ reviewer enforced at construction (target's producer session ≠ reviewing session) · "certified" is not a reachable state — certification exists only as an observed ARB event.

### BC-6 Design & Decision Support
- **DecisionDraft** (root) — VOs: DecisionStatement (**exactly one decision** — invariant), Option (≥2 required), Consequence, ConvergenceNote (ER-05: states the complexity delta), DraftStatus (PROPOSED→REVIEW only; APPROVED unreachable — approval is an observed external event that closes the draft and registers an observation of the real ADR).
- **CapabilityModel** (root) — VOs per the 12-section pattern: OwnershipMatrix (every row names exactly one owner — PGP-02 as constructor validation), InvariantCatalog (every invariant classified Platform/Infrastructure/Constitutional/Business/Operational **and names its guard host** — PGP-03), ExtensionModel, PlannedFitnessFunction (each marked RED-first). Invariant: all 12 sections present before status REVIEW.

---

## 5. Domain Events

Events are immutable, past-tense, **evidence-bearing** (each carries the refs that make it checkable), and the *only* integration mechanism between platform contexts besides published languages — no context reaches into another's aggregates.

### Authoritative events (produced by Human Authority; consumed by everything)

| Event | Producer | Key consumers |
|---|---|---|
| `ADRApproved` / `ADRRejected` | Chief Architect / ARB | BC-6 (closes draft), BC-1 (authority promotion), BC-3 (unblocks ER-01) |
| `IDDApproved` | Chief Architect | BC-3 (step-3 gate), BC-4 (traceability) |
| `CapabilityCertified` / `CertificationRefused` | ARB | BC-6, BC-1, BC-4 (matrix) |
| `ArtifactFrozen` / `FreezeAmended` | ARB via ADR | BC-1 (status), all contexts (conformance) |
| `MergeApproved` | Chief Architect / human reviewer | BC-3 (step 15), BC-4 (chain closure) |
| `ConstitutionalIncidentResolved` | Sponsor / ARB | BC-4, BC-2 |

### Platform events

| Event | Producing context | Consuming contexts |
|---|---|---|
| `KnowledgeCaptured` · `KnowledgeSuperseded` · `AuthorityPromoted` | BC-1 | BC-2 (package refresh), all |
| `KnowledgeLintViolationDetected` (orphan/cycle/dual-authority) | BC-1 | BC-3 (blocks doc-dependent steps), humans |
| `PackagePublished` | BC-1 | BC-2 |
| `SessionStarted` · `NextActionRecorded` · `SessionArchived` | BC-2 | BC-1 (log registered Historical), auditors |
| `ContextRehydrated` | BC-2 | — (audit fact) |
| `PlanDrafted` · `StepCompleted` · `MicroSliceCompleted` · `DoDBoxSatisfied` · `ProgressDerived` | BC-3 | BC-2, BC-4 (traceability), humans |
| `GatePassed` / `GateFailed` | BC-4 | BC-3 (advance/block), BC-5 (evidence pool), BC-2 |
| `FitnessFunctionFalsified` (RED proven) | BC-4 | BC-6, BC-5 |
| `TraceabilityBroken` | BC-4 | BC-3 (blocks Done), humans |
| `ConstitutionalGuardTripped` (observed, e.g. CI-5/Q7) | BC-4 (as observer) | **Escalation only:** BC-2 halts the session, humans notified. **No machine consumer may resume or retry** (mirrors Blueprint §F9: constitutional breach = permanent, dead-letter + escalation, never auto-retried). |
| `FindingRaised` · `RejectionAttempted` · `ReviewCompleted` · `CertificationRecommended` | BC-5 | ARB (via published artifacts), BC-1 |
| `DecisionDrafted` · `CapabilityModeled` · `ConvergenceAssessed` | BC-6 | BC-1 (registered as `generated`), humans |

---

## 6. Governance Model

Three enforcement tiers, aligned with the project's existing gate classes (Implementation Process v1.0: Capability / Architecture / Engineering-Improvement gates):

### Tier 1 — Blocking gates (executable; cannot be argued past)
1. **Authority Boundary** — promotion of any `generated` artifact toward `authoritative` without a referenced human decision event is impossible by aggregate construction, plus lint as guard.
2. **Honesty Invariant** — a GateVerdict or progress figure without executable-output provenance cannot be recorded.
3. **Step ordering** — GREEN without RED evidence, PR without gate verdicts, Done without complete traceability + 14/14 DoD: blocked in ImplementationPlan.
4. **Knowledge integrity** — single-authority, orphan, cycle violations (knowledge-lint, hosted by BC-1 per PGP-03).
5. **Separation of duties** — producer = reviewer on an adversarial review: rejected at construction.
6. **Constitutional halt** — `ConstitutionalGuardTripped` hard-halts the active session and escalates; structurally no retry path.
7. **Action-space asymmetry** — the platform has read-only observation of constitutional guards and **zero authority** to modify constitutional invariants, their guards, or anonymity-relevant code paths without an ARB-routed decision. (This is a gate on the *platform's own* action space, not on the software.)

### Tier 2 — Non-blocking reminders (facts surfaced; work continues)
Stale ContextSnapshot (older than the last repo change it references) · pending DoD boxes · `provisional` knowledge about to be used in a gate · missing developer-guide draft mid-ticket · `TraceabilityBroken` on non-active tickets · discipline-sequence tripwire (Business → DDD → Architecture → Tests → Implementation about to be skipped).

### Tier 3 — Advisory guidance (judgment offered; never enforced)
TDD coaching · refactoring suggestions · ER-05 convergence advisories (complexity-trend commentary) · capability-reuse hints (ARR-gate prompts: "consume unchanged?") · "consider an ADR" prompts. **Advice, too, must cite its grounds** — a CoachingIntervention is a VO carrying rule ref + evidence ref.

### How this prevents accidental violation of…
- **DDD** — discipline tripwire (Tier 2) + the gap sequence (Finding → Architecture Decision → RED → GREEN → Certification) encoded as step-order invariants (Tier 1); BC-6 refuses capability models with ambiguous ownership.
- **Architecture** — ER-01 as policy: drift detected against frozen artifacts → STOP + ADR request; ARR-gate questions surfaced whenever a ticket touches a frozen Platform Capability.
- **Auditability** — everything the platform does emits evidence-bearing events into an append-only record; nothing preserved is rewritable.
- **Security / election integrity** — action-space asymmetry (Tier 1 #7) + constitutional halt (Tier 1 #6); the platform can never weaken a guard it does not own.
- **Knowledge governance** — quarantine-by-default (`generated`), lint as blocking gate, Authority Boundary.

---

## 7. AI Responsibility Matrix

| Activity | AI decides | AI recommends | Human approval | ARB-only | Sponsor-only |
|---|:---:|:---:|:---:|:---:|:---:|
| Assemble Knowledge Package / bootstrap payload | ✔ | | | | |
| Run lint, gates, fitness functions; record verdicts | ✔ | | | | |
| Derive progress from WBS | ✔ | | | | |
| Write RED test, implement GREEN, refactor **within an approved IDD micro-slice** | ✔ | | | | |
| Draft ADR / capability model / IDD sections | | ✔ (`generated`) | | | |
| Approve ADR | | | Chief Architect / ARB | | |
| Approve IDD (step 3) | | | Chief Architect | | |
| Merge PR | | drafts PR | ✔ | | |
| Adversarial review pass (analysis, findings) | | ✔ (`generated`) | | | |
| Certify a capability (D-11 gate) | | recommends | | ✔ | |
| Amend a Frozen artifact / re-version the process | | drafts ADR | | ✔ | |
| Promote knowledge `generated` → `authoritative` | | ✔ | ✔ (owner) | | |
| Define a new fitness function | | ✔ | ✔ (rule owner) | | |
| Change a constitutional invariant (CI-1..5) | | | | ✔ | ✔ (value judgment) |
| Handle a constitutional incident | escalates only | | | ✔ | ✔ |
| Declare a ticket "Done" | computes evidence | | ✔ (gates + sign-off) | | |
| Change the platform's own ownership matrix | | drafts | | ✔ | |

**The pattern:** the AI **decides** wherever the outcome is a *derived fact of an executable check*; it **recommends** wherever the outcome is a *judgment*; humans hold every gate where **authority, value, or entrenchment** changes.

---

## 8. Architecture Fitness Functions for the Platform Itself

All executable and falsifiable; none is a synthetic score (Phase 1 honesty criterion). Each must prove falsifiability (detect a synthetic violation) before counting as active — the same bar as `Messaging_Architecture_Verification.md`.

| # | Fitness function | Executable verification |
|---|---|---|
| FF-1 | **Authority integrity** | Graph query: zero items `authority: authoritative` lacking a reference to a human decision event. |
| FF-2 | **Single authority** | knowledge-lint: zero topics with two authoritative items. |
| FF-3 | **Honesty of verdicts** | Ledger scan: 100% of GateVerdicts resolve their EvidenceRef to an existing executable-output artifact. |
| FF-4 | **Falsifiability coverage** | Catalog scan: zero active fitness functions without a recorded RED run. |
| FF-5 | **Derived progress only** | Regenerate progress from WBS; diff against every reported figure; zero mismatches. |
| FF-6 | **Traceability completeness** | For every merged ticket: chain ticket→IDD→commits→tests→DoD resolves with zero missing hops. |
| FF-7 | **Separation of duties** | Metadata check: zero reviews where producer session id = reviewer session id. |
| FF-8 | **Evidence-bearing findings** | Schema check: zero Findings without evidence ref; zero reviews without ≥1 recorded rejection attempt. |
| FF-9 | **No external runtime** | Dependency/permission-manifest check against the allowlist (Phase 1 §10 goal, directly executable). |
| FF-10 | **Graph hygiene** | knowledge-lint: zero orphans, zero relationship cycles. |
| FF-11 | **Continuity discipline** | Lint: every ContextSnapshot has exactly one SingleNextAction; every StableFact has a resolvable provenance ref. |
| FF-12 | **Immutability of the historical record** | Hash check: archived sessions, verdicts, superseded items byte-identical to recorded digests. |
| FF-13 | **Convergence (ER-05)** | Chosen complexity metrics (file count, capability count, rule count, dependency count) non-increasing per iteration unless an ADR authorises the increase — a diff of measured values, not an opinion. |

---

## 9. Alternative Decompositions Considered (and rejected)

**Alternative A — Minimal (3 contexts):** Knowledge & Continuity / Engineering Execution (guidance + verification + review) / Decision Support.
*Rejected:* fuses verification and certification into one model, directly contradicting D-11's declared linguistic boundary; puts the adversarial checker inside the workflow it checks (owner-checks-itself — violating separation of duties and the spirit of PGP-03); merging Session Continuity into Knowledge ignores a real rate-of-change and lifecycle boundary. The god-context in disguise.

**Alternative B — Fine-grained (9–10 contexts, the brief's candidate list as-is):**
*Rejected:* TDD Coaching, Testing Guidance, and Release Governance share the 15-step workflow's vocabulary almost completely — separate contexts would constantly translate terms they already share (the classic sign of a false boundary). Documentation Governance has no language of its own beyond the knowledge card. Splitting Architecture Fitness from Quality Gates cuts one D-11 "verification" language in two. Ten contexts also fail ER-05 pragmatically: more contexts than distinct languages is complexity without payoff.

**Recommendation — six contexts + two external domains**, because every boundary corresponds to a boundary the project's constitution already draws in language (EKP · session practice · frozen process · D-11 verification · D-11 certification · the discover-duty), every context has exactly one ownable responsibility (PGP-02), every guard is hosted by its rule's owner (PGP-03), and the platform as a whole sits **downstream and conformist** to project governance — precisely the property that makes it safe.

---

*Next artifact: `Phase-02.5-Certification-Plan.md` (deliverable 10 — the capability model, expressed with the project's Platform Capability Pattern discipline). Phase 3 (implementation design of the `.claude` platform) begins only after ARB approval of this model.*
