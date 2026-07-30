# DDD-Driven Architectural Implementation Protocol

**Status: `OPERATIONAL`** — currently followed; **NOT a governed engineering standard.**

| Status | Meaning | This protocol |
|---|---|---|
| Draft | Proposed only | — |
| **Operational** | Currently followed | ✅ **HERE** (since 2026-07-30) |
| Qualified | Demonstrated across N work packages (bar: 3+) | pending — 2 slices to date |
| Governed | Official engineering standard | — |
| Retired | Superseded | — |

*The status is a lifecycle independent of the content: the protocol's text can change without its status changing, and its status can change without its text changing.*
**Issued by:** Principal Architect / Decision Authority, 2026-07-30 · **Applies to:** implementation execution (work packages)
**Home rationale:** `.claude/` is the runtime mount point. This document records *what is currently being followed*, not what governance has ratified — so it deliberately does **not** live in `engineering/governance/`.

## Standing (the hierarchy this protocol sits inside)

> **This protocol governs implementation execution unless superseded by explicit architectural authority or a later governance decision.**

```
Business Authority
        ↓
Architecture Decisions (ADRs · ARB rulings)
        ↓
Governance (standards, ES-00x)
        ↓
Implementation Protocol (this document — operational practice)
        ↓
Implementation
        ↓
Operational Evidence
```

**No protocol ever outranks architectural authority.** Where this document and an issued ADR / approved design / governance ruling disagree, the authority wins and **this document is the thing that changes**.

**The precedence table — it settles disputes without debate:**

| Question | Answer |
|---|---|
| Can a protocol override an ADR? | **No** |
| Can implementation override a protocol? | **No** |
| Can governance override architecture without authority? | **No** |
| Can business decisions invalidate implementation? | **Yes** |

*Canonical statement of the hierarchy for implementation execution. Hosting it as a governed rule (ES-001 would be the natural host, since it governs governance creation) is a promotion the DA may order — it is not self-authorized here.*

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
>
> **A good practice does not become a standard by being well liked.**
> **Evidence + practice is not policy. A standard is an explicit act by governance.**

Reasoning is subordinate to authority; authority is subordinate to the business. The second couplet is the first one applied to methodology — which is why this document is `OPERATIONAL`, not `GOVERNED`.

## Operational evidence register (DA refinement — mandatory for every protocol element)

Methodology here is an evidence-backed artifact: patterns are **discovered from repeated behaviour**, never designed in the abstract. Every phase must name where it was first observed.

| Phase | First observed | Latest validation | Evidence |
|---|---|---|---|
| 1 Commission Reset | WP-2 | WP-2 | F-2 formally closed before WP-2 opened; "closed commissions, not to be reopened" header in the WP-2 plan |
| 2 Authority Register | WP-1 | WP-2 | WP-1 plan's authority list; WP-2 plan §1 (12 sources) |
| 3 Business Understanding | WP-2 | WP-2 | WP-2 plan §2 — goal, problem (EPIC-003 R-1), language **and rejected vocabulary**, 6 invariants, ownership map |
| 4 Strategic DDD | WP-2 | WP-2 | WP-2 plan §3 |
| 5 Business Model Fidelity | WP-2 | WP-2 | WP-2 plan §4 — surfaced the WP-2/WP-4 transport boundary instead of assuming it |
| 6 Architectural Traceability | WP-2 | WP-2 | WP-2 plan §11.1 — 16 components mapped |
| 7 Simplification Review | WP-2 | WP-2 | §11.3 — 4 VOs reused instead of created; mapper question raised |
| 8 Business Assumption Review | WP-2 | WP-2 | §13 — F-T1 classified as derived implication; RED claim narrowed to WP-2's own obligation |
| 9 Tactical DDD | WP-1 | WP-2 | WP-1 §5; WP-2 §5 — orchestration seated in Application, not modelled as an aggregate |
| 10 RED First | WP-1 | WP-2 | WP-1 10 keystone failures; WP-2 25 tests / 25 expected errors |
| 11 GREEN (minimal) | WP-1 | WP-2 | Both slices implemented only what RED demanded |
| 12 Refactor | **— none yet** | **—** | **Honest gap: no slice has yet needed a behaviour-preserving refactor. Untested phase.** |
| 13 Architectural Verification | F-2 | WP-2 | The architecture-to-implementation fidelity audit; WP-2 plan §15 triple qualification |
| 14 Static Analysis as design feedback | WP-2 | WP-2 | `whereNotIn` type-erasure fixed at the root (21 → 0 errors), never suppressed |
| 15 Failure Analysis | WP-2 | WP-2 | The state guards rejected my own test; the five-way diagnosis returned *"incorrect test"* — the test changed, the code did not |
| 16 Governance Discipline | F-2 | WP-2 | Mapper deletion reversed on authority; the placement commission deferred to D-1 rather than executed; this protocol not self-promoted |
| 17 Completion Review | WP-1 | WP-2 | Triple qualification + measurable conformance gate on both slices |

**Reading the gap honestly:** Phase 12 has no instance. The register's purpose is exactly this — to show which phases are evidence-backed and which are still assertions.

## Success criteria — judge the protocol by outcomes, never by its own completeness

**Standing caution (DA, 2026-07-30): the protocol must never become an objective in itself.** It exists to improve engineering outcomes, and is judged by:

| Question | Indicator |
|---|---|
| Does it produce clearer bounded contexts? | Strategic DDD clarity |
| Does it reduce architectural drift? | Fidelity over time |
| Does it improve business fidelity? | Business-model preservation |
| Does it reduce rework? | First-time quality |
| Does it improve decision traceability? | Evidence-register completeness |

**If any answer becomes "no", the protocol itself is reconsidered through its own governance process** — a phase that costs more than it protects is a candidate for removal, not for defence.

## Artifact classes — FLAGGED, not defined here (a taxonomy already exists)

The DA proposed three classes: **Runtime** (`.claude/`) · **Governance** (`engineering/governance/`) · **Historical evidence** (`.claude/sessions/`, `.claude/plans/`). The insight is right and this document obeys it — it sits in `.claude/` precisely because it is Runtime, not Governance.

**But a governed taxonomy already exists and must not be forked.** **ES-004.3** defines **four artifact roles** — Runtime · Historical · **Reference** · **Decision** — where governance documents are classified *Reference* and rulings/acceptance records are *Decision*. The proposed "Governance" class therefore maps onto **Reference + Decision**, not onto a new fifth thing:

| DA's proposed class | ES-004.3 role(s) | Note |
|---|---|---|
| Runtime artifact | **Runtime** | identical |
| Governance artifact | **Reference** + **Decision** | one proposed class, two governed roles |
| Historical evidence | **Historical** | identical |

**Disposition:** no second taxonomy is created here (*rules live once* — ES-004.3 is the canonical home for artifact roles). The remaining question — *which physical location each role maps to* — is already the pending **Decision D-1** in `docs/implementation/Placement_Rule_Decision_Paper.md`, whose end-state is a hosted rule in **ES-005** (candidate ES-005.5), with moves deferred by **R-40/A3**. **Routed there, not decided here.**

## Amendment log — this protocol governing its own amendment

Its own rule: *amendments must themselves follow the protocol* (authority · evidence · traceability · explicit governance decision). Demonstrated, not merely declared:

| # | Amendment | Authority | Evidence | Governance decision |
|---|---|---|---|---|
| A-1 | Formal `Status` field + lifecycle (Draft→Operational→Qualified→Governed→Retired) | DA instruction 2026-07-30 | Status was implicit; "level 1 of 3" conflated content maturity with lifecycle | DA ruling, explicit |
| A-2 | Canonical hierarchy + precedence table | DA instruction 2026-07-30 | Precedence questions had been answered ad hoc in three separate turns | DA ruling, explicit |
| A-3 | `Latest validation` column in the evidence register | DA instruction 2026-07-30 | Register recorded origin only; continued validity was unrepresentable | DA ruling, explicit |
| A-4 | Outcome-based success criteria + the standing caution | DA instruction 2026-07-30 | No mechanism existed by which the protocol could be judged, or removed | DA ruling, explicit |
| A-5 | Governing principle extended to methodology (practice→policy) | DA instruction 2026-07-30 | The reasoning→authority couplet had already been applied to methodology twice in practice (mapper, F-T1) before being stated | DA ruling, explicit |
| A-6 | Artifact classes **flagged and routed** rather than defined | This protocol's Phase 16 + ES-004.3 | ES-004.3 already governs four artifact roles; D-1 already owns placement; a second taxonomy would fork a governed rule | **No decision taken** — routed to D-1 |

*A-6 is the log's most useful row: the protocol's own governance discipline stopped an amendment that would have duplicated a governed rule.*

## Traceability

DA instruction 2026-07-30 (protocol issued · five rulings · ladder · mandatory evidence register · supersession wording) · originating practice: WP-1 (`.claude/plans/WP-1-evidenceset-v3.md`) and WP-2 (`.claude/plans/WP-2-apm-core.md` §§1–15) · governing principle originated in WP-2's mapper self-correction · parked promotion candidate recorded 2026-07-26 (session log) with the ~WP-3/WP-4 revisit point · related: ES-006 promotion ladder · ES-004.3 artifact lifecycle · DDD Tactical Governance Principles (`engineering/knowledge/methodology/`).
