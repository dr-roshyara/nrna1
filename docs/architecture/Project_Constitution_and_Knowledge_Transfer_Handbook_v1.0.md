# NRNA Voting Platform — Project Constitution & Knowledge Transfer Handbook v1.0

**Status:** 📖 LIVING (versioned) · **Level 2 navigation center of the Architecture Knowledge Base** (`Architecture_Knowledge_Base_v1.0.md`) · evolve only via architecture review + versioned release.
**Date:** 2026-06-27 · **Audience:** any senior architect (human or AI) joining cold.
**Nature:** This is the **navigation + synthesis layer** over the frozen artifacts. Where this handbook summarizes a frozen document, **the frozen document is authoritative** — this handbook points to it, it does not replace it. Read this first; then read the cited sources.

> **The one sentence to internalize:** *You are not building a CRUD Laravel app. You are building a high-assurance, constitutionally-governed, anonymity-preserving online voting platform whose architecture was derived by evidence and is now frozen — implementation must conform to it, not re-litigate it.*

---

## Part 0 — Document map (what is authoritative)
| Concern | Authoritative artifact (path under repo) |
|---------|------------------------------------------|
| Strategic boundaries | `docs/architecture/design/Round49-06_Boundary_Decision_Register.md` (BDR v1.1) |
| Frozen baseline bundle | `…/Architecture_Release_1.0.md` + `…_Release_Notes_1.0.md` |
| Aggregates | `…/Round50-01_Aggregate_Discovery.md`, `…/Round50-02_Aggregate_Review.md` |
| Events (relationships / contracts) | `…/Round50-04_Domain_Event_Design.md`, `…/Round50-05_Event_Catalogue.md` |
| Policies | `…/Round50-06_Policy_Catalogue.md` |
| State machines | `…/Round50-07_Aggregate_State_Machines.md` (v1.2 FINAL) |
| Repository & transactions | `…/Round50-08_Repository_Transaction_Design.md` |
| Verification gate | `…/Round50-09_Architecture_Decision_Verification.md` |
| Constitutional ruling | `…/Round38C-15_ARB_Ruling_OQ-38B05-05.md` + `38C-16` template |
| Implementation governance | `docs/implementation/*` (Constitution, Playbook, Event Catalog, Failure Strategy, Overview, Traceability Matrix, Readiness Audit) + `docs/adr/ADR-T-LOG-Tactical-Implementation.md` |
| Literature | `…/Round50-LIT_*` (EBTAE charter) + `Round50-LIT-A_Decision_Critical_Review.md` |

---

# Part I — Executive Summary
**What:** a multi-tenant platform for conducting secure, anonymous, verifiable elections for organisations; its differentiator is a **constitutional correction loop** (challenge → adjudication → binding determination → contained correction → finality).
**Why:** online voting fails on *trust*, not on vote-casting; the unsolved problem in the literature is **end-to-end dispute resolution with binding finality** (LIT-A, S17/S19).
**Different:** vote anonymity is a hard constitutional invariant (no `user_id` in votes — Q7); governance is modelled as a domain, not bolted on; the architecture was derived by an evidence-based method (EBSD) and frozen.
**Maturity:** Strategic DDD **complete**; Tactical design **frozen**; implementation **~10–15%** (greenfield Core started, `Challenge` aggregate done).
**Roadmap:** finish greenfield Core (Contestation + Adjudication) → migrate Operational BCs → empirical evaluation → dissertation.

# Part II — Vision
- **Long-term:** democratise trustworthy elections for any organisation, from 10 to 100,000 voters.
- **Mission:** *"Vote with confidence, audit with certainty, remain completely anonymous."*
- **Research vision:** demonstrate that governance can be **translated into software** under evidence discipline (EBSD) and that **binding-finality dispute resolution** is implementable.
- **Software vision:** a modular monolith with DDD discipline, event-driven seams, deterministic replay, and executable architecture conformance.

# Part III — Problem Statement
Current systems either (a) claim anonymity but store voter↔vote links, (b) lack verifiable correction when an election is contested, or (c) treat governance as configuration. CRUD software cannot encode constitutional invariants, finality semantics, or anonymity-bounded correction. DDD is required because the **domain rules are the product** — the correction loop, the trust roots, the finality doctrine. See Round 38C series + Architecture Release 1.0.

# Part IV — Project Goals
- **Functional:** complete voting lifecycle; regional/national posts; two-use codes; demo mode; the correction loop.
- **Non-functional:** anonymity (Q7), auditability, deterministic replay, reliability (outbox/inbox), tenant isolation, evolvability.
- **Research:** validate EBSD; produce candidate dissertation contributions (Part X).
- **Business:** multi-tenant SaaS for corporate/non-profit/political/educational/professional elections.
- **Educational:** a reference for evidence-based DDD on a high-assurance system.

# Part V — Scope
- **In:** the 6 aggregates (Vote, EvidenceEnvelope, Mandate, Challenge, Determination, Election/Lifecycle); the correction loop; anonymity; audit; replay-as-rebuild.
- **Out (now):** cryptographic E2E verifiability (homomorphic tally / Benaloh / mixnets) — **deferred, ADR-T13**, recorded limitation.
- **Future:** E2E crypto, blockchain verification, mobile app, third-party API, advanced fraud detection.

# Part VI — Technology Stack
Laravel 11 · PHP 8.2/8.3 · Inertia 2.0 + Vue 3 (forms use `router.post()`, never raw fetch) · MySQL/Postgres · Spatie Permission · Sanctum · custom multi-tenancy (`organisation_id` + `BelongsToTenant` scope) · PHPUnit 11 · Deptrac + PHPStan + Infection (architecture conformance — being installed). Existing infra: transactional outbox (`ProcessOutboxEvents`), dead-letter (`DeadLetterEntry`), `tests/Architecture/`.

# Part VII — Architecture
**Style:** modular monolith · hexagonal (Domain ← Application ← Infrastructure) · DDD · CQRS-light (Eloquent reads; repository+DTO writes) · event-driven seams.
**Code home:** `app/Contexts/<Context>/{Domain,Application,Infrastructure}` (authoritative per Round 49-03).
**Layer rules:** Domain = pure PHP (no Laravel/Eloquent/Carbon/facades); Application = limited (constructor injection, DTOs, no facades/Eloquent); Infrastructure = Laravel-free-for-all. See `docs/implementation/Implementation_Architecture_Overview.md`.
**Tactical principles:** **TP-1** event-is-the-seam · **TP-2** request-not-create · **TP-3** events version-never-mutate.
**Constitutional invariant:** **Q7** — no voter↔vote linkage anywhere; votes table has no `user_id`.
Full detail: Round 50-04…50-09 + the 10 Architecture Principles in Architecture Release 1.0.

# Part VIII — Strategic DDD (frozen)
- **EBSD** (Evidence-Based Strategic DDD): certified semantics → candidate contexts → conformance → evidence grading (L1/L2/L3; Direct/Strong/Moderate/Weak) → falsification → BDR.
- **Boundary Decision Register v1.1** (immutable + append-only evolution): **5 Confirmed BCs** — Evidence, Voting, Appointment (**Operational**); **Adjudication, Contestation (Architectural-Greenfield)**. Downgrades recorded with reasons.
- **BDR-05 key finding:** administrative finality (Counting→ResultsPublished, owned by Lifecycle) ≠ **constitutional finality** (Challenge→Adjudication→Determination→Correction→Finality). The latter is **unbuilt** — the genuine Core.
- **Architecture Release 1.0** = Knowledge 1.0 + Vocabulary 1.0 + Landscape 1.0 + BDR 1.1 + 10 Architecture Principles. **Frozen.**
- **Why rejected candidates were rejected:** see BDR downgrades (Replay/Authorization/Lifecycle/Audit = no module; capability-discovery ≠ BC-confirmation).

# Part IX — Tactical DDD (frozen)
- **Aggregates (decision-first):** EvidenceEnvelope (immutable+hash) · Vote (anonymous; Ballot inside; Results = async projection) · Mandate (Active/Revoked/Expired) · **Challenge** (greenfield) · **Determination** (greenfield, final-once) · Election/Lifecycle (+CorrectionApplied).
- **Correction terminus (50-03):** Option A — Adjudication *issues* `DeterminationIssued`, does **not** enforce; Election/Lifecycle reacts → `ElectionCorrectionApplied` (anonymity-bounded `ContainedOnly`). `AdjudicationService` coordinates (request-not-create).
- **Events:** Canonical Event Catalog v1.0 (11 events; single producer; classification Decision/Evidence/Process/Integration; stability Core/Supporting).
- **Policies (50-06):** Invariant / Decision / Authorization / Validation / Calculation; every aggregate decision has a guarding policy; 2 constitutional Q7 invariants (Anonymity, ContainedCorrection).
- **State machines (50-07 v1.2):** 10 principles; per-aggregate states/invariants/guards/transition-tables/UML; atomicity (guard→mutate→event→outbox→commit); illegal-transition = DomainException + audit + no mutation; clock authority (UTC, Temporal trust-root).
- **Repository & transactions (50-08):** one repo per aggregate root; **one aggregate per transaction** + outbox row; optimistic concurrency (`AggregateVersion`); strong-intra / eventual-inter consistency.
- **Failure (Failure Strategy):** outbox+inbox; at-least-once+idempotent=effectively-once; park-not-fail (causal); halt-not-heal (integrity); **Safe Halt** (fail-closed) on infra loss; Byzantine posture.
- **Governance docs:** Implementation Architecture Constitution v1.0 + Coding Standard (forthcoming) + Playbook + ADR-T log.

# Part X — Research
- **EBSD** — evidence-based strategic boundary discovery (candidate contribution; positions ≈ architecture recovery — to be validated in LIT-METHOD).
- **Knowledge Certification Pipeline** — Draft→Certified→Published lifecycle for domain knowledge.
- **BDR as a governed, versioned, append-only architecture artifact.**
- **Governance → Software translation** pipeline.
- **Anonymity-bounded correction (`ContainedOnly`)** — correction without reversibility (no literature equivalent; compensation assumes reversibility).
- **Binding-finality dispute resolution for online voting** — the recognised open problem (LIT-A S17/S19).
- **Literature:** LIT-1/2/3 done; EBTAE Part A done (D1–D12 all KEEP); LIT-METHOD/4/5 deferred to post-implementation.

# Part XI — Implementation (status)
- **Branch:** `greenfield-core` (off `enhance-election-only`).
- **Done:** Slice 1 scaffolding (Deptrac/PHPStan configs, fitness tests 6/6, package structure for Contestation+Adjudication); **`Challenge` aggregate** (TDD, 14/14 green) — VOs, enum, 5 events, exception, full state machine.
- **In progress / remaining (Slice 1):** register Architecture testsuite (F-4); Challenge **repository interface**; `AdjudicationService`; **`Determination`** aggregate; repository implementations; outbox/inbox wiring; Election reaction; integration + fitness; merge review.
- **Later slices:** Evidence/Voting/Appointment migration (strangler: Evidence→Appointment→Voting, Voting last = live data + anonymity).

# Part XII — Quality
TDD-first (RED→GREEN→REFACTOR→**arch tests**→**PHPStan max**→**mutation (Infection)**→**Architecture + Security review gates**→merge). Executable conformance: `tests/Architecture/` (constitutional rules incl. AT-Q7-001 no-linkage, AT-EVT-001 single-producer) + Deptrac (layers/boundaries) + PHPStan + Infection. KPIs (Playbook): 0 violations, 0 voter linkage, 100% replay-determinism, 100% version-compat. Domain + security metrics dashboards. Threat-model + anonymity checklist at the Security Review Gate.

# Part XIII — Working Principles (never violate)
No Eloquent/infrastructure/`Carbon`/`now()` in Domain · no cross-aggregate transaction · no aggregate creates another (request-not-create) · no policy/invariant bypass · no event outside the Catalog · no public setters/mutable VOs · **no `user_id`/voter↔vote linkage (Q7)** · forbidden transitions throw + audit + no mutation · software does **not** own governance enforcement (SD-5 #4). Frozen decisions are frozen — change only via ADR-T + review gate. (See Implementation Architecture Constitution v1.0.)

# Part XIV — AI Collaboration Guide
- **Read before acting:** the Constitution's required-reading order (Release 1.0 → BDR → 50-01…50-09 → ADR-T → Catalog → Failure Strategy → Playbook → Overview → Traceability → Constitution → this handbook).
- **Reason from the contract, not enthusiasm.** Start a slice from the **Readiness Audit checklist**, not from the aggregate.
- **Never** silently broaden scope, invent events, or duplicate authoritative docs. **Never** over-claim (no "verified/canonical/proven" without evidence); separate evidence ↔ interpretation ↔ decision.
- **May propose improvements** as ADR-T candidates with evidence; **must ask** before live-repo code, destructive ops, or reopening a frozen decision.
- **Verify before asserting**: run tests; cite file:line; recalled memory is background, verify against current code.
- **Self-review for drift** (e.g. the `lapse`-event drift was caught this way) and report it.

# Part XV — Roadmap
```
Strategic Discovery ✔  Architecture Release 1.0 ✔  Migration Plan ✔
Aggregate Discovery ✔  Aggregate Review ✔  Events ✔  Policies ✔
Repositories ✔  State Machines ✔  Implementation Constitution ✔  Readiness Audit ✔
──────────────────────────────────────────────────────────────
Implementation ► ~10-15%
  Challenge ✔ | Determination □ | Repository(if) □ | AdjudicationService □
  Outbox □ | Inbox □ | Election reaction □ | Integration □ | Fitness wired □
Empirical evaluation □  →  LIT-METHOD □  →  LIT-4 □  →  Release 1.1 □  →  Dissertation □
```

# Part XVI — Risks
- **Architecture:** drift during implementation (mitigated: fitness tests, review gates, this handbook).
- **Implementation:** greenfield Core complexity; aggregate boundaries may need micro-adjustment (keep persistence behind interfaces until stable).
- **Research:** EBSD novelty unproven until LIT-METHOD; candidate contributions need empirical backing.
- **Operational/Security:** anonymity regressions (Q7) — highest-severity; replay non-determinism; infra failure handling (Safe Halt).
- **Quality:** architecture tests not yet CI-enforced (F-4); mutation testing not yet wired (F-2).

# Part XVII — Lessons Learned
- Capability discovery ≠ bounded-context confirmation (falsify, don't confirm).
- Administrative finality ≠ constitutional finality (BDR-05) — surfaced the real Core.
- Don't over-claim; bound empirical claims to evidence examined.
- Stop producing methodology docs once stable; shift to implementation-facing artifacts.
- Freeze architecture **before** scaling implementation; start each slice from a checklist.
- Self-review catches drift (lapse-event) before it ships.

# Part XVIII — Future Research
Cryptographic E2E verifiability (ADR-T13) · formal verification of the correction loop · distributed trust / threshold decryption · architecture recovery positioning of EBSD (LIT-METHOD) · AI-assisted architecture conformance.

# Part XIX — Mentor Guide (most important)
> Tell the next architect: **"You are not designing a CRUD application. You are protecting a frozen, evidence-derived architecture for a high-assurance voting system."**
- **Mentor by** anchoring every decision to a frozen artifact + ADR-T; require evidence; separate evidence/interpretation/decision.
- **Review PRs against** the Architecture + Security review gates and the Traceability Matrix; reject code that adds an un-catalogued event, a cross-aggregate transaction, infrastructure in Domain, or any voter↔vote linkage.
- **Accept code** only when arch tests + PHPStan + mutation + gates are green and DoD is met.
- **Request literature** only at the scheduled milestones (LIT-METHOD post-impl), not to drive design.
- **Challenge assumptions** with falsification, never confirmation.

# Part XX — Current Status (where we stopped)
- **Branch:** `greenfield-core`. **Last commits:** scaffolding (`bc9ded4b9`), Challenge aggregate (`18dd03aae`), governance pass (`b0ca3e4f7`).
- **Outstanding (immediate):** install Deptrac/PHPStan/Infection (in progress); register Architecture testsuite (F-4); freeze package structure + naming conventions; Coding Standard v1.0; then repository interface → `AdjudicationService` → `Determination`.
- **Next milestone:** complete + merge Slice 1 (the correction-loop core path) behind all quality gates.
- **Authoritative truth:** the frozen artifacts in Part 0; this handbook is the map.

---
*NRNA Project Constitution & Knowledge Transfer Handbook v1.0 — authoritative onboarding/navigation layer over the frozen artifacts (which remain authoritative). Evolve only via architecture review + versioned release. Covers Parts I–XX: vision, problem, goals, scope, stack, architecture, strategic + tactical DDD, research, implementation status, quality, working principles, AI-collaboration, roadmap, risks, lessons, future research, mentor guide, current status.*
