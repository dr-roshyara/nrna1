# PKS — Architecture Validation Report (Stage 1A)

| | |
|---|---|
| **Kind** | **ARCHITECTURE VALIDATION** — reads the governing artifacts and reports what they determine. ***It decides nothing, mints no identifier, creates no component, amends no artifact, and authorizes no engineering.*** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Status** | **DELIVERED.** Stage 1A of the PA-directed sequence *(Architecture Validation → then Engineering)* |
| **Commission** | PA instruction, 2026-08-01: *"read AD-1, M6, IBC-1 … produce one Architecture Validation Report … confirm PKS identity · boundaries · ownership · capabilities · implementation constraints"* |
| **Governing artifacts read** | **AD-1** `PKS_Phase_IIB_Architecture_Definition.md` (347 lines, **read in full**) · **M6** `PKS_Phase_II_M6_Bounded_Context_Discovery.md` (§0, §1, §7.4, §7.5, §7.6, §8, §9 — the conclusion-bearing sections) · **IBC-1 review** `PKS_IBC-1_Architecture_Review.md` (§1, §2) · **IBC-1 recertification** `PKS_IBC1_Recertification_Review.md` (§1) · **M4** `…_M4_Identity_and_Lifecycle_Model.md` Part 1 · **ADR-PKS-001** §1–3 · C4 L1/L2 · `knowledge_tranfer/20260729_1812_pks_progress.md` §1–2 |
| **Placement** | ⭐ **DERIVED, not chosen** — `php scripts/doc-placement.php --scope=product-specific --domain=pks --maturity=qualified` → `docs/pks` (exit 0) |

---

## 0. Why this report exists

**A proposal for PKS development reached revision 5 while its author had not read the governing architecture.** Its citations of AD-1 came second-hand, through the C4 documents that reference it. The PA's question — *"have you read all strategic discovery documentation?"* — surfaced this, and the answer was **no: ~300 KB of a ~2.4 MB corpus, with AD-1, M6 and IBC-1 unopened.**

> ### **This report replaces inference with reading. Four of its findings CONTRADICT the proposal that preceded it.**

---

## 1. Answers to the five commissioned questions

### 1.1 PKS IDENTITY — *what is PKS?*

**The PA asked the broad form: repository? product? knowledge corpus? domain? bounded context? capability? method? platform?**

| Candidate framing | Verdict from the governing record |
|---|---|
| **Domain** | ✅ **YES — this is the governed reading.** M6 is a *Bounded Context Discovery **within** PKS*; it discovered contexts **inside** a domain boundary and disposed one context **outside** it |
| **Bounded context** | ⛔ **NO.** *PKS **contains** bounded contexts.* M6: **CBC-1 ACCEPTED · CBC-2 ACCEPTED · CBC-4 ACCEPTED (adjacent, outside) · CBC-3 CANDIDATE SEAM**, plus an unpartitioned core |
| **Software** | ⛔ **NO.** *"The PKS is software — ❌ No: it is **knowledge specifications** (YAML + Markdown)"* |
| **Knowledge corpus** | ⚠️ **partly** — but *"corpus"* is precisely what **OQ-PKS-7** leaves open (below) |
| **Capability · method · platform** | ⛔ **Not governed classifications.** None appears as a disposition anywhere in M6, AD-1 or the Authority Disposition. *Using them asserts a classification the record does not hold* |

> ### ⛔ **AND THE CENTRE OF THE QUESTION IS FORMALLY OPEN.**
>
> **OQ-PKS-7 — *"Do the three roots (`docs/`, `architecture/`, `engineering/` bindings) form ONE CORPUS OR THREE?"* — status: SURFACED to ARB; placement NOT DECIDED.**
>
> **M6's own impact statement:** *"If one corpus: `binds` is an intra-domain relationship… If three: `binds` is an inter-domain contract with the Engineering Governance domain, and **part of the demoted seam's content is OUTSIDE PKS**."*

**Consequence, stated plainly:** ***"What is PKS?" cannot be answered definitively today, and the reason is recorded rather than accidental.*** The best-supported answer is: **PKS is a domain containing two accepted bounded contexts, one candidate seam, and one unpartitioned region — whose outer extent depends on an ARB question that has not been decided.**

⚠️ **AD-1 inherits this and says so:** AR-1 is drawn inside the PKS boundary *"under OQ-PKS-7's one-corpus reading, which remains open and ARB-owned… **This placement asserts no answer to OQ-PKS-7.**"*

### 1.2 PKS BOUNDARIES

**Strategic (M6) → Architectural (AD-1), a bijective mapping:**

| M6 strategic element | AD-1 architectural element | Standing |
|---|---|---|
| **CBC-1 Knowledge Assessment** | **AC-1** — logical component, boundary **SB-1** | accepted BC · **Medium-High** |
| **CBC-2 Knowledge Projection** | **AC-2** — logical component, boundary **SB-2** | accepted BC · **Medium-High** |
| **CBC-4 Work Management** | **XD-1** — external domain, integration boundary **IB-1** | accepted **ADJACENT — outside PKS** · Medium |
| **CBC-3 Normative Governance** | **AR-1** — architecturally **UNDEFINED region** | **candidate seam** · Low-Medium |
| Expressed-knowledge core *(Decision · Term · Model element · Contract)* | **AR-2** — architecturally **UNDEFINED region** | **unpartitioned by choice** |

> ### ⛔ **THE BINDING CONSTRAINT: "No component may be defined here" — AD-1 §6.2, of BOTH AR-1 and AR-2.**
>
> **Because:** defining a component over the seam *"would assert an encapsulation the governance declined to grant"*; over the core it *"would be a boundary by preference — exactly the error the discovery refused."* **AD-1 names the failure mode: *the elegant-partition error at one remove.***

**Only two components exist, and AD-1 states why (§6.1):** *a logical component requires a **governed boundary** to enclose. Exactly two elements have one.*

### 1.3 PKS OWNERSHIP

| Question | Governed answer |
|---|---|
| Who owns criteria/norms? | **NOT AC-1. AP-7: *criteria are used here, owned elsewhere*.** **DR-4: read-only — no element inside AC-1 may author, amend or version a criterion** |
| Who owns authority? | **Nobody inside PKS. AP-1: *knowledge feeds authority; it never holds authority*. A verdict is an OUTPUT, never a decision** |
| Who owns identity? | ⭐ **No one centrally. AP-4: identity is *per-kind and register-scoped*; collisions are prevented BY REGISTER DISCIPLINE, NOT BY A CENTRAL AUTHORITY** |
| Who owns whole-system conformance assessment? | ⛔ **NOBODY.** *Absent* (T-17); **no role owns it** (OQ-PKS-3, unresolved). AC-1's boundary encloses *"a specification for a capability no mechanism performs"* |
| Who owns Risk · Question · Exception record? | ⛔ **Deliberately UNASSIGNED** (M6 §8). *"The architecture allocates their handling **nowhere**, and may not allocate it by default"* |
| Who owns the XD-1 translation obligation? | ⛔ **UNASSIGNED (U-2)** — and *"the architecture may not assign it"* |

### 1.4 PKS CAPABILITIES

**⭐ The finding that most directly affects the development proposal.**

**M6 §7.5 evaluated *"Identity & register(ns) discipline"* AS A CANDIDATE BOUNDARY — AND DEMOTED IT.**

| | |
|---|---|
| **What was present** | *"Strong L4; ownership vacuum (L1-14)"* |
| **What was missing** | *"**No responsibility class**; and the candidate's own name is a **three-sense collision** (F-BCP-4) — naming a boundary with the corpus's contested word would deepen the drift"* |
| ⭐ **Reopening trigger** | ***"A UL ruling on 'register', plus a named owner for identity discipline"*** |
| **Trigger met?** | ⛔ **NO. Neither limb has occurred** |

**And M6 demoted *"Consistency / contradiction management"* on the same page with a line that governs the whole capability question:**

> ### ***"A capability gap is not a boundary."***

**Consequence for a proposed "Identifier Capability" that would own *Identifier · Registry · Policy · Validation · Events*:**

| Test | Result |
|---|---|
| Is it a governed bounded context? | ⛔ **No — demoted candidate, trigger unmet** |
| Would it be a component over AR-2? | ⚠️ **Very likely yes.** Identity is a property *of the kinds in AR-2* (Decision · Term · Model element · Contract) per M4 §1.1 |
| Is that permitted? | ⛔ **AD-1 §6.2 says no** — and **AD-1 Q-2** *("may a logical component ever be defined over a candidate seam or an unpartitioned region?")* is **OPEN and routed to AUTHORITY, not to research** |

> ### **So a BOUNDED CONTEXT or ARCHITECTURAL COMPONENT named "Identifier Capability" is NOT JUSTIFIED by the current governing architecture.** *It is Q-2, and Q-2 is the Authority's.*

> ### ⭐ **What this does NOT close (PA refinement, 2026-08-02 — and it corrects a conflation in this report's first issue):**
>
> **An ENGINEERING CAPABILITY called *Identifier Validation* remains entirely open.** *That is a different concept from a strategic bounded context, and **strategic architecture and engineering capabilities must not be conflated.***
>
> | ⛔ Not justified | ✅ Not foreclosed |
> |---|---|
> | **Strategic bounded context / architectural component** *"Identifier Capability"* — would encapsulate AR-2 content, needs Q-2 + the M6 §7.5 trigger | **Engineering capability** *"Identifier Validation"* — an executable function enforcing existing register discipline, asserting no boundary |
>
> ***The first draws a line on the context map. The second does not. Only the first requires Authority.***

### 1.5 PKS IMPLEMENTATION CONSTRAINTS

**Binding on any engineering that touches this domain:**

| # | Constraint | Source |
|---|---|---|
| **1** | ⛔ **No global identifier scheme. Collisions are prevented by REGISTER DISCIPLINE, not by a central authority** | **AP-4** |
| **2** | ⛔ **Nothing may depend on AC-2.** Projection is a pure sink — no component may consume a projection **as authority or as evidence** | **DR-1** |
| **3** | ⛔ **No element may substitute a projection for its source.** Source governs; the projection is corrected | **DR-2** |
| **4** | **AC-1 may depend ONLY on AR-1 (criteria, read-only), AR-2 (knowledge assessed), and an external issuance trigger** | **DR-3** |
| **5** | ⛔ **Criteria dependencies are READ-ONLY** | **DR-4** |
| **6** | ⛔ **No PKS element may depend on XD-1** | **DR-5** |
| **7** | **Dependencies on AR-1/AR-2 are *dependency contracts*, never component dependencies** — they may not assume encapsulation, a stable interface, or internal structure | **DR-6** |
| **8** | ⭐ **Only the CLOSED VERDICT VOCABULARY crosses SB-1 outbound: PASS · PASS AFTER CORRECTION · WARN · FAIL · INCONCLUSIVE · EMERGENT · CERTIFIED.** Nothing richer crosses | **AP-8 · DR-8** |
| **9** | **AC-1 cannot self-issue.** ⚠️ **But WHERE the trigger lives is NOT DETERMINED — Q-7, open, Authority-owned** | **AC-1 constraint 1 · Q-7** |
| **10** | **The path toward projection must be made STRUCTURALLY IMPOSSIBLE to reverse, not merely discouraged** | **AP-9** |
| **11** | ⚠️ **Synchronicity, coupling, delivery and ordering are NOT DERIVABLE** and were deliberately not supplied | **Q-1** |
| **12** | **Every element rests on a model graded at most Medium-High, one corpus / one lineage. *The architecture cannot be more certain than the model it derives from*** | **AD-1 §13.8** |

---

## 2. ⛔ Four findings that CONTRADICT the preceding development proposal

**Recorded plainly, because the proposal is advisory and was built partly on second-hand citation.**

| # | The proposal said | The governing record says |
|---|---|---|
| **F-1** | Build an **Identifier Registry** — *"one YAML: series · id · subject · status · minted-where"* | ⛔ **AP-4 forbids it: *no global identifier scheme may be introduced; collisions are prevented by register discipline, NOT BY A CENTRAL AUTHORITY*.** ✅ **A VALIDATOR OVER THE EXISTING REGISTERS is lawful — and is exactly what AP-4 prescribes.** *The artifact must not become a new source of truth* |
| **F-2** | The checker returns *"free / collides / unregistered-series"* | ⛔ **AP-8/DR-8: only the closed verdict vocabulary crosses — PASS · PASS AFTER CORRECTION · WARN · FAIL · INCONCLUSIVE · EMERGENT · CERTIFIED** |
| **F-3** | *"AP-5 — AC-1 cannot self-issue; the trigger originates outside → **a PreToolUse hook is the architecturally correct shape**"* | ⛔ **Two errors.** *(a)* **Wrong identifier** — AP-5 is *semantic ≠ representational identity*; non-self-issuance is AC-1 constraint 1. *(b)* ⭐ **The claim is the exact interpretation AD-1's C-02 amendment struck out**: the earlier text said G-15 *"places the issuing trigger outside the boundary"* and AD-1 records — ***"That is an interpretation presented as a derivation."*** **Where the trigger lives is Q-7, OPEN** |
| **F-4** | A cached identifier index the checker reads | ⚠️ **A regenerated index is a projection. DR-1 forbids consuming a projection as evidence for a decision.** **The checker must read the REGISTERS themselves** |

**⭐ What survives, and it survives stronger:** **PMR-10 is GOVERNED**, its remediation was **explicitly deferred by M4** *("no remediation of the named collision liabilities — those are II.B/backlog")*, and **M4's carried liabilities include the exact defects observed** — *OQ- overload* and *R-nn cross-kind ambiguity*. **A register-discipline validator is therefore both lawful under AP-4 and scheduled by M4.**

---

## 3. ⛔ The finding that governs whether Stage 1B may begin at all

> ### **IBC-1 — the Implementation Boundary Contract — DOES NOT EXIST AS A REPOSITORY ARTIFACT.**

| Evidence | |
|---|---|
| The architecture review states it | *"IBC-1 **does not exist as a repository artifact** (verified). It has no authority header, no traceability footer, no epistemic classes, and no Disposition History"*; its object was *"Sections 1–13 **as presented inline in the review request**"* |
| The re-certification confirms it | *"**The re-certification cannot be completed as specified**… **THE OBJECT OF THE ORIGINAL REVIEW IS NOT IN THE REPOSITORY**"* |
| Its review verdict | **REVISE — 3 Critical · 10 Major · 5 Minor** |
| **Yet ADR-PKS-001 relies on it** | *"**IBC-1 is the semantic firewall**… already the implementation-agnostic handover artifact… The strategic baseline **ends at IBC-1**"* |

**The three Criticals matter to implementation directly:**

- **IBC-C1** — it declared AD-1 a *"certified baseline"* when AD-1 was not certified. *(⚠️ Partly overtaken: **AD-1 was PROMOTED 2026-07-30**. Whether that discharges IBC-C1 is what the re-certification could not determine.)*
- **IBC-C2** — ⭐ ***AD-1's Q-1 — that coupling and delivery are NOT derivable — appears nowhere in the contract's open questions or forbidden assumptions, "because an implementation team must choose a coupling mechanism and will do so on day one."***
- **IBC-C3** — its conflict rule was **inverted**: until editorial applications are made, **the decision record governs on conflict, not the artifact.**

> ### **Consequence: the handover contract that ADR-PKS-001 designates as the strategic/implementation firewall is ABSENT, and its last recorded verdict was REVISE.**
>
> ***Engineering can still proceed — but it proceeds without the artifact designed to tell it what it inherits, what it must respect, and what it must not assume.*** **That is a governance fact the Authority should hold before authorizing Stage 1B, not after.**

---

## 4. ⚠️ One defect observed in a PROMOTED artifact — recorded, not repaired

**AD-1 §9.1 retains wording that AD-1's own C-02 amendment struck out elsewhere.**

| Location | Text |
|---|---|
| **§9.1** *(Communication Model, "Initiation of judgment")* | *"**AC-1 cannot initiate its own issuance** — **the trigger originates in AR-1** (AP-1)"* |
| **§5 AP-1 + §7 (as amended by C-02)** | *"**WHERE it is performed is not determined by the governed model**… the earlier form… **is an interpretation presented as a derivation**"* |
| **§12 Q-7** | *"**Where is the issuance trigger located?** … the architecture cannot say whether issuance is triggered inside AR-1, at a boundary, or outside PKS altogether"* |

**Classification:** *superseded wording surviving in one section of a promoted governing artifact* — the C-02 amendment corrected §5 and §7 and did not reach §9.1. **The same class as PD-F1, which AD-1 §11A already remedies for a different set of items.**

⛔ **Reported, not repaired** — AD-1 is promoted and under configuration control; editing it is an Authority act. **Routed.**

---

## 5. What this report concludes — and what it refuses to conclude

| ✅ Concluded on evidence | ⛔ Refused |
|---|---|
| **PKS is a DOMAIN containing two accepted BCs, one candidate seam, one unpartitioned region; CBC-4 is adjacent/outside** | **Whether PKS is one corpus or three — OQ-PKS-7, ARB** |
| **No component may be defined over AR-1 or AR-2** | **Whether one ever may — AD-1 Q-2, Authority** |
| **A BOUNDED CONTEXT / COMPONENT named "Identifier Capability" is a DEMOTED candidate whose reopening trigger is unmet** *(an ENGINEERING capability named "Identifier Validation" is a different concept and is NOT foreclosed)* | **Whether to reopen the BOUNDARY — needs a UL ruling on "register" + a named owner** |
| **A register-discipline VALIDATOR is lawful under AP-4 and scheduled by M4** | **Whether to authorize building it — Authority** |
| **Twelve implementation constraints (§1.5) bind any engineering here** | **Coupling/synchronicity — Q-1, not derivable** |
| **IBC-1 does not exist; its verdict was REVISE** | **Whether that blocks Stage 1B — Authority** |

> ### **The strongest claim this report supports: the architecture is sufficiently defined to constrain implementation, and insufficiently defined to be extended by it.**
>
> **Twelve constraints are binding today. Five questions (Q-1..Q-7 minus resolved) and at least six OQ-PKS items remain Authority-owned. Building within the constraints is lawful; drawing new boundaries is not.**

---

## 6. Recommended next acts — none of them taken here

| # | Act | Owner |
|---|---|---|
| **1** | **Note the four contradictions (§2)** and re-scope Stage 1 as a **register-discipline validator**, not a registry | *advisory — no authority needed* |
| **1b** | ⭐ **INSERT AN ENGINEERING TRANSLATION STEP before any coding** *(PA, 2026-08-02)*. AD-1 is **strategic**; it does not answer *which classes implement validation · where the validator executes · how evidence is collected · which context owns execution · which layer invokes it*. **Those must not be answered directly from Strategic Discovery.** ⚠️ **PARSIMONY NOTE (ES-001.1): the label `Engineering Translation` passes the PMR-10 collision check (free), but the SCOPE is already owned — ADR-PKS-001: *"Phase II.D (Implementation) is where implementation-specific guidance belongs."* **Record it as Phase II.D's FIRST SLICE, not as a new sibling phase** | *design work — no authority needed to produce; Authority still gates the coding it precedes* |
| **2** | ⭐ **Decide whether IBC-1's absence blocks Stage 1B**, given ADR-PKS-001 designates it the firewall | **Authority** |
| **3** | **Dispose the AD-1 §9.1 superseded-wording defect** *(annotation, per the R-53 pattern — not an edit to decision text)* | **Authority** |
| **4** | **Route the R-65..R-71 identifier collision into 7C's decision C-4** — it is a live instance of the PMR-10 defect class | **ARB** *(already queued)* |
| **5** | **Leave OQ-PKS-7, Q-2 and the M6 §7.5 reopening trigger open** — none is decidable from present evidence | **ARB** |

---

*Traceability: PA commission 2026-08-01 (Stage 1A Architecture Validation) · **AD-1 read in full (347 lines)**; **M6 conclusion-bearing sections read** (§0 · §1 · §7.4–7.6 · §8 · §9); **IBC-1 review and re-certification §1–2 read**; M4 Part 1; ADR-PKS-001 §1–3; C4 L1/L2; `knowledge_tranfer/20260729_1812_pks_progress.md` §1–2 · **placement DERIVED via `scripts/doc-placement.php` (exit 0 → `docs/pks`)** · four contradictions of the preceding proposal recorded (§2) · one defect in a promoted artifact reported and NOT repaired (§4) · **nothing decided, nothing minted, nothing amended, no engineering authorized.***

> **Stage 1A complete. The architecture has been read rather than inferred. Stage 1B remains unauthorized, and §3 records a governance fact the Authority should weigh before it is.**
