# Layer Verification Rule (Engineering Platform — methodology module)

**Class:** Engineering Platform methodology module · **Owner:** Decision Authority
**Status:** 🟡 **PROPOSED — NOT ADOPTED.** Authority requires an explicit Decision Authority ruling (**R-34**: authority is created only by explicit issuance; nothing becomes a ruling by inference from praise or suggestion). **Until issued, this module is non-binding and must be cited as a *recommended heuristic*, never as authority for what is permitted.**
**Nature:** a **methodology module about the governance of change** — it classifies *which abstraction a proposed change actually modifies*, and therefore *whose authority is engaged*. It is **not** architecture, **not** a tactical-DDD rule, and it decides no design question.
**Relationship to existing modules:** complements `DDD_Tactical_Governance_Principles.md` (tactical design) without overlapping it. That module governs *what to build*; this one governs *who may change it, and at which level*.
**Provenance:** derived during EPIC-004 WP-7 (finding **G-1**), refined across four ARB iterations. Promotion to a reusable artifact requested by the ARB, 2026-08-01.

---

## 1. The four-level model

| Level | Role | Authority | May engineering change it? |
|---|---|---|---|
| **1 · Business Policy** | **decides WHAT** | Business / Decision Authority | ❌ never |
| **2 · Architectural Invariant** | **protects WHAT** | ARB | ❌ never |
| **3 · Mechanism** | **decides HOW** | **engineering, within the invariant** | ✅ **the only substitutable level** |
| **4 · Implementation** | **realizes HOW** | engineering | ✅ yes |

**What the model is for:** it converts *"is this substitutable?"* from a judgement into a **lookup**. A level-3 collision dissolves without touching the model; **a level-2 collision cannot be engineered around — it returns to the ARB.** Knowing which situation you are in **before** searching for a fix is the entire value.

**The failure mode it addresses:** plans routinely record an **invariant and its mechanism in the same sentence**, so the two are read as one binding thing. Split them before treating either as binding.

## 2. The rule

> ### **Can this layer change WITHOUT changing the layer above it?**
> ### **YES → it belongs at this layer. NO → you are modifying the wrong abstraction.**

### 2.1 The dual — what a failure *means*

> **If changing this layer forces a change above it, you have discovered an ARCHITECTURAL dependency, not an implementation dependency.**

**Why the dual matters:** the rule alone tells a reviewer *that* something is wrong; the dual tells them **why**, and therefore **where to go next**. A failure is not a defect to be worked around — it is **evidence that the change is larger than it was presented as**, and it names the authority that must be engaged. **A failing rule escalates; it does not block.**

### 2.2 Proposal checklist — applied *before* implementation

The rule evaluates changes; this applies it to **proposals**, which is where it is cheapest to act on:

1. **Which layer is intended to change?**
2. **Which higher layer would also change?**
3. **If any higher layer changes → ESCALATE before implementation.**

**Apply it to any change that feels like "just a technical choice"** — that is precisely how both historical defects (§3) presented themselves.

## 3. Evidence base — and its limits, stated

The platform's own **Methodological Fitness Rule** sets the standard a criterion must meet: *"a criterion that never rejects or modifies a candidate over the lifetime of the methodology is presumed ceremonial until evidence shows otherwise."* **The rule is offered for adoption because it meets that standard — it both accepts and rejects.**

| Case | Layer intended | Layer above changes? | Rule's verdict | Correct? |
|---|---|---|---|---|
| **G-1** — import the provider's port → **consumer-side port** | Mechanism | ❌ *"MAD has exactly one canonical home" untouched* | ✅ **ACCEPT** — a legitimate mechanism substitution | ✅ *(prospective)* |
| **AP-2** — a MAD key added to a retention config | presented as Mechanism | ✅ **destroys** the invariant | ❌ **REJECT** — an **invariant breach dressed as a mechanism choice** | ✅ *(retrospective)* |
| **AP-1** — `max(1,$days)` clamping in an adapter | presented as Implementation | ✅ overrides *"Q-2 decides durations"* (**two levels up**) | ❌ **REJECT** — an implementation edit reaching level 1 | ✅ *(retrospective)* |
| Renaming an adapter's config keys | Implementation | ❌ | ✅ ACCEPT | ✅ |

**The significant fact:** **AP-1 and AP-2 passed every automated gate** — Architecture suite, Deptrac, PHPStan, the widened regression suite — and were caught only by a human preservation review. **The rule flags both.** It reaches a defect class the executable gates demonstrably do not.

### Limits — recorded, not minimised

- **One work package.** Thinner than the DDD module's evidence base at *its* promotion, which was itself recorded as a governance exception (**R-39**).
- **Two of three validating cases are retrospective.** Retrospective fit is weaker evidence than prospective prediction: the defects were known when the rule was written. **Only G-1 was prospective.**
- **Level assignment is a judgement, not a computation.** The rule presupposes the four levels are correctly assigned; it detects *crossing* them, not *mislabelling* them. **A change misfiled at the wrong level will pass.**
- **Unenforceable by tooling.** This is a review heuristic. It belongs in review, not in CI.

### Expected validation before the exception closes

**Two prospective applications outside EPIC-004**, at least one of which **rejects or escalates** a proposal that would otherwise have proceeded. **If, after that, the rule has never escalated anything, it is ceremonial by the platform's own fitness rule and should be retired** rather than retained out of attachment.

## 4. Non-scope

**This module does not:** decide where code lives · select tactical patterns · assign bounded-context ownership · substitute for Deptrac, architecture tests or PHPStan (it reaches a *different* defect class) · grant any authority to the person applying it. **Its only output is a classification and, where warranted, an escalation.**

## 5. Adoption

**Requested of the Decision Authority.** If adopted:

- record the ruling in the platform rulings register (as **R-39** was for the DDD module), including the thin-evidence exception and the validation expectation in §3;
- index it in `engineering/governance/STANDARDS_INDEX.md`;
- consider a non-blocking review reminder, matching the precedent of `.claude/scripts/ddd-principles-reminder.sh` (AST-014).

**None of the above has been done.** Indexing and rulings are governance acts and are **not** performed by the artifact that requests them.

---

**Traceability:** EPIC-004 WP-7 — implementation guard commission (**G-1**) · architecture–enforcement alignment commission (four-level model; rule; retrospective validation) · WP-6 findings **AP-1** (fail closed / no substituted business value) and **AP-2** (one home per parameter) · `DDD_Tactical_Governance_Principles.md` §1 Methodological Fitness Rule (the standard applied to this rule in §3) and §4 Architectural Silence Principle (rejections are recorded decisions) · **R-34** (authority only by explicit issuance) · **R-39** (precedent for promotion on a thin evidence base as a recorded exception). **Non-binding until issued.**
