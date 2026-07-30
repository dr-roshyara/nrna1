# DDD-Driven Architectural Implementation Protocol

**Status: OPERATIONAL PRACTICE — level 1 of 3. NOT a governed engineering standard.**
**Issued by:** Principal Architect / Decision Authority, 2026-07-30 · **Applies to:** implementation execution (work packages)
**Home rationale:** `.claude/` is the runtime mount point. This document records *what is currently being followed*, not what governance has ratified — so it deliberately does **not** live in `engineering/governance/`.

## Standing (the hierarchy this protocol sits inside)

> **This protocol governs implementation execution unless superseded by explicit architectural authority or a later governance decision.**

```
Business Authority  →  Architecture  →  Governance  →  Implementation Protocol
```

**No protocol ever outranks architectural authority.** Where this document and an issued ADR / approved design / governance ruling disagree, the authority wins and this document is the thing that changes.

## Level of adoption (DA ruling, 2026-07-30)

| Level | Meaning | This protocol |
|---|---|---|
| **Operational practice** | The execution protocol currently being followed | ✅ **HERE** |
| **Project standard** | Official engineering standard adopted by governance | ❌ not yet |
| **Constitutional standard** | Stable methodology approved after sufficient operational evidence | ❌ not yet |

**DA rulings:** accepted as the current operational execution protocol · **not** promoted to a governed engineering standard · continue using for WP-3 and subsequent work packages · promotion reviewed after further operational evidence (~WP-3/WP-4) · **future amendments to this protocol must themselves follow this protocol** — authority, evidence, traceability, and an explicit governance decision before any of it becomes standard.

## Process-evolution ladder (DA refinement — the intermediate state that prevents accidental policy)

```
Observation → Candidate Protocol → Operational Practice → Operational Evidence
            → Governance Review → Engineering Standard
```

This protocol is at **Operational Practice**, accumulating Operational Evidence. The extra stage exists so a good workflow can never become policy merely because it is liked. *(Same discipline as ES-006's promotion ladder and the "evidence before abstraction" rule.)*

## The seventeen phases

**Fundamental ordering, never reversed:** Business Model → Strategic Architecture → Architectural Decisions → Approved Design → Implementation → Repository State → Operational Evidence.

| # | Phase | In one line |
|---|---|---|
| 1 | **Commission Reset** | Every work package is a new commission; carry forward only approved ADRs/decisions/rulings/standards — previous *reasoning* is history, not authority |
| 2 | **Authority Register** | Before reading code, list every governing authority and what it governs |
| 3 | **Business Understanding** | Capability · objective · policies · invariants · ubiquitous language (and rejected vocabulary) · ownership — ownership is the business's, never the repository's |
| 4 | **Strategic DDD** | Bounded context · upstream/downstream · published language · ACLs · shared kernel · ownership boundaries — before any tactical design |
| 5 | **Business Model Fidelity** | Does the *planned* implementation preserve language, ownership, aggregate boundaries, published language, invariants, policies, autonomy? If undemonstrable — **STOP** |
| 6 | **Architectural Traceability** | Every class, enum, port, mapper, event, migration and test answers *"which authority requires this?"* No authority → do not create it |
| 7 | **Simplification Review** | Can any planned component be removed while still satisfying the architecture? If yes, remove it — before coding |
| 8 | **Business Assumption Review** | Classify every interpretation: explicit authority (implement) · derived implication (implement **with traceability**) · architectural assumption (record + confirm first) · open business question (**stop**) |
| 9 | **Tactical DDD** | Aggregates, entities, VOs, services, events, repositories, factories, application services; protect boundaries; no infrastructure in the domain; orchestration is not an aggregate unless the business gives it identity |
| 10 | **RED First** | Failing tests that express business invariants, policies, transitions, rules and architectural constraints — not implementation detail |
| 11 | **GREEN** | The minimum satisfying RED; no speculative abstraction; no anticipating later work packages |
| 12 | **Refactor** | Only if behaviour is unchanged, architecture becomes clearer, and traceability survives |
| 13 | **Architectural Verification** | Verify against the business model and approved architecture — never code against itself |
| 14 | **Static Analysis Review** | Static analysis, fitness tests, dependency rules, standards — findings are **design feedback**, not tooling noise |
| 15 | **Failure Analysis** | On failure, diagnose before patching: wrong implementation? wrong test? wrong architecture? wrong assumption? wrong authority reading? Fix the root cause |
| 16 | **Governance Discipline** | Never reopen closed commissions, implicitly modify approved architecture, promote observations into standards, restructure unrelated code, or create decisions by implementation. Better ideas get recorded and routed |
| 17 | **Completion Review** | Fidelity · language · aggregate boundaries · ownership · traceability · derived implications documented · assumptions classified · every artifact justified · static analysis acceptable · tests express business behaviour |

## Governing principle

> **A good question does not become a decision by being well argued.**
> **Evidence + reasoning is not authority. A decision is an explicit act by an authority.**

Reasoning is subordinate to authority; authority is subordinate to the business.

## Operational evidence register (DA refinement — mandatory for every protocol element)

Methodology here is an evidence-backed artifact: patterns are **discovered from repeated behaviour**, never designed in the abstract. Every phase must name where it was first observed.

| Phase | First observed | Evidence |
|---|---|---|
| 1 Commission Reset | WP-2 | F-2 formally closed before WP-2 opened; "closed commissions, not to be reopened" header in the WP-2 plan |
| 2 Authority Register | WP-1 | WP-1 plan's authority list; WP-2 plan §1 (12 sources) |
| 3 Business Understanding | WP-2 | WP-2 plan §2 — goal, problem (EPIC-003 R-1), language **and rejected vocabulary**, 6 invariants, ownership map |
| 4 Strategic DDD | WP-2 | WP-2 plan §3 |
| 5 Business Model Fidelity | WP-2 | WP-2 plan §4 — surfaced the WP-2/WP-4 transport boundary instead of assuming it |
| 6 Architectural Traceability | WP-2 | WP-2 plan §11.1 — 16 components mapped |
| 7 Simplification Review | WP-2 | §11.3 — 4 VOs reused instead of created; mapper question raised |
| 8 Business Assumption Review | WP-2 | §13 — F-T1 classified as derived implication; RED claim narrowed to WP-2's own obligation |
| 9 Tactical DDD | WP-1 · WP-2 | WP-1 §5; WP-2 §5 — orchestration seated in Application, not modelled as an aggregate |
| 10 RED First | WP-1 | WP-1 10 keystone failures; WP-2 25 tests / 25 expected errors |
| 11 GREEN (minimal) | WP-1 | Both slices implemented only what RED demanded |
| 12 Refactor | **— none yet** | **Honest gap: no slice has yet needed a behaviour-preserving refactor. Untested phase.** |
| 13 Architectural Verification | F-2 · WP-2 | The architecture-to-implementation fidelity audit; WP-2 plan §15 triple qualification |
| 14 Static Analysis as design feedback | WP-2 | `whereNotIn` type-erasure fixed at the root (21 → 0 errors), never suppressed |
| 15 Failure Analysis | WP-2 | The state guards rejected my own test; the five-way diagnosis returned *"incorrect test"* — the test changed, the code did not |
| 16 Governance Discipline | F-2 · WP-2 | Mapper deletion reversed on authority; the placement commission deferred to D-1 rather than executed; this protocol not self-promoted |
| 17 Completion Review | WP-1 · WP-2 | Triple qualification + measurable conformance gate on both slices |

**Reading the gap honestly:** Phase 12 has no instance. The register's purpose is exactly this — to show which phases are evidence-backed and which are still assertions.

## Traceability

DA instruction 2026-07-30 (protocol issued · five rulings · ladder · mandatory evidence register · supersession wording) · originating practice: WP-1 (`.claude/plans/WP-1-evidenceset-v3.md`) and WP-2 (`.claude/plans/WP-2-apm-core.md` §§1–15) · governing principle originated in WP-2's mapper self-correction · parked promotion candidate recorded 2026-07-26 (session log) with the ~WP-3/WP-4 revisit point · related: ES-006 promotion ladder · ES-004.3 artifact lifecycle · DDD Tactical Governance Principles (`engineering/knowledge/methodology/`).
