# Architecture Knowledge Transfer (AKT) — Part 3

**Engineering Governance**

| | |
|---|---|
| **Artifact** | Architecture Knowledge Transfer (AKT) — **Part 3 of 10 + Appendix** |
| **Part** | **Part 3 — Engineering Governance** |
| **Baseline** | **PKS Phase III — Operational Validation** · **EEP STABLE** (DA 2026-07-10) · **IMPLEMENTATION_PROTOCOL FROZEN for routine work** (DA 2026-07-30) · **review framework FROZEN FOR STABILIZATION** (ARB 2026-07-30) |
| **Version** | 1.0 |
| **Status** | **Living Architecture Reference.** *Lifecycle: **AUTHORED**, not ISSUED* |
| **Audience** | AI Architects · Principal Engineers · Architecture Review Board |
| **Authored** | 2026-08-01 · branch `feature/pb003` · HEAD `622c515d4` |
| **Prerequisite** | **Part 1** (philosophy, roles, the behavioural contract). **Part 2** (what is being governed) |

---

## 0. Scope, and the one thing to understand first

**Part 1 gave the governance *philosophy*. Part 3 gives the governance *machinery*** — who the authorities are, which flows exist, how a ruling comes into being, what vocabulary is binding, and how a work package is governed from idea to closure.

**Deliberately not restated here** (cite, never copy — ES-005.4): the four governance categories and their invariants (**Part 1 §5.5**) · the review-framework layer map (**Part 1 §5.9**) · the ES set (**Part 2 §3.2**). This Part *uses* them.

> ### **The single sentence that explains this entire Part:**
> ***Governance in this programme is not a review culture — it is a state machine over work, in which every transition has exactly one named authority, produces exactly one durable artifact, and cannot be entered by anyone else, including by the person who is obviously right.***

**And the corollary that makes it work rather than merely slow:** ⚠️ ***a failing gate ESCALATES, it does not block.*** A rule that cannot be satisfied names the authority that must be engaged and reports that the change is larger than it was presented as.

---

## 1. The authorities — who decides what

### 1.1 The role set

**The provider-independent roles (EEP §2 — one home, binding on *every* engineering work item, in any project, in any language, by any engineer, human or automated):**

| Role | Responsibility |
|---|---|
| **Engineer** | Prepares implementation plans · implements **only approved scope** · performs verification · submits implementation reports. **May be a human engineer or an automated engineering agent — the obligations are IDENTICAL** |
| **Independent Reviewer** | Evaluates implementation plans **before** approval. **Must be independent of the plan's author.** May be a human architect, a review board, or an automated reviewer |
| **Decision Authority** | Approves or rejects plans · accepts or contests reports · decides continuation. **Always accountable to the organization; final authority is NEVER delegated to an automated system** |
| **Stakeholder** *(optional)* | Provides the need the work serves; consulted when the objective or its acceptance criteria are unclear |

> ### ⛔ **THE SEPARATION-OF-DUTIES RULE, stated once and never relaxed:**
> **One person or system may NOT occupy both the Engineer and the Independent Reviewer roles for the same work item.**

**The programme-specific roles observed in the record:**

| Role | What it does | Evidence in the record |
|---|---|---|
| **ARB** — Architecture Review Board | Owns the rulings register · **authorizes** execution · **accepts** delivered work · **ratifies** interpretations · declares scope and freezes | `ADR-AIP-LOG-Platform-Rulings.md` (**Owner: Architecture Review Board**); R-43..R-64 |
| **Decision Authority (DA)** | Owns the EEP · **approves plans (EP-01)** · owns the ES ratification batch · issues canonical version nomenclature · rules on protocol adoption level | **R-46**; EEP header (*Owner: Decision Authority*); IMPLEMENTATION_PROTOCOL (*FROZEN FOR ROUTINE WORK, DA 2026-07-30*) |
| **Principal Architect (PA)** | Issues instructions that become standards; endorses syntheses; states durable governance distinctions | **R-41** (*"Principal Architect instruction"*) · the 2026-07-28 governance distinctions · the *"PA-endorsed synthesis"* awaiting the DA/PA choice |
| **Recording Architect** | **RECORDS. Does not decide.** Prepares packages and records; never pre-fills an outcome | *"Record prepared by: Recording Architect"* on the acceptance records |
| **Engineering** | Executes authorized work; owns **mechanism** (level 3) and implementation | the four-level model, Part 1 §4.5 |

### 1.2 `OBSERVATION` — "Decision Authority (ARB)" is written as one authority but acts as two

**The ES documents each carry `Authority: Decision Authority (ARB)`** — a single parenthesized identity. **But R-46 distinguishes them explicitly:**

> **R-46 — WP-7 EP-01 PLAN APPROVED — Planning Governance · Approval · DECISION AUTHORITY** *("not the ARB: EP-01 plan approval is the Decision Authority's act, **which is why it was voted separately**")*

And Part 1 §5.5's category rule states the general form: **Planning Governance alone spans two authorities** — EP-01 plan approval = **Decision Authority**; opening a work package = **ARB**.

**Consequence for you, and it is practical rather than academic:** **never infer the authority from the category, and never write "the ARB approved the plan."** *For plan approval, name the Decision Authority; for authorization and acceptance, name the ARB.* **Whether DA and ARB are one body wearing two hats or two bodies is not resolved in the repository** — recorded here as an **observation, routed, not enacted.** *Ask the human if a specific act's authority is genuinely ambiguous; do not pick.*

### 1.3 Where the AI sits — and it is not on the authority row

**ES-001.2 is the boundary:** *documents record governance, they do not create it.* **Only explicit ARB decisions create governance.** The Standards Index makes it a matrix column:

| Primary Decision Authority | Meaning |
|---|---|
| **Machine** | a structural instrument decides (paths, ids, folder rules) |
| **AI evaluates** | **the AI reasons and recommends** |
| **Human decides / approves / accepts** | the authority act |

> **The AI always *evaluates and recommends*; authority stays with governance.** **`AI-01: AI never owns engineering authority`** *(the Engineering Standards candidate spec, R-32)*.

**And the load-bearing detail: ⛔ workflow words are not approvals.** *continue · looks good · go ahead* are **permission to proceed — never Approved / Promoted / Retired / Closed.** When a governance decision is needed: **STOP and ask per item (Approve / Reject / Defer).**

---

## 2. The governance state machine

### 2.1 The chain — memorize this, it resolves most questions

```
lifecycle transition  →  category      →  AUTHORITY        →  artifact
(what happened)          (who governs)    (who exercised)     (what remains)
```

> ⛔ **A transition with no named authority DID NOT OCCUR — it was only described.**
>
> **INVARIANT: one category = one lifecycle transition = one durable artifact.** If a category cannot demonstrate all three, it is incorrectly modelled.
>
> **A transition that appears to belong to two categories IS two transitions, requiring two authority acts.**

**Derive the category from the EVENT, never from the terminology.** *Asking "which label sounds right?" is how the model drifts; asking "which business transition is being exercised?" is how it holds.*

### 2.2 The declared scope of the model — and what it deliberately excludes (R-63, R-64)

**R-63 — GOVERNANCE SCOPE DECLARED: the canonical governance model governs the WORK-PACKAGE LIFECYCLE.** Grounds: operational validation showed every supported transition — *opened · authorized · accepted & closed · design decided* — classifies consistently **within that scope**.

**⭐ The consequence is subtle and important: acts operating UPON GOVERNANCE ARTIFACTS are explicitly OUTSIDE the current scope** —

| Act | Example |
|---|---|
| choosing a remediation **strategy** | **R-49** |
| **annotating** a ruling | **R-53** |
| **correcting** an approved plan | **R-57** |

> **They cease to be DEFECTS and become OUT-OF-SCOPE.** The validation demonstrated **a scope boundary, not a model defect** — *and the boundary is now declared rather than merely implied.*

**R-64 — META-GOVERNANCE EXTENSION DEFERRED.** No new categories. The reason is a governance principle worth carrying everywhere:

> ### ***A model should be as simple as the problem it currently solves.***

*Governance acting upon governance artifacts may warrant a **second bounded context** — materially larger than adding a category, and it **requires SUSTAINED operational demand, not a single validation.*** **The validation stands as evidence for that future decision without forcing it today.**

---

## 3. The approval flow — EP-01 · EP-02 · EP-03

**Relation to the platform:** the EP rules are **PublicDigit's project-specific binding of the provider-independent EEP.** The protocol defines the lifecycle and roles **once**; the EP section binds it to PublicDigit's tools, gates, templates and depth tiers. **On conflict, the binding may be STRICTER than the protocol, never looser.**

**And the namespace split:** **EP rules govern *how a working session operates*** (human or AI, any provider — Claude, Copilot, Cursor, Gemini, Codex behave identically); **ER rules govern *what the work must satisfy*.** ⛔ **Assistant configuration files (e.g. `.claude/CLAUDE.md`) may only REFERENCE EP rules, never restate them.**

### 3.1 EP-03 — Engineering Readiness Review (the ERR)

**Before any non-trivial implementation: derive as many answers as possible from authoritative project knowledge — ADRs, BDRs, the constitution and architecture artifacts, engineering standards, developer guides, source code, tests, repository history — and ask the human ONLY what cannot be determined confidently.** The review's output **IS** the EP-01 plan.

> ***This is the Engineering Conversation: think first; code never starts the conversation.***

| Domain | The questions *(derive first; ask only gaps)* |
|---|---|
| **Business** | What problem? Who benefits? **Which business/constitutional invariant applies — what can never be violated?** |
| **DDD** | Which bounded context? Existing or new aggregate? Ubiquitous-language changes? Domain events, policies, invariants? |
| **Architecture** | Which style and constraints govern here? **Which ADR governs this change?** |
| **Process** | Does an approved plan exist? **If not: STOP — produce one (EP-01)** |
| **TDD** | Existing or new behaviour? Where is the failing test? **Can RED be written first — and if not, why?** |
| **Design** | Which existing pattern applies (strategy · factory · state · specification · repository · application/domain service)? **Reusing or inventing?** |
| **Impact** | What changes — aggregate, API, database, UI, contract, developer guide, ADR? |
| **Verification** | How will correctness be known — tests, architecture tests, PHPStan gate, mutation, fitness functions, human review? |
| **Completion** | DoD boxes, developer guide, evidence, commit shape, review path? |

**Stopping condition — the ERR ends ONLY when:** ✓ sufficient understanding exists · ✓ **every remaining unknown has been explicitly identified and routed to the human** · ✓ a plan can be produced. **Otherwise implementation shall not begin.**

**Depth scales with the task; the sequence does not.** A typo answers *"trivial/skip"* in seconds; a PB ticket answers in full — **for PB tickets the 17-section IDD *is* the ERR's complete form.**

**⭐ State derivations explicitly in the plan, so the human sees what was derived and decides only what remains:**

```
Derived: context = Election · aggregate = existing · ADR = ADR-PL-01
Cannot determine: extend aggregate vs. new policy — please decide
```

### 3.2 EP-01 — Plan First

| Step | |
|---|---|
| 1 | **Understand** the request |
| 2 | **Analyze** the relevant architecture — **frozen artifacts win over memory** |
| 3 | **Produce a plan** *(for PB tickets the plan IS the IDD → Architecture Review path; for smaller governed work, a written plan in the plans directory)* |
| 4 | **Wait for explicit human approval.** ⛔ **APPROVAL APPLIES TO THE PLAN, NOT MERELY TO THE TASK REQUEST** — ***"implement X" authorizes PLANNING X; only an approved plan authorizes IMPLEMENTING X*** |
| 5 | **Implement only the approved plan** |
| 6 | **Re-plan on invalidation:** if implementation reveals the approved plan is no longer valid — **STOP**, explain why, present the revised plan, **wait for approval.** *Never silently change direction mid-implementation* |

> **Step 6 is the load-bearing clause.** *(The process document says so in its own words.)*

**How this rule bites in practice — the F-A finding:** the WP-7 plan's status line had read *"awaiting EP-01 approval"* **all along**. **Authorizing WP-7 without approving its plan would have satisfied the roadmap and violated EP-01**, so the decision pack's item became *plan approval **AND** execution authorization* — and dependency verification then **split it into two votes** (see §8.1). **R-46 records the approval as attaching to *the plan*, and to the plan *as amended*, because the text had changed that day.**

### 3.3 EP-01-Light — the ceremony shrinks, the gate does not

**Under a frozen baseline most changes are small.** For any repository mutation below IDD weight, present this (30–60 seconds) and **wait for approval**:

```text
EP-01 Implementation Review
Objective        — one sentence
Classification   — ☐ Record only  ☐ Documentation  ☐ Runtime  ☐ Product  ☐ Architecture
Risk             — Low / Medium / High
Files affected   — list
```

### 3.4 "Non-trivial" — the decision table, so planning does not degenerate into ceremony

| Task | Planning required? |
|---|---|
| Architecture-touching change · New feature · **Refactoring** · Database schema/migration · **ADR / process / governance change** · API/contract change · Domain model change · **Bug fix affecting behaviour** | ✅ **Yes** |
| Typo · formatting · comments · documentation spelling · import cleanup | ❌ Usually no *(judgment; **ER rules and gates still apply**)* |
| Questions · explanations · translations · text review — **analysis without repository mutation** | ❌ No |

### 3.5 EP-02 — Completion Review

> **Verification answers *"does it work?"*** (executable gates, PASS/FAIL). **Completion Review answers *"did we implement the APPROVED PLAN?"*** — a human comparison of delivered work against the approved plan/IDD **before the work is called done.**

**These are different questions and both are required.** *A slice can pass every gate and still not be what was approved.*

---

## 4. The execution flow

### 4.1 The EEP lifecycle — no stage may be skipped

```
Idea
  ↓
Implementation Plan          (Engineer)
  ↓
Independent Review           (Independent Reviewer — NOT the author)
  ↓
Approval                     (Decision Authority)
  ↓
Implementation               (Engineer — APPROVED SCOPE ONLY)
  ↓
Verification                 (Engineer — evidence-based)
  ↓
Implementation Report        (Engineer)
  ↓
Decision                     (Decision Authority)
  ↓
Next Phase ──► new Implementation Plan     or     Complete
```

> **No stage may be skipped. The DEPTH of each stage scales with size and risk; the SEQUENCE does not.**

**The plan's minimum contents (EEP §4):** Objective · **Governing decision** (*the architectural decision or standard that justifies the change*) · Scope · Files affected · Risks · **Verification strategy** (*HOW correctness will be known — the methods*) · **Evidence** (*WHAT KIND of artifacts verification is expected to produce* — the exact artifacts are recorded in the report, because **verification does not always know beforehand precisely what evidence will exist**) · **Out of scope**.

**The EEP's own change rule, and it is the platform's discipline applied to itself:** **STABLE — *changes only on usage evidence from real engineering sessions; imagined improvements are rejected by default.***

### 4.2 The seventeen phases — the DDD-driven implementation protocol

**`.claude/IMPLEMENTATION_PROTOCOL.md` · FROZEN FOR ROUTINE WORK (DA, 2026-07-30).** Every work package runs these in order.

| # | Phase | The obligation |
|---|---|---|
| **1** | **Commission Reset** | **Every work package is a NEW COMMISSION.** Carry forward only approved ADRs/decisions/rulings/standards — ***previous REASONING is history, not authority*** |
| **2** | **Authority Register** | **Before reading code**, list every governing authority and what it governs |
| **3** | **Business Understanding** | Capability · objective · policies · invariants · ubiquitous language **(and rejected vocabulary)** · ownership — ***ownership is the business's, never the repository's*** |
| **4** | **Strategic DDD** | Bounded context · upstream/downstream · published language · ACLs · shared kernel · ownership boundaries — **before any tactical design** |
| **5** | **Business Model Fidelity** | Does the *planned* implementation preserve language, ownership, aggregate boundaries, published language, invariants, policies, autonomy? **If undemonstrable — STOP** |
| **6** | **Architectural Traceability** | Every class, enum, port, mapper, event, migration and test answers ***"which authority requires this?"*** **No authority → do not create it** |
| **7** | **Simplification Review** | Can any planned component be removed while still satisfying the architecture? **If yes, remove it — BEFORE coding** |
| **8** | **Business Assumption Review** | Classify every interpretation: **explicit authority** (implement) · **derived implication** (implement *with traceability*) · **architectural assumption** (record + confirm first) · **open business question** (**stop**) |
| **9** | **Tactical DDD** | Aggregates, entities, VOs, services, events, repositories, factories, application services; protect boundaries; no infrastructure in the domain; ***orchestration is not an aggregate unless the business gives it identity*** |
| **10** | **RED First** | Failing tests expressing **business invariants, policies, transitions, rules and architectural constraints** — **not implementation detail** |
| **11** | **GREEN** | **The minimum satisfying RED.** No speculative abstraction; **no anticipating later work packages** |
| **12** | **Refactor** | Only if behaviour is unchanged, architecture becomes clearer, **and traceability survives** |
| **13** | **Architectural Verification** | Verify against the business model and approved architecture — ***never code against itself*** |
| **14** | **Static Analysis Review** | Findings are **design feedback**, not tooling noise |
| **15** | **Failure Analysis** | On failure, **diagnose before patching**: wrong implementation? wrong test? wrong architecture? wrong assumption? **wrong authority reading?** Fix the root cause |
| **16** | **Governance Discipline** | **Never** reopen closed commissions · implicitly modify approved architecture · promote observations into standards · restructure unrelated code · **create decisions by implementation.** ***Better ideas get RECORDED AND ROUTED*** |
| **17** | **Completion Review** | Fidelity · language · aggregate boundaries · ownership · traceability · derived implications documented · assumptions classified · **every artifact justified** · static analysis acceptable · **tests express business behaviour** |

### 4.3 The protocol is held to its own evidence standard

**A mandatory operational-evidence register (DA refinement) records, per protocol element, where it was FIRST demonstrated and where it was CONFIRMED.** Selected entries worth knowing because they show what the phases actually caught:

| Phase | What the evidence records |
|---|---|
| **5 Business Model Fidelity** | **surfaced the WP-2/WP-4 transport boundary instead of assuming it** |
| **7 Simplification Review** | **4 VOs reused instead of created** |
| **8 Business Assumption Review** | F-T1 classified as a *derived implication*; **the RED claim was NARROWED to WP-2's own obligation** |
| **14 Static Analysis** | `whereNotIn` type-erasure **fixed at the root (21 → 0 errors), never suppressed** |
| **15 Failure Analysis** | **the state guards rejected the author's own test**; the five-way diagnosis returned *"incorrect test"* — **the test changed, the code did not** |
| **16 Governance Discipline** | **a mapper deletion was REVERSED on authority**; a placement commission **deferred to D-1 rather than executed**; ***the protocol did not self-promote*** |
| **12 Refactor** | ⚠️ ***— none yet. Honest gap: no slice has yet needed a behaviour-preserving refactor. UNTESTED PHASE*** |

> **Note what that last row is doing: a frozen protocol openly recording that one of its own phases has never been exercised.** *This is the programme's evidence discipline turned on its own process — and it is why the register exists.*

**Judge the protocol by outcomes, never by its own completeness.**

---

## 5. The review flow

### 5.1 Review philosophy and the eleven objectives, in mandated order

**Load the relevant framework layer(s) BEFORE reviewing any Strategic DDD or governance artifact** (Part 1 §5.9). The **Method** supplies the workflow:

| # | Objective | What it looks for | Quality |
|---|---|---|---|
| **1** | **Knowledge Integrity** | Semantic drift · hidden assumptions · unstated reinterpretation · **the four acts: representation · synthesis · interpretation · INVENTION** · accidental redesign | C (and A) |
| **2** | **Strategic DDD Integrity** | Ubiquitous language · bounded contexts · relationships · context-map semantics · domain invariants · strategic constraints · architectural principles | A, F |
| **3** | **Authority Integrity** | ***Every normative statement must name a governing artifact.*** **Never infer authority. Never elevate a supporting artifact into a governing one** | B |
| **4** | **Governance Integrity** | Wording that **accidentally performs** promotion · certification · acceptance · authority disposition · implementation authorization · governance interpretation. ***An ADR must NEVER perform an Authority act by implication*** | B |
| **5** | **Knowledge Classification** | Strategic Architecture · Governance · Authority Record · Decision · Constraint · Assumption · Interpretation · Historical Record · Implementation Guidance · Observation · Recommendation — **report whenever two classes MIX** | C, D |
| **6** | **Strategic/Tactical Boundary** | Any transition across it. **Also: what crosses a context boundary, and whether it crosses as *inheritance* or *translation*** (quality F). *Strategic DDD defines what exists, why, boundaries, relationships, invariants, constraints — **never** aggregates, entities, repositories, APIs, services, persistence, frameworks, technologies, AI agents, deployment, or implementation patterns* | A |
| **7** | **Temporal Integrity** | Each statement's temporal class: **constitutional truth · timeless fact · point-in-time · execution state · historical evidence.** ***Temporary state must not become permanent architectural knowledge*** | D |
| **8** | **Consistency & Cascade Review** | Contradictions · duplicated semantics · incompatible terminology · taxonomy drift · conflicting classifications. ***ACTIVELY SEARCH for cascades:*** a correction in one section frequently requires corrections elsewhere | E |
| **9** | **Decision Quality** | Explicit · bounded · testable · implementation-independent · authority-supported · internally consistent. **Reject decisions that depend on implied assumptions** | B |
| **10** | **Document Balance** | Whether material **explains rather than decides**, records transient state, or belongs in an appendix. *Normative definitions stay in the body; transient evidence generally does not* | D |
| **11** | **Knowledge Cohesion** | ***Does this artifact have a SINGLE architectural responsibility?*** Review for **responsibility creep** — *an ADR must not simultaneously be architecture, governance manual, review log, implementation guide, and project status report* | C, D |

### 5.2 The eighteen conduct rules — the Discipline, by concern

**Baseline v1.0 FROZEN.** Grouped as the document groups them:

| Part | Rules | The discipline |
|---|---|---|
| **I — Scope** | **1** distinguish *architectural constraint · architectural property · tactical implementation mechanism* · **2** recommend architectural constraints freely · **3** do NOT prescribe implementation mechanisms unless the certified architecture already mandates them · **4** express implementation expectations as **conformance requirements** (*"shall demonstrate"*), **never as realization techniques** (*"use ArchUnit"*, *"use an ACL"*) · **7** verify each recommendation stays within scope before issuing it | **Stay on your side of the strategic/tactical line** |
| **II — Classification** | **5** classify every identifier mapping as *authoritative / traceable / inferred / proposed* · **8** every finding declares its **authority basis** (one value) · **9** every review output declares its **constitutional category** (one value) · **11** before raising any finding ask ***"what constitutional act would resolve this?"*** · **12** every recommendation states whether it is *mandatory for conformance · strengthening · informative · a future-methodology candidate* | **A finding's constitutional weight comes from its authority basis, NOT its severity label. Severity ⊥ category — never merge them** |
| **III — Source of truth** | **6** improvements to inherited normative wording belong in the **authoritative source** and propagate downstream — **never modify an assembly artifact independently** · **10** before proposing new wording, locate it: *(a) authoritative source → source amendment · (b) assembly artifact → propagation · (c) explanatory prose → **only (c) may be rewritten directly*** | **Fix the source, not the copy** |
| **IV — Reporting** | **13** when a review changes its own finding during self-audit, record *original classification · revised classification · the governing rule responsible · the reasoning* · **14** declare **review scope** before issuing findings and **verdicts apply only within declared scope** · **17** report **by constitutional category FIRST, severity second** · **18** every declared scope shall **identify its governing artifacts**, and **no finding may exceed their authority** | **Review history is evidence for methodology refinement, never hidden editorial evolution** |
| **V — Provisional / declined** | **16 PROVISIONAL** *(n=1)* — every finding declares its **evidence origin** · **15 DECLINED** *(n=0)* — per-finding confidence labels | See §7 on the admission ladder |

**Rule 18 completes the chain:** `scope → authority → evidence → finding → constitutional act`.

**Rule 11's escape hatch is important and under-used:** **if the required constitutional act is unclear, classify the observation as a QUESTION, not a finding.**

**Two rules earned their place by catching real defects, and the evidence is recorded with them:**
- **Rule 5** caught **IBC-M11** — *a Major finding asserted against a requirement **the reviewer had supplied**.* ***Citing a governed identifier does not make the requirement governed.***
- **Rule 9** re-classification revealed **0 architecture defects** where a severity list had read as *"substantially wrong architecture"*, and **split IBC-m3, whose merged form made a routing error look like a drafting error.**
- **Rule 14:** the unscoped IBC-1 *"REVISE"* invited the reading *"architecturally wrong"*; **scoped, it reads Architecture PASS · Governance REVISE.**

### 5.3 The finding vocabulary — authoritative in the Method, referenced by the Discipline

| Field | Permitted values — **exactly one** |
|---|---|
| **Authority basis** | *baseline violation · accepted-architecture violation · methodology violation · derived architectural constraint · reviewer recommendation* |
| **Constitutional category** | *architecture defect · governance defect · methodology observation · improvement recommendation* |
| **Evidence origin** *(**PROVISIONAL**, n=1)* | *artifact-local* (independently reproducible **from the reviewed artifact alone**) · *cross-artifact* (**must identify the governing artifact**) · *reviewer knowledge* (**must be clearly identified**; normally converted into traceable evidence before becoming mandatory) |
| **Severity** | ⛔ **NO canonical enumeration exists in any layer — see Q-FW-1.** Convention in use: *Critical · Major · Minor · Recommendation*. **Do not invent one** |

**Why Q-FW-1 stays open rather than being tidied up — this is the admission filter refusing to fire:** *defining a severity scale here would be reviewer-invented vocabulary, which the admission filter forbids absent evidence that its absence let a defect escape; **no such evidence exists** (Rule 9 already prevents severity from carrying constitutional weight, which is what would make an undefined scale dangerous).* **Recorded as a question for the Authority, deliberately not resolved.**

**And the relocation principle that produced this layout (FW-1):** ***moving a vocabulary between layers changes WHERE it is defined, never HOW MUCH evidence supports it.*** **Each rule was split at its own seam rather than moved whole:** the *obligation* (`every finding shall declare its authority basis` = output shape) went to the Method; the *reasoning constraint* (`never merge severity with category` = reviewer conduct) stayed in the Discipline. ***Moving the second would have re-created the original defect with the layers reversed.***

### 5.4 The review gate — a reviewer may not act on its own findings

> **`review delivered → Authority disposes findings → apply → record`**
>
> **A reviewer may not apply its own findings before disposition — INCLUDING ITS OWN SUPPLEMENTARY FINDINGS.**

**The precedent, and it is the cleanest illustration in the whole record (FW-1, 2026-07-30):** the reviewer **found** a defect · **recorded** it · **did NOT fix it** (the fix touched the frozen baseline) · **the Authority disposed** · **then the reviewer executed.** *CCP-1 §12.6's chain applied to the review framework itself.* **Discipline v1.0's freeze constrains reviewer accretion, not Authority acts.**

**Reviews do not absorb adjacent governance work.** And:

> ### ***VERIFICATION IS ITSELF A RESULT*** *(Authority determination, CDR-1)*
>
> A verification that finds nothing has produced a result. **A stabilization pass that starts editing is no longer a verification.**

**What a surviving review is and is not evidence of:** a review that survives scrutiny evidences **the review's soundness** — not the correctness of everything it examined. And **a review's value is not the count of its findings.** The recorded exemplar: an observation was raised → **verification challenged the CLASSIFICATION, not the observation** → the classification dissolved (*the two values are not of the same kind*) → **the finding disappeared** → **a methodological candidate remained.** *Net findings: zero. Net value: a candidate and a sharper model.*

**Order of operations when assessing a historical finding** *(Authority, 2026-07-31)*: **1** restate the original claim · **2** verify the current object · **3** compare claim and evidence · **4** **only then classify.** *Restating first prevents re-litigating a claim nobody made.*

---

## 6. How rulings are issued

### 6.1 The register

**`engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` — Living (APPEND-ONLY) · Owner: Architecture Review Board.**

**Why it exists (structural correction, ARB 2026-07-08):** rulings had been recorded **inside a proposal document**. Once the Baseline was accepted, **the proposals became historical evidence — *what was proposed* — and must never be edited again.** So: **R-1..R-29 remain there as the historical record; from R-30 onward, rulings live in the register.**

> ### **Proposals stay frozen · ADRs record acceptance · the log records ongoing rulings.**

**R-30 seals it: proposals are historical evidence — no further edits, EVER.** **Moves allowed, edits never.**

### 6.2 R-34 — ruling-classification discipline (apply this to every review comment you make)

**Three classes of review information:**

| Class | What it is | Where it goes |
|---|---|---|
| **1 · Reviewer observations** | discussion | **session logs only** |
| **2 · ARB decisions** | rulings / ADRs | **created ONLY on explicit ARB adoption — never by inference from praise or suggestion** |
| **3 · Implemented engineering changes** | code / docs | the repository |

> ⛔ **Reviewer suggestions are NOT automatically rulings. The DEFAULT classification is *observation*.**
>
> **Purpose, stated by the ruling itself: *the rulings log must not grow faster than the software.***

### 6.3 The anatomy of a ruling

Every recent row carries, explicitly:

```
R-nn | date | TITLE — <Category> Governance · <Transition type> · <AUTHORITY>
     | grounds/evidence | recorded limits | Effect
```

**Worked example (R-48):** *WP-7 SLICE 7A ACCEPTED — **Delivery Governance · Approval · ARB**. Evidence: slice 7A GREEN report — 11/11 keystones · Deptrac 0 with `deptrac.yaml` unmodified · PHPStan max clean · Architecture suite 149 green · developer guide filed · RED report filed.*

**⭐ Two habits visible in nearly every ruling, and you should reproduce both:**

1. **Guards are named and shown satisfied.** R-47: *"Guard `WP-6 ACCEPTED ∧ WP-7 PLAN APPROVED` satisfied by R-43 ∧ R-46 (and `PLAN APPROVED`'s own guard `G-1 RESOLVED` by R-44); zero architectural gates remain."*
2. **Limits are recorded WITHOUT qualifying the decision.** R-43: *"**Recorded limit on the evidence (not a qualification of the acceptance):** the AP-1/AP-2 defect class — *a business value was invented* — is covered by NO automated gate."* R-44: *"**Recorded limit:** 'Deptrac passes unmodified' was an analytical prediction at ruling time, not an executed result."*

> **A prediction will not be presented as a verification.** *Say which it is, and the decision can still be made.*

### 6.4 Annotation vs amendment — how a ruling is corrected without rewriting history

**The mechanism (R-53, under ES-004.3):**

| | |
|---|---|
| **THIS IS AN ANNOTATION, NOT AN AMENDMENT** | the annotated ruling's **decision text is unchanged** and its effect **STANDS** |
| **Where the note lives** | **in the NEW ruling's row** |
| **What the old ruling receives** | **a minimal forward-pointer status annotation — permitted where decision text and history are not** |
| **What it fixes** | a recorded weakness: **supersession and qualification had been FORWARD-LINKED ONLY**, leaving a reader at the old ruling with **no pointer onward** |

**The content of R-53 is also a model of how to state an unresolved fact:** *reproduced evidence establishes that under the documented protocol at commit `22d604844` the suite terminated with a fatal error and the merge gate would not have completed successfully **under those reproduced conditions**. The note **does not determine whether the gate was actually executed at the time of acceptance**. Whether the evidence was **FALSE** (run and misreported) or **UNSUPPORTED** (not run) **remains UNDETERMINED from repository evidence and is not resolved by this note.***

> ***Two hypotheses, neither chosen, the difference named, and the acceptance left standing. That is what "the strongest claim the evidence supports" looks like when the evidence is bad news.***

### 6.5 The register audits itself — R-61 → R-64

| Ruling | Act | The lesson |
|---|---|---|
| **R-61** | Governance validation **ACCEPTED**. 17 rulings (R-43..R-60) classified against the chain: **11 clean · 2 recording errors · 2 type inconsistencies · 3 unclassifiable · 1 weak** | **Accepted because *the validation produced evidence without modifying the canonical model*** |
| **R-62** | Recording corrections **A1** (*accepted and closed* is typed **Acceptance**; R-43/R-48 had typed it **Approval** — **one transition carried two types**) and **A3** (**R-50 authorized an engineering activity yet was filed Delivery**, where the model says **Execution**) | **Type labels are corrected; SEMANTICS are unchanged** |
| **R-63** | Governance **scope declared** (§2.2) | **Three previously-unclassifiable rulings became OUT-OF-SCOPE rather than defective** |
| **R-64** | Meta-governance extension **DEFERRED** | ***A model should be as simple as the problem it currently solves*** |

> **A governance model that measured itself, found 6 of 17 rows imperfect, corrected the labels, declared a scope boundary, and then refused to grow. Read that sequence before proposing any governance addition.**

### 6.6 Amending a standard under the freeze

**The rules, and the correction the programme made to its own understanding of them:**

| | |
|---|---|
| **R-37 operational terms** | `engineering/` permits **only bug fixes, broken-link fixes, typo corrections** |
| **R-38** | bans **NEW STANDARDS** — ⛔ **not new CLAUSES in existing ones.** *The decisive distinction, stated by R-41 itself: "rule text hosted ONCE as ES-004.3 (documentation standard, **NOT a new standard document**)"* |
| **Precedent exists and is recent** | `git log -- 'engineering/governance/ES-*.md'` shows **repeated post-R-37 amendments** — R-41/ES-004.3 · ES-006.4 Harvest Question · ES-004.2 plan naming · ES-002 pointer registration |
| **An authority act is NOT an "exception" to the freeze** | **the authority that issued the freeze acts within it by issuing another ruling.** Practice shows both styles — R-39 calls itself *"a recorded governance exception"*, R-41 uses **no** exception language. **The common element is not a label but EXPLICIT ISSUANCE (R-34)** |
| **What R-37 actually constrains** | **SELF-DIRECTED editing. It has never constrained the Authority's own acts** |
| **Required authorization** | **ONE ruling that (a) accepts the package, (b) STATES THE AMENDED RULE TEXT, (c) records provenance.** *Nothing more — no freeze amendment, no exception instrument, no new mechanism* |
| **The seat of the rule** | ***THE RULING IS WHERE THE RULE LIVES; THE ES EDIT MERELY HOSTS IT.*** **Two acts, never one: acceptance ≠ authorization-to-edit** |

**⚠️ A recorded self-correction worth internalizing:** the claim *"no precedent exists for a standards amendment under the freeze"* was **wrong**, and the reason it was wrong is instructive — *"I searched the freeze-note convention and the rulings register but **NEVER RAN GIT LOG ON THE ES FILES**. That is the obvious search."*

---

## 7. Admission and lifecycle vocabulary — binding, and easy to get wrong

### 7.1 Two orthogonal axes, because merging them creates a guarded homonym

**The Authority proposed one flat list. Adopting it verbatim would have collided with vocabulary already in force** — the ladder's `Candidate · Provisional · Governed · Declined` and the documents' `Authored · Proposed · Adopted · Binding`. **`Proposed` already meant something else, and `Provisional` vs `Provisionally Recognized` would have read as two states.** *Merging would have produced exactly the guarded-homonym defect quality F names.*

**Axis A — CONTROL ADMISSION** *(subject: a **rule · quality · objective · method · review contract**)*

| State | Meaning |
|---|---|
| **Candidate** | no qualifying evidence yet, or a hypothesis |
| **Provisional** | **valid for the case that produced it; evidence insufficient to generalize** |
| **Governed** | recognized for general application; **repeated** evidence |
| **Declined** | **NEVER admitted** — insufficient evidence at the admission test |
| **Withdrawn** | **ADMITTED, THEN REMOVED** by contrary evidence |

> **`Declined` and `Withdrawn` are NOT synonyms** — *Rule 15 **declined** at n=1, never admitted; AF-6 **withdrawn** on verification, after being raised.*

**⭐ The verb varies by subject, and that is LAWFUL, not drift:** *provisionally **certified*** (a methodology) · *provisionally **adopted*** (a rule) · *provisionally **recognized*** (a review contract) **are ONE state with correct subject–verb agreement. Do not flatten them to one verb; do not read them as three states.**

**Axis B — ARTIFACT LIFECYCLE** *(subject: **a document**)*

| State | Meaning |
|---|---|
| **Authored** | written; **no governance status** |
| **Issued** | its substance issued by an authority |
| **Proposed** | offered for adoption; **no adoption record exists** |
| **Adopted** | an adoption act is recorded |
| **Binding** | in force on its stated subjects |

> **Axis A and Axis B are ORTHOGONAL.** *A **Provisional** control may live in a **Proposed** document.* **Reading one axis's state as the other's is the error this vocabulary prevents.**

**Cross-cutting dispositions:** **Deferred** — intentionally unresolved pending further evidence, **with the trigger NAMED** · **Superseded** — replaced by a later governed decision, **forward-only; the original stands as history.**

### 7.2 The promotion chain, with owners — and the step everyone collapses

```
Review Complete        (reviewer)
      ↓
Findings Dispositioned (Authority)
      ↓
Remediation Verified   (reviewer / verification commission)
      ↓
Promotion Ready        ★ A STATE DERIVED BY VERIFICATION — NOT A DECISION BY ANYONE
      ↓
Authority Promotion Decision   (the act)
      ↓
Promoted Baseline      (the governed state)
```

> **Each step has a different owner and a different authority; NO STEP MAY ABSORB THE ONE AFTER IT.**
>
> **The fourth is the one most often collapsed: *promotion-ready* is DERIVED, not DECIDED** — hence ***verified-ready ≠ promoted***, the fifth member of a family that already holds:
>
> **frozen ≠ promoted · review outcome ≠ adoption · recommendation ≠ issuance ≠ adoption · closing a review ≠ adopting the artifact**
>
> ***Every member was added because the programme conflated it once.***

### 7.3 RECOGNITION ≠ AGREEMENT ≠ ADOPTION

| Act | Who | Effect |
|---|---|---|
| **RECOGNITION** | a review may recognize a useful idea | **the idea is on the record. NOTHING CHANGES** |
| **AGREEMENT** | the Authority may agree it has merit | **merit is established. STILL NOTHING CHANGES** |
| **ADOPTION** | **only the governing path may adopt it** | **practice changes** |

> ### **If it changes future practice, it is NOT ADOPTED until it traverses the appropriate governance path.**

**GOVERNED on repeated, cross-context application (n=5):**

| n | Case | The gap it closed |
|---|---|---|
| 1 | review recommendations never became architecture | across all twelve reviews |
| 2 | RET-1's body stripped of review-derived governance reasoning | **recognition had begun to write itself into an artifact** |
| 3 | FW-1's finding vocabulary relocated, **not re-authored** | a repair, not an adoption |
| 4 | PMR-7 gained no standing from ERV-R1's acceptance | ***acceptance of the EVIDENCE is not acceptance of the LESSON*** |
| 5 | the Authority **AGREED** with an assessment-ordering rule and it was **routed, not adopted** | ***an endorsement is not an adoption*** |

> ### ***Its force is that it binds the Authority too. A framework in which the deciding party can adopt by agreeing has no entry gate — it has a preference.***

**The full admission chain:** `Recognition → Agreement → Methodological admission (if applicable) → Assessment → Authority disposition → Adoption`.

### 7.4 The contract admission rule — governance economy

> ***Artifact kinds may define review EMPHASES, but review CONTRACTS are introduced only when operational evidence demonstrates a review capability that existing contracts cannot provide.*** **(GOVERNED, n=4)**

**The test is not *"is this artifact different?"* and not *"was this review useful?"* It is:** ***could an existing contract have FOUND these defects?***

**⭐ The evidence is four FALSIFICATION ATTEMPTS with both positive and negative outcomes — not four confirmations.** *Four identical confirmations would evidence a habit; four attempts of which three declined and one recognized evidence **a test that can fail** — which is what a governance criterion must be.*

| Occasion | Evidence | Outcome |
|---|---|---|
| Governance Integrity Review | n=0 | **DECLINED** |
| Methodology Review | n=0 — **and MCA → CDR already does it** | **DECLINED** *(unnecessary, not merely unevidenced)* |
| **Authority Fidelity Review** | one named capability — line-by-line comparison against the register's *offered* and *chosen* options; **AF-3/4/5 reachable by nothing else** | **PROVISIONALLY RECOGNIZED** |
| Strategic Model Publication Review | **eleven real findings, ZERO requiring a capability the KCR lacks** | **DECLINED** |

> **The fourth row is the rule's sharpest demonstration: a review that produced ELEVEN GENUINE FINDINGS was denied a contract, because usefulness is not the test.**

**The operative statements:**

> ***Usefulness is evidence for an EMPHASIS. Unique capability is evidence for a CONTRACT.***
>
> ***A distinct lens is not a distinct capability.*** *(DDD basis: different bounded contexts justify different models because they solve **different problems**; different **artifact kinds** do not automatically justify different **governance mechanisms**.)*
>
> ***A named emphasis costs nothing; a named contract costs a governance construct.***

**Corollary — the error the rule catches:** ***"no existing contract asks my question" is a WEAKER claim than "no existing contract can find my defects."*** *The declined Publication Review rested on the first and was mistaken for the second.*

**Demotion condition, retained:** **an emphasis that changes no reviewer behaviour is not an emphasis.**

### 7.5 The three stabilizers, and their general form

| Stabilizer | Statement | The growth route it closes |
|---|---|---|
| **1** | ***A new LIFECYCLE STAGE does not imply a new review EMPHASIS*** | a new **stage** |
| **2** | ***A new ANALYTICAL SCOPE does not imply a new governance RESPONSIBILITY*** — ***a review that SEES more does not thereby DECIDE more*** | a new **scope** |
| **3** | ***A new governed ARTIFACT TYPE does not imply a new governance CONTRACT*** — ***expanding the FACETS of a responsibility is not expanding the responsibility; the responsibility is invariant while the MECHANISM varies by object kind*** | a new **artifact type** |

> ### **THE FORM EVERY STABILIZATION TAKES: *Variation in X does not imply variation in Y.***

*Evidence that the taxonomy is stabilizing: **the stage count and the emphasis count moved independently for the first time*** (Execution Readiness Verification joined as a stage while emphases stayed at ten — *CCP-1 designs execution, ERV-1 verifies the design: two artifact kinds, one responsibility family*).

---

## 8. Work package governance

### 8.1 The slice lifecycle — the WP-7 worked example, end to end

**This is the canonical trace. Study it once and the model is yours.**

```
                 ┌──────────────────────────────────────────────┐
 ARCHITECTURE    │ G-1 analysed → recommended realization       │
 GOVERNANCE      │ A-1 RATIFIED  R-44   (invariant vs mechanism)│
 (ARB)           │ A-2 RATIFIED  R-45   (ANSWERS vs ACTS)       │
                 └───────────────────┬──────────────────────────┘
                                     ▼
 PLANNING        ┌──────────────────────────────────────────────┐
 GOVERNANCE      │ EP-01 PLAN APPROVED   R-46                   │
 (DECISION       │  · attaches to THE PLAN, as amended          │
 AUTHORITY)      │  · guard: G-1 RESOLVED (R-44)                │
                 └───────────────────┬──────────────────────────┘
                                     ▼
 EXECUTION       ┌──────────────────────────────────────────────┐
 GOVERNANCE      │ SLICE 7A EXECUTION AUTHORIZED   R-47         │
 (ARB)           │  · guard: WP-6 ACCEPTED ∧ PLAN APPROVED      │
                 │  · SLICE-GRANULAR: "7A ONLY — 7B and 7C are  │
                 │    NOT authorized"                           │
                 └───────────────────┬──────────────────────────┘
                                     ▼
                          RED → GREEN → reports
                                     ▼
 DELIVERY        ┌──────────────────────────────────────────────┐
 GOVERNANCE      │ SLICE 7A ACCEPTED   R-48                     │
 (ARB)           │  → accepted IMPLEMENTATION baseline          │
                 └───────────────────┬──────────────────────────┘
                                     ▼
                    (7B repeats: R-51/54 prep → R-56 planning
                     → R-57 correction → R-58 execution
                     → R-59 acceptance → R-60 follow-up opened)
                                     ▼
                        7C: pre-authorization verified,
                            AUTHORIZATION NOT GRANTED
```

**Five properties of this trace to carry forward:**

| | |
|---|---|
| **1** | **Authorization is SLICE-GRANULAR while the gates are slice-granular.** *Dependency verification finding **DD-3** forced this: "authorization was not slice-granular while the gates are"* |
| **2** | **A conflated vote is a defect, even in the pack that asserts the rule.** **DD-1:** item 4 conflated *two* state transitions in one vote → **split into 4a (EP-01 plan approval, DA) and 4b (authorize 7A, ARB) = five votes.** *The split also **gave the ARB latitude it lacked** — approve the plan while holding authorization — and made **4b-without-4a structurally impossible**, which EP-01 requires* |
| **3** | **A decision whose REJECTION spawns an unlisted decision is not fully prepared.** **DD-2:** no agenda branch existed for A-1 being **rejected**. ⛔ **Option (c) — relaxing a correct gate — is explicitly NOT pre-authorized; it must be its own deliberate act** |
| **4** | **Independence must be tested in BOTH directions.** *WP-6 acceptance concerns delivered Adjudication code; A-1/A-2 concern the reading of a WP-7 plan sentence — **neither is evidence for the other**.* The dependency graph is a **DAG with a single sink** |
| **5** | **A plan can be CORRECTED after approval, as its own act.** **R-57:** the approved plan carried a mechanism **R-44 had superseded** (*"consume the existing port"*), which would have instructed **the direct cross-context import TP-1 forbids and Deptrac fails.** *Corrected by ruling — not silently edited* |

**⭐ And the meta-lesson from the dependency verification itself:** it was **run AFTER the pack it should have preceded**, so *"the only honest way to run it was as a TEST of the pack, with a real possibility of invalidating it."* **It did not pass unchanged — three defects, all applied.** *By the platform's own Methodological Fitness Rule, a dependency check that confirms everything is ceremonial.*

### 8.2 The Knowledge Governance Lifecycle

**Knowledge progresses through separated strategic responsibilities. Each stage CONSUMES the previous and PRODUCES inputs for the next. NO STAGE MAY REPEAT OR REPLACE ANOTHER.**

```
Discovery → Review → Impact Assessment → Authority → Controlled Change Planning
  → Execution Readiness Verification → Editorial Execution → Consolidation
  → Strategic Modeling → Certification
```

**Two stages exist for a specific reason:**

| Stage | Why it sits where it sits |
|---|---|
| **Impact Assessment** — **between Review and Authority** | a **governance safety mechanism**: it analyzes consequences **so the Authority inherits no hidden assumptions** |
| **Editorial Execution** — **after Authority** | ***"Authority resolves uncertainty; editors implement resolved uncertainty"*** |

> ### **The governing separation both stages exist to hold: *Authority changes GOVERNANCE state · editorial work changes REPRESENTATION state — and never both in one commission.***

### 8.3 Closing a work package — record one of four outcomes

**At every work-package closure, record exactly one** (the eight-dimension review framework, operationally adopted 2026-07-31):

| Outcome | |
|---|---|
| **Exposed** | the framework surfaced a real issue |
| **Prevented** | it stopped a defect before it landed |
| **Required no changes** | it applied cleanly and found nothing |
| ⚠️ **Failed to classify** | ***the ONLY reopening trigger*** |

**The framework is FROZEN FOR REFINEMENT, NOT FOR USE.** It reopens **only** when an *independent* slice shows it cannot classify or expose a real issue — **never for elegance, preference or simplification.**

**Governance stays EXTERNAL to the framework:** it asks ***who may authorize***, never states ***who does*** — *Taxonomy selects the governance **rule**; the rule identifies the authority.*

**Portable rule from that framework worth remembering:** ***an assigned executor does not substitute for an unowned concept.***

### 8.4 Closure and synchronization (ES-004.3 / R-41)

**An artifact has ONE authoritative lifecycle state at any moment.** When work crosses a lifecycle boundary (**authorization → execution → acceptance → closure**), **every authoritative artifact transitions with it.**

| Role | Obligation |
|---|---|
| **Work plans** | state the **PRESENT** state |
| **Session logs** | preserve the **SEQUENCE** of past states — **append-only** |
| **History** | ⛔ **never rewritten to manufacture consistency** |

**Minimum synchronization checklist at slice closure:** Work Plan · CONTEXT · Session Log · Developer Guide · Acceptance Record · ADR references.

**Provenance of the rule — it was earned:** the **WP-1 closure inconsistency** (a **CLOSED** plan whose header still read *"AUTHORIZED — execution begins…"*), corrected 2026-07-30; **the first checklist execution the same day caught a second instance** (ADR-T22's issuance-time *"Implementation NOT yet authorized"* clause, annotated).

**⚠️ And see Part 1 §7.5: this checklist is currently NOT being satisfied for `.claude/CONTEXT.md`.** *A live, unfixed instance of the very rule this section states.*

---

## 9. Evidence-first — the governance form

**Part 1 §4.2 gave the admission filter. Here is what counts as evidence when an authority is deciding.**

| Principle | Statement |
|---|---|
| **Readiness ≠ acceptance** | ***Readiness is EVIDENCE; acceptance is AUTHORITY.*** Architecturally authorizing a transition **≠** authorizing RED to start |
| **Analysis cannot ratify itself** | `analysis → recommended realization → ratification → resolution`. **A report that recommends AND declares resolved while listing pending ratifications contradicts itself** |
| **Completeness ≠ sufficiency** | *"Completeness is verified; **SUFFICIENCY is the ARB's judgement**, and no acceptance is decided or implied"* |
| **Prediction ≠ verification** | *"'Deptrac passes unmodified' was an **analytical prediction**, not an executed result"* — **and a prediction will not be presented as a verification** |
| **One corpus is one observation** | 47 references and 30 targets are still **one class of failure in one repository** |
| **Same-lineage ≠ independent** | a new lens over the same corpus **is not new evidence** |
| **A refusal is evidence** | ***a model that REFUSES is as much evidence as one that answers*** |
| **Verification is a result** | a verification that finds nothing **has produced a result** |
| **Recorded limits do not qualify decisions** | state the limit; **make the decision anyway** |

**The honest-failure-direction question** *(GOVERNED at n=5)* — asked of every substantial claim: **which way is this most likely to be wrong?** *For the governance baseline, the recorded answer is: **likelier TOO EXPENSIVE FOR ITS VALUE than wrong.***

**Negative-claim discipline** — and note that it was broken a third time **while already in force in the document that states it**. *State what was checked, over what scope, with what instrument. "No X exists" without a stated search is not a finding.*

---

## 10. ADR philosophy

| Principle | |
|---|---|
| **One architectural question per ADR** | *ADR-MP exists because bundled decision-log entry **D-12 was too large** and was split* |
| **ADRs RECORD decisions; they do not INVENT them** | Objective 4: ***an ADR must NEVER perform an Authority act by implication*** |
| **Superseding is explicit and forward-only** | ADR-T23 supersedes ADR-T17 → **T17 Status: Superseded — *remains valid history, never edited*** |
| **A superseding ADR needs an Architecture Review Gate** | a new ADR contradicting an upstream node (esp. **T11 anonymity** or **T1 one-txn**) is **rejected** |
| **The dependency graph prevents conflicting future ADRs** | the ADR-T log carries one, as a diagram, with the rejection rule stated |
| **Status annotations are permitted; decision text is not touched** | ADR-T21/T22 rows carry *"(status at issuance — since AUTHORIZED/REALIZED …; annotation per ES-004.3, **decision text unchanged**)"* |
| **Principles endure; ADRs are their APPLICATION** | *"each ADR below **applies** a Platform Governance Principle… Principles endure; these ADRs are their first application"* |
| **Rejected alternatives are recorded** | ADR-T19 records **Model A rejected** and why. *This is what makes Part 5 possible* |
| **Descriptive ≠ normative** | the Cross-Context Integration Contract is **DESCRIPTIVE**; ***elevating any clause to normative status is a SEPARATE decision the document does not take*** |

---

## 11. The named failure modes — a governance anti-pattern catalogue

**Each of these is in the record because it happened. Reading this list is the fastest way to avoid repeating it.**

| Anti-pattern | The correct shape |
|---|---|
| **Governance Verification Drift** — asserting full enforcement when coverage is partial | state the coverage arithmetic; **the completion statement was deliberately NOT recorded** |
| **A ticket becoming architecture** | record the factual options; **do not choose** |
| **Analysis ratifying itself** | analysis → recommendation → ratification → resolution |
| **A package pre-filling an outcome** | **blank templates; never *"is accepted"*** |
| **A "Record" written before any ruling exists** | *the body held the line; the name did not* — **name it a PACKAGE** |
| **A reviewer fixing its own finding** | record → dispose → apply |
| **Conflating two transitions in one vote** | split into two votes |
| **Inferring authority from category** | name the authority per transition |
| **Reverting to launder a sequence** | **audit + governance note; do NOT roll back** |
| **Dual truth** (registry explaining *why*) | registry stores WHAT + a `ref:` |
| **A rule generalized from one corpus** | candidate, with a promotion trigger |
| **Abstraction before a second consumer** | closed **for a stated reason**, with the trigger in the item |
| **A trigger recorded only in a report** | **write it INTO the backlog item** |
| **Green lint read as a green repository** | state the linter's actual scope |
| **A plausible-looking repair** | require existence + uniqueness evidence; **never invent a target** |
| **Silent architectural debt** | **recorded debt with a named owner and a class** |
| **"All broken links were fixed"** | *"all **deterministic** repairs completed; the remainder classified and transferred"* |
| **"No further governance act needed"** | *"no further governance **PREPARATION** is required"* |
| **Adopting by agreeing** | **RECOGNITION ≠ AGREEMENT ≠ ADOPTION** |
| **Extracting a fourth document on first recognition** | host the rule where its subject lives |
| **Inventing a severity scale / a destination / a location** | **raise a question; record PENDING; escalate** |

---

## Traceability

**Primary sources (all repository-internal, read at authoring):**

- `engineering/governance/Engineering_Execution_Protocol.md` — roles, separation of duties, lifecycle, plan minimum contents, STABLE change rule
- `engineering/governance/STANDARDS_INDEX.md` — Decision Authority & Verification Matrix (three dimensions), stopping rule, consolidation convention
- `engineering/governance/ES-001-Engineering-Constitution.md` — ES-001.1 parsimony, **ES-001.2**, registered constitutional sources
- `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` — **R-30, R-34, R-37, R-38, R-39, R-41, R-42, R-43..R-64** (all quoted rulings; register ownership and append-only rule)
- `docs/implementation/Implementation_Process_v1.1_Draft.md` §*Execution Rules — Engineering Process (EP)* — **EP-01 (six steps), EP-02, EP-03 (ERR question domains, stopping condition), EP-01-Light, the non-trivial decision table**, EP/ER namespace split
- `.claude/IMPLEMENTATION_PROTOCOL.md` — the **seventeen phases**, the operational-evidence register (incl. the phase-12 honest gap), success criteria
- `docs/implementation/PKS_ARB_Review_Discipline.md` — **Rules 1–18**, Parts I–V, **Amendment 1** (FW-1 relocation), **Amendment 2** (Axis A/B, promotion chain), **Amendment 3** (contract admission rule, the two aphorisms, emphasis construct), **Amendment 4** (RECOGNITION ≠ AGREEMENT ≠ ADOPTION, the admission chain), the admission ladder
- `docs/implementation/PKS_Knowledge_Contract_Review_Method.md` — the **eleven objectives**, **§Finding vocabulary** (three enumerations + Q-FW-1), the **Knowledge Governance Lifecycle**, the three stabilizers, honest-failure-direction, negative-claim discipline, *verification is itself a result*, the exemplar case
- `engineering/verification/reports/2026-08-01-slice-7b-acceptance-record.md` · `…-arb-session-decision-pack.md` · `…-arb-decision-dependency-verification.md` (DD-1/2/3) · `…-wp6-remediation-acceptance-record.md`
- `.claude/plans/WP-7-retention-alignment.md` · `.claude/plans/WP-1-evidenceset-v3.md` · `WP-2-apm-core.md` (closure/authorization headers)
- `.claude/MEMORY.md` — the four governance categories, preparation-vs-recording, the two process rulings, the eight-dimension framework's four closure outcomes

**New observations recorded by this Part (routed, not enacted):** §1.2 — *"Decision Authority (ARB)"* is written as one authority in every ES header but acts as two in R-46 and in the Planning-Governance category rule.

**Supersedes:** nothing. **Superseded by:** nothing. **Depends on:** Parts 1 and 2.
