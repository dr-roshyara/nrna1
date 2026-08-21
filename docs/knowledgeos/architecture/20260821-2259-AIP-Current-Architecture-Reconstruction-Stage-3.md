# AIP Current Architecture Reconstruction — Stage 3

> **PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE · NOT ADOPTED**
> **Stage 3 of the approved EP-01 study** (`docs/plans/20260821-2118-eks-pks-aip-current-architecture-landscape-knowledgeos-evolution-study-plan.md`).
> **READ-ONLY reconstruction of what AIP *is* — not what AIP should become.**
> AIP is frozen: no redesign, no frozen-baseline reopening, no technology change, no implementation change.

**Date:** 2026-08-21 · **Authoring lane:** P3 · **Output stage:** Stage 3 (AIP current architecture reconstruction).
**Companion stages:** Stage 1 (P1/EKS) · Stage 2 (P2/PKS) · Stage 4 (landscape analysis — **NOT performed in this session**).

---

## 🔧 ERRATUM — P3-F1 · BOUNDED EVIDENCE COMPLETION (2026-08-21)

> **Bounded evidence-completion pass — NOT a P3 architecture rewrite.** The HPA review (`docs/knowledgeos/reviews/2026-08-21-KOS-EP01-P3-aip-reconstruction-hpa-review.md`, commit `c673de5d`) made a bounded evidence-completion pass a **mandatory precondition** before P4 consumes this baseline: the plan §5.3 approved corpus names the 196KB MIGRATION-PLAN, whose body was not read in full. Independent verification (`docs/knowledgeos/reviews/2026-08-21-KOS-EP01-P3-findings-independent-verification.md`, commit `e5f98a6a`) ruled **P3-F1 CONFIRMED (BLOCKING)**: the unread §1 is an explicit current-state inventory whose facts **contradict** two load-bearing current-state claims. This erratum corrects only those claims (§8.1 density · §8.1/§9.3 writer surface · §18 U-11). **No other P3 content is altered; no target architecture, kernel design, EKS/PKS/AIP comparison or durability redesign is introduced.**

| ID | Original claim | Corrected claim | Evidence | Reason | Affected P3 sections |
|---|---|---|---|---|---|
| **E-1** | *"`seq` is dense and monotonic per record ⇒ omission is mechanically detectable."* | Dense-and-monotonic `seq` is **NOT a completeness proof** — the current persistence mechanism does **not** provide completeness merely from dense sequence numbers. | MIGRATION-PLAN §1.3 AMD3 `CL-1` (`docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md`, commit `2f0301c2`): **23 / 30 concurrent-append trials silently lost a transition** (losing process exited 0, reported `{"ok":true,"seq":2}`) while **every survivor was dense and monotonic** (the clobbering writer reuses the lost writer's sequence number). The write is atomic per write (`tmp`+`rename`); the **read-modify-write is not** (no lock, lease, CAS or version check). | The baseline inferred an integrity guarantee the plan's own current-state inventory explicitly withdrew; materiality confirmed by `e5f98a6a` §1.3. | §8.1 |
| **E-2** | *"Exactly one writer: `workflow-state.php` (`saveRecord` from init, append, grant — three sites only)."* | `workflow-state.php` is the **currently identified primary writer** of governance evidence — **not a structurally guaranteed single-writer architecture**. A **second write-capable path** exists: `session-resolve.php:90` reads `getenv('KOS_MECHANISM_PATH')` and `:103` executes the environment-named program via `proc_open`, **handing it the authority-record directory as an argument** (`:156`). `grep -rln file_put_contents .claude/scripts/` returns three files; the third (`session-changes-logger.sh:60`) writes session state, never `runtime/workflow`. | MIGRATION-PLAN §1.3 AMD4 `RC-11` · §1.5 `P-4` / AMD3 `CL-10`. | The baseline omitted the P-4 mechanism axis — a current-state fact about the authority model: *"one writer" is true of the committed code paths today and false as a structural guarantee.* | §8.1 · §9.3 |
| **E-3** | U-11: *"NOT READ in full here; PROPOSED, NOT EXECUTED — **not needed for current-state reconstruction**."* | The **"not needed" clause is withdrawn**. The MIGRATION-PLAN body **has been read in full**: §1 is an explicit current-state inventory (OBSERVED, measured, nothing modified) whose material facts are folded in (E-1/E-2); the remainder is **target-state migration design** that cannot affect current-state reconstruction (PROPOSED, NOT EXECUTED). U-11 is **RESOLVED**. | MIGRATION-PLAN §1/§11/§12 read in full; verification `e5f98a6a`. | The unread body is part of the approved corpus (plan §5.3) and contained current-state facts contradicting the baseline; "not needed" was asserted, not established. | §18 U-11 |

**Inference discipline (binding):** E-1 establishes only that the current mechanism does not provide completeness from dense sequence numbers; E-2 establishes only a current authority/write-surface problem. **Neither implies any future architecture** — no event sourcing, no append-only event store, no kernel design, no durability redesign.

---

## 0 · Method and evidence discipline

### 0.1 Status marking

This reconstruction note is **PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE · NOT ADOPTED**. It reconstructs the current architecture of the **AI Engineering Platform (AIP)** from AIP's own evidence corpus. It is a baseline, not a redesign. It decides nothing; it adopts nothing; it amends nothing. It exists to be reviewed by the Human Principal Architect.

### 0.2 The DO-NOT boundary (Stage 3)

- ⛔ No comparison of AIP with EKS or PKS.
- ⛔ No construction of the EKS–PKS–AIP landscape (that is Stage 4, not performed here).
- ⛔ No classification of shared concepts · no reconciliation ledger · no kernel candidates · no KnowledgeOS design · no evolution proposal.
- ⛔ No reopening of the frozen AIP baseline, no technology change, no implementation change.

### 0.3 Evidence tiers (hierarchy, strongest first)

| Tier | Evidence kind | Example |
|---|---|---|
| **T1** | Implementation code | `.claude/scripts/workflow-state.php`, `session-resolve.php`, hook scripts |
| **T2** | Tests | `WorkflowStateRecordContractTest`, `SessionAssignmentResolverContractTest`, replay test |
| **T3** | Persistence | `.claude/runtime/workflow/*.json` authority records |
| **T4** | ADRs · decisions · governance registrations | ADR-AIP-04, GOV-STATE-DURABILITY ADR, PO/ARB registrations, six-role adoption |
| **T5** | Docs (registry, operating instructions, plans, reviews) | `registry.yaml`, `OPERATING_INSTRUCTIONS.md`, the EP-01 plan, amendment summaries |
| **T6** | C4 diagrams | C4 container/code views (not load-bearing here) |
| **T7** | Brainstorming / unadopted / untracked proposals | the 6-role brainstorming document |
| **T8** | External corroborative | `perpleixity_research_on_roles.md` (untracked, never authority) |

### 0.4 Reality classes

| Class | Meaning |
|---|---|
| **A** | IMPLEMENTED — exists and operates |
| **B** | IMPLICIT — present by convention, not enforced |
| **C** | PARTIAL — some surface exists, not the whole claim |
| **D** | WRONG-BOUNDARY — attributed to the wrong boundary |
| **E** | MISSING — not found in current AIP evidence |
| **F** | CONTRADICTED — records conflict |
| **G** | DOCUMENTED / HYPOTHESIS — described as desired, not evidenced as implemented |
| **UNKNOWN** | No evidence establishes the fact; must remain UNKNOWN |

**Binding rule (P2 discipline):** documentation of desired behaviour is **never** evidence of implementation. **No B/C/G finding is promoted to A.** **No gap is filled to make the reconstruction coherent.** **Contradictions are surfaced, never silently reconciled — both sides are recorded.**

### 0.5 Evidence corpus

**Governed starting corpus (EP-01 plan §5.3, read-only):**

| # | Artifact | Role |
|---|---|---|
| 1 | `.claude/plans/AIP-iteration-1-construction.md` | frozen platform baseline |
| 2 | `docs/knowledgeos/architecture/KOS-AIP04-DISCOVERY-001-capability-architecture-analysis.md` | capability analysis (PROPOSAL) |
| 3 | `docs/knowledgeos/architecture/2026-08-18-KOS-AIP04-DISCOVERY-001-adr-aip-04-capability-discovery-proposal.md` | ADR-AIP-04 (PROPOSAL) |
| 4 | `docs/knowledgeos/reviews/2026-08-19-six-role-operating-model-adoption.md` | six-role adoption (DECISION) |
| 5 | `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-{ADR,IMPLEMENTATION-DESIGN,MIGRATION-PLAN}.md` (+ AMD4/5/6) | governance-state durability chain |
| 6 | `docs/knowledgeos/brainstorming/20260817-145153-ai-engineering-platform-6-role-model.md` | historical, unadopted, untracked |

**Additional AIP evidence admitted by role, with recorded findings (§11):** `registry.yaml`, `OPERATING_INSTRUCTIONS.md`, `.claude/scripts/` inventory, `.claude/runtime/workflow/*.json` live fold, and `docs/knowledgeos/reviews/2026-08-19-KOS-AIP-GOV-STATE-DURABILITY-po-arb-position-registration.md`. Each admission is for a defined role (implementation, persistence, governance) and none changes the frozen corpus.

---

## 1 · Purpose

**AIP is the PublicDigit AI Engineering Platform** — a session-based, registry-first engineering operating model that governs how AI-assisted engineering work is initiated, authorised, executed, verified, and closed inside the `.claude/` workspace. **[T5·A]** It exists so that AI-assisted engineering is **governed, traceable, and auditable**: every artifact is registered with traceability, reviewed/approved as a registry entry, implemented, and verified against the registered intent (the **registry-first workflow**, ARB strategic rule 2026-07-08, binding). **[T5·A]**

Its own operating instructions state the purpose in product terms:

> "The product always has priority over the platform." · "Architecture should become quieter over time; implementation should become louder." · "The platform is successful when engineers notice **fewer documents, fewer decisions, fewer arguments, fewer repeated explanations — and faster, safer implementation of PublicDigit**." **[T5·A]**

The **Product Primacy** principle (AIP-14) is recorded: the platform is never the core domain; every implementation decision answers *"How does this help implement the next PublicDigit feature?"* **[T4·A]**

**Structural reading:** AIP is a **socio-technical operating system** for engineering (the phrase appears in its own historical material as an aspiration), whose current implemented core is a **workflow/authority engine** plus **guard surfaces** — not a product domain. **[T5·B]** The "product" it serves (the election/voting platform, the KnowledgeOS body of knowledge) is outside AIP; AIP governs the *process of building it*.

---

## 2 · Capabilities

### 2.1 The platform registry capability map (declared)

The registry (`registry.yaml`) declares **8 components (CMP-001..CMP-008)** mapped to capabilities **CAP-01..CAP-14** and bounded contexts: **[T5·A]**

| CMP | Component | Capabilities | Bounded context (declared) | Status (declared) | Reality |
|---|---|---|---|---|---|
| CMP-001 | composition_root | — (wires contexts) | none | adopted | A (settings, hooks) |
| CMP-002 | session_manager | CAP-01, CAP-02 | session-continuity | adopted | A (inject-context, session-changes-logger, session-log-reminder) |
| CMP-003 | knowledge_manager | CAP-03 | knowledge-governance | **deferred** | E (NOT built; version 0.0) |
| CMP-004 | workflow_engine | CAP-05, CAP-06 | implementation-guidance | adopted | A (AST-005/006/012/013/014/015/016) |
| CMP-005 | verification_engine | CAP-07, CAP-08, CAP-09 | verification-evidence | under-construction | C (AST-007 adopted; AST-010 planned) |
| CMP-006 | review_engine | CAP-11 | adversarial-review-support | **deferred** | E (NOT built) |
| CMP-007 | drafting_studio | CAP-10, CAP-12 | design-decision-support | adopted-by-reference | A (consumes frozen templates, creates no copies) |
| CMP-008 | platform_registry | CAP-13 | design-decision-support | active | A (AST-009 = this file) |

### 2.2 The capability inventory (ADR-AIP-04 discovery)

ADR-AIP-04 inventories **capabilities C-1..C-20** with per-capability evidence and status **[T4·G — PROPOSAL, decides nothing]**. The accepted **capability map CAP-01..CAP-14 ↔ BC-1..BC-7** (KOS-ARCH-BASELINE-002 v2, accepted) is the load-bearing allocation **[T4·A]**.

### 2.3 The four unowned capabilities (corrected state)

The capability-architecture analysis (PROPOSAL/DECISION PREPARATION) records four capabilities with **no bounded-context owner**, in a corrected executive state **[T4·G, Proposal]**:

| Capability | Existence | Category | Boundary |
|---|---|---|---|
| **C-5** Separation attestation | YES | cross-context control-plane assurance capability | **NOT a bounded context** |
| **C-10** Knowledge distribution | **NOT YET ESTABLISHED** | DEFERRED | applicability + receipt |
| **C-14** Policy enforcement | **CONTESTED** | DEFERRED | (two readings: no distinct capability / policy tiering) |
| **C-19** Communication composition | YES | stewardship / cross-cutting expression concern | **NOT a bounded context** |

The four are analysed as **one control loop**: `policy/knowledge DEFINED (BC-1) → C-10 DELIVERS → C-14 GATES → C-19 EXPRESSES ← C-5 ATTESTS`. **[T4·G]** ⚠️ This is a proposal finding, not an adopted architecture. C-10 and C-14 existence/category remain **UNDECIDED**.

---

## 3 · Actors

### 3.1 The adopted six-role operating model

**DECISION (PO/ARB, 2026-08-19):** the **six-role engineering operating model** is ADOPTED as AIP's engineering operating model **[T4·A]**: **Governance Engineer · Architecture Engineer · Implementation Engineer · Verification Engineer · Knowledge Engineer · Communication Engineer.**

**Load-bearing non-equivalences (binding):** role ≠ bounded context ≠ capability ≠ agent ≠ platform service ≠ organisational position. **"The adopted role model must not be used as proof that a capability or bounded context exists."** **[T4·A]**

### 3.2 Lane roles (BC-7)

BC-7 (Governed Session Orchestration, ADR-AIP-03, Supporting Subdomain) owns the executable **lane-role model** — the roles that operate each governed work lane (e.g. `REGISTER`, `HANDOFF`, `START`, …). Lane roles are distinct from the six adopted operating-model roles and from human personas. **[T4·A]**

### 3.3 Humans and the ARB

Human authority: the **PO/ARB** is the reserved decision authority; grants register a recorded human act by reference and **never manufacture authority** (`G-2`/`R5b`). **[T4·A]** The four-stage chain is recorded in AIP's own evidence:

```
Architecture proposal → Principal Architect recommendation → PO/ARB decision → Governance registration
```

### 3.4 Organisations

AIP's evidence records that it is designed to be adopted by organisations (the Platform≙Adoption separation observation, §21), and that the separation of platform concepts from a single adoption's evidence is driven by **the first successful adoption in another project**. **[T5·B/G]**

---

## 4 · Ubiquitous language (AIP's own)

AIP's own vocabulary, as evidenced in registry/ADR/workflow records:

| Term | Meaning (as AIP uses it) | Evidence |
|---|---|---|
| **registry** | machine-readable runtime configuration; single authoritative inventory of components/assets | T5·A |
| **component (CMP-nnn)** | abstraction with stable id; never bound to a technology | T5·A |
| **asset (AST-nnn)** | concrete implementation of a component; stable id, path may change | T5·A |
| **work item** | the governed unit of work (e.g. `KOS-AIP04-DISCOVERY-001`) | T3·A |
| **lane** | the per-work-item execution track with role sequence | T3·A |
| **grant** | a registered authority act, referencing a recorded human act (`humanActRef`) | T3·A |
| **transition** | an append-only state change in the workflow record | T3·A |
| **fold** | reading the transition log to derive current state at read time | T3·A |
| **sessionRegistry ≠ authorityState** | the session record is not the authority record; never merged | T4·A |
| **state = fold** | no derived state is persisted; state is recomputed | T3·A |
| **runtime moment** | `SESSION_START · PRE_ACTION · POST_ARTIFACT · SESSION_END · ON_DEMAND` | T5·A |
| **loading order** | `configuration → registry → knowledge → rules → hooks → commands → agents` | T5·A |
| **"role" (collision)** | BC-7 lane role **vs** operating-model engineer role **vs** decision-right owner — three senses | T4·A (recorded collision) |

⚠️ **Ubiquitous-language collision (recorded, not reconciled):** the word **role** is overloaded in AIP's own corpus across three senses (BC-7 lane role, six-role operating model, ARB decision-right allocations). ADR-AIP-04 records this collision explicitly. **[T4·A]**

---

## 5 · Ownership

### 5.1 Accepted bounded-context candidates (BC-1..BC-7 ↔ CAP-01..14)

| BC | Name | Capabilities | Built? | Evidence |
|---|---|---|---|---|
| **BC-1** | Knowledge Governance | CAP-03 | **NOT BUILT — convention only** | T4·A (declared) · T5·E (no implementation) |
| **BC-2** | Implementation Guidance | CAP-05/06 | A (workflow_engine, drafting) | T1·A |
| **BC-3** | Verification & Evidence | CAP-07/08/09 | C (guard surface; gate execution planned) | T1·A / T1·G |
| **BC-4** | Adversarial Review | CAP-11 | E (review_engine deferred) | T5·E |
| **BC-5** | Design & Decision Support | CAP-10/12/13 | A (drafting_studio, platform_registry) | T1·A |
| **BC-6** | Session Continuity | CAP-01/02 | A (session_manager) | T1·A |
| **BC-7** | Governed Session Orchestration | CAP-14 | A (workflow engine: AST-015/016) | T1·A · T2·A · T3·A |

**Ownership discipline (recorded):** BC-1 owns knowledge governance but **is not built**; the six-role model's Knowledge Engineer and Communication Engineer are **adopted as operating-model roles, not as bounded contexts** and not as proof that knowledge/communication contexts exist. **[T4·A]**

### 5.2 Unowned capabilities

C-5, C-10, C-14, C-19 have **no owning bounded context** (§2.3). **[T4·G]**

### 5.3 Registry ownership

Components carry a declared `owner` (e.g. "Architecture Review Board" for CMP-001, "Session Continuity" for CMP-002). **[T5·A]** Ownership in the registry is governance accountability, not a bounded context. **[T5·A]**

---

## 6 · Authority

### 6.1 Registry-first authority

The registry-first workflow is binding: (1) register with full traceability → (2) review/approve the registry entry → (3) implement → (4) verify against registered intent. Every asset answers five questions (R-17): capability → bounded context → architecture principle → platform decision → ADR. An asset that cannot answer them "shall not be registered — and shall not exist." **[T5·A]**

### 6.2 Workflow authority

- **Grants** register authority; a grant without a `humanActRef` is refused by the engine ("the record never manufactures authority — G-2/R5b"). **[T3·A / T4·A]**
- **Exactly one mutation owner** per work item is a mechanically enforced invariant (I-1). **[T2·A]**
- **Closure is a governance act** (G-1): COMPLETE refuses any writer but governance/human. **[T2·A]**
- **Producer ≠ acceptor** (R-34, INV-ATTR-2): a process cannot verify/accept its own work; separation is declared, not attestable. **[T4·A]**

### 6.3 The authority record vs the session record

AIP's own invariant: **I-4** registry ≠ authority, never merged; **I-10** the record outranks prose. **[T4·A]** The authority record is the per-work-item transition+grant log; the session registry is separate and is not authority. **[T4·A]**

### 6.4 The GOV-STATE-DURABILITY authority decision state — **CONTRADICTION SURFACED**

Two records disagree on whether the durability decision was made. **Both are recorded; neither is reconciled here.**

- **Runtime authority record (T3·A):** the work item `KOS-AIP-GOV-STATE-DURABILITY-ADR` carries a grant `G-KOS-GOV-STATE-DURABILITY-DECISION` whose `humanActRef` quotes a PO/ARB DECISION ACT 2026-08-19: **"DECISION 1 — DURABILITY MODEL. SELECT: B′ — Governance Evidence Relocation." · "DECISION 2 — R-CONFLICT. SELECT: ADOPT." · "PLACEMENT GOVERNANCE … no new placement rule … OQ-2 remains open under its existing owner."** The IMPLEMENTATION-DESIGN and MIGRATION-PLAN consistently treat D1 (B′ relocation) and D2 (R-CONFLICT) as **adopted**. **[T4/T5·A]**
- **Tracked registration review (T4/T5·F):** `…po-arb-position-registration.md` records, at its last act, **D1 NOT SELECTED · D2 NOT SELECTED · DECISION.md NOT PRODUCED**, and its appended DECISION ACT section records the selections as blank. Its state box ends: `D1 🟡 NOT SELECTED · D2 🟡 NOT SELECTED`. **[T4/T5·F]**

**UNKNOWN / contradiction:** the precise act sequence by which D1/D2 passed from "NOT SELECTED" (per the registration review) to "SELECT B′ / ADOPT" (per the runtime grant) is **not established by the current corpus**. The runtime authority record is the authoritative workflow state per AST-015 (state = fold), so the operative recorded state is D1 = B′ SELECTED, D2 = ADOPT, **but the tracked registration review contradicts that and is not amended**. Both sides stand. **[F → UNKNOWN]**

---

## 7 · Lifecycle

### 7.1 Workflow lifecycle (AST-015)

States: **REGISTER · HANDOFF · START · CONTINUATION · STOP · COMPLETE · FAIL · CANCEL**. **[T1/T3·A]**

### 7.2 Mechanically enforced invariants

| Invariant | Meaning | Enforced |
|---|---|---|
| **I-1** | exactly one mutation owner | mechanically (tests) |
| **I-2** | HANDOFF ∧ human START (neither alone yields ACTIVE) | mechanically |
| **I-3** | STOPPED is sticky (only CONTINUATION exits) | mechanically |
| **G-1** | closure is a governance act (COMPLETE refuses non-governance writers) | mechanically |
| **G-2** | Authority State has exactly one writer (grant writer rule R5a/R5b) | mechanically |

**[T2·A]** — all contract-pinned by tests.

### 7.3 Declared-only invariants (NOT mechanically enforced)

| Invariant | Meaning | Enforcement reality |
|---|---|---|
| **R-34** | producer ≠ acceptor | declared; attestation unattestable (INV-ATTR-2) |
| **INV-ATTR-2** | separation is declared, not attestable | declared |
| **AIP-11** | supersede-never-in-place | declared |
| **AIP-14** | Product Primacy | declared |
| **I-4** | registry ≠ authority, never merged | declared |
| **I-10** | the record outranks prose | declared |

**[T4·A]**

### 7.4 **Headline structural finding — the invariant asymmetry**

Every invariant BC-7 (the workflow engine) owns is **mechanically enforced**; almost every invariant outside BC-7 is **declared only**. Assurance is strong where it is executable and weak where it is prose. **[T2·A vs T4·A]** This asymmetry is the single most important structural property of AIP's current architecture: **the platform mechanically protects orchestration, and protects knowledge/verification/communication only by declaration.**

---

## 8 · Persistence

### 8.1 The authority records

- One file per work item under `.claude/runtime/workflow/<work-item>.json`, containing `schema · workItem · workflow · roles · transitions · grants`. **[T3·A]**
- **No derived state is persisted** (schema, workItem, workflow, roles, transitions, grants only); `mutationOwner`, `sessions`, `workItemState` are **folded at read time**. **[T3·A]**
- **Writes are atomic** (`tmp` + `rename`) per write. **`seq` is dense and monotonic per record** — but **density is NOT a completeness proof** (corrected, **E-1**): the technical review reproduced **23 / 30 concurrent-append trials silently losing a transition** while **every survivor was dense and monotonic** (the clobbering writer reuses the sequence number the lost writer took); the **read-modify-write is not atomic** (no lock, lease, CAS or version check). **[T3·A corrected by MIGRATION-PLAN §1.3 AMD3 CL-1]**
- **Only 2 of 218 transitions carry any date** (measured 2026-08-21 22:59); order is `seq` alone, no systematic time dimension. **[T3·A]**
- **Currently identified primary writer of governance evidence:** `workflow-state.php` (`saveRecord` from init, append, grant — three sites only). ⚠️ **Not a structurally guaranteed single-writer architecture** (corrected, **E-2**): `session-resolve.php:90` reads `getenv('KOS_MECHANISM_PATH')` and `:103` executes the environment-named program via `proc_open`, **handing it the authority-record directory as an argument** (`:156`) — a **write-capable substitution path** through a component whose own bytes contain no write call. **[T3/T1·A corrected by MIGRATION-PLAN §1.3 AMD4 RC-11 · §1.5 P-4/CL-10]**
- Readers: `workflow-state.php`, `session-resolve.php`, `registry.yaml` (asset metadata), prose traceability lines. **[T3/T1·A]**

### 8.2 Current volume (measured)

Live fold of `.claude/runtime/workflow/` on **2026-08-21 22:59** (this session):

```
records:       18
transitions:  218
grants:       126   (125 unique grantIds)
```

Documented prior measurement points: ADR written 16/210/99 · IMPLEMENTATION-DESIGN START 17/213/102 · AMD5 verified 18/216/110 · AMD6 verified 18/216/114 (113 unique). **[T3·A]** The estate grows while the migration is unimplemented.

### 8.3 The durability inversion (**headline structural finding**)

The runtime directory — the **only** gitignored `.claude/` subdirectory (`.gitignore:25/32`) — holds the estate's **most authoritative** records (grants, transitions), while its own name says "runtime." The ADR's measured finding stands: **there is no runtime state in the runtime directory** — governance evidence is misfiled into an ephemeral, ignored location. **[T4·A]** The load-bearing DDD statement recorded from the PO/ARB: *"An aggregate cannot depend on accidental reconstruction."* **[T4·A]** Documented consequence (commit `de998173`, 2026-08-19): the narrative lineage (16 review documents) was committed while **all grants and transitions remained outside git**. **[T4·A]**

### 8.4 Migration status

The GOV-STATE-DURABILITY migration (B′ relocation) is **NOT EXECUTED, NOT AUTHORIZED TO EXECUTE, MUST NOT BEGIN**. The IMPLEMENTATION-DESIGN is **PROPOSED — DESIGN ONLY**; the MIGRATION-PLAN is **PROPOSED, AMENDED (AMD3/4/5/6)**; AMD3/4/5/6 **unregistered as delivered (OPEN-M6)**; **OPEN-M1..M7 open**. Phase 5 remains prohibited. **[T4·A]**

---

## 9 · Runtime

### 9.1 What executes

| Asset | Component | Moment(s) | Function |
|---|---|---|---|
| AST-001 `.claude/settings.json` | CMP-001 | START/PRE_ACTION/POST_ARTIFACT/END | wiring (hooks) |
| AST-002 `inject-context.sh` | CMP-002 | SESSION_START | deterministic plan resolution |
| AST-003 `session-changes-logger.sh` | CMP-002 | POST_ARTIFACT | repo-relative fact log, derived state json |
| AST-004 `session-log-reminder.sh` | CMP-002 | SESSION_END | non-blocking reminder |
| AST-005 `discipline-gate-reminder.sh` | CMP-004 | PRE_ACTION | non-blocking DDD-gate tripwire |
| AST-006 `dev-guide-reminder.sh` | CMP-004 | SESSION_END | non-blocking guide reminder |
| AST-007 `db-safety-check.sh` | CMP-005 | PRE_ACTION | **Tier-1 blocking gate** (db safety) |
| AST-010 `run-gates.sh` | CMP-005 | ON_DEMAND | **planned** (not yet implemented) — first gate execution |
| AST-012 `.claude/CLAUDE.md` | CMP-004 | SESSION_START | doctrine surface (advisory) |
| AST-013 `OPERATING_INSTRUCTIONS.md` | CMP-004 | SESSION_START | operating doctrine |
| AST-014 `ddd-principles-reminder.sh` | CMP-004 | PRE_ACTION | non-blocking reminder |
| AST-015 `workflow-state.php` | CMP-004 | ON_DEMAND | workflow state record: read/fold/append/grant |
| AST-016 `session-resolve.php` | CMP-004 | ON_DEMAND | read-only Session Assignment Resolver |
| AST-009 `registry.yaml` | CMP-008 | START/ON_DEMAND | runtime configuration |
| AST-011 IDD template | CMP-007 | ON_DEMAND | consumed by reference |

**[T5·A declaration] · [T1·A for implemented scripts]**

### 9.2 Loading order

`configuration → registry → knowledge → rules → hooks → commands → agents` (ARB amendment 3: knowledge loads before rules). **[T5·A]**

### 9.3 Constraints

- **AST-015/016 are advisory** — violations are visible/adjudicable, not physically prevented (Increment-2 not authorized). **[T1/T4·A]**
- **AST-016 V-3 is an OPEN ARCHITECTURE/SPECIFICATION GAP**: it cannot distinguish a recorded handoff from an absent one; it fails safe. Remedy UNDECIDED. PO/ARB condition (BINDING and OUTSTANDING): V-3 **must** be resolved before AST-016 is wired into SESSION_START or any automatic startup path. **[T4·A]**
- **Path sources are THREE, on TWO axes** (corrected, **E-2**; MIGRATION-PLAN §1.5): *where the record lives* — **P-1** default `workflow-state.php:81`, **P-2** default `session-resolve.php:74` (two independent defaults, RA-2), **P-3a/b** the two `--dir` overrides; *which mechanism interprets it* — **P-4** `session-resolve.php:90` `getenv('KOS_MECHANISM_PATH')` → `:103` `proc_open` → `:156`, a **write-capable** substitution path. The mechanism already supports relocation; only the default contradicts B′. **[T1/T4·A corrected by MIGRATION-PLAN §1.5]**

---

## 10 · Workflows and integrations

### 10.1 Core workflows

1. **Registry-first construction** (register → approve → implement → verify). **[T5·A]**
2. **Governed lane workflow** (REGISTER → HANDOFF → START → … → COMPLETE/FAIL/CANCEL) with the mutation-owner and START-stickiness invariants. **[T3/T2·A]**
3. **The operating loop** (Business capability → Strategic DDD → Canonical Discovery → Tactical DDD → Stewardship → Impact classification → Readiness → RED → GREEN → VERIFY → ACCEPT → Operational Evidence → PKS classification → KnowledgeOS promotion check). **[T4/T5·A — the runtime binding; the full fillable form is PROPOSED]**
4. **Construction discipline** (one objective, one approved plan, one completion review, one stopping point). **[T5·A]**

### 10.2 Integrations

- **Hooks** wired through `.claude/settings.json` at the five runtime moments. **[T1·A]**
- **Tests** pin the workflow engine contracts (`WorkflowStateRecordContractTest` R1–R8, replay 65/69; `SessionAssignmentResolverContractTest` T-1..T-15). **[T2·A]**
- **Persistence** = the runtime workflow records (T3) + session logs + `docs/` governed artifacts. **[T3·A]**
- **The registry** (AST-009) is consumed at START and ON_DEMAND as runtime configuration; human documentation is generated from it, never the reverse. **[T5·A]**
- **Drafting studio** consumes frozen templates by reference (no parallel copies). **[T5·A]**

---

## 11 · Evidence and corpus-boundary findings

### 11.1 Evidence classes (AIP's own)

| Class | Meaning | Examples |
|---|---|---|
| **A — GOVERNED** | committed/registered/decided | registry.yaml, ADRs, decisions, grants |
| **B — UNGOVERNED** | untracked diagrams, the 925-line role document, brainstorming | `…6-role-model.md` (status authoritative:false) |
| **C — EXTERNAL/CORROBORATIVE** | never authority | `perpleixity_research_on_roles.md` |

**[T4/T5·A]**

### 11.2 Additional evidence admitted by role (recorded findings)

| Admission | Role | Finding |
|---|---|---|
| `registry.yaml` | implementation/declared inventory (T5·A) | gives the 8-component/16-asset declared structure; some assets are T1·A |
| `OPERATING_INSTRUCTIONS.md` | operating doctrine (T5·A) | confirms Product Primacy, economies, construction discipline |
| `.claude/scripts/` inventory | implementation (T1·A) | confirms the executed guard surface and the two PHP engines |
| `.claude/runtime/workflow/*.json` live fold | persistence (T3·A) | confirms 18/218/126(125) and the D1/D2 decision grant |
| `…po-arb-position-registration.md` | governance registration (T4/T5) | source of the D1/D2 NOT SELECTED contradiction |
| GOV-STATE-DURABILITY DECISION.md | **not produced** | ⛔ recorded as NOT PRODUCED by the registration review |

### 11.3 Quarantined — never current-architecture evidence

The proposed KnowledgeOS architecture **Review Set** (`docs/knowledgeos/architecture/00…07-*`), kernel-extraction analyses, and the Digitalization Robot appear in this reconstruction **only as "Not established by current AIP evidence."** They are not used as current architecture. **[corpus rule]**

---

## 12 · Governance

### 12.1 Governing acts (recorded)

- **AIP-iteration-1 construction plan** — APPROVED WITH AMENDMENTS, ARB, 2026-07-08 (frozen baseline). **[T4·A]**
- **ADR-AIP-01** — Baseline v1.0, Reference Implementation Pending; ARB declared decision authority. **[T4·A]**
- **ADR-AIP-03** — BC-7 Governed Session Orchestration (accepted). **[T4·A]**
- **Six-role operating model** — ADOPTED 2026-08-19. **[T4·A]**
- **GOV-STATE-DURABILITY** — ADR PROPOSED; D1/D2 decision state contradictory (§6.4); **migration NOT executed**. **[T4/T3]**
- **KOS-ARCH-BASELINE-002 v2** — capability map CAP-01..14 ↔ BC-1..BC-7 accepted. **[T4·A]**

### 12.2 Governance gaps (AIP's own records)

- **C-5 separation** exists; **access/artifact isolation NOT ESTABLISHED**; organizational independence **NOT_ESTABLISHED** (not NOT_APPLICABLE). **[T4·G]**
- **Four forbidden pairings all OPEN** (producer/verifier/acceptance boundaries). **[T4·G]**
- **AMD3/4/5/6 unregistered (OPEN-M6)**; **OPEN-M1..M7 open**. **[T4·A]**
- **AIP's own recorded capability failures (EKS-01..04):** knowledge-distribution failure (a tokenRef taught a lane the superseded path); policy-enforcement coverage gap (*"the rule exists, but the workflow does not make it hard enough to violate"*); evidence-identity path-coupling (39→40 tokenRefs hard-code paths); lifecycle-interpretation ambiguity (*"handed to the next role"* ≠ *"session formally closed"*). **[T4·A]** These are AIP observing its own gaps — evidence that the platform's assurance is uneven (§7.4).

---

## 13 · What AIP owns · consumes · produces · does NOT own

### 13.1 Owns

- The **workflow/authority engine** (AST-015) and the **session-assignment resolver** (AST-016) — orchestration mechanics. **[T1·A]**
- The **registry** (AST-009) and its construction process. **[T5·A]**
- The **guard surface** (AST-005/006/007/014, db-safety as Tier-1 blocking). **[T1·A]**
- The **session-continuity assets** (AST-002/003/004). **[T1·A]**
- The **platform operating doctrine** (AST-013) and the `.claude/CLAUDE.md` doctrine surface (AST-012). **[T5·A]**
- **Ownership of invariants it mechanically enforces** (I-1, I-2, I-3, G-1, G-2). **[T2·A]**

### 13.2 Consumes

- Human authority acts (via `humanActRef`). **[T3/T4·A]**
- Frozen product/document templates by reference (drafting studio). **[T5·A]**
- `docs/` governed knowledge as input to its own loading order (knowledge before rules). **[T5·A]**
- The `.claude/runtime/workflow/*` records it reads and extends. **[T3·A]**

### 13.3 Produces

- The **authority record** (grants, transitions) — the estate's most authoritative artifact. **[T3·A]**
- **Session logs** and **fact logs** (session-changes-logger). **[T1·A]**
- **Guard-reminder signals** (non-blocking) and **blocking gate verdicts** (db-safety). **[T1·A]**
- **Registry-driven configuration** and generated documentation. **[T5·A]**
- **Verification reports, findings, decision records, acceptance records** (as governance evidence). **[T4·A]**

### 13.4 Explicitly does NOT own

- **The product domain** (the voting platform / PublicDigit) — Product Primacy (AIP-14): the platform never the core domain. **[T4·A]**
- **Knowledge governance** — BC-1 is **NOT BUILT**; the Knowledge Engineer is an adopted operating-model role, not an implemented context. **[T4·A]**
- **Adversarial review automation** — CMP-006 review_engine deferred; reviews are human. **[T5·E]**
- **Policy enforcement as a distinct implemented capability** — C-14 existence CONTESTED/DEFERRED. **[T4·G]**
- **Knowledge distribution as an established capability** — C-10 existence NOT YET ESTABLISHED/DEFERRED. **[T4·G]**
- **Communication composition as a bounded context** — C-19 stewardship/cross-cutting, NOT a bounded context. **[T4·G]**
- **Runtime/execution state of the product** — AIP's runtime governs the engineering process, not the product's execution. **[T4·A]**

---

## 14 · Relationship to EKS (AIP's self-declared stance — not a comparison)

Stage 3 records only what AIP's own evidence says about EKS. **The EKS–PKS–AIP landscape construction is Stage 4 and is NOT performed here.**

- AIP's own discovery records **EKS-01..EKS-04** as capability-failure observations (§12.2). These are AIP's self-recorded evidence that its own capabilities (knowledge distribution, policy enforcement, evidence identity, lifecycle interpretation) exhibit gaps when exercised. **[T4·A]**
- AIP's evidence treats these as **platform observations about its own operation** — not as a comparison with EKS. Any statement beyond this (which side of a boundary EKS occupies, whether EKS is a sibling system, a target, or an adoption) is **UNKNOWN** in the current AIP corpus and is left to Stage 4. **[UNKNOWN]**

---

## 15 · Relationship to PKS (AIP's self-declared stance — not a comparison)

- AIP's own evidence records the **ARB observation "Platform ≙ Adoption separation"**: platform concepts and PublicDigit adoption evidence are **interwoven in the same documents**; the separation is expected to be driven by the **first successful adoption in another project**. **[T5·B/G]**
- AIP's evidence also distinguishes, for its own model, the platform layer (`.claude/`) from the domain being built (the product), mirroring the framework-vs-application split. **[T5·B]**
- Beyond these self-declarations, the PKS↔AIP boundary is **UNKNOWN** in the current AIP corpus and is left to Stage 4. **[UNKNOWN]**

---

## 16 · Knowledge-concern vs platform-concern — candidate split (evidence/finding, NOT decided)

The reconstruction records a **candidate split** present in AIP's own evidence; it does **not** decide the boundary.

| Concern | Candidates in AIP evidence | Reality |
|---|---|---|
| **Knowledge concern** | BC-1 Knowledge Governance (CAP-03) — **NOT BUILT**; Knowledge Engineer (memory) role; knowledge lifecycle/evidence linking | E / adopted-role-only |
| **Assurance concern** | BC-3 Verification & Evidence, BC-4 Adversarial Review | C / E |
| **Platform/mechanical concern** | BC-7 Governed Session Orchestration (CAP-14), workflow engine, registry | A |
| **Communication concern** | C-10 knowledge distribution (NOT ESTABLISHED) · C-19 communication composition (stewardship) · Communication Engineer (nervous-system framing in unadopted history) | F/G |
| **Boundary decision** | **OQ-D (EKP/PKS boundary) is OPEN — deliberately not decided** | UNKNOWN |

**[T4/T5]** The KnowledgeOS review-set boundary questions, the EKP/PKS boundary, and the knowledge-vs-platform split remain **UNDECIDED** by current AIP evidence.

---

## 17 · Headline structural findings

1. **AIP is a registry-first, session-based engineering operating platform** — a socio-technical operating system for AI-assisted engineering, not a product domain. Product Primacy keeps the product outside the platform. **[T5/T4·A]**
2. **The implemented core is the workflow/authority engine (AST-015/016) plus guard surfaces.** Components CMP-003 (knowledge) and CMP-006 (review) are **deferred/not built**; CMP-005 verification is under-construction (one blocking guard, gate execution still planned). **[T1/T5]**
3. **The invariant asymmetry is the load-bearing structural property:** BC-7's invariants are mechanically enforced; knowledge/verification/communication invariants are declared-only prose. Assurance is strong where executable, weak where prose. **[T2·A vs T4·A]**
4. **The authority record lives in an ignored runtime directory** — the durability inversion. The B′ relocation is decided in the runtime authority record but **NOT EXECUTED**; the estate grows (18/218/126 at measurement). **[T3·A]**
5. **The D1/D2 decision state is contradictory** across the corpus: runtime grant records SELECT B′ / SELECT ADOPT; the tracked registration review records NOT SELECTED. Surfaced, not reconciled. **[T3·F / T4/T5·F]**
6. **Four unowned capabilities form one control loop** (defined → deliver → gate → express ← attest), with C-10 and C-14 existence UNDECIDED. **[T4·G]**
7. **AIP self-records four capability failures (EKS-01..04)** — the platform observing its own uneven assurance. **[T4·A]**

---

## 18 · UNKNOWN entries

| # | Item | Status |
|---|---|---|
| U-1 | C-10 Knowledge distribution — existence | **NOT YET ESTABLISHED** (category DEFERRED) |
| U-2 | C-14 Policy enforcement — existence | **CONTESTED** (category DEFERRED) |
| U-3 | The act sequence that took D1/D2 from NOT SELECTED to SELECT B′/ADOPT | **UNKNOWN** (contradiction surfaced, §6.4) |
| U-4 | Whether the six-role operating model maps to any platform service/agent | **NOT ESTABLISHED** (role ≠ agent ≠ service, binding) |
| U-5 | The EKP/PKS boundary (OQ-D) | **OPEN — deliberately not decided** |
| U-6 | The knowledge-concern vs platform-concern boundary | **UNDECIDED** (candidate split only, §16) |
| U-7 | AST-016 V-3 remedy (handoff-distinguishability gap) | **UNDECIDED** (condition binds against SESSION_START wiring) |
| U-8 | C-5 access/artifact isolation; organisational independence | **NOT_ESTABLISHED** (not NOT_APPLICABLE) |
| U-9 | The four forbidden pairings (producer/verifier/acceptance) | **ALL OPEN** |
| U-10 | EKS's and PKS's place relative to AIP (boundaries, side of the split) | **UNKNOWN — left to Stage 4** |
| U-11 | The full internal body of the 196KB MIGRATION-PLAN (beyond header + AMD4/5/6) | **RESOLVED by P3-F1 evidence completion** (2026-08-21, erratum **E-3**): read in full; §1 is an explicit current-state inventory (OBSERVED, measured, nothing modified) whose material facts are folded into §8.1/§9.3 (E-1/E-2); the remainder is **target-state migration design** that cannot affect current-state reconstruction (PROPOSED, NOT EXECUTED). **"Not needed" clause withdrawn.** Evidence: MIGRATION-PLAN §1/§11/§12 · verification `e5f98a6a` |
| U-12 | Whether `KOS-AIP-GOV-STATE-DURABILITY-DECISION.md` exists | **NOT PRODUCED** (per registration review; not found in the architecture directory) |

---

## 19 · Verification checklist (DoD subset, binding)

- ✅ No implementation changes made (this session created only the reconstruction note; the working tree's pre-existing unrelated changes were not touched).
- ✅ Implemented ≠ conceptual: every claim is tier-tagged; no B/C/G promoted to A; no gap filled.
- ✅ AIP read-only: no redesign, no frozen-baseline reopening, no technology change.
- ✅ Output marked **PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE · NOT ADOPTED**.
- ✅ Review Set never used as current architecture (§11.3 quarantine).
- ✅ No source content lost; no existing artifact renamed.
- ✅ Contradictions surfaced, not reconciled (§6.4).
- ✅ UNKNOWN remains UNKNOWN (§18).
- ✅ No comparison of AIP with EKS/PKS; no landscape construction; no reconciliation ledger; no kernel candidates; no KnowledgeOS design; no evolution proposal (§0.2).
- ✅ Stage 4 NOT performed.

---

## 20 · Traceability

- **Plan:** `docs/plans/20260821-2118-eks-pks-aip-current-architecture-landscape-knowledgeos-evolution-study-plan.md` (§5.3 corpus; §8 commit message).
- **Frozen baseline:** `.claude/plans/AIP-iteration-1-construction.md`.
- **Corpus (read-only inputs):** ADR-AIP-04 discovery proposal · KOS-AIP04 capability-architecture analysis · six-role adoption review · GOV-STATE-DURABILITY ADR / IMPLEMENTATION-DESIGN / MIGRATION-PLAN (+AMD4/5/6) · 6-role brainstorming document.
- **Additional evidence admitted by role:** `.claude/platform/registry.yaml` · `.claude/platform/OPERATING_INSTRUCTIONS.md` · `.claude/scripts/` · `.claude/runtime/workflow/*.json` (live fold) · `…po-arb-position-registration.md`.
- **Placement:** `docs/knowledgeos/architecture/` per `php scripts/doc-placement.php` (product-specific, domain knowledgeos — as established by the corpus artifacts already placed there).
- **Erratum P3-F1 (2026-08-21):** bounded evidence completion per HPA review `c673de5d` · independent verification `e5f98a6a` · MIGRATION-PLAN §1/§11/§12 read in full. Corrects §8.1, §9.3 and §18 U-11 only.
- **Status:** **PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE · NOT ADOPTED.** Corrected by erratum **P3-F1** (bounded evidence completion). **Next actor: Human Principal Architect re-review — P4 remains CLOSED until the corrected baseline receives HPA approval.**
