# EP-01 Plan — PKS Identifier Validation Capability

| | |
|---|---|
| **Kind** | **EP-01 IMPLEMENTATION PLAN.** Phase 0 (Tactical Capability Discovery) is **executed inside this plan**; Phases 1–2 are **proposed and unauthorized** |
| **Status** | ▶️ **AUTHORIZED AND IN EXECUTION — RED COMPLETE.** *Authorizing act: the PA implementation commission of 2026-08-02 ("Execute CAP-001… Use TDD… Stop after CAP-001"), which supplies role, scope, deliverables, success criteria and a stop condition. **Recorded here as the authorizing act, per the standing offer to do so rather than infer it.*** |
| **Capability name** | ⚠️ **RECONCILED: "Engineering Knowledge Validation – CAP-001: Identifier Integrity"** *(was "Identifier Validation" — the commission names capabilities by business responsibility, not mechanism)* |
| **RED boundary** | ✅ **19 tests · 17 errors · 2 failures · 0 passing**, every one for the single expected reason: **the production classes do not exist.** Architecture fitness suite re-run after the `composer.json` autoload change: **149/149 OK** |
| **Authority sought** | **EP-01 plan approval — Decision Authority** *(per R-46's precedent: EP-01 plan approval is the DA's act, not the ARB's)*. Execution authorization is a **separate, later act** |
| **Governing decisions** | **PMR-10** (GOVERNED — *an identifier must be checked for collision before it is minted*) · **AD-1** (PROMOTED governing logical architecture) · **M4** §1.1–1.2 (identity modes; register(ns) as namespace unit) · **M6** §7.5 (the demoted candidate + its reopening trigger) |
| **Inputs** | `docs/pks/2026-08-02-pks-architecture-validation-report.md` (Stage 1A) · `docs/pks/2026-08-02-engineering-translation-register-discipline-validator.md` (Stage 1A.5) |
| **Placement** | **ES-004.2** — `docs/plans/YYYYMMDD-HHMM-<what>-plan.md` |
| **Label check** | **PMR-10 performed** on `Identifier Validation Capability` · `Tactical Capability Discovery` — free, except `Identifier Validation` which appears only in this plan's own Stage 1A input |

**Epistemic classes used throughout: Observed · Derived · Hypothesis · Recommendation · Open Question.** *Not mixed.*

---

# PHASE 0 — TACTICAL CAPABILITY DISCOVERY *(executed here, per the hybrid direction)*

> **Objective: derive the engineering capability from the strategic model rather than assume it.** ⚠️ **The outcome was NOT presumed. §0.5 records what changed as a result.**

## 0.1 Strategic constraints affecting implementation *(Step 1)*

| Source | Constraint | Engineering implication | Class |
|---|---|---|---|
| **AP-4** | Identity is per-kind, register(ns)-scoped; **collisions prevented by register discipline, not a central authority** | ⛔ **may not introduce a new source of truth for identifiers** | Derived |
| **AP-1** | Knowledge feeds authority; never holds it | **emits a verdict; never decides** | Derived |
| **AP-2 · DR-1** | Projections regenerable, non-authoritative; **nothing may consume a projection as evidence** | ⛔ **no cached index may be consulted for a decision** | Derived |
| **DR-2** | Source governs on conflict | the register wins | Derived |
| **AP-7 · DR-4** | Criteria used here, owned elsewhere; **read-only** | ⛔ **may not define/amend/version a numbering scheme** | Derived |
| **AP-8 · DR-8** | Only the closed verdict vocabulary crosses SB-1 | verdicts from a fixed set only | Derived |
| **AC-1 constraint 1** | Cannot self-issue | ⛔ **no daemon**; externally triggered | Derived |
| **AD-1 Q-7** | **Where the trigger lives is NOT determined** | ⚠️ trigger choice is engineering, **not architecture** | Open Question |
| **AD-1 §6.2 · Q-2** | **No component may be defined over AR-1/AR-2** | ⛔ **may not create a capability that encapsulates AR-2 content** | Derived + Open Question |
| **AD-1 Q-1** | Coupling/timing/delivery **not derivable** | ⚠️ any coupling choice is an engineering decision, recorded as such | Open Question |
| **M4** | *"No new numbering scheme, no renumbering, no remediation of the named liabilities"* | ⛔ **prevents; never repairs** | Observed |
| **M6 §7.5** | *"Identity & register(ns) discipline"* **demoted**; trigger = *UL ruling on "register" + a named owner* | ⛔ **may not be built as a boundary**; ⚠️ **and must avoid naming itself with the contested word** | Observed |
| **M6 §7.5** | *"A capability gap is not a boundary"* | a gap justifies a function, not a context | Observed |
| **AD-1 §13.8** | Model graded at most Medium-High, one corpus | ⛔ **no promotion to KnowledgeOS from this alone** | Derived |
| **`pks_progress` §1.2** | *"The PKS is software — ❌ No"* | ⛔ **the artifact is NOT a member of PKS** | Observed |

## 0.2 Clusters by engineering responsibility *(Step 2 — not by document)*

**A. Identity discipline** *(AP-4 · M4 · M6 §7.5 · PMR-10)* — **B. Projection discipline** *(AP-2 · AP-9 · DR-1 · DR-2)* — **C. Criteria custody** *(AP-7 · DR-4)* — **D. Interchange discipline** *(AP-8 · DR-8)* — **E. Initiation discipline** *(non-self-issuance · Q-7)* — **F. Boundary preservation** *(§6.2 · Q-2 · M6 §7.5)* — **G. Evidence & traceability** *(Phase III charter · PKS observation shape)*

*Clusters D, E, F, G are **cross-cutting**: they constrain every candidate rather than distinguishing between them. **Only A and B can generate a capability.***

## 0.3 Candidate engineering capabilities *(Step 3 — responsibilities only)*

| # | Responsibility | Cluster |
|---|---|---|
| **C-a** | **Validate an identifier against its register(ns) before minting** | A |
| **C-b** | **Verify that every projection regenerates from its sources** | B |
| **C-c** | Resolve a knowledge reference (placement / link) | B |
| **C-d** | Check that every normative statement names a governing artifact | G |
| **C-e** | Detect cross-artifact contradiction | — |
| **C-f** | Verify that emitted judgments use only the closed verdict vocabulary | D |

## 0.4 DDD evaluation *(Step 4 — reject anything that REDEFINES rather than REALIZES)*

| | New BC? | New ownership? | New authority? | New lifecycle? | New UL? | Verdict |
|---|---|---|---|---|---|---|
| **C-a** | No | **No — registers already own identifiers** | No — emits a verdict | No | ⚠️ **must avoid the contested word** | ✅ **ADMISSIBLE** |
| **C-b** | No | No | No | No | No | ✅ **ADMISSIBLE** |
| **C-c** | No | No | No | No | No | ⛔ **REJECT — ALREADY EXISTS** (`link-check.php`, `doc-placement.php`). *Reuse before create (R-36)* |
| **C-d** | No | No | No | No | No | ⚠️ **DEFER — criteria do not exist**; would emit INCONCLUSIVE universally |
| **C-e** | No | ⛔ **YES** — *"no mechanism, no owner (OQ-PKS-3), no cadence."* Building it **assigns an ownership the governance left vacant** | Possibly | No | No | ⛔ **REJECT — redefines** |
| **C-f** | No | No | No | No | No | ⚠️ **DEFER — no escaped-defect evidence** |

## 0.5 Selection *(Step 5)* — and what the discovery CHANGED

**Two candidates survived. They were compared on evidence, not preference.**

| | **C-a Identifier validation** | **C-b Projection regenerability** |
|---|---|---|
| **Governed rule requiring it** | ⭐ **PMR-10 — GOVERNED** | AP-2 *(a property, not an obligation to check)* |
| **Escaped-defect evidence** | ⭐ **4 instances** — R-65..R-71 · C-1..C-4 · plus M4's named *OQ- overload* and *R-nn cross-kind ambiguity* | indirect *(guide steps 6/31; 121-vs-9 links)* |
| **Already scheduled?** | ⭐ **YES — M4: *"remediation… those are II.B/backlog"*** | no |
| **Are criteria available?** | ⚠️ **partially** — M4 supplies modes + namespace unit; **no canonical register list** | ⛔ **worse — no artifact records each projection's sources** |
| **Architectural risk** | **Lowest** — asserts no ownership | low |
| **AD-1 support** | AP-4 prescribes register discipline | ⭐ *"its correctness is verifiable structurally"* |

> ### ✅ **SELECTED: C-a — Identifier validation.**
> **Why not C-b:** it is the more elegant candidate and AD-1 even invites it — but **its criteria are less available than C-a's**, and it has **no governed rule obliging the check**. *Choosing it would be preference; choosing C-a is evidence.* **C-b is recorded as the next candidate.**

### ⭐ What Phase 0 changed — the discovery was not ceremonial

| # | Change | Reason |
|---|---|---|
| **1** | ⭐ **RENAMED: "Register-Discipline Validator" → "Identifier Validation"** | **M6 §7.5 warns that *"naming a boundary with the corpus's contested word would deepen the drift"* — `register` is a **three-sense collision** (F-BCP-4). *The prior name put the contested word in the title.* **Where the term is unavoidable it is written `register(ns)`**, per M6's own disambiguation |
| **2** | **C-c formally REJECTED as already-existing** | it had been implicitly assumed absent |
| **3** | **C-e formally REJECTED as ownership-creating** | *the most attractive-looking gap in the corpus is the one that must not be filled by engineering* |
| **4** | **C-b recorded as the successor** | so capability 2 is chosen, not defaulted |

*(Had the discovery been skipped, items 1 and 3 would have gone unrecorded. **Applying the Methodological Fitness Rule to Phase 0 itself: it rejected two candidates and renamed the survivor — it discriminated, so it was not ceremonial.**)*

## 0.6 Tactical boundary *(Step 6 — an engineering responsibility, not a class)*

| | |
|---|---|
| **Responsibility** | Given a proposed identifier and its register(ns), **report whether minting it would collide** |
| **Inputs** | the proposed identifier · its declared register(ns) · **the governed registers themselves** *(never a cached index — DR-1)* |
| **Outputs** | **exactly one verdict** from the closed set (§0.7) + the evidence supporting it |
| **Dependencies** | M4's identity model **read-only** (AP-7/DR-4) · the register artifacts **read-only** |
| **Policies it executes** | **PMR-10** *(must be checked before minting)* · **AP-4** *(register discipline, not central authority)* |
| **Owns** | **execution only.** ⛔ **Owns no identifier, no register, no criterion, no numbering scheme** |
| **Evidence produced** | did the check fire? · did it change what engineering did? · which verdict, on what input |
| **Failure modes** | *(i)* series is not a governed register → **INCONCLUSIVE** *(correct, and evidence the register ruling is needed)* · *(ii)* register unreadable → **INCONCLUSIVE**, never PASS · *(iii)* ⛔ **fail-closed: absence of evidence is never PASS** |

## 0.7 The verdict set — mapped to AP-8, not invented

| Situation | Verdict |
|---|---|
| free in its register(ns) | **PASS** |
| already minted there | **FAIL** |
| cited but unminted *(R-65..R-71)* | **WARN** |
| series not a governed register *(bare-ADR liability)* | **INCONCLUSIVE** |
| cross-kind reuse *(R-nn Risk/Ruling; C-1..C-4)* | **WARN** |

⛔ **`PASS AFTER CORRECTION` · `EMERGENT` · `CERTIFIED` are never emitted** — they belong to review and certification acts.

## 0.8 Implementation derivation *(Step 7 — engineering design, no code)*

| Aspect | Decision | Basis |
|---|---|---|
| **Execution model** | **one CLI script**, behaviour-only | 4-for-4 house precedent |
| **Repository location** | **`scripts/`** | ⛔ **NOT `app/Contexts/`** — PKS is not software; AD-1 is deployment-neutral and cannot own a file |
| **Configuration** | the governed registers + M4's modes | AP-7/DR-4 read-only |
| **Invocation** | **author-invoked before minting**; merge-gate entry once stable. ⚠️ **A PreToolUse hook is OPTIONAL and deferred** | non-self-issuance ✅; **Q-7 open → not architecture** |
| **Runtime** | PHP 8.2, no framework, no DI | house pattern |
| **Verification** | RED first · `composer merge-gate` green | ADR-T7, EP-01 |

---

# PHASE 1 — IMPLEMENTATION *(proposed · ⛔ UNAUTHORIZED)*

**Objective.** Make PMR-10 executable for identifier validation, without introducing a source of truth.

**Scope (IN).** `scripts/identifier-check.php` — read governed registers · answer *"is `<id>` free in `<register(ns)>`?"* · emit one §0.7 verdict · report the R-65..R-71 and C-1..C-4 hazards · print a reproduction command. Tests. A developer guide.

⛔ **Scope (OUT), each with its blocking rule.** A central registry file **(AP-4)** · renumbering or repair **(M4)** · a new numbering scheme **(DR-4)** · a bounded context / component / capability boundary **(AD-1 §6.2 · Q-2 · M6 §7.5)** · a cached index consulted for decisions **(DR-1)** · a PreToolUse hook **(deferred — Q-7)** · anything under `app/` **(PKS is not software)**.

**Files affected.** `scripts/identifier-check.php` *(new)* · `tests/…` *(new)* · a developer guide *(new)* · `composer.json` *(one merge-gate line — only after Phase 2)*.

**Verification strategy.** **RED first** — failing tests for each §0.7 verdict, confirmed failing for the expected reasons, **reported at the RED boundary before any production code**. Then GREEN (minimum satisfying RED). Then `composer merge-gate`.

**Evidence expected.** A PKS observation in the `Observation → Evidence (with method) → Classification → Recommendation → Reproduction command` shape.

---

# PHASE 2 — REVIEW & EVIDENCE *(proposed)*

Did it fire? · Did it change engineering behaviour? · **Which verdict did it emit on R-65..R-71 before decision C-4 was taken?** · Is C-b (projection regenerability) confirmed as capability 2?

⛔ **No promotion to KnowledgeOS from this alone — one corpus is one observation.**

---

# Risks

| # | Risk | Mitigation |
|---|---|---|
| **1** | ⚠️ **No canonical list of governed registers exists** | **Disclosed, not solved.** Emits **INCONCLUSIVE** — correct behaviour, and evidence that the register ruling (M6 §7.5's own trigger) is needed |
| **2** | The validator drifts into a registry | **Scope OUT is blocking-rule-cited; Phase 2 re-checks it** |
| **3** | Someone reads this as creating a capability boundary | ⛔ **§0.4/§0.6 state it owns execution only.** *An engineering capability, not a strategic boundary* |
| **4** | **IBC-1 does not exist** — the designated handover firewall is absent | ⚠️ **Open — Authority.** *This plan proceeds under AD-1 directly, which is the more conservative reading (IBC-C3: the decision record governs on conflict, not the artifact)* |

# Open questions *(none resolved here)*

**Q-7** trigger location · **Q-2** components over undefined regions · **OQ-PKS-7** one corpus or three · **the governed-register list** · **IBC-1's absence** · **R-65..R-71 → decision C-4**.

# Next actions

| # | Action | Owner |
|---|---|---|
| **1** | ⏳ **Approve or reject THIS PLAN (EP-01)** | **Decision Authority** |
| **2** | Then a **separate** execution authorization | **ARB** |
| **3** | Then **RED**, reported at the boundary | engineering |

---

*Traceability: hybrid direction (PA, 2026-08-02 — *"EP-01 plan with Tactical Capability Discovery as Phase 0; don't create a separate document"*) · Phase 0 executes Steps 1–7 of the Tactical Capability Discovery method · derives solely from AD-1, M4, M6, PMR-10 and the Stage 1A/1A.5 inputs · **no strategic boundary changed · no new bounded context · no aggregate, entity, repository, service, API, schema or event invented · no governance created** · every statement carries an epistemic class · **PMR-10 collision check performed on all new labels.***

> **⛔ This plan is AWAITING APPROVAL. No code has been written and no engineering has begun.**
