# Architecture Knowledge Transfer (AKT) — Part 2

**Programme Architecture**

| | |
|---|---|
| **Artifact** | Architecture Knowledge Transfer (AKT) — **Part 2 of 10 + Appendix** |
| **Part** | **Part 2 — Programme Architecture** |
| **Baseline** | **PKS Phase III — Operational Validation** · governance baseline **SDM v1.2 / EOP v1.2 FROZEN** · product architecture **PushB Architecture Blueprint v1.0 FROZEN (2026-07-06, IMMUTABLE)** |
| **Version** | 1.0 |
| **Status** | **Living Architecture Reference.** *Lifecycle: **AUTHORED**, not ISSUED* |
| **Audience** | AI Architects · Principal Engineers · Architecture Review Board |
| **Authored** | 2026-08-01 · branch `feature/pb003` · HEAD `622c515d4` |
| **Prerequisite** | **Part 1 — Programme Overview & Architectural Context.** This Part assumes it |

---

## 0. Scope of this Part

Part 1 established *what the programme is and what philosophy governs it*. **Part 2 states the architecture itself** — the long-term structure of all three assets, the relationships between them, the bounded contexts, and the repository philosophy that places everything.

**Boundary of this Part, observed strictly:**

| In scope | Out of scope (and where it lives) |
|---|---|
| Architectural structure · boundaries · ownership · dependency direction · integration contracts · layering · enforcement perimeter | Individual decisions and their rationale → **Part 5** · coding conventions and idioms → **Part 7** · slice-by-slice status → **Part 8** |

**Everything in §2 is grounded in code or in a frozen artifact.** Where this Part records an observation of its own, it is marked **`OBSERVATION`** and is **routed, never enacted** — per Part 1 §5.4.

---

## 1. The three-asset architecture (programme level)

```
┌────────────────────────────────────────────────────────────────────────────────────┐
│  PROGRAMME ARCHITECTURE                                                            │
│                                                                                    │
│  ┌──────────────────────┐   defines    ┌────────────────────────────────────────┐  │
│  │    KnowledgeOS       │─────────────▶│  reusable engineering governance       │  │
│  │  (Engineering        │              │  ES-001..006 · AIP · PD · FF · rulings │  │
│  │   Platform · v0.1    │              │  SDM/EOP · review framework            │  │
│  │   NOT STARTED)       │              └────────────────────┬───────────────────┘  │
│  └──────────────────────┘                                   │ governs              │
│             ▲                                               ▼                      │
│             │ refinement                    ┌──────────────────────────────────┐   │
│             │ (evidence-gated)              │        PublicDigit               │   │
│             │                               │  the PRODUCT · and the           │   │
│             │                               │  REFERENCE IMPLEMENTATION and    │   │
│             │                               │  EVIDENCE GENERATOR              │   │
│             │                               │  = the VALIDATION ENGINE         │   │
│             │                               └──────────────┬───────────────────┘   │
│             │                                              │ produces              │
│  ┌──────────┴───────────┐    holds        ┌────────────────▼───────────────────┐   │
│  │        PKS           │◀────────────────│      operational evidence          │   │
│  │  Product Knowledge   │                 │  observations · findings · lessons │   │
│  │  System              │                 └────────────────────────────────────┘   │
│  └──────────────────────┘                                                          │
└────────────────────────────────────────────────────────────────────────────────────┘
```

**Three structural facts about this diagram, each load-bearing:**

| # | Fact |
|---|---|
| **1** | **The asymmetry is deliberate.** KnowledgeOS *defines*, PublicDigit *generates*, PKS *holds*. **No asset both defines and validates its own rules** — that is the whole point of routing evidence through a product. |
| **2** | **The refinement edge is EVIDENCE-GATED, not automatic.** Evidence reaching PKS does not amend KnowledgeOS. The path is *evidence → MCA-class assessment → CDR-class decision → issuance* (Part 1 §3.3). |
| **3** | ⚠️ **The loop has been traversed ONCE, and has NOT closed on the model.** It delivered evidence *to* the decision point and stopped. **This is Phase III's operating posture only; it decides neither D-3 nor D-5** (Part 1 §3.5). |

---

## 2. PublicDigit — product architecture

### 2.1 The three-concern separation (ES-005.1)

The single most important structural rule in the repository, and the reason the folder layout looks the way it does:

| Concern | Roots | Answers |
|---|---|---|
| **Product** | `docs/` · `architecture_legacy/` · `app/` · `tests/` | **what PublicDigit is** |
| **Engineering Platform** | `engineering/` | **how it is engineered** |
| **Runtime mount** | `.claude/` | **how the current adapter executes** |

> **The mount never moves and is never "the architecture."** Audit trail: `engineering/MIGRATION_REPORT.md` (EM-001, ARB 2026-07-10).

**Why this matters architecturally, not just cosmetically:** it is what makes *"could a different project adopt this document unchanged?"* (ES-005.3, the **Placement Litmus**) a decidable question. Cross-product → `engineering/`. Needs project context or evidence → the project. Active session state → the runtime mount.

### 2.2 Bounded contexts — the registry, and the enforcement perimeter

**Two different sets, and confusing them is a real error.**

**(a) The registered controlled vocabulary** — `docs/knowledge/schema/bounded-contexts.yaml`, the source of truth for the `bounded_context` frontmatter field, validated by `knowledge-lint`:

| Key | Label | `code_path` | Maturity |
|---|---|---|---|
| `global` | Global / Platform-wide | *(none — owned by no single context)* | — |
| `membership` | Membership — member lifecycle, applications, fees, committee structures | `app/Contexts/Membership` | mature |
| `governance` | Governance — authority models, approval workflows, committee hierarchy | `app/Contexts/Governance` | mature |
| `adjudication` | Adjudication — challenge determination, determination state machine, outcomes | `app/Contexts/Adjudication` | **growing** |
| `contestation` | Contestation — challenge submission, admission, routing workflow | `app/Contexts/Contestation` | **growing** |
| `elections` | Elections — voting eligibility, voter policies, voter repositories | `app/Contexts/Elections` | foundational |
| `election` | **Election (root domain)** — election-wide aggregate, constitution, state machine, ballot security | **`app/Domain/Election`** | **core** |
| `geography` | Geography — regional/administrative units, hierarchies, spatial data | `app/Contexts/Geography` | core |
| `committee` | Committee — read models (application layer) | `app/Contexts/Committee` | minimal |
| `finance` | Finance — financial domain events *(incomplete)* | `app/Contexts/Finance` | **stub** |
| `trust` | Trust — trust attestation domain events *(incomplete)* | `app/Contexts/Trust` | **stub** |
| `shared` | Shared — cross-context events, value objects, orchestration | `app/Contexts/Shared` | foundation |

**(b) The structural enforcement perimeter** — exactly four directories, and *nothing else in the codebase has structural coverage*:

```
app/Contexts/Contestation
app/Contexts/Adjudication
app/Contexts/Election          ← NOTE: app/Contexts/Election, not app/Domain/Election
app/Contexts/Shared
```

Enforced by **all three** structural gates: `deptrac.yaml` (paths) · `phpstan-greenfield.neon` · `Tests\Architecture\GreenfieldCoreArchitectureTest` (which scans Contestation + Adjudication + **Election, joined at PB-004 Step 4C once it became a complete Hexagonal context**).

> **⛔ CONSEQUENCE YOU MUST INTERNALIZE: `app/Console/Commands/` and `app/Helpers/` have ZERO structural coverage.** This is not a theoretical gap — it is where WP-7's deletion guard lives, and it is why the WP-7 Implementation Guard Commission found that 7 of 11 constraints are manually enforced. **"Deptrac 0 violations" is a statement about four directories.**

#### 2.2.1 `OBSERVATION` — an unregistered context in the enforcement perimeter

**Three similarly-named artifacts exist; the registry documents two of them:**

| Path | PHP files | Registry key |
|---|---|---|
| `app/Domain/Election` | **106** | `election` ✅ |
| `app/Contexts/Elections` | **15** | `elections` ✅ |
| **`app/Contexts/Election`** | **27** | ⛔ **NONE** |

`app/Contexts/Election` is **inside the Deptrac perimeter**, **inside `GreenfieldCoreArchitectureTest`**, and is the home of `EvidencePreservationDurations` — WP-7's central port — yet **no key in `bounded-contexts.yaml` maps to it.** The registry's header comment states it *"mirrors the real bounded contexts in `app/Contexts/` (+ root `app/Domain/Election`)"*, which is the intent; the `election` key's `code_path` points at `app/Domain/Election` instead.

**Reproduction:** `grep -n "Contexts/Election" docs/knowledge/schema/bounded-contexts.yaml` → returns only line 40, `app/Contexts/Elections`.

**Class:** an **identifier collision** across three near-homonyms (`Election` root domain / `Election` context / `Elections` context), of the same family as the R-number collision already recorded for 7C. **Consequence today:** any knowledge card about WP-7's port cannot name its context truthfully — it must choose between `election` (which resolves to the wrong path) and `global`.

**Routed, not enacted.** *Which of the three is the bounded context, and what the other two are, is a modelling question for the ARB — not a YAML edit.* **Do not "fix" this by adding a key.**

### 2.3 The Greenfield Core vs the legacy estate

PublicDigit is **not** a uniform codebase. It has two populations, and they are governed differently:

| | **Greenfield Core** | **Legacy estate** |
|---|---|---|
| **What** | Contestation · Adjudication · Election · Shared | the other contexts, `app/Domain/*`, `app/Models`, `app/Services`, `app/Http`, `app/Console`, `app/Helpers`, … |
| **Architecture** | per-context **Hexagonal**, events-only collaboration | Laravel-conventional, `app/`-wide |
| **Governance** | frozen Blueprint + ADR-T log + **traceability required per PR** | ordinary product engineering |
| **Enforcement** | Deptrac + greenfield PHPStan + architecture fitness tests | the general `tests/Architecture` suite only |
| **Rule of engagement** | **no design decision may be introduced without an ADR** | the CLAUDE.md decision tree (simple CRUD → Laravel way) |

> **The Blueprint's binding governance rule:** *"No Pull Request may be merged unless every changed class can be traced to one or more sections of this blueprint, the applicable ADR(s), and a Traceability Matrix row. If any implementation cannot be traced to this approved blueprint, STOP and request an architecture review — do not introduce a new design decision."*

**The legacy folders are named and ruled** (`docs/adr/20260801_1712_legacy_folder_and_files.md`): `architecture/` → **`architecture_legacy/`** · developer guides consolidated into **`developer_guide/`**, *which is itself a legacy folder* · **new developer guides go under `./docs`**.

### 2.4 Hexagonal layering, and the Deptrac model as encoded

**The approved model, stated in `deptrac.yaml`'s own header** — and note the principle, which is the reason the file is worth reading before changing anything:

> **Rules are derived from the APPROVED ARCHITECTURE (bounded contexts, hexagonal layers, approved dependencies) — never from the incidental filesystem/package layout. The tool verifies the architecture; the architecture never evolves because the tool guessed something.**

```
Per context:   Domain  ←  Application  ←  Infrastructure      (dependencies point INWARD)
Across contexts:  events ONLY (TP-1) — no direct code dependency
Shared:  a PLATFORM layer — depends only on itself + approved external libraries
```

**The ruleset, verbatim in substance:**

| Layer | May depend on |
|---|---|
| `ContestationDomain` · `AdjudicationDomain` · `ElectionDomain` | **`~` — NOTHING.** Pure PHP. **Not even `Shared`** |
| `…Application` | its **own** Domain **+ `Shared`** |
| `…Infrastructure` | its **own** Domain **+** its own Application **+ `Shared`** |
| `Shared` | **itself only** — no bounded-context layer is listed |

**Two structural consequences worth stating explicitly:**

1. **Cross-context isolation is enforced BY OMISSION.** No context's layers appear in another context's ruleset, so a cross-context import is a violation without any rule naming it. *This is why the model is "one axis" — no class is in more than one layer.*
2. **This is the mechanism behind the G-1 collision** (Part 1 §7.4): a service that must read a value owned elsewhere **cannot import the owner's port** and live inside a context. The resolution was a **consumer-side port** — Election declares its own `EvidencePreservationDurations`, reading the one canonical MAD key. *Mechanism substitution at level 3; the invariant untouched.*

**Uncovered ≠ approved** (ARB 7A refinement 1): the ~90 uncovered targets are **approved external platform dependencies** — `ClockInterface` (PB-004 clock ruling), `Illuminate\*` (framework, Infrastructure only), `BelongsToTenant`/`TenantContext` (app-wide tenant mechanism). An explicit `ExternalPlatform` layer is a **recorded future refinement** so everything becomes covered-or-intentionally-ignored.

**Tooling note (engineering evidence, PB-007 7A):** the canonical `deptrac/deptrac` Composer package is unresolvable against this dependency graph; the supported PHAR shim **`qossmic/deptrac-shim` (deptrac 1.0.2)** is used. *Same tool, same gate.* Deptrac has been in **fail mode since PB-007 7D** and currently reports **0 violations**.

### 2.5 `Shared` is a platform layer, not a dumping ground

**The admission rule (ARB 7A refinement 2), and it is enforced socially rather than mechanically:**

> **`Shared` exists ONLY to host technical concepts that would otherwise duplicate across bounded contexts.** Approved: messaging, inbox/outbox, infrastructure abstractions. **Never bounded-context business logic.** **Business concepts migrate into `Shared` only through EXPLICIT ARB APPROVAL — `Shared` must never degrade into a general utility layer.**

**This rule has already refused something concrete:** R-D1's honest cost is that duration-precedence logic now exists twice and could drift. **Extracting it to `Shared` on first repetition was DECLINED** — the second-consumer bar (Part 1 §4.2) applied to the platform's own layering.

### 2.6 The correction spine — PublicDigit's central architectural narrative

Everything in the Greenfield Core exists to make **one loop** correct:

```
ChallengeRaised → ChallengeAdmitted → ChallengeRouted
      → DeterminationIssued → Election reacts → ElectionCorrectionApplied
      → Challenge.resolve() → ChallengeResolved
```

**Canonical events** (`Canonical_Event_Catalog_v1.0.md`): `ChallengeRaised` · `ChallengeAdmitted` · `ChallengeDismissed` · `ChallengeRouted` · `ChallengeResolved` · `DeterminationIssued` · `ElectionCorrectionApplied` · `EvidenceRecorded`.

**Phase structure (Blueprint §2):**

| Phase | Context | What happens |
|---|---|---|
| **1 · Intake** | Contestation | `raise()` → **Raised** → `admit()` → **Admitted** → `route(jurisdiction)` → **Routed** — *or* `dismiss()` → **Dismissed ▣** / `lapse()` → **Lapsed ▣**, which ends the loop and **satisfies CI-1** |
| **2 · Adjudication** | Adjudication | `ChallengeRouted` ⇒ relay ⇒ `IssueDetermination`; Challenge loaded **READ-ONLY** (ADR-T14), `canProceedToAdjudication()` verified; **Determination is the sole aggregate written**; `DeterminationIssued` enqueued in the same transaction |
| **3 · Reaction** | Election | consumes `DeterminationIssued`, applies a **forward-only `ContainedOnly`** correction, emits `ElectionCorrectionApplied` |
| **4 · Closure** | Contestation | consumes `ElectionCorrectionApplied` → `resolve()` → **Resolved** → `ChallengeResolved` |

**⭐ ADR-T20 — the domain distinction that shapes the whole lifecycle:** **`Adjudicated` ≠ `Resolved`.**

> **Legal finality** (a binding determination exists, on `DeterminationIssued`) **≠ operational completion** (consequences executed, on `ElectionCorrectionApplied`). **Contestation records two independent facts and never waits synchronously** — which is what avoids cross-context lifecycle coupling. Lifecycle: `Raised → Admitted → Investigating → Routed → Adjudicated → Resolved`.

**No saga (ADR-T8):** a thin `AdjudicationService` coordinator, forward-only correction. **Compensation assumes reversibility, and anonymity forbids un-casting** — so there are **no compensating transactions**; eventual consistency arrives via events.

### 2.7 Integration architecture — outbox, envelope, inbox, and the APM

**Messaging is a PLATFORM CAPABILITY, not a bounded context (ADR-MP-01):** *architecturally a Platform Capability; in DDD terms a **Generic Technical Subdomain***, realized as Shared Infrastructure + a thin Shared Kernel / Published Language. **It holds no business decisions.** Its constituent parts — **PB-001 (Registry) + PB-002 (Relay) + PB-003 (Inbox)** — are governed henceforth **as one capability**, not as three tickets.

> **Role and classification are stated separately, never conflated.** *(This is the Platform Capability Pattern, PGP-01 — and it is the model for how any future capability should be introduced: as a capability first, per Part 1 §4.1's anti-pattern.)*

**Ownership (ADR-MP-02, PGP-02):** every responsibility has **exactly one owner**, expressed with the disposition vocabulary **Owns / Coordinates / Preserves / Observes / Does-NOT-own**. *Ambiguous ownership is a modelling defect that blocks promotion.* Operations owns tunable values (**Coordinates**); **business semantics never enter the platform.**

**⭐ ADR-MP-03 — owner-hosts-the-guard (PGP-03), a generalizable rule:**

> **The executable guard for an invariant is hosted by the suite owned by the invariant's OWNER.** Platform/Infrastructure invariants → the Messaging fitness suite. **Constitutional invariants → the constitutional suite.** Operational → config/ops. **A preserver must NEVER host an owner's guarantee.**
>
> *Consequence already recorded: the anonymity guard added during C6B is **mis-hosted** and must be relocated → tracked as **AD-M1**.* **This is Part 1 §3.1's own/preserve distinction made executable.**

**The Adjudication Process Manager (APM)** is the loop's orchestration seat — WP-2 built its core, WP-3 made `ChallengeRouted` published language and **relocated the correlation mint to the true chain head** (Contestation's raise path, `CoordinatesAdjudication` moving to `EventProvenance::fromConsumed`), and WP-4 wires Adjudication's consumption of `ChallengeRouted`.

**⭐ ADR-T23 supersedes ADR-T17 — a case study in how this programme handles a moved foundation:** ADR-T17 placed legitimacy in an Adjudication **domain service**. Then *the ground moved*: **Q-1 ruled that the constitutional authority decides and Governance owns validity**, and the Candidate-2 ruling gave judgment orchestration to the PM. So **no domain service decides legitimacy/sufficiency — the authority decides; the APM receives.** ADR-T17 → **Superseded** — *remains valid history, never edited.* A future **advisory** computation (explicitly non-binding) may return through ARB review **with a consumer as evidence**.

### 2.8 The boundary object lifecycle, and the nine discovered invariants

**Status of the contract document — read this label carefully:** `docs/architecture/Cross_Context_Integration_Contract.md` is **DESCRIPTIVE — derived from the existing implementation, not invented.** It documents rules the code already follows and Deptrac already enforces. **Elevating any clause to normative status (an ADR, or a new fitness test) is a separate decision the document does not take.** *This is Part 1 §4.3 in practice: the document claims exactly what its evidence supports.*

**Every cross-context interaction that exists today — all three of them:**

| # | Producer | Event | Consumer | Consumer class |
|---|---|---|---|---|
| 1 | Adjudication | `DeterminationIssued` | **Election** | `DeterminationIssuedReactionHandler` |
| 2 | Adjudication | `DeterminationIssued` | **Contestation** | `AdjudicateChallengeHandler` |
| 3 | Election | `ElectionCorrectionApplied` | **Contestation** | `ResolveChallengeHandler` |

*Verifiable: `grep -rl "implements InboxHandler" app/Contexts/` returns these three files and no others.*

**The lifecycle of a boundary object:**

```
PRODUCER CONTEXT                        SHARED PLATFORM                 CONSUMER CONTEXT
Aggregate / Application service
   │ records
   ▼
Domain Event          ← NEVER crosses. Producer-internal.
   │ adapter maps to primitives (+ schema_version)
   ▼
OutboxEvent row       ← Infrastructure DTO: persistence + transport
   │ relay reads
   ├─ hydratorFor()->hydrate() → Domain Event again, IN-PROCESS, PRODUCER-SIDE ONLY (R-3)
   ▼
IntegrationEvent envelope  ← + correlation/causation (D-1 fields)
   │ dispatcher resolves consumers, one InboxMessage each
   ▼
InboxMessage          ← a Shared APPLICATION type; payload = primitives only
   │
   ▼
Consumer reconstructs its OWN local VOs from primitives
```

**The nine invariants, each with implementation evidence:**

| # | Invariant |
|---|---|
| **R-1** | **A consumer never imports another context's Domain namespace.** *Exhaustive scan: zero.* **Machine-enforced by Deptrac, not aspirational** |
| **R-2** | **A consumer never imports another context's Infrastructure either — including its hydrators** |
| **R-3** | **The hydrator is PRODUCER-SIDE.** It reconstructs the producer's own event inside the producing context for event-bus dispatch — **it is not the consumer's reconstruction mechanism** |
| **R-4** | **Consumers reconstruct LOCAL VOs from primitives** |
| **R-5** | **The wire payload carries primitives only** — strings, enum backing values, ISO-8601 timestamps, and (since ADR-T22) flat arrays of strings |
| **R-6** | **Provenance travels on the ENVELOPE, never in the domain event** |
| **R-7** | **The producer never names its consumers.** Registration is consumer-side, keyed by consumer context — *PB-006's **Registration ≠ Delivery*** |
| **R-8** | **Every consumer is idempotent at TWO seats** — inbox dedupe `(event_id, consumer_context)` **plus** handler/aggregate-level idempotency |
| **R-9** | **Identity crosses as an OPAQUE STRING.** Contestation's `DeterminationId` **≠** Adjudication's; both reconstruct locally *(ADR-T16 realized)* |

> **The contract is already executable architecture.** *Cross-context = violation by omission, in fail mode, 0 violations.*

### 2.9 Constitutional vs business invariants — a separation with teeth

**Separated per ARB review (2026-07-06), and the distinction is about who may change what:**

> **Constitutional invariants almost never change — weakening one is a CONSTITUTIONAL INCIDENT, not a design choice. Business invariants may evolve — changing one requires an ADR, not a constitutional amendment.**

**Constitutional (permanent):**

| CI | Invariant |
|---|---|
| **CI-1** | Every Challenge MUST either be dismissed/lapsed **or** produce a binding Determination. **No challenge disappears without a terminal legal outcome** |
| **CI-3** | Election correction must **NEVER** modify anonymous votes. Only election **state** may change. Corrections are **forward-only** (`ContainedOnly`; no un-casting) |
| **CI-4** | Challenge resolution must **NEVER** re-open a finalized Determination. `Resolved` **consumes** the ruling; it cannot amend it |
| **CI-5** | **No consumer may infer voter identity from any event payload.** Opaque/hashed refs only; **zero voter↔vote linkage** (Q7, ADR-T11 — ***always*-invariant, never "eventual"**) |

*CI-2 was reclassified by the ARB into two business invariants; **the CI number is retired, not reused.*** *(Same discipline as R-numbers — see Part 1 §7.4.)*

**Business (may evolve via ADR):**

| BI | Invariant | Today's realization |
|---|---|---|
| **BI-1** | Every Challenge may produce **at most one** binding Determination | `DeterminationAlreadyIssued` service precondition |
| **BI-2** | Every Determination belongs to **exactly one** Challenge | `UNIQUE(organisation_id, challenge_ref)` |

> **BI-1 and BI-2 are two different invariants** — cardinality from the Challenge side vs ownership from the Determination side — and **the constraint column is merely today's realization. The invariant is the contract.** *(This is the four-level model of Part 1 §4.5 expressed in the Blueprint's own vocabulary.)*

**Anonymity, enforced in code:** `GreenfieldCoreArchitectureTest` scans for forbidden linkage tokens — `user_id` · `voter_id` · `voterId` · `voting_code` · `votingCode` — across the three contexts. Identities are **opaque refs** (`RaiserStandingRef`); references to evidence/vote are **hashes** (`envelopeHash`, `voteHash`).

### 2.10 Executable architecture — the four gates, and what they do NOT cover

**ADR-T7:** *manual review is insufficient; drift fails CI.* The toolchain:

| Gate | Scope | Current |
|---|---|---|
| **Deptrac** | boundaries + hexagonal layers, 4 directories | **0 violations**, fail mode |
| **PHPStan** (`phpstan-greenfield.neon` + max level) | types, 4 directories | **max clean** (4 root fixes, **none suppressed**) |
| **`tests/Architecture`** incl. `GreenfieldCoreArchitectureTest` | constitutional rules, anonymity, aggregate patterns | **149 green** *(146 at the WP-6 era; grew with 7A/7B)* |
| **PHPUnit suite** | behaviour | **266 tests / 665 assertions / 0 failures** *(at the 7C pre-authorization)* |

**⚠️ Always cite a gate figure WITH its ruling.** *A bare "146 green" or "255 tests" is a snapshot, and the register shows these numbers moving slice by slice.* **And note R-53:** reproduced evidence established that at the WP-6 closure commit `22d604844` the **GreenfieldCore suite terminated with a fatal error** under the documented reproduction protocol — *whether the original evidence line was FALSE or UNSUPPORTED remains **UNDETERMINED***. **A recorded gate result is a claim, not a guarantee, unless it was reproduced.**

> **⛔ THE MOST IMPORTANT SENTENCE IN THIS SECTION:** **"Deptrac 0 / 146 green" does NOT cover the defect class *"a business value was invented."*** **AP-1** (`max(1,$days)` overriding *Q-2 decides durations*) and **AP-2** (a MAD key in a retention config, destroying *one canonical home*) **passed every automated gate.** That is a statement about evidence **SCOPE**, not completeness. *The Layer Verification Rule (Part 1 §4.5) exists precisely because this class exists.*

**The honest coverage arithmetic** (WP-7 Implementation Guard Commission): of 11 constraints, **4 executably enforced → 5 after the alignment commission**, **7 manual** (3 cheaply automatable, 4 accepted manual with stated reasons). **One named hole remains: a PSR `ClockInterface` would enter the EPW value object undetected.**

**Placement principle for coverage — worth memorizing:**

> **Coverage follows MEANING, not the reverse.** The VO / port / service / adapter are gated **because they carry policy**; the folder→election parser, traversal, deletion and CLI stay ungated **correctly, because they carry none.**

### 2.11 Multi-tenancy as an architectural layer

Tenant isolation is **defense in depth**, and each layer has a different bypass cost:

| Level | Protection | Bypass requires |
|---|---|---|
| **Database** | FK constraints with `organisation_id` | impossible |
| **Model** | `BelongsToTenant` global scope | `withoutGlobalScopes()` |
| **Controller** | manual validation | a code change |
| **Middleware** | `TenantContext` route filtering | a route change |

**MODE 1 (Demo)** = `organisation_id = NULL` + separate `demo_*` tables (`demo_votes`, `demo_candidacies`, `demo_codes`) with reset capability · **MODE 2 (Live)** = `organisation_id = X`. **Same UI/UX, zero impact on real elections.**

**Testing rule that follows from this (RULE 9):** unit tests **MUST** test tenant isolation logic · always test with **multiple** `TenantId` values · **mock repositories must enforce tenant boundaries** · integration tests use actual tenant connections · **test tenant switching explicitly**.

---

## 3. KnowledgeOS — engineering platform architecture

### 3.1 What is architecturally present today

**Recall the status (Part 1 §3.2): Platform v0.1 — NOT STARTED. Architecture ≠ product.** What exists is a **governed rule system**, not software.

```
engineering/
├── governance/     ES-001..ES-006 + STANDARDS_INDEX + Engineering_Execution_Protocol
├── architecture/   baseline/ (SEALED corpus) · adr/ (rulings register) · reference/ · c4/
├── knowledge/      methodology/ ← the canon directory (DDD_Tactical_Governance_Principles,
│                                  Layer_Verification_Rule)
├── verification/   reports/ ← every commission's evidence
└── developer_guide/
```

### 3.2 The constitutional hierarchy

```
                    ES-001 Constitution
                          │
              ┌───────────┴───────────┐
              ▼                       ▼
      ES-002 Execution        ES-003 Qualification
              │                       │
              └───────────┬───────────┘
                          ▼
                 ES-004 Documentation
                          │
                          ▼
                  ES-005 Repository
                          │
                          ▼
        ES-006 Engineering Knowledge Governance
```

| Standard | Governs | Hosts |
|---|---|---|
| **ES-001** | foundational principles & how governance itself is created | rule parsimony · documents-record-governance |
| **ES-002** | how work is performed | implementation-first default · AIP observation stop |
| **ES-003** | how the platform verifies itself | qualification lifecycle · score-persistence stop · measurement conventions |
| **ES-004** | how records are structured & governed | retrospectives-recommend · record conventions |
| **ES-005** | how the repository is organized | folder rule · placement litmus |
| **ES-006** | how **ENGINEERING** knowledge is harvested/promoted/retired — **NOT project knowledge, which is a separate bounded context with its own future standards after the pilot** | promotion ladder · knowledge research freeze · harvest discipline · harvest question |

**⚠️ All six are `PROPOSED`. Not one is ratified.** The ratification batch is pending with the Decision Authority.

**The consolidation convention — the set obeys the rule it enforces:** a **HOSTED** rule's full canonical text lives in the ES document (it was previously homeless — MEMORY-only or an evidence-record addendum); a **REGISTERED** rule already has a governed home and the ES document carries **the authoritative pointer + one line**. *Copying it would create the duplication the consolidation cures.*

**End state:** `MEMORY = runtime hints only · Standards = constitutional truth · Qualification verifies standards · the EEP executes them.` **Every rule findable from `engineering/README.md` → the index.**

### 3.3 Registered constitutional sources — pointers, never copies

| Rule family | Canonical home |
|---|---|
| **Principles AIP-01..14** — incl. AIP-10 Assertion Integrity · AIP-11 Append-Only History · AIP-13 Implementation-Driven Evolution · **AIP-14 Product Primacy** | sealed Baseline corpus, `engineering/architecture/baseline/` (+ ADR-AIP-01/02) |
| **Platform Decisions PD-01..20 · Fitness Functions FF-01..17** — *FF implementation deferred per AIP-14* | sealed Baseline corpus |
| **Rulings R-30..R-37+** — living governance decisions (**R-27** governance freeze · **R-37** structural freeze + burden of proof) | `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` — **append-only**; R-1..29 sealed in Phase-02.5 §6 |
| **Reference Architecture** — what the platform IS (DRAFT→ADOPTED→STABLE) | `engineering/architecture/reference/` |
| **Governing insight** | *Governance precedes automation. Automation may implement governance. **Automation never defines governance.*** |

**The sealed-corpus rule (R-30): moves allowed, edits never.**

### 3.4 The Decision Authority & Verification Matrix — three dimensions, never mixed

| Dimension | Question | Values |
|---|---|---|
| **Primary Decision Authority** | *who determines compliance?* | **Machine** · **AI evaluates** · **Human decides** — *the AI always evaluates and recommends; authority stays with governance (ES-001.2)* |
| **Verification** | *how is compliance checked?* | OQ instrument · EP-02 review · human review · reminder |
| **Automation** | *what software exists or is justified?* | Existing · Candidate · None |

**The smallest automation set justified by evidence: ZERO new hooks.** Machine verification lives in **qualification instruments (periodic, per R-26)** — not resident daemons. **Exactly one candidate awaits the ARB:** an append-only guard for ES-004.2 — *the only rule with real incident evidence (a 2026-07-11 overwrite, git-recovered in minutes)* — and **it may still not clear the burden of proof.**

### 3.5 The open architectural question that shapes this whole asset

**⚠️ OQ-5 — is `KnowledgeOS` the same thing as the Engineering Platform (`engineering/`)?** Unresolved, **ARB-owned** (Part 1 §3.2). **R-67: `engineering/` expresses cross-product SCOPE, not a domain.** Until ruled, **cross-product artifacts route to `engineering/`, and `docs/knowledgeos/` stays effectively empty of standards.** **ENG-009 is BLOCKED on this.**

---

## 4. PKS — knowledge system architecture

### 4.1 The strategic model (Phase II, M0→M8, FROZEN)

```
                         ┌──────────────────────────────┐
      sources ──────────▶│  CBC-2 Knowledge Projection  │  accepted BC (Medium-High)
      (Conformist,       └──────────────────────────────┘
       R-3)                          ▲   ║ Separate Ways (R-5)
                                     │   ║
   ┌──────────────────────────┐      │   ▼
   │ CBC-1 Knowledge          │──────┘  ┌──────────────────────────────┐
   │ Assessment               │         │  CBC-4 Work Management       │
   │ accepted BC (Medium-High)│         │  ADJACENT — the domain's     │
   └────────────┬─────────────┘         │  OUTER EDGE (Medium)         │
        R-2     │ Customer/Supplier     └──────────────────────────────┘
        over a  ▼ narrow Published Language
   ┌──────────────────────────────────┐
   │ CBC-3 Normative Governance       │  accepted CANDIDATE SEAM
   │ (Low-Medium, evidence-decided)   │  — a FORMAL STATE, not a hedge
   └──────────────────────────────────┘

   ╔══════════════════════════════════════════════════════════════════╗
   ║  EXPRESSED-KNOWLEDGE CORE — UNPARTITIONED REGION                 ║
   ║  Decision · Term · Model element · Contract                      ║
   ╚══════════════════════════════════════════════════════════════════╝

   ⚠️ Risk / Question / Exception record — CONTESTED, UNASSIGNED
```

**R-1 (seam→CBC-1) and R-4 (PKS↔CBC-4) are carried as PATTERN-FREE DEPENDENCIES.** The graph is **acyclic**.

**Two constraints on citing this model:**

1. **The citation rule (binding downstream):** cite the **structural layer** — dependency · direction · ownership — freely; **cite Evans pattern names ONLY where they survived DAR-1.**
2. **R-3 carries a unidirectionality constraint:** ***"nothing may cite a view as authority."***

### 4.2 The process baseline

| Instrument | State |
|---|---|
| **SDM v1.2** (Strategic Discovery Method) · **EOP v1.2** (Execution/Operating Protocol) | **FROZEN, reference-defined** (BRM-1 retained Model B) |
| **Process Under Configuration Control** | **DECLARED** — changes only via *execution evidence → MCA-class assessment → CDR-class decision → issuance* |
| **MCR-1..4 + MCR-5-statement + MCR-6** | **ADOPTED** — member-set fixation binding · candidate seam = a formal state · re-entry route defined · **Evidence Restatement stage codified** · **independence-basis required on every grade** · MCR-5 instrument deferred with a trigger |
| **Certification** | **PROVISIONALLY CERTIFIED**, two-dimensional; **re-assessment BINDING at the M7/M8 checkpoint** |
| **Register** | 10 items — **5 adopted, 5 deferred**; sixteen reviews, **none open, no finding open** |

**The governance layer was RECOGNIZED forward-looking as a *governed empirical architecture process*, with Strategic DDD as the discovery technique within it** — not as a replacement for it.

**Completion standard, stated precisely:** *every question **necessary** for the baseline reached a lawful terminal state — **adopted · resolved · deferred · retired*** — **not that every possible question was answered.** ⚠️ **SIA-1 additionally reached *"no present constitutional act is required"* — a disposition used ONCE, expressly NOT a fifth general terminal state (n=1).**

> **Scope of completion:** *the programme **exhausted the questions it set out to answer** — it did **not** finish governance.* **Any further substantial governance work begins as a NEW CYCLE with a NEW MANDATE, triggered by new evidence, new architectural needs or operational experience — never by reopening questions already at lawful terminal states.**

### 4.3 The review framework as architecture — five layers, one dependency chain

*(Full content in Part 1 §5.9; here is the architectural shape.)*

```
   Layer 5  PROGRAM GOVERNANCE PROCESS   (pre-existing: SDM · EOP · CDR · CCP-1 §12.6)
   Layer 4  FRAMEWORK GROWTH GOVERNANCE  (NO DOCUMENT — deliberately DISTRIBUTED)
   ─────────────────────────────────────────────────────────────────────
   Layer 3  Discipline  (CONDUCT)   ──depends on──▶  Layer 2
   Layer 2  Method      (WORKFLOW)  ──depends on──▶  Layer 1
   Layer 1  Integrity Model (CRITERIA)  ──depends on── NOTHING
```

**Why the dependency direction is load-bearing:** **the Integrity Model must be revisable without reading either sibling.** *Dependency* = content cannot be applied without the other; *back-reference* (coverage evidence) may point anywhere and **forms no cycle.**

**Layer 4 has no document ON PURPOSE, and this is a genuine architectural decision rather than an omission:** each growth rule is **hosted in the document whose growth it governs** (mostly the Discipline), **which is maximal locality — not a defect.** ⛔ **Do NOT create a fourth document: extraction on first recognition would violate the repeated-evidence filter that growth governance itself holds.** Promotion trigger: a growth rule **no single layer can host**, or one that must be **RESTATED in two layers** to stay applicable (the KC-4 condition). **Open `Q-GG-1`:** is this distinct, or **Configuration Control specialized to the framework**? *(The CDR shape — evidence → assessment → decision — and the ladder shape — escaped-defect evidence → provisional → governed — are suspiciously the same; if it is an instance, no document is ever justified.)*

**Precedent worth carrying (FW-1, 2026-07-30):** the reviewer **found** a defect, **recorded** it, **did NOT fix it** (the fix touched the frozen baseline), **the Authority disposed**, **then the reviewer executed.** *CCP-1 §12.6's chain applied to the review framework itself.* **Discipline v1.0's freeze constrains reviewer accretion, not Authority acts.** **Relocation changes WHERE a vocabulary is defined, never HOW MUCH evidence supports it.**

---

## 5. The Operational Evidence Loop

### 5.1 The loop as architecture

Two loops exist and they are **different objects**. Keep them apart.

**(a) The PROGRAMME loop (Charter §6.1)** — asset-level, months-scale:

```
KnowledgeOS → PublicDigit engineering → operational evidence → PKS → KnowledgeOS refinement
```

**(b) The KNOWLEDGE-OPERATIONS loop** — artifact-level, day-scale, **traversed once on 2026-08-01**:

```
Observation → Classification → Placement → Validation → Operational Evidence → Backlog
                                   │
                                   └─ resolver: php scripts/doc-placement.php
                                      exit 2 = UNRULED → record PENDING, ESCALATE
```

### 5.2 What the traversal proved, at exactly its strength

| Claim | Standing |
|---|---|
| A new artifact was **CLASSIFIED FIRST and its location DERIVED** by the resolver (`--scope=product-specific --domain=pks` → `docs/pks`), then written where the machinery said — **no path chosen, argued or hard-coded** | ✅ **Operationally validated, FIRST EXECUTION** |
| Routine use of the mechanism | ❌ **Not claimed.** *One script proves possibility; routine use proves capability* |
| **A second, WEAKER execution the same day: the resolver returned PENDING for a cross-product research artifact and THE RESPONSE WAS TO STOP, NOT INVENT A LOCATION** | ✅ **A model that REFUSES is as much evidence as one that answers** — the failure mode the tooling exists to prevent, exercised for real |
| Evidence **reaches** authority | ✅ shown |
| Evidence **changes** the model | ❌ **NOT shown.** No ruling issued, no standard amended, no candidate promoted |

**Recorded claim:** *"first operational validation of the KnowledgeOS feedback loop, **up to the decision point**."*

### 5.3 The loop's outputs today

**A closed workstream with explicit successors** (documentation placement & link integrity, closed 2026-08-01):

| Successor | State |
|---|---|
| **ENG-008** externalize the link-repair confidence policy | **CLOSED for a stated reason** — abstraction before a second consumer. **Trigger written INTO the item: a second consumer** |
| **ENG-009** | **BLOCKED on OQ-5** |
| **ENG-010** documentation integrity | OPEN |
| **ENG-011** repo-wide validation | OPEN |
| **6 ambiguous references** (`./ARCHITECTURE.md`, `./INDEX.md`) | need **a human choice**, not a repair |

**Two candidates recorded with no ruled home** — *recorded inside the evidence record that produced them, NOT given invented files*: (1) `Layer_Verification_Rule.md` (from WP-7 finding G-1) and (2) the ES candidate *"a documentation index shall not reference an artifact that does not yet exist unless explicitly marked as planned."* **Both are `cross-product + research`, the cell the resolver returns PENDING for. The stewardship deferral's stated precondition ("the evidence is one artifact") is NO LONGER the situation — which is a REPORT, not a request to decide.**

---

## 6. The relationship between the three — and its boundaries

### 6.1 Dependency, direction, ownership

| Edge | Nature | Constraint |
|---|---|---|
| **KnowledgeOS → PublicDigit** | PublicDigit engineering is **governed by** the ES set, the EP process, the ADR discipline | *Governance precedes implementation* |
| **PublicDigit → operational evidence** | engineering work **produces** observations, findings, friction reports | ⛔ **A Phase III act that produces a governance artifact without producing operational evidence has FAILED THE MANDATE — regardless of the artifact's quality** |
| **evidence → PKS** | PKS **holds** the structured knowledge | **Shape: Observation → Evidence (with method) → Classification → Recommendation → Reproduction command** |
| **PKS → KnowledgeOS** | refinement | **EVIDENCE-GATED and SLOW.** *"Give it TIME — **months** of real engineering, not weeks"*; the strengthened admission filter requires **repeated** evidence, and **a single project's early impressions are not repetition** |

### 6.2 What the relationship deliberately does NOT establish

| ⛔ | |
|---|---|
| **The three names are not governed terms** | *"They do not become governed terms by being given roles"* (D-5 open) |
| **The loop is not a governing rule** | It is **materially D-3**; recording it as governing would decide D-3 **by assertion** |
| **The loop is not a history** | **EAD-1 §E.1: the corpus's provenance runs the OTHER WAY** — the framework was built from **review practice**, not product evidence |
| **PublicDigit's success would not certify the methodology** | Operational Evidence is a **separate certification dimension** and moves only on **use**, not on outcome |

### 6.3 Repository-level consequence of the separation

**⚠️ The PKS corpus is split, and this was NAMED rather than discovered later:** 89 PKS documents remain in `docs/implementation/` while new ones land in `docs/pks/`. **Correct behaviour on NEW work while migration is blocked — but PKS documentation lives in TWO PLACES until Phase 2 runs. Search both.** On issuance of the ES-005 amendment, **92 artifacts (89 PKS + 3 KnowledgeOS) become immediately non-conformant** — their derived location is a domain root that does not yet hold them. **Prefer recording a TRANSITIONAL NON-CONFORMANCE (the R-39 pattern) over a conditional fallback that makes the rule self-nullifying.** *A standard that records its own non-conformance is enforceable; one that dissolves on contact is not.*

---

## 7. Repository philosophy

### 7.1 The four rules of ES-005

| Rule | Statement |
|---|---|
| **ES-005.1 Three-Concern Separation** | Product (`docs/` `architecture_legacy/` `app/` `tests/`) · Engineering Platform (`engineering/`) · Runtime mount (`.claude/`). **The mount never moves and is never "the architecture."** |
| **ES-005.2 The Folder Rule** | **A directory exists only when its first artifact arrives.** Reserved namespaces are **documented, never created speculatively**. Empty directories are removed on discovery (verified-empty only) |
| **ES-005.3 The Placement Litmus** | **Could a different project adopt this document UNCHANGED?** Yes → `engineering/`. Needs project context or evidence → the project. Active session state → the runtime mount. **Research artifacts stay project-side until promoted through qualification (ES-006 ladder)** |
| **ES-005.4 Never a Copy** *(candidate — ARB to confirm scope)* | Governed knowledge **references, assembles, validates and contextualizes** existing artifacts; **it never duplicates them.** **One rule → one home; everything else points** |

**The folder rule in action, and note that it was OBEYED rather than worked around:** the three documentation roots were each **created WITH a README as their first artifact** — *the README satisfies the folder rule rather than working around it.*

### 7.2 Placement is derived, and executable

```
Classification (what is this artifact?)          Location = f(Classification)
    Scope     cross-product | product-specific   ← DERIVED. An OUTPUT.
    Steward   who curates it                       NEVER a member of the classification.
    Maturity  research | qualified | adopted
    Domain    which domain's knowledge  (N/A when Scope = cross-product)
```

| Classification | Derived location |
|---|---|
| Product-specific · **PublicDigit** | `docs/publicdigit/` |
| Product-specific · **PKS** | `docs/pks/` |
| Product-specific · **KnowledgeOS** | `docs/knowledgeos/` |
| Cross-product · Engineering Steward · **Standard** | ⚠️ **contested by OQ-5** — routes to `engineering/` today |
| Cross-product · Engineering Steward · **Research** | ⛔ **UNRULED — stewardship decision pending.** Resolver exits **2** |
| **Active session state** | `.claude/` (runtime mount) |

**Single source of truth:** `docs/knowledge/schema/documentation-placement.yaml` — *beside `statuses.yaml` / `authorities.yaml` / `bounded-contexts.yaml`, where enumerations already live.* **Single consulted mechanism:** `scripts/doc-placement.php` (`npm run docs:placement` / `docs:placement:verify`).

> **NEVER hard-code a documentation root in a template, script or prompt — RESOLVE it.** **Adding a domain or renaming a root = one registry edit, zero script edits.**

**Two governing invariants (the documentation-roots ADR):**

1. **Classification precedes placement.** *"It's already under KnowledgeOS therefore it must be KnowledgeOS" is invalid reasoning.*
2. **Artifact identity is independent of physical location.** *A repository move is a repository concern. It carries no governance meaning — it neither promotes, demotes, re-owns nor re-authorizes what it moves.* **Repository normalization changes ORGANIZATION only — never artifact identity, architectural meaning, or ownership.**

**The roots are architectural BOUNDARIES only:** they define **ownership and placement**, and **do NOT prescribe the internal information architecture of each domain.** *Internal organization is owned by the domain and may evolve without amending the ADR.*

### 7.3 Runtime vs persistent artifacts

| | **Runtime (`.claude/`)** | **Persistent (repo proper)** |
|---|---|---|
| **Holds** | MEMORY · CONTEXT · sessions · plans · scripts · platform registry · IMPLEMENTATION_PROTOCOL | standards · ADRs · rulings · verification reports · dev guides · code |
| **Lifetime** | the current adapter | the programme |
| **Mutability** | MEMORY/CONTEXT mutable; **session logs APPEND-ONLY** | **decision text and history never rewritten** |
| **Authority** | **hints and current state — never constitutional truth** | canonical |

**A ruled boundary case worth knowing:** `.claude/scripts/engineering-placement-guard.sh` **is not registered** in `.claude/platform/registry.yaml` — **R-42** ruled the registry governs **platform assets only**; a project-side workflow hook has no AIP lineage, and **registering it would require fabricating traceability.** **The guard obeys its own litmus:** it is runtime session tooling, so **ES-005.3 puts it in `.claude/scripts/`, not in `engineering/`.**

### 7.4 The layer discipline that keeps the repository honest

> **ADRs GOVERN POLICY · ES documents EXPLAIN STANDARDS · the REGISTRY STORES CONFIGURATION · SCRIPTS EXECUTE BEHAVIOUR · REPORTS RECORD EVIDENCE.** **Each fact lives in exactly one layer.**

**DUAL TRUTH is the failure mode.** A registry row carries a `ref:` (`ES-005.3`, `ADR:OQ-2`) — **it stores WHAT and points at WHO SAYS SO.** **No governance prose, no historical commentary, no rule text in executable tooling.** **Root READMEs stay ~15–20 lines:** purpose · holds · owner · internal-layout ownership · a pointer table (policy / configuration / resolver). **Everything else belongs in the ADR.**

---

## 8. What Part 2 deliberately does not contain

| Excluded | Because |
|---|---|
| Aggregates, repositories, services, APIs, infrastructure choices **as prescriptions for future work** | **Strategic DDD ends with *what the implementation must respect*, NOT *how implementation must realize it*.** The moment a handover prescribes implementation structure, it has crossed the boundary |
| A ranked list of "next architectural improvements" | **No further architectural commission unless new architectural evidence appears.** *The diminishing-returns signal has already fired once* |
| Any resolution of OQ-5, D-3, D-5, Q-GG-1, Q-FW-1, the stewardship decision, or the `app/Contexts/Election` registry gap | **All ARB-owned. Surfaced here, decided nowhere** |

**Naming discipline that goes with the boundary:** govern **the *handover* / the *transition contract***, never *"the transition into implementation"* — the latter reads as one methodology continuing into another, when the correct shape is **Knowledge Methodology → governed handover (an anticorruption boundary) → Implementation Methodology**, each separately governed.

---

## Traceability

**Primary sources (all repository-internal, read at authoring):**

- `deptrac.yaml` — the approved model as encoded, layer ruleset, uncovered-dependency note, Shared admission rule, tooling-shim evidence
- `docs/implementation/PushB_Architecture_Blueprint.md` **v1.0 FROZEN** — §0 gap register · §1 constitutional/business invariants · §2 end-to-end sequence · governance/traceability rule
- `docs/architecture/Cross_Context_Integration_Contract.md` **(DESCRIPTIVE)** — the three consumers, invariants R-1..R-9, boundary object lifecycle
- `docs/adr/ADR-T-LOG-Tactical-Implementation.md` — ADR-T1..T23 + the ADR dependency graph and its rejection rule
- `docs/adr/ADR-MP-Messaging-Platform.md` — MP-01 platform capability · MP-02 ownership vocabulary · MP-03 owner-hosts-the-guard + AD-M1
- `docs/implementation/Canonical_Event_Catalog_v1.0.md` — the canonical event set
- `docs/knowledge/schema/bounded-contexts.yaml` — the registered vocabulary *(and the §2.2.1 observation)*
- `tests/Architecture/GreenfieldCoreArchitectureTest.php` — enforcement scope, forbidden linkage tokens, the PB-004 Step 4C note
- `engineering/governance/ES-005-Repository.md` · `ES-001-Engineering-Constitution.md` · `STANDARDS_INDEX.md`
- `docs/adr/ADR_20260801_1740_ Documentation Roots and Artifact Placement.md` · `docs/adr/20260801_1712_legacy_folder_and_files.md`
- `docs/implementation/PKS_Phase_III_Operational_Validation_Charter.md` §6.1/§6.2/§7 · `.claude/MEMORY.md` (Phase II model, framework layers, placement/link rules)
- `app/Contexts/Election/Application/Port/EvidencePreservationDurations.php` · `config/adjudication.php` · `config/election_preservation.php`
- `app/` and `app/Contexts/` directory inventory · `docs/architecture/c4/` (01–06, documentation only — **frozen artifacts win on conflict**)

**New observations recorded by this Part (routed, not enacted):** §2.2.1 — `app/Contexts/Election` is inside the enforcement perimeter but has no key in `bounded-contexts.yaml`.

**Supersedes:** nothing. **Superseded by:** nothing. **Depends on:** Part 1.
