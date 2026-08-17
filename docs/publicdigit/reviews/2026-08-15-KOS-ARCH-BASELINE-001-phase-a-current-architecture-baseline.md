# KnowledgeOS — Current Architecture Baseline v1.1
## KOS-ARCH-BASELINE-001 · Phase A · Reconstruction from Evidence

**Session 4 — `S4-architecture-baseline` · ACTIVE, mutation owner (resolver-verified before analysis) · grant `G-KOS-ARCHBASE-A` — PHASE A ONLY · 2026-08-15**

> ## v1.1 — CORRECTED 2026-08-17 · **ACCEPTED 2026-08-17** as the authoritative reconstructed current-state architecture baseline (PO/ARB act; registration: `docs/knowledgeos/reviews/2026-08-17-KOS-ARCH-BASELINE-001-phase-a-acceptance-registration.md` — Stage-2 analysis authorized only; no bounded context, aggregate, implementation architecture, technology or migration decision approved). Accepted content: `40026b12`; this banner line is a lifecycle annotation, the sole post-verification change.
> **Bounded precision corrections only**, under grant `G-KOS-ARCHBASE-A-CORRECT`, repairing the defects confirmed by independent verification #1 (`2026-08-17-…-phase-a-independent-verification.md`): **D-1**/**V-A** (hook count 8→10, incl. the §6 caption and the §11 row) · **D-2** (executable-substance count) · **D-3** (7-vs-8 census, new §6.4) · **V-B** (why `CAP-04` has no row) · **V-D** (method attached to the dependency scan) · **V-C** (duplicate `.gitignore` citation). **V-E is not correctable** and is recorded as an acceptance-context note in §5.1.
>
> **The snapshot is unchanged: this remains a reconstruction as of 2026-08-15.** Figures are corrected to what was true *at* the snapshot — never updated to today's state. The reconstruction scope is unchanged; no new investigation was performed. **No conclusion of v1.0 was reversed** — the verifier found the thesis sound and the defects confined to precision and internal consistency.
>
> **R-34 forward constraint:** the process that applied these corrections **must not perform their re-verification.**

> **This document reconstructs what KnowledgeOS *is*. It contains no target architecture, no redesign, no recommendation, and no remediation.** Where something looks wrong, it is recorded as a finding and left alone. Phase B (assessment) and Phase C (target) are **not authorized** and are not begun here.
>
> **Startup gate (commission §1):** work item `KOS-ARCH-BASELINE-001` ✅ · assignment `S4-architecture-baseline` ✅ ACTIVE + mutation owner ✅ · grant `G-KOS-ARCHBASE-A` AUTHORIZED, Phase A only ✅. *I am authorized to reconstruct the current KnowledgeOS architecture. I am NOT authorized to redesign, implement, remediate, verify, qualify, or adopt.*
>
> **Self-authorship disclosure (commission §5, mandatory):** a substantial portion of what is reconstructed below — the workflow record mechanism, the resolver, the role model, the attribution invariants, and most 2026-08-14/15 governance artifacts — was produced by *this same broader process*. Everything so originating is classified **`Declared`**, never `Observed`, unless I re-measured it this session. §11 lists the affected elements. **This baseline is therefore not independently validated, and Session 1's falsification pass should weight self-authored elements hardest.**

---

# 1 · Executive Summary

**KnowledgeOS is a governance-first AI engineering platform whose authority model is fully realized in convention and documents, and whose *mechanism* is realized in four small scripts plus one JSON record type.** It is not an orchestrator, not a workflow engine in the executable sense, and not an agent framework. Its distinguishing property is inverted from typical platforms: **the rules are dense and mature; the code is deliberately thin.**

Five statements that characterize the system as measured:

1. **Two architectures coexist, and they are not the same size.** A *declared* architecture of 6 bounded contexts + **7 components** exists as frozen ARB-approved documents (`Observed` as documents; `Declared` as architecture) — while the **live registry carries 8** (`CMP-001…008`); the extra is **`CMP-001 composition_root`, absent from the declared table** *(corrects **D-3**; §6.4)*. An *executing* architecture consists of **10 wired hooks** *(corrects **D-1**/**V-A**)*, 12 scripts, 16 registered assets, and 8 workflow records. **Two of the eight registered components have no assets at all** (`CMP-003`, `CMP-006`); two more are partial or by-reference (`CMP-005`, `CMP-007`) *(corrects **D-2**; consistent with §3.1's own table, which the superseded wording contradicted)*.
2. **The platform's real substance is its rule corpus, not its runtime.** `ES-001`…`ES-006`, the AIP principles, the ADR-AIP rulings register, and the KnowledgeOS orchestration rulebook (amendments `A-1`…`A-8`) constitute the bulk of the system. All six ES standards are **`PROPOSED`, none ratified** (measured).
3. **Exactly one component carries the new mechanism.** `workflow_engine` (CMP-004) holds 7 of 16 assets including both `AST-015` (the record mechanism) and `AST-016` (the resolver). It was described as "paper" three days ago; it is now the platform's only executable governance surface.
4. **Authority is deliberately *not* automated.** No mechanism grants, blocks, or evaluates authorization. `AST-015` refuses malformed transitions; it does not decide who may act. Every gate that matters — START, approval, qualification, closure — is a human act that Governance *registers*. This is architecture, not an omission (`R-37`: governance precedes automation).
5. **The platform's own authoritative state is untracked.** All 8 workflow records live in `.claude/runtime/workflow/`, **gitignored** — no history, no provenance, no recovery. The system that exists to make authority auditable keeps its own state in the one place git cannot see.

---

# 2 · System Boundary

| | Content | Classification |
|---|---|---|
| **Inside KnowledgeOS** | the rule corpus (`engineering/governance/ES-*`, `engineering/architecture/baseline/*`, `engineering/architecture/adr/ADR-AIP-*`) · the runtime mount `.claude/` (scripts, settings, registry, runtime records, CLAUDE.md, MEMORY, CONTEXT, session logs, plans) · the orchestration rulebook (`docs/architecture/governance/KnowledgeOS_Controlled_Session_Orchestration_Proposal.md`) · the governance/decision artifacts in `docs/publicdigit/reviews/` that concern the platform | `Observed` (files exist, hooks execute) |
| **Outside** | PublicDigit the product — `app/`, `tests/`, Election business rules, `docs/publicdigit/business_rules/`. `AIP-14` (Product Primacy) declares the Election system the **Core Domain** and this platform a **Supporting Subdomain** | `Declared` (ADR-AIP-02) + `Observed` (dependency direction, §6) |
| **Unclear** | `docs/` at large (~60 top-level entries, mixed depth) — parts are platform knowledge, parts product; `docs/knowledge/` exists with a Knowledge-Constitution while its owning component `knowledge_manager` (CMP-003) is `deferred, v0.0` · `architecture_legacy/` · whether `docs/publicdigit/reviews/` is a platform artifact store or a product one (it holds both lanes) | `Unknown` — see §10 |

**Boundary rule as measured, not as stated:** `ES-005.1` declares a three-concern separation (`docs/`+`app/`+`tests/` = Product · `engineering/` = Platform · `.claude/` = runtime mount). Measured deviation: platform governance artifacts are written into `docs/publicdigit/reviews/`, a Product-concern root, throughout. The rule and the practice disagree; **recorded, not adjudicated.**

---

# 3 · Context Map

## 3.1 Declared contexts (source: `Phase-02-Domain-Model.md`, FROZEN, ARB-approved)

Six contexts + two external domains, derived by an explicit merge/keep-separate analysis (that analysis is itself visible in the document — a genuine derivation, not an assertion):

| # | Bounded context | Declared purpose | Executable realization measured today | Confidence |
|---|---|---|---|---|
| BC-1 | **Knowledge Governance** | knowledge cards, authority×status dimensions, packages, lint | **NONE** — CMP-003 `deferred v0.0`; `docs/knowledge/` exists with a constitution but no component executes it | `Declared` only |
| BC-2 | **Implementation Guidance** | plan/WBS/DoD, discipline tripwires, step order | **YES** — CMP-004, 7 assets, 3 active hooks + 2 mechanism scripts | `Observed` |
| BC-3 | **Verification & Evidence** | registered checks, immutable verdicts, traceability, fitness functions | **PARTIAL** — CMP-005 `under-construction v0.1`; one guard asset live (`db-safety-check.sh`), gate execution planned | `Observed` (partial) |
| BC-4 | **Adversarial Review Support** | attempt-to-reject protocols, producer ≠ reviewer | **NONE** — CMP-006 `deferred v0.0`. *The function is performed, but by human/AI convention, not by a component* | `Declared` only |
| BC-5 | **Design & Decision Support** *(declared Core)* | ADR/IDD drafting, capability modelling, the registry | **PARTIAL** — CMP-007 `adopted-by-reference` (consumes frozen templates, creates nothing); CMP-008 `active` (the registry file) | `Observed` (partial) |
| BC-6 | **Session Continuity** | bootstrap assembly, append-only session records, staleness | **YES** — CMP-002, 3 assets, 3 active hooks | `Observed` |
| ext | Project Governance · Product Engineering | upstream fact sources | external by declaration | `Declared` |

## 3.2 The context that exists in practice but not in the declared map

**`Inferred` — stated as inference, with its reasoning:** the 2026-08-14/15 work created a *de facto* seventh area — **governed session orchestration**: work items, session assignments, roles, transitions, grants, handoffs, human acts. Its language (`SessionAssignment`, `mutationOwner`, `HANDOFF`, `START`, `grant`, `humanActRef`) appears in **none** of the six declared contexts' ubiquitous-language sections. It was placed inside CMP-004 (`workflow_engine`) by the Increment-1 boundary decision.

**Why this is `Inferred`, not `Observed`:** the *code* and *records* are observed; the claim that it constitutes a distinct bounded context is my interpretation. **Evidence for:** a distinct, self-consistent ubiquitous language; its own aggregate (Work Item/Workflow) with its own invariants; its own persistence. **Evidence against:** it was deliberately placed in an existing component under `ES-001.1` parsimony and the accepted rule's "no new independent subsystem" direction. **Both readings are defensible; Phase A does not choose.** Recorded as an unknown (§10, U-3).

---

# 4 · Capability Map

Declared capabilities `CAP-01`…`CAP-13` (source: `Phase-02.5-Certification-Plan.md`). Measured realization. **`CAP-04` has no row because the ARB merged it into `CAP-03` (`Phase-02.5:41`) — the capability does not exist separately, so its absence is substantive, not an omission** *(corrects **V-B**)*:

| Capability | Declared owner | Realized by | State |
|---|---|---|---|
| CAP-01 Context Bootstrap & Rehydration | Session Continuity | `inject-context.sh` (AST-002), `SessionStart` | **live** |
| CAP-02 Session Recording & Archival | Session Continuity | `session-changes-logger.sh` (AST-003), `session-log-reminder.sh` (AST-004) | **live** |
| CAP-03 Knowledge curation | Knowledge Governance | — | **not built** |
| CAP-05 Planning & Progress Derivation | Implementation Guidance | plan/CONTEXT convention only (no executable WBS/progress derivation found) | **convention only** |
| CAP-06 Discipline Gating & Tripwires | Implementation Guidance | `discipline-gate-reminder.sh` (AST-005), `dev-guide-reminder.sh` (AST-006), `ddd-principles-reminder.sh` (AST-014) | **live**, all Tier-2 non-blocking |
| CAP-07/08/09 Checks, verdicts, traceability | Verification & Evidence | `db-safety-check.sh` (AST-007) live; `run-gates.sh` (AST-010) **planned** | **partial** |
| CAP-10/12 Drafting & coaching | Design & Decision Support | frozen templates consumed by reference (AST-011) | **by reference** |
| CAP-11 Adversarial review | Adversarial Review Support | — | **not built** (performed by convention) |
| CAP-13 Platform Self-Governance | Design & Decision Support | `registry.yaml` (AST-009) | **live** (data, not executable) |
| *(undeclared)* **governed session orchestration** | — | `workflow-state.php` (AST-015), `session-resolve.php` (AST-016) | **live** — no CAP id exists for it |

**Measured gap, stated as fact:** the newest and most consequential executable capability on the platform — the workflow state record and its resolver — **has no declared `CAP-` identifier**, while the capability model requires every component to realize declared capabilities. `Observed`.

---

# 5 · Ownership Model

## 5.1 State ownership

| State | Authoritative holder | Evidence | Classification |
|---|---|---|---|
| Workflow/session state (assignments, roles, transitions, grants) | `.claude/runtime/workflow/<work-item>.json`, interpreted **solely** by `AST-015` | executed; `AST-016` self-documents `interpretationAuthority: AST-015` and delegates via `proc_open` | `Observed` |
| Session-day fact state | `.claude/runtime/YYYY-MM-DD-{files.log,state.json}` written by AST-003 | source | `Observed` |
| Narrative/current state | `.claude/CONTEXT.md` (mutable, shared), `.claude/sessions/YYYY-MM-DD.md` (append-only) | source + convention | `Observed` |
| Durable facts | `.claude/MEMORY.md` | convention | `Observed` |
| Platform inventory | `.claude/platform/registry.yaml` — 16 assets, 8 components | counted twice, independently | `Observed` |
| **Authority state** | grants inside the same JSON records; **origin** is a committed human act referenced by `humanActRef` | source + records | `Observed` |

**Critical measured property:** the workflow records are **gitignored** (`.gitignore:25` and `:32` — two duplicate identical entries, *(**V-C**)*) and untracked — **zero history for any of the 8**. Every other authority artifact is a git commit. The platform's machine-authoritative state is therefore the *only* governance artifact with no provenance, no diff, no recovery.

> **Acceptance-context note (**V-E** — recorded, NOT corrected).** This finding applies reflexively to the count above: because the records are gitignored with no history (U-8), **"8 workflow records" is retroactively unverifiable** — no one can now audit what existed on 2026-08-15. It is not an error, and the document cannot repair it; the baseline's own machine-state figures are declarations of a moment that left no trace. **The PO/ARB should accept the baseline knowing this.**

## 5.2 Knowledge ownership

`docs/knowledge/` holds a `Knowledge-Constitution.md`, `_meta/`, `domains/`, `ai/`, `global/`, `archive/`. Its declared owner, CMP-003, is **`deferred v0.0`** — so **knowledge is governed by constitution and convention, with no executing component**. `Observed`. A second knowledge tier exists in `docs/pks/` (PKS observations/candidates) governed by `ES-006`'s promotion ladder. Whether these are one knowledge system or two is `Unknown` (§10, U-4).

## 5.3 Decision ownership

```
Human PO/ARB  ──performative act (committed artifact)──▶  the decision exists
      │
Governance (role)  ──registers──▶  grant row + humanActRef  ──▶  queryable
      │
Architecture proposes · Implementation executes · Verification reports
```
`Observed` in 8 records and dozens of registration artifacts. **No mechanism creates a decision.** `AST-015` validates `recordedBy ∈ {governance, human}` on `COMPLETE`/`CONTINUATION` only; on `REGISTER`/`HANDOFF`/`START` it is an **unvalidated free string** (handover measurement #1, not re-measured by me — `Declared`).

## 5.4 Authority ownership

| Authority | Holder | Evidence |
|---|---|---|
| Business/architecture decisions, approvals, qualification | **Human PO/ARB only** | `R-34`, ADR-AIP-02, every registration artifact |
| Registration of human acts into state | **Governance role only** (`G-2`) | rulebook + practice |
| Activation | **conjunction**: recorded predecessor HANDOFF **∧** recorded human START (`G-3`) | enforced by `AST-015` preconditions; observed refusals |
| Mutation of the shared execution context | single `mutationOwner` per work item | single-valued field, `Observed` |
| **Nothing** | any component, script, session, or terminal | `INV-DISC-2`; no code path reads process identity for a gate |

---

# 6 · Dependency Model

```
                       HUMAN PO/ARB  (authority origin — outside the software)
                              │ committed performative acts
                              ▼
   ┌─────────────────── .claude/ (runtime mount) ────────────────────┐
   │                                                                  │
   │   settings.json (CMP-001 composition root)                       │
   │        │ wires 10 hooks at 4 runtime moments   (D-1/V-A)         │
   │        ├─▶ CMP-002 session_manager: inject-context ·             │
   │        │        session-changes-logger · session-log-reminder    │
   │        ├─▶ CMP-004 workflow_engine: discipline-gate ·            │
   │        │        dev-guide · ddd-principles  (all Tier-2)         │
   │        ├─▶ CMP-005 verification_engine: db-safety-check (Tier-1) │
   │        └─▶ 3 UNREGISTERED hooks (§6.2)                           │
   │                                                                  │
   │   CMP-004 mechanism (invoked manually, NOT hook-wired):          │
   │        AST-016 session-resolve.php ──proc_open──▶ AST-015        │
   │        AST-015 workflow-state.php ──reads/writes──▶ runtime/*.json│
   └──────────────────────────────────────────────────────────────────┘
                              │ reads (never writes)
                              ▼
       engineering/ (rule corpus)   docs/ (artifacts, both lanes)
```

**Measured dependency facts:**

- **AST-016 → AST-015 is a one-way consumer dependency**, implemented by `proc_open` at `session-resolve.php:103`, with the mechanism path resolved from `KOS_MECHANISM_PATH` or defaulting to the sibling. The resolver self-documents that AST-015 is the *interpretation authority* and that it "performs no independent interpretation." `Observed`.
- **AST-015 depends on nothing** in the platform: a scan for dependency constructs (`require`/`include`/`exec`) returns only internal function definitions, its own dispatch, and the English word *"requires"* inside refusal strings — **no cross-asset dependency**. It is the dependency root. `Observed`. *(**V-D**: the superseded text carried a bare match count without its method; a near-identical pattern family yields a different number, so the count is method-dependent and evidentially inert. The conclusion is unchanged and was re-derived independently.)*
- **Platform → Product direction is one-way**: the platform reads/guards `app/`; no `app/` code imports or invokes any `.claude/` asset. `Observed` (consistent with `AIP-14`).
- **The registry is data, not a dependency**: nothing loads `registry.yaml` at runtime. It is a governed inventory consulted by humans and by registry-first process. `Observed`.

## 6.2 Registry-first compliance — measured

**Three scripts execute as wired hooks but are absent from the registry:** `claude-code-trigger.sh` (PostToolUse), `engineering-placement-guard.sh` (PreToolUse), `project-knowledge-guard.sh` (PreToolUse). The registry's own binding workflow is *register → review → implement → verify* (`R-17`/`R-21`). **Recorded as a finding; not repaired, not judged.** `Observed`.

## 6.3 A measured correction to the evidence handover

The handover states **24 assets**; two independent counts give **16** (`AST-001`…`AST-016`, no higher ids, no gaps). The handover's own §3.1 warns its inventory is a declaration, not an attestation — this is an instance. **Recorded as a correction, not a defect.** `Observed`.

## 6.4 Declared component table vs. live registry — a census discrepancy *(corrects **D-3**)*

The frozen `Phase-03A-Reference-Architecture.md` states **"Seven components"** and its table does not contain `composition_root`. The live registry carries **eight** — `CMP-001…008` — including **`CMP-001 composition_root`, which holds 2 assets** (`settings.json` AST-001 and the plan-timestamp hook).

**The original document used "7" and "8" in different sentences without noting they disagree.** For a reconstruction whose thesis is *declared ≠ executing*, an unflagged declared-vs-registry gap — and specifically the **composition root**, the component that wires every hook in §6's diagram — was an instance of its own subject matter, missed. `Observed`.

---

# 7 · Workflow and State Model

**Lifecycle state and authority state are two records that are never merged** — this is the system's central structural rule (`G`/`§10a` of the rulebook), and it is realized in the schema: `transitions[]` (lifecycle) and `grants[]` (authority) share no fields and have disjoint writer rules.

```
LIFECYCLE STATE                                AUTHORITY STATE
transitions[] — append-only; state = fold      grants[] — registered by Governance only
  REGISTER → (CREATED)                           {grantId, status, authority, scope,
  HANDOFF  → transfers ownership                  humanActRef, registeredBy}
  START    → ACTIVE  [needs HANDOFF ∧ human act]
  HANDED_OFF · STOPPED(sticky) · COMPLETED
  FAILED · CANCELLED
        │                                                 │
        └──── answers "who exists, where, who owns" ──────┴── answers "what was authorized, by whom"

        ACTIVE  ≠  AUTHORIZED        OWNERSHIP  ≠  AUTHORITY        EXISTENCE  ≠  PERMISSION
```

**Measured properties of the mechanism** (from the handover's executed probes — `Declared`, reproducible but not re-run by me): two sessions can be simultaneously `ACTIVE`; only `mutationOwner` is single-valued; a second `START` silently transfers ownership with no precondition on the current owner; there is **no closure vocabulary** (`CLOSE` refused, exit 65) — `workItemState` is only `OPEN`|`STOPPED`, so work-item closure exists in governance prose but not in the mechanism.

**Aggregate identification (`Inferred`, reasoning given):** the Work Item/Workflow is the aggregate root — it holds identity, the invariants (single owner, sticky STOPPED, the G-3 conjunction), and its persistence boundary is exactly one JSON file. `SessionAssignment` is an entity within it; `Role` and `WorkflowState` are value objects; transitions are domain events; grants are a separate record inside the same consistency boundary. Per-file isolation realizes cross-work-item independence.

---

# 8 · Invariants

Each traced to evidence; none invented.

| # | Invariant | Why it exists | Evidence | Owner |
|---|---|---|---|---|
| I-1 | Exactly one mutation owner per work item | F1/F2/F4/F6 — git-level collisions between concurrent sessions | single-valued field; `Observed` | CMP-004 |
| I-2 | Activation requires recorded HANDOFF **∧** recorded human START | authority must never be inferred; F9 | `AST-015` preconditions; observed refusals incl. against this session | Governance + human |
| I-3 | STOPPED is sticky — only explicit CONTINUATION exits | a stopped session must not imply permission for another | state machine; `Declared`+`Observed` | CMP-004 |
| I-4 | Session Registry ≠ Authority State, never merged | F5 — divergent authorization state in prose | disjoint schema; `Observed` | rulebook `G-2` |
| I-5 | Only Governance writes authority, and only registering a recorded human act | prevents manufactured authority | `G-2`; practice in all 16 grants | Governance |
| I-6 | Role is immutable per SessionAssignment; change = new assignment | prevents silent role drift | `R8`; observed in the assignment chains | rulebook `A-1.2` |
| I-7 | Process/terminal identity is evidential, never authorising | terminals are a human arrangement, not architecture | `INV-DISC-2`, accepted principle 5; **no code path reads process identity for a gate** — `Observed` | rulebook |
| I-8 | Engineering never accepts its own work | independence of acceptance | `R-34`; acceptance reserved to PO/ARB throughout | human |
| I-9 | Governance precedes automation; mechanisms only after evidence of insufficiency | prevents speculative tooling | `R-37`; the platform's zero-new-hooks position | ARB |
| I-10 | The record outranks prose for machine-truth | prose claims of authority have failed repeatedly | `A-4`-family; `AST-016` refuses prose input | CMP-004 |

**Invariant with no mechanism behind it (measured):** I-2's *"recorded"* qualifier and I-5's writer restriction are enforced by convention plus partial validation only — `recordedBy` is unvalidated on the three transitions that matter most. `Observed` limitation.

---

# 9 · AI Responsibility Model

| Actor | Responsibility | May not | Evidence |
|---|---|---|---|
| **Human PO/ARB** | originate authority; approve boundaries; qualify; decide business meaning | — | `R-34`, every registration |
| **Governance (S2)** | register human acts; reconcile gates; record closure; route | create authority; implement | `G-1`/`G-2`; observed |
| **Architecture (S4)** | reconstruct, analyse, propose boundaries | implement; govern; self-approve | this document's own grant |
| **Implementation (S3)** | build inside an approved boundary; RED→GREEN | redesign; self-certify | `A-1.4`/`D-5` |
| **Verification (S1)** | independently falsify; produce evidence | repair; close | `R-34`, `G-1` |
| **Mechanism (AST-015/016)** | record, fold, report | decide, authorize, activate | `Observed` — no gate reads authority |

**Measured shape of AI participation:** four role-bound assignments over one shared worktree and one git identity, coordinated by records and human acts. **AI holds no authority anywhere in the model** — it produces evidence and proposals, and registers human decisions.

---

# 10 · Architecture Unknowns

| # | Unknown | Why it cannot be settled in Phase A |
|---|---|---|
| U-1 | Whether the declared 6-context model still describes the system, given that **2 of 8 registered components have no assets and 2 more are partial/by-reference** *(figure corrected per **D-2**; the question is unchanged)* | requires judgment about whether a context can exist as rules alone — a Phase B question |
| U-2 | Whether `docs/publicdigit/reviews/` is a platform or product artifact store | it holds both lanes; `ES-005.1` says one thing, practice another |
| U-3 | Whether governed session orchestration is a seventh bounded context or a capability inside CMP-004 | both readings defensible; §3.2 |
| U-4 | Whether `docs/knowledge/` (EKP) and `docs/pks/` are one knowledge system or two | different constitutions, no executing component to arbitrate |
| U-5 | What CAP-05's "progress derivation" is realized by, if anything executable | no derivation code found; may be pure convention |
| U-6 | Whether `AST-016`'s `KOS_MECHANISM_PATH` indirection is a boundary or a convenience | documented as runtime-selectable and explicitly *not* hardened (C-2) |
| U-7 | The true provenance of most 2026-08-14/15 artifacts | two lanes, one git identity, interleaved commits — authorship not separable from the record (handover §3.1) |
| U-8 | Whether the 8 workflow records are complete/consistent | untracked and unversioned; no history to check against |
| U-9 | Runtime behaviour of the 3 unregistered hooks | not inspected in Phase A; they execute on every tool use |

**A baseline without unknowns would contain hidden assumptions.** Nine are recorded; none is filled by inference.

---

# 11 · Evidence Appendix

**Self-authored elements (commission §5) — all classified `Declared`, never `Observed`:** the workflow record mechanism and its Increment-1 boundary · the Session Assignment Resolver design · the role-bound execution model (`R8`, canonical roles, startup check) · `INV-ATTR-1`/`INV-ATTR-2` · the Election authority archaeology · this baseline. **Per handover §3.1 this list is a declaration from turn history — unverifiable and possibly incomplete; absence from it is not evidence of independent production.**

| Statement | Class | Evidence | Confidence | Open |
|---|---|---|---|---|
| 6 contexts + 7 components are declared | `Observed` (as documents) | `Phase-02-Domain-Model.md`, `Phase-03A-Reference-Architecture.md`, both FROZEN | high | U-1 |
| **2 of 8 registered components have no assets** (`CMP-003`, `CMP-006`); 2 more partial/by-reference | `Observed` | asset-to-component census, recounted | high | — *(corrects **D-2**)* |
| **Declared table lists 7 components; registry carries 8** — `CMP-001 composition_root` is registry-only | `Observed` | `Phase-03A` table vs registry ids | high | — *(corrects **D-3**)* |
| 16 assets, not 24 | `Observed` | two independent counts | high | — |
| **10 hooks wired at 4 runtime moments** | `Observed` | `settings.json` parse — **count computed, not eyeballed** (the superseded "8" was an eyeballed total; §6's diagram already summed to 10) | high | U-9 *(corrects **D-1**/**V-A**)* |
| 3 wired hooks are unregistered | `Observed` | disk vs registry comparison | high | — |
| AST-016 → AST-015 one-way, by `proc_open` | `Observed` | source `:90,:103,:288` | high | U-6 |
| AST-015 is the dependency root | `Observed` | source scan | high | — |
| Platform → Product dependency is one-way | `Observed` | no `.claude/` reference from `app/` | high | — |
| Workflow records are gitignored/untracked | `Observed` | `.gitignore:25,32`; `git ls-files` empty | high | U-8 |
| All six ES standards are `PROPOSED` | `Observed` | header census | high | — |
| Two sessions can be simultaneously ACTIVE; START transfers ownership unguarded; no CLOSE vocabulary; `recordedBy` unvalidated on 3 transitions | `Declared` (handover's executed probes) | handover §3.4 #1,6,7,8 | medium — reproducible, not re-run here | — |
| Governed session orchestration is a distinct bounded context | `Inferred` | distinct ubiquitous language + own aggregate + own persistence, vs. deliberate placement in CMP-004 | low — both readings defensible | U-3 |
| The Work Item/Workflow is the aggregate root | `Inferred` | holds identity + invariants; persistence boundary = one file | medium | — |

---

**Traceability:** grant `G-KOS-ARCHBASE-A` (Phase A only) · commission + evidence handover `2026-08-15-KOS-ARCH-BASELINE-001-phase-a-commission.md` §3.1–§3.6 · `Phase-02-Domain-Model.md` · `Phase-02.5-Certification-Plan.md` · `Phase-03A-Reference-Architecture.md` · `ADR-AIP-01`/`-02` · `ES-001`…`ES-006` + `STANDARDS_INDEX` · `KnowledgeOS_Controlled_Session_Orchestration_Proposal.md` (`A-1`…`A-8`) · `.claude/platform/registry.yaml` · `.claude/settings.json` · `.claude/scripts/{workflow-state,session-resolve}.php` + 10 hook scripts · `.claude/runtime/workflow/*.json` (8) · `.gitignore:25,32` · open-findings register `2026-08-15-open-findings-register.md`.

---

> # PHASE A BASELINE — PROPOSED, NOT VALIDATED
> **Next: Session 1 (Verification) attempts falsification — weighting self-authored elements hardest. Then Session 2 (Governance) adjudicates authority/scope. Then PO/ARB decides whether Phase B is commissioned. No target architecture exists, and none is implied by this document.**
