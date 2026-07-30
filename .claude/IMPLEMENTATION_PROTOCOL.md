# DDD-Driven Architectural Implementation Protocol

**Status: `OPERATIONAL`** — currently followed; **NOT a governed engineering standard.**

| Status | Meaning | This protocol |
|---|---|---|
| Draft | Proposed only | — |
| **Operational** | Currently followed | ✅ **HERE** (since 2026-07-30) |
| Qualified | Demonstrated through **sufficient operational evidence as determined by governance** — evidence-based, never numeric | pending (2 slices to date; sufficiency is governance's call, not a count) |
| Governed | Official engineering standard | — |
| Retired | Superseded | — |

*The status is a lifecycle independent of the content: the protocol's text can change without its status changing, and its status can change without its text changing.*

## FROZEN FOR ROUTINE WORK (DA, 2026-07-30)

**The protocol is operationally complete.** No amendment will be considered unless justified by **operational evidence from a completed work package**.

| Trigger | Action |
|---|---|
| Operational evidence from a completed WP | Propose an amendment through the amendment rule |
| A design discussion in isolation | Record as an **observation** — do not amend |
| A conceptual refinement without evidence | Record as a **candidate** — do not amend |
| The same failure across 2+ WPs | **Strong trigger** — systemic deficiency |

The trigger is *"WP-X exposed a recurring problem this protocol does not address"* — never *"we have a good idea."*

> **Never let the sophistication of the protocol become a proxy for engineering quality.** The software is the primary artifact; the protocol exists to improve it. A session that produced governance refinement but no working software has produced less than it appears to. *(A-13, surfaced here because it guards against the protocol becoming self-referential.)*

**From here the protocol proves itself by outcomes — better software, fewer architectural corrections, less rework — not by new sections.**
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

> **A good protocol does not grow by being refined.**
> **Evidence + practice is not amendment. A change is justified only by operational deficiency.**

**The three couplets are one principle at three altitudes** — decisions · standards · the protocol itself. Each half has its own domain: the first governs **decisions** (architectural interpretation), the second governs **standards** (methodology promotion). Reasoning is subordinate to authority; authority is subordinate to the business. The second couplet is the first applied to methodology — which is why this document reads `OPERATIONAL` and not `GOVERNED`.

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

**Standing caution, sharper (DA, 2026-07-30): never let the sophistication of the protocol become a proxy for engineering quality.** The software is the primary artifact; the protocol exists to improve it. A more elaborate protocol is not a better one, and a session that produced governance refinement but no working software has produced less than it appears to.

## Artifact classes — FLAGGED, not defined here (a taxonomy already exists)

The DA proposed three classes: **Runtime** (`.claude/`) · **Governance** (`engineering/governance/`) · **Historical evidence** (`.claude/sessions/`, `.claude/plans/`). The insight is right and this document obeys it — it sits in `.claude/` precisely because it is Runtime, not Governance.

> **The ubiquitous language lives once.**
>
> This is the DDD principle *underneath* "rules live once" — and the reason duplicate taxonomies are harmful: a second vocabulary for the same concepts splits the model, and a split model is the thing DDD exists to prevent. "Rules live once" is the filing consequence; the language is the cause.

**A governed taxonomy already exists and must not be forked.** **ES-004.3** defines **four artifact roles** — Runtime · Historical · **Reference** · **Decision** — where governance documents are classified *Reference* and rulings/acceptance records are *Decision*. The proposed "Governance" class therefore maps onto **Reference + Decision**, not onto a new fifth thing:

| DA's proposed class | ES-004.3 role(s) | Note |
|---|---|---|
| Runtime artifact | **Runtime** | identical |
| Governance artifact | **Reference** + **Decision** | one proposed class, two governed roles |
| Historical evidence | **Historical** | identical |

**Disposition:** no second taxonomy is created here (*rules live once* — ES-004.3 is the canonical home for artifact roles). The remaining question — *which physical location each role maps to* — is already the pending **Decision D-1** in `docs/implementation/Placement_Rule_Decision_Paper.md`, whose end-state is a hosted rule in **ES-005** (candidate ES-005.5), with moves deferred by **R-40/A3**. **Routed there, not decided here.**

## Two feedback loops — distinct bounded contexts, deliberately not merged

The project runs **two** control systems. They share vocabulary but not authority, and conflating them is how a process starts governing the thing it exists to serve.

**The engineering loop — governs software:**

```
Business  →  Architecture  →  Implementation  →  Operational Evidence
```

**The methodology loop — governs the engineering process:**

```
Observation  →  Protocol  →  Operational Practice  →  Evidence  →  Governance
```

| | Engineering loop | Methodology loop |
|---|---|---|
| Subject | the software | the process that produces it |
| Authority | business → architecture | observation → governance |
| Output | working, qualified software | a protocol at a recorded status |
| Failure mode | architectural drift | **process becoming the objective** |

**They are separate bounded contexts.** The methodology loop's output is *this document*; the engineering loop's output is *the software*. Only the second is the point. When the two compete for a session's attention, the engineering loop wins.

## The protocol's own ubiquitous language (stabilized — resist renaming)

These terms have stabilized through repeated use across WP-1, WP-2 and F-2. They are now vocabulary, not phrasing, and renaming any of them requires a domain reason — not a stylistic preference.

| Term | Meaning |
|---|---|
| **Commission** | A bounded unit of work with its own authority, opened and closed explicitly |
| **Authority** | The governing source: ADR · ARB ruling · DA decision · approved design |
| **Business Model Fidelity** | Preservation of business semantics by a planned implementation |
| **Traceability** | The component → authority mapping; nothing exists without one |
| **Derived implication** | A logical consequence of explicit authority; implementable *with traceability* |
| **Architectural assumption** | Plausible but not established; recorded and confirmed *before* implementation |
| **Operational Practice** | What is currently followed (this document's status) |
| **Operational Evidence** | Proof of repeated success, recorded per phase |
| **Governance Review** | The explicit act that promotes practice to standard |

## Separation of concerns — PREPARED, not executed

This document currently mixes four concerns: execution (the phases) · evidence (the register) · governance (status, ladder, principles) · change history (the amendment log). **That is acceptable at `OPERATIONAL` status and is deliberately left as-is** — splitting now would create three artifacts to maintain for a protocol that may yet change shape.

**Pre-governance preparation (a condition of promotion, not a task for today):** before any promotion to `GOVERNED`, split into —

| Document | Concern |
|---|---|
| `IMPLEMENTATION_PROTOCOL.md` | the phases and their execution only |
| `IMPLEMENTATION_PROTOCOL_EVIDENCE.md` | evidence register · validations · outcome metrics |
| `IMPLEMENTATION_PROTOCOL_CHANGELOG.md` | the amendment log |

Recorded here so promotion cannot quietly carry the mixed-concern shape into a governed standard. *(Same separation-of-concerns discipline applied to software design — and the same reason: one artifact, one responsibility.)*

## Amendment log

Its own rule: *amendments must themselves follow the protocol* (authority · evidence · traceability · explicit governance decision) — plus **Impact**, so a future reviewer sees at a glance why an amendment mattered.

| # | Amendment | Authority | Evidence | Governance decision | **Impact** |
|---|---|---|---|---|---|
| A-1 | `Status` field + lifecycle | DA 2026-07-30 | Status implicit; "level 1 of 3" conflated content maturity with lifecycle | Explicit | Clarifies status; **no execution change** |
| A-2 | Canonical hierarchy + precedence table | DA 2026-07-30 | Precedence answered ad hoc across three turns | Explicit | Clarifies precedence; **no execution change** |
| A-3 | `Latest validation` column | DA 2026-07-30 | Register recorded origin only; continued validity unrepresentable | Explicit | Improves evidence tracking |
| A-4 | Outcome-based success criteria | DA 2026-07-30 | No mechanism by which the protocol could be judged or removed | Explicit | **Adds a protocol evaluation (and termination) mechanism** |
| A-5 | Governing principle extended to methodology | DA 2026-07-30 | The reasoning→authority couplet had been applied to methodology twice (mapper, F-T1) before being stated | Explicit | Completes the governance principle as a matched pair |
| A-6 | Artifact classes **flagged and routed** | Protocol Phase 16 + ES-004.3 | ES-004.3 already governs four roles; D-1 already owns placement | **No decision taken** — routed to D-1 | **Prevented a duplicate governance taxonomy** |
| A-7 | *"The ubiquitous language lives once"* elevated as the principle beneath *"rules live once"* | DA 2026-07-30 | A-6 was correct but under-explained: the harm is a split model, not untidy filing | Explicit | Names the DDD cause, not just the filing rule |
| A-8 | `Qualified` de-numericized | DA 2026-07-30 | "3+ slices" hard-coded a governance judgement as a count | Explicit | **Sufficiency returns to governance**; no arithmetic gate |
| A-9 | `Impact` column added to this log | DA 2026-07-30 | Log showed provenance but not consequence | Explicit | Future reviewers see *why* each amendment mattered |
| A-10 | Two feedback loops documented as distinct bounded contexts | DA 2026-07-30 | Engineering and methodology loops had been described interchangeably | Explicit | **Names the failure mode: process becoming the objective** |
| A-11 | Protocol ubiquitous-language glossary | DA 2026-07-30 | Nine terms stabilized through repeated use across WP-1/WP-2/F-2 | Explicit | Protects the vocabulary from stylistic renaming |
| A-12 | Separation-of-concerns split **prepared, not executed** | DA 2026-07-30 | Four concerns share one document; acceptable at OPERATIONAL, not at GOVERNED | Explicit — **as a promotion condition** | Prevents promotion from carrying a mixed-concern shape into a standard |
| A-13 | Caution: sophistication ≠ engineering quality | DA 2026-07-30 | The protocol grew materially in one day while one slice of software shipped | Explicit | **Keeps the software the primary artifact** |

| A-14 | **Freeze for routine work** | DA 2026-07-30 | Thirteen amendments and one software slice in a single day; A-13's risk demonstrated by the log itself | Explicit | **Freezes the protocol for routine work**; amendment now requires operational evidence, not a good idea |

**The log's arc is its own evidence.** Early amendments *added capability* (A-1..A-5: status, hierarchy, evidence tracking, evaluation, principle). Later ones *added restraint* (A-6 stopped a duplicate taxonomy · A-8 removed an arbitrary numeric gate · A-12 delayed a document split · A-13 constrained growth · A-14 froze it). A governance model whose amendments shift from adding mechanism to limiting unnecessary mechanism is maturing — and the most valuable rows remain the ones recording what did **not** happen.

## Traceability

DA instruction 2026-07-30 (protocol issued · five rulings · ladder · mandatory evidence register · supersession wording) · originating practice: WP-1 (`.claude/plans/WP-1-evidenceset-v3.md`) and WP-2 (`.claude/plans/WP-2-apm-core.md` §§1–15) · governing principle originated in WP-2's mapper self-correction · parked promotion candidate recorded 2026-07-26 (session log) with the ~WP-3/WP-4 revisit point · related: ES-006 promotion ladder · ES-004.3 artifact lifecycle · DDD Tactical Governance Principles (`engineering/knowledge/methodology/`).
