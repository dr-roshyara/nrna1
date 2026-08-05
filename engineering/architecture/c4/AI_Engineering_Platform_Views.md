# AI Engineering Platform — Architecture Views

**Class:** documentation only — **frozen artifacts win on conflict** (same rule as all `c4/` views). Authority: ADR-AIP-01 Baseline corpus, `Implementation_Process_v1.1_Draft.md`, `registry.yaml`.
**Status:** views document (ARB-proposed diagrams, 2026-07-08, two corrections applied — see notes). Promotion to *"PublicDigit AI Engineering Architecture v1.0"* is an ARB/ADR act, earliest post-PB-004.

## 0. Strategic DDD — where the platform sits in the ecosystem *(the first thing a new architect should see: the platform is not the product)*

```mermaid
graph TD
  PD[PublicDigit Ecosystem]
  PD --> CORE["CORE DOMAIN<br/>Election System<br/>(the only reason everything else exists)"]
  PD --> SUP1["Supporting Subdomain<br/>AI Engineering Platform"]
  PD --> SUP2["Supporting Subdomain<br/>Build Infrastructure"]
  PD --> GEN1["Generic Subdomain<br/>Git"]
  PD --> GEN2["Generic Subdomain<br/>Database"]
  PD --> GEN3["Generic Subdomain<br/>CI/CD"]
  style CORE fill:#fff4c2
```

## 0b. How an engineering request flows *(the whole process in one picture)*

```mermaid
flowchart LR
  PR[Problem] --> ERR[EP-03<br/>Readiness Review] --> PL[EP-01<br/>Planning] --> AP[Human<br/>Approval] --> IM[Implementation] --> VF[Verification] --> CR[EP-02<br/>Completion Review] --> FT[PublicDigit<br/>Feature] --> EV[Evidence] --> RT[Retrospective] --> ES[Engineering<br/>Standards]
  ES -.->|govern the next request| ERR
```

## 1. The central engineering loop *(the provider is not visible — by design)*

```mermaid
graph TD
  A[Engineering Standards] --> B[Engineering Process]
  B --> C[PublicDigit Product]
  C --> D[Evidence]
  D --> E[Retrospective]
  E --> F[Continuous Evolution]
  F --> A
```

## 2. Provider-independent stack

```mermaid
graph TD
  S[Engineering Standards] --> P[Engineering Process]
  P --> PL[AI Engineering Platform]
  PL --> PD[PublicDigit]
  PL --> PB[Provider Binding]
  PB --> CC[Claude Code — today]
  PB --> CG[Gemini]
  PB --> CX[Codex]
  PB --> CP[Copilot]
```

The provider is replaceable; the platform is not.

## 3. Bounded contexts — the real context map *(CORRECTED: not a linear chain; relationships per the frozen Phase-02 map. "Configuration" is a component — the composition root — not a bounded context.)*

```mermaid
graph TD
  PG[Project Governance<br/>external upstream — OHS/PL] -->|Conformist| KG[Knowledge Governance]
  PG -->|Conformist| IG[Implementation Guidance]
  PG -->|Conformist| VE[Verification & Evidence]
  PG -->|Conformist| DS[Design & Decision Support]
  KG -->|OHS/PL: cards, packages| SC[Session Continuity]
  KG -->|published artifacts| RS[Adversarial Review Support]
  IG -->|active ticket, next action| SC
  VE -->|gate verdicts| IG
  VE -->|evidence only — ER-02| RS
  DS -.->|SEPARATE WAYS<br/>discover ≠ check| RS
  HA[Human Authority<br/>ARB · Chief Architect · Sponsor] -->|authoritative decision events| PG
  DS -->|drafts, Supplier| HA
  RS -->|recommendations, Supplier| HA
```

## 3b. Domain model — what lives inside the contexts *(per the frozen Phase-02 §4 aggregates — CORRECTED vs. the ARB sketch: KnowledgePackage is a documentation manifest, not an aggregate (R-2); ADRs/Tickets/IDDs are OBSERVED project artifacts held as VO references (ADR-T16 discipline), never platform aggregates; KnowledgeHarvest is a Think-layer candidate, not yet in the frozen model)*

```mermaid
graph TD
  KG[Knowledge Governance] --> KI[KnowledgeItem ⬢]
  KG -.-> KP[KnowledgePackage — manifest, not aggregate]
  KG -.-> KH[KnowledgeHarvest — candidate, Think layer]
  SC[Session Continuity] --> SES[Session ⬢] & CS[ContextSnapshot ⬢<br/>exactly one SingleNextAction]
  SC -.-> BP[BootstrapPayload — VO]
  IG[Implementation Guidance] --> IP[ImplementationPlan ⬢<br/>WbsItem · MicroSlice entities<br/>progress = derived, no setter]
  IG -.-> TR[TicketRef · IddRef — VO refs, observed]
  VE[Verification & Evidence] --> FF[FitnessFunction ⬢<br/>RED falsifiability required] & GV[GateVerdict ⬢<br/>immutable, evidence-required] & TC[TraceabilityChain ⬢]
  VE -.-> VM[VerificationMatrix — read model]
  RS[Adversarial Review Support] --> AR[AdversarialReview ⬢<br/>Finding entities, evidence mandatory<br/>producer ≠ reviewer]
  DS[Design & Decision Support] --> DD[DecisionDraft ⬢<br/>one decision · APPROVED unreachable] & CM[CapabilityModel ⬢<br/>exactly-one-owner validation]
  DS -.-> ADR[ADRs — observed references, owned by the project]
```

⬢ = aggregate root. Dotted = not an aggregate (VO, manifest, read model, observed reference, or candidate).

## 3c. Why DDD? *(everything starts with business — never with AI)*

```mermaid
graph LR
  B[Business] --> D[DDD] --> ES[Engineering Standards] --> EP[Engineering Process] --> EA[Engineering Architecture] --> PB[Provider Binding] --> CR[Claude Runtime — today]
```

## 4. Runtime loading order *(knowledge before rules)*

```mermaid
graph LR
  Configuration --> Registry --> Knowledge --> Rules --> Hooks --> Commands --> Agents --> Execution
```

## 5. Registry architecture *(configuration-driven: abstraction → realization → evidence)*

```mermaid
graph TD
  C[Component CMP-nnn<br/>versioned abstraction] --> I[Implementation]
  I --> A[Runtime Asset AST-nnn]
  A --> V[Verification — VERIFY evidence]
  V --> AD[Adoption: adopted / planned / deprecated]
  I --> Script & Hook & Command & Agent
```

## 6. Engineering Process lifecycle

```mermaid
graph TD
  ERR[EP-03 Engineering Readiness Review<br/>derive first, ask only gaps] --> PLAN[EP-01 Planning Stage]
  PLAN --> APPROVAL[Human Approval<br/>applies to the PLAN, not the task]
  APPROVAL --> IMPLEMENT[Implementation<br/>approved plan only]
  IMPLEMENT --> VERIFY[Verification<br/>measuring instruments, no interpretation]
  VERIFY --> REVIEW[EP-02 Completion Review<br/>did we build the approved plan?]
  REVIEW --> DONE[Done]
  IMPLEMENT -.->|plan invalidated: STOP| PLAN
```

## 7. Engineering Readiness — nine domains (frozen at nine)

```mermaid
mindmap
  root((Engineering<br/>Readiness))
    Business
    DDD
    Architecture
    Process
    TDD
    Design
    Impact
    Verification
    Completion
```

## 8. DDD's position in engineering

```mermaid
graph LR
  BP[Business Problem] --> SD[Strategic DDD] --> TD[Tactical DDD] --> AR[Architecture] --> T[TDD] --> IM[Implementation] --> VF[Verification]
```

## 9. Knowledge Harvest lifecycle *(CORRECTED: Pattern Evidence Register, not "Evidence Log")*

```mermaid
graph LR
  SRC[External Source] --> KH[Knowledge Harvest<br/>aggregate: Source·Patterns·Evidence·Decision]
  KH --> PC[Pattern Cards EPC-nnn]
  PC --> ER[Pattern Evidence Register]
  ER --> RT[Retrospective]
  RT --> DEC[Decision: adopt / reject]
  DEC --> ES[Engineering Standard]
```

## 10. PublicDigit ecosystem *(the platform is never the Core Domain)*

```mermaid
graph TD
  PD[PublicDigit Ecosystem] --> CORE[Core Domain<br/>Election System]
  PD --> SUP[Supporting Subdomain<br/>AI Engineering Platform]
  SUP --> ST[Standards] & PR[Process] & RG[Registry] & VF2[Verification] & PB2[Provider Binding]
```

## 10b. Evidence View *(added 2026-07-10 — evidence is a first-class concern with its own loop; implemented-truth vocabulary only)*

```mermaid
graph LR
  CHK[Executable checks<br/>PHPStan gate · Architecture suite · knowledge-lint · gate runner] --> VD[Verdicts<br/>evidence-bearing, immutable — R-26]
  RV[Reviews & Completion Reviews<br/>findings with evidence refs] --> ER
  PI[Product reality<br/>incidents · retrospective observations] --> ER
  VD --> ER[Evidence Records<br/>Pattern Evidence Register · session logs · ledgers]
  ER --> RT[Retrospective<br/>human judgment over accumulated evidence]
  RT --> PM[Promotion / Rejection / Deletion<br/>via the governed chain]
  PM --> ST[Standards & Process<br/>govern the next request]
  ST -.-> CHK
```

Evidence has many sources and one destination: a human decision. No metric ledger appears at this level — **evidence is architecture; metrics are implementation** (one way of producing evidence, replaceable like any adapter).

## 11. What the provider actually is

```mermaid
graph TD
  ES2[Engineering Standards] --> EP2[Engineering Process] --> PL2[AI Engineering Platform] --> PB3[Provider Binding] --> CR[Claude Code Runtime — today] --> SRC2[PublicDigit Source Code]
```

The provider is the runtime at the bottom of a governed engineering stack — never the center of the system.

## 12. Addendum — the constitutional layer (added 2026-07-12; this file predates it entirely)

*(Evidence-reconstruction finding: this document contains zero references to ES-001..006, the Engineering Decision Model, the EEP, or the rulings register beyond R-35 — it was written 2026-07-08, before the 2026-07-11 constitutional consolidation. Added as an addendum, not a rewrite, per this file's own rule: "frozen artifacts win on conflict.")*

```mermaid
graph TD
  ES[Engineering Standards<br/>ES-001..ES-006] --> DM[Engineering Decision Model<br/>8 decisions, DRAFT]
  DM --> EEP[Engineering Execution Protocol<br/>Adopted · Stable]
  EEP --> RB[Runtime Binding<br/>.claude/CLAUDE.md — frozen pointer]
  RB --> RT[Runtime Hooks & Merge Gates<br/>see developer_guide/ai_platform/03_runtime_mechanics.md]
  RT --> AG[Architecture Governance<br/>ARB · ADR · EP-02/EP-03]
  AG --> EV[Operational Evidence]
  EV -.->|retrospective, R-38 conceptual freeze| ES
```

This sits between §2 (Provider-independent stack) and §11 (What the provider actually is) in the reading order: the Decision Model and ES-standards are what "Engineering Standards" in §1/§2 concretely *is*, as of 2026-07-11. The Registry architecture (§5, CMP/AST) and this constitutional layer coexist — the registry governs `.claude` runtime assets; ES-001..006 govern the engineering process and its decisions. Neither absorbed the other.

---

*Corrections applied vs. the ARB draft (2026-07-08): §3 redrawn from a linear chain to the frozen Phase-02 relationships (OHS/PL, Conformist, Separate Ways, Human Authority as event source); §9 renamed to Pattern Evidence Register. §12 added 2026-07-12 (constitutional layer, post-dates original). On any conflict, the frozen Baseline corpus and the Implementation Process win over these views.*
